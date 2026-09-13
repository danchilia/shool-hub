<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php $warn = $this->session->flashdata('dir_warn'); ?>
<?php if ($warn): ?><div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> <?php echo $warn; ?></div><?php endif; ?>

<div class="ap-card" style="max-width:620px;">
    <div class="ap-card-header">
        <i class="fas fa-school me-2"></i>Add a School Not in the Directory
    </div>
    <div class="ap-card-body">
        <p style="font-size:13px;color:#7f8c8d;margin-bottom:20px;">
            Fill in the details below. The school will be added to your pipeline immediately and submitted to the CST team for verification.
        </p>
        <?php echo form_open('agent_portal/add_new_to_directory'); ?>
        <div class="form-group">
            <label>School Name <span class="required">*</span></label>
            <input type="text" name="school_name" class="form-control" required placeholder="e.g. St. Mary's Primary School">
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Type</label>
                    <select name="type" class="form-control">
                        <option value="">— Select —</option>
                        <option>Primary</option>
                        <option>Secondary</option>
                        <option>ECDE/Pre-Primary</option>
                        <option>College/TVET</option>
                        <option>Special Needs</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Ownership</label>
                    <select name="ownership" class="form-control">
                        <option value="">— Select —</option>
                        <option>Public</option>
                        <option>Private</option>
                        <option>Mission/Church</option>
                        <option>Not Specified</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Area / Town</label>
                    <input type="text" name="area" class="form-control" placeholder="e.g. Westlands">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Region</label>
                    <input type="text" name="region" class="form-control" placeholder="e.g. Nairobi">
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" name="phone" class="form-control" placeholder="e.g. 0712345678">
        </div>
        <div class="form-group">
            <label>Road / Location</label>
            <input type="text" name="road_location" class="form-control" placeholder="e.g. Waiyaki Way, next to Shell petrol station">
        </div>
        <div style="margin-top:8px;">
            <button type="submit" name="save" value="1" class="btn btn-primary">
                <i class="fas fa-save"></i> Save & Add to My Pipeline
            </button>
            <a href="<?php echo base_url('agent_portal/directory'); ?>" class="btn btn-default" style="margin-left:8px;">Cancel</a>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
