<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-money-bill-wave"></i> Subscription Payment Settings</h4>
			</header>
			<?php echo form_open($this->uri->uri_string());?>
				<div class="panel-body">
					<div class="alert alert-info">
						<i class="fas fa-info-circle"></i> This information is shown to school admins on their dashboard when their subscription is expiring or expired. They use it to pay you.
					</div>
					<div class="form-group">
						<label class="control-label">M-Pesa Paybill Number <span class="required">*</span></label>
						<input type="text" class="form-control" name="paybill_number" value="<?=isset($settings->paybill_number) ? $settings->paybill_number : ''?>" placeholder="e.g. 522522" style="font-size:20px; font-weight:bold;">
					</div>
					<div class="form-group">
						<label class="control-label">Payment Instructions</label>
						<textarea class="form-control" name="account_info" rows="5" placeholder="Instructions shown to schools..."><?=isset($settings->account_info) ? $settings->account_info : ''?></textarea>
						<small class="text-muted">The Account Name will automatically show as the school's name. Write any extra instructions here.</small>
					</div>

					<hr>
					<h5><strong>Preview (what schools see on their dashboard):</strong></h5>
					<div class="alert alert-warning" style="border-left: 5px solid #f0ad4e; padding: 15px;">
						<h5 style="margin-top:0;"><i class="fas fa-clock"></i> Subscription Expiring Soon</h5>
						<p>Your plan expires in <strong>X</strong> days.</p>
						<p><strong>Renew via M-Pesa:</strong></p>
						<table>
							<tr><td style="padding:3px 15px 3px 0;"><strong>Paybill:</strong></td><td><strong style="font-size:18px;"><?=isset($settings->paybill_number) ? $settings->paybill_number : '______'?></strong></td></tr>
							<tr><td style="padding:3px 15px 3px 0;"><strong>Account Name:</strong></td><td><strong>(School's Name)</strong></td></tr>
						</table>
						<p class="mt-sm"><small>After payment, contact DCK Solutions with your M-Pesa code to reactivate.</small></p>
					</div>
				</div>
				<div class="panel-footer">
					<button class="btn btn-default pull-right" type="submit">
						<i class="fas fa-save"></i> Save Settings
					</button>
				</div>
			<?php echo form_close();?>
		</section>
	</div>
</div>
