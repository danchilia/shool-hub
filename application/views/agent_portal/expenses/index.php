<div class="row g-4">
  <!-- Claim form -->
  <div class="col-md-4">
    <div class="ap-card">
      <div class="ap-card-header">Submit Expense Claim</div>
      <div class="ap-card-body">
        <?php if (validation_errors()): ?>
        <div class="alert alert-danger" style="font-size:.82rem"><?= validation_errors() ?></div>
        <?php endif; ?>
        <form method="post" action="<?= base_url('agent_portal/expenses') ?>">
          <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
          <input type="hidden" name="save" value="1">
          <div class="mb-3">
            <label class="form-label" style="font-size:.82rem">School (optional)</label>
            <select name="school_id" class="form-select form-select-sm">
              <option value="">— not school-specific —</option>
              <?php foreach ($schools as $sch): ?>
                <option value="<?= $sch['id'] ?>"><?= htmlspecialchars($sch['school_name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label" style="font-size:.82rem">Description <span class="text-danger">*</span></label>
            <input type="text" name="description" class="form-control form-control-sm"
                   placeholder="e.g. Matatu fare to Thika" value="<?= set_value('description') ?>">
          </div>
          <div class="mb-3">
            <label class="form-label" style="font-size:.82rem">Amount (KSh) <span class="text-danger">*</span></label>
            <input type="number" name="amount" class="form-control form-control-sm" min="0" step="0.01"
                   value="<?= set_value('amount') ?>">
          </div>
          <button type="submit" class="ap-btn-primary w-100" style="font-size:.85rem">
            <i class="fas fa-paper-plane me-1"></i>Submit Claim
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- Claims list -->
  <div class="col-md-8">
    <div class="ap-card">
      <div class="ap-card-header">My Expense Claims</div>
      <?php if (empty($expenses)): ?>
        <div class="ap-card-body text-center text-muted py-4">No expense claims yet.</div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-hover mb-0" style="font-size:.875rem">
            <thead style="background:var(--ap-bg)">
              <tr>
                <th>Date</th>
                <th>Description</th>
                <th>School</th>
                <th class="text-end">Amount</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($expenses as $ex): ?>
              <tr>
                <td><?= date('d M Y', strtotime($ex['created_at'])) ?></td>
                <td><?= htmlspecialchars($ex['description']) ?></td>
                <td><?= htmlspecialchars($ex['school_name'] ?: '—') ?></td>
                <td class="text-end">KSh <?= number_format($ex['amount']) ?></td>
                <td>
                  <?php $bc = array('pending' => 'warning text-dark', 'approved' => 'success', 'rejected' => 'danger'); ?>
                  <span class="badge bg-<?= $bc[$ex['status']] ?? 'secondary' ?>"><?= ucfirst($ex['status']) ?></span>
                  <?php if ($ex['review_note']): ?>
                    <div style="font-size:.72rem;color:var(--ap-muted)"><?= htmlspecialchars($ex['review_note']) ?></div>
                  <?php endif; ?>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
