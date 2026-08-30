<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Help extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (is_teacher_loggedin()) {
            $role = 'teacher';
        } elseif (is_superadmin_loggedin()) {
            $role = 'superadmin';
        } else {
            $role = 'admin';
        }

        $this->data['role']        = $role;
        $this->data['is_uni']      = is_university_branch();
        $this->data['title']       = 'User Guide';
        $this->data['sub_page']    = 'help/index';
        $this->data['main_menu']   = 'help';
        $this->load->view('layout/index', $this->data);
    }
}
