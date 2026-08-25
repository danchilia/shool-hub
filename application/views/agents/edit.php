<div class="card border-0 shadow-sm" style="max-width:600px">
  <div class="card-header bg-white fw-bold">Edit Agent — <?= htmlspecialchars($agent['first_name'] . ' ' . $agent['last_name']) ?></div>
  <div class="card-body">
    <?php if (validation_errors()): ?>
      <div class="alert alert-danger"><?= validation_errors() ?></div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('agents/edit/' . $agent['id']) ?>">
      <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
      <input type="hidden" name="save" value="1">

      <div class="row g-3 mb-3">
        <div class="col-md-6">
          <label class="form-label">First Name</label>
          <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($agent['first_name']) ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Last Name</label>
          <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($agent['last_name']) ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Email (Login)</label>
          <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($agent['email']) ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Phone</label>
          <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($agent['phone']) ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Region</label>
          <input type="text" name="region" class="form-control" value="<?= htmlspecialchars($agent['region']) ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">New Password <small class="text-muted">(leave blank to keep)</small></label>
          <input type="password" name="password" class="form-control" minlength="6">
        </div>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save Changes</button>
        <a href="<?= base_url('agents/view/' . $agent['id']) ?>" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
