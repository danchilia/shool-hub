<div class="content-header">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <a href="<?php echo base_url('assets'); ?>" class="text-muted small"><i class="fas fa-arrow-left me-1"></i>Asset Register</a>
            <h4 class="mb-0 mt-1"><i class="fas fa-tools me-2 text-warning"></i>Maintenance: <?php echo html_escape($asset->name); ?></h4>
        </div>
        <button class="btn btn-sm btn-warning" onclick="mfp_modal('#modal-maint')">
            <i class="fas fa-plus me-1"></i>Log Maintenance
        </button>
    </div>
</div>

<div class="container-fluid">
    <div class="alert alert-light border d-flex gap-4 py-2 mb-4">
        <span><strong>Tag:</strong> <?php echo html_escape($asset->asset_tag); ?></span>
        <span><strong>Condition:</strong> <span class="badge cond-<?php echo $asset->condition_status; ?>"><?php echo ucfirst($asset->condition_status); ?></span></span>
        <?php if ($asset->serial_no): ?><span><strong>S/N:</strong> <?php echo html_escape($asset->serial_no); ?></span><?php endif; ?>
        <?php if ($asset->location): ?><span><strong>Location:</strong> <?php echo html_escape($asset->location); ?></span><?php endif; ?>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0" id="maint-table">
                <thead class="table-light">
                    <tr><th>#</th><th>Date</th><th>Type</th><th>Done By</th><th>Description</th><th>Cost (KES)</th><th>Next Due</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <?php if (empty($logs)): ?>
                    <tr><td colspan="8" class="text-center py-4 text-muted">No maintenance records yet.</td></tr>
                    <?php else: foreach ($logs as $i => $l): ?>
                    <tr>
                        <td><?php echo $i+1; ?></td>
                        <td><?php echo date('d M Y', strtotime($l->maintenance_date)); ?></td>
                        <td><span class="badge" style="background:#e0e7ff;color:#3730a3;"><?php echo ucfirst(str_replace('_',' ',$l->maintenance_type)); ?></span></td>
                        <td><?php echo html_escape($l->done_by); ?></td>
                        <td class="small"><?php echo html_escape($l->description); ?></td>
                        <td><?php echo $l->cost ? number_format($l->cost, 2) : '—'; ?></td>
                        <td class="small"><?php echo $l->next_due_date ? date('d M Y',strtotime($l->next_due_date)) : '—'; ?></td>
                        <td><?php echo btn_delete('assets/delete_maintenance/'.$l->id); ?></td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Log Maintenance Modal -->
<div id="modal-maint" class="mfp-hide">
    <div class="card mb-0" style="min-width:500px; max-width:580px;">
        <div class="card-header d-flex align-items-center justify-content-between"><h5 class="mb-0"><i class="fas fa-tools me-2"></i>Log Maintenance</h5><button type="button" class="btn-close ms-2" onclick="$.magnificPopup.close()" title="Close"></button></div>
        <div class="card-body">
            <?php echo form_open('assets/save_maintenance', ['class'=>'frm-submit']); ?>
            <input type="hidden" name="asset_id" value="<?php echo $asset->id; ?>">
            <div class="row g-3">
                <div class="col-sm-6">
                    <label class="form-label">Type <span class="text-danger">*</span></label>
                    <select name="maintenance_type" class="form-select" required>
                        <option value="service">Service</option>
                        <option value="repair">Repair</option>
                        <option value="inspection">Inspection</option>
                        <option value="replacement">Replacement</option>
                    </select>
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Date <span class="text-danger">*</span></label>
                    <input type="date" name="maintenance_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Done By</label>
                    <input type="text" name="done_by" class="form-control" placeholder="Technician name">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Cost (KES)</label>
                    <input type="number" name="cost" class="form-control" step="0.01" min="0">
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="2"></textarea>
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Next Due Date</label>
                    <input type="date" name="next_due_date" class="form-control">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Condition After</label>
                    <select name="condition_after" class="form-select">
                        <option value="">-- Unchanged --</option>
                        <option value="good">Good</option>
                        <option value="fair">Fair</option>
                        <option value="poor">Poor</option>
                        <option value="damaged">Damaged</option>
                        <option value="disposed">Disposed</option>
                    </select>
                </div>
            </div>
            <div class="mt-3 text-end"><button class="btn btn-primary" type="submit"><i class="fas fa-save me-1"></i>Save</button></div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<script>$(function(){ $('#maint-table').DataTable({order:[[1,'desc']],pageLength:25}); });</script>
