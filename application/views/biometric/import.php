<style>
@media (prefers-color-scheme:dark) {
    .card        { background:#2b2b3a; border-color:#3a3a50; }
    .card-header { background:#232333; border-color:#3a3a50; }
    .table-light { --bs-table-bg:#232333; }
}
:root[data-theme="dark"]  .card        { background:#2b2b3a; border-color:#3a3a50; }
:root[data-theme="dark"]  .card-header { background:#232333; border-color:#3a3a50; }
:root[data-theme="dark"]  .table-light { --bs-table-bg:#232333; }
:root[data-theme="light"] .card        { background:#fff;    border-color:#dee2e6; }
:root[data-theme="light"] .card-header { background:#f8f9fa; border-color:#dee2e6; }
:root[data-theme="light"] .table-light { --bs-table-bg:#f8f9fa; }
</style>

<div class="container-fluid">
    <div class="card">
        <?php echo form_open_multipart($this->uri->uri_string()); ?>
        <div class="card-header">
            <h6 class="mb-0"><i class="fas fa-file-csv me-2"></i>Import Biometric Scan Data (CSV)</h6>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-1"></i><strong>Use this if your fingerprint device cannot push data automatically.</strong>
                Most devices (ZKTeco, etc.) let you export attendance logs as Excel/CSV from their desktop software.
                Save that file as CSV with these exact column headers, then upload it here.
            </div>

            <div class="table-responsive mb-3">
                <table class="table table-bordered table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>BiometricID</th>
                            <th>ScanTime</th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>23</td><td>2026-06-29 07:45:00</td><td>in</td></tr>
                        <tr><td>45</td><td>2026-06-29 07:52:00</td><td>in</td></tr>
                    </tbody>
                </table>
            </div>

            <p class="mb-2"><strong>Column meaning:</strong></p>
            <ul class="mb-3">
                <li><strong>BiometricID</strong> — The fingerprint ID number from the device (must match what you set in Biometric &gt; ID Mapping)</li>
                <li><strong>ScanTime</strong> — Date and time of the scan, format: YYYY-MM-DD HH:MM:SS</li>
                <li><strong>Type</strong> — "in" or "out" (optional, can leave blank)</li>
            </ul>

            <div class="mb-3">
                <label class="form-label">Select CSV File <span class="text-danger">*</span></label>
                <input type="file" name="userfile" class="dropify" data-height="140" data-allowed-file-extensions="csv">
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end">
            <button type="submit" name="save" value="1" class="btn btn-primary">
                <i class="fas fa-upload me-1"></i>Import &amp; Mark Attendance
            </button>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
