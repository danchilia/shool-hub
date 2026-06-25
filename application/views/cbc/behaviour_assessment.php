<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"><i class="fas fa-heart"></i> CBC Behaviour & Values Assessment</h4>
	</header>
	<div class="panel-body">
		<?php echo form_open($this->uri->uri_string(), array('class' => 'validate'));?>
		<div class="row mb-md">
			<?php if (is_superadmin_loggedin()): ?>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label"><?=translate('branch')?></label>
					<?php
						$arrayBranch = $this->app_lib->getSelectList('branch');
						echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id', $branch_id), "class='form-control' id='branch_id' onchange='getClassByBranch(this.value)' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
					?>
				</div>
			</div>
			<?php endif; ?>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label"><?=translate('class')?></label>
					<?php
						$arrayClass = $this->app_lib->getClass($branch_id);
						echo form_dropdown("class_id", $arrayClass, set_value('class_id'), "class='form-control' id='class_id' onchange='getSectionByClass(this.value,0)' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
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
				<div class="form-group mt-lg">
					<button type="submit" name="search" value="1" class="btn btn-default btn-block">
						<i class="fas fa-filter"></i> <?=translate('search')?>
					</button>
				</div>
			</div>
		</div>
		<?php echo form_close();?>

		<?php if (isset($students)): ?>
		<?php echo form_open('cbc/behaviour_save', array('class' => 'frm-submit-msg'));?>
			<input type="hidden" name="exam_id" value="<?=$exam_id?>">
			<?php if (is_superadmin_loggedin()): ?>
			<input type="hidden" name="branch_id" value="<?=$branch_id?>">
			<?php endif; ?>
			<div class="table-responsive mt-md">
				<table class="table table-bordered table-hover table-condensed">
					<thead>
						<tr class="success">
							<th><?=translate('sl')?></th>
							<th>Student Name</th>
							<?php foreach ($categories as $cat): ?>
							<th><?=$cat?></th>
							<?php endforeach; ?>
						</tr>
					</thead>
					<tbody>
					<?php if (count($students)): $i = 1; foreach ($students as $stu): ?>
						<tr>
							<td><?=$i++?></td>
							<td><?=$stu['first_name'] . ' ' . $stu['last_name']?></td>
							<?php foreach ($categories as $cat):
								$existing = isset($stu['behaviours'][$cat]['rating']) ? $stu['behaviours'][$cat]['rating'] : '';
							?>
							<td>
								<select name="behaviour[<?=$stu['student_id']?>][<?=$cat?>][rating]" class="form-control input-sm">
									<option value="">-</option>
									<option value="EE" <?=$existing == 'EE' ? 'selected' : ''?>>EE</option>
									<option value="ME" <?=$existing == 'ME' ? 'selected' : ''?>>ME</option>
									<option value="AE" <?=$existing == 'AE' ? 'selected' : ''?>>AE</option>
									<option value="BE" <?=$existing == 'BE' ? 'selected' : ''?>>BE</option>
								</select>
							</td>
							<?php endforeach; ?>
						</tr>
					<?php endforeach; else: ?>
						<tr><td colspan="<?=2 + count($categories)?>"><h5 class="text-danger text-center"><?=translate('no_information_available')?></h5></td></tr>
					<?php endif; ?>
					</tbody>
				</table>
			</div>
			<?php if (count($students)): ?>
			<div class="pull-right mt-md mb-md">
				<button type="submit" class="btn btn-default" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
					<i class="fas fa-save"></i> Save Behaviour Assessment
				</button>
			</div>
			<?php endif; ?>
		<?php echo form_close();?>
		<?php endif; ?>
	</div>
</section>

<script type="text/javascript">
	$(document).ready(function() {
		getCbcExams();
	});
	function getCbcExams() {
		$.ajax({
			url: base_url + 'cbc/getCbcExamsByBranch',
			type: 'POST',
			data: {},
			success: function(data) { $('#cbc_exam_id').html(data); }
		});
	}
</script>
