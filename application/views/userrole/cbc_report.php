<style>
.cbc-portal { font-size: 13px; }
.cbc-exam-card { border: 1px solid #dde4ea; border-radius: 8px; margin-bottom: 20px; overflow: hidden; }
.cbc-exam-header { background: #1a5276; color: #fff; padding: 10px 18px; font-weight: 700; font-size: 14px; }
.cbc-la-header { background: #1a5276; color: #fff; font-weight: 700; padding: 7px 14px; font-size: 13px; display: flex; justify-content: space-between; align-items: center; }
.cbc-strand-header { background: #d6eaf8; color: #1a5276; font-weight: 600; padding: 5px 20px; font-size: 11.5px; border-bottom: 1px solid #aed6f1; }
.cbc-row { display: flex; padding: 6px 24px; border-bottom: 1px solid #eee; align-items: flex-start; gap: 10px; }
.cbc-row:last-child { border-bottom: none; }
.cbc-row-text { flex: 1; }
.cbc-sub-strand { font-weight: 600; color: #2c3e50; font-size: 12px; }
.cbc-lo { font-size: 11px; color: #555; margin-top: 2px; line-height: 1.4; }
.cbc-lo-code { display: inline-block; background: #eaf2f8; border-radius: 3px; padding: 0 5px; font-weight: 700; font-size: 10px; margin-right: 3px; color: #1a5276; }
.cbc-level { min-width: 52px; text-align: center; font-weight: 700; font-size: 12px; border-radius: 5px; padding: 3px 7px; color: #fff; flex-shrink: 0; }
.cbc-remarks { width: 34%; font-size: 11px; color: #555; flex-shrink: 0; }
.la-badge { background: rgba(255,255,255,.2); border-radius: 4px; padding: 2px 10px; font-size: 11px; font-weight: 700; }
.cbc-summary { background: #eaf2f8; padding: 8px 14px; font-size: 11px; display: flex; flex-wrap: wrap; gap: 6px; align-items: center; border-top: 1px solid #aed6f1; }
.no-cbc { text-align: center; padding: 30px; color: #888; }
@media(max-width:600px) { .cbc-remarks { display: none; } .cbc-row { padding: 5px 12px; } }
</style>

<section class="panel cbc-portal">
    <header class="panel-heading">
        <h4 class="panel-title"><i class="fas fa-clipboard-check"></i> CBC Progress Reports</h4>
    </header>
    <div class="panel-body">
    <?php
    if (empty($stu['student_id'])):
    ?>
        <div class="alert alert-warning text-center">Please select a child to view CBC reports.</div>
    <?php elseif (empty($exams)): ?>
        <div class="no-cbc">
            <i class="fas fa-clipboard fa-3x mb-md" style="color:#ccc;"></i>
            <p>No CBC assessments have been recorded yet.</p>
        </div>
    <?php else:
        $this->load->model('cbc_model');
        $levelColors = array(
            'EE2' => '#155724', 'EE1' => '#1e7e34',
            'ME2' => '#004085', 'ME1' => '#0062cc',
            'AE2' => '#856404', 'AE1' => '#d39e00',
            'BE2' => '#721c24', 'BE1' => '#dc3545',
        );
        $levelTextColors = array('AE2' => '#000', 'AE1' => '#000');

        foreach ($exams as $exam):
            $result = $this->cbc_model->getStudentCbcReport($stu['student_id'], $exam['exam_id'], $exam['session_id']);
            $assessments = $result['assessments'];
            if (empty($assessments)) continue;

            // Normalise old 2-letter codes and count
            $counts = array('EE2'=>0,'EE1'=>0,'ME2'=>0,'ME1'=>0,'AE2'=>0,'AE1'=>0,'BE2'=>0,'BE1'=>0);
            $grouped = array();
            foreach ($assessments as $a) {
                $lvl = $a['competency_level'];
                if ($lvl === 'EE') $lvl = 'EE2';
                elseif ($lvl === 'ME') $lvl = 'ME2';
                elseif ($lvl === 'AE') $lvl = 'AE1';
                elseif ($lvl === 'BE') $lvl = 'BE1';
                if (isset($counts[$lvl])) $counts[$lvl]++;

                $laId = $a['learning_area_id'];
                $stId = !empty($a['strand_id']) ? $a['strand_id'] : '__none__';
                if (!isset($grouped[$laId])) $grouped[$laId] = array('name' => $a['learning_area_name'], 'levels' => array(), 'strands' => array());
                if (!isset($grouped[$laId]['strands'][$stId])) $grouped[$laId]['strands'][$stId] = array('name' => !empty($a['strand_name']) ? $a['strand_name'] : '', 'rows' => array());
                $grouped[$laId]['strands'][$stId]['rows'][] = array_merge($a, array('_lvl' => $lvl));
                $grouped[$laId]['levels'][] = $lvl;
            }

            $termLabel = !empty($exam['term_id']) ? ' — ' . get_type_name_by_id('exam_term', $exam['term_id']) : '';
            $sessionLabel = !empty($exam['session_id']) ? get_type_name_by_id('schoolyear', $exam['session_id'], 'school_year') : '';
    ?>
    <div class="cbc-exam-card">
        <div class="cbc-exam-header">
            <i class="fas fa-file-alt"></i>
            <?=htmlspecialchars($exam['exam_name'])?>
            <?php if ($sessionLabel): ?><span style="font-size:11px;font-weight:400;opacity:.8;"> &mdash; <?=$sessionLabel?><?=$termLabel?></span><?php endif; ?>
        </div>

        <?php foreach ($grouped as $laId => $la):
            // Dominant competency level for this LA
            $laCounts = array_count_values($la['levels']);
            arsort($laCounts);
            $dominant = array_key_first($laCounts);
            $domColor = isset($levelColors[$dominant]) ? $levelColors[$dominant] : '#6c757d';
            $domText  = isset($levelTextColors[$dominant]) ? $levelTextColors[$dominant] : '#fff';
        ?>
        <div>
            <div class="cbc-la-header">
                <span><?=htmlspecialchars($la['name'])?></span>
                <span class="la-badge" style="background:<?=$domColor?>;color:<?=$domText?>;"><?=$dominant?></span>
            </div>

            <?php foreach ($la['strands'] as $stId => $st): ?>
            <?php if (!empty($st['name'])): ?>
            <div class="cbc-strand-header">&#9658; <?=htmlspecialchars($st['name'])?></div>
            <?php endif; ?>

            <?php foreach ($st['rows'] as $row):
                $lvl = $row['_lvl'];
                $bg  = isset($levelColors[$lvl]) ? $levelColors[$lvl] : '#6c757d';
                $fg  = isset($levelTextColors[$lvl]) ? $levelTextColors[$lvl] : '#fff';
            ?>
            <div class="cbc-row">
                <div class="cbc-row-text">
                    <?php if (!empty($row['sub_strand_name'])): ?>
                    <div class="cbc-sub-strand"><?=htmlspecialchars($row['sub_strand_name'])?></div>
                    <?php endif; ?>
                    <?php if (!empty($row['learning_outcome_name'])): ?>
                    <div class="cbc-lo">
                        <?php if (!empty($row['learning_outcome_code'])): ?>
                        <span class="cbc-lo-code"><?=htmlspecialchars($row['learning_outcome_code'])?></span>
                        <?php endif; ?>
                        <?=htmlspecialchars($row['learning_outcome_name'])?>
                    </div>
                    <?php endif; ?>
                    <?php if (empty($row['sub_strand_name']) && empty($row['learning_outcome_name'])): ?>
                    <span style="color:#bbb;font-size:11px;">General assessment</span>
                    <?php endif; ?>
                </div>
                <span class="cbc-level" style="background:<?=$bg?>;color:<?=$fg?>;"><?=$lvl?></span>
                <div class="cbc-remarks"><?=htmlspecialchars($row['remarks'])?></div>
            </div>
            <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>

        <!-- Overall summary -->
        <div class="cbc-summary">
            <strong>Overall:</strong>
            <?php foreach ($counts as $lv => $cnt): if ($cnt === 0) continue;
                $bg2 = isset($levelColors[$lv]) ? $levelColors[$lv] : '#6c757d';
                $fg2 = isset($levelTextColors[$lv]) ? $levelTextColors[$lv] : '#fff';
            ?>
            <span style="background:<?=$bg2?>;color:<?=$fg2?>;padding:2px 9px;border-radius:4px;font-weight:700;"><?=$lv?>: <?=$cnt?></span>
            <?php endforeach; ?>
            <span style="margin-left:auto;color:#888;"><?=count($assessments)?> assessments</span>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
    </div>
</section>
