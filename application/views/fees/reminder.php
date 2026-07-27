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
                        <i class="fas fa-list-ul me-1"></i><?php echo translate('reminder') . ' ' . translate('list'); ?>
                    </button>
                </li>
                <?php if (get_permission('fees_reminder', 'is_add')): ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-create" role="tab">
                        <i class="far fa-edit me-1"></i><?php echo translate('add') . ' ' . translate('reminder'); ?>
                    </button>
                </li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">

                <!-- List -->
                <div class="tab-pane fade show active" id="tab-list" role="tabpanel">
                    <div class="export_title"><?php echo translate('reminder') . ' ' . translate('list'); ?></div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm mb-0 table-export">
                            <thead class="table-light">
                                <tr>
                                    <th width="50"><?php echo translate('sl'); ?></th>
                                    <?php if (is_superadmin_loggedin()): ?>
                                    <th><?php echo translate('branch'); ?></th>
                                    <?php endif; ?>
                                    <th><?php echo translate('frequency'); ?><?php echo help_tip('When to send reminder: Before due date or After due date'); ?></th>
                                    <th><?php echo translate('days'); ?><?php echo help_tip('Number of days before/after due date to send SMS. Example: 7 (sends reminder 7 days before due)'); ?></th>
                                    <th><?php echo translate('notify'); ?></th>
                                    <th><?php echo translate('action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $count = 1; foreach ($reminderlist as $row): ?>
                                <tr>
                                    <td><?php echo $count++; ?></td>
                                    <?php if (is_superadmin_loggedin()): ?>
                                    <td><?php echo htmlspecialchars($row['branch_name']); ?></td>
                                    <?php endif; ?>
                                    <td><?php echo ucfirst($row['frequency']); ?></td>
                                    <td><?php echo $row['days']; ?></td>
                                    <td><?php
                                        echo ($row['student'] == 1 ? '- ' . translate('student') . '<br>' : '');
                                        echo ($row['guardian'] == 1 ? '- ' . translate('guardian') : '');
                                    ?></td>
                                    <td class="text-nowrap">
                                        <?php if (get_permission('fees_reminder', 'is_edit')): ?>
                                        <a href="<?php echo base_url('fees/edit_reminder/' . $row['id']); ?>"
                                            class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="<?php echo translate('edit'); ?>">
                                            <i class="fas fa-pen-nib"></i>
                                        </a>
                                        <?php endif; ?>
                                        <?php if (get_permission('fees_reminder', 'is_delete')): ?>
                                        <?php echo btn_delete('fees/reminder_delete/' . $row['id']); ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Create -->
                <?php if (get_permission('fees_reminder', 'is_add')): ?>
                <div class="tab-pane fade" id="tab-create" role="tabpanel">
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
                            <label class="form-label"><?php echo translate('frequency'); ?> <span class="text-danger">*</span></label>
                            <?php
                            $arrayType = array(
                                '' => translate('select'),
                                'before' => translate('before'),
                                'after'  => translate('after'),
                            );
                            echo form_dropdown("frequency", $arrayType, set_value('frequency'), "class='form-control' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                            ?>
                            <span class="error small text-danger d-block"></span>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><?php echo translate('days'); ?> <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="days" value="<?php echo set_value('days'); ?>" autocomplete="off">
                            <span class="error small text-danger d-block"></span>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label"><?php echo translate('message'); ?><?php echo help_tip('SMS text sent to parents. Use placeholders: {guardian_name}, {student_name}, {amount}, {due_date}, {school_name}'); ?></label>
                            <textarea class="form-control" id="message" name="message" rows="3"></textarea>
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
                                <input class="form-check-input" type="checkbox" name="chk_student" id="chkStudent">
                                <label class="form-check-label" for="chkStudent"><?php echo translate('student'); ?></label>
                            </div>
                            <div class="form-check mt-1">
                                <input class="form-check-input" type="checkbox" name="chk_guardian" id="chkGuardian">
                                <label class="form-check-label" for="chkGuardian"><?php echo translate('guardian'); ?></label>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
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
