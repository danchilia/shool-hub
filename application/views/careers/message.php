<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row justify-content-center">
<div class="col-md-6">
    <div class="job-card p-5 text-center">
        <i class="fas fa-info-circle fa-3x mb-3 d-block" style="color:#1a5276;"></i>
        <h5><?php echo isset($message) ? html_escape($message) : 'Something went wrong.'; ?></h5>
        <a href="<?php echo base_url('careers'); ?>" class="btn text-white mt-3" style="background:#1a5276;">
            <i class="fas fa-arrow-left me-1"></i>Back to Jobs
        </a>
    </div>
</div>
</div>
