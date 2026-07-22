<div class="content-header">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <a href="<?php echo base_url('cbt'); ?>" class="text-muted small"><i class="fas fa-arrow-left me-1"></i>All Exams</a>
            <h4 class="mb-0 mt-1"><i class="fas fa-chart-bar me-2 text-success"></i>Results: <?php echo html_escape($quiz->title); ?></h4>
        </div>
        <button onclick="window.print()" class="btn btn-sm btn-outline-secondary"><i class="fas fa-print me-1"></i>Print</button>
    </div>
</div>

<div class="container-fluid">
    <div class="alert alert-info py-2 d-flex gap-4">
        <span><i class="fas fa-clock me-1"></i><?php echo $quiz->duration_mins; ?> mins</span>
        <span><i class="fas fa-star me-1"></i><?php echo $quiz->total_marks; ?> marks</span>
        <span><i class="fas fa-check-circle me-1"></i>Pass: <?php echo $quiz->pass_marks; ?></span>
        <span><i class="fas fa-users me-1"></i><?php echo count($attempts); ?> submitted</span>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="results-table">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Adm No.</th>
                            <th>Student Name</th>
                            <th>Score</th>
                            <th>Percentage</th>
                            <th>Status</th>
                            <th>Submitted At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($attempts)): ?>
                        <tr><td colspan="7" class="text-center py-4 text-muted">No submissions yet.</td></tr>
                        <?php else: foreach ($attempts as $i => $a):
                            $pct    = $quiz->total_marks > 0 ? round(($a->total_score / $quiz->total_marks) * 100, 1) : 0;
                            $passed = $pct >= (($quiz->pass_marks / $quiz->total_marks) * 100);
                        ?>
                        <tr>
                            <td><?php echo $i+1; ?></td>
                            <td><?php echo html_escape($a->admission_no); ?></td>
                            <td><?php echo html_escape($a->first_name . ' ' . $a->last_name); ?></td>
                            <td><?php echo $a->total_score; ?> / <?php echo $quiz->total_marks; ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div style="flex:1; height:8px; background:#e2e8f0; border-radius:4px; overflow:hidden;">
                                        <div style="width:<?php echo $pct; ?>%; height:100%; background:<?php echo $pct>=70?'#10b981':($pct>=40?'#f59e0b':'#ef4444'); ?>;"></div>
                                    </div>
                                    <span class="small"><?php echo $pct; ?>%</span>
                                </div>
                            </td>
                            <td>
                                <?php if ($passed): ?>
                                <span class="badge" style="background:#d1fae5;color:#065f46;">PASS</span>
                                <?php else: ?>
                                <span class="badge" style="background:#fee2e2;color:#991b1b;">FAIL</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $a->submitted_at ? date('d M H:i', strtotime($a->submitted_at)) : 'In Progress'; ?></td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
$(function(){ $('#results-table').DataTable({order:[[3,'desc']], pageLength:50}); });
</script>
