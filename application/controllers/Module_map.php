<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Module_map extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data['title']     = 'All Available Modules';
        $this->data['sub_page']  = 'module_map/index';
        $this->data['main_menu'] = 'module_map';
        $this->load->view('layout/index', $this->data);
    }
}
