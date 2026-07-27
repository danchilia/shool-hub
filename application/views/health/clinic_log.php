<style>
@media (prefers-color-scheme:dark) {
    .card        { background:#2b2b3a; border-color:#3a3a50; }
    .card-header { background:#232333; border-color:#3a3a50; }
    .table-light { --bs-table-bg:#232333; }
    .filter-bar  { background:#232333 !important; border-color:#3a3a50 !important; }
}
:root[data-theme="dark"]  .card        { background:#2b2b3a; border-color:#3a3a50; }
:root[data-theme="dark"]  .card-header { background:#232333; border-color:#3a3a50; }
:root[data-theme="dark"]  .table-light { --bs-table-bg:#232333; }
:root[data-theme="dark"]  .filter-bar  { background:#232333 !important; border-color:#3a3a50 !important; }
:root[data-theme="light"] .card        { background:#fff;    border-color:#dee2e6; }
:root[data-theme="light"] .card-header { background:#f8f9fa; border-color:#dee2e6; }
:root[data-theme="light"] .table-light { --bs-table-bg:#f8f9fa; }
:root[data-theme="light"] .filter-bar  { background:#f8f9fa !important; border-color:#dee2e6 !important; }
</style>

<?php
$visits = isset($visits) ? $visits : array();
$from   = isset($from)   ? $from   : date('Y-m-01');
$to     = isset($to)     ? $to     : date('Y-m-d');
?>

<div class="content-header">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <a href="<?php echo base_url('health/search'); ?>" class="text-muted small"><i class="fas fa-arrow-left me-1"></i>Health Records</a>
    </div>
</div>

<div class="container-fluid">

    <!-- Date filter -->
    <div class="card mb-3 filter-bar">
        <div class="card-body py-2">
            <form method="get" action="<?php echo base_url('health/clinic_log'); ?>" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label mb-1 small">From</label>
                    <input type="date" name="from" class="form-control form-control-sm" value="<?php echo html_escape($from); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label mb-1 small">To</label>
                    <input type="date" name="to" class="form-control form-control-sm" value="<?php echo html_escape($to); ?>">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-sm btn-outline-secondary w-100">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Visits table -->
    <div class="card">
        <div class="card-body p-0">
            <?php if (empty($visits)): ?>
            <p class="text-center text-muted py-4">No visits in this period.</p>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0" id="clinic-log-table">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Student</th>
                            <th>Reg. No.</th>
                            <th>Class</th>
                            <th>Complaint</th>
                            <th>Diagnosis</th>
                            <th>Referred</th>
                            <th>Attended By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($visits as $v): ?>
                        <tr>
                            <td class="text-nowrap"><?php echo date('d M Y', strtotime($v['visit_date'])); ?></td>
                            <td>
                                <a href="<?php echo base_url('health/student/' . $v['student_id']); ?>">
                                    <?php echo htmlspecialchars($v['student_name'] ?? ''); ?>
                                </a>
                            </td>
                            <td><?php echo htmlspecialchars($v['register_no'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars(trim(($v['class'] ?? '') . ' ' . ($v['section_name'] ?? ''))); ?></td>
                            <td><?php echo htmlspecialchars($v['complaint']); ?></td>
                            <td><?php echo htmlspecialchars($v['diagnosis'] ?? '—'); ?></td>
                            <td>
                                <?php if ($v['referred']): ?>
                                    <span class="badge bg-warning text-dark">Yes</span>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($v['attended_by'] ?? '—'); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<script>
$(function(){
    if ($('#clinic-log-table').length) {
        $('#clinic-log-table').DataTable({
            order: [[0, 'desc']],
            pageLength: 50
        });
    }
});
</script>
