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

<?php
$typeMap = [
    'government' => ['bg-primary',          'Government'],
    'ngcdf'      => ['bg-success',          'NG-CDF'],
    'private'    => ['bg-info text-dark',   'Private'],
    'church'     => ['bg-warning text-dark','Church'],
    'other'      => ['bg-secondary',        'Other'],
];
$statusMap = [
    'open'      => ['bg-success',  'Open'],
    'closed'    => ['bg-secondary','Closed'],
    'disbursed' => ['bg-primary',  'Disbursed'],
];
?>

<div class="content-header">
    <div class="d-flex align-items-center justify-content-end flex-wrap gap-2">
        <button class="btn btn-sm btn-primary" onclick="resetProgForm(); mfp_modal('#modal-programme')">
            <i class="fas fa-plus me-1"></i>New Programme
        </button>
    </div>
</div>

<div class="container-fluid">
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0" id="bursary-table">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Programme Name</th>
                            <th>Provider</th>
                            <th>Type</th>
                            <th>Year</th>
                            <th>Allocation</th>
                            <th>Beneficiaries</th>
                            <th>Awarded</th>
                            <th>Disbursed</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($programmes)): ?>
                        <tr><td colspan="11" class="text-center py-4 text-muted">No programmes yet. Click <strong>New Programme</strong> to start.</td></tr>
                        <?php else: foreach ($programmes as $i => $p):
                            [$typeCls, $typeLbl]   = $typeMap[$p['provider_type']]  ?? ['bg-secondary', ucfirst($p['provider_type'])];
                            [$statCls, $statLbl]   = $statusMap[$p['status']]       ?? ['bg-secondary', ucfirst($p['status'])];
                        ?>
                        <tr>
                            <td><?php echo $i + 1; ?></td>
                            <td><strong><?php echo html_escape($p['name']); ?></strong></td>
                            <td><?php echo html_escape($p['provider']); ?></td>
                            <td><span class="badge <?php echo $typeCls; ?>"><?php echo $typeLbl; ?></span></td>
                            <td><?php echo html_escape($p['academic_year'] ?? '—'); ?></td>
                            <td class="text-nowrap"><?php echo !empty($p['total_allocation']) ? 'KES ' . number_format($p['total_allocation'], 2) : '—'; ?></td>
                            <td class="text-center"><?php echo (int)$p['beneficiaries']; ?></td>
                            <td class="text-nowrap">KES <?php echo number_format($p['total_awarded'], 2); ?></td>
                            <td class="text-nowrap">KES <?php echo number_format($p['total_disbursed'], 2); ?></td>
                            <td><span class="badge <?php echo $statCls; ?>"><?php echo $statLbl; ?></span></td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="<?php echo base_url('bursary/awards/' . $p['id']); ?>" class="btn btn-xs btn-outline-primary" title="Manage Awards">
                                        <i class="fas fa-users"></i>
                                    </a>
                                    <button class="btn btn-xs btn-outline-warning" title="Edit" onclick="editProg(this)"
                                        data-json="<?php echo htmlspecialchars(json_encode([
                                            'id'                   => (int)$p['id'],
                                            'name'                 => $p['name'],
                                            'provider'             => $p['provider'],
                                            'provider_type'        => $p['provider_type'],
                                            'description'          => $p['description']          ?? '',
                                            'total_allocation'     => $p['total_allocation']     ?? '',
                                            'academic_year'        => $p['academic_year']        ?? '',
                                            'application_deadline' => $p['application_deadline'] ?? '',
                                            'status'               => $p['status'],
                                        ]), ENT_QUOTES); ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <?php echo btn_delete('bursary/delete_programme/' . $p['id']); ?>
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

<!-- Add / Edit Programme Modal -->
<div id="modal-programme" class="mfp-hide">
    <div class="card mb-0" style="min-width:540px; max-width:640px;">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0" id="prog-modal-title"><i class="fas fa-plus-circle me-2"></i>New Bursary / Scholarship Programme</h5>
            <button type="button" class="btn-close ms-2" onclick="$.magnificPopup.close()" title="Close"></button>
        </div>
        <div class="card-body">
            <?php echo form_open('bursary/save_programme', ['class' => 'frm-submit', 'id' => 'prog-form']); ?>
            <input type="hidden" name="id" id="prog-edit-id" value="">
            <div class="row g-3">
                <div class="col-sm-8">
                    <label class="form-label">Programme Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. NG-CDF Bursary 2025">
                </div>
                <div class="col-sm-4">
                    <label class="form-label">Academic Year</label>
                    <input type="text" name="academic_year" class="form-control" placeholder="e.g. 2025/2026">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Provider <span class="text-danger">*</span></label>
                    <input type="text" name="provider" class="form-control" placeholder="e.g. Westlands Constituency">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Provider Type <span class="text-danger">*</span></label>
                    <select name="provider_type" class="form-select">
                        <option value="government">Government</option>
                        <option value="ngcdf">NG-CDF</option>
                        <option value="private">Private / Corporate</option>
                        <option value="church">Church / Religious</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Total Allocation (KES)</label>
                    <input type="number" name="total_allocation" class="form-control" step="0.01" min="0">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Application Deadline</label>
                    <input type="date" name="application_deadline" class="form-control">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="open">Open</option>
                        <option value="closed">Closed</option>
                        <option value="disbursed">Disbursed</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="mt-3 text-end">
                <button type="button" class="btn btn-secondary me-1" onclick="$.magnificPopup.close()">Cancel</button>
                <button type="submit" class="btn btn-primary" id="prog-submit-btn"><i class="fas fa-save me-1"></i>Save Programme</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
function resetProgForm() {
    var f = document.getElementById('prog-form');
    f.reset();
    document.getElementById('prog-edit-id').value = '';
    document.getElementById('prog-modal-title').innerHTML = '<i class="fas fa-plus-circle me-2"></i>New Bursary / Scholarship Programme';
    document.getElementById('prog-submit-btn').innerHTML  = '<i class="fas fa-save me-1"></i>Save Programme';
}
function editProg(btn) {
    var d = JSON.parse(btn.getAttribute('data-json'));
    var f = document.getElementById('prog-form');
    f.reset();
    document.getElementById('prog-edit-id').value = d.id;
    f.querySelector('[name="name"]').value                 = d.name                 || '';
    f.querySelector('[name="provider"]').value             = d.provider             || '';
    f.querySelector('[name="provider_type"]').value        = d.provider_type        || 'government';
    f.querySelector('[name="description"]').value          = d.description          || '';
    f.querySelector('[name="total_allocation"]').value     = d.total_allocation     || '';
    f.querySelector('[name="academic_year"]').value        = d.academic_year        || '';
    f.querySelector('[name="application_deadline"]').value = d.application_deadline || '';
    f.querySelector('[name="status"]').value               = d.status               || 'open';
    document.getElementById('prog-modal-title').innerHTML = '<i class="fas fa-edit me-2"></i>Edit Programme';
    document.getElementById('prog-submit-btn').innerHTML  = '<i class="fas fa-save me-1"></i>Save Changes';
    mfp_modal('#modal-programme');
}
$(function(){
    $('#bursary-table').DataTable({
        order: [[0, 'asc']],
        pageLength: 25,
        columnDefs: [{ orderable: false, targets: [10] }]
    });
});
</script>
