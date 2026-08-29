<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('contact_model');
        $this->load->model('email_model');
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
                $this->_notify_superadmin(
                    $this->input->post('full_name',   TRUE),
                    $this->input->post('school_name', TRUE),
                    $this->input->post('phone',       TRUE),
                    $this->input->post('email',       TRUE),
                    $this->input->post('plan',        TRUE),
                    $this->input->post('message',     TRUE)
                );
                $success = true;
            }
        }

        $this->data['plan']    = $plan;
        $this->data['success'] = $success;
        $this->load->view('contact/index', $this->data);
    }

    private function _notify_superadmin($name, $school, $phone, $email, $plan, $message) {
        $to = !empty($this->data['global_config']['company_email'])
            ? $this->data['global_config']['company_email']
            : 'info@cstschoolhub.co.ke';

        $plan_line = $plan ? $plan : 'Not specified';
        $body  = "<h3>New Demo Request — CST SchoolHub</h3>";
        $body .= "<table cellpadding='6' style='font-family:sans-serif;font-size:14px;border-collapse:collapse'>";
        $body .= "<tr><td><strong>Name</strong></td><td>" . htmlspecialchars($name)   . "</td></tr>";
        $body .= "<tr><td><strong>School</strong></td><td>" . htmlspecialchars($school) . "</td></tr>";
        $body .= "<tr><td><strong>Phone</strong></td><td>" . htmlspecialchars($phone)  . "</td></tr>";
        $body .= "<tr><td><strong>Email</strong></td><td>" . htmlspecialchars($email)  . "</td></tr>";
        $body .= "<tr><td><strong>Plan</strong></td><td>" . htmlspecialchars($plan_line) . "</td></tr>";
        if ($message) {
            $body .= "<tr><td><strong>Message</strong></td><td>" . nl2br(htmlspecialchars($message)) . "</td></tr>";
        }
        $body .= "</table>";

        try {
            $this->email_model->sendEmail([
                'recipient' => $to,
                'subject'   => 'New Demo Request: ' . $school,
                'message'   => $body,
                'branch_id' => null,
            ]);
        } catch (Exception $e) {
            // silent — submission is already saved to DB
        }
    }
}
