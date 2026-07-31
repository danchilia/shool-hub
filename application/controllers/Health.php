<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Health extends Admin_Controller
{
    // Fields in student_health that contain special-category health data (Kenya DPA 2019)
    private $_enc_health_fields = array(
        'blood_group', 'vision_left', 'vision_right', 'hearing',
        'allergies', 'chronic_conditions', 'disabilities',
        'nhif_number', 'insurance_provider',
        'emergency_contact_name', 'emergency_contact_phone',
        'doctor_name', 'doctor_phone', 'notes',
    );

    // Fields in clinic_visits that contain sensitive clinical data
    private $_enc_visit_fields = array('complaint', 'diagnosis', 'treatment', 'medication', 'referral_facility');

    public function __construct()
    {
        parent::__construct();
        $this->load->library('encryption');
    }

    private function _he($value)
    {
        if (empty($value)) return $value;
        $enc = $this->encryption->encrypt($value);
        return $enc !== false ? $enc : $value;
    }

    private function _hd($value)
    {
        if (empty($value)) return $value;
        $dec = $this->encryption->decrypt($value);
        return $dec !== false ? $dec : $value; // fallback for existing plaintext rows
    }

    private function _enc_row(array $row, array $fields)
    {
        foreach ($fields as $f) {
            if (isset($row[$f])) $row[$f] = $this->_he($row[$f]);
        }
        return $row;
    }

    private function _dec_row(array $row, array $fields)
    {
        foreach ($fields as $f) {
            if (isset($row[$f])) $row[$f] = $this->_hd($row[$f]);
        }
        return $row;
    }

    /** Health record for a specific student */
    public function student($studentId = '')
    {
        $branchId = get_loggedin_branch_id();
        $student  = $this->db->select('s.id AS student_id, s.*, c.name AS class, se.name AS section_name')
            ->from('student s')
            ->join('enroll e', 'e.student_id = s.id AND e.branch_id = ' . (int)$branchId)
            ->join('class c', 'c.id = e.class_id', 'left')
            ->join('section se', 'se.id = e.section_id', 'left')
            ->where('s.id', $studentId)
            ->get()->row_array();

        if (!$student) {
            show_404();
        }

        $health       = $this->db->get_where('student_health', array('student_id' => $studentId))->row_array();
        $vaccinations = $this->db->where('student_id', $studentId)->order_by('date_given', 'DESC')->get('student_vaccinations')->result_array();
        $visits       = $this->db->where('student_id', $studentId)->order_by('visit_date', 'DESC')->limit(10)->get('clinic_visits')->result_array();

        if ($health) {
            $health = $this->_dec_row($health, $this->_enc_health_fields);
        }
        foreach ($visits as &$v) {
            $v = $this->_dec_row($v, $this->_enc_visit_fields);
        }
        unset($v);

        $this->data['student']      = $student;
        $this->data['health']       = $health ?: array();
        $this->data['vaccinations'] = $vaccinations;
        $this->data['visits']       = $visits;
        $this->data['sub_page']     = 'health/student';
        $this->data['main_menu']    = 'health';
        $this->data['title']        = 'Health Record — ' . $student['first_name'] . ' ' . $student['last_name'];
        $this->load->view('layout/index', $this->data);
    }

    /** Save/update the main health profile */
    public function save_profile()
    {
        if (!$this->input->is_ajax_request()) show_404();
        $this->form_validation->set_rules('student_id', 'Student', 'trim|required|is_natural_no_zero');
        if ($this->form_validation->run() !== false) {
            $studentId = (int) $this->input->post('student_id');
            $branchId  = get_loggedin_branch_id();
            $data = array(
                'student_id'              => $studentId,
                'blood_group'             => $this->_he($this->input->post('blood_group')),
                'height_cm'               => $this->input->post('height_cm') ?: null,
                'weight_kg'               => $this->input->post('weight_kg') ?: null,
                'vision_left'             => $this->_he($this->input->post('vision_left')),
                'vision_right'            => $this->_he($this->input->post('vision_right')),
                'hearing'                 => $this->_he($this->input->post('hearing')),
                'allergies'               => $this->_he($this->input->post('allergies')),
                'chronic_conditions'      => $this->_he($this->input->post('chronic_conditions')),
                'disabilities'            => $this->_he($this->input->post('disabilities')),
                'emergency_contact_name'  => $this->_he($this->input->post('emergency_contact_name')),
                'emergency_contact_phone' => $this->_he($this->input->post('emergency_contact_phone')),
                'doctor_name'             => $this->_he($this->input->post('doctor_name')),
                'doctor_phone'            => $this->_he($this->input->post('doctor_phone')),
                'nhif_number'             => $this->_he($this->input->post('nhif_number')),
                'insurance_provider'      => $this->_he($this->input->post('insurance_provider')),
                'notes'                   => $this->_he($this->input->post('notes')),
                'branch_id'               => $branchId,
            );
            $exists = $this->db->where('student_id', $studentId)->count_all_results('student_health');
            if ($exists) {
                $this->db->where('student_id', $studentId)->update('student_health', $data);
            } else {
                $this->db->insert('student_health', $data);
            }
            echo json_encode(array('status' => 'success', 'url' => base_url('health/student/' . $studentId)));
        } else {
            echo json_encode(array('status' => 'error', 'msg' => validation_errors()));
        }
    }

    /** Add vaccination record */
    public function add_vaccination()
    {
        if (!$this->input->is_ajax_request()) show_404();
        $this->form_validation->set_rules('student_id',   'Student',      'trim|required');
        $this->form_validation->set_rules('vaccine_name', 'Vaccine Name', 'trim|required');
        if ($this->form_validation->run() !== false) {
            $this->db->insert('student_vaccinations', array(
                'student_id'   => (int) $this->input->post('student_id'),
                'vaccine_name' => $this->input->post('vaccine_name'),
                'dose'         => $this->input->post('dose'),
                'date_given'   => $this->input->post('date_given') ?: null,
                'next_due'     => $this->input->post('next_due') ?: null,
                'given_by'     => $this->input->post('given_by'),
                'remarks'      => $this->input->post('remarks'),
                'branch_id'    => get_loggedin_branch_id(),
            ));
            $studentId = (int) $this->input->post('student_id');
            echo json_encode(array('status' => 'success', 'url' => base_url('health/student/' . $studentId)));
        } else {
            echo json_encode(array('status' => 'error', 'msg' => validation_errors()));
        }
    }

    /** Delete vaccination record */
    public function delete_vaccination($id)
    {
        if (!$this->input->is_ajax_request()) show_404();
        $branchId = get_loggedin_branch_id();
        $row      = $this->db->select('student_id')->where(array('id' => $id, 'branch_id' => $branchId))->get('student_vaccinations')->row_array();
        $studentId = $row['student_id'] ?? 0;
        $this->db->where(array('id' => $id, 'branch_id' => $branchId))->delete('student_vaccinations');
        echo json_encode(array('status' => 'success', 'url' => base_url('health/student/' . $studentId)));
    }

    /** Add clinic visit */
    public function add_visit()
    {
        if (!$this->input->is_ajax_request()) show_404();
        $this->form_validation->set_rules('student_id', 'Student',   'trim|required');
        $this->form_validation->set_rules('visit_date', 'Date',      'trim|required');
        $this->form_validation->set_rules('complaint',  'Complaint', 'trim|required');
        if ($this->form_validation->run() !== false) {
            $this->db->insert('clinic_visits', array(
                'student_id'       => (int) $this->input->post('student_id'),
                'visit_date'       => $this->input->post('visit_date'),
                'complaint'        => $this->_he($this->input->post('complaint')),
                'diagnosis'        => $this->_he($this->input->post('diagnosis')),
                'treatment'        => $this->_he($this->input->post('treatment')),
                'medication'       => $this->_he($this->input->post('medication')),
                'referred'         => $this->input->post('referred') ? 1 : 0,
                'referral_facility'=> $this->_he($this->input->post('referral_facility')),
                'attended_by'      => $this->input->post('attended_by'),
                'follow_up_date'   => $this->input->post('follow_up_date') ?: null,
                'branch_id'        => get_loggedin_branch_id(),
            ));
            $studentId = (int) $this->input->post('student_id');
            echo json_encode(array('status' => 'success', 'url' => base_url('health/student/' . $studentId)));
        } else {
            echo json_encode(array('status' => 'error', 'msg' => validation_errors()));
        }
    }

    /** Delete clinic visit */
    public function delete_visit($id)
    {
        if (!$this->input->is_ajax_request()) show_404();
        $branchId  = get_loggedin_branch_id();
        $row       = $this->db->select('student_id')->where(array('id' => $id, 'branch_id' => $branchId))->get('clinic_visits')->row_array();
        $studentId = $row['student_id'] ?? 0;
        $this->db->where(array('id' => $id, 'branch_id' => $branchId))->delete('clinic_visits');
        echo json_encode(array('status' => 'success', 'url' => base_url('health/student/' . $studentId)));
    }

    /** Branch-wide clinic visits log */
    public function clinic_log()
    {
        $branchId = get_loggedin_branch_id();
        $from     = $this->input->get('from') ?: date('Y-m-01');
        $to       = $this->input->get('to')   ?: date('Y-m-d');

        $visits = $this->db->select('cv.*, CONCAT(s.first_name," ",s.last_name) AS student_name, s.register_no, c.name AS class, se.name AS section_name')
            ->from('clinic_visits cv')
            ->join('student s', 's.id = cv.student_id', 'left')
            ->join('enroll e', 'e.student_id = s.id', 'left')
            ->join('class c', 'c.id = e.class_id', 'left')
            ->join('section se', 'se.id = e.section_id', 'left')
            ->where('cv.branch_id', $branchId)
            ->where('cv.visit_date >=', $from)
            ->where('cv.visit_date <=', $to)
            ->order_by('cv.visit_date', 'DESC')
            ->get()->result_array();

        foreach ($visits as &$v) {
            $v = $this->_dec_row($v, $this->_enc_visit_fields);
        }
        unset($v);

        $this->data['visits']     = $visits;
        $this->data['from']       = $from;
        $this->data['to']         = $to;
        $this->data['sub_page']   = 'health/clinic_log';
        $this->data['main_menu']  = 'health';
        $this->data['title']      = 'Clinic Visits Log';
        $this->load->view('layout/index', $this->data);
    }

    /** Search student for health record access */
    public function search()
    {
        $branchId = get_loggedin_branch_id();
        $students = $this->db->select('s.id AS student_id, s.first_name, s.last_name, s.register_no, c.name AS class, se.name AS section_name,
            (SELECT COUNT(*) FROM student_health h WHERE h.student_id = s.id) AS has_record')
            ->from('student s')
            ->join('enroll e', 'e.student_id = s.id AND e.branch_id = '.(int)$branchId)
            ->join('class c', 'c.id = e.class_id', 'left')
            ->join('section se', 'se.id = e.section_id', 'left')
            ->group_by('s.id')
            ->order_by('s.first_name')
            ->get()->result_array();

        $this->data['students']   = $students;
        $this->data['sub_page']   = 'health/search';
        $this->data['main_menu']  = 'health';
        $this->data['title']      = 'Health Records';
        $this->load->view('layout/index', $this->data);
    }
}
