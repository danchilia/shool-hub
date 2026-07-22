<?php
$notices = isset($notices) ? $notices : array();
$priorityClass = array(
    'normal'    => 'notice-normal',
    'important' => 'notice-important',
    'urgent'    => 'notice-urgent',
);
$priorityBadge = array(
    'normal'    => 'badge-secondary',
    'important' => 'badge-warning',
    'urgent'    => 'badge-danger',
);
?>
<style>
.notice-card { border-left: 4px solid #ccc; background: #fff; border-radius: 4px; margin-bottom: 16px; padding: 16px 20px; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
.notice-card.notice-important { border-left-color: #f0ad4e; }
.notice-card.notice-urgent    { border-left-color: #d9534f; background: #fff8f8; }
.notice-card .notice-title    { font-size: 1.08rem; font-weight: 700; margin-bottom: 6px; }
.notice-card .notice-meta     { font-size: .8rem; color: #888; margin-bottom: 8px; }
.notice-card .notice-body     { color: #444; white-space: pre-line; }
.notice-card .notice-attach   { margin-top: 10px; }
.empty-state { text-align:center; padding: 60px 20px; color: #aaa; }
.empty-state i { font-size: 3rem; margin-bottom: 12px; display:block; }
</style>

<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <h4 class="panel-title"><i class="fas fa-bullhorn"></i> Notice Board</h4>
            </header>
            <div class="panel-body">
                <?php if (empty($notices)): ?>
                    <div class="empty-state">
                        <i class="fas fa-bullhorn"></i>
                        <p>No active notices at this time. Check back later.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($notices as $n):
                        $cls = $priorityClass[$n['priority']] ?? 'notice-normal';
                        $badge = $priorityBadge[$n['priority']] ?? 'badge-secondary';
                    ?>
                    <div class="notice-card <?= $cls ?>">
                        <div class="notice-title">
                            <?= htmlspecialchars($n['title']) ?>
                            <span class="badge <?= $badge ?> ml-xs"><?= ucfirst($n['priority']) ?></span>
                        </div>
                        <div class="notice-meta">
                            <i class="fas fa-calendar-alt"></i> <?= date('d F Y', strtotime($n['notice_date'])) ?>
                            &nbsp;&bull;&nbsp;
                            <i class="fas fa-users"></i> <?= ucwords(str_replace(',', ', ', $n['audience'])) ?>
                            <?php if (!empty($n['expiry_date'])): ?>
                            &nbsp;&bull;&nbsp;
                            <i class="fas fa-clock"></i> Expires: <?= date('d F Y', strtotime($n['expiry_date'])) ?>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($n['details'])): ?>
                        <div class="notice-body"><?= htmlspecialchars($n['details']) ?></div>
                        <?php endif; ?>
                        <?php if (!empty($n['attachment'])): ?>
                        <div class="notice-attach">
                            <a href="<?= base_url('uploads/notices/' . $n['attachment']) ?>" target="_blank" class="btn btn-default btn-xs">
                                <i class="fas fa-paperclip"></i> View Attachment
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>
