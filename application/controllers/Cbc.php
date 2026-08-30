<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cbc extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('cbc_model');
    }

    // --- Learning Areas CRUD ---

    public function learning_areas()
    {
        if (!get_permission('cbc_learning_areas', 'is_view')) {
            access_denied();
        }
        $branchID = $this->application_model->get_branch_id();
        if (isset($_POST['save'])) {
            if (!get_permission('cbc_learning_areas', 'is_add')) {
                access_denied();
            }
            if (is_superadmin_loggedin()) {
                $this->form_validation->set_rules('branch_id', translate('branch'), 'required');
            }
            $this->form_validation->set_rules('name', translate('name'), 'trim|required');
            $this->form_validation->set_rules('level', translate('level'), 'trim|required');
            if ($this->form_validation->run() !== false) {
                $this->cbc_model->saveLearningArea($this->input->post());
                set_alert('success', translate('information_has_been_saved_successfully'));
                redirect(current_url());
            }
        }
        $this->data['areas'] = $this->cbc_model->getLearningAreas($branchID);
        $this->data['branch_id'] = $branchID;
        $this->data['title'] = 'CBC Learning Areas';
        $this->data['sub_page'] = 'cbc/learning_areas';
        $this->data['main_menu'] = 'cbc';
        $this->load->view('layout/index', $this->data);
    }

    public function learning_area_edit()
    {
        if ($_POST) {
            if (!get_permission('cbc_learning_areas', 'is_edit')) {
                ajax_access_denied();
            }
            if (is_superadmin_loggedin()) {
                $this->form_validation->set_rules('branch_id', translate('branch'), 'required');
            }
            $this->form_validation->set_rules('name', translate('name'), 'trim|required');
            $this->form_validation->set_rules('level', translate('level'), 'trim|required');
            if ($this->form_validation->run() !== false) {
                $this->cbc_model->saveLearningArea($this->input->post());
                set_alert('success', translate('information_has_been_updated_successfully'));
                $array = array('status' => 'success', 'url' => base_url('cbc/learning_areas'));
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
        }
    }

    public function learning_area_delete($id)
    {
        if (get_permission('cbc_learning_areas', 'is_delete')) {
            if (!is_superadmin_loggedin()) {
                $this->db->where('branch_id', get_loggedin_branch_id());
            }
            $this->db->where('id', $id);
            $this->db->delete('cbc_learning_areas');
            $this->db->where('learning_area_id', $id)->delete('cbc_strands');
            $this->db->where('learning_area_id', $id)->delete('cbc_sub_strands');
            $this->db->where('learning_area_id', $id)->delete('cbc_learning_outcomes');
        }
    }

    // --- Strands CRUD ---

    public function strands()
    {
        if (!get_permission('cbc_strands', 'is_view')) {
            access_denied();
        }
        $branchID = $this->application_model->get_branch_id();
        if (isset($_POST['save'])) {
            if (!get_permission('cbc_strands', 'is_add')) {
                access_denied();
            }
            if (is_superadmin_loggedin()) {
                $this->form_validation->set_rules('branch_id', translate('branch'), 'required');
            }
            $this->form_validation->set_rules('name', translate('name'), 'trim|required');
            $this->form_validation->set_rules('learning_area_id', 'Learning Area', 'trim|required');
            if ($this->form_validation->run() !== false) {
                $this->cbc_model->saveStrand($this->input->post());
                set_alert('success', translate('information_has_been_saved_successfully'));
                redirect(current_url());
            }
        }
        $this->data['strands'] = $this->cbc_model->getStrands('', $branchID);
        $this->data['learning_areas'] = $this->cbc_model->getLearningAreas($branchID);
        $this->data['branch_id'] = $branchID;
        $this->data['title'] = 'CBC Strands';
        $this->data['sub_page'] = 'cbc/strands';
        $this->data['main_menu'] = 'cbc';
        $this->load->view('layout/index', $this->data);
    }

    public function strand_edit()
    {
        if ($_POST) {
            if (!get_permission('cbc_strands', 'is_edit')) {
                ajax_access_denied();
            }
            if (is_superadmin_loggedin()) {
                $this->form_validation->set_rules('branch_id', translate('branch'), 'required');
            }
            $this->form_validation->set_rules('name', translate('name'), 'trim|required');
            $this->form_validation->set_rules('learning_area_id', 'Learning Area', 'trim|required');
            if ($this->form_validation->run() !== false) {
                $this->cbc_model->saveStrand($this->input->post());
                set_alert('success', translate('information_has_been_updated_successfully'));
                $array = array('status' => 'success', 'url' => base_url('cbc/strands'));
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
        }
    }

    public function strand_delete($id)
    {
        if (get_permission('cbc_strands', 'is_delete')) {
            if (!is_superadmin_loggedin()) {
                $this->db->where('branch_id', get_loggedin_branch_id());
            }
            $this->db->where('id', $id);
            $this->db->delete('cbc_strands');
            $this->db->where('strand_id', $id)->delete('cbc_sub_strands');
            $this->db->where('strand_id', $id)->delete('cbc_learning_outcomes');
        }
    }

    // --- Sub-Strands CRUD ---

    public function sub_strands()
    {
        if (!get_permission('cbc_sub_strands', 'is_view')) {
            access_denied();
        }
        $branchID = $this->application_model->get_branch_id();
        if (isset($_POST['save'])) {
            if (!get_permission('cbc_sub_strands', 'is_add')) {
                access_denied();
            }
            $this->form_validation->set_rules('name', translate('name'), 'trim|required');
            $this->form_validation->set_rules('strand_id', 'Strand', 'trim|required');
            $this->form_validation->set_rules('learning_area_id', 'Learning Area', 'trim|required');
            if ($this->form_validation->run() !== false) {
                $this->cbc_model->saveSubStrand($this->input->post());
                set_alert('success', translate('information_has_been_saved_successfully'));
                redirect(current_url());
            }
        }
        $this->data['sub_strands']    = $this->cbc_model->getSubStrands('', $branchID);
        $this->data['strands']        = $this->cbc_model->getStrands('', $branchID);
        $this->data['learning_areas'] = $this->cbc_model->getLearningAreas($branchID);
        $this->data['branch_id']      = $branchID;
        $this->data['title']          = 'CBC Sub-Strands';
        $this->data['sub_page']       = 'cbc/sub_strands';
        $this->data['main_menu']      = 'cbc';
        $this->load->view('layout/index', $this->data);
    }

    public function sub_strand_edit()
    {
        if ($_POST) {
            if (!get_permission('cbc_sub_strands', 'is_edit')) {
                ajax_access_denied();
            }
            $this->form_validation->set_rules('name', translate('name'), 'trim|required');
            $this->form_validation->set_rules('strand_id', 'Strand', 'trim|required');
            $this->form_validation->set_rules('learning_area_id', 'Learning Area', 'trim|required');
            if ($this->form_validation->run() !== false) {
                $this->cbc_model->saveSubStrand($this->input->post());
                set_alert('success', translate('information_has_been_updated_successfully'));
                $array = array('status' => 'success', 'url' => base_url('cbc/sub_strands'));
            } else {
                $array = array('status' => 'fail', 'error' => $this->form_validation->error_array());
            }
            echo json_encode($array);
        }
    }

    public function sub_strand_delete($id)
    {
        if (get_permission('cbc_sub_strands', 'is_delete')) {
            if (!is_superadmin_loggedin()) {
                $this->db->where('branch_id', get_loggedin_branch_id());
            }
            $this->db->where('id', $id)->delete('cbc_sub_strands');
        }
    }

    // AJAX: strands by learning area (for sub-strand form)
    public function getStrandsByLearningArea()
    {
        $html = '<option value="">' . translate('select') . '</option>';
        $branchID = $this->application_model->get_branch_id();
        $laId = intval($this->input->post('learning_area_id'));
        if ($laId) {
            $strands = $this->cbc_model->getStrands($laId, $branchID);
            foreach ($strands as $s) {
                $html .= '<option value="' . $s['id'] . '">' . htmlspecialchars($s['name']) . '</option>';
            }
        }
        echo $html;
    }

    // --- Learning Outcomes CRUD ---

    public function learning_outcomes()
    {
        if (!get_permission('cbc_learning_outcomes', 'is_view')) {
            access_denied();
        }
        $branchID = $this->application_model->get_branch_id();
        if (isset($_POST['save'])) {
            if (!get_permission('cbc_learning_outcomes', 'is_add')) {
                access_denied();
            }
            $this->form_validation->set_rules('name', 'Learning Outcome', 'trim|required');
            $this->form_validation->set_rules('strand_id', 'Strand', 'trim|required');
            $this->form_validation->set_rules('learning_area_id', 'Learning Area', 'trim|required');
            if ($this->form_validation->run() !== false) {
                $this->cbc_model->saveLearningOutcome($this->input->post());
                set_alert('success', translate('information_has_been_saved_successfully'));
                redirect(current_url());
            }
        }
        $this->data['outcomes']       = $this->cbc_model->getLearningOutcomes('', '', $branchID);
        $this->data['learning_areas'] = $this->cbc_model->getLearningAreas($branchID);
        $this->data['strands']        = $this->cbc_model->getStrands('', $branchID);
        $this->data['sub_strands']    = $this->cbc_model->getSubStrands('', $branchID);
        $this->data['branch_id']      = $branchID;
        $this->data['title']          = 'CBC Learning Outcomes';
        $this->data['sub_page']       = 'cbc/learning_outcomes';
        $this->data['main_menu']      = 'cbc';
        $this->load->view('layout/index', $this->data);
    }

    public function learning_outcome_edit()
    {
        if ($_POST) {
            if (!get_permission('cbc_learning_outcomes', 'is_edit')) {
                ajax_access_denied();
            }
            $this->form_validation->set_rules('name', 'Learning Outcome', 'trim|required');
            $this->form_validation->set_rules('strand_id', 'Strand', 'trim|required');
            $this->form_validation->set_rules('learning_area_id', 'Learning Area', 'trim|required');
            if ($this->form_validation->run() !== false) {
                $this->cbc_model->saveLearningOutcome($this->input->post());
                set_alert('success', translate('information_has_been_updated_successfully'));
                $array = array('status' => 'success', 'url' => base_url('cbc/learning_outcomes'));
            } else {
                $array = array('status' => 'fail', 'error' => $this->form_validation->error_array());
            }
            echo json_encode($array);
        }
    }

    public function learning_outcome_delete($id)
    {
        if (get_permission('cbc_learning_outcomes', 'is_delete')) {
            if (!is_superadmin_loggedin()) {
                $this->db->where('branch_id', get_loggedin_branch_id());
            }
            $this->db->where('id', $id)->delete('cbc_learning_outcomes');
        }
    }

    // AJAX: learning outcomes by sub-strand (for assessment form)
    public function getLearningOutcomesBySubStrand()
    {
        $html = '<option value="">' . translate('select') . ' (optional)</option>';
        $branchID = $this->application_model->get_branch_id();
        $subStrandId = intval($this->input->post('sub_strand_id'));
        $strandId    = intval($this->input->post('strand_id'));
        if ($subStrandId || $strandId) {
            $outcomes = $this->cbc_model->getLearningOutcomes($subStrandId ?: '', $strandId ?: '', $branchID);
            foreach ($outcomes as $lo) {
                $label = $lo['code'] ? '[' . $lo['code'] . '] ' . $lo['name'] : $lo['name'];
                $html .= '<option value="' . $lo['id'] . '">' . htmlspecialchars($label) . '</option>';
            }
        }
        echo $html;
    }

    // AJAX: sub-strands by strand (for assessment entry)
    public function getSubStrandsByStrand()
    {
        $html = '<option value="">' . translate('select') . ' (optional)</option>';
        $branchID = $this->application_model->get_branch_id();
        $strandId = intval($this->input->post('strand_id'));
        if ($strandId) {
            $subs = $this->cbc_model->getSubStrands($strandId, $branchID);
            foreach ($subs as $ss) {
                $html .= '<option value="' . $ss['id'] . '">' . htmlspecialchars($ss['name']) . '</option>';
            }
        }
        echo $html;
    }

    // --- CBC Assessment Entry ---

    public function assessment()
    {
        if (!get_permission('cbc_assessment', 'is_add')) {
            access_denied();
        }
        $branchID = $this->application_model->get_branch_id();
        $this->data['branch_id'] = $branchID;

        if (isset($_POST['search'])) {
            $classID = $this->input->post('class_id');
            $sectionID = $this->input->post('section_id');
            $examID = $this->input->post('exam_id');
            $learningAreaID = $this->input->post('learning_area_id');

            $strandID          = $this->input->post('strand_id');
            $subStrandID       = $this->input->post('sub_strand_id');
            $learningOutcomeID = $this->input->post('learning_outcome_id');

            $this->data['class_id']            = $classID;
            $this->data['section_id']          = $sectionID;
            $this->data['exam_id']             = $examID;
            $this->data['learning_area_id']    = $learningAreaID;
            $this->data['strand_id']           = $strandID;
            $this->data['sub_strand_id']       = $subStrandID;
            $this->data['learning_outcome_id'] = $learningOutcomeID;
            $this->data['students']       = $this->cbc_model->getStudentsForAssessment($classID, $sectionID, $branchID);
            $this->data['strands']        = $this->cbc_model->getStrands($learningAreaID, $branchID);

            foreach ($this->data['students'] as &$student) {
                $student['existing'] = $this->cbc_model->getExistingAssessment(
                    $student['student_id'], $examID, $learningAreaID, get_session_id()
                );
            }
        }

        $this->data['title'] = 'CBC Assessment Entry';
        $this->data['sub_page'] = 'cbc/assessment';
        $this->data['main_menu'] = 'cbc';
        $this->load->view('layout/index', $this->data);
    }

    public function assessment_save()
    {
        if ($_POST) {
            if (!get_permission('cbc_assessment', 'is_add')) {
                ajax_access_denied();
            }
            $branchID = $this->application_model->get_branch_id();
            $classID = $this->input->post('class_id');
            $sectionID = $this->input->post('section_id');
            $examID = $this->input->post('exam_id');
            $learningAreaID = $this->input->post('learning_area_id');
            $assessments = $this->input->post('assessment');

            if (!empty($assessments)) {
                foreach ($assessments as $studentId => $data) {
                    if (!empty($data['competency_level'])) {
                        $this->cbc_model->saveAssessment(array(
                            'student_id'       => $studentId,
                            'exam_id'          => $examID,
                            'learning_area_id' => $learningAreaID,
                            'strand_id'           => isset($data['strand_id'])           ? $data['strand_id']           : null,
                            'sub_strand_id'       => isset($data['sub_strand_id'])       ? $data['sub_strand_id']       : null,
                            'learning_outcome_id' => isset($data['learning_outcome_id']) ? $data['learning_outcome_id'] : null,
                            'competency_level'    => $data['competency_level'],
                            'class_id'         => $classID,
                            'section_id'       => $sectionID,
                            'remarks'          => isset($data['remarks']) ? $data['remarks'] : '',
                            'session_id'       => get_session_id(),
                            'branch_id'        => $branchID,
                        ));
                    }
                }
            }
            set_alert('success', translate('information_has_been_saved_successfully'));
            echo json_encode(array('status' => 'success'));
        }
    }

    // --- Behaviour Assessment ---

    public function behaviour_assessment()
    {
        if (!get_permission('cbc_behaviour', 'is_add')) {
            access_denied();
        }
        $branchID = $this->application_model->get_branch_id();
        $this->data['branch_id'] = $branchID;
        $this->data['categories'] = array('Social', 'Spiritual', 'Emotional', 'Physical', 'Creative');

        if (isset($_POST['search'])) {
            $classID = $this->input->post('class_id');
            $sectionID = $this->input->post('section_id');
            $examID = $this->input->post('exam_id');

            $this->data['class_id'] = $classID;
            $this->data['section_id'] = $sectionID;
            $this->data['exam_id'] = $examID;
            $this->data['students'] = $this->cbc_model->getStudentsForAssessment($classID, $sectionID, $branchID);

            foreach ($this->data['students'] as &$student) {
                $student['behaviours'] = array();
                foreach ($this->data['categories'] as $cat) {
                    $student['behaviours'][$cat] = $this->cbc_model->getExistingBehaviour(
                        $student['student_id'], $examID, $cat, get_session_id()
                    );
                }
            }
        }

        $this->data['title'] = 'CBC Behaviour Assessment';
        $this->data['sub_page'] = 'cbc/behaviour_assessment';
        $this->data['main_menu'] = 'cbc';
        $this->load->view('layout/index', $this->data);
    }

    public function behaviour_save()
    {
        if ($_POST) {
            if (!get_permission('cbc_behaviour', 'is_add')) {
                ajax_access_denied();
            }
            $branchID = $this->application_model->get_branch_id();
            $examID = $this->input->post('exam_id');
            $behaviours = $this->input->post('behaviour');

            if (!empty($behaviours)) {
                foreach ($behaviours as $studentId => $categories) {
                    foreach ($categories as $category => $data) {
                        if (!empty($data['rating'])) {
                            $this->cbc_model->saveBehaviour(array(
                                'student_id' => $studentId,
                                'exam_id' => $examID,
                                'category' => $category,
                                'rating' => $data['rating'],
                                'remarks' => isset($data['remarks']) ? $data['remarks'] : '',
                                'session_id' => get_session_id(),
                                'branch_id' => $branchID,
                            ));
                        }
                    }
                }
            }
            set_alert('success', translate('information_has_been_saved_successfully'));
            echo json_encode(array('status' => 'success'));
        }
    }

    // --- CBC Report Card ---

    public function report_card()
    {
        if (!get_permission('cbc_report_card', 'is_view')) {
            access_denied();
        }
        $branchID = $this->application_model->get_branch_id();
        if ($_POST) {
            $sessionID = $this->input->post('session_id');
            $examID = $this->input->post('exam_id');
            $classID = $this->input->post('class_id');
            $sectionID = $this->input->post('section_id');

            $this->db->select('e.roll, s.id, s.first_name, s.last_name, s.register_no, s.photo');
            $this->db->from('enroll as e');
            $this->db->join('student as s', 'e.student_id = s.id', 'inner');
            $this->db->join('cbc_assessment as ca', 'ca.student_id = s.id AND ca.exam_id = ' . intval($examID), 'inner');
            $this->db->where('e.session_id', $sessionID);
            $this->db->where('e.class_id', $classID);
            $this->db->where('e.section_id', $sectionID);
            $this->db->where('e.branch_id', $branchID);
            $this->db->group_by('s.id');
            $this->data['students'] = $this->db->get()->result_array();
        }
        $this->data['branch_id'] = $branchID;
        $this->data['title'] = 'CBC Report Card';
        $this->data['sub_page'] = 'cbc/report_card';
        $this->data['main_menu'] = 'cbc';
        $this->load->view('layout/index', $this->data);
    }

    public function report_card_print()
    {
        if ($_POST) {
            if (!get_permission('cbc_report_card', 'is_view')) {
                ajax_access_denied();
            }
            $this->data['student_array'] = $this->input->post('student_id');
            $this->data['examID'] = $this->input->post('exam_id');
            $this->data['sessionID'] = $this->input->post('session_id');
            $this->data['print_date'] = $this->input->post('print_date');
            echo $this->load->view('cbc/reportCardPrint', $this->data, true);
        }
    }

    // --- AJAX helpers ---

    public function getLearningAreasByBranch()
    {
        $html = '<option value="">' . translate('select') . '</option>';
        $branchID = $this->application_model->get_branch_id();
        $classID = $this->input->post('class_id');
        if (!empty($classID)) {
            $areas = $this->cbc_model->getLearningAreasByClassLevel($classID, $branchID);
            foreach ($areas as $area) {
                $html .= '<option value="' . $area['id'] . '">' . $area['name'] . ' (' . ucfirst(str_replace('_', ' ', $area['level'])) . ')</option>';
            }
        }
        echo $html;
    }

    public function getCbcExamsByBranch()
    {
        $html = '<option value="">' . translate('select') . '</option>';
        $branchID = $this->application_model->get_branch_id();
        $exams = $this->cbc_model->getCbcExams($branchID);
        foreach ($exams as $exam) {
            $label = $exam['name'];
            if (!empty($exam['term_name'])) {
                $label .= ' (' . $exam['term_name'] . ')';
            }
            $html .= '<option value="' . $exam['id'] . '">' . $label . '</option>';
        }
        echo $html;
    }
}
