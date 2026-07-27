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
        <div class="card-header p-0">
            <ul class="nav nav-tabs border-0 px-3 pt-2" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?=empty($branch_id) ? 'active' : ''?>"
                        data-bs-toggle="tab" data-bs-target="#tab-list" role="tab">
                        <i class="fas fa-list-ul me-1"></i><?php echo translate('fees_group') . ' ' . translate('list'); ?>
                    </button>
                </li>
                <?php if (get_permission('fees_group', 'is_add')): ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?=!empty($branch_id) ? 'active' : ''?>"
                        data-bs-toggle="tab" data-bs-target="#tab-create" role="tab">
                        <i class="far fa-edit me-1"></i><?php echo translate('add') . ' ' . translate('fees_group'); ?>
                    </button>
                </li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">

                <!-- List -->
                <div class="tab-pane fade <?=empty($branch_id) ? 'show active' : ''?>" id="tab-list" role="tabpanel">
                    <div class="export_title"><?php echo translate('fees_group') . ' ' . translate('list'); ?></div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm mb-0 table-export">
                            <thead class="table-light">
                                <tr>
                                    <th width="50"><?php echo translate('sl'); ?></th>
                                    <?php if (is_superadmin_loggedin()): ?>
                                    <th><?php echo translate('branch'); ?></th>
                                    <?php endif; ?>
                                    <th><?php echo translate('name'); ?></th>
                                    <th><?php echo translate('fees_type'); ?></th>
                                    <th><?php echo translate('description'); ?></th>
                                    <th><?php echo translate('action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $count = 1; foreach ($categorylist as $row): ?>
                                <tr>
                                    <td><?php echo $count++; ?></td>
                                    <?php if (is_superadmin_loggedin()): ?>
                                    <td><?php echo get_type_name_by_id('branch', $row['branch_id']); ?></td>
                                    <?php endif; ?>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php
                                    foreach (isset($group_details_map[$row['id']]) ? $group_details_map[$row['id']] : array() as $d) {
                                        echo htmlspecialchars($d['name']) . ' - ' . $currency_symbol . $d['amount'] . '<br>';
                                    }
                                    ?></td>
                                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                                    <td class="text-nowrap">
                                        <?php if (get_permission('fees_group', 'is_edit')): ?>
                                        <a href="<?php echo base_url('fees/group_edit/' . $row['id']); ?>"
                                            class="btn btn-sm btn-outline-secondary" title="<?php echo translate('edit'); ?>">
                                            <i class="fas fa-pen-nib"></i>
                                        </a>
                                        <?php endif; ?>
                                        <?php if (get_permission('fees_group', 'is_delete')): ?>
                                        <?php echo btn_delete('fees/group_delete/' . $row['id']); ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Create -->
                <?php if (get_permission('fees_group', 'is_add')): ?>
                <div class="tab-pane fade <?=!empty($branch_id) ? 'show active' : ''?>" id="tab-create" role="tabpanel">
                    <?php echo form_open($this->uri->uri_string(), array('class' => 'frm-submit')); ?>
                    <div class="row g-3 mb-3">
                        <?php if (is_superadmin_loggedin()): ?>
                        <div class="col-md-6">
                            <label class="form-label"><?php echo translate('branch'); ?> <span class="text-danger">*</span></label>
                            <?php
                            $arrayBranch = $this->app_lib->getSelectList('branch');
                            echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id', $branch_id), "class='form-control' id='branch_id' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                            ?>
                            <span class="error small text-danger d-block"></span>
                        </div>
                        <?php endif; ?>
                        <div class="col-md-6">
                            <label class="form-label"><?php echo translate('group_name'); ?><?php echo help_tip('Bundle of fees for a term. Example: Term 2 Fees - Primary'); ?> <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" value="<?php echo set_value('name'); ?>" autocomplete="off">
                            <span class="error small text-danger d-block"></span>
                        </div>
                        <div class="col-12">
                            <label class="form-label"><?php echo translate('description'); ?></label>
                            <textarea class="form-control" name="description" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm" id="tableID">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input" type="checkbox" id="selectAllchkbox">
                                        </div>
                                    </th>
                                    <th><?php echo translate('fees_type'); ?></th>
                                    <th><?php echo translate('due_date'); ?> <span class="text-danger">*</span></th>
                                    <th><?php echo translate('amount'); ?> <span class="text-danger">*</span></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if (count($fee_types_for_create)): ?>
                                <?php foreach ($fee_types_for_create as $key => $value): ?>
                                <tr>
                                    <td class="checked-area">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input row-chkbox" type="checkbox"
                                                name="elem[<?php echo $key; ?>][fees_type_id]"
                                                value="<?php echo $value['id']; ?>">
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($value['name']); ?></td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" name="elem[<?php echo $key; ?>][due_date]"
                                            value="" data-plugin-datepicker data-plugin-options='{"startView": 1}' autocomplete="off">
                                        <span class="error small text-danger d-block"></span>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" name="elem[<?php echo $key; ?>][amount]"
                                            value="0.00" autocomplete="off">
                                        <span class="error small text-danger d-block"></span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4"><p class="text-danger text-center mb-0"><?php echo translate('no_information_available'); ?></p></td></tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
                            <i class="fas fa-plus-circle me-1"></i><?php echo translate('save'); ?>
                        </button>
                    </div>
                    <?php echo form_close(); ?>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<script>
$(function(){
    $('#selectAllchkbox').on('change', function(){
        $('.row-chkbox').prop('checked', this.checked);
    });
    $('#branch_id').on('change', function(){
        window.location.href = base_url + 'fees/group/' + $(this).val();
    });
});
</script>
