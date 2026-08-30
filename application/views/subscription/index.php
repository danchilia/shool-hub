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

<?php if (!empty($pending_payments)): ?>
<section class="panel" style="margin-top:20px;border-top:3px solid #f39c12;">
  <header class="panel-heading" style="background:#fffbf0;">
    <h4 class="panel-title" style="color:#d68910;">
      <i class="fas fa-clock"></i>
      Payments Awaiting Activation (<?= count($pending_payments) ?>)
      <small style="font-weight:400;font-size:.8rem;color:#777;margin-left:8px">M-Pesa received. Click Activate to enable school access.</small>
    </h4>
  </header>
  <div class="panel-body">
    <div class="table-responsive">
      <table class="table table-bordered table-condensed">
        <thead>
          <tr>
            <th>#</th><th>School</th><th>Plan</th><th>Billing</th><th>Amount (KES)</th><th>Paid At</th><th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php $n = 1; foreach ($pending_payments as $pp): ?>
          <tr>
            <td><?= $n++ ?></td>
            <td>
              <strong><?= htmlspecialchars($pp['school_name']) ?></strong>
              <small class="text-muted d-block"><?= htmlspecialchars($pp['branch_name']) ?></small>
            </td>
            <td><?= htmlspecialchars($pp['plan_name'] ?: 'N/A') ?></td>
            <td><?= ucfirst($pp['billing_cycle'] ?: '') ?></td>
            <td><?= number_format($pp['amount']) ?></td>
            <td><?= $pp['created_at'] ?></td>
            <td>
              <a href="<?= base_url('subscription/confirm_payment/' . $pp['id']) ?>"
                 class="btn btn-warning btn-sm"
                 onclick="return confirm('Activate this school? They will get immediate access.')">
                <i class="fas fa-check"></i> Activate
              </a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if (!empty($unsubscribed_branches)): ?>
<section class="panel" style="margin-top:20px;border-top:3px solid #e74c3c;">
  <header class="panel-heading" style="background:#fdf2f2;">
    <h4 class="panel-title" style="color:#c0392b;">
      <i class="fas fa-exclamation-triangle"></i>
      Schools Without Subscription (<?= count($unsubscribed_branches) ?>)
      <small style="font-weight:400;font-size:.8rem;color:#777;margin-left:8px">These schools have no plan assigned. Activate them manually or wait for them to pay.</small>
    </h4>
  </header>
  <div class="panel-body">
    <div class="table-responsive">
      <table class="table table-bordered table-condensed">
        <thead>
          <tr>
            <th>#</th>
            <th>School Name</th>
            <th>Branch</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php $n = 1; foreach ($unsubscribed_branches as $b): ?>
          <tr>
            <td><?= $n++ ?></td>
            <td><strong><?= htmlspecialchars($b['school_name']) ?></strong></td>
            <td><?= htmlspecialchars($b['branch_name']) ?></td>
            <td>
              <button class="btn btn-success btn-sm" onclick="manualActivate(<?= $b['id'] ?>, '<?= htmlspecialchars(addslashes($b['school_name'])) ?>')">
                <i class="fas fa-check"></i> Activate Manually
              </button>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- Manual Activate Modal -->
<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="manualActivateModal">
  <section class="panel">
    <?php echo form_open('subscription/manual_activate', array('id' => 'manualActivateForm')); ?>
      <header class="panel-heading">
        <h4 class="panel-title"><i class="fas fa-check-circle"></i> Manually Activate: <span id="maSchoolName"></span></h4>
      </header>
      <div class="panel-body">
        <input type="hidden" name="branch_id" id="maBranchId">
        <div class="form-group">
          <label class="control-label">Subscription Plan <span class="required">*</span></label>
          <select name="plan_id" class="form-control" required>
            <option value="">— select plan —</option>
            <?php foreach ($plans as $p): ?>
            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?> — KES <?= number_format($p['monthly_price']) ?>/mo</option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label class="control-label">Billing Cycle <span class="required">*</span></label>
          <select name="billing_cycle" class="form-control" required>
            <option value="monthly">Monthly</option>
            <option value="yearly">Yearly</option>
          </select>
        </div>
        <p class="text-muted" style="font-size:.82rem">
          <i class="fas fa-info-circle"></i>
          This creates an active subscription from today. The school will immediately have full access.
        </p>
      </div>
      <footer class="panel-footer">
        <div class="row">
          <div class="col-md-12 text-right">
            <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Activate Now</button>
            <button type="button" class="btn btn-default modal-dismiss"><?= translate('cancel') ?></button>
          </div>
        </div>
      </footer>
    <?php echo form_close(); ?>
  </section>
</div>

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
	function manualActivate(branchId, schoolName) {
		document.getElementById('maBranchId').value = branchId;
		document.getElementById('maSchoolName').textContent = schoolName;
		mfp_modal('#manualActivateModal');
	}
</script>
