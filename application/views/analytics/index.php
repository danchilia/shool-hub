<?php
$atRisk          = isset($atRisk)          ? $atRisk          : array();
$feeDefaulters   = isset($feeDefaulters)   ? $feeDefaulters   : array();
$totalStudents   = isset($totalStudents)   ? $totalStudents   : 0;
$totalAtRisk     = isset($totalAtRisk)     ? $totalAtRisk     : 0;
$totalDefaulters = isset($totalDefaulters) ? $totalDefaulters : 0;
$totalOutstanding= isset($totalOutstanding)? $totalOutstanding: 0;
$trend           = isset($trend)           ? $trend           : array();
?>
<style>
.risk-bar{height:8px;border-radius:4px;background:#eee;display:inline-block;width:80px;vertical-align:middle;margin-right:6px}
.risk-fill{height:100%;border-radius:4px;background:linear-gradient(90deg,#27ae60,#f39c12,#e74c3c)}
.score-badge{display:inline-block;min-width:36px;text-align:center;padding:2px 6px;border-radius:10px;font-size:.78rem;font-weight:700;color:#fff}
.score-low{background:#27ae60}.score-med{background:#f39c12}.score-high{background:#e74c3c}
</style>

<!-- Summary Cards -->
<div class="row">
    <div class="col-md-3">
        <div class="panel widget-stat" style="border-top:3px solid #2e86c1">
            <div class="panel-body text-center" style="padding:20px">
                <h3 style="color:#2e86c1;margin-bottom:4px"><?= $totalStudents ?></h3>
                <small class="text-muted">Total Active Students</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel widget-stat" style="border-top:3px solid #e74c3c">
            <div class="panel-body text-center" style="padding:20px">
                <h3 style="color:#e74c3c;margin-bottom:4px"><?= $totalAtRisk ?></h3>
                <small class="text-muted">At-Risk Students <i class="fas fa-exclamation-triangle text-danger"></i></small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel widget-stat" style="border-top:3px solid #e67e22">
            <div class="panel-body text-center" style="padding:20px">
                <h3 style="color:#e67e22;margin-bottom:4px"><?= $totalDefaulters ?></h3>
                <small class="text-muted">Fee Defaulters</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel widget-stat" style="border-top:3px solid #8e44ad">
            <div class="panel-body text-center" style="padding:20px">
                <h3 style="color:#8e44ad;margin-bottom:4px">KES <?= number_format($totalOutstanding, 0) ?></h3>
                <small class="text-muted">Total Outstanding Fees</small>
            </div>
        </div>
    </div>
</div>

<!-- Attendance Trend Chart -->
<?php if (!empty($trend)): ?>
<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading"><h4 class="panel-title"><i class="fas fa-chart-line"></i> Attendance Trend (Last 14 Days)</h4></header>
            <div class="panel-body">
                <canvas id="trendChart" height="80"></canvas>
            </div>
        </section>
    </div>
</div>
<?php endif; ?>

<!-- At-Risk Students -->
<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <h4 class="panel-title"><i class="fas fa-exclamation-triangle text-danger"></i> At-Risk Students</h4>
            </header>
            <div class="panel-body">
                <p class="text-muted" style="font-size:.85rem;margin-bottom:12px">
                    Risk score combines: <strong>Attendance (0-40)</strong> + <strong>Academic performance (0-40)</strong> + <strong>Fee arrears (0-20)</strong>.
                    Students scoring ≥20 are flagged.
                </p>
                <?php if (empty($atRisk)): ?>
                    <p class="text-center text-success mt-md"><i class="fas fa-check-circle"></i> No at-risk students found.</p>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed table-hover table-export">
                        <thead>
                            <tr>
                                <th>Student</th><th>Class</th>
                                <th>Risk Score</th>
                                <th>Absences (30d)</th>
                                <th>Avg Mark</th>
                                <th>Outstanding (KES)</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($atRisk as $s):
                                $score = $s['risk_score'];
                                $cls   = $score >= 60 ? 'score-high' : ($score >= 30 ? 'score-med' : 'score-low');
                                $pct   = min(100, $score);
                            ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($s['first_name'] . ' ' . $s['last_name']) ?></strong><br>
                                    <small class="text-muted"><?= htmlspecialchars($s['register_no']) ?></small>
                                </td>
                                <td><?= htmlspecialchars(($s['class'] ?? '') . ' ' . ($s['section_name'] ?? '')) ?></td>
                                <td>
                                    <span class="risk-bar"><span class="risk-fill" style="width:<?= $pct ?>%"></span></span>
                                    <span class="score-badge <?= $cls ?>"><?= $score ?></span>
                                </td>
                                <td>
                                    <?php if ($s['total_days'] > 0): ?>
                                        <span class="<?= $s['absent_days'] / $s['total_days'] > 0.3 ? 'text-danger' : '' ?>">
                                            <?= $s['absent_days'] ?> / <?= $s['total_days'] ?> days
                                        </span>
                                    <?php else: echo 'N/A'; endif; ?>
                                </td>
                                <td>
                                    <?php if ($s['avg_mark'] !== null): ?>
                                        <span class="<?= $s['avg_mark'] < 40 ? 'text-danger' : 'text-success' ?>"><?= number_format($s['avg_mark'], 1) ?>%</span>
                                    <?php else: echo '—'; endif; ?>
                                </td>
                                <td><?= $s['outstanding'] > 0 ? '<span class="text-danger">'.number_format($s['outstanding'],2).'</span>' : '—' ?></td>
                                <td>
                                    <a href="<?= base_url('student/profile/' . $s['student_id']) ?>" class="btn btn-default btn-circle btn-xs" data-toggle="tooltip" data-original-title="Student Profile">
                                        <i class="fas fa-user"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>

<!-- Fee Defaulters -->
<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <h4 class="panel-title"><i class="fas fa-money-bill-wave text-warning"></i> Fee Defaulters (Balance ≥ KES 1,000)</h4>
            </header>
            <div class="panel-body">
                <?php if (empty($feeDefaulters)): ?>
                    <p class="text-center text-success mt-md"><i class="fas fa-check-circle"></i> No significant fee defaulters.</p>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed table-hover table-export">
                        <thead>
                            <tr><th>Student</th><th>Class</th><th>Outstanding (KES)</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($feeDefaulters as $s): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($s['first_name'] . ' ' . $s['last_name']) ?></strong><br>
                                    <small class="text-muted"><?= htmlspecialchars($s['register_no']) ?></small>
                                </td>
                                <td><?= htmlspecialchars(($s['class'] ?? '') . ' ' . ($s['section_name'] ?? '')) ?></td>
                                <td class="text-danger" style="font-weight:700">KES <?= number_format($s['outstanding'], 2) ?></td>
                                <td>
                                    <a href="<?= base_url('student/profile/' . $s['student_id']) ?>" class="btn btn-default btn-circle btn-xs" data-toggle="tooltip" data-original-title="View Profile">
                                        <i class="fas fa-user"></i>
                                    </a>
                                    <a href="<?= base_url('feespayment/student_fee/' . $s['student_id']) ?>" class="btn btn-default btn-circle btn-xs" data-toggle="tooltip" data-original-title="Fee Details">
                                        <i class="fas fa-receipt"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>

<?php if (!empty($trend)): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
(function() {
    var trend = <?= json_encode($trend) ?>;
    var labels = trend.map(function(r){ return r.attendance_date; });
    var pctPresent = trend.map(function(r){ return r.total > 0 ? Math.round(r.present / r.total * 100) : 0; });
    var ctx = document.getElementById('trendChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Attendance %',
                data: pctPresent,
                borderColor: '#2e86c1',
                backgroundColor: 'rgba(46,134,193,.1)',
                fill: true,
                tension: 0.3,
                pointRadius: 4
            }]
        },
        options: {
            scales: {
                y: { min: 0, max: 100, ticks: { callback: function(v){ return v + '%'; } } }
            },
            plugins: { legend: { display: false } }
        }
    });
})();
</script>
<?php endif; ?>
