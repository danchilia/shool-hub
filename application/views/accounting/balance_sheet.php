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
    <?php if (is_superadmin_loggedin()): ?>
    <div class="card mb-3">
        <div class="card-body">
            <?php echo form_open($this->uri->uri_string(), array('class' => 'validate')); ?>
            <div class="row g-3 align-items-end justify-content-center">
                <div class="col-md-5">
                    <label class="form-label"><?php echo translate('branch'); ?> <span class="text-danger">*</span></label>
                    <?php
                    $arrayBranch = $this->app_lib->getSelectList('branch');
                    echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                    ?>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i><?php echo translate('filter'); ?>
                    </button>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($branch_id)): ?>
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0"><i class="fas fa-list-ol me-2"></i><?php echo translate('balance') . ' ' . translate('sheet'); ?></h6>
        </div>
        <div class="card-body p-0">
            <div class="export_title px-3 pt-2">Balance Sheet</div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm table-export mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="50"><?php echo translate('sl'); ?></th>
                            <th><?php echo translate('account') . ' ' . translate('name'); ?></th>
                            <th><?php echo translate('total_dr'); ?></th>
                            <th><?php echo translate('total_cr'); ?></th>
                            <th><?php echo translate('balance'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_bal = 0;
                        $count     = 1;
                        foreach ($results as $row):
                            $total_bal += $row['fbalance'];
                        ?>
                        <tr>
                            <td><?php echo $count++; ?></td>
                            <td><?php echo html_escape($row['ac_name']); ?></td>
                            <td><?php echo $currency_symbol . $row['total_dr']; ?></td>
                            <td><?php echo $currency_symbol . $row['total_cr']; ?></td>
                            <td><?php echo $currency_symbol . $row['fbalance']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4"></th>
                            <th><?php echo $currency_symbol . number_format($total_bal, 2, '.', ''); ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
