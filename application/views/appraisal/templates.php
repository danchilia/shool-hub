<div class="content-header">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h4 class="mb-0"><i class="fas fa-clipboard-list me-2 text-primary"></i>Appraisal Templates</h4>
        <div class="d-flex gap-2">
            <a href="<?php echo base_url('appraisal'); ?>" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Appraisals</a>
            <button class="btn btn-sm btn-primary" onclick="mfp_modal('#modal-tpl')"><i class="fas fa-plus me-1"></i>New Template</button>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row g-3">
        <?php if (empty($templates)): ?>
        <div class="col-12"><div class="alert alert-info">No templates yet. Create one to begin appraisals.</div></div>
        <?php else: foreach ($templates as $t):
            $criteriaCount = $this->db->where('template_id',$t->id)->count_all_results('appraisal_criteria');
        ?>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between mb-2">
                        <h6 class="mb-0"><?php echo html_escape($t->name); ?></h6>
                        <span class="badge" style="background:#e0e7ff;color:#3730a3;"><?php echo ucfirst(str_replace('_',' ',$t->appraisal_type)); ?></span>
                    </div>
                    <?php if ($t->description): ?>
                    <p class="text-muted small mb-3"><?php echo html_escape($t->description); ?></p>
                    <?php endif; ?>
                    <div class="text-muted small mb-3"><i class="fas fa-list me-1"></i><?php echo $criteriaCount; ?> criteria</div>
                    <div class="d-flex gap-2">
                        <a href="<?php echo base_url('appraisal/criteria/'.$t->id); ?>" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-tasks me-1"></i>Criteria
                        </a>
                        <?php echo btn_delete('appraisal/delete_template/'.$t->id); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; endif; ?>
    </div>
</div>

<div id="modal-tpl" class="mfp-hide">
    <div class="card mb-0" style="min-width:460px; max-width:540px;">
        <div class="card-header d-flex align-items-center justify-content-between"><h5 class="mb-0"><i class="fas fa-plus me-2"></i>New Template</h5><button type="button" class="btn-close ms-2" onclick="$.magnificPopup.close()" title="Close"></button></div>
        <div class="card-body">
            <?php echo form_open('appraisal/save_template', ['class'=>'frm-submit']); ?>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Template Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" required placeholder="e.g. Annual Teacher Appraisal">
                </div>
                <div class="col-12">
                    <label class="form-label">Type</label>
                    <select name="appraisal_type" class="form-select">
                        <option value="annual">Annual</option>
                        <option value="mid_year">Mid-Year</option>
                        <option value="probation">Probation</option>
                        <option value="tsc">TSC (Teachers Service Commission)</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="mt-3 text-end"><button class="btn btn-primary" type="submit"><i class="fas fa-arrow-right me-1"></i>Create & Add Criteria</button></div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
