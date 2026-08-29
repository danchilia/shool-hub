<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact_model extends MY_Model {

    public function save_request($data) {
        $this->db->insert('contact_requests', $data);
        return $this->db->insert_id();
    }

    public function get_all() {
        return $this->db->order_by('created_at', 'DESC')->get('contact_requests')->result_array();
    }

    public function get_request($id) {
        return $this->db->get_where('contact_requests', ['id' => $id])->row_array();
    }

    public function mark_read($id) {
        $this->db->update('contact_requests', ['is_read' => 1], ['id' => $id]);
    }

    public function delete($id) {
        $this->db->delete('contact_requests', ['id' => $id]);
    }

    public function unread_count() {
        return $this->db->where('is_read', 0)->count_all_results('contact_requests');
    }
}
