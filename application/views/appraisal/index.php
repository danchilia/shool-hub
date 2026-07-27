<style>
.grade-A { background:#d1fae5; color:#065f46; }
.grade-B { background:#dbeafe; color:#1e40af; }
.grade-C { background:#fef3c7; color:#92400e; }
.grade-D { background:#fed7aa; color:#9a3412; }
.grade-E { background:#fee2e2; color:#991b1b; }
.status-draft     { background:#f3f4f6; color:#374151; }
.status-submitted { background:#dbeafe; color:#1e40af; }
.status-reviewed  { background:#fef3c7; color:#92400e; }
.status-approved  { background:#d1fae5; color:#065f46; }
</style>

<div class="content-header">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h4 class="mb-0"><i class="fas fa-user-check me-2 text-primary"></i>Staff Performance Appraisals</h4>
        <div class="d-flex gap-2">
            <a href="<?php echo base_url('appraisal/templates'); ?>" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-clipboard-list me-1"></i>Templates
            </a>
            <?php if (is_admin_loggedin() || is_superadmin_loggedin()): ?>
            <button class="btn btn-sm btn-primary" onclick="mfp_modal('#modal-start')">
                <i class="fas fa-plus me-1"></i>Start Appraisal
            </button>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="container-fluid">
    <!-- Year filter -->
    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" class="d-flex align-items-center gap-3">
                <label class="mb-0 fw-semibold">Year:</label>
                <select name="year" class="form-select form-select-sm" style="width:120px" onchange="this.form.submit()">
                    <?php for ($y = date('Y'); $y >= date('Y')-4; $y--): ?>
                    <option value="<?php echo $y; ?>" <?php echo $year==$y?'selected':''; ?>><?php echo $y; ?></option>
                    <?php endfor; ?>
                </select>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="appraisal-table">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Staff Name</th>
                            <th>Designation</th>
                            <th>Template</th>
                            <th>Period</th>
                            <th>Overall Score</th>
                            <th>Grade</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($appraisals)): ?>
                        <tr><td colspan="9" class="text-center py-4 text-muted">No appraisals for <?php echo $year; ?> yet.</td></tr>
                        <?php else: foreach ($appraisals as $i => $a): ?>
                        <tr>
                            <td><?php echo $i+1; ?></td>
                            <td><strong><?php echo html_escape($a->staff_name); ?></strong></td>
                            <td class="text-muted small"><?php echo html_escape($a->designation); ?></td>
                            <td><?php echo html_escape($a->template_name); ?></td>
                            <td class="small"><?php echo html_escape($a->appraisal_period ?: $a->appraisal_year); ?></td>
                            <td>
                                <?php if ($a->overall_score !== null): ?>
                                <div class="d-flex align-items-center gap-2">
                                    <div style="flex:1;height:6px;background:#e2e8f0;border-radius:3px;overflow:hidden;">
                                        <div style="width:<?php echo min(100,$a->overall_score); ?>%;height:100%;background:<?php echo $a->overall_score>=65?'#10b981':($a->overall_score>=40?'#f59e0b':'#ef4444'); ?>;"></div>
                                    </div>
                                    <span class="small"><?php echo round($a->overall_score, 1); ?>%</span>
                                </div>
                                <?php else: echo '—'; endif; ?>
                            </td>
                            <td>
                                <?php if ($a->grade): ?>
                                <span class="badge grade-<?php echo $a->grade; ?>"><?php echo $a->grade; ?></span>
                                <?php else: echo '—'; endif; ?>
                            </td>
                            <td>
                                <span class="badge status-<?php echo $a->status; ?>"><?php echo ucfirst($a->status); ?></span>
                            </td>
                            <td>
                                <a href="<?php echo base_url('appraisal/fill/'.$a->id); ?>" class="btn btn-xs btn-outline-primary" title="Fill/View">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php echo btn_delete('appraisal/delete_appraisal/'.$a->id); ?>
                            </td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Start Appraisal Modal -->
<div id="modal-start" class="mfp-hide">
    <div class="card mb-0" style="min-width:480px; max-width:560px;">
        <div class="card-header d-flex align-items-center justify-content-between"><h5 class="mb-0"><i class="fas fa-plus me-2"></i>Start New Appraisal</h5><button type="button" class="btn-close ms-2" onclick="$.magnificPopup.close()" title="Close"></button></div>
        <div class="card-body">
            <?php echo form_open('appraisal/start_appraisal', ['class'=>'frm-submit']); ?>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Staff Member <span class="text-danger">*</span></label>
                    <select name="staff_id" class="form-select" data-plugin-selectTwo data-width="100%" required>
                        <option value="">-- Select Staff --</option>
                        <?php foreach ($staff as $s): ?>
                        <option value="<?php echo $s->id; ?>"><?php echo html_escape($s->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Template <span class="text-danger">*</span></label>
                    <select name="template_id" class="form-select" data-plugin-selectTwo data-width="100%" required>
                        <option value="">-- Select Template --</option>
                        <?php foreach ($templates as $t): ?>
                        <option value="<?php echo $t->id; ?>"><?php echo html_escape($t->name); ?> (<?php echo ucfirst(str_replace('_',' ',$t->appraisal_type)); ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Year <span class="text-danger">*</span></label>
                    <input type="text" name="appraisal_year" class="form-control" value="<?php echo date('Y'); ?>" required>
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Period</label>
                    <input type="text" name="appraisal_period" class="form-control" placeholder="e.g. Term 1 2026">
                </div>
            </div>
            <div class="mt-3 text-end">
                <button class="btn btn-primary" type="submit"><i class="fas fa-arrow-right me-1"></i>Start & Fill Form</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>$(function(){ $('#appraisal-table').DataTable({order:[[0,'desc']],pageLength:25,columnDefs:[{orderable:false,targets:[8]}]}); });</script>
