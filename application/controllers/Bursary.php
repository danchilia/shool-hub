<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bursary extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $branchId   = get_loggedin_branch_id();
        $programmes = $this->db->where('branch_id', $branchId)
                               ->order_by('id', 'DESC')
                               ->get('bursary_programmes')->result_array();

        // Pre-aggregate totals to avoid N+1
        $progIds = array_column($programmes, 'id');
        $totalsMap = [];
        if (!empty($progIds)) {
            $rows = $this->db
                ->select('programme_id, SUM(amount_awarded) AS total_awarded, SUM(amount_disbursed) AS total_disbursed, COUNT(*) AS beneficiaries')
                ->where_in('programme_id', $progIds)
                ->group_by('programme_id')
                ->get('bursary_awards')->result_array();
            foreach ($rows as $r) {
                $totalsMap[$r['programme_id']] = $r;
            }
        }
        foreach ($programmes as &$p) {
            $t = $totalsMap[$p['id']] ?? [];
            $p['total_awarded']   = $t['total_awarded']   ?? 0;
            $p['total_disbursed'] = $t['total_disbursed'] ?? 0;
            $p['beneficiaries']   = $t['beneficiaries']   ?? 0;
        }
        unset($p);

        $this->data['programmes'] = $programmes;
        $this->data['title']      = 'Bursary & Scholarships';
        $this->data['sub_page']   = 'bursary/index';
        $this->data['main_menu']  = 'bursary';
        $this->load->view('layout/index', $this->data);
    }

    public function save_programme()
    {
        if (!$this->input->is_ajax_request()) show_404();

        $this->form_validation->set_rules('name',          'Programme Name', 'trim|required');
        $this->form_validation->set_rules('provider',      'Provider',       'trim|required');
        $this->form_validation->set_rules('provider_type', 'Provider Type',  'trim|required');

        if ($this->form_validation->run() !== false) {
            $branchId = get_loggedin_branch_id();
            $id       = (int)$this->input->post('id');
            $data = [
                'name'                 => $this->input->post('name'),
                'provider'             => $this->input->post('provider'),
                'provider_type'        => $this->input->post('provider_type'),
                'description'          => $this->input->post('description'),
                'total_allocation'     => $this->input->post('total_allocation')     ?: null,
                'academic_year'        => $this->input->post('academic_year'),
                'application_deadline' => $this->input->post('application_deadline') ?: null,
                'status'               => $this->input->post('status'),
                'branch_id'            => $branchId,
            ];
            if ($id) {
                $this->db->where(['id' => $id, 'branch_id' => $branchId])->update('bursary_programmes', $data);
            } else {
                $this->db->insert('bursary_programmes', $data);
            }
            echo json_encode(['status' => 'success', 'url' => base_url('bursary')]);
        } else {
            echo json_encode(['status' => 'error', 'msg' => validation_errors()]);
        }
    }

    public function delete_programme($id)
    {
        if (!$this->input->is_ajax_request()) show_404();
        $branchId = get_loggedin_branch_id();
        // Cascade: remove awards first to avoid orphaned records
        $this->db->where('programme_id', $id)->delete('bursary_awards');
        $this->db->where(['id' => $id, 'branch_id' => $branchId])->delete('bursary_programmes');
        echo json_encode(['status' => 'success', 'url' => base_url('bursary')]);
    }

    public function awards($programmeId = '')
    {
        $branchId  = get_loggedin_branch_id();
        $programme = $this->db->get_where('bursary_programmes', ['id' => $programmeId, 'branch_id' => $branchId])->row_array();
        if (!$programme) show_404();

        $awards = $this->db
            ->select('ba.*, CONCAT(s.first_name," ",s.last_name) AS student_name, s.register_no,
                c.name AS class, se.name AS section_name')
            ->from('bursary_awards ba')
            ->join('student s',  's.id = ba.student_id',  'left')
            ->join('enroll e',   'e.student_id = s.id',   'left')
            ->join('class c',    'c.id = e.class_id',     'left')
            ->join('section se', 'se.id = e.section_id',  'left')
            ->where('ba.programme_id', $programmeId)
            ->order_by('ba.id', 'DESC')
            ->get()->result_array();

        $students = $this->db
            ->select('s.id AS student_id, s.first_name, s.last_name, s.register_no')
            ->from('student s')
            ->join('enroll e', 'e.student_id = s.id AND e.branch_id = ' . (int)$branchId)
            ->group_by('s.id')
            ->order_by('s.first_name')
            ->get()->result_array();

        $this->data['programme'] = $programme;
        $this->data['awards']    = $awards;
        $this->data['students']  = $students;
        $this->data['title']     = 'Awards — ' . $programme['name'];
        $this->data['sub_page']  = 'bursary/awards';
        $this->data['main_menu'] = 'bursary';
        $this->load->view('layout/index', $this->data);
    }

    public function save_award()
    {
        if (!$this->input->is_ajax_request()) show_404();

        $this->form_validation->set_rules('programme_id',   'Programme',      'trim|required');
        $this->form_validation->set_rules('student_id',     'Student',        'trim|required');
        $this->form_validation->set_rules('amount_awarded', 'Amount Awarded', 'trim|required|numeric|greater_than[0]');

        if ($this->form_validation->run() !== false) {
            $branchId    = get_loggedin_branch_id();
            $programmeId = (int)$this->input->post('programme_id');
            $id          = (int)$this->input->post('id');
            $data = [
                'programme_id'      => $programmeId,
                'student_id'        => (int)$this->input->post('student_id'),
                'amount_awarded'    => (float)$this->input->post('amount_awarded'),
                'amount_disbursed'  => (float)$this->input->post('amount_disbursed') ?: 0,
                'disbursement_date' => $this->input->post('disbursement_date') ?: null,
                'applied_date'      => $this->input->post('applied_date')      ?: null,
                'status'            => $this->input->post('status'),
                'remarks'           => $this->input->post('remarks'),
                'branch_id'         => $branchId,
            ];
            if ($id) {
                // branch_id guard prevents cross-branch edits
                $this->db->where(['id' => $id, 'branch_id' => $branchId])->update('bursary_awards', $data);
            } else {
                $this->db->insert('bursary_awards', $data);
            }
            echo json_encode(['status' => 'success', 'url' => base_url('bursary/awards/' . $programmeId)]);
        } else {
            echo json_encode(['status' => 'error', 'msg' => validation_errors()]);
        }
    }

    public function delete_award($id)
    {
        if (!$this->input->is_ajax_request()) show_404();
        $branchId    = get_loggedin_branch_id();
        // Fetch programme_id before deleting so we can redirect back to the right page
        $award       = $this->db->select('programme_id')
                                ->where(['id' => $id, 'branch_id' => $branchId])
                                ->get('bursary_awards')->row_array();
        $programmeId = $award['programme_id'] ?? 0;
        $this->db->where(['id' => $id, 'branch_id' => $branchId])->delete('bursary_awards');
        echo json_encode(['status' => 'success', 'url' => base_url('bursary/awards/' . $programmeId)]);
    }
}
