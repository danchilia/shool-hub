<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Subscription_payment extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('mpesa_payment');
        $this->load->model('subscription_model');
    }

    // AJAX — school admin initiates STK Push
    public function initiate()
    {
        if (!$this->input->is_ajax_request()) show_404();

        $branchId = get_loggedin_branch_id();
        $rawPhone = $this->input->post('phone');
        $phone    = preg_replace('/\D/', '', $rawPhone);

        if (substr($phone, 0, 1) === '0') {
            $phone = '254' . substr($phone, 1);
        } elseif (substr($phone, 0, 4) === '+254') {
            $phone = '254' . substr($phone, 4);
        } elseif (substr($phone, 0, 3) !== '254') {
            $phone = '254' . $phone;
        }

        if (strlen($phone) !== 12) {
            echo json_encode(array('success' => false, 'message' => 'Enter a valid Kenyan phone number e.g. 0712345678'));
            return;
        }

        $sub = $this->subscription_model->getActiveSubscription($branchId);
        if (!$sub) {
            echo json_encode(array('success' => false, 'message' => 'No active subscription found. Contact support.'));
            return;
        }

        $planPrice   = $sub['billing_cycle'] === 'yearly' ? floatval($sub['yearly_price']) : floatval($sub['monthly_price']);
        $vatAmount   = round($planPrice * 0.16, 2);
        $totalAmount = intval(ceil($planPrice + $vatAmount));

        $schoolName = get_type_name_by_id('branch', $branchId, 'school_name');
        $accountRef = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $schoolName));
        if (strlen($accountRef) > 12) {
            $accountRef = substr($accountRef, 0, 12);
        }
        if (empty($accountRef)) {
            $accountRef = 'SCHOOL' . $branchId;
        }

        $this->mpesa_payment->loadGlobalConfig();
        if (!$this->mpesa_payment->isGlobalConfigValid()) {
            echo json_encode(array('success' => false, 'message' => 'M-Pesa not configured yet. Contact CST SchoolHub support.'));
            return;
        }

        $result = $this->mpesa_payment->stkPush($phone, $totalAmount, $accountRef, 'CST SchoolHub Subscription');

        if ($result['success']) {
            $this->db->insert('subscription_payments', array(
                'branch_id'           => $branchId,
                'phone'               => $phone,
                'amount'              => $totalAmount,
                'checkout_request_id' => $result['CheckoutRequestID'],
                'merchant_request_id' => $result['MerchantRequestID'],
                'status'              => 'pending',
                'created_at'          => date('Y-m-d H:i:s'),
            ));
            echo json_encode(array(
                'success'             => true,
                'checkout_request_id' => $result['CheckoutRequestID'],
                'amount'              => number_format($totalAmount),
            ));
        } else {
            echo json_encode(array('success' => false, 'message' => $result['message']));
        }
    }

    // AJAX — poll for payment status
    public function check()
    {
        if (!$this->input->is_ajax_request()) show_404();

        $checkoutId = $this->input->post('checkout_request_id');
        $branchId   = get_loggedin_branch_id();

        $payment = $this->db->get_where('subscription_payments', array(
            'checkout_request_id' => $checkoutId,
            'branch_id'           => $branchId,
        ))->row_array();

        if (!$payment) {
            echo json_encode(array('status' => 'error'));
            return;
        }

        if ($payment['status'] === 'completed') {
            // Check if superadmin has activated the subscription yet
            $active = $this->subscription_model->getActiveSubscription($branchId);
            echo json_encode(array('status' => $active ? 'activated' : 'awaiting'));
            return;
        }

        echo json_encode(array('status' => $payment['status']));
    }

    // View / print invoice
    public function invoice($id = '')
    {
        $branchId = get_loggedin_branch_id();
        $where    = array('id' => (int) $id);
        if (!is_superadmin_loggedin()) {
            $where['branch_id'] = $branchId;
        }

        $invoice = $this->db->get_where('subscription_vat_invoices', $where)->row_array();
        if (!$invoice) show_404();

        $settings = $this->db->get_where('global_settings', array('id' => 1))->row_array();
        $branch   = $this->db->get_where('branch', array('id' => $invoice['branch_id']))->row_array();

        $this->data['invoice']   = $invoice;
        $this->data['settings']  = $settings;
        $this->data['branch']    = $branch;
        $this->data['title']     = 'Invoice ' . $invoice['invoice_number'];
        $this->data['sub_page']  = 'subscription/invoice';
        $this->data['main_menu'] = 'settings';
        $this->load->view('layout/index', $this->data);
    }

    // Superadmin: all payments log
    public function payments()
    {
        if (!is_superadmin_loggedin()) access_denied();

        $from = $this->input->get('from') ?: date('Y-m-01');
        $to   = $this->input->get('to')   ?: date('Y-m-d');

        $this->db->select('sp.*, b.school_name, b.name as branch_name, i.invoice_number, i.id as invoice_id');
        $this->db->from('subscription_payments sp');
        $this->db->join('branch b', 'b.id = sp.branch_id', 'left');
        $this->db->join('subscription_vat_invoices i', 'i.payment_id = sp.id', 'left');
        $this->db->where('DATE(sp.created_at) >=', $from);
        $this->db->where('DATE(sp.created_at) <=', $to);
        $this->db->order_by('sp.created_at', 'DESC');
        $payments = $this->db->get()->result_array();

        $totalCollected = array_sum(array_column(
            array_filter($payments, function($p) { return $p['status'] === 'completed'; }),
            'amount'
        ));

        $this->data['payments']       = $payments;
        $this->data['total_collected'] = $totalCollected;
        $this->data['from']           = $from;
        $this->data['to']             = $to;
        $this->data['title']          = 'Subscription Payments';
        $this->data['sub_page']       = 'subscription/payments';
        $this->data['main_menu']      = 'settings';
        $this->load->view('layout/index', $this->data);
    }

    // First-time: school admin picks a plan and pays
    public function choose_plan()
    {
        $branchId = get_loggedin_branch_id();
        $existing = $this->subscription_model->getActiveSubscription($branchId);
        if ($existing) {
            redirect(base_url('dashboard'));
            return;
        }
        // Paid but awaiting superadmin activation
        $pending = $this->db
            ->where('branch_id', $branchId)
            ->where('status', 'completed')
            ->where('plan_id IS NOT NULL', null, false)
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get('subscription_payments')->row_array();
        if ($pending) {
            $this->data['pending']   = $pending;
            $this->data['title']     = 'Payment Received';
            $this->data['sub_page']  = 'subscription/awaiting_activation';
            $this->data['main_menu'] = 'dashboard';
            $this->load->view('layout/index', $this->data);
            return;
        }
        $plans = $this->subscription_model->getPlans(true);
        $this->data['plans']     = $plans;
        $this->data['title']     = 'Activate Your School Account';
        $this->data['sub_page']  = 'subscription/choose_plan';
        $this->data['main_menu'] = 'dashboard';
        $this->load->view('layout/index', $this->data);
    }

    // Non-admin employees see this when school subscription is inactive
    public function inactive()
    {
        $this->data['title']     = 'School Subscription Inactive';
        $this->data['sub_page']  = 'subscription/inactive_notice';
        $this->data['main_menu'] = 'dashboard';
        $this->load->view('layout/index', $this->data);
    }

    // AJAX — first-time STK Push with plan selection
    public function initiate_plan()
    {
        if (!$this->input->is_ajax_request()) show_404();

        $branchId = get_loggedin_branch_id();
        $planId   = intval($this->input->post('plan_id'));
        $billing  = $this->input->post('billing_cycle') === 'yearly' ? 'yearly' : 'monthly';
        $rawPhone = $this->input->post('phone');
        $phone    = preg_replace('/\D/', '', $rawPhone);

        if (substr($phone, 0, 1) === '0') {
            $phone = '254' . substr($phone, 1);
        } elseif (substr($phone, 0, 4) === '+254') {
            $phone = '254' . substr($phone, 4);
        } elseif (substr($phone, 0, 3) !== '254') {
            $phone = '254' . $phone;
        }

        if (strlen($phone) !== 12) {
            echo json_encode(array('success' => false, 'message' => 'Enter a valid Kenyan phone number e.g. 0712345678'));
            return;
        }

        $plan = $this->db->get_where('subscription_plans', array('id' => $planId, 'is_active' => 1))->row_array();
        if (!$plan) {
            echo json_encode(array('success' => false, 'message' => 'Invalid plan selected.'));
            return;
        }

        $planPrice   = ($billing === 'yearly') ? floatval($plan['yearly_price']) : floatval($plan['monthly_price']);
        $vatAmount   = round($planPrice * 0.16, 2);
        $totalAmount = intval(ceil($planPrice + $vatAmount));

        $schoolName = get_type_name_by_id('branch', $branchId, 'school_name');
        $accountRef = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $schoolName));
        if (strlen($accountRef) > 12) $accountRef = substr($accountRef, 0, 12);
        if (empty($accountRef)) $accountRef = 'SCHOOL' . $branchId;

        $this->mpesa_payment->loadGlobalConfig();
        if (!$this->mpesa_payment->isGlobalConfigValid()) {
            echo json_encode(array('success' => false, 'message' => 'M-Pesa not configured. Contact CST SchoolHub support.'));
            return;
        }

        $result = $this->mpesa_payment->stkPush($phone, $totalAmount, $accountRef, 'CST SchoolHub Subscription');

        if ($result['success']) {
            $this->db->insert('subscription_payments', array(
                'branch_id'           => $branchId,
                'plan_id'             => $planId,
                'billing_cycle'       => $billing,
                'phone'               => $phone,
                'amount'              => $totalAmount,
                'checkout_request_id' => $result['CheckoutRequestID'],
                'merchant_request_id' => $result['MerchantRequestID'],
                'status'              => 'pending',
                'created_at'          => date('Y-m-d H:i:s'),
            ));
            echo json_encode(array(
                'success'             => true,
                'checkout_request_id' => $result['CheckoutRequestID'],
                'amount'              => number_format($totalAmount),
                'plan_name'           => $plan['name'],
            ));
        } else {
            echo json_encode(array('success' => false, 'message' => $result['message']));
        }
    }

    // My invoices — school admin
    public function my_invoices()
    {
        $branchId = get_loggedin_branch_id();
        $invoices = $this->db->order_by('created_at', 'DESC')
            ->get_where('subscription_vat_invoices', array('branch_id' => $branchId))
            ->result_array();

        $this->data['invoices']  = $invoices;
        $this->data['title']     = 'My Subscription Invoices';
        $this->data['sub_page']  = 'subscription/my_invoices';
        $this->data['main_menu'] = 'settings';
        $this->load->view('layout/index', $this->data);
    }
}
