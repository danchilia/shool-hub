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
                        <i class="fas fa-list-ul me-1"></i><?php echo translate('template') . ' ' . translate('list'); ?>
                    </button>
                </li>
                <?php if (get_permission('sendsmsmail_template', 'is_add')): ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-create" role="tab">
                        <i class="far fa-edit me-1"></i><?php echo translate('create') . ' ' . translate('template'); ?>
                    </button>
                </li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">

                <!-- Template List -->
                <div class="tab-pane fade show active" id="tab-list" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm mb-0 table_default">
                            <thead class="table-light">
                                <tr>
                                    <th><?php echo translate('sl'); ?></th>
                                    <?php if (is_superadmin_loggedin()): ?>
                                    <th><?php echo translate('branch'); ?></th>
                                    <?php endif; ?>
                                    <th><?php echo translate('name'); ?></th>
                                    <th><?php echo translate('body'); ?></th>
                                    <th><?php echo translate('action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $count = 1; foreach ($templetelist as $row): ?>
                                <tr>
                                    <td><?php echo $count++; ?></td>
                                    <?php if (is_superadmin_loggedin()): ?>
                                    <td><?php echo htmlspecialchars($row['branch_name']); ?></td>
                                    <?php endif; ?>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars(mb_strimwidth(strip_tags($row['body']), 0, 70, '...')); ?></td>
                                    <td class="text-nowrap">
                                        <?php if (get_permission('sendsmsmail_template', 'is_edit')): ?>
                                        <a href="<?php echo base_url('sendsmsmail/template_edit/' . $type . '/' . $row['id']); ?>"
                                            class="btn btn-sm btn-outline-secondary" title="<?php echo translate('edit'); ?>">
                                            <i class="fas fa-pen-nib"></i>
                                        </a>
                                        <?php endif; ?>
                                        <?php if (get_permission('sendsmsmail_template', 'is_delete')): ?>
                                        <?php echo btn_delete('sendsmsmail/template_delete/' . $row['id']); ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Create Template -->
                <?php if (get_permission('sendsmsmail_template', 'is_add')): ?>
                <div class="tab-pane fade" id="tab-create" role="tabpanel">
                    <?php echo form_open($this->uri->uri_string(), array('class' => 'frm-submit')); ?>
                    <div class="row g-3">
                        <?php if (is_superadmin_loggedin()): ?>
                        <div class="col-md-6">
                            <label class="form-label"><?php echo translate('branch'); ?> <span class="text-danger">*</span></label>
                            <?php
                            $arrayBranch = $this->app_lib->getSelectList('branch');
                            echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                            ?>
                            <span class="error small text-danger d-block"></span>
                        </div>
                        <?php endif; ?>
                        <div class="col-md-6">
                            <label class="form-label"><?php echo translate('name'); ?> <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="template_name" value="">
                            <span class="error small text-danger d-block"></span>
                        </div>
                        <div class="col-12">
                            <label class="form-label"><?php echo translate('message'); ?> <span class="text-danger">*</span></label>
                            <textarea name="message" id="message" class="summernote"></textarea>
                            <span class="error small text-danger d-block"></span>
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
    $('.btn_tag').on('click', function(){
        $('.summernote').summernote('editor.insertText', $(this).data('value'));
    });
});
</script>
