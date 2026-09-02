<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row justify-content-center">
<div class="col-md-6 col-lg-5">
    <div class="job-card p-4">
        <h4 class="mb-1" style="color:#1a5276;"><i class="fas fa-user-plus me-2"></i>Create Account</h4>
        <p class="text-muted small mb-4">Register to apply for positions at CST SchoolHub.</p>

        <?php if (!empty($error)): ?>
        <div class="flash-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php echo form_open('careers/register'); ?>
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
        <div class="mb-3">
            <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
            <input type="text" name="full_name" class="form-control" value="<?php echo set_value('full_name'); ?>" required>
            <div class="text-danger small"><?php echo form_error('full_name'); ?></div>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control" value="<?php echo set_value('email'); ?>" required>
            <div class="text-danger small"><?php echo form_error('email'); ?></div>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
            <input type="tel" name="phone" class="form-control" value="<?php echo set_value('phone'); ?>" placeholder="e.g. 0712345678" required>
            <div class="text-danger small"><?php echo form_error('phone'); ?></div>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
            <input type="password" name="password" class="form-control" placeholder="At least 6 characters" required>
            <div class="text-danger small"><?php echo form_error('password'); ?></div>
        </div>
        <div class="mb-4">
            <label class="form-label fw-semibold">Confirm Password <span class="text-danger">*</span></label>
            <input type="password" name="confirm_password" class="form-control" required>
            <div class="text-danger small"><?php echo form_error('confirm_password'); ?></div>
        </div>
        <button type="submit" class="btn w-100 text-white" style="background:#1a5276;">
            <i class="fas fa-user-check me-1"></i>Create Account
        </button>
        <?php echo form_close(); ?>

        <p class="text-center text-muted small mt-3 mb-0">
            Already have an account? <a href="<?php echo base_url('careers/login'); ?>">Login here</a>
        </p>
    </div>
</div>
</div>
