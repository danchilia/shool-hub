<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title">
			<i class="fas fa-user-clock"></i> Admission Request Details
			<?php
				$statusBadge = '';
				switch ($request['status']) {
					case 'pending': $statusBadge = '<span class="label label-warning">Pending</span>'; break;
					case 'approved': $statusBadge = '<span class="label label-success">Approved</span>'; break;
					case 'rejected': $statusBadge = '<span class="label label-danger">Rejected</span>'; break;
				}
				echo $statusBadge;
			?>
		</h4>
	</header>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<h5><strong>Student Information</strong></h5>
				<table class="table table-bordered table-condensed">
					<tr><th width="40%">First Name</th><td><?=$student_data['first_name']?></td></tr>
					<tr><th>Last Name</th><td><?=$student_data['last_name']?></td></tr>
					<tr><th>Email</th><td><?=$student_data['email']?></td></tr>
					<tr><th>Mobile</th><td><?=$student_data['mobileno']?></td></tr>
					<tr><th>Gender</th><td><?=ucfirst($student_data['gender'])?></td></tr>
					<tr><th>Birthday</th><td><?=!empty($student_data['birthday']) ? $student_data['birthday'] : 'N/A'?></td></tr>
					<tr><th>UPI Number</th><td><?=!empty($student_data['upi_number']) ? $student_data['upi_number'] : 'N/A'?></td></tr>
					<tr><th>Register No</th><td><?=$student_data['register_no']?></td></tr>
					<tr><th>Admission Date</th><td><?=$student_data['admission_date']?></td></tr>
					<tr><th>Category</th><td><?=get_type_name_by_id('student_category', $student_data['category_id'])?></td></tr>
					<tr><th>Religion</th><td><?=!empty($student_data['religion']) ? $student_data['religion'] : 'N/A'?></td></tr>
					<tr><th>Address</th><td><?=!empty($student_data['current_address']) ? $student_data['current_address'] : 'N/A'?></td></tr>
				</table>
			</div>
			<div class="col-md-6">
				<h5><strong>Guardian Information</strong></h5>
				<table class="table table-bordered table-condensed">
					<?php if (isset($guardian_data['guardian_chk']) && $guardian_data['guardian_chk']): ?>
					<tr><th width="40%">Existing Parent ID</th><td><?=$guardian_data['parent_id']?></td></tr>
					<?php else: ?>
					<tr><th width="40%">Name</th><td><?=isset($guardian_data['grd_name']) ? $guardian_data['grd_name'] : 'N/A'?></td></tr>
					<tr><th>Relation</th><td><?=isset($guardian_data['grd_relation']) ? $guardian_data['grd_relation'] : 'N/A'?></td></tr>
					<tr><th>Occupation</th><td><?=isset($guardian_data['grd_occupation']) ? $guardian_data['grd_occupation'] : 'N/A'?></td></tr>
					<tr><th>Mobile</th><td><?=isset($guardian_data['grd_mobileno']) ? $guardian_data['grd_mobileno'] : 'N/A'?></td></tr>
					<tr><th>Email</th><td><?=isset($guardian_data['grd_email']) ? $guardian_data['grd_email'] : 'N/A'?></td></tr>
					<?php endif; ?>
				</table>

				<h5><strong>Enrollment Details</strong></h5>
				<table class="table table-bordered table-condensed">
					<tr><th width="40%">Class</th><td><?=$request['class_name']?></td></tr>
					<tr><th>Section</th><td><?=$request['section_name']?></td></tr>
					<tr><th>Roll</th><td><?=$request['roll']?></td></tr>
					<tr><th>Requested By</th><td><?=$request['teacher_name']?></td></tr>
					<tr><th>Request Date</th><td><?=_d($request['created_at'])?></td></tr>
					<?php if ($request['status'] != 'pending'): ?>
					<tr><th>Reviewed By</th><td><?=!empty($request['reviewer_name']) ? $request['reviewer_name'] : 'N/A'?></td></tr>
					<tr><th>Reviewed At</th><td><?=!empty($request['reviewed_at']) ? _d($request['reviewed_at']) : 'N/A'?></td></tr>
					<?php endif; ?>
					<?php if ($request['status'] == 'rejected' && !empty($request['review_remarks'])): ?>
					<tr><th>Rejection Reason</th><td class="text-danger"><?=$request['review_remarks']?></td></tr>
					<?php endif; ?>
				</table>
			</div>
		</div>

		<?php if ($request['status'] == 'pending' && get_permission('admission_approval', 'is_add')): ?>
		<div class="row mt-lg">
			<div class="col-md-12 text-center">
				<a href="<?=base_url('admission_request/approve/' . $request['id'])?>" class="btn btn-success btn-lg"
				   onclick="return confirm('Are you sure you want to approve this admission request?')">
					<i class="fas fa-check"></i> Approve Admission
				</a>
				<a href="javascript:void(0);" class="btn btn-danger btn-lg" onclick="mfp_modal('#rejectModal')">
					<i class="fas fa-times"></i> Reject Admission
				</a>
			</div>
		</div>

		<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="rejectModal">
			<section class="panel">
				<?php echo form_open('admission_request/reject/' . $request['id']);?>
					<header class="panel-heading">
						<h4 class="panel-title"><i class="fas fa-times-circle"></i> Reject Admission</h4>
					</header>
					<div class="panel-body">
						<div class="form-group">
							<label class="control-label">Reason for Rejection <span class="required">*</span></label>
							<textarea name="review_remarks" class="form-control" rows="4" required></textarea>
						</div>
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-12 text-right">
								<button type="submit" class="btn btn-danger"><i class="fas fa-times"></i> Reject</button>
								<button class="btn btn-default modal-dismiss"><?=translate('cancel')?></button>
							</div>
						</div>
					</footer>
				<?php echo form_close();?>
			</section>
		</div>
		<?php endif; ?>
	</div>
</section>
