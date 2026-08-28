<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row justify-content-center">
<div class="col-lg-8">

    <!-- Header -->
    <div class="job-card p-4 mb-3">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
            <div>
                <h5 class="mb-1" style="color:#1a5276;"><?php echo html_escape($app['position_title']); ?></h5>
                <?php if ($app['department']): ?>
                <p class="text-muted small mb-0"><i class="fas fa-building me-1"></i><?php echo html_escape($app['department']); ?></p>
                <?php endif; ?>
            </div>
            <span class="status-pill status-<?php echo $app['status']; ?>" style="font-size:13px;"><?php echo ucfirst($app['status']); ?></span>
        </div>
        <p class="text-muted small mb-1"><i class="fas fa-calendar-alt me-1"></i>Applied on <?php echo date('d F Y', strtotime($app['created_at'])); ?></p>
        <p class="text-muted small mb-0"><i class="fas fa-file-pdf me-1"></i>CV submitted: <?php echo html_escape($app['cv_orig_name']); ?></p>
    </div>

    <!-- Cover Letter -->
    <?php if (!empty($app['cover_letter'])): ?>
    <div class="job-card p-4 mb-3">
        <h6 class="fw-bold mb-2" style="color:#1a5276;"><i class="fas fa-align-left me-1"></i>Your Cover Letter</h6>
        <p class="mb-0" style="white-space:pre-wrap;line-height:1.7;"><?php echo nl2br(html_escape($app['cover_letter'])); ?></p>
    </div>
    <?php endif; ?>

    <!-- Messages -->
    <div class="job-card p-4 mb-3">
        <h6 class="fw-bold mb-3" style="color:#1a5276;"><i class="fas fa-comments me-1"></i>Messages from HR</h6>

        <?php if (empty($replies)): ?>
        <div class="text-center py-3 text-muted">
            <i class="fas fa-clock me-1"></i>No messages yet. We will be in touch soon.
        </div>
        <?php else: ?>
        <?php foreach ($replies as $r): ?>
        <div class="msg-bubble msg-<?php echo $r['sender']; ?>">
            <div class="d-flex justify-content-between mb-1">
                <small class="fw-bold" style="color:#1a5276;">
                    <?php if ($r['sender'] === 'admin'): ?>
                        <i class="fas fa-user-tie me-1"></i>CST HR Team
                    <?php else: ?>
                        <i class="fas fa-user me-1"></i>You
                    <?php endif; ?>
                </small>
                <small class="text-muted"><?php echo date('d M Y, H:i', strtotime($r['created_at'])); ?></small>
            </div>
            <p class="mb-0" style="white-space:pre-wrap;"><?php echo nl2br(html_escape($r['message'])); ?></p>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <a href="<?php echo base_url('careers/dashboard'); ?>" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Back to My Applications
    </a>

</div>
</div>
