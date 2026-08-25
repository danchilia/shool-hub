<div class="card border-0 shadow-sm" style="max-width:600px">
  <div class="card-header bg-white fw-bold">Add Field Agent</div>
  <div class="card-body">
    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <?php if (validation_errors()): ?>
      <div class="alert alert-danger"><?= validation_errors() ?></div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('agents/add') ?>">
      <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
      <input type="hidden" name="save" value="1">

      <div class="row g-3 mb-3">
        <div class="col-md-6">
          <label class="form-label">First Name <span class="text-danger">*</span></label>
          <input type="text" name="first_name" class="form-control" value="<?= set_value('first_name') ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Last Name <span class="text-danger">*</span></label>
          <input type="text" name="last_name" class="form-control" value="<?= set_value('last_name') ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Email Address <span class="text-danger">*</span></label>
          <input type="email" name="email" class="form-control" value="<?= set_value('email') ?>" required>
          <div class="form-text">This is their login username.</div>
        </div>
        <div class="col-md-6">
          <label class="form-label">Phone <span class="text-danger">*</span></label>
          <input type="text" name="phone" class="form-control" value="<?= set_value('phone') ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Assigned Region / Territory</label>
          <input type="text" name="region" class="form-control" placeholder="e.g. Nairobi North, Mombasa"
                 value="<?= set_value('region') ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Password <span class="text-danger">*</span></label>
          <input type="password" name="password" class="form-control" minlength="6" required>
          <div class="form-text">Minimum 6 characters. Share securely with agent.</div>
        </div>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Create Agent</button>
        <a href="<?= base_url('agents') ?>" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>

<div class="mt-3 card border-0 shadow-sm p-3" style="max-width:600px;background:#f8f9fa">
  <div class="small text-muted">
    <i class="fas fa-info-circle me-1"></i>
    After creating the agent, share their login URL:
    <strong><?= base_url('agent_portal/login') ?></strong>
  </div>
</div>
