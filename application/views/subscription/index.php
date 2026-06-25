<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"><i class="fas fa-building"></i> Branch Subscriptions
			<div class="pull-right">
				<a href="<?=base_url('subscription/payment_settings')?>" class="btn btn-default btn-sm">
					<i class="fas fa-cog"></i> Payment Settings
				</a>
				<a href="<?=base_url('subscription/assign')?>" class="btn btn-default btn-sm">
					<i class="fas fa-plus"></i> Assign Plan
				</a>
			</div>
		</h4>
	</header>
	<div class="panel-body">
		<div class="table-responsive">
			<table class="table table-bordered table-hover table-condensed table-export">
				<thead>
					<tr>
						<th><?=translate('sl')?></th>
						<th>School Name</th>
						<th>Plan</th>
						<th>Billing</th>
						<th>End Date</th>
						<th>Days Left</th>
						<th>Status</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
				<?php $c = 1; if (count($subscriptions)): foreach ($subscriptions as $row):
					$statusClass = '';
					switch ($row['status']) {
						case 'active': $statusClass = 'label-success'; break;
						case 'trial': $statusClass = 'label-info'; break;
						case 'expired': $statusClass = 'label-danger'; break;
						case 'cancelled': $statusClass = 'label-default'; break;
					}
					$daysLeft = max(0, floor((strtotime($row['end_date']) - time()) / 86400));
					$daysColor = $daysLeft <= 7 ? 'color:#d9534f; font-weight:bold;' : ($daysLeft <= 14 ? 'color:#f0ad4e; font-weight:bold;' : 'color:#5cb85c;');
				?>
					<tr>
						<td><?=$c++?></td>
						<td>
							<strong><?=$row['school_name']?></strong>
							<br><small class="text-muted"><?=$row['branch_name']?></small>
						</td>
						<td><?=$row['plan_name']?></td>
						<td><?=ucfirst($row['billing_cycle'])?></td>
						<td><?=_d($row['end_date'])?></td>
						<td style="<?=$daysColor?> font-size:16px;"><?=$daysLeft?> days</td>
						<td><span class="label <?=$statusClass?>"><?=ucfirst($row['status'])?></span></td>
						<td>
							<?php if ($row['status'] == 'expired' || $row['status'] == 'cancelled'): ?>
								<a href="<?=base_url('subscription/activate/' . $row['id'])?>" class="btn btn-success btn-sm" onclick="return confirm('Activate this school?')">
									<i class="fas fa-check"></i> Activate
								</a>
							<?php else: ?>
								<a href="<?=base_url('subscription/deactivate/' . $row['id'])?>" class="btn btn-danger btn-sm" onclick="return confirm('Deactivate this school? They will lose access.')">
									<i class="fas fa-ban"></i> Deactivate
								</a>
							<?php endif; ?>
							<a href="javascript:void(0);" class="btn btn-default btn-sm" onclick="extendSub(<?=$row['id']?>)">
								<i class="fas fa-calendar-plus"></i> Extend
							</a>
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

<!-- Extend Modal -->
<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="extendModal">
	<section class="panel">
		<?php echo form_open('', array('id' => 'extendForm')); ?>
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-calendar-plus"></i> Extend Subscription</h4>
			</header>
			<div class="panel-body">
				<div class="form-group">
					<label class="control-label">Extend by (days) <span class="required">*</span></label>
					<select name="extend_days" class="form-control">
						<option value="30">30 days (1 month)</option>
						<option value="60">60 days (2 months)</option>
						<option value="90">90 days (3 months)</option>
						<option value="180">180 days (6 months)</option>
						<option value="365">365 days (1 year)</option>
					</select>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-default"><i class="fas fa-check"></i> Extend</button>
						<button class="btn btn-default modal-dismiss"><?=translate('cancel')?></button>
					</div>
				</div>
			</footer>
		<?php echo form_close();?>
	</section>
</div>

<script type="text/javascript">
	function extendSub(id) {
		$('#extendForm').attr('action', base_url + 'subscription/extend/' + id);
		mfp_modal('#extendModal');
	}
</script>
