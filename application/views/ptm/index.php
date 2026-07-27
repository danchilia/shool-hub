<style>
@media (prefers-color-scheme:dark) {
    .card        { background:#2b2b3a; border-color:#3a3a50; }
    .card-header { background:#232333; border-color:#3a3a50; }
    .table-light { --bs-table-bg:#232333; }
}
:root[data-theme="dark"]  .card        { background:#2b2b3a; border-color:#3a3a50; }
:root[data-theme="dark"]  .card-header { background:#232333; border-color:#3a3a50; }
:root[data-theme="dark"]  .table-light { --bs-table-bg:#232333; }
:root[data-theme="light"] .card        { background:#fff;    border-color:#dee2e6; }
:root[data-theme="light"] .card-header { background:#f8f9fa; border-color:#dee2e6; }
:root[data-theme="light"] .table-light { --bs-table-bg:#f8f9fa; }
</style>

<div class="content-header">
    <div class="d-flex align-items-center justify-content-end flex-wrap gap-2">
        <button class="btn btn-sm btn-primary" onclick="resetSessionForm(); mfp_modal('#modal-session')">
            <i class="fas fa-plus me-1"></i>Schedule PTM
        </button>
    </div>
</div>

<div class="container-fluid">
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0" id="ptm-table">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Venue</th>
                            <th>Slot</th>
                            <th>Bookings</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($sessions)): ?>
                        <tr><td colspan="8" class="text-center py-4 text-muted">No PTM sessions scheduled yet.</td></tr>
                        <?php else: foreach ($sessions as $i => $s): ?>
                        <tr>
                            <td><?php echo $i + 1; ?></td>
                            <td><strong><?php echo html_escape($s['title']); ?></strong></td>
                            <td><?php echo date('d M Y', strtotime($s['session_date'])); ?></td>
                            <td class="small"><?php echo date('H:i', strtotime($s['start_time'])); ?> – <?php echo date('H:i', strtotime($s['end_time'])); ?></td>
                            <td><?php echo html_escape($s['venue'] ?? '—'); ?></td>
                            <td class="text-center"><?php echo (int)$s['slot_duration_mins']; ?> min</td>
                            <td class="text-center"><span class="badge bg-info text-dark"><?php echo (int)$s['bookings']; ?></span></td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="<?php echo base_url('ptm/bookings/' . $s['id']); ?>" class="btn btn-xs btn-outline-primary" title="Manage Bookings">
                                        <i class="fas fa-calendar-check"></i>
                                    </a>
                                    <button class="btn btn-xs btn-outline-warning" title="Edit" onclick="editSession(this)"
                                        data-json="<?php echo htmlspecialchars(json_encode([
                                            'id'                 => (int)$s['id'],
                                            'title'              => $s['title'],
                                            'session_date'       => $s['session_date'],
                                            'start_time'         => substr($s['start_time'], 0, 5),
                                            'end_time'           => substr($s['end_time'], 0, 5),
                                            'venue'              => $s['venue'] ?? '',
                                            'slot_duration_mins' => (int)$s['slot_duration_mins'],
                                            'notes'              => $s['notes'] ?? '',
                                        ]), ENT_QUOTES); ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <?php echo btn_delete('ptm/delete_session/' . $s['id']); ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add / Edit Session Modal -->
<div id="modal-session" class="mfp-hide">
    <div class="card mb-0" style="min-width:500px; max-width:600px;">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0" id="session-modal-title"><i class="fas fa-plus-circle me-2"></i>Schedule PTM</h5>
            <button type="button" class="btn-close ms-2" onclick="$.magnificPopup.close()" title="Close"></button>
        </div>
        <div class="card-body">
            <?php echo form_open('ptm/save_session', ['class' => 'frm-submit', 'id' => 'session-form']); ?>
            <input type="hidden" name="id" id="session-edit-id" value="">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. Term 2 PTM 2025">
                </div>
                <div class="col-sm-4">
                    <label class="form-label">Date <span class="text-danger">*</span></label>
                    <input type="date" name="session_date" class="form-control">
                </div>
                <div class="col-sm-4">
                    <label class="form-label">Start Time <span class="text-danger">*</span></label>
                    <input type="time" name="start_time" class="form-control" value="08:00">
                </div>
                <div class="col-sm-4">
                    <label class="form-label">End Time <span class="text-danger">*</span></label>
                    <input type="time" name="end_time" class="form-control" value="17:00">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Venue</label>
                    <input type="text" name="venue" class="form-control" placeholder="e.g. School Hall">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Slot Duration (mins) <span class="text-danger">*</span></label>
                    <input type="number" name="slot_duration_mins" class="form-control" value="15" min="5" max="60">
                </div>
                <div class="col-12">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="mt-3 text-end">
                <button type="button" class="btn btn-secondary me-1" onclick="$.magnificPopup.close()">Cancel</button>
                <button type="submit" class="btn btn-primary" id="session-submit-btn"><i class="fas fa-save me-1"></i>Save Session</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
function resetSessionForm() {
    var f = document.getElementById('session-form');
    f.reset();
    document.getElementById('session-edit-id').value = '';
    document.getElementById('session-modal-title').innerHTML = '<i class="fas fa-plus-circle me-2"></i>Schedule PTM';
    document.getElementById('session-submit-btn').innerHTML  = '<i class="fas fa-save me-1"></i>Save Session';
}
function editSession(btn) {
    var d = JSON.parse(btn.getAttribute('data-json'));
    var f = document.getElementById('session-form');
    f.reset();
    document.getElementById('session-edit-id').value = d.id;
    f.querySelector('[name="title"]').value              = d.title              || '';
    f.querySelector('[name="session_date"]').value       = d.session_date       || '';
    f.querySelector('[name="start_time"]').value         = d.start_time         || '08:00';
    f.querySelector('[name="end_time"]').value           = d.end_time           || '17:00';
    f.querySelector('[name="venue"]').value              = d.venue              || '';
    f.querySelector('[name="slot_duration_mins"]').value = d.slot_duration_mins || 15;
    f.querySelector('[name="notes"]').value              = d.notes              || '';
    document.getElementById('session-modal-title').innerHTML = '<i class="fas fa-edit me-2"></i>Edit PTM Session';
    document.getElementById('session-submit-btn').innerHTML  = '<i class="fas fa-save me-1"></i>Save Changes';
    mfp_modal('#modal-session');
}
$(function(){
    $('#ptm-table').DataTable({
        order: [[2, 'desc']],
        pageLength: 25,
        columnDefs: [{ orderable: false, targets: [7] }]
    });
});
</script>
