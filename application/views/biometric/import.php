<div class="row">
	<div class="col-md-12">
		<section class="panel">
		<?php echo form_open_multipart($this->uri->uri_string()); ?>
			<header class="panel-heading"><h4 class="panel-title"><i class="fas fa-file-csv"></i> Import Biometric Scan Data (CSV)</h4></header>
			<div class="panel-body">
				<div class="alert alert-info">
					<i class="fas fa-info-circle"></i> <strong>Use this if your fingerprint device cannot push data automatically.</strong>
					Most devices (ZKTeco, etc.) let you export attendance logs as Excel/CSV from their desktop software.
					Save that file as CSV with these exact column headers, then upload it here.
				</div>
				<table class="table table-bordered table-condensed">
					<thead><tr><th>BiometricID</th><th>ScanTime</th><th>Type</th></tr></thead>
					<tbody>
						<tr><td>23</td><td>2026-06-29 07:45:00</td><td>in</td></tr>
						<tr><td>45</td><td>2026-06-29 07:52:00</td><td>in</td></tr>
					</tbody>
				</table>
				<p><strong>Column meaning:</strong></p>
				<ul>
					<li><strong>BiometricID</strong> - The fingerprint ID number from the device (must match what you set in Biometric &gt; ID Mapping)</li>
					<li><strong>ScanTime</strong> - Date and time of the scan, format: YYYY-MM-DD HH:MM:SS</li>
					<li><strong>Type</strong> - "in" or "out" (optional, can leave blank)</li>
				</ul>
				<div class="form-group mt-md">
					<label>Select CSV File <span class="required">*</span></label>
					<input type="file" name="userfile" class="dropify" data-height="140" data-allowed-file-extensions="csv" />
				</div>
			</div>
			<div class="panel-footer">
				<button type="submit" name="save" value="1" class="btn btn-default pull-right"><i class="fas fa-upload"></i> Import & Mark Attendance</button>
			</div>
		<?php echo form_close(); ?>
		</section>
	</div>
</div>
