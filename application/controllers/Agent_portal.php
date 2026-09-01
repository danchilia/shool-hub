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
        if ($this->session->userdata('agent_must_change_password')) {
            redirect('agent_portal/change_password');
        }
        // Skip terms check on the terms page itself
        $current = $this->uri->uri_string();
        if (strpos($current, 'agent_portal/terms') === false &&
            strpos($current, 'agent_portal/logout') === false) {
            if (!$this->agent_model->hasAcceptedTerms($this->_agent_id())) {
                redirect('agent_portal/terms');
            }
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
                    $mustChange = !empty($agent['must_change_password']);
                    $this->session->set_userdata(array(
                        'agent_loggedin'             => true,
                        'loggedin_agent_id'          => $agent['id'],
                        'loggedin_agent'             => $agent,
                        'agent_must_change_password' => $mustChange,
                    ));
                    redirect($mustChange ? 'agent_portal/change_password' : 'agent_portal');
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
                    'lat'            => $this->input->post('lat')  ?: null,
                    'lng'            => $this->input->post('lng')  ?: null,
                    'gps_accuracy'   => $this->input->post('gps_accuracy') ?: null,
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
                'lat'                => $this->input->post('lat') ?: null,
                'lng'                => $this->input->post('lng') ?: null,
                'gps_accuracy'       => $this->input->post('gps_accuracy') ?: null,
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
                'description' => 'Visit fee: ' . $school['school_name'],
                'status'      => 'pending',
            ));

            redirect('agent_portal/view_school/' . $schoolId);
        }

        $data['school'] = $school;
        $data['plans']  = $this->agent_model->getPlans(true);
        $data['title']  = 'Log Visit: ' . $school['school_name'];
        $this->_render('agent_portal/visits/log', $data);
    }

    // AJAX — duplicate school check
    public function check_duplicate()
    {
        if (!$this->session->userdata('agent_loggedin')) {
            echo json_encode(array('found' => false));
            return;
        }
        $name  = trim($this->input->post('school_name'));
        $phone = trim($this->input->post('phone'));

        $this->db->select('s.school_name, s.county, s.created_at, a.first_name, a.last_name');
        $this->db->from('agent_school s');
        $this->db->join('agent a', 'a.id = s.agent_id', 'left');
        if ($name) {
            $this->db->group_start();
            $this->db->like('s.school_name', $name, 'both');
            if ($phone) {
                $this->db->or_where('s.phone', $phone);
            }
            $this->db->group_end();
        } elseif ($phone) {
            $this->db->where('s.phone', $phone);
        } else {
            echo json_encode(array('found' => false));
            return;
        }
        $row = $this->db->limit(1)->get()->row_array();

        if ($row) {
            echo json_encode(array(
                'found'       => true,
                'school_name' => $row['school_name'],
                'agent_name'  => trim($row['first_name'] . ' ' . $row['last_name']),
                'county'      => $row['county'],
                'added_on'    => date('d M Y', strtotime($row['created_at'])),
            ));
        } else {
            echo json_encode(array('found' => false));
        }
    }

    // ─── FOLLOW-UPS ────────────────────────────────────────────────

    public function change_password()
    {
        if (!$this->session->userdata('agent_loggedin')) {
            redirect('agent_portal/login');
        }
        $agentId = $this->_agent_id();
        $data = array('error' => '', 'success' => '');

        if ($this->input->post('save')) {
            $new     = $this->input->post('new_password');
            $confirm = $this->input->post('confirm_password');
            if (strlen($new) < 6) {
                $data['error'] = 'Password must be at least 6 characters.';
            } elseif ($new !== $confirm) {
                $data['error'] = 'Passwords do not match.';
            } else {
                $this->agent_model->resetPassword($agentId, $new);
                $this->db->where('id', $agentId)->update('agent', array('must_change_password' => 0));
                $this->session->unset_userdata('agent_must_change_password');
                redirect('agent_portal');
            }
        }

        $data['_agent']      = $this->session->userdata('loggedin_agent');
        $data['_active_url'] = $this->uri->uri_string();
        $data['title']       = 'Set Your Password';
        $this->load->view('agent_portal/layout/header', $data);
        $this->load->view('agent_portal/change_password', $data);
        $this->load->view('agent_portal/layout/footer', $data);
    }

    public function my_level()
    {
        $this->_require_auth();
        $agentId = $this->_agent_id();
        $data['level_data'] = $this->agent_model->getAgentLevelData($agentId);
        $data['title']      = 'My Level';
        $this->_render('agent_portal/levels/index', $data);
    }

    // ─── TERMS ACCEPTANCE ─────────────────────────────────────────

    public function terms()
    {
        if (!$this->session->userdata('agent_loggedin')) {
            redirect('agent_portal/login');
        }
        if ($this->session->userdata('agent_must_change_password')) {
            redirect('agent_portal/change_password');
        }

        $agentId = $this->_agent_id();

        if ($this->agent_model->hasAcceptedTerms($agentId)) {
            redirect('agent_portal');
        }

        $data = array('error' => '');

        if ($this->input->post('accept')) {
            if ($this->input->post('i_agree') != '1') {
                $data['error'] = 'You must check the box to confirm you have read and agree to the terms.';
            } else {
                $this->agent_model->acceptTerms($agentId, $this->input->ip_address());
                redirect('agent_portal');
            }
        }

        $data['title'] = 'Agent Terms & Conditions';
        $this->_render('agent_portal/terms', $data);
    }

    // ─── CONTRACT UPLOAD ──────────────────────────────────────────

    public function my_contract()
    {
        $this->_require_auth();
        $agentId   = $this->_agent_id();
        $levelData = $this->agent_model->getAgentLevelData($agentId);
        $contract  = $this->agent_model->getContract($agentId);

        if ($this->input->post('upload') && !empty($_FILES['contract_file']['name'])) {
            $uploadPath = './uploads/documents/agent_contracts/';
            if (!is_dir($uploadPath)) mkdir($uploadPath, 0755, true);

            $this->load->library('upload', array(
                'upload_path'   => $uploadPath,
                'allowed_types' => 'pdf|jpg|jpeg|png',
                'max_size'      => 10240,
                'encrypt_name'  => true,
            ));

            if ($this->upload->do_upload('contract_file')) {
                $file = $this->upload->data();
                $this->agent_model->uploadContract(
                    $agentId,
                    'uploads/documents/agent_contracts/' . $file['file_name'],
                    $levelData['current']['name']
                );
                $this->session->set_flashdata('contract_success', 'Contract uploaded successfully. CST will review and verify it.');
                redirect('agent_portal/my_contract');
            } else {
                $data['upload_error'] = $this->upload->display_errors('', '');
            }
        }

        $data['contract']   = $this->agent_model->getContract($agentId);
        $data['level_data'] = $levelData;
        $data['title']      = 'My Contract';
        $this->_render('agent_portal/contract/index', $data);
    }

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
            $this->form_validation->set_rules('school_id',  'School',       'trim|required');
            $this->form_validation->set_rules('amount',     'Amount',       'trim|required|numeric');
            if ($this->form_validation->run()) {
                $amount = (float) $this->input->post('amount');
                if ($amount > 300) {
                    $this->session->set_flashdata('expense_error', 'Expense claim cannot exceed KSh 300 per school visit.');
                    redirect('agent_portal/expenses');
                }
                $this->agent_model->addExpense(array(
                    'agent_id'    => $agentId,
                    'school_id'   => (int) $this->input->post('school_id') ?: null,
                    'description' => $this->input->post('description'),
                    'amount'      => $amount,
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

    // ─── DOWNLOAD CONTRACT TEMPLATE ───────────────────────────────

    public function download_contract()
    {
        $this->_require_auth();
        $agentId   = $this->_agent_id();
        $agent     = $this->agent_model->getAgent($agentId);
        $levelData = $this->agent_model->getAgentLevelData($agentId);
        $level     = $levelData['current']['name'];
        $date      = date('d F Y');
        $salary    = $levelData['salary'] > 0 ? 'KSh ' . number_format($levelData['salary']) . ' per month' : 'As per the active level schedule';

        $html = '<!DOCTYPE html><html><head><meta charset="UTF-8">
<style>
body{font-family:Arial,sans-serif;font-size:13px;color:#222;margin:40px;line-height:1.8}
h1{font-size:18px;text-align:center;margin-bottom:4px}
.center{text-align:center}
.section{margin:24px 0}
h3{font-size:14px;margin:16px 0 6px;text-decoration:underline}
p{margin:8px 0}
.sign-block{margin-top:60px;display:flex;justify-content:space-between}
.sign-line{border-top:1px solid #000;width:220px;margin-top:48px;font-size:11px;text-align:center;padding-top:4px}
</style>
</head><body>
<div class="center">
<h1>CST SOLUTIONS</h1>
<p style="font-size:12px;color:#555">Building Digital Solutions for a Better Tomorrow</p>
<hr>
<h1 style="margin-top:16px">FIELD AGENT EMPLOYMENT CONTRACT</h1>
<p style="font-size:12px">Level: <strong>' . htmlspecialchars($level) . '</strong> &nbsp;|&nbsp; Date: <strong>' . $date . '</strong></p>
</div>

<div class="section">
<h3>1. PARTIES</h3>
<p>This contract is entered into between <strong>CST Solutions</strong> (hereinafter "the Company") and:</p>
<p>Full Name: <strong>' . htmlspecialchars($agent['first_name'] . ' ' . $agent['last_name']) . '</strong></p>
<p>Email: <strong>' . htmlspecialchars($agent['email']) . '</strong></p>
<p>Phone: <strong>' . htmlspecialchars($agent['phone'] ?? '') . '</strong></p>
<p>Region: <strong>' . htmlspecialchars($agent['region'] ?? '') . '</strong></p>
<p>(hereinafter "the Agent")</p>
</div>

<div class="section">
<h3>2. NATURE OF CONTRACT</h3>
<p>This is a <strong>one-year employment contract</strong> commencing on the date of signing and valid for twelve (12) months. The contract is renewable based on the Agent\'s continued performance and active school portfolio.</p>
</div>

<div class="section">
<h3>3. MONTHLY RETAINER</h3>
<p>The Agent is entitled to a monthly retainer of <strong>' . $salary . '</strong>, payable while their active school portfolio is maintained as per the levels schedule.</p>
<p>The retainer is calculated at KSh 1,000 per active school per month, up to a maximum of KSh 50,000 per month. A school is active as long as it continues paying and using the CST SchoolHub platform.</p>
</div>

<div class="section">
<h3>4. AGENT RESPONSIBILITIES</h3>
<p>The Agent agrees to:</p>
<ul>
<li>Identify and visit potential schools in their assigned region</li>
<li>Present and demonstrate the CST SchoolHub platform professionally and honestly</li>
<li>Provide ongoing customer care and follow-up support to all schools in their portfolio</li>
<li>Submit accurate visit reports and updates as required</li>
<li>Maintain professional conduct and uphold the reputation of CST Solutions</li>
</ul>
</div>

<div class="section">
<h3>5. EXPENSE REIMBURSEMENT</h3>
<p>The Company will reimburse legitimate school visit expenses up to a maximum of <strong>KSh 300 per school visit</strong>, paid at the end of each month upon approval of claims submitted through the agent portal.</p>
</div>

<div class="section">
<h3>6. CONFIDENTIALITY</h3>
<p>The Agent shall not disclose any proprietary information, client data, pricing, or business strategies of CST Solutions to any third party during or after the term of this contract.</p>
</div>

<div class="section">
<h3>7. TERMINATION</h3>
<p>Either party may terminate this contract by providing <strong>14 days written notice</strong>. The Company reserves the right to terminate immediately in cases of misconduct, dishonesty, or material breach of this contract.</p>
</div>

<div class="section">
<h3>8. CONTRACT RENEWAL</h3>
<p>This contract is renewable annually based on the Agent\'s performance and active portfolio. The Company will review and notify the Agent of renewal terms at least 30 days before expiry.</p>
</div>

<div class="section">
<h3>9. GOVERNING LAW</h3>
<p>This contract is governed by the laws of Kenya. Any disputes shall be resolved through mutual negotiation or through the appropriate Kenyan courts.</p>
</div>

<div class="sign-block">
  <div>
    <div class="sign-line">Signature of Agent</div>
    <p style="font-size:11px;margin:4px 0 0">Name: ' . htmlspecialchars($agent['first_name'] . ' ' . $agent['last_name']) . '</p>
    <p style="font-size:11px;margin:2px 0">Date: ___________________</p>
  </div>
  <div>
    <div class="sign-line">Authorized Signatory — CST Solutions</div>
    <p style="font-size:11px;margin:4px 0 0">Name: ___________________</p>
    <p style="font-size:11px;margin:2px 0">Date: ___________________</p>
  </div>
</div>
</body></html>';

        header('Content-Type: text/html');
        header('Content-Disposition: attachment; filename="CST_Agent_Contract_' . preg_replace('/[^a-zA-Z0-9]/', '_', $agent['first_name'] . '_' . $agent['last_name']) . '.html"');
        echo $html;
        exit;
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
        $data['title']   = 'Submit School for Setup: ' . $school['school_name'];
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
        $this->_render('agent_portal/demo', array('title' => 'Demo School: Sunrise Academy'));
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
