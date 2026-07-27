<style>
@media (prefers-color-scheme:dark) {
    .card          { background:#2b2b3a; border-color:#3a3a50; }
    .card-header   { background:#232333; border-color:#3a3a50; }
    .table-light   { --bs-table-bg:#232333; }
    .prog-summary  { background:#1a2e1a !important; border-color:#27ae60 !important; }
}
:root[data-theme="dark"]  .card         { background:#2b2b3a; border-color:#3a3a50; }
:root[data-theme="dark"]  .card-header  { background:#232333; border-color:#3a3a50; }
:root[data-theme="dark"]  .table-light  { --bs-table-bg:#232333; }
:root[data-theme="dark"]  .prog-summary { background:#1a2e1a !important; border-color:#27ae60 !important; }
:root[data-theme="light"] .card         { background:#fff;    border-color:#dee2e6; }
:root[data-theme="light"] .card-header  { background:#f8f9fa; border-color:#dee2e6; }
:root[data-theme="light"] .table-light  { --bs-table-bg:#f8f9fa; }
:root[data-theme="light"] .prog-summary { background:#f0fdf4 !important; border-color:#27ae60 !important; }
</style>

<?php
$awardStatusMap = [
    'applied'   => ['bg-warning text-dark', 'Applied'],
    'approved'  => ['bg-info text-dark',    'Approved'],
    'disbursed' => ['bg-success',           'Disbursed'],
    'rejected'  => ['bg-danger',            'Rejected'],
];
?>

<div class="content-header">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <a href="<?php echo base_url('bursary'); ?>" class="text-muted small"><i class="fas fa-arrow-left me-1"></i>All Programmes</a>
        <button class="btn btn-sm btn-primary" onclick="resetAwardForm(); mfp_modal('#modal-award')">
            <i class="fas fa-plus me-1"></i>Add Award
        </button>
    </div>
</div>

<div class="container-fluid">

    <!-- Programme summary -->
    <div class="card mb-3 prog-summary border-start border-success border-3">
        <div class="card-body py-2">
            <div class="d-flex flex-wrap gap-3 small text-muted">
                <span><i class="fas fa-building me-1"></i><?php echo html_escape($programme['provider'] ?? ''); ?></span>
                <?php if (!empty($programme['academic_year'])): ?>
                <span><i class="fas fa-calendar me-1"></i><?php echo html_escape($programme['academic_year']); ?></span>
                <?php endif; ?>
                <?php if (!empty($programme['total_allocation'])): ?>
                <span><i class="fas fa-coins me-1"></i>Allocation: KES <?php echo number_format($programme['total_allocation'], 2); ?></span>
                <?php endif; ?>
                <?php if (!empty($programme['application_deadline'])): ?>
                <span><i class="fas fa-calendar-times me-1"></i>Deadline: <?php echo date('d M Y', strtotime($programme['application_deadline'])); ?></span>
                <?php endif; ?>
                <span class="ms-auto fw-semibold text-body"><i class="fas fa-users me-1"></i><?php echo count($awards); ?> beneficiaries</span>
            </div>
        </div>
    </div>

    <!-- Awards table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0" id="awards-table">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Student</th>
                            <th>Reg. No.</th>
                            <th>Class</th>
                            <th>Awarded (KES)</th>
                            <th>Disbursed (KES)</th>
                            <th>Applied</th>
                            <th>Status</th>
                            <th>Remarks</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($awards)): ?>
                        <tr><td colspan="10" class="text-center py-4 text-muted">No awards yet. Click <strong>Add Award</strong> above.</td></tr>
                        <?php else: foreach ($awards as $i => $a):
                            [$badgeCls, $badgeLbl] = $awardStatusMap[$a['status']] ?? ['bg-secondary', ucfirst($a['status'] ?? '')];
                        ?>
                        <tr>
                            <td><?php echo $i + 1; ?></td>
                            <td><?php echo html_escape($a['student_name'] ?? ''); ?></td>
                            <td><?php echo html_escape($a['register_no'] ?? ''); ?></td>
                            <td><?php echo html_escape(trim(($a['class'] ?? '') . ' ' . ($a['section_name'] ?? ''))); ?></td>
                            <td class="text-nowrap"><?php echo number_format((float)$a['amount_awarded'], 2); ?></td>
                            <td class="text-nowrap"><?php echo number_format((float)$a['amount_disbursed'], 2); ?></td>
                            <td><?php echo $a['applied_date'] ? date('d M Y', strtotime($a['applied_date'])) : '—'; ?></td>
                            <td><span class="badge <?php echo $badgeCls; ?>"><?php echo $badgeLbl; ?></span></td>
                            <td class="small"><?php echo html_escape($a['remarks'] ?? ''); ?></td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-xs btn-outline-warning" title="Edit" onclick="editAward(this)"
                                        data-json="<?php echo htmlspecialchars(json_encode([
                                            'id'                => (int)$a['id'],
                                            'student_id'        => (int)$a['student_id'],
                                            'amount_awarded'    => $a['amount_awarded'],
                                            'amount_disbursed'  => $a['amount_disbursed'],
                                            'disbursement_date' => $a['disbursement_date'] ?? '',
                                            'applied_date'      => $a['applied_date']      ?? '',
                                            'status'            => $a['status'],
                                            'remarks'           => $a['remarks']           ?? '',
                                        ]), ENT_QUOTES); ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <?php echo btn_delete('bursary/delete_award/' . $a['id']); ?>
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

<!-- Add / Edit Award Modal -->
<div id="modal-award" class="mfp-hide">
    <div class="card mb-0" style="min-width:500px; max-width:580px;">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0" id="award-modal-title"><i class="fas fa-plus-circle me-2"></i>Add Bursary Award</h5>
            <button type="button" class="btn-close ms-2" onclick="$.magnificPopup.close()" title="Close"></button>
        </div>
        <div class="card-body">
            <?php echo form_open('bursary/save_award', ['class' => 'frm-submit', 'id' => 'award-form']); ?>
            <input type="hidden" name="id" id="award-edit-id" value="">
            <input type="hidden" name="programme_id" value="<?php echo (int)($programme['id'] ?? 0); ?>">
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
                    <label class="form-label">Amount Awarded (KES) <span class="text-danger">*</span></label>
                    <input type="number" name="amount_awarded" class="form-control" step="0.01" min="1">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Amount Disbursed (KES)</label>
                    <input type="number" name="amount_disbursed" class="form-control" step="0.01" min="0" value="0">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Applied Date</label>
                    <input type="date" name="applied_date" class="form-control">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Disbursement Date</label>
                    <input type="date" name="disbursement_date" class="form-control">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="applied">Applied</option>
                        <option value="approved">Approved</option>
                        <option value="disbursed">Disbursed</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Remarks</label>
                    <textarea name="remarks" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="mt-3 text-end">
                <button type="button" class="btn btn-secondary me-1" onclick="$.magnificPopup.close()">Cancel</button>
                <button type="submit" class="btn btn-primary" id="award-submit-btn"><i class="fas fa-save me-1"></i>Save Award</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
function resetAwardForm() {
    var f = document.getElementById('award-form');
    f.reset();
    document.getElementById('award-edit-id').value = '';
    document.getElementById('award-modal-title').innerHTML = '<i class="fas fa-plus-circle me-2"></i>Add Bursary Award';
    document.getElementById('award-submit-btn').innerHTML  = '<i class="fas fa-save me-1"></i>Save Award';
    $('#award-form [name="student_id"]').val('').trigger('change');
}
function editAward(btn) {
    var d = JSON.parse(btn.getAttribute('data-json'));
    var f = document.getElementById('award-form');
    f.reset();
    document.getElementById('award-edit-id').value = d.id;
    f.querySelector('[name="amount_awarded"]').value    = d.amount_awarded    || '';
    f.querySelector('[name="amount_disbursed"]').value  = d.amount_disbursed  || 0;
    f.querySelector('[name="applied_date"]').value      = d.applied_date      || '';
    f.querySelector('[name="disbursement_date"]').value = d.disbursement_date || '';
    f.querySelector('[name="status"]').value            = d.status            || 'applied';
    f.querySelector('[name="remarks"]').value           = d.remarks           || '';
    $('#award-form [name="student_id"]').val(d.student_id || '').trigger('change');
    document.getElementById('award-modal-title').innerHTML = '<i class="fas fa-edit me-2"></i>Edit Award';
    document.getElementById('award-submit-btn').innerHTML  = '<i class="fas fa-save me-1"></i>Save Changes';
    mfp_modal('#modal-award');
}
$(function(){
    $('#awards-table').DataTable({
        order: [[0, 'asc']],
        pageLength: 50,
        columnDefs: [{ orderable: false, targets: [9] }]
    });
});
</script>
