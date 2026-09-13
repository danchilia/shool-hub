<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
.dir-school-card {
    background:#fff;border:1px solid #e0e6ed;border-radius:8px;padding:14px 16px;
    margin-bottom:10px;display:flex;justify-content:space-between;align-items:flex-start;gap:12px;
}
.dir-school-card.in-pipeline { border-left:4px solid #27ae60; }
.dir-school-name { font-weight:700;color:#1a2e4a;font-size:.95rem; }
.dir-school-meta { font-size:.78rem;color:#7f8c8d;margin-top:3px; }
.dir-tag {
    display:inline-block;font-size:.7rem;font-weight:600;padding:2px 8px;
    border-radius:10px;margin-right:4px;margin-top:3px;
}
.tag-type      { background:#eaf4fb;color:#2980b9; }
.tag-ownership { background:#fef9e7;color:#d68910; }
.tag-area      { background:#f0f0f0;color:#555; }
</style>

<?php
$info    = $this->session->flashdata('dir_info');
$success = $this->session->flashdata('dir_success');
$warn    = $this->session->flashdata('dir_warn');
?>
<?php if ($success): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $success; ?></div><?php endif; ?>
<?php if ($info):    ?><div class="alert alert-info"><i class="fas fa-info-circle"></i> <?php echo $info; ?></div><?php endif; ?>
<?php if ($warn):    ?><div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> <?php echo $warn; ?></div><?php endif; ?>

<!-- Filters -->
<?php echo form_open(current_url(), ['method' => 'get']); ?>
<div class="row" style="margin-bottom:12px;">
    <div class="col-xs-12 col-sm-4" style="margin-bottom:6px;">
        <input type="text" name="q" class="form-control" placeholder="Search school name…" value="<?php echo html_escape($filters['q']); ?>">
    </div>
    <div class="col-xs-6 col-sm-2" style="margin-bottom:6px;">
        <select name="region" class="form-control">
            <option value="">All Regions</option>
            <?php foreach ($regions as $r): ?>
            <option value="<?php echo html_escape($r['region']); ?>" <?php echo $filters['region']==$r['region']?'selected':''; ?>>
                <?php echo html_escape($r['region']); ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-xs-6 col-sm-2" style="margin-bottom:6px;">
        <select name="type" class="form-control">
            <option value="">All Types</option>
            <?php foreach ($types as $t): ?>
            <option value="<?php echo html_escape($t['type']); ?>" <?php echo $filters['type']==$t['type']?'selected':''; ?>>
                <?php echo html_escape($t['type']); ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-xs-6 col-sm-2" style="margin-bottom:6px;">
        <select name="ownership" class="form-control">
            <option value="">All Ownership</option>
            <?php foreach ($ownerships as $o): ?>
            <option value="<?php echo html_escape($o['ownership']); ?>" <?php echo $filters['ownership']==$o['ownership']?'selected':''; ?>>
                <?php echo html_escape($o['ownership']); ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-xs-6 col-sm-2" style="margin-bottom:6px;">
        <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search"></i> Search</button>
    </div>
</div>
<?php echo form_close(); ?>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
    <span class="text-muted" style="font-size:13px;">
        <?php echo number_format($total); ?> schools
        <?php if (!empty($filters['region'])): ?>in <?php echo html_escape($filters['region']); ?><?php endif; ?>
    </span>
    <a href="<?php echo base_url('agent_portal/add_new_to_directory'); ?>" class="btn btn-sm btn-warning">
        <i class="fas fa-plus"></i> School Not Listed? Add It
    </a>
</div>

<?php if (empty($schools)): ?>
<div class="ap-card" style="text-align:center;padding:40px;color:#7f8c8d;">
    <i class="fas fa-school" style="font-size:36px;margin-bottom:12px;display:block;"></i>
    No schools found. Try changing your filters or <a href="<?php echo base_url('agent_portal/add_new_to_directory'); ?>">add a new school</a>.
</div>
<?php else: ?>

<?php foreach ($schools as $s): ?>
<?php $inPip = in_array($s['id'], $in_pipeline); ?>
<div class="dir-school-card <?php echo $inPip ? 'in-pipeline' : ''; ?>">
    <div style="flex:1;">
        <div class="dir-school-name"><?php echo html_escape($s['school_name']); ?></div>
        <div class="dir-school-meta">
            <?php if (!empty($s['phone'])): ?>
            <i class="fas fa-phone"></i> <?php echo html_escape($s['phone']); ?> &nbsp;
            <?php endif; ?>
            <?php if (!empty($s['road_location'])): ?>
            <i class="fas fa-map-marker-alt"></i> <?php echo html_escape($s['road_location']); ?>
            <?php endif; ?>
        </div>
        <div style="margin-top:5px;">
            <?php if (!empty($s['type'])): ?>
            <span class="dir-tag tag-type"><?php echo html_escape($s['type']); ?></span>
            <?php endif; ?>
            <?php if (!empty($s['ownership']) && $s['ownership'] !== 'Not Specified'): ?>
            <span class="dir-tag tag-ownership"><?php echo html_escape($s['ownership']); ?></span>
            <?php endif; ?>
            <?php if (!empty($s['area'])): ?>
            <span class="dir-tag tag-area"><i class="fas fa-map-pin"></i> <?php echo html_escape($s['area']); ?></span>
            <?php endif; ?>
        </div>
    </div>
    <div style="flex-shrink:0;padding-top:4px;">
        <?php if ($inPip): ?>
        <span style="color:#27ae60;font-size:12px;font-weight:600;">
            <i class="fas fa-check-circle"></i> In Pipeline
        </span>
        <?php else: ?>
        <a href="<?php echo base_url('agent_portal/add_from_directory/' . $s['id']); ?>"
           class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Add to Pipeline
        </a>
        <?php endif; ?>
    </div>
</div>
<?php endforeach; ?>

<!-- Pagination -->
<?php if ($total > $limit): ?>
<div class="text-center" style="margin-top:16px;">
    <?php
    $pages = ceil($total / $limit);
    for ($p = 1; $p <= $pages; $p++):
        $qs = http_build_query(array_merge($filters, ['page' => $p]));
    ?>
    <a href="?<?php echo $qs; ?>" class="btn btn-xs <?php echo $p == $page ? 'btn-primary' : 'btn-default'; ?>" style="margin:2px;"><?php echo $p; ?></a>
    <?php endfor; ?>
</div>
<?php endif; ?>

<?php endif; ?>
