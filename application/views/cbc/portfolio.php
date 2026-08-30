<section class="panel">
    <header class="panel-heading">
        <h4 class="panel-title"><i class="fas fa-folder-open"></i> CBC Portfolio — Select Student</h4>
    </header>
    <div class="panel-body">
        <!-- Student picker -->
        <?php echo form_open($this->uri->uri_string(), array('method' => 'get', 'id' => 'studentPickForm')); ?>
        <div class="row mb-md">
            <?php if (is_superadmin_loggedin()): ?>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label"><?=translate('branch')?></label>
                    <?php echo form_dropdown('branch_id', $this->app_lib->getSelectList('branch'), $branch_id, "class='form-control' id='pf_branch' onchange='getClassByBranch(this.value)' data-plugin-selectTwo data-width='100%'"); ?>
                </div>
            </div>
            <?php endif; ?>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label"><?=translate('class')?></label>
                    <?php echo form_dropdown('class_id', $this->app_lib->getClass($branch_id), '', "class='form-control' id='pf_class' onchange='getSectionByClass(this.value,0)' data-plugin-selectTwo data-width='100%'"); ?>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label"><?=translate('section')?></label>
                    <select name="section_id" id="pf_section" class="form-control" onchange="loadStudentPicker()" data-plugin-selectTwo data-width="100%">
                        <option value=""><?=translate('select')?></option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Student</label>
                    <select name="student_id" id="pf_student" class="form-control" data-plugin-selectTwo data-width="100%">
                        <option value=""><?=translate('select')?></option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group mt-lg">
                    <button type="submit" class="btn btn-default btn-block"><i class="fas fa-folder-open"></i> View Portfolio</button>
                </div>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</section>

<?php if ($student_id): ?>
<?php
    $studentRow = $this->db->select('s.first_name,s.last_name,s.photo,s.register_no,c.name as class_name,sec.name as section_name')
        ->from('student as s')
        ->join('enroll as e', 'e.student_id = s.id', 'left')
        ->join('class as c', 'c.id = e.class_id', 'left')
        ->join('section as sec', 'sec.id = e.section_id', 'left')
        ->where('s.id', $student_id)
        ->where('e.session_id', get_session_id())
        ->get()->row_array();
