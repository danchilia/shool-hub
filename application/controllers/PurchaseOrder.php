<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PurchaseOrder extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('lpo_model');
    }

    public function index()
    {
        if (!get_permission('purchase_orders', 'is_view')) { access_denied(); }
        $branchID = $this->application_model->get_branch_id();
        $status = $this->input->get('status');
        $this->data['orders'] = $this->lpo_model->getPurchaseOrders($branchID, $status, get_session_id());
        $this->data['summary'] = $this->lpo_model->getLpoSummary($branchID, get_session_id());
        $this->data['current_status'] = $status;
        $this->data['branch_id'] = $branchID;
        $this->data['title'] = 'Purchase Orders (LPO)';
        $this->data['sub_page'] = 'purchase_order/index';
        $this->data['main_menu'] = 'accounting';
        $this->load->view('layout/index', $this->data);
    }

    public function create()
    {
        if (!get_permission('purchase_orders', 'is_add')) { access_denied(); }
        $branchID = $this->application_model->get_branch_id();
        if (isset($_POST['save'])) {
            $this->form_validation->set_rules('supplier_id', 'Supplier', 'required');
            $this->form_validation->set_rules('order_date', 'Order Date', 'required');
            if ($this->form_validation->run() == true) {
                $post = $this->input->post();
                $items = isset($post['items']) ? $post['items'] : array();
                $poId = $this->lpo_model->savePurchaseOrder($post, $items);
                set_alert('success', 'Purchase Order created successfully.');
                redirect(base_url('purchase-orders/view/' . $poId));
            }
        }
        $this->data['lpo_number'] = $this->lpo_model->generateLpoNumber($branchID);
        $this->data['suppliers'] = $this->lpo_model->getSuppliers($branchID);
        $this->data['voucher_heads'] = $this->db->where('branch_id', $branchID)->where('type', 'expense')->get('voucher_head')->result_array();
        $this->data['branch_id'] = $branchID;
        $this->data['title'] = 'Create Purchase Order';
        $this->data['sub_page'] = 'purchase_order/create';
        $this->data['main_menu'] = 'accounting';
        $this->load->view('layout/index', $this->data);
    }

    public function view($id = '')
    {
        if (!get_permission('purchase_orders', 'is_view')) { access_denied(); }
        $order = $this->lpo_model->getPurchaseOrder($id);
        if (!$order) { show_404(); }
        $branchID = $this->application_model->get_branch_id();
        $this->data['order'] = $order;
        $this->data['items'] = $this->lpo_model->getOrderItems($id);
        $this->data['accounts'] = $this->db->where('branch_id', $branchID)->get('accounts')->result_array();
        $this->data['title'] = 'LPO: ' . $order['lpo_number'];
        $this->data['sub_page'] = 'purchase_order/view';
        $this->data['main_menu'] = 'accounting';
        $this->load->view('layout/index', $this->data);
    }

    public function submit_for_approval($id = '')
    {
        if (!get_permission('purchase_orders', 'is_add')) { access_denied(); }
        $this->lpo_model->updateStatus($id, 'pending_approval');
        set_alert('success', 'LPO submitted for approval.');
        redirect(base_url('purchase-orders/view/' . $id));
    }

    public function approve($id = '')
    {
        if (!get_permission('lpo_approval', 'is_add')) { access_denied(); }
        $this->lpo_model->updateStatus($id, 'approved', array(
            'approved_by' => get_loggedin_user_id(), 'approved_at' => date('Y-m-d H:i:s'),
        ));
        set_alert('success', 'Purchase Order approved.');
        redirect(base_url('purchase-orders/view/' . $id));
    }

    public function reject($id = '')
    {
        if (!get_permission('lpo_approval', 'is_add')) { access_denied(); }
        $this->lpo_model->updateStatus($id, 'cancelled', array(
            'approved_by' => get_loggedin_user_id(), 'approved_at' => date('Y-m-d H:i:s'),
            'rejection_reason' => $this->input->post('rejection_reason'),
        ));
        set_alert('success', 'Purchase Order rejected.');
        redirect(base_url('purchase-orders'));
    }

    public function mark_sent($id = '')
    {
        $this->lpo_model->updateStatus($id, 'sent');
        set_alert('success', 'LPO marked as sent to supplier.');
        redirect(base_url('purchase-orders/view/' . $id));
    }

    public function send_email($id = '')
    {
        if (!get_permission('purchase_orders', 'is_edit')) { access_denied(); }
        $order = $this->lpo_model->getPurchaseOrder($id);
        if (!$order || empty($order['supplier_email'])) {
            set_alert('error', 'Supplier has no email address. Add email in Suppliers page first.');
            redirect(base_url('purchase-orders/view/' . $id));
        }

        $branchId = $order['branch_id'];
        $getConfig = $this->db->get_where('email_config', array('branch_id' => $branchId))->row_array();
        if (empty($getConfig) || empty($getConfig['smtp_host'])) {
            set_alert('error', 'Email not configured. Set up in School Settings > Email Config first.');
            redirect(base_url('purchase-orders/view/' . $id));
        }

        $items = $this->lpo_model->getOrderItems($id);
        $currency = $this->data['global_config']['currency_symbol'];

        $itemRows = '';
        $n = 1;
        foreach ($items as $item) {
            $itemRows .= '<tr><td>' . $n++ . '</td><td>' . $item['item_name'] . '</td><td>' . $item['unit'] . '</td><td>' . intval($item['quantity']) . '</td><td style="text-align:right;">' . number_format($item['unit_price'], 2) . '</td><td style="text-align:right;">' . number_format($item['total_price'], 2) . '</td></tr>';
        }

        $message = '<div style="font-family:Arial,sans-serif; max-width:700px; margin:0 auto;">';
        $message .= '<div style="background:#1a5276; color:#fff; padding:15px 20px;"><h2 style="margin:0;">' . $order['school_name'] . '</h2><p style="margin:2px 0;">' . $order['school_address'] . ' | Tel: ' . $order['school_phone'] . '</p></div>';
        $message .= '<div style="background:#eaf2f8; padding:10px; text-align:center; border-bottom:2px solid #1a5276;"><h3 style="margin:0; color:#1a5276;">LOCAL PURCHASE ORDER</h3></div>';
        $message .= '<div style="padding:15px;">';
        $message .= '<p><strong>LPO Number:</strong> ' . $order['lpo_number'] . '</p>';
        $message .= '<p><strong>Date:</strong> ' . $order['order_date'] . '</p>';
        $message .= '<p><strong>Valid Until:</strong> ' . ($order['valid_until'] ?: '-') . '</p>';
        $message .= '<p><strong>Delivery Date:</strong> ' . ($order['delivery_date'] ?: '-') . '</p>';
        $message .= '<p><strong>Delivery Address:</strong> ' . $order['delivery_address'] . '</p>';
        $message .= '<table style="width:100%; border-collapse:collapse; margin-top:10px;">';
        $message .= '<thead><tr style="background:#1a5276; color:#fff;"><th style="padding:8px; border:1px solid #1a5276;">#</th><th style="padding:8px; border:1px solid #1a5276;">Item</th><th style="padding:8px; border:1px solid #1a5276;">Unit</th><th style="padding:8px; border:1px solid #1a5276;">Qty</th><th style="padding:8px; border:1px solid #1a5276;">Price</th><th style="padding:8px; border:1px solid #1a5276;">Total (KES)</th></tr></thead>';
        $message .= '<tbody>' . $itemRows . '</tbody>';
        $message .= '<tfoot><tr><td colspan="5" style="text-align:right; padding:8px; border:1px solid #ccc; font-weight:bold;">TOTAL:</td><td style="text-align:right; padding:8px; border:1px solid #ccc; font-weight:bold;">KES ' . number_format($order['total_amount'], 2) . '</td></tr></tfoot>';
        $message .= '</table>';
        if (!empty($order['notes'])) {
            $message .= '<p style="margin-top:10px;"><strong>Notes:</strong> ' . $order['notes'] . '</p>';
        }
        $message .= '<p style="margin-top:15px;">Approved by: ' . ($order['approved_by_name'] ?: '-') . '</p>';
        $message .= '<hr><p style="font-size:11px; color:#999;">This is a computer-generated LPO from ' . $order['school_name'] . ' | Powered by DCK Solutions</p>';
        $message .= '</div></div>';

        $config = array(
            'protocol' => 'smtp', 'smtp_host' => trim($getConfig['smtp_host']),
            'smtp_port' => trim($getConfig['smtp_port']), 'smtp_user' => trim($getConfig['smtp_user']),
            'smtp_pass' => trim($getConfig['smtp_pass']), 'smtp_crypto' => $getConfig['smtp_encryption'],
            'mailtype' => 'html', 'charset' => 'utf-8', 'newline' => "\r\n",
        );

        $this->load->library('email');
        $this->email->initialize($config);
        $this->email->from($getConfig['smtp_user'], $order['school_name']);
        $this->email->to($order['supplier_email']);
        $this->email->subject('Purchase Order: ' . $order['lpo_number'] . ' - ' . $order['school_name']);
        $this->email->message($message);

        if ($this->email->send()) {
            $this->lpo_model->updateStatus($id, 'sent');
            set_alert('success', 'LPO sent to supplier (' . $order['supplier_email'] . ') via email and marked as Sent.');
        } else {
            set_alert('error', 'Failed to send email. Check School Settings > Email Config.');
        }
        redirect(base_url('purchase-orders/view/' . $id));
    }

    public function mark_delivered($id = '')
    {
        $this->lpo_model->updateStatus($id, 'delivered', array(
            'received_by' => get_loggedin_user_id(), 'received_at' => date('Y-m-d H:i:s'),
        ));
        set_alert('success', 'Goods marked as delivered.');
        redirect(base_url('purchase-orders/view/' . $id));
    }

    public function mark_paid($id = '')
    {
        if ($_POST) {
            $order = $this->lpo_model->getPurchaseOrder($id);
            $this->lpo_model->updateStatus($id, 'paid', array(
                'paid_by' => get_loggedin_user_id(), 'payment_date' => date('Y-m-d'),
                'payment_reference' => $this->input->post('payment_reference'),
                'payment_method' => $this->input->post('payment_method'),
                'account_id' => $this->input->post('account_id'),
            ));
            if (!empty($order['voucher_head_id']) && !empty($this->input->post('account_id'))) {
                $this->db->insert('transactions', array(
                    'account_id' => $this->input->post('account_id'), 'voucher_head_id' => $order['voucher_head_id'],
                    'type' => 'expense', 'amount' => $order['total_amount'], 'dr' => $order['total_amount'], 'cr' => 0,
                    'date' => date('Y-m-d'), 'pay_via' => $this->input->post('payment_method'),
                    'description' => 'LPO Payment: ' . $order['lpo_number'] . ' - ' . $order['supplier_name'],
                    'ref' => $order['lpo_number'], 'branch_id' => $order['branch_id'],
                ));
            }
            set_alert('success', 'LPO marked as paid. Expense recorded.');
            redirect(base_url('purchase-orders/view/' . $id));
        }
    }

    public function cancel($id = '')
    {
        $this->lpo_model->updateStatus($id, 'cancelled');
        set_alert('success', 'Purchase Order cancelled.');
        redirect(base_url('purchase-orders'));
    }

    public function print_lpo($id = '')
    {
        $this->data['order'] = $this->lpo_model->getPurchaseOrder($id);
        $this->data['items'] = $this->lpo_model->getOrderItems($id);
        $this->load->view('purchase_order/print_lpo', $this->data);
    }

    public function suppliers()
    {
        if (!get_permission('suppliers', 'is_view')) { access_denied(); }
        $branchID = $this->application_model->get_branch_id();
        if (isset($_POST['save'])) {
            $this->form_validation->set_rules('name', 'Supplier Name', 'required');
            $this->form_validation->set_rules('phone', 'Phone', 'required');
            if ($this->form_validation->run() !== false) {
                $this->lpo_model->saveSupplier($this->input->post());
                set_alert('success', 'Supplier saved.');
                redirect(base_url('purchase-orders/suppliers'));
            }
        }
        $this->data['suppliers'] = $this->lpo_model->getSuppliers($branchID);
        $this->data['branch_id'] = $branchID;
        $this->data['title'] = 'Supplier Directory';
        $this->data['sub_page'] = 'purchase_order/suppliers';
        $this->data['main_menu'] = 'accounting';
        $this->load->view('layout/index', $this->data);
    }

    public function supplier_delete($id = '')
    {
        if (get_permission('suppliers', 'is_delete')) {
            if (!is_superadmin_loggedin()) { $this->db->where('branch_id', get_loggedin_branch_id()); }
            $this->db->where('id', $id)->delete('suppliers');
        }
    }
}
