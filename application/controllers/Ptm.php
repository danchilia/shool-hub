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
        $sessions = $this->db->where('branch_id', $branchId)->order_by('session_date', 'DESC')->get('ptm_sessions')->result_array();
        foreach ($sessions as &$s) {
            $s['bookings'] = $this->db->where('ptm_session_id', $s['id'])->count_all_results('ptm_bookings');
        }
        $this->data['sessions']   = $sessions;
        $this->data['sub_page']   = 'ptm/index';
        $this->data['main_menu']  = 'ptm';
        $this->data['page_title'] = 'Parent-Teacher Meetings';
        $this->load->view('layout/index', $this->data);
    }

    public function save_session()
    {
        $this->form_validation->set_rules('title',              'Title',          'trim|required');
        $this->form_validation->set_rules('session_date',       'Date',           'trim|required');
        $this->form_validation->set_rules('start_time',         'Start Time',     'trim|required');
        $this->form_validation->set_rules('end_time',           'End Time',       'trim|required');
        $this->form_validation->set_rules('slot_duration_mins', 'Slot Duration',  'trim|required|is_natural_no_zero');
        if ($this->form_validation->run() !== false) {
            $branchId = get_loggedin_branch_id();
            $id       = (int) $this->input->post('id');
            $data = array(
                'title'              => $this->input->post('title'),
                'session_date'       => $this->input->post('session_date'),
                'start_time'         => $this->input->post('start_time'),
                'end_time'           => $this->input->post('end_time'),
                'venue'              => $this->input->post('venue'),
                'slot_duration_mins' => (int) $this->input->post('slot_duration_mins'),
                'notes'              => $this->input->post('notes'),
                'branch_id'          => $branchId,
            );
            if ($id) {
                $this->db->where(array('id' => $id, 'branch_id' => $branchId))->update('ptm_sessions', $data);
            } else {
                $this->db->insert('ptm_sessions', $data);
            }
            echo json_encode(array('status' => 'success', 'message' => 'PTM session saved.'));
        } else {
            echo json_encode(array('status' => 'fail', 'error' => $this->form_validation->error_array()));
        }
    }

    public function delete_session($id)
    {
        $branchId = get_loggedin_branch_id();
        $this->db->where(array('ptm_session_id' => $id, 'branch_id' => $branchId))->delete('ptm_bookings');
        $this->db->where(array('id' => $id, 'branch_id' => $branchId))->delete('ptm_sessions');
        echo json_encode(array('status' => 'success', 'message' => 'Session deleted.'));
    }

    public function bookings($sessionId = '')
    {
        $branchId = get_loggedin_branch_id();
        $session  = $this->db->get_where('ptm_sessions', array('id' => $sessionId, 'branch_id' => $branchId))->row_array();
        if (!$session) {
            show_404();
        }

        $bookings = $this->db->select('pb.*, CONCAT(s.first_name," ",s.last_name) AS student_name, s.register_no,
            c.name AS class, se.name AS section_name, st.name AS teacher_name,
            p.name AS parent_name, p.mobileno AS parent_phone')
            ->from('ptm_bookings pb')
            ->join('student s', 's.id = pb.student_id', 'left')
            ->join('enroll en', 'en.student_id = s.id', 'left')
            ->join('class c', 'c.id = en.class_id', 'left')
            ->join('section se', 'se.id = en.section_id', 'left')
            ->join('staff st', 'st.id = pb.teacher_id', 'left')
            ->join('parent p', 'p.id = pb.parent_id', 'left')
            ->where('pb.ptm_session_id', $sessionId)
            ->order_by('pb.slot_time')
            ->get()->result_array();

        $students = $this->db->select('s.id AS student_id, s.first_name, s.last_name, s.register_no, s.parent_id')
            ->from('student s')
            ->join('enroll e', 'e.student_id = s.id AND e.branch_id = '.(int)$branchId)
            ->group_by('s.id')
            ->order_by('s.first_name')
            ->get()->result_array();

        $teachers = $this->db->select('id, name')
            ->where('branch_id', $branchId)->order_by('name')->get('staff')->result_array();

        // Generate available slots
        $slots = array();
        $cur = strtotime($session['session_date'] . ' ' . $session['start_time']);
        $end = strtotime($session['session_date'] . ' ' . $session['end_time']);
        $dur = (int) $session['slot_duration_mins'] * 60;
        while ($cur < $end) {
            $slots[] = date('H:i', $cur);
            $cur += $dur;
        }

        $this->data['session']   = $session;
        $this->data['bookings']  = $bookings;
        $this->data['students']  = $students;
        $this->data['teachers']  = $teachers;
        $this->data['slots']     = $slots;
        $this->data['sub_page']  = 'ptm/bookings';
        $this->data['main_menu'] = 'ptm';
        $this->data['page_title']= 'PTM Bookings — ' . $session['title'];
        $this->load->view('layout/index', $this->data);
    }

    public function save_booking()
    {
        $this->form_validation->set_rules('ptm_session_id', 'Session',   'trim|required');
        $this->form_validation->set_rules('student_id',     'Student',   'trim|required');
        $this->form_validation->set_rules('slot_time',      'Slot Time', 'trim|required');
        if ($this->form_validation->run() !== false) {
            $branchId  = get_loggedin_branch_id();
            $sessionId = (int) $this->input->post('ptm_session_id');
            $teacherId = (int) $this->input->post('teacher_id') ?: null;
            $slotTime  = $this->input->post('slot_time');

            // Check slot not already taken
            $conflict = $this->db->where(array('ptm_session_id' => $sessionId, 'teacher_id' => $teacherId, 'slot_time' => $slotTime))->count_all_results('ptm_bookings');
            if ($conflict > 0) {
                echo json_encode(array('status' => 'fail', 'error' => array('slot_time' => 'This slot is already booked for that teacher.')));
                return;
            }

            $student = $this->db->select('parent_id')->where('id', (int) $this->input->post('student_id'))->get('student')->row_array();

            $this->db->insert('ptm_bookings', array(
                'ptm_session_id' => $sessionId,
                'student_id'     => (int) $this->input->post('student_id'),
                'parent_id'      => $student['parent_id'] ?? null,
                'teacher_id'     => $teacherId,
                'slot_time'      => $slotTime,
                'status'         => 'booked',
                'notes'          => $this->input->post('notes'),
                'branch_id'      => $branchId,
            ));
            echo json_encode(array('status' => 'success', 'message' => 'Booking created.'));
        } else {
            echo json_encode(array('status' => 'fail', 'error' => $this->form_validation->error_array()));
        }
    }

    public function update_status($id)
    {
        $status = $this->input->post('status');
        $this->db->where(array('id' => $id, 'branch_id' => get_loggedin_branch_id()))->update('ptm_bookings', array('status' => $status));
        echo json_encode(array('status' => 'success', 'message' => 'Status updated.'));
    }

    public function delete_booking($id)
    {
        $this->db->where(array('id' => $id, 'branch_id' => get_loggedin_branch_id()))->delete('ptm_bookings');
        echo json_encode(array('status' => 'success', 'message' => 'Booking deleted.'));
    }
}
