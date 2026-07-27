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
    <div class="row g-3">
        <?php if (get_permission('biometric_devices', 'is_add')): ?>
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="far fa-edit me-2"></i>Register Biometric Device</h6>
                </div>
                <div class="card-body">
                    <?php echo form_open($this->uri->uri_string()); ?>
                    <div class="mb-3">
                        <label class="form-label">Device Name <span class="text-danger">*</span><?php echo help_tip('Friendly name for this scanner. Example: Main Gate Scanner, Staff Room Scanner'); ?></label>
                        <input type="text" class="form-control" name="device_name" value="<?php echo set_value('device_name'); ?>">
                        <span class="error text-danger small"><?php echo form_error('device_name'); ?></span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Location<?php echo help_tip('Where the device is physically placed. Example: Main Gate, Staff Room, Block A Entrance'); ?></label>
                        <input type="text" class="form-control" name="location" value="<?php echo set_value('location'); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Device Serial Number</label>
                        <input type="text" class="form-control" name="device_serial" value="<?php echo set_value('device_serial'); ?>" placeholder="Optional - for your reference">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Used For</label>
                        <select name="device_type" class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity">
                            <option value="both">Both Students &amp; Staff</option>
                            <option value="student">Students Only</option>
                            <option value="staff">Staff Only</option>
                        </select>
                    </div>
                    <div class="text-end">
                        <button class="btn btn-primary" type="submit" name="save" value="1">
                            <i class="fas fa-plus-circle me-1"></i>Register Device
                        </button>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="col-md-<?php echo get_permission('biometric_devices', 'is_add') ? '7' : '12'; ?>">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-fingerprint me-2"></i>Registered Devices</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Device Name</th>
                                    <th>Location</th>
                                    <th>Used For</th>
                                    <th>Last Seen</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $c = 1; if (count($devices)): foreach ($devices as $d): ?>
                                <tr>
                                    <td><?php echo $c++; ?></td>
                                    <td><strong><?php echo html_escape($d['device_name']); ?></strong></td>
                                    <td><?php echo html_escape($d['location']); ?></td>
                                    <td><?php echo ucfirst($d['device_type']); ?></td>
                                    <td><?php echo !empty($d['last_seen']) ? _d($d['last_seen']) : '<span class="text-danger">Never connected</span>'; ?></td>
                                    <td>
                                        <?php if ($d['is_active']): ?>
                                        <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-nowrap">
                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                            onclick="showToken('<?php echo html_escape($d['device_name']); ?>','<?php echo html_escape($d['api_token']); ?>','<?php echo $push_url; ?>')"
                                            data-bs-toggle="tooltip" title="View API Token">
                                            <i class="fas fa-key"></i>
                                        </button>
                                        <a href="<?php echo base_url('biometric/regenerate_token/' . $d['id']); ?>"
                                            class="btn btn-sm btn-warning"
                                            onclick="return confirm('Generate a new token? The old token will stop working immediately.')"
                                            data-bs-toggle="tooltip" title="Regenerate Token">
                                            <i class="fas fa-sync"></i>
                                        </a>
                                        <?php if (get_permission('biometric_devices', 'is_delete')): ?>
                                        <?php echo btn_delete('biometric/device_delete/' . $d['id']); ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr><td colspan="7" class="text-center text-danger"><?php echo translate('no_information_available'); ?></td></tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="alert alert-info mt-3">
        <i class="fas fa-info-circle me-1"></i><strong>How to connect a fingerprint device:</strong>
        <ol class="mb-0 mt-1">
            <li>Register the device above and click the key icon to get its <strong>API Token</strong> and <strong>Push URL</strong>.</li>
            <li>Configure your fingerprint device (e.g. ZKTeco) to push attendance data to the Push URL with the token.</li>
            <li>Go to <strong>Biometric &gt; ID Mapping</strong> to link each student/staff's fingerprint ID to their school record.</li>
            <li>If your device cannot push automatically, export scans as CSV and use <strong>Biometric &gt; Import CSV</strong> instead.</li>
        </ol>
    </div>
</div>

<!-- Token Modal (Magnific Popup inline) -->
<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="tokenModal">
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0" id="tokenDeviceName"></h6>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">API Push URL <small class="text-muted">(configure this on your device)</small></label>
                <input type="text" class="form-control" id="tokenPushUrl" readonly onclick="this.select()">
            </div>
            <div class="mb-3">
                <label class="form-label">API Token <small class="text-muted">(device authentication key)</small></label>
                <input type="text" class="form-control" id="tokenValue" readonly onclick="this.select()">
            </div>
            <div class="alert alert-warning mb-0">
                <small>Send a POST request with JSON body: <code>{"token":"...", "biometric_id":"23", "scan_time":"2026-06-29 07:45:00", "scan_type":"in"}</code></small>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end">
            <button class="btn btn-outline-secondary modal-dismiss"><?php echo translate('cancel'); ?></button>
        </div>
    </div>
</div>

<script>
function showToken(name, token, pushUrl) {
    $('#tokenDeviceName').text(name + ' - API Credentials');
    $('#tokenValue').val(token);
    $('#tokenPushUrl').val(pushUrl);
    mfp_modal('#tokenModal');
}
</script>
