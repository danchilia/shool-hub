<?php
$widget = (is_superadmin_loggedin() ? 3 : 4);
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
                <div class="col-md-3">
                    <label class="form-label"><?php echo translate('branch'); ?> <span class="text-danger">*</span></label>
                    <?php
                    $arrayBranch = $this->app_lib->getSelectList('branch');
                    echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' id='branch_id' onchange='getClassByBranch(this.value)' required data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                    ?>
                </div>
                <?php endif; ?>
                <div class="col-md-<?php echo $widget; ?>">
                    <label class="form-label"><?php echo translate('class'); ?></label>
                    <?php
                    $arrayClass = $this->app_lib->getClass($branch_id);
                    echo form_dropdown("class_id", $arrayClass, set_value('class_id'), "class='form-control' id='class_id' onchange='getSectionByClass(this.value,0)' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                    ?>
                </div>
                <div class="col-md-<?php echo $widget; ?>">
                    <label class="form-label"><?php echo translate('section'); ?></label>
                    <?php
                    $arraySection = $this->app_lib->getSections(set_value('class_id'), false);
                    echo form_dropdown("section_id", $arraySection, set_value('section_id'), "class='form-control' id='section_id' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                    ?>
                </div>
                <div class="col-md-<?php echo $widget; ?>">
                    <label class="form-label"><?php echo translate('date'); ?> <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                        <input type="text" class="form-control daterange" name="daterange"
                            value="<?php echo set_value('daterange', date('Y/m/d') . ' - ' . date('Y/m/d')); ?>" required>
                    </div>
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
            <h6 class="mb-0"><i class="fas fa-list-ol me-2"></i><?php echo translate('fees_fine_reports'); ?></h6>
        </div>
        <div class="card-body p-0">
            <div class="export_title px-3 pt-2"><?php echo translate('fees_fine_reports'); ?></div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm mb-0 table-export">
                    <thead class="table-light">
                        <tr>
                            <th><?php echo translate('sl'); ?></th>
                            <th><?php echo translate('student'); ?></th>
                            <th><?php echo translate('register_no'); ?></th>
                            <th><?php echo translate('roll'); ?></th>
                            <th><?php echo translate('date'); ?></th>
                            <th><?php echo translate('class'); ?></th>
                            <th><?php echo translate('collect_by'); ?></th>
                            <th><?php echo translate('payment_via'); ?></th>
                            <th><?php echo translate('fees_type'); ?></th>
                            <th><?php echo translate('fine'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $count     = 1;
                        $totalfine = 0;
                        foreach ($invoicelist as $row):
                            $totalfine += $row['fine'];
                        ?>
                        <tr>
                            <td><?php echo $count++; ?></td>
                            <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['register_no']); ?></td>
                            <td><?php echo htmlspecialchars($row['roll']); ?></td>
                            <td><?php echo _d($row['date']); ?></td>
                            <td><?php echo htmlspecialchars($row['class_name'] . ' (' . $row['section_name'] . ')'); ?></td>
                            <td><?php
                                if ($row['collect_by'] == 'online') {
                                    echo 'Online';
                                } else {
                                    echo get_type_name_by_id('staff', $row['collect_by']);
                                }
                            ?></td>
                            <td><?php echo htmlspecialchars($row['pay_via']); ?></td>
                            <td><?php echo htmlspecialchars($row['type_name']); ?></td>
                            <td><?php echo $currency_symbol . $row['fine']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="9"></th>
                            <th><?php echo $currency_symbol . number_format($totalfine, 2, '.', ''); ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
