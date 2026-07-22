<div class="content-header">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h4 class="mb-0"><i class="fas fa-utensils me-2 text-success"></i>Canteen Menu Management</h4>
        <div class="d-flex gap-2">
            <a href="<?php echo base_url('canteen/pos'); ?>" class="btn btn-sm btn-success"><i class="fas fa-cash-register me-1"></i>POS</a>
            <button class="btn btn-sm btn-primary" onclick="mfp_modal('#modal-item')"><i class="fas fa-plus me-1"></i>Add Item</button>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="menu-table">
                    <thead class="table-light">
                        <tr><th>#</th><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Available</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <?php if (empty($items)): ?>
                        <tr><td colspan="7" class="text-center py-4 text-muted">No menu items yet.</td></tr>
                        <?php else: foreach ($items as $i => $item): ?>
                        <tr>
                            <td><?php echo $i+1; ?></td>
                            <td><strong><?php echo html_escape($item->name); ?></strong></td>
                            <td><?php echo html_escape($item->category); ?></td>
                            <td><strong>KES <?php echo number_format($item->price,2); ?></strong></td>
                            <td><?php echo $item->quantity_in_stock !== null ? $item->quantity_in_stock : '<span class="text-muted">—</span>'; ?></td>
                            <td>
                                <?php if ($item->is_available): ?>
                                <span class="badge" style="background:#d1fae5;color:#065f46;">Yes</span>
                                <?php else: ?>
                                <span class="badge" style="background:#fee2e2;color:#991b1b;">No</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo btn_delete('canteen/delete_item/'.$item->id); ?></td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="modal-item" class="mfp-hide">
    <div class="card mb-0" style="min-width:440px; max-width:500px;">
        <div class="card-header d-flex align-items-center justify-content-between"><h5 class="mb-0"><i class="fas fa-plus me-2"></i>Add Menu Item</h5><button type="button" class="btn-close ms-2" onclick="$.magnificPopup.close()" title="Close"></button></div>
        <div class="card-body">
            <?php echo form_open('canteen/save_item', ['class'=>'frm-submit']); ?>
            <div class="row g-3">
                <div class="col-sm-8">
                    <label class="form-label">Item Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" required placeholder="e.g. Chapati, Milk">
                </div>
                <div class="col-sm-4">
                    <label class="form-label">Price (KES) <span class="text-danger">*</span></label>
                    <input type="number" name="price" class="form-control" step="0.50" min="0" required>
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Category</label>
                    <input type="text" name="category" class="form-control" placeholder="Snacks, Drinks, Meals">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Stock Quantity</label>
                    <input type="number" name="quantity_in_stock" class="form-control" min="0" placeholder="Leave blank if unlimited">
                </div>
                <div class="col-12">
                    <div class="form-check">
                        <input type="checkbox" name="is_available" value="1" class="form-check-input" id="avail" checked>
                        <label class="form-check-label" for="avail">Available for sale</label>
                    </div>
                </div>
            </div>
            <div class="mt-3 text-end"><button class="btn btn-primary" type="submit"><i class="fas fa-save me-1"></i>Save</button></div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<script>$(function(){ $('#menu-table').DataTable({order:[[2,'asc'],[1,'asc']],pageLength:50}); });</script>
