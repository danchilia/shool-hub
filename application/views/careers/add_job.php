<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
<div class="col-md-8 col-md-offset-2">
    <div class="panel">
        <header class="panel-heading">
            <h4 class="panel-title">
                <i class="fas fa-<?php echo $job ? 'edit' : 'plus'; ?> me-2"></i>
                <?php echo $job ? 'Edit Job Position' : 'Post New Job'; ?>
            </h4>
        </header>
        <?php echo form_open($this->uri->uri_string()); ?>
        <div class="panel-body">
            <div class="form-group">
                <label class="control-label">Job Title <span class="required">*</span></label>
                <input type="text" name="title" class="form-control" required
                    value="<?php echo $job ? html_escape($job['title']) : set_value('title'); ?>">
                <span class="text-danger small"><?php echo form_error('title'); ?></span>
            </div>
            <div class="form-group">
                <label class="control-label">Department</label>
                <input type="text" name="department" class="form-control" placeholder="e.g. Engineering, Sales, Support"
                    value="<?php echo $job ? html_escape($job['department']) : set_value('department'); ?>">
            </div>
            <div class="form-group">
                <label class="control-label">Job Description <span class="required">*</span></label>
                <textarea name="description" class="form-control" rows="8" required
                    placeholder="Describe the role, responsibilities, and what the candidate will do..."><?php echo $job ? html_escape($job['description']) : set_value('description'); ?></textarea>
                <span class="text-danger small"><?php echo form_error('description'); ?></span>
            </div>
            <div class="form-group">
                <label class="control-label">Requirements / Qualifications</label>
                <textarea name="requirements" class="form-control" rows="6"
                    placeholder="List required qualifications, skills, and experience..."><?php echo $job ? html_escape($job['requirements']) : set_value('requirements'); ?></textarea>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Application Deadline</label>
                        <input type="date" name="deadline" class="form-control"
                            value="<?php echo $job ? $job['deadline'] : set_value('deadline'); ?>">
                        <small class="text-muted">Leave blank for no deadline.</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Status <span class="required">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="open"   <?php echo (!$job || $job['status'] === 'open')   ? 'selected' : ''; ?>>Open (accepting applications)</option>
                            <option value="closed" <?php echo ($job && $job['status'] === 'closed') ? 'selected' : ''; ?>>Closed</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <a href="<?php echo base_url('careers/manage'); ?>" class="btn btn-default">
                <i class="fas fa-arrow-left me-1"></i>Back
            </a>
            <button class="btn btn-primary pull-right" type="submit">
                <i class="fas fa-save me-1"></i><?php echo $job ? 'Save Changes' : 'Post Job'; ?>
            </button>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
</div>
