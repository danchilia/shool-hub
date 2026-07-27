<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ptm extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $branchId = get_loggedin_branch_id();
        $sessions = $this->db->where('branch_id', $branchId)
                             ->order_by('session_date', 'DESC')
                             ->get('ptm_sessions')->result_array();

        // Pre-aggregate booking counts to avoid N+1
        $sessionIds    = array_column($sessions, 'id');
        $bookingCounts = [];
        if (!empty($sessionIds)) {
            $rows = $this->db->select('ptm_session_id, COUNT(*) AS cnt')
                             ->where_in('ptm_session_id', $sessionIds)
                             ->group_by('ptm_session_id')
                             ->get('ptm_bookings')->result_array();
            foreach ($rows as $r) {
                $bookingCounts[$r['ptm_session_id']] = (int)$r['cnt'];
            }
        }
        foreach ($sessions as &$s) {
            $s['bookings'] = $bookingCounts[$s['id']] ?? 0;
        }
        unset($s);

        $this->data['sessions']  = $sessions;
        $this->data['title']     = 'Parent-Teacher Meetings';
        $this->data['sub_page']  = 'ptm/index';
        $this->data['main_menu'] = 'ptm';
        $this->load->view('layout/index', $this->data);
    }

    public function save_session()
    {
        if (!$this->input->is_ajax_request()) show_404();

        $this->form_validation->set_rules('title',              'Title',         'trim|required');
        $this->form_validation->set_rules('session_date',       'Date',          'trim|required');
        $this->form_validation->set_rules('start_time',         'Start Time',    'trim|required');
        $this->form_validation->set_rules('end_time',           'End Time',      'trim|required');
        $this->form_validation->set_rules('slot_duration_mins', 'Slot Duration', 'trim|required|is_natural_no_zero');

        if ($this->form_validation->run() !== false) {
            $branchId = get_loggedin_branch_id();
            $id       = (int)$this->input->post('id');
            $data = [
                'title'              => $this->input->post('title'),
                'session_date'       => $this->input->post('session_date'),
                'start_time'         => $this->input->post('start_time'),
                'end_time'           => $this->input->post('end_time'),
                'venue'              => $this->input->post('venue'),
                'slot_duration_mins' => (int)$this->input->post('slot_duration_mins'),
                'notes'              => $this->input->post('notes'),
                'branch_id'          => $branchId,
            ];
            if ($id) {
                $this->db->where(['id' => $id, 'branch_id' => $branchId])->update('ptm_sessions', $data);
            } else {
                $this->db->insert('ptm_sessions', $data);
            }
            echo json_encode(['status' => 'success', 'url' => base_url('ptm')]);
        } else {
            echo json_encode(['status' => 'error', 'msg' => validation_errors()]);
        }
    }

    public function delete_session($id)
    {
        if (!$this->input->is_ajax_request()) show_404();
        $branchId = get_loggedin_branch_id();
        $this->db->where(['ptm_session_id' => $id, 'branch_id' => $branchId])->delete('ptm_bookings');
        $this->db->where(['id' => $id, 'branch_id' => $branchId])->delete('ptm_sessions');
        echo json_encode(['status' => 'success', 'url' => base_url('ptm')]);
    }

    public function bookings($sessionId = '')
    {
        $branchId = get_loggedin_branch_id();
        $session  = $this->db->get_where('ptm_sessions', ['id' => $sessionId, 'branch_id' => $branchId])->row_array();
        if (!$session) show_404();

        $bookings = $this->db
            ->select('pb.*, CONCAT(s.first_name," ",s.last_name) AS student_name, s.register_no,
                c.name AS class, se.name AS section_name, st.name AS teacher_name,
                p.name AS parent_name, p.mobileno AS parent_phone')
            ->from('ptm_bookings pb')
            ->join('student s',  's.id = pb.student_id',  'left')
            ->join('enroll en',  'en.student_id = s.id',  'left')
            ->join('class c',    'c.id = en.class_id',    'left')
            ->join('section se', 'se.id = en.section_id', 'left')
            ->join('staff st',   'st.id = pb.teacher_id', 'left')
            ->join('parent p',   'p.id = pb.parent_id',   'left')
            ->where('pb.ptm_session_id', $sessionId)
            ->order_by('pb.slot_time')
            ->get()->result_array();

        $students = $this->db
            ->select('s.id AS student_id, s.first_name, s.last_name, s.register_no')
            ->from('student s')
            ->join('enroll e', 'e.student_id = s.id AND e.branch_id = ' . (int)$branchId)
            ->group_by('s.id')
            ->order_by('s.first_name')
            ->get()->result_array();

        $teachers = $this->db->select('id, name')
            ->where('branch_id', $branchId)->order_by('name')->get('staff')->result_array();

        // Generate available time slots from session window
        $slots = [];
        $cur   = strtotime($session['session_date'] . ' ' . $session['start_time']);
        $end   = strtotime($session['session_date'] . ' ' . $session['end_time']);
        $dur   = (int)$session['slot_duration_mins'] * 60;
        while ($cur < $end) {
            $slots[] = date('H:i', $cur);
            $cur += $dur;
        }

        $this->data['session']   = $session;
        $this->data['bookings']  = $bookings;
        $this->data['students']  = $students;
        $this->data['teachers']  = $teachers;
        $this->data['slots']     = $slots;
        $this->data['title']     = 'PTM Bookings — ' . $session['title'];
        $this->data['sub_page']  = 'ptm/bookings';
        $this->data['main_menu'] = 'ptm';
        $this->load->view('layout/index', $this->data);
    }

    public function save_booking()
    {
        if (!$this->input->is_ajax_request()) show_404();

        $this->form_validation->set_rules('ptm_session_id', 'Session',   'trim|required');
        $this->form_validation->set_rules('student_id',     'Student',   'trim|required');
        $this->form_validation->set_rules('slot_time',      'Slot Time', 'trim|required');

        if ($this->form_validation->run() !== false) {
            $branchId  = get_loggedin_branch_id();
            $sessionId = (int)$this->input->post('ptm_session_id');
            $teacherId = (int)$this->input->post('teacher_id') ?: null;
            $slotTime  = $this->input->post('slot_time');

            // Slot conflict: same session + same teacher + same slot within this branch
            $conflict = $this->db->where([
                'ptm_session_id' => $sessionId,
                'teacher_id'     => $teacherId,
                'slot_time'      => $slotTime,
                'branch_id'      => $branchId,
            ])->count_all_results('ptm_bookings');

            if ($conflict > 0) {
                echo json_encode(['status' => 'error', 'msg' => 'This slot is already booked for that teacher.']);
                return;
            }

            $student = $this->db->select('parent_id')
                                ->where('id', (int)$this->input->post('student_id'))
                                ->get('student')->row_array();

            $this->db->insert('ptm_bookings', [
                'ptm_session_id' => $sessionId,
                'student_id'     => (int)$this->input->post('student_id'),
                'parent_id'      => $student['parent_id'] ?? null,
                'teacher_id'     => $teacherId,
                'slot_time'      => $slotTime,
                'status'         => 'booked',
                'notes'          => $this->input->post('notes'),
                'branch_id'      => $branchId,
            ]);
            echo json_encode(['status' => 'success', 'url' => base_url('ptm/bookings/' . $sessionId)]);
        } else {
            echo json_encode(['status' => 'error', 'msg' => validation_errors()]);
        }
    }

    public function update_status($id)
    {
        if (!$this->input->is_ajax_request()) show_404();
        $allowed = ['booked', 'attended', 'missed', 'cancelled'];
        $status  = $this->input->post('status');
        if (!in_array($status, $allowed, true)) {
            echo json_encode(['status' => 'error', 'msg' => 'Invalid status value']);
            return;
        }
        $this->db->where(['id' => $id, 'branch_id' => get_loggedin_branch_id()])
                 ->update('ptm_bookings', ['status' => $status]);
        echo json_encode(['status' => 'success']);
    }

    public function delete_booking($id)
    {
        if (!$this->input->is_ajax_request()) show_404();
        $branchId  = get_loggedin_branch_id();
        $booking   = $this->db->select('ptm_session_id')
                              ->where(['id' => $id, 'branch_id' => $branchId])
                              ->get('ptm_bookings')->row_array();
        $sessionId = $booking['ptm_session_id'] ?? 0;
        $this->db->where(['id' => $id, 'branch_id' => $branchId])->delete('ptm_bookings');
        echo json_encode(['status' => 'success', 'url' => base_url('ptm/bookings/' . $sessionId)]);
    }
}
