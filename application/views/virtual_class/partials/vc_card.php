<?php
$safe_meeting_link   = preg_match('/^https?:\/\//i', $vc->meeting_link)                              ? $vc->meeting_link   : '#';
$safe_recording_link = ($vc->recording_link && preg_match('/^https?:\/\//i', $vc->recording_link))   ? $vc->recording_link : '';
$_json = htmlspecialchars(json_encode([
    'id'           => (int)$vc->id,
    'title'        => $vc->title,
    'platform'     => $vc->platform,
    'duration'     => (int)$vc->duration_mins,
    'link'         => $vc->meeting_link,
    'meeting_id'   => $vc->meeting_id,
    'password'     => $vc->password,
    'scheduled_at' => date('Y-m-d\TH:i', strtotime($vc->scheduled_at)),
    'class_id'     => $vc->class_id,
    'subject_id'   => $vc->subject_id,
    'description'  => $vc->description,
]), ENT_QUOTES);
$_can_manage = is_admin_loggedin() || is_superadmin_loggedin() || is_teacher_loggedin();
?>
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
    <div class="p-3 border-top d-flex gap-2 flex-wrap align-items-center">
        <?php if ($vc->status !== 'cancelled' && $vc->status !== 'completed'): ?>
        <a href="<?php echo html_escape($safe_meeting_link); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-success">
            <i class="fas fa-video me-1"></i>Join
        </a>
        <?php endif; ?>
        <?php if ($safe_recording_link): ?>
        <a href="<?php echo html_escape($safe_recording_link); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-play me-1"></i>Recording
        </a>
        <?php endif; ?>
        <?php if ($vc->meeting_id): ?>
        <div class="text-muted small w-100">ID: <?php echo html_escape($vc->meeting_id); ?><?php if ($vc->password): ?> &middot; Pass: <?php echo html_escape($vc->password); ?><?php endif; ?></div>
        <?php endif; ?>
        <?php if ($_can_manage): ?>
            <?php if ($vc->status === 'upcoming'): ?>
            <button class="btn btn-sm btn-warning" onclick="vcStatus(<?php echo $vc->id; ?>,'ongoing')">
                <i class="fas fa-broadcast-tower me-1"></i>Go Live
            </button>
            <?php elseif ($vc->status === 'ongoing'): ?>
            <button class="btn btn-sm btn-secondary" onclick="vcStatus(<?php echo $vc->id; ?>,'completed')">
                <i class="fas fa-check me-1"></i>Mark Done
            </button>
            <?php endif; ?>
            <?php if ($vc->status !== 'cancelled' && $vc->status !== 'completed'): ?>
            <button class="btn btn-sm btn-outline-danger" onclick="vcStatus(<?php echo $vc->id; ?>,'cancelled')">
                <i class="fas fa-ban me-1"></i>Cancel
            </button>
            <?php endif; ?>
            <button class="btn btn-sm btn-outline-primary" onclick="editVc(this)" data-json="<?php echo $_json; ?>" title="Edit">
                <i class="fas fa-edit"></i>
            </button>
            <?php echo btn_delete('virtual_class/delete/'.$vc->id); ?>
        <?php endif; ?>
    </div>
</div>
