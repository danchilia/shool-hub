<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"><i class="fas fa-clipboard-check"></i> CBC Assessment Entry</h4>
	</header>
	<div class="panel-body">
		<?php echo form_open($this->uri->uri_string(), array('class' => 'validate'));?>
		<div class="row mb-md">
			<?php if (is_superadmin_loggedin()): ?>
			<div class="col-md-2">
				<div class="form-group">
					<label class="control-label"><?=translate('branch')?></label>
					<?php
						$arrayBranch = $this->app_lib->getSelectList('branch');
						echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id', $branch_id), "class='form-control' id='branch_id' onchange='getClassByBranch(this.value)' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
					?>
				</div>
			</div>
			<?php endif; ?>
			<div class="col-md-2">
				<div class="form-group">
					<label class="control-label"><?=translate('class')?></label>
					<?php
						$arrayClass = $this->app_lib->getClass($branch_id);
						echo form_dropdown("class_id", $arrayClass, set_value('class_id'), "class='form-control' id='class_id' onchange='getSectionByClass(this.value,0); getLearningAreasByClass(this.value);' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
					?>
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group">
					<label class="control-label"><?=translate('section')?></label>
					<select name="section_id" id="section_id" class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity">
						<option value=""><?=translate('select')?></option>
					</select>
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group">
					<label class="control-label">CBC Exam</label>
					<select name="exam_id" id="cbc_exam_id" class="form-control" data-plugin-selectTwo data-width="100%">
						<option value=""><?=translate('select')?></option>
					</select>
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group">
					<label class="control-label">Learning Area</label>
					<select name="learning_area_id" id="learning_area_id" class="form-control" data-plugin-selectTwo data-width="100%">
						<option value=""><?=translate('select')?></option>
					</select>
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group mt-lg">
					<button type="submit" name="search" value="1" class="btn btn-default btn-block">
						<i class="fas fa-filter"></i> <?=translate('search')?>
					</button>
				</div>
			</div>
		</div>
		<?php echo form_close();?>

		<?php if (isset($students)): ?>
		<?php echo form_open('cbc/assessment_save', array('class' => 'frm-submit-msg'));?>
			<input type="hidden" name="class_id" value="<?=$class_id?>">
			<input type="hidden" name="section_id" value="<?=$section_id?>">
			<input type="hidden" name="exam_id" value="<?=$exam_id?>">
			<input type="hidden" name="learning_area_id" value="<?=$learning_area_id?>">
			<?php if (is_superadmin_loggedin()): ?>
			<input type="hidden" name="branch_id" value="<?=$branch_id?>">
			<?php endif; ?>
			<div class="table-responsive mt-md">
				<table class="table table-bordered table-hover table-condensed">
					<thead>
						<tr class="success">
							<th><?=translate('sl')?></th>
							<th>Photo</th>
							<th>Student Name</th>
							<th>Register No</th>
							<th>Roll</th>
							<th width="200">Competency Level <span class="required">*</span><?=help_tip('KNEC 8-Level CBC Rubric:&lt;br&gt;EE2 = Exceeding Expectations (Advanced)&lt;br&gt;EE1 = Exceeding Expectations&lt;br&gt;ME2 = Meeting Expectations (Proficient)&lt;br&gt;ME1 = Meeting Expectations&lt;br&gt;AE2 = Approaching Expectations (Developing)&lt;br&gt;AE1 = Approaching Expectations&lt;br&gt;BE2 = Below Expectations (Beginning)&lt;br&gt;BE1 = Below Expectations')?></th>
							<th>Remarks<?=help_tip('Short comment on student performance. Example: Good progress, Needs practice in numbers, Very creative')?></th>
						</tr>
					</thead>
					<tbody>
					<?php if (count($students)): $i = 1; foreach ($students as $stu):
						$existingLevel = isset($stu['existing']['competency_level']) ? $stu['existing']['competency_level'] : '';
						$existingRemarks = isset($stu['existing']['remarks']) ? $stu['existing']['remarks'] : '';
					?>
						<tr>
							<td><?=$i++?></td>
							<td><img src="<?=get_image_url('student', $stu['photo'])?>" width="30" height="30" class="img-circle"></td>
							<td><?=$stu['first_name'] . ' ' . $stu['last_name']?></td>
							<td><?=$stu['register_no']?></td>
							<td><?=$stu['roll']?></td>
							<td>
								<select name="assessment[<?=$stu['student_id']?>][competency_level]" class="form-control input-sm cbc-level-select" required>
									<option value="">Select Level</option>
									<optgroup label="Exceeding Expectations">
										<option value="EE2" <?=$existingLevel == 'EE2' || $existingLevel == 'EE' ? 'selected' : ''?>>EE2 - Exceeding (Advanced)</option>
										<option value="EE1" <?=$existingLevel == 'EE1' ? 'selected' : ''?>>EE1 - Exceeding Expectations</option>
									</optgroup>
									<optgroup label="Meeting Expectations">
										<option value="ME2" <?=$existingLevel == 'ME2' || $existingLevel == 'ME' ? 'selected' : ''?>>ME2 - Meeting (Proficient)</option>
										<option value="ME1" <?=$existingLevel == 'ME1' ? 'selected' : ''?>>ME1 - Meeting Expectations</option>
									</optgroup>
									<optgroup label="Approaching Expectations">
										<option value="AE2" <?=$existingLevel == 'AE2' ? 'selected' : ''?>>AE2 - Approaching (Developing)</option>
										<option value="AE1" <?=$existingLevel == 'AE1' || $existingLevel == 'AE' ? 'selected' : ''?>>AE1 - Approaching Expectations</option>
									</optgroup>
									<optgroup label="Below Expectations">
										<option value="BE2" <?=$existingLevel == 'BE2' ? 'selected' : ''?>>BE2 - Below (Beginning)</option>
										<option value="BE1" <?=$existingLevel == 'BE1' || $existingLevel == 'BE' ? 'selected' : ''?>>BE1 - Below Expectations</option>
									</optgroup>
								</select>
							</td>
							<td>
								<input type="text" name="assessment[<?=$stu['student_id']?>][remarks]" class="form-control input-sm" value="<?=$existingRemarks?>" placeholder="Optional remarks">
							</td>
						</tr>
					<?php endforeach; else: ?>
						<tr><td colspan="7"><h5 class="text-danger text-center"><?=translate('no_information_available')?></h5></td></tr>
					<?php endif; ?>
					</tbody>
				</table>
			</div>
			<?php if (count($students)): ?>
			<div class="pull-right mt-md mb-md">
				<button type="submit" class="btn btn-default" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
					<i class="fas fa-save"></i> Save Assessment
				</button>
			</div>
			<?php endif; ?>
		<?php echo form_close();?>
		<?php endif; ?>
	</div>
</section>

<script type="text/javascript">
	$(document).ready(function() {
		<?php if (is_superadmin_loggedin() && !empty($branch_id)): ?>
		getClassByBranch('<?=$branch_id?>');
		<?php endif; ?>
		getCbcExams();
	});

	function getLearningAreasByClass(classId) {
		$.ajax({
			url: base_url + 'cbc/getLearningAreasByBranch',
			type: 'POST',
			data: {class_id: classId},
			success: function(data) {
				$('#learning_area_id').html(data);
			}
		});
	}

	function getCbcExams() {
		$.ajax({
			url: base_url + 'cbc/getCbcExamsByBranch',
			type: 'POST',
			data: {},
			success: function(data) {
				$('#cbc_exam_id').html(data);
			}
		});
	}
</script>
