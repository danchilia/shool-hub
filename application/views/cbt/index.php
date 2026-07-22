<style>
.quiz-card { border:1px solid #e2e8f0; border-radius:10px; padding:18px; background:#fff; transition:box-shadow .2s; }
.quiz-card:hover { box-shadow:0 4px 12px rgba(0,0,0,.1); }
.quiz-status-draft     { background:#fef3c7; color:#92400e; }
.quiz-status-published { background:#d1fae5; color:#065f46; }
.quiz-status-closed    { background:#f3f4f6; color:#6b7280; }
@media(prefers-color-scheme:dark){
  .quiz-card { background:#2b2b3a; border-color:#3a3a50; }
}
</style>

<div class="content-header">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h4 class="mb-0"><i class="fas fa-laptop-code me-2 text-primary"></i>CBT Examinations</h4>
        <button class="btn btn-sm btn-primary" onclick="mfp_modal('#modal-quiz')">
            <i class="fas fa-plus me-1"></i>New Quiz / Exam
        </button>
    </div>
</div>

<div class="container-fluid">
    <?php if (empty($quizzes)): ?>
    <div class="alert alert-info">No exams created yet. Click "New Quiz / Exam" to begin.</div>
    <?php else: ?>
    <div class="row g-3">
        <?php foreach ($quizzes as $q):
            $qCount = $this->db->where('quiz_id',$q->id)->count_all_results('cbt_questions');
            $aCount = $this->db->where('quiz_id',$q->id)->where('status','submitted')->count_all_results('cbt_attempts');
        ?>
        <div class="col-md-4">
            <div class="quiz-card h-100 d-flex flex-column">
                <div class="d-flex align-items-start justify-content-between mb-2">
                    <h6 class="mb-0"><?php echo html_escape($q->title); ?></h6>
                    <span class="badge quiz-status-<?php echo $q->status; ?>"><?php echo ucfirst($q->status); ?></span>
                </div>
                <div class="text-muted small mb-3">
                    <div><i class="fas fa-clock me-1"></i><?php echo $q->duration_mins; ?> mins &nbsp;|&nbsp;
                         <i class="fas fa-star me-1"></i><?php echo $q->total_marks; ?> marks</div>
                    <div class="mt-1"><i class="fas fa-question-circle me-1"></i><?php echo $qCount; ?> questions &nbsp;|&nbsp;
                         <i class="fas fa-users me-1"></i><?php echo $aCount; ?> submitted</div>
                    <?php if ($q->start_datetime): ?>
                    <div class="mt-1"><i class="fas fa-calendar me-1"></i><?php echo date('d M Y H:i', strtotime($q->start_datetime)); ?></div>
                    <?php endif; ?>
                </div>
                <div class="mt-auto d-flex gap-2 flex-wrap">
                    <a href="<?php echo base_url('cbt/questions/'.$q->id); ?>" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-list me-1"></i>Questions
                    </a>
                    <a href="<?php echo base_url('cbt/results/'.$q->id); ?>" class="btn btn-sm btn-outline-success">
                        <i class="fas fa-chart-bar me-1"></i>Results
                    </a>
                    <?php echo btn_delete('cbt/delete_quiz/'.$q->id); ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<!-- New Quiz Modal -->
<div id="modal-quiz" class="mfp-hide">
    <div class="card mb-0" style="min-width:540px; max-width:620px;">
        <div class="card-header d-flex align-items-center justify-content-between"><h5 class="mb-0"><i class="fas fa-laptop-code me-2"></i>New Exam / Quiz</h5><button type="button" class="btn-close ms-2" onclick="$.magnificPopup.close()" title="Close"></button></div>
        <div class="card-body">
            <?php echo form_open('cbt/save_quiz', ['class'=>'frm-submit']); ?>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Subject</label>
                    <select name="subject_id" class="form-select select2">
                        <option value="">-- Select --</option>
                        <?php foreach ($subjects as $s): ?>
                        <option value="<?php echo $s->id; ?>"><?php echo html_escape($s->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Class</label>
                    <select name="class_id" class="form-select select2">
                        <option value="">-- All Classes --</option>
                        <?php foreach ($classes as $c): ?>
                        <option value="<?php echo $c->id; ?>"><?php echo html_escape($c->class); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-sm-4">
                    <label class="form-label">Duration (mins) <span class="text-danger">*</span></label>
                    <input type="number" name="duration_mins" class="form-control" value="60" required min="1">
                </div>
                <div class="col-sm-4">
                    <label class="form-label">Total Marks <span class="text-danger">*</span></label>
                    <input type="number" name="total_marks" class="form-control" value="100" required min="1">
                </div>
                <div class="col-sm-4">
                    <label class="form-label">Pass Marks</label>
                    <input type="number" name="pass_marks" class="form-control" value="40" min="0">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Start Date &amp; Time</label>
                    <input type="datetime-local" name="start_datetime" class="form-control">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">End Date &amp; Time</label>
                    <input type="datetime-local" name="end_datetime" class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label">Instructions</label>
                    <textarea name="instructions" class="form-control" rows="2"></textarea>
                </div>
                <div class="col-sm-4">
                    <div class="form-check">
                        <input type="checkbox" name="shuffle_questions" value="1" class="form-check-input" id="sq">
                        <label class="form-check-label" for="sq">Shuffle Questions</label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-check">
                        <input type="checkbox" name="shuffle_options" value="1" class="form-check-input" id="so">
                        <label class="form-check-label" for="so">Shuffle Options</label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-check">
                        <input type="checkbox" name="show_result" value="1" class="form-check-input" id="sr" checked>
                        <label class="form-check-label" for="sr">Show Result</label>
                    </div>
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>
            </div>
            <div class="mt-3 text-end">
                <button type="submit" class="btn btn-primary">Create &amp; Add Questions <i class="fas fa-arrow-right ms-1"></i></button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
