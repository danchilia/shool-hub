<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="panel">
    <header class="panel-heading" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;">
        <div>
            <h4 class="panel-title" style="margin-bottom:2px;">
                <i class="fas fa-users me-2"></i>Applications for: <?php echo html_escape($job['title']); ?>
            </h4>
            <small class="text-muted">
                <?php if ($job['department']): ?><i class="fas fa-building me-1"></i><?php echo html_escape($job['department']); ?> &nbsp;·&nbsp; <?php endif; ?>
                <?php echo count($applications); ?> application(s)
            </small>
        </div>
        <a href="<?php echo base_url('careers/manage'); ?>" class="btn btn-default btn-sm">
            <i class="fas fa-arrow-left me-1"></i>Back to Positions
        </a>
    </header>
    <div class="panel-body">
        <?php if (empty($applications)): ?>
        <div class="text-center text-muted py-4">
            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
            No applications received yet for this position.
        </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Applicant</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Applied On</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($applications as $i => $app): ?>
                    <tr>
                        <td><?php echo $i + 1; ?></td>
                        <td><strong><?php echo html_escape($app['full_name']); ?></strong></td>
                        <td><?php echo html_escape($app['email']); ?></td>
                        <td><?php echo html_escape($app['phone']); ?></td>
                        <td><?php echo date('d M Y', strtotime($app['created_at'])); ?></td>
                        <td>
                            <?php
                            $colors = [
                                'pending'     => '#f39c12',
                                'shortlisted' => '#3498db',
                                'interview'   => '#9b59b6',
                                'rejected'    => '#e74c3c',
                                'hired'       => '#27ae60',
                            ];
                            $c = $colors[$app['status']] ?? '#999';
                            ?>
                            <span class="badge" style="background:<?php echo $c; ?>;color:#fff;padding:4px 10px;border-radius:20px;">
                                <?php echo ucfirst($app['status']); ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?php echo base_url('careers/view_application/' . $app['id']); ?>" class="btn btn-xs btn-primary">
                                <i class="fas fa-eye me-1"></i>Review
                            </a>
                            <a href="<?php echo base_url('careers/download_cv/' . $app['id']); ?>" class="btn btn-xs btn-default" title="Download CV">
                                <i class="fas fa-download me-1"></i>CV
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>
