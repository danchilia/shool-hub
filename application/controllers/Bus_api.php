<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Public GPS endpoint — extends CI_Controller directly so GPS devices
 * and driver apps can call it without a login session.
 * Auth is done via a per-bus gps_token instead.
 */
class Bus_api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Return JSON, never HTML
        $this->output->set_content_type('application/json');
    }

    /**
     * GPS device / driver app calls this to push a location update.
     *
     * GET or POST:
     *   token   = bus gps_token  (required — authenticates the bus)
     *   lat     = latitude       (required)
     *   lng     = longitude      (required)
     *   speed   = speed km/h     (optional)
     *
     * Example:
     *   GET /bus-api/location?token=abc123&lat=-1.286&lng=36.817&speed=40
     */
    public function location() {
        $token = $this->input->get_post('token');
        $lat   = $this->input->get_post('lat');
        $lng   = $this->input->get_post('lng');
        $speed = $this->input->get_post('speed');

        if (!$token || !$lat || !$lng) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'msg' => 'token, lat and lng are required']);
            return;
        }

        $bus = $this->db->where('gps_token', $token)->get('school_buses')->row();
        if (!$bus) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'msg' => 'Invalid token']);
            return;
        }

        $this->db->insert('bus_locations', [
            'bus_id'      => $bus->id,
            'latitude'    => (float) $lat,
            'longitude'   => (float) $lng,
            'speed'       => $speed !== null ? (float) $speed : null,
            'recorded_at' => date('Y-m-d H:i:s'),
            'branch_id'   => $bus->branch_id,
        ]);

        echo json_encode(['status' => 'success', 'bus' => $bus->bus_name]);
    }
}
