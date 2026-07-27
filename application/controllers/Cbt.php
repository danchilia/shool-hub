<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cbt extends Admin_Controller {

    public function __construct() {
        parent::__construct();
    }

    // ── Admin: Quiz list ────────────────────────────────────────────────
    public function index() {
        $branchId = get_loggedin_branch_id();
        $quizzes  = $this->db->where('branch_id', $branchId)->order_by('id','DESC')->get('cbt_quiz')->result();

        // Pre-aggregate to avoid N+1 queries in the view
        $qRows = $this->db->select('quiz_id, COUNT(*) AS cnt')->group_by('quiz_id')->get('cbt_questions')->result();
        $qCounts = [];
        foreach ($qRows as $r) { $qCounts[$r->quiz_id] = (int)$r->cnt; }

        $aRows = $this->db->select('quiz_id, COUNT(*) AS cnt')->where('status','submitted')->group_by('quiz_id')->get('cbt_attempts')->result();
        $aCounts = [];
        foreach ($aRows as $r) { $aCounts[$r->quiz_id] = (int)$r->cnt; }

        $this->data['quizzes']   = $quizzes;
        $this->data['qCounts']   = $qCounts;
        $this->data['aCounts']   = $aCounts;
        $this->data['classes']   = $this->db->where('branch_id', $branchId)->get('class')->result();
        $this->data['subjects']  = $this->db->where('branch_id', $branchId)->get('subject')->result();
        $this->data['title']     = 'CBT Examinations';
        $this->data['main_menu'] = 'cbt';
        $this->data['sub_page']  = 'cbt/index';
        $this->load->view('layout/index', $this->data);
    }

    public function save_quiz() {
        if (!$this->input->is_ajax_request()) show_404();
        $branchId = get_loggedin_branch_id();

        $rules = [
            ['field'=>'title',         'label'=>'Title',    'rules'=>'required|max_length[300]'],
            ['field'=>'duration_mins', 'label'=>'Duration', 'rules'=>'required|is_natural_no_zero'],
            ['field'=>'total_marks',   'label'=>'Marks',    'rules'=>'required|is_natural_no_zero'],
        ];
        $this->form_validation->set_rules($rules);
        if (!$this->form_validation->run()) {
            echo json_encode(['status'=>'error','msg'=>validation_errors()]);
            return;
        }

        $data = [
            'title'             => $this->input->post('title'),
            'subject_id'        => $this->input->post('subject_id') ?: null,
            'class_id'          => $this->input->post('class_id') ?: null,
            'section_id'        => $this->input->post('section_id') ?: null,
            'duration_mins'     => $this->input->post('duration_mins'),
            'start_datetime'    => $this->input->post('start_datetime') ?: null,
            'end_datetime'      => $this->input->post('end_datetime') ?: null,
            'total_marks'       => $this->input->post('total_marks'),
            'pass_marks'        => $this->input->post('pass_marks') ?: null,
            'instructions'      => $this->input->post('instructions'),
            'shuffle_questions' => $this->input->post('shuffle_questions') ? 1 : 0,
            'shuffle_options'   => $this->input->post('shuffle_options') ? 1 : 0,
            'show_result'       => $this->input->post('show_result') ? 1 : 0,
            'status'            => $this->input->post('status') ?: 'draft',
            'created_by'        => get_loggedin_user_id(),
            'branch_id'         => $branchId,
        ];

        $id = $this->input->post('id');
        if ($id) {
            $this->db->where('id', $id)->where('branch_id', $branchId)->update('cbt_quiz', $data);
            echo json_encode(['status'=>'success','url'=>base_url('cbt')]);
        } else {
            $this->db->insert('cbt_quiz', $data);
            $id = $this->db->insert_id();
            echo json_encode(['status'=>'success','url'=>base_url('cbt/questions/'.$id)]);
        }
    }

    public function delete_quiz($id) {
        $branchId   = get_loggedin_branch_id();
        $attemptIds = array_column(
            $this->db->select('id')->where('quiz_id', $id)->get('cbt_attempts')->result_array(),
            'id'
        );
        if (!empty($attemptIds)) {
            $this->db->where_in('attempt_id', $attemptIds)->delete('cbt_answers');
            $this->db->where_in('id', $attemptIds)->delete('cbt_attempts');
        }
        $this->db->where('quiz_id', $id)->delete('cbt_questions');
        $this->db->where('id', $id)->where('branch_id', $branchId)->delete('cbt_quiz');
        echo json_encode(['status'=>'success','url'=>base_url('cbt')]);
    }

    // ── Question management ─────────────────────────────────────────────
    public function questions($quizId) {
        $branchId = get_loggedin_branch_id();
        $quiz     = $this->db->where('id', $quizId)->where('branch_id', $branchId)->get('cbt_quiz')->row();
        if (!$quiz) show_404();

        $questions = $this->db->where('quiz_id', $quizId)->get('cbt_questions')->result();

        $this->data['quiz']      = $quiz;
        $this->data['questions'] = $questions;
        $this->data['title']     = 'Questions: ' . $quiz->title;
        $this->data['main_menu'] = 'cbt';
        $this->data['sub_page']  = 'cbt/questions';
        $this->load->view('layout/index', $this->data);
    }

    public function save_question() {
        if (!$this->input->is_ajax_request()) show_404();
        $branchId = get_loggedin_branch_id();

        $rules = [
            ['field'=>'quiz_id',       'label'=>'Quiz',     'rules'=>'required|is_natural_no_zero'],
            ['field'=>'question_text', 'label'=>'Question', 'rules'=>'required'],
            ['field'=>'correct_answer','label'=>'Answer',   'rules'=>'required'],
            ['field'=>'marks',         'label'=>'Marks',    'rules'=>'required|is_natural_no_zero'],
        ];
        $this->form_validation->set_rules($rules);
        if (!$this->form_validation->run()) {
            echo json_encode(['status'=>'error','msg'=>validation_errors()]);
            return;
        }

        $quizId = $this->input->post('quiz_id');
        $data = [
            'quiz_id'       => $quizId,
            'question_text' => $this->input->post('question_text'),
            'question_type' => $this->input->post('question_type') ?: 'mcq',
            'option_a'      => $this->input->post('option_a'),
            'option_b'      => $this->input->post('option_b'),
            'option_c'      => $this->input->post('option_c'),
            'option_d'      => $this->input->post('option_d'),
            'correct_answer'=> $this->input->post('correct_answer'),
            'marks'         => $this->input->post('marks'),
            'branch_id'     => $branchId,
        ];

        $id = $this->input->post('id');
        if ($id) {
            $this->db->where('id', $id)->update('cbt_questions', $data);
        } else {
            $this->db->insert('cbt_questions', $data);
        }
        echo json_encode(['status'=>'success','url'=>base_url('cbt/questions/'.$quizId)]);
    }

    public function delete_question($id) {
        $branchId = get_loggedin_branch_id();
        $q        = $this->db->where('id',$id)->where('branch_id',$branchId)->get('cbt_questions')->row();
        $quizId   = $q ? $q->quiz_id : 0;
        $this->db->where('id',$id)->where('branch_id',$branchId)->delete('cbt_questions');
        echo json_encode(['status'=>'success','url'=>base_url('cbt/questions/'.$quizId)]);
    }

    // ── Results overview ────────────────────────────────────────────────
    public function results($quizId) {
        $branchId = get_loggedin_branch_id();
        $quiz     = $this->db->where('id',$quizId)->where('branch_id',$branchId)->get('cbt_quiz')->row();
        if (!$quiz) show_404();

        $attempts = $this->db
            ->select('a.*, s.first_name, s.last_name, s.register_no AS admission_no')
            ->from('cbt_attempts a')
            ->join('student s','s.id=a.student_id','left')
            ->where('a.quiz_id', $quizId)
            ->where('a.branch_id', $branchId)
            ->order_by('a.total_score','DESC')
            ->get()->result();

        $this->data['quiz']      = $quiz;
        $this->data['attempts']  = $attempts;
        $this->data['title']     = 'Results: ' . $quiz->title;
        $this->data['main_menu'] = 'cbt';
        $this->data['sub_page']  = 'cbt/results';
        $this->load->view('layout/index', $this->data);
    }

    // ── Student exam session ────────────────────────────────────────────
    public function take($quizId) {
        $branchId  = get_loggedin_branch_id();
        $studentId = $this->session->userdata('student_id');
        if (!$studentId) redirect('authentication/login');

        $quiz = $this->db->where('id',$quizId)->where('branch_id',$branchId)
                         ->where('status','published')->get('cbt_quiz')->row();
        if (!$quiz) show_404();

        // Check window
        $now = date('Y-m-d H:i:s');
        if ($quiz->start_datetime && $now < $quiz->start_datetime) {
            show_error('This exam has not started yet.');
        }
        if ($quiz->end_datetime && $now > $quiz->end_datetime) {
            show_error('This exam window has closed.');
        }

        // Existing attempt?
        $attempt = $this->db->where('quiz_id',$quizId)->where('student_id',$studentId)->get('cbt_attempts')->row();
        if ($attempt && $attempt->status !== 'in_progress') {
            redirect('cbt/my_result/'.$attempt->id);
        }

        if (!$attempt) {
            $this->db->insert('cbt_attempts',[
                'quiz_id'    => $quizId,
                'student_id' => $studentId,
                'started_at' => $now,
                'status'     => 'in_progress',
                'branch_id'  => $branchId,
            ]);
            $attemptId = $this->db->insert_id();
        } else {
            $attemptId = $attempt->id;
        }

        $questions = $this->db->where('quiz_id',$quizId)->get('cbt_questions')->result();
        if ($quiz->shuffle_questions) shuffle($questions);

        $this->data['quiz']      = $quiz;
        $this->data['questions'] = $questions;
        $this->data['attempt_id']= $attemptId;
        $this->data['title']     = $quiz->title;
        $this->data['main_menu'] = 'cbt';
        $this->data['sub_page']  = 'cbt/take';
        $this->load->view('layout/index', $this->data);
    }

    public function submit() {
        if (!$this->input->is_ajax_request()) show_404();
        $studentId = $this->session->userdata('student_id');
        if (!$studentId) { echo json_encode(['status'=>'error','msg'=>'Not logged in']); return; }

        $attemptId = $this->input->post('attempt_id');
        $attempt   = $this->db->where('id',$attemptId)->where('student_id',$studentId)->get('cbt_attempts')->row();
        if (!$attempt || $attempt->status !== 'in_progress') {
            echo json_encode(['status'=>'error','msg'=>'Invalid attempt']); return;
        }

        $quiz      = $this->db->where('id',$attempt->quiz_id)->get('cbt_quiz')->row();

        // Server-side time limit: reject if more than 2 minutes past the allowed duration
        if ((time() - strtotime($attempt->started_at)) > ($quiz->duration_mins + 2) * 60) {
            echo json_encode(['status'=>'error','msg'=>'Time limit exceeded. Contact your teacher.']); return;
        }

        $questions = $this->db->where('quiz_id',$attempt->quiz_id)->get('cbt_questions')->result();
        $answers   = $this->input->post('answers'); // array: [question_id => answer]
        $totalScore = 0;

        foreach ($questions as $q) {
            $given    = isset($answers[$q->id]) ? trim($answers[$q->id]) : '';
            $correct  = strtolower(trim($q->correct_answer)) === strtolower($given);
            $awarded  = $correct ? $q->marks : 0;
            $totalScore += $awarded;

            $this->db->insert('cbt_answers',[
                'attempt_id'   => $attemptId,
                'question_id'  => $q->id,
                'student_answer'=> $given,
                'is_correct'   => $correct ? 1 : 0,
                'marks_awarded'=> $awarded,
                'branch_id'    => $attempt->branch_id,
            ]);
        }

        $this->db->where('id',$attemptId)->update('cbt_attempts',[
            'submitted_at' => date('Y-m-d H:i:s'),
            'total_score'  => $totalScore,
            'status'       => 'submitted',
        ]);

        echo json_encode(['status'=>'success','url'=>base_url('cbt/my_result/'.$attemptId)]);
    }

    public function my_result($attemptId) {
        $studentId = $this->session->userdata('student_id');
        $attempt   = $this->db->where('id',$attemptId)->get('cbt_attempts')->row();
        if (!$attempt) show_404();
        // Students can only see their own result; admins/teachers (no student_id) can see any
        if ($studentId && (int)$attempt->student_id !== (int)$studentId) show_404();

        $quiz    = $this->db->where('id',$attempt->quiz_id)->get('cbt_quiz')->row();
        $answers = $this->db->select('a.*, q.question_text, q.correct_answer, q.option_a, q.option_b, q.option_c, q.option_d, q.marks')
                            ->from('cbt_answers a')
                            ->join('cbt_questions q','q.id=a.question_id','left')
                            ->where('a.attempt_id',$attemptId)->get()->result();

        $this->data['attempt'] = $attempt;
        $this->data['quiz']    = $quiz;
        $this->data['answers'] = $answers;
        $this->data['title']   = 'Exam Result';
        $this->data['main_menu'] = 'cbt';
        $this->data['sub_page']  = 'cbt/my_result';
        $this->load->view('layout/index', $this->data);
    }
}
