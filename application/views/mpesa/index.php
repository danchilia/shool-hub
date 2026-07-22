<?php
$currency = $this->data['global_config']['currency_symbol'] ?? 'KES ';
?>
<div class="row">
    <div class="col-md-12">

        <!-- Summary Cards -->
        <div class="row" style="margin-bottom:18px;">
            <div class="col-md-3 col-sm-6">
                <div class="panel" style="border-top:3px solid #10b981;">
                    <div class="panel-body text-center">
                        <div style="font-size:1.8rem;font-weight:800;color:#10b981;"><?=$summary['amount'] ? $currency . number_format($summary['amount'],2) : $currency.'0.00'?></div>
                        <div style="font-size:.75rem;color:#64748b;text-transform:uppercase;letter-spacing:.05em;">Confirmed M-Pesa</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="panel" style="border-top:3px solid #f59e0b;">
                    <div class="panel-body text-center">
                        <div style="font-size:1.8rem;font-weight:800;color:#f59e0b;"><?=$summary['pending'] ?? 0?></div>
                        <div style="font-size:.75rem;color:#64748b;text-transform:uppercase;letter-spacing:.05em;">Pending</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="panel" style="border-top:3px solid #ef4444;">
                    <div class="panel-body text-center">
                        <div style="font-size:1.8rem;font-weight:800;color:#ef4444;"><?=($summary['failed'] ?? 0) + ($summary['cancelled'] ?? 0)?></div>
                        <div style="font-size:.75rem;color:#64748b;text-transform:uppercase;letter-spacing:.05em;">Failed / Cancelled</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="panel" style="border-top:3px solid #0e7490;">
                    <div class="panel-body text-center">
                        <div style="font-size:1.8rem;font-weight:800;color:#0e7490;"><?=$summary['total']?></div>
                        <div style="font-size:.75rem;color:#64748b;text-transform:uppercase;letter-spacing:.05em;">Total Transactions</div>
                    </div>
                </div>
            </div>
        </div>

        <section class="panel">
            <header class="panel-heading">
                <div class="row" style="align-items:center;display:flex;flex-wrap:wrap;gap:8px;">
                    <div style="flex:1;">
                        <h4 class="panel-title"><i class="fas fa-mobile-alt" style="color:#10b981;"></i> M-Pesa Transactions</h4>
                    </div>
                    <div>
                        <button id="reconcile-btn" class="btn btn-success btn-sm">
                            <i class="fas fa-sync-alt"></i> Reconcile Pending
                        </button>
                    </div>
                </div>
            </header>

            <!-- Filters -->
            <div class="panel-body" style="padding-bottom:0;">
                <form method="get" action="<?=base_url('mpesa')?>" class="form-inline" style="gap:8px;display:flex;flex-wrap:wrap;">
                    <div class="form-group">
                        <label class="control-label mr-sm">From</label>
                        <input type="date" name="from" class="form-control" value="<?=htmlspecialchars($from)?>">
                    </div>
                    <div class="form-group">
                        <label class="control-label mr-sm">To</label>
                        <input type="date" name="to" class="form-control" value="<?=htmlspecialchars($to)?>">
                    </div>
                    <div class="form-group">
                        <select name="status" class="form-control">
                            <option value="all"       <?=$filter=='all'?'selected':''?>>All Status</option>
                            <option value="completed" <?=$filter=='completed'?'selected':''?>>Completed</option>
                            <option value="pending"   <?=$filter=='pending'?'selected':''?>>Pending</option>
                            <option value="failed"    <?=$filter=='failed'?'selected':''?>>Failed</option>
                            <option value="cancelled" <?=$filter=='cancelled'?'selected':''?>>Cancelled</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-default btn-sm"><i class="fas fa-search"></i> Filter</button>
                </form>
            </div>

            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-hover data-table" id="mpesa-table">
                        <thead>
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
                            $badge = 'badge-secondary';
                            $icon  = 'fa-clock';
                            if ($t['status'] === 'completed')  { $badge = 'badge-success'; $icon = 'fa-check-circle'; }
                            if ($t['status'] === 'failed')     { $badge = 'badge-danger';  $icon = 'fa-times-circle'; }
                            if ($t['status'] === 'cancelled')  { $badge = 'badge-warning'; $icon = 'fa-ban'; }
                            if ($t['status'] === 'pending')    { $badge = 'badge-info';    $icon = 'fa-hourglass-half'; }
                            ?>
                            <tr>
                                <td><?=$i+1?></td>
                                <td><?=isset($t['created_at']) ? date('d M Y H:i', strtotime($t['created_at'])) : '-'?></td>
                                <td>
                                    <?php if ($t['first_name']): ?>
                                        <?=$t['first_name'].' '.$t['last_name']?>
                                        <small class="text-muted d-block"><?=$t['register_no']?></small>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td><?=$t['phone_number']?></td>
                                <td><strong><?=$currency.number_format($t['amount'],2)?></strong></td>
                                <td>
                                    <span class="badge <?=$badge?>" style="font-size:.78rem;padding:4px 10px;">
                                        <i class="fas <?=$icon?>"></i> <?=ucfirst($t['status'])?>
                                    </span>
                                </td>
                                <td>
                                    <code><?=htmlspecialchars($t['transaction_id'] ?: '—')?></code>
                                </td>
                                <td>
                                    <small style="font-family:monospace;font-size:.7rem;"><?=htmlspecialchars($t['checkout_request_id'])?></small>
                                </td>
                                <td>
                                    <?php if ($t['status'] === 'pending'): ?>
                                    <button class="btn btn-xs btn-default verify-btn icon"
                                        data-id="<?=htmlspecialchars($t['checkout_request_id'])?>"
                                        title="Verify with Safaricom">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <?php else: ?>
                                    <span class="text-muted" style="font-size:.75rem;">—</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($transactions)): ?>
                            <tr><td colspan="9" class="text-center text-muted" style="padding:32px;">No M-Pesa transactions found for this period.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
$(function() {
    // Reconcile all pending
    $('#reconcile-btn').on('click', function() {
        var $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Reconciling...');
        $.post(base_url + 'mpesa/reconcile_all', {}, function(data) {
            swal(data.message);
            if (data.reconciled > 0) setTimeout(function(){ location.reload(); }, 1500);
        }, 'json').always(function() {
            $btn.prop('disabled', false).html('<i class="fas fa-sync-alt"></i> Reconcile Pending');
        });
    });

    // Verify single transaction
    $(document).on('click', '.verify-btn', function() {
        var $btn    = $(this);
        var cid     = $btn.data('id');
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        $.post(base_url + 'mpesa/verify_transaction', {checkout_request_id: cid}, function(data) {
            swal(data.message);
            if (data.status === 'completed') setTimeout(function(){ location.reload(); }, 1200);
        }, 'json').always(function() {
            $btn.prop('disabled', false).html('<i class="fas fa-search"></i>');
        });
    });
});
</script>
