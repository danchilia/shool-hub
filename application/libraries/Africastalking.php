<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Africastalking
{
    private $username;
    private $api_key;
    private $sender_id;

    public function __construct()
    {
        $ci = &get_instance();
        if (is_superadmin_loggedin()) {
            $branchID = $ci->input->post('branch_id');
        } else {
            $branchID = get_loggedin_branch_id();
        }
        $config = $ci->db->get_where('sms_credential', array('sms_api_id' => 6, 'branch_id' => $branchID))->row_array();
        if ($config) {
            $this->username = $config['field_one'];
            $this->api_key = $config['field_two'];
            $this->sender_id = isset($config['field_three']) ? $config['field_three'] : '';
        }
    }

    public function sendSms($numbers, $message)
    {
        if (empty($this->api_key) || empty($this->username)) {
            return false;
        }

        $recipients = is_array($numbers) ? implode(',', $numbers) : $numbers;
        $url = 'https://api.africastalking.com/version1/messaging';

        $data = array(
            'username' => $this->username,
            'to' => $recipients,
            'message' => $message,
        );
        if (!empty($this->sender_id)) {
            $data['from'] = $this->sender_id;
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'apiKey: ' . $this->api_key,
            'Accept: application/json',
            'Content-Type: application/x-www-form-urlencoded',
        ));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
}
