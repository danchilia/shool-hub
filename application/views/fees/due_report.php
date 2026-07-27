<?php
$widget = (is_superadmin_loggedin() ? 4 : 6);
$currency_symbol = $global_config['currency_symbol'];
?>
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
    <div class="card mb-3">
        <div class="card-body">
            <?php echo form_open($this->uri->uri_string(), array('class' => 'validate')); ?>
            <div class="row g-3 align-items-end">
                <?php if (is_superadmin_loggedin()): ?>
                <div class="col-md-4">
                    <label class="form-label"><?php echo translate('branch'); ?> <span class="text-danger">*</span></label>
                    <?php
                    $arrayBranch = $this->app_lib->getSelectList('branch');
                    echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' onchange='getClassByBranch(this.value)' required data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                    ?>
                </div>
                <?php endif; ?>
                <div class="col-md-<?php echo $widget; ?>">
                    <label class="form-label"><?php echo translate('class'); ?> <span class="text-danger">*</span></label>
                    <?php
                    $arrayClass = $this->app_lib->getClass($branch_id);
                    echo form_dropdown("class_id", $arrayClass, set_value('class_id'), "class='form-control' id='class_id' onchange='getSectionByClass(this.value,0)' required data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                    ?>
                </div>
                <div class="col-md-<?php echo $widget; ?>">
                    <label class="form-label"><?php echo translate('section'); ?></label>
                    <?php
                    $arraySection = $this->app_lib->getSections(set_value('class_id'), false);
                    echo form_dropdown("section_id", $arraySection, set_value('section_id'), "class='form-control' id='section_id' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                    ?>
                </div>
                <div class="col-md-2">
                    <button type="submit" name="search" value="1" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i><?php echo translate('filter'); ?>
                    </button>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>

    <?php if (isset($invoicelist)): ?>
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0"><i class="fas fa-list-ol me-2"></i><?php echo translate('due_fees_report'); ?></h6>
        </div>
        <div class="card-body p-0">
            <div class="export_title px-3 pt-2"><?php echo translate('due_fees_report'); ?></div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm mb-0 table-export">
                    <thead class="table-light">
                        <tr>
                            <th><?php echo translate('sl'); ?></th>
                            <th><?php echo translate('student'); ?></th>
                            <th><?php echo translate('register_no'); ?></th>
                            <th><?php echo translate('roll'); ?></th>
                            <th><?php echo translate('mobile_no'); ?></th>
                            <th><?php echo translate('total_fees'); ?></th>
                            <th><?php echo translate('total_paid'); ?></th>
                            <th><?php echo translate('total_discount'); ?></th>
                            <th><?php echo translate('total_fine'); ?></th>
                            <th><?php echo translate('total_balance'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $count        = 1;
                        $totalfees    = 0;
                        $totalpaid    = 0;
                        $totaldiscount = 0;
                        $totalfine    = 0;
                        $totalbalance = 0;
                        foreach ($invoicelist as $row):
                            $paid = $row['payment']['total_paid'] + $row['payment']['total_discount'];
                            if ((float)$row['total_fees'] <= (float)$paid) { continue; }
                            $totalfees     += $row['total_fees'];
                            $totalpaid     += $row['payment']['total_paid'];
                            $totaldiscount += $row['payment']['total_discount'];
                            $totalfine     += $row['payment']['total_fine'];
                            $totalbalance  += ($row['total_fees'] - $paid);
                        ?>
                        <tr>
                            <td><?php echo $count++; ?></td>
                            <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['register_no']); ?></td>
                            <td><?php echo htmlspecialchars($row['roll']); ?></td>
                            <td><?php echo htmlspecialchars($row['mobileno']); ?></td>
                            <td><?php echo $currency_symbol . number_format($row['total_fees'],               2, '.', ''); ?></td>
                            <td><?php echo $currency_symbol . number_format($row['payment']['total_paid'],    2, '.', ''); ?></td>
                            <td><?php echo $currency_symbol . number_format($row['payment']['total_discount'],2, '.', ''); ?></td>
                            <td><?php echo $currency_symbol . number_format($row['payment']['total_fine'],    2, '.', ''); ?></td>
                            <td><?php echo $currency_symbol . number_format(($row['total_fees'] - $paid),    2, '.', ''); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5"></th>
                            <th><?php echo $currency_symbol . number_format($totalfees,    2, '.', ''); ?></th>
                            <th><?php echo $currency_symbol . number_format($totalpaid,    2, '.', ''); ?></th>
                            <th><?php echo $currency_symbol . number_format($totaldiscount,2, '.', ''); ?></th>
                            <th><?php echo $currency_symbol . number_format($totalfine,    2, '.', ''); ?></th>
                            <th><?php echo $currency_symbol . number_format($totalbalance, 2, '.', ''); ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
