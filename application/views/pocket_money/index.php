<style>
@media (prefers-color-scheme:dark) {
    .card        { background:#2b2b3a; border-color:#3a3a50; }
    .card-header { background:#232333; border-color:#3a3a50; }
    .table-light { --bs-table-bg:#232333; }
    .stat-card   { background:#1a2e1a !important; }
}
:root[data-theme="dark"]  .card        { background:#2b2b3a; border-color:#3a3a50; }
:root[data-theme="dark"]  .card-header { background:#232333; border-color:#3a3a50; }
:root[data-theme="dark"]  .table-light { --bs-table-bg:#232333; }
:root[data-theme="dark"]  .stat-card   { background:#1a2e1a !important; }
:root[data-theme="light"] .card        { background:#fff;    border-color:#dee2e6; }
:root[data-theme="light"] .card-header { background:#f8f9fa; border-color:#dee2e6; }
:root[data-theme="light"] .table-light { --bs-table-bg:#f8f9fa; }
:root[data-theme="light"] .stat-card   { background:#f0fdf4 !important; }
</style>

<?php
$students  = isset($students)  ? $students  : array();
$totalHeld = isset($totalHeld) ? $totalHeld : 0;
?>

<div class="container-fluid">

    <!-- Summary stats -->
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="card stat-card border-start border-success border-3 h-100">
                <div class="card-body text-center py-3">
                    <div class="fs-4 fw-bold text-success">KES <?php echo number_format($totalHeld, 2); ?></div>
                    <small class="text-muted">Total Cash Held for Students</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-start border-primary border-3 h-100">
                <div class="card-body text-center py-3">
                    <div class="fs-4 fw-bold text-primary"><?php echo count($students); ?></div>
                    <small class="text-muted">Enrolled Students</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-start border-warning border-3 h-100">
                <div class="card-body text-center py-3">
                    <div class="fs-4 fw-bold text-warning"><?php echo count(array_filter($students, fn($s) => $s['balance'] > 0)); ?></div>
                    <small class="text-muted">Students with Balance</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Students table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-wallet me-2"></i>All Students — Pocket Money Balances</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0" id="pm-table">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Reg. No.</th>
                            <th>Name</th>
                            <th>Class</th>
                            <th>Stream</th>
                            <th>Balance (KES)</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($students)): ?>
                        <tr><td colspan="7" class="text-center py-4 text-muted">No students found.</td></tr>
                        <?php else: foreach ($students as $i => $s): ?>
                        <tr>
                            <td><?php echo $i + 1; ?></td>
                            <td><?php echo htmlspecialchars($s['register_no']); ?></td>
                            <td><?php echo htmlspecialchars($s['first_name'] . ' ' . $s['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($s['class'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($s['section_name'] ?? ''); ?></td>
                            <td class="fw-semibold <?php echo $s['balance'] > 0 ? 'text-success' : 'text-muted'; ?>">
                                <?php echo number_format($s['balance'], 2); ?>
                            </td>
                            <td>
                                <a href="<?php echo base_url('pocket_money/ledger/' . $s['student_id']); ?>" class="btn btn-xs btn-outline-primary" title="View Ledger">
                                    <i class="fas fa-list-ul me-1"></i>Ledger
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
$(function(){
    $('#pm-table').DataTable({
        order: [[2, 'asc']],
        pageLength: 50,
        columnDefs: [{ orderable: false, targets: [6] }]
    });
});
</script>
