<div class="content-header">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h4 class="mb-0"><i class="fas fa-chart-bar me-2 text-success"></i>Canteen Sales Report</h4>
        <a href="<?php echo base_url('canteen/pos'); ?>" class="btn btn-sm btn-success"><i class="fas fa-cash-register me-1"></i>Back to POS</a>
    </div>
</div>

<div class="container-fluid">
    <!-- Date filter -->
    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" class="d-flex align-items-center gap-3 flex-wrap">
                <div class="d-flex align-items-center gap-2">
                    <label class="mb-0 fw-semibold">From:</label>
                    <input type="date" name="date_from" class="form-control form-control-sm" style="width:150px" value="<?php echo html_escape($date_from); ?>">
                </div>
                <div class="d-flex align-items-center gap-2">
                    <label class="mb-0 fw-semibold">To:</label>
                    <input type="date" name="date_to" class="form-control form-control-sm" style="width:150px" value="<?php echo html_escape($date_to); ?>">
                </div>
                <button class="btn btn-sm btn-secondary" type="submit">Filter</button>
            </form>
        </div>
    </div>

    <div class="alert alert-success py-2 mb-3 d-flex gap-4">
        <span><i class="fas fa-receipt me-1"></i><strong><?php echo count($transactions); ?></strong> transactions</span>
        <span><i class="fas fa-coins me-1"></i>Total Revenue: <strong>KES <?php echo number_format($total_revenue,2); ?></strong></span>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="sales-table">
                    <thead class="table-light">
                        <tr><th>#</th><th>Date/Time</th><th>Student</th><th>Adm No.</th><th>Amount (KES)</th><th>Balance Before</th><th>Balance After</th></tr>
                    </thead>
                    <tbody>
                        <?php if (empty($transactions)): ?>
                        <tr><td colspan="7" class="text-center py-4 text-muted">No transactions in this period.</td></tr>
                        <?php else: foreach ($transactions as $i => $t): ?>
                        <tr>
                            <td><?php echo $i+1; ?></td>
                            <td><?php echo date('d M Y H:i', strtotime($t->created_at)); ?></td>
                            <td><?php echo html_escape($t->student_name); ?></td>
                            <td><?php echo html_escape($t->admission_no); ?></td>
                            <td><strong><?php echo number_format($t->total_amount,2); ?></strong></td>
                            <td class="text-muted"><?php echo number_format($t->balance_before,2); ?></td>
                            <td><?php echo number_format($t->balance_after,2); ?></td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>$(function(){ $('#sales-table').DataTable({order:[[1,'desc']],pageLength:50}); });</script>
