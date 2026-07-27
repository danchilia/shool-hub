<style>
@media (prefers-color-scheme:dark) {
    .card        { background:#2b2b3a; border-color:#3a3a50; }
    .card-header { background:#232333; border-color:#3a3a50; }
    .table-light { --bs-table-bg:#232333; }
}
:root[data-theme="dark"]  .card        { background:#2b2b3a; border-color:#3a3a50; }
:root[data-theme="dark"]  .card-header { background:#232333; border-color:#3a3a50; }
:root[data-theme="dark"]  .table-light { --bs-table-bg:#232333; }
:root[data-theme="light"] .card        { background:#fff;    border-color:#dee2e6; }
:root[data-theme="light"] .card-header { background:#f8f9fa; border-color:#dee2e6; }
:root[data-theme="light"] .table-light { --bs-table-bg:#f8f9fa; }
</style>

<?php
$notices = isset($notices) ? $notices : array();
$priorityLabels = array('normal' => 'Normal', 'important' => 'Important', 'urgent' => 'Urgent');
$priorityBadge  = array(
    'normal'    => 'bg-secondary',
    'important' => 'bg-warning text-dark',
    'urgent'    => 'bg-danger',
);
?>

<div class="content-header">
    <div class="d-flex align-items-center justify-content-end flex-wrap gap-2">
        <button class="btn btn-sm btn-primary" onclick="mfp_modal('#add-notice-modal')">
            <i class="fas fa-plus me-1"></i>Post Notice
        </button>
    </div>
</div>

<div class="container-fluid">
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0" id="notices-table">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Audience</th>
                            <th>Priority</th>
                            <th>Date</th>
                            <th>Expires</th>
                            <th>Attachment</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($notices)): ?>
                        <tr><td colspan="8" class="text-center py-4 text-muted"><i class="fas fa-info-circle me-1"></i>No notices posted yet.</td></tr>
                        <?php else: foreach ($notices as $i => $n):
                            $badgeCls = $priorityBadge[$n['priority']] ?? 'bg-secondary';
                            $badgeLbl = $priorityLabels[$n['priority']] ?? ucfirst($n['priority']);
                        ?>
                        <tr>
                            <td><?php echo $i + 1; ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($n['title']); ?></strong>
                                <?php if (!empty($n['details'])): ?>
                                <br><small class="text-muted"><?php echo htmlspecialchars(substr($n['details'], 0, 80)) . (strlen($n['details']) > 80 ? '…' : ''); ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars(ucwords(str_replace(',', ', ', $n['audience']))); ?></td>
                            <td><span class="badge <?php echo $badgeCls; ?>"><?php echo $badgeLbl; ?></span></td>
                            <td class="text-nowrap"><?php echo date('d M Y', strtotime($n['notice_date'])); ?></td>
                            <td class="text-nowrap"><?php echo !empty($n['expiry_date']) ? date('d M Y', strtotime($n['expiry_date'])) : '<span class="text-muted">—</span>'; ?></td>
                            <td>
                                <?php if (!empty($n['attachment'])): ?>
                                <a href="<?php echo base_url('uploads/notices/' . $n['attachment']); ?>" target="_blank" class="btn btn-xs btn-outline-secondary">
                                    <i class="fas fa-paperclip me-1"></i>View
                                </a>
                                <?php else: ?><span class="text-muted">—</span><?php endif; ?>
                            </td>
                            <td>
                                <?php if (is_admin_loggedin() || is_superadmin_loggedin()): ?>
                                <?php echo btn_delete('noticeboard/delete/' . $n['id']); ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Post Notice Modal -->
<div id="add-notice-modal" class="mfp-hide">
    <div class="card mb-0" style="min-width:580px; max-width:680px;">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Post New Notice</h5>
            <button type="button" class="btn-close ms-2" onclick="$.magnificPopup.close()" title="Close"></button>
        </div>
        <div class="card-body">
            <form id="notice-form" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="Notice title">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Details</label>
                        <textarea name="details" class="form-control" rows="4" placeholder="Full notice details..."></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Notice Date <span class="text-danger">*</span></label>
                        <input type="date" name="notice_date" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Expiry Date</label>
                        <input type="date" name="expiry_date" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Priority <span class="text-danger">*</span></label>
                        <select name="priority" class="form-select">
                            <option value="normal">Normal</option>
                            <option value="important">Important</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Audience <span class="text-danger">*</span></label>
                        <select name="audience[]" class="form-select" multiple style="height:120px;">
                            <option value="all" selected>Everyone</option>
                            <option value="students">Students</option>
                            <option value="parents">Parents</option>
                            <option value="staff">Staff</option>
                            <option value="teachers">Teachers</option>
                        </select>
                        <small class="text-muted">Hold Ctrl/Cmd to select multiple</small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Attachment <small class="text-muted">(PDF/Image/Doc, max 5MB)</small></label>
                        <input type="file" name="attachment" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                    </div>
                </div>
                <div class="mt-3 text-end">
                    <button type="button" class="btn btn-secondary me-1" onclick="$.magnificPopup.close()">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="notice-submit-btn">
                        <i class="fas fa-paper-plane me-1"></i>Post Notice
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(function(){
    $('#notices-table').DataTable({
        order: [[4, 'desc']],
        pageLength: 25,
        columnDefs: [{ orderable: false, targets: [7] }]
    });

    // Multipart FormData submission (frm-submit serialize() drops files)
    $('#notice-form').on('submit', function(e){
        e.preventDefault();
        var $btn = $('#notice-submit-btn');
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Posting...');

        var fd = new FormData(this);
        // Inject CSRF token
        $.each(csrfData, function(k, v){ fd.append(k, v); });

        $.ajax({
            url: base_url + 'noticeboard/add',
            type: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(res){
                if (typeof res === 'string') res = JSON.parse(res);
                if (res.status === 'success') {
                    window.location.href = res.url;
                } else {
                    alert(res.msg || 'Error saving notice.');
                    $btn.prop('disabled', false).html('<i class="fas fa-paper-plane me-1"></i>Post Notice');
                }
            },
            error: function(){
                alert('Server error. Please try again.');
                $btn.prop('disabled', false).html('<i class="fas fa-paper-plane me-1"></i>Post Notice');
            }
        });
    });
});
</script>
