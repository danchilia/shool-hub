<div class="ap-form-card">
  <h6 class="mb-4" style="font-weight:700;color:var(--ap-navy)">
    <i class="fas fa-school me-2"></i>Add New School Lead
  </h6>

  <?php if (validation_errors()): ?>
  <div class="alert alert-danger" style="font-size:.85rem"><?= validation_errors() ?></div>
  <?php endif; ?>

  <!-- GPS capture widget -->
  <div id="gpsBox" style="background:#f0f9ff;border:1px solid #bee3f8;border-radius:8px;padding:14px 16px;margin-bottom:20px;font-size:.85rem">
    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
      <span id="gpsIcon" style="font-size:1.2rem;color:#3182ce"><i class="fas fa-satellite-dish fa-spin"></i></span>
      <div>
        <strong style="color:#2b6cb0">Capturing your GPS location…</strong>
        <div id="gpsStatus" style="color:#4a5568;font-size:.8rem">Please allow location access when prompted</div>
      </div>
      <button type="button" id="gpsRetry" onclick="captureGps()" style="display:none;margin-left:auto" class="btn btn-xs btn-outline-secondary">
        <i class="fas fa-redo"></i> Retry
      </button>
    </div>
  </div>

  <!-- Duplicate warning (hidden until AJAX finds a match) -->
  <div id="dupWarning" style="display:none;background:#fff8e1;border:2px solid #f6ad55;border-radius:8px;padding:14px 16px;margin-bottom:20px;font-size:.88rem">
    <strong style="color:#c05621"><i class="fas fa-exclamation-triangle me-1"></i>School Already in System!</strong>
    <p id="dupMsg" style="margin:6px 0 0;color:#744210"></p>
    <small style="color:#975a16">You can still save this lead. The superadmin will review duplicates.</small>
  </div>

  <form method="post" action="<?= base_url('agent_portal/add_school') ?>">
    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
    <input type="hidden" name="save" value="1">
    <input type="hidden" name="lat" id="fLat">
    <input type="hidden" name="lng" id="fLng">
    <input type="hidden" name="gps_accuracy" id="fAcc">

    <div class="row g-3 mb-3">
      <div class="col-12">
        <label class="form-label">School Name <span class="text-danger">*</span></label>
        <input type="text" name="school_name" id="schoolName" class="form-control"
               value="<?= set_value('school_name') ?>" required autocomplete="off">
      </div>
      <div class="col-md-6">
        <label class="form-label">Principal / Contact Name</label>
        <input type="text" name="principal_name" class="form-control" value="<?= set_value('principal_name') ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Phone Number</label>
        <input type="text" name="phone" id="schoolPhone" class="form-control" value="<?= set_value('phone') ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="<?= set_value('email') ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">No. of Students (approx.)</label>
        <input type="number" name="num_students" class="form-control" min="0" value="<?= set_value('num_students') ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">County</label>
        <input type="text" name="county" class="form-control" value="<?= set_value('county') ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Sub-County</label>
        <input type="text" name="sub_county" class="form-control" value="<?= set_value('sub_county') ?>">
      </div>
      <div class="col-12">
        <label class="form-label">Current System / Software they use</label>
        <input type="text" name="current_system" class="form-control" placeholder="e.g. Manual, Smartschool, None"
               value="<?= set_value('current_system') ?>">
      </div>
      <div class="col-12">
        <label class="form-label">Notes</label>
        <textarea name="notes" class="form-control" rows="3"><?= set_value('notes') ?></textarea>
      </div>
    </div>

    <div class="d-flex gap-2">
      <button type="submit" class="ap-btn-primary"><i class="fas fa-save me-1"></i>Save Lead</button>
      <a href="<?= base_url('agent_portal/schools') ?>" class="btn btn-sm btn-outline-secondary">Cancel</a>
    </div>
  </form>
</div>

<script>
var dupTimeout = null;

function captureGps() {
  var icon = document.getElementById('gpsIcon');
  var status = document.getElementById('gpsStatus');
  var retry = document.getElementById('gpsRetry');
  icon.innerHTML = '<i class="fas fa-satellite-dish fa-spin" style="color:#3182ce"></i>';
  status.textContent = 'Locating… please wait';
  retry.style.display = 'none';

  if (!navigator.geolocation) {
    icon.innerHTML = '<i class="fas fa-times-circle" style="color:#e53e3e"></i>';
    status.textContent = 'GPS not supported on this device.';
    return;
  }

  navigator.geolocation.getCurrentPosition(
    function(pos) {
      var lat = pos.coords.latitude.toFixed(6);
      var lng = pos.coords.longitude.toFixed(6);
      var acc = Math.round(pos.coords.accuracy);
      document.getElementById('fLat').value = lat;
      document.getElementById('fLng').value = lng;
      document.getElementById('fAcc').value = acc;
      icon.innerHTML = '<i class="fas fa-check-circle" style="color:#38a169"></i>';
      status.innerHTML = '<strong style="color:#276749">Location captured</strong> ' +
        lat + ', ' + lng + ' (±' + acc + 'm) ' +
        '<a href="https://www.google.com/maps?q=' + lat + ',' + lng + '" target="_blank" style="font-size:.78rem">View on Map</a>';
      document.getElementById('gpsBox').style.borderColor = '#9ae6b4';
      document.getElementById('gpsBox').style.background = '#f0fff4';
    },
    function(err) {
      icon.innerHTML = '<i class="fas fa-exclamation-triangle" style="color:#dd6b20"></i>';
      status.textContent = err.code === 1 ? 'Location access denied. Enable GPS in browser settings.' : 'Could not get location. Try again.';
      retry.style.display = 'inline-block';
      document.getElementById('gpsBox').style.borderColor = '#fbd38d';
      document.getElementById('gpsBox').style.background = '#fffaf0';
    },
    {timeout: 12000, enableHighAccuracy: true}
  );
}

function checkDuplicate() {
  var name  = document.getElementById('schoolName').value.trim();
  var phone = document.getElementById('schoolPhone').value.trim();
  if (name.length < 3 && phone.length < 5) return;

  clearTimeout(dupTimeout);
  dupTimeout = setTimeout(function() {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '<?= base_url('agent_portal/check_duplicate') ?>', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
      try {
        var res = JSON.parse(xhr.responseText);
        var warn = document.getElementById('dupWarning');
        if (res.found) {
          document.getElementById('dupMsg').textContent =
            '"' + res.school_name + '" (' + (res.county || 'Kenya') + ') was already added by Agent ' +
            res.agent_name + ' on ' + res.added_on + '.';
          warn.style.display = 'block';
        } else {
          warn.style.display = 'none';
        }
      } catch(e) {}
    };
    xhr.send('school_name=' + encodeURIComponent(name) + '&phone=' + encodeURIComponent(phone) +
      '&<?= $this->security->get_csrf_token_name() ?>=<?= $this->security->get_csrf_hash() ?>');
  }, 600);
}

document.getElementById('schoolName').addEventListener('blur', checkDuplicate);
document.getElementById('schoolPhone').addEventListener('blur', checkDuplicate);

captureGps();
</script>
