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

<div class="content-header">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <a href="<?php echo base_url('fees/group'); ?>" class="text-muted small">
            <i class="fas fa-arrow-left me-1"></i><?php echo translate('fees_group') . ' ' . translate('list'); ?>
        </a>
    </div>
</div>

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="far fa-edit me-2"></i><?php echo translate('edit') . ' ' . translate('fees_group'); ?></h5>
        </div>
        <div class="card-body">
            <?php echo form_open($this->uri->uri_string(), array('class' => 'frm-submit')); ?>
            <input type="hidden" name="group_id" value="<?php echo $group['id']; ?>">
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label"><?php echo translate('group_name'); ?> <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($group['name']); ?>" autocomplete="off">
                    <span class="error small text-danger d-block"></span>
                </div>
                <div class="col-12">
                    <label class="form-label"><?php echo translate('description'); ?></label>
                    <textarea class="form-control" name="description" rows="2"><?php echo htmlspecialchars($group['description']); ?></textarea>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm">
                    <thead class="table-light">
                        <tr>
                            <th width="50">
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="checkbox" id="selectAllchkbox">
                                </div>
                            </th>
                            <th><?php echo translate('fees_type'); ?> <span class="text-danger">*</span></th>
                            <th><?php echo translate('due_date'); ?> <span class="text-danger">*</span></th>
                            <th><?php echo translate('amount'); ?> <span class="text-danger">*</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($fee_types as $key => $row):
                            $details = isset($existing_details_map[$row['id']]) ? $existing_details_map[$row['id']] : array();
                        ?>
                        <tr>
                            <td class="checked-area">
                                <div class="form-check mb-0">
                                    <input class="form-check-input row-chkbox" type="checkbox"
                                        name="elem[<?php echo $key; ?>][fees_type_id]"
                                        value="<?php echo $row['id']; ?>"
                                        <?php echo (!empty($details) && $row['id'] == $details['fee_type_id']) ? 'checked' : ''; ?>>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td>
                                <input type="text" class="form-control form-control-sm"
                                    name="elem[<?php echo $key; ?>][due_date]"
                                    value="<?php echo !empty($details['due_date']) ? $details['due_date'] : ''; ?>"
                                    data-plugin-datepicker data-plugin-options='{"startView": 1}' autocomplete="off">
                                <span class="error small text-danger d-block"></span>
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm"
                                    name="elem[<?php echo $key; ?>][amount]"
                                    value="<?php echo !empty($details['amount']) ? $details['amount'] : '0.00'; ?>"
                                    autocomplete="off">
                                <span class="error small text-danger d-block"></span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end mt-3">
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
    $('#selectAllchkbox').on('change', function(){
        $('.row-chkbox').prop('checked', this.checked);
    });
});
</script>
