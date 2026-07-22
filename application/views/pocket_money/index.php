<?php
$students  = isset($students)  ? $students  : array();
$totalHeld = isset($totalHeld) ? $totalHeld : 0;
?>
<div class="row">
    <div class="col-md-4">
        <div class="panel widget-stat" style="border-top:3px solid #27ae60">
            <div class="panel-body" style="text-align:center;padding:20px">
                <h3 style="color:#27ae60;margin-bottom:4px">KES <?= number_format($totalHeld, 2) ?></h3>
                <small class="text-muted">Total Cash Held for Students</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel widget-stat" style="border-top:3px solid #2e86c1">
            <div class="panel-body" style="text-align:center;padding:20px">
                <h3 style="color:#2e86c1;margin-bottom:4px"><?= count($students) ?></h3>
                <small class="text-muted">Registered Students</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel widget-stat" style="border-top:3px solid #e67e22">
            <div class="panel-body" style="text-align:center;padding:20px">
                <h3 style="color:#e67e22;margin-bottom:4px"><?= count(array_filter($students, fn($s) => $s['balance'] > 0)) ?></h3>
                <small class="text-muted">Students with Balance</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <h4 class="panel-title"><i class="fas fa-wallet"></i> Pocket Money — All Students</h4>
            </header>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed table-hover table-export">
                        <thead>
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
                            <?php foreach ($students as $i => $s): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= htmlspecialchars($s['register_no']) ?></td>
                                <td><?= htmlspecialchars($s['first_name'] . ' ' . $s['last_name']) ?></td>
                                <td><?= htmlspecialchars($s['class'] ?? '') ?></td>
                                <td><?= htmlspecialchars($s['section_name'] ?? '') ?></td>
                                <td class="<?= $s['balance'] > 0 ? 'text-success' : '' ?>" style="font-weight:600">
                                    <?= number_format($s['balance'], 2) ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('pocket_money/ledger/' . $s['student_id']) ?>" class="btn btn-default btn-circle icon" data-toggle="tooltip" data-original-title="Ledger">
                                        <i class="fas fa-list-ul"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>
