<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Canteen extends Admin_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() { redirect(base_url('canteen/pos')); }

    private function _branchId() { return get_loggedin_branch_id(); }

    private function _getWallet($studentId) {
        $w = $this->db->where('student_id', $studentId)->get('canteen_wallet')->row();
        if (!$w) {
            $branchId = $this->_branchId();
            $this->db->insert('canteen_wallet', ['student_id' => $studentId, 'balance' => 0, 'branch_id' => $branchId]);
            $w = (object)['balance' => 0];
        }
        return $w;
    }

    // ── POS terminal ────────────────────────────────────────────────────
    public function pos() {
        $branchId = $this->_branchId();
        $items    = $this->db->where('branch_id', $branchId)->where('is_available', 1)
                             ->order_by('category,name')->get('canteen_items')->result();

        $this->data['items']     = $items;
        $this->data['title']     = 'Canteen POS';
        $this->data['main_menu'] = 'canteen';
        $this->data['sub_page']  = 'canteen/pos';
        $this->load->view('layout/index', $this->data);
    }

    public function get_student_wallet() {
        if (!$this->input->is_ajax_request()) show_404();
        $branchId = $this->_branchId();
        $search   = trim($this->input->post('student_id'));

        if (!$search) { echo json_encode(['status' => 'error', 'msg' => 'Enter student ID or register number']); return; }

        // Search by numeric ID or register_no
        $student = null;
        if (is_numeric($search)) {
            $student = $this->db->select('s.id, s.first_name, s.last_name, s.register_no, s.photo')
                ->from('student s')
                ->join('enroll e', 'e.student_id = s.id')
                ->where('s.id', $search)
                ->where('e.branch_id', $branchId)
                ->get()->row();
        }
        if (!$student) {
            $student = $this->db->select('s.id, s.first_name, s.last_name, s.register_no, s.photo')
                ->from('student s')
                ->join('enroll e', 'e.student_id = s.id')
                ->where('s.register_no', $search)
                ->where('e.branch_id', $branchId)
                ->get()->row();
        }

        if (!$student) { echo json_encode(['status' => 'error', 'msg' => 'Student not found in this branch']); return; }

        $wallet = $this->_getWallet($student->id);
        echo json_encode([
            'status'  => 'success',
            'student' => ['id' => $student->id, 'firstname' => $student->first_name, 'lastname' => $student->last_name, 'admission_no' => $student->register_no],
            'balance' => $wallet->balance,
        ]);
    }

    public function checkout_pos() {
        if (!$this->input->is_ajax_request()) show_404();
        $branchId  = $this->_branchId();
        $studentId = $this->input->post('student_id');
        $cartJson  = $this->input->post('cart');

        if (!$studentId || !$cartJson) { echo json_encode(['status' => 'error', 'msg' => 'Missing data']); return; }

        $cart      = json_decode($cartJson, true);
        $total     = 0;
        $lineItems = [];

        foreach ($cart as $entry) {
            $item = $this->db->where('id', $entry['id'])->where('branch_id', $branchId)->get('canteen_items')->row();
            if (!$item) continue;
            $qty      = max(1, (int)$entry['qty']);
            $subtotal = $item->price * $qty;
            $total   += $subtotal;
            $lineItems[] = ['item' => $item, 'qty' => $qty, 'subtotal' => $subtotal];
        }

        if ($total <= 0 || empty($lineItems)) { echo json_encode(['status' => 'error', 'msg' => 'Empty cart']); return; }

        $wallet = $this->_getWallet($studentId);
        if ($wallet->balance < $total) {
            echo json_encode(['status' => 'error', 'msg' => 'Insufficient balance. Available: KES ' . number_format($wallet->balance, 2)]); return;
        }

        $newBalance = $wallet->balance - $total;

        $this->db->insert('canteen_transactions', [
            'student_id'       => $studentId,
            'transaction_type' => 'purchase',
            'total_amount'     => $total,
            'balance_before'   => $wallet->balance,
            'balance_after'    => $newBalance,
            'served_by'        => get_loggedin_user_id(),
            'branch_id'        => $branchId,
        ]);
        $txId = $this->db->insert_id();

        foreach ($lineItems as $li) {
            $this->db->insert('canteen_transaction_items', [
                'transaction_id' => $txId,
                'item_id'        => $li['item']->id,
                'quantity'       => $li['qty'],
                'unit_price'     => $li['item']->price,
                'subtotal'       => $li['subtotal'],
                'branch_id'      => $branchId,
            ]);
            if ($li['item']->quantity_in_stock !== null) {
                $this->db->where('id', $li['item']->id)->set('quantity_in_stock', 'quantity_in_stock - ' . $li['qty'], false)->update('canteen_items');
            }
        }

        $this->db->where('student_id', $studentId)->update('canteen_wallet', ['balance' => $newBalance]);

        echo json_encode([
            'status'      => 'success',
            'msg'         => 'Sale complete',
            'total'       => number_format($total, 2),
            'new_balance' => number_format($newBalance, 2),
            'receipt_no'  => 'CNT-' . $txId,
        ]);
    }

    // ── Top-up wallet ────────────────────────────────────────────────────
    public function topup() {
        if (!$this->input->is_ajax_request()) show_404();
        $branchId  = $this->_branchId();
        $studentId = $this->input->post('student_id');
        $amount    = (float)$this->input->post('amount');

        if (!$studentId || $amount <= 0) { echo json_encode(['status' => 'error', 'msg' => 'Invalid top-up data']); return; }

        $wallet     = $this->_getWallet($studentId);
        $newBalance = $wallet->balance + $amount;

        $this->db->where('student_id', $studentId)->update('canteen_wallet', ['balance' => $newBalance]);
        $this->db->insert('canteen_transactions', [
            'student_id'       => $studentId,
            'transaction_type' => 'top_up',
            'total_amount'     => $amount,
            'balance_before'   => $wallet->balance,
            'balance_after'    => $newBalance,
            'served_by'        => get_loggedin_user_id(),
            'notes'            => $this->input->post('notes'),
            'branch_id'        => $branchId,
        ]);

        echo json_encode(['status' => 'success', 'url' => base_url('canteen/wallets')]);
    }

    // ── Menu management ─────────────────────────────────────────────────
    public function menu() {
        $branchId = $this->_branchId();
        $items    = $this->db->where('branch_id', $branchId)->order_by('category,name')->get('canteen_items')->result();

        $this->data['items']     = $items;
        $this->data['title']     = 'Canteen Menu';
        $this->data['main_menu'] = 'canteen';
        $this->data['sub_page']  = 'canteen/menu';
        $this->load->view('layout/index', $this->data);
    }

    public function save_item() {
        if (!$this->input->is_ajax_request()) show_404();
        $branchId = $this->_branchId();

        $rules = [
            ['field' => 'name',  'label' => 'Name',  'rules' => 'required|max_length[200]'],
            ['field' => 'price', 'label' => 'Price', 'rules' => 'required|numeric'],
        ];
        $this->form_validation->set_rules($rules);
        if (!$this->form_validation->run()) { echo json_encode(['status' => 'error', 'msg' => validation_errors()]); return; }

        $stockVal = $this->input->post('quantity_in_stock');
        $data = [
            'name'              => $this->input->post('name'),
            'category'          => $this->input->post('category'),
            'price'             => $this->input->post('price'),
            'quantity_in_stock' => ($stockVal !== '' && $stockVal !== null) ? (int)$stockVal : null,
            'is_available'      => $this->input->post('is_available') ? 1 : 0,
            'branch_id'         => $branchId,
        ];

        $id = $this->input->post('id');
        $id ? $this->db->where('id', $id)->where('branch_id', $branchId)->update('canteen_items', $data)
            : $this->db->insert('canteen_items', $data);
        echo json_encode(['status' => 'success', 'url' => base_url('canteen/menu')]);
    }

    public function delete_item($id) {
        $this->db->where('id', $id)->where('branch_id', $this->_branchId())->delete('canteen_items');
        echo json_encode(['status' => 'success', 'url' => base_url('canteen/menu')]);
    }

    // ── Wallets overview ─────────────────────────────────────────────────
    public function wallets() {
        $branchId = $this->_branchId();

        $wallets = $this->db
            ->select('w.*, CONCAT(s.first_name, " ", s.last_name) AS student_name, s.register_no AS admission_no, s.photo')
            ->from('canteen_wallet w')
            ->join('student s', 's.id = w.student_id', 'left')
            ->where('w.branch_id', $branchId)
            ->order_by('s.first_name')
            ->get()->result();

        // All enrolled students in this branch for top-up dropdown
        $students = $this->db
            ->select('s.id, s.first_name, s.last_name, s.register_no')
            ->from('student s')
            ->join('enroll e', 'e.student_id = s.id')
            ->where('e.branch_id', $branchId)
            ->group_by('s.id')
            ->order_by('s.first_name')
            ->get()->result();

        $this->data['wallets']   = $wallets;
        $this->data['students']  = $students;
        $this->data['title']     = 'Canteen Wallets';
        $this->data['main_menu'] = 'canteen';
        $this->data['sub_page']  = 'canteen/wallets';
        $this->load->view('layout/index', $this->data);
    }

    // ── Sales report ─────────────────────────────────────────────────────
    public function report() {
        $branchId = $this->_branchId();
        $dateFrom = $this->input->get('date_from') ?: date('Y-m-01');
        $dateTo   = $this->input->get('date_to')   ?: date('Y-m-d');

        $transactions = $this->db
            ->select('t.*, CONCAT(s.first_name, " ", s.last_name) AS student_name, s.register_no AS admission_no')
            ->from('canteen_transactions t')
            ->join('student s', 's.id = t.student_id', 'left')
            ->where('t.branch_id', $branchId)
            ->where('t.transaction_type', 'purchase')
            ->where('DATE(t.created_at) >=', $dateFrom)
            ->where('DATE(t.created_at) <=', $dateTo)
            ->order_by('t.created_at', 'DESC')
            ->get()->result();

        $totalRevenue = array_sum(array_column($transactions, 'total_amount'));

        $this->data['transactions']  = $transactions;
        $this->data['total_revenue'] = $totalRevenue;
        $this->data['date_from']     = $dateFrom;
        $this->data['date_to']       = $dateTo;
        $this->data['title']         = 'Canteen Sales Report';
        $this->data['main_menu']     = 'canteen';
        $this->data['sub_page']      = 'canteen/report';
        $this->load->view('layout/index', $this->data);
    }
}
