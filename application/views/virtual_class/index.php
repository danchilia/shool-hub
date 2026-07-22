<style>
.vc-card { border:1px solid #e2e8f0; border-radius:10px; background:#fff; overflow:hidden; }
.vc-platform { font-size:.72rem; border-radius:4px; padding:2px 8px; font-weight:600; }
.vc-zoom     { background:#e8f1ff; color:#2D8CFF; }
.vc-meet     { background:#e8f5e9; color:#0F9D58; }
.vc-teams    { background:#ede7f6; color:#6264A7; }
.vc-webex    { background:#fff3e0; color:#E87722; }
.vc-other    { background:#f3f4f6; color:#6b7280; }
.status-upcoming  { background:#dbeafe; color:#1e40af; }
.status-ongoing   { background:#d1fae5; color:#065f46; }
.status-completed { background:#f3f4f6; color:#374151; }
.status-cancelled { background:#fee2e2; color:#991b1b; }
@media(prefers-color-scheme:dark){ .vc-card{ background:#2b2b3a; border-color:#3a3a50; } }
</style>

<div class="content-header">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h4 class="mb-0"><i class="fas fa-video me-2 text-primary"></i>Virtual Classroom</h4>
        <?php if (is_admin_loggedin() || is_superadmin_loggedin() || is_teacher_loggedin()): ?>
        <button class="btn btn-sm btn-primary" onclick="mfp_modal('#modal-vc')">
            <i class="fas fa-plus me-1"></i>Schedule Class
        </button>
        <?php endif; ?>
    </div>
</div>

<div class="container-fluid">
    <?php
    $upcoming  = array_filter($virtual_classes, fn($v) => $v->status === 'upcoming');
    $ongoing   = array_filter($virtual_classes, fn($v) => $v->status === 'ongoing');
    $completed = array_filter($virtual_classes, fn($v) => in_array($v->status,['completed','cancelled']));
    ?>

    <?php if (!empty($ongoing)): ?>
    <h6 class="text-uppercase text-muted fw-bold small mb-2"><i class="fas fa-circle text-danger me-1" style="font-size:.5rem;"></i>Live Now</h6>
    <div class="row g-3 mb-4">
        <?php foreach ($ongoing as $vc): ?>
        <div class="col-md-4"><?php include 'partials/vc_card.php'; ?></div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($upcoming)): ?>
    <h6 class="text-uppercase text-muted fw-bold small mb-2">Upcoming</h6>
    <div class="row g-3 mb-4">
        <?php foreach ($upcoming as $vc): ?>
        <div class="col-md-4"><?php include 'partials/vc_card.php'; ?></div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($completed)): ?>
    <h6 class="text-uppercase text-muted fw-bold small mb-2">Past Classes</h6>
    <div class="row g-3">
        <?php foreach ($completed as $vc): ?>
        <div class="col-md-4"><?php include 'partials/vc_card.php'; ?></div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (empty($virtual_classes)): ?>
    <div class="alert alert-info">No virtual classes scheduled yet. Click "Schedule Class" to begin.</div>
    <?php endif; ?>
</div>

<!-- Schedule Modal -->
<div id="modal-vc" class="mfp-hide">
    <div class="card mb-0" style="min-width:540px; max-width:620px; max-height:90vh; overflow-y:auto;">
        <div class="card-header d-flex align-items-center justify-content-between"><h5 class="mb-0"><i class="fas fa-video me-2"></i>Schedule Virtual Class</h5><button type="button" class="btn-close ms-2" onclick="$.magnificPopup.close()" title="Close"></button></div>
        <div class="card-body">
            <?php echo form_open('virtual_class/save', ['class'=>'frm-submit']); ?>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" required placeholder="e.g. Grade 7 Mathematics - Fractions">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Platform</label>
                    <select name="platform" class="form-select">
                        <option value="meet">Google Meet</option>
                        <option value="zoom">Zoom</option>
                        <option value="teams">Microsoft Teams</option>
                        <option value="webex">Webex</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Duration (mins)</label>
                    <input type="number" name="duration_mins" class="form-control" value="45" min="15">
                </div>
                <div class="col-12">
                    <label class="form-label">Meeting Link <span class="text-danger">*</span></label>
                    <input type="url" name="meeting_link" class="form-control" required placeholder="https://meet.google.com/...">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Meeting ID</label>
                    <input type="text" name="meeting_id" class="form-control">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Password</label>
                    <input type="text" name="password" class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label">Scheduled Date & Time <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="scheduled_at" class="form-control" required>
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Class</label>
                    <select name="class_id" class="form-select select2">
                        <option value="">-- All / Not specified --</option>
                        <?php foreach ($classes as $c): ?>
                        <option value="<?php echo $c->id; ?>"><?php echo html_escape($c->class); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Subject</label>
                    <select name="subject_id" class="form-select select2">
                        <option value="">-- All / Not specified --</option>
                        <?php foreach ($subjects as $s): ?>
                        <option value="<?php echo $s->id; ?>"><?php echo html_escape($s->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Description / Notes</label>
                    <textarea name="description" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="mt-3 text-end"><button class="btn btn-primary" type="submit"><i class="fas fa-save me-1"></i>Schedule</button></div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
