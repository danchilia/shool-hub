<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Subscription_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function savePlan($data)
    {
        $array = array(
            'name' => $data['name'],
            'description' => isset($data['description']) ? $data['description'] : '',
            'max_students' => intval($data['max_students']),
            'max_staff' => intval($data['max_staff']),
            'monthly_price' => floatval($data['monthly_price']),
            'yearly_price' => floatval($data['yearly_price']),
            'is_active' => isset($data['is_active']) ? 1 : 0,
        );
        if (!isset($data['plan_id'])) {
            $this->db->insert('subscription_plans', $array);
        } else {
            $this->db->where('id', $data['plan_id']);
            $this->db->update('subscription_plans', $array);
        }
    }

    public function getPlans($activeOnly = false)
    {
        if ($activeOnly) {
            $this->db->where('is_active', 1);
        }
        return $this->db->get('subscription_plans')->result_array();
    }

    public function assignPlan($branchId, $planId, $cycle, $startDate = '')
    {
        if (empty($startDate)) {
            $startDate = date('Y-m-d');
        }
        if ($cycle == 'yearly') {
            $endDate = date('Y-m-d', strtotime($startDate . ' +1 year'));
        } else {
            $endDate = date('Y-m-d', strtotime($startDate . ' +1 month'));
        }

        $this->db->where('branch_id', $branchId);
        $this->db->where_in('status', array('active', 'trial'));
        $this->db->update('branch_subscriptions', array('status' => 'expired'));

        $insert = array(
            'branch_id' => $branchId,
            'plan_id' => $planId,
            'billing_cycle' => $cycle,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'active',
        );
        $this->db->insert('branch_subscriptions', $insert);
        $subId = $this->db->insert_id();

        $plan = $this->db->get_where('subscription_plans', array('id' => $planId))->row();
        $amount = ($cycle == 'yearly') ? $plan->yearly_price : $plan->monthly_price;
        $this->db->insert('subscription_invoices', array(
            'subscription_id' => $subId,
            'branch_id' => $branchId,
            'amount' => $amount,
            'due_date' => $startDate,
            'status' => 'pending',
        ));

        return $subId;
    }

    public function getActiveSubscription($branchId)
    {
        $this->db->select('bs.*, sp.name as plan_name, sp.max_students, sp.max_staff, sp.monthly_price, sp.yearly_price');
        $this->db->from('branch_subscriptions as bs');
        $this->db->join('subscription_plans as sp', 'sp.id = bs.plan_id', 'left');
        $this->db->where('bs.branch_id', $branchId);
        $this->db->where_in('bs.status', array('active', 'trial'));
        $this->db->order_by('bs.id', 'DESC');
        $this->db->limit(1);
        return $this->db->get()->row_array();
    }

    public function isSubscriptionActive($branchId)
    {
        $sub = $this->getActiveSubscription($branchId);
        if (!$sub) {
            return true; // no subscription system enforced yet
        }
        if ($sub['status'] == 'active' || $sub['status'] == 'trial') {
            if (strtotime($sub['end_date']) >= strtotime(date('Y-m-d'))) {
                return true;
            }
        }
        return false;
    }

    public function checkStudentLimit($branchId)
    {
        $sub = $this->getActiveSubscription($branchId);
        if (!$sub || $sub['max_students'] == 0) {
            return true;
        }
        $currentCount = $this->db->where('branch_id', $branchId)
            ->where('session_id', get_session_id())
            ->count_all_results('enroll');
        return $currentCount < $sub['max_students'];
    }

    public function checkStaffLimit($branchId)
    {
        $sub = $this->getActiveSubscription($branchId);
        if (!$sub || $sub['max_staff'] == 0) {
            return true;
        }
        $currentCount = $this->db->where('branch_id', $branchId)
            ->count_all_results('staff');
        return $currentCount < $sub['max_staff'];
    }

    public function getAllSubscriptions()
    {
        $this->db->select('bs.*, sp.name as plan_name, b.name as branch_name, b.school_name');
        $this->db->from('branch_subscriptions as bs');
        $this->db->join('subscription_plans as sp', 'sp.id = bs.plan_id', 'left');
        $this->db->join('branch as b', 'b.id = bs.branch_id', 'left');
        $this->db->order_by('bs.id', 'DESC');
        return $this->db->get()->result_array();
    }

    public function getInvoices($branchId = '')
    {
        $this->db->select('si.*, b.name as branch_name, b.school_name, sp.name as plan_name');
        $this->db->from('subscription_invoices as si');
        $this->db->join('branch as b', 'b.id = si.branch_id', 'left');
        $this->db->join('branch_subscriptions as bs', 'bs.id = si.subscription_id', 'left');
        $this->db->join('subscription_plans as sp', 'sp.id = bs.plan_id', 'left');
        if (!empty($branchId)) {
            $this->db->where('si.branch_id', $branchId);
        }
        $this->db->order_by('si.due_date', 'DESC');
        return $this->db->get()->result_array();
    }

    public function markInvoicePaid($invoiceId, $reference)
    {
        $this->db->where('id', $invoiceId);
        $this->db->update('subscription_invoices', array(
            'status' => 'paid',
            'paid_date' => date('Y-m-d'),
            'payment_reference' => $reference,
        ));
    }

    public function checkExpiredSubscriptions()
    {
        $this->db->where('end_date <', date('Y-m-d'));
        $this->db->where('status', 'active');
        $this->db->update('branch_subscriptions', array('status' => 'expired'));

        $this->db->where('due_date <', date('Y-m-d'));
        $this->db->where('status', 'pending');
        $this->db->update('subscription_invoices', array('status' => 'overdue'));
    }
}
