<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Admission_request_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function saveRequest($post, $branchId)
    {
        $studentData = array(
            'first_name' => $post['first_name'],
            'last_name' => $post['last_name'],
            'gender' => isset($post['gender']) ? $post['gender'] : '',
            'birthday' => isset($post['birthday']) ? date('Y-m-d', strtotime($post['birthday'])) : '',
            'religion' => isset($post['religion']) ? $post['religion'] : '',
            'caste' => isset($post['caste']) ? $post['caste'] : '',
            'blood_group' => isset($post['blood_group']) ? $post['blood_group'] : '',
            'mother_tongue' => isset($post['mother_tongue']) ? $post['mother_tongue'] : '',
            'mobileno' => $post['mobileno'],
            'email' => $post['email'],
            'current_address' => isset($post['current_address']) ? $post['current_address'] : '',
            'permanent_address' => isset($post['permanent_address']) ? $post['permanent_address'] : '',
            'city' => isset($post['city']) ? $post['city'] : '',
            'state' => isset($post['state']) ? $post['state'] : '',
            'category_id' => $post['category_id'],
            'register_no' => isset($post['register_no']) ? $post['register_no'] : '',
            'admission_date' => isset($post['admission_date']) ? date('Y-m-d', strtotime($post['admission_date'])) : date('Y-m-d'),
            'upi_number' => isset($post['upi_number']) ? $post['upi_number'] : '',
            'password' => isset($post['password']) ? $post['password'] : '',
        );

        $guardianData = array();
        if (!isset($post['guardian_chk'])) {
            $guardianData = array(
                'grd_name' => isset($post['grd_name']) ? $post['grd_name'] : '',
                'grd_relation' => isset($post['grd_relation']) ? $post['grd_relation'] : '',
                'grd_father_name' => isset($post['grd_father_name']) ? $post['grd_father_name'] : '',
                'grd_mother_name' => isset($post['grd_mother_name']) ? $post['grd_mother_name'] : '',
                'grd_occupation' => isset($post['grd_occupation']) ? $post['grd_occupation'] : '',
                'grd_mobileno' => isset($post['grd_mobileno']) ? $post['grd_mobileno'] : '',
                'grd_email' => isset($post['grd_email']) ? $post['grd_email'] : '',
                'grd_password' => isset($post['grd_password']) ? $post['grd_password'] : '',
                'grd_address' => isset($post['grd_address']) ? $post['grd_address'] : '',
            );
        } else {
            $guardianData = array(
                'parent_id' => $post['parent_id'],
                'guardian_chk' => true,
            );
        }

        $insert = array(
            'request_data' => json_encode($studentData),
            'guardian_data' => json_encode($guardianData),
            'class_id' => $post['class_id'],
            'section_id' => $post['section_id'],
            'roll' => isset($post['roll']) ? $post['roll'] : 0,
            'session_id' => $post['year_id'],
            'branch_id' => $branchId,
            'requested_by' => get_loggedin_user_id(),
            'status' => 'pending',
        );
        $this->db->insert('admission_requests', $insert);
        return $this->db->insert_id();
    }

    public function getRequests($branchId, $status = '', $requestedBy = '')
    {
        $this->db->select('ar.*, c.name as class_name, sec.name as section_name, st.name as teacher_name');
        $this->db->from('admission_requests as ar');
        $this->db->join('class as c', 'c.id = ar.class_id', 'left');
        $this->db->join('section as sec', 'sec.id = ar.section_id', 'left');
        $this->db->join('staff as st', 'st.id = ar.requested_by', 'left');
        $this->db->where('ar.branch_id', $branchId);
        if (!empty($status)) {
            $this->db->where('ar.status', $status);
        }
        if (!empty($requestedBy)) {
            $this->db->where('ar.requested_by', $requestedBy);
        }
        $this->db->order_by('ar.created_at', 'DESC');
        return $this->db->get()->result_array();
    }

    public function getRequestDetail($id)
    {
        $this->db->select('ar.*, c.name as class_name, sec.name as section_name, st.name as teacher_name, rv.name as reviewer_name');
        $this->db->from('admission_requests as ar');
        $this->db->join('class as c', 'c.id = ar.class_id', 'left');
        $this->db->join('section as sec', 'sec.id = ar.section_id', 'left');
        $this->db->join('staff as st', 'st.id = ar.requested_by', 'left');
        $this->db->join('staff as rv', 'rv.id = ar.reviewed_by', 'left');
        $this->db->where('ar.id', $id);
        return $this->db->get()->row_array();
    }

    public function approveRequest($id, $adminId)
    {
        $request = $this->getRequestDetail($id);
        if (!$request || $request['status'] != 'pending') {
            return false;
        }

        $studentData = json_decode($request['request_data'], true);
        $guardianData = json_decode($request['guardian_data'], true);
        $branchId = $request['branch_id'];

        $parentId = 0;
        if (isset($guardianData['guardian_chk']) && $guardianData['guardian_chk']) {
            $parentId = $guardianData['parent_id'];
        } else {
            $parentInsert = array(
                'name' => $guardianData['grd_name'],
                'relation' => $guardianData['grd_relation'],
                'father_name' => isset($guardianData['grd_father_name']) ? $guardianData['grd_father_name'] : '',
                'mother_name' => isset($guardianData['grd_mother_name']) ? $guardianData['grd_mother_name'] : '',
                'occupation' => $guardianData['grd_occupation'],
                'mobileno' => $guardianData['grd_mobileno'],
                'email' => $guardianData['grd_email'],
                'address' => isset($guardianData['grd_address']) ? $guardianData['grd_address'] : '',
                'photo' => 'defualt.png',
            );
            $this->db->insert('parent', $parentInsert);
            $parentId = $this->db->insert_id();

            $grdCredential = array(
                'user_id' => $parentId,
                'username' => $guardianData['grd_email'],
                'password' => $this->app_lib->pass_hashed($guardianData['grd_password']),
                'role' => 6,
                'active' => 1,
            );
            $this->db->insert('login_credential', $grdCredential);
        }

        $studentInsert = array(
            'register_no' => $studentData['register_no'],
            'admission_date' => $studentData['admission_date'],
            'first_name' => $studentData['first_name'],
            'last_name' => $studentData['last_name'],
            'gender' => $studentData['gender'],
            'birthday' => $studentData['birthday'],
            'religion' => $studentData['religion'],
            'caste' => $studentData['caste'],
            'blood_group' => $studentData['blood_group'],
            'mother_tongue' => $studentData['mother_tongue'],
            'mobileno' => $studentData['mobileno'],
            'email' => $studentData['email'],
            'current_address' => $studentData['current_address'],
            'permanent_address' => $studentData['permanent_address'],
            'city' => $studentData['city'],
            'state' => $studentData['state'],
            'category_id' => $studentData['category_id'],
            'parent_id' => $parentId,
            'upi_number' => isset($studentData['upi_number']) ? $studentData['upi_number'] : '',
        );
        $this->db->insert('student', $studentInsert);
        $studentId = $this->db->insert_id();

        $stuCredential = array(
            'user_id' => $studentId,
            'username' => $studentData['email'],
            'password' => $this->app_lib->pass_hashed($studentData['password']),
            'role' => 7,
            'active' => 1,
        );
        $this->db->insert('login_credential', $stuCredential);

        $enrollInsert = array(
            'student_id' => $studentId,
            'class_id' => $request['class_id'],
            'section_id' => $request['section_id'],
            'roll' => $request['roll'],
            'session_id' => $request['session_id'],
            'branch_id' => $branchId,
        );
        $this->db->insert('enroll', $enrollInsert);

        $this->db->where('id', $id);
        $this->db->update('admission_requests', array(
            'status' => 'approved',
            'reviewed_by' => $adminId,
            'reviewed_at' => date('Y-m-d H:i:s'),
        ));

        return $studentId;
    }

    public function rejectRequest($id, $adminId, $remarks)
    {
        $this->db->where('id', $id);
        $this->db->update('admission_requests', array(
            'status' => 'rejected',
            'reviewed_by' => $adminId,
            'review_remarks' => $remarks,
            'reviewed_at' => date('Y-m-d H:i:s'),
        ));
    }

    public function getPendingCount($branchId)
    {
        return $this->db->where(array('branch_id' => $branchId, 'status' => 'pending'))->count_all_results('admission_requests');
    }
}
