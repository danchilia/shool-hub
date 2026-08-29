<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('contact_model');
        $this->load->library('form_validation');
    }

    public function index() {
        $plan = $this->input->get('plan') ?: '';
        $success = false;

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('full_name',   'Full Name',   'trim|required|max_length[150]');
            $this->form_validation->set_rules('school_name', 'School Name', 'trim|required|max_length[200]');
            $this->form_validation->set_rules('phone',       'Phone',       'trim|required|max_length[20]');
            $this->form_validation->set_rules('email',       'Email',       'trim|valid_email|max_length[150]');

            if ($this->form_validation->run()) {
                $this->contact_model->save_request([
                    'full_name'   => $this->input->post('full_name',   TRUE),
                    'school_name' => $this->input->post('school_name', TRUE),
                    'phone'       => $this->input->post('phone',       TRUE),
                    'email'       => $this->input->post('email',       TRUE),
                    'plan'        => $this->input->post('plan',        TRUE),
                    'message'     => $this->input->post('message',     TRUE),
                    'is_read'     => 0,
                    'created_at'  => date('Y-m-d H:i:s'),
                ]);
                $success = true;
            }
        }

        $this->data['plan']    = $plan;
        $this->data['success'] = $success;
        $this->load->view('contact/index', $this->data);
    }
}
