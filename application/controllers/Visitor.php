<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Visitor extends Admin_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $branchId = get_loggedin_branch_id();
        $date     = $this->input->get('date') ?: date('Y-m-d');

        $this->db->where('branch_id', $branchId)
                 ->where('DATE(check_in)', $date)
                 ->order_by('check_in', 'DESC');
        $visitors = $this->db->get('visitor_log')->result();

        $still_in = $this->db->where('branch_id', $branchId)
                              ->where('check_out IS NULL', null, false)
                              ->count_all_results('visitor_log');

        $this->data['visitors']  = $visitors;
        $this->data['still_in']  = $still_in;
        $this->data['date']      = $date;
        $this->data['title']     = 'Visitor / Gate Log';
        $this->data['main_menu'] = 'visitor';
        $this->data['sub_page']  = 'visitor/index';
        $this->load->view('layout/index', $this->data);
    }

    public function checkin() {
        if (!$this->input->is_ajax_request()) show_404();

        $rules = [
            ['field' => 'visitor_name',   'label' => 'Visitor Name', 'rules' => 'required|max_length[200]'],
            ['field' => 'purpose',        'label' => 'Purpose',      'rules' => 'required|max_length[300]'],
        ];
        $this->form_validation->set_rules($rules);
        if (!$this->form_validation->run()) {
            echo json_encode(['status' => 'error', 'msg' => validation_errors()]);
            return;
        }

        $branchId = get_loggedin_branch_id();
        $data = [
            'visitor_name'    => $this->input->post('visitor_name'),
            'visitor_id_no'   => $this->input->post('visitor_id_no'),
            'visitor_phone'   => $this->input->post('visitor_phone'),
            'purpose'         => $this->input->post('purpose'),
            'person_to_visit' => $this->input->post('person_to_visit'),
            'student_id'      => $this->input->post('student_id') ?: null,
            'badge_no'        => $this->input->post('badge_no'),
            'vehicle_reg'     => $this->input->post('vehicle_reg'),
            'check_in'        => date('Y-m-d H:i:s'),
            'recorded_by'     => get_loggedin_user_id(),
            'branch_id'       => $branchId,
        ];

        $this->db->insert('visitor_log', $data);
        echo json_encode(['status' => 'success', 'url' => base_url('visitor')]);
    }

    public function checkout($id) {
        if (!$this->input->is_ajax_request()) show_404();
        $branchId = get_loggedin_branch_id();

        $row = $this->db->where('id', $id)->where('branch_id', $branchId)->get('visitor_log')->row();
        if (!$row) {
            echo json_encode(['status' => 'error', 'msg' => 'Record not found']);
            return;
        }
        if ($row->check_out) {
            echo json_encode(['status' => 'error', 'msg' => 'Already checked out']);
            return;
        }

        $this->db->where('id', $id)->update('visitor_log', ['check_out' => date('Y-m-d H:i:s')]);
        echo json_encode(['status' => 'success', 'url' => base_url('visitor')]);
    }

    public function delete($id) {
        if (!is_admin_loggedin() && !is_superadmin_loggedin()) show_404();
        $branchId = get_loggedin_branch_id();
        $this->db->where('id', $id)->where('branch_id', $branchId)->delete('visitor_log');
        echo json_encode(['status' => 'success', 'url' => base_url('visitor')]);
    }

    public function report() {
        $branchId  = get_loggedin_branch_id();
        $dateFrom  = $this->input->get('date_from') ?: date('Y-m-01');
        $dateTo    = $this->input->get('date_to')   ?: date('Y-m-d');

        $this->db->where('branch_id', $branchId)
                 ->where('DATE(check_in) >=', $dateFrom)
                 ->where('DATE(check_in) <=', $dateTo)
                 ->order_by('check_in', 'DESC');
        $visitors = $this->db->get('visitor_log')->result();

        $this->data['visitors']  = $visitors;
        $this->data['date_from'] = $dateFrom;
        $this->data['date_to']   = $dateTo;
        $this->data['title']     = 'Visitor Report';
        $this->data['main_menu'] = 'visitor';
        $this->data['sub_page']  = 'visitor/report';
        $this->load->view('layout/index', $this->data);
    }
}
