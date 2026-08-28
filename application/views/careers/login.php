<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row justify-content-center">
<div class="col-md-5 col-lg-4">
    <div class="job-card p-4">
        <h4 class="mb-1" style="color:#1a5276;"><i class="fas fa-sign-in-alt me-2"></i>Applicant Login</h4>
        <p class="text-muted small mb-4">Login to apply or track your applications.</p>

        <?php if (!empty($error)): ?>
        <div class="flash-error"><i class="fas fa-exclamation-circle me-1"></i><?php echo $error; ?></div>
        <?php endif; ?>

        <?php echo form_open('careers/login'); ?>
        <div class="mb-3">
            <label class="form-label fw-semibold">Email Address</label>
            <input type="email" name="email" class="form-control" value="<?php echo set_value('email'); ?>" required autofocus>
            <div class="text-danger small"><?php echo form_error('email'); ?></div>
        </div>
        <div class="mb-4">
            <label class="form-label fw-semibold">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn w-100 text-white" style="background:#1a5276;">
            <i class="fas fa-sign-in-alt me-1"></i>Login
        </button>
        <?php echo form_close(); ?>

        <p class="text-center text-muted small mt-3 mb-0">
            No account yet? <a href="<?php echo base_url('careers/register'); ?>">Register here</a>
        </p>
    </div>
</div>
</div>
