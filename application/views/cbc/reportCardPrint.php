<style type="text/css">
	@media print { .pagebreak { page-break-before: always; } }
	.cbc-report { background: #fff; width: 1000px; position: relative; z-index: 2; margin: 0 auto; padding: 20px 30px; }
	.cbc-report table { border-collapse: collapse; width: 100%; margin: 0 auto; }
	.cbc-report .level-ee { background: #d4edda; font-weight: bold; }
	.cbc-report .level-me { background: #cce5ff; }
	.cbc-report .level-ae { background: #fff3cd; }
	.cbc-report .level-be { background: #f8d7da; }
	.cbc-report .section-header { background: #f0f0f0; font-weight: bold; text-align: center; }
</style>

<?php
$this->load->model('cbc_model');
if (count($student_array)) {
	foreach ($student_array as $sc => $studentID) {
		$result = $this->cbc_model->getStudentCbcReport($studentID, $examID, $sessionID);
		$student = $result['student'];
		$assessments = $result['assessments'];
		$behaviours = $result['behaviour'];

		$getExam = $this->db->where('id', $examID)->get('exam')->row_array();
		$getSchool = $this->db->where('id', $getExam['branch_id'])->get('branch')->row_array();
		$schoolYear = get_type_name_by_id('schoolyear', $sessionID, 'school_year');
		$termName = !empty($getExam['term_id']) ? get_type_name_by_id('exam_term', $getExam['term_id']) : '';

		$levelLabels = array('EE' => 'Exceeding Expectations', 'ME' => 'Meeting Expectations', 'AE' => 'Approaching Expectations', 'BE' => 'Below Expectations');
		$levelClasses = array('EE' => 'level-ee', 'ME' => 'level-me', 'AE' => 'level-ae', 'BE' => 'level-be');
?>
<div class="cbc-report">
	<!-- School Header -->
	<table border="0" style="margin-top: 10px;">
		<tbody>
			<tr>
				<td style="width:20%; vertical-align: top; text-align: center;">
					<img style="max-width:120px;" src="<?=get_branch_logo($getExam['branch_id'], 'report_card_logo')?>">
				</td>
				<td style="width:60%; vertical-align: top; text-align: center;">
					<h2 style="margin: 0; font-size: 24px;"><?=$getSchool['school_name']?></h2>
					<p style="margin: 2px 0;"><?=$getSchool['address']?></p>
					<p style="margin: 2px 0;">Tel: <?=$getSchool['mobileno']?> | Email: <?=$getSchool['email']?></p>
					<h3 style="margin: 10px 0 5px; font-size: 18px; text-decoration: underline;">COMPETENCY BASED CURRICULUM (CBC) PROGRESS REPORT</h3>
					<p style="margin: 2px 0;"><strong>Academic Year:</strong> <?=$schoolYear?>
					<?php if (!empty($termName)): ?> | <strong>Term:</strong> <?=$termName?><?php endif; ?>
					</p>
				</td>
				<td style="width:20%; vertical-align: top; text-align: right;">
					<img style="max-width:90px; max-height:90px;" src="<?=get_image_url('student', $student['photo'])?>">
				</td>
			</tr>
		</tbody>
	</table>

	<!-- Learner Details -->
	<table class="table table-bordered" style="margin-top: 15px;">
		<tbody>
			<tr>
				<th style="width:20%">Learner's Name</th>
				<td style="width:30%"><?=$student['first_name'] . ' ' . $student['last_name']?></td>
				<th style="width:20%">UPI Number</th>
				<td style="width:30%"><?=!empty($student['upi_number']) ? $student['upi_number'] : 'N/A'?></td>
			</tr>
			<tr>
				<th>Admission No</th>
				<td><?=$student['register_no']?></td>
				<th>Class / Stream</th>
				<td><?=$student['class_name']?> <?=$student['section_name']?></td>
			</tr>
			<tr>
				<th>Date of Birth</th>
				<td><?=!empty($student['birthday']) ? _d($student['birthday']) : 'N/A'?></td>
				<th>Gender</th>
				<td><?=ucfirst($student['gender'])?></td>
			</tr>
			<tr>
				<th>Guardian Name</th>
				<td><?=!empty($student['guardian_name']) ? $student['guardian_name'] : 'N/A'?></td>
				<th>Roll Number</th>
				<td><?=$student['roll']?></td>
			</tr>
		</tbody>
	</table>

	<!-- Competency Level Key -->
	<table class="table table-bordered" style="margin-top: 10px;">
		<thead>
			<tr class="section-header"><th colspan="4">Competency Level Key</th></tr>
		</thead>
		<tbody>
			<tr>
				<td class="level-ee" style="width:25%; text-align:center;">EE - Exceeding Expectations</td>
				<td class="level-me" style="width:25%; text-align:center;">ME - Meeting Expectations</td>
				<td class="level-ae" style="width:25%; text-align:center;">AE - Approaching Expectations</td>
				<td class="level-be" style="width:25%; text-align:center;">BE - Below Expectations</td>
			</tr>
		</tbody>
	</table>

	<!-- Learning Areas Assessment -->
	<table class="table table-bordered table-condensed" style="margin-top: 10px;">
		<thead>
			<tr class="section-header"><th colspan="3">Learning Areas Assessment</th></tr>
			<tr>
				<th style="width:50%">Learning Area</th>
				<th style="width:20%; text-align:center;">Competency Level</th>
				<th style="width:30%">Teacher's Remarks</th>
			</tr>
		</thead>
		<tbody>
		<?php
		if (count($assessments)) {
			$eeCount = 0; $meCount = 0; $aeCount = 0; $beCount = 0;
			foreach ($assessments as $a) {
				$levelClass = isset($levelClasses[$a['competency_level']]) ? $levelClasses[$a['competency_level']] : '';
				if ($a['competency_level'] == 'EE') $eeCount++;
				elseif ($a['competency_level'] == 'ME') $meCount++;
				elseif ($a['competency_level'] == 'AE') $aeCount++;
				elseif ($a['competency_level'] == 'BE') $beCount++;
		?>
			<tr>
				<td><?=$a['learning_area_name']?></td>
				<td class="<?=$levelClass?>" style="text-align:center; font-weight:bold;"><?=$a['competency_level']?></td>
				<td><?=$a['remarks']?></td>
			</tr>
		<?php } ?>
			<tr style="font-weight: bold; background: #f9f9f9;">
				<td>Summary</td>
				<td colspan="2" style="text-align:center;">
					EE: <?=$eeCount?> | ME: <?=$meCount?> | AE: <?=$aeCount?> | BE: <?=$beCount?>
					| Total Areas: <?=count($assessments)?>
				</td>
			</tr>
		<?php } else { ?>
			<tr><td colspan="3" class="text-center text-danger">No assessments recorded</td></tr>
		<?php } ?>
		</tbody>
	</table>

	<!-- Behaviour & Values Assessment -->
	<?php if (count($behaviours)): ?>
	<table class="table table-bordered table-condensed" style="margin-top: 10px;">
		<thead>
			<tr class="section-header"><th colspan="3">Behaviour & Values Assessment</th></tr>
			<tr>
				<th style="width:40%">Category</th>
				<th style="width:20%; text-align:center;">Rating</th>
				<th style="width:40%">Remarks</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($behaviours as $b):
			$levelClass = isset($levelClasses[$b['rating']]) ? $levelClasses[$b['rating']] : '';
		?>
			<tr>
				<td><?=$b['category']?></td>
				<td class="<?=$levelClass?>" style="text-align:center; font-weight:bold;"><?=$b['rating']?></td>
				<td><?=$b['remarks']?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php endif; ?>

	<!-- Signatures -->
	<table style="width:100%; outline:none; margin-top: 40px;">
		<tbody>
			<tr>
				<td style="font-size: 13px;">Print Date: <?=_d($print_date)?></td>
				<td style="border-top: 1px solid #333; font-size: 13px; text-align:center; padding-top:5px;">Class Teacher's Signature</td>
				<td style="border-top: 1px solid #333; font-size: 13px; text-align:center; padding-top:5px;">Head Teacher's Signature & Stamp</td>
				<td style="border-top: 1px solid #333; font-size: 13px; text-align:center; padding-top:5px;">Parent/Guardian's Signature</td>
			</tr>
		</tbody>
	</table>
</div>
<div class="pagebreak"></div>
<?php } } ?>
