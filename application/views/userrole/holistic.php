<?php
$this->load->model('cbc_model');
$studentId = $stu['student_id'];
$branchId  = $stu['branch_id'];
$grouped   = $this->cbc_model->getStudentHolisticByExam($studentId, $branchId);

$lvlColors = array(
    'EE' => '#1e7e34', 'ME' => '#0062cc', 'AE' => '#d39e00', 'BE' => '#dc3545',
);
$lvlLabels = array(
    'EE' => 'Exceeding Expectations',
    'ME' => 'Meeting Expectations',
    'AE' => 'Approaching Expectations',
    'BE' => 'Below Expectations',
);
?>

<style>
.holistic-domain-card { border: 1px solid #dde4ea; border-radius: 6px; margin-bottom: 14px; overflow: hidden; }
.holistic-domain-card .dh { background: #1a5276; color: #fff; padding: 7px 14px; font-weight: 600; font-size: 13px; }
.holistic-indicator-row { display: flex; align-items: center; gap: 10px; padding: 7px 14px; border-top: 1px solid #eee; }
.holistic-indicator-row:hover { background: #f8f9fa; }
.hi-name { flex: 1; font-size: 13px; color: #2c3e50; }
.hi-badge { padding: 2px 10px; border-radius: 4px; font-size: 11px; font-weight: 700; color: #fff; white-space: nowrap; }
.hi-remarks { font-size: 11px; color: #777; flex: 1; text-align: right; font-style: italic; }
</style>

<?php if (empty($grouped)): ?>
<section class="panel">
    <header class="panel-heading">
        <h4 class="panel-title"><i class="fas fa-user-check"></i> Holistic Development Profile</h4>
    </header>
    <div class="panel-body text-center" style="padding:40px;color:#888;">
        <i class="fas fa-user-check fa-3x mb-md" style="color:#ddd;"></i>
        <p>No holistic development profile recorded yet. Your teacher will enter assessments here.</p>
    </div>
</section>
<?php else: ?>
<?php foreach ($grouped as $examId => $examData): ?>
<section class="panel">
    <header class="panel-heading">
        <h4 class="panel-title">
            <i class="fas fa-user-check"></i> Holistic Development Profile
            <small class="text-muted ml-sm"><?=htmlspecialchars($examData['exam_name'])?></small>
        </h4>
    </header>
    <div class="panel-body">
        <?php foreach ($examData['domains'] as $domainData): ?>
        <div class="holistic-domain-card">
            <div class="dh"><i class="fas fa-star"></i> <?=htmlspecialchars($domainData['name'])?></div>
            <?php foreach ($domainData['indicators'] as $ind):
                $bg = isset($lvlColors[$ind['rating']]) ? $lvlColors[$ind['rating']] : '#6c757d';
                $lbl = isset($lvlLabels[$ind['rating']]) ? $ind['rating'] . ' — ' . $lvlLabels[$ind['rating']] : $ind['rating'];
            ?>
            <div class="holistic-indicator-row">
                <div class="hi-name"><?=htmlspecialchars($ind['name'])?></div>
                <span class="hi-badge" style="background:<?=$bg?>;"><?=$lbl?></span>
                <?php if (!empty($ind['remarks'])): ?>
                <div class="hi-remarks"><?=htmlspecialchars($ind['remarks'])?></div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endforeach; ?>
<?php endif; ?>
