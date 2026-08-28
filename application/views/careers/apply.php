<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row justify-content-center">
<div class="col-lg-7">
    <div class="job-card p-4">

        <!-- Position summary -->
        <div class="p-3 mb-4 rounded" style="background:#eaf4fb;border-left:4px solid #1a5276;">
            <h5 class="mb-1" style="color:#1a5276;"><?php echo html_escape($job['title']); ?></h5>
            <?php if ($job['department']): ?>
            <p class="mb-0 text-muted small"><i class="fas fa-building me-1"></i><?php echo html_escape($job['department']); ?></p>
            <?php endif; ?>
        </div>

        <h5 class="mb-3" style="color:#1a5276;"><i class="fas fa-paper-plane me-2"></i>Submit Your Application</h5>

        <?php if (!empty($error)): ?>
        <div class="flash-error"><i class="fas fa-exclamation-circle me-1"></i><?php echo $error; ?></div>
        <?php endif; ?>

        <?php echo form_open_multipart('careers/apply/' . $job['id']); ?>

        <div class="mb-3">
            <label class="form-label fw-semibold">Upload CV / Resume <span class="text-danger">*</span></label>
            <input type="file" name="cv" class="form-control" accept=".pdf,.doc,.docx" required>
            <div class="form-text">Accepted: PDF, DOC, DOCX &nbsp;·&nbsp; Max size: 5 MB</div>
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold">
                Cover Letter <span class="text-muted fw-normal">(optional)</span>
            </label>
            <textarea name="cover_letter" class="form-control" rows="7"
                placeholder="Tell us why you're the right fit for this role. What experience do you bring? Why CST SchoolHub?"><?php echo set_value('cover_letter'); ?></textarea>
        </div>

        <div class="d-flex gap-2 flex-wrap">
            <a href="<?php echo base_url('careers/job/' . $job['id']); ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back
            </a>
            <button type="submit" class="btn text-white" style="background:#1a5276;">
                <i class="fas fa-paper-plane me-1"></i>Submit Application
            </button>
        </div>

        <?php echo form_close(); ?>
    </div>
</div>
</div>
