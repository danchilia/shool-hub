<div class="ap-form-card">
  <div class="d-flex align-items-center gap-2 mb-4">
    <a href="<?= base_url('agent_portal/view_school/' . $school['id']) ?>" class="btn btn-sm btn-outline-secondary">
      <i class="fas fa-arrow-left"></i>
    </a>
    <div>
      <div style="font-size:.75rem;color:var(--ap-muted)">Logging visit for</div>
      <strong><?= htmlspecialchars($school['school_name']) ?></strong>
    </div>
  </div>

  <?php if (validation_errors()): ?>
  <div class="alert alert-danger" style="font-size:.85rem"><?= validation_errors() ?></div>
  <?php endif; ?>

  <!-- GPS check-in -->
  <div id="gpsBox" style="background:#f0f9ff;border:1px solid #bee3f8;border-radius:8px;padding:12px 16px;margin-bottom:18px;font-size:.84rem">
    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
      <span id="gpsIcon" style="font-size:1.1rem;color:#3182ce"><i class="fas fa-map-marker-alt fa-pulse"></i></span>
      <div>
        <strong style="color:#2b6cb0">GPS Check-in</strong>
        <div id="gpsStatus" style="color:#4a5568;font-size:.79rem">Capturing your location for this visit…</div>
      </div>
      <button type="button" id="gpsRetry" onclick="captureGps()" style="display:none;margin-left:auto" class="btn btn-xs btn-outline-secondary">
        <i class="fas fa-redo"></i> Retry
      </button>
    </div>
  </div>

  <form method="post" action="<?= base_url('agent_portal/log_visit/' . $school['id']) ?>">
    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
    <input type="hidden" name="save" value="1">
    <input type="hidden" name="lat" id="fLat">
    <input type="hidden" name="lng" id="fLng">
    <input type="hidden" name="gps_accuracy" id="fAcc">

    <div class="row g-3 mb-3">
      <div class="col-md-6">
        <label class="form-label">Visit Date <span class="text-danger">*</span></label>
        <input type="date" name="visit_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Visit Type</label>
        <select name="visit_type" class="form-select">
          <option value="demo">Demo / Presentation</option>
          <option value="follow_up">Follow-up Visit</option>
          <option value="setup">System Setup</option>
          <option value="other">Other</option>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">School's Interest Level</label>
        <select name="interest_level" class="form-select">
          <option value="unknown">Unknown</option>
          <option value="cold">Cold</option>
          <option value="warm">Warm</option>
          <option value="hot">Hot</option>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Visit Outcome <span class="text-danger">*</span></label>
        <select name="outcome" class="form-select" id="outcomeSelect" required>
          <option value="pending">Still Considering</option>
          <option value="interested">Interested</option>
          <option value="needs_followup">Needs Follow-up</option>
          <option value="signed_up">Signed Up! 🎉</option>
          <option value="not_interested">Not Interested</option>
        </select>
      </div>

      <!-- Follow-up date — shown when needs_followup -->
      <div class="col-md-6" id="followupRow" style="display:none">
        <label class="form-label">Next Follow-up Date</label>
        <input type="date" name="next_followup_date" class="form-control">
      </div>

      <!-- Plan selection — shown when signed_up -->
      <div class="col-md-6" id="planRow" style="display:none">
        <label class="form-label">Plan Signed <span class="text-danger">*</span></label>
        <select name="plan_id" class="form-select" id="planSelect">
          <option value="">— select plan —</option>
          <?php foreach ($plans as $p): ?>
          <option value="<?= $p['id'] ?>">
            <?= htmlspecialchars($p['name']) ?> (KSh <?= number_format($p['price']) ?>) | Commission: KSh <?= number_format($p['commission_amount']) ?>
          </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-12">
        <label class="form-label">Modules Demoed</label>
        <input type="text" name="modules_demoed" class="form-control"
               placeholder="e.g. Student Management, Fees, Exams, Attendance"
               value="<?= set_value('modules_demoed') ?>">
      </div>
      <div class="col-12">
        <label class="form-label">Visit Notes</label>
        <textarea name="notes" class="form-control" rows="3"
                  placeholder="Key discussion points, objections, what impressed them…"><?= set_value('notes') ?></textarea>
      </div>
    </div>

    <!-- Commission preview (shown when plan selected) -->
    <div id="commissionPreview" class="alert alert-success py-2 px-3 mb-3" style="display:none;font-size:.85rem">
      <i class="fas fa-check-circle me-1"></i>
      <strong>Earnings on this visit:</strong>
      Visit Fee: <strong id="visitFeeAmt">KSh 0</strong> +
      Commission: <strong id="commissionAmt">KSh 0</strong>
    </div>

    <div class="d-flex gap-2">
      <button type="submit" class="ap-btn-primary"><i class="fas fa-save me-1"></i>Save Visit Log</button>
      <a href="<?= base_url('agent_portal/view_school/' . $school['id']) ?>" class="btn btn-sm btn-outline-secondary">Cancel</a>
    </div>
  </form>
</div>

<script>
function captureGps() {
  var icon   = document.getElementById('gpsIcon');
  var status = document.getElementById('gpsStatus');
  var retry  = document.getElementById('gpsRetry');
  icon.innerHTML = '<i class="fas fa-map-marker-alt fa-pulse" style="color:#3182ce"></i>';
  retry.style.display = 'none';
  if (!navigator.geolocation) {
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
      status.innerHTML = '<strong style="color:#276749">Check-in recorded</strong> ' + lat + ', ' + lng +
        ' (±' + acc + 'm) &nbsp;<a href="https://www.google.com/maps?q=' + lat + ',' + lng +
        '" target="_blank" style="font-size:.77rem">View on Map</a>';
      document.getElementById('gpsBox').style.background = '#f0fff4';
      document.getElementById('gpsBox').style.borderColor = '#9ae6b4';
    },
    function(err) {
      icon.innerHTML = '<i class="fas fa-exclamation-triangle" style="color:#dd6b20"></i>';
      status.textContent = err.code === 1 ? 'Location denied. Enable GPS in browser settings and retry.' : 'Could not get location.';
      retry.style.display = 'inline-block';
      document.getElementById('gpsBox').style.background = '#fffaf0';
      document.getElementById('gpsBox').style.borderColor = '#fbd38d';
    },
    {timeout: 12000, enableHighAccuracy: true}
  );
}
captureGps();

var plans = <?= json_encode(array_column($plans, null, 'id')) ?>;
var defaultVisitFee = <?= !empty($plans) ? (float)$plans[0]['visit_fee'] : 500 ?>;

var outcomeEl = document.getElementById('outcomeSelect');
var followupRow = document.getElementById('followupRow');
var planRow = document.getElementById('planRow');
var planSelect = document.getElementById('planSelect');
var preview = document.getElementById('commissionPreview');

function updateRows() {
  var val = outcomeEl.value;
  followupRow.style.display = val === 'needs_followup' ? '' : 'none';
  planRow.style.display     = val === 'signed_up'      ? '' : 'none';
  updatePreview();
}

function updatePreview() {
  if (outcomeEl.value === 'signed_up' && planSelect.value) {
    var p = plans[planSelect.value];
    if (p) {
      document.getElementById('visitFeeAmt').textContent = 'KSh ' + parseInt(defaultVisitFee).toLocaleString();
      document.getElementById('commissionAmt').textContent = 'KSh ' + parseInt(p.commission_amount).toLocaleString();
      preview.style.display = '';
      return;
    }
  }
  preview.style.display = 'none';
}

outcomeEl.addEventListener('change', updateRows);
planSelect.addEventListener('change', updatePreview);
updateRows();
</script>
