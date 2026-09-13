<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;

class School_directory extends MY_Controller {

    public function __construct() {
        parent::__construct();
        if (!is_superadmin_loggedin()) redirect(base_url('dashboard'));
        $this->load->model('school_directory_model');
    }

    // ── Directory listing ─────────────────────────────────────────────────

    public function index() {
        $filters = [
            'q'         => $this->input->get('q',         true),
            'region'    => $this->input->get('region',    true),
            'type'      => $this->input->get('type',      true),
            'ownership' => $this->input->get('ownership', true),
            'status'    => $this->input->get('status',    true),
        ];
        $page   = max(1, (int) $this->input->get('page'));
        $limit  = 50;
        $offset = ($page - 1) * $limit;

        $result = $this->school_directory_model->search($filters, $limit, $offset);

        $this->data['schools']    = $result['rows'];
        $this->data['total']      = $result['total'];
        $this->data['page']       = $page;
        $this->data['limit']      = $limit;
        $this->data['filters']    = $filters;
        $this->data['regions']    = $this->school_directory_model->get_regions();
        $this->data['types']      = $this->school_directory_model->get_types();
        $this->data['ownerships'] = $this->school_directory_model->get_ownerships();
        $this->data['pending']    = $this->school_directory_model->count_by_status('pending_review');
        $this->data['title']      = 'School Directory';
        $this->data['sub_page']   = 'school_directory/index';
        $this->data['main_menu']  = 'school_directory';
        $this->load->view('layout/index', $this->data);
    }

    // ── Pending review queue ──────────────────────────────────────────────

    public function pending() {
        $this->data['schools']   = $this->school_directory_model->get_pending(100);
        $this->data['title']     = 'Pending Review — School Directory';
        $this->data['sub_page']  = 'school_directory/pending';
        $this->data['main_menu'] = 'school_directory';
        $this->load->view('layout/index', $this->data);
    }

    public function approve($id) {
        $this->school_directory_model->approve($id);
        set_alert('success', 'School approved and added to the active directory.');
        redirect(base_url('school_directory/pending'));
    }

    public function delete($id) {
        $this->school_directory_model->delete($id);
        set_alert('success', 'School removed from directory.');
        redirect($this->input->server('HTTP_REFERER') ?: base_url('school_directory'));
    }

    // ── Bulk upload ───────────────────────────────────────────────────────

    public function upload() {
        $this->data['title']     = 'Upload Schools from Excel';
        $this->data['sub_page']  = 'school_directory/upload';
        $this->data['main_menu'] = 'school_directory';
        $this->data['preview']   = null;
        $this->data['error']     = null;

        if ($this->input->post('confirm_import')) {
            $this->_do_import();
            return;
        }

        if ($this->input->post('preview') && !empty($_FILES['xlsx_file']['name'])) {
            $this->_preview_upload();
            return;
        }

        $this->load->view('layout/index', $this->data);
    }

    private function _preview_upload() {
        $file = $_FILES['xlsx_file'];
        $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, ['xlsx', 'xls'])) {
            $this->data['error'] = 'Please upload an Excel file (.xlsx or .xls).';
            $this->load->view('layout/index', $this->data);
            return;
        }

        $tmp = sys_get_temp_dir() . '/sd_upload_' . session_id() . '.' . $ext;
        move_uploaded_file($file['tmp_name'], $tmp);

        try {
            $spreadsheet = IOFactory::load($tmp);
        } catch (Exception $e) {
            $this->data['error'] = 'Could not read the file: ' . $e->getMessage();
            $this->load->view('layout/index', $this->data);
            return;
        }

        $sheets  = [];
        $regions = $this->input->post('regions') ?: [];

        foreach ($spreadsheet->getSheetNames() as $i => $sheetName) {
            $sheet = $spreadsheet->getSheet($i);
            $rows  = $sheet->toArray(null, true, true, true);

            // Detect header row (first non-empty row)
            $header = [];
            $data   = [];
            foreach ($rows as $row) {
                $vals = array_values(array_filter($row, fn($v) => $v !== null && $v !== ''));
                if (empty($vals)) continue;
                if (empty($header)) { $header = array_map('strtolower', array_map('trim', array_values($row))); continue; }
                $mapped = array_combine($header, array_values($row));
                if (empty(trim($mapped[array_key_first($mapped)] ?? ''))) continue;
                $data[] = $mapped;
            }

            $defaultRegion = isset($regions[$i]) ? $regions[$i] : $sheetName;

            $sheets[] = [
                'index'   => $i,
                'name'    => $sheetName,
                'region'  => $defaultRegion,
                'headers' => $header,
                'count'   => count($data),
                'sample'  => array_slice($data, 0, 5),
            ];
        }

        // Store temp file path in session for the confirm step
        $this->session->set_userdata('sd_tmp_file', $tmp);
        $this->data['preview'] = $sheets;
        $this->load->view('layout/index', $this->data);
    }

    private function _do_import() {
        $tmp = $this->session->userdata('sd_tmp_file');
        if (!$tmp || !file_exists($tmp)) {
            set_alert('error', 'Upload session expired. Please upload the file again.');
            redirect(base_url('school_directory/upload'));
        }

        $regions = $this->input->post('regions') ?: [];

        try {
            $spreadsheet = IOFactory::load($tmp);
        } catch (Exception $e) {
            set_alert('error', 'Could not read file: ' . $e->getMessage());
            redirect(base_url('school_directory/upload'));
        }

        $imported = 0; $skipped = 0; $errors = 0;

        foreach ($spreadsheet->getSheetNames() as $i => $sheetName) {
            $region = isset($regions[$i]) ? trim($regions[$i]) : $sheetName;
            $sheet  = $spreadsheet->getSheet($i);
            $rows   = $sheet->toArray(null, true, true, true);

            $header = [];
            foreach ($rows as $row) {
                $vals = array_values(array_filter($row, fn($v) => $v !== null && $v !== ''));
                if (empty($vals)) continue;
                if (empty($header)) { $header = array_map('strtolower', array_map('trim', array_values($row))); continue; }

                $mapped = array_combine($header, array_values($row));
                $name   = trim($mapped['school name'] ?? $mapped['name'] ?? '');
                if (!$name) continue;

                $phone     = trim($mapped['phone number'] ?? $mapped['phone'] ?? '');
                $ownership = trim($mapped['ownership'] ?? '');
                if (strtolower($ownership) === 'not specified' || $ownership === '') $ownership = 'Not Specified';

                $duplicate = $this->school_directory_model->find_duplicate($name, $phone);
                if ($duplicate) { $skipped++; continue; }

                try {
                    $this->school_directory_model->insert([
                        'school_name'   => $name,
                        'type'          => trim($mapped['type'] ?? ''),
                        'ownership'     => $ownership,
                        'area'          => trim($mapped['area'] ?? ''),
                        'region'        => $region,
                        'phone'         => $phone,
                        'road_location' => trim($mapped['road/location'] ?? $mapped['road location'] ?? $mapped['location'] ?? ''),
                        'status'        => 'active',
                    ]);
                    $imported++;
                } catch (Exception $e) {
                    $errors++;
                }
            }
        }

        @unlink($tmp);
        $this->session->unset_userdata('sd_tmp_file');

        set_alert('success', "Import complete: {$imported} imported, {$skipped} duplicates skipped" . ($errors ? ", {$errors} errors." : '.'));
        redirect(base_url('school_directory'));
    }
}
