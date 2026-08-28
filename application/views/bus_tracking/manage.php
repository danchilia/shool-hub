<style>
@media(prefers-color-scheme:dark)   { .container-fluid .bg-light { background:#1e2030 !important; color:#cbd5e1; } }
:root[data-theme="dark"]  .container-fluid .bg-light { background:#1e2030 !important; color:#cbd5e1; }
:root[data-theme="light"] .container-fluid .bg-light { background:#f8fafc !important; }
</style>

<div class="content-header">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h4 class="mb-0"><i class="fas fa-bus me-2 text-primary"></i>Manage School Buses</h4>
        <div class="d-flex gap-2">
            <a href="<?php echo base_url('bus_tracking'); ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-map me-1"></i>Live Map</a>
            <button class="btn btn-sm btn-primary" onclick="resetBusForm(); mfp_modal('#modal-bus')"><i class="fas fa-plus me-1"></i>Add Bus</button>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0" id="buses-table">
                <thead class="table-light">
                    <tr><th>#</th><th>Bus Name</th><th>Reg No.</th><th>Capacity</th><th>Driver</th><th>Driver Phone</th><th>Status</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <?php if (empty($buses)): ?>
                    <tr><td colspan="8" class="text-center py-4 text-muted">No buses registered yet.</td></tr>
                    <?php else: foreach ($buses as $i => $bus): ?>
                    <tr>
                        <td><?php echo $i+1; ?></td>
                        <td><strong><?php echo html_escape($bus->bus_name); ?></strong></td>
                        <td><?php echo html_escape($bus->reg_number); ?></td>
                        <td><?php echo $bus->capacity ?: '—'; ?></td>
                        <td><?php echo html_escape($bus->driver_name ?: '—'); ?></td>
                        <td><?php echo html_escape($bus->driver_phone ?: '—'); ?></td>
                        <td>
                            <?php if ($bus->is_active): ?>
                            <span class="badge" style="background:#d1fae5;color:#065f46;">Active</span>
                            <?php else: ?>
                            <span class="badge" style="background:#f3f4f6;color:#6b7280;">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="btn btn-xs btn-outline-warning" onclick="editBus(this)" title="Edit Bus"
                                data-json="<?php echo htmlspecialchars(json_encode([
                                    'id'               => (int)$bus->id,
                                    'bus_name'         => $bus->bus_name,
                                    'reg_number'       => $bus->reg_number,
                                    'capacity'         => $bus->capacity,
                                    'driver_name'      => $bus->driver_name,
                                    'driver_phone'     => $bus->driver_phone,
                                    'route_description'=> $bus->route_description,
                                    'is_active'        => (int)$bus->is_active,
                                ]), ENT_QUOTES); ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-xs btn-outline-info" onclick="showGpsUrl(<?php echo $bus->id; ?>, '<?php echo html_escape($bus->bus_name); ?>', '<?php echo html_escape($bus->gps_token ?? ''); ?>')" title="GPS Setup URL">
                                <i class="fas fa-satellite-dish"></i>
                            </button>
                            <?php echo btn_delete('bus_tracking/delete_bus/'.$bus->id); ?>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h6 class="mb-0"><i class="fas fa-satellite-dish me-2"></i>GPS Device Integration</h6>
        </div>
        <div class="card-body">
            <p class="text-muted small mb-3">
                Each bus has a unique private URL. Click the <i class="fas fa-satellite-dish text-info"></i> button next to a bus to see its specific URL.
                GPS devices and driver phone apps send location updates to that URL, no login required.
            </p>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="border rounded p-3 bg-light">
                        <div class="fw-semibold small mb-2"><i class="fas fa-mobile-alt me-1 text-primary"></i> Driver Phone App (recommended)</div>
                        <p class="text-muted small mb-2">Share the bus URL with the driver. They open it in any browser or GPS app that supports HTTP callbacks. Recommended interval: every 30 seconds.</p>
                        <p class="text-muted small mb-0">Apps that work: <strong>GPS Logger (Android)</strong>, <strong>OwnTracks</strong>, or any app that can POST to a custom URL.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border rounded p-3 bg-light">
                        <div class="fw-semibold small mb-2"><i class="fas fa-microchip me-1 text-success"></i> Hardware GPS Tracker</div>
                        <p class="text-muted small mb-2">Configure the tracker's server URL in its admin panel. Use GET method. Parameters: <code>token</code>, <code>lat</code>, <code>lng</code>, <code>speed</code>.</p>
                        <p class="text-muted small mb-0">Compatible with: <strong>Concox</strong>, <strong>Teltonika</strong>, <strong>Queclink</strong>, and most trackers with HTTP mode.</p>
                    </div>
                </div>
            </div>
            <div class="alert alert-info small mt-3 mb-0">
                <i class="fas fa-lock me-1"></i>
                Each URL contains a private token unique to that bus. Keep it confidential. Anyone with the URL can update that bus's location. If a token is compromised, delete the bus and re-add it to get a new token.
            </div>
        </div>
    </div>
</div>

<!-- GPS URL Modal -->
<div id="modal-gps-url" class="mfp-hide">
    <div class="card mb-0" style="min-width:520px;max-width:620px;">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0"><i class="fas fa-satellite-dish me-2"></i>GPS Setup — <span id="gps-bus-name"></span></h5>
            <button type="button" class="btn-close ms-2" onclick="$.magnificPopup.close()"></button>
        </div>
        <div class="card-body">
            <p class="text-muted small mb-3">Send this URL to your driver or configure it on the GPS hardware tracker. It is unique to this bus.</p>

            <label class="form-label fw-semibold small">Bus GPS URL</label>
            <div class="input-group mb-3">
                <input type="text" id="gps-url-field" class="form-control form-control-sm font-monospace" readonly>
                <button class="btn btn-outline-secondary btn-sm" onclick="copyGpsUrl()" title="Copy"><i class="fas fa-copy"></i></button>
            </div>

            <div class="border rounded p-3 mb-3 bg-light">
                <div class="fw-semibold small mb-2">URL Parameters</div>
                <table class="table table-sm table-borderless mb-0 small">
                    <tr><td class="fw-semibold text-nowrap" style="width:80px">token</td><td class="text-muted">Required. Already included in the URL above, do not change it.</td></tr>
                    <tr><td class="fw-semibold">lat</td><td class="text-muted">Required. GPS latitude (decimal degrees, e.g. -1.286389)</td></tr>
                    <tr><td class="fw-semibold">lng</td><td class="text-muted">Required. GPS longitude (decimal degrees, e.g. 36.817223)</td></tr>
                    <tr><td class="fw-semibold">speed</td><td class="text-muted">Optional. Speed in km/h</td></tr>
                </table>
            </div>

            <div class="mb-3">
                <div class="fw-semibold small mb-2"><i class="fas fa-mobile-alt me-1 text-primary"></i>GPS Logger App (Android, Free)</div>
                <ol class="small text-muted ps-3 mb-0">
                    <li>Install <strong>GPS Logger</strong> from Google Play Store</li>
                    <li>Open app → Menu → <strong>Logging Details</strong> → Custom URL</li>
                    <li>Paste the URL above into the <strong>URL</strong> field</li>
                    <li>Replace <code>LAT</code> with <code>%LAT</code> and <code>LNG</code> with <code>%LON</code> and <code>SPEED</code> with <code>%SPD</code></li>
                    <li>Set interval to <strong>30 seconds</strong> → Start Logging</li>
                </ol>
            </div>

            <div>
                <div class="fw-semibold small mb-2"><i class="fas fa-microchip me-1 text-success"></i>Hardware GPS Tracker</div>
                <ol class="small text-muted ps-3 mb-0">
                    <li>Log in to your tracker's admin panel or SMS it the server config command</li>
                    <li>Set server type to <strong>HTTP GET</strong></li>
                    <li>Enter the URL above as the reporting endpoint</li>
                    <li>Replace <code>LAT</code> and <code>LNG</code> with the tracker's own location variables (check your tracker's manual)</li>
                    <li>Set reporting interval to <strong>30 seconds</strong></li>
                </ol>
            </div>
        </div>
    </div>
