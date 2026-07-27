<?php
$pct    = $quiz->total_marks > 0 ? round(($attempt->total_score / $quiz->total_marks) * 100, 1) : 0;
$passed = $attempt->total_score >= $quiz->pass_marks;
$color  = $pct >= 70 ? '#10b981' : ($pct >= 40 ? '#f59e0b' : '#ef4444');
?>
<style>
.result-hero { text-align:center; padding:40px 20px; }
.result-circle { width:140px; height:140px; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-direction:column; border:6px solid <?php echo $color; ?>; margin:0 auto 20px; }
.result-pct { font-size:2.2rem; font-weight:800; color:<?php echo $color; ?>; }
.result-label { font-size:.85rem; color:#888; }
.ans-row { padding:12px 0; border-bottom:1px solid #f1f5f9; }
.ans-row:last-child { border-bottom:none; }
.ans-correct { color:#10b981; }
.ans-wrong   { color:#ef4444; }
@media(prefers-color-scheme:dark) {
    .ans-row { border-bottom-color:#3a3a50; }
}
:root[data-theme="dark"]  .ans-row { border-bottom-color:#3a3a50; }
:root[data-theme="light"] .ans-row { border-bottom-color:#f1f5f9; }
</style>

<div class="container" style="max-width:700px; padding-top:30px;">
    <div class="card mb-4">
        <div class="card-body result-hero">
            <h5 class="mb-1"><?php echo html_escape($quiz->title); ?></h5>
            <div class="text-muted small mb-4">Submitted <?php echo $attempt->submitted_at ? date('d M Y H:i', strtotime($attempt->submitted_at)) : 'N/A'; ?></div>
            <div class="result-circle">
                <div class="result-pct"><?php echo $pct; ?>%</div>
                <div class="result-label"><?php echo $attempt->total_score; ?>/<?php echo $quiz->total_marks; ?></div>
            </div>
            <h3 style="color:<?php echo $color; ?>;"><?php echo $passed ? 'PASSED' : 'FAILED'; ?></h3>
            <div class="text-muted">Pass mark: <?php echo $quiz->pass_marks; ?> / <?php echo $quiz->total_marks; ?></div>
        </div>
    </div>

    <?php if ($quiz->show_result && !empty($answers)): ?>
    <div class="card">
        <div class="card-header"><h6 class="mb-0">Answer Review</h6></div>
        <div class="card-body">
            <?php foreach ($answers as $i => $a): ?>
            <div class="ans-row">
                <div class="d-flex align-items-start gap-2">
                    <?php if ($a->is_correct): ?>
                    <i class="fas fa-check-circle ans-correct mt-1"></i>
                    <?php else: ?>
                    <i class="fas fa-times-circle ans-wrong mt-1"></i>
                    <?php endif; ?>
                    <div>
                        <div class="fw-semibold mb-1">Q<?php echo $i+1; ?>. <?php echo html_escape($a->question_text); ?></div>
                        <div class="small">
                            Your answer: <strong class="<?php echo $a->is_correct?'ans-correct':'ans-wrong'; ?>"><?php echo html_escape($a->student_answer) ?: '<em>Not answered</em>'; ?></strong>
                            <?php if (!$a->is_correct): ?>
                            &nbsp;|&nbsp; Correct: <strong class="ans-correct"><?php echo html_escape($a->correct_answer); ?></strong>
                            <?php endif; ?>
                        </div>
                        <div class="small text-muted">Marks: <?php echo $a->marks_awarded; ?> / <?php echo $a->marks; ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="text-center mt-4 mb-5">
        <a href="<?php echo base_url('dashboard'); ?>" class="btn btn-primary">
            <i class="fas fa-home me-1"></i> Back to Dashboard
        </a>
    </div>
</div>
