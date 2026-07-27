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
    <?php if ($unmatched_count > 0): ?>
    <div class="alert alert-warning mb-3">
        <i class="fas fa-exclamation-triangle me-1"></i>
        <strong><?php echo $unmatched_count; ?> scans</strong> could not be matched to a student/staff record.
        Go to <a href="<?php echo base_url('biometric/mapping'); ?>">Biometric &gt; ID Mapping</a> to link the missing biometric IDs.
    </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h6 class="mb-0"><i class="fas fa-history me-2"></i>Recent Scan Logs <small class="text-muted fw-normal">(latest 200)</small></h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm table-export mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Scan Time</th>
                            <th>Biometric ID</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Device</th>
                            <th>Source</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $c = 1; if (count($logs)): foreach ($logs as $l): ?>
                        <tr>
                            <td><?php echo $c++; ?></td>
                            <td><?php echo _d($l['scan_time']); ?></td>
                            <td><?php echo html_escape($l['biometric_id']); ?></td>
                            <td>
                                <?php if ($l['matched']): ?>
                                <strong><?php echo html_escape($l['person_name']); ?></strong>
                                <small class="text-muted">(<?php echo ucfirst($l['person_type']); ?>)</small>
                                <?php else: ?>
                                <span class="text-danger">Unmatched</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo ucfirst($l['scan_type']); ?></td>
                            <td><?php echo !empty($l['device_name']) ? html_escape($l['device_name']) . ' <small class="text-muted">(' . html_escape($l['location']) . ')</small>' : '&mdash;'; ?></td>
                            <td><?php echo $l['source'] === 'api_push' ? 'Live Device' : 'CSV Import'; ?></td>
                            <td>
                                <?php if ($l['processed']): ?>
                                <span class="badge bg-success">Attendance Marked</span>
                                <?php else: ?>
                                <span class="badge bg-secondary">Not Processed</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="8" class="text-center text-danger"><?php echo translate('no_information_available'); ?></td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
