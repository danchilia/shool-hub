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

<?php $students = isset($students) ? $students : array(); ?>

<div class="content-header">
    <div class="d-flex align-items-center justify-content-end flex-wrap gap-2">
        <a href="<?php echo base_url('health/clinic_log'); ?>" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-notes-medical me-1"></i>Clinic Log
        </a>
    </div>
</div>

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-search me-2"></i>All Students</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0" id="health-search-table">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Reg. No.</th>
                            <th>Name</th>
                            <th>Class</th>
                            <th>Stream</th>
                            <th>Health Record</th>
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
                            <td>
                                <?php if ($s['has_record'] > 0): ?>
                                    <span class="badge bg-success">On file</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">None</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo base_url('health/student/' . $s['student_id']); ?>" class="btn btn-xs btn-outline-danger" title="View Health Record">
                                    <i class="fas fa-heartbeat me-1"></i>View
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
    $('#health-search-table').DataTable({
        order: [[2, 'asc']],
        pageLength: 50,
        columnDefs: [{ orderable: false, targets: [6] }]
    });
});
</script>
