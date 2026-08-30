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

    // --- CBC Portfolio ---

    public function portfolio()
    {
        if (!get_permission('cbc_portfolio', 'is_view')) { access_denied(); }
        $branchID = $this->application_model->get_branch_id();

        // Student filter
        $studentId = intval($this->input->get('student_id'));

        $this->data['branch_id']      = $branchID;
        $this->data['student_id']     = $studentId;
        $this->data['learning_areas'] = $this->cbc_model->getLearningAreas($branchID);
        $this->data['portfolio']      = $studentId ? $this->cbc_model->getPortfolio($studentId, $branchID) : array();
        $this->data['title']          = 'CBC Portfolio';
        $this->data['sub_page']       = 'cbc/portfolio';
        $this->data['main_menu']      = 'cbc';
        $this->load->view('layout/index', $this->data);
    }

    public function portfolio_save()
    {
        if (!get_permission('cbc_portfolio', 'is_add')) { ajax_access_denied(); }
        $this->form_validation->set_rules('student_id',       'Student',        'trim|required');
        $this->form_validation->set_rules('learning_area_id', 'Learning Area',  'trim|required');
        $this->form_validation->set_rules('title',            'Title',          'trim|required');
        $this->form_validation->set_rules('entry_date',       'Date',           'trim|required');

        if ($this->form_validation->run()) {
            $branchID = $this->application_model->get_branch_id();

            // Handle file upload
            $evidenceFile = '';
            if (!empty($_FILES['evidence_file']['name'])) {
                $config = array(
                    'upload_path'   => FCPATH . 'uploads/cbc_portfolio/',
                    'allowed_types' => 'jpg|jpeg|png|gif|pdf|doc|docx|mp4|mov',
                    'max_size'      => 10240,
                    'encrypt_name'  => true,
                );
                if (!is_dir($config['upload_path'])) mkdir($config['upload_path'], 0755, true);
                $this->load->library('upload', $config);
                if ($this->upload->do_upload('evidence_file')) {
                    $evidenceFile = $this->upload->data('file_name');
                }
            }

            $this->cbc_model->savePortfolioEntry(array(
                'portfolio_id'      => $this->input->post('portfolio_id'),
                'student_id'        => $this->input->post('student_id'),
                'learning_area_id'  => $this->input->post('learning_area_id'),
                'strand_id'         => $this->input->post('strand_id'),
                'title'             => $this->input->post('title'),
                'description'       => $this->input->post('description'),
                'competency_level'  => $this->input->post('competency_level'),
                'evidence_file'     => $evidenceFile,
                'entry_date'        => $this->input->post('entry_date'),
                'session_id'        => get_session_id(),
                'branch_id'         => $branchID,
                'created_by'        => get_loggedin_user_id(),
            ));
            set_alert('success', translate('information_has_been_saved_successfully'));
        }
        redirect(base_url('cbc/portfolio?student_id=' . $this->input->post('student_id')));
    }

    public function portfolio_delete($id = '')
    {
        if (!get_permission('cbc_portfolio', 'is_delete')) { access_denied(); }
        $branchID = $this->application_model->get_branch_id();
        $this->db->where('id', $id)->where('branch_id', $branchID)->delete('cbc_portfolio');
    }

    // --- CBC Projects ---

    public function projects()
    {
        if (!get_permission('cbc_projects', 'is_view')) { access_denied(); }
        $branchID  = $this->application_model->get_branch_id();
        $classId   = intval($this->input->get('class_id'));
        $sectionId = intval($this->input->get('section_id'));

        $this->data['branch_id']      = $branchID;
        $this->data['class_id']       = $classId;
        $this->data['section_id']     = $sectionId;
        $this->data['projects']       = $this->cbc_model->getProjects($branchID, $classId ?: null, $sectionId ?: null);
        $this->data['learning_areas'] = $this->cbc_model->getLearningAreas($branchID);
        $this->data['title']          = 'CBC Projects';
        $this->data['sub_page']       = 'cbc/projects';
        $this->data['main_menu']      = 'cbc';
        $this->load->view('layout/index', $this->data);
    }

    public function project_save()
    {
        if (!get_permission('cbc_projects', 'is_add')) { ajax_access_denied(); }
        $this->form_validation->set_rules('name',       'Project Name', 'trim|required');
        $this->form_validation->set_rules('class_id',   'Class',        'trim|required');
        $this->form_validation->set_rules('section_id', 'Section',      'trim|required');
        $this->form_validation->set_rules('max_score',  'Max Score',    'trim|required|numeric');

        if ($this->form_validation->run()) {
            $branchID = $this->application_model->get_branch_id();
            $this->cbc_model->saveProject(array(
                'project_id'        => $this->input->post('project_id'),
                'name'              => $this->input->post('name'),
                'description'       => $this->input->post('description'),
                'learning_area_id'  => $this->input->post('learning_area_id'),
                'class_id'          => $this->input->post('class_id'),
                'section_id'        => $this->input->post('section_id'),
                'due_date'          => $this->input->post('due_date'),
                'max_score'         => $this->input->post('max_score'),
                'session_id'        => get_session_id(),
                'branch_id'         => $branchID,
            ));
            set_alert('success', translate('information_has_been_saved_successfully'));
        }
        redirect(base_url('cbc/projects'));
    }

    public function project_delete($id = '')
    {
        if (!get_permission('cbc_projects', 'is_delete')) { access_denied(); }
        $branchID = $this->application_model->get_branch_id();
        $this->db->where('id', $id)->where('branch_id', $branchID)->delete('cbc_projects');
        $this->db->where('project_id', $id)->delete('cbc_project_scores');
    }

    public function project_scores($projectId = '')
    {
        if (!get_permission('cbc_projects', 'is_add')) { access_denied(); }
        $branchID = $this->application_model->get_branch_id();
        $project  = $this->db->where('id', $projectId)->where('branch_id', $branchID)->get('cbc_projects')->row_array();
        if (empty($project)) { show_404(); }

        // Students in this class/section
        $this->db->select('e.student_id, s.first_name, s.last_name, s.register_no, s.photo, e.roll');
        $this->db->from('enroll as e');
        $this->db->join('student as s', 's.id = e.student_id', 'inner');
        $this->db->where(array('e.class_id' => $project['class_id'], 'e.section_id' => $project['section_id'], 'e.session_id' => get_session_id(), 'e.branch_id' => $branchID));
        $students = $this->db->get()->result_array();

        // Existing scores
        $existingRaw = $this->cbc_model->getProjectScores($projectId);
        $existing = array();
        foreach ($existingRaw as $r) { $existing[$r['student_id']] = $r; }

        if ($_POST) {
            $scores = $this->input->post('scores');
            if ($scores) {
                $this->cbc_model->saveProjectScores($projectId, $scores, $branchID);
                set_alert('success', translate('information_has_been_saved_successfully'));
            }
            redirect(base_url('cbc/project_scores/' . $projectId));
        }

        $this->data['project']  = $project;
        $this->data['students'] = $students;
        $this->data['existing'] = $existing;
        $this->data['title']    = 'Project Scores: ' . $project['name'];
        $this->data['sub_page'] = 'cbc/project_scores';
        $this->data['main_menu']= 'cbc';
        $this->load->view('layout/index', $this->data);
    }

    // --- CBC Pathways (Senior Secondary) ---

    public function pathways()
    {
        if (!get_permission('cbc_pathways', 'is_view')) { access_denied(); }
        $branchID = $this->application_model->get_branch_id();

        if ($this->input->post('save')) {
            if (!get_permission('cbc_pathways', 'is_add')) { access_denied(); }
            $this->form_validation->set_rules('name', 'Pathway Name', 'trim|required');
            if ($this->form_validation->run()) {
                $this->cbc_model->savePathway(array(
                    'name'        => $this->input->post('name'),
                    'description' => $this->input->post('description'),
                    'branch_id'   => $branchID,
                ));
                set_alert('success', translate('information_has_been_saved_successfully'));
                redirect(base_url('cbc/pathways'));
            }
        }

        $this->data['pathways']       = $this->cbc_model->getPathways($branchID);
        $this->data['learning_areas'] = $this->cbc_model->getLearningAreas($branchID, 'senior_secondary');
        $this->data['title']          = 'CBC Pathways';
        $this->data['sub_page']       = 'cbc/pathways';
        $this->data['main_menu']      = 'cbc';
        $this->load->view('layout/index', $this->data);
    }

    public function pathway_edit()
    {
        if (!get_permission('cbc_pathways', 'is_edit')) { ajax_access_denied(); }
        $this->form_validation->set_rules('name', 'Pathway Name', 'trim|required');
        if ($this->form_validation->run()) {
            $this->cbc_model->savePathway(array(
                'pathway_id'  => $this->input->post('pathway_id'),
                'name'        => $this->input->post('name'),
                'description' => $this->input->post('description'),
            ));
            set_alert('success', translate('information_has_been_updated_successfully'));
        }
        $array = array('status' => validation_errors() ? 'fail' : 'success', 'error' => validation_errors());
        echo json_encode($array);
    }

    public function pathway_delete($id = '')
    {
        if (!get_permission('cbc_pathways', 'is_delete')) { access_denied(); }
        $branchID = $this->application_model->get_branch_id();
        $this->db->where('id', $id)->where('branch_id', $branchID)->delete('cbc_pathways');
        // Unlink from learning areas
        $this->db->where('pathway_id', $id)->set('pathway_id', null)->update('cbc_learning_areas');
        // Unlink from students
        $this->db->where('cbc_pathway_id', $id)->set('cbc_pathway_id', null)->update('student');
    }

    public function pathway_assign_la()
    {
        if (!get_permission('cbc_pathways', 'is_edit')) { ajax_access_denied(); }
        $laId      = intval($this->input->post('la_id'));
        $pathwayId = $this->input->post('pathway_id'); // may be empty to unassign
        $this->db->where('id', $laId)->update('cbc_learning_areas', array('pathway_id' => ($pathwayId ?: null)));
        echo json_encode(array('status' => 'success'));
    }

    // --- CBC Analytics ---

    public function analytics()
    {
        if (!get_permission('cbc_assessment', 'is_view')) {
            access_denied();
        }
        $branchID = $this->application_model->get_branch_id();
        $this->data['branch_id']  = $branchID;
        $this->data['title']      = 'CBC Analytics';
        $this->data['sub_page']   = 'cbc/analytics';
        $this->data['main_menu']  = 'cbc';
        $this->load->view('layout/index', $this->data);
    }

    public function getAnalyticsData()
    {
        if (!get_permission('cbc_assessment', 'is_view')) {
            echo json_encode(array('error' => 'access denied')); return;
        }
        $branchID  = $this->application_model->get_branch_id();
        $examId    = intval($this->input->post('exam_id'));
        $classId   = intval($this->input->post('class_id'));
        $sectionId = intval($this->input->post('section_id'));
        $sessionId = get_session_id();

        $rows    = $this->cbc_model->getAnalyticsData($examId, $classId, $sectionId, $branchID);
        $trend   = $this->cbc_model->getAnalyticsTrend($classId, $sectionId, $branchID, $sessionId);

        // Total enrolled students in this class/section
        $totalStudents = $this->db
            ->where(array('class_id' => $classId, 'section_id' => $sectionId, 'session_id' => $sessionId, 'branch_id' => $branchID))
            ->count_all_results('enroll');

        echo json_encode(array(
            'rows'    => $rows,
            'trend'   => $trend,
            'total'   => $totalStudents,
        ));
    }

    // --- AJAX helpers ---

    public function getStudentsBySection()
    {
        $branchID  = $this->application_model->get_branch_id();
        $classId   = intval($this->input->post('class_id'));
        $sectionId = intval($this->input->post('section_id'));
        $html = '<option value="">' . translate('select') . '</option>';
        if ($classId && $sectionId) {
            $this->db->select('s.id, s.first_name, s.last_name, s.register_no');
            $this->db->from('enroll as e');
            $this->db->join('student as s', 's.id = e.student_id', 'inner');
            $this->db->where(array('e.class_id' => $classId, 'e.section_id' => $sectionId, 'e.session_id' => get_session_id(), 'e.branch_id' => $branchID));
            $this->db->order_by('s.first_name', 'ASC');
            $rows = $this->db->get()->result_array();
            foreach ($rows as $r) {
                $html .= '<option value="' . $r['id'] . '">' . htmlspecialchars($r['first_name'] . ' ' . $r['last_name']) . ' (' . $r['register_no'] . ')</option>';
            }
        }
        echo $html;
    }

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