</div>

<script>
var baseUrl = '<?php echo base_url(); ?>';

function showGpsUrl(busId, busName, token) {
    document.getElementById('gps-bus-name').textContent = busName;
    var url = baseUrl + 'bus-api/location?token=' + token + '&lat=LAT&lng=LNG&speed=SPEED';
    document.getElementById('gps-url-field').value = url;
    mfp_modal('#modal-gps-url');
}

function copyGpsUrl() {
    var field = document.getElementById('gps-url-field');
    field.select();
    document.execCommand('copy');
    var btn = field.nextElementSibling;
    btn.innerHTML = '<i class="fas fa-check text-success"></i>';
    setTimeout(function(){ btn.innerHTML = '<i class="fas fa-copy"></i>'; }, 1500);
}

$(function(){ if (!$.fn.DataTable.isDataTable('#buses-table')) { $('#buses-table').DataTable({pageLength:25}); } });

function resetBusForm() {
    document.getElementById('bus-form').reset();
    document.getElementById('bus-edit-id').value = '';
    document.getElementById('bus-modal-title').innerHTML = '<i class="fas fa-plus me-2"></i>Register Bus';
}

function editBus(btn) {
    var d = JSON.parse(btn.getAttribute('data-json'));
    var f = document.getElementById('bus-form');
    f.reset();
    document.getElementById('bus-edit-id').value              = d.id;
    f.querySelector('[name="bus_name"]').value                 = d.bus_name         || '';
    f.querySelector('[name="reg_number"]').value               = d.reg_number       || '';
    f.querySelector('[name="capacity"]').value                 = d.capacity         || '';
    f.querySelector('[name="driver_name"]').value              = d.driver_name      || '';
    f.querySelector('[name="driver_phone"]').value             = d.driver_phone     || '';
    f.querySelector('[name="route_description"]').value        = d.route_description|| '';
    f.querySelector('[name="is_active"]').checked              = d.is_active === 1;
    document.getElementById('bus-modal-title').innerHTML = '<i class="fas fa-edit me-2"></i>Edit Bus';
    mfp_modal('#modal-bus');
}
</script>

