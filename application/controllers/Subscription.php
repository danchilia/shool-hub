<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Subscription extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!is_superadmin_loggedin()) {
            redirect(base_url('dashboard'));
        }
        $this->load->model('subscription_model');
    }

    public function index()
    {
        $this->data['subscriptions']        = $this->subscription_model->getAllSubscriptions();
        $this->data['unsubscribed_branches'] = $this->subscription_model->getUnsubscribedBranches();
        $this->data['plans']                = $this->subscription_model->getPlans(true);
        $this->data['title']                = 'Branch Subscriptions';
        $this->data['sub_page']             = 'subscription/index';
        $this->data['main_menu']            = 'subscription';
        $this->load->view('layout/index', $this->data);
    }

    public function manual_activate()
    {
        if ($_POST) {
            $branchId = intval($this->input->post('branch_id'));
            $planId   = intval($this->input->post('plan_id'));
            $billing  = $this->input->post('billing_cycle') === 'yearly' ? 'yearly' : 'monthly';
            $this->subscription_model->assignPlan($branchId, $planId, $billing);
            set_alert('success', 'School subscription manually activated.');
        }
        redirect(base_url('subscription'));
    }

    public function plans()
    {
        if (isset($_POST['save'])) {
            $this->form_validation->set_rules('name', translate('name'), 'trim|required');
            $this->form_validation->set_rules('max_students', 'Max Students', 'trim|required|numeric');
            $this->form_validation->set_rules('max_staff', 'Max Staff', 'trim|required|numeric');
            $this->form_validation->set_rules('monthly_price', 'Monthly Price', 'trim|required|numeric');
            $this->form_validation->set_rules('yearly_price', 'Yearly Price', 'trim|required|numeric');
            if ($this->form_validation->run() !== false) {
                $this->subscription_model->savePlan($this->input->post());
                set_alert('success', translate('information_has_been_saved_successfully'));
                redirect(current_url());
            }
        }
        $this->data['plans'] = $this->subscription_model->getPlans();
        $this->data['title'] = 'Subscription Plans';
        $this->data['sub_page'] = 'subscription/plans';
        $this->data['main_menu'] = 'subscription';
        $this->load->view('layout/index', $this->data);
    }

    public function plan_edit()
    {
        if ($_POST) {
            $this->form_validation->set_rules('name', translate('name'), 'trim|required');
            $this->form_validation->set_rules('max_students', 'Max Students', 'trim|required|numeric');
            $this->form_validation->set_rules('max_staff', 'Max Staff', 'trim|required|numeric');
            $this->form_validation->set_rules('monthly_price', 'Monthly Price', 'trim|required|numeric');
            $this->form_validation->set_rules('yearly_price', 'Yearly Price', 'trim|required|numeric');
            if ($this->form_validation->run() !== false) {
                $this->subscription_model->savePlan($this->input->post());
                set_alert('success', translate('information_has_been_updated_successfully'));
                $array = array('status' => 'success', 'url' => base_url('subscription/plans'));
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
        }
    }

    public function plan_delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('subscription_plans');
    }

    public function assign()
    {
        if ($_POST) {
            $this->form_validation->set_rules('branch_id', translate('branch'), 'trim|required');
            $this->form_validation->set_rules('plan_id', 'Plan', 'trim|required');
            $this->form_validation->set_rules('billing_cycle', 'Billing Cycle', 'trim|required');
            if ($this->form_validation->run() !== false) {
                $this->subscription_model->assignPlan(
                    $this->input->post('branch_id'),
                    $this->input->post('plan_id'),
                    $this->input->post('billing_cycle')
                );
                set_alert('success', 'Subscription assigned successfully.');
                redirect(base_url('subscription'));
            }
        }
        $this->data['branches'] = $this->db->get('branch')->result_array();
        $this->data['plans'] = $this->subscription_model->getPlans(true);
        $this->data['title'] = 'Assign Subscription';
        $this->data['sub_page'] = 'subscription/assign';
        $this->data['main_menu'] = 'subscription';
        $this->load->view('layout/index', $this->data);
    }

    public function invoices()
    {
        $this->data['invoices'] = $this->subscription_model->getInvoices();
        $this->data['title'] = 'Subscription Invoices';
        $this->data['sub_page'] = 'subscription/invoices';
        $this->data['main_menu'] = 'subscription';
        $this->load->view('layout/index', $this->data);
    }

    public function invoice_pay()
    {
        if ($_POST) {
            $invoiceId = $this->input->post('invoice_id');
            $reference = $this->input->post('payment_reference');
            $this->subscription_model->markInvoicePaid($invoiceId, $reference);
            set_alert('success', 'Invoice marked as paid.');
            $array = array('status' => 'success', 'url' => base_url('subscription/invoices'));
            echo json_encode($array);
        }
    }

    public function activate($id = '')
    {
        $this->db->where('id', $id);
        $this->db->update('branch_subscriptions', array('status' => 'active'));
        set_alert('success', 'School subscription activated successfully.');
        redirect(base_url('subscription'));
    }

    public function deactivate($id = '')
    {
        $this->db->where('id', $id);
        $this->db->update('branch_subscriptions', array('status' => 'expired'));
        set_alert('success', 'School subscription deactivated.');
        redirect(base_url('subscription'));
    }

    public function extend($id = '')
    {
        if ($_POST) {
            $days = intval($this->input->post('extend_days'));
            $sub = $this->db->get_where('branch_subscriptions', array('id' => $id))->row();
            if ($sub) {
                $currentEnd = strtotime($sub->end_date) > time() ? $sub->end_date : date('Y-m-d');
                $newEnd = date('Y-m-d', strtotime($currentEnd . ' +' . $days . ' days'));
                $this->db->where('id', $id);
                $this->db->update('branch_subscriptions', array('end_date' => $newEnd, 'status' => 'active'));
                set_alert('success', 'Subscription extended to ' . $newEnd);
            }
            redirect(base_url('subscription'));
        }
    }

    public function payment_settings()
    {
        if ($_POST) {
            $this->db->where('id', 1);
            $this->db->update('global_settings', array(
                'paybill_number' => $this->input->post('paybill_number'),
                'account_info' => $this->input->post('account_info'),
            ));
            set_alert('success', 'Payment settings updated.');
            redirect(base_url('subscription/payment_settings'));
        }
        $this->data['settings'] = $this->db->get_where('global_settings', array('id' => 1))->row();
        $this->data['title'] = 'Subscription Payment Settings';
        $this->data['sub_page'] = 'subscription/payment_settings';
        $this->data['main_menu'] = 'subscription';
        $this->load->view('layout/index', $this->data);
    }
}
