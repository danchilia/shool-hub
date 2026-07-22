<style>
body { background:#f1f5f9; }
.exam-wrapper { max-width:800px; margin:0 auto; padding:20px; }
.exam-header { background:#1a5276; color:#fff; border-radius:10px; padding:20px 24px; margin-bottom:20px; }
.exam-timer { font-size:2rem; font-weight:700; font-variant-numeric:tabular-nums; }
.exam-timer.warn { color:#fbbf24; }
.exam-timer.danger { color:#f87171; animation:pulse 1s infinite; }
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.5} }
.q-block { background:#fff; border-radius:10px; padding:20px; margin-bottom:16px; box-shadow:0 1px 4px rgba(0,0,0,.07); }
.q-block .q-num { font-size:.8rem; color:#6366f1; font-weight:700; text-transform:uppercase; letter-spacing:.05em; }
.q-block .q-text { font-size:1.05rem; margin:10px 0 16px; }
.opt-label { display:flex; align-items:center; gap:12px; padding:10px 14px; border:2px solid #e2e8f0; border-radius:8px; cursor:pointer; margin-bottom:8px; transition:.15s; }
.opt-label:hover { border-color:#6366f1; background:#eef2ff; }
.opt-label input[type=radio]:checked + .opt-mark { background:#6366f1; color:#fff; }
.opt-label input[type=radio]:checked ~ .opt-text { color:#1e1b4b; font-weight:600; }
.opt-label input[type=radio] { display:none; }
.opt-mark { width:28px; height:28px; border-radius:50%; border:2px solid #c7d2fe; display:flex; align-items:center; justify-content:center; font-size:.78rem; font-weight:700; color:#6366f1; flex-shrink:0; }
</style>

<div class="exam-wrapper">
    <div class="exam-header">
        <div class="d-flex align-items-start justify-content-between">
            <div>
                <h4 class="mb-1"><?php echo html_escape($quiz->title); ?></h4>
                <div class="opacity-75 small"><?php echo count($questions); ?> questions &middot; <?php echo $quiz->total_marks; ?> marks</div>
                <?php if ($quiz->instructions): ?>
                <div class="mt-2 small opacity-90"><?php echo nl2br(html_escape($quiz->instructions)); ?></div>
                <?php endif; ?>
            </div>
            <div class="text-end">
                <div class="small opacity-75">Time Remaining</div>
                <div class="exam-timer" id="exam-timer">--:--</div>
            </div>
        </div>
    </div>

    <?php echo form_open('cbt/submit', ['id'=>'exam-form']); ?>
    <input type="hidden" name="attempt_id" value="<?php echo $attempt_id; ?>">

    <?php foreach ($questions as $i => $q): ?>
    <div class="q-block">
        <div class="q-num">Question <?php echo $i+1; ?> of <?php echo count($questions); ?></div>
        <div class="q-text"><?php echo nl2br(html_escape($q->question_text)); ?></div>

        <?php if ($q->question_type === 'mcq'): ?>
        <?php foreach (['a','b','c','d'] as $opt):
            $val = $q->{'option_'.$opt};
            if (!$val) continue;
        ?>
        <label class="opt-label">
            <input type="radio" name="answers[<?php echo $q->id; ?>]" value="<?php echo $opt; ?>">
            <div class="opt-mark"><?php echo strtoupper($opt); ?></div>
            <span class="opt-text"><?php echo html_escape($val); ?></span>
        </label>
        <?php endforeach; ?>

        <?php elseif ($q->question_type === 'true_false'): ?>
        <label class="opt-label">
            <input type="radio" name="answers[<?php echo $q->id; ?>]" value="true">
            <div class="opt-mark">T</div>
            <span class="opt-text">True</span>
        </label>
        <label class="opt-label">
            <input type="radio" name="answers[<?php echo $q->id; ?>]" value="false">
            <div class="opt-mark">F</div>
            <span class="opt-text">False</span>
        </label>

        <?php else: ?>
        <input type="text" name="answers[<?php echo $q->id; ?>]" class="form-control" placeholder="Your answer...">
        <?php endif; ?>
    </div>
    <?php endforeach; ?>

    <div class="text-center mt-4 mb-5">
        <button type="button" class="btn btn-success btn-lg px-5" id="submit-btn"
                onclick="submitExam()">
            <i class="fas fa-paper-plane me-2"></i>Submit Exam
        </button>
    </div>
    <?php echo form_close(); ?>
</div>

<script>
var durationSecs = <?php echo $quiz->duration_mins * 60; ?>;
var timerEl = document.getElementById('exam-timer');
var remaining = durationSecs;

function updateTimer(){
    var m = Math.floor(remaining/60);
    var s = remaining % 60;
    timerEl.textContent = (m<10?'0':'')+m+':'+(s<10?'0':'')+s;
    if (remaining <= 60) timerEl.className = 'exam-timer danger';
    else if (remaining <= 300) timerEl.className = 'exam-timer warn';
    if (remaining <= 0) { autoSubmit(); return; }
    remaining--;
}
updateTimer();
var tick = setInterval(updateTimer, 1000);

function buildFormData(){
    var answers = {};
    document.querySelectorAll('#exam-form input[name^="answers"]').forEach(function(el){
        if (el.type === 'radio' && !el.checked) return;
        var m = el.name.match(/answers\[(\d+)\]/);
        if (m) answers[m[1]] = el.value;
    });
    return answers;
}

function submitExam(){
    clearInterval(tick);
    document.getElementById('submit-btn').disabled = true;
    $.ajax({
        url: base_url+'cbt/submit',
        type: 'POST',
        data: $.extend({attempt_id: <?php echo $attempt_id; ?>, answers: buildFormData()}, csrfData),
        success: function(r){
            var res = typeof r === 'string' ? JSON.parse(r) : r;
            if (res.status === 'success') window.location.href = res.url;
            else alert(res.msg || 'Error submitting.');
        }
    });
}

function autoSubmit(){ submitExam(); }
</script>
