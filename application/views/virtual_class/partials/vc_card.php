<div class="vc-card h-100 d-flex flex-column">
    <div class="p-3 flex-grow-1">
        <div class="d-flex align-items-start justify-content-between mb-2">
            <span class="vc-platform vc-<?php echo $vc->platform; ?>"><?php echo strtoupper($vc->platform); ?></span>
            <span class="badge status-<?php echo $vc->status; ?>"><?php echo ucfirst($vc->status); ?></span>
        </div>
        <h6 class="mb-1"><?php echo html_escape($vc->title); ?></h6>
        <div class="text-muted small mb-2">
            <div><i class="fas fa-calendar me-1"></i><?php echo date('d M Y H:i', strtotime($vc->scheduled_at)); ?></div>
            <div><i class="fas fa-clock me-1"></i><?php echo $vc->duration_mins; ?> minutes</div>
            <?php if ($vc->class_name): ?><div><i class="fas fa-chalkboard me-1"></i><?php echo html_escape($vc->class_name); ?></div><?php endif; ?>
            <?php if ($vc->teacher_name): ?><div><i class="fas fa-user me-1"></i><?php echo html_escape($vc->teacher_name); ?></div><?php endif; ?>
        </div>
        <?php if ($vc->description): ?>
        <p class="text-muted small mb-0"><?php echo html_escape($vc->description); ?></p>
        <?php endif; ?>
    </div>
    <div class="p-3 border-top d-flex gap-2 flex-wrap">
        <?php if ($vc->status !== 'cancelled' && $vc->status !== 'completed'): ?>
        <a href="<?php echo html_escape($vc->meeting_link); ?>" target="_blank" class="btn btn-sm btn-success">
            <i class="fas fa-video me-1"></i>Join
        </a>
        <?php endif; ?>
        <?php if ($vc->recording_link): ?>
        <a href="<?php echo html_escape($vc->recording_link); ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-play me-1"></i>Recording
        </a>
        <?php endif; ?>
        <?php if ($vc->meeting_id): ?>
        <div class="text-muted small mt-1 w-100">ID: <?php echo html_escape($vc->meeting_id); ?><?php if ($vc->password): ?> &middot; Pass: <?php echo html_escape($vc->password); ?><?php endif; ?></div>
        <?php endif; ?>
        <?php echo btn_delete('virtual_class/delete/'.$vc->id); ?>
    </div>
</div>
