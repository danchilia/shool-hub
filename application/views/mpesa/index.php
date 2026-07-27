<?php
$currency = $global_config['currency_symbol'] ?? 'KES ';
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

    <!-- Summary Cards -->
    <div class="row g-3 mb-3">
        <div class="col-md-3 col-sm-6">
            <div class="card h-100" style="border-top:3px solid #10b981;">
                <div class="card-body text-center">
                    <div style="font-size:1.8rem;font-weight:800;color:#10b981;">
                        <?php echo $summary['amount'] ? $currency . number_format($summary['amount'], 2) : $currency . '0.00'; ?>
                    </div>
                    <div style="font-size:.75rem;color:#64748b;text-transform:uppercase;letter-spacing:.05em;">Confirmed M-Pesa</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card h-100" style="border-top:3px solid #f59e0b;">
                <div class="card-body text-center">
                    <div style="font-size:1.8rem;font-weight:800;color:#f59e0b;"><?php echo $summary['pending'] ?? 0; ?></div>
                    <div style="font-size:.75rem;color:#64748b;text-transform:uppercase;letter-spacing:.05em;">Pending</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card h-100" style="border-top:3px solid #ef4444;">
                <div class="card-body text-center">
                    <div style="font-size:1.8rem;font-weight:800;color:#ef4444;"><?php echo ($summary['failed'] ?? 0) + ($summary['cancelled'] ?? 0); ?></div>
                    <div style="font-size:.75rem;color:#64748b;text-transform:uppercase;letter-spacing:.05em;">Failed / Cancelled</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card h-100" style="border-top:3px solid #0e7490;">
                <div class="card-body text-center">
                    <div style="font-size:1.8rem;font-weight:800;color:#0e7490;"><?php echo $summary['total']; ?></div>
                    <div style="font-size:.75rem;color:#64748b;text-transform:uppercase;letter-spacing:.05em;">Total Transactions</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Card -->
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h6 class="mb-0"><i class="fas fa-mobile-alt me-2" style="color:#10b981;"></i>M-Pesa Transactions</h6>
            <button id="reconcile-btn" class="btn btn-sm btn-success">
                <i class="fas fa-sync-alt me-1"></i>Reconcile Pending
            </button>
        </div>

        <!-- Filters -->
        <div class="card-body pb-0">
            <form method="get" action="<?php echo base_url('mpesa'); ?>" class="d-flex flex-wrap gap-2 align-items-end">
                <div>
                    <label class="form-label mb-1">From</label>
                    <input type="date" name="from" class="form-control form-control-sm" value="<?php echo htmlspecialchars($from); ?>">
                </div>
                <div>
                    <label class="form-label mb-1">To</label>
                    <input type="date" name="to" class="form-control form-control-sm" value="<?php echo htmlspecialchars($to); ?>">
                </div>
                <div>
                    <label class="form-label mb-1">&nbsp;</label>
                    <select name="status" class="form-control form-control-sm">
                        <option value="all"       <?php echo $filter == 'all'       ? 'selected' : ''; ?>>All Status</option>
                        <option value="completed" <?php echo $filter == 'completed' ? 'selected' : ''; ?>>Completed</option>
                        <option value="pending"   <?php echo $filter == 'pending'   ? 'selected' : ''; ?>>Pending</option>
                        <option value="failed"    <?php echo $filter == 'failed'    ? 'selected' : ''; ?>>Failed</option>
                        <option value="cancelled" <?php echo $filter == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-search me-1"></i>Filter
                    </button>
                </div>
            </form>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0" id="mpesa-table">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Student</th>
                            <th>Phone</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>M-Pesa Ref</th>
                            <th>Checkout ID</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($transactions as $i => $t): ?>
                        <?php
                        $badgeCls = 'bg-secondary';
                        $icon     = 'fa-clock';
                        if ($t['status'] === 'completed') { $badgeCls = 'bg-success'; $icon = 'fa-check-circle'; }
                        if ($t['status'] === 'failed')    { $badgeCls = 'bg-danger';  $icon = 'fa-times-circle'; }
                        if ($t['status'] === 'cancelled') { $badgeCls = 'bg-warning text-dark'; $icon = 'fa-ban'; }
                        if ($t['status'] === 'pending')   { $badgeCls = 'bg-info text-dark';    $icon = 'fa-hourglass-half'; }
                        ?>
                        <tr>
                            <td><?php echo $i + 1; ?></td>
                            <td><?php echo isset($t['created_at']) ? date('d M Y H:i', strtotime($t['created_at'])) : '-'; ?></td>
                            <td>
                                <?php if ($t['first_name']): ?>
                                    <?php echo htmlspecialchars($t['first_name'] . ' ' . $t['last_name']); ?>
                                    <small class="text-muted d-block"><?php echo htmlspecialchars($t['register_no']); ?></small>
                                <?php else: ?>
                                    <span class="text-muted">&mdash;</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($t['phone_number']); ?></td>
                            <td><strong><?php echo $currency . number_format($t['amount'], 2); ?></strong></td>
                            <td>
                                <span class="badge <?php echo $badgeCls; ?>" style="font-size:.78rem;padding:4px 10px;">
                                    <i class="fas <?php echo $icon; ?>"></i> <?php echo ucfirst($t['status']); ?>
                                </span>
                            </td>
                            <td><code><?php echo htmlspecialchars($t['transaction_id'] ?: '&mdash;'); ?></code></td>
                            <td><small style="font-family:monospace;font-size:.7rem;"><?php echo htmlspecialchars($t['checkout_request_id']); ?></small></td>
                            <td>
                                <?php if ($t['status'] === 'pending'): ?>
                                <button class="btn btn-sm btn-outline-secondary verify-btn"
                                    data-id="<?php echo htmlspecialchars($t['checkout_request_id']); ?>"
                                    title="Verify with Safaricom">
                                    <i class="fas fa-search"></i>
                                </button>
                                <?php else: ?>
                                <span class="text-muted" style="font-size:.75rem;">&mdash;</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($transactions)): ?>
                        <tr><td colspan="9" class="text-center text-muted py-4">No M-Pesa transactions found for this period.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
$(function(){
    $('#reconcile-btn').on('click', function(){
        var $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Reconciling...');
        $.post(base_url + 'mpesa/reconcile_all', csrfData, function(data){
            swal(data.message);
            if (data.reconciled > 0) setTimeout(function(){ location.reload(); }, 1500);
        }, 'json').always(function(){
            $btn.prop('disabled', false).html('<i class="fas fa-sync-alt me-1"></i>Reconcile Pending');
        });
    });

    $(document).on('click', '.verify-btn', function(){
        var $btn = $(this);
        var cid  = $btn.data('id');
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        $.post(base_url + 'mpesa/verify_transaction', $.extend({checkout_request_id: cid}, csrfData), function(data){
            swal(data.message);
            if (data.status === 'completed') setTimeout(function(){ location.reload(); }, 1200);
        }, 'json').always(function(){
            $btn.prop('disabled', false).html('<i class="fas fa-search"></i>');
        });
    });
});
</script>
