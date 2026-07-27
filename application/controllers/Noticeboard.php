<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Noticeboard extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $branchId = get_loggedin_branch_id();
        $role     = get_loggedin_user_type(); // student, parent, staff, teacher, admin

        $this->db->where('branch_id', $branchId)
            ->where('notice_date <=', date('Y-m-d'))
            ->group_start()
                ->where('expiry_date IS NULL')
                ->or_where('expiry_date >=', date('Y-m-d'))
            ->group_end();

        // Filter by audience based on logged-in role
        if (is_student_loggedin() || is_parent_loggedin()) {
            $this->db->group_start()
                ->where_in('audience', array('all', is_student_loggedin() ? 'students' : 'parents'))
                ->or_like('audience', 'all')
            ->group_end();
        }

        $notices = $this->db->order_by('priority DESC, notice_date DESC')
            ->get('notice_board')->result_array();

        $this->data['notices']    = $notices;
        $this->data['sub_page']   = 'noticeboard/index';
        $this->data['main_menu']  = 'noticeboard';
        $this->data['title']      = 'Notice Board';
        $this->load->view('layout/index', $this->data);
    }

    public function manage()
    {
        if (!is_admin_loggedin() && !is_superadmin_loggedin() && !is_teacher_loggedin()) {
            access_denied();
        }
        $branchId = get_loggedin_branch_id();
        $notices  = $this->db->where('branch_id', $branchId)
            ->order_by('id', 'DESC')
            ->get('notice_board')->result_array();

        $this->data['notices']    = $notices;
        $this->data['sub_page']   = 'noticeboard/manage';
        $this->data['main_menu']  = 'noticeboard';
        $this->data['title']      = 'Manage Notices';
        $this->load->view('layout/index', $this->data);
    }

    public function add()
    {
        if (!$this->input->is_ajax_request()) show_404();
        if (!is_admin_loggedin() && !is_superadmin_loggedin() && !is_teacher_loggedin()) {
            echo json_encode(array('status' => 'error', 'msg' => 'Access denied.')); return;
        }

        $this->form_validation->set_rules('title',       'Title',       'trim|required|max_length[300]');
        $this->form_validation->set_rules('notice_date', 'Notice Date', 'trim|required');
        $this->form_validation->set_rules('audience',    'Audience',    'trim|required');

        if ($this->form_validation->run() !== false) {
            $branchId   = get_loggedin_branch_id();
            $postedBy   = get_loggedin_user_id();
            $attachment = null;

            if (!empty($_FILES['attachment']['name'])) {
                $config = array(
                    'upload_path'   => './uploads/notices/',
                    'allowed_types' => 'pdf|jpg|jpeg|png|doc|docx',
                    'max_size'      => 5120,
                    'encrypt_name'  => true,
                );
                if (!is_dir('./uploads/notices/')) {
                    mkdir('./uploads/notices/', 0755, true);
                }
                $this->load->library('upload', $config);
                if ($this->upload->do_upload('attachment')) {
                    $attachment = $this->upload->data('file_name');
                } else {
                    echo json_encode(array('status' => 'error', 'msg' => $this->upload->display_errors('', '')));
                    return;
                }
            }

            $audience = $this->input->post('audience');
            if (is_array($audience)) {
                $audience = implode(',', $audience);
            }

            $this->db->insert('notice_board', array(
                'title'       => $this->input->post('title'),
                'details'     => $this->input->post('details'),
                'notice_date' => $this->input->post('notice_date'),
                'expiry_date' => $this->input->post('expiry_date') ?: null,
                'audience'    => $audience,
                'priority'    => $this->input->post('priority'),
                'attachment'  => $attachment,
                'posted_by'   => $postedBy,
                'branch_id'   => $branchId,
            ));
            echo json_encode(array('status' => 'success', 'url' => base_url('noticeboard/manage')));
        } else {
            echo json_encode(array('status' => 'error', 'msg' => validation_errors()));
        }
    }

    public function delete($id)
    {
        if (!$this->input->is_ajax_request()) show_404();
        if (!is_admin_loggedin() && !is_superadmin_loggedin()) {
            echo json_encode(array('status' => 'error', 'msg' => 'Access denied.')); return;
        }
        $branchId = get_loggedin_branch_id();
        $notice   = $this->db->get_where('notice_board', array('id' => $id, 'branch_id' => $branchId))->row_array();
        if ($notice && !empty($notice['attachment'])) {
            @unlink('./uploads/notices/' . $notice['attachment']);
        }
        $this->db->where(array('id' => $id, 'branch_id' => $branchId))->delete('notice_board');
        echo json_encode(array('status' => 'success', 'url' => base_url('noticeboard/manage')));
    }
}
