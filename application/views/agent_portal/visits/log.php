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

  <form method="post" action="<?= base_url('agent_portal/log_visit/' . $school['id']) ?>">
    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
    <input type="hidden" name="save" value="1">

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
            <?= htmlspecialchars($p['name']) ?> (KSh <?= number_format($p['price']) ?>) — Commission: KSh <?= number_format($p['commission_amount']) ?>
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
