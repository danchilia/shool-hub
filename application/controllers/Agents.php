<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Agents extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!is_superadmin_loggedin()) {
            access_denied();
        }
        $this->load->model('agent_model');
    }

    public function index()
    {
        $this->data['agents']    = $this->agent_model->getAllAgents();
        $this->data['stats']     = $this->agent_model->getSuperadminStats();
        $this->data['title']     = 'Field Agents';
        $this->data['sub_page']  = 'agents/index';
        $this->data['main_menu'] = 'agents';
        $this->load->view('layout/index', $this->data);
    }

    public function add()
    {
        if ($this->input->post('save')) {
            $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
            $this->form_validation->set_rules('last_name',  'Last Name',  'trim|required');
            $this->form_validation->set_rules('email',      'Email',      'trim|required|valid_email');
            $this->form_validation->set_rules('phone',      'Phone',      'trim|required');

            if ($this->form_validation->run()) {
                $email    = $this->input->post('email');
                $existing = $this->db->where(array('username' => $email, 'role' => 8))
                                     ->get('login_credential')->num_rows();
                if ($existing > 0) {
                    $this->data['error'] = 'An agent with this email already exists.';
                } else {
                    $defaultPassword = '12345678';
                    $firstName       = $this->input->post('first_name');
                    $lastName        = $this->input->post('last_name');

                    $this->agent_model->createAgent(array(
                        'first_name'          => $firstName,
                        'last_name'           => $lastName,
                        'email'               => $email,
                        'phone'               => $this->input->post('phone'),
                        'region'              => $this->input->post('region'),
                        'active'              => 1,
                        'must_change_password'=> 1,
                        'created_by'          => get_loggedin_user_id(),
                    ), $defaultPassword);

                    // Send welcome email
                    $this->load->model('email_model');
                    $portalUrl = base_url('agent_portal/login');
                    $message = '
                        <p>Dear ' . htmlspecialchars($firstName) . ',</p>
                        <p>Welcome to the <strong>CST SchoolHub Agent Portal</strong>! Your account has been created.</p>
                        <p>Use the details below to log in:</p>
                        <table style="border-collapse:collapse;margin:12px 0">
                            <tr><td style="padding:6px 14px 6px 0;font-weight:600">Portal URL:</td><td><a href="' . $portalUrl . '">' . $portalUrl . '</a></td></tr>
                            <tr><td style="padding:6px 14px 6px 0;font-weight:600">Email:</td><td>' . htmlspecialchars($email) . '</td></tr>
                            <tr><td style="padding:6px 14px 6px 0;font-weight:600">Temporary Password:</td><td><strong>' . $defaultPassword . '</strong></td></tr>
                        </table>
                        <p style="color:#e74c3c;font-weight:600">You will be required to change your password on first login.</p>
                        <p>If you have any questions, contact the CST Solutions team.</p>
                        <p>Best regards,<br><strong>CST Solutions</strong></p>
                    ';
                    $this->email_model->sendEmail(array(
                        'recipient' => $email,
                        'subject'   => 'Welcome to CST SchoolHub Agent Portal — Your Login Details',
                        'message'   => $message,
                    ));

                    redirect('agents');
                }
            }
        }

        $this->data['title']     = 'Add Field Agent';
        $this->data['sub_page']  = 'agents/add';
        $this->data['main_menu'] = 'agents';
        $this->load->view('layout/index', $this->data);
    }

    public function edit($id = '')
    {
        $agent = $this->agent_model->getAgent($id);
        if (!$agent) {
            show_404();
        }

        if ($this->input->post('save')) {
            $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
            $this->form_validation->set_rules('email',      'Email',      'trim|required|valid_email');
            if ($this->form_validation->run()) {
                $this->agent_model->updateAgent($id, array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name'  => $this->input->post('last_name'),
                    'email'      => $this->input->post('email'),
                    'phone'      => $this->input->post('phone'),
                    'region'     => $this->input->post('region'),
                ));
                if ($this->input->post('password')) {
                    $this->agent_model->resetPassword($id, $this->input->post('password'));
                }
                redirect('agents/view/' . $id);
            }
        }

        $this->data['agent']     = $agent;
        $this->data['title']     = 'Edit Agent';
        $this->data['sub_page']  = 'agents/edit';
        $this->data['main_menu'] = 'agents';
        $this->load->view('layout/index', $this->data);
    }

    public function toggle($id = '')
    {
        $this->agent_model->toggleActive($id);
        redirect('agents');
    }

    public function view($id = '')
    {
        $agent = $this->agent_model->getAgent($id);
        if (!$agent) {
            show_404();
        }

        $this->data['agent']     = $agent;
        $this->data['stats']     = $this->agent_model->getDashboardStats($id);
        $this->data['schools']   = $this->agent_model->getSchools($id);
        $this->data['visits']    = $this->agent_model->getVisits($id, null, 10);
        $this->data['earnings']  = $this->agent_model->getEarnings($id);
        $this->data['agreement'] = $this->agent_model->getAgreement($id);
        $this->data['contract']  = $this->agent_model->getContract($id);
        $this->data['title']     = $agent['first_name'] . ' ' . $agent['last_name'];
        $this->data['sub_page']  = 'agents/view';
        $this->data['main_menu'] = 'agents';
        $this->load->view('layout/index', $this->data);
    }

    public function review_contract($agentId = '')
    {
        $status = $this->input->post('status');
        $note   = $this->input->post('note', true);
        $this->agent_model->reviewContract($agentId, $status, $note, get_loggedin_user_id());
        redirect('agents/view/' . $agentId);
    }

    public function download_agent_contract($agentId = '')
    {
        $contract = $this->agent_model->getContract($agentId);
        if (!$contract || empty($contract['file_path'])) show_404();
        $file = FCPATH . $contract['file_path'];
        if (!file_exists($file)) show_404();
        $this->load->helper('download');
        $ext  = pathinfo($file, PATHINFO_EXTENSION);
        $agent = $this->agent_model->getAgent($agentId);
        $name = preg_replace('/[^a-zA-Z0-9_]/', '_', $agent['first_name'] . '_' . $agent['last_name']);
        force_download('Signed_Contract_' . $name . '.' . $ext, file_get_contents($file));
    }

    public function earnings()
    {
        $filter = $this->input->get('status') ?: '';
        $agent_filter = (int) $this->input->get('agent_id') ?: null;

        $this->data['earnings']  = $this->agent_model->getEarnings($agent_filter, $filter);
        $this->data['agents']    = $this->agent_model->getAllAgents();
        $this->data['filter']    = $filter;
        $this->data['agent_filter'] = $agent_filter;
        $this->data['title']     = 'Agent Earnings';
        $this->data['sub_page']  = 'agents/earnings';
        $this->data['main_menu'] = 'agents';
        $this->load->view('layout/index', $this->data);
    }

    public function approve_earning($id = '')
    {
        $this->db->where('id', $id)->update('agent_earning', array(
            'status'     => 'approved',
            'updated_at' => date('Y-m-d H:i:s'),
        ));
        redirect('agents/earnings');
    }

    public function mark_paid($id = '')
    {
        $this->agent_model->updateEarningStatus($id, 'paid', get_loggedin_user_id());
        redirect('agents/earnings');
    }

    public function reject_earning($id = '')
    {
        $this->db->where('id', $id)->update('agent_earning', array(
            'status'     => 'rejected',
            'updated_at' => date('Y-m-d H:i:s'),
        ));
        redirect('agents/earnings');
    }

    public function all_schools()
    {
        $agent_filter = (int) $this->input->get('agent_id') ?: null;
        $status_filter = $this->input->get('status') ?: '';

        $this->data['schools']      = $this->agent_model->getSchools($agent_filter, $status_filter);
        $this->data['agents']       = $this->agent_model->getAllAgents();
        $this->data['agent_filter'] = $agent_filter;
        $this->data['status_filter']= $status_filter;
        $this->data['title']        = 'All Prospect Schools';
        $this->data['sub_page']     = 'agents/all_schools';
        $this->data['main_menu']    = 'agents';
        $this->load->view('layout/index', $this->data);
    }

    public function expenses()
    {
        $filter = $this->input->get('status') ?: '';

        $this->data['expenses']  = $this->agent_model->getExpenses(null, $filter);
        $this->data['filter']    = $filter;
        $this->data['title']     = 'Agent Expense Claims';
        $this->data['sub_page']  = 'agents/expenses';
        $this->data['main_menu'] = 'agents';
        $this->load->view('layout/index', $this->data);
    }

    public function approve_expense($id = '')
    {
        $this->agent_model->updateExpenseStatus($id, 'approved', get_loggedin_user_id());
        redirect('agents/expenses');
    }

    public function reject_expense($id = '')
    {
        $note = $this->input->post('note') ?: 'Rejected';
        $this->agent_model->updateExpenseStatus($id, 'rejected', get_loggedin_user_id(), $note);
        redirect('agents/expenses');
    }

    public function onboarding_requests()
    {
        $status = $this->input->get('status') ?: '';
        $q      = $status ? array('status' => $status) : array();

        $this->db->select('sor.*, CONCAT(a.first_name," ",a.last_name) AS agent_name, sp.name AS plan_name, sp.monthly_price, sp.yearly_price');
        $this->db->from('school_onboarding_requests sor');
        $this->db->join('agent a',               'a.id = sor.agent_id',              'left');
        $this->db->join('subscription_plans sp', 'sp.id = sor.subscription_plan_id', 'left');
        if ($status) $this->db->where('sor.status', $status);
        $this->db->order_by('sor.submitted_at', 'DESC');
        $rows = $this->db->get()->result_array();

        $this->data['requests']  = $rows;
        $this->data['status']    = $status;
        $this->data['pending']   = $this->db->where('status','pending')->count_all_results('school_onboarding_requests');
        $this->data['title']     = 'School Onboarding Requests';
        $this->data['sub_page']  = 'agents/onboarding_requests';
        $this->data['main_menu'] = 'agents';
        $this->load->view('layout/index', $this->data);
    }

    public function update_onboarding($id = '')
    {
        $status = $this->input->post('status');
        $note   = $this->input->post('admin_notes', TRUE);
        $this->db->update('school_onboarding_requests', array('status' => $status, 'admin_notes' => $note), array('id' => $id));
        redirect('agents/onboarding_requests');
    }

    public function complete_setup($id = '')
    {
        $req = $this->db->get_where('school_onboarding_requests', array('id' => $id))->row_array();
        if (!$req || $req['status'] !== 'approved' || !empty($req['setup_completed_at'])) {
            redirect('agents/onboarding_requests');
        }

        $this->db->where('id', $id)->update('school_onboarding_requests', array(
            'setup_completed_at' => date('Y-m-d H:i:s'),
        ));

        // Create commission based on dck_plan assigned to the agent_school
        $school = $this->db->get_where('agent_school', array('id' => $req['agent_school_id']))->row_array();
        if ($school && !empty($school['assigned_plan_id'])) {
            $plan = $this->agent_model->getPlan($school['assigned_plan_id']);
            if ($plan && $plan['commission_amount'] > 0) {
                $this->agent_model->addEarning(array(
                    'agent_id'    => $req['agent_id'],
                    'school_id'   => $req['agent_school_id'],
                    'type'        => 'commission',
                    'amount'      => $plan['commission_amount'],
                    'description' => 'Commission — ' . $req['school_name'] . ' (setup completed)',
                    'status'      => 'pending',
                ));
            }
        }

        $this->session->set_flashdata('msg', 'Setup marked complete. Commission added to agent earnings.');
        redirect('agents/onboarding_requests');
    }

    public function download_filled_form($id = '')
    {
        $req = $this->db->get_where('school_onboarding_requests', array('id' => $id))->row_array();
        if (!$req || empty($req['filled_form_path'])) show_404();

        $file = FCPATH . $req['filled_form_path'];
        if (!file_exists($file)) show_404();

        $this->load->helper('download');
        $ext  = pathinfo($file, PATHINFO_EXTENSION);
        $name = preg_replace('/[^a-zA-Z0-9_-]/', '_', $req['school_name']);
        force_download('filled_form_' . $name . '.' . $ext, file_get_contents($file));
    }
}
