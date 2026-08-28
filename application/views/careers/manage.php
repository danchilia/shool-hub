<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Stats Row -->
<div class="row mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="panel panel-default text-center" style="padding:20px;">
            <div style="font-size:2rem;color:#1a5276;"><i class="fas fa-briefcase"></i></div>
            <div style="font-size:2rem;font-weight:700;"><?php echo $stats['total_positions']; ?></div>
            <div class="text-muted">Total Positions</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="panel panel-default text-center" style="padding:20px;">
            <div style="font-size:2rem;color:#1abc9c;"><i class="fas fa-door-open"></i></div>
            <div style="font-size:2rem;font-weight:700;"><?php echo $stats['open_positions']; ?></div>
            <div class="text-muted">Open Now</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="panel panel-default text-center" style="padding:20px;">
            <div style="font-size:2rem;color:#f39c12;"><i class="fas fa-users"></i></div>
            <div style="font-size:2rem;font-weight:700;"><?php echo $stats['total_applications']; ?></div>
            <div class="text-muted">Total Applications</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="panel panel-default text-center" style="padding:20px;">
            <div style="font-size:2rem;color:#3498db;"><i class="fas fa-user-check"></i></div>
            <div style="font-size:2rem;font-weight:700;"><?php echo $stats['shortlisted']; ?></div>
            <div class="text-muted">Shortlisted</div>
        </div>
    </div>
</div>

<!-- Positions Table -->
<div class="panel">
    <header class="panel-heading" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;">
        <h4 class="panel-title"><i class="fas fa-briefcase me-2"></i>Job Positions</h4>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?php echo base_url('careers'); ?>" target="_blank" class="btn btn-default btn-sm">
                <i class="fas fa-external-link-alt me-1"></i>View Public Page
            </a>
            <a href="<?php echo base_url('careers/add_job'); ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i>Post New Job
            </a>
        </div>
    </header>
    <div class="panel-body">
        <?php if (empty($jobs)): ?>
        <div class="text-center text-muted py-4">
            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
            No positions yet. <a href="<?php echo base_url('careers/add_job'); ?>">Post your first job</a>.
        </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Job Title</th>
                        <th>Department</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th>Applications</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($jobs as $i => $job): ?>
                    <tr>
                        <td><?php echo $i + 1; ?></td>
                        <td><strong><?php echo html_escape($job['title']); ?></strong></td>
                        <td><?php echo html_escape($job['department']) ?: '—'; ?></td>
                        <td><?php echo $job['deadline'] ? date('d M Y', strtotime($job['deadline'])) : '—'; ?></td>
                        <td>
                            <?php if ($job['status'] === 'open'): ?>
                                <span class="badge" style="background:#1abc9c;color:#fff;padding:4px 10px;border-radius:20px;">Open</span>
                            <?php else: ?>
                                <span class="badge" style="background:#95a5a6;color:#fff;padding:4px 10px;border-radius:20px;">Closed</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo base_url('careers/applications/' . $job['id']); ?>" class="btn btn-xs btn-info">
                                <i class="fas fa-users me-1"></i>View
                            </a>
                        </td>
                        <td>
                            <a href="<?php echo base_url('careers/add_job/' . $job['id']); ?>" class="btn btn-xs btn-default" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="<?php echo base_url('careers/toggle_job/' . $job['id']); ?>" class="btn btn-xs btn-default" title="Toggle Open/Closed"
                               onclick="return confirm('Toggle this position status?')">
                                <i class="fas fa-toggle-on"></i>
                            </a>
                            <a href="<?php echo base_url('careers/delete_job/' . $job['id']); ?>" class="btn btn-xs btn-danger" title="Delete"
                               onclick="return confirm('Delete this position and ALL its applications? This cannot be undone.')">
                                <i class="fas fa-trash"></i>
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
