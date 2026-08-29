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
            $this->form_validation->set_rules('password',   'Password',   'trim|required|min_length[6]');

            if ($this->form_validation->run()) {
                $email    = $this->input->post('email');
                $existing = $this->db->where(array('username' => $email, 'role' => 8))
                                     ->get('login_credential')->num_rows();
                if ($existing > 0) {
                    $this->data['error'] = 'An agent with this email already exists.';
                } else {
                    $this->agent_model->createAgent(array(
                        'first_name' => $this->input->post('first_name'),
                        'last_name'  => $this->input->post('last_name'),
                        'email'      => $email,
                        'phone'      => $this->input->post('phone'),
                        'region'     => $this->input->post('region'),
                        'active'     => 1,
                        'created_by' => get_loggedin_user_id(),
                    ), $this->input->post('password'));
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
        $this->data['title']     = $agent['first_name'] . ' ' . $agent['last_name'];
        $this->data['sub_page']  = 'agents/view';
        $this->data['main_menu'] = 'agents';
        $this->load->view('layout/index', $this->data);
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
}
