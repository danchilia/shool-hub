<?php $currency_symbol = $global_config['currency_symbol']; ?>
<style>
@media (prefers-color-scheme:dark) {
    .card        { background:#2b2b3a; border-color:#3a3a50; }
    .card-header { background:#232333; border-color:#3a3a50; }
    .table-light { --bs-table-bg:#232333; }
}
:root[data-theme="dark"]  .card        { background:#2b2b3a; border-color:#3a3a50; }
:root[data-theme="dark"]  .card-header { background:#232333; border-color:#3a3a50; }
:root[data-theme="dark"]  .table-light { --bs-table-bg:#232333; }
:root[data-theme="light"] .card        { background:#fff;    border-color:#dee2e6; }
:root[data-theme="light"] .card-header { background:#f8f9fa; border-color:#dee2e6; }
:root[data-theme="light"] .table-light { --bs-table-bg:#f8f9fa; }
</style>

<div class="container-fluid">
    <div class="card">
        <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs px-3 pt-2">
                <li class="nav-item">
                    <a class="nav-link active" href="#list" data-bs-toggle="tab">
                        <i class="fas fa-list-ul me-1"></i><?php echo translate('expense') . ' ' . translate('list'); ?>
                    </a>
                </li>
                <?php if (get_permission('expense', 'is_add')): ?>
                <li class="nav-item">
                    <a class="nav-link" href="#create" data-bs-toggle="tab">
                        <i class="far fa-edit me-1"></i><?php echo translate('add') . ' ' . translate('expense'); ?>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="tab-content">
            <!-- List Tab -->
            <div id="list" class="tab-pane fade show active p-3">
                <div class="export_title"><?php echo translate('expense') . ' ' . translate('list'); ?></div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm table-export mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="50"><?php echo translate('sl'); ?></th>
                                <?php if (is_superadmin_loggedin()): ?>
                                <th><?php echo translate('branch'); ?></th>
                                <?php endif; ?>
                                <th><?php echo translate('account') . ' ' . translate('name'); ?></th>
                                <th><?php echo translate('voucher') . ' ' . translate('head'); ?></th>
                                <th><?php echo translate('ref_no'); ?></th>
                                <th><?php echo translate('description'); ?></th>
                                <th><?php echo translate('pay_via'); ?></th>
                                <th><?php echo translate('amount'); ?></th>
                                <th><?php echo translate('date'); ?></th>
                                <th><?php echo translate('action'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 1; foreach ($voucherlist as $row): ?>
                            <tr>
                                <td><?php echo $count++; ?></td>
                                <?php if (is_superadmin_loggedin()): ?>
                                <td><?php echo html_escape(get_type_name_by_id('branch', $row['branch_id'])); ?></td>
                                <?php endif; ?>
                                <td><?php echo (!empty($row['attachments']) ? '<i class="fas fa-paperclip me-1"></i>' : ''); ?><?php echo html_escape($row['ac_name']); ?></td>
                                <td><?php echo html_escape($row['v_head']); ?></td>
                                <td><?php echo html_escape($row['ref']); ?></td>
                                <td><?php echo html_escape($row['description']); ?></td>
                                <td><?php echo html_escape($row['via_name']); ?></td>
                                <td><?php echo $currency_symbol . $row['amount']; ?></td>
                                <td><?php echo _d($row['date']); ?></td>
                                <td class="text-nowrap">
                                    <?php if (get_permission('expense', 'is_edit')): ?>
                                    <a href="<?php echo base_url('accounting/voucher_expense_edit/' . $row['id']); ?>" class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="<?php echo translate('edit'); ?>">
                                        <i class="fas fa-pen-nib"></i>
                                    </a>
                                    <?php endif; ?>
                                    <?php if (get_permission('expense', 'is_delete')): ?>
                                    <?php echo btn_delete('accounting/voucher_delete/' . $row['id']); ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Create Tab -->
            <?php if (get_permission('expense', 'is_add')): ?>
            <div class="tab-pane fade p-3" id="create">
                <?php echo form_open_multipart('accounting/voucher_save', array('class' => 'frm-submit-data')); ?>
                <input type="hidden" name="voucher_type" value="expense">
                <div class="row">
                    <?php if (is_superadmin_loggedin()): ?>
                    <div class="col-md-9 offset-md-3 mb-3">
                        <div class="row">
                            <label class="col-md-4 col-form-label"><?php echo translate('branch'); ?> <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <?php
                                $arrayBranch = $this->app_lib->getSelectList('branch');
                                echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' id='branch_id' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
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
                                $accounts_list = $this->app_lib->getSelectByBranch('accounts', $branch_id);
                                echo form_dropdown("account_id", $accounts_list, "", "class='form-control' id='account_id' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
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
                                $arrayVoucherHead = $this->app_lib->getSelectByBranch('voucher_head', $branch_id, false, array('type' => 'expense'));
                                echo form_dropdown("voucher_head_id", $arrayVoucherHead, "", "class='form-control' id='voucher_head_id' data-plugin-selectTwo data-width='100%'");
                                ?>
                                <span class="error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9 offset-md-3 mb-3">
                        <div class="row">
                            <label class="col-md-4 col-form-label"><?php echo translate('ref'); ?></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="ref_no" value="<?php echo set_value('ref_no'); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9 offset-md-3 mb-3">
                        <div class="row">
                            <label class="col-md-4 col-form-label"><?php echo translate('amount'); ?> <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="amount" autocomplete="off" value="<?php echo set_value('amount'); ?>">
                                <span class="error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9 offset-md-3 mb-3">
                        <div class="row">
                            <label class="col-md-4 col-form-label"><?php echo translate('date'); ?> <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="date" value="<?php echo set_value('date', date('Y-m-d')); ?>" data-plugin-datepicker autocomplete="off" data-plugin-options='{"todayHighlight":true,"endDate":"+0d"}'>
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
                                echo form_dropdown("pay_via", $payvia_list, set_value('pay_via'), "class='form-control' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9 offset-md-3 mb-3">
                        <div class="row">
                            <label class="col-md-4 col-form-label"><?php echo translate('description'); ?></label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="description" rows="3"></textarea>
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
                            <i class="fas fa-plus-circle me-1"></i><?php echo translate('save'); ?>
                        </button>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
$(function(){
    $('#branch_id').on('change', function(){
        var branchID = $(this).val();
        $.ajax({
            url: base_url + 'ajax/getDataByBranch',
            type: 'POST',
            data: $.extend({'branch_id': branchID, 'table': 'accounts'}, csrfData),
            success: function(data){ $('#account_id').html(data); }
        });
        $.ajax({
            url: base_url + 'accounting/getVoucherHead',
            type: 'POST',
            data: $.extend({'branch_id': branchID, 'type': 'expense'}, csrfData),
            success: function(data){ $('#voucher_head_id').html(data); }
        });
    });
});
</script>
