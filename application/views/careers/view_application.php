<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
<div class="col-md-8">

    <!-- Applicant & Position Info -->
    <div class="panel">
        <header class="panel-heading" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;">
            <h4 class="panel-title"><i class="fas fa-user me-2"></i><?php echo html_escape($app['full_name']); ?></h4>
            <?php
            $colors = ['pending'=>'#f39c12','shortlisted'=>'#3498db','interview'=>'#9b59b6','rejected'=>'#e74c3c','hired'=>'#27ae60'];
            $c = $colors[$app['status']] ?? '#999';
            ?>
            <span class="badge" style="background:<?php echo $c; ?>;color:#fff;padding:6px 14px;border-radius:20px;font-size:13px;">
                <?php echo ucfirst($app['status']); ?>
            </span>
        </header>
        <div class="panel-body">
            <table class="table table-condensed" style="margin-bottom:0;">
                <tr><td class="text-muted" width="120">Position</td><td><strong><?php echo html_escape($app['position_title']); ?></strong></td></tr>
                <tr><td class="text-muted">Department</td><td><?php echo html_escape($app['department']) ?: '—'; ?></td></tr>
                <tr><td class="text-muted">Email</td><td><a href="mailto:<?php echo html_escape($app['email']); ?>"><?php echo html_escape($app['email']); ?></a></td></tr>
                <tr><td class="text-muted">Phone</td><td><?php echo html_escape($app['phone']); ?></td></tr>
                <tr><td class="text-muted">Applied</td><td><?php echo date('d F Y', strtotime($app['created_at'])); ?></td></tr>
                <tr>
                    <td class="text-muted">CV</td>
                    <td>
                        <a href="<?php echo base_url('careers/download_cv/' . $app['id']); ?>" class="btn btn-xs btn-default">
                            <i class="fas fa-download me-1"></i><?php echo html_escape($app['cv_orig_name']); ?>
                        </a>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Cover Letter -->
    <?php if (!empty($app['cover_letter'])): ?>
    <div class="panel">
        <header class="panel-heading">
            <h4 class="panel-title"><i class="fas fa-align-left me-2"></i>Cover Letter</h4>
        </header>
        <div class="panel-body">
            <p style="white-space:pre-wrap;line-height:1.7;"><?php echo nl2br(html_escape($app['cover_letter'])); ?></p>
        </div>
    </div>
    <?php endif; ?>

    <!-- Message Thread -->
    <div class="panel">
        <header class="panel-heading">
            <h4 class="panel-title"><i class="fas fa-comments me-2"></i>HR Messages</h4>
        </header>
        <div class="panel-body">
            <?php if (empty($replies)): ?>
            <p class="text-center text-muted">No messages yet. Use the form below to send your first reply.</p>
            <?php else: ?>
            <?php foreach ($replies as $r): ?>
            <div style="background:<?php echo $r['sender']==='admin'?'#eaf4fb':'#f0f9f5'; ?>;border-left:3px solid <?php echo $r['sender']==='admin'?'#1a5276':'#1abc9c'; ?>;border-radius:8px;padding:12px 16px;margin-bottom:12px;">
                <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                    <strong style="color:#1a5276;">
                        <?php echo $r['sender']==='admin' ? '<i class="fas fa-user-tie me-1"></i>HR (You)' : '<i class="fas fa-user me-1"></i>Applicant'; ?>
                    </strong>
                    <small class="text-muted"><?php echo date('d M Y, H:i', strtotime($r['created_at'])); ?></small>
                </div>
                <p style="margin:0;white-space:pre-wrap;"><?php echo nl2br(html_escape($r['message'])); ?></p>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</div>

<!-- Reply + Status Panel -->
<div class="col-md-4">
    <div class="panel">
        <header class="panel-heading">
            <h4 class="panel-title"><i class="fas fa-reply me-2"></i>Reply & Update Status</h4>
        </header>
        <?php echo form_open($this->uri->uri_string()); ?>
        <div class="panel-body">
            <div class="form-group">
                <label class="control-label">Update Status</label>
                <select name="app_status" class="form-control">
                    <?php
                    $statuses = ['pending' => 'Pending', 'shortlisted' => 'Shortlisted', 'interview' => 'Invite to Interview', 'rejected' => 'Rejected', 'hired' => 'Hired'];
                    foreach ($statuses as $val => $label):
                    ?>
                    <option value="<?php echo $val; ?>" <?php echo $app['status'] === $val ? 'selected' : ''; ?>>
                        <?php echo $label; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="control-label">Message to Applicant <span class="required">*</span></label>
                <textarea name="reply_message" class="form-control" rows="8" required
                    placeholder="Write your message to the applicant. They will receive it by email."></textarea>
                <small class="text-muted">The applicant will be notified by email automatically.</small>
            </div>
        </div>
        <div class="panel-footer">
            <a href="<?php echo base_url('careers/applications/' . $app['position_id']); ?>" class="btn btn-default btn-sm">
                <i class="fas fa-arrow-left me-1"></i>Back
            </a>
            <button type="submit" class="btn btn-primary btn-sm pull-right">
                <i class="fas fa-paper-plane me-1"></i>Send Reply
            </button>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

</div>
