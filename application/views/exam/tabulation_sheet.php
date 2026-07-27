<?php
$widget = (is_superadmin_loggedin() ? 2 : 3);
$branch = $this->db->where('id', $branch_id)->get('branch')->row_array();
?>
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
			<?php echo form_open('exam/tabulation_sheet', array('class' => 'validate')); ?>
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
							foreach ($years as $year) {
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

		<?php if (isset($get_subjects)): ?>
		<div class="card appear-animation" data-appear-animation="<?php echo $global_config['animations']; ?>" data-appear-animation-delay="100">
			<div class="card-header d-flex align-items-center justify-content-between">
				<h5 class="mb-0"><i class="fas fa-users me-2"></i><?=translate('tabulation_sheet')?></h5>
				<button type="button" onclick="fn_printElem('printResult')" class="btn btn-sm btn-outline-secondary">
					<i class="fas fa-print me-1"></i><?=translate('print')?>
				</button>
			</div>
			<div class="card-body">
				<div class="table-responsive mt-2 mb-3">
					<div id="printResult">
						<div class="d-none d-print-block text-center mb-3">
							<h4 class="fw-bold"><?=htmlspecialchars($branch['name'])?></h4>
							<h5><?=htmlspecialchars($branch['address'])?></h5>
							<h5 class="fw-bold"><?=htmlspecialchars($this->application_model->exam_name_by_id(set_value('exam_id')))?> - Tabulation Sheet</h5>
							<h5>
								<?php
								echo htmlspecialchars(translate('class') . ' : ' . get_type_name_by_id('class', set_value('class_id')));
								echo ' ( ' . htmlspecialchars(translate('section') . ' : ' . get_type_name_by_id('section', set_value('section_id'))) . ' )';
								?>
							</h5>
							<hr>
						</div>
						<table class="table table-bordered table-hover table-sm mb-0">
							<thead>
								<tr>
									<th><?=translate('sl')?></th>
									<th><?=translate('students')?></th>
									<th><?=translate('roll')?></th>
									<?php
									foreach ($get_subjects as $subject) {
										$fullMark = array_sum(array_column(json_decode($subject['mark_distribution'], true), 'full_mark'));
										echo '<th>' . htmlspecialchars($subject['subject_name']) . ' (' . (int)$fullMark . ')</th>';
									}
									?>
									<th><?=translate('total_marks')?></th>
									<th>GPA</th>
									<th><?=translate('result')?></th>
								</tr>
							</thead>
							<tbody>
								<?php
								$count = 1;
								$enrolls = $this->db->get_where('enroll', array(
									'class_id'   => set_value('class_id'),
									'section_id' => set_value('section_id'),
									'session_id' => set_value('session_id'),
									'branch_id'  => $branch_id,
								))->result_array();
								if (!empty($enrolls)) {
									foreach ($enrolls as $enroll):
										$stu = $this->db->select('CONCAT(first_name, " ", last_name) as fullname')
											->where('id', $enroll['student_id'])
											->get('student')->row_array();
								?>
								<tr>
									<td><?php echo $count++; ?></td>
									<td><?php echo htmlspecialchars($stu['fullname']); ?></td>
									<td><?php echo htmlspecialchars($enroll['roll']); ?></td>
									<?php
									$totalMarks      = 0;
									$totalFullmarks  = 0;
									$totalGradePoint = 0;
									$unset_subject   = 0;
									$result_status   = 1;
									foreach ($get_subjects as $subject):
									?>
									<td>
									<?php
										$this->db->where(array(
											'class_id'   => set_value('class_id'),
											'exam_id'    => set_value('exam_id'),
											'subject_id' => $subject['subject_id'],
											'student_id' => $enroll['student_id'],
											'session_id' => set_value('session_id'),
										));
										$getMark = $this->db->get('mark')->row_array();
										if (!empty($getMark)) {
											if ($getMark['absent'] != 'on') {
												$totalObtained = 0;
												$totalFullMark = 0;
												$fullMarkDistribution = json_decode($subject['mark_distribution'], true);
												$obtainedMark = json_decode($getMark['mark'], true);
												foreach ($fullMarkDistribution as $i => $val) {
													$obtained_mark = floatval($obtainedMark[$i]);
													$totalObtained += $obtained_mark;
													$totalFullMark += $val['full_mark'];
													$passMark = floatval($val['pass_mark']);
													if ($obtained_mark < $passMark) {
														$result_status = 0;
													}
												}
												echo htmlspecialchars($totalObtained . '/' . $totalFullMark);
												if ($totalObtained != 0 && !empty($totalObtained) && !empty($totalFullMark)) {
													$percentage = ($totalObtained * 100) / $totalFullMark;
													$grade = $this->exam_model->get_grade($percentage, $branch_id);
													if (!empty($grade)) {
														$totalGradePoint += $grade['grade_point'];
													}
												}
												$totalMarks += $totalObtained;
											} else {
												echo htmlspecialchars(translate('absent'));
											}
											$totalFullmarks += $totalFullMark;
										} else {
											echo 'N/A';
											$unset_subject++;
										}
									?>
									</td>
									<?php endforeach; ?>
									<td><?php echo htmlspecialchars($totalMarks . '/' . $totalFullmarks); ?></td>
									<td>
										<?php
										$totalSubjects = count($get_subjects);
										if (!empty($totalSubjects)) {
											echo number_format($totalGradePoint / $totalSubjects, 2, '.', '');
										}
										?>
									</td>
									<td>
										<?php
										if ($unset_subject == 0) {
											if ($result_status) {
												echo '<span class="badge bg-success">PASS</span>';
											} else {
												echo '<span class="badge bg-danger">FAIL</span>';
											}
										}
										?>
									</td>
								</tr>
								<?php
									endforeach;
								} else {
									$colspan = (count($get_subjects) + 5);
									echo '<tr><td colspan="' . $colspan . '" class="text-center"><h5 class="text-danger mb-0">' . translate('no_information_available') . '</h5></td></tr>';
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
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
	});
</script>
