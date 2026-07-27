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
        <!-- Nav tabs -->
        <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs px-3 pt-2">
                <li class="nav-item">
                    <a class="nav-link active" href="#list" data-bs-toggle="tab">
                        <i class="fas fa-list-ul me-1"></i><?php echo translate('account') . ' ' . translate('list'); ?>
                    </a>
                </li>
                <?php if (get_permission('account', 'is_add')): ?>
                <li class="nav-item">
                    <a class="nav-link" href="#create" data-bs-toggle="tab">
                        <i class="far fa-edit me-1"></i><?php echo translate('create') . ' ' . translate('account'); ?>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="tab-content">
            <!-- List Tab -->
            <div id="list" class="tab-pane fade show active p-3">
                <div class="export_title"><?php echo translate('account') . ' ' . translate('list'); ?></div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm table-export mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="50"><?php echo translate('sl'); ?></th>
                                <?php if (is_superadmin_loggedin()): ?>
                                <th><?php echo translate('branch'); ?></th>
                                <?php endif; ?>
                                <th><?php echo translate('account') . ' ' . translate('name'); ?></th>
                                <th><?php echo translate('account') . ' ' . translate('number'); ?></th>
                                <th><?php echo translate('description'); ?></th>
                                <th><?php echo translate('date'); ?></th>
                                <th><?php echo translate('action'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 1; foreach ($accountslist as $row): ?>
                            <tr>
                                <td><?php echo $count++; ?></td>
                                <?php if (is_superadmin_loggedin()): ?>
                                <td><?php echo html_escape($row['branch_name']); ?></td>
                                <?php endif; ?>
                                <td><?php echo html_escape($row['name']); ?></td>
                                <td><?php echo html_escape($row['number']); ?></td>
                                <td><?php echo html_escape($row['description']); ?></td>
                                <td><?php echo _d($row['created_at']); ?></td>
                                <td class="text-nowrap">
                                    <?php if (get_permission('account', 'is_edit')): ?>
                                    <a href="<?php echo base_url('accounting/edit/' . $row['id']); ?>" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-pen-nib"></i>
                                    </a>
                                    <?php endif; ?>
                                    <?php if (get_permission('account', 'is_delete')): ?>
                                    <?php echo btn_delete('accounting/delete/' . $row['id']); ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Create Tab -->
            <?php if (get_permission('account', 'is_add')): ?>
            <div class="tab-pane fade p-3" id="create">
                <?php echo form_open($this->uri->uri_string(), array('class' => 'frm-submit')); ?>
                <div class="row">
                    <?php if (is_superadmin_loggedin()): ?>
                    <div class="col-md-9 offset-md-3 mb-3">
                        <div class="row">
                            <label class="col-md-4 col-form-label"><?php echo translate('branch'); ?> <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <?php
                                $arrayBranch = $this->app_lib->getSelectList('branch');
                                echo form_dropdown("branch_id", $arrayBranch, "", "class='form-control' id='branch_id' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                                ?>
                                <span class="error"></span>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="col-md-9 offset-md-3 mb-3">
                        <div class="row">
                            <label class="col-md-4 col-form-label"><?php echo translate('account') . ' ' . translate('name'); ?> <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="account_name" value="<?php echo set_value('account_name'); ?>">
                                <span class="error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9 offset-md-3 mb-3">
                        <div class="row">
                            <label class="col-md-4 col-form-label"><?php echo translate('account') . ' ' . translate('number'); ?></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="account_number" value="<?php echo set_value('account_number'); ?>">
                                <span class="error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9 offset-md-3 mb-3">
                        <div class="row">
                            <label class="col-md-4 col-form-label"><?php echo translate('description'); ?></label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="description" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9 offset-md-3 mb-4">
                        <div class="row">
                            <label class="col-md-4 col-form-label"><?php echo translate('opening_balance'); ?></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="opening_balance" value="<?php echo set_value('opening_balance', 0); ?>">
                                <span class="error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 offset-md-5">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-plus-circle me-1"></i><?php echo translate('save'); ?>
                        </button>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
