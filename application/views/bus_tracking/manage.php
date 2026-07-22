<div class="content-header">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h4 class="mb-0"><i class="fas fa-bus me-2 text-primary"></i>Manage School Buses</h4>
        <div class="d-flex gap-2">
            <a href="<?php echo base_url('bus_tracking'); ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-map me-1"></i>Live Map</a>
            <button class="btn btn-sm btn-primary" onclick="mfp_modal('#modal-bus')"><i class="fas fa-plus me-1"></i>Add Bus</button>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0" id="buses-table">
                <thead class="table-light">
                    <tr><th>#</th><th>Bus Name</th><th>Reg No.</th><th>Capacity</th><th>Driver</th><th>Driver Phone</th><th>Status</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <?php if (empty($buses)): ?>
                    <tr><td colspan="8" class="text-center py-4 text-muted">No buses registered yet.</td></tr>
                    <?php else: foreach ($buses as $i => $bus): ?>
                    <tr>
                        <td><?php echo $i+1; ?></td>
                        <td><strong><?php echo html_escape($bus->bus_name); ?></strong></td>
                        <td><?php echo html_escape($bus->reg_number); ?></td>
                        <td><?php echo $bus->capacity ?: '—'; ?></td>
                        <td><?php echo html_escape($bus->driver_name ?: '—'); ?></td>
                        <td><?php echo html_escape($bus->driver_phone ?: '—'); ?></td>
                        <td>
                            <?php if ($bus->is_active): ?>
                            <span class="badge" style="background:#d1fae5;color:#065f46;">Active</span>
                            <?php else: ?>
                            <span class="badge" style="background:#f3f4f6;color:#6b7280;">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo btn_delete('bus_tracking/delete_bus/'.$bus->id); ?></td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header d-flex align-items-center justify-content-between"><h6 class="mb-0"><i class="fas fa-code me-2"></i>GPS Device Integration</h6></div>
        <div class="card-body">
            <p class="text-muted small mb-2">GPS devices or driver apps should send location updates to:</p>
            <code class="d-block bg-light p-2 rounded"><?php echo base_url('bus_tracking/update_location'); ?>?bus_id=1&lat=-1.286&lng=36.817&speed=40</code>
            <p class="text-muted small mt-2 mb-0">Recommended: update every 30 seconds. Supports GET and POST requests.</p>
        </div>
    </div>
</div>

<div id="modal-bus" class="mfp-hide">
    <div class="card mb-0" style="min-width:480px; max-width:560px;">
        <div class="card-header d-flex align-items-center justify-content-between"><h5 class="mb-0"><i class="fas fa-plus me-2"></i>Register Bus</h5><button type="button" class="btn-close ms-2" onclick="$.magnificPopup.close()" title="Close"></button></div>
        <div class="card-body">
            <?php echo form_open('bus_tracking/save_bus', ['class'=>'frm-submit']); ?>
            <div class="row g-3">
                <div class="col-sm-6">
                    <label class="form-label">Bus Name <span class="text-danger">*</span></label>
                    <input type="text" name="bus_name" class="form-control" required placeholder="e.g. Bus 1">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Reg Number <span class="text-danger">*</span></label>
                    <input type="text" name="reg_number" class="form-control" required placeholder="KXX 000A">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Capacity</label>
                    <input type="number" name="capacity" class="form-control" min="1">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Driver Name</label>
                    <input type="text" name="driver_name" class="form-control">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Driver Phone</label>
                    <input type="text" name="driver_phone" class="form-control">
                </div>
                <div class="col-sm-6">
                    <div class="form-check mt-4">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="bus-active" checked>
                        <label class="form-check-label" for="bus-active">Active</label>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label">Route Description</label>
                    <textarea name="route_description" class="form-control" rows="2" placeholder="e.g. Westlands → Kileleshwa → Lavington"></textarea>
                </div>
            </div>
            <div class="mt-3 text-end"><button class="btn btn-primary" type="submit"><i class="fas fa-save me-1"></i>Save</button></div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<script>$(function(){ $('#buses-table').DataTable({pageLength:25}); });</script>
