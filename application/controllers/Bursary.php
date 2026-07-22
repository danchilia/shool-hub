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
        $branchId = get_loggedin_branch_id();
        $programmes = $this->db->where('branch_id', $branchId)->order_by('id', 'DESC')->get('bursary_programmes')->result_array();

        // Append totals per programme
        foreach ($programmes as &$p) {
            $totals = $this->db->select('SUM(amount_awarded) AS total_awarded, SUM(amount_disbursed) AS total_disbursed, COUNT(*) AS beneficiaries')
                ->where('programme_id', $p['id'])->get('bursary_awards')->row_array();
            $p['total_awarded']   = $totals['total_awarded']   ?? 0;
            $p['total_disbursed'] = $totals['total_disbursed'] ?? 0;
            $p['beneficiaries']   = $totals['beneficiaries']   ?? 0;
        }

        $this->data['programmes'] = $programmes;
        $this->data['sub_page']   = 'bursary/index';
        $this->data['main_menu']  = 'bursary';
        $this->data['page_title'] = 'Bursary & Scholarships';
        $this->load->view('layout/index', $this->data);
    }

    public function save_programme()
    {
        $this->form_validation->set_rules('name',          'Programme Name', 'trim|required');
        $this->form_validation->set_rules('provider',      'Provider',       'trim|required');
        $this->form_validation->set_rules('provider_type', 'Provider Type',  'trim|required');
        if ($this->form_validation->run() !== false) {
            $branchId = get_loggedin_branch_id();
            $id = (int) $this->input->post('id');
            $data = array(
                'name'                => $this->input->post('name'),
                'provider'            => $this->input->post('provider'),
                'provider_type'       => $this->input->post('provider_type'),
                'description'         => $this->input->post('description'),
                'total_allocation'    => $this->input->post('total_allocation') ?: null,
                'academic_year'       => $this->input->post('academic_year'),
                'application_deadline'=> $this->input->post('application_deadline') ?: null,
                'status'              => $this->input->post('status'),
                'branch_id'           => $branchId,
            );
            if ($id) {
                $this->db->where(array('id' => $id, 'branch_id' => $branchId))->update('bursary_programmes', $data);
            } else {
                $this->db->insert('bursary_programmes', $data);
            }
            echo json_encode(array('status' => 'success', 'message' => 'Programme saved.'));
        } else {
            echo json_encode(array('status' => 'fail', 'error' => $this->form_validation->error_array()));
        }
    }

    public function delete_programme($id)
    {
        $this->db->where(array('id' => $id, 'branch_id' => get_loggedin_branch_id()))->delete('bursary_programmes');
        echo json_encode(array('status' => 'success', 'message' => 'Programme deleted.'));
    }

    public function awards($programmeId = '')
    {
        $branchId = get_loggedin_branch_id();
        $programme = $this->db->get_where('bursary_programmes', array('id' => $programmeId, 'branch_id' => $branchId))->row_array();
        if (!$programme) {
            show_404();
        }

        $awards = $this->db->select('ba.*, CONCAT(s.first_name," ",s.last_name) AS student_name, s.register_no, c.name AS class, se.name AS section_name')
            ->from('bursary_awards ba')
            ->join('student s', 's.id = ba.student_id', 'left')
            ->join('enroll e', 'e.student_id = s.id', 'left')
            ->join('class c', 'c.id = e.class_id', 'left')
            ->join('section se', 'se.id = e.section_id', 'left')
            ->where('ba.programme_id', $programmeId)
            ->order_by('ba.id', 'DESC')
            ->get()->result_array();

        $students = $this->db->select('s.id AS student_id, s.first_name, s.last_name, s.register_no')
            ->from('student s')
            ->join('enroll e', 'e.student_id = s.id AND e.branch_id = '.(int)$branchId)
            ->group_by('s.id')
            ->order_by('s.first_name')
            ->get()->result_array();

        $this->data['programme'] = $programme;
        $this->data['awards']    = $awards;
        $this->data['students']  = $students;
        $this->data['sub_page']  = 'bursary/awards';
        $this->data['main_menu'] = 'bursary';
        $this->data['page_title']= 'Awards — ' . $programme['name'];
        $this->load->view('layout/index', $this->data);
    }

    public function save_award()
    {
        $this->form_validation->set_rules('programme_id',   'Programme',        'trim|required');
        $this->form_validation->set_rules('student_id',     'Student',          'trim|required');
        $this->form_validation->set_rules('amount_awarded', 'Amount Awarded',   'trim|required|numeric|greater_than[0]');
        if ($this->form_validation->run() !== false) {
            $data = array(
                'programme_id'     => (int) $this->input->post('programme_id'),
                'student_id'       => (int) $this->input->post('student_id'),
                'amount_awarded'   => (float) $this->input->post('amount_awarded'),
                'amount_disbursed' => (float) $this->input->post('amount_disbursed') ?: 0,
                'disbursement_date'=> $this->input->post('disbursement_date') ?: null,
                'applied_date'     => $this->input->post('applied_date') ?: null,
                'status'           => $this->input->post('status'),
                'remarks'          => $this->input->post('remarks'),
                'branch_id'        => get_loggedin_branch_id(),
            );
            $id = (int) $this->input->post('id');
            if ($id) {
                $this->db->where('id', $id)->update('bursary_awards', $data);
            } else {
                $this->db->insert('bursary_awards', $data);
            }
            echo json_encode(array('status' => 'success', 'message' => 'Award saved.'));
        } else {
            echo json_encode(array('status' => 'fail', 'error' => $this->form_validation->error_array()));
        }
    }

    public function delete_award($id)
    {
        $this->db->where(array('id' => $id, 'branch_id' => get_loggedin_branch_id()))->delete('bursary_awards');
        echo json_encode(array('status' => 'success', 'message' => 'Award deleted.'));
    }
}
