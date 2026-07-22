<style type="text/css">
	@media print {
		.pagebreak { page-break-before: always; }
		body { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; color-adjust: exact !important; }
		.level-ee, .level-me, .level-ae, .level-be, .section-header, .key-ee, .key-me, .key-ae, .key-be { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
	}
	.cbc-report {
		background: #fff; width: 210mm; max-width: 800px; position: relative; z-index: 2;
		margin: 0 auto; padding: 15px 25px; font-family: 'Segoe UI', Tahoma, Arial, sans-serif; font-size: 13px; color: #222;
	}
	.cbc-report table { border-collapse: collapse; width: 100%; margin: 0 auto; }
	.cbc-report th, .cbc-report td { padding: 6px 10px; border: 1px solid #999; }
	.cbc-report th { background: #f5f5f5; font-weight: 600; }

	.school-header { border: 2px solid #1a5276; border-radius: 8px; padding: 12px; margin-bottom: 12px; background: linear-gradient(135deg, #f8f9fa 0%, #e8f4fd 100%); }
	.school-name { font-size: 22px; font-weight: 700; color: #1a5276; margin: 0; text-transform: uppercase; }
	.school-address { font-size: 11px; color: #555; margin: 2px 0; }
	.report-title { font-size: 16px; font-weight: 700; color: #fff; background: #1a5276; padding: 6px 15px; text-align: center; margin: 8px 0 0; border-radius: 4px; letter-spacing: 1px; }
	.term-info { text-align: center; font-size: 13px; margin-top: 5px; color: #333; font-weight: 600; }

	.student-info th { background: #eaf2f8; color: #1a5276; font-weight: 600; width: 22%; text-align: left; border: 1px solid #aab7c4; }
	.student-info td { border: 1px solid #aab7c4; font-weight: 500; }

	.key-table { margin: 10px 0; }
	.key-table td { text-align: center; font-weight: 700; font-size: 11px; padding: 8px 5px; border: 1px solid #999; }
	.key-ee { background: #28a745 !important; color: #fff !important; }
	.key-me { background: #007bff !important; color: #fff !important; }
	.key-ae { background: #ffc107 !important; color: #000 !important; }
	.key-be { background: #dc3545 !important; color: #fff !important; }

	.section-header { background: #1a5276 !important; color: #fff !important; font-weight: 700; text-align: center; font-size: 14px; padding: 8px; letter-spacing: 1px; }
	.section-header th { background: #1a5276 !important; color: #fff !important; border-color: #1a5276; }

	.assessment-table th { background: #d6eaf8 !important; color: #1a5276; font-weight: 600; text-align: center; border: 1px solid #999; }
	.assessment-table td { border: 1px solid #999; }

	.level-ee { background: #d4edda !important; color: #155724 !important; font-weight: 700; text-align: center; font-size: 14px; }
	.level-me { background: #cce5ff !important; color: #004085 !important; font-weight: 700; text-align: center; font-size: 14px; }
	.level-ae { background: #fff3cd !important; color: #856404 !important; font-weight: 700; text-align: center; font-size: 14px; }
	.level-be { background: #f8d7da !important; color: #721c24 !important; font-weight: 700; text-align: center; font-size: 14px; }

	.summary-row { background: #eaf2f8 !important; font-weight: 700; }
	.summary-row td { text-align: center; font-size: 13px; }

	.behaviour-table th { background: #e8daef !important; color: #4a235a; font-weight: 600; text-align: center; border: 1px solid #999; }
	.behaviour-header th { background: #6c3483 !important; color: #fff !important; }

	.signature-table { margin-top: 30px; }
	.signature-table td { border: none !important; padding: 5px 15px; font-size: 12px; }
	.sig-line { border-top: 2px solid #333 !important; padding-top: 8px !important; text-align: center; font-weight: 600; }
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

		$levelClasses = array(
			'EE2' => 'level-ee2', 'EE1' => 'level-ee1',
			'ME2' => 'level-me2', 'ME1' => 'level-me1',
			'AE2' => 'level-ae2', 'AE1' => 'level-ae1',
			'BE2' => 'level-be2', 'BE1' => 'level-be1',
			// backward compat
			'EE' => 'level-ee2', 'ME' => 'level-me2', 'AE' => 'level-ae1', 'BE' => 'level-be1',
		);
?>
<div class="cbc-report">

	<!-- SCHOOL HEADER -->
	<div class="school-header">
		<table border="0" style="border:none;">
			<tr>
				<td style="width:15%; vertical-align:middle; text-align:center; border:none;">
					<img style="max-width:80px; max-height:80px;" src="<?=get_branch_logo($getExam['branch_id'], 'report_card_logo')?>">
				</td>
				<td style="width:55%; vertical-align:middle; text-align:center; border:none;">
					<p class="school-name"><?=$getSchool['school_name']?></p>
					<p class="school-address"><?=$getSchool['address']?></p>
					<p class="school-address">Tel: <?=$getSchool['mobileno']?> | Email: <?=$getSchool['email']?></p>
					<div class="report-title">COMPETENCY BASED CURRICULUM (CBC) PROGRESS REPORT</div>
					<p class="term-info">Academic Year: <?=$schoolYear?>
					<?php if (!empty($termName)): ?> &nbsp;|&nbsp; Term: <?=$termName?><?php endif; ?>
					</p>
				</td>
				<td style="width:15%; vertical-align:middle; text-align:center; border:none;">
					<img style="max-width:80px; max-height:80px; border-radius:5px; border:2px solid #1a5276;" src="<?=get_image_url('student', $student['photo'])?>">
				</td>
			</tr>
		</table>
	</div>

	<!-- LEARNER DETAILS -->
	<table class="student-info" style="margin-bottom:10px;">
		<tr>
			<th>Learner's Name</th>
			<td><?=$student['first_name'] . ' ' . $student['last_name']?></td>
			<th>UPI Number</th>
			<td><?=!empty($student['upi_number']) ? $student['upi_number'] : 'N/A'?></td>
		</tr>
		<tr>
			<th>Admission No</th>
			<td><?=$student['register_no']?></td>
			<th>Class / Stream</th>
			<td><?=$student['class_name']?> - <?=$student['section_name']?></td>
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
	</table>

	<!-- KNEC 8-LEVEL COMPETENCY KEY -->
	<table class="key-table" style="font-size:10px;">
		<tr>
			<td class="key-ee" style="width:12.5%; border-radius:4px 0 0 4px; background:#155724; color:#fff;">EE2 - Exceeding (Advanced)</td>
			<td class="key-ee" style="width:12.5%; background:#1e7e34; color:#fff;">EE1 - Exceeding Expectations</td>
			<td class="key-me" style="width:12.5%; background:#004085; color:#fff;">ME2 - Meeting (Proficient)</td>
			<td class="key-me" style="width:12.5%; background:#0062cc; color:#fff;">ME1 - Meeting Expectations</td>
			<td class="key-ae" style="width:12.5%; background:#856404; color:#fff;">AE2 - Approaching (Developing)</td>
			<td class="key-ae" style="width:12.5%; background:#d39e00; color:#000;">AE1 - Approaching Expectations</td>
			<td class="key-be" style="width:12.5%; background:#721c24; color:#fff;">BE2 - Below (Beginning)</td>
			<td class="key-be" style="width:12.5%; background:#dc3545; color:#fff; border-radius:0 4px 4px 0;">BE1 - Below Expectations</td>
		</tr>
	</table>

	<!-- LEARNING AREAS ASSESSMENT -->
	<table class="assessment-table">
		<thead>
			<tr class="section-header"><th colspan="4" style="border:none;">LEARNING AREAS ASSESSMENT</th></tr>
			<tr>
				<th style="width:5%; text-align:center;">#</th>
				<th style="width:40%; text-align:left;">Learning Area</th>
				<th style="width:18%; text-align:center;">Competency Level</th>
				<th style="width:37%; text-align:left;">Teacher's Remarks</th>
			</tr>
		</thead>
		<tbody>
		<?php
		if (count($assessments)) {
			$counts = array('EE2'=>0,'EE1'=>0,'ME2'=>0,'ME1'=>0,'AE2'=>0,'AE1'=>0,'BE2'=>0,'BE1'=>0);
			$num = 1;
			foreach ($assessments as $a) {
				$lvl = $a['competency_level'];
				// normalise old 4-level values
				if ($lvl === 'EE') $lvl = 'EE2';
				elseif ($lvl === 'ME') $lvl = 'ME2';
				elseif ($lvl === 'AE') $lvl = 'AE1';
				elseif ($lvl === 'BE') $lvl = 'BE1';
				$levelClass = isset($levelClasses[$lvl]) ? $levelClasses[$lvl] : '';
				if (isset($counts[$lvl])) $counts[$lvl]++;
		?>
			<tr>
				<td style="text-align:center;"><?=$num++?></td>
				<td><?=$a['learning_area_name']?></td>
				<td class="<?=$levelClass?>" style="font-weight:700;text-align:center;"><?=$lvl?></td>
				<td style="font-size:12px;"><?=$a['remarks']?></td>
			</tr>
		<?php } ?>
			<tr class="summary-row">
				<td colspan="2" style="text-align:right; padding-right:15px;font-size:11px;">KNEC 8-Level Summary:</td>
				<td colspan="2" style="font-size:10px;">
					<?php foreach ($counts as $lv => $cnt): if ($cnt > 0): ?>
					<?php
						$bg = '#6c757d';
						if (strpos($lv,'EE')===0) $bg = ($lv=='EE2'?'#155724':'#1e7e34');
						elseif (strpos($lv,'ME')===0) $bg = ($lv=='ME2'?'#004085':'#0062cc');
						elseif (strpos($lv,'AE')===0) $bg = ($lv=='AE2'?'#856404':'#d39e00');
						elseif (strpos($lv,'BE')===0) $bg = ($lv=='BE2'?'#721c24':'#dc3545');
						$fg = ($lv == 'AE1') ? '#000' : '#fff';
					?>
					<span style="background:<?=$bg?>;color:<?=$fg?>;padding:1px 7px;border-radius:3px;margin-right:3px;"><?=$lv?>:<?=$cnt?></span>
					<?php endif; endforeach; ?>
					&nbsp; Total: <?=count($assessments)?> areas
				</td>
			</tr>
		<?php } else { ?>
			<tr><td colspan="4" style="text-align:center; color:#dc3545; padding:15px;">No assessments recorded</td></tr>
		<?php } ?>
		</tbody>
	</table>

	<!-- BEHAVIOUR & VALUES ASSESSMENT -->
	<?php if (count($behaviours)): ?>
	<table class="behaviour-table" style="margin-top:10px;">
		<thead>
			<tr class="behaviour-header"><th colspan="4" style="border:none;">BEHAVIOUR & VALUES ASSESSMENT</th></tr>
			<tr>
				<th style="width:5%; text-align:center;">#</th>
				<th style="width:35%; text-align:left;">Category</th>
				<th style="width:18%; text-align:center;">Rating</th>
				<th style="width:42%; text-align:left;">Remarks</th>
			</tr>
		</thead>
		<tbody>
		<?php $bNum = 1; foreach ($behaviours as $b):
			$levelClass = isset($levelClasses[$b['rating']]) ? $levelClasses[$b['rating']] : '';
		?>
			<tr>
				<td style="text-align:center;"><?=$bNum++?></td>
				<td><?=$b['category']?></td>
				<td class="<?=$levelClass?>"><?=$b['rating']?></td>
				<td style="font-size:12px;"><?=$b['remarks']?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php endif; ?>

	<!-- SIGNATURES -->
	<table class="signature-table" style="width:100%;">
		<tr>
			<td style="width:25%;">Print Date: <strong><?=_d($print_date)?></strong></td>
			<td style="width:25%;">&nbsp;</td>
			<td style="width:25%;">&nbsp;</td>
			<td style="width:25%;">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td class="sig-line">Class Teacher's Signature</td>
			<td class="sig-line">Head Teacher's Signature & Stamp</td>
			<td class="sig-line">Parent/Guardian's Signature</td>
		</tr>
	</table>

</div>
<div class="pagebreak"></div>
<?php } } ?>
