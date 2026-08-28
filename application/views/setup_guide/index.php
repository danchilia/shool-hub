<style>
/* ── Token system ──────────────────────────────────────────────── */
:root {
    --sg-bg:          #f4f6fb;
    --sg-card-bg:     #ffffff;
    --sg-card-border: #e3e8f0;
    --sg-card-shadow: 0 2px 8px rgba(0,0,0,.06);
    --sg-text:        #1a1d23;
    --sg-muted:       #6b7280;
    --sg-tip-bg:      #f0f4ff;
    --sg-tip-border:  #c7d7ff;
    --sg-done-bg:     #edfaf4;
    --sg-done-border: #a7f3d0;
    --sg-lock-bg:     #f8fafc;
    --sg-lock-border: #e2e8f0;

    /* phase accent colours */
    --phase-1: #3b82f6;
    --phase-2: #8b5cf6;
    --phase-3: #14b8a6;
    --phase-4: #22c55e;
    --phase-5: #f59e0b;
    --phase-6: #ef4444;
}
@media (prefers-color-scheme: dark) {
    :root {
        --sg-bg:          #12131a;
        --sg-card-bg:     #1e2030;
        --sg-card-border: #2d3148;
        --sg-card-shadow: 0 2px 12px rgba(0,0,0,.35);
        --sg-text:        #e4e6f0;
        --sg-muted:       #9ca3b0;
        --sg-tip-bg:      #1a1f3a;
        --sg-tip-border:  #2d3a6e;
        --sg-done-bg:     #0d2318;
        --sg-done-border: #15532e;
        --sg-lock-bg:     #181920;
        --sg-lock-border: #2d3148;
    }
}
:root[data-theme="dark"]  {
    --sg-bg:          #12131a;
    --sg-card-bg:     #1e2030;
    --sg-card-border: #2d3148;
    --sg-card-shadow: 0 2px 12px rgba(0,0,0,.35);
    --sg-text:        #e4e6f0;
    --sg-muted:       #9ca3b0;
    --sg-tip-bg:      #1a1f3a;
    --sg-tip-border:  #2d3a6e;
    --sg-done-bg:     #0d2318;
    --sg-done-border: #15532e;
    --sg-lock-bg:     #181920;
    --sg-lock-border: #2d3148;
}
:root[data-theme="light"] {
    --sg-bg:          #f4f6fb;
    --sg-card-bg:     #ffffff;
    --sg-card-border: #e3e8f0;
    --sg-card-shadow: 0 2px 8px rgba(0,0,0,.06);
    --sg-text:        #1a1d23;
    --sg-muted:       #6b7280;
    --sg-tip-bg:      #f0f4ff;
    --sg-tip-border:  #c7d7ff;
    --sg-done-bg:     #edfaf4;
    --sg-done-border: #a7f3d0;
    --sg-lock-bg:     #f8fafc;
    --sg-lock-border: #e2e8f0;
}

/* ── Layout ────────────────────────────────────────────────────── */
.sg-wrap       { background: var(--sg-bg); border-radius: 12px; padding: 28px 24px 36px; }
.sg-hero       { display: flex; align-items: flex-start; gap: 20px; margin-bottom: 28px; flex-wrap: wrap; }
.sg-hero-text  { flex: 1 1 260px; }
.sg-hero h2    { font-size: 1.55rem; font-weight: 700; color: var(--sg-text); margin: 0 0 4px; }
.sg-hero p     { color: var(--sg-muted); margin: 0; font-size: .95rem; }
.sg-hero-bar   { flex: 0 0 300px; min-width: 200px; }

/* ── Progress bar ──────────────────────────────────────────────── */
.sg-progress-wrap { background: var(--sg-card-bg); border: 1px solid var(--sg-card-border); border-radius: 10px; padding: 16px 20px; }
.sg-progress-label{ display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 8px; font-size: .88rem; color: var(--sg-muted); }
.sg-progress-label strong { font-size: 1.1rem; color: var(--sg-text); }
.sg-bar-track  { height: 10px; border-radius: 99px; background: var(--sg-card-border); overflow: hidden; }
.sg-bar-fill   { height: 100%; border-radius: 99px; background: linear-gradient(90deg, #3b82f6, #8b5cf6); transition: width .6s ease; }
.sg-pct-note   { font-size: .78rem; color: var(--sg-muted); margin-top: 6px; }

/* ── Phase heading ─────────────────────────────────────────────── */
.sg-phase      { margin-bottom: 8px; margin-top: 32px; }
.sg-phase:first-of-type { margin-top: 8px; }
.sg-phase-label{
    display: inline-flex; align-items: center; gap: 8px;
    font-size: .78rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase;
    padding: 4px 12px; border-radius: 99px; color: #fff;
}
.sg-phase-desc { font-size: .82rem; color: var(--sg-muted); margin: 6px 0 16px 2px; }

/* ── Card ──────────────────────────────────────────────────────── */
.sg-card {
    background: var(--sg-card-bg);
    border: 1.5px solid var(--sg-card-border);
    border-radius: 12px;
    box-shadow: var(--sg-card-shadow);
    padding: 20px 18px 18px;
    display: flex; flex-direction: column; gap: 10px;
    position: relative; overflow: hidden;
    transition: transform .15s, box-shadow .15s;
    height: 100%;
}
.sg-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,.1); }
.sg-card.is-done  { border-color: var(--sg-done-border); background: var(--sg-done-bg); }

