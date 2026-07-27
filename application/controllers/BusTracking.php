<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BusTracking extends Admin_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $branchId = get_loggedin_branch_id();
        $buses = $this->db->where('branch_id', $branchId)->where('is_active', 1)->get('school_buses')->result();

        // Single query: latest location per bus (avoids N+1)
        $locs = $this->db->query(
            'SELECT bl.* FROM bus_locations bl
             INNER JOIN (SELECT bus_id, MAX(recorded_at) AS max_at FROM bus_locations GROUP BY bus_id) x
             ON bl.bus_id = x.bus_id AND bl.recorded_at = x.max_at'
        )->result();
        $locMap = [];
        foreach ($locs as $l) { $locMap[$l->bus_id] = $l; }
        foreach ($buses as $bus) { $bus->last_location = $locMap[$bus->id] ?? null; }

        $this->data['buses']     = $buses;
        $this->data['title']     = 'GPS Bus Tracking';
        $this->data['main_menu'] = 'bus_tracking';
        $this->data['sub_page']  = 'bus_tracking/index';
        $this->load->view('layout/index', $this->data);
    }

    public function manage() {
        $branchId = get_loggedin_branch_id();
        $buses    = $this->db->where('branch_id', $branchId)->get('school_buses')->result();

        $this->data['buses']     = $buses;
        $this->data['title']     = 'Manage Buses';
        $this->data['main_menu'] = 'bus_tracking';
        $this->data['sub_page']  = 'bus_tracking/manage';
        $this->load->view('layout/index', $this->data);
    }

    public function save_bus() {
        if (!$this->input->is_ajax_request()) show_404();
        $branchId = get_loggedin_branch_id();

        $rules = [
            ['field'=>'bus_name',   'label'=>'Bus Name',  'rules'=>'required|max_length[100]'],
            ['field'=>'reg_number', 'label'=>'Reg Number','rules'=>'required|max_length[50]'],
        ];
        $this->form_validation->set_rules($rules);
        if (!$this->form_validation->run()) {
            echo json_encode(['status'=>'error','msg'=>validation_errors()]); return;
        }

        $data = [
            'bus_name'          => $this->input->post('bus_name'),
            'reg_number'        => $this->input->post('reg_number'),
            'capacity'          => $this->input->post('capacity') ?: null,
            'driver_name'       => $this->input->post('driver_name'),
            'driver_phone'      => $this->input->post('driver_phone'),
            'route_description' => $this->input->post('route_description'),
            'is_active'         => $this->input->post('is_active') ? 1 : 0,
            'branch_id'         => $branchId,
        ];

        $id = $this->input->post('id');
        if ($id) {
            $this->db->where('id', $id)->where('branch_id', $branchId)->update('school_buses', $data);
        } else {
            // Generate a unique token for this bus so the GPS endpoint can authenticate it
            $data['gps_token'] = bin2hex(random_bytes(16));
            $this->db->insert('school_buses', $data);
        }
        echo json_encode(['status'=>'success','url'=>base_url('bus_tracking/manage')]);
    }

    public function delete_bus($id) {
        $branchId = get_loggedin_branch_id();
        $this->db->where('bus_id',$id)->delete('bus_locations');
        $this->db->where('id',$id)->where('branch_id',$branchId)->delete('school_buses');
        echo json_encode(['status'=>'success','url'=>base_url('bus_tracking/manage')]);
    }

    // AJAX: get latest location for all active buses (parent live view)
    public function live_positions() {
        $branchId = get_loggedin_branch_id();
        $buses    = $this->db->where('branch_id',$branchId)->where('is_active',1)->get('school_buses')->result();
        $result   = [];

        // Single query: latest location per bus (avoids N+1)
        $locs = $this->db->query(
            'SELECT bl.* FROM bus_locations bl
             INNER JOIN (SELECT bus_id, MAX(recorded_at) AS max_at FROM bus_locations GROUP BY bus_id) x
             ON bl.bus_id = x.bus_id AND bl.recorded_at = x.max_at'
        )->result();
        $locMap = [];
        foreach ($locs as $l) { $locMap[$l->bus_id] = $l; }

        foreach ($buses as $bus) {
            $loc = $locMap[$bus->id] ?? null;
            $result[] = [
                'id'          => $bus->id,
                'name'        => $bus->bus_name,
                'reg'         => $bus->reg_number,
                'driver'      => $bus->driver_name,
                'driver_phone'=> $bus->driver_phone,
                'lat'         => $loc ? (float)$loc->latitude  : null,
                'lng'         => $loc ? (float)$loc->longitude : null,
                'speed'       => $loc ? $loc->speed : null,
                'last_seen'   => $loc ? $loc->recorded_at : null,
            ];
        }
        echo json_encode($result);
    }
}
