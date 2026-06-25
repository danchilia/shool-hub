<?php defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );

class MY_Controller extends CI_Controller {

	function __construct() {
		parent::__construct();
		
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		
		if ($this->config->item('installed') == FALSE) {
            redirect(site_url('install'));
		}
		
		$get_config = $this->db->get_where('global_settings',array('id'=>1))->row_array();
        $this->data['global_config'] = $get_config;
		$this->data['theme_config'] = $this->db->get_where('theme_settings',array('id'=>1))->row_array();

		date_default_timezone_set($get_config['timezone']);
	}

    public function get_payment_config() {
        $branchID = $this->application_model->get_branch_id();
        $this->db->where('branch_id', $branchID);
        $this->db->select('*')->from('payment_config');
        return $this->db->get()->row_array();
    }
}

class Admin_Controller extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!is_loggedin()) {
            $this->session->set_userdata('redirect_url', current_url());
            redirect(base_url('authentication'), 'refresh');
        }

        if (!is_superadmin_loggedin()) {
            $branchId = get_loggedin_branch_id();
            if (!empty($branchId)) {
                $sub = $this->db->select('status, end_date')
                    ->where('branch_id', $branchId)
                    ->order_by('id', 'DESC')
                    ->limit(1)
                    ->get('branch_subscriptions')->row();
                if ($sub && ($sub->status == 'expired' || (strtotime($sub->end_date) < time() && $sub->status == 'active'))) {
                    if ($sub->status == 'active') {
                        $this->db->where('id', $sub->id ?? 0);
                        $this->db->update('branch_subscriptions', array('status' => 'expired'));
                    }
                    $allowed = array('dashboard', 'authentication', 'profile', 'userrole');
                    $controller = $this->router->fetch_class();
                    if (!in_array(strtolower($controller), $allowed)) {
                        redirect(base_url('dashboard'));
                    }
                }
            }
        }
    }
}

class User_Controller extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!is_student_loggedin() && !is_parent_loggedin()) {
            $this->session->set_userdata('redirect_url', current_url());
            redirect(base_url('authentication'), 'refresh');
        }
    }
}

class Authentication_Controller extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('authentication_model');
    }
}