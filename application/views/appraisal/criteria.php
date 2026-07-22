<div class="content-header">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <a href="<?php echo base_url('appraisal/templates'); ?>" class="text-muted small"><i class="fas fa-arrow-left me-1"></i>Templates</a>
            <h4 class="mb-0 mt-1"><i class="fas fa-tasks me-2 text-primary"></i>Criteria: <?php echo html_escape($template->name); ?></h4>
        </div>
        <button class="btn btn-sm btn-primary" onclick="mfp_modal('#modal-criterion')"><i class="fas fa-plus me-1"></i>Add Criterion</button>
    </div>
</div>

<div class="container-fluid">
    <?php
    $grouped = [];
    foreach ($criteria as $c) { $grouped[$c->category ?: 'General'][] = $c; }
    ?>
    <?php if (empty($criteria)): ?>
    <div class="alert alert-info">No criteria yet. Add scoring criteria for this template.</div>
    <?php else: foreach ($grouped as $cat => $items): ?>
    <div class="card mb-3">
        <div class="card-header py-2">
            <strong><?php echo html_escape($cat); ?></strong>
            <span class="text-muted ms-2 small">(<?php echo count($items); ?> criteria)</span>
        </div>
        <div class="card-body p-0">
            <table class="table table-sm mb-0">
                <thead class="table-light">
                    <tr><th>#</th><th>Criterion</th><th>Max Score</th><th>Weight</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $i => $c): ?>
                    <tr>
                        <td><?php echo $i+1; ?></td>
                        <td><?php echo html_escape($c->criterion); ?></td>
                        <td><?php echo $c->max_score; ?></td>
                        <td><?php echo $c->weight; ?></td>
                        <td><?php echo btn_delete('appraisal/delete_criterion/'.$c->id); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endforeach; endif; ?>

    <div class="mt-3">
        <a href="<?php echo base_url('appraisal'); ?>" class="btn btn-outline-primary">
            <i class="fas fa-check me-1"></i>Done – Go to Appraisals
        </a>
    </div>
</div>

<div id="modal-criterion" class="mfp-hide">
    <div class="card mb-0" style="min-width:480px; max-width:560px;">
        <div class="card-header d-flex align-items-center justify-content-between"><h5 class="mb-0"><i class="fas fa-plus me-2"></i>Add Criterion</h5><button type="button" class="btn-close ms-2" onclick="$.magnificPopup.close()" title="Close"></button></div>
        <div class="card-body">
            <?php echo form_open('appraisal/save_criterion', ['class'=>'frm-submit']); ?>
            <input type="hidden" name="template_id" value="<?php echo $template->id; ?>">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Criterion <span class="text-danger">*</span></label>
                    <input type="text" name="criterion" class="form-control" required placeholder="e.g. Lesson preparation & delivery">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Category</label>
                    <input type="text" name="category" class="form-control" placeholder="e.g. Teaching Skills, Conduct">
                </div>
                <div class="col-sm-3">
                    <label class="form-label">Max Score <span class="text-danger">*</span></label>
                    <input type="number" name="max_score" class="form-control" value="10" min="1" required>
                </div>
                <div class="col-sm-3">
                    <label class="form-label">Weight</label>
                    <input type="number" name="weight" class="form-control" value="1" step="0.1" min="0.1">
                </div>
            </div>
            <div class="mt-3 text-end"><button class="btn btn-primary" type="submit"><i class="fas fa-save me-1"></i>Add</button></div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
