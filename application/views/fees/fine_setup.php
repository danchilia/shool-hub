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
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-list" role="tab">
                        <i class="fas fa-list-ul me-1"></i><?php echo translate('fine') . ' ' . translate('list'); ?>
                        <?php echo help_tip('Fine amount in KES (if fixed) or percentage (if percentage). Example: 50'); ?>
                    </button>
                </li>
                <?php if (get_permission('fees_fine_setup', 'is_add')): ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-create" role="tab">
                        <i class="far fa-edit me-1"></i><?php echo translate('add') . ' ' . translate('fine'); ?>
                    </button>
                </li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">

                <!-- List -->
                <div class="tab-pane fade show active" id="tab-list" role="tabpanel">
                    <div class="export_title"><?php echo translate('fine') . ' ' . translate('list'); ?></div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm mb-0 table-export">
                            <thead class="table-light">
                                <tr>
                                    <th width="50"><?php echo translate('sl'); ?></th>
                                    <?php if (is_superadmin_loggedin()): ?>
                                    <th><?php echo translate('branch'); ?></th>
                                    <?php endif; ?>
                                    <th><?php echo translate('group_name'); ?></th>
                                    <th><?php echo translate('fees_type') . ' ' . translate('name'); ?></th>
                                    <th><?php echo translate('fine_type'); ?><?php echo help_tip('Fixed = flat amount (e.g. KES 50), Percentage = percent of fee amount (e.g. 2%)'); ?></th>
                                    <th><?php echo translate('fine_value'); ?></th>
                                    <th><?php echo translate('late_fee_frequency'); ?><?php echo help_tip('How often the fine is charged. Example: Every 7 days after due date, or 0 for one-time fine'); ?></th>
                                    <th><?php echo translate('action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $feeFrequency = array(
                                    ''    => translate('select'),
                                    '0'   => translate('fixed'),
                                    '1'   => translate('daily'),
                                    '7'   => translate('weekly'),
                                    '30'  => translate('monthly'),
                                    '365' => translate('annually'),
                                );
                                $count = 1; foreach ($finelist as $row): ?>
                                <tr>
                                    <td><?php echo $count++; ?></td>
                                    <?php if (is_superadmin_loggedin()): ?>
                                    <td><?php echo htmlspecialchars($row['branch_name']); ?></td>
                                    <?php endif; ?>
                                    <td><?php echo get_type_name_by_id('fee_groups', $row['group_id']); ?></td>
                                    <td><?php echo get_type_name_by_id('fees_type', $row['type_id']); ?></td>
                                    <td><?php echo $row['fine_type'] == 1 ? translate('fixed_amount') : translate('percentage'); ?></td>
                                    <td><?php
                                        if ($row['fine_type'] == 1) {
                                            echo $currency_symbol . $row['fine_value'];
                                        } else {
                                            echo $row['fine_value'] . '%';
                                        }
                                    ?></td>
                                    <td><?php echo isset($feeFrequency[$row['fee_frequency']]) ? $feeFrequency[$row['fee_frequency']] : ''; ?></td>
                                    <td class="text-nowrap">
                                        <?php if (get_permission('fees_fine_setup', 'is_edit')): ?>
                                        <a href="<?php echo base_url('fees/fine_setup_edit/' . $row['id']); ?>"
                                            class="btn btn-sm btn-outline-secondary" title="<?php echo translate('edit'); ?>">
                                            <i class="fas fa-pen-nib"></i>
                                        </a>
                                        <?php endif; ?>
                                        <?php if (get_permission('fees_fine_setup', 'is_delete')): ?>
                                        <?php echo btn_delete('fees/fine_delete/' . $row['id']); ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Create -->
                <?php if (get_permission('fees_fine_setup', 'is_add')): ?>
                <div class="tab-pane fade" id="tab-create" role="tabpanel">
                    <?php echo form_open($this->uri->uri_string(), array('class' => 'frm-submit')); ?>
                    <div class="row g-3">
                        <?php if (is_superadmin_loggedin()): ?>
                        <div class="col-md-6">
                            <label class="form-label"><?php echo translate('branch'); ?> <span class="text-danger">*</span></label>
                            <?php
                            $arrayBranch = $this->app_lib->getSelectList('branch');
                            echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' id='branch_id' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                            ?>
                            <span class="error small text-danger d-block"></span>
                        </div>
                        <?php endif; ?>
                        <div class="col-md-6">
                            <label class="form-label"><?php echo translate('group_name'); ?></label>
                            <?php
                            $arrayGroup = $this->app_lib->getSelectByBranch('fee_groups', $branch_id);
                            echo form_dropdown("group_id", $arrayGroup, set_value('group_id'), "class='form-control' id='groupID' data-plugin-selectTwo data-width='100%'");
                            ?>
                            <span class="error small text-danger d-block"></span>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?php echo translate('fees_type'); ?></label>
                            <?php
                            $arrayType = array('' => translate('first_select_the_group'));
                            echo form_dropdown("fine_type_id", $arrayType, set_value('fine_type_id'), "class='form-control' id='feesTypeID' data-plugin-selectTwo data-width='100%'");
                            ?>
                            <span class="error small text-danger d-block"></span>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?php echo translate('fine_type'); ?></label>
                            <?php
                            $arrayFine = array('' => translate('select'), '1' => translate('fixed_amount'), '2' => translate('percentage'));
                            echo form_dropdown("fine_type", $arrayFine, set_value('fine_type'), "class='form-control' id='fineType' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                            ?>
                            <span class="error small text-danger d-block"></span>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?php echo translate('fine') . ' ' . translate('value'); ?> <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="fine_value" value="<?php echo set_value('fine_value'); ?>" autocomplete="off">
                            <span class="error small text-danger d-block"></span>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?php echo translate('late_fee_frequency'); ?></label>
                            <?php
                            $feeFreqOpts = array('' => translate('select'), '0' => translate('fixed'), '1' => translate('daily'), '7' => translate('weekly'), '30' => translate('monthly'), '365' => translate('annually'));
                            echo form_dropdown("fee_frequency", $feeFreqOpts, set_value('fee_frequency'), "class='form-control' id='feeFrequency' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                            ?>
                            <span class="error small text-danger d-block"></span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-4">
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
    $('#branch_id').on('change', function(){
        $.ajax({
            url: base_url + 'fees/getGroupByBranch',
            type: 'POST',
            data: $.extend({'branch_id': $(this).val()}, csrfData),
            success: function(data){ $('#groupID').html(data); }
        });
    });
    $('#groupID').on('change', function(){
        $.ajax({
            url: base_url + 'fees/getTypeByGroup',
            type: 'POST',
            data: $.extend({'group_id': $(this).val()}, csrfData),
            success: function(data){ $('#feesTypeID').html(data); }
        });
    });
});
</script>
