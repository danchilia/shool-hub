<section class="panel">
    <header class="panel-heading">
        <h4 class="panel-title"><i class="fas fa-user-check"></i> Holistic Development Profile</h4>
    </header>
    <div class="panel-body">
        <?php echo form_open($this->uri->uri_string(), array('method' => 'post', 'class' => 'validate')); ?>
        <div class="row mb-md">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label"><?=translate('class')?></label>
                    <?php
                        $arrayClass = $this->app_lib->getClass($branch_id);
                        echo form_dropdown("class_id", $arrayClass, $class_id, "class='form-control' id='class_id' onchange='getSectionByClass(this.value,0)' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                    ?>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label"><?=translate('section')?></label>
                    <select name="section_id" id="section_id" class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity">
                        <option value=""><?=translate('select')?></option>
                        <?php foreach (get_section_list($class_id) as $s): ?>
                        <option value="<?=$s['id']?>" <?=$section_id==$s['id']?'selected':''?>><?=$s['section']?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">CBC Exam</label>
                    <select name="exam_id" class="form-control" data-plugin-selectTwo data-width="100%">
                        <option value=""><?=translate('select')?></option>
                        <?php foreach ($exams as $ex): ?>
                        <option value="<?=$ex['id']?>" <?=$exam_id==$ex['id']?'selected':''?>><?=htmlspecialchars($ex['name'])?><?=!empty($ex['term_name']) ? ' (' . $ex['term_name'] . ')' : ''?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group mt-lg">
                    <button type="submit" class="btn btn-default btn-block"><i class="fas fa-search"></i> Load Students</button>
                </div>
            </div>
        </div>
        <?php echo form_close(); ?>

        <?php if (!empty($students)): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm">
                <thead>
                    <tr class="success">
                        <th><?=translate('sl')?></th>
                        <th>Photo</th>
                        <th><?=translate('student_name')?></th>
                        <th>Reg No</th>
                        <th>Roll</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php $i = 1; foreach ($students as $stu): ?>
                <tr>
                    <td><?=$i++?></td>
                    <td><img src="<?=get_image_url('student', $stu['photo'])?>" width="30" height="30" class="img-circle"></td>
                    <td><?=htmlspecialchars($stu['first_name'] . ' ' . $stu['last_name'])?></td>
                    <td><?=$stu['register_no']?></td>
                    <td><?=$stu['roll']?></td>
                    <td class="text-center">
                        <a href="<?=base_url('cbc/holistic_entry/' . $stu['student_id'] . '?exam_id=' . $exam_id)?>" class="btn btn-xs btn-primary">
                            <i class="fas fa-edit"></i> Enter Profile
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php elseif ($class_id && $section_id && $exam_id): ?>
        <div class="alert alert-info">No students found for the selected class/section.</div>
        <?php endif; ?>
    </div>
</section>
