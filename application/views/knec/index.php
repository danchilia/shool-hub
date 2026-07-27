<style>
@media (prefers-color-scheme:dark) {
  .card { background:#2b2b3a; border-color:#3a3a50; }
  .card-header { background:#232333; border-color:#3a3a50; }
  .table-light { --bs-table-bg:#232333; }
  .table-bordered td, .table-bordered th { border-color:#3a3a50; }
}
:root[data-theme="dark"]  .card { background:#2b2b3a; border-color:#3a3a50; }
:root[data-theme="dark"]  .card-header { background:#232333; border-color:#3a3a50; }
:root[data-theme="dark"]  .table-light { --bs-table-bg:#232333; }
:root[data-theme="dark"]  .table-bordered td,
:root[data-theme="dark"]  .table-bordered th { border-color:#3a3a50; }
:root[data-theme="light"] .card { background:#fff; border-color:#dee2e6; }
:root[data-theme="light"] .card-header { background:#f8f9fa; border-color:#dee2e6; }
:root[data-theme="light"] .table-light { --bs-table-bg:#f8f9fa; }
:root[data-theme="light"] .table-bordered td,
:root[data-theme="light"] .table-bordered th { border-color:#dee2e6; }
</style>

<?php
$statusMap = [
    'registered' => ['bg-info text-dark', 'Registered'],
    'confirmed'  => ['bg-success',        'Confirmed'],
    'absent'     => ['bg-warning text-dark','Absent'],
    'completed'  => ['bg-primary',         'Completed'],
];
?>

<div class="content-header">
    <div class="d-flex align-items-center justify-content-end flex-wrap gap-2">
        <div class="d-flex gap-2 flex-wrap">
            <button class="btn btn-sm btn-outline-secondary" onclick="resetCentreForm(); mfp_modal('#modal-centre')">
                <i class="fas fa-school me-1"></i>Manage Centres
            </button>
            <button class="btn btn-sm btn-primary" onclick="resetCandForm(); mfp_modal('#modal-candidate')">
                <i class="fas fa-plus me-1"></i>Add Candidate
            </button>
            <a href="<?php echo base_url('knec/export_csv?exam_type='.urlencode($examType).'&exam_year='.urlencode($examYear)); ?>"
               class="btn btn-sm btn-success">
                <i class="fas fa-file-csv me-1"></i>Export CSV
            </a>
        </div>
    </div>
</div>

<div class="container-fluid">

    <!-- Filter -->
    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="get" action="<?php echo base_url('knec'); ?>" class="d-flex align-items-center gap-3 flex-wrap">
                <div class="d-flex align-items-center gap-2">
                    <label class="mb-0 fw-semibold">Exam Type:</label>
                    <select name="exam_type" class="form-select form-select-sm" style="width:auto">
                        <?php foreach (['KCPE','KCSE','CBC_Grade9'] as $t): ?>
                        <option value="<?php echo $t; ?>" <?php echo $examType === $t ? 'selected' : ''; ?>><?php echo $t; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <label class="mb-0 fw-semibold">Year:</label>
                    <select name="exam_year" class="form-select form-select-sm" style="width:auto">
                        <?php for ($y = date('Y') + 1; $y >= date('Y') - 5; $y--): ?>
                        <option value="<?php echo $y; ?>" <?php echo (string)$examYear === (string)$y ? 'selected' : ''; ?>><?php echo $y; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-sm btn-secondary"><i class="fas fa-filter me-1"></i>Filter</button>
            </form>
        </div>
    </div>

    <!-- Candidates Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0" id="knec-table">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Index No.</th>
                            <th>Name</th>
                            <th>UPI</th>
                            <th>Adm No.</th>
                            <th>Class</th>
                            <th>Centre</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($candidates)): ?>
                        <tr><td colspan="9" class="text-center py-4 text-muted">No candidates registered for <?php echo html_escape($examType); ?> <?php echo html_escape($examYear); ?>.</td></tr>
                        <?php else: foreach ($candidates as $i => $c):
                            [$badgeCls, $badgeLbl] = $statusMap[$c['registration_status']] ?? ['bg-secondary', ucfirst($c['registration_status'] ?? '')];
                        ?>
                        <tr>
                            <td><?php echo $i + 1; ?></td>
                            <td><strong><?php echo html_escape($c['index_number']); ?></strong></td>
                            <td><?php echo html_escape($c['student_name'] ?? ''); ?></td>
                            <td class="text-muted small"><?php echo html_escape($c['upi_number'] ?? '—'); ?></td>
                            <td><?php echo html_escape($c['register_no'] ?? ''); ?></td>
                            <td><?php echo html_escape(trim(($c['class'] ?? '') . ' ' . ($c['section_name'] ?? ''))); ?></td>
                            <td class="small"><?php
                                $ctr = trim(($c['centre_code'] ?? '') . (!empty($c['centre_name']) ? ' — ' . $c['centre_name'] : ''));
                                echo html_escape($ctr ?: '—');
                            ?></td>
                            <td><span class="badge <?php echo $badgeCls; ?>"><?php echo $badgeLbl; ?></span></td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-xs btn-outline-warning" title="Edit" onclick="editCand(this)"
                                        data-json="<?php echo htmlspecialchars(json_encode([
                                            'id'                  => (int)$c['id'],
                                            'student_id'          => (int)$c['student_id'],
                                            'centre_id'           => (int)$c['centre_id'],
                                            'index_number'        => $c['index_number'],
                                            'exam_year'           => $c['exam_year'],
                                            'exam_type'           => $c['exam_type'],
                                            'registration_status' => $c['registration_status'],
                                        ]), ENT_QUOTES); ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <?php echo btn_delete('knec/delete_candidate/' . $c['id']); ?>
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

<!-- Add / Edit Candidate Modal -->
<div id="modal-candidate" class="mfp-hide">
    <div class="card mb-0" style="min-width:520px; max-width:600px;">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0" id="cand-modal-title"><i class="fas fa-plus-circle me-2"></i>Register KNEC Candidate</h5>
            <button type="button" class="btn-close ms-2" onclick="$.magnificPopup.close()" title="Close"></button>
        </div>
        <div class="card-body">
            <?php echo form_open('knec/save_candidate', ['class' => 'frm-submit', 'id' => 'cand-form']); ?>
            <input type="hidden" name="id" id="cand-edit-id" value="">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Student <span class="text-danger">*</span></label>
                    <select name="student_id" class="form-select" data-plugin-selectTwo data-width="100%">
                        <option value="">— Select Student —</option>
                        <?php foreach ($students as $s): ?>
                        <option value="<?php echo $s['student_id']; ?>">
                            <?php echo html_escape($s['first_name'] . ' ' . $s['last_name'] . ' (' . $s['register_no'] . ')' . (!empty($s['upi_number']) ? ' · UPI:' . $s['upi_number'] : '')); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Exam Type <span class="text-danger">*</span></label>
                    <select name="exam_type" class="form-select">
                        <?php foreach (['KCPE','KCSE','CBC_Grade9'] as $t): ?>
                        <option value="<?php echo $t; ?>" <?php echo $examType === $t ? 'selected' : ''; ?>><?php echo $t; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Exam Year <span class="text-danger">*</span></label>
                    <input type="number" name="exam_year" class="form-control" value="<?php echo (int)$examYear; ?>" min="2020" max="2030">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Index Number <span class="text-danger">*</span></label>
                    <input type="text" name="index_number" class="form-control" placeholder="e.g. 12345678">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Exam Centre <span class="text-danger">*</span></label>
                    <select name="centre_id" class="form-select">
                        <option value="">— Select Centre —</option>
                        <?php foreach ($centres as $ce): ?>
                        <option value="<?php echo $ce['id']; ?>"><?php echo html_escape($ce['centre_code'] . ' — ' . $ce['centre_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Status</label>
                    <select name="registration_status" class="form-select">
                        <option value="registered">Registered</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="absent">Absent</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
            </div>
            <div class="mt-3 text-end">
                <button type="button" class="btn btn-secondary me-1" onclick="$.magnificPopup.close()">Cancel</button>
                <button type="submit" class="btn btn-primary" id="cand-submit-btn"><i class="fas fa-save me-1"></i>Save Candidate</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<!-- Manage Centres Modal -->
<div id="modal-centre" class="mfp-hide">
    <div class="card mb-0" style="min-width:540px; max-width:640px;">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0" id="centre-modal-title"><i class="fas fa-school me-2"></i>Add Exam Centre</h5>
            <button type="button" class="btn-close ms-2" onclick="$.magnificPopup.close()" title="Close"></button>
        </div>
        <div class="card-body">
            <?php echo form_open('knec/save_centre', ['class' => 'frm-submit', 'id' => 'centre-form']); ?>
            <input type="hidden" name="id" id="centre-edit-id" value="">
            <div class="row g-3">
                <div class="col-sm-4">
                    <label class="form-label">Centre Code <span class="text-danger">*</span></label>
                    <input type="text" name="centre_code" class="form-control" placeholder="e.g. 123456">
                </div>
                <div class="col-sm-8">
                    <label class="form-label">Centre Name <span class="text-danger">*</span></label>
                    <input type="text" name="centre_name" class="form-control" placeholder="e.g. Nairobi Primary School">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Exam Type <span class="text-danger">*</span></label>
                    <select name="exam_type" class="form-select">
                        <?php foreach (['KCPE','KCSE','CBC_Grade9'] as $t): ?>
                        <option value="<?php echo $t; ?>" <?php echo $examType === $t ? 'selected' : ''; ?>><?php echo $t; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="mt-3 text-end">
                <button type="button" class="btn btn-secondary me-1" onclick="resetCentreForm()">Clear</button>
                <button type="submit" class="btn btn-primary" id="centre-submit-btn"><i class="fas fa-save me-1"></i>Save Centre</button>
            </div>
            <?php echo form_close(); ?>

            <?php if (!empty($centres)): ?>
            <hr class="my-3">
            <p class="fw-semibold small mb-2 text-muted">Existing Centres (<?php echo html_escape($examType); ?>)</p>
            <div class="table-responsive">
                <table class="table table-sm table-bordered mb-0">
                    <thead class="table-light">
                        <tr><th>Code</th><th>Name</th><th>Type</th><th></th></tr>
                    </thead>
                    <tbody>
                    <?php foreach ($centres as $ce): ?>
                    <tr>
                        <td><?php echo html_escape($ce['centre_code']); ?></td>
                        <td><?php echo html_escape($ce['centre_name']); ?></td>
                        <td><?php echo html_escape($ce['exam_type']); ?></td>
                        <td>
                            <div class="d-flex gap-1">
                            <button type="button" class="btn btn-xs btn-outline-warning" title="Edit" onclick="editCentre(this)"
                                data-json="<?php echo htmlspecialchars(json_encode([
                                    'id'          => (int)$ce['id'],
                                    'centre_code' => $ce['centre_code'],
                                    'centre_name' => $ce['centre_name'],
                                    'exam_type'   => $ce['exam_type'],
                                ]), ENT_QUOTES); ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                            <?php echo btn_delete('knec/delete_centre/' . $ce['id']); ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function resetCandForm() {
    var f = document.getElementById('cand-form');
    f.reset();
    document.getElementById('cand-edit-id').value = '';
    document.getElementById('cand-modal-title').innerHTML = '<i class="fas fa-plus-circle me-2"></i>Register KNEC Candidate';
    document.getElementById('cand-submit-btn').innerHTML = '<i class="fas fa-save me-1"></i>Save Candidate';
    $('#cand-form [name="student_id"]').val('').trigger('change');
}
function editCand(btn) {
    var d = JSON.parse(btn.getAttribute('data-json'));
    var f = document.getElementById('cand-form');
    f.reset();
    document.getElementById('cand-edit-id').value = d.id;
    f.querySelector('[name="exam_type"]').value             = d.exam_type           || 'KCPE';
    f.querySelector('[name="exam_year"]').value             = d.exam_year           || '';
    f.querySelector('[name="index_number"]').value          = d.index_number        || '';
    f.querySelector('[name="centre_id"]').value             = d.centre_id           || '';
    f.querySelector('[name="registration_status"]').value   = d.registration_status || 'registered';
    $('#cand-form [name="student_id"]').val(d.student_id || '').trigger('change');
    document.getElementById('cand-modal-title').innerHTML = '<i class="fas fa-edit me-2"></i>Edit KNEC Candidate';
    document.getElementById('cand-submit-btn').innerHTML  = '<i class="fas fa-save me-1"></i>Save Changes';
    mfp_modal('#modal-candidate');
}
function resetCentreForm() {
    var f = document.getElementById('centre-form');
    f.reset();
    document.getElementById('centre-edit-id').value = '';
    document.getElementById('centre-modal-title').innerHTML = '<i class="fas fa-school me-2"></i>Add Exam Centre';
    document.getElementById('centre-submit-btn').innerHTML  = '<i class="fas fa-save me-1"></i>Save Centre';
}
function editCentre(btn) {
    var d = JSON.parse(btn.getAttribute('data-json'));
    var f = document.getElementById('centre-form');
    f.reset();
    document.getElementById('centre-edit-id').value = d.id;
    f.querySelector('[name="centre_code"]').value = d.centre_code || '';
    f.querySelector('[name="centre_name"]').value = d.centre_name || '';
    f.querySelector('[name="exam_type"]').value   = d.exam_type   || 'KCPE';
    document.getElementById('centre-modal-title').innerHTML = '<i class="fas fa-edit me-2"></i>Edit Exam Centre';
    document.getElementById('centre-submit-btn').innerHTML  = '<i class="fas fa-save me-1"></i>Save Changes';
}
$(function(){
    $('#knec-table').DataTable({
        order: [[1,'asc']],
        pageLength: 50,
        columnDefs: [{ orderable: false, targets: [8] }]
    });
});
</script>
