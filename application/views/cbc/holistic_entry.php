<?php
$lvlColors = array(
    'EE' => '#1e7e34', 'ME' => '#0062cc', 'AE' => '#d39e00', 'BE' => '#dc3545',
);
$levelOptions = array('' => '— Not Rated —', 'EE' => 'EE — Exceeding Expectations', 'ME' => 'ME — Meeting Expectations', 'AE' => 'AE — Approaching Expectations', 'BE' => 'BE — Below Expectations');
?>

<style>
.holistic-domain { border: 1px solid #dde4ea; border-radius: 6px; margin-bottom: 16px; overflow: hidden; }
.holistic-domain .domain-header { background: #1a5276; color: #fff; padding: 8px 14px; font-weight: 600; font-size: 13px; }
.holistic-domain .indicator-row { display: flex; align-items: center; border-top: 1px solid #e8edf2; padding: 8px 14px; gap: 10px; }
.holistic-domain .indicator-row:hover { background: #f8f9fa; }
.indicator-name { flex: 1; font-size: 13px; color: #2c3e50; }
.indicator-rating { width: 200px; flex-shrink: 0; }
.indicator-remarks { flex: 1; max-width: 260px; flex-shrink: 0; }
</style>

<section class="panel">
    <header class="panel-heading">
        <h4 class="panel-title">
            <i class="fas fa-user-check"></i> Holistic Profile &mdash;
            <?php if (!empty($student)): ?>
            <?=htmlspecialchars($student['first_name'] . ' ' . $student['last_name'])?>
            <small class="text-muted"><?=!empty($student['class_name']) ? $student['class_name'] . ' ' . $student['section_name'] : ''?> | Reg: <?=$student['register_no']?></small>
            <?php endif; ?>
        </h4>
    </header>
    <div class="panel-body">
        <a href="<?=base_url('cbc/holistic')?>" class="btn btn-default btn-sm mb-md"><i class="fas fa-arrow-left"></i> Back to Students</a>

        <?php if (empty($domains)): ?>
        <div class="alert alert-warning">No holistic domains configured. Please run the migration <code>008_holistic_profile.sql</code> on the server.</div>
        <?php else: ?>

        <?php echo form_open('cbc/holistic_save', array('class' => 'frm-submit-msg')); ?>
        <input type="hidden" name="student_id" value="<?=$student['id']?>">
        <input type="hidden" name="exam_id" value="<?=$exam_id?>">

        <!-- Rating key -->
        <div class="row mb-md" style="font-size:11px;">
            <div class="col-md-12">
                <span style="padding:2px 8px; border-radius:4px; background:#1e7e34; color:#fff; margin-right:6px;">EE — Exceeding Expectations</span>
                <span style="padding:2px 8px; border-radius:4px; background:#0062cc; color:#fff; margin-right:6px;">ME — Meeting Expectations</span>
                <span style="padding:2px 8px; border-radius:4px; background:#d39e00; color:#fff; margin-right:6px;">AE — Approaching Expectations</span>
                <span style="padding:2px 8px; border-radius:4px; background:#dc3545; color:#fff;">BE — Below Expectations</span>
            </div>
        </div>

        <?php foreach ($domains as $domain): ?>
        <div class="holistic-domain">
            <div class="domain-header">
                <i class="fas fa-star"></i> <?=htmlspecialchars($domain['name'])?>
                <?php if (!empty($domain['description'])): ?>
                <small style="font-weight:400; opacity:0.8;"> — <?=htmlspecialchars($domain['description'])?></small>
                <?php endif; ?>
            </div>
            <?php foreach ($domain['indicators'] as $ind):
                $saved = isset($ratings[$ind['id']]) ? $ratings[$ind['id']] : array();
                $savedRating  = !empty($saved['rating'])  ? $saved['rating']  : '';
                $savedRemarks = !empty($saved['remarks']) ? $saved['remarks'] : '';
            ?>
            <div class="indicator-row">
                <div class="indicator-name"><?=htmlspecialchars($ind['name'])?></div>
                <div class="indicator-rating">
                    <select name="ratings[<?=$ind['id']?>][rating]" class="form-control input-sm">
                        <?php foreach ($levelOptions as $val => $label): ?>
                        <option value="<?=$val?>" <?=$savedRating===$val?'selected':''?>><?=$label?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="indicator-remarks">
                    <input type="text" name="ratings[<?=$ind['id']?>][remarks]" class="form-control input-sm" value="<?=htmlspecialchars($savedRemarks)?>" placeholder="Remarks (optional)">
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>

        <div class="mb-md mt-md">
            <button type="submit" class="btn btn-default" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Saving...">
                <i class="fas fa-save"></i> Save Holistic Profile
            </button>
            <a href="<?=base_url('cbc/holistic')?>" class="btn btn-default ml-sm">Cancel</a>
        </div>
        <?php echo form_close(); ?>
        <?php endif; ?>
    </div>
</section>
