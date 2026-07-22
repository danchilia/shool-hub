<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Knec extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $branchId  = get_loggedin_branch_id();
        $examType  = $this->input->get('exam_type') ?: 'KCPE';
        $examYear  = $this->input->get('exam_year')  ?: date('Y');

        $candidates = $this->db->select('kc.*, CONCAT(s.first_name," ",s.last_name) AS student_name,
            s.register_no, s.upi_number, c.name AS class, se.name AS section_name, ce.centre_code, ce.centre_name')
            ->from('knec_candidates kc')
            ->join('student s', 's.id = kc.student_id', 'left')
            ->join('enroll e', 'e.student_id = s.id', 'left')
            ->join('class c', 'c.id = e.class_id', 'left')
            ->join('section se', 'se.id = e.section_id', 'left')
            ->join('knec_centres ce', 'ce.id = kc.centre_id', 'left')
            ->where('kc.branch_id', $branchId)
            ->where('kc.exam_type', $examType)
            ->where('kc.exam_year', $examYear)
            ->order_by('kc.index_number')
            ->get()->result_array();

        $centres  = $this->db->where(array('branch_id' => $branchId, 'exam_type' => $examType))->get('knec_centres')->result_array();
        $students = $this->db->select('s.id AS student_id, s.first_name, s.last_name, s.register_no, s.upi_number')
            ->from('student s')
            ->join('enroll e', 'e.student_id = s.id AND e.branch_id = '.(int)$branchId)
            ->group_by('s.id')
            ->order_by('s.first_name')
            ->get()->result_array();

        $this->data['candidates'] = $candidates;
        $this->data['centres']    = $centres;
        $this->data['students']   = $students;
        $this->data['examType']   = $examType;
        $this->data['examYear']   = $examYear;
        $this->data['sub_page']   = 'knec/index';
        $this->data['main_menu']  = 'knec';
        $this->data['page_title'] = 'KNEC Exam Index Numbers';
        $this->load->view('layout/index', $this->data);
    }

    public function save_centre()
    {
        $this->form_validation->set_rules('centre_code', 'Centre Code', 'trim|required');
        $this->form_validation->set_rules('centre_name', 'Centre Name', 'trim|required');
        $this->form_validation->set_rules('exam_type',   'Exam Type',   'trim|required');
        if ($this->form_validation->run() !== false) {
            $branchId = get_loggedin_branch_id();
            $id = (int) $this->input->post('id');
            $data = array(
                'centre_code' => $this->input->post('centre_code'),
                'centre_name' => $this->input->post('centre_name'),
                'exam_type'   => $this->input->post('exam_type'),
                'branch_id'   => $branchId,
            );
            if ($id) {
                $this->db->where(array('id' => $id, 'branch_id' => $branchId))->update('knec_centres', $data);
            } else {
                $this->db->insert('knec_centres', $data);
            }
            echo json_encode(array('status' => 'success', 'message' => 'Centre saved.'));
        } else {
            echo json_encode(array('status' => 'fail', 'error' => $this->form_validation->error_array()));
        }
    }

    public function save_candidate()
    {
        $this->form_validation->set_rules('student_id',    'Student',       'trim|required');
        $this->form_validation->set_rules('centre_id',     'Centre',        'trim|required');
        $this->form_validation->set_rules('index_number',  'Index Number',  'trim|required');
        $this->form_validation->set_rules('exam_year',     'Exam Year',     'trim|required');
        $this->form_validation->set_rules('exam_type',     'Exam Type',     'trim|required');
        if ($this->form_validation->run() !== false) {
            $branchId = get_loggedin_branch_id();
            $id = (int) $this->input->post('id');
            $data = array(
                'student_id'          => (int) $this->input->post('student_id'),
                'centre_id'           => (int) $this->input->post('centre_id'),
                'index_number'        => $this->input->post('index_number'),
                'exam_year'           => $this->input->post('exam_year'),
                'exam_type'           => $this->input->post('exam_type'),
                'registration_status' => $this->input->post('registration_status'),
                'branch_id'           => $branchId,
            );
            if ($id) {
                $this->db->where(array('id' => $id, 'branch_id' => $branchId))->update('knec_candidates', $data);
            } else {
                $this->db->insert('knec_candidates', $data);
            }
            echo json_encode(array('status' => 'success', 'message' => 'Candidate saved.'));
        } else {
            echo json_encode(array('status' => 'fail', 'error' => $this->form_validation->error_array()));
        }
    }

    public function delete_candidate($id)
    {
        $this->db->where(array('id' => $id, 'branch_id' => get_loggedin_branch_id()))->delete('knec_candidates');
        echo json_encode(array('status' => 'success', 'message' => 'Candidate removed.'));
    }

    /** Download candidate list as CSV for KNEC portal */
    public function export_csv()
    {
        $branchId = get_loggedin_branch_id();
        $examType = $this->input->get('exam_type') ?: 'KCPE';
        $examYear = $this->input->get('exam_year')  ?: date('Y');

        $rows = $this->db->select('kc.index_number, s.first_name, s.last_name, s.upi_number, s.gender, s.birthday,
            ce.centre_code, ce.centre_name, c.name AS class, se.name AS section_name, s.register_no')
            ->from('knec_candidates kc')
            ->join('student s', 's.id = kc.student_id', 'left')
            ->join('enroll e', 'e.student_id = s.id', 'left')
            ->join('class c', 'c.id = e.class_id', 'left')
            ->join('section se', 'se.id = e.section_id', 'left')
            ->join('knec_centres ce', 'ce.id = kc.centre_id', 'left')
            ->where('kc.branch_id', $branchId)
            ->where('kc.exam_type', $examType)
            ->where('kc.exam_year', $examYear)
            ->order_by('kc.index_number')
            ->get()->result_array();

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="KNEC_' . $examType . '_' . $examYear . '.csv"');
        echo "\xEF\xBB\xBF"; // UTF-8 BOM
        $out = fopen('php://output', 'w');
        fputcsv($out, array('INDEX_NUMBER','SURNAME','OTHER_NAMES','UPI_NUMBER','GENDER','DATE_OF_BIRTH','CENTRE_CODE','CENTRE_NAME','CLASS','STREAM','ADM_NUMBER'));
        foreach ($rows as $r) {
            fputcsv($out, array(
                $r['index_number'],
                strtoupper($r['last_name']),
                strtoupper($r['first_name']),
                $r['upi_number'] ?? '',
                strtoupper($r['gender'] ?? ''),
                $r['birthday'] ?? '',
                $r['centre_code'] ?? '',
                $r['centre_name'] ?? '',
                $r['class'] ?? '',
                $r['section_name'] ?? '',
                $r['register_no'] ?? '',
            ));
        }
        fclose($out);
        exit;
    }
}
