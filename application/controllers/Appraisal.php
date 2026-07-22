<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Appraisal extends Admin_Controller {

    public function __construct() {
        parent::__construct();
    }

    private function _branchId() { return get_loggedin_branch_id(); }

    // ── Templates ───────────────────────────────────────────────────────
    public function templates() {
        $branchId = $this->_branchId();
        $templates = $this->db->where('branch_id', $branchId)->get('appraisal_templates')->result();

        $this->data['templates']  = $templates;
        $this->data['title']      = 'Appraisal Templates';
        $this->data['main_menu']  = 'appraisal';
        $this->data['sub_page']   = 'appraisal/templates';
        $this->load->view('layout/index', $this->data);
    }

    public function save_template() {
        if (!$this->input->is_ajax_request()) show_404();
        $branchId = $this->_branchId();

        $this->form_validation->set_rules('name','Template Name','required|max_length[300]');
        if (!$this->form_validation->run()) {
            echo json_encode(['status'=>'error','msg'=>validation_errors()]); return;
        }

        $data = [
            'name'            => $this->input->post('name'),
            'description'     => $this->input->post('description'),
            'appraisal_type'  => $this->input->post('appraisal_type') ?: 'annual',
            'status'          => 'active',
            'branch_id'       => $branchId,
        ];

        $id = $this->input->post('id');
        if ($id) {
            $this->db->where('id',$id)->where('branch_id',$branchId)->update('appraisal_templates',$data);
        } else {
            $this->db->insert('appraisal_templates',$data);
            $id = $this->db->insert_id();
        }
        echo json_encode(['status'=>'success','url'=>base_url('appraisal/criteria/'.$id)]);
    }

    public function delete_template($id) {
        $branchId = $this->_branchId();
        $this->db->where('template_id',$id)->delete('appraisal_criteria');
        $this->db->where('id',$id)->where('branch_id',$branchId)->delete('appraisal_templates');
        echo json_encode(['status'=>'success','url'=>base_url('appraisal/templates')]);
    }

    // ── Criteria ────────────────────────────────────────────────────────
    public function criteria($templateId) {
        $branchId = $this->_branchId();
        $template = $this->db->where('id',$templateId)->where('branch_id',$branchId)->get('appraisal_templates')->row();
        if (!$template) show_404();

        $criteria = $this->db->where('template_id',$templateId)->order_by('category,id')->get('appraisal_criteria')->result();

        $this->data['template'] = $template;
        $this->data['criteria'] = $criteria;
        $this->data['title']    = 'Criteria: ' . $template->name;
        $this->data['main_menu'] = 'appraisal';
        $this->data['sub_page']  = 'appraisal/criteria';
        $this->load->view('layout/index', $this->data);
    }

    public function save_criterion() {
        if (!$this->input->is_ajax_request()) show_404();
        $branchId = $this->_branchId();

        $rules = [
            ['field'=>'template_id','label'=>'Template', 'rules'=>'required'],
            ['field'=>'criterion',  'label'=>'Criterion','rules'=>'required|max_length[300]'],
            ['field'=>'max_score',  'label'=>'Max Score','rules'=>'required|is_natural_no_zero'],
        ];
        $this->form_validation->set_rules($rules);
        if (!$this->form_validation->run()) {
            echo json_encode(['status'=>'error','msg'=>validation_errors()]); return;
        }

        $templateId = $this->input->post('template_id');
        $data = [
            'template_id' => $templateId,
            'criterion'   => $this->input->post('criterion'),
            'max_score'   => $this->input->post('max_score'),
            'weight'      => $this->input->post('weight') ?: 1,
            'category'    => $this->input->post('category'),
            'branch_id'   => $branchId,
        ];

        $id = $this->input->post('id');
        $id ? $this->db->where('id',$id)->update('appraisal_criteria',$data)
            : $this->db->insert('appraisal_criteria',$data);
        echo json_encode(['status'=>'success','url'=>base_url('appraisal/criteria/'.$templateId)]);
    }

    public function delete_criterion($id) {
        $row = $this->db->where('id',$id)->get('appraisal_criteria')->row();
        $tplId = $row ? $row->template_id : 0;
        $this->db->where('id',$id)->delete('appraisal_criteria');
        echo json_encode(['status'=>'success','url'=>base_url('appraisal/criteria/'.$tplId)]);
    }

    // ── Appraisals overview ─────────────────────────────────────────────
    public function index() {
        $branchId = $this->_branchId();
        $year     = $this->input->get('year') ?: date('Y');

        $appraisals = $this->db
            ->select('a.*, s.name AS staff_name, d.name AS designation, t.name AS template_name')
            ->from('appraisals a')
            ->join('staff s','s.id=a.staff_id','left')
            ->join('staff_designation d','d.id=s.designation','left')
            ->join('appraisal_templates t','t.id=a.template_id','left')
            ->where('a.branch_id',$branchId)
            ->where('a.appraisal_year',$year)
            ->order_by('a.created_at','DESC')
            ->get()->result();

        $staff     = $this->db->where('branch_id',$branchId)->get('staff')->result();
        $templates = $this->db->where('branch_id',$branchId)->where('status','active')->get('appraisal_templates')->result();

        $this->data['appraisals'] = $appraisals;
        $this->data['staff']      = $staff;
        $this->data['templates']  = $templates;
        $this->data['year']       = $year;
        $this->data['title']      = 'Staff Appraisals';
        $this->data['main_menu']  = 'appraisal';
        $this->data['sub_page']   = 'appraisal/index';
        $this->load->view('layout/index', $this->data);
    }

    public function start_appraisal() {
        if (!$this->input->is_ajax_request()) show_404();
        $branchId = $this->_branchId();

        $rules = [
            ['field'=>'template_id','label'=>'Template','rules'=>'required'],
            ['field'=>'staff_id',   'label'=>'Staff',   'rules'=>'required'],
            ['field'=>'appraisal_year','label'=>'Year', 'rules'=>'required'],
        ];
        $this->form_validation->set_rules($rules);
        if (!$this->form_validation->run()) {
            echo json_encode(['status'=>'error','msg'=>validation_errors()]); return;
        }

        $tplId   = $this->input->post('template_id');
        $staffId = $this->input->post('staff_id');
        $year    = $this->input->post('appraisal_year');

        $exists = $this->db->where('template_id',$tplId)->where('staff_id',$staffId)
                           ->where('appraisal_year',$year)->count_all_results('appraisals');
        if ($exists) {
            echo json_encode(['status'=>'error','msg'=>'An appraisal already exists for this staff/year.']); return;
        }

        $this->db->insert('appraisals',[
            'template_id'     => $tplId,
            'staff_id'        => $staffId,
            'appraiser_id'    => get_loggedin_user_id(),
            'appraisal_year'  => $year,
            'appraisal_period'=> $this->input->post('appraisal_period'),
            'status'          => 'draft',
            'branch_id'       => $branchId,
        ]);
        $appraisalId = $this->db->insert_id();
        echo json_encode(['status'=>'success','url'=>base_url('appraisal/fill/'.$appraisalId)]);
    }

    // ── Fill / Score appraisal ──────────────────────────────────────────
    public function fill($appraisalId) {
        $branchId  = $this->_branchId();
        $appraisal = $this->db
            ->select('a.*, s.name AS staff_name, d.name AS designation, t.name AS template_name, t.appraisal_type')
            ->from('appraisals a')
            ->join('staff s','s.id=a.staff_id','left')
            ->join('staff_designation d','d.id=s.designation','left')
            ->join('appraisal_templates t','t.id=a.template_id','left')
            ->where('a.id',$appraisalId)->where('a.branch_id',$branchId)->get()->row();
        if (!$appraisal) show_404();

        $criteria = $this->db->where('template_id',$appraisal->template_id)->order_by('category,id')->get('appraisal_criteria')->result();
        $scores   = [];
        foreach ($this->db->where('appraisal_id',$appraisalId)->get('appraisal_scores')->result() as $sc) {
            $scores[$sc->criterion_id] = $sc;
        }

        $this->data['appraisal'] = $appraisal;
        $this->data['criteria']  = $criteria;
        $this->data['scores']    = $scores;
        $this->data['title']     = 'Appraisal Form';
        $this->data['main_menu'] = 'appraisal';
        $this->data['sub_page']  = 'appraisal/fill';
        $this->load->view('layout/index', $this->data);
    }

    public function save_scores() {
        if (!$this->input->is_ajax_request()) show_404();
        $branchId    = $this->_branchId();
        $appraisalId = $this->input->post('appraisal_id');

        $appraisal = $this->db->where('id',$appraisalId)->where('branch_id',$branchId)->get('appraisals')->row();
        if (!$appraisal) { echo json_encode(['status'=>'error','msg'=>'Not found']); return; }

        $scores  = $this->input->post('scores');   // [criterion_id => score]
        $comments= $this->input->post('comments'); // [criterion_id => comment]

        $totalWeightedScore = 0;
        $totalWeight        = 0;

        $criteria = $this->db->where('template_id',$appraisal->template_id)->get('appraisal_criteria')->result();
        foreach ($criteria as $c) {
            $score = isset($scores[$c->id]) ? (int)$scores[$c->id] : 0;
            $score = min($score, $c->max_score);

            $this->db->delete('appraisal_scores', ['appraisal_id'=>$appraisalId,'criterion_id'=>$c->id]);
            $this->db->insert('appraisal_scores',[
                'appraisal_id'   => $appraisalId,
                'criterion_id'   => $c->id,
                'appraiser_score'=> $score,
                'comments'       => isset($comments[$c->id]) ? $comments[$c->id] : null,
                'branch_id'      => $branchId,
            ]);

            $pct = $c->max_score > 0 ? ($score / $c->max_score) * 100 : 0;
            $totalWeightedScore += $pct * $c->weight;
            $totalWeight        += $c->weight;
        }

        $overall = $totalWeight > 0 ? round($totalWeightedScore / $totalWeight, 2) : 0;
        $grade   = $overall >= 80 ? 'A' : ($overall >= 65 ? 'B' : ($overall >= 50 ? 'C' : ($overall >= 40 ? 'D' : 'E')));
        $newStatus = $this->input->post('submit_final') ? 'submitted' : 'draft';

        $this->db->where('id',$appraisalId)->update('appraisals',[
            'overall_score'       => $overall,
            'grade'               => $grade,
            'status'              => $newStatus,
            'appraiser_comments'  => $this->input->post('appraiser_comments'),
            'submitted_at'        => $newStatus === 'submitted' ? date('Y-m-d H:i:s') : $appraisal->submitted_at,
        ]);

        $url = $newStatus === 'submitted' ? base_url('appraisal') : base_url('appraisal/fill/'.$appraisalId);
        echo json_encode(['status'=>'success','url'=>$url]);
    }

    public function delete_appraisal($id) {
        $branchId = $this->_branchId();
        $this->db->where('appraisal_id',$id)->delete('appraisal_scores');
        $this->db->where('id',$id)->where('branch_id',$branchId)->delete('appraisals');
        echo json_encode(['status'=>'success','url'=>base_url('appraisal')]);
    }
}
