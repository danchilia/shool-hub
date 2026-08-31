<?php
$ld      = $level_data;
$current = $ld['current'];
$salary  = $ld['salary'];
$active  = $ld['active_schools'];
$next    = $ld['next_level'];
$pct     = $ld['progress_pct'];
$toNext  = $ld['schools_to_next'];
$c       = $current['color'];
?>
<style>
.lvl-hero {
    background: linear-gradient(135deg, <?=$c?> 0%, <?=$c?>cc 100%);
    border-radius: 14px; padding: 32px 28px; color: #fff;
    margin-bottom: 24px; position: relative; overflow: hidden;
}
.lvl-hero::after {
    content: ''; position: absolute; right: -30px; top: -30px;
    width: 180px; height: 180px; border-radius: 50%;
    background: rgba(255,255,255,.08);
}
.lvl-badge {
    display: inline-block; background: rgba(255,255,255,.2);
    border: 1.5px solid rgba(255,255,255,.4);
    border-radius: 20px; padding: 4px 16px;
    font-size: .78rem; font-weight: 700; letter-spacing: 1px;
    text-transform: uppercase; margin-bottom: 10px;
}
.lvl-name  { font-size: 2rem; font-weight: 800; margin: 0 0 4px; }
.lvl-salary { font-size: 1.1rem; opacity: .9; margin: 0; }

.lvl-progress-wrap { margin-top: 20px; }
.lvl-progress-label { font-size: .8rem; opacity: .85; margin-bottom: 6px; display: flex; justify-content: space-between; }
.lvl-bar-bg { background: rgba(255,255,255,.25); border-radius: 10px; height: 10px; }
.lvl-bar-fill { background: #fff; border-radius: 10px; height: 10px; transition: width .4s; }

.lvl-contract-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(255,255,255,.15); border-radius: 20px;
    padding: 5px 14px; font-size: .8rem; font-weight: 600; margin-top: 14px;
}

.lvl-table-wrap { background: var(--ap-white); border-radius: 12px; border: 1px solid var(--ap-border); overflow: hidden; }
.lvl-table-head { padding: 16px 20px; border-bottom: 1px solid var(--ap-border); font-weight: 700; font-size: .95rem; }
table.lvl-table { width: 100%; border-collapse: collapse; }
table.lvl-table th { background: var(--ap-bg); padding: 10px 20px; font-size: .75rem; text-transform: uppercase; letter-spacing: .8px; color: var(--ap-muted); font-weight: 600; text-align: left; }
table.lvl-table td { padding: 13px 20px; border-top: 1px solid var(--ap-border); font-size: .88rem; }
table.lvl-table tr.is-current td { background: <?=$c?>18; }
.lvl-dot { width: 12px; height: 12px; border-radius: 50%; display: inline-block; margin-right: 8px; }
.lvl-current-tag { background: <?=$c?>; color: #fff; font-size: .68rem; font-weight: 700; border-radius: 10px; padding: 2px 8px; margin-left: 6px; }
</style>

<!-- HERO: current level -->
<div class="lvl-hero">
    <div class="lvl-badge"><i class="fas fa-trophy me-1"></i> Your Level</div>
    <div class="lvl-name"><?= htmlspecialchars($current['name']) ?></div>
    <p class="lvl-salary">
        <?php if ($salary > 0): ?>
            Monthly Retainer: <strong>KSh <?= number_format($salary) ?></strong>
        <?php else: ?>
            Bring <strong>10 active schools</strong> to start earning a monthly retainer
        <?php endif; ?>
    </p>

    <?php if ($current['contract']): ?>
    <div class="lvl-contract-badge">
        <i class="fas fa-file-contract"></i> Permanent Yearly Contract — Renewable
    </div>
    <?php endif; ?>

    <?php if ($next): ?>
    <div class="lvl-progress-wrap">
        <div class="lvl-progress-label">
            <span>Progress to <?= htmlspecialchars($next['name']) ?></span>
            <span><?= $active ?> / <?= $next['min'] ?> schools</span>
        </div>
        <div class="lvl-bar-bg">
            <div class="lvl-bar-fill" style="width:<?= $pct ?>%"></div>
        </div>
        <div style="font-size:.78rem;opacity:.8;margin-top:6px">
            <?= $toNext ?> more active school<?= $toNext !== 1 ? 's' : '' ?> to reach <?= htmlspecialchars($next['name']) ?>
        </div>
    </div>
    <?php else: ?>
    <div class="lvl-progress-wrap">
        <div class="lvl-bar-bg"><div class="lvl-bar-fill" style="width:100%"></div></div>
        <div style="font-size:.78rem;opacity:.8;margin-top:6px">You have reached the maximum level!</div>
    </div>
    <?php endif; ?>
</div>

<!-- STATS ROW -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card text-center">
            <div class="stat-val"><?= $active ?></div>
            <div class="stat-lbl">Active Schools</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card text-center">
            <div class="stat-val">KSh <?= number_format($salary) ?></div>
            <div class="stat-lbl">Monthly Retainer</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card text-center">
            <div class="stat-val"><?= $toNext > 0 ? $toNext : '—' ?></div>
            <div class="stat-lbl">Schools to Next Level</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card text-center">
            <div class="stat-val" style="font-size:1.1rem"><?= $current['contract'] ? '✅ Active' : 'Not yet' ?></div>
            <div class="stat-lbl">Yearly Contract</div>
        </div>
    </div>
</div>

<!-- ALL LEVELS TABLE -->
<div class="lvl-table-wrap">
    <div class="lvl-table-head"><i class="fas fa-layer-group me-2"></i>All Levels</div>
    <table class="lvl-table">
        <thead>
            <tr>
                <th>Level</th>
                <th>Schools Required</th>
                <th>Monthly Retainer</th>
                <th>Contract</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($ld['all_levels'] as $lvl):
            $isCurrent = ($lvl['name'] === $current['name']);
            $lvlSalary = ($lvl['min'] < 10) ? 0 : min($lvl['min'] * 1000, 50000);
        ?>
            <tr class="<?= $isCurrent ? 'is-current' : '' ?>">
                <td>
                    <span class="lvl-dot" style="background:<?= $lvl['color'] ?>"></span>
                    <strong><?= htmlspecialchars($lvl['name']) ?></strong>
                    <?php if ($isCurrent): ?>
                        <span class="lvl-current-tag">YOU ARE HERE</span>
                    <?php endif; ?>
                </td>
                <td><?= $lvl['min'] === 0 ? '0 – 9' : ($lvl['min'] === 50 ? '50+' : $lvl['min'] . ' – ' . ($lvl['min'] + 4)) ?></td>
                <td><?= $lvlSalary > 0 ? 'KSh ' . number_format($lvlSalary) . '/month' : '—' ?></td>
                <td>
                    <?php if ($lvl['contract']): ?>
                        <span style="color:#27ae60;font-weight:600"><i class="fas fa-check-circle me-1"></i>Yearly (Renewable)</span>
                    <?php else: ?>
                        <span style="color:#aaa">Commission only</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<p style="font-size:.78rem;color:var(--ap-muted);margin-top:12px;padding:0 4px">
    * Monthly retainer is paid while your active schools continue using and paying for the system.
    A school that stops paying is removed from your active count.
</p>
