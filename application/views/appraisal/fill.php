<style>
.score-slider { -webkit-appearance:none; width:100%; height:6px; border-radius:3px; outline:none; background:#e2e8f0; }
.score-slider::-webkit-slider-thumb { -webkit-appearance:none; width:18px; height:18px; border-radius:50%; background:#6366f1; cursor:pointer; }
.score-cell { font-size:1.3rem; font-weight:700; color:#6366f1; min-width:40px; text-align:center; }
.cat-header { background:#f8fafc; border-left:4px solid #6366f1; padding:10px 16px; margin:0; }
.grade-A { background:#d1fae5; color:#065f46; }
.grade-B { background:#dbeafe; color:#1e40af; }
.grade-C { background:#fef3c7; color:#92400e; }
.grade-D { background:#fed7aa; color:#9a3412; }
.grade-E { background:#fee2e2; color:#991b1b; }
.status-draft     { background:#f3f4f6; color:#374151; }
.status-submitted { background:#dbeafe; color:#1e40af; }
.status-reviewed  { background:#fef3c7; color:#92400e; }
.status-approved  { background:#d1fae5; color:#065f46; }
@media(prefers-color-scheme:dark){ .cat-header { background:#1e2030; } }
:root[data-theme="dark"]  .cat-header { background:#1e2030; }
:root[data-theme="light"] .cat-header { background:#f8fafc; }
</style>

<div class="content-header">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <a href="<?php echo base_url('appraisal'); ?>" class="text-muted small"><i class="fas fa-arrow-left me-1"></i>Appraisals</a>
            <h4 class="mb-0 mt-1"><i class="fas fa-user-check me-2 text-primary"></i>Appraisal Form</h4>
        </div>
        <div>
            <span class="badge status-<?php echo $appraisal->status; ?>"><?php echo ucfirst($appraisal->status); ?></span>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="card mb-3">
        <div class="card-body py-2 d-flex gap-4 flex-wrap">
            <div><i class="fas fa-user me-1 text-primary"></i><strong><?php echo html_escape($appraisal->staff_name); ?></strong></div>
            <div class="text-muted"><?php echo html_escape($appraisal->designation); ?></div>
            <div><i class="fas fa-clipboard-list me-1"></i><?php echo html_escape($appraisal->template_name); ?></div>
            <div><i class="fas fa-calendar me-1"></i><?php echo html_escape($appraisal->appraisal_period ?: $appraisal->appraisal_year); ?></div>
            <?php if ($appraisal->overall_score !== null): ?>
            <div class="ms-auto"><strong>Score: <?php echo round($appraisal->overall_score,1); ?>% &nbsp; Grade: <?php echo $appraisal->grade; ?></strong></div>
            <?php endif; ?>
        </div>
    </div>

    <?php echo form_open('appraisal/save_scores', ['class'=>'frm-submit','id'=>'appraisal-form']); ?>
    <input type="hidden" name="appraisal_id" value="<?php echo $appraisal->id; ?>">

    <?php
    $grouped = [];
    foreach ($criteria as $c) { $grouped[$c->category ?: 'General'][] = $c; }
    ?>

    <?php foreach ($grouped as $cat => $items): ?>
    <div class="card mb-3">
        <div class="cat-header"><strong><i class="fas fa-folder me-2"></i><?php echo html_escape($cat); ?></strong></div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:40%">Criterion</th>
                        <th style="width:8%" class="text-center">Max</th>
                        <th style="width:30%">Score</th>
                        <th style="width:8%" class="text-center">Given</th>
                        <th>Comments</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $c):
                        $sc = isset($scores[$c->id]) ? $scores[$c->id] : null;
                        $val = $sc ? ($sc->appraiser_score ?? 0) : 0;
                    ?>
                    <tr>
                        <td><?php echo html_escape($c->criterion); ?></td>
                        <td class="text-center text-muted"><?php echo $c->max_score; ?></td>
                        <td>
                            <input type="range" class="score-slider"
                                   name="scores[<?php echo $c->id; ?>]"
                                   min="0" max="<?php echo $c->max_score; ?>"
                                   value="<?php echo $val; ?>"
                                   oninput="document.getElementById('sv-<?php echo $c->id; ?>').textContent=this.value">
                        </td>
                        <td class="score-cell" id="sv-<?php echo $c->id; ?>"><?php echo $val; ?></td>
                        <td>
                            <input type="text" name="comments[<?php echo $c->id; ?>]" class="form-control form-control-sm"
                                   value="<?php echo html_escape($sc ? $sc->comments : ''); ?>" placeholder="Remarks">
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endforeach; ?>

    <div class="card mb-4">
        <div class="card-body">
            <label class="form-label fw-semibold">Appraiser Comments / Recommendations</label>
            <textarea name="appraiser_comments" class="form-control" rows="3"><?php echo html_escape($appraisal->appraiser_comments); ?></textarea>
        </div>
    </div>

    <div class="d-flex gap-3 justify-content-end mb-5">
        <button type="submit" name="submit_final" value="0" class="btn btn-outline-secondary">
            <i class="fas fa-save me-1"></i>Save Draft
        </button>
        <button type="button" class="btn btn-success" onclick="submitFinal()">
            <i class="fas fa-paper-plane me-1"></i>Submit Final
        </button>
    </div>

    <?php echo form_close(); ?>
</div>

<script>
function submitFinal() {
    if (!confirm('Submit this appraisal as final? You cannot edit it after submitting.')) return;
    var form = document.getElementById('appraisal-form');
    var inp = document.createElement('input');
    inp.type='hidden'; inp.name='submit_final'; inp.value='1';
    form.appendChild(inp);
    $(form).submit();
}
</script>
