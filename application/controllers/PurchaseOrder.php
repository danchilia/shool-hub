<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PurchaseOrder extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Load necessary models/helpers/libraries here
        $this->load->model('PurchaseOrder_model');
    }

    public function index() {
        $data['orders'] = $this->PurchaseOrder_model->get_all_orders();
        $this->load->view('purchase_order_dashboard', $data);
    }
}
