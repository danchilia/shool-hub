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

<div class="content-header">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <a href="<?php echo base_url('fees/reminder'); ?>" class="text-muted small">
            <i class="fas fa-arrow-left me-1"></i><?php echo translate('reminder') . ' ' . translate('list'); ?>
        </a>
    </div>
</div>

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="far fa-edit me-2"></i><?php echo translate('edit') . ' ' . translate('reminder'); ?></h5>
        </div>
        <div class="card-body">
            <?php echo form_open($this->uri->uri_string(), array('class' => 'frm-submit')); ?>
            <input type="hidden" name="reminder_id" value="<?php echo $reminder['id']; ?>">
            <div class="row g-3 mb-3">
                <?php if (is_superadmin_loggedin()): ?>
                <div class="col-md-6">
                    <label class="form-label"><?php echo translate('branch'); ?> <span class="text-danger">*</span></label>
                    <?php
                    $arrayBranch = $this->app_lib->getSelectList('branch');
                    echo form_dropdown("branch_id", $arrayBranch, $reminder['branch_id'], "class='form-control' id='branch_id' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                    ?>
                    <span class="error small text-danger d-block"></span>
                </div>
                <?php endif; ?>
                <div class="col-md-6">
                    <label class="form-label"><?php echo translate('frequency'); ?> <span class="text-danger">*</span></label>
                    <?php
                    $arrayType = array(
                        '' => translate('select'),
                        'before' => translate('before'),
                        'after'  => translate('after'),
                    );
                    echo form_dropdown("frequency", $arrayType, $reminder['frequency'], "class='form-control' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                    ?>
                    <span class="error small text-danger d-block"></span>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><?php echo translate('days'); ?> <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="days" value="<?php echo htmlspecialchars($reminder['days']); ?>" autocomplete="off">
                    <span class="error small text-danger d-block"></span>
                </div>
                <div class="col-md-12">
                    <label class="form-label"><?php echo translate('message'); ?></label>
                    <textarea class="form-control" id="message" name="message" rows="3"><?php echo htmlspecialchars($reminder['message']); ?></textarea>
                    <span class="error small text-danger d-block"></span>
                    <div class="d-flex justify-content-end mt-1">
                        <small class="text-danger">
                            <span id="remaining_count">160 characters remaining</span> &nbsp; <span id="messages">1 message</span>
                        </small>
                    </div>
                    <div class="mt-3">
                        <strong>Dynamic Tag : </strong>
                        <a data-value=" {guardian_name} " class="btn btn-sm btn-outline-secondary btn_tag">{guardian_name}</a>
                        <a data-value=" {child_name} " class="btn btn-sm btn-outline-secondary btn_tag">{child_name}</a>
                        <a data-value=" {due_date} " class="btn btn-sm btn-outline-secondary btn_tag">{due_date}</a>
                        <a data-value=" {due_amount} " class="btn btn-sm btn-outline-secondary btn_tag">{due_amount}</a>
                        <a data-value=" {fee_type} " class="btn btn-sm btn-outline-secondary btn_tag">{fee_type}</a>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><?php echo translate('notify'); ?></label>
                    <div class="form-check mt-1">
                        <input class="form-check-input" type="checkbox" name="chk_student" id="chkStudent" <?php echo ($reminder['student'] == 1 ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="chkStudent"><?php echo translate('student'); ?></label>
                    </div>
                    <div class="form-check mt-1">
                        <input class="form-check-input" type="checkbox" name="chk_guardian" id="chkGuardian" <?php echo ($reminder['guardian'] == 1 ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="chkGuardian"><?php echo translate('guardian'); ?></label>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
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
    var $remaining = $('#remaining_count'),
        $messages  = $remaining.next();
    $('#message').on('keyup', function(){
        var chars     = this.value.length,
            messages  = Math.ceil(chars / 160) || 1,
            remaining = messages * 160 - (chars % (messages * 160) || messages * 160);
        $remaining.text(remaining + ' characters remaining');
        $messages.text(messages + ' message');
    });

    $('.btn_tag').on('click', function(){
        var $txt        = $('#message');
        var caretPos    = $txt[0].selectionStart;
        var textAreaTxt = $txt.val();
        var txtToAdd    = $(this).data('value');
        $txt.val(textAreaTxt.substring(0, caretPos) + txtToAdd + textAreaTxt.substring(caretPos));
    });
});
</script>
