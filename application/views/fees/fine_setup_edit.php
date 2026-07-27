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

<div class="content-header">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <a href="<?php echo base_url('fees/fine_setup'); ?>" class="text-muted small">
            <i class="fas fa-arrow-left me-1"></i><?php echo translate('fine') . ' ' . translate('list'); ?>
        </a>
    </div>
</div>

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="far fa-edit me-2"></i><?php echo translate('edit') . ' ' . translate('fine'); ?></h5>
        </div>
        <div class="card-body">
            <?php echo form_open($this->uri->uri_string(), array('class' => 'frm-submit')); ?>
            <input type="hidden" name="fine_id" value="<?php echo $fine['id']; ?>">
            <div class="row g-3">
                <?php if (is_superadmin_loggedin()): ?>
                <div class="col-md-6">
                    <label class="form-label"><?php echo translate('branch'); ?> <span class="text-danger">*</span></label>
                    <?php
                    $arrayBranch = $this->app_lib->getSelectList('branch');
                    echo form_dropdown("branch_id", $arrayBranch, $fine['branch_id'], "class='form-control' id='branch_id' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                    ?>
                    <span class="error small text-danger d-block"></span>
                </div>
                <?php endif; ?>
                <div class="col-md-6">
                    <label class="form-label"><?php echo translate('group_name'); ?></label>
                    <?php
                    $arrayGroup = $this->app_lib->getSelectByBranch('fee_groups', $fine['branch_id']);
                    echo form_dropdown("group_id", $arrayGroup, $fine['group_id'], "class='form-control' id='groupID' data-plugin-selectTwo data-width='100%'");
                    ?>
                    <span class="error small text-danger d-block"></span>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><?php echo translate('fees_type'); ?></label>
                    <?php
                    $arrayType = array('' => translate('first_select_the_group'));
                    echo form_dropdown("fine_type_id", $arrayType, "", "class='form-control' id='feesTypeID' data-plugin-selectTwo data-width='100%'");
                    ?>
                    <span class="error small text-danger d-block"></span>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><?php echo translate('fine_type'); ?></label>
                    <?php
                    $arrayFine = array('' => translate('select'), '1' => translate('fixed_amount'), '2' => translate('percentage'));
                    echo form_dropdown("fine_type", $arrayFine, $fine['fine_type'], "class='form-control' id='fineType' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                    ?>
                    <span class="error small text-danger d-block"></span>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><?php echo translate('fine') . ' ' . translate('value'); ?> <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="fine_value" value="<?php echo htmlspecialchars($fine['fine_value']); ?>" autocomplete="off">
                    <span class="error small text-danger d-block"></span>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><?php echo translate('late_fee_frequency'); ?></label>
                    <?php
                    $feeFreqOpts = array('' => translate('select'), '0' => translate('fixed'), '1' => translate('daily'), '7' => translate('weekly'), '30' => translate('monthly'), '365' => translate('annually'));
                    echo form_dropdown("fee_frequency", $feeFreqOpts, $fine['fee_frequency'], "class='form-control' id='feeFrequency' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                    ?>
                    <span class="error small text-danger d-block"></span>
                </div>
            </div>
            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
                    <i class="fas fa-save me-1"></i><?php echo translate('update'); ?>
                </button>
            </div>
            <?php echo form_close(); ?>
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

    function getTypeByGroup(groupID, typeID) {
        typeID = typeID || '';
        $.ajax({
            url: base_url + 'fees/getTypeByGroup',
            type: 'POST',
            data: $.extend({'group_id': groupID, 'type_id': typeID}, csrfData),
            success: function(data){ $('#feesTypeID').html(data); }
        });
    }

    $('#groupID').on('change', function(){ getTypeByGroup($(this).val()); });
    getTypeByGroup("<?php echo $fine['group_id']; ?>", "<?php echo $fine['type_id']; ?>");
});
</script>
