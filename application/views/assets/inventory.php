<style>
.inv-low { background:#fee2e2; color:#991b1b; }
.inv-ok  { background:#d1fae5; color:#065f46; }
</style>

<div class="content-header">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h4 class="mb-0"><i class="fas fa-warehouse me-2 text-info"></i>Inventory Management</h4>
        <div class="d-flex gap-2">
            <a href="<?php echo base_url('assets'); ?>" class="btn btn-sm btn-outline-secondary"><i class="fas fa-boxes me-1"></i>Assets</a>
            <button class="btn btn-sm btn-primary" onclick="mfp_modal('#modal-item')"><i class="fas fa-plus me-1"></i>Add Item</button>
        </div>
    </div>
</div>

<div class="container-fluid">
    <?php $lowStock = array_filter($items, fn($i) => $i->quantity_in_stock <= $i->reorder_level); ?>
    <?php if ($lowStock): ?>
    <div class="alert alert-warning py-2 mb-3">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong><?php echo count($lowStock); ?> item(s) at or below reorder level.</strong>
    </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="inv-table">
                    <thead class="table-light">
                        <tr>
                            <th>#</th><th>Item Name</th><th>Category</th><th>Unit</th>
                            <th>In Stock</th><th>Reorder Level</th><th>Unit Cost</th>
                            <th>Location</th><th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($items)): ?>
                        <tr><td colspan="9" class="text-center py-4 text-muted">No inventory items yet.</td></tr>
                        <?php else: foreach ($items as $i => $item): ?>
                        <tr>
                            <td><?php echo $i+1; ?></td>
                            <td><strong><?php echo html_escape($item->name); ?></strong></td>
                            <td><?php echo html_escape($item->category); ?></td>
                            <td><?php echo html_escape($item->unit); ?></td>
                            <td>
                                <span class="badge <?php echo $item->quantity_in_stock <= $item->reorder_level ? 'inv-low' : 'inv-ok'; ?>">
                                    <?php echo $item->quantity_in_stock; ?>
                                </span>
                            </td>
                            <td class="text-muted"><?php echo $item->reorder_level; ?></td>
                            <td><?php echo $item->unit_cost ? 'KES '.number_format($item->unit_cost,2) : '—'; ?></td>
                            <td class="small"><?php echo html_escape($item->location); ?></td>
                            <td>
                                <button class="btn btn-xs btn-outline-success" title="Stock In/Out"
                                        onclick="openTx(<?php echo $item->id; ?>, '<?php echo html_escape(addslashes($item->name)); ?>', <?php echo $item->quantity_in_stock; ?>)">
                                    <i class="fas fa-exchange-alt"></i>
                                </button>
                                <?php echo btn_delete('assets/delete_item/'.$item->id); ?>
                            </td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Item Modal -->
<div id="modal-item" class="mfp-hide">
    <div class="card mb-0" style="min-width:500px; max-width:560px;">
        <div class="card-header d-flex align-items-center justify-content-between"><h5 class="mb-0"><i class="fas fa-plus me-2"></i>Add Inventory Item</h5><button type="button" class="btn-close ms-2" onclick="$.magnificPopup.close()" title="Close"></button></div>
        <div class="card-body">
            <?php echo form_open('assets/save_item', ['class'=>'frm-submit']); ?>
            <div class="row g-3">
                <div class="col-sm-8">
                    <label class="form-label">Item Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-sm-4">
                    <label class="form-label">Unit</label>
                    <input type="text" name="unit" class="form-control" placeholder="pcs / kg / ream">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Category</label>
                    <input type="text" name="category" class="form-control" placeholder="Stationery, Cleaning...">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Initial Quantity</label>
                    <input type="number" name="quantity_in_stock" class="form-control" value="0" min="0">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Reorder Level</label>
                    <input type="number" name="reorder_level" class="form-control" value="5" min="0">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Unit Cost (KES)</label>
                    <input type="number" name="unit_cost" class="form-control" step="0.01" min="0">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Supplier</label>
                    <input type="text" name="supplier" class="form-control">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Storage Location</label>
                    <input type="text" name="location" class="form-control">
                </div>
            </div>
            <div class="mt-3 text-end"><button class="btn btn-primary" type="submit"><i class="fas fa-save me-1"></i>Save</button></div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<!-- Stock Transaction Modal -->
<div id="modal-tx" class="mfp-hide">
    <div class="card mb-0" style="min-width:400px; max-width:460px;">
        <div class="card-header d-flex align-items-center justify-content-between"><h5 class="mb-0" id="tx-title"><i class="fas fa-exchange-alt me-2"></i>Stock Transaction</h5><button type="button" class="btn-close ms-2" onclick="$.magnificPopup.close()" title="Close"></button></div>
        <div class="card-body">
            <?php echo form_open('assets/stock_transaction', ['class'=>'frm-submit']); ?>
            <input type="hidden" name="item_id" id="tx-item-id">
            <div class="mb-3">
                <label class="form-label">Transaction Type</label>
                <select name="transaction_type" class="form-select" required>
                    <option value="stock_in">Stock In (Received)</option>
                    <option value="stock_out">Stock Out (Issued)</option>
                    <option value="adjustment">Adjustment (Set Quantity)</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Quantity <span class="text-danger">*</span></label>
                <input type="number" name="quantity" class="form-control" required min="1">
                <div class="form-text">Current stock: <strong id="tx-current"></strong></div>
            </div>
            <div class="mb-3">
                <label class="form-label">Reference No.</label>
                <input type="text" name="reference_no" class="form-control" placeholder="LPO, Requisition no.">
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <input type="text" name="description" class="form-control">
            </div>
            <div class="text-end"><button class="btn btn-success" type="submit"><i class="fas fa-check me-1"></i>Save Transaction</button></div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
$(function(){ $('#inv-table').DataTable({order:[[1,'asc']],pageLength:25,columnDefs:[{orderable:false,targets:[8]}]}); });
function openTx(id, name, stock) {
    document.getElementById('tx-item-id').value = id;
    document.getElementById('tx-title').innerHTML = '<i class="fas fa-exchange-alt me-2"></i>'+name;
    document.getElementById('tx-current').textContent = stock;
    mfp_modal('#modal-tx');
}
</script>
