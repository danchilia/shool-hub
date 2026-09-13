<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php if (!empty($error)): ?>
<div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
<?php endif; ?>

<?php if (empty($preview)): ?>

<!-- Upload form -->
<div class="row">
<div class="col-md-8 col-md-offset-2">
<div class="panel">
    <div class="panel-heading"><h4 class="panel-title"><i class="fas fa-file-excel me-2" style="color:#1d6f42;"></i>Upload Schools from Excel</h4></div>
    <div class="panel-body">
        <div class="alert alert-info" style="font-size:13px;">
            <strong>Expected columns in your Excel file:</strong><br>
            <code>School Name</code> &nbsp;|&nbsp; <code>Type</code> &nbsp;|&nbsp; <code>Ownership</code> &nbsp;|&nbsp;
            <code>Area</code> &nbsp;|&nbsp; <code>Phone Number</code> &nbsp;|&nbsp; <code>Road/Location</code><br>
            <span class="text-muted">Each sheet in the workbook is treated as one region. Sheet name becomes the region name.</span>
        </div>
        <?php echo form_open_multipart('school_directory/upload'); ?>
        <div class="form-group">
            <label>Excel File (.xlsx or .xls)</label>
            <input type="file" name="xlsx_file" accept=".xlsx,.xls" required class="form-control">
        </div>
        <button type="submit" name="preview" value="1" class="btn btn-primary">
            <i class="fas fa-eye"></i> Preview Import
        </button>
        <a href="<?php echo base_url('school_directory'); ?>" class="btn btn-default" style="margin-left:8px;">Cancel</a>
        <?php echo form_close(); ?>
    </div>
</div>
</div>
</div>

<?php else: ?>

<!-- Preview & confirm -->
<div class="alert alert-warning" style="font-size:13px;">
    <i class="fas fa-info-circle"></i>
    Review each sheet below. You can edit the region name before importing.
    Schools that already exist (matched by name or phone) will be skipped automatically.
</div>

<?php echo form_open('school_directory/upload'); ?>
<?php foreach ($preview as $i => $sheet): ?>
<div class="panel" style="border-top:3px solid #1d6f42;">
    <div class="panel-heading" style="display:flex;justify-content:space-between;align-items:center;">
        <h4 class="panel-title">
            <i class="fas fa-table me-2"></i>
            Sheet: <strong><?php echo html_escape($sheet['name']); ?></strong>
            &nbsp;<span class="text-muted" style="font-weight:400;">(<?php echo number_format($sheet['count']); ?> rows)</span>
        </h4>
        <div style="display:flex;align-items:center;gap:10px;">
            <label style="margin:0;font-size:12px;font-weight:600;">Region name:</label>
            <input type="text" name="regions[<?php echo $i; ?>]"
                   value="<?php echo html_escape($sheet['region']); ?>"
                   class="form-control" style="width:200px;display:inline-block;">
        </div>
    </div>
    <div class="panel-body" style="padding:0;">
        <table class="table table-condensed" style="margin:0;font-size:12px;">
            <thead>
                <tr>
                    <?php foreach ($sheet['headers'] as $h): ?>
                    <th><?php echo html_escape(ucwords($h)); ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($sheet['sample'] as $row): ?>
            <tr>
                <?php foreach ($sheet['headers'] as $h): ?>
                <td><?php echo html_escape($row[$h] ?? ''); ?></td>
                <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>
            <?php if ($sheet['count'] > 5): ?>
            <tr><td colspan="<?php echo count($sheet['headers']); ?>" class="text-muted text-center" style="padding:8px;">
                … and <?php echo number_format($sheet['count'] - 5); ?> more rows
            </td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endforeach; ?>

<div style="margin-top:8px;">
    <button type="submit" name="confirm_import" value="1" class="btn btn-success btn-lg">
        <i class="fas fa-check"></i> Confirm Import
    </button>
    <a href="<?php echo base_url('school_directory/upload'); ?>" class="btn btn-default btn-lg" style="margin-left:10px;">
        <i class="fas fa-times"></i> Cancel
    </a>
</div>
<?php echo form_close(); ?>

<?php endif; ?>
