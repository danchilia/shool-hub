<div class="content-header">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h4 class="mb-0"><i class="fas fa-tags me-2 text-primary"></i>Asset Categories</h4>
        <div class="d-flex gap-2">
            <a href="<?php echo base_url('assets'); ?>" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Back to Assets</a>
            <button class="btn btn-sm btn-primary" onclick="mfp_modal('#modal-cat')"><i class="fas fa-plus me-1"></i>Add Category</button>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0" id="cat-table">
                <thead class="table-light">
                    <tr><th>#</th><th>Name</th><th>Description</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <?php if (empty($categories)): ?>
                    <tr><td colspan="4" class="text-center py-4 text-muted">No categories yet.</td></tr>
                    <?php else: foreach ($categories as $i => $c): ?>
                    <tr>
                        <td><?php echo $i+1; ?></td>
                        <td><strong><?php echo html_escape($c->name); ?></strong></td>
                        <td class="text-muted small"><?php echo html_escape($c->description); ?></td>
                        <td><?php echo btn_delete('assets/delete_category/'.$c->id); ?></td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modal-cat" class="mfp-hide">
    <div class="card mb-0" style="min-width:400px; max-width:480px;">
        <div class="card-header d-flex align-items-center justify-content-between"><h5 class="mb-0"><i class="fas fa-tag me-2"></i>New Category</h5><button type="button" class="btn-close ms-2" onclick="$.magnificPopup.close()" title="Close"></button></div>
        <div class="card-body">
            <?php echo form_open('assets/save_category', ['class'=>'frm-submit']); ?>
            <div class="mb-3">
                <label class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" required placeholder="e.g. Electronics, Furniture">
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="2"></textarea>
            </div>
            <div class="text-end"><button class="btn btn-primary" type="submit"><i class="fas fa-save me-1"></i>Save</button></div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<script>$(function(){ $('#cat-table').DataTable({pageLength:25}); });</script>
