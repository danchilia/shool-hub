<style>
.cond-good    { background:#d1fae5; color:#065f46; }
.cond-fair    { background:#fef3c7; color:#92400e; }
.cond-poor,
.cond-damaged { background:#fee2e2; color:#991b1b; }
.cond-disposed{ background:#f3f4f6; color:#6b7280; }
.asset-stat   { border-radius:8px; padding:14px 18px; flex:1; min-width:120px; }
</style>

<div class="content-header">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h4 class="mb-0"><i class="fas fa-boxes me-2 text-primary"></i>Asset Register</h4>
        <div class="d-flex gap-2">
            <a href="<?php echo base_url('assets/categories'); ?>" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-tags me-1"></i>Categories
            </a>
            <a href="<?php echo base_url('assets/inventory'); ?>" class="btn btn-sm btn-outline-info">
                <i class="fas fa-warehouse me-1"></i>Inventory
            </a>
            <button class="btn btn-sm btn-primary" onclick="mfp_modal('#modal-asset')">
                <i class="fas fa-plus me-1"></i>Add Asset
            </button>
        </div>
    </div>
</div>

<div class="container-fluid">
    <!-- Stats -->
    <div class="d-flex gap-3 flex-wrap mb-4">
        <div class="asset-stat" style="background:#dbeafe; color:#1e40af;">
            <div style="font-size:1.6rem; font-weight:700;"><?php echo $stats['total']; ?></div>
            <div style="font-size:.78rem;">Total Assets</div>
        </div>
        <div class="asset-stat" style="background:#d1fae5; color:#065f46;">
            <div style="font-size:1.6rem; font-weight:700;"><?php echo $stats['good']; ?></div>
            <div style="font-size:.78rem;">Good Condition</div>
        </div>
        <div class="asset-stat" style="background:#fef3c7; color:#92400e;">
            <div style="font-size:1.6rem; font-weight:700;"><?php echo $stats['fair']; ?></div>
            <div style="font-size:.78rem;">Fair Condition</div>
        </div>
        <div class="asset-stat" style="background:#fee2e2; color:#991b1b;">
            <div style="font-size:1.6rem; font-weight:700;"><?php echo $stats['damaged']; ?></div>
            <div style="font-size:.78rem;">Poor / Damaged</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" class="d-flex gap-3 flex-wrap align-items-center">
                <select name="category_id" class="form-select form-select-sm" style="width:180px">
                    <option value="">-- All Categories --</option>
                    <?php foreach ($categories as $c): ?>
                    <option value="<?php echo $c->id; ?>" <?php echo $filter_cat==$c->id?'selected':''; ?>><?php echo html_escape($c->name); ?></option>
                    <?php endforeach; ?>
                </select>
                <select name="condition" class="form-select form-select-sm" style="width:150px">
                    <option value="">-- All Conditions --</option>
                    <?php foreach (['good','fair','poor','damaged','disposed'] as $cnd): ?>
                    <option value="<?php echo $cnd; ?>" <?php echo $filter_cond===$cnd?'selected':''; ?>><?php echo ucfirst($cnd); ?></option>
                    <?php endforeach; ?>
                </select>
                <button class="btn btn-sm btn-secondary" type="submit">Filter</button>
                <a href="<?php echo base_url('assets'); ?>" class="btn btn-sm btn-link">Reset</a>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="assets-table">
                    <thead class="table-light">
                        <tr>
                            <th>Tag</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Brand/Model</th>
                            <th>Serial No.</th>
                            <th>Location</th>
                            <th>Condition</th>
                            <th>Warranty</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($assets)): ?>
                        <tr><td colspan="9" class="text-center py-4 text-muted">No assets found.</td></tr>
                        <?php else: foreach ($assets as $a): ?>
                        <tr>
                            <td><code><?php echo html_escape($a->asset_tag); ?></code></td>
                            <td><strong><?php echo html_escape($a->name); ?></strong></td>
                            <td><?php echo html_escape($a->category_name); ?></td>
                            <td><?php echo html_escape(trim($a->brand . ' ' . $a->model)); ?></td>
                            <td class="text-muted small"><?php echo html_escape($a->serial_no); ?></td>
                            <td><?php echo html_escape($a->location); ?></td>
                            <td>
                                <span class="badge cond-<?php echo $a->condition_status; ?>">
                                    <?php echo ucfirst($a->condition_status); ?>
                                </span>
                            </td>
                            <td class="small text-muted">
                                <?php
                                if ($a->warranty_expiry) {
                                    $exp = strtotime($a->warranty_expiry);
                                    $class = $exp < time() ? 'text-danger' : '';
                                    echo '<span class="'.$class.'">'.date('d M Y', $exp).'</span>';
                                } else { echo '—'; }
                                ?>
                            </td>
                            <td>
                                <a href="<?php echo base_url('assets/maintenance/'.$a->id); ?>" class="btn btn-xs btn-outline-warning" title="Maintenance Log">
                                    <i class="fas fa-tools"></i>
                                </a>
                                <?php echo btn_delete('assets/delete_asset/'.$a->id); ?>
                            </td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Asset Modal -->
<div id="modal-asset" class="mfp-hide">
    <div class="card mb-0" style="min-width:560px; max-width:640px; max-height:90vh; overflow-y:auto;">
        <div class="card-header d-flex align-items-center justify-content-between"><h5 class="mb-0"><i class="fas fa-plus me-2"></i>Register Asset</h5><button type="button" class="btn-close ms-2" onclick="$.magnificPopup.close()" title="Close"></button></div>
        <div class="card-body">
            <?php echo form_open('assets/save_asset', ['class'=>'frm-submit']); ?>
            <div class="row g-3">
                <div class="col-sm-6">
                    <label class="form-label">Asset Tag <span class="text-danger">*</span></label>
                    <input type="text" name="asset_tag" class="form-control" required placeholder="e.g. LAP-001">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" required placeholder="Dell Laptop">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-select" data-plugin-selectTwo data-width="100%">
                        <option value="">-- Select --</option>
                        <?php foreach ($categories as $c): ?>
                        <option value="<?php echo $c->id; ?>"><?php echo html_escape($c->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Condition</label>
                    <select name="condition_status" class="form-select">
                        <option value="good">Good</option>
                        <option value="fair">Fair</option>
                        <option value="poor">Poor</option>
                        <option value="damaged">Damaged</option>
                        <option value="disposed">Disposed</option>
                    </select>
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Brand</label>
                    <input type="text" name="brand" class="form-control">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Model</label>
                    <input type="text" name="model" class="form-control">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Serial No.</label>
                    <input type="text" name="serial_no" class="form-control">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Location</label>
                    <input type="text" name="location" class="form-control" placeholder="Lab 1 / Store Room">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Purchase Date</label>
                    <input type="date" name="purchase_date" class="form-control">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Purchase Cost (KES)</label>
                    <input type="number" name="purchase_cost" class="form-control" step="0.01" min="0">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Supplier</label>
                    <input type="text" name="supplier" class="form-control">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Warranty Expiry</label>
                    <input type="date" name="warranty_expiry" class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="mt-3 text-end">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save Asset</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
$(function(){ $('#assets-table').DataTable({order:[[0,'asc']], pageLength:25, columnDefs:[{orderable:false,targets:[8]}]}); });
</script>
