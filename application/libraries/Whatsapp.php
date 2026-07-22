<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * WhatsApp Business Cloud API (Meta)
 * Sends template/text messages via the Graph API.
 * Credentials stored in sms_credential with sms_api_id = 7.
 *   field_one  = Phone Number ID (from Meta Developer Console)
 *   field_two  = Permanent Access Token
 *   field_three = WhatsApp Business Account ID (optional, for reference)
 */
class Whatsapp
{
    private $phoneNumberId;
    private $accessToken;
    private $apiVersion = 'v19.0';

    public function __construct()
    {
        $ci       = &get_instance();
        $branchID = is_superadmin_loggedin()
            ? ($ci->input->post('branch_id') ?: get_loggedin_branch_id())
            : get_loggedin_branch_id();

        $config = $ci->db->get_where('sms_credential', array(
            'sms_api_id' => 7,
            'branch_id'  => $branchID,
        ))->row_array();

        if ($config) {
            $this->phoneNumberId = $config['field_one'];
            $this->accessToken   = $config['field_two'];
        }
    }

    /**
     * Send a plain text message (free-form, only within 24h service window).
     */
    public function sendText($to, $message)
    {
        if (empty($this->phoneNumberId) || empty($this->accessToken)) {
            return array('success' => false, 'message' => 'WhatsApp not configured');
        }

        $phone = $this->_formatPhone($to);
        $url   = "https://graph.facebook.com/{$this->apiVersion}/{$this->phoneNumberId}/messages";

        $payload = array(
            'messaging_product' => 'whatsapp',
            'to'                => $phone,
            'type'              => 'text',
            'text'              => array('body' => $message),
        );

        return $this->_post($url, $payload);
    }

    /**
     * Send a template message (works outside 24h window — required for notifications).
     * $components: array of parameter objects per Meta docs.
     */
    public function sendTemplate($to, $templateName, $languageCode = 'en', $components = array())
    {
        if (empty($this->phoneNumberId) || empty($this->accessToken)) {
            return array('success' => false, 'message' => 'WhatsApp not configured');
        }

        $phone = $this->_formatPhone($to);
        $url   = "https://graph.facebook.com/{$this->apiVersion}/{$this->phoneNumberId}/messages";

        $template = array(
            'name'     => $templateName,
            'language' => array('code' => $languageCode),
        );
        if (!empty($components)) {
            $template['components'] = $components;
        }

        $payload = array(
            'messaging_product' => 'whatsapp',
            'to'                => $phone,
            'type'              => 'template',
            'template'          => $template,
        );

        return $this->_post($url, $payload);
    }

    private function _post($url, $payload)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $this->accessToken,
            'Content-Type: application/json',
        ));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $decoded = json_decode($response, true);
        $success = ($httpCode >= 200 && $httpCode < 300 && !isset($decoded['error']));
        return array(
            'success'  => $success,
            'message'  => $success ? 'Sent' : ($decoded['error']['message'] ?? 'Unknown error'),
            'response' => $decoded,
        );
    }

    private function _formatPhone($phone)
    {
        // Remove spaces, dashes, parentheses
        $phone = preg_replace('/[\s\-\(\)]+/', '', $phone);
        // Kenya: convert 07x/01x to 2547x/2541x
        if (preg_match('/^0([7|1]\d{8})$/', $phone, $m)) {
            $phone = '254' . $m[1];
        }
        // Strip leading + if present
        $phone = ltrim($phone, '+');
        return $phone;
    }
}
