<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <div>
        <h4 class="mb-0" style="color:#1a5276;"><i class="fas fa-folder-open me-2"></i>My Applications</h4>
        <p class="text-muted small mb-0">Welcome, <?php echo html_escape($applicant['full_name']); ?></p>
    </div>
    <a href="<?php echo base_url('careers'); ?>" class="btn btn-sm text-white" style="background:#1a5276;">
        <i class="fas fa-search me-1"></i>Browse Open Jobs
    </a>
</div>

<?php if (empty($applications)): ?>
<div class="job-card p-5 text-center">
    <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
    <h5 class="text-muted">No applications yet</h5>
    <p class="text-muted">Find open positions and submit your first application.</p>
    <a href="<?php echo base_url('careers'); ?>" class="btn text-white mt-2" style="background:#1a5276;">
        <i class="fas fa-briefcase me-1"></i>View Open Jobs
    </a>
</div>
<?php else: ?>
<div class="row g-3">
    <?php foreach ($applications as $app): ?>
    <div class="col-md-6">
        <div class="job-card p-3 h-100 d-flex flex-column">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h6 class="mb-0 me-2" style="color:#1a5276;"><?php echo html_escape($app['position_title']); ?></h6>
                <span class="status-pill status-<?php echo $app['status']; ?>"><?php echo ucfirst($app['status']); ?></span>
            </div>
            <?php if ($app['department']): ?>
            <p class="text-muted small mb-1"><i class="fas fa-building me-1"></i><?php echo html_escape($app['department']); ?></p>
            <?php endif; ?>
            <p class="text-muted small mb-3"><i class="fas fa-calendar-alt me-1"></i>Applied: <?php echo date('d M Y', strtotime($app['created_at'])); ?></p>
            <div class="mt-auto">
                <a href="<?php echo base_url('careers/my_application/' . $app['id']); ?>" class="btn btn-sm btn-outline-secondary w-100">
                    <i class="fas fa-eye me-1"></i>View Details & Messages
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
