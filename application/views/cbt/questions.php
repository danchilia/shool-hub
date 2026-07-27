<style>
.q-card { border:1px solid #e2e8f0; border-radius:8px; padding:14px 16px; background:#fff; margin-bottom:10px; }
.q-type-badge { font-size:.7rem; background:#e0e7ff; color:#3730a3; border-radius:4px; padding:2px 7px; }
.option-row { display:flex; align-items:center; gap:10px; margin-top:6px; font-size:.88rem; }
.option-mark { width:22px; height:22px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:.75rem; font-weight:700; }
.opt-correct { background:#d1fae5; color:#065f46; }
.opt-wrong   { background:#f3f4f6; color:#6b7280; }
@media(prefers-color-scheme:dark)  { .q-card { background:#2b2b3a; border-color:#3a3a50; } }
:root[data-theme="dark"]  .q-card  { background:#2b2b3a; border-color:#3a3a50; }
:root[data-theme="light"] .q-card  { background:#fff;     border-color:#e2e8f0; }
</style>

<div class="content-header">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <a href="<?php echo base_url('cbt'); ?>" class="text-muted small"><i class="fas fa-arrow-left me-1"></i>All Exams</a>
            <h4 class="mb-0 mt-1"><i class="fas fa-question-circle me-2 text-primary"></i><?php echo html_escape($quiz->title); ?></h4>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo base_url('cbt/results/'.$quiz->id); ?>" class="btn btn-sm btn-outline-success">
                <i class="fas fa-chart-bar me-1"></i>Results
            </a>
            <button class="btn btn-sm btn-primary" onclick="resetQForm(); mfp_modal('#modal-question')">
                <i class="fas fa-plus me-1"></i>Add Question
            </button>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="alert alert-info d-flex align-items-center gap-3 py-2">
        <div><i class="fas fa-clock me-1"></i><strong><?php echo $quiz->duration_mins; ?> mins</strong></div>
        <div><i class="fas fa-star me-1"></i><strong><?php echo $quiz->total_marks; ?> total marks</strong></div>
        <div><i class="fas fa-question-circle me-1"></i><strong><?php echo count($questions); ?> questions</strong></div>
        <span class="badge quiz-status-<?php echo $quiz->status; ?> ms-auto"><?php echo ucfirst($quiz->status); ?></span>
    </div>

    <?php if (empty($questions)): ?>
    <div class="text-center py-5 text-muted"><i class="fas fa-question-circle fa-3x mb-3"></i><br>No questions yet. Add the first one.</div>
    <?php else: ?>
    <?php foreach ($questions as $i => $q): ?>
    <div class="q-card">
        <div class="d-flex align-items-start justify-content-between gap-2">
            <div class="flex-grow-1">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="fw-semibold">Q<?php echo $i+1; ?>.</span>
                    <span class="q-type-badge"><?php echo strtoupper(str_replace('_',' ',$q->question_type)); ?></span>
                    <span class="ms-auto text-muted small"><?php echo $q->marks; ?> mk<?php echo $q->marks!=1?'s':''; ?></span>
                </div>
                <p class="mb-2"><?php echo nl2br(html_escape($q->question_text)); ?></p>
                <?php if ($q->question_type === 'mcq'): ?>
                <div class="row g-1">
                    <?php foreach (['a','b','c','d'] as $opt):
                        $val = $q->{'option_'.$opt};
                        if (!$val) continue;
                        $isCorrect = strtolower($q->correct_answer) === $opt;
                    ?>
                    <div class="col-sm-6">
                        <div class="option-row">
                            <div class="option-mark <?php echo $isCorrect?'opt-correct':'opt-wrong'; ?>">
                                <?php echo strtoupper($opt); ?>
                            </div>
                            <span><?php echo html_escape($val); ?></span>
                            <?php if ($isCorrect): ?><i class="fas fa-check text-success ms-1"></i><?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php elseif ($q->question_type === 'true_false'): ?>
                <div class="option-row">
                    <div class="option-mark <?php echo strtolower($q->correct_answer)==='true'?'opt-correct':'opt-wrong'; ?>">T</div>
                    <span>True</span>
                    &nbsp;&nbsp;
                    <div class="option-mark <?php echo strtolower($q->correct_answer)==='false'?'opt-correct':'opt-wrong'; ?>">F</div>
                    <span>False</span>
                </div>
                <?php else: ?>
                <div class="text-muted small">Answer: <strong><?php echo html_escape($q->correct_answer); ?></strong></div>
                <?php endif; ?>
            </div>
            <div class="flex-shrink-0 d-flex gap-1">
                <button class="btn btn-xs btn-outline-warning" onclick="editQ(this)" title="Edit"
                    data-json="<?php echo htmlspecialchars(json_encode([
                        'id'            => (int)$q->id,
                        'question_type' => $q->question_type,
                        'marks'         => (int)$q->marks,
                        'question_text' => $q->question_text,
                        'option_a'      => $q->option_a,
                        'option_b'      => $q->option_b,
                        'option_c'      => $q->option_c,
                        'option_d'      => $q->option_d,
                        'correct_answer'=> $q->correct_answer,
                    ]), ENT_QUOTES); ?>">
                    <i class="fas fa-edit"></i>
                </button>
                <?php echo btn_delete('cbt/delete_question/'.$q->id); ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Add Question Modal -->
<div id="modal-question" class="mfp-hide">
    <div class="card mb-0" style="min-width:540px; max-width:620px;">
        <div class="card-header d-flex align-items-center justify-content-between"><h5 class="mb-0" id="q-modal-title"><i class="fas fa-plus-circle me-2"></i>Add Question</h5><button type="button" class="btn-close ms-2" onclick="$.magnificPopup.close()" title="Close"></button></div>
        <div class="card-body">
            <?php echo form_open('cbt/save_question', ['class'=>'frm-submit','id'=>'q-form']); ?>
            <input type="hidden" name="quiz_id" value="<?php echo $quiz->id; ?>">
            <input type="hidden" name="id" id="q-edit-id" value="">
            <div class="row g-3">
                <div class="col-sm-6">
                    <label class="form-label">Type</label>
                    <select name="question_type" id="q-type" class="form-select" onchange="toggleOptions()">
                        <option value="mcq">Multiple Choice (MCQ)</option>
                        <option value="true_false">True / False</option>
                        <option value="short_answer">Short Answer</option>
                    </select>
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Marks <span class="text-danger">*</span></label>
                    <input type="number" name="marks" class="form-control" value="1" required min="1">
                </div>
                <div class="col-12">
                    <label class="form-label">Question <span class="text-danger">*</span></label>
                    <textarea name="question_text" class="form-control" rows="3" required></textarea>
                </div>
                <div id="mcq-options">
                    <div class="col-12 mb-2"><small class="text-muted fw-semibold">Options (fill all four)</small></div>
                    <div class="row g-2">
                        <div class="col-sm-6"><div class="input-group"><span class="input-group-text">A</span><input type="text" name="option_a" class="form-control" placeholder="Option A"></div></div>
                        <div class="col-sm-6"><div class="input-group"><span class="input-group-text">B</span><input type="text" name="option_b" class="form-control" placeholder="Option B"></div></div>
                        <div class="col-sm-6"><div class="input-group"><span class="input-group-text">C</span><input type="text" name="option_c" class="form-control" placeholder="Option C"></div></div>
                        <div class="col-sm-6"><div class="input-group"><span class="input-group-text">D</span><input type="text" name="option_d" class="form-control" placeholder="Option D"></div></div>
                    </div>
                    <div class="mt-2">
                        <label class="form-label">Correct Answer <span class="text-danger">*</span></label>
                        <select name="correct_answer" id="correct-mcq" class="form-select">
                            <option value="a">A</option>
                            <option value="b">B</option>
                            <option value="c">C</option>
                            <option value="d">D</option>
                        </select>
                    </div>
                </div>
                <div id="tf-options" style="display:none;" class="col-12">
                    <label class="form-label">Correct Answer</label>
                    <select name="correct_answer_tf" class="form-select">
                        <option value="true">True</option>
                        <option value="false">False</option>
                    </select>
                </div>
                <div id="sa-answer" style="display:none;" class="col-12">
                    <label class="form-label">Correct Answer (keywords) <span class="text-danger">*</span></label>
                    <input type="text" name="correct_answer_sa" class="form-control" placeholder="Expected answer">
                </div>
            </div>
            <div class="mt-3 text-end">
                <button type="submit" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Add Question</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
function resetQForm() {
    var f = document.getElementById('q-form');
    f.reset();
    document.getElementById('q-edit-id').value = '';
    document.getElementById('q-modal-title').innerHTML = '<i class="fas fa-plus-circle me-2"></i>Add Question';
    f.querySelector('button[type="submit"]').innerHTML = '<i class="fas fa-plus me-1"></i>Add Question';
    toggleOptions();
}

function editQ(btn) {
    var d = JSON.parse(btn.getAttribute('data-json'));
    var f = document.getElementById('q-form');
    f.reset();
    document.getElementById('q-edit-id').value              = d.id;
    document.getElementById('q-type').value                 = d.question_type || 'mcq';
    f.querySelector('[name="marks"]').value                 = d.marks || 1;
    f.querySelector('[name="question_text"]').value         = d.question_text || '';
    ['a','b','c','d'].forEach(function(o) {
        var el = f.querySelector('[name="option_'+o+'"]');
        if (el) el.value = d['option_'+o] || '';
    });
    toggleOptions(); // fix panel visibility + rename name attrs before setting values
    document.getElementById('correct-mcq').value            = d.correct_answer || 'a';
    var tfSel = document.querySelector('#tf-options select');
    if (tfSel) tfSel.value = d.correct_answer || 'true';
    var saIn  = document.querySelector('#sa-answer input[type="text"]');
    if (saIn)  saIn.value  = d.correct_answer || '';
    document.getElementById('q-modal-title').innerHTML = '<i class="fas fa-edit me-2"></i>Edit Question';
    f.querySelector('button[type="submit"]').innerHTML = '<i class="fas fa-save me-1"></i>Save Changes';
    mfp_modal('#modal-question');
}

function toggleOptions() {
    var t = document.getElementById('q-type').value;
    document.getElementById('mcq-options').style.display = t==='mcq' ? '' : 'none';
    document.getElementById('tf-options').style.display  = t==='true_false' ? '' : 'none';
    document.getElementById('sa-answer').style.display   = t==='short_answer' ? '' : 'none';
    // swap correct_answer field name
    document.getElementById('correct-mcq').name = t==='mcq' ? 'correct_answer' : '_unused_mcq';
    document.querySelector('[name="correct_answer_tf"]') && (document.querySelector('[name="correct_answer_tf"]').name = t==='true_false' ? 'correct_answer' : '_unused_tf');
    document.querySelector('[name="correct_answer_sa"]') && (document.querySelector('[name="correct_answer_sa"]').name = t==='short_answer' ? 'correct_answer' : '_unused_sa');
}
$(function(){ toggleOptions(); });
</script>
