<style>
.portfolio-card { border:1px solid #dde4ea; border-radius:8px; overflow:hidden; height:100%; }
.portfolio-card img.cover { width:100%; height:130px; object-fit:cover; }
.portfolio-card .card-body { padding:10px 12px; }
.portfolio-card .la-label { font-size:11px; color:#1a5276; font-weight:600; margin-top:3px; }
.portfolio-card .card-title { font-size:13px; font-weight:700; color:#2c3e50; margin-top:4px; }
.portfolio-card .card-desc { font-size:11px; color:#666; margin-top:4px; line-height:1.4; }
.portfolio-card .card-foot { font-size:10px; color:#aaa; margin-top:8px; display:flex; justify-content:space-between; align-items:center; }
.level-badge { padding:1px 8px; border-radius:4px; font-size:11px; font-weight:700; color:#fff; }
.project-row td { vertical-align:middle; }
</style>

<?php
$this->load->model('cbc_model');
$studentId = $stu['student_id'];
$branchId  = $stu['branch_id'];
$portfolio = $this->cbc_model->getPortfolio($studentId, $branchId);
$projects  = $this->cbc_model->getStudentProjects($studentId, $branchId, get_session_id());

$lvlColors = array('EE2'=>'#155724','EE1'=>'#1e7e34','ME2'=>'#004085','ME1'=>'#0062cc','AE2'=>'#856404','AE1'=>'#d39e00','BE2'=>'#721c24','BE1'=>'#dc3545');
?>

<!-- Portfolio Entries -->
<section class="panel">
    <header class="panel-heading">
        <h4 class="panel-title"><i class="fas fa-folder-open"></i> My Portfolio (<?=count($portfolio)?> entries)</h4>
    </header>
    <div class="panel-body">
    <?php if (!empty($portfolio)): ?>
        <div class="row">
        <?php foreach ($portfolio as $entry):
            $lvl = $entry['competency_level'];
            $bg  = isset($lvlColors[$lvl]) ? $lvlColors[$lvl] : '';
            $ext = $entry['evidence_file'] ? strtolower(pathinfo($entry['evidence_file'], PATHINFO_EXTENSION)) : '';
            $isImg = in_array($ext, array('jpg','jpeg','png','gif'));
        ?>
        <div class="col-md-4 col-sm-6 mb-md">
            <div class="portfolio-card">
                <?php if ($isImg): ?>
                <img class="cover" src="<?=base_url('uploads/cbc_portfolio/' . $entry['evidence_file'])?>">
                <?php elseif ($entry['evidence_file']): ?>
                <div style="background:#f8f9fa;height:70px;display:flex;align-items:center;justify-content:center;">
                    <a href="<?=base_url('uploads/cbc_portfolio/' . $entry['evidence_file'])?>" target="_blank" class="btn btn-sm btn-default"><i class="fas fa-file"></i> View Evidence</a>
                </div>
                <?php endif; ?>
                <div class="card-body">
                    <div class="la-label"><?=htmlspecialchars($entry['learning_area_name'])?><?=!empty($entry['strand_name']) ? ' › ' . htmlspecialchars($entry['strand_name']) : ''?></div>
                    <div class="card-title"><?=htmlspecialchars($entry['title'])?></div>
                    <?php if (!empty($entry['description'])): ?>
                    <div class="card-desc"><?=htmlspecialchars(mb_substr($entry['description'], 0, 100)) . (mb_strlen($entry['description']) > 100 ? '…' : '')?></div>
                    <?php endif; ?>
                    <div class="card-foot">
                        <span><i class="fas fa-calendar-alt"></i> <?=_d($entry['entry_date'])?></span>
                        <?php if ($lvl): ?>
                        <span class="level-badge" style="background:<?=$bg?>;<?=($lvl==='AE1'?'color:#000':'')?>;"><?=$lvl?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center" style="padding:30px;color:#888;">
            <i class="fas fa-folder fa-3x mb-md" style="color:#ddd;"></i>
            <p>No portfolio entries recorded yet. Your teacher will add evidence of your learning here.</p>
        </div>
    <?php endif; ?>
    </div>
</section>

<!-- Project Scores -->
<?php if (!empty($projects)): ?>
<section class="panel">
    <header class="panel-heading">
        <h4 class="panel-title"><i class="fas fa-project-diagram"></i> Project Assessments</h4>
    </header>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th><?=translate('sl')?></th>
                        <th>Project</th>
                        <th>Learning Area</th>
                        <th>Due Date</th>
                        <th class="text-center">Score</th>
                        <th class="text-center">Level</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                <?php $n = 1; foreach ($projects as $pr):
                    $lvl = $pr['competency_level'];
                    $bg  = isset($lvlColors[$lvl]) ? $lvlColors[$lvl] : '#6c757d';
                    $pct = ($pr['score'] !== null && $pr['max_score'] > 0) ? round($pr['score'] * 100 / $pr['max_score']) : null;
                ?>
                <tr class="project-row">
                    <td><?=$n++?></td>
                    <td><strong><?=htmlspecialchars($pr['name'])?></strong></td>
                    <td style="font-size:12px;"><?=htmlspecialchars($pr['learning_area_name'] ?: '—')?></td>
                    <td style="font-size:12px;"><?=!empty($pr['due_date']) ? _d($pr['due_date']) : '—'?></td>
                    <td class="text-center">
                        <?php if ($pr['score'] !== null): ?>
                        <strong><?=$pr['score']?>/<?=$pr['max_score']?></strong>
                        <div style="font-size:10px;color:#888;"><?=$pct?>%</div>
                        <?php else: ?>
                        <span style="color:#ccc;">Pending</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <?php if ($lvl): ?>
                        <span class="level-badge" style="background:<?=$bg?>;<?=($lvl==='AE1'?'color:#000':'')?>;"><?=$lvl?></span>
                        <?php else: ?>
                        <span style="color:#ccc;">—</span>
                        <?php endif; ?>
                    </td>
                    <td style="font-size:11px;"><?=htmlspecialchars($pr['remarks'] ?: '—')?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php endif; ?>
