<div class="row g-4">
  <div class="col-md-5">
    <div class="ap-card">
      <div class="ap-card-header">My Profile</div>
      <div class="ap-card-body">
        <?php if (!empty($pw_success)): ?>
          <div class="alert alert-success py-2 mb-3"><?= $pw_success ?></div>
        <?php endif; ?>
        <?php if (validation_errors()): ?>
          <div class="alert alert-danger" style="font-size:.85rem"><?= validation_errors() ?></div>
        <?php endif; ?>
        <form method="post">
          <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
          <input type="hidden" name="save_profile" value="1">
          <div class="mb-3">
            <label class="form-label">First Name</label>
            <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($agent['first_name']) ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Last Name</label>
            <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($agent['last_name']) ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($agent['phone']) ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" value="<?= htmlspecialchars($agent['email']) ?>" disabled>
            <div class="form-text">Contact superadmin to change email.</div>
          </div>
          <div class="mb-3">
            <label class="form-label">Assigned Region</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($agent['region'] ?: '—') ?>" disabled>
          </div>
          <button type="submit" class="ap-btn-primary">Save Profile</button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="ap-card">
      <div class="ap-card-header">Change Password</div>
      <div class="ap-card-body">
        <?php if (!empty($pw_error)): ?>
          <div class="alert alert-danger py-2"><?= $pw_error ?></div>
        <?php endif; ?>
        <form method="post">
          <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
          <input type="hidden" name="save_password" value="1">
          <div class="mb-3">
            <label class="form-label">Current Password</label>
            <input type="password" name="current_password" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">New Password <small class="text-muted">(min 6 chars)</small></label>
            <input type="password" name="new_password" class="form-control" minlength="6" required>
          </div>
          <button type="submit" class="btn btn-sm btn-outline-secondary">Update Password</button>
        </form>
      </div>
    </div>
  </div>
</div>
