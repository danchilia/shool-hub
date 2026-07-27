<style>
@media (prefers-color-scheme:dark) {
    .card        { background:#2b2b3a; border-color:#3a3a50; }
    .card-header { background:#232333; border-color:#3a3a50; }
}
:root[data-theme="dark"]  .card        { background:#2b2b3a; border-color:#3a3a50; }
:root[data-theme="dark"]  .card-header { background:#232333; border-color:#3a3a50; }
:root[data-theme="light"] .card        { background:#fff;    border-color:#dee2e6; }
:root[data-theme="light"] .card-header { background:#f8f9fa; border-color:#dee2e6; }
</style>

<div class="container-fluid">
    <div class="content-header mb-3">
        <a href="<?php echo base_url('accounting'); ?>" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i><?php echo translate('account') . ' ' . translate('list'); ?>
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h6 class="mb-0"><i class="far fa-edit me-2"></i><?php echo translate('edit') . ' ' . translate('account'); ?></h6>
        </div>
        <div class="card-body">
            <?php echo form_open($this->uri->uri_string(), array('class' => 'frm-submit')); ?>
            <input type="hidden" name="account_id" value="<?php echo html_escape($account['id']); ?>">
            <div class="row">
                <?php if (is_superadmin_loggedin()): ?>
                <div class="col-md-9 offset-md-3 mb-3">
                    <div class="row">
                        <label class="col-md-4 col-form-label"><?php echo translate('branch'); ?> <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <?php
                            $arrayBranch = $this->app_lib->getSelectList('branch');
                            echo form_dropdown("branch_id", $arrayBranch, $account['branch_id'], "class='form-control' id='branch_id' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                            ?>
                            <span class="error"></span>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <div class="col-md-9 offset-md-3 mb-3">
                    <div class="row">
                        <label class="col-md-4 col-form-label"><?php echo translate('account') . ' ' . translate('name'); ?> <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="account_name" value="<?php echo html_escape($account['name']); ?>">
                            <span class="error"></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 offset-md-3 mb-3">
                    <div class="row">
                        <label class="col-md-4 col-form-label"><?php echo translate('account') . ' ' . translate('number'); ?></label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="account_number" value="<?php echo html_escape($account['number']); ?>">
                            <span class="error"></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 offset-md-3 mb-4">
                    <div class="row">
                        <label class="col-md-4 col-form-label"><?php echo translate('description'); ?></label>
                        <div class="col-md-8">
                            <textarea class="form-control" name="description" rows="3"><?php echo html_escape($account['description']); ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 offset-md-5">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-1"></i><?php echo translate('update'); ?>
                    </button>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
