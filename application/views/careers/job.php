<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row justify-content-center">
<div class="col-lg-8">
    <div class="job-card p-4 mb-3">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
            <div>
                <h3 class="mb-1" style="color:#1a5276;"><?php echo html_escape($job['title']); ?></h3>
                <?php if ($job['department']): ?>
                <p class="text-muted mb-0"><i class="fas fa-building me-1"></i><?php echo html_escape($job['department']); ?></p>
                <?php endif; ?>
            </div>
            <span class="badge-open">Open</span>
        </div>

        <?php if ($job['deadline']): ?>
        <div class="alert alert-warning py-2 px-3 mb-3">
            <i class="fas fa-clock me-1"></i> Application deadline: <strong><?php echo date('d F Y', strtotime($job['deadline'])); ?></strong>
        </div>
        <?php endif; ?>

        <h6 class="fw-bold mt-3 mb-2" style="color:#1a5276;border-bottom:2px solid #1a5276;padding-bottom:6px;">Job Description</h6>
        <div class="mb-4" style="white-space:pre-wrap;line-height:1.7;"><?php echo nl2br(html_escape($job['description'])); ?></div>

        <?php if ($job['requirements']): ?>
        <h6 class="fw-bold mb-2" style="color:#1a5276;border-bottom:2px solid #1a5276;padding-bottom:6px;">Requirements</h6>
        <div class="mb-4" style="white-space:pre-wrap;line-height:1.7;"><?php echo nl2br(html_escape($job['requirements'])); ?></div>
        <?php endif; ?>

        <div class="d-flex gap-2 mt-4 flex-wrap">
            <a href="<?php echo base_url('careers'); ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Jobs
            </a>
            <a href="<?php echo base_url('careers/apply/' . $job['id']); ?>" class="btn text-white" style="background:#1a5276;">
                <i class="fas fa-paper-plane me-1"></i>Apply for this Position
            </a>
        </div>
    </div>
</div>
</div>
