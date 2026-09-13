<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class School_directory_model extends CI_Model {

    // Normalize a school name for duplicate detection
    public function normalize($name) {
        $name = mb_strtolower(trim($name));
        $name = str_replace(['–', '—', '−'], '-', $name);   // unify dashes
        $name = preg_replace('/\s+/', ' ', $name);           // collapse spaces
        $name = preg_replace('/[^\w\s\-]/u', '', $name);     // strip punctuation except dash
        return trim($name);
    }

    // Check if a school already exists (name+area OR phone match)
    public function find_duplicate($name, $phone = '', $exclude_id = null) {
        $norm = $this->normalize($name);

        // Name+area normalized match
        $this->db->where('name_normalized', $norm);
        if ($exclude_id) $this->db->where('id !=', $exclude_id);
        $byName = $this->db->get('school_directory')->row_array();
        if ($byName) return $byName;

        // Phone match (if phone provided and not empty/generic)
        if ($phone && strlen(trim($phone)) >= 7) {
            $cleanPhone = preg_replace('/\D/', '', $phone);
            if ($cleanPhone && strlen($cleanPhone) >= 7) {
                $this->db->where("REPLACE(REPLACE(REPLACE(REPLACE(`phone`,'+',''),' ',''),'-',''),'(','') = " . $this->db->escape($cleanPhone), null, false);
                if ($exclude_id) $this->db->where('id !=', $exclude_id);
                $byPhone = $this->db->get('school_directory')->row_array();
                if ($byPhone) return $byPhone;
            }
        }

        return null;
    }

    public function insert($data) {
        $data['name_normalized'] = $this->normalize($data['school_name']);
        $data['created_at']      = date('Y-m-d H:i:s');
        $this->db->insert('school_directory', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data) {
        if (isset($data['school_name'])) {
            $data['name_normalized'] = $this->normalize($data['school_name']);
        }
        $this->db->where('id', $id)->update('school_directory', $data);
    }

    public function get($id) {
        return $this->db->get_where('school_directory', ['id' => $id])->row_array();
    }

    public function search($filters = [], $limit = 50, $offset = 0) {
        $this->db->select('sd.*, a.first_name as agent_first, a.last_name as agent_last');
        $this->db->from('school_directory sd');
        $this->db->join('agent a', 'a.id = sd.added_by', 'left');

        if (!empty($filters['region']))    $this->db->where('sd.region',    $filters['region']);
        if (!empty($filters['county']))    $this->db->where('sd.county',    $filters['county']);
        if (!empty($filters['type']))      $this->db->where('sd.type',      $filters['type']);
        if (!empty($filters['ownership'])) $this->db->where('sd.ownership', $filters['ownership']);
        if (!empty($filters['status']))    $this->db->where('sd.status',    $filters['status']);
        if (!empty($filters['q'])) {
            $this->db->group_start();
            $this->db->like('sd.school_name',   $filters['q']);
            $this->db->or_like('sd.area',        $filters['q']);
            $this->db->or_like('sd.road_location',$filters['q']);
            $this->db->group_end();
        }

        $this->db->order_by('sd.school_name', 'ASC');

        $total = $this->db->count_all_results('', false);

        $this->db->limit($limit, $offset);
        $rows = $this->db->get()->result_array();

        return ['rows' => $rows, 'total' => $total];
    }

    public function get_regions()    { return $this->db->distinct()->select('region')->order_by('region','ASC')->get('school_directory')->result_array(); }
    public function get_types()      { return $this->db->distinct()->select('type')->order_by('type','ASC')->get('school_directory')->result_array(); }
    public function get_ownerships() { return $this->db->distinct()->select('ownership')->order_by('ownership','ASC')->get('school_directory')->result_array(); }

    public function count_by_status($status) {
        return $this->db->where('status', $status)->count_all_results('school_directory');
    }

    public function delete($id) {
        $this->db->delete('school_directory', ['id' => $id]);
    }

    public function approve($id) {
        $this->db->where('id', $id)->update('school_directory', ['status' => 'active']);
    }

    public function get_pending($limit = 50) {
        $this->db->select('sd.*, a.first_name, a.last_name');
        $this->db->from('school_directory sd');
        $this->db->join('agent a', 'a.id = sd.added_by', 'left');
        $this->db->where('sd.status', 'pending_review');
        $this->db->order_by('sd.created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }
}
