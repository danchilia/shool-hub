<?php $widget = (is_superadmin_loggedin() ? 4 : 6); ?>
<style>
@media (prefers-color-scheme:dark) {
    .card        { background:#2b2b3a; border-color:#3a3a50; }
    .card-header { background:#232333; border-color:#3a3a50; }
}
:root[data-theme="dark"]  .card        { background:#2b2b3a; border-color:#3a3a50; }
:root[data-theme="dark"]  .card-header { background:#232333; border-color:#3a3a50; }
:root[data-theme="light"] .card        { background:#fff;    border-color:#dee2e6; }
:root[data-theme="light"] .card-header { background:#f8f9fa; border-color:#dee2e6; }
.hidden-div { display:none; }
</style>

<div class="content-header">
    <div class="d-flex align-items-center justify-content-end flex-wrap gap-2">
        <a href="<?php echo base_url('sendsmsmail/campaign_reports'); ?>" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-chart-bar me-1"></i>Campaign Reports
        </a>
    </div>
</div>

<div class="container-fluid">
    <div class="card">
        <div class="card-header p-0">
            <ul class="nav nav-tabs border-0 px-3 pt-2">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url('sendsmsmail/sms'); ?>">
                        <i class="far fa-comment me-1"></i>SMS
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="<?php echo base_url('sendsmsmail/email'); ?>">
                        <i class="far fa-envelope me-1"></i>Email
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <?php echo form_open('sendsmsmail/save', array('class' => 'frm-submit')); ?>
            <input type="hidden" name="message_type" value="<?php echo $this->uri->segment(2); ?>">

            <div class="row g-3">
                <?php if (is_superadmin_loggedin()): ?>
                <div class="col-md-4">
                    <label class="form-label"><?php echo translate('branch'); ?> <span class="text-danger">*</span></label>
                    <?php
                    $arrayBranch = $this->app_lib->getSelectList('branch');
                    echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' data-width='100%' id='branch_id' data-plugin-selectTwo data-minimum-results-for-search='Infinity'");
                    ?>
                    <span class="error small text-danger d-block"></span>
                </div>
                <?php endif; ?>
                <div class="col-md-<?php echo $widget; ?>">
                    <label class="form-label"><?php echo translate('campaign_name'); ?> <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="campaign_name" value="">
                    <span class="error small text-danger d-block"></span>
                </div>
                <div class="col-md-<?php echo $widget; ?>">
                    <label class="form-label"><?php echo translate('template'); ?></label>
                    <?php
                    $arrayTemplate = $this->app_lib->getSelectByBranch('bulk_msg_category', $branch_id, false, array('type' => 2));
                    echo form_dropdown("email_template", $arrayTemplate, set_value('email_template'), "class='form-control' id='email_template' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                    ?>
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-12">
                    <label class="form-label"><?php echo translate('message'); ?> <span class="text-danger">*</span></label>
                    <textarea name="message" id="message" class="summernote"></textarea>
                    <span class="error small text-danger d-block"></span>
                    <div class="mt-2">
                        <strong>Dynamic Tags:</strong>
                        <a data-value=" {name} " class="btn btn-sm btn-outline-secondary ms-1 btn_tag">{name}</a>
                        <a data-value=" {email} " class="btn btn-sm btn-outline-secondary ms-1 btn_tag">{email}</a>
                        <a data-value=" {mobile_no} " class="btn btn-sm btn-outline-secondary ms-1 btn_tag">{mobile_no}</a>
                    </div>
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-md-6">
                    <label class="form-label"><?php echo translate('email_subject'); ?> <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="email_subject" value="">
                    <span class="error small text-danger d-block"></span>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><?php echo translate('type'); ?> <span class="text-danger">*</span></label>
                    <?php
                    $arrayType = array(
                        ""  => translate('select'),
                        "1" => translate('group'),
                        "2" => translate('individual'),
                        "3" => translate('class'),
                    );
                    echo form_dropdown("recipient_type", $arrayType, "", "class='form-control' id='typeID' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                    ?>
                    <span class="error small text-danger d-block"></span>
                </div>
            </div>

            <!-- Group recipients -->
            <div class="row g-3 mt-1 hidden-div" id="group_div">
                <div class="col-12">
                    <label class="form-label">Role <span class="text-danger">*</span></label>
                    <?php
                    $role_list = $this->app_lib->getRoles(1);
                    unset($role_list['']);
                    echo form_dropdown("role_group[]", $role_list, "", "class='form-control' multiple id='role_group' data-plugin-selectTwo data-width='100%'");
                    ?>
                    <span class="error small text-danger d-block"></span>
                    <div class="form-check mt-2">
                        <input class="form-check-input chk-sendsmsmail" type="checkbox" name="chk_role" id="chk_role">
                        <label class="form-check-label" for="chk_role">Select All</label>
                    </div>
                </div>
            </div>

            <!-- Individual recipients -->
            <div class="row g-3 mt-1 hidden-div" id="individual_div">
                <div class="col-12">
                    <label class="form-label"><?php echo translate('role'); ?> <span class="text-danger">*</span></label>
                    <?php
                    $role_list = $this->app_lib->getRoles(1);
                    echo form_dropdown("role_id", $role_list, set_value('role_id'), "class='form-control' id='roleID' onchange='getRecipientsByRole()' data-plugin-selectTwo data-width='100%'");
                    ?>
                    <span class="error small text-danger d-block"></span>
                </div>
                <div class="col-12">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <select class="form-control" name="recipients[]" id="recipients" data-plugin-selectTwo multiple></select>
                    <span class="error small text-danger d-block"></span>
                    <div class="form-check mt-2">
                        <input class="form-check-input chk-sendsmsmail" type="checkbox" name="chk_recipients" id="chk_recipients">
                        <label class="form-check-label" for="chk_recipients">Select All</label>
                    </div>
                </div>
            </div>

            <!-- Class recipients -->
            <div class="row g-3 mt-1 hidden-div" id="class_div">
                <div class="col-12">
                    <label class="form-label"><?php echo translate('class'); ?> <span class="text-danger">*</span></label>
                    <?php
                    $arrayClass = $this->app_lib->getClass($branch_id);
                    echo form_dropdown("class_id", $arrayClass, set_value('class_id'), "class='form-control' id='class_id' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                    ?>
                    <span class="error small text-danger d-block"></span>
                </div>
                <div class="col-12">
                    <label class="form-label"><?php echo translate('section'); ?> <span class="text-danger">*</span></label>
                    <select class="form-control" name="section[]" id="section_id" data-plugin-selectTwo multiple></select>
                    <span class="error small text-danger d-block"></span>
                    <div class="form-check mt-2">
                        <input class="form-check-input chk-sendsmsmail" type="checkbox" name="chk_section" id="chk_section">
                        <label class="form-check-label" for="chk_section">Select All</label>
                    </div>
                </div>
            </div>

            <!-- Send Later -->
            <div class="mt-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="send_later" id="send_later">
                    <label class="form-check-label" for="send_later">Send Later</label>
                </div>
            </div>

            <div class="row g-3 mt-1" id="schedule_row" style="display:none;">
                <div class="col-md-8">
                    <label class="form-label">Schedule Date <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                        <input type="text" class="form-control" name="schedule_date" id="schedule_date" value="<?php echo date('Y-m-d'); ?>" disabled data-plugin-datepicker>
                    </div>
                    <span class="error small text-danger d-block"></span>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Schedule Time <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="far fa-clock"></i></span>
                        <input type="text" name="schedule_time" id="schedule_time" class="form-control" value="<?php echo date('H:i'); ?>" disabled data-plugin-timepicker>
                    </div>
                    <span class="error small text-danger d-block"></span>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
                    <i class="far fa-share-square me-1"></i><?php echo translate('send'); ?>
                </button>
            </div>

            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
$(function(){
    $('#branch_id').on('change', function(){
        var branchID = $(this).val();
        getRecipientsByRole();
        getClassByBranch(branchID);
        $.ajax({
            url: base_url + 'sendsmsmail/getTemplateByBranch',
            type: 'POST',
            data: $.extend({ branch_id: branchID, type: 'email' }, csrfData),
            success: function(d){ $('#email_template').html(d); }
        });
    });

    $('#typeID').on('change', function(){
        var val = $(this).val();
        $('#group_div, #individual_div, #class_div').hide();
        if (val == 1) $('#group_div').show('fast');
        if (val == 2) $('#individual_div').show('fast');
        if (val == 3) $('#class_div').show('fast');
    });

    $('.chk-sendsmsmail').on('change', function(){
        var $sel = $(this).closest('.col-12').find('select');
        $sel.find('option').prop('selected', $(this).is(':checked'));
        $sel.trigger('change');
    });

    $('#send_later').on('change', function(){
        if ($(this).is(':checked')) {
            $('#schedule_row').show('fast');
            $('#schedule_date, #schedule_time').prop('disabled', false);
        } else {
            $('#schedule_row').hide('fast');
            $('#schedule_date, #schedule_time').prop('disabled', true);
        }
    });

    $('.btn_tag').on('click', function(){
        $('.summernote').summernote('editor.insertText', $(this).data('value'));
    });

    $('#class_id').on('change', function(){
        $.ajax({
            url: base_url + 'sendsmsmail/getSectionByClass',
            type: 'POST',
            data: $.extend({ class_id: $(this).val() }, csrfData),
            success: function(r){ $('#section_id').html(r); }
        });
    });

    $('#email_template').on('change', function(){
        if (!$(this).val()) return;
        $.ajax({
            url: base_url + 'sendsmsmail/getSmsTemplateText',
            type: 'POST',
            data: $.extend({ id: $(this).val() }, csrfData),
            success: function(r){ $('.summernote').summernote('code', r); }
        });
    });
});

function getRecipientsByRole(){
    var roleID   = $('#roleID').val();
    var branchID = ($('#branch_id').length ? $('#branch_id').val() : '');
    if (!roleID) return;
    $.ajax({
        url: base_url + 'sendsmsmail/getRecipientsByRole',
        type: 'POST',
        data: $.extend({ branch_id: branchID, role_id: roleID }, csrfData),
        success: function(d){ $('#recipients').html(d); }
    });
}
</script>