/* left accent bar */
.sg-card::before {
    content: ''; position: absolute; left: 0; top: 0; bottom: 0;
    width: 4px; border-radius: 12px 0 0 12px;
    background: var(--phase-color, #3b82f6);
}

/* ── Card: top row ─────────────────────────────────────────────── */
.sg-card-top   { display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; }
.sg-num-icon   { display: flex; align-items: center; gap: 10px; }
.sg-num        {
    width: 38px; height: 38px; border-radius: 50%; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: .85rem; font-weight: 800; color: #fff;
    background: var(--phase-color, #3b82f6);
}
.sg-icon       { font-size: 1.3rem; color: var(--phase-color, #3b82f6); margin-top: 2px; }

/* ── Status badge ──────────────────────────────────────────────── */
.sg-badge {
    font-size: .7rem; font-weight: 700; padding: 3px 9px; border-radius: 99px;
    white-space: nowrap; letter-spacing: .04em;
}
.sg-badge-done    { background: #d1fae5; color: #065f46; }
.sg-badge-prefill { background: #e0e7ff; color: #3730a3; }
.sg-badge-pending { background: #fef3c7; color: #92400e; }
.sg-badge-info    { background: #f0f4ff; color: #3b5dce; border: 1px solid #c7d7ff; }

/* ── Card body ─────────────────────────────────────────────────── */
.sg-title      { font-size: .98rem; font-weight: 700; color: var(--sg-text); margin: 0; line-height: 1.3; }
.sg-desc       { font-size: .83rem; color: var(--sg-muted); margin: 0; line-height: 1.5; }

/* ── Tip box ───────────────────────────────────────────────────── */
.sg-tip        {
    background: var(--sg-tip-bg); border: 1px solid var(--sg-tip-border);
    border-radius: 7px; padding: 8px 11px;
    font-size: .78rem; color: var(--sg-muted); line-height: 1.5;
    font-style: italic;
}
.sg-tip i      { color: var(--phase-color, #3b82f6); margin-right: 4px; font-style: normal; }

/* ── Count line ────────────────────────────────────────────────── */
.sg-count      { font-size: .8rem; color: var(--sg-muted); display: flex; align-items: center; gap: 6px; }
.sg-count-dot  { width: 7px; height: 7px; border-radius: 50%; background: var(--phase-color, #3b82f6); flex-shrink: 0; }
.sg-count span { font-weight: 600; color: var(--sg-text); }

/* ── Action button ─────────────────────────────────────────────── */
.sg-btn {
    display: block; text-align: center; width: 100%; padding: 9px 0;
    border-radius: 8px; font-size: .84rem; font-weight: 600;
    border: none; cursor: pointer; text-decoration: none;
    transition: opacity .15s, filter .15s;
    margin-top: auto;
}
.sg-btn:hover  { opacity: .88; text-decoration: none; }
.sg-btn-done   { background: #d1fae5; color: #065f46; }
.sg-btn-start  { background: var(--phase-color, #3b82f6); color: #fff; }
.sg-btn-info   { background: #e0e7ff; color: #3730a3; }

/* ── Responsive grid ───────────────────────────────────────────── */
.sg-grid       { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
@media (max-width: 900px)  { .sg-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 580px)  { .sg-grid { grid-template-columns: 1fr; } }

/* ── Superadmin school picker landing cards ────────────────────── */
.sg-school-card {
    display: flex; align-items: center; gap: 10px;
    background: var(--sg-card-bg); border: 1.5px solid var(--sg-card-border);
    border-radius: 10px; padding: 14px 16px;
    color: var(--sg-text); text-decoration: none;
    font-weight: 600; font-size: .9rem;
    transition: border-color .15s, box-shadow .15s;
}
.sg-school-card:hover { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,.12); color: #3b82f6; text-decoration: none; }
.sg-school-card i:first-child { color: #3b82f6; font-size: 1.1rem; flex-shrink: 0; }
.sg-school-card span { flex: 1; }
.sg-school-card small { color: var(--sg-muted); font-size: .75rem; }
.sg-school-picker { background: var(--sg-card-bg); border: 1px solid var(--sg-card-border); border-radius: 10px; padding: 14px 18px; }
</style>

<?php
/* Group steps by phase */
$phases = array();
foreach ($steps as $step) {
    $phases[$step['phase']][] = $step;
}

$phaseDescs = array(
    1 => 'Everything starts here. Without these, nothing else can be configured.',
    2 => 'The class and subject structure that drives timetables, marks registers, and report cards.',
    3 => 'Staff profiles and class teacher assignments needed for attendance and mark entry.',
    4 => 'Fee types, groups with amounts, and allocation to students.',
    5 => 'Admit students into classes. Bulk CSV import available for large intakes.',
    6 => 'Exam terms, assessment components, grade bands, and exam creation.',
);

$phaseColors = array(
    1 => '#3b82f6',
    2 => '#8b5cf6',
    3 => '#14b8a6',
    4 => '#22c55e',
    5 => '#f59e0b',
    6 => '#ef4444',
);

// Find selected branch name for superadmin
$selectedBranchName = '';
if (!empty($branches) && !empty($branchID)) {
    foreach ($branches as $b) {
        if ($b['id'] == $branchID) {
            $selectedBranchName = $b['school_name'] ?: $b['name'];
            break;
        }
    }
}
?>

<div class="sg-wrap">

    <?php if (!empty($branches)): ?>
    <!-- Superadmin: school picker -->
    <div class="sg-school-picker mb-4">
        <form method="get" action="<?=base_url('setup_guide')?>" class="d-flex align-items-center gap-3 flex-wrap">
            <label class="form-label mb-0 fw-semibold" style="white-space:nowrap">
                <i class="fas fa-school me-1" style="color:#3b82f6"></i>Viewing school:
            </label>
            <select name="branch_id" class="form-control" style="max-width:320px"
                    onchange="this.form.submit()"
                    data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity">
                <option value="">— Select a school —</option>
                <?php foreach ($branches as $b): ?>
                <option value="<?=$b['id']?>" <?=$b['id'] == $branchID ? 'selected' : ''?>>
                    <?=htmlspecialchars($b['school_name'] ?: $b['name'])?>
                </option>
                <?php endforeach; ?>
            </select>
            <?php if (!empty($branchID)): ?>
            <a href="<?=base_url('setup_guide')?>" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-times me-1"></i>Clear
            </a>
            <?php endif; ?>
        </form>
    </div>
    <?php endif; ?>

    <?php if (!empty($branches) && empty($branchID)): ?>
    <!-- No school selected yet (superadmin landing) -->
    <div class="text-center py-5" style="color:var(--sg-muted)">
        <i class="fas fa-compass" style="font-size:3rem;color:#3b82f6;opacity:.5"></i>
        <h4 class="mt-3" style="color:var(--sg-text)">Select a School Above</h4>
        <p>Choose any school from the dropdown to view its setup progress and guide.</p>
        <div class="sg-grid mt-4" style="max-width:600px;margin:0 auto">
        <?php foreach ($branches as $b):
            $bName = htmlspecialchars($b['school_name'] ?: $b['name']);
        ?>
        <div>
            <a href="<?=base_url('setup_guide?branch_id=' . $b['id'])?>" class="sg-school-card">
                <i class="fas fa-school"></i>
                <span><?=$bName?></span>
                <small><i class="fas fa-arrow-right"></i></small>
            </a>
        </div>
        <?php endforeach; ?>
        </div>
    </div>

    <?php else: ?>

    <!-- Hero + progress -->
    <div class="sg-hero">
        <div class="sg-hero-text">
            <h2><i class="fas fa-compass me-2" style="color:#3b82f6"></i>
            <?php if (!empty($selectedBranchName)): ?>
                <?=htmlspecialchars($selectedBranchName)?> — Setup Guide
            <?php else: ?>
                School Setup Guide
            <?php endif; ?>
            </h2>
            <p>Follow these <?=count($steps)?> steps in order to get the school fully operational. Steps link directly to the setup page. The order is a recommendation, not a lock.</p>
        </div>
        <div class="sg-hero-bar">
            <div class="sg-progress-wrap">
                <div class="sg-progress-label">
                    <span>Overall progress</span>
                    <strong><?=$complete?> / <?=$total?></strong>
                </div>
                <div class="sg-bar-track">
                    <div class="sg-bar-fill" style="width:<?=$percent?>%"></div>
                </div>
                <p class="sg-pct-note">
                <?php if ($percent == 100): ?>
                    <i class="fas fa-check-circle text-success"></i> Setup complete. Your school is ready!
                <?php elseif ($percent >= 50): ?>
                    <i class="fas fa-spinner text-warning"></i> <?=$percent?>% done. Keep going, almost there.
                <?php elseif ($percent > 0): ?>
                    <i class="fas fa-play-circle" style="color:#3b82f6"></i> <?=$percent?>% done. Great start!
                <?php else: ?>
                    <i class="fas fa-flag-checkered"></i> Start with Step 1 below.
                <?php endif; ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Phase sections -->
    <?php foreach ($phases as $phaseNum => $phaseSteps):
        $phaseColor = $phaseColors[$phaseNum];
        $firstStep  = $phaseSteps[0];
    ?>
    <div class="sg-phase">
        <span class="sg-phase-label" style="background:<?=$phaseColor?>">
            <i class="fas fa-circle" style="font-size:.45rem;opacity:.7"></i>
            Phase <?=$phaseNum?> &mdash; <?=$firstStep['phase_label']?>
        </span>
        <p class="sg-phase-desc"><?=$phaseDescs[$phaseNum]?></p>

        <div class="sg-grid">
        <?php foreach ($phaseSteps as $s):
            $isDone     = ($s['count'] !== null && $s['count'] > 0);
            $isInfo     = ($s['count'] === null);
            $noAccess   = !$s['permission'];

            // Pre-fill: data exists but low count looks seeded (terms, grades, distribution)
            $isPrefill  = $isDone && in_array($s['number'], array(16, 17, 18));
        ?>
        <div>
            <div class="sg-card <?=$isDone ? 'is-done' : ''?>"
                 style="--phase-color: <?=$phaseColor?>">

                <!-- Top row: number + icon | badge -->
                <div class="sg-card-top">
                    <div class="sg-num-icon">
                        <div class="sg-num"><?=$s['number']?></div>
                        <i class="<?=$s['icon']?> sg-icon"></i>
                    </div>
                    <?php if ($noAccess): ?>
                        <span class="sg-badge" style="background:#f1f5f9;color:#64748b">No access</span>
                    <?php elseif ($isInfo): ?>
                        <span class="sg-badge sg-badge-info">Optional</span>
                    <?php elseif ($isPrefill): ?>
                        <span class="sg-badge sg-badge-prefill">Pre-filled</span>
                    <?php elseif ($isDone): ?>
                        <span class="sg-badge sg-badge-done"><i class="fas fa-check me-1"></i>Done</span>
                    <?php else: ?>
                        <span class="sg-badge sg-badge-pending">Pending</span>
                    <?php endif; ?>
                </div>

                <!-- Title -->
                <p class="sg-title"><?=htmlspecialchars($s['title'])?></p>

                <!-- Description -->
                <p class="sg-desc"><?=htmlspecialchars($s['desc'])?></p>

                <!-- Tip box -->
                <div class="sg-tip">
                    <i class="fas fa-lightbulb"></i><?=htmlspecialchars($s['example'])?>
                </div>

                <!-- Count -->
                <?php if (!$isInfo && !$noAccess): ?>
                <div class="sg-count">
                    <span class="sg-count-dot"></span>
                    <?php if ($isDone): ?>
                        <span><?=$s['count']?></span>&nbsp;<?=htmlspecialchars($s['unit'])?> added
                    <?php else: ?>
                        Not started yet
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <!-- Action button -->
                <?php if ($noAccess): ?>
                    <span class="sg-btn" style="background:#f1f5f9;color:#94a3b8;cursor:default">
                        <i class="fas fa-lock me-1"></i>No permission
                    </span>
                <?php elseif ($isDone): ?>
                    <a href="<?=$s['url']?>" class="sg-btn sg-btn-done">
                        <i class="fas fa-pen-to-square me-1"></i>Review / Add More
                    </a>
                <?php elseif ($isInfo): ?>
                    <a href="<?=$s['url']?>" class="sg-btn sg-btn-info">
                        <i class="fas fa-file-csv me-1"></i>Open Bulk Import
                    </a>
                <?php else: ?>
                    <a href="<?=$s['url']?>" class="sg-btn sg-btn-start">
                        <i class="fas fa-arrow-right me-1"></i>Start
                    </a>
                <?php endif; ?>

            </div>
        </div>
        <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>

    <?php endif; /* end else (school selected or school admin) */ ?>

</div>
