<section class="panel">
    <header class="panel-heading">
        <h4 class="panel-title">
            <i class="fas fa-marker"></i> Project Scores — <?=htmlspecialchars($project['name'])?>
            <small class="text-muted"> | Max: <?=$project['max_score']?><?=!empty($project['due_date']) ? ' | Due: ' . _d($project['due_date']) : ''?></small>
        </h4>
    </header>
    <div class="panel-body">
        <a href="<?=base_url('cbc/projects')?>" class="btn btn-default btn-sm mb-md"><i class="fas fa-arrow-left"></i> Back to Projects</a>

        <?php echo form_open('cbc/project_scores/' . $project['id'], array('class' => 'frm-submit-msg')); ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm">
                <thead>
                    <tr class="success">
                        <th><?=translate('sl')?></th>
                        <th>Photo</th>
                        <th>Student</th>
                        <th>Reg No</th>
                        <th>Roll</th>
                        <th>Score <small>(out of <?=$project['max_score']?>)</small></th>
                        <th>Competency Level</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                <?php $i = 1; foreach ($students as $stu):
                    $ex = isset($existing[$stu['student_id']]) ? $existing[$stu['student_id']] : array();
                    $exScore = isset($ex['score']) ? $ex['score'] : '';
                    $exLevel = isset($ex['competency_level']) ? $ex['competency_level'] : '';
                    $exRemarks = isset($ex['remarks']) ? $ex['remarks'] : '';
                ?>
                <tr>
                    <td><?=$i++?></td>
                    <td><img src="<?=get_image_url('student', $stu['photo'])?>" width="30" height="30" class="img-circle"></td>
                    <td><?=htmlspecialchars($stu['first_name'] . ' ' . $stu['last_name'])?></td>
                    <td><?=$stu['register_no']?></td>
                    <td><?=$stu['roll']?></td>
                    <td style="width:110px;">
                        <input type="number" name="scores[<?=$stu['student_id']?>][score]" class="form-control input-sm" value="<?=$exScore?>" min="0" max="<?=$project['max_score']?>" step="0.5" placeholder="0–<?=$project['max_score']?>">
                    </td>
                    <td style="width:180px;">
                        <select name="scores[<?=$stu['student_id']?>][competency_level]" class="form-control input-sm">
                            <option value="">— not rated —</option>
                            <optgroup label="Exceeding">
                                <option value="EE2" <?=$exLevel=='EE2'?'selected':''?>>EE2</option>
                                <option value="EE1" <?=$exLevel=='EE1'?'selected':''?>>EE1</option>
                            </optgroup>
                            <optgroup label="Meeting">
                                <option value="ME2" <?=$exLevel=='ME2'?'selected':''?>>ME2</option>
                                <option value="ME1" <?=$exLevel=='ME1'?'selected':''?>>ME1</option>
                            </optgroup>
                            <optgroup label="Approaching">
                                <option value="AE2" <?=$exLevel=='AE2'?'selected':''?>>AE2</option>
                                <option value="AE1" <?=$exLevel=='AE1'?'selected':''?>>AE1</option>
                            </optgroup>
                            <optgroup label="Below">
                                <option value="BE2" <?=$exLevel=='BE2'?'selected':''?>>BE2</option>
                                <option value="BE1" <?=$exLevel=='BE1'?'selected':''?>>BE1</option>
                            </optgroup>
                        </select>
                    </td>
                    <td>
                        <input type="text" name="scores[<?=$stu['student_id']?>][remarks]" class="form-control input-sm" value="<?=htmlspecialchars($exRemarks)?>" placeholder="Optional remarks">
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="float-end mt-md mb-md">
            <button type="submit" class="btn btn-default" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Saving...">
                <i class="fas fa-save"></i> Save Scores
            </button>
        </div>
        <?php echo form_close(); ?>
    </div>
</section>
