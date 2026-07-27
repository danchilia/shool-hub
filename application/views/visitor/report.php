<style>
@media (prefers-color-scheme:dark) {
  .card { background:#2b2b3a; border-color:#3a3a50; }
  .card-header { background:#232333; border-color:#3a3a50; }
  .table-light { --bs-table-bg:#2b2b3a; }
}
:root[data-theme="dark"]  .card { background:#2b2b3a; border-color:#3a3a50; }
:root[data-theme="dark"]  .card-header { background:#232333; border-color:#3a3a50; }
:root[data-theme="dark"]  .table-light { --bs-table-bg:#2b2b3a; }
:root[data-theme="light"] .card { background:#fff; border-color:#dee2e6; }
:root[data-theme="light"] .card-header { background:#f8f9fa; border-color:#dee2e6; }
:root[data-theme="light"] .table-light { --bs-table-bg:#f8f9fa; }
</style>

<div class="content-header">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h4 class="mb-0"><i class="fas fa-chart-bar me-2 text-primary"></i>Visitor Report</h4>
        <a href="<?php echo base_url('visitor'); ?>" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Today
        </a>
    </div>
</div>

<div class="container-fluid">

    <!-- Date range filter -->
    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" action="" class="d-flex align-items-center gap-3 flex-wrap">
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

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Total: <strong><?php echo count($visitors); ?></strong> visitors</span>
            <button onclick="window.print()" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-print me-1"></i>Print
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0" id="report-table">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Visitor Name</th>
                            <th>ID No.</th>
                            <th>Phone</th>
                            <th>Purpose</th>
                            <th>Person to Visit</th>
                            <th>Vehicle</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Duration</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($visitors)): ?>
                        <tr><td colspan="11" class="text-center py-4 text-muted">No visitors in this period.</td></tr>
                        <?php else: foreach ($visitors as $i => $v): ?>
                        <tr>
                            <td><?php echo $i + 1; ?></td>
                            <td><?php echo date('d M Y', strtotime($v->check_in)); ?></td>
                            <td><?php echo html_escape($v->visitor_name); ?></td>
                            <td><?php echo html_escape($v->visitor_id_no); ?></td>
                            <td><?php echo html_escape($v->visitor_phone); ?></td>
                            <td><?php echo html_escape($v->purpose); ?></td>
                            <td><?php echo html_escape($v->person_to_visit); ?></td>
                            <td><?php echo html_escape($v->vehicle_reg); ?></td>
                            <td><?php echo date('h:i A', strtotime($v->check_in)); ?></td>
                            <td><?php echo $v->check_out ? date('h:i A', strtotime($v->check_out)) : '—'; ?></td>
                            <td>
                                <?php
                                if ($v->check_out) {
                                    $mins = (strtotime($v->check_out) - strtotime($v->check_in)) / 60;
                                    echo $mins < 60 ? round($mins) . ' min' : round($mins/60, 1) . ' hr';
                                } else { echo 'Ongoing'; }
                                ?>
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
    $('#report-table').DataTable({ order:[[1,'desc']], pageLength:50 });
});
</script>
