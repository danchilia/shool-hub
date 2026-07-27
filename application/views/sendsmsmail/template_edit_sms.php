<style>
@media (prefers-color-scheme:dark) {
    .card        { background:#2b2b3a; border-color:#3a3a50; }
    .card-header { background:#232333; border-color:#3a3a50; }
    .char-bar    { background:#1e1e2e; border-color:#3a3a50; }
}
:root[data-theme="dark"]  .card        { background:#2b2b3a; border-color:#3a3a50; }
:root[data-theme="dark"]  .card-header { background:#232333; border-color:#3a3a50; }
:root[data-theme="dark"]  .char-bar    { background:#1e1e2e; border-color:#3a3a50; }
:root[data-theme="light"] .card        { background:#fff;    border-color:#dee2e6; }
:root[data-theme="light"] .card-header { background:#f8f9fa; border-color:#dee2e6; }
:root[data-theme="light"] .char-bar    { background:#f8f9fa; border-color:#dee2e6; }
</style>

<div class="content-header">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <a href="<?php echo base_url('sendsmsmail/template/' . $type); ?>" class="text-muted small">
            <i class="fas fa-arrow-left me-1"></i><?php echo translate('template') . ' ' . translate('list'); ?>
        </a>
    </div>
</div>

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="far fa-edit me-2"></i><?php echo translate('edit') . ' ' . translate('template'); ?></h5>
        </div>
        <div class="card-body">
            <?php echo form_open($this->uri->uri_string(), array('class' => 'frm-submit')); ?>
            <input type="hidden" name="template_id" value="<?php echo $templete['id']; ?>">
            <div class="row g-3">
                <?php if (is_superadmin_loggedin()): ?>
                <div class="col-md-6">
                    <label class="form-label"><?php echo translate('branch'); ?> <span class="text-danger">*</span></label>
                    <?php
                    $arrayBranch = $this->app_lib->getSelectList('branch');
                    echo form_dropdown("branch_id", $arrayBranch, $templete['branch_id'], "class='form-control' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                    ?>
                    <span class="error small text-danger d-block"></span>
                </div>
                <?php endif; ?>
                <div class="col-md-6">
                    <label class="form-label"><?php echo translate('name'); ?> <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="template_name" value="<?php echo htmlspecialchars($templete['name']); ?>">
                    <span class="error small text-danger d-block"></span>
                </div>
                <div class="col-12">
                    <label class="form-label"><?php echo translate('message'); ?> <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="message" id="message" rows="5"><?php echo htmlspecialchars($templete['body']); ?></textarea>
                    <span class="error small text-danger d-block"></span>
                    <div class="char-bar d-flex justify-content-end gap-3 small px-2 py-1 rounded border mt-1">
                        <span id="remaining_count">160 characters remaining</span>
                        <span id="messages">1 message</span>
                    </div>
                </div>
                <div class="col-12">
                    <strong>Dynamic Tags:</strong>
                    <a data-value=" {name} " class="btn btn-sm btn-outline-secondary ms-1 btn_tag">{name}</a>
                    <a data-value=" {email} " class="btn btn-sm btn-outline-secondary ms-1 btn_tag">{email}</a>
                    <a data-value=" {mobile_no} " class="btn btn-sm btn-outline-secondary ms-1 btn_tag">{mobile_no}</a>
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
    var $rem = $('#remaining_count'), $msg = $('#messages');
    function updateCount(){
        var chars = document.getElementById('message').value.length,
            msgs  = Math.ceil(chars / 160) || 1,
            rem   = msgs * 160 - (chars % (msgs * 160) || msgs * 160);
        $rem.text(rem + ' characters remaining');
        $msg.text(msgs + ' message' + (msgs > 1 ? 's' : ''));
    }
    $('#message').on('keyup', updateCount);
    updateCount();

    $('.btn_tag').on('click', function(){
        var $txt = $('#message'),
            pos  = $txt[0].selectionStart,
            val  = $txt.val(),
            tag  = $(this).data('value');
        $txt.val(val.substring(0, pos) + tag + val.substring(pos)).trigger('keyup');
    });
});
</script>
