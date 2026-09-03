<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Careers extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['careers_model', 'email_model']);
        $this->load->library('form_validation');
        $this->load->helper(['url', 'form', 'download']);
    }

    // ── Private Helpers ───────────────────────────────────────────────────

    private function applicant_id() {
        return $this->session->userdata('career_applicant_id');
    }

    private function require_applicant() {
        if (!$this->applicant_id()) {
            $this->session->set_flashdata('error', 'Please login to continue.');
            redirect(base_url('careers/login'));
        }
    }

    private function require_superadmin() {
        if (!is_superadmin_loggedin()) {
            redirect(base_url('dashboard'));
        }
    }

    private function public_view($view, $data = []) {
        $data['applicant_id'] = $this->applicant_id();
        if ($data['applicant_id']) {
            $data['applicant'] = $this->careers_model->get_applicant($data['applicant_id']);
        }
        $this->load->view('careers/public_header', $data);
        $this->load->view('careers/' . $view, $data);
        $this->load->view('careers/public_footer');
    }

    private function send_email($to, $subject, $body) {
        $this->email_model->sendEmail([
            'recipient' => $to,
            'subject'   => $subject,
            'message'   => $body,
        ]);
    }

    private function email_tpl($heading, $body) {
        return "
        <div style='font-family:Arial,sans-serif;max-width:600px;margin:0 auto;background:#f5f5f5;padding:20px;'>
            <div style='background:#1a5276;padding:20px;text-align:center;border-radius:6px 6px 0 0;'>
                <h2 style='color:#fff;margin:0;'>CST SchoolHub</h2>
                <p style='color:#aed6f1;margin:4px 0 0;font-size:13px;'>Careers Portal</p>
            </div>
            <div style='background:#fff;padding:30px;border-radius:0 0 6px 6px;'>
                <h3 style='color:#1a5276;border-bottom:2px solid #1a5276;padding-bottom:8px;'>{$heading}</h3>
                <div style='color:#333;line-height:1.7;'>{$body}</div>
            </div>
            <p style='text-align:center;color:#999;font-size:12px;margin-top:12px;'>
                &copy; " . date('Y') . " CST. All rights reserved.
            </p>
        </div>";
    }

    // ── Public: Job Listings ──────────────────────────────────────────────

    public function index() {
        $data['jobs']       = $this->careers_model->get_all_positions('open');
        $data['page_title'] = 'Job Openings | CST SchoolHub';
        $this->public_view('index', $data);
    }

    public function job($id = null) {
        if (!$id) redirect(base_url('careers'));
        $job = $this->careers_model->get_position($id);
        if (!$job) show_404();
        $data['job']        = $job;
        $data['page_title'] = $job['title'] . ' | CST SchoolHub Careers';
        $this->public_view('job', $data);
    }

    // ── Public: Applicant Auth ────────────────────────────────────────────

    public function register() {
        if ($this->applicant_id()) redirect(base_url('careers/dashboard'));

        $this->form_validation->set_rules('full_name',        'Full Name',        'required|trim');
        $this->form_validation->set_rules('email',            'Email',            'required|trim|valid_email');
        $this->form_validation->set_rules('phone',            'Phone',            'required|trim');
        $this->form_validation->set_rules('password',         'Password',         'required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');

        $data['page_title'] = 'Create Account | CST SchoolHub Careers';
        $data['error']      = '';

        if ($this->form_validation->run()) {
            $email = $this->input->post('email', true);
            if ($this->careers_model->get_applicant_by_email($email)) {
                $data['error'] = 'An account with this email already exists. Please <a href="' . base_url('careers/login') . '">login</a>.';
            } else {
                $id = $this->careers_model->register_applicant([
                    'full_name' => $this->input->post('full_name', true),
                    'email'     => $email,
                    'phone'     => $this->input->post('phone',     true),
                    'password'  => $this->input->post('password'),
                ]);
                $this->session->set_userdata('career_applicant_id', $id);
                $redirect = $this->session->flashdata('apply_redirect');
                redirect($redirect ?: base_url('careers/dashboard'));
            }
        }

        $this->public_view('register', $data);
    }

    public function login() {
        if ($this->applicant_id()) redirect(base_url('careers/dashboard'));

        $this->form_validation->set_rules('email',    'Email',    'required|trim|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');

        $data['page_title'] = 'Login | CST SchoolHub Careers';
        $data['error']      = '';

        if ($this->form_validation->run()) {
            $applicant = $this->careers_model->login_applicant(
                $this->input->post('email',    true),
                $this->input->post('password')
            );
            if ($applicant) {
                $this->session->set_userdata('career_applicant_id', $applicant['id']);
                $redirect = $this->session->flashdata('apply_redirect');
                redirect($redirect ?: base_url('careers/dashboard'));
            } else {
                $data['error'] = 'Invalid email or password.';
            }
        }

        $this->public_view('login', $data);
    }

    public function logout() {
        $this->session->unset_userdata('career_applicant_id');
        redirect(base_url('careers'));
    }

    // ── Public: Apply ─────────────────────────────────────────────────────

    public function apply($position_id = null) {
        if (!$position_id) redirect(base_url('careers'));

        $job = $this->careers_model->get_position($position_id);
        if (!$job || $job['status'] !== 'open') {
            $data['page_title'] = 'Position Unavailable';
            $data['message']    = 'This position is no longer accepting applications.';
            $this->public_view('message', $data);
            return;
        }

        if (!$this->applicant_id()) {
            $this->session->set_flashdata('apply_redirect', base_url('careers/apply/' . $position_id));
            redirect(base_url('careers/login'));
        }

        $applicant_id = $this->applicant_id();

        if ($this->careers_model->has_applied($position_id, $applicant_id)) {
            $this->session->set_flashdata('info', 'You have already applied for this position.');
            redirect(base_url('careers/dashboard'));
        }

        $applicant      = $this->db->get_where('career_applicants', array('id' => $applicant_id))->row_array();
        $data['job']        = $job;
        $data['applicant']  = $applicant;
        $data['page_title'] = 'Apply — ' . $job['title'];
        $data['error']      = '';

        if ($this->input->post()) {
            // Update contact info if changed
            $newPhone = trim($this->input->post('phone'));
            $newEmail = trim($this->input->post('contact_email'));
            if ($newPhone || $newEmail) {
                $update = array();
                if ($newPhone) $update['phone'] = $newPhone;
                if ($newEmail) $update['email'] = $newEmail;
                $this->db->where('id', $applicant_id)->update('career_applicants', $update);
            }

            if (empty($_FILES['cv']['name'])) {
                $data['error'] = 'Please upload your CV (PDF or DOC, max 5MB).';
                $this->public_view('apply', $data);
                return;
            }

            $upload_path = FCPATH . 'uploads/documents/careers/';
            if (!is_dir($upload_path)) mkdir($upload_path, 0755, true);

            $file          = $_FILES['cv'];
            $allowed_ext   = ['pdf', 'doc', 'docx'];
            $ext           = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if ($file['error'] !== UPLOAD_ERR_OK) {
                $data['error'] = 'Upload error (code ' . $file['error'] . '). Please try again.';
                $this->public_view('apply', $data);
                return;
            }
            if (!in_array($ext, $allowed_ext)) {
                $data['error'] = 'Only PDF, DOC, and DOCX files are allowed.';
                $this->public_view('apply', $data);
                return;
            }
            if ($file['size'] > 5 * 1024 * 1024) {
                $data['error'] = 'File size must be under 5 MB.';
                $this->public_view('apply', $data);
                return;
            }

            $cv_orig = $file['name'];
            $cv_enc  = md5(uniqid(rand(), true)) . '.' . $ext;

            if (!move_uploaded_file($file['tmp_name'], $upload_path . $cv_enc)) {
                $data['error'] = 'Failed to save your CV. Please try again.';
                $this->public_view('apply', $data);
                return;
            }

            $app_id = $this->careers_model->save_application([
                'position_id'  => $position_id,
                'applicant_id' => $applicant_id,
                'county'       => $this->input->post('county', true),
                'cover_letter' => $this->input->post('cover_letter', true),
                'cv_orig_name' => $cv_orig,
                'cv_enc_name'  => $cv_enc,
                'status'       => 'pending',
            ]);

            $applicant = $this->careers_model->get_applicant($applicant_id);
            $this->send_email(
                $applicant['email'],
                'Application Received — ' . $job['title'],
                $this->email_tpl('Application Received',
                    "Dear {$applicant['full_name']},<br><br>
                    Thank you for applying for the position of <strong>{$job['title']}</strong> at CST SchoolHub.<br><br>
                    We have received your application and our HR team will review it shortly. You will receive an email update on the status of your application.<br><br>
                    Best regards,<br><strong>CST SchoolHub HR Team</strong>"
                )
            );

            $this->session->set_flashdata('success', 'Application submitted! We will contact you by email.');
            redirect(base_url('careers/dashboard'));
        }

        $this->public_view('apply', $data);
    }

    // ── Public: Applicant Dashboard ───────────────────────────────────────

    public function dashboard() {
        $this->require_applicant();
        $applicant_id           = $this->applicant_id();
        $data['applicant']      = $this->careers_model->get_applicant($applicant_id);
        $data['applications']   = $this->careers_model->get_applicant_applications($applicant_id);
        $data['page_title']     = 'My Applications | CST SchoolHub';
        $this->public_view('dashboard', $data);
    }

    public function my_application($id = null) {
        $this->require_applicant();
        if (!$id) redirect(base_url('careers/dashboard'));
        $app = $this->careers_model->get_application($id);
        if (!$app || $app['applicant_id'] != $this->applicant_id()) show_404();
        $data['app']        = $app;
        $data['replies']    = $this->careers_model->get_replies($id);
        $data['applicant']  = $this->careers_model->get_applicant($this->applicant_id());
        $data['page_title'] = 'Application | ' . $app['position_title'];
        $this->public_view('my_application', $data);
    }

    // ── Superadmin: Careers Management ────────────────────────────────────

    public function manage() {
        $this->require_superadmin();
        $this->data['stats']     = $this->careers_model->get_stats();
        $this->data['jobs']      = $this->careers_model->get_all_positions();
        $this->data['title']     = 'Careers Management';
        $this->data['sub_page']  = 'careers/manage';
        $this->data['main_menu'] = 'careers';
        $this->load->view('layout/index', $this->data);
    }

    public function add_job($id = null) {
        $this->require_superadmin();
        $job = $id ? $this->careers_model->get_position($id) : null;

        $this->form_validation->set_rules('title',        'Job Title',    'required|trim');
        $this->form_validation->set_rules('department',   'Department',   'trim');
        $this->form_validation->set_rules('description',  'Description',  'required|trim');
        $this->form_validation->set_rules('requirements', 'Requirements', 'trim');
        $this->form_validation->set_rules('deadline',     'Deadline',     'trim');
        $this->form_validation->set_rules('status',       'Status',       'required|in_list[open,closed]');

        if ($this->form_validation->run()) {
            $deadline = $this->input->post('deadline', true);
            $this->careers_model->save_position([
                'title'        => $this->input->post('title',        true),
                'department'   => $this->input->post('department',   true),
                'description'  => $this->input->post('description',  true),
                'requirements' => $this->input->post('requirements', true),
                'deadline'     => $deadline ?: null,
                'status'       => $this->input->post('status',       true),
            ], $id);
            set_alert('success', $id ? 'Job position updated.' : 'Job position posted successfully.');
            redirect(base_url('careers/manage'));
        }

        $this->data['job']       = $job;
        $this->data['title']     = $id ? 'Edit Job Position' : 'Post New Job';
        $this->data['sub_page']  = 'careers/add_job';
        $this->data['main_menu'] = 'careers';
        $this->load->view('layout/index', $this->data);
    }

    public function toggle_job($id) {
        $this->require_superadmin();
        $this->careers_model->toggle_position($id);
        set_alert('success', 'Position status updated.');
        redirect(base_url('careers/manage'));
    }

    public function delete_job($id) {
        $this->require_superadmin();
        $this->careers_model->delete_position($id);
        set_alert('success', 'Position deleted.');
        redirect(base_url('careers/manage'));
    }

    public function applications($position_id) {
        $this->require_superadmin();
        $job = $this->careers_model->get_position($position_id);
        if (!$job) show_404();
        $this->data['job']          = $job;
        $this->data['applications'] = $this->careers_model->get_applications_by_position($position_id);
        $this->data['title']        = 'Applications — ' . $job['title'];
        $this->data['sub_page']     = 'careers/applications';
        $this->data['main_menu']    = 'careers';
        $this->load->view('layout/index', $this->data);
    }

    public function view_application($id) {
        $this->require_superadmin();
        $app = $this->careers_model->get_application($id);
        if (!$app) show_404();

        if ($this->input->post('reply_message')) {
            $msg        = $this->input->post('reply_message', true);
            $new_status = $this->input->post('app_status',   true);

            $this->careers_model->add_reply([
                'application_id' => $id,
                'sender'         => 'admin',
                'message'        => $msg,
            ]);

            $allowed = ['pending', 'shortlisted', 'interview', 'rejected', 'hired'];
            if ($new_status && in_array($new_status, $allowed)) {
                $this->careers_model->update_application_status($id, $new_status);
                $app['status'] = $new_status;
            }

            $status_label = ucfirst($app['status']);
            $app_url      = base_url('careers/my_application/' . $id);

            $this->send_email(
                $app['email'],
                'Update on Your Application | ' . $app['position_title'],
                $this->email_tpl('Application Update',
                    "Dear {$app['full_name']},<br><br>
                    There is an update on your application for <strong>{$app['position_title']}</strong>.<br><br>
                    <strong>Current Status:</strong> {$status_label}<br><br>
                    <strong>Message from HR:</strong><br>" .
                    nl2br(htmlspecialchars($msg)) .
                    "<br><br><a href='{$app_url}' style='background:#1a5276;color:#fff;padding:10px 20px;border-radius:4px;text-decoration:none;'>View Application</a><br><br>
                    Best regards,<br><strong>CST SchoolHub HR Team</strong>"
                )
            );

            set_alert('success', 'Reply sent. Applicant notified by email.');
            redirect(base_url('careers/view_application/' . $id));
        }

        $this->data['app']       = $app;
        $this->data['replies']   = $this->careers_model->get_replies($id);
        $this->data['title']     = 'Application | ' . $app['full_name'];
        $this->data['sub_page']  = 'careers/view_application';
        $this->data['main_menu'] = 'careers';
        $this->load->view('layout/index', $this->data);
    }

    public function download_cv($id) {
        $this->require_superadmin();
        $app = $this->careers_model->get_application($id);
        if (!$app || !$app['cv_enc_name']) show_404();
        $file = FCPATH . 'uploads/documents/careers/' . $app['cv_enc_name'];
        if (!file_exists($file)) show_404();
        force_download($app['cv_orig_name'], file_get_contents($file));
    }
}
