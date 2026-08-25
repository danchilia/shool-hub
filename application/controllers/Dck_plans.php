<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dck_plans extends Admin_Controller
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
        $this->data['plans']     = $this->agent_model->getPlans();
        $this->data['title']     = 'DCK Subscription Plans';
        $this->data['sub_page']  = 'dck_plans/index';
        $this->data['main_menu'] = 'agents';
        $this->load->view('layout/index', $this->data);
    }

    public function add()
    {
        if ($this->input->post('save')) {
            $this->form_validation->set_rules('name',              'Plan Name',  'trim|required');
            $this->form_validation->set_rules('price',             'Price',      'trim|required|numeric');
            $this->form_validation->set_rules('visit_fee',         'Visit Fee',  'trim|required|numeric');
            $this->form_validation->set_rules('commission_amount', 'Commission', 'trim|required|numeric');
            if ($this->form_validation->run()) {
                $this->agent_model->savePlan(array(
                    'name'              => $this->input->post('name'),
                    'description'       => $this->input->post('description'),
                    'price'             => (float) $this->input->post('price'),
                    'visit_fee'         => (float) $this->input->post('visit_fee'),
                    'commission_amount' => (float) $this->input->post('commission_amount'),
                    'active'            => $this->input->post('active') ? 1 : 0,
                    'created_by'        => get_loggedin_user_id(),
                ));
                redirect('dck_plans');
            }
        }

        $this->data['title']     = 'Add Plan';
        $this->data['sub_page']  = 'dck_plans/add';
        $this->data['main_menu'] = 'agents';
        $this->load->view('layout/index', $this->data);
    }

    public function edit($id = '')
    {
        $plan = $this->agent_model->getPlan($id);
        if (!$plan) {
            show_404();
        }

        if ($this->input->post('save')) {
            $this->form_validation->set_rules('name', 'Plan Name', 'trim|required');
            if ($this->form_validation->run()) {
                $this->agent_model->updatePlan($id, array(
                    'name'              => $this->input->post('name'),
                    'description'       => $this->input->post('description'),
                    'price'             => (float) $this->input->post('price'),
                    'visit_fee'         => (float) $this->input->post('visit_fee'),
                    'commission_amount' => (float) $this->input->post('commission_amount'),
                    'active'            => $this->input->post('active') ? 1 : 0,
                ));
                redirect('dck_plans');
            }
        }

        $this->data['plan']      = $plan;
        $this->data['title']     = 'Edit Plan: ' . $plan['name'];
        $this->data['sub_page']  = 'dck_plans/edit';
        $this->data['main_menu'] = 'agents';
        $this->load->view('layout/index', $this->data);
    }

    public function toggle($id = '')
    {
        $plan = $this->agent_model->getPlan($id);
        if ($plan) {
            $this->agent_model->updatePlan($id, array('active' => $plan['active'] ? 0 : 1));
        }
        redirect('dck_plans');
    }

    public function delete($id = '')
    {
        $this->agent_model->deletePlan($id);
        redirect('dck_plans');
    }
}
