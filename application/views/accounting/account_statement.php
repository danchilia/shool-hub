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
                <div class="col-md-<?php echo $widget; ?>">
                    <label class="form-label"><?php echo translate('branch'); ?> <span class="text-danger">*</span></label>
                    <?php
                    $arrayBranch = $this->app_lib->getSelectList('branch');
                    echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' id='branch_id' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                    ?>
                </div>
                <?php endif; ?>
                <div class="col-md-<?php echo $widget; ?>">
                    <label class="form-label"><?php echo translate('account'); ?> <span class="text-danger">*</span></label>
                    <?php
                    $accountlist = $this->app_lib->getSelectByBranch('accounts', $branch_id);
                    echo form_dropdown("account_id", $accountlist, set_value('account_id'), "class='form-control' id='account_id' required data-plugin-selectTwo data-width='100%'");
                    ?>
                </div>
                <div class="col-md-<?php echo $widget; ?>">
                    <label class="form-label"><?php echo translate('type'); ?> <span class="text-danger">*</span></label>
                    <?php
                    $typelList = array(
                        ''        => translate('select'),
                        'all'     => translate('all'),
                        'expense' => translate('expense') . ' (Dr.)',
                        'deposit' => translate('income') . ' (Cr.)',
                    );
                    echo form_dropdown("type", $typelList, set_value('type'), "class='form-control' required data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                    ?>
                </div>
                <div class="col-md-<?php echo $widget; ?>">
                    <label class="form-label"><?php echo translate('date'); ?> <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                        <input type="text" class="form-control daterange" name="daterange" value="<?php echo set_value('daterange', date('Y/m/d') . ' - ' . date('Y/m/d')); ?>" required>
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

    <?php if (isset($results)): ?>
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0"><i class="fas fa-list-ol me-2"></i><?php echo translate('account') . ' ' . translate('statement'); ?></h6>
        </div>
        <div class="card-body p-0">
            <div class="export_title px-3 pt-2"><?php echo get_type_name_by_id('accounts', set_value('account_id')); ?> Statement : <?php echo _d($daterange[0]); ?> To <?php echo _d($daterange[1]); ?></div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm table-export mb-0">
                    <thead class="table-light">
                        <tr>
                            <th><?php echo translate('sl'); ?></th>
                            <th><?php echo translate('voucher') . ' ' . translate('head'); ?></th>
                            <th><?php echo translate('ref_no'); ?></th>
                            <th><?php echo translate('description'); ?></th>
                            <th><?php echo translate('date'); ?></th>
                            <th><?php echo translate('dr'); ?>.</th>
                            <th><?php echo translate('cr'); ?>.</th>
                            <th><?php echo translate('balance'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_dr  = 0;
                        $total_cr  = 0;
                        if (!empty($results)) {
                            $count = 1;
                            foreach ($results as $row):
                                $total_dr += $row['dr'];
                                $total_cr += $row['cr'];
                        ?>
                        <tr>
                            <td><?php echo $count++; ?></td>
                            <td><?php echo html_escape($row['v_head']); ?></td>
                            <td><?php echo html_escape($row['ref']); ?></td>
                            <td><?php echo html_escape($row['description']); ?></td>
                            <td><?php echo html_escape(_d($row['date'])); ?></td>
                            <td><?php echo $currency_symbol . number_format($row['dr'], 2, '.', ''); ?></td>
                            <td><?php echo $currency_symbol . number_format($row['cr'], 2, '.', ''); ?></td>
                            <td><?php echo (set_value('type') == 'all' ? $currency_symbol . number_format($row['bal'], 2, '.', '') : $currency_symbol . '0.00'); ?></td>
                        </tr>
                        <?php endforeach; } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5"></th>
                            <th><?php echo $currency_symbol . number_format($total_dr, 2, '.', ''); ?></th>
                            <th><?php echo $currency_symbol . number_format($total_cr, 2, '.', ''); ?></th>
                            <th><?php echo set_value('type') == 'all' ? $currency_symbol . number_format($total_cr - $total_dr, 2, '.', '') : $currency_symbol . '0.00'; ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
$(function(){
    $('#branch_id').on('change', function(){
        var branchID = $(this).val();
        $.ajax({
            url: base_url + 'ajax/getDataByBranch',
            type: 'POST',
            data: $.extend({'branch_id': branchID, 'table': 'accounts'}, csrfData),
            success: function(data){ $('#account_id').html(data); }
        });
    });
});
</script>
