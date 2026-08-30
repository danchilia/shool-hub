<style>
.cbc-analytics .metric-card { border-radius: 8px; padding: 18px 20px; color: #fff; text-align: center; }
.cbc-analytics .metric-num  { font-size: 2rem; font-weight: 700; line-height: 1; }
.cbc-analytics .metric-lbl  { font-size: 11px; opacity: .85; margin-top: 4px; }
.cbc-analytics .chart-wrap  { position: relative; height: 320px; }
.cbc-analytics .la-bar-wrap { position: relative; height: 420px; }
.cbc-analytics .level-chip  { display:inline-block;padding:2px 9px;border-radius:4px;font-weight:700;font-size:11px;color:#fff;margin:2px; }
.cbc-analytics table thead th { background:#1a5276; color:#fff; white-space:nowrap; }
.cbc-analytics .pct-bar { height: 10px; border-radius: 5px; background: #e9ecef; overflow: hidden; margin-top: 3px; }
.cbc-analytics .pct-fill { height: 100%; border-radius: 5px; transition: width .5s; }
</style>

<section class="panel cbc-analytics">
    <header class="panel-heading">
        <h4 class="panel-title"><i class="fas fa-chart-bar"></i> CBC Analytics</h4>
    </header>
    <div class="panel-body">

        <!-- Filters -->
        <?php echo form_open($this->uri->uri_string(), array('id' => 'analyticsForm')); ?>
        <div class="row mb-md">
            <?php if (is_superadmin_loggedin()): ?>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label"><?=translate('branch')?></label>
                    <?php
                        $arrayBranch = $this->app_lib->getSelectList('branch');
                        echo form_dropdown('branch_id', $arrayBranch, $branch_id, "class='form-control' id='an_branch_id' onchange='getClassByBranch(this.value)' data-plugin-selectTwo data-width='100%'");
                    ?>
                </div>
            </div>
            <?php endif; ?>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label"><?=translate('class')?></label>
                    <?php $arrayClass = $this->app_lib->getClass($branch_id);
                    echo form_dropdown('class_id', $arrayClass, '', "class='form-control' id='an_class_id' onchange='getSectionByClass(this.value,0)' data-plugin-selectTwo data-width='100%'"); ?>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label"><?=translate('section')?></label>
                    <select name="section_id" id="an_section_id" class="form-control" data-plugin-selectTwo data-width="100%">
                        <option value=""><?=translate('select')?></option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">CBC Exam</label>
                    <select name="exam_id" id="an_exam_id" class="form-control" data-plugin-selectTwo data-width="100%">
                        <option value=""><?=translate('select')?></option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group mt-lg">
                    <button type="button" id="btnAnalyse" class="btn btn-default btn-block">
                        <i class="fas fa-chart-bar"></i> Analyse
                    </button>
                </div>
            </div>
        </div>
        <?php echo form_close(); ?>

        <div id="analyticsResults" hidden>

            <!-- Metric cards -->
            <div class="row mb-md" id="metricRow"></div>

            <!-- Charts row -->
            <div class="row mb-md">
                <div class="col-md-5">
                    <section class="panel">
                        <header class="panel-heading"><h4 class="panel-title">Overall Level Distribution</h4></header>
                        <div class="panel-body">
                            <div class="chart-wrap"><canvas id="donutChart"></canvas></div>
                        </div>
                    </section>
                </div>
                <div class="col-md-7">
                    <section class="panel">
                        <header class="panel-heading"><h4 class="panel-title">Level Distribution by Learning Area</h4></header>
                        <div class="panel-body">
                            <div class="la-bar-wrap"><canvas id="laBarChart"></canvas></div>
                        </div>
                    </section>
                </div>
            </div>

            <!-- Trend chart (across exams) -->
            <div class="row mb-md">
                <div class="col-md-12">
                    <section class="panel">
                        <header class="panel-heading"><h4 class="panel-title">Term / Exam Trend</h4></header>
                        <div class="panel-body">
                            <div style="position:relative;height:240px;"><canvas id="trendChart"></canvas></div>
                        </div>
                    </section>
                </div>
            </div>

            <!-- Detailed LA table -->
            <div class="row">
                <div class="col-md-12">
                    <section class="panel">
                        <header class="panel-heading"><h4 class="panel-title">Learning Area Breakdown</h4></header>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm" id="laDetailTable">
                                    <thead>
                                        <tr>
                                            <th style="width:28%">Learning Area</th>
                                            <th class="text-center" style="background:#155724 !important;">EE2</th>
                                            <th class="text-center" style="background:#1e7e34 !important;">EE1</th>
                                            <th class="text-center" style="background:#004085 !important;">ME2</th>
                                            <th class="text-center" style="background:#0062cc !important;">ME1</th>
                                            <th class="text-center" style="background:#856404 !important;">AE2</th>
                                            <th class="text-center" style="background:#d39e00 !important;color:#000 !important;">AE1</th>
                                            <th class="text-center" style="background:#721c24 !important;">BE2</th>
                                            <th class="text-center" style="background:#dc3545 !important;">BE1</th>
                                            <th class="text-center">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="laTableBody"></tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

        </div><!-- /analyticsResults -->

        <div id="analyticsEmpty" class="text-center" style="padding:40px; color:#888;">
            <i class="fas fa-chart-pie fa-3x mb-md" style="color:#ccc;"></i>
            <p>Select a class, section, and CBC exam, then click <strong>Analyse</strong>.</p>
        </div>

    </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
var donutChart, laBarChart, trendChart;

var LEVEL_COLORS = {
    EE2: '#155724', EE1: '#1e7e34',
    ME2: '#004085', ME1: '#0062cc',
    AE2: '#856404', AE1: '#d39e00',
    BE2: '#721c24', BE1: '#dc3545',
};
var ALL_LEVELS = ['EE2','EE1','ME2','ME1','AE2','AE1','BE2','BE1'];

$(document).ready(function () {
    getCbcExams();

    $('#btnAnalyse').on('click', function () {
        var examId    = $('#an_exam_id').val();
        var classId   = $('#an_class_id').val();
        var sectionId = $('#an_section_id').val();
        if (!examId || !classId || !sectionId) {
            alert('Please select class, section, and exam.'); return;
        }
        $.ajax({
            url: base_url + 'cbc/getAnalyticsData',
            type: 'POST',
            data: {exam_id: examId, class_id: classId, section_id: sectionId},
            dataType: 'json',
            success: function (data) {
                if (!data || !data.rows || data.rows.length === 0) {
                    alert('No CBC assessment data found for this selection.'); return;
                }
                renderAnalytics(data);
                $('#analyticsEmpty').hide();
                $('#analyticsResults').prop('hidden', false);
            }
        });
    });
});

function getCbcExams() {
    $.post(base_url + 'cbc/getCbcExamsByBranch', {}, function(html) {
        $('#an_exam_id').html(html);
    });
}

function normLevel(lvl) {
    var map = {EE:'EE2', ME:'ME2', AE:'AE1', BE:'BE1'};
    return map[lvl] || lvl;
}

function renderAnalytics(data) {
    var rows  = data.rows;
    var trend = data.trend;
    var total = parseInt(data.total);

    // --- Build LA map ---
    var laMap = {};    // laName → {EE2:0,...}
    var overallCounts = {};
    ALL_LEVELS.forEach(function(l){ overallCounts[l] = 0; });

    rows.forEach(function(r) {
        var lvl = normLevel(r.competency_level);
        var cnt = parseInt(r.cnt);
        if (!laMap[r.la_name]) {
            laMap[r.la_name] = {};
            ALL_LEVELS.forEach(function(l){ laMap[r.la_name][l] = 0; });
        }
        laMap[r.la_name][lvl] += cnt;
        if (overallCounts[lvl] !== undefined) overallCounts[lvl] += cnt;
    });

    // --- Metric cards ---
    var ee = (overallCounts.EE2||0) + (overallCounts.EE1||0);
    var me = (overallCounts.ME2||0) + (overallCounts.ME1||0);
    var ae = (overallCounts.AE2||0) + (overallCounts.AE1||0);
    var be = (overallCounts.BE2||0) + (overallCounts.BE1||0);
    var totalAssessments = ee + me + ae + be;
    var laCount = Object.keys(laMap).length;

    $('#metricRow').html(
        metricCard('#155724', 'Exceeding (EE)', ee) +
        metricCard('#004085', 'Meeting (ME)', me) +
        metricCard('#856404', 'Approaching (AE)', ae) +
        metricCard('#721c24', 'Below (BE)', be) +
        metricCard('#1a5276', 'Enrolled', total) +
        metricCard('#2c3e50', 'Learning Areas', laCount)
    );

    // --- Donut chart ---
    var donutLabels = ALL_LEVELS.filter(function(l){ return overallCounts[l] > 0; });
    var donutData   = donutLabels.map(function(l){ return overallCounts[l]; });
    var donutColors = donutLabels.map(function(l){ return LEVEL_COLORS[l]; });

    if (donutChart) donutChart.destroy();
    donutChart = new Chart(document.getElementById('donutChart'), {
        type: 'doughnut',
        data: { labels: donutLabels, datasets: [{ data: donutData, backgroundColor: donutColors, borderWidth: 2 }] },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { position: 'right', labels: { font: { size: 11 } } } }
        }
    });

    // --- LA horizontal bar chart ---
    var laNames = Object.keys(laMap);
    var laDatasets = ALL_LEVELS.map(function(lvl) {
        return {
            label: lvl,
            data: laNames.map(function(la){ return laMap[la][lvl] || 0; }),
            backgroundColor: LEVEL_COLORS[lvl],
            borderWidth: 0,
        };
    });
    if (laBarChart) laBarChart.destroy();
    laBarChart = new Chart(document.getElementById('laBarChart'), {
        type: 'bar',
        data: { labels: laNames, datasets: laDatasets },
        options: {
            indexAxis: 'y',
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { position: 'top', labels: { font: { size: 10 }, boxWidth: 12 } } },
            scales: {
                x: { stacked: true, ticks: { precision: 0 } },
                y: { stacked: true, ticks: { font: { size: 11 } } }
            }
        }
    });

    // --- Trend chart ---
    var trendExams = [], trendMap = {};
    trend.forEach(function(r) {
        var lvl = normLevel(r.competency_level);
        if (trendExams.indexOf(r.exam_name) === -1) trendExams.push(r.exam_name);
        if (!trendMap[lvl]) trendMap[lvl] = {};
        trendMap[lvl][r.exam_name] = (trendMap[lvl][r.exam_name] || 0) + parseInt(r.cnt);
    });
    var trendDatasets = ALL_LEVELS.filter(function(l){ return trendMap[l]; }).map(function(lvl) {
        return {
            label: lvl,
            data: trendExams.map(function(ex){ return trendMap[lvl] && trendMap[lvl][ex] ? trendMap[lvl][ex] : 0; }),
            borderColor: LEVEL_COLORS[lvl],
            backgroundColor: LEVEL_COLORS[lvl] + '33',
            tension: 0.3, fill: false, borderWidth: 2, pointRadius: 4,
        };
    });
    if (trendChart) trendChart.destroy();
    trendChart = new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: { labels: trendExams, datasets: trendDatasets },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { position: 'top', labels: { font: { size: 10 }, boxWidth: 12 } } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });

    // --- LA detail table ---
    var tableHtml = '';
    laNames.forEach(function(la) {
        var rowTotal = ALL_LEVELS.reduce(function(s,l){ return s + (laMap[la][l]||0); }, 0);
        tableHtml += '<tr><td><strong>' + la + '</strong></td>';
        ALL_LEVELS.forEach(function(lvl) {
            var cnt = laMap[la][lvl] || 0;
            var pct = rowTotal > 0 ? Math.round(cnt * 100 / rowTotal) : 0;
            tableHtml += '<td class="text-center">';
            if (cnt > 0) {
                tableHtml += '<strong>' + cnt + '</strong>';
                tableHtml += '<div class="pct-bar"><div class="pct-fill" style="width:' + pct + '%;background:' + LEVEL_COLORS[lvl] + '"></div></div>';
                tableHtml += '<small style="color:#888;">' + pct + '%</small>';
            } else {
                tableHtml += '<span style="color:#ccc;">—</span>';
            }
            tableHtml += '</td>';
        });
        tableHtml += '<td class="text-center"><strong>' + rowTotal + '</strong></td></tr>';
    });
    $('#laTableBody').html(tableHtml);
}

function metricCard(color, label, val) {
    return '<div class="col-md-2"><div class="metric-card" style="background:' + color + '">' +
           '<div class="metric-num">' + val + '</div>' +
           '<div class="metric-lbl">' + label + '</div></div></div>';
}
</script>
