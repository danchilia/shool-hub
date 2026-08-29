<?php if ($success): ?>
<div class="ap-card text-center py-5">
  <div style="font-size:3rem;color:var(--ap-green);margin-bottom:16px"><i class="fas fa-check-circle"></i></div>
  <h5>Submission Received</h5>
  <p class="text-muted" style="font-size:.9rem">The school details for <strong><?= htmlspecialchars($school['school_name']) ?></strong> have been submitted to CST SchoolHub for setup.</p>
  <a href="<?= base_url('agent_portal/schools') ?>" class="ap-btn-primary mt-2">Back to Schools</a>
</div>
<?php return; endif; ?>

<div class="d-flex align-items-center gap-2 mb-3">
  <a href="<?= base_url('agent_portal/view_school/' . $school['id']) ?>" class="btn btn-sm btn-outline-secondary">
    <i class="fas fa-arrow-left me-1"></i>Back
  </a>
  <a href="<?= base_url('agent_portal/download_form') ?>" class="btn btn-sm ms-auto" style="background:var(--ap-navy2);color:#fff">
    <i class="fas fa-file-word me-1"></i>Download Data Collection Form
  </a>
</div>

<div class="ap-card mb-3" style="border-left:3px solid var(--ap-accent);background:rgba(243,156,18,.06)">
  <div class="ap-card-body" style="font-size:.84rem;color:var(--ap-text)">
    <i class="fas fa-info-circle me-1" style="color:var(--ap-accent)"></i>
    Fill in the school's details below so CST SchoolHub can set up their account. You can download and use the <strong>Data Collection Form</strong> to gather all information from the school before completing this form.
  </div>
</div>

<?php echo form_open('agent_portal/submit_school/' . $school['id']); ?>
<input type="hidden" name="submit_onboarding" value="1">

<?php if (validation_errors()): ?>
<div class="alert alert-danger" style="font-size:.85rem"><?= validation_errors() ?></div>
<?php endif; ?>

<!-- SCHOOL IDENTITY -->
<div class="ap-card mb-3">
  <div class="ap-card-header">School Identity</div>
  <div class="ap-card-body">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label" style="font-size:.8rem;font-weight:600">School Name <span class="text-danger">*</span></label>
        <input type="text" name="school_name" class="form-control form-control-sm" value="<?= set_value('school_name', htmlspecialchars($school['school_name'])) ?>" required>
      </div>
      <div class="col-md-6">
        <label class="form-label" style="font-size:.8rem;font-weight:600">MoE / NEMIS Reg. Number</label>
        <input type="text" name="reg_number" class="form-control form-control-sm" value="<?= set_value('reg_number') ?>" placeholder="e.g. 14800234">
      </div>
      <div class="col-md-4">
        <label class="form-label" style="font-size:.8rem;font-weight:600">School Type</label>
        <select name="school_type" class="form-select form-select-sm">
          <?php foreach (['Public','Private','Faith-based','International'] as $t): ?>
            <option value="<?= $t ?>" <?= set_value('school_type') === $t ? 'selected' : '' ?>><?= $t ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label" style="font-size:.8rem;font-weight:600">Category</label>
        <select name="school_category" class="form-select form-select-sm">
          <?php foreach (['Pre-Primary','Primary','Secondary','Mixed (Primary & Secondary)','Special Needs'] as $c): ?>
            <option value="<?= $c ?>" <?= set_value('school_category') === $c ? 'selected' : '' ?>><?= $c ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label" style="font-size:.8rem;font-weight:600">No. of Streams / Classes</label>
        <input type="text" name="num_streams" class="form-control form-control-sm" value="<?= set_value('num_streams') ?>" placeholder="e.g. 3 streams per grade">
      </div>
    </div>
  </div>
</div>

<!-- LOCATION -->
<div class="ap-card mb-3">
  <div class="ap-card-header">Location</div>
  <div class="ap-card-body">
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label" style="font-size:.8rem;font-weight:600">County</label>
        <input type="text" name="county" class="form-control form-control-sm" value="<?= set_value('county', htmlspecialchars($school['county'])) ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label" style="font-size:.8rem;font-weight:600">Sub-County</label>
        <input type="text" name="sub_county" class="form-control form-control-sm" value="<?= set_value('sub_county', htmlspecialchars($school['sub_county'])) ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label" style="font-size:.8rem;font-weight:600">Ward</label>
        <input type="text" name="ward" class="form-control form-control-sm" value="<?= set_value('ward') ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label" style="font-size:.8rem;font-weight:600">Physical Address</label>
        <input type="text" name="physical_address" class="form-control form-control-sm" value="<?= set_value('physical_address') ?>" placeholder="Street / Estate">
      </div>
      <div class="col-md-6">
        <label class="form-label" style="font-size:.8rem;font-weight:600">Postal Address / P.O. Box</label>
        <input type="text" name="postal_address" class="form-control form-control-sm" value="<?= set_value('postal_address') ?>" placeholder="P.O. Box 0000-00100, Nairobi">
      </div>
    </div>
  </div>
</div>

