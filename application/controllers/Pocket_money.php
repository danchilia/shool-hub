<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pocket_money extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    private function _getBalance($studentId)
    {
        $row = $this->db->get_where('pocket_money_balance', array('student_id' => $studentId))->row_array();
        return $row ? (float) $row['balance'] : 0.0;
    }

    private function _updateBalance($studentId, $branchId, $newBalance)
    {
        $exists = $this->db->where('student_id', $studentId)->count_all_results('pocket_money_balance');
        if ($exists) {
            $this->db->where('student_id', $studentId)->update('pocket_money_balance', array('balance' => $newBalance, 'branch_id' => $branchId));
        } else {
            $this->db->insert('pocket_money_balance', array('student_id' => $studentId, 'balance' => $newBalance, 'branch_id' => $branchId));
        }
    }

    /** Branch-wide overview */
    public function index()
    {
        $branchId = get_loggedin_branch_id();
        $students = $this->db->select('s.id AS student_id, s.first_name, s.last_name, s.register_no, c.name AS class, se.name AS section_name, COALESCE(pmb.balance, 0) AS balance')
            ->from('student s')
            ->join('enroll e', 'e.student_id = s.id AND e.branch_id = '.(int)$branchId)
            ->join('class c', 'c.id = e.class_id', 'left')
            ->join('section se', 'se.id = e.section_id', 'left')
            ->join('pocket_money_balance pmb', 'pmb.student_id = s.id', 'left')
            ->group_by('s.id')
            ->order_by('s.first_name')
            ->get()->result_array();

        $totalHeld = array_sum(array_column($students, 'balance'));

        $this->data['students']  = $students;
        $this->data['totalHeld'] = $totalHeld;
        $this->data['sub_page']  = 'pocket_money/index';
        $this->data['main_menu'] = 'pocket_money';
        $this->data['page_title']= 'Pocket Money';
        $this->load->view('layout/index', $this->data);
    }

    /** Student ledger */
    public function ledger($studentId = '')
    {
        $branchId = get_loggedin_branch_id();
        $student  = $this->db->select('s.*, c.name AS class, se.name AS section_name')
            ->from('student s')
            ->join('enroll e', 'e.student_id = s.id', 'left')
            ->join('class c', 'c.id = e.class_id', 'left')
            ->join('section se', 'se.id = e.section_id', 'left')
            ->where('s.id', $studentId)
            ->get()->row_array();
        if (!$student) {
            show_404();
        }

        $transactions = $this->db->where('student_id', $studentId)->order_by('id', 'DESC')->get('pocket_money')->result_array();
        $balance      = $this->_getBalance($studentId);

        $this->data['student']      = $student;
        $this->data['transactions'] = $transactions;
        $this->data['balance']      = $balance;
        $this->data['sub_page']     = 'pocket_money/ledger';
        $this->data['main_menu']    = 'pocket_money';
        $this->data['page_title']   = 'Pocket Money — ' . $student['first_name'];
        $this->load->view('layout/index', $this->data);
    }

    /** Deposit cash */
    public function deposit()
    {
        $this->form_validation->set_rules('student_id', 'Student',     'trim|required|is_natural_no_zero');
        $this->form_validation->set_rules('amount',     'Amount',      'trim|required|numeric|greater_than[0]');
        if ($this->form_validation->run() !== false) {
            $studentId = (int) $this->input->post('student_id');
            $amount    = (float) $this->input->post('amount');
            $branchId  = get_loggedin_branch_id();
            $balance   = $this->_getBalance($studentId) + $amount;
            $this->_updateBalance($studentId, $branchId, $balance);
            $this->db->insert('pocket_money', array(
                'student_id'      => $studentId,
                'transaction_type'=> 'deposit',
                'amount'          => $amount,
                'balance_after'   => $balance,
                'description'     => $this->input->post('description'),
                'received_from'   => $this->input->post('received_from'),
                'reference_no'    => $this->input->post('reference_no'),
                'recorded_by'     => get_loggedin_user_id(),
                'branch_id'       => $branchId,
            ));
            echo json_encode(array('status' => 'success', 'message' => 'KES ' . number_format($amount, 2) . ' deposited. New balance: KES ' . number_format($balance, 2)));
        } else {
            echo json_encode(array('status' => 'fail', 'error' => $this->form_validation->error_array()));
        }
    }

    /** Withdrawal */
    public function withdraw()
    {
        $this->form_validation->set_rules('student_id', 'Student', 'trim|required|is_natural_no_zero');
        $this->form_validation->set_rules('amount',     'Amount',  'trim|required|numeric|greater_than[0]');
        if ($this->form_validation->run() !== false) {
            $studentId = (int) $this->input->post('student_id');
            $amount    = (float) $this->input->post('amount');
            $branchId  = get_loggedin_branch_id();
            $balance   = $this->_getBalance($studentId);
            if ($amount > $balance) {
                echo json_encode(array('status' => 'fail', 'error' => array('amount' => 'Insufficient balance. Available: KES ' . number_format($balance, 2))));
                return;
            }
            $newBalance = $balance - $amount;
            $this->_updateBalance($studentId, $branchId, $newBalance);
            $this->db->insert('pocket_money', array(
                'student_id'      => $studentId,
                'transaction_type'=> 'withdraw',
                'amount'          => $amount,
                'balance_after'   => $newBalance,
                'description'     => $this->input->post('description'),
                'recorded_by'     => get_loggedin_user_id(),
                'branch_id'       => $branchId,
            ));
            echo json_encode(array('status' => 'success', 'message' => 'KES ' . number_format($amount, 2) . ' withdrawn. Remaining: KES ' . number_format($newBalance, 2)));
        } else {
            echo json_encode(array('status' => 'fail', 'error' => $this->form_validation->error_array()));
        }
    }
}
