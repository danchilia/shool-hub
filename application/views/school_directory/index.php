<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Filter bar -->
<?php echo form_open(current_url(), ['method' => 'get', 'class' => 'row']); ?>
<div class="col-md-3">
    <input type="text" name="q" class="form-control" placeholder="Search school name, area…" value="<?php echo html_escape($filters['q']); ?>">
</div>
<div class="col-md-2">
    <select name="region" class="form-control">
        <option value="">All Regions</option>
        <?php foreach ($regions as $r): ?>
        <option value="<?php echo html_escape($r['region']); ?>" <?php echo $filters['region']==$r['region']?'selected':''; ?>>
            <?php echo html_escape($r['region']); ?>
        </option>
        <?php endforeach; ?>
    </select>
</div>
<div class="col-md-2">
    <select name="type" class="form-control">
        <option value="">All Types</option>
        <?php foreach ($types as $t): ?>
        <option value="<?php echo html_escape($t['type']); ?>" <?php echo $filters['type']==$t['type']?'selected':''; ?>>
            <?php echo html_escape($t['type']); ?>
        </option>
        <?php endforeach; ?>
    </select>
</div>
<div class="col-md-2">
    <select name="ownership" class="form-control">
        <option value="">All Ownership</option>
        <?php foreach ($ownerships as $o): ?>
        <option value="<?php echo html_escape($o['ownership']); ?>" <?php echo $filters['ownership']==$o['ownership']?'selected':''; ?>>
            <?php echo html_escape($o['ownership']); ?>
        </option>
        <?php endforeach; ?>
    </select>
</div>
<div class="col-md-2">
    <select name="status" class="form-control">
        <option value="">All Status</option>
        <option value="active"         <?php echo $filters['status']==='active'?'selected':''; ?>>Active</option>
        <option value="pending_review" <?php echo $filters['status']==='pending_review'?'selected':''; ?>>Pending Review</option>
    </select>
</div>
<div class="col-md-1">
    <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search"></i></button>
</div>
<?php echo form_close(); ?>

<div class="row" style="margin-top:16px;margin-bottom:6px;">
    <div class="col-md-8">
        <span class="text-muted" style="font-size:13px;">
            <?php echo number_format($total); ?> schools found
            <?php if ($total > $limit): ?>
            — Page <?php echo $page; ?> of <?php echo ceil($total/$limit); ?>
            <?php endif; ?>
        </span>
    </div>
    <div class="col-md-4 text-right">
        <?php if ($pending > 0): ?>
        <a href="<?php echo base_url('school_directory/pending'); ?>" class="btn btn-warning btn-sm" style="margin-right:6px;">
            <i class="fas fa-clock"></i> <?php echo $pending; ?> Pending Review
        </a>
        <?php endif; ?>
        <a href="<?php echo base_url('school_directory/upload'); ?>" class="btn btn-success btn-sm">
            <i class="fas fa-file-excel"></i> Upload Excel
        </a>
    </div>
</div>

<div class="panel">
    <div class="panel-body" style="padding:0;">
        <table class="table table-condensed table-hover" style="margin:0;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>School Name</th>
                    <th>Type</th>
                    <th>Ownership</th>
                    <th>Area</th>
                    <th>Region</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($schools)): ?>
                <tr><td colspan="9" class="text-center text-muted" style="padding:30px;">No schools found. <a href="<?php echo base_url('school_directory/upload'); ?>">Upload your first Excel file.</a></td></tr>
            <?php else: ?>
            <?php $offset = ($page - 1) * $limit; ?>
            <?php foreach ($schools as $i => $s): ?>
            <tr>
                <td class="text-muted"><?php echo $offset + $i + 1; ?></td>
                <td><strong><?php echo html_escape($s['school_name']); ?></strong>
                    <?php if (!empty($s['road_location'])): ?>
                    <br><small class="text-muted"><?php echo html_escape($s['road_location']); ?></small>
                    <?php endif; ?>
                </td>
                <td><?php echo html_escape($s['type']) ?: '—'; ?></td>
                <td><?php echo html_escape($s['ownership']) ?: '—'; ?></td>
                <td><?php echo html_escape($s['area']) ?: '—'; ?></td>
                <td><?php echo html_escape($s['region']) ?: '—'; ?></td>
                <td><?php echo html_escape($s['phone']) ?: '—'; ?></td>
                <td>
                    <?php if ($s['status'] === 'active'): ?>
                    <span class="label label-success">Active</span>
                    <?php else: ?>
                    <span class="label label-warning">Pending</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="<?php echo base_url('school_directory/delete/' . $s['id']); ?>"
                       class="btn btn-xs btn-danger"
                       onclick="return confirm('Remove this school from the directory?')">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<?php if ($total > $limit): ?>
<div class="text-center">
    <?php
    $pages = ceil($total / $limit);
    for ($p = 1; $p <= $pages; $p++):
        $qs = http_build_query(array_merge($filters, ['page' => $p]));
    ?>
    <a href="?<?php echo $qs; ?>" class="btn btn-xs <?php echo $p == $page ? 'btn-primary' : 'btn-default'; ?>" style="margin:2px;">
        <?php echo $p; ?>
    </a>
    <?php endfor; ?>
</div>
<?php endif; ?>