<!-- CONTACT -->
<div class="ap-card mb-3">
  <div class="ap-card-header">School Contact</div>
  <div class="ap-card-body">
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label" style="font-size:.8rem;font-weight:600">School Phone <span class="text-danger">*</span></label>
        <input type="text" name="school_phone" class="form-control form-control-sm" value="<?= set_value('school_phone', htmlspecialchars($school['phone'])) ?>" required>
      </div>
      <div class="col-md-4">
        <label class="form-label" style="font-size:.8rem;font-weight:600">School Email</label>
        <input type="email" name="school_email" class="form-control form-control-sm" value="<?= set_value('school_email', htmlspecialchars($school['email'])) ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label" style="font-size:.8rem;font-weight:600">School Website</label>
        <input type="text" name="school_website" class="form-control form-control-sm" value="<?= set_value('school_website') ?>" placeholder="www.example.sc.ke">
      </div>
      <div class="col-md-4">
        <label class="form-label" style="font-size:.8rem;font-weight:600">Principal / Head Teacher <span class="text-danger">*</span></label>
        <input type="text" name="principal_name" class="form-control form-control-sm" value="<?= set_value('principal_name', htmlspecialchars($school['principal_name'])) ?>" required>
      </div>
      <div class="col-md-4">
        <label class="form-label" style="font-size:.8rem;font-weight:600">Principal Phone</label>
        <input type="text" name="principal_phone" class="form-control form-control-sm" value="<?= set_value('principal_phone') ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label" style="font-size:.8rem;font-weight:600">Principal Email</label>
        <input type="email" name="principal_email" class="form-control form-control-sm" value="<?= set_value('principal_email') ?>">
      </div>
    </div>
  </div>
</div>

<!-- STAFF & STUDENTS -->
<div class="ap-card mb-3">
  <div class="ap-card-header">Staff & Students</div>
  <div class="ap-card-body">
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label" style="font-size:.8rem;font-weight:600">Total Students</label>
        <input type="number" name="num_students" class="form-control form-control-sm" value="<?= set_value('num_students', $school['num_students']) ?>" min="0">
      </div>
      <div class="col-md-4">
        <label class="form-label" style="font-size:.8rem;font-weight:600">Teaching Staff</label>
        <input type="number" name="num_teaching_staff" class="form-control form-control-sm" value="<?= set_value('num_teaching_staff') ?>" min="0">
      </div>
      <div class="col-md-4">
        <label class="form-label" style="font-size:.8rem;font-weight:600">Non-Teaching Staff</label>
        <input type="number" name="num_non_teaching_staff" class="form-control form-control-sm" value="<?= set_value('num_non_teaching_staff') ?>" min="0">
      </div>
    </div>
  </div>
</div>

<!-- SYSTEM ADMIN -->
<div class="ap-card mb-3">
  <div class="ap-card-header">System Administrator (person who will manage the system)</div>
  <div class="ap-card-body">
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label" style="font-size:.8rem;font-weight:600">Full Name <span class="text-danger">*</span></label>
        <input type="text" name="admin_name" class="form-control form-control-sm" value="<?= set_value('admin_name') ?>" required>
      </div>
      <div class="col-md-4">
        <label class="form-label" style="font-size:.8rem;font-weight:600">Phone <span class="text-danger">*</span></label>
        <input type="text" name="admin_phone" class="form-control form-control-sm" value="<?= set_value('admin_phone') ?>" required>
      </div>
      <div class="col-md-4">
        <label class="form-label" style="font-size:.8rem;font-weight:600">Email <span class="text-danger">*</span></label>
        <input type="email" name="admin_email" class="form-control form-control-sm" value="<?= set_value('admin_email') ?>" required>
      </div>
    </div>
  </div>
</div>

<!-- PLAN -->
<div class="ap-card mb-3">
  <div class="ap-card-header">Subscription Plan</div>
  <div class="ap-card-body">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label" style="font-size:.8rem;font-weight:600">Plan Selected</label>
        <select name="subscription_plan_id" class="form-select form-select-sm">
          <option value="">— Not confirmed yet —</option>
          <?php foreach ($sub_plans as $p): ?>
            <option value="<?= $p['id'] ?>" <?= set_value('subscription_plan_id') == $p['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($p['name']) ?> — KES <?= number_format($p['monthly_price'],0) ?>/mo &nbsp;|&nbsp; up to <?= $p['max_students'] == 0 ? 'Unlimited' : number_format($p['max_students']) ?> students
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label" style="font-size:.8rem;font-weight:600">Billing Cycle</label>
        <select name="billing_cycle" class="form-select form-select-sm">
          <option value="monthly">Monthly</option>
          <option value="yearly">Yearly (save ~17%)</option>
        </select>
      </div>
      <div class="col-12">
        <label class="form-label" style="font-size:.8rem;font-weight:600">Additional Notes / Special Requirements</label>
        <textarea name="notes" class="form-control form-control-sm" rows="3" placeholder="Any specific modules, data migration needs, or special instructions..."><?= set_value('notes') ?></textarea>
      </div>
    </div>
  </div>
</div>

<button type="submit" class="ap-btn-primary">
  <i class="fas fa-paper-plane me-2"></i>Submit School for Setup
</button>
<?php echo form_close(); ?>
