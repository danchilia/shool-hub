<style>
@media (prefers-color-scheme:dark) {
    .card           { background:#2b2b3a; border-color:#3a3a50; }
    .card-header    { background:#232333; border-color:#3a3a50; }
    .table-light    { --bs-table-bg:#232333; }
    .student-info   { background:#1a2e1a !important; border-color:#27ae60 !important; }
}
:root[data-theme="dark"]  .card           { background:#2b2b3a; border-color:#3a3a50; }
:root[data-theme="dark"]  .card-header    { background:#232333; border-color:#3a3a50; }
:root[data-theme="dark"]  .table-light    { --bs-table-bg:#232333; }
:root[data-theme="dark"]  .student-info   { background:#1a2e1a !important; border-color:#27ae60 !important; }
:root[data-theme="light"] .card           { background:#fff;    border-color:#dee2e6; }
:root[data-theme="light"] .card-header    { background:#f8f9fa; border-color:#dee2e6; }
:root[data-theme="light"] .table-light    { --bs-table-bg:#f8f9fa; }
:root[data-theme="light"] .student-info   { background:#f0fdf4 !important; border-color:#27ae60 !important; }
</style>

<?php
$student      = isset($student)      ? $student      : array();
$transactions = isset($transactions) ? $transactions : array();
$balance      = isset($balance)      ? $balance      : 0;
$studentId    = (int)($student['student_id'] ?? $student['id'] ?? 0);
?>

<div class="content-header">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <a href="<?php echo base_url('pocket_money'); ?>" class="text-muted small"><i class="fas fa-arrow-left me-1"></i>All Students</a>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-success" onclick="mfp_modal('#deposit-modal')">
                <i class="fas fa-plus me-1"></i>Deposit
            </button>
            <button class="btn btn-sm btn-danger" onclick="mfp_modal('#withdraw-modal')">
                <i class="fas fa-minus me-1"></i>Withdraw
            </button>
        </div>
    </div>
</div>

<div class="container-fluid">

    <!-- Student info card -->
    <div class="card mb-3 student-info border-start border-success border-3">
        <div class="card-body py-2">
            <div class="d-flex align-items-center gap-3">
                <img src="<?php echo get_image_url('student', $student['photo'] ?? ''); ?>" width="56" height="56" class="rounded" style="object-fit:cover">
                <div class="flex-grow-1">
                    <div class="fw-bold"><?php echo htmlspecialchars(($student['first_name'] ?? '') . ' ' . ($student['last_name'] ?? '')); ?></div>
                    <small class="text-muted">
                        <?php echo htmlspecialchars($student['register_no'] ?? ''); ?>
                        <?php if (!empty($student['class'])): ?> | <?php echo htmlspecialchars(trim($student['class'] . ' ' . ($student['section_name'] ?? ''))); ?><?php endif; ?>
                    </small>
                </div>
                <div class="text-end">
                    <div class="fs-4 fw-bold text-success">KES <?php echo number_format($balance, 2); ?></div>
                    <small class="text-muted">Current Balance</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-exchange-alt me-2"></i>Transactions</h5>
        </div>
        <div class="card-body p-0">
            <?php if (empty($transactions)): ?>
            <p class="text-center text-muted py-4">No transactions yet.</p>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0" id="ledger-table">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Received From</th>
                            <th>Amount (KES)</th>
                            <th>Balance After (KES)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transactions as $t): ?>
                        <tr>
                            <td class="text-nowrap"><?php echo date('d M Y H:i', strtotime($t['created_at'])); ?></td>
                            <td>
                                <?php if ($t['transaction_type'] === 'deposit'): ?>
                                    <span class="badge bg-success">Deposit</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Withdrawal</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($t['description'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($t['received_from'] ?? '—'); ?></td>
                            <td class="fw-semibold <?php echo $t['transaction_type'] === 'deposit' ? 'text-success' : 'text-danger'; ?>">
                                <?php echo $t['transaction_type'] === 'deposit' ? '+' : '-'; ?><?php echo number_format($t['amount'], 2); ?>
                            </td>
                            <td class="text-nowrap"><?php echo number_format($t['balance_after'], 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<!-- Deposit Modal -->
<div id="deposit-modal" class="mfp-hide">
    <div class="card mb-0" style="min-width:420px; max-width:500px;">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0"><i class="fas fa-plus-circle me-2 text-success"></i>Deposit Cash</h5>
            <button type="button" class="btn-close ms-2" onclick="$.magnificPopup.close()" title="Close"></button>
        </div>
        <div class="card-body">
            <?php echo form_open('pocket_money/deposit', array('class' => 'frm-submit', 'id' => 'deposit-form')); ?>
            <input type="hidden" name="student_id" value="<?php echo $studentId; ?>">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Amount (KES) <span class="text-danger">*</span></label>
                    <input type="number" name="amount" class="form-control" step="0.01" min="1" placeholder="0.00">
                </div>
                <div class="col-12">
                    <label class="form-label">Received From</label>
                    <input type="text" name="received_from" class="form-control" placeholder="Parent/guardian name">
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <input type="text" name="description" class="form-control" placeholder="e.g. Weekly pocket money">
                </div>
                <div class="col-12">
                    <label class="form-label">Reference No.</label>
                    <input type="text" name="reference_no" class="form-control" placeholder="e.g. M-Pesa transaction code">
                </div>
            </div>
            <div class="mt-3 text-end">
                <button type="button" class="btn btn-secondary me-1" onclick="$.magnificPopup.close()">Cancel</button>
                <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i>Deposit</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<!-- Withdraw Modal -->
<div id="withdraw-modal" class="mfp-hide">
    <div class="card mb-0" style="min-width:420px; max-width:500px;">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0"><i class="fas fa-minus-circle me-2 text-danger"></i>Withdraw Cash</h5>
            <button type="button" class="btn-close ms-2" onclick="$.magnificPopup.close()" title="Close"></button>
        </div>
        <div class="card-body">
            <?php echo form_open('pocket_money/withdraw', array('class' => 'frm-submit', 'id' => 'withdraw-form')); ?>
            <input type="hidden" name="student_id" value="<?php echo $studentId; ?>">
            <div class="row g-3">
                <div class="col-12">
                    <p class="text-muted mb-0">Current balance: <strong class="text-success">KES <?php echo number_format($balance, 2); ?></strong></p>
                </div>
                <div class="col-12">
                    <label class="form-label">Amount (KES) <span class="text-danger">*</span></label>
                    <input type="number" name="amount" class="form-control" step="0.01" min="1" max="<?php echo $balance; ?>" placeholder="0.00">
                </div>
                <div class="col-12">
                    <label class="form-label">Reason</label>
                    <input type="text" name="description" class="form-control" placeholder="e.g. Transport, food">
                </div>
            </div>
            <div class="mt-3 text-end">
                <button type="button" class="btn btn-secondary me-1" onclick="$.magnificPopup.close()">Cancel</button>
                <button type="submit" class="btn btn-danger"><i class="fas fa-save me-1"></i>Withdraw</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
$(function(){
    if ($('#ledger-table').length) {
        $('#ledger-table').DataTable({
            order: [[0, 'desc']],
            pageLength: 50
        });
    }
});
</script>
