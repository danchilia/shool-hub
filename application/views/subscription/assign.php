<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-link"></i> Assign Subscription Plan to Branch</h4>
			</header>
			<?php echo form_open($this->uri->uri_string());?>
				<div class="panel-body">
					<div class="form-group">
						<label class="control-label"><?=translate('branch')?> <span class="required">*</span></label>
						<select name="branch_id" class="form-control" data-plugin-selectTwo data-width="100%">
							<option value=""><?=translate('select')?></option>
							<?php foreach ($branches as $b): ?>
							<option value="<?=$b['id']?>"><?=$b['name']?> - <?=$b['school_name']?></option>
							<?php endforeach; ?>
						</select>
						<span class="error"><?=form_error('branch_id')?></span>
					</div>
					<div class="form-group">
						<label class="control-label">Subscription Plan <span class="required">*</span></label>
						<select name="plan_id" id="plan_select" class="form-control" data-plugin-selectTwo data-width="100%">
							<option value=""><?=translate('select')?></option>
							<?php foreach ($plans as $p): ?>
							<option value="<?=$p['id']?>" data-monthly="<?=$p['monthly_price']?>" data-yearly="<?=$p['yearly_price']?>"><?=$p['name']?> (<?=$p['max_students'] == 0 ? 'Unlimited' : $p['max_students']?> students, KES <?=number_format($p['monthly_price'])?>/mo)</option>
							<?php endforeach; ?>
						</select>
						<span class="error"><?=form_error('plan_id')?></span>
					</div>
					<div class="form-group">
						<label class="control-label">Billing Cycle <span class="required">*</span></label>
						<select name="billing_cycle" id="billing_cycle" class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity">
							<option value="monthly">Monthly</option>
							<option value="yearly">Yearly (+ FREE computer while subscribed)</option>
						</select>
						<span class="error"><?=form_error('billing_cycle')?></span>
					</div>
					<div id="price_display" class="alert alert-info" style="display:none; padding:15px;">
						<div id="price_monthly" style="display:none;">
							<strong>Monthly Payment:</strong> KES <span id="monthly_amount">0</span>/month
						</div>
						<div id="price_yearly" style="display:none;">
							<strong>Yearly Payment:</strong> KES <span id="yearly_amount">0</span>/year
							<br><br>
							<div class="alert alert-success" style="margin:10px 0 0; padding:10px;">
								<i class="fas fa-laptop"></i> <strong>FREE COMPUTER INCLUDED!</strong>
								<br>School receives a computer for use while subscription is active.
								<br><small>Computer remains property of DCK Solutions. Returned if subscription is cancelled.</small>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label">Notes</label>
						<textarea name="notes" class="form-control" rows="2" placeholder="e.g. Computer serial: HP-2026-001, Delivered on: 15/06/2026"></textarea>
					</div>
				</div>
				<div class="panel-footer">
					<button class="btn btn-default pull-right" type="submit" name="save" value="1">
						<i class="fas fa-check"></i> Assign Plan
					</button>
				</div>
			<?php echo form_close();?>
		</section>
	</div>
</div>

<script type="text/javascript">
function updatePrice() {
	var plan = $('#plan_select option:selected');
	var cycle = $('#billing_cycle').val();
	var monthly = plan.data('monthly');
	var yearly = plan.data('yearly');
	if (monthly > 0) {
		$('#price_display').show();
		if (cycle == 'monthly') {
			$('#monthly_amount').text(Number(monthly).toLocaleString());
			$('#price_monthly').show();
			$('#price_yearly').hide();
		} else {
			$('#yearly_amount').text(Number(yearly).toLocaleString());
			$('#price_yearly').show();
			$('#price_monthly').hide();
		}
	} else {
		$('#price_display').hide();
	}
}
$('#plan_select, #billing_cycle').on('change', updatePrice);
</script>
