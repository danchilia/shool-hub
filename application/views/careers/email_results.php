<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>Retrospective Confirmation Emails</h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url('careers/manage') ?>"><i class="fa fa-briefcase"></i> Careers</a></li>
            <li class="active">Send Confirmation Emails</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-4">
                <div class="box box-success">
                    <div class="box-body text-center">
                        <h2 class="text-success"><?= $sent ?></h2>
                        <p>Emails Sent Successfully</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box box-danger">
                    <div class="box-body text-center">
                        <h2 class="text-danger"><?= $failed ?></h2>
                        <p>Failed / Skipped</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box box-info">
                    <div class="box-body text-center">
                        <h2><?= $sent + $failed ?></h2>
                        <p>Total Applications</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Delivery Log</h3>
            </div>
            <div class="box-body table-responsive">
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
    </section>
</div>
