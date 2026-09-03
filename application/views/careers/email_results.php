<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default text-center" style="padding:20px;">
            <div style="font-size:2rem;color:#27ae60;"><i class="fa fa-check-circle"></i></div>
            <div style="font-size:2rem;font-weight:700;"><?= $sent ?></div>
            <div class="text-muted">Emails Sent</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default text-center" style="padding:20px;">
            <div style="font-size:2rem;color:#e74c3c;"><i class="fa fa-times-circle"></i></div>
            <div style="font-size:2rem;font-weight:700;"><?= $failed ?></div>
            <div class="text-muted">Failed / Skipped</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default text-center" style="padding:20px;">
            <div style="font-size:2rem;color:#2980b9;"><i class="fa fa-users"></i></div>
            <div style="font-size:2rem;font-weight:700;"><?= $sent + $failed ?></div>
            <div class="text-muted">Total Applications</div>
        </div>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading"><strong>Delivery Log</strong></div>
    <div class="panel-body table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Applicant</th>
                    <th>Email</th>
                    <th>Position</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($log as $i => $row): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['job']) ?></td>
                    <td>
                        <?php if ($row['status'] === 'sent'): ?>
                            <span class="label label-success">Sent</span>
                        <?php else: ?>
                            <span class="label label-danger">Failed</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<a href="<?= base_url('careers/manage') ?>" class="btn btn-default">
    <i class="fa fa-arrow-left"></i> Back to Careers
</a>
