<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Agent_portal extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('agent_model');
        $this->load->library(array('session', 'form_validation', 'app_lib'));
        $this->load->helper(array('url', 'form'));
    }

    // ─── AUTH HELPERS ──────────────────────────────────────────────

    private function _require_auth()
    {
        if (!$this->session->userdata('agent_loggedin')) {
            redirect('agent_portal/login');
        }
    }

    private function _agent_id()
    {
        return (int) $this->session->userdata('loggedin_agent_id');
    }

    private function _render($template, $data = array())
    {
        $data['_agent']      = $this->session->userdata('loggedin_agent');
        $data['_active_url'] = $this->uri->uri_string();
        $this->load->view('agent_portal/layout/header', $data);
        $this->load->view($template, $data);
        $this->load->view('agent_portal/layout/footer', $data);
    }

    // ─── LOGIN / LOGOUT ────────────────────────────────────────────

    public function login()
    {
        if ($this->session->userdata('agent_loggedin')) {
            redirect('agent_portal');
        }

        $data = array('error' => '');

        if ($this->input->post('login')) {
            $email    = trim($this->input->post('email'));
            $password = $this->input->post('password');

            $cred = $this->db->where(array('username' => $email, 'role' => 8, 'active' => 1))
                             ->get('login_credential')->row_array();

            if ($cred && $this->app_lib->verify_password($password, $cred['password'])) {
                $agent = $this->agent_model->getAgent($cred['user_id']);
                if ($agent && $agent['active']) {
                    $this->session->set_userdata(array(
                        'agent_loggedin'    => true,
                        'loggedin_agent_id' => $agent['id'],
                        'loggedin_agent'    => $agent,
                    ));
                    redirect('agent_portal');
                } else {
                    $data['error'] = 'Your account is inactive. Contact the administrator.';
                }
            } else {
                $data['error'] = 'Invalid email or password.';
            }
        }

        $this->load->view('agent_portal/login', $data);
    }

    public function logout()
    {
        $this->session->unset_userdata(array('agent_loggedin', 'loggedin_agent_id', 'loggedin_agent'));
        redirect('agent_portal/login');
    }

    // ─── DASHBOARD ─────────────────────────────────────────────────

    public function index()
    {
        $this->_require_auth();
        $agentId = $this->_agent_id();

        $data['stats']         = $this->agent_model->getDashboardStats($agentId);
        $data['followups']     = $this->agent_model->getFollowUps($agentId);
        $data['recent_visits'] = $this->agent_model->getVisits($agentId, null, 5);
        $data['title']         = 'Dashboard';
        $this->_render('agent_portal/dashboard', $data);
    }

    // ─── SCHOOLS ───────────────────────────────────────────────────

    public function schools()
    {
        $this->_require_auth();
        $agentId = $this->_agent_id();
        $status  = $this->input->get('status') ?: '';
        $search  = $this->input->get('q') ?: '';

        $data['schools'] = $this->agent_model->getSchools($agentId, $status, $search);
        $data['status']  = $status;
        $data['search']  = $search;
        $data['title']   = 'My Schools';
        $this->_render('agent_portal/schools/index', $data);
    }

    public function add_school()
    {
        $this->_require_auth();
        $agentId = $this->_agent_id();

        if ($this->input->post('save')) {
            $this->form_validation->set_rules('school_name', 'School Name', 'trim|required');
            if ($this->form_validation->run()) {
                $this->agent_model->addSchool(array(
                    'agent_id'       => $agentId,
                    'school_name'    => $this->input->post('school_name'),
                    'principal_name' => $this->input->post('principal_name'),
                    'phone'          => $this->input->post('phone'),
                    'email'          => $this->input->post('email'),
                    'county'         => $this->input->post('county'),
                    'sub_county'     => $this->input->post('sub_county'),
                    'num_students'   => (int) $this->input->post('num_students'),
                    'current_system' => $this->input->post('current_system'),
                    'notes'          => $this->input->post('notes'),
                    'status'         => 'lead',
                    'interest_level' => 'unknown',
                ));
                redirect('agent_portal/schools');
            }
        }

        $data['title'] = 'Add School Lead';
        $this->_render('agent_portal/schools/add', $data);
    }

    public function view_school($id = '')
    {
        $this->_require_auth();
        $agentId = $this->_agent_id();
        $school  = $this->agent_model->getSchool($id, $agentId);
        if (!$school) {
            show_404();
        }

        // Handle inline status/notes update
        if ($this->input->post('update_school')) {
            $upd = array(
                'principal_name' => $this->input->post('principal_name'),
                'phone'          => $this->input->post('phone'),
                'email'          => $this->input->post('email'),
                'interest_level' => $this->input->post('interest_level'),
                'status'         => $this->input->post('status'),
                'notes'          => $this->input->post('notes'),
            );
            $newStatus = $this->input->post('status');
            if ($newStatus === 'closed_won') {
                $upd['assigned_plan_id'] = (int) $this->input->post('assigned_plan_id') ?: null;
            }
            $this->agent_model->updateSchool($id, $upd);
            redirect('agent_portal/view_school/' . $id);
        }

        $data['school'] = $this->agent_model->getSchool($id, $agentId);
        $data['visits'] = $this->agent_model->getVisits($agentId, $id);
        $data['plans']  = $this->agent_model->getPlans(true);
        $data['title']  = $data['school']['school_name'];
        $this->_render('agent_portal/schools/view', $data);
    }

    // ─── VISITS ────────────────────────────────────────────────────

    public function log_visit($schoolId = '')
    {
        $this->_require_auth();
        $agentId = $this->_agent_id();
        $school  = $this->agent_model->getSchool($schoolId, $agentId);
        if (!$school) {
            show_404();
        }

        if ($this->input->post('save')) {
            $outcome   = $this->input->post('outcome');
            $planId    = (int) $this->input->post('plan_id') ?: null;
            $visitData = array(
                'agent_id'           => $agentId,
                'school_id'          => $schoolId,
                'visit_date'         => $this->input->post('visit_date') ?: date('Y-m-d'),
                'visit_type'         => $this->input->post('visit_type'),
                'interest_level'     => $this->input->post('interest_level'),
                'modules_demoed'     => $this->input->post('modules_demoed'),
                'notes'              => $this->input->post('notes'),
                'outcome'            => $outcome,
                'next_followup_date' => $this->input->post('next_followup_date') ?: null,
                'plan_id'            => $planId,
            );
            $visitId = $this->agent_model->logVisit($visitData);

            // Update school pipeline status
            $statusMap = array(
                'signed_up'      => 'closed_won',
                'not_interested' => 'closed_lost',
                'needs_followup' => 'follow_up',
            );
            $newSchoolStatus = isset($statusMap[$outcome]) ? $statusMap[$outcome]
                             : ($visitData['visit_type'] === 'demo' ? 'demo_done' : 'visited');

            $schoolUpd = array(
                'status'         => $newSchoolStatus,
                'interest_level' => $visitData['interest_level'],
            );
            if ($outcome === 'signed_up' && $planId) {
                $schoolUpd['assigned_plan_id'] = $planId;
            }
            $this->agent_model->updateSchool($schoolId, $schoolUpd);

            // Auto-create visit_fee earning
            $plans    = $this->agent_model->getPlans(true);
            $visitFee = !empty($plans) ? $plans[0]['visit_fee'] : 500;
            $this->agent_model->addEarning(array(
                'agent_id'    => $agentId,
                'visit_id'    => $visitId,
                'school_id'   => $schoolId,
                'type'        => 'visit_fee',
                'amount'      => $visitFee,
                'description' => 'Visit fee — ' . $school['school_name'],
                'status'      => 'pending',
            ));

            redirect('agent_portal/view_school/' . $schoolId);
        }

        $data['school'] = $school;
        $data['plans']  = $this->agent_model->getPlans(true);
        $data['title']  = 'Log Visit — ' . $school['school_name'];
        $this->_render('agent_portal/visits/log', $data);
    }

    // ─── FOLLOW-UPS ────────────────────────────────────────────────

    public function followups()
    {
        $this->_require_auth();
        $agentId = $this->_agent_id();
        $data['followups'] = $this->agent_model->getFollowUps($agentId);
        $data['title']     = 'Follow-ups';
        $this->_render('agent_portal/followups/index', $data);
    }

    // ─── EARNINGS ──────────────────────────────────────────────────

    public function earnings()
    {
        $this->_require_auth();
        $agentId = $this->_agent_id();

        $rows    = $this->agent_model->getEarnings($agentId);
        $total   = array_sum(array_column($rows, 'amount'));
        $pending = 0; $paid = 0;
        foreach ($rows as $r) {
            if ($r['status'] === 'paid')    $paid    += $r['amount'];
            if ($r['status'] === 'pending') $pending += $r['amount'];
        }

        $data['earnings'] = $rows;
        $data['summary']  = array('total' => $total, 'pending' => $pending, 'paid' => $paid);
        $data['title']    = 'My Earnings';
        $this->_render('agent_portal/earnings/index', $data);
    }

    // ─── EXPENSES ──────────────────────────────────────────────────

    public function expenses()
    {
        $this->_require_auth();
        $agentId = $this->_agent_id();

        if ($this->input->post('save')) {
            $this->form_validation->set_rules('description', 'Description', 'trim|required');
            $this->form_validation->set_rules('amount', 'Amount', 'trim|required|numeric');
            if ($this->form_validation->run()) {
                $this->agent_model->addExpense(array(
                    'agent_id'    => $agentId,
                    'school_id'   => (int) $this->input->post('school_id') ?: null,
                    'description' => $this->input->post('description'),
                    'amount'      => $this->input->post('amount'),
                    'status'      => 'pending',
                ));
                redirect('agent_portal/expenses');
            }
        }

        $data['expenses'] = $this->agent_model->getExpenses($agentId);
        $data['schools']  = $this->agent_model->getSchools($agentId);
        $data['title']    = 'Expense Claims';
        $this->_render('agent_portal/expenses/index', $data);
    }

    // ─── DOWNLOAD BROCHURE ─────────────────────────────────────────

    public function download_brochure()
    {
        $this->_require_auth();
        $file = FCPATH . 'uploads/documents/cst-schoolhub-brochure.pdf';
        if (!file_exists($file)) {
            show_404();
        }
        $this->load->helper('download');
        force_download('CST_SchoolHub_Brochure.pdf', file_get_contents($file));
    }

    // ─── DOWNLOAD DATA COLLECTION FORM ────────────────────────────

    public function download_form()
    {
        $this->_require_auth();
        $file = FCPATH . 'uploads/documents/CST_School_Hub_Data_Collection_Form_v2.2.docx';
        if (!file_exists($file)) {
            show_404();
        }
        $this->load->helper('download');
        force_download('CST_School_Hub_Data_Collection_Form_v2.2.docx', file_get_contents($file));
    }

    // ─── SCHOOL ONBOARDING SUBMISSION ─────────────────────────────

    public function submit_school($schoolId = '')
    {
        $this->_require_auth();
        $agentId = $this->_agent_id();
        $school  = $this->agent_model->getSchool($schoolId, $agentId);
        if (!$school) show_404();

        $data['success'] = false;
        $data['errors']  = array();

        if ($this->input->post('submit_onboarding')) {
            $this->form_validation->set_rules('school_name',    'School Name',    'trim|required');
            $this->form_validation->set_rules('principal_name', 'Principal Name', 'trim|required');
            $this->form_validation->set_rules('school_phone',   'School Phone',   'trim|required');
            $this->form_validation->set_rules('admin_name',     'Admin Name',     'trim|required');
            $this->form_validation->set_rules('admin_phone',    'Admin Phone',    'trim|required');
            $this->form_validation->set_rules('admin_email',    'Admin Email',    'trim|required|valid_email');

            if ($this->form_validation->run()) {
                // Handle filled form upload
                $filePath = null;
                if (!empty($_FILES['filled_form']['name'])) {
                    $uploadDir = FCPATH . 'uploads/onboarding/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    $uploadConfig = array(
                        'upload_path'   => $uploadDir,
                        'allowed_types' => 'doc|docx|pdf',
                        'max_size'      => 10240,
                        'file_name'     => 'school_' . $schoolId . '_' . time(),
                    );
                    $this->load->library('upload', $uploadConfig);
                    if ($this->upload->do_upload('filled_form')) {
                        $upData   = $this->upload->data();
                        $filePath = 'uploads/onboarding/' . $upData['file_name'];
                    }
                }

                $this->db->insert('school_onboarding_requests', array(
                    'agent_id'               => $agentId,
                    'agent_school_id'        => $schoolId,
                    'school_name'            => $this->input->post('school_name',      TRUE),
                    'subscription_plan_id'   => (int) $this->input->post('subscription_plan_id') ?: null,
                    'billing_cycle'          => $this->input->post('billing_cycle') === 'yearly' ? 'yearly' : 'monthly',
                    'reg_number'             => $this->input->post('reg_number',       TRUE),
                    'school_type'            => $this->input->post('school_type',      TRUE),
                    'school_category'        => $this->input->post('school_category',  TRUE),
                    'county'                 => $this->input->post('county',           TRUE),
                    'sub_county'             => $this->input->post('sub_county',       TRUE),
                    'ward'                   => $this->input->post('ward',             TRUE),
                    'physical_address'       => $this->input->post('physical_address', TRUE),
                    'postal_address'         => $this->input->post('postal_address',   TRUE),
                    'school_phone'           => $this->input->post('school_phone',     TRUE),
                    'school_email'           => $this->input->post('school_email',     TRUE),
                    'school_website'         => $this->input->post('school_website',   TRUE),
                    'principal_name'         => $this->input->post('principal_name',   TRUE),
                    'principal_phone'        => $this->input->post('principal_phone',  TRUE),
                    'principal_email'        => $this->input->post('principal_email',  TRUE),
                    'num_students'           => (int) $this->input->post('num_students'),
                    'num_teaching_staff'     => (int) $this->input->post('num_teaching_staff'),
                    'num_non_teaching_staff' => (int) $this->input->post('num_non_teaching_staff'),
                    'num_streams'            => $this->input->post('num_streams',      TRUE),
                    'admin_name'             => $this->input->post('admin_name',       TRUE),
                    'admin_phone'            => $this->input->post('admin_phone',      TRUE),
                    'admin_email'            => $this->input->post('admin_email',      TRUE),
                    'notes'                  => $this->input->post('notes',            TRUE),
                    'filled_form_path'       => $filePath,
                    'status'                 => 'pending',
                    'submitted_at'           => date('Y-m-d H:i:s'),
                ));
                $this->agent_model->updateSchool($schoolId, array('status' => 'closed_won'));
                $data['success'] = true;
            }
        }

        $data['school']  = $school;
        $data['sub_plans'] = $this->db->order_by('monthly_price','ASC')->get_where('subscription_plans', array('is_active' => 1))->result_array();
        $data['title']   = 'Submit School for Setup — ' . $school['school_name'];
        $this->_render('agent_portal/schools/submit', $data);
    }

    public function my_submissions()
    {
        $this->_require_auth();
        $agentId = $this->_agent_id();
        $rows = $this->db->where('agent_id', $agentId)->order_by('submitted_at','DESC')->get('school_onboarding_requests')->result_array();
        $data['submissions'] = $rows;
        $data['title']       = 'My Submissions';
        $this->_render('agent_portal/schools/submissions', $data);
    }

    // ─── VISIT SCRIPT ──────────────────────────────────────────────

    public function script()
    {
        $this->_require_auth();
        $this->_render('agent_portal/script', array('title' => 'School Visit Script'));
    }

    // ─── GUIDE ─────────────────────────────────────────────────────

    public function guide()
    {
        $this->_require_auth();
        $this->_render('agent_portal/guide', array('title' => 'How to Use the Agent Portal'));
    }

    // ─── DEMO SCHOOL ───────────────────────────────────────────────

    public function demo()
    {
        $this->_require_auth();
        $this->_render('agent_portal/demo', array('title' => 'Demo School — Sunrise Academy'));
    }

    // ─── ALL MODULES SHOWCASE ──────────────────────────────────────

    public function modules()
    {
        $this->_require_auth();
        $this->_render('agent_portal/modules', array('title' => 'All System Modules'));
    }

    // ─── PROFILE ───────────────────────────────────────────────────

    public function profile()
    {
        $this->_require_auth();
        $agentId = $this->_agent_id();

        if ($this->input->post('save_profile')) {
            $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
            $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
            if ($this->form_validation->run()) {
                $upd = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name'  => $this->input->post('last_name'),
                    'phone'      => $this->input->post('phone'),
                );
                $this->agent_model->updateAgent($agentId, $upd);
                // refresh session
                $agent = $this->agent_model->getAgent($agentId);
                $this->session->set_userdata('loggedin_agent', $agent);
                redirect('agent_portal/profile');
            }
        }

        if ($this->input->post('save_password')) {
            $this->form_validation->set_rules('new_password', 'New Password', 'trim|required|min_length[6]');
            if ($this->form_validation->run()) {
                $current  = $this->input->post('current_password');
                $cred     = $this->db->where(array('user_id' => $agentId, 'role' => 8))->get('login_credential')->row_array();
                if ($cred && $this->app_lib->verify_password($current, $cred['password'])) {
                    $this->agent_model->resetPassword($agentId, $this->input->post('new_password'));
                    $data['pw_success'] = 'Password changed successfully.';
                } else {
                    $data['pw_error'] = 'Current password is incorrect.';
                }
            }
        }

        $data['agent'] = $this->agent_model->getAgent($agentId);
        $data['title'] = 'My Profile';
        $this->_render('agent_portal/profile', $data);
    }
}
