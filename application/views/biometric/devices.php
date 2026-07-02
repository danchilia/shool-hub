<div class="row">
<?php if (get_permission('biometric_devices', 'is_add')): ?>
	<div class="col-md-5">
		<section class="panel">
			<header class="panel-heading"><h4 class="panel-title"><i class="far fa-edit"></i> Register Biometric Device</h4></header>
			<?php echo form_open($this->uri->uri_string()); ?>
				<div class="panel-body">
					<div class="form-group">
						<label>Device Name <span class="required">*</span><?=help_tip('Friendly name for this scanner. Example: Main Gate Scanner, Staff Room Scanner')?></label>
						<input type="text" class="form-control" name="device_name" value="<?=set_value('device_name')?>" />
						<span class="error"><?=form_error('device_name')?></span>
					</div>
					<div class="form-group">
						<label>Location<?=help_tip('Where the device is physically placed. Example: Main Gate, Staff Room, Block A Entrance')?></label>
						<input type="text" class="form-control" name="location" value="<?=set_value('location')?>" />
					</div>
					<div class="form-group">
						<label>Device Serial Number</label>
						<input type="text" class="form-control" name="device_serial" value="<?=set_value('device_serial')?>" placeholder="Optional - for your reference" />
					</div>
					<div class="form-group">
						<label>Used For</label>
						<select name="device_type" class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity">
							<option value="both">Both Students & Staff</option>
							<option value="student">Students Only</option>
							<option value="staff">Staff Only</option>
						</select>
					</div>
				</div>
				<div class="panel-footer">
					<button class="btn btn-default pull-right" type="submit" name="save" value="1"><i class="fas fa-plus-circle"></i> Register Device</button>
				</div>
			<?php echo form_close(); ?>
		</section>
	</div>
<?php endif; ?>
	<div class="col-md-<?=get_permission('biometric_devices', 'is_add') ? '7' : '12'?>">
		<section class="panel">
			<header class="panel-heading"><h4 class="panel-title"><i class="fas fa-fingerprint"></i> Registered Devices</h4></header>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-condensed mb-none">
						<thead>
							<tr>
								<th>#</th><th>Device Name</th><th>Location</th><th>Used For</th><th>Last Seen</th><th>Status</th><th>Action</th>
							</tr>
						</thead>
						<tbody>
						<?php $c = 1; if (count($devices)): foreach ($devices as $d): ?>
							<tr>
								<td><?=$c++?></td>
								<td><strong><?=$d['device_name']?></strong></td>
								<td><?=$d['location']?></td>
								<td><?=ucfirst($d['device_type'])?></td>
								<td><?=!empty($d['last_seen']) ? _d($d['last_seen']) : '<span class="text-danger">Never connected</span>'?></td>
								<td><?=$d['is_active'] ? '<span class="label label-success">Active</span>' : '<span class="label label-default">Inactive</span>'?></td>
								<td>
									<a href="javascript:void(0);" class="btn btn-default btn-circle icon" onclick="showToken('<?=$d['device_name']?>','<?=$d['api_token']?>','<?=$push_url?>')" title="View API Token"><i class="fas fa-key"></i></a>
									<a href="<?=base_url('biometric/regenerate_token/' . $d['id'])?>" class="btn btn-warning btn-circle icon" onclick="return confirm('Generate a new token? The old token will stop working immediately.')" title="Regenerate Token"><i class="fas fa-sync"></i></a>
									<?php if (get_permission('biometric_devices', 'is_delete')): ?>
									<a class="btn btn-danger icon btn-circle" onclick="confirm_modal('<?=base_url('biometric/device_delete/' . $d['id'])?>')"><i class="fas fa-trash-alt"></i></a>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; else: ?>
							<tr><td colspan="7"><h5 class="text-danger text-center"><?=translate('no_information_available')?></h5></td></tr>
						<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</section>
	</div>
</div>

<div class="alert alert-info">
	<i class="fas fa-info-circle"></i> <strong>How to connect a fingerprint device:</strong>
	<ol>
		<li>Register the device above and click the key icon to get its <strong>API Token</strong> and <strong>Push URL</strong></li>
		<li>Configure your fingerprint device (e.g. ZKTeco) to push attendance data to the Push URL with the token</li>
		<li>Go to <strong>Biometric > ID Mapping</strong> to link each student/staff's fingerprint ID to their school record</li>
		<li>If your device cannot push automatically, export scans as CSV and use <strong>Biometric > Import CSV</strong> instead</li>
	</ol>
</div>

<!-- Token Modal -->
<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="tokenModal">
	<section class="panel">
		<header class="panel-heading"><h4 class="panel-title" id="tokenDeviceName"></h4></header>
		<div class="panel-body">
			<div class="form-group">
				<label>API Push URL (configure this on your device)</label>
				<input type="text" class="form-control" id="tokenPushUrl" readonly onclick="this.select()">
			</div>
			<div class="form-group">
				<label>API Token (device authentication key)</label>
				<input type="text" class="form-control" id="tokenValue" readonly onclick="this.select()">
			</div>
			<div class="alert alert-warning" style="margin-bottom:0;">
				<small>Send a POST request with JSON body: <code>{"token":"...", "biometric_id":"23", "scan_time":"2026-06-29 07:45:00", "scan_type":"in"}</code></small>
			</div>
		</div>
		<footer class="panel-footer text-right">
			<button class="btn btn-default modal-dismiss"><?=translate('cancel')?></button>
		</footer>
	</section>
</div>

<script>
function showToken(name, token, pushUrl) {
	$('#tokenDeviceName').text(name + ' - API Credentials');
	$('#tokenValue').val(token);
	$('#tokenPushUrl').val(pushUrl);
	mfp_modal('#tokenModal');
}
</script>
