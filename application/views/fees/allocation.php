<?php $widget = (is_superadmin_loggedin() ? 3 : 4); ?>
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
    <div class="card mb-3">
        <div class="card-body">
            <?php echo form_open($this->uri->uri_string(), array('class' => 'validate')); ?>
            <div class="row g-3 align-items-end">
                <?php if (is_superadmin_loggedin()): ?>
                <div class="col-md-3">
                    <label class="form-label"><?php echo translate('branch'); ?> <span class="text-danger">*</span></label>
                    <?php
                    $arrayBranch = $this->app_lib->getSelectList('branch');
                    echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' id='branch_id' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                    ?>
                </div>
                <?php endif; ?>
                <div class="col-md-<?php echo $widget; ?>">
                    <label class="form-label"><?php echo translate('class'); ?> <span class="text-danger">*</span></label>
                    <?php
                    $arrayClass = $this->app_lib->getClass($branch_id);
                    echo form_dropdown("class_id", $arrayClass, set_value('class_id'), "class='form-control' id='class_id' onchange='getSectionByClass(this.value,1)' required data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                    ?>
                </div>
                <div class="col-md-<?php echo $widget; ?>">
                    <label class="form-label"><?php echo translate('section'); ?> <span class="text-danger">*</span></label>
                    <?php
                    $arraySection = $this->app_lib->getSections(set_value('class_id'), true);
                    echo form_dropdown("section_id", $arraySection, set_value('section_id'), "class='form-control' id='section_id' required data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                    ?>
                </div>
                <div class="col-md-<?php echo $widget; ?>">
                    <label class="form-label"><?php echo translate('fee_group'); ?> <span class="text-danger">*</span></label>
                    <?php
                    $arrayGroup = $this->app_lib->getSelectByBranch('fee_groups', $branch_id);
                    echo form_dropdown("fee_group_id", $arrayGroup, set_value('fee_group_id'), "class='form-control' id='groupID' required data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                    ?>
                </div>
                <div class="col-md-2">
                    <button type="submit" name="search" value="1" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i><?php echo translate('filter'); ?>
                    </button>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>

    <?php if (isset($studentlist)): ?>
    <div class="card">
        <?php echo form_open($this->uri->uri_string()); ?>
        <input type="hidden" name="fee_group_id" value="<?php echo $fee_group_id; ?>">
        <input type="hidden" name="branch_id" value="<?php echo $branch_id; ?>">
        <div class="card-header">
            <h6 class="mb-0"><i class="fas fa-list me-2"></i><?php echo translate('student_list'); ?></h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="50">
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="checkbox" id="selectAllchkbox">
                                </div>
                            </th>
                            <th width="60"><?php echo translate('sl'); ?></th>
                            <th><?php echo translate('name'); ?></th>
                            <th><?php echo translate('register_no'); ?></th>
                            <th><?php echo translate('roll'); ?></th>
                            <th><?php echo translate('gender'); ?></th>
                            <th><?php echo translate('mobile_no'); ?></th>
                            <th><?php echo translate('email'); ?></th>
                            <th><?php echo translate('guardian_name'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $count = 1;
                        if (count($studentlist)) {
                            foreach ($studentlist as $row): ?>
                        <tr>
                            <td class="checked-area">
                                <div class="form-check mb-0">
                                    <input class="form-check-input row-chkbox" type="checkbox"
                                        name="stu_operations[]"
                                        value="<?php echo $row['student_id']; ?>"
                                        <?php echo ($row['allocation_id'] != 0 ? 'checked' : ''); ?>>
                                </div>
                            </td>
                            <td><?php echo $count++; ?></td>
                            <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                            <td><?php echo htmlspecialchars($row['register_no']); ?></td>
                            <td><?php echo htmlspecialchars($row['roll']); ?></td>
                            <td><?php echo ucfirst($row['gender']); ?></td>
                            <td><?php echo htmlspecialchars($row['mobileno']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo (!empty($row['parent_id']) ? get_type_name_by_id('parent', $row['parent_id']) : 'N/A'); ?></td>
                        </tr>
                        <?php endforeach; } else { ?>
                        <tr><td colspan="9" class="text-center text-danger"><?php echo translate('no_information_available'); ?></td></tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end">
            <button type="submit" name="save" value="1" class="btn btn-primary">
                <i class="fas fa-plus-circle me-1"></i><?php echo translate('save'); ?>
            </button>
        </div>
        <?php echo form_close(); ?>
    </div>
    <?php endif; ?>
</div>

<script>
$(function(){
    $('#selectAllchkbox').on('change', function(){
        $('.row-chkbox').prop('checked', this.checked);
    });
    $('#branch_id').on('change', function(){
        var branchID = $(this).val();
        getClassByBranch(branchID);
        $.ajax({
            url: base_url + 'fees/getGroupByBranch',
            type: 'POST',
            data: $.extend({'branch_id': branchID}, csrfData),
            success: function(data){ $('#groupID').html(data); }
        });
    });
});
</script>
