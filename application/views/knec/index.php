<?php
$candidates = isset($candidates) ? $candidates : array();
$centres    = isset($centres)    ? $centres    : array();
$students   = isset($students)   ? $students   : array();
$examType   = isset($examType)   ? $examType   : 'KCPE';
$examYear   = isset($examYear)   ? $examYear   : date('Y');
$statusBadge = array(
    'registered' => 'badge-info',
    'confirmed'  => 'badge-success',
    'absent'     => 'badge-warning',
    'completed'  => 'badge-primary',
);
?>
<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <div class="panel-btn">
                    <button class="btn btn-default btn-circle" onclick="mfp_modal('#add-candidate-modal')"><i class="fas fa-plus"></i> Add Candidate</button>
                    <button class="btn btn-default btn-circle" onclick="mfp_modal('#add-centre-modal')"><i class="fas fa-school"></i> Add Centre</button>
                    <a href="<?= base_url('knec/export_csv?exam_type=' . $examType . '&exam_year=' . $examYear) ?>" class="btn btn-default btn-circle"><i class="fas fa-download"></i> Export CSV</a>
                </div>
                <h4 class="panel-title"><i class="fas fa-id-card"></i> KNEC Index Numbers — <?= $examType ?> <?= $examYear ?></h4>
            </header>
            <div class="panel-body">
                <!-- Filter -->
                <?php echo form_open('knec', array('method' => 'get')); ?>
                <div class="row mb-sm">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Exam Type</label>
                            <select name="exam_type" class="form-control">
                                <?php foreach (['KCPE','KCSE','CBC_Grade9'] as $t): ?>
                                <option value="<?= $t ?>" <?= $examType == $t ? 'selected' : '' ?>><?= $t ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Year</label>
                            <select name="exam_year" class="form-control">
                                <?php for ($y = date('Y') + 1; $y >= date('Y') - 5; $y--): ?>
                                <option value="<?= $y ?>" <?= $examYear == $y ? 'selected' : '' ?>><?= $y ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group"><label>&nbsp;</label><button type="submit" class="btn btn-default btn-block"><i class="fas fa-filter"></i> Filter</button></div>
                    </div>
                </div>
                <?php echo form_close(); ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-condensed table-hover table-export">
                        <thead>
                            <tr><th>#</th><th>Index No.</th><th>Name</th><th>UPI</th><th>Adm No.</th><th>Class</th><th>Centre</th><th>Status</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            <?php if (empty($candidates)): ?>
                            <tr><td colspan="9" class="text-center text-muted">No candidates registered for <?= $examType ?> <?= $examYear ?>.</td></tr>
                            <?php endif; ?>
                            <?php foreach ($candidates as $i => $c): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><strong><?= htmlspecialchars($c['index_number']) ?></strong></td>
                                <td><?= htmlspecialchars($c['student_name'] ?? '') ?></td>
                                <td><?= htmlspecialchars($c['upi_number'] ?? '—') ?></td>
                                <td><?= htmlspecialchars($c['register_no'] ?? '') ?></td>
                                <td><?= htmlspecialchars(($c['class'] ?? '') . ' ' . ($c['section_name'] ?? '')) ?></td>
                                <td><?= htmlspecialchars($c['centre_code'] ?? '') ?> — <?= htmlspecialchars($c['centre_name'] ?? '') ?></td>
                                <td><span class="badge <?= $statusBadge[$c['registration_status']] ?? 'badge-secondary' ?>"><?= ucfirst($c['registration_status']) ?></span></td>
                                <td><?= btn_delete('knec/delete_candidate/' . $c['id']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Add Candidate Modal -->
<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="add-candidate-modal">
    <section class="panel">
        <header class="panel-heading"><h4 class="panel-title">Register KNEC Candidate</h4><button type="button" class="close" onclick="$.magnificPopup.close()" style="float:right;font-size:1.4rem;line-height:1;opacity:.7;" title="Close">&times;</button></header>
        <?php echo form_open('knec/save_candidate', array('class' => 'frm-submit')); ?>
        <input type="hidden" name="id" value="0">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12"><div class="form-group"><label>Student <span class="required">*</span></label>
                    <select name="student_id" class="form-control" data-plugin-selectTwo data-width="100%">
                        <option value="">— Select Student —</option>
                        <?php foreach ($students as $s): ?>
                        <option value="<?= $s['student_id'] ?>"><?= htmlspecialchars($s['first_name'] . ' ' . $s['last_name'] . ' (' . $s['register_no'] . ')' . (!empty($s['upi_number']) ? ' UPI:' . $s['upi_number'] : '')) ?></option>
                        <?php endforeach; ?>
                    </select><span class="error"></span>
                </div></div>
                <div class="col-md-6"><div class="form-group"><label>Exam Type <span class="required">*</span></label>
                    <select name="exam_type" class="form-control">
                        <?php foreach (['KCPE','KCSE','CBC_Grade9'] as $t): ?>
                        <option value="<?= $t ?>" <?= $examType == $t ? 'selected' : '' ?>><?= $t ?></option>
                        <?php endforeach; ?>
                    </select><span class="error"></span>
                </div></div>
                <div class="col-md-6"><div class="form-group"><label>Exam Year <span class="required">*</span></label>
                    <input type="number" name="exam_year" class="form-control" value="<?= $examYear ?>" min="2020" max="2030"><span class="error"></span>
                </div></div>
                <div class="col-md-6"><div class="form-group"><label>Index Number <span class="required">*</span></label>
                    <input type="text" name="index_number" class="form-control" placeholder="e.g. 12345678"><span class="error"></span>
                </div></div>
                <div class="col-md-6"><div class="form-group"><label>Exam Centre <span class="required">*</span></label>
                    <select name="centre_id" class="form-control">
                        <option value="">— Select Centre —</option>
                        <?php foreach ($centres as $ce): ?>
                        <option value="<?= $ce['id'] ?>"><?= htmlspecialchars($ce['centre_code'] . ' — ' . $ce['centre_name']) ?></option>
                        <?php endforeach; ?>
                    </select><span class="error"></span>
                </div></div>
                <div class="col-md-6"><div class="form-group"><label>Status</label>
                    <select name="registration_status" class="form-control">
                        <option value="registered">Registered</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="absent">Absent</option>
                        <option value="completed">Completed</option>
                    </select>
                </div></div>
            </div>
        </div>
        <footer class="panel-footer"><div class="text-right">
            <button type="button" class="btn btn-default modal-dismiss">Cancel</button>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
        </div></footer>
        <?php echo form_close(); ?>
    </section>
</div>

<!-- Add Centre Modal -->
<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="add-centre-modal">
    <section class="panel">
        <header class="panel-heading"><h4 class="panel-title">Add Exam Centre</h4><button type="button" class="close" onclick="$.magnificPopup.close()" style="float:right;font-size:1.4rem;line-height:1;opacity:.7;" title="Close">&times;</button></header>
        <?php echo form_open('knec/save_centre', array('class' => 'frm-submit')); ?>
        <input type="hidden" name="id" value="0">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4"><div class="form-group"><label>Centre Code <span class="required">*</span></label><input type="text" name="centre_code" class="form-control" placeholder="e.g. 123456"><span class="error"></span></div></div>
                <div class="col-md-8"><div class="form-group"><label>Centre Name <span class="required">*</span></label><input type="text" name="centre_name" class="form-control" placeholder="e.g. Nairobi Primary School"><span class="error"></span></div></div>
                <div class="col-md-6"><div class="form-group"><label>Exam Type <span class="required">*</span></label>
                    <select name="exam_type" class="form-control">
                        <?php foreach (['KCPE','KCSE','CBC_Grade9'] as $t): ?>
                        <option value="<?= $t ?>" <?= $examType == $t ? 'selected' : '' ?>><?= $t ?></option>
                        <?php endforeach; ?>
                    </select>
                </div></div>
            </div>
        </div>
        <footer class="panel-footer"><div class="text-right">
            <button type="button" class="btn btn-default modal-dismiss">Cancel</button>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Centre</button>
        </div></footer>
        <?php echo form_close(); ?>
    </section>
</div>
