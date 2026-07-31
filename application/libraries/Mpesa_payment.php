<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mpesa_payment
{
    private $ci;
    public $api_config;
    private $sandbox_url = 'https://sandbox.safaricom.co.ke';
    private $production_url = 'https://api.safaricom.co.ke';

    public function __construct()
    {
        $this->ci = &get_instance();
    }

    public function loadConfig($branchId)
    {
        $this->api_config = $this->ci->db->select('mpesa_consumer_key, mpesa_consumer_secret, mpesa_shortcode, mpesa_passkey, mpesa_sandbox, mpesa_callback_url')
            ->where('branch_id', $branchId)
            ->get('payment_config')->row_array();

        if (!empty($this->api_config)) {
            $this->ci->load->library('encryption');
            foreach (array('mpesa_consumer_key', 'mpesa_consumer_secret', 'mpesa_passkey') as $key) {
                if (!empty($this->api_config[$key])) {
                    $dec = $this->ci->encryption->decrypt($this->api_config[$key]);
                    $this->api_config[$key] = $dec !== false ? $dec : $this->api_config[$key];
                }
            }
        }
    }

    private function getBaseUrl()
    {
        if (isset($this->api_config['mpesa_sandbox']) && $this->api_config['mpesa_sandbox'] == 1) {
            return $this->sandbox_url;
        }
        return $this->production_url;
    }

    public function getAccessToken()
    {
        $url = $this->getBaseUrl() . '/oauth/v1/generate?grant_type=client_credentials';
        $credentials = base64_encode($this->api_config['mpesa_consumer_key'] . ':' . $this->api_config['mpesa_consumer_secret']);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Basic ' . $credentials,
            'Content-Type: application/json',
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response);
        if (isset($result->access_token)) {
            return $result->access_token;
        }
        return false;
    }

    public function stkPush($phone, $amount, $accountRef, $description = 'Fee Payment')
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return array('success' => false, 'message' => 'Failed to get access token');
        }

        $timestamp = date('YmdHis');
        $shortcode = $this->api_config['mpesa_shortcode'];
        $passkey = $this->api_config['mpesa_passkey'];
        $password = base64_encode($shortcode . $passkey . $timestamp);

        $callbackUrl = $this->api_config['mpesa_callback_url'];
        if (empty($callbackUrl)) {
            $callbackUrl = base_url('mpesa-callback/stk');
        }

        $url = $this->getBaseUrl() . '/mpesa/stkpush/v1/processrequest';
        $data = array(
            'BusinessShortCode' => $shortcode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => intval($amount),
            'PartyA' => $phone,
            'PartyB' => $shortcode,
            'PhoneNumber' => $phone,
            'CallBackURL' => $callbackUrl,
            'AccountReference' => $accountRef,
            'TransactionDesc' => $description,
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
        ));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);
        if (isset($result['ResponseCode']) && $result['ResponseCode'] == '0') {
            return array(
                'success' => true,
                'MerchantRequestID' => $result['MerchantRequestID'],
                'CheckoutRequestID' => $result['CheckoutRequestID'],
                'ResponseDescription' => $result['ResponseDescription'],
            );
        }
        return array(
            'success' => false,
            'message' => isset($result['errorMessage']) ? $result['errorMessage'] : 'STK Push failed',
            'response' => $result,
        );
    }

    public function queryStatus($checkoutRequestId)
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return array('success' => false, 'message' => 'Failed to get access token');
        }

        $timestamp = date('YmdHis');
        $shortcode = $this->api_config['mpesa_shortcode'];
        $password = base64_encode($shortcode . $this->api_config['mpesa_passkey'] . $timestamp);

        $url = $this->getBaseUrl() . '/mpesa/stkpushquery/v1/query';
        $data = array(
            'BusinessShortCode' => $shortcode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'CheckoutRequestID' => $checkoutRequestId,
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
        ));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
}
