<?php
$visits = isset($visits) ? $visits : array();
$from   = isset($from)   ? $from   : date('Y-m-01');
$to     = isset($to)     ? $to     : date('Y-m-d');
?>
<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <h4 class="panel-title"><i class="fas fa-notes-medical"></i> Clinic Visits Log</h4>
            </header>
            <div class="panel-body">
                <?php echo form_open('health/clinic_log', array('class' => 'validate', 'method' => 'get')); ?>
                <div class="row mb-sm">
                    <div class="col-md-4">
                        <div class="form-group"><label>From</label><input type="date" name="from" class="form-control" value="<?= $from ?>"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label>To</label><input type="date" name="to" class="form-control" value="<?= $to ?>"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label>&nbsp;</label><button type="submit" class="btn btn-default btn-block"><i class="fas fa-filter"></i> Filter</button></div>
                    </div>
                </div>
                <?php echo form_close(); ?>

                <?php if (empty($visits)): ?>
                    <p class="text-center text-muted mt-md">No visits in this period.</p>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed table-hover table-export">
                        <thead>
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
                                <td><?= date('d M Y', strtotime($v['visit_date'])) ?></td>
                                <td>
                                    <a href="<?= base_url('health/student/' . $v['student_id']) ?>">
                                        <?= htmlspecialchars($v['student_name'] ?? '') ?>
                                    </a>
                                </td>
                                <td><?= htmlspecialchars($v['register_no'] ?? '') ?></td>
                                <td><?= htmlspecialchars(($v['class'] ?? '') . ' ' . ($v['section_name'] ?? '')) ?></td>
                                <td><?= htmlspecialchars($v['complaint']) ?></td>
                                <td><?= htmlspecialchars($v['diagnosis'] ?? '—') ?></td>
                                <td>
                                    <?php if ($v['referred']): ?>
                                        <span class="badge badge-warning">Yes</span>
                                    <?php else: echo '—'; endif; ?>
                                </td>
                                <td><?= htmlspecialchars($v['attended_by'] ?? '—') ?></td>
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
