<?php
$programmes = isset($programmes) ? $programmes : array();
$typeBadge = array(
    'government' => 'badge-primary',
    'ngcdf'      => 'badge-success',
    'private'    => 'badge-info',
    'church'     => 'badge-warning',
    'other'      => 'badge-secondary',
);
$statusBadge = array(
    'open'      => 'badge-success',
    'closed'    => 'badge-secondary',
    'disbursed' => 'badge-primary',
);
?>
<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <div class="panel-btn">
                    <button class="btn btn-default btn-circle" onclick="mfp_modal('#add-programme-modal')">
                        <i class="fas fa-plus"></i> New Programme
                    </button>
                </div>
                <h4 class="panel-title"><i class="fas fa-graduation-cap"></i> Bursary &amp; Scholarship Programmes</h4>
            </header>
            <div class="panel-body">
                <?php if (empty($programmes)): ?>
                    <p class="text-center text-muted mt-md">No programmes added yet. Click <strong>New Programme</strong> to start.</p>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed table-hover table-export">
                        <thead>
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
                            <?php foreach ($programmes as $i => $p): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><strong><?= htmlspecialchars($p['name']) ?></strong></td>
                                <td><?= htmlspecialchars($p['provider']) ?></td>
                                <td><span class="badge <?= $typeBadge[$p['provider_type']] ?? 'badge-secondary' ?>"><?= strtoupper($p['provider_type']) ?></span></td>
                                <td><?= htmlspecialchars($p['academic_year'] ?? '—') ?></td>
                                <td><?= !empty($p['total_allocation']) ? 'KES ' . number_format($p['total_allocation'], 2) : '—' ?></td>
                                <td><?= $p['beneficiaries'] ?></td>
                                <td><?= 'KES ' . number_format($p['total_awarded'], 2) ?></td>
                                <td><?= 'KES ' . number_format($p['total_disbursed'], 2) ?></td>
                                <td><span class="badge <?= $statusBadge[$p['status']] ?? 'badge-secondary' ?>"><?= ucfirst($p['status']) ?></span></td>
                                <td>
                                    <a href="<?= base_url('bursary/awards/' . $p['id']) ?>" class="btn btn-default btn-circle btn-xs" data-toggle="tooltip" data-original-title="Manage Awards">
                                        <i class="fas fa-users"></i>
                                    </a>
                                    <?= btn_delete('bursary/delete_programme/' . $p['id']) ?>
                                </td>
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

<!-- Add Programme Modal -->
<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="add-programme-modal">
    <section class="panel">
        <header class="panel-heading"><h4 class="panel-title">New Bursary / Scholarship Programme</h4><button type="button" class="close" onclick="$.magnificPopup.close()" style="float:right;font-size:1.4rem;line-height:1;opacity:.7;" title="Close">&times;</button></header>
        <?php echo form_open('bursary/save_programme', array('class' => 'frm-submit')); ?>
        <input type="hidden" name="id" value="0">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-8"><div class="form-group"><label>Programme Name <span class="required">*</span></label><input type="text" name="name" class="form-control" placeholder="e.g. NG-CDF Bursary 2025"><span class="error"></span></div></div>
                <div class="col-md-4"><div class="form-group"><label>Academic Year</label><input type="text" name="academic_year" class="form-control" placeholder="e.g. 2025/2026"></div></div>
                <div class="col-md-6"><div class="form-group"><label>Provider <span class="required">*</span></label><input type="text" name="provider" class="form-control" placeholder="e.g. Westlands Constituency"><span class="error"></span></div></div>
                <div class="col-md-6"><div class="form-group"><label>Provider Type <span class="required">*</span></label>
                    <select name="provider_type" class="form-control">
                        <option value="government">Government</option>
                        <option value="ngcdf">NG-CDF</option>
                        <option value="private">Private/Corporate</option>
                        <option value="church">Church/Religious</option>
                        <option value="other">Other</option>
                    </select>
                </div></div>
                <div class="col-md-6"><div class="form-group"><label>Total Allocation (KES)</label><input type="number" name="total_allocation" class="form-control" step="0.01" min="0"></div></div>
                <div class="col-md-6"><div class="form-group"><label>Application Deadline</label><input type="date" name="application_deadline" class="form-control"></div></div>
                <div class="col-md-6"><div class="form-group"><label>Status</label>
                    <select name="status" class="form-control">
                        <option value="open">Open</option>
                        <option value="closed">Closed</option>
                        <option value="disbursed">Disbursed</option>
                    </select>
                </div></div>
                <div class="col-md-12"><div class="form-group"><label>Description</label><textarea name="description" class="form-control" rows="3"></textarea></div></div>
            </div>
        </div>
        <footer class="panel-footer"><div class="text-right">
            <button type="button" class="btn btn-default modal-dismiss">Cancel</button>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
        </div></footer>
        <?php echo form_close(); ?>
    </section>
</div>
