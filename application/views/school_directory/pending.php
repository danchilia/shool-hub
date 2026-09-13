<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="panel">
    <div class="panel-heading">
        <h4 class="panel-title"><i class="fas fa-clock me-2" style="color:#e67e22;"></i>Pending Review — Agent-Added Schools</h4>
    </div>
    <div class="panel-body" style="padding:0;">
        <table class="table table-condensed table-hover" style="margin:0;">
            <thead>
                <tr>
                    <th>School Name</th>
                    <th>Type</th>
                    <th>Ownership</th>
                    <th>Area / Region</th>
                    <th>Phone</th>
                    <th>Added By</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($schools)): ?>
            <tr><td colspan="8" class="text-center text-muted" style="padding:30px;">No schools pending review.</td></tr>
            <?php else: ?>
            <?php foreach ($schools as $s): ?>
            <tr>
                <td>
                    <strong><?php echo html_escape($s['school_name']); ?></strong>
                    <?php if (!empty($s['road_location'])): ?>
                    <br><small class="text-muted"><?php echo html_escape($s['road_location']); ?></small>
                    <?php endif; ?>
                </td>
                <td><?php echo html_escape($s['type']) ?: '—'; ?></td>
                <td><?php echo html_escape($s['ownership']) ?: '—'; ?></td>
                <td><?php echo html_escape($s['area']) ?: '—'; ?><?php if (!empty($s['region'])): ?> / <?php echo html_escape($s['region']); ?><?php endif; ?></td>
                <td><?php echo html_escape($s['phone']) ?: '—'; ?></td>
                <td><?php echo html_escape(trim($s['first_name'] . ' ' . $s['last_name'])) ?: 'Unknown'; ?></td>
                <td><?php echo date('d M Y', strtotime($s['created_at'])); ?></td>
                <td>
                    <a href="<?php echo base_url('school_directory/approve/' . $s['id']); ?>" class="btn btn-xs btn-success">
                        <i class="fas fa-check"></i> Approve
                    </a>
                    <a href="<?php echo base_url('school_directory/delete/' . $s['id']); ?>"
                       class="btn btn-xs btn-danger"
                       onclick="return confirm('Remove this school?')">
                        <i class="fas fa-times"></i> Remove
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<a href="<?php echo base_url('school_directory'); ?>" class="btn btn-default btn-sm">
    <i class="fas fa-arrow-left me-1"></i>Back to Directory
</a>
