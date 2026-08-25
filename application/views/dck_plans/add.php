<div class="card border-0 shadow-sm" style="max-width:520px">
  <div class="card-header bg-white fw-bold">Add DCK Plan</div>
  <div class="card-body">
    <?php if (validation_errors()): ?>
      <div class="alert alert-danger"><?= validation_errors() ?></div>
    <?php endif; ?>
    <form method="post" action="<?= base_url('dck_plans/add') ?>">
      <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
      <input type="hidden" name="save" value="1">
      <div class="mb-3">
        <label class="form-label">Plan Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" placeholder="e.g. Basic, Standard, Premium"
               value="<?= set_value('name') ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="2"><?= set_value('description') ?></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Plan Price (KSh) <span class="text-danger">*</span></label>
        <input type="number" name="price" class="form-control" min="0" step="0.01"
               value="<?= set_value('price') ?>" required>
        <div class="form-text">What the school pays for this plan.</div>
      </div>
      <div class="mb-3">
        <label class="form-label">Visit Fee (KSh) <span class="text-danger">*</span></label>
        <input type="number" name="visit_fee" class="form-control" min="0" step="0.01"
               value="<?= set_value('visit_fee', '500') ?>" required>
        <div class="form-text">Paid to the agent per qualified visit (regardless of outcome).</div>
      </div>
      <div class="mb-3">
        <label class="form-label">Commission Amount (KSh) <span class="text-danger">*</span></label>
        <input type="number" name="commission_amount" class="form-control" min="0" step="0.01"
               value="<?= set_value('commission_amount') ?>" required>
        <div class="form-text">Paid to the agent when a school signs up this plan.</div>
      </div>
      <div class="mb-4">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="active" id="activeChk" value="1" checked>
          <label class="form-check-label" for="activeChk">Active (visible to agents when logging visits)</label>
        </div>
      </div>
      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save Plan</button>
        <a href="<?= base_url('dck_plans') ?>" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
