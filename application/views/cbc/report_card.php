<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"><i class="fas fa-file-alt"></i> CBC Report Card</h4>
	</header>
	<div class="panel-body">
		<?php echo form_open($this->uri->uri_string());?>
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
					<label class="control-label"><?=translate('academic_year')?></label>
					<?php
						$arrayYear = $this->app_lib->getSelectByBranch('schoolyear', $branch_id);
						echo form_dropdown("session_id", $arrayYear, set_value('session_id', get_session_id()), "class='form-control' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
					?>
				</div>
			</div>
			<div class="col-md-2">
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
					<button type="submit" class="btn btn-default btn-block">
						<i class="fas fa-filter"></i> <?=translate('search')?>
					</button>
				</div>
			</div>
		</div>
		<?php echo form_close();?>

		<?php if (isset($students) && count($students)): ?>
		<div class="table-responsive mt-md">
			<table class="table table-bordered table-hover table-condensed">
				<thead>
					<tr>
						<th width="30"><input type="checkbox" id="selectAll"></th>
						<th><?=translate('sl')?></th>
						<th>Photo</th>
						<th>Student Name</th>
						<th>Register No</th>
						<th>Roll</th>
					</tr>
				</thead>
				<tbody>
				<?php $i = 1; foreach ($students as $stu): ?>
					<tr>
						<td><input type="checkbox" class="student-check" value="<?=$stu['id']?>"></td>
						<td><?=$i++?></td>
						<td><img src="<?=get_image_url('student', $stu['photo'])?>" width="30" height="30" class="img-circle"></td>
						<td><?=$stu['first_name'] . ' ' . $stu['last_name']?></td>
						<td><?=$stu['register_no']?></td>
						<td><?=$stu['roll']?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>

		<div class="row mt-md">
			<div class="col-md-3">
				<div class="form-group">
					<label>Print Date</label>
					<input type="text" name="print_date" class="form-control" id="print_date" value="<?=date('Y-m-d')?>" />
				</div>
			</div>
			<div class="col-md-3 mt-lg">
				<button type="button" class="btn btn-default" id="printReport">
					<i class="fas fa-print"></i> Print Report Card
				</button>
			</div>
		</div>
		<?php endif; ?>
	</div>
</section>

<script type="text/javascript">
	$(document).ready(function() {
		getCbcExams();

		$('#selectAll').click(function() {
			$('.student-check').prop('checked', this.checked);
		});

		$('#printReport').click(function() {
			var students = [];
			$('.student-check:checked').each(function() {
				students.push($(this).val());
			});
			if (students.length === 0) {
				alert('Please select at least one student');
				return;
			}
			var form = $('<form>', {action: base_url + 'cbc/report_card_print', method: 'POST', target: '_blank'});
			$.each(students, function(i, v) {
				form.append($('<input>', {type: 'hidden', name: 'student_id[]', value: v}));
			});
			form.append($('<input>', {type: 'hidden', name: 'exam_id', value: $('[name=exam_id]').val()}));
			form.append($('<input>', {type: 'hidden', name: 'session_id', value: $('[name=session_id]').val()}));
			form.append($('<input>', {type: 'hidden', name: 'print_date', value: $('#print_date').val()}));
			$('body').append(form);
			form.submit();
			form.remove();
		});
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
