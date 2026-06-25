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
}
