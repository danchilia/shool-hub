<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mpesa_callback extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('application_model');
    }

    public function stk_callback()
    {
        $callbackJSON = file_get_contents('php://input');
        $callbackData = json_decode($callbackJSON, true);

        if (!isset($callbackData['Body']['stkCallback'])) {
            echo json_encode(array('ResultCode' => 1, 'ResultDesc' => 'Invalid callback data'));
            return;
        }

        $stk = $callbackData['Body']['stkCallback'];
        $merchantRequestId = $stk['MerchantRequestID'];
        $checkoutRequestId = $stk['CheckoutRequestID'];
        $resultCode = $stk['ResultCode'];
        $resultDesc = $stk['ResultDesc'];

        $transaction = $this->db->get_where('mpesa_transactions', array(
            'checkout_request_id' => $checkoutRequestId,
        ))->row();

        if (!$transaction) {
            echo json_encode(array('ResultCode' => 1, 'ResultDesc' => 'Transaction not found'));
            return;
        }

        if ($resultCode == 0) {
            $mpesaReceiptNumber = '';
            $transactionDate = '';
            $phoneNumber = '';

            if (isset($stk['CallbackMetadata']['Item'])) {
                foreach ($stk['CallbackMetadata']['Item'] as $item) {
                    if ($item['Name'] == 'MpesaReceiptNumber') {
                        $mpesaReceiptNumber = $item['Value'];
                    }
                    if ($item['Name'] == 'TransactionDate') {
                        $transactionDate = $item['Value'];
                    }
                    if ($item['Name'] == 'PhoneNumber') {
                        $phoneNumber = $item['Value'];
                    }
                }
            }

            $this->db->where('id', $transaction->id);
            $this->db->update('mpesa_transactions', array(
                'status' => 'completed',
                'transaction_id' => $mpesaReceiptNumber,
                'result_code' => $resultCode,
                'result_desc' => $resultDesc,
            ));

            if (!empty($transaction->allocation_id) && !empty($transaction->type_id)) {
                $feePayment = array(
                    'allocation_id' => $transaction->allocation_id,
                    'type_id' => $transaction->type_id,
                    'collect_by' => 'online',
                    'amount' => $transaction->amount,
                    'discount' => 0,
                    'fine' => 0,
                    'pay_via' => 11,
                    'remarks' => 'M-Pesa: ' . $mpesaReceiptNumber,
                    'date' => date('Y-m-d'),
                );
                $this->db->insert('fee_payment_history', $feePayment);
            }
        } else {
            $status = ($resultCode == 1032) ? 'cancelled' : 'failed';
            $this->db->where('id', $transaction->id);
            $this->db->update('mpesa_transactions', array(
                'status' => $status,
                'result_code' => $resultCode,
                'result_desc' => $resultDesc,
            ));
        }

        echo json_encode(array('ResultCode' => 0, 'ResultDesc' => 'Accepted'));
    }

    public function subscription()
    {
        $callbackJSON = file_get_contents('php://input');
        $callbackData = json_decode($callbackJSON, true);

        if (!isset($callbackData['Body']['stkCallback'])) {
            echo json_encode(array('ResultCode' => 1, 'ResultDesc' => 'Invalid callback data'));
            return;
        }

        $stk               = $callbackData['Body']['stkCallback'];
        $checkoutRequestId = $stk['CheckoutRequestID'];
        $resultCode        = $stk['ResultCode'];
        $resultDesc        = $stk['ResultDesc'];

        $payment = $this->db->get_where('subscription_payments', array(
            'checkout_request_id' => $checkoutRequestId,
        ))->row_array();

        if (!$payment) {
            echo json_encode(array('ResultCode' => 0, 'ResultDesc' => 'Accepted'));
            return;
        }

        if ($resultCode == 0) {
            $mpesaReceipt = '';
            if (isset($stk['CallbackMetadata']['Item'])) {
                foreach ($stk['CallbackMetadata']['Item'] as $item) {
                    if ($item['Name'] === 'MpesaReceiptNumber') {
                        $mpesaReceipt = $item['Value'];
                    }
                }
            }

            $this->db->where('id', $payment['id'])->update('subscription_payments', array(
                'status'        => 'completed',
                'mpesa_receipt' => $mpesaReceipt,
                'result_desc'   => $resultDesc,
                'updated_at'    => date('Y-m-d H:i:s'),
            ));

            // First-time payment: activate the subscription automatically
            if (!empty($payment['plan_id']) && !empty($payment['billing_cycle'])) {
                $this->load->model('subscription_model');
                $this->subscription_model->assignPlan(
                    $payment['branch_id'],
                    $payment['plan_id'],
                    $payment['billing_cycle']
                );
            }

            $this->_createVatInvoice($payment, $mpesaReceipt);
        } else {
            $status = ($resultCode == 1032) ? 'cancelled' : 'failed';
            $this->db->where('id', $payment['id'])->update('subscription_payments', array(
                'status'      => $status,
                'result_desc' => $resultDesc,
                'updated_at'  => date('Y-m-d H:i:s'),
            ));
        }

        echo json_encode(array('ResultCode' => 0, 'ResultDesc' => 'Accepted'));
    }

    private function _createVatInvoice($payment, $mpesaReceipt)
    {
        $totalAmount     = $payment['amount'];
        $amountBeforeVat = round($totalAmount / 1.16, 2);
        $vatAmount       = round($totalAmount - $amountBeforeVat, 2);

        $branch = $this->db->get_where('branch', array('id' => $payment['branch_id']))->row_array();

        $sub = $this->db->select('bs.billing_cycle, sp.name as plan_name')
            ->from('branch_subscriptions bs')
            ->join('subscription_plans sp', 'sp.id = bs.plan_id', 'left')
            ->where('bs.branch_id', $payment['branch_id'])
            ->where_in('bs.status', array('active', 'trial'))
            ->order_by('bs.id', 'DESC')
            ->limit(1)->get()->row_array();

        $seq           = $this->db->count_all('subscription_vat_invoices') + 1;
        $invoiceNumber = 'CST-' . date('Y') . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);

        $this->db->insert('subscription_vat_invoices', array(
            'invoice_number'    => $invoiceNumber,
            'branch_id'         => $payment['branch_id'],
            'payment_id'        => $payment['id'],
            'school_name'       => $branch ? $branch['school_name'] : '',
            'plan_name'         => $sub ? $sub['plan_name'] : '',
            'billing_cycle'     => $sub ? $sub['billing_cycle'] : '',
            'amount_before_vat' => $amountBeforeVat,
            'vat_amount'        => $vatAmount,
            'total_amount'      => $totalAmount,
            'mpesa_receipt'     => $mpesaReceipt,
            'created_at'        => date('Y-m-d H:i:s'),
        ));
    }
}
