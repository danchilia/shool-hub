<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"><i class="fas fa-user-clock"></i> Admission Requests</h4>
	</header>
	<div class="panel-body">
		<div class="table-responsive">
			<table class="table table-bordered table-hover table-condensed table-export">
				<thead>
					<tr>
						<th><?=translate('sl')?></th>
						<th>Student Name</th>
						<th><?=translate('class')?></th>
						<th><?=translate('section')?></th>
						<th>Requested By</th>
						<th>Date</th>
						<th>Status</th>
						<th><?=translate('action')?></th>
					</tr>
				</thead>
				<tbody>
				<?php
				$count = 1;
				if (isset($requests) && count($requests)):
					foreach ($requests as $row):
						$studentData = json_decode($row['request_data'], true);
						$statusBadge = '';
						switch ($row['status']) {
							case 'pending': $statusBadge = '<span class="label label-warning">Pending</span>'; break;
							case 'approved': $statusBadge = '<span class="label label-success">Approved</span>'; break;
							case 'rejected': $statusBadge = '<span class="label label-danger">Rejected</span>'; break;
						}
				?>
				<tr>
					<td><?=$count++?></td>
					<td><?=$studentData['first_name'] . ' ' . $studentData['last_name']?></td>
					<td><?=$row['class_name']?></td>
					<td><?=$row['section_name']?></td>
					<td><?=$row['teacher_name']?></td>
					<td><?=_d($row['created_at'])?></td>
					<td><?=$statusBadge?></td>
					<td>
						<a href="<?=base_url('admission_request/view/' . $row['id'])?>" class="btn btn-default btn-circle icon">
							<i class="fas fa-eye"></i>
						</a>
						<?php if ($row['status'] == 'pending'): ?>
							<?php if (get_permission('admission_approval', 'is_add')): ?>
							<a href="<?=base_url('admission_request/approve/' . $row['id'])?>" class="btn btn-success btn-circle icon"
							   onclick="return confirm('Are you sure you want to approve this admission request?')">
								<i class="fas fa-check"></i>
							</a>
							<a href="javascript:void(0);" class="btn btn-danger btn-circle icon" onclick="rejectRequest(<?=$row['id']?>)">
								<i class="fas fa-times"></i>
							</a>
							<?php endif; ?>
							<?php if (get_permission('admission_request', 'is_delete')): ?>
								<?php echo btn_delete('admission_request/delete_request/' . $row['id']);?>
							<?php endif; ?>
						<?php endif; ?>
					</td>
				</tr>
				<?php endforeach; else: ?>
				<tr><td colspan="8"><h5 class="text-danger text-center"><?=translate('no_information_available')?></h5></td></tr>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</section>

<!-- Reject Modal -->
<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="rejectModal">
	<section class="panel">
		<?php echo form_open('', array('id' => 'rejectForm'));?>
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-times-circle"></i> Reject Admission Request</h4>
			</header>
			<div class="panel-body">
				<div class="form-group">
					<label class="control-label">Reason for Rejection <span class="required">*</span></label>
					<textarea name="review_remarks" class="form-control" rows="4" required placeholder="Enter reason for rejecting this request..."></textarea>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-danger">
							<i class="fas fa-times"></i> Reject Request
						</button>
						<button class="btn btn-default modal-dismiss"><?=translate('cancel')?></button>
					</div>
				</div>
			</footer>
		<?php echo form_close();?>
	</section>
</div>

<script type="text/javascript">
	function rejectRequest(id) {
		$('#rejectForm').attr('action', base_url + 'admission_request/reject/' + id);
		mfp_modal('#rejectModal');
	}
</script>
