<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admission_request extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('admission_request_model');
        $this->load->model('student_model');
        $this->load->helpers('custom_fields');
    }

    public function index()
    {
        $branchID = $this->application_model->get_branch_id();
        if (get_permission('admission_approval', 'is_view')) {
            $this->data['requests'] = $this->admission_request_model->getRequests($branchID);
        } elseif (get_permission('admission_request', 'is_view')) {
            $this->data['requests'] = $this->admission_request_model->getRequests($branchID, '', get_loggedin_user_id());
        } else {
            access_denied();
        }

        $this->data['branch_id'] = $branchID;
        $this->data['title'] = 'Admission Requests';
        $this->data['sub_page'] = 'admission_request/index';
        $this->data['main_menu'] = 'admission';
        $this->load->view('layout/index', $this->data);
    }

    public function add()
    {
        if (!get_permission('admission_request', 'is_add')) {
            access_denied();
        }

        $branchID = get_loggedin_branch_id();
        if (isset($_POST['save'])) {
            $this->form_validation->set_rules('year_id', translate('academic_year'), 'trim|required');
            $this->form_validation->set_rules('class_id', translate('class'), 'trim|required');
            $this->form_validation->set_rules('section_id', translate('section'), 'trim|required');
            $this->form_validation->set_rules('first_name', translate('first_name'), 'trim|required');
            $this->form_validation->set_rules('last_name', translate('last_name'), 'trim|required');
            $this->form_validation->set_rules('mobileno', translate('mobile_no'), 'trim|required');
            $this->form_validation->set_rules('email', translate('email'), 'trim|required|valid_email');
            $this->form_validation->set_rules('password', translate('password'), 'trim|required|min_length[4]');
            $this->form_validation->set_rules('retype_password', translate('retype_password'), 'trim|required|matches[password]');

            if (!isset($_POST['guardian_chk'])) {
                $this->form_validation->set_rules('grd_name', translate('name'), 'trim|required');
                $this->form_validation->set_rules('grd_relation', translate('relation'), 'trim|required');
                $this->form_validation->set_rules('grd_mobileno', translate('mobile_no'), 'trim|required');
                $this->form_validation->set_rules('grd_email', translate('email'), 'trim|required|valid_email');
                $this->form_validation->set_rules('grd_password', translate('password'), 'trim|required');
            } else {
                $this->form_validation->set_rules('parent_id', translate('guardian'), 'required');
            }

            if ($this->form_validation->run() == true) {
                $requestId = $this->admission_request_model->saveRequest($this->input->post(), $branchID);
                set_alert('success', 'Admission request submitted successfully. Awaiting admin approval.');
                redirect(base_url('admission_request'));
            }
        }

        $this->data['branch_id'] = $branchID;
        $this->data['register_id'] = $this->student_model->regSerNumber();
        $this->data['title'] = 'New Admission Request';
        $this->data['sub_page'] = 'admission_request/add';
        $this->data['main_menu'] = 'admission';
        $this->data['headerelements'] = array(
            'css' => array('vendor/dropify/css/dropify.min.css'),
            'js' => array('js/student.js', 'vendor/dropify/js/dropify.min.js'),
        );
        $this->load->view('layout/index', $this->data);
    }

    public function view($id = '')
    {
        $request = $this->admission_request_model->getRequestDetail($id);
        if (!$request) {
            show_404();
        }
        if (!is_superadmin_loggedin()) {
            if ($request['branch_id'] != get_loggedin_branch_id()) {
                access_denied();
            }
        }
        $this->data['request'] = $request;
        $this->data['student_data'] = json_decode($request['request_data'], true);
        $this->data['guardian_data'] = json_decode($request['guardian_data'], true);
        $this->data['title'] = 'Admission Request Details';
        $this->data['sub_page'] = 'admission_request/view';
        $this->data['main_menu'] = 'admission';
        $this->load->view('layout/index', $this->data);
    }

    public function approve($id = '')
    {
        if (!get_permission('admission_approval', 'is_add')) {
            access_denied();
        }
        $studentId = $this->admission_request_model->approveRequest($id, get_loggedin_user_id());
        if ($studentId) {
            set_alert('success', 'Admission request approved. Student has been enrolled successfully.');
        } else {
            set_alert('error', 'Failed to approve request. It may have already been processed.');
        }
        redirect(base_url('admission_request'));
    }

    public function reject($id = '')
    {
        if (!get_permission('admission_approval', 'is_add')) {
            access_denied();
        }
        $remarks = $this->input->post('review_remarks');
        $this->admission_request_model->rejectRequest($id, get_loggedin_user_id(), $remarks);
        set_alert('success', 'Admission request has been rejected.');
        redirect(base_url('admission_request'));
    }

    public function delete_request($id = '')
    {
        if (get_permission('admission_request', 'is_delete')) {
            $request = $this->admission_request_model->getRequestDetail($id);
            if ($request && $request['status'] == 'pending') {
                if (!is_superadmin_loggedin()) {
                    $this->db->where('branch_id', get_loggedin_branch_id());
                }
                $this->db->where('id', $id);
                $this->db->delete('admission_requests');
            }
        }
    }

    public function csv_import()
    {
        if (!get_permission('admission_request', 'is_add')) {
            access_denied();
        }

        $branchID = get_loggedin_branch_id();
        if (isset($_POST['save'])) {
            $err_msg = "";
            $i = 0;
            $this->load->library('csvimport');
            $this->form_validation->set_rules('class_id', 'Class', 'trim|required');
            $this->form_validation->set_rules('section_id', 'Section', 'trim|required');
            if (isset($_FILES["userfile"]) && empty($_FILES['userfile']['name'])) {
                $this->form_validation->set_rules('userfile', 'CSV File', 'required');
            }
            if ($this->form_validation->run() == true) {
                $classID = $this->input->post('class_id');
                $sectionID = $this->input->post('section_id');
                $csv_array = $this->csvimport->get_array($_FILES["userfile"]["tmp_name"]);
                if ($csv_array) {
                    $columnHeaders = array('FirstName','LastName','BloodGroup','Gender','Birthday','MotherTongue','Religion','Caste','Phone','City','State','PresentAddress','PermanentAddress','CategoryID','Roll','AdmissionDate','UPINumber','StudentEmail','StudentPassword','GuardianName','GuardianRelation','FatherName','MotherName','GuardianOccupation','GuardianMobileNo','GuardianAddress','GuardianEmail','GuardianPassword');
                    $csvData = array();
                    foreach ($csv_array as $row) {
                        if ($i == 0) {
                            $csvData = array_keys($row);
                        }
                        $csv_chk = array_diff($columnHeaders, $csvData);
                        if (count($csv_chk) <= 0) {
                            if (filter_var($row['StudentEmail'], FILTER_VALIDATE_EMAIL)) {
                                $existingEmail = $this->db->where('username', $row['StudentEmail'])->get('login_credential')->num_rows();
                                if ($existingEmail > 0) {
                                    $err_msg .= $row['FirstName'] . ' ' . $row['LastName'] . " - Failed: Email already exists.<br>";
                                } else {
                                    $studentData = array(
                                        'first_name' => $row['FirstName'],
                                        'last_name' => $row['LastName'],
                                        'gender' => $row['Gender'],
                                        'birthday' => date('Y-m-d', strtotime($row['Birthday'])),
                                        'religion' => $row['Religion'],
                                        'caste' => $row['Caste'],
                                        'blood_group' => $row['BloodGroup'],
                                        'mother_tongue' => $row['MotherTongue'],
                                        'mobileno' => $row['Phone'],
                                        'email' => $row['StudentEmail'],
                                        'current_address' => $row['PresentAddress'],
                                        'permanent_address' => $row['PermanentAddress'],
                                        'city' => $row['City'],
                                        'state' => $row['State'],
                                        'category_id' => $row['CategoryID'],
                                        'register_no' => '',
                                        'admission_date' => date('Y-m-d', strtotime($row['AdmissionDate'])),
                                        'upi_number' => isset($row['UPINumber']) ? $row['UPINumber'] : '',
                                        'password' => $row['StudentPassword'],
                                    );

                                    $guardianData = array(
                                        'grd_name' => $row['GuardianName'],
                                        'grd_relation' => $row['GuardianRelation'],
                                        'grd_father_name' => isset($row['FatherName']) ? $row['FatherName'] : '',
                                        'grd_mother_name' => isset($row['MotherName']) ? $row['MotherName'] : '',
                                        'grd_occupation' => $row['GuardianOccupation'],
                                        'grd_mobileno' => $row['GuardianMobileNo'],
                                        'grd_email' => $row['GuardianEmail'],
                                        'grd_password' => $row['GuardianPassword'],
                                        'grd_address' => $row['GuardianAddress'],
                                    );

                                    $existingParent = $this->db->where(array('email' => $row['GuardianEmail'], 'branch_id' => $branchID))->get('parent')->row();
                                    if ($existingParent) {
                                        $guardianData = array('parent_id' => $existingParent->id, 'guardian_chk' => true);
                                    }

                                    $insert = array(
                                        'request_data' => json_encode($studentData),
                                        'guardian_data' => json_encode($guardianData),
                                        'class_id' => $classID,
                                        'section_id' => $sectionID,
                                        'roll' => $row['Roll'],
                                        'session_id' => get_session_id(),
                                        'branch_id' => $branchID,
                                        'requested_by' => get_loggedin_user_id(),
                                        'status' => 'pending',
                                    );
                                    $this->db->insert('admission_requests', $insert);
                                    $i++;
                                }
                            } else {
                                $err_msg .= $row['FirstName'] . ' ' . $row['LastName'] . " - Failed: Invalid Email.<br>";
                            }
                        } else {
                            set_alert('error', 'Invalid CSV file format. Please use the sample file.');
                            redirect(base_url("admission_request/csv_import"));
                        }
                    }
                    if ($err_msg != null) {
                        $this->session->set_flashdata('csvimport', $err_msg);
                    }
                    if ($i > 0) {
                        set_alert('success', $i . ' admission requests submitted. Awaiting admin approval.');
                    }
                    redirect(base_url("admission_request/csv_import"));
                } else {
                    set_alert('error', 'Invalid CSV file.');
                    redirect(base_url("admission_request/csv_import"));
                }
            }
        }

        $this->data['branch_id'] = $branchID;
        $this->data['title'] = 'Bulk Admission Request (CSV)';
        $this->data['sub_page'] = 'admission_request/csv_import';
        $this->data['main_menu'] = 'admission';
        $this->data['headerelements'] = array(
            'css' => array('vendor/dropify/css/dropify.min.css'),
            'js' => array('vendor/dropify/js/dropify.min.js'),
        );
        $this->load->view('layout/index', $this->data);
    }

    public function csv_sample_download()
    {
        $this->load->helper('download');
        $data = file_get_contents(FCPATH . 'uploads/multi_student_sample.csv');
        force_download("admission_request_sample.csv", $data);
    }
}