?>
<div class="row">
    <!-- Add entry form -->
    <?php if (get_permission('cbc_portfolio', 'is_add')): ?>
    <div class="col-md-4">
        <section class="panel">
            <header class="panel-heading">
                <h4 class="panel-title"><i class="far fa-edit"></i> Add Portfolio Entry</h4>
            </header>
            <?php echo form_open_multipart('cbc/portfolio_save'); ?>
            <input type="hidden" name="student_id" value="<?=$student_id?>">
            <div class="panel-body">
                <?php if (!empty($studentRow)): ?>
                <div class="d-flex align-items-center mb-md" style="gap:10px;">
                    <img src="<?=get_image_url('student', $studentRow['photo'])?>" width="40" height="40" class="img-circle">
                    <div>
                        <strong><?=htmlspecialchars($studentRow['first_name'] . ' ' . $studentRow['last_name'])?></strong>
                        <div style="font-size:11px;color:#888;"><?=htmlspecialchars($studentRow['class_name'])?> — <?=htmlspecialchars($studentRow['section_name'])?> | Reg: <?=htmlspecialchars($studentRow['register_no'])?></div>
                    </div>
                </div>
                <?php endif; ?>
                <div class="form-group">
                    <label class="control-label">Learning Area <span class="required">*</span></label>
                    <select name="learning_area_id" id="pf_la_id" class="form-control" data-plugin-selectTwo data-width="100%" onchange="loadPortfolioStrands(this.value)">
                        <option value=""><?=translate('select')?></option>
                        <?php foreach ($learning_areas as $la): ?>
                        <option value="<?=$la['id']?>"><?=htmlspecialchars($la['name'])?> (<?=ucfirst(str_replace('_',' ',$la['level']))?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label">Strand <small class="text-muted">(optional)</small></label>
                    <select name="strand_id" id="pf_strand_id" class="form-control" data-plugin-selectTwo data-width="100%">
                        <option value="">— none —</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label">Entry Title <span class="required">*</span></label>
                    <input type="text" class="form-control" name="title" placeholder="e.g. Number patterns worksheet" />
                </div>
                <div class="form-group">
                    <label class="control-label">Description / Observation</label>
                    <textarea class="form-control" name="description" rows="3" placeholder="Teacher's observation or student reflection"></textarea>
                </div>
                <div class="form-group">
                    <label class="control-label">Competency Level</label>
                    <select name="competency_level" class="form-control" data-plugin-selectTwo data-width="100%">
                        <option value="">— not rated —</option>
                        <optgroup label="Exceeding Expectations">
                            <option value="EE2">EE2 — Exceeding (Advanced)</option>
                            <option value="EE1">EE1 — Exceeding Expectations</option>
                        </optgroup>
                        <optgroup label="Meeting Expectations">
                            <option value="ME2">ME2 — Meeting (Proficient)</option>
                            <option value="ME1">ME1 — Meeting Expectations</option>
                        </optgroup>
                        <optgroup label="Approaching Expectations">
                            <option value="AE2">AE2 — Approaching (Developing)</option>
                            <option value="AE1">AE1 — Approaching Expectations</option>
                        </optgroup>
                        <optgroup label="Below Expectations">
                            <option value="BE2">BE2 — Below (Beginning)</option>
                            <option value="BE1">BE1 — Below Expectations</option>
                        </optgroup>
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label">Date <span class="required">*</span></label>
                    <input type="text" class="form-control" name="entry_date" value="<?=date('Y-m-d')?>" data-plugin-datepicker data-plugin-options='{"todayHighlight":true}' />
                </div>
                <div class="form-group">
                    <label class="control-label">Evidence File <small class="text-muted">(photo, PDF, doc — max 10MB)</small></label>
                    <input type="file" name="evidence_file" class="form-control" accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.mp4,.mov" />
                </div>
            </div>
            <div class="panel-footer">
                <button class="btn btn-default float-end" type="submit"><i class="fas fa-plus-circle"></i> Add Entry</button>
            </div>
            <?php echo form_close(); ?>
        </section>
    </div>
    <?php endif; ?>

    <!-- Portfolio list -->
    <div class="col-md-<?=get_permission('cbc_portfolio', 'is_add') ? '8' : '12'?>">
        <section class="panel">
            <header class="panel-heading">
                <h4 class="panel-title"><i class="fas fa-layer-group"></i> Portfolio Entries (<?=count($portfolio)?>)</h4>
            </header>
            <div class="panel-body">
            <?php if (!empty($portfolio)): ?>
                <div class="row">
                <?php foreach ($portfolio as $entry):
                    $lvl = $entry['competency_level'];
                    $lvlColors = array('EE2'=>'#155724','EE1'=>'#1e7e34','ME2'=>'#004085','ME1'=>'#0062cc','AE2'=>'#856404','AE1'=>'#d39e00','BE2'=>'#721c24','BE1'=>'#dc3545');
                    $lvlBg  = isset($lvlColors[$lvl]) ? $lvlColors[$lvl] : '#6c757d';
                    $lvlFg  = ($lvl === 'AE1') ? '#000' : '#fff';
                    $ext = $entry['evidence_file'] ? strtolower(pathinfo($entry['evidence_file'], PATHINFO_EXTENSION)) : '';
                    $isImg = in_array($ext, array('jpg','jpeg','png','gif'));
                ?>
                <div class="col-md-6 mb-md">
                    <div style="border:1px solid #dde4ea;border-radius:8px;overflow:hidden;">
                        <?php if ($isImg): ?>
                        <img src="<?=base_url('uploads/cbc_portfolio/' . $entry['evidence_file'])?>" style="width:100%;height:140px;object-fit:cover;">
                        <?php elseif ($entry['evidence_file']): ?>
                        <div style="background:#f8f9fa;height:70px;display:flex;align-items:center;justify-content:center;">
                            <a href="<?=base_url('uploads/cbc_portfolio/' . $entry['evidence_file'])?>" target="_blank" class="btn btn-sm btn-default"><i class="fas fa-file"></i> View Evidence</a>
                        </div>
                        <?php endif; ?>
                        <div style="padding:10px 12px;">
                            <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                                <strong style="font-size:13px;"><?=htmlspecialchars($entry['title'])?></strong>
                                <?php if ($lvl): ?>
                                <span style="background:<?=$lvlBg?>;color:<?=$lvlFg?>;padding:1px 8px;border-radius:4px;font-size:11px;font-weight:700;flex-shrink:0;margin-left:6px;"><?=$lvl?></span>
                                <?php endif; ?>
                            </div>
                            <div style="font-size:11px;color:#1a5276;margin-top:3px;"><?=htmlspecialchars($entry['learning_area_name'])?><?=!empty($entry['strand_name']) ? ' › ' . htmlspecialchars($entry['strand_name']) : ''?></div>
                            <?php if (!empty($entry['description'])): ?>
                            <div style="font-size:11px;color:#555;margin-top:4px;line-height:1.4;"><?=htmlspecialchars($entry['description'])?></div>
                            <?php endif; ?>
                            <div style="font-size:10px;color:#aaa;margin-top:6px;display:flex;justify-content:space-between;">
                                <span><i class="fas fa-calendar-alt"></i> <?=_d($entry['entry_date'])?></span>
                                <?php if (get_permission('cbc_portfolio', 'is_delete')): ?>
                                <?=btn_delete('cbc/portfolio_delete/' . $entry['id'])?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center" style="padding:30px;color:#888;"><i class="fas fa-folder fa-3x mb-md" style="color:#ddd;"></i><p>No portfolio entries yet.</p></div>
            <?php endif; ?>
            </div>
        </section>
    </div>
</div>
<?php endif; ?>

<script>
function loadPortfolioStrands(laId) {
    if (!laId) { $('#pf_strand_id').html('<option value="">— none —</option>'); return; }
    $.post(base_url + 'cbc/getStrandsByLearningArea', {learning_area_id: laId}, function(html) {
        $('#pf_strand_id').html('<option value="">— none —</option>' + html.replace('<option value="">Select (optional)</option>', ''));
    });
}
function loadStudentPicker() {
    var classId = $('#pf_class').val(), sectionId = $('#pf_section').val();
    if (!classId || !sectionId) return;
    $.post(base_url + 'cbc/getStudentsBySection', {class_id: classId, section_id: sectionId}, function(html) {
        $('#pf_student').html(html);
        if (typeof $.fn.select2 !== 'undefined') $('#pf_student').trigger('change');
    });
}
</script>
