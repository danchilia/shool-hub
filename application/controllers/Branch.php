<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Ramom school management system
 * @version : 2.0
 * @developed by : RamomCoder
 * @support : ramomcoder@yahoo.com
 * @author url : http://codecanyon.net/user/RamomCoder
 * @filename : Accounting.php
 * @copyright : Reserved RamomCoders Team
 */

class Branch extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('branch_model');
    }

    /* branch all data are prepared and stored in the database here */
    public function index()
    {
        if (is_superadmin_loggedin()) {
            if ($this->input->post('submit') == 'save') {
                $this->form_validation->set_rules('branch_name', translate('branch_name'), 'required|callback_unique_name');
                $this->form_validation->set_rules('school_name', translate('school_name'), 'required');
                $this->form_validation->set_rules('email', translate('email'), 'required|valid_email');
                $this->form_validation->set_rules('mobileno', translate('mobile_no'), 'required');
                $this->form_validation->set_rules('currency', translate('currency'), 'required');
                $this->form_validation->set_rules('currency_symbol', translate('currency_symbol'), 'required');
                if ($this->form_validation->run() == true) {
                    $post = $this->input->post();
                    $response = $this->branch_model->save($post);
                    if ($response) {
                        set_alert('success', translate('information_has_been_saved_successfully'));
                    }
                    redirect(base_url('branch'));
                } else {
                    $this->data['validation_error'] = true;
                }
            }
            $this->data['title'] = translate('branch');
            $this->data['sub_page'] = 'branch/add';
            $this->data['main_menu'] = 'branch';
            $this->load->view('layout/index', $this->data);
        } else {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
    }

    /* branch information update here */
    public function edit($id = '')
    {
        if (is_superadmin_loggedin()) {
            if ($this->input->post('submit') == 'save') {
                $this->form_validation->set_rules('branch_name', translate('branch_name'), 'required|callback_unique_name');
                $this->form_validation->set_rules('school_name', translate('school_name'), 'required');
                $this->form_validation->set_rules('email', translate('email'), 'required|valid_email');
                $this->form_validation->set_rules('mobileno', translate('mobile_no'), 'required');
                $this->form_validation->set_rules('currency', translate('currency'), 'required');
                $this->form_validation->set_rules('currency_symbol', translate('currency_symbol'), 'required');
                if ($this->form_validation->run() == true) {
                    $post = $this->input->post();
                    $response = $this->branch_model->save($post, $id);
                    if ($response) {
                        set_alert('success', translate('information_has_been_updated_successfully'));
                    }
                    redirect(base_url('branch'));
                }
            }

            $this->data['data'] = $this->branch_model->getSingle('branch', $id, true);
            $this->data['title'] = translate('branch');
            $this->data['sub_page'] = 'branch/edit';
            $this->data['main_menu'] = 'branch';
            $this->load->view('layout/index', $this->data);
        } else {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
    }

    /* delete information */
    public function delete_data($id = '')
    {
        if (is_superadmin_loggedin()) {
            // Delete all branch data
            $tables_with_branch_id = array(
                'class', 'section', 'subject', 'student_category',
                'staff_department', 'staff_designation', 'exam_term',
                'exam_mark_distribution', 'exam_hall', 'grade',
                'fees_type', 'fee_groups', 'fee_fine', 'fees_reminder',
                'leave_category', 'event_types', 'event',
                'voucher_head', 'accounts', 'transactions',
                'book_category', 'book', 'book_issues',
                'hostel_category', 'hostel', 'hostel_room',
                'transport_route', 'transport_vehicle', 'transport_stoppage', 'transport_assign',
                'cbc_learning_areas', 'cbc_strands', 'cbc_assessment', 'cbc_behaviour_assessment',
                'admission_requests', 'mpesa_transactions',
                'payment_config', 'email_config', 'sms_credential',
                'sms_template_details', 'email_templates_details',
                'timetable_class', 'timetable_exam', 'exam',
                'teacher_allocation', 'subject_assign', 'sections_allocation',
                'student_attendance', 'staff_attendance', 'exam_attendance',
                'homework', 'attachments', 'attachments_type',
                'salary_template', 'payslip', 'advance_salary',
                'award', 'leave_application', 'live_class',
                'mark', 'hall_allocation', 'custom_field',
                'branch_subscriptions', 'subscription_invoices',
            );
            foreach ($tables_with_branch_id as $table) {
                $this->db->where('branch_id', $id);
                $this->db->delete($table);
            }

            // Delete students and their data
            $students = $this->db->select('student_id')->where('branch_id', $id)->get('enroll')->result();
            if (count($students)) {
                $studentIds = array();
                foreach ($students as $s) { $studentIds[] = $s->student_id; }
                $this->db->where_in('id', $studentIds);
                $this->db->delete('student');
                $this->db->where_in('user_id', $studentIds);
                $this->db->where('role', 7);
                $this->db->delete('login_credential');
                $this->db->where_in('student_id', $studentIds);
                $this->db->delete('enroll');
                $this->db->where_in('student_id', $studentIds);
                $this->db->delete('fee_allocation');
                $this->db->where_in('student_id', $studentIds);
                $this->db->delete('student_documents');
            }

            // Delete parents
            $parents = $this->db->select('id')->where('branch_id', $id)->get('parent')->result();
            if (count($parents)) {
                $parentIds = array();
                foreach ($parents as $p) { $parentIds[] = $p->id; }
                $this->db->where_in('id', $parentIds);
                $this->db->delete('parent');
                $this->db->where_in('user_id', $parentIds);
                $this->db->where('role', 6);
                $this->db->delete('login_credential');
            }

            // Delete staff and their login credentials
            $staff = $this->db->select('id')->where('branch_id', $id)->get('staff')->result();
            if (count($staff)) {
                $staffIds = array();
                foreach ($staff as $st) { $staffIds[] = $st->id; }
                $this->db->where_in('id', $staffIds);
                $this->db->delete('staff');
                $this->db->where_in('user_id', $staffIds);
                $this->db->where('role !=', 1);
                $this->db->delete('login_credential');
            }

            // Finally delete the branch
            $this->db->where('id', $id);
            $this->db->delete('branch');

            set_alert('success', 'Branch and all its data deleted successfully.');
            redirect(base_url('branch'));
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    /* unique valid branch name verification is done here */
    public function unique_name($name)
    {
        $branch_id = $this->input->post('branch_id');
        if (!empty($branch_id)) {
            $this->db->where_not_in('id', $branch_id);
        }
        $this->db->where('name', $name);
        $name = $this->db->get('branch')->num_rows();
        if ($name == 0) {
            return true;
        } else {
            $this->form_validation->set_message("unique_name", translate('already_taken'));
            return false;
        }
    }
}
