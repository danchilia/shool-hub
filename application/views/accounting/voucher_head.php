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
    <div class="row g-3">
        <?php if (get_permission('voucher_head', 'is_add')): ?>
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="far fa-edit me-2"></i><?php echo translate('add') . ' ' . translate('voucher') . ' ' . translate('head'); ?></h6>
                </div>
                <div class="card-body">
                    <?php echo form_open($this->uri->uri_string()); ?>
                    <?php if (is_superadmin_loggedin()): ?>
                    <div class="mb-3">
                        <label class="form-label"><?php echo translate('branch'); ?> <span class="text-danger">*</span></label>
                        <?php
                        $arrayBranch = $this->app_lib->getSelectList('branch');
                        echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' data-width='100%' id='branch_id' data-plugin-selectTwo data-minimum-results-for-search='Infinity'");
                        ?>
                        <span class="error text-danger small"><?php echo form_error('branch_id'); ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label"><?php echo translate('name'); ?> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="voucher_head" value="<?php echo set_value('voucher_head'); ?>">
                        <span class="error text-danger small"><?php echo form_error('voucher_head'); ?></span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo translate('type'); ?> <span class="text-danger">*</span></label>
                        <?php
                        $arrayType = array(
                            '' => translate('select'),
                            'expense' => 'Expense',
                            'income'  => 'Income',
                        );
                        echo form_dropdown("type", $arrayType, set_value('type'), "class='form-control' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                        ?>
                        <span class="error text-danger small"><?php echo form_error('type'); ?></span>
                    </div>
                    <div class="text-end">
                        <button class="btn btn-primary" type="submit" name="save" value="1">
                            <i class="fas fa-plus-circle me-1"></i><?php echo translate('save'); ?>
                        </button>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if (get_permission('voucher_head', 'is_view')): ?>
        <div class="col-md-<?php echo get_permission('voucher_head', 'is_add') ? '7' : '12'; ?>">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-list-ul me-2"></i><?php echo translate('voucher') . ' ' . translate('head') . ' ' . translate('list'); ?></h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th><?php echo translate('sl'); ?></th>
                                    <th><?php echo translate('branch'); ?></th>
                                    <th><?php echo translate('name'); ?></th>
                                    <th><?php echo translate('type'); ?></th>
                                    <th><?php echo translate('action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $count = 1;
                            if (count($productlist)) {
                                foreach ($productlist as $row):
                            ?>
                                <tr>
                                    <td><?php echo $count++; ?></td>
                                    <td><?php echo html_escape($row['branch_name']); ?></td>
                                    <td><?php echo html_escape($row['name']); ?></td>
                                    <td><?php echo ucfirst($row['type']); ?></td>
                                    <td class="text-nowrap">
                                        <?php if (get_permission('voucher_head', 'is_edit')): ?>
                                        <a class="btn btn-sm btn-outline-secondary" href="javascript:void(0);" onclick="getVoucherHead('<?php echo $row['id']; ?>')">
                                            <i class="fas fa-pen-nib"></i>
                                        </a>
                                        <?php endif; ?>
                                        <?php if (get_permission('voucher_head', 'is_delete')): ?>
                                        <?php echo btn_delete('accounting/voucher_head_delete/' . $row['id']); ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php
                                endforeach;
                            } else {
                                echo '<tr><td colspan="5" class="text-center text-danger">' . translate('no_information_available') . '</td></tr>';
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php if (get_permission('voucher_head', 'is_edit')): ?>
<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="modal">
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0"><i class="far fa-edit me-2"></i><?php echo translate('edit') . ' ' . translate('voucher') . ' ' . translate('head'); ?></h6>
        </div>
        <?php echo form_open('accounting/voucher_head_edit', array('class' => 'frm-submit')); ?>
        <div class="card-body">
            <input type="hidden" name="voucher_head_id" id="evoucherhead_id" value="">
            <?php if (is_superadmin_loggedin()): ?>
            <div class="mb-3">
                <label class="form-label"><?php echo translate('branch'); ?> <span class="text-danger">*</span></label>
                <?php
                $arrayBranch = $this->app_lib->getSelectList('branch');
                echo form_dropdown("branch_id", $arrayBranch, "", "class='form-control' id='ebranch_id' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                ?>
                <span class="error text-danger small"><?php echo form_error('branch_id'); ?></span>
            </div>
            <?php endif; ?>
            <div class="mb-3">
                <label class="form-label"><?php echo translate('name'); ?> <span class="text-danger">*</span></label>
                <input type="text" class="form-control" value="" name="voucher_head" id="ename">
                <span class="error"></span>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i><?php echo translate('update'); ?>
            </button>
            <button type="button" class="btn btn-outline-secondary modal-dismiss"><?php echo translate('cancel'); ?></button>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
<?php endif; ?>

<script>
function getVoucherHead(id) {
    $('.error').html('');
    $.ajax({
        url: base_url + 'accounting/voucherHeadDetails',
        type: 'POST',
        data: $.extend({'id': id}, csrfData),
        dataType: 'json',
        success: function(data) {
            $('#evoucherhead_id').val(data.id);
            if ($('#ebranch_id').length) {
                $('#ebranch_id').val(data.branch_id).trigger('change');
            }
            $('#ename').val(data.name);
            mfp_modal('#modal');
        }
    });
}
</script>
