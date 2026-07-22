<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mpesa extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('mpesa_payment');
        if (!is_superadmin_loggedin() && !is_admin_loggedin()) {
            access_denied();
        }
    }

    public function index()
    {
        $branchId = get_loggedin_branch_id();
        $filter   = $this->input->get('status') ?: 'all';
        $from     = $this->input->get('from') ?: date('Y-m-01');
        $to       = $this->input->get('to')   ?: date('Y-m-d');

        $this->db->select('t.*, s.first_name, s.last_name, s.register_no')
            ->from('mpesa_transactions t')
            ->join('student s', 's.id = t.student_id', 'left')
            ->where('t.branch_id', $branchId)
            ->where('DATE(t.created_at) >=', $from)
            ->where('DATE(t.created_at) <=', $to);
        if ($filter !== 'all') {
            $this->db->where('t.status', $filter);
        }
        $this->db->order_by('t.id', 'DESC');
        $transactions = $this->db->get()->result_array();

        $summary = array(
            'total'     => 0,
            'completed' => 0,
            'pending'   => 0,
            'failed'    => 0,
            'amount'    => 0,
        );
        foreach ($transactions as $t) {
            $summary['total']++;
            $summary[$t['status']] = ($summary[$t['status']] ?? 0) + 1;
            if ($t['status'] === 'completed') {
                $summary['amount'] += $t['amount'];
            }
        }

        $this->data['transactions'] = $transactions;
        $this->data['summary']      = $summary;
        $this->data['filter']       = $filter;
        $this->data['from']         = $from;
        $this->data['to']           = $to;
        $this->data['main_menu']    = 'fees';
        $this->data['sub_page']     = 'mpesa/index';
        $this->data['page_title']   = 'M-Pesa Transactions';
        $this->load->view('layout/index', $this->data);
    }

    public function verify_transaction()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $checkoutId = $this->input->post('checkout_request_id');
        $branchId   = get_loggedin_branch_id();

        $tx = $this->db->get_where('mpesa_transactions', array(
            'checkout_request_id' => $checkoutId,
            'branch_id'           => $branchId,
        ))->row_array();

        if (!$tx) {
            echo json_encode(array('status' => 'error', 'message' => 'Transaction not found'));
            return;
        }

        if ($tx['status'] === 'completed') {
            echo json_encode(array('status' => 'already_complete', 'message' => 'Already confirmed.'));
            return;
        }

        $this->mpesa_payment->loadConfig($branchId);
        $result = $this->mpesa_payment->queryStatus($checkoutId);

        if (!$result) {
            echo json_encode(array('status' => 'error', 'message' => 'Could not reach Safaricom API'));
            return;
        }

        $resultCode = isset($result['ResultCode']) ? $result['ResultCode'] : null;

        if ($resultCode === 0 || $resultCode === '0') {
            $this->db->where('id', $tx['id'])
                ->update('mpesa_transactions', array('status' => 'completed', 'result_code' => 0, 'result_desc' => 'Manually verified'));

            if (!empty($tx['allocation_id']) && !empty($tx['type_id'])) {
                $exists = $this->db->where(array(
                    'allocation_id' => $tx['allocation_id'],
                    'type_id'       => $tx['type_id'],
                    'remarks'       => 'M-Pesa: ' . $checkoutId,
                ))->count_all_results('fee_payment_history');
                if ($exists === 0) {
                    $this->db->insert('fee_payment_history', array(
                        'allocation_id' => $tx['allocation_id'],
                        'type_id'       => $tx['type_id'],
                        'collect_by'    => 'online',
                        'amount'        => $tx['amount'],
                        'discount'      => 0,
                        'fine'          => 0,
                        'pay_via'       => 11,
                        'remarks'       => 'M-Pesa: ' . $checkoutId,
                        'date'          => date('Y-m-d'),
                    ));
                }
            }
            echo json_encode(array('status' => 'completed', 'message' => 'Payment confirmed and recorded.'));
        } else {
            $desc = isset($result['ResultDesc']) ? $result['ResultDesc'] : 'Payment not completed yet.';
            echo json_encode(array('status' => 'pending', 'message' => $desc));
        }
    }

    public function reconcile_all()
    {
        $branchId = get_loggedin_branch_id();
        $pending  = $this->db->get_where('mpesa_transactions', array(
            'branch_id' => $branchId,
            'status'    => 'pending',
        ))->result_array();

        if (empty($pending)) {
            echo json_encode(array('reconciled' => 0, 'message' => 'No pending transactions.'));
            return;
        }

        $this->mpesa_payment->loadConfig($branchId);
        $reconciled = 0;

        foreach ($pending as $tx) {
            $result     = $this->mpesa_payment->queryStatus($tx['checkout_request_id']);
            $resultCode = isset($result['ResultCode']) ? (int)$result['ResultCode'] : -1;

            if ($resultCode === 0) {
                $this->db->where('id', $tx['id'])
                    ->update('mpesa_transactions', array('status' => 'completed', 'result_code' => 0));

                if (!empty($tx['allocation_id']) && !empty($tx['type_id'])) {
                    $exists = $this->db->where(array(
                        'allocation_id' => $tx['allocation_id'],
                        'type_id'       => $tx['type_id'],
                        'remarks'       => 'M-Pesa: ' . $tx['checkout_request_id'],
                    ))->count_all_results('fee_payment_history');
                    if ($exists === 0) {
                        $this->db->insert('fee_payment_history', array(
                            'allocation_id' => $tx['allocation_id'],
                            'type_id'       => $tx['type_id'],
                            'collect_by'    => 'online',
                            'amount'        => $tx['amount'],
                            'discount'      => 0,
                            'fine'          => 0,
                            'pay_via'       => 11,
                            'remarks'       => 'M-Pesa: ' . $tx['checkout_request_id'],
                            'date'          => date('Y-m-d'),
                        ));
                    }
                }
                $reconciled++;
            }
        }

        echo json_encode(array(
            'reconciled' => $reconciled,
            'total'      => count($pending),
            'message'    => "Reconciled {$reconciled} of " . count($pending) . " pending transactions.",
        ));
    }
}
