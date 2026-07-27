<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alumni extends Admin_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $branchId = get_loggedin_branch_id();
        $year     = $this->input->get('year');

        $this->db->where('branch_id', $branchId);
        if ($year) $this->db->where('graduation_year', $year);
        $alumni = $this->db->order_by('graduation_year', 'DESC')->order_by('full_name', 'ASC')->get('alumni')->result();

        $years = $this->db->query(
            "SELECT DISTINCT graduation_year FROM alumni WHERE branch_id = ? ORDER BY graduation_year DESC",
            [$branchId]
        )->result();

        $this->data['alumni']      = $alumni;
        $this->data['years']       = $years;
        $this->data['filter_year'] = $year;
        $this->data['classes']     = $this->db->where('branch_id', $branchId)->order_by('name')->get('class')->result();
        $this->data['title']       = 'Alumni Management';
        $this->data['main_menu']   = 'alumni';
        $this->data['sub_page']    = 'alumni/index';
        $this->load->view('layout/index', $this->data);
    }

    public function save() {
        if (!$this->input->is_ajax_request()) show_404();
        $branchId = get_loggedin_branch_id();

        $this->form_validation->set_rules('full_name','Full Name','required|max_length[300]');
        if (!$this->form_validation->run()) {
            echo json_encode(['status'=>'error','msg'=>validation_errors()]); return;
        }

        $data = [
            'full_name'        => $this->input->post('full_name'),
            'admission_no'     => $this->input->post('admission_no'),
            'graduation_year'  => $this->input->post('graduation_year') ?: null,
            'class_completed'  => $this->input->post('class_completed'),
            'email'            => $this->input->post('email'),
            'phone'            => $this->input->post('phone'),
            'current_location' => $this->input->post('current_location'),
            'occupation'       => $this->input->post('occupation'),
            'employer'         => $this->input->post('employer'),
            'university_joined'=> $this->input->post('university_joined'),
            'course_studied'   => $this->input->post('course_studied'),
            'achievements'     => $this->input->post('achievements'),
            'is_verified'      => $this->input->post('is_verified') ? 1 : 0,
            'branch_id'        => $branchId,
        ];

        $id = $this->input->post('id');
        $id ? $this->db->where('id',$id)->where('branch_id',$branchId)->update('alumni',$data)
            : $this->db->insert('alumni',$data);
        echo json_encode(['status'=>'success','url'=>base_url('alumni')]);
    }

    public function delete($id) {
        $branchId = get_loggedin_branch_id();
        $this->db->where('id',$id)->where('branch_id',$branchId)->delete('alumni');
        echo json_encode(['status'=>'success','url'=>base_url('alumni')]);
    }

    public function import_from_students() {
        if (!$this->input->is_ajax_request()) show_404();
        $branchId = get_loggedin_branch_id();
        $year     = $this->input->post('graduation_year') ?: date('Y');
        $classId  = $this->input->post('class_id');

        if (!$classId) { echo json_encode(['status'=>'error','msg'=>'Select a class']); return; }

        // Get students enrolled in this class/branch
        $students = $this->db
            ->select('s.id, s.first_name, s.last_name, s.register_no, s.mobileno, s.email')
            ->from('student s')
            ->join('enroll e', 'e.student_id = s.id')
            ->where('e.branch_id', $branchId)
            ->where('e.class_id', $classId)
            ->group_by('s.id')
            ->get()->result();

        // Single batch query instead of N+1 existence checks
        $regNos = array_filter(array_column(array_map('get_object_vars', $students), 'register_no'));
        $existingNos = [];
        if (!empty($regNos)) {
            $rows = $this->db->select('admission_no')->where('branch_id', $branchId)
                             ->where_in('admission_no', $regNos)->get('alumni')->result();
            $existingNos = array_column(array_map('get_object_vars', $rows), 'admission_no');
        }

        $imported = 0;
        foreach ($students as $s) {
            if (in_array($s->register_no, $existingNos)) continue;

            $this->db->insert('alumni', [
                'student_id'      => $s->id,
                'full_name'       => $s->first_name . ' ' . $s->last_name,
                'admission_no'    => $s->register_no,
                'graduation_year' => $year,
                'phone'           => $s->mobileno,
                'email'           => $s->email,
                'is_verified'     => 0,
                'branch_id'       => $branchId,
            ]);
            $imported++;
        }
        echo json_encode(['status'=>'success','msg'=>$imported.' alumni imported.','url'=>base_url('alumni')]);
    }
}
