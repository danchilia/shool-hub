<?php $currency_symbol = $global_config['currency_symbol']; ?>
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
        <a href="<?php echo base_url('accounting/voucher_expense'); ?>" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i><?php echo translate('expense') . ' ' . translate('list'); ?>
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h6 class="mb-0"><i class="far fa-edit me-2"></i><?php echo translate('edit') . ' ' . translate('expense'); ?></h6>
        </div>
        <div class="card-body">
            <?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'frm-submit-data')); ?>
            <input type="hidden" name="voucher_type" value="expense">
            <input type="hidden" name="voucher_old_id" value="<?php echo html_escape($expense['id']); ?>">
            <div class="row">
                <?php if (is_superadmin_loggedin()): ?>
                <div class="col-md-9 offset-md-3 mb-3">
                    <div class="row">
                        <label class="col-md-4 col-form-label"><?php echo translate('branch'); ?> <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <?php
                            $arrayBranch = $this->app_lib->getSelectList('branch');
                            echo form_dropdown("branch_id", $arrayBranch, $expense['branch_id'], "class='form-control' id='branch_id' disabled data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                            ?>
                            <span class="error"></span>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <div class="col-md-9 offset-md-3 mb-3">
                    <div class="row">
                        <label class="col-md-4 col-form-label"><?php echo translate('account'); ?> <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <?php
                            $accounts_list = $this->app_lib->getSelectByBranch('accounts', $expense['branch_id']);
                            echo form_dropdown("account_id", $accounts_list, $expense['account_id'], "class='form-control' id='account_id' disabled data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                            ?>
                            <span class="error"></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 offset-md-3 mb-3">
                    <div class="row">
                        <label class="col-md-4 col-form-label"><?php echo translate('voucher') . ' ' . translate('head'); ?> <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <?php
                            $arrayVoucherHead = $this->app_lib->getSelectByBranch('voucher_head', $expense['branch_id'], false, array('type' => 'expense'));
                            echo form_dropdown("voucher_head_id", $arrayVoucherHead, $expense['voucher_head_id'], "class='form-control' id='voucher_head_id' data-plugin-selectTwo data-width='100%'");
                            ?>
                            <span class="error"></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 offset-md-3 mb-3">
                    <div class="row">
                        <label class="col-md-4 col-form-label"><?php echo translate('ref'); ?></label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="ref_no" value="<?php echo html_escape($expense['ref']); ?>">
                        </div>
                    </div>
                </div>
                <div class="col-md-9 offset-md-3 mb-3">
                    <div class="row">
                        <label class="col-md-4 col-form-label"><?php echo translate('amount'); ?> <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="amount" value="<?php echo html_escape($expense['amount']); ?>" disabled>
                            <span class="error"></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 offset-md-3 mb-3">
                    <div class="row">
                        <label class="col-md-4 col-form-label"><?php echo translate('date'); ?> <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="date" value="<?php echo set_value('date', $expense['date']); ?>" data-plugin-datepicker data-plugin-options='{"todayHighlight":true,"endDate":"+0d"}' readonly>
                            <span class="error"></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 offset-md-3 mb-3">
                    <div class="row">
                        <label class="col-md-4 col-form-label"><?php echo translate('pay_via'); ?></label>
                        <div class="col-md-8">
                            <?php
                            $payvia_list = $this->app_lib->getSelectList('payment_types');
                            echo form_dropdown("pay_via", $payvia_list, $expense['pay_via'], "class='form-control' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 offset-md-3 mb-3">
                    <div class="row">
                        <label class="col-md-4 col-form-label"><?php echo translate('description'); ?></label>
                        <div class="col-md-8">
                            <textarea class="form-control" name="description" rows="3"><?php echo html_escape($expense['description']); ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 offset-md-3 mb-4">
                    <div class="row">
                        <label class="col-md-4 col-form-label"><?php echo translate('attachment'); ?></label>
                        <div class="col-md-8">
                            <input type="file" name="attachment_file" class="dropify" data-height="70">
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
