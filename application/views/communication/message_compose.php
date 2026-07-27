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

<div class="card">
    <?php echo form_open_multipart('communication/message_send', array('class' => 'frm-submit-data')); ?>
    <div class="card-header">
        <h6 class="mb-0"><i class="far fa-edit me-2"></i><?php echo translate('write_message'); ?></h6>
    </div>
    <div class="card-body">
        <?php if (is_superadmin_loggedin()): ?>
        <div class="mb-3">
            <label class="form-label"><?php echo translate('branch'); ?> <span class="text-danger">*</span></label>
            <?php
            $arrayBranch = $this->app_lib->getSelectList('branch');
            echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' id='branchID' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
            ?>
            <span class="error small text-danger d-block"></span>
        </div>
        <?php endif; ?>

        <div class="mb-3">
            <label class="form-label"><?php echo translate('role'); ?> <span class="text-danger">*</span></label>
            <?php
            $role_list = $this->app_lib->getRoles(1);
            echo form_dropdown("role_id", $role_list, set_value('role_id'), "class='form-control' id='roleID' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
            ?>
            <span class="error small text-danger d-block"></span>
        </div>

        <div class="mb-3 class_div" <?php if (empty($class_id)) echo 'style="display:none"'; ?>>
            <label class="form-label"><?php echo translate('class'); ?> <span class="text-danger">*</span></label>
            <?php
            $arrayClass = $this->app_lib->getClass($branch_id);
            echo form_dropdown("class_id", $arrayClass, set_value('class_id'), "class='form-control' id='class_id' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
            ?>
            <span class="error small text-danger d-block"></span>
        </div>

        <div class="mb-3">
            <label class="form-label"><?php echo translate('receiver'); ?> <span class="text-danger">*</span></label>
            <?php
            $arrayUser = array("" => translate('select'));
            echo form_dropdown("receiver_id", $arrayUser, set_value('receiver_id'), "class='form-control' id='receiverID' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
            ?>
            <span class="error small text-danger d-block"></span>
        </div>

        <div class="mb-3">
            <label class="form-label"><?php echo translate('subject'); ?> <span class="text-danger">*</span></label>
            <input id="subject" name="subject" type="text" class="form-control" value="">
            <span class="error small text-danger d-block"></span>
        </div>

        <div class="mb-3">
            <label class="form-label"><?php echo translate('message'); ?> <span class="text-danger">*</span></label>
            <textarea name="message_body" class="form-control summernote" id="summernote" rows="10"></textarea>
            <span class="error small text-danger d-block"></span>
        </div>

        <div class="mb-3">
            <label class="form-label"><?php echo translate('attachment_file'); ?></label>
            <input type="file" name="attachment_file" class="dropify" data-height="80"
                data-allowed-file-extensions="pdf csv doc xls docx xlsx jpg jpeg png gif bmp">
            <span class="error small text-danger d-block"></span>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-end gap-2">
        <button type="reset" class="btn btn-outline-secondary">
            <i class="fas fa-times me-1"></i><?php echo translate('discard'); ?>
        </button>
        <button type="submit" name="submit" value="send" class="btn btn-primary" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
            <i class="fas fa-paper-plane me-1"></i><?php echo translate('send'); ?>
        </button>
    </div>
    <?php echo form_close(); ?>
</div>

<script>
$(function(){
    $('#branchID').on('change', function(){
        var branchID = $(this).val();
        getClassByBranch(branchID);
        $('#roleID').val('').trigger('change.select2');
        $('#receiverID').empty().html("<option value=''><?php echo translate('select_user'); ?></option>");
    });

    $('#roleID').on('change', function(){
        var roleID   = $(this).val();
        var branchID = $('#branchID').length ? $('#branchID').val() : '';
        if (roleID == 6) {
            $.ajax({
                url: base_url + 'communication/getParentListBranch',
                type: 'POST',
                data: $.extend({ branch_id: branchID }, csrfData),
                success: function(data){ $('#receiverID').html(data); }
            });
            $('.class_div').hide(400);
        } else if (roleID == 7) {
            $('.class_div').show(400);
            $('#receiverID').empty().html("<option value=''><?php echo translate('select_user'); ?></option>");
        } else {
            $('.class_div').hide(400);
            $.ajax({
                url: base_url + 'communication/getStafflistRole',
                type: 'POST',
                data: $.extend({ branch_id: branchID, role_id: roleID }, csrfData),
                success: function(data){ $('#receiverID').html(data); }
            });
        }
    });

    $('#class_id').on('change', function(){
        var classID  = $(this).val();
        var branchID = $('#branchID').length ? $('#branchID').val() : '';
        $.ajax({
            url: base_url + 'communication/getStudentByClass',
            type: 'POST',
            data: $.extend({ branch_id: branchID, class_id: classID }, csrfData),
            success: function(data){ $('#receiverID').html(data); }
        });
    });
});
</script>
