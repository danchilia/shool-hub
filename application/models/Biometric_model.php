<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Biometric_model extends MY_Model
{
    public function generateToken()
    {
        return bin2hex(random_bytes(32));
    }

    public function saveDevice($data)
    {
        $branchId = $this->application_model->get_branch_id();
        $array = array(
            'device_name' => $data['device_name'],
            'device_serial' => isset($data['device_serial']) ? $data['device_serial'] : '',
            'location' => isset($data['location']) ? $data['location'] : '',
            'device_type' => isset($data['device_type']) ? $data['device_type'] : 'both',
            'branch_id' => $branchId,
        );
        if (!isset($data['device_id']) || empty($data['device_id'])) {
            $array['api_token'] = $this->generateToken();
            $this->db->insert('biometric_devices', $array);
            return $this->db->insert_id();
        } else {
            $this->db->where('id', $data['device_id']);
            $this->db->update('biometric_devices', $array);
            return $data['device_id'];
        }
    }

    public function getDevices($branchId)
    {
        return $this->db->where('branch_id', $branchId)->order_by('id', 'DESC')->get('biometric_devices')->result_array();
    }

    public function regenerateToken($id)
    {
        $token = $this->generateToken();
        $this->db->where('id', $id)->update('biometric_devices', array('api_token' => $token));
        return $token;
    }

    public function findDeviceByToken($token)
    {
        return $this->db->where('api_token', $token)->where('is_active', 1)->get('biometric_devices')->row_array();
    }

    public function saveMapping($biometricId, $personType, $personId, $branchId)
    {
        $existing = $this->db->where(array('biometric_id' => $biometricId, 'branch_id' => $branchId))->get('biometric_id_map')->row();
        if ($existing) {
            $this->db->where('id', $existing->id);
            $this->db->update('biometric_id_map', array('person_type' => $personType, 'person_id' => $personId));
        } else {
            $this->db->insert('biometric_id_map', array(
                'biometric_id' => $biometricId, 'person_type' => $personType,
                'person_id' => $personId, 'branch_id' => $branchId,
            ));
        }
    }

    public function getMappings($branchId, $personType = '')
    {
        $this->db->select('m.*');
        $this->db->from('biometric_id_map as m');
        $this->db->where('m.branch_id', $branchId);
        if (!empty($personType)) {
            $this->db->where('m.person_type', $personType);
        }
        $maps = $this->db->get()->result_array();
        foreach ($maps as &$map) {
            if ($map['person_type'] == 'student') {
                $p = $this->db->select('first_name, last_name, register_no')->where('id', $map['person_id'])->get('student')->row();
                $map['person_name'] = $p ? $p->first_name . ' ' . $p->last_name : 'Unknown';
                $map['person_code'] = $p ? $p->register_no : '';
            } else {
                $p = $this->db->select('name, staff_id')->where('id', $map['person_id'])->get('staff')->row();
                $map['person_name'] = $p ? $p->name : 'Unknown';
                $map['person_code'] = $p ? $p->staff_id : '';
            }
        }
        return $maps;
    }

    public function deleteMapping($id, $branchId)
    {
        $this->db->where(array('id' => $id, 'branch_id' => $branchId));
        $this->db->delete('biometric_id_map');
    }

    public function findMapping($biometricId, $branchId)
    {
        return $this->db->where(array('biometric_id' => $biometricId, 'branch_id' => $branchId))->get('biometric_id_map')->row();
    }

    public function recordScan($data)
    {
        $mapping = $this->findMapping($data['biometric_id'], $data['branch_id']);
        $log = array(
            'device_id' => isset($data['device_id']) ? $data['device_id'] : null,
            'biometric_id' => $data['biometric_id'],
            'scan_time' => $data['scan_time'],
            'scan_type' => isset($data['scan_type']) ? $data['scan_type'] : 'unknown',
            'source' => isset($data['source']) ? $data['source'] : 'api_push',
            'branch_id' => $data['branch_id'],
            'matched' => 0,
            'processed' => 0,
        );
        if ($mapping) {
            $log['person_type'] = $mapping->person_type;
            $log['person_id'] = $mapping->person_id;
            $log['matched'] = 1;
        }
        $this->db->insert('biometric_logs', $log);
        $logId = $this->db->insert_id();

        if ($mapping) {
            $this->processLogIntoAttendance($logId, $mapping->person_type, $mapping->person_id, $data['scan_time'], $data['branch_id']);
        }
        return array('matched' => (bool) $mapping, 'log_id' => $logId);
    }

    public function processLogIntoAttendance($logId, $personType, $personId, $scanTime, $branchId)
    {
        $date = date('Y-m-d', strtotime($scanTime));
        $time = date('H:i:s', strtotime($scanTime));

        // Late cutoff: 08:00 AM (configurable later)
        $lateCutoff = '08:00:00';
        $status = ($time > $lateCutoff) ? 'L' : 'P';

        if ($personType == 'student') {
            $existing = $this->db->where(array('student_id' => $personId, 'date' => $date))->get('student_attendance')->row();
            if (!$existing) {
                $this->db->insert('student_attendance', array(
                    'student_id' => $personId, 'date' => $date, 'status' => $status,
                    'remark' => 'Biometric scan at ' . $time, 'branch_id' => $branchId,
                ));
            }
        } else {
            $existing = $this->db->where(array('staff_id' => $personId, 'date' => $date))->get('staff_attendance')->row();
            if (!$existing) {
                $this->db->insert('staff_attendance', array(
                    'staff_id' => $personId, 'date' => $date, 'status' => $status,
                    'remark' => 'Biometric scan at ' . $time, 'branch_id' => $branchId,
                ));
            }
        }

        $this->db->where('id', $logId)->update('biometric_logs', array('processed' => 1));
    }

    public function importCsv($rows, $branchId)
    {
        $imported = 0;
        $unmatched = 0;
        foreach ($rows as $row) {
            if (empty($row['BiometricID']) || empty($row['ScanTime'])) continue;
            $result = $this->recordScan(array(
                'biometric_id' => trim($row['BiometricID']),
                'scan_time' => date('Y-m-d H:i:s', strtotime($row['ScanTime'])),
                'scan_type' => isset($row['Type']) ? strtolower($row['Type']) : 'unknown',
                'source' => 'csv_import',
                'branch_id' => $branchId,
            ));
            if ($result['matched']) {
                $imported++;
            } else {
                $unmatched++;
            }
        }
        return array('imported' => $imported, 'unmatched' => $unmatched);
    }

    public function getRecentLogs($branchId, $limit = 100)
    {
        $this->db->select('l.*, d.device_name, d.location');
        $this->db->from('biometric_logs as l');
        $this->db->join('biometric_devices as d', 'd.id = l.device_id', 'left');
        $this->db->where('l.branch_id', $branchId);
        $this->db->order_by('l.scan_time', 'DESC');
        $this->db->limit($limit);
        $logs = $this->db->get()->result_array();
        foreach ($logs as &$log) {
            if ($log['matched']) {
                if ($log['person_type'] == 'student') {
                    $p = $this->db->select('first_name, last_name')->where('id', $log['person_id'])->get('student')->row();
                    $log['person_name'] = $p ? $p->first_name . ' ' . $p->last_name : 'Unknown';
                } else {
                    $p = $this->db->select('name')->where('id', $log['person_id'])->get('staff')->row();
                    $log['person_name'] = $p ? $p->name : 'Unknown';
                }
            } else {
                $log['person_name'] = null;
            }
        }
        return $logs;
    }

    public function getUnmatchedCount($branchId)
    {
        return $this->db->where(array('branch_id' => $branchId, 'matched' => 0))->count_all_results('biometric_logs');
    }
}
