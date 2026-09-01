<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Agent_model extends CI_Model
{
    // ─── DCK PLANS ─────────────────────────────────────────────────

    public function getPlans($activeOnly = false)
    {
        if ($activeOnly) {
            $this->db->where('active', 1);
        }
        return $this->db->order_by('price', 'ASC')->get('dck_plan')->result_array();
    }

    public function getPlan($id)
    {
        return $this->db->get_where('dck_plan', array('id' => $id))->row_array();
    }

    public function savePlan($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert('dck_plan', $data);
        return $this->db->insert_id();
    }

    public function updatePlan($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id)->update('dck_plan', $data);
    }

    public function deletePlan($id)
    {
        $this->db->delete('dck_plan', array('id' => $id));
    }

    // ─── AGENTS ────────────────────────────────────────────────────

    public function getAgentByEmail($email)
    {
        return $this->db->get_where('agent', array('email' => $email, 'active' => 1))->row_array();
    }

    public function getAgent($id)
    {
        return $this->db->get_where('agent', array('id' => $id))->row_array();
    }

    public function getAllAgents()
    {
        return $this->db->order_by('first_name', 'ASC')->get('agent')->result_array();
    }

    public function createAgent($agentData, $password)
    {
        $agentData['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert('agent', $agentData);
        $agentId = $this->db->insert_id();

        $this->load->library('app_lib');
        $this->db->insert('login_credential', array(
            'user_id'  => $agentId,
            'username' => $agentData['email'],
            'password' => $this->app_lib->pass_hashed($password),
            'role'     => 8,
            'active'   => 1,
        ));

        return $agentId;
    }

    public function updateAgent($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id)->update('agent', $data);
        if (!empty($data['email'])) {
            $this->db->where('user_id', $id)->where('role', 8)
                     ->update('login_credential', array('username' => $data['email']));
        }
    }

    public function toggleActive($id)
    {
        $agent  = $this->getAgent($id);
        $newVal = $agent['active'] ? 0 : 1;
        $now    = date('Y-m-d H:i:s');
        $this->db->where('id', $id)->update('agent', array('active' => $newVal, 'updated_at' => $now));
        $this->db->where('user_id', $id)->where('role', 8)->update('login_credential', array('active' => $newVal));
    }

    public function resetPassword($agentId, $newPassword)
    {
        $this->load->library('app_lib');
        $this->db->where('user_id', $agentId)->where('role', 8)
                 ->update('login_credential', array('password' => $this->app_lib->pass_hashed($newPassword)));
    }

    // ─── PROSPECT SCHOOLS ──────────────────────────────────────────

    public function addSchool($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert('agent_school', $data);
        return $this->db->insert_id();
    }

    public function getSchools($agentId = null, $status = '', $search = '')
    {
        $this->db->select('s.*, a.first_name, a.last_name, p.name as plan_name');
        $this->db->from('agent_school s');
        $this->db->join('agent a', 'a.id = s.agent_id', 'left');
        $this->db->join('dck_plan p', 'p.id = s.assigned_plan_id', 'left');
        if ($agentId) {
            $this->db->where('s.agent_id', $agentId);
        }
        if ($status) {
            $this->db->where('s.status', $status);
        }
        if ($search) {
            $this->db->like('s.school_name', $search);
        }
        $this->db->order_by('s.created_at', 'DESC');
        return $this->db->get()->result_array();
    }

    public function getSchool($id, $agentId = null)
    {
        $this->db->select('s.*, p.name as plan_name, a.first_name, a.last_name');
        $this->db->from('agent_school s');
        $this->db->join('dck_plan p', 'p.id = s.assigned_plan_id', 'left');
        $this->db->join('agent a', 'a.id = s.agent_id', 'left');
        $this->db->where('s.id', $id);
        if ($agentId) {
            $this->db->where('s.agent_id', $agentId);
        }
        return $this->db->get()->row_array();
    }

    public function updateSchool($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id)->update('agent_school', $data);
    }

    // ─── VISITS ────────────────────────────────────────────────────

    public function logVisit($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert('agent_visit', $data);
        return $this->db->insert_id();
    }

    public function getVisits($agentId = null, $schoolId = null, $limit = 0)
    {
        $this->db->select('v.*, s.school_name, p.name as plan_name');
        $this->db->from('agent_visit v');
        $this->db->join('agent_school s', 's.id = v.school_id', 'left');
        $this->db->join('dck_plan p', 'p.id = v.plan_id', 'left');
        if ($agentId) {
            $this->db->where('v.agent_id', $agentId);
        }
        if ($schoolId) {
            $this->db->where('v.school_id', $schoolId);
        }
        $this->db->order_by('v.visit_date', 'DESC');
        if ($limit) {
            $this->db->limit($limit);
        }
        return $this->db->get()->result_array();
    }

    public function getFollowUps($agentId)
    {
        $this->db->select('v.*, s.school_name, s.principal_name, s.phone, s.county');
        $this->db->from('agent_visit v');
        $this->db->join('agent_school s', 's.id = v.school_id', 'left');
        $this->db->where('v.agent_id', $agentId);
        $this->db->where('v.next_followup_date IS NOT NULL', null, false);
        $this->db->where('v.outcome', 'needs_followup');
        $this->db->order_by('v.next_followup_date', 'ASC');
        return $this->db->get()->result_array();
    }

    // ─── EARNINGS ──────────────────────────────────────────────────

    public function addEarning($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert('agent_earning', $data);
        return $this->db->insert_id();
    }

    public function getEarnings($agentId = null, $status = '')
    {
        $this->db->select('e.*, a.first_name, a.last_name, s.school_name');
        $this->db->from('agent_earning e');
        $this->db->join('agent a', 'a.id = e.agent_id', 'left');
        $this->db->join('agent_school s', 's.id = e.school_id', 'left');
        if ($agentId) {
            $this->db->where('e.agent_id', $agentId);
        }
        if ($status) {
            $this->db->where('e.status', $status);
        }
        $this->db->order_by('e.created_at', 'DESC');
        return $this->db->get()->result_array();
    }

    public function updateEarningStatus($id, $status, $paidBy = null)
    {
        $upd = array('status' => $status, 'updated_at' => date('Y-m-d H:i:s'));
        if ($status === 'paid') {
            $upd['paid_date'] = date('Y-m-d');
            $upd['paid_by']   = $paidBy;
        }
        $this->db->where('id', $id)->update('agent_earning', $upd);
    }

    // ─── EXPENSES ──────────────────────────────────────────────────

    public function addExpense($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert('agent_expense', $data);
        return $this->db->insert_id();
    }

    public function getExpenses($agentId = null, $status = '')
    {
        $this->db->select('ex.*, a.first_name, a.last_name, s.school_name');
        $this->db->from('agent_expense ex');
        $this->db->join('agent a', 'a.id = ex.agent_id', 'left');
        $this->db->join('agent_school s', 's.id = ex.school_id', 'left');
        if ($agentId) {
            $this->db->where('ex.agent_id', $agentId);
        }
        if ($status) {
            $this->db->where('ex.status', $status);
        }
        $this->db->order_by('ex.created_at', 'DESC');
        return $this->db->get()->result_array();
    }

    public function updateExpenseStatus($id, $status, $reviewedBy, $note = '')
    {
        $this->db->where('id', $id)->update('agent_expense', array(
            'status'      => $status,
            'reviewed_by' => $reviewedBy,
            'reviewed_at' => date('Y-m-d H:i:s'),
            'review_note' => $note,
            'updated_at'  => date('Y-m-d H:i:s'),
        ));
    }

    // ─── LEVELS ────────────────────────────────────────────────────

    public function getAgentLevelData($agentId)
    {
        $activeSchools = $this->db->where(array('agent_id' => $agentId, 'status' => 'closed_won'))
                                  ->count_all_results('agent_school');

        // Level definitions: min_schools => [name, badge_color, has_contract]
        $levels = array(
            array('min' => 0,  'name' => 'Starter',      'color' => '#7f8c8d', 'contract' => false),
            array('min' => 10, 'name' => 'Level 1',      'color' => '#cd7f32', 'contract' => true),
            array('min' => 15, 'name' => 'Level 2',      'color' => '#95a5a6', 'contract' => true),
            array('min' => 20, 'name' => 'Level 3',      'color' => '#f1c40f', 'contract' => true),
            array('min' => 25, 'name' => 'Level 4',      'color' => '#2ecc71', 'contract' => true),
            array('min' => 30, 'name' => 'Level 5',      'color' => '#3498db', 'contract' => true),
            array('min' => 35, 'name' => 'Level 6',      'color' => '#9b59b6', 'contract' => true),
            array('min' => 40, 'name' => 'Level 7',      'color' => '#e67e22', 'contract' => true),
            array('min' => 45, 'name' => 'Level 8',      'color' => '#e74c3c', 'contract' => true),
            array('min' => 50, 'name' => 'Legend',       'color' => '#c9a84c', 'contract' => true),
        );

        // Find current level
        $current = $levels[0];
        $currentIdx = 0;
        foreach ($levels as $i => $lvl) {
            if ($activeSchools >= $lvl['min']) {
                $current = $lvl;
                $currentIdx = $i;
            }
        }

        // Monthly salary: 0 if under 10, else schools * 1000 capped at 50000
        $salary = ($activeSchools < 10) ? 0 : min($activeSchools * 1000, 50000);

        // Next level
        $nextLevel = isset($levels[$currentIdx + 1]) ? $levels[$currentIdx + 1] : null;
        $schoolsToNext = $nextLevel ? ($nextLevel['min'] - $activeSchools) : 0;

        // Progress bar percent toward next level
        $progressMin = $current['min'];
        $progressMax = $nextLevel ? $nextLevel['min'] : $current['min'];
        $progressPct = ($nextLevel && $progressMax > $progressMin)
            ? min(100, round(($activeSchools - $progressMin) / ($progressMax - $progressMin) * 100))
            : 100;

        return array(
            'active_schools' => $activeSchools,
            'salary'         => $salary,
            'current'        => $current,
            'next_level'     => $nextLevel,
            'schools_to_next'=> $schoolsToNext,
            'progress_pct'   => $progressPct,
            'all_levels'     => $levels,
        );
    }

    // ─── STATS ─────────────────────────────────────────────────────

    public function getDashboardStats($agentId)
    {
        $total_schools = $this->db->where('agent_id', $agentId)->count_all_results('agent_school');
        $total_visits  = $this->db->where('agent_id', $agentId)->count_all_results('agent_visit');
        $closed_won    = $this->db->where(array('agent_id' => $agentId, 'status' => 'closed_won'))->count_all_results('agent_school');
        $pending       = $this->db->where('agent_id', $agentId)
                                  ->where_in('status', array('lead','visited','demo_done','proposal_sent','follow_up'))
                                  ->count_all_results('agent_school');

        $earned = $this->db->select_sum('amount')
                           ->where('agent_id', $agentId)
                           ->where_in('status', array('approved','paid'))
                           ->get('agent_earning')->row_array();
        $paid   = $this->db->select_sum('amount')
                           ->where(array('agent_id' => $agentId, 'status' => 'paid'))
                           ->get('agent_earning')->row_array();

        $overdue = $this->db->where('agent_id', $agentId)
                            ->where('next_followup_date <', date('Y-m-d'))
                            ->where('outcome', 'needs_followup')
                            ->count_all_results('agent_visit');

        return array(
            'total_schools'     => $total_schools,
            'total_visits'      => $total_visits,
            'closed_won'        => $closed_won,
            'pending'           => $pending,
            'total_earned'      => $earned['amount'] ?: 0,
            'total_paid'        => $paid['amount'] ?: 0,
            'overdue_followups' => $overdue,
            'conversion_rate'   => $total_schools > 0 ? round(($closed_won / $total_schools) * 100) : 0,
        );
    }

    public function getSuperadminStats()
    {
        $total_agents  = $this->db->count_all('agent');
        $total_schools = $this->db->count_all('agent_school');
        $total_visits  = $this->db->count_all('agent_visit');
        $total_won     = $this->db->where('status', 'closed_won')->count_all_results('agent_school');

        $pending_pay = $this->db->select_sum('amount')
                                ->where('status', 'pending')
                                ->get('agent_earning')->row_array();
        $total_comm  = $this->db->select_sum('amount')
                                ->where('type', 'commission')
                                ->where_in('status', array('approved','paid'))
                                ->get('agent_earning')->row_array();

        return array(
            'total_agents'    => $total_agents,
            'total_schools'   => $total_schools,
            'total_visits'    => $total_visits,
            'total_won'       => $total_won,
            'pending_payout'  => $pending_pay['amount'] ?: 0,
            'total_commission'=> $total_comm['amount'] ?: 0,
        );
    }

    // ─── AGREEMENTS (DIGITAL TERMS) ────────────────────────────────

    public function hasAcceptedTerms($agentId)
    {
        return $this->db->where(array('agent_id' => $agentId, 'type' => 'starter_terms'))
                        ->count_all_results('agent_agreements') > 0;
    }

    public function acceptTerms($agentId, $ip)
    {
        $this->db->insert('agent_agreements', array(
            'agent_id'    => $agentId,
            'type'        => 'starter_terms',
            'accepted_at' => date('Y-m-d H:i:s'),
            'ip_address'  => $ip,
        ));
    }

    public function getAgreement($agentId)
    {
        return $this->db->where(array('agent_id' => $agentId, 'type' => 'starter_terms'))
                        ->get('agent_agreements')->row_array();
    }

    // ─── CONTRACTS (PHYSICAL SIGNED UPLOAD) ───────────────────────

    public function getContract($agentId)
    {
        return $this->db->get_where('agent_contracts', array('agent_id' => $agentId))->row_array();
    }

    public function createContractRecord($agentId, $levelName)
    {
        $existing = $this->getContract($agentId);
        if ($existing) return;
        $this->db->insert('agent_contracts', array(
            'agent_id'   => $agentId,
            'level_name' => $levelName,
            'status'     => 'pending_upload',
            'created_at' => date('Y-m-d H:i:s'),
        ));
    }

    public function uploadContract($agentId, $filePath, $levelName)
    {
        $existing = $this->getContract($agentId);
        if ($existing) {
            $this->db->where('agent_id', $agentId)->update('agent_contracts', array(
                'file_path'   => $filePath,
                'level_name'  => $levelName,
                'uploaded_at' => date('Y-m-d H:i:s'),
                'status'      => 'uploaded',
            ));
        } else {
            $this->db->insert('agent_contracts', array(
                'agent_id'    => $agentId,
                'level_name'  => $levelName,
                'file_path'   => $filePath,
                'uploaded_at' => date('Y-m-d H:i:s'),
                'status'      => 'uploaded',
                'created_at'  => date('Y-m-d H:i:s'),
            ));
        }
    }

    public function reviewContract($agentId, $status, $note, $reviewedBy)
    {
        $this->db->where('agent_id', $agentId)->update('agent_contracts', array(
            'status'      => $status,
            'review_note' => $note,
            'reviewed_by' => $reviewedBy,
            'reviewed_at' => date('Y-m-d H:i:s'),
        ));
    }
}
