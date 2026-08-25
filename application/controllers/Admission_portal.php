<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Public Online Admission Portal — no login required.
 * Parents visit apply/<branch_id> to submit a child's admission form.
 * Submissions go into admission_requests with status=pending for admin approval.
 */
class Admission_portal extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /** Landing page listing all active branches */
    public function index()
    {
        $branches = $this->db->select('id, name, address, mobileno, email')
            ->get('branch')->result_array();

        $this->data['branches'] = $branches;
        $this->load->view('admission_portal/index', $this->data);
    }

    /** Public admission form for a specific branch */
    public function apply($branchId = '')
    {
        $branchId = (int) $branchId;
        $branch   = $this->db->where('id', $branchId)->get('branch')->row_array();
        if (!$branch) {
            show_404();
        }

        $classes      = $this->db->where('branch_id', $branchId)->get('class')->result_array();
        $school_years = $this->db->order_by('id', 'DESC')->get('schoolyear')->result_array();

        $this->data['branch']       = $branch;
        $this->data['classes']      = $classes;
        $this->data['school_years'] = $school_years;
        $this->load->view('admission_portal/apply', $this->data);
    }

    /** Handle public admission form submission */
    public function submit()
    {
        $branchId = (int) $this->input->post('branch_id');
        $branch   = $this->db->where('id', $branchId)->get('branch')->row_array();
        if (!$branch) {
            show_404();
        }

        $this->form_validation->set_rules('branch_id',    'School',        'trim|required|is_natural_no_zero');
        $this->form_validation->set_rules('class_id',     'Class',         'trim|required');
        $this->form_validation->set_rules('first_name',   'First Name',    'trim|required');
        $this->form_validation->set_rules('last_name',    'Last Name',     'trim|required');
        $this->form_validation->set_rules('grd_name',     'Guardian Name', 'trim|required');
        $this->form_validation->set_rules('grd_mobileno', 'Guardian Phone','trim|required');
        $this->form_validation->set_rules('grd_email',    'Guardian Email','trim|required|valid_email');

        if ($this->form_validation->run() !== false) {
            $token = bin2hex(random_bytes(16));

            $studentData = array(
                'first_name'         => $this->input->post('first_name'),
                'last_name'          => $this->input->post('last_name'),
                'gender'             => $this->input->post('gender'),
                'birthday'           => $this->input->post('birthday'),
                'religion'           => $this->input->post('religion'),
                'mobileno'           => $this->input->post('student_phone'),
                'email'              => $this->input->post('student_email'),
                'current_address'    => $this->input->post('address'),
                'upi_number'         => $this->input->post('upi_number'),
                'admission_date'     => date('Y-m-d'),
                'password'           => 'changeme123',
            );

            $guardianData = array(
                'grd_name'      => $this->input->post('grd_name'),
                'grd_relation'  => $this->input->post('grd_relation'),
                'grd_mobileno'  => $this->input->post('grd_mobileno'),
                'grd_email'     => $this->input->post('grd_email'),
                'grd_password'  => 'changeme123',
            );

            // Find the latest academic year (schoolyear is global, not per-branch)
            $session   = $this->db->order_by('id', 'DESC')->limit(1)->get('schoolyear')->row_array();
            $sessionId = $session ? $session['id'] : 0;

            $this->db->insert('admission_requests', array(
                'request_data'   => json_encode($studentData),
                'guardian_data'  => json_encode($guardianData),
                'class_id'       => (int) $this->input->post('class_id'),
                'section_id'     => (int) ($this->input->post('section_id') ?: 0),
                'session_id'     => $sessionId,
                'branch_id'      => $branchId,
                'requested_by'   => null,
                'status'         => 'pending',
                'tracking_token' => $token,
                'applicant_email'=> $this->input->post('grd_email'),
                'applicant_phone'=> $this->input->post('grd_mobileno'),
            ));

            redirect(base_url('admission_portal/success/' . $token));
        } else {
            // Re-render form with errors
            $classes      = $this->db->where('branch_id', $branchId)->get('class')->result_array();
            $school_years = $this->db->order_by('id', 'DESC')->get('schoolyear')->result_array();
            $this->data['branch']            = $branch;
            $this->data['classes']           = $classes;
            $this->data['school_years']      = $school_years;
            $this->data['validation_errors'] = $this->form_validation->error_array();
            $this->load->view('admission_portal/apply', $this->data);
        }
    }

    /** Success / tracking page */
    public function success($token = '')
    {
        $request = $this->db->where('tracking_token', $token)
            ->get('admission_requests')->row_array();
        if (!$request) {
            show_404();
        }
        $studentData     = json_decode($request['request_data'], true);
        $branch          = $this->db->where('id', $request['branch_id'])->get('branch')->row_array();
        $this->data['request']     = $request;
        $this->data['student']     = $studentData;
        $this->data['branch']      = $branch;
        $this->data['token']       = $token;
        $this->load->view('admission_portal/success', $this->data);
    }

    /** AJAX: get sections by class */
    public function sections($classId = '')
    {
        $sections = $this->db->where('class_id', (int) $classId)->get('section')->result_array();
        echo json_encode($sections);
    }
}
