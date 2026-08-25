<div class="card border-0 shadow-sm" style="max-width:520px">
  <div class="card-header bg-white fw-bold">Edit Plan — <?= htmlspecialchars($plan['name']) ?></div>
  <div class="card-body">
    <?php if (validation_errors()): ?>
      <div class="alert alert-danger"><?= validation_errors() ?></div>
    <?php endif; ?>
    <form method="post" action="<?= base_url('dck_plans/edit/' . $plan['id']) ?>">
      <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
      <input type="hidden" name="save" value="1">
      <div class="mb-3">
        <label class="form-label">Plan Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($plan['name']) ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="2"><?= htmlspecialchars($plan['description']) ?></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Plan Price (KSh)</label>
        <input type="number" name="price" class="form-control" min="0" step="0.01" value="<?= $plan['price'] ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Visit Fee (KSh)</label>
        <input type="number" name="visit_fee" class="form-control" min="0" step="0.01" value="<?= $plan['visit_fee'] ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Commission Amount (KSh)</label>
        <input type="number" name="commission_amount" class="form-control" min="0" step="0.01" value="<?= $plan['commission_amount'] ?>" required>
      </div>
      <div class="mb-4">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="active" id="activeChk" value="1" <?= $plan['active'] ? 'checked' : '' ?>>
          <label class="form-check-label" for="activeChk">Active</label>
        </div>
      </div>
      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save Changes</button>
        <a href="<?= base_url('dck_plans') ?>" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
