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
            'student_id'       => $data['student_id'],
            'exam_id'          => $data['exam_id'],
            'learning_area_id' => $data['learning_area_id'],
            'session_id'       => $data['session_id'],
            'branch_id'        => $data['branch_id'],
        );
        if (!empty($data['strand_id'])) {
            $where['strand_id'] = $data['strand_id'];
        }
        if (!empty($data['sub_strand_id'])) {
            $where['sub_strand_id'] = $data['sub_strand_id'];
        }
        if (!empty($data['learning_outcome_id'])) {
            $where['learning_outcome_id'] = $data['learning_outcome_id'];
        }
        $query = $this->db->get_where('cbc_assessment', $where);
        $record = array(
            'student_id'          => $data['student_id'],
            'exam_id'             => $data['exam_id'],
            'learning_area_id'    => $data['learning_area_id'],
            'strand_id'           => !empty($data['strand_id'])           ? $data['strand_id']           : null,
            'sub_strand_id'       => !empty($data['sub_strand_id'])       ? $data['sub_strand_id']       : null,
            'learning_outcome_id' => !empty($data['learning_outcome_id']) ? $data['learning_outcome_id'] : null,
            'competency_level'    => $data['competency_level'],
            'class_id'         => $data['class_id'],
            'section_id'       => $data['section_id'],
            'remarks'          => isset($data['remarks']) ? $data['remarks'] : '',
            'assessed_by'      => get_loggedin_user_id(),
            'session_id'       => $data['session_id'],
            'branch_id'        => $data['branch_id'],
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

    public function getLearningAreas($branchId = '', $level = '', $pathwayId = null)
    {
        $this->db->select('la.*, b.name as branch_name, COALESCE(p.name, "") as pathway_name');
        $this->db->from('cbc_learning_areas as la');
        $this->db->join('branch as b', 'b.id = la.branch_id', 'left');
        $this->db->join('cbc_pathways as p', 'p.id = la.pathway_id', 'left');
        if (!empty($branchId)) {
            $this->db->where('la.branch_id', $branchId);
        }
        if (!empty($level)) {
            $this->db->where('la.level', $level);
        }
        if ($pathwayId !== null) {
            $this->db->group_start()
                     ->where('la.pathway_id', $pathwayId)
                     ->or_where('la.pathway_id IS NULL', null, false)
                     ->group_end();
        }
        $this->db->order_by('la.name', 'ASC');
        return $this->db->get()->result_array();
    }

    public function saveSubStrand($data)
    {
        $array = array(
            'name'             => $data['name'],
            'strand_id'        => $data['strand_id'],
            'learning_area_id' => $data['learning_area_id'],
            'branch_id'        => $this->application_model->get_branch_id(),
        );
        if (!isset($data['sub_strand_id'])) {
            $this->db->insert('cbc_sub_strands', $array);
        } else {
            $this->db->where('id', $data['sub_strand_id']);
            $this->db->update('cbc_sub_strands', $array);
        }
    }

    public function saveLearningOutcome($data)
    {
        $array = array(
            'code'             => isset($data['code']) ? trim($data['code']) : null,
            'name'             => $data['name'],
            'sub_strand_id'    => !empty($data['sub_strand_id']) ? $data['sub_strand_id'] : null,
            'strand_id'        => $data['strand_id'],
            'learning_area_id' => $data['learning_area_id'],
            'branch_id'        => $this->application_model->get_branch_id(),
        );
        if (!isset($data['learning_outcome_id'])) {
            $this->db->insert('cbc_learning_outcomes', $array);
        } else {
            $this->db->where('id', $data['learning_outcome_id']);
            $this->db->update('cbc_learning_outcomes', $array);
        }
    }

    public function getLearningOutcomes($subStrandId = '', $strandId = '', $branchId = '')
    {
        $this->db->select('lo.*, ss.name as sub_strand_name, s.name as strand_name, la.name as learning_area_name');
        $this->db->from('cbc_learning_outcomes as lo');
        $this->db->join('cbc_strands as s', 's.id = lo.strand_id', 'left');
        $this->db->join('cbc_learning_areas as la', 'la.id = lo.learning_area_id', 'left');
        $this->db->join('cbc_sub_strands as ss', 'ss.id = lo.sub_strand_id', 'left');
        if (!empty($subStrandId)) {
            $this->db->where('lo.sub_strand_id', $subStrandId);
        }
        if (!empty($strandId)) {
            $this->db->where('lo.strand_id', $strandId);
        }
        if (!empty($branchId)) {
            $this->db->where('lo.branch_id', $branchId);
        }
        $this->db->order_by('la.name', 'ASC');
        $this->db->order_by('s.name', 'ASC');
        $this->db->order_by('lo.code', 'ASC');
        $this->db->order_by('lo.name', 'ASC');
        return $this->db->get()->result_array();
    }

    public function getSubStrands($strandId = '', $branchId = '')
    {
        $this->db->select('ss.*, s.name as strand_name, la.name as learning_area_name');
        $this->db->from('cbc_sub_strands as ss');
        $this->db->join('cbc_strands as s', 's.id = ss.strand_id', 'left');
        $this->db->join('cbc_learning_areas as la', 'la.id = ss.learning_area_id', 'left');
        if (!empty($strandId)) {
            $this->db->where('ss.strand_id', $strandId);
        }
        if (!empty($branchId)) {
            $this->db->where('ss.branch_id', $branchId);
        }
        $this->db->order_by('la.name', 'ASC');
        $this->db->order_by('s.name', 'ASC');
        $this->db->order_by('ss.name', 'ASC');
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

        $this->db->select('a.*, la.name as learning_area_name, s.name as strand_name, ss.name as sub_strand_name, lo.name as learning_outcome_name, lo.code as learning_outcome_code');
        $this->db->from('cbc_assessment as a');
        $this->db->join('cbc_learning_areas as la', 'la.id = a.learning_area_id', 'left');
        $this->db->join('cbc_strands as s', 's.id = a.strand_id', 'left');
        $this->db->join('cbc_sub_strands as ss', 'ss.id = a.sub_strand_id', 'left');
        $this->db->join('cbc_learning_outcomes as lo', 'lo.id = a.learning_outcome_id', 'left');
        $this->db->where('a.student_id', $studentId);
        $this->db->where('a.exam_id', $examId);
        $this->db->where('a.session_id', $sessionId);
        $this->db->order_by('la.name', 'ASC');
        $this->db->order_by('s.name', 'ASC');
        $this->db->order_by('ss.name', 'ASC');
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

    public function getAnalyticsData($examId, $classId, $sectionId, $branchId)
    {
        // Per-LA, per-level student count
        $sql = "SELECT la.name as la_name, la.id as la_id,
                       a.competency_level,
                       COUNT(DISTINCT a.student_id) as cnt
                FROM cbc_assessment a
                INNER JOIN cbc_learning_areas la ON la.id = a.learning_area_id
                INNER JOIN enroll en ON en.student_id = a.student_id
                WHERE a.exam_id = ? AND a.branch_id = ?
                  AND en.class_id = ? AND en.section_id = ?
                  AND en.session_id = a.session_id
                GROUP BY la.id, a.competency_level
                ORDER BY la.name ASC, a.competency_level ASC";
        return $this->db->query($sql, array($examId, $branchId, $classId, $sectionId))->result_array();
    }

    // --- Portfolio ---

    public function savePortfolioEntry($data)
    {
        if (!empty($data['portfolio_id'])) {
            $id = intval($data['portfolio_id']);
            $this->db->where('id', $id)->update('cbc_portfolio', array(
                'learning_area_id'  => $data['learning_area_id'],
                'strand_id'         => !empty($data['strand_id']) ? $data['strand_id'] : null,
                'title'             => $data['title'],
                'description'       => $data['description'],
                'competency_level'  => $data['competency_level'],
                'entry_date'        => $data['entry_date'],
                'evidence_file'     => $data['evidence_file'],
            ));
            return $id;
        }
        $this->db->insert('cbc_portfolio', array(
            'student_id'        => $data['student_id'],
            'learning_area_id'  => $data['learning_area_id'],
            'strand_id'         => !empty($data['strand_id']) ? $data['strand_id'] : null,
            'title'             => $data['title'],
            'description'       => $data['description'],
            'competency_level'  => $data['competency_level'],
            'evidence_file'     => $data['evidence_file'],
            'entry_date'        => $data['entry_date'],
            'session_id'        => $data['session_id'],
            'branch_id'         => $data['branch_id'],
            'created_by'        => $data['created_by'],
        ));
        return $this->db->insert_id();
    }

    public function getPortfolio($studentId, $branchId)
    {
        $this->db->select('p.*, la.name as learning_area_name, s.name as strand_name');
        $this->db->from('cbc_portfolio as p');
        $this->db->join('cbc_learning_areas as la', 'la.id = p.learning_area_id', 'left');
        $this->db->join('cbc_strands as s', 's.id = p.strand_id', 'left');
        $this->db->where('p.student_id', $studentId);
        $this->db->where('p.branch_id', $branchId);
        $this->db->order_by('p.entry_date', 'DESC');
        return $this->db->get()->result_array();
    }

    // --- Projects ---

    public function saveProject($data)
    {
        if (!empty($data['project_id'])) {
            $this->db->where('id', $data['project_id'])->update('cbc_projects', array(
                'name'              => $data['name'],
                'description'       => $data['description'],
                'learning_area_id'  => !empty($data['learning_area_id']) ? $data['learning_area_id'] : null,
                'due_date'          => !empty($data['due_date']) ? $data['due_date'] : null,
                'max_score'         => $data['max_score'],
            ));
            return $data['project_id'];
        }
        $this->db->insert('cbc_projects', array(
            'name'              => $data['name'],
            'description'       => $data['description'],
            'learning_area_id'  => !empty($data['learning_area_id']) ? $data['learning_area_id'] : null,
            'class_id'          => $data['class_id'],
            'section_id'        => $data['section_id'],
            'due_date'          => !empty($data['due_date']) ? $data['due_date'] : null,
            'max_score'         => $data['max_score'],
            'session_id'        => $data['session_id'],
            'branch_id'         => $data['branch_id'],
        ));
        return $this->db->insert_id();
    }

    public function getProjects($branchId, $classId = null, $sectionId = null)
    {
        $this->db->select('pr.*, la.name as learning_area_name, c.name as class_name, s.name as section_name');
        $this->db->from('cbc_projects as pr');
        $this->db->join('cbc_learning_areas as la', 'la.id = pr.learning_area_id', 'left');
        $this->db->join('class as c', 'c.id = pr.class_id', 'left');
        $this->db->join('section as s', 's.id = pr.section_id', 'left');
        $this->db->where('pr.branch_id', $branchId);
        if ($classId)   $this->db->where('pr.class_id', $classId);
        if ($sectionId) $this->db->where('pr.section_id', $sectionId);
        $this->db->order_by('pr.id', 'DESC');
        return $this->db->get()->result_array();
    }

    public function saveProjectScores($projectId, $scores, $branchId)
    {
        foreach ($scores as $studentId => $data) {
            $row = array(
                'project_id'       => $projectId,
                'student_id'       => $studentId,
                'score'            => isset($data['score']) && $data['score'] !== '' ? $data['score'] : null,
                'competency_level' => !empty($data['competency_level']) ? $data['competency_level'] : null,
                'remarks'          => isset($data['remarks']) ? $data['remarks'] : '',
                'branch_id'        => $branchId,
            );
            $exists = $this->db->where(array('project_id' => $projectId, 'student_id' => $studentId))->get('cbc_project_scores')->num_rows();
            if ($exists) {
                $this->db->where(array('project_id' => $projectId, 'student_id' => $studentId))->update('cbc_project_scores', $row);
            } else {
                $this->db->insert('cbc_project_scores', $row);
            }
        }
    }

    public function getProjectScores($projectId)
    {
        return $this->db->where('project_id', $projectId)->get('cbc_project_scores')->result_array();
    }

    public function getStudentProjects($studentId, $branchId, $sessionId)
    {
        $this->db->select('pr.*, ps.score, ps.competency_level, ps.remarks, la.name as learning_area_name, c.name as class_name');
        $this->db->from('cbc_projects as pr');
        $this->db->join('cbc_project_scores as ps', 'ps.project_id = pr.id AND ps.student_id = ' . intval($studentId), 'left');
        $this->db->join('cbc_learning_areas as la', 'la.id = pr.learning_area_id', 'left');
        $this->db->join('class as c', 'c.id = pr.class_id', 'left');
        $this->db->join('enroll as e', 'e.class_id = pr.class_id AND e.section_id = pr.section_id AND e.student_id = ' . intval($studentId), 'inner');
        $this->db->where('pr.branch_id', $branchId);
        $this->db->where('pr.session_id', $sessionId);
        $this->db->order_by('pr.due_date', 'ASC');
        return $this->db->get()->result_array();
    }

    public function getPathways($branchId)
    {
        return $this->db->where('branch_id', $branchId)->get('cbc_pathways')->result_array();
    }

    public function savePathway($data)
    {
        if (!empty($data['pathway_id'])) {
            $this->db->where('id', $data['pathway_id'])->update('cbc_pathways', array('name' => $data['name'], 'description' => $data['description']));
            return $data['pathway_id'];
        }
        $this->db->insert('cbc_pathways', array('name' => $data['name'], 'description' => $data['description'], 'branch_id' => $data['branch_id']));
        return $this->db->insert_id();
    }

    public function getAnalyticsTrend($classId, $sectionId, $branchId, $sessionId)
    {
        // Per-exam, per-level total count (trend across exams in session)
        $sql = "SELECT e.name as exam_name, e.id as exam_id,
                       a.competency_level,
                       COUNT(DISTINCT a.student_id) as cnt
                FROM cbc_assessment a
                INNER JOIN exam e ON e.id = a.exam_id
                INNER JOIN enroll en ON en.student_id = a.student_id
                WHERE a.branch_id = ? AND en.class_id = ? AND en.section_id = ?
                  AND a.session_id = ? AND en.session_id = ?
                GROUP BY a.exam_id, a.competency_level
                ORDER BY e.id ASC, a.competency_level ASC";
        return $this->db->query($sql, array($branchId, $classId, $sectionId, $sessionId, $sessionId))->result_array();
    }
}
