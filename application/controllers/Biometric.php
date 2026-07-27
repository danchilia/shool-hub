<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Biometric extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('biometric_model');
    }

    public function devices()
    {
        if (!get_permission('biometric_devices', 'is_view')) { access_denied(); }
        $branchID = $this->application_model->get_branch_id();
        if (isset($_POST['save'])) {
            if (!get_permission('biometric_devices', 'is_add')) { access_denied(); }
            $this->form_validation->set_rules('device_name', 'Device Name', 'required');
            if ($this->form_validation->run() !== false) {
                $this->biometric_model->saveDevice($this->input->post());
                set_alert('success', 'Biometric device registered successfully.');
                redirect(base_url('biometric/devices'));
            }
        }
        $this->data['devices'] = $this->biometric_model->getDevices($branchID);
        $this->data['branch_id'] = $branchID;
        $this->data['push_url'] = base_url('biometric-api/push');
        $this->data['title'] = 'Biometric Devices';
        $this->data['sub_page'] = 'biometric/devices';
        $this->data['main_menu'] = 'biometric';
        $this->load->view('layout/index', $this->data);
    }

    public function regenerate_token($id = '')
    {
        if (!get_permission('biometric_devices', 'is_edit')) { access_denied(); }
        $this->biometric_model->regenerateToken($id);
        set_alert('success', 'New API token generated. Update the device configuration with the new token.');
        redirect(base_url('biometric/devices'));
    }

    public function device_delete($id = '')
    {
        if (!$this->input->is_ajax_request()) show_404();
        if (!get_permission('biometric_devices', 'is_delete')) {
            ajax_access_denied();
        }
        $this->db->where('id', $id)->delete('biometric_devices');
        echo json_encode(array('status' => 'success'));
    }

    public function mapping()
    {
        if (!get_permission('biometric_mapping', 'is_view')) { access_denied(); }
        $branchID = $this->application_model->get_branch_id();

        if (isset($_POST['save'])) {
            if (!get_permission('biometric_mapping', 'is_add')) { access_denied(); }
            $this->form_validation->set_rules('biometric_id', 'Biometric ID', 'required');
            $this->form_validation->set_rules('person_type', 'Type', 'required');
            $this->form_validation->set_rules('person_id', 'Person', 'required');
            if ($this->form_validation->run() !== false) {
                $this->biometric_model->saveMapping(
                    $this->input->post('biometric_id'), $this->input->post('person_type'),
                    $this->input->post('person_id'), $branchID
                );
                set_alert('success', 'Biometric ID linked successfully.');
                redirect(base_url('biometric/mapping'));
            }
        }

        $this->data['mappings'] = $this->biometric_model->getMappings($branchID);
        $this->data['branch_id'] = $branchID;
        $this->data['title'] = 'Biometric ID Mapping';
        $this->data['sub_page'] = 'biometric/mapping';
        $this->data['main_menu'] = 'biometric';
        $this->load->view('layout/index', $this->data);
    }

    public function mapping_delete($id = '')
    {
        if (!$this->input->is_ajax_request()) show_404();
        if (!get_permission('biometric_mapping', 'is_delete')) {
            ajax_access_denied();
        }
        $this->biometric_model->deleteMapping($id, get_loggedin_branch_id());
        echo json_encode(array('status' => 'success'));
    }

    public function get_students_by_class()
    {
        if (!$this->input->is_ajax_request()) show_404();
        $classId = $this->input->post('class_id');
        $sectionId = $this->input->post('section_id');
        $branchId = $this->application_model->get_branch_id();
        $this->db->select('s.id, s.first_name, s.last_name, s.register_no');
        $this->db->from('enroll as e');
        $this->db->join('student as s', 's.id = e.student_id', 'inner');
        $this->db->where(array('e.class_id' => $classId, 'e.section_id' => $sectionId, 'e.branch_id' => $branchId, 'e.session_id' => get_session_id()));
        $students = $this->db->get()->result_array();
        $html = '<option value="">' . translate('select') . '</option>';
        foreach ($students as $s) {
            $html .= '<option value="' . $s['id'] . '">' . $s['first_name'] . ' ' . $s['last_name'] . ' (' . $s['register_no'] . ')</option>';
        }
        echo $html;
    }

    public function import()
    {
        if (!get_permission('biometric_logs', 'is_add')) { access_denied(); }
        $branchID = $this->application_model->get_branch_id();

        if (isset($_POST['save'])) {
            $this->load->library('csvimport');
            if (isset($_FILES["userfile"]) && !empty($_FILES['userfile']['name'])) {
                $csv_array = $this->csvimport->get_array($_FILES["userfile"]["tmp_name"]);
                if ($csv_array) {
                    $result = $this->biometric_model->importCsv($csv_array, $branchID);
                    set_alert('success', $result['imported'] . ' scans imported and attendance marked. ' . ($result['unmatched'] > 0 ? $result['unmatched'] . ' scans had unmapped biometric IDs.' : ''));
                } else {
                    set_alert('error', 'Invalid CSV file.');
                }
            } else {
                set_alert('error', 'Please select a CSV file.');
            }
            redirect(base_url('biometric/import'));
        }

        $this->data['branch_id'] = $branchID;
        $this->data['title'] = 'Import Biometric Scan Data';
        $this->data['sub_page'] = 'biometric/import';
        $this->data['main_menu'] = 'biometric';
        $this->load->view('layout/index', $this->data);
    }

    public function logs()
    {
        if (!get_permission('biometric_logs', 'is_view')) { access_denied(); }
        $branchID = $this->application_model->get_branch_id();
        $this->data['logs'] = $this->biometric_model->getRecentLogs($branchID, 200);
        $this->data['unmatched_count'] = $this->biometric_model->getUnmatchedCount($branchID);
        $this->data['branch_id'] = $branchID;
        $this->data['title'] = 'Biometric Scan Logs';
        $this->data['sub_page'] = 'biometric/logs';
        $this->data['main_menu'] = 'biometric';
        $this->load->view('layout/index', $this->data);
    }
}
