<div class="row">
<?php if (get_permission('cbc_projects', 'is_add')): ?>
<div class="col-md-4">
    <section class="panel">
        <header class="panel-heading">
            <h4 class="panel-title"><i class="fas fa-project-diagram"></i> Add Project</h4>
        </header>
        <?php echo form_open($this->uri->uri_string()); ?>
        <div class="panel-body">
            <div class="form-group">
                <label class="control-label"><?=translate('class')?> <span class="required">*</span></label>
                <?php echo form_dropdown('class_id', $this->app_lib->getClass($branch_id), set_value('class_id', $class_id), "class='form-control' id='proj_class_id' onchange='getSectionByClass(this.value,0)' data-plugin-selectTwo data-width='100%'"); ?>
                <span class="error"><?=form_error('class_id')?></span>
            </div>
            <div class="form-group">
                <label class="control-label"><?=translate('section')?> <span class="required">*</span></label>
                <select name="section_id" id="proj_section_id" class="form-control" data-plugin-selectTwo data-width="100%">
                    <option value=""><?=translate('select')?></option>
                </select>
                <span class="error"><?=form_error('section_id')?></span>
            </div>
            <div class="form-group">
                <label class="control-label">Project Name <span class="required">*</span></label>
                <input type="text" class="form-control" name="name" value="<?=set_value('name')?>" placeholder="e.g. Science Fair Model" />
                <span class="error"><?=form_error('name')?></span>
            </div>
            <div class="form-group">
                <label class="control-label">Learning Area <small class="text-muted">(optional)</small></label>
                <select name="learning_area_id" class="form-control" data-plugin-selectTwo data-width="100%">
                    <option value="">— General —</option>
                    <?php foreach ($learning_areas as $la): ?>
                    <option value="<?=$la['id']?>" <?=set_select('learning_area_id', $la['id'])?>><?=htmlspecialchars($la['name'])?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="control-label">Description</label>
                <textarea class="form-control" name="description" rows="2"><?=set_value('description')?></textarea>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Due Date</label>
                        <input type="text" class="form-control" name="due_date" value="<?=set_value('due_date')?>" data-plugin-datepicker />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Max Score <span class="required">*</span></label>
                        <input type="number" class="form-control" name="max_score" value="<?=set_value('max_score', 100)?>" min="1" max="1000" />
                        <span class="error"><?=form_error('max_score')?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <button class="btn btn-default float-end" type="submit" name="save" value="1">
                <i class="fas fa-plus-circle"></i> <?=translate('save')?>
            </button>
        </div>
        <?php echo form_close(); ?>
    </section>
</div>
<?php endif; ?>

<div class="col-md-<?=get_permission('cbc_projects', 'is_add') ? '8' : '12'?>">
    <section class="panel">
        <header class="panel-heading">
            <h4 class="panel-title"><i class="fas fa-list-ul"></i> Projects List</h4>
        </header>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm table-export mb-0">
                    <thead>
                        <tr>
                            <th><?=translate('sl')?></th>
                            <th>Project</th>
                            <th>Class / Section</th>
                            <th>Learning Area</th>
                            <th>Due Date</th>
                            <th>Max Score</th>
                            <th><?=translate('action')?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $n = 1; if (!empty($projects)): foreach ($projects as $row):
                        $scored = $this->db->where('project_id', $row['id'])->count_all_results('cbc_project_scores');
                    ?>
                    <tr>
                        <td><?=$n++?></td>
                        <td>
                            <strong><?=htmlspecialchars($row['name'])?></strong>
                            <?php if (!empty($row['description'])): ?>
                            <div style="font-size:11px;color:#888;"><?=htmlspecialchars(mb_substr($row['description'], 0, 60)) . (mb_strlen($row['description']) > 60 ? '…' : '')?></div>
                            <?php endif; ?>
                        </td>
                        <td style="font-size:12px;"><?=htmlspecialchars($row['class_name'])?> — <?=htmlspecialchars($row['section_name'])?></td>
                        <td style="font-size:12px;"><?=htmlspecialchars($row['learning_area_name'] ?: '—')?></td>
                        <td style="font-size:12px;"><?=!empty($row['due_date']) ? _d($row['due_date']) : '—'?></td>
                        <td style="text-align:center;"><?=$row['max_score']?></td>
                        <td>
                            <a href="<?=base_url('cbc/project_scores/' . $row['id'])?>" class="btn btn-default btn-circle icon" title="Enter Scores">
                                <i class="fas fa-marker"></i>
                            </a>
                            <span class="badge badge-info"><?=$scored?> scored</span>
                            <?php if (get_permission('cbc_projects', 'is_delete')): ?>
                            <?=btn_delete('cbc/project_delete/' . $row['id'])?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="7"><h5 class="text-danger text-center"><?=translate('no_information_available')?></h5></td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
</div>
