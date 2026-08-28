<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Hero -->
<div class="rounded-3 mb-4 p-4" style="background:linear-gradient(135deg,#1a5276 0%,#2980b9 100%);color:#fff;">
    <h2 class="mb-1"><i class="fas fa-briefcase me-2"></i>Current Job Openings</h2>
    <p class="mb-0" style="color:#aed6f1;">Join the CST SchoolHub team shaping education technology in Kenya.</p>
</div>

<?php if (empty($jobs)): ?>
<div class="job-card p-5 text-center">
    <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
    <h5 class="text-muted">No open positions right now.</h5>
    <p class="text-muted mb-0">Check back soon, we're always growing!</p>
</div>
<?php else: ?>
<div class="row g-3">
    <?php foreach ($jobs as $job): ?>
    <div class="col-md-6 col-lg-4">
        <div class="job-card p-4 h-100 d-flex flex-column transition">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h5 class="mb-0 me-2" style="color:#1a5276;"><?php echo html_escape($job['title']); ?></h5>
                <span class="badge-open flex-shrink-0">Open</span>
            </div>
            <?php if ($job['department']): ?>
                <p class="mb-1 text-muted small"><i class="fas fa-building me-1"></i><?php echo html_escape($job['department']); ?></p>
            <?php endif; ?>
            <?php if ($job['deadline']): ?>
                <p class="mb-2 text-muted small"><i class="fas fa-calendar-alt me-1"></i>Deadline: <?php echo date('d M Y', strtotime($job['deadline'])); ?></p>
            <?php endif; ?>
            <p class="text-muted small flex-grow-1 mb-3">
                <?php $desc = strip_tags($job['description']); echo html_escape(strlen($desc) > 160 ? substr($desc, 0, 160) . '…' : $desc); ?>
            </p>
            <div class="d-flex gap-2">
                <a href="<?php echo base_url('careers/job/' . $job['id']); ?>" class="btn btn-sm btn-outline-secondary flex-grow-1">Details</a>
                <a href="<?php echo base_url('careers/apply/' . $job['id']); ?>" class="btn btn-sm flex-grow-1 text-white" style="background:#1a5276;">Apply Now</a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
