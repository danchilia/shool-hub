<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
.dir-search-bar {
    display:flex;gap:10px;align-items:center;margin-bottom:14px;flex-wrap:wrap;
}
.dir-search-bar .search-wrap {
    flex:1;min-width:200px;position:relative;
}
.dir-search-bar .search-wrap i {
    position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#aaa;font-size:.9rem;
}
.dir-search-bar input[type=text] {
    width:100%;padding:9px 12px 9px 34px;border:1.5px solid #d0d7de;border-radius:8px;
    font-size:.9rem;outline:none;transition:border .2s;
}
.dir-search-bar input[type=text]:focus { border-color:#1a6fb3; }


.dir-stats {
    display:flex;justify-content:space-between;align-items:center;
    margin-bottom:12px;font-size:.82rem;color:#7f8c8d;
}

.dir-school-card {
    background:#fff;border:1px solid #e0e6ed;border-radius:10px;padding:14px 16px;
    margin-bottom:8px;display:flex;justify-content:space-between;align-items:flex-start;gap:12px;
    transition:box-shadow .15s;
}
.dir-school-card:hover { box-shadow:0 2px 10px rgba(0,0,0,.08); }
.dir-school-card.in-pipeline { border-left:4px solid #27ae60; }
.dir-school-name { font-weight:700;color:#1a2e4a;font-size:.95rem;line-height:1.3; }
.dir-school-meta { font-size:.78rem;color:#7f8c8d;margin-top:4px;line-height:1.6; }
.dir-tag {
    display:inline-block;font-size:.7rem;font-weight:600;padding:2px 8px;
    border-radius:10px;margin-right:4px;margin-top:4px;
}
.tag-type      { background:#eaf4fb;color:#2980b9; }
.tag-ownership { background:#fef9e7;color:#d68910; }
.tag-area      { background:#f0f0f0;color:#555; }

.dir-empty {
    text-align:center;padding:50px 20px;color:#aaa;
}
.dir-empty i { font-size:40px;margin-bottom:10px;display:block; }

.dir-add-btn {
    background:#e67e22;color:#fff;border:none;padding:8px 16px;border-radius:8px;
    font-size:.82rem;font-weight:600;cursor:pointer;text-decoration:none;white-space:nowrap;
    display:inline-flex;align-items:center;gap:6px;
}
.dir-add-btn:hover { background:#d35400;color:#fff;text-decoration:none; }

#no-results {
    display:none;text-align:center;padding:40px;color:#aaa;
}
</style>

<?php
$info    = $this->session->flashdata('dir_info');
$success = $this->session->flashdata('dir_success');
$warn    = $this->session->flashdata('dir_warn');
?>
<?php if ($success): ?><div class="alert alert-success mb-3"><i class="fas fa-check-circle"></i> <?php echo $success; ?></div><?php endif; ?>
<?php if ($info):    ?><div class="alert alert-info mb-3"><i class="fas fa-info-circle"></i> <?php echo $info; ?></div><?php endif; ?>
<?php if ($warn):    ?><div class="alert alert-warning mb-3"><i class="fas fa-exclamation-triangle"></i> <?php echo $warn; ?></div><?php endif; ?>

<!-- Live search bar -->
<div class="dir-search-bar">
    <div class="search-wrap">
        <i class="fas fa-search"></i>
        <input type="text" id="dir-search" placeholder="Search school name, area, location…" autocomplete="off">
    </div>
    <a href="<?php echo base_url('agent_portal/add_new_to_directory'); ?>" class="dir-add-btn">
        <i class="fas fa-plus"></i> Add Unlisted School
    </a>
</div>

<!-- Area & Type dropdowns -->
<?php
$areas = [];
foreach ($schools as $s) {
    $a = trim($s['area'] ?? '');
    if ($a && strtolower($a) !== 'not specified' && strtolower($a) !== 'not listed') $areas[$a] = true;
}
ksort($areas);
?>
<div style="display:flex;gap:10px;margin-bottom:14px;flex-wrap:wrap;">
    <select id="area-filter" class="form-control" style="flex:1;min-width:160px;max-width:220px;">
        <option value="">All Areas</option>
        <?php foreach ($areas as $area => $_): ?>
        <option value="<?php echo html_escape($area); ?>"><?php echo html_escape($area); ?></option>
        <?php endforeach; ?>
    </select>
    <select id="type-filter" class="form-control" style="flex:1;min-width:180px;max-width:240px;">
        <option value="">All Types</option>
        <?php foreach ($types as $t): if (empty($t['type'])) continue; ?>
        <option value="<?php echo html_escape($t['type']); ?>"><?php echo html_escape($t['type']); ?></option>
        <?php endforeach; ?>
    </select>
</div>

<!-- Stats row -->
<div class="dir-stats">
    <span id="visible-count"><?php echo number_format($total); ?> schools in your area</span>
    <span style="font-size:.75rem;color:#bbb;">
        <i class="fas fa-circle" style="color:#27ae60;font-size:.55rem;vertical-align:middle;"></i> Green = already in your pipeline
    </span>
</div>

<!-- School cards -->
<div id="school-list">
<?php if (empty($schools)): ?>
<div class="dir-empty">
    <i class="fas fa-school"></i>
    No schools found in your area yet.<br>
    <a href="<?php echo base_url('agent_portal/add_new_to_directory'); ?>">Add a school manually</a>
</div>
<?php else: ?>
<?php foreach ($schools as $s): ?>
<?php $inPip = in_array($s['id'], $in_pipeline); ?>
<div class="dir-school-card <?php echo $inPip ? 'in-pipeline' : ''; ?>"
     data-name="<?php echo strtolower(html_escape($s['school_name'])); ?>"
     data-area="<?php echo strtolower(html_escape($s['area'] ?? '')); ?>"
     data-location="<?php echo strtolower(html_escape($s['road_location'] ?? '')); ?>"
     data-type="<?php echo html_escape($s['type'] ?? ''); ?>">
    <div style="flex:1;">
        <div class="dir-school-name"><?php echo html_escape($s['school_name']); ?></div>
        <div class="dir-school-meta">
            <?php if (!empty($s['area']) && $s['area'] !== 'Not specified'): ?>
            <i class="fas fa-map-pin" style="color:#e67e22;"></i> <?php echo html_escape($s['area']); ?> &nbsp;
            <?php endif; ?>
            <?php if (!empty($s['phone']) && $s['phone'] !== 'Not listed'): ?>
            <i class="fas fa-phone" style="color:#27ae60;"></i> <?php echo html_escape($s['phone']); ?> &nbsp;
            <?php endif; ?>
            <?php if (!empty($s['road_location']) && $s['road_location'] !== 'Not listed'): ?>
            <i class="fas fa-road" style="color:#aaa;"></i> <?php echo html_escape($s['road_location']); ?>
            <?php endif; ?>
        </div>
        <div>
            <?php if (!empty($s['type'])): ?>
            <span class="dir-tag tag-type"><?php echo html_escape($s['type']); ?></span>
            <?php endif; ?>
            <?php if (!empty($s['ownership']) && !in_array($s['ownership'], ['Not Specified','Not specified'])): ?>
            <span class="dir-tag tag-ownership"><?php echo html_escape($s['ownership']); ?></span>
            <?php endif; ?>
        </div>
    </div>
    <div style="flex-shrink:0;padding-top:4px;">
        <?php if ($inPip): ?>
        <span style="color:#27ae60;font-size:.8rem;font-weight:700;">
            <i class="fas fa-check-circle"></i> In Pipeline
        </span>
        <?php else: ?>
        <a href="<?php echo base_url('agent_portal/add_from_directory/' . $s['id']); ?>"
           class="btn btn-sm btn-primary" style="font-size:.78rem;">
            <i class="fas fa-plus"></i> Add
        </a>
        <?php endif; ?>
    </div>
</div>
<?php endforeach; ?>
<?php endif; ?>
</div>

<div id="no-results">
    <i class="fas fa-search" style="font-size:32px;margin-bottom:10px;display:block;"></i>
    No schools match your search.<br>
    <a href="<?php echo base_url('agent_portal/add_new_to_directory'); ?>" style="font-size:.85rem;">
        Add this school manually →
    </a>
</div>

<!-- Server-side pagination (shown only when no live filter active) -->
<?php if ($total > $limit): ?>
<div id="pagination" class="text-center" style="margin-top:16px;">
    <?php
    $pages = ceil($total / $limit);
    for ($p = 1; $p <= $pages; $p++):
        $qs = http_build_query(array_merge($filters, ['page' => $p]));
    ?>
    <a href="?<?php echo $qs; ?>" class="btn btn-xs <?php echo $p == $page ? 'btn-primary' : 'btn-default'; ?>" style="margin:2px;"><?php echo $p; ?></a>
    <?php endfor; ?>
</div>
<?php endif; ?>

<script>
(function(){
    var cards       = Array.from(document.querySelectorAll('.dir-school-card'));
    var searchInput = document.getElementById('dir-search');
    var noResults   = document.getElementById('no-results');
    var countEl     = document.getElementById('visible-count');
    var pagination  = document.getElementById('pagination');
    var activeType  = '';
    var activeArea  = '';

    function filter() {
        var q     = searchInput.value.toLowerCase().trim();
        var shown = 0;
        cards.forEach(function(c) {
            var nameMatch = !q || c.dataset.name.indexOf(q) > -1
                               || c.dataset.area.indexOf(q) > -1
                               || c.dataset.location.indexOf(q) > -1;
            var typeMatch = !activeType || c.dataset.type === activeType;
            var areaMatch = !activeArea || c.dataset.area.toLowerCase() === activeArea.toLowerCase();
            if (nameMatch && typeMatch && areaMatch) { c.style.display = ''; shown++; }
            else c.style.display = 'none';
        });
        countEl.textContent = shown + ' school' + (shown !== 1 ? 's' : '') + ' shown';
        noResults.style.display = (shown === 0 && cards.length > 0) ? 'block' : 'none';
        if (pagination) pagination.style.display = (q || activeType || activeArea) ? 'none' : '';
    }

    searchInput.addEventListener('input', filter);

    var areaSelect = document.getElementById('area-filter');
    var typeSelect = document.getElementById('type-filter');
    if (areaSelect) areaSelect.addEventListener('change', function(){ activeArea = this.value; filter(); });
    if (typeSelect) typeSelect.addEventListener('change', function(){ activeType = this.value; filter(); });
})();
</script>
