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
        <div class="card-header">
            <h6 class="mb-0"><i class="fas fa-list-ol me-2"></i><?php echo translate('transactions'); ?></h6>
        </div>
        <div class="card-body p-0">
            <div class="export_title px-3 pt-2">All Transactions</div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm table-export mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="50"><?php echo translate('sl'); ?></th>
                            <?php if (is_superadmin_loggedin()): ?>
                            <th><?php echo translate('branch'); ?></th>
                            <?php endif; ?>
                            <th><?php echo translate('account') . ' ' . translate('name'); ?></th>
                            <th><?php echo translate('type'); ?></th>
                            <th><?php echo translate('voucher') . ' ' . translate('head'); ?></th>
                            <th><?php echo translate('ref_no'); ?></th>
                            <th><?php echo translate('description'); ?></th>
                            <th><?php echo translate('pay_via'); ?></th>
                            <th><?php echo translate('amount'); ?></th>
                            <th><?php echo translate('dr'); ?></th>
                            <th><?php echo translate('cr'); ?></th>
                            <th><?php echo translate('balance'); ?></th>
                            <th><?php echo translate('date'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $count = 1; foreach ($voucherlist as $row): ?>
                        <tr>
                            <td><?php echo $count++; ?></td>
                            <?php if (is_superadmin_loggedin()): ?>
                            <td><?php echo html_escape(get_type_name_by_id('branch', $row['branch_id'])); ?></td>
                            <?php endif; ?>
                            <td><?php echo (!empty($row['attachments']) ? '<i class="fas fa-paperclip me-1"></i>' : ''); ?><?php echo html_escape($row['ac_name']); ?></td>
                            <td><?php echo ucfirst($row['type']); ?></td>
                            <td><?php echo html_escape($row['v_head']); ?></td>
                            <td><?php echo html_escape($row['ref']); ?></td>
                            <td><?php echo html_escape($row['description']); ?></td>
                            <td><?php echo html_escape($row['via_name']); ?></td>
                            <td><?php echo $currency_symbol . $row['amount']; ?></td>
                            <td><?php echo $currency_symbol . $row['dr']; ?></td>
                            <td><?php echo $currency_symbol . $row['cr']; ?></td>
                            <td><?php echo $currency_symbol . $row['bal']; ?></td>
                            <td><?php echo _d($row['date']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
