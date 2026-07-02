<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Public endpoint - biometric devices push scan data here.
// No login required; secured by per-device API token instead.
class Biometric_api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('biometric_model');
        $this->load->model('application_model');
    }

    // Generic push endpoint. Accepts JSON or form POST:
    // { "token": "device_token", "biometric_id": "23", "scan_time": "2026-06-29 07:45:00", "scan_type": "in" }
    public function push()
    {
        header('Content-Type: application/json');
        $raw = file_get_contents('php://input');
        $json = json_decode($raw, true);
        $input = is_array($json) ? $json : $this->input->post();

        $token = isset($input['token']) ? $input['token'] : $this->input->get('token');
        $biometricId = isset($input['biometric_id']) ? trim($input['biometric_id']) : '';
        $scanTime = isset($input['scan_time']) ? $input['scan_time'] : date('Y-m-d H:i:s');
        $scanType = isset($input['scan_type']) ? strtolower($input['scan_type']) : 'unknown';

        if (empty($token) || empty($biometricId)) {
            echo json_encode(array('status' => 'error', 'message' => 'Missing token or biometric_id'));
            return;
        }

        $device = $this->biometric_model->findDeviceByToken($token);
        if (!$device) {
            echo json_encode(array('status' => 'error', 'message' => 'Invalid or inactive device token'));
            return;
        }

        $this->db->where('id', $device['id'])->update('biometric_devices', array('last_seen' => date('Y-m-d H:i:s')));

        $result = $this->biometric_model->recordScan(array(
            'device_id' => $device['id'],
            'biometric_id' => $biometricId,
            'scan_time' => date('Y-m-d H:i:s', strtotime($scanTime)),
            'scan_type' => in_array($scanType, array('in', 'out')) ? $scanType : 'unknown',
            'source' => 'api_push',
            'branch_id' => $device['branch_id'],
        ));

        echo json_encode(array(
            'status' => 'success',
            'matched' => $result['matched'],
            'message' => $result['matched'] ? 'Attendance recorded' : 'Scan logged but biometric ID not mapped to any student/staff',
        ));
    }

    // Devices can ping this to check connectivity / token validity
    public function ping()
    {
        header('Content-Type: application/json');
        $token = $this->input->get('token');
        $device = $this->biometric_model->findDeviceByToken($token);
        if ($device) {
            $this->db->where('id', $device['id'])->update('biometric_devices', array('last_seen' => date('Y-m-d H:i:s')));
            echo json_encode(array('status' => 'success', 'device_name' => $device['device_name']));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Invalid token'));
        }
    }
}
