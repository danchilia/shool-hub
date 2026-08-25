<div class="ap-form-card">
  <h6 class="mb-4" style="font-weight:700;color:var(--ap-navy)">
    <i class="fas fa-school me-2"></i>Add New School Lead
  </h6>

  <?php if (validation_errors()): ?>
  <div class="alert alert-danger" style="font-size:.85rem"><?= validation_errors() ?></div>
  <?php endif; ?>

  <form method="post" action="<?= base_url('agent_portal/add_school') ?>">
    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
    <input type="hidden" name="save" value="1">

    <div class="row g-3 mb-3">
      <div class="col-12">
        <label class="form-label">School Name <span class="text-danger">*</span></label>
        <input type="text" name="school_name" class="form-control" value="<?= set_value('school_name') ?>" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Principal / Contact Name</label>
        <input type="text" name="principal_name" class="form-control" value="<?= set_value('principal_name') ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Phone Number</label>
        <input type="text" name="phone" class="form-control" value="<?= set_value('phone') ?>">
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
