<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Cbc_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function saveLearningArea($data)
    {
        $array = array(
            'name' => $data['name'],
            'level' => $data['level'],
            'subject_id' => isset($data['subject_id']) ? $data['subject_id'] : null,
            'branch_id' => $this->application_model->get_branch_id(),
        );
        if (!isset($data['learning_area_id'])) {
            $this->db->insert('cbc_learning_areas', $array);
        } else {
            $this->db->where('id', $data['learning_area_id']);
            $this->db->update('cbc_learning_areas', $array);
        }
    }

    public function saveStrand($data)
    {
        $array = array(
            'name' => $data['name'],
            'learning_area_id' => $data['learning_area_id'],
            'branch_id' => $this->application_model->get_branch_id(),
        );
        if (!isset($data['strand_id'])) {
            $this->db->insert('cbc_strands', $array);
        } else {
            $this->db->where('id', $data['strand_id']);
            $this->db->update('cbc_strands', $array);
        }
    }

    public function saveAssessment($data)
    {
        $where = array(
            'student_id' => $data['student_id'],
            'exam_id' => $data['exam_id'],
            'learning_area_id' => $data['learning_area_id'],
            'session_id' => $data['session_id'],
            'branch_id' => $data['branch_id'],
        );
        if (!empty($data['strand_id'])) {
            $where['strand_id'] = $data['strand_id'];
        }
        $query = $this->db->get_where('cbc_assessment', $where);
        $record = array(
            'student_id' => $data['student_id'],
            'exam_id' => $data['exam_id'],
            'learning_area_id' => $data['learning_area_id'],
            'strand_id' => !empty($data['strand_id']) ? $data['strand_id'] : null,
            'competency_level' => $data['competency_level'],
            'class_id' => $data['class_id'],
            'section_id' => $data['section_id'],
            'remarks' => isset($data['remarks']) ? $data['remarks'] : '',
            'assessed_by' => get_loggedin_user_id(),
            'session_id' => $data['session_id'],
            'branch_id' => $data['branch_id'],
        );
        if ($query->num_rows() > 0) {
            $this->db->where('id', $query->row()->id);
            $this->db->update('cbc_assessment', $record);
        } else {
            $this->db->insert('cbc_assessment', $record);
        }
    }

    public function saveBehaviour($data)
    {
        $where = array(
            'student_id' => $data['student_id'],
            'exam_id' => $data['exam_id'],
            'category' => $data['category'],
            'session_id' => $data['session_id'],
            'branch_id' => $data['branch_id'],
        );
        $query = $this->db->get_where('cbc_behaviour_assessment', $where);
        $record = array(
            'student_id' => $data['student_id'],
            'exam_id' => $data['exam_id'],
            'category' => $data['category'],
            'rating' => $data['rating'],
            'remarks' => isset($data['remarks']) ? $data['remarks'] : '',
            'session_id' => $data['session_id'],
            'branch_id' => $data['branch_id'],
        );
        if ($query->num_rows() > 0) {
            $this->db->where('id', $query->row()->id);
            $this->db->update('cbc_behaviour_assessment', $record);
        } else {
            $this->db->insert('cbc_behaviour_assessment', $record);
        }
    }

    public function getLearningAreas($branchId = '', $level = '')
    {
        $this->db->select('la.*, b.name as branch_name');
        $this->db->from('cbc_learning_areas as la');
        $this->db->join('branch as b', 'b.id = la.branch_id', 'left');
        if (!empty($branchId)) {
            $this->db->where('la.branch_id', $branchId);
        }
        if (!empty($level)) {
            $this->db->where('la.level', $level);
        }
        $this->db->order_by('la.name', 'ASC');
        return $this->db->get()->result_array();
    }

    public function getStrands($learningAreaId = '', $branchId = '')
    {
        $this->db->select('s.*, la.name as learning_area_name, b.name as branch_name');
        $this->db->from('cbc_strands as s');
        $this->db->join('cbc_learning_areas as la', 'la.id = s.learning_area_id', 'left');
        $this->db->join('branch as b', 'b.id = s.branch_id', 'left');
        if (!empty($learningAreaId)) {
            $this->db->where('s.learning_area_id', $learningAreaId);
        }
        if (!empty($branchId)) {
            $this->db->where('s.branch_id', $branchId);
        }
        $this->db->order_by('la.name', 'ASC');
        $this->db->order_by('s.name', 'ASC');
        return $this->db->get()->result_array();
    }

    public function getStudentsForAssessment($classId, $sectionId, $branchId)
    {
        $sessionId = get_session_id();
        $this->db->select('e.id as enroll_id, e.roll, s.id as student_id, s.first_name, s.last_name, s.photo, s.register_no');
        $this->db->from('enroll as e');
        $this->db->join('student as s', 's.id = e.student_id', 'inner');
        $this->db->where('e.class_id', $classId);
        $this->db->where('e.section_id', $sectionId);
        $this->db->where('e.branch_id', $branchId);
        $this->db->where('e.session_id', $sessionId);
        $this->db->order_by('e.roll', 'ASC');
        return $this->db->get()->result_array();
    }

    public function getExistingAssessment($studentId, $examId, $learningAreaId, $sessionId)
    {
        return $this->db->get_where('cbc_assessment', array(
            'student_id' => $studentId,
            'exam_id' => $examId,
            'learning_area_id' => $learningAreaId,
            'session_id' => $sessionId,
        ))->row_array();
    }

    public function getExistingBehaviour($studentId, $examId, $category, $sessionId)
    {
        return $this->db->get_where('cbc_behaviour_assessment', array(
            'student_id' => $studentId,
            'exam_id' => $examId,
            'category' => $category,
            'session_id' => $sessionId,
        ))->row_array();
    }

    public function getStudentCbcReport($studentId, $examId, $sessionId)
    {
        $result = array();

        $this->db->select('s.*, e.roll, e.class_id, e.section_id, c.name as class_name, sec.name as section_name, sc.name as category_name, p.name as guardian_name, p.father_name, p.mother_name');
        $this->db->from('student as s');
        $this->db->join('enroll as e', 'e.student_id = s.id', 'inner');
        $this->db->join('class as c', 'c.id = e.class_id', 'left');
        $this->db->join('section as sec', 'sec.id = e.section_id', 'left');
        $this->db->join('student_category as sc', 'sc.id = s.category_id', 'left');
        $this->db->join('parent as p', 'p.id = s.parent_id', 'left');
        $this->db->where('s.id', $studentId);
        $this->db->where('e.session_id', $sessionId);
        $result['student'] = $this->db->get()->row_array();

        $this->db->select('a.*, la.name as learning_area_name');
        $this->db->from('cbc_assessment as a');
        $this->db->join('cbc_learning_areas as la', 'la.id = a.learning_area_id', 'left');
        $this->db->where('a.student_id', $studentId);
        $this->db->where('a.exam_id', $examId);
        $this->db->where('a.session_id', $sessionId);
        $this->db->order_by('la.name', 'ASC');
        $result['assessments'] = $this->db->get()->result_array();

        $this->db->where('student_id', $studentId);
        $this->db->where('exam_id', $examId);
        $this->db->where('session_id', $sessionId);
        $result['behaviour'] = $this->db->get('cbc_behaviour_assessment')->result_array();

        return $result;
    }

    public function getCbcExams($branchId)
    {
        $sessionId = get_session_id();
        $this->db->select('e.*, et.name as term_name');
        $this->db->from('exam as e');
        $this->db->join('exam_term as et', 'et.id = e.term_id', 'left');
        $this->db->where('e.grading_system', 'cbc');
        $this->db->where('e.session_id', $sessionId);
        if (!empty($branchId)) {
            $this->db->where('e.branch_id', $branchId);
        }
        return $this->db->get()->result_array();
    }

    public function getLearningAreasByClassLevel($classId, $branchId)
    {
        $class = $this->db->get_where('class', array('id' => $classId))->row();
        if (!$class || empty($class->level)) {
            return array();
        }
        return $this->getLearningAreas($branchId, $class->level);
    }
}
