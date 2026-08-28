<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Careers_model extends MY_Model {

    // ── Positions ─────────────────────────────────────────────────────────

    public function get_all_positions($status = null) {
        if ($status) $this->db->where('status', $status);
        return $this->db->order_by('created_at', 'DESC')->get('career_positions')->result_array();
    }

    public function get_position($id) {
        return $this->db->get_where('career_positions', ['id' => $id])->row_array();
    }

    public function save_position($data, $id = null) {
        if ($id) {
            $this->db->update('career_positions', $data, ['id' => $id]);
        } else {
            $this->db->insert('career_positions', $data);
            return $this->db->insert_id();
        }
    }

    public function delete_position($id) {
        $this->db->delete('career_positions', ['id' => $id]);
    }

    public function toggle_position($id) {
        $pos = $this->get_position($id);
        $new = ($pos['status'] === 'open') ? 'closed' : 'open';
        $this->db->update('career_positions', ['status' => $new], ['id' => $id]);
    }

    // ── Applicants ────────────────────────────────────────────────────────

    public function get_applicant_by_email($email) {
        return $this->db->get_where('career_applicants', ['email' => $email])->row_array();
    }

    public function get_applicant($id) {
        return $this->db->get_where('career_applicants', ['id' => $id])->row_array();
    }

    public function register_applicant($data) {
        $data['password'] = $this->hash($data['password']);
        $this->db->insert('career_applicants', $data);
        return $this->db->insert_id();
    }

    public function login_applicant($email, $password) {
        $hashed = $this->hash($password);
        return $this->db->get_where('career_applicants', ['email' => $email, 'password' => $hashed])->row_array();
    }

    // ── Applications ──────────────────────────────────────────────────────

    public function has_applied($position_id, $applicant_id) {
        return $this->db->get_where('career_applications', [
            'position_id'  => $position_id,
            'applicant_id' => $applicant_id,
        ])->num_rows() > 0;
    }

    public function save_application($data) {
        $this->db->insert('career_applications', $data);
        return $this->db->insert_id();
    }

    public function get_application($id) {
        $this->db->select('ca.*, cp.title as position_title, cp.department, capp.full_name, capp.email, capp.phone');
        $this->db->from('career_applications ca');
        $this->db->join('career_positions cp',   'cp.id = ca.position_id');
        $this->db->join('career_applicants capp', 'capp.id = ca.applicant_id');
        $this->db->where('ca.id', $id);
        return $this->db->get()->row_array();
    }

    public function get_applications_by_position($position_id) {
        $this->db->select('ca.*, capp.full_name, capp.email, capp.phone');
        $this->db->from('career_applications ca');
        $this->db->join('career_applicants capp', 'capp.id = ca.applicant_id');
        $this->db->where('ca.position_id', $position_id);
        $this->db->order_by('ca.created_at', 'DESC');
        return $this->db->get()->result_array();
    }

    public function get_applicant_applications($applicant_id) {
        $this->db->select('ca.*, cp.title as position_title, cp.department, cp.status as position_status');
        $this->db->from('career_applications ca');
        $this->db->join('career_positions cp', 'cp.id = ca.position_id');
        $this->db->where('ca.applicant_id', $applicant_id);
        $this->db->order_by('ca.created_at', 'DESC');
        return $this->db->get()->result_array();
    }

    public function update_application_status($id, $status) {
        $this->db->update('career_applications', ['status' => $status], ['id' => $id]);
    }

    // ── Replies ───────────────────────────────────────────────────────────

    public function get_replies($application_id) {
        return $this->db->order_by('created_at', 'ASC')
            ->get_where('career_replies', ['application_id' => $application_id])
            ->result_array();
    }

    public function add_reply($data) {
        $this->db->insert('career_replies', $data);
        return $this->db->insert_id();
    }

    // ── Stats ─────────────────────────────────────────────────────────────

    public function get_stats() {
        return [
            'total_positions'    => $this->db->count_all('career_positions'),
            'open_positions'     => $this->db->get_where('career_positions', ['status' => 'open'])->num_rows(),
            'total_applications' => $this->db->count_all('career_applications'),
            'pending'            => $this->db->get_where('career_applications', ['status' => 'pending'])->num_rows(),
            'shortlisted'        => $this->db->get_where('career_applications', ['status' => 'shortlisted'])->num_rows(),
        ];
    }
}
