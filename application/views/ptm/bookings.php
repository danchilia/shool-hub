<style>
.slot-badge { font-size:.9rem; font-weight:700; }
@media (prefers-color-scheme:dark) {
    .card         { background:#2b2b3a; border-color:#3a3a50; }
    .card-header  { background:#232333; border-color:#3a3a50; }
    .table-light  { --bs-table-bg:#232333; }
    .session-info { background:#1a2840 !important; border-color:#2563eb !important; }
}
:root[data-theme="dark"]  .card         { background:#2b2b3a; border-color:#3a3a50; }
:root[data-theme="dark"]  .card-header  { background:#232333; border-color:#3a3a50; }
:root[data-theme="dark"]  .table-light  { --bs-table-bg:#232333; }
:root[data-theme="dark"]  .session-info { background:#1a2840 !important; border-color:#2563eb !important; }
:root[data-theme="light"] .card         { background:#fff;    border-color:#dee2e6; }
:root[data-theme="light"] .card-header  { background:#f8f9fa; border-color:#dee2e6; }
:root[data-theme="light"] .table-light  { --bs-table-bg:#f8f9fa; }
:root[data-theme="light"] .session-info { background:#eff6ff !important; border-color:#bfdbfe !important; }
</style>

<div class="content-header">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <a href="<?php echo base_url('ptm'); ?>" class="text-muted small"><i class="fas fa-arrow-left me-1"></i>All Sessions</a>
        <button class="btn btn-sm btn-primary" onclick="mfp_modal('#modal-booking')">
            <i class="fas fa-plus me-1"></i>Add Booking
        </button>
    </div>
</div>

<div class="container-fluid">

    <!-- Session summary -->
    <div class="card mb-3 session-info border-start border-primary border-3">
        <div class="card-body py-2">
            <div class="d-flex flex-wrap gap-3 small text-muted">
                <span><i class="fas fa-calendar me-1"></i><?php echo date('l, d F Y', strtotime($session['session_date'])); ?></span>
                <span><i class="fas fa-clock me-1"></i><?php echo date('H:i', strtotime($session['start_time'])); ?> – <?php echo date('H:i', strtotime($session['end_time'])); ?></span>
                <span><i class="fas fa-stopwatch me-1"></i><?php echo (int)$session['slot_duration_mins']; ?>-min slots</span>
                <?php if (!empty($session['venue'])): ?>
                <span><i class="fas fa-map-marker-alt me-1"></i><?php echo html_escape($session['venue']); ?></span>
                <?php endif; ?>
                <span class="ms-auto fw-semibold text-body"><i class="fas fa-users me-1"></i><?php echo count($bookings); ?> bookings</span>
            </div>
        </div>
    </div>

    <!-- Bookings table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0" id="bookings-table">
                    <thead class="table-light">
                        <tr>
                            <th>Slot</th>
                            <th>Student</th>
                            <th>Class</th>
                            <th>Parent</th>
                            <th>Phone</th>
                            <th>Teacher</th>
                            <th>Status</th>
                            <th>Notes</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($bookings)): ?>
                        <tr><td colspan="9" class="text-center py-4 text-muted">No bookings yet. Click <strong>Add Booking</strong> above.</td></tr>
                        <?php else: foreach ($bookings as $b): ?>
                        <tr>
                            <td><strong class="slot-badge"><?php echo date('H:i', strtotime($b['slot_time'])); ?></strong></td>
                            <td>
                                <?php echo html_escape($b['student_name'] ?? ''); ?>
                                <br><small class="text-muted"><?php echo html_escape($b['register_no'] ?? ''); ?></small>
                            </td>
                            <td><?php echo html_escape(trim(($b['class'] ?? '') . ' ' . ($b['section_name'] ?? ''))); ?></td>
                            <td><?php echo html_escape($b['parent_name'] ?? '—'); ?></td>
                            <td><?php echo html_escape($b['parent_phone'] ?? '—'); ?></td>
                            <td><?php echo html_escape($b['teacher_name'] ?? '—'); ?></td>
                            <td>
                                <select class="form-select form-select-sm status-select" data-id="<?php echo (int)$b['id']; ?>" style="width:auto;min-width:110px">
                                    <?php foreach (['booked','attended','missed','cancelled'] as $st): ?>
                                    <option value="<?php echo $st; ?>" <?php echo $b['status'] === $st ? 'selected' : ''; ?>><?php echo ucfirst($st); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td class="small"><?php echo html_escape($b['notes'] ?? ''); ?></td>
                            <td><?php echo btn_delete('ptm/delete_booking/' . $b['id']); ?></td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Add Booking Modal -->
<div id="modal-booking" class="mfp-hide">
    <div class="card mb-0" style="min-width:480px; max-width:560px;">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0"><i class="fas fa-calendar-plus me-2"></i>Book a PTM Slot</h5>
            <button type="button" class="btn-close ms-2" onclick="$.magnificPopup.close()" title="Close"></button>
        </div>
        <div class="card-body">
            <?php echo form_open('ptm/save_booking', ['class' => 'frm-submit', 'id' => 'booking-form']); ?>
            <input type="hidden" name="ptm_session_id" value="<?php echo (int)($session['id'] ?? 0); ?>">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Student <span class="text-danger">*</span></label>
                    <select name="student_id" class="form-select" data-plugin-selectTwo data-width="100%">
                        <option value="">— Select Student —</option>
                        <?php foreach ($students as $s): ?>
                        <option value="<?php echo $s['student_id']; ?>"><?php echo html_escape($s['first_name'] . ' ' . $s['last_name'] . ' (' . $s['register_no'] . ')'); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Teacher</label>
                    <select name="teacher_id" class="form-select" data-plugin-selectTwo data-width="100%">
                        <option value="">— Select Teacher —</option>
                        <?php foreach ($teachers as $t): ?>
                        <option value="<?php echo $t['id']; ?>"><?php echo html_escape($t['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Slot Time <span class="text-danger">*</span></label>
                    <select name="slot_time" class="form-select">
                        <?php foreach ($slots as $slot): ?>
                        <option value="<?php echo $slot; ?>"><?php echo $slot; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="mt-3 text-end">
                <button type="button" class="btn btn-secondary me-1" onclick="$.magnificPopup.close()">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Book Slot</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
$(document).on('change', '.status-select', function () {
    var $sel    = $(this);
    var id      = $sel.data('id');
    var status  = $sel.val();
    var payload = $.extend({ status: status }, csrfData);
    $.post(base_url + 'ptm/update_status/' + id, payload, function (res) {
        if (typeof res === 'string') res = JSON.parse(res);
        if (res.status !== 'success') {
            alert(res.msg || 'Failed to update status');
            location.reload();
        }
    });
});
$(function(){
    $('#bookings-table').DataTable({
        order: [[0, 'asc']],
        pageLength: 50,
        columnDefs: [{ orderable: false, targets: [8] }]
    });
});
</script>
