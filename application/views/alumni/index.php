<style>
.alumni-card { border:1px solid #e2e8f0; border-radius:10px; padding:16px; background:#fff; }
.alumni-avatar { width:52px; height:52px; border-radius:50%; object-fit:cover; background:#e0e7ff; display:flex; align-items:center; justify-content:center; font-size:1.3rem; color:#6366f1; flex-shrink:0; }
.alumni-avatar img { width:52px; height:52px; border-radius:50%; object-fit:cover; }
@media(prefers-color-scheme:dark){ .alumni-card{ background:#2b2b3a; border-color:#3a3a50; } }
</style>

<div class="content-header">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h4 class="mb-0"><i class="fas fa-graduation-cap me-2 text-primary"></i>Alumni Management</h4>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-info" onclick="mfp_modal('#modal-import')">
                <i class="fas fa-file-import me-1"></i>Import Students
            </button>
            <button class="btn btn-sm btn-primary" onclick="mfp_modal('#modal-alumni')">
                <i class="fas fa-plus me-1"></i>Add Alumni
            </button>
        </div>
    </div>
</div>

<div class="container-fluid">
    <!-- Stats + filter row -->
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div class="d-flex gap-3">
            <div class="text-center">
                <div style="font-size:1.8rem; font-weight:700;"><?php echo count($alumni); ?></div>
                <div class="text-muted small">Alumni<?php if ($filter_year): ?> (<?php echo $filter_year; ?>)<?php endif; ?></div>
            </div>
        </div>
        <form method="GET" class="d-flex align-items-center gap-2">
            <label class="mb-0 fw-semibold">Year:</label>
            <select name="year" class="form-select form-select-sm" style="width:120px" onchange="this.form.submit()">
                <option value="">All Years</option>
                <?php foreach ($years as $y): ?>
                <option value="<?php echo $y->graduation_year; ?>" <?php echo $filter_year==$y->graduation_year?'selected':''; ?>>
                    <?php echo $y->graduation_year; ?>
                </option>
                <?php endforeach; ?>
            </select>
            <?php if ($filter_year): ?><a href="<?php echo base_url('alumni'); ?>" class="btn btn-sm btn-link">Clear</a><?php endif; ?>
        </form>
    </div>

    <?php if (empty($alumni)): ?>
    <div class="alert alert-info">No alumni records yet. Add them manually or import from inactive students.</div>
    <?php else: ?>
    <div class="row g-3">
        <?php foreach ($alumni as $al): ?>
        <div class="col-md-4 col-lg-3">
            <div class="alumni-card h-100">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="alumni-avatar">
                        <?php if ($al->profile_photo): ?>
                        <img src="<?php echo base_url('uploads/'.$al->profile_photo); ?>" alt="">
                        <?php else: ?>
                        <i class="fas fa-user-graduate"></i>
                        <?php endif; ?>
                    </div>
                    <div>
                        <div class="fw-semibold"><?php echo html_escape($al->full_name); ?></div>
                        <div class="text-muted small">
                            <?php echo html_escape($al->graduation_year ?: '—'); ?>
                            <?php if ($al->class_completed): ?> &middot; <?php echo html_escape($al->class_completed); ?><?php endif; ?>
                        </div>
                        <?php if ($al->is_verified): ?>
                        <span class="badge" style="background:#d1fae5;color:#065f46;font-size:.65rem;"><i class="fas fa-check me-1"></i>Verified</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="text-muted small">
                    <?php if ($al->occupation): ?><div><i class="fas fa-briefcase me-1"></i><?php echo html_escape($al->occupation); ?><?php if ($al->employer): ?> @ <?php echo html_escape($al->employer); ?><?php endif; ?></div><?php endif; ?>
                    <?php if ($al->university_joined): ?><div><i class="fas fa-university me-1"></i><?php echo html_escape($al->university_joined); ?></div><?php endif; ?>
                    <?php if ($al->current_location): ?><div><i class="fas fa-map-marker-alt me-1"></i><?php echo html_escape($al->current_location); ?></div><?php endif; ?>
                    <?php if ($al->email): ?><div><i class="fas fa-envelope me-1"></i><?php echo html_escape($al->email); ?></div><?php endif; ?>
                    <?php if ($al->phone): ?><div><i class="fas fa-phone me-1"></i><?php echo html_escape($al->phone); ?></div><?php endif; ?>
                </div>
                <?php if ($al->achievements): ?>
                <div class="mt-2 small" style="font-style:italic; color:#6b7280;"><?php echo nl2br(html_escape($al->achievements)); ?></div>
                <?php endif; ?>
                <div class="mt-3">
                    <?php echo btn_delete('alumni/delete/'.$al->id); ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<!-- Add Alumni Modal -->
<div id="modal-alumni" class="mfp-hide">
    <div class="card mb-0" style="min-width:540px; max-width:620px; max-height:90vh; overflow-y:auto;">
        <div class="card-header d-flex align-items-center justify-content-between"><h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Add Alumni</h5><button type="button" class="btn-close ms-2" onclick="$.magnificPopup.close()" title="Close"></button></div>
        <div class="card-body">
            <?php echo form_open('alumni/save', ['class'=>'frm-submit']); ?>
            <div class="row g-3">
                <div class="col-sm-8">
                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="full_name" class="form-control" required>
                </div>
                <div class="col-sm-4">
                    <label class="form-label">Adm. No.</label>
                    <input type="text" name="admission_no" class="form-control">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Graduation Year</label>
                    <input type="number" name="graduation_year" class="form-control" min="1980" max="2100" placeholder="e.g. 2024">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Last Class</label>
                    <input type="text" name="class_completed" class="form-control" placeholder="e.g. Form 4, Grade 9">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label">Current Location</label>
                    <input type="text" name="current_location" class="form-control" placeholder="City, Country">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Occupation</label>
                    <input type="text" name="occupation" class="form-control" placeholder="Teacher, Engineer...">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Employer</label>
                    <input type="text" name="employer" class="form-control">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">University / College</label>
                    <input type="text" name="university_joined" class="form-control">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Course Studied</label>
                    <input type="text" name="course_studied" class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label">Notable Achievements</label>
                    <textarea name="achievements" class="form-control" rows="2"></textarea>
                </div>
                <div class="col-12">
                    <div class="form-check">
                        <input type="checkbox" name="is_verified" value="1" class="form-check-input" id="alum-verified">
                        <label class="form-check-label" for="alum-verified">Verified Alumni</label>
                    </div>
                </div>
            </div>
            <div class="mt-3 text-end"><button class="btn btn-primary" type="submit"><i class="fas fa-save me-1"></i>Save</button></div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div id="modal-import" class="mfp-hide">
    <div class="card mb-0" style="min-width:400px; max-width:460px;">
        <div class="card-header d-flex align-items-center justify-content-between"><h5 class="mb-0"><i class="fas fa-file-import me-2"></i>Import from Students</h5><button type="button" class="btn-close ms-2" onclick="$.magnificPopup.close()" title="Close"></button></div>
        <div class="card-body">
            <?php echo form_open('alumni/import_from_students', ['class'=>'frm-submit']); ?>
            <p class="text-muted small">Imports inactive (graduated) students from a class as alumni records.</p>
            <div class="mb-3">
                <label class="form-label">Graduation Year</label>
                <input type="number" name="graduation_year" class="form-control" value="<?php echo date('Y'); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Class <span class="text-danger">*</span></label>
                <select name="class_id" class="form-select select2" required>
                    <option value="">-- Select Class --</option>
                    <?php
                    $allClasses = $this->db->where('branch_id',get_loggedin_branch_id())->get('class')->result();
                    foreach ($allClasses as $c): ?>
                    <option value="<?php echo $c->id; ?>"><?php echo html_escape($c->name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="text-end"><button class="btn btn-info" type="submit"><i class="fas fa-file-import me-1"></i>Import</button></div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
