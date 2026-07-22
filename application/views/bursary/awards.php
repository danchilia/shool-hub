<?php
$programme = isset($programme) ? $programme : array();
$awards    = isset($awards)    ? $awards    : array();
$students  = isset($students)  ? $students  : array();
$statusBadge = array(
    'applied'   => 'badge-warning',
    'approved'  => 'badge-info',
    'disbursed' => 'badge-success',
    'rejected'  => 'badge-danger',
);
?>
<div class="row">
    <div class="col-md-12">
        <section class="panel" style="border-top:3px solid #27ae60">
            <div class="panel-body">
                <h4 class="mb-xs"><?= htmlspecialchars($programme['name'] ?? '') ?></h4>
                <small class="text-muted">Provider: <?= htmlspecialchars($programme['provider'] ?? '') ?> | Year: <?= htmlspecialchars($programme['academic_year'] ?? 'N/A') ?></small>
                <a href="<?= base_url('bursary') ?>" class="btn btn-default btn-sm pull-right"><i class="fas fa-arrow-left"></i> Back</a>
            </div>
        </section>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <div class="panel-btn">
                    <button class="btn btn-default btn-circle" onclick="mfp_modal('#add-award-modal')">
                        <i class="fas fa-plus"></i> Add Award
                    </button>
                </div>
                <h4 class="panel-title"><i class="fas fa-award"></i> Beneficiaries</h4>
            </header>
            <div class="panel-body">
                <?php if (empty($awards)): ?>
                    <p class="text-center text-muted mt-md">No awards yet.</p>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed table-hover table-export">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student</th>
                                <th>Reg. No.</th>
                                <th>Class</th>
                                <th>Awarded</th>
                                <th>Disbursed</th>
                                <th>Applied</th>
                                <th>Status</th>
                                <th>Remarks</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($awards as $i => $a): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= htmlspecialchars($a['student_name'] ?? '') ?></td>
                                <td><?= htmlspecialchars($a['register_no'] ?? '') ?></td>
                                <td><?= htmlspecialchars(($a['class'] ?? '') . ' ' . ($a['section_name'] ?? '')) ?></td>
                                <td>KES <?= number_format($a['amount_awarded'], 2) ?></td>
                                <td>KES <?= number_format($a['amount_disbursed'], 2) ?></td>
                                <td><?= $a['applied_date'] ? date('d M Y', strtotime($a['applied_date'])) : '—' ?></td>
                                <td><span class="badge <?= $statusBadge[$a['status']] ?? 'badge-secondary' ?>"><?= ucfirst($a['status']) ?></span></td>
                                <td><?= htmlspecialchars($a['remarks'] ?? '') ?></td>
                                <td><?= btn_delete('bursary/delete_award/' . $a['id']) ?></td>
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

<!-- Add Award Modal -->
<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="add-award-modal">
    <section class="panel">
        <header class="panel-heading"><h4 class="panel-title">Add Bursary Award</h4><button type="button" class="close" onclick="$.magnificPopup.close()" style="float:right;font-size:1.4rem;line-height:1;opacity:.7;" title="Close">&times;</button></header>
        <?php echo form_open('bursary/save_award', array('class' => 'frm-submit')); ?>
        <input type="hidden" name="id" value="0">
        <input type="hidden" name="programme_id" value="<?= $programme['id'] ?? '' ?>">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12"><div class="form-group"><label>Student <span class="required">*</span></label>
                    <select name="student_id" class="form-control" data-plugin-selectTwo data-width="100%">
                        <option value="">— Select Student —</option>
                        <?php foreach ($students as $s): ?>
                        <option value="<?= $s['student_id'] ?>"><?= htmlspecialchars($s['first_name'] . ' ' . $s['last_name'] . ' (' . $s['register_no'] . ')') ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="error"></span>
                </div></div>
                <div class="col-md-6"><div class="form-group"><label>Amount Awarded (KES) <span class="required">*</span></label><input type="number" name="amount_awarded" class="form-control" step="0.01" min="1"><span class="error"></span></div></div>
                <div class="col-md-6"><div class="form-group"><label>Amount Disbursed (KES)</label><input type="number" name="amount_disbursed" class="form-control" step="0.01" min="0" value="0"></div></div>
                <div class="col-md-6"><div class="form-group"><label>Applied Date</label><input type="date" name="applied_date" class="form-control"></div></div>
                <div class="col-md-6"><div class="form-group"><label>Disbursement Date</label><input type="date" name="disbursement_date" class="form-control"></div></div>
                <div class="col-md-6"><div class="form-group"><label>Status</label>
                    <select name="status" class="form-control">
                        <option value="applied">Applied</option>
                        <option value="approved">Approved</option>
                        <option value="disbursed">Disbursed</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div></div>
                <div class="col-md-12"><div class="form-group"><label>Remarks</label><textarea name="remarks" class="form-control" rows="2"></textarea></div></div>
            </div>
        </div>
        <footer class="panel-footer"><div class="text-right">
            <button type="button" class="btn btn-default modal-dismiss">Cancel</button>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Award</button>
        </div></footer>
        <?php echo form_close(); ?>
    </section>
</div>
