<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Lpo_model extends MY_Model
{
    public function generateLpoNumber($branchId)
    {
        $branch = $this->db->select('name')->where('id', $branchId)->get('branch')->row();
        $prefix = $branch ? strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $branch->name), 0, 3)) : 'LPO';
        $year = date('Y');
        $result = $this->db->select('MAX(id) as max_id')->where('branch_id', $branchId)->get('purchase_orders')->row();
        $next = ($result && $result->max_id) ? $result->max_id + 1 : 1;
        return $prefix . '/LPO/' . $year . '/' . str_pad($next, 3, '0', STR_PAD_LEFT);
    }

    public function savePurchaseOrder($data, $items)
    {
        $branchId = $this->application_model->get_branch_id();
        $order = array(
            'lpo_number' => $data['lpo_number'],
            'supplier_id' => $data['supplier_id'],
            'order_date' => date('Y-m-d', strtotime($data['order_date'])),
            'valid_until' => !empty($data['valid_until']) ? date('Y-m-d', strtotime($data['valid_until'])) : null,
            'delivery_date' => !empty($data['delivery_date']) ? date('Y-m-d', strtotime($data['delivery_date'])) : null,
            'delivery_address' => isset($data['delivery_address']) ? $data['delivery_address'] : 'School Gate',
            'voucher_head_id' => !empty($data['voucher_head_id']) ? $data['voucher_head_id'] : null,
            'notes' => isset($data['notes']) ? $data['notes'] : '',
            'prepared_by' => get_loggedin_user_id(),
            'status' => 'draft',
            'branch_id' => $branchId,
            'session_id' => get_session_id(),
        );

        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += floatval($item['quantity']) * floatval($item['unit_price']);
        }
        $taxAmount = isset($data['tax_amount']) ? floatval($data['tax_amount']) : 0;
        $order['subtotal'] = $subtotal;
        $order['tax_amount'] = $taxAmount;
        $order['total_amount'] = $subtotal + $taxAmount;

        if (!isset($data['po_id']) || empty($data['po_id'])) {
            $this->db->insert('purchase_orders', $order);
            $poId = $this->db->insert_id();
        } else {
            $poId = $data['po_id'];
            $this->db->where('id', $poId);
            $this->db->update('purchase_orders', $order);
            $this->db->where('purchase_order_id', $poId);
            $this->db->delete('purchase_order_items');
        }

        foreach ($items as $item) {
            if (!empty($item['item_name'])) {
                $qty = floatval($item['quantity']);
                $price = floatval($item['unit_price']);
                $this->db->insert('purchase_order_items', array(
                    'purchase_order_id' => $poId,
                    'item_name' => $item['item_name'],
                    'description' => isset($item['description']) ? $item['description'] : '',
                    'quantity' => $qty,
                    'unit' => isset($item['unit']) ? $item['unit'] : 'pcs',
                    'unit_price' => $price,
                    'total_price' => $qty * $price,
                ));
            }
        }
        return $poId;
    }

    public function getPurchaseOrders($branchId, $status = '', $sessionId = '')
    {
        $this->db->select('po.*, s.name as supplier_name, s.phone as supplier_phone, st.name as prepared_by_name, ap.name as approved_by_name');
        $this->db->from('purchase_orders as po');
        $this->db->join('suppliers as s', 's.id = po.supplier_id', 'left');
        $this->db->join('staff as st', 'st.id = po.prepared_by', 'left');
        $this->db->join('staff as ap', 'ap.id = po.approved_by', 'left');
        $this->db->where('po.branch_id', $branchId);
        if (!empty($status)) {
            $this->db->where('po.status', $status);
        }
        if (!empty($sessionId)) {
            $this->db->where('po.session_id', $sessionId);
        }
        $this->db->order_by('po.id', 'DESC');
        return $this->db->get()->result_array();
    }

    public function getPurchaseOrder($id)
    {
        $this->db->select('po.*, s.name as supplier_name, s.phone as supplier_phone, s.email as supplier_email, s.address as supplier_address, s.kra_pin as supplier_kra, s.contact_person, st.name as prepared_by_name, ap.name as approved_by_name, rv.name as receiver_name, pd.name as paid_by_name, b.school_name, b.address as school_address, b.mobileno as school_phone, b.email as school_email, vh.name as voucher_head_name');
        $this->db->from('purchase_orders as po');
        $this->db->join('suppliers as s', 's.id = po.supplier_id', 'left');
        $this->db->join('staff as st', 'st.id = po.prepared_by', 'left');
        $this->db->join('staff as ap', 'ap.id = po.approved_by', 'left');
        $this->db->join('staff as rv', 'rv.id = po.received_by', 'left');
        $this->db->join('staff as pd', 'pd.id = po.paid_by', 'left');
        $this->db->join('branch as b', 'b.id = po.branch_id', 'left');
        $this->db->join('voucher_head as vh', 'vh.id = po.voucher_head_id', 'left');
        $this->db->where('po.id', $id);
        return $this->db->get()->row_array();
    }

    public function getOrderItems($poId)
    {
        return $this->db->where('purchase_order_id', $poId)->get('purchase_order_items')->result_array();
    }

    public function updateStatus($id, $status, $extraData = array())
    {
        $update = array('status' => $status);
        $update = array_merge($update, $extraData);
        $this->db->where('id', $id);
        $this->db->update('purchase_orders', $update);
    }

    public function getSuppliers($branchId)
    {
        return $this->db->where('branch_id', $branchId)->order_by('name', 'ASC')->get('suppliers')->result_array();
    }

    public function saveSupplier($data)
    {
        $array = array(
            'name' => $data['name'],
            'contact_person' => isset($data['contact_person']) ? $data['contact_person'] : '',
            'phone' => isset($data['phone']) ? $data['phone'] : '',
            'email' => isset($data['email']) ? $data['email'] : '',
            'kra_pin' => isset($data['kra_pin']) ? $data['kra_pin'] : '',
            'address' => isset($data['address']) ? $data['address'] : '',
            'city' => isset($data['city']) ? $data['city'] : '',
            'notes' => isset($data['notes']) ? $data['notes'] : '',
            'branch_id' => $this->application_model->get_branch_id(),
        );
        if (!isset($data['supplier_id']) || empty($data['supplier_id'])) {
            $this->db->insert('suppliers', $array);
            return $this->db->insert_id();
        } else {
            $this->db->where('id', $data['supplier_id']);
            $this->db->update('suppliers', $array);
            return $data['supplier_id'];
        }
    }

    public function getPendingCount($branchId)
    {
        return $this->db->where(array('branch_id' => $branchId, 'status' => 'pending_approval'))->count_all_results('purchase_orders');
    }

    public function getLpoSummary($branchId, $sessionId)
    {
        $summary = array();
        $summary['total'] = $this->db->where(array('branch_id' => $branchId, 'session_id' => $sessionId))->count_all_results('purchase_orders');
        $summary['pending'] = $this->db->where(array('branch_id' => $branchId, 'session_id' => $sessionId, 'status' => 'pending_approval'))->count_all_results('purchase_orders');
        $summary['approved'] = $this->db->where(array('branch_id' => $branchId, 'session_id' => $sessionId))->where_in('status', array('approved', 'sent'))->count_all_results('purchase_orders');
        $r = $this->db->select('SUM(total_amount) as total')->where(array('branch_id' => $branchId, 'session_id' => $sessionId))->where_in('status', array('approved', 'sent', 'delivered', 'partially_delivered'))->get('purchase_orders')->row();
        $summary['committed_amount'] = $r ? floatval($r->total) : 0;
        $r2 = $this->db->select('SUM(total_amount) as total')->where(array('branch_id' => $branchId, 'session_id' => $sessionId, 'status' => 'paid'))->get('purchase_orders')->row();
        $summary['paid_amount'] = $r2 ? floatval($r2->total) : 0;
        return $summary;
    }
}
