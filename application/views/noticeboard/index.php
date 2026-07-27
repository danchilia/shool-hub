<style>
.notice-card { border-left: 4px solid #ccc; border-radius: 6px; margin-bottom: 16px; padding: 16px 20px; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
.notice-card.notice-important { border-left-color: #f59e0b; }
.notice-card.notice-urgent    { border-left-color: #dc3545; }
.notice-card .notice-title    { font-size: 1.05rem; font-weight: 700; margin-bottom: 6px; }
.notice-card .notice-meta     { font-size: .8rem; color: #888; margin-bottom: 8px; }
.notice-card .notice-body     { white-space: pre-line; }
.notice-card .notice-attach   { margin-top: 10px; }
.empty-state { text-align:center; padding: 60px 20px; }
.empty-state i { font-size: 3rem; margin-bottom: 12px; display:block; }

/* Light mode */
.notice-card                  { background:#fff; }
.notice-card.notice-urgent    { background:#fff5f5; }
/* Dark mode */
@media (prefers-color-scheme:dark) {
    .card          { background:#2b2b3a; border-color:#3a3a50; }
    .card-header   { background:#232333; border-color:#3a3a50; }
    .notice-card             { background:#2b2b3a; color:#d0d0e0; }
    .notice-card .notice-meta{ color:#888; }
    .notice-card .notice-body{ color:#c0c0d0; }
    .notice-card.notice-urgent { background:#3a1a1a; }
}
:root[data-theme="dark"]  .card          { background:#2b2b3a; border-color:#3a3a50; }
:root[data-theme="dark"]  .card-header   { background:#232333; border-color:#3a3a50; }
:root[data-theme="dark"]  .notice-card             { background:#2b2b3a; color:#d0d0e0; }
:root[data-theme="dark"]  .notice-card .notice-meta{ color:#888; }
:root[data-theme="dark"]  .notice-card .notice-body{ color:#c0c0d0; }
:root[data-theme="dark"]  .notice-card.notice-urgent { background:#3a1a1a; }
:root[data-theme="light"] .card          { background:#fff;    border-color:#dee2e6; }
:root[data-theme="light"] .card-header   { background:#f8f9fa; border-color:#dee2e6; }
:root[data-theme="light"] .notice-card             { background:#fff; }
:root[data-theme="light"] .notice-card.notice-urgent { background:#fff5f5; }
</style>

<?php
$notices = isset($notices) ? $notices : array();
$priorityClass = array(
    'normal'    => 'notice-normal',
    'important' => 'notice-important',
    'urgent'    => 'notice-urgent',
);
$priorityBadge = array(
    'normal'    => 'bg-secondary',
    'important' => 'bg-warning text-dark',
    'urgent'    => 'bg-danger',
);
?>

<div class="content-header">
    <div class="d-flex align-items-center justify-content-end flex-wrap gap-2">
        <?php if (is_admin_loggedin() || is_superadmin_loggedin() || is_teacher_loggedin()): ?>
        <a href="<?php echo base_url('noticeboard/manage'); ?>" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-cog me-1"></i>Manage Notices
        </a>
        <?php endif; ?>
    </div>
</div>

<div class="container-fluid">

    <?php if (empty($notices)): ?>
    <div class="card">
        <div class="card-body empty-state text-muted">
            <i class="fas fa-bullhorn"></i>
            <p class="mb-0">No active notices at this time. Check back later.</p>
        </div>
    </div>
    <?php else: ?>
        <?php foreach ($notices as $n):
            $cls   = $priorityClass[$n['priority']] ?? 'notice-normal';
            $badge = $priorityBadge[$n['priority']] ?? 'bg-secondary';
        ?>
        <div class="notice-card <?php echo $cls; ?>">
            <div class="notice-title">
                <?php echo htmlspecialchars($n['title']); ?>
                <span class="badge <?php echo $badge; ?> ms-2"><?php echo ucfirst($n['priority']); ?></span>
            </div>
            <div class="notice-meta">
                <i class="fas fa-calendar-alt me-1"></i><?php echo date('d F Y', strtotime($n['notice_date'])); ?>
                &nbsp;&bull;&nbsp;
                <i class="fas fa-users me-1"></i><?php echo htmlspecialchars(ucwords(str_replace(',', ', ', $n['audience']))); ?>
                <?php if (!empty($n['expiry_date'])): ?>
                &nbsp;&bull;&nbsp;
                <i class="fas fa-clock me-1"></i>Expires: <?php echo date('d F Y', strtotime($n['expiry_date'])); ?>
                <?php endif; ?>
            </div>
            <?php if (!empty($n['details'])): ?>
            <div class="notice-body small"><?php echo htmlspecialchars($n['details']); ?></div>
            <?php endif; ?>
            <?php if (!empty($n['attachment'])): ?>
            <div class="notice-attach">
                <a href="<?php echo base_url('uploads/notices/' . $n['attachment']); ?>" target="_blank" class="btn btn-xs btn-outline-secondary">
                    <i class="fas fa-paperclip me-1"></i>View Attachment
                </a>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>
