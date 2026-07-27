<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Assets extends Admin_Controller {

    public function __construct() {
        parent::__construct();
    }

    private function _branchId() {
        return get_loggedin_branch_id();
    }

    // ── Categories ──────────────────────────────────────────────────────
    public function categories() {
        $branchId = $this->_branchId();
        $cats = $this->db->where('branch_id', $branchId)->get('asset_categories')->result();

        $this->data['categories'] = $cats;
        $this->data['title']      = 'Asset Categories';
        $this->data['main_menu']  = 'assets';
        $this->data['sub_page']   = 'assets/categories';
        $this->load->view('layout/index', $this->data);
    }

    public function save_category() {
        if (!$this->input->is_ajax_request()) show_404();
        $branchId = $this->_branchId();

        $this->form_validation->set_rules('name','Category Name','required|max_length[200]');
        if (!$this->form_validation->run()) {
            echo json_encode(['status'=>'error','msg'=>validation_errors()]); return;
        }
        $data = ['name'=>$this->input->post('name'),'description'=>$this->input->post('description'),'branch_id'=>$branchId];
        $id   = $this->input->post('id');
        $id ? $this->db->where('id',$id)->update('asset_categories',$data) : $this->db->insert('asset_categories',$data);
        echo json_encode(['status'=>'success','url'=>base_url('assets/categories')]);
    }

    public function delete_category($id) {
        $this->db->where('category_id', $id)->update('assets', ['category_id' => null]);
        $this->db->where('id',$id)->where('branch_id',$this->_branchId())->delete('asset_categories');
        echo json_encode(['status'=>'success','url'=>base_url('assets/categories')]);
    }

    // ── Assets ──────────────────────────────────────────────────────────
    public function index() {
        $branchId = $this->_branchId();
        $catId    = $this->input->get('category_id');
        $cond     = $this->input->get('condition');

        $this->db->select('a.*, c.name AS category_name')
                 ->from('assets a')
                 ->join('asset_categories c','c.id=a.category_id','left')
                 ->where('a.branch_id', $branchId);
        if ($catId)  $this->db->where('a.category_id', $catId);
        if ($cond)   $this->db->where('a.condition_status', $cond);
        $assets = $this->db->order_by('a.name')->get()->result();

        $stats = [
            'total'    => count($assets),
            'good'     => count(array_filter($assets, fn($a) => $a->condition_status === 'good')),
            'fair'     => count(array_filter($assets, fn($a) => $a->condition_status === 'fair')),
            'damaged'  => count(array_filter($assets, fn($a) => in_array($a->condition_status,['poor','damaged']))),
        ];

        $this->data['assets']     = $assets;
        $this->data['categories'] = $this->db->where('branch_id',$branchId)->get('asset_categories')->result();
        $this->data['stats']      = $stats;
        $this->data['filter_cat'] = $catId;
        $this->data['filter_cond']= $cond;
        $this->data['title']      = 'Asset Register';
        $this->data['main_menu']  = 'assets';
        $this->data['sub_page']   = 'assets/index';
        $this->load->view('layout/index', $this->data);
    }

    public function save_asset() {
        if (!$this->input->is_ajax_request()) show_404();
        $branchId = $this->_branchId();

        $rules = [
            ['field'=>'asset_tag','label'=>'Asset Tag','rules'=>'required|max_length[100]'],
            ['field'=>'name',     'label'=>'Name',     'rules'=>'required|max_length[300]'],
        ];
        $this->form_validation->set_rules($rules);
        if (!$this->form_validation->run()) {
            echo json_encode(['status'=>'error','msg'=>validation_errors()]); return;
        }

        $data = [
            'asset_tag'         => $this->input->post('asset_tag'),
            'name'              => $this->input->post('name'),
            'category_id'       => $this->input->post('category_id') ?: null,
            'brand'             => $this->input->post('brand'),
            'model'             => $this->input->post('model'),
            'serial_no'         => $this->input->post('serial_no'),
            'purchase_date'     => $this->input->post('purchase_date') ?: null,
            'purchase_cost'     => $this->input->post('purchase_cost') ?: null,
            'supplier'          => $this->input->post('supplier'),
            'location'          => $this->input->post('location'),
            'assigned_to_type'  => $this->input->post('assigned_to_type') ?: 'none',
            'assigned_to_id'    => $this->input->post('assigned_to_id') ?: null,
            'condition_status'  => $this->input->post('condition_status') ?: 'good',
            'warranty_expiry'   => $this->input->post('warranty_expiry') ?: null,
            'notes'             => $this->input->post('notes'),
            'branch_id'         => $branchId,
        ];

        $id = $this->input->post('id');
        $id ? $this->db->where('id',$id)->where('branch_id',$branchId)->update('assets',$data)
            : $this->db->insert('assets',$data);
        echo json_encode(['status'=>'success','url'=>base_url('assets')]);
    }

    public function delete_asset($id) {
        $branchId = $this->_branchId();
        $this->db->where('asset_id',$id)->delete('asset_maintenance');
        $this->db->where('id',$id)->where('branch_id',$branchId)->delete('assets');
        echo json_encode(['status'=>'success','url'=>base_url('assets')]);
    }

    // ── Maintenance log ─────────────────────────────────────────────────
    public function maintenance($assetId) {
        $branchId = $this->_branchId();
        $asset    = $this->db->where('id',$assetId)->where('branch_id',$branchId)->get('assets')->row();
        if (!$asset) show_404();

        $logs = $this->db->where('asset_id',$assetId)->order_by('maintenance_date','DESC')->get('asset_maintenance')->result();

        $this->data['asset']   = $asset;
        $this->data['logs']    = $logs;
        $this->data['title']   = 'Maintenance: ' . $asset->name;
        $this->data['main_menu']  = 'assets';
        $this->data['sub_page']   = 'assets/maintenance';
        $this->load->view('layout/index', $this->data);
    }

    public function save_maintenance() {
        if (!$this->input->is_ajax_request()) show_404();
        $branchId = $this->_branchId();

        $rules = [
            ['field'=>'asset_id',         'label'=>'Asset',  'rules'=>'required'],
            ['field'=>'maintenance_date',  'label'=>'Date',   'rules'=>'required'],
            ['field'=>'maintenance_type',  'label'=>'Type',   'rules'=>'required'],
        ];
        $this->form_validation->set_rules($rules);
        if (!$this->form_validation->run()) {
            echo json_encode(['status'=>'error','msg'=>validation_errors()]); return;
        }

        $assetId = $this->input->post('asset_id');
        $data = [
            'asset_id'         => $assetId,
            'maintenance_type' => $this->input->post('maintenance_type'),
            'maintenance_date' => $this->input->post('maintenance_date'),
            'cost'             => $this->input->post('cost') ?: null,
            'done_by'          => $this->input->post('done_by'),
            'description'      => $this->input->post('description'),
            'next_due_date'    => $this->input->post('next_due_date') ?: null,
            'recorded_by'      => get_loggedin_user_id(),
            'branch_id'        => $branchId,
        ];
        $this->db->insert('asset_maintenance', $data);

        // Update condition if provided
        $cond = $this->input->post('condition_after');
        if ($cond) $this->db->where('id',$assetId)->update('assets',['condition_status'=>$cond]);

        echo json_encode(['status'=>'success','url'=>base_url('assets/maintenance/'.$assetId)]);
    }

    public function delete_maintenance($id) {
        $row = $this->db->where('id',$id)->get('asset_maintenance')->row();
        $assetId = $row ? $row->asset_id : 0;
        $this->db->where('id',$id)->delete('asset_maintenance');
        echo json_encode(['status'=>'success','url'=>base_url('assets/maintenance/'.$assetId)]);
    }

    // ── Inventory ───────────────────────────────────────────────────────
    public function inventory() {
        $branchId = $this->_branchId();
        $items    = $this->db->where('branch_id',$branchId)->order_by('name')->get('inventory_items')->result();

        $this->data['items']     = $items;
        $this->data['title']     = 'Inventory';
        $this->data['main_menu'] = 'assets';
        $this->data['sub_page']  = 'assets/inventory';
        $this->load->view('layout/index', $this->data);
    }

    public function save_item() {
        if (!$this->input->is_ajax_request()) show_404();
        $branchId = $this->_branchId();

        $this->form_validation->set_rules('name','Item Name','required|max_length[300]');
        if (!$this->form_validation->run()) {
            echo json_encode(['status'=>'error','msg'=>validation_errors()]); return;
        }

        $data = [
            'name'              => $this->input->post('name'),
            'category'          => $this->input->post('category'),
            'unit'              => $this->input->post('unit'),
            'quantity_in_stock' => $this->input->post('quantity_in_stock') ?: 0,
            'reorder_level'     => $this->input->post('reorder_level') ?: 5,
            'unit_cost'         => $this->input->post('unit_cost') ?: null,
            'supplier'          => $this->input->post('supplier'),
            'location'          => $this->input->post('location'),
            'branch_id'         => $branchId,
        ];

        $id = $this->input->post('id');
        $id ? $this->db->where('id',$id)->where('branch_id',$branchId)->update('inventory_items',$data)
            : $this->db->insert('inventory_items',$data);
        echo json_encode(['status'=>'success','url'=>base_url('assets/inventory')]);
    }

    public function delete_item($id) {
        $branchId = $this->_branchId();
        $this->db->where('item_id',$id)->delete('inventory_transactions');
        $this->db->where('id',$id)->where('branch_id',$branchId)->delete('inventory_items');
        echo json_encode(['status'=>'success','url'=>base_url('assets/inventory')]);
    }

    public function stock_transaction() {
        if (!$this->input->is_ajax_request()) show_404();
        $branchId = $this->_branchId();

        $rules = [
            ['field'=>'item_id',          'label'=>'Item',      'rules'=>'required'],
            ['field'=>'transaction_type', 'label'=>'Type',      'rules'=>'required'],
            ['field'=>'quantity',         'label'=>'Quantity',  'rules'=>'required|is_natural_no_zero'],
        ];
        $this->form_validation->set_rules($rules);
        if (!$this->form_validation->run()) {
            echo json_encode(['status'=>'error','msg'=>validation_errors()]); return;
        }

        $itemId = $this->input->post('item_id');
        $type   = $this->input->post('transaction_type');
        $qty    = (int)$this->input->post('quantity');

        $item = $this->db->where('id',$itemId)->where('branch_id',$branchId)->get('inventory_items')->row();
        if (!$item) { echo json_encode(['status'=>'error','msg'=>'Item not found']); return; }

        $newQty = $item->quantity_in_stock;
        if ($type === 'stock_in')  $newQty += $qty;
        if ($type === 'stock_out') {
            if ($qty > $item->quantity_in_stock) {
                echo json_encode(['status'=>'error','msg'=>'Insufficient stock (available: '.$item->quantity_in_stock.')']); return;
            }
            $newQty -= $qty;
        }
        if ($type === 'adjustment') $newQty = $qty;

        $this->db->where('id',$itemId)->update('inventory_items',['quantity_in_stock'=>$newQty]);
        $this->db->insert('inventory_transactions',[
            'item_id'          => $itemId,
            'transaction_type' => $type,
            'quantity'         => $qty,
            'balance_after'    => $newQty,
            'reference_no'     => $this->input->post('reference_no'),
            'description'      => $this->input->post('description'),
            'recorded_by'      => get_loggedin_user_id(),
            'branch_id'        => $branchId,
        ]);
        echo json_encode(['status'=>'success','url'=>base_url('assets/inventory')]);
    }
}
