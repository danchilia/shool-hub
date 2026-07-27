<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VirtualClass extends Admin_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $branchId = get_loggedin_branch_id();
        $classes  = $this->db
            ->select('vc.*, c.name AS class_name, s.name AS subject_name, st.name AS teacher_name')
            ->from('virtual_classes vc')
            ->join('class c','c.id=vc.class_id','left')
            ->join('subject s','s.id=vc.subject_id','left')
            ->join('staff st','st.id=vc.teacher_id','left')
            ->where('vc.branch_id',$branchId)
            ->order_by('vc.scheduled_at','DESC')
            ->get()->result();

        $this->data['virtual_classes'] = $classes;
        $this->data['classes']         = $this->db->where('branch_id',$branchId)->get('class')->result();
        $this->data['subjects']        = $this->db->where('branch_id',$branchId)->get('subject')->result();
        $this->data['teachers']        = $this->db->where('branch_id',$branchId)->get('staff')->result();
        $this->data['title']           = 'Virtual Classroom';
        $this->data['main_menu']       = 'virtual_class';
        $this->data['sub_page']        = 'virtual_class/index';
        $this->load->view('layout/index', $this->data);
    }

    public function save() {
        if (!$this->input->is_ajax_request()) show_404();
        $branchId = get_loggedin_branch_id();

        $rules = [
            ['field'=>'title',       'label'=>'Title',    'rules'=>'required|max_length[300]'],
            ['field'=>'meeting_link','label'=>'Link',     'rules'=>'required|max_length[500]'],
            ['field'=>'scheduled_at','label'=>'Date/Time','rules'=>'required'],
        ];
        $this->form_validation->set_rules($rules);
        if (!$this->form_validation->run()) {
            echo json_encode(['status'=>'error','msg'=>validation_errors()]); return;
        }

        $link = $this->input->post('meeting_link');
        if (!preg_match('/^https?:\/\//i', $link)) {
            echo json_encode(['status'=>'error','msg'=>'Meeting link must start with http:// or https://']); return;
        }

        $data = [
            'title'          => $this->input->post('title'),
            'description'    => $this->input->post('description'),
            'platform'       => $this->input->post('platform') ?: 'meet',
            'meeting_link'   => $link,
            'meeting_id'     => $this->input->post('meeting_id'),
            'password'       => $this->input->post('password'),
            'class_id'       => $this->input->post('class_id') ?: null,
            'section_id'     => $this->input->post('section_id') ?: null,
            'subject_id'     => $this->input->post('subject_id') ?: null,
            'teacher_id'     => $this->input->post('teacher_id') ?: get_loggedin_user_id(),
            'scheduled_at'   => $this->input->post('scheduled_at'),
            'duration_mins'  => $this->input->post('duration_mins') ?: 45,
            'recording_link' => $this->input->post('recording_link'),
            'branch_id'      => $branchId,
        ];

        $id = $this->input->post('id');
        if ($id) {
            $this->db->where('id',$id)->where('branch_id',$branchId)->update('virtual_classes',$data);
        } else {
            $data['status'] = 'upcoming';
            $this->db->insert('virtual_classes',$data);
        }
        echo json_encode(['status'=>'success','url'=>base_url('virtual_class')]);
    }

    public function update_status($id) {
        if (!$this->input->is_ajax_request()) show_404();
        $status = $this->input->post('status');
        $branchId = get_loggedin_branch_id();

        $allowed = ['upcoming','ongoing','completed','cancelled'];
        if (!in_array($status, $allowed)) {
            echo json_encode(['status'=>'error','msg'=>'Invalid status']); return;
        }
        $update = ['status' => $status];
        if ($this->input->post('recording_link')) {
            $update['recording_link'] = $this->input->post('recording_link');
        }
        $this->db->where('id',$id)->where('branch_id',$branchId)->update('virtual_classes', $update);
        echo json_encode(['status'=>'success','url'=>base_url('virtual_class')]);
    }

    public function delete($id) {
        $branchId = get_loggedin_branch_id();
        $this->db->where('id',$id)->where('branch_id',$branchId)->delete('virtual_classes');
        echo json_encode(['status'=>'success','url'=>base_url('virtual_class')]);
    }
}
