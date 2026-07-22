<?php
$session  = isset($session)  ? $session  : array();
$bookings = isset($bookings) ? $bookings : array();
$students = isset($students) ? $students : array();
$teachers = isset($teachers) ? $teachers : array();
$slots    = isset($slots)    ? $slots    : array();
$statusBadge = array(
    'booked'    => 'badge-info',
    'attended'  => 'badge-success',
    'missed'    => 'badge-danger',
    'cancelled' => 'badge-secondary',
);
?>
<div class="row">
    <div class="col-md-12">
        <section class="panel" style="border-top:3px solid #2e86c1">
            <div class="panel-body" style="display:flex;align-items:center;gap:16px">
                <div>
                    <h4 class="mb-xs"><?= htmlspecialchars($session['title'] ?? '') ?></h4>
                    <small class="text-muted">
                        <i class="fas fa-calendar"></i> <?= date('l, d F Y', strtotime($session['session_date'])) ?>
                        &nbsp;|&nbsp;
                        <i class="fas fa-clock"></i> <?= date('H:i', strtotime($session['start_time'])) ?> – <?= date('H:i', strtotime($session['end_time'])) ?>
                        &nbsp;|&nbsp;
                        <?= $session['slot_duration_mins'] ?>-min slots
                        <?php if (!empty($session['venue'])): ?>&nbsp;|&nbsp; <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($session['venue']) ?><?php endif; ?>
                    </small>
                </div>
                <a href="<?= base_url('ptm') ?>" class="btn btn-default btn-sm ml-auto"><i class="fas fa-arrow-left"></i> Back</a>
                <button class="btn btn-default btn-sm" onclick="mfp_modal('#add-booking-modal')"><i class="fas fa-plus"></i> Add Booking</button>
            </div>
        </section>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <h4 class="panel-title"><i class="fas fa-calendar-check"></i> Bookings (<?= count($bookings) ?>)</h4>
            </header>
            <div class="panel-body">
                <?php if (empty($bookings)): ?>
                    <p class="text-center text-muted mt-md">No bookings yet. Click <strong>Add Booking</strong> above.</p>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed table-hover table-export">
                        <thead>
                            <tr><th>Slot</th><th>Student</th><th>Class</th><th>Parent</th><th>Phone</th><th>Teacher</th><th>Status</th><th>Notes</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bookings as $b): ?>
                            <tr>
                                <td><strong><?= date('H:i', strtotime($b['slot_time'])) ?></strong></td>
                                <td><?= htmlspecialchars($b['student_name'] ?? '') ?><br><small class="text-muted"><?= htmlspecialchars($b['register_no'] ?? '') ?></small></td>
                                <td><?= htmlspecialchars(($b['class'] ?? '') . ' ' . ($b['section_name'] ?? '')) ?></td>
                                <td><?= htmlspecialchars($b['parent_name'] ?? '—') ?></td>
                                <td><?= htmlspecialchars($b['parent_phone'] ?? '—') ?></td>
                                <td><?= htmlspecialchars($b['teacher_name'] ?? '—') ?></td>
                                <td>
                                    <select class="form-control input-xs status-select" data-id="<?= $b['id'] ?>" style="width:auto">
                                        <?php foreach (['booked','attended','missed','cancelled'] as $st): ?>
                                        <option value="<?= $st ?>" <?= $b['status'] == $st ? 'selected' : '' ?>><?= ucfirst($st) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td><?= htmlspecialchars($b['notes'] ?? '') ?></td>
                                <td><?= btn_delete('ptm/delete_booking/' . $b['id']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>

<!-- Add Booking Modal -->
<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="add-booking-modal">
    <section class="panel">
        <header class="panel-heading"><h4 class="panel-title">Book a PTM Slot</h4><button type="button" class="close" onclick="$.magnificPopup.close()" style="float:right;font-size:1.4rem;line-height:1;opacity:.7;" title="Close">&times;</button></header>
        <?php echo form_open('ptm/save_booking', array('class' => 'frm-submit')); ?>
        <input type="hidden" name="ptm_session_id" value="<?= $session['id'] ?? '' ?>">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12"><div class="form-group"><label>Student <span class="required">*</span></label>
                    <select name="student_id" class="form-control" data-plugin-selectTwo data-width="100%">
                        <option value="">— Select Student —</option>
                        <?php foreach ($students as $s): ?>
                        <option value="<?= $s['student_id'] ?>"><?= htmlspecialchars($s['first_name'] . ' ' . $s['last_name'] . ' (' . $s['register_no'] . ')') ?></option>
                        <?php endforeach; ?>
                    </select><span class="error"></span>
                </div></div>
                <div class="col-md-6"><div class="form-group"><label>Teacher</label>
                    <select name="teacher_id" class="form-control" data-plugin-selectTwo data-width="100%">
                        <option value="">— Select Teacher —</option>
                        <?php foreach ($teachers as $t): ?>
                        <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div></div>
                <div class="col-md-6"><div class="form-group"><label>Slot Time <span class="required">*</span></label>
                    <select name="slot_time" class="form-control">
                        <?php foreach ($slots as $slot): ?>
                        <option value="<?= $slot ?>"><?= $slot ?></option>
                        <?php endforeach; ?>
                    </select><span class="error"></span>
                </div></div>
                <div class="col-md-12"><div class="form-group"><label>Notes</label><textarea name="notes" class="form-control" rows="2"></textarea></div></div>
            </div>
        </div>
        <footer class="panel-footer"><div class="text-right">
            <button type="button" class="btn btn-default modal-dismiss">Cancel</button>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Book Slot</button>
        </div></footer>
        <?php echo form_close(); ?>
    </section>
</div>

<script>
$(document).on('change', '.status-select', function () {
    var id     = $(this).data('id');
    var status = $(this).val();
    $.post(base_url + 'ptm/update_status/' + id, {status: status}, function (res) {
        if (res.status === 'success') {
            swal({type:'success', title:'Updated', text: res.message, timer:1500, showConfirmButton:false});
        }
    }, 'json');
});
</script>
