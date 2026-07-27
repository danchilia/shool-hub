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
		<?php echo form_open($this->uri->uri_string(), array('class' => 'validate')); ?>
			<div class="card-header">
				<h5 class="mb-0"><?=translate('select_ground')?></h5>
			</div>
			<div class="card-body">
				<div class="row g-3">
					<?php if (is_superadmin_loggedin()): ?>
					<div class="col-md-2">
						<label class="form-label"><?=translate('branch')?> <span class="text-danger">*</span></label>
						<?php
							$arrayBranch = $this->app_lib->getSelectList('branch');
							echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' id='branch_id'
							data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
						?>
					</div>
					<?php endif; ?>
					<div class="col-md-<?php echo $widget; ?>">
						<label class="form-label"><?=translate('exam')?> <span class="text-danger">*</span></label>
						<?php
							if (isset($branch_id)) {
								$arrayExam = array("" => translate('select'));
								$exams = $this->db->get_where('exam', array('branch_id' => $branch_id, 'session_id' => get_session_id()))->result();
								foreach ($exams as $row) {
									$arrayExam[$row->id] = $this->application_model->exam_name_by_id($row->id);
								}
							} else {
								$arrayExam = array("" => translate('select_branch_first'));
							}
							echo form_dropdown("exam_id", $arrayExam, set_value('exam_id'), "class='form-control' id='exam_id' required data-plugin-selectTwo
							data-width='100%' data-minimum-results-for-search='Infinity'");
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
					<div class="col-md-<?php echo $widget; ?>">
						<label class="form-label"><?=translate('section')?> <span class="text-danger">*</span></label>
						<?php
							$arraySection = $this->app_lib->getSections(set_value('class_id'), false);
							echo form_dropdown("section_id", $arraySection, set_value('section_id'), "class='form-control' id='section_id' required
							data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
						?>
					</div>
					<div class="col-md-3">
						<label class="form-label"><?=translate('subject')?> <span class="text-danger">*</span></label>
						<?php
							if (!empty(set_value('class_id'))) {
								$arraySubject = array("" => translate('select'));
								$assigns = $this->db->get_where('subject_assign', array('class_id' => set_value('class_id'), 'section_id' => set_value('section_id')))->result();
								foreach ($assigns as $row) {
									$arraySubject[$row->subject_id] = get_type_name_by_id('subject', $row->subject_id);
								}
							} else {
								$arraySubject = array("" => translate('select_class_first'));
							}
							echo form_dropdown("subject_id", $arraySubject, set_value('subject_id'), "class='form-control' id='subject_id' required
							data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
						?>
					</div>
				</div>
			</div>
			<div class="card-footer">
				<div class="row">
					<div class="col-md-2 offset-md-10">
						<div class="d-grid">
							<button type="submit" name="search" value="1" class="btn btn-outline-secondary">
								<i class="fas fa-filter"></i> <?=translate('filter')?>
							</button>
						</div>
					</div>
				</div>
			</div>
			<?php echo form_close(); ?>
		</div>

		<?php if (isset($student)): ?>
		<div class="card appear-animation" data-appear-animation="<?php echo $global_config['animations']; ?>" data-appear-animation-delay="100">
			<?php echo form_open('exam/mark_save', array('class' => 'frm-submit-msg'));
				$data = array(
					'class_id'   => $class_id,
					'section_id' => $section_id,
					'exam_id'    => $exam_id,
					'subject_id' => $subject_id,
					'session_id' => get_session_id(),
					'branch_id'  => $branch_id,
				);
				echo form_hidden($data);
			?>
			<div class="card-header">
				<h5 class="mb-0"><i class="fas fa-users me-2"></i><?=translate('mark_entries')?></h5>
			</div>
			<div class="card-body">
				<?php if (!empty($student) && !empty($timetable_detail)): ?>
				<div class="table-responsive mt-3 mb-4">
					<table class="table table-bordered table-sm mb-0">
						<thead>
							<tr>
								<th><?=translate('sl')?></th>
								<th><?=translate('student_name')?></th>
								<th><?=translate('category')?></th>
								<th><?=translate('register_no')?></th>
								<th><?=translate('roll')?></th>
								<th>IsAbsent</th>
							<?php
							$distributions = json_decode($timetable_detail['mark_distribution'], true);
							foreach ($distributions as $i => $value): ?>
								<th><?php echo htmlspecialchars(get_type_name_by_id('exam_mark_distribution', $i)) . ' (' . htmlspecialchars($value['full_mark']) . ')'; ?></th>
							<?php endforeach; ?>
							</tr>
						</thead>
						<tbody>
							<?php $count = 1; foreach ($student as $key => $row): ?>
							<tr>
								<td><?php echo $count++; ?><input type="hidden" name="mark[<?=$key?>][student_id]" value="<?=$row['student_id']?>"></td>
								<td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
								<td><?php echo htmlspecialchars(get_type_name_by_id('student_category', $row['category_id'])); ?></td>
								<td><?php echo htmlspecialchars($row['register_no']); ?></td>
								<td><?php echo htmlspecialchars($row['roll']); ?></td>
								<td>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" name="mark[<?=$key?>][absent]" <?=($row['get_abs'] == 'on' ? 'checked' : ''); ?>>
									</div>
								</td>
								<?php
								$getDetails = json_decode($row['get_mark'], true);
								foreach ($distributions as $id => $ass):
									$existMark = isset($getDetails[$id]) ? $getDetails[$id] : '';
								?>
								<td class="min-w-sm">
									<input type="text" class="form-control" autocomplete="off" name="mark[<?=$key?>][assessment][<?=$id?>]" value="<?=htmlspecialchars($existMark)?>">
								</td>
								<?php endforeach; ?>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<?php else: ?>
				<div class="alert alert-info mt-3 text-center"><?=translate('no_information_available')?></div>
				<?php endif; ?>
			</div>
			<div class="card-footer">
				<div class="row">
					<div class="col-md-2 offset-md-10">
						<div class="d-grid">
							<button type="submit" class="btn btn-primary" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
								<i class="fas fa-plus-circle me-1"></i><?=translate('save')?>
							</button>
						</div>
					</div>
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
			$('#subject_id').html('').append('<option value=""><?=translate("select")?></option>');
		});

		$('#section_id').on('change', function() {
			var classID   = $('#class_id').val();
			var sectionID = $(this).val();
			$.ajax({
				url: base_url + 'subject/getByClassSection',
				type: 'POST',
				data: $.extend({ classID: classID, sectionID: sectionID }, csrfData),
				success: function(data) {
					$('#subject_id').html(data);
				}
			});
		});
	});
</script>
