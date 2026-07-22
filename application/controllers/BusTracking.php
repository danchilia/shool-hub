<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BusTracking extends Admin_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $branchId = get_loggedin_branch_id();
        $buses = $this->db->where('branch_id', $branchId)->where('is_active', 1)->get('school_buses')->result();

        // Get last known location for each bus
        foreach ($buses as $bus) {
            $loc = $this->db->where('bus_id', $bus->id)
                            ->order_by('recorded_at', 'DESC')
                            ->limit(1)->get('bus_locations')->row();
            $bus->last_location = $loc;
        }

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
        $id ? $this->db->where('id',$id)->where('branch_id',$branchId)->update('school_buses',$data)
            : $this->db->insert('school_buses',$data);
        echo json_encode(['status'=>'success','url'=>base_url('bus_tracking/manage')]);
    }

    public function delete_bus($id) {
        $branchId = get_loggedin_branch_id();
        $this->db->where('bus_id',$id)->delete('bus_locations');
        $this->db->where('id',$id)->where('branch_id',$branchId)->delete('school_buses');
        echo json_encode(['status'=>'success','url'=>base_url('bus_tracking/manage')]);
    }

    // Driver app / GPS device calls this to update location
    public function update_location() {
        // Accepts GET or POST — GPS devices use GET with token
        $busId = $this->input->get_post('bus_id');
        $lat   = $this->input->get_post('lat');
        $lng   = $this->input->get_post('lng');
        $speed = $this->input->get_post('speed');

        if (!$busId || !$lat || !$lng) {
            echo json_encode(['status'=>'error','msg'=>'Missing data']); return;
        }

        $bus = $this->db->where('id',$busId)->get('school_buses')->row();
        if (!$bus) { echo json_encode(['status'=>'error','msg'=>'Bus not found']); return; }

        $this->db->insert('bus_locations',[
            'bus_id'      => $busId,
            'latitude'    => $lat,
            'longitude'   => $lng,
            'speed'       => $speed ?: null,
            'recorded_at' => date('Y-m-d H:i:s'),
            'branch_id'   => $bus->branch_id,
        ]);
        echo json_encode(['status'=>'success']);
    }

    // AJAX: get latest location for all active buses (parent live view)
    public function live_positions() {
        $branchId = get_loggedin_branch_id();
        $buses    = $this->db->where('branch_id',$branchId)->where('is_active',1)->get('school_buses')->result();
        $result   = [];

        foreach ($buses as $bus) {
            $loc = $this->db->where('bus_id',$bus->id)->order_by('recorded_at','DESC')->limit(1)->get('bus_locations')->row();
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