<div id="modal-bus" class="mfp-hide">
    <div class="card mb-0" style="min-width:480px; max-width:560px;">
        <div class="card-header d-flex align-items-center justify-content-between"><h5 class="mb-0" id="bus-modal-title"><i class="fas fa-plus me-2"></i>Register Bus</h5><button type="button" class="btn-close ms-2" onclick="$.magnificPopup.close()" title="Close"></button></div>
        <div class="card-body">
            <?php echo form_open('bus_tracking/save_bus', ['class'=>'frm-submit','id'=>'bus-form']); ?>
            <input type="hidden" name="id" id="bus-edit-id" value="">
            <div class="row g-3">
                <div class="col-sm-6">
                    <label class="form-label">Bus Name <span class="text-danger">*</span></label>
                    <input type="text" name="bus_name" class="form-control" required placeholder="e.g. Bus 1">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Reg Number <span class="text-danger">*</span></label>
                    <input type="text" name="reg_number" class="form-control" required placeholder="KXX 000A">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Capacity</label>
                    <input type="number" name="capacity" class="form-control" min="1">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Driver Name</label>
                    <input type="text" name="driver_name" class="form-control">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Driver Phone</label>
                    <input type="text" name="driver_phone" class="form-control">
                </div>
                <div class="col-sm-6">
                    <div class="form-check mt-4">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="bus-active" checked>
                        <label class="form-check-label" for="bus-active">Active</label>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label">Route Description</label>
                    <textarea name="route_description" class="form-control" rows="2" placeholder="e.g. Westlands → Kileleshwa → Lavington"></textarea>
                </div>
            </div>
            <div class="mt-3 text-end"><button class="btn btn-primary" type="submit"><i class="fas fa-save me-1"></i>Save</button></div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
