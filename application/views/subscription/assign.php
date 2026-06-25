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
						<select name="plan_id" class="form-control" data-plugin-selectTwo data-width="100%">
							<option value=""><?=translate('select')?></option>
							<?php foreach ($plans as $p): ?>
							<option value="<?=$p['id']?>"><?=$p['name']?> (KES <?=number_format($p['monthly_price'])?>/ mo)</option>
							<?php endforeach; ?>
						</select>
						<span class="error"><?=form_error('plan_id')?></span>
					</div>
					<div class="form-group">
						<label class="control-label">Billing Cycle <span class="required">*</span></label>
						<select name="billing_cycle" class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity">
							<option value="monthly">Monthly</option>
							<option value="yearly">Yearly</option>
						</select>
						<span class="error"><?=form_error('billing_cycle')?></span>
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
