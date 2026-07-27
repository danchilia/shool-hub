<?php $widget = (is_superadmin_loggedin() ? 2 : 3); ?>
<style>
@media (prefers-color-scheme: dark) {
    .card        { background: #2b2b3a; border-color: #3a3a50; }
    .card-header { background: #232333; border-color: #3a3a50; }
}
:root[data-theme="dark"]  .card        { background: #2b2b3a; border-color: #3a3a50; }
:root[data-theme="dark"]  .card-header { background: #232333; border-color: #3a3a50; }
:root[data-theme="light"] .card        { background: #fff;    border-color: #dee2e6; }
:root[data-theme="light"] .card-header { background: #f8f9fa; border-color: #dee2e6; }
</style>

<div class="row">
	<div class="col-md-12">
		<div class="card mb-4">
			<?php echo form_open('exam/marksheet', array('class' => 'validate')); ?>
			<div class="card-header">
				<h5 class="mb-0"><?=translate('select_ground')?></h5>
			</div>
			<div class="card-body">
				<div class="row g-3">
				<?php if (is_superadmin_loggedin()): ?>
					<div class="col-md-3">
						<label class="form-label"><?=translate('branch')?> <span class="text-danger">*</span></label>
						<?php
							$arrayBranch = $this->app_lib->getSelectList('branch');
							echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' id='branch_id' required
							data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
						?>
					</div>
				<?php endif; ?>
					<div class="col-md-<?=$widget?>">
						<label class="form-label"><?=translate('academic_year')?> <span class="text-danger">*</span></label>
						<?php
							$arrayYear = array("" => translate('select'));
							$years = $this->db->get('schoolyear')->result();
							foreach ($years as $year){
								$arrayYear[$year->id] = $year->school_year;
							}
							echo form_dropdown("session_id", $arrayYear, set_value('session_id', get_session_id()), "class='form-control' required
							data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
						?>
					</div>
					<div class="col-md-<?=$widget?>">
						<label class="form-label"><?=translate('exam')?> <span class="text-danger">*</span></label>
						<?php
							if (!empty($branch_id)) {
								$arrayExam = array("" => translate('select'));
								$exams = $this->db->get_where('exam', array('branch_id' => $branch_id, 'session_id' => get_session_id()))->result();
								foreach ($exams as $exam) {
									$arrayExam[$exam->id] = $this->application_model->exam_name_by_id($exam->id);
								}
							} else {
								$arrayExam = array("" => translate('select_branch_first'));
							}
							echo form_dropdown("exam_id", $arrayExam, set_value('exam_id'), "class='form-control' id='exam_id' required
							data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
						?>
					</div>
					<div class="col-md-3">
						<label class="form-label"><?=translate('class')?> <span class="text-danger">*</span></label>
						<?php
							$arrayClass = $this->app_lib->getClass($branch_id);
							echo form_dropdown("class_id", $arrayClass, set_value('class_id'), "class='form-control' id='class_id' onchange='getSectionByClass(this.value,0)'
							required data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
						?>
					</div>
					<div class="col-md-<?=$widget?>">
						<label class="form-label"><?=translate('section')?> <span class="text-danger">*</span></label>
						<?php
							$arraySection = $this->app_lib->getSections(set_value('class_id'), true);
							echo form_dropdown("section_id", $arraySection, set_value('section_id'), "class='form-control' id='section_id' required
							data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
						?>
					</div>
				</div>
			</div>
			<div class="card-footer">
				<div class="row">
					<div class="col-md-2 offset-md-10">
						<div class="d-grid">
							<button type="submit" name="submit" value="search" class="btn btn-outline-secondary">
								<i class="fas fa-filter"></i> <?=translate('filter')?>
							</button>
						</div>
					</div>
				</div>
			</div>
			<?php echo form_close(); ?>
		</div>

		<?php if (isset($student)): ?>
			<div class="card appear-animation" data-appear-animation="<?php echo $global_config['animations']?>" data-appear-animation-delay="100">
				<?php echo form_open('exam/reportCardPrint', array('class' => 'printIn')); ?>
				<input type="hidden" name="exam_id" value="<?=set_value('exam_id')?>">
				<input type="hidden" name="session_id" value="<?=set_value('session_id')?>">
				<div class="card-header d-flex align-items-center justify-content-between">
					<h5 class="mb-0"><i class="fas fa-users me-2"></i><?=translate('student_list')?></h5>
					<button type="submit" class="btn btn-outline-secondary btn-print-rc">
						<i class="fas fa-print me-1"></i><?=translate('generate')?>
					</button>
				</div>
				<div class="card-body">
					<div class="row mb-4">
						<div class="col-md-4">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" name="attendance" value="true" checked id="chkAttendance">
								<label class="form-check-label" for="chkAttendance">Print Attendance</label>
							</div>
							<div class="form-check mt-1">
								<input class="form-check-input" type="checkbox" name="grade_scale" value="true" checked id="chkGradeScale">
								<label class="form-check-label" for="chkGradeScale">Print Grade Scale</label>
							</div>
							<div class="mt-2">
								<label class="form-label"><?=translate('print_date')?></label>
								<input type="text" name="print_date" data-plugin-datepicker data-plugin-options='{ "todayHighlight" : true }' class="form-control" autocomplete="off" value="<?=date('Y-m-d')?>">
							</div>
						</div>
					</div>

					<div class="table-responsive">
						<table class="table table-bordered table-hover table-sm mb-0">
							<thead class="fw-bold">
								<tr>
									<th><?=translate('sl')?></th>
									<th>
										<div class="form-check">
											<input class="form-check-input" type="checkbox" id="selectAllchkbox" data-bs-toggle="tooltip" data-bs-title="Print Show / Hidden">
										</div>
									</th>
									<th><?=translate('student_name')?></th>
									<th><?=translate('category')?></th>
									<th><?=translate('register_no')?></th>
									<th><?=translate('roll')?></th>
									<th><?=translate('mobile_no')?></th>
									<th><?=translate('remarks')?></th>
								</tr>
							</thead>
							<tbody>
								<?php
								$count = 1;
								if (!empty($student)) {
									foreach ($student as $row): ?>
								<tr>
									<td><?=$count++?></td>
									<td class="d-print-none checked-area" width="30">
										<div class="form-check">
											<input class="form-check-input" type="checkbox" name="student_id[]" value="<?=$row['id']?>">
										</div>
									</td>
									<td><?=htmlspecialchars($row['first_name'] . ' ' . $row['last_name'])?></td>
									<td><?=htmlspecialchars($row['category'])?></td>
									<td><?=htmlspecialchars($row['register_no'])?></td>
									<td><?=htmlspecialchars($row['roll'])?></td>
									<td><?=htmlspecialchars($row['mobileno'])?></td>
									<td class="min-w-sm">
										<input type="text" class="form-control" autocomplete="off" name="remarks[]" value="">
									</td>
								</tr>
								<?php
									endforeach;
								} else {
									echo '<tr><td colspan="8" class="text-center"><h5 class="text-danger mb-0">' . translate('no_information_available') . '</h5></td></tr>';
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
				<?php echo form_close(); ?>
			</div>
		<?php endif; ?>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function () {

		$('#branch_id').on('change', function() {
			var branchID = $(this).val();
			getClassByBranch(branchID);
			getExamByBranch(branchID);
		});

		$('form.printIn').on('submit', function(e) {
			e.preventDefault();
			var $form = $(this);
			var $btn  = $form.find('.btn-print-rc');
			var origHtml = $btn.html();
			$btn.prop('disabled', true).html("<i class='fas fa-spinner fa-spin me-1'></i> Processing");

			$.ajax({
				url: $form.attr('action'),
				type: 'POST',
				data: $.extend({}, $form.serializeArray().reduce(function(obj, item) {
					if (obj[item.name] === undefined) { obj[item.name] = item.value; }
					else { if (!Array.isArray(obj[item.name])) { obj[item.name] = [obj[item.name]]; } obj[item.name].push(item.value); }
					return obj;
				}, {}), csrfData),
				dataType: 'html',
				success: function(data) {
					fn_printElem(data, true);
				},
				error: function() {
					alert('An error occurred, please try again');
				},
				complete: function() {
					$btn.prop('disabled', false).html(origHtml);
				}
			});
		});
	});
</script>
