<?php
$notices = isset($notices) ? $notices : array();
$priorityLabels = array('normal' => 'Normal', 'important' => 'Important', 'urgent' => 'Urgent');
$priorityBadge  = array('normal' => 'badge-secondary', 'important' => 'badge-warning', 'urgent' => 'badge-danger');
?>
<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <div class="panel-btn">
                    <button class="btn btn-default btn-circle" onclick="mfp_modal('#add-notice-modal')">
                        <i class="fas fa-plus"></i> Post Notice
                    </button>
                </div>
                <h4 class="panel-title"><i class="fas fa-bullhorn"></i> Manage Notices</h4>
            </header>
            <div class="panel-body">
                <?php if (empty($notices)): ?>
                    <p class="text-center text-muted mt-md mb-md"><i class="fas fa-info-circle"></i> No notices posted yet.</p>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed table-hover table-export">
                        <thead>
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
                        <?php foreach ($notices as $i => $n): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><strong><?= htmlspecialchars($n['title']) ?></strong>
                                    <?php if (!empty($n['details'])): ?>
                                    <br><small class="text-muted"><?= htmlspecialchars(substr($n['details'], 0, 80)) . (strlen($n['details']) > 80 ? '…' : '') ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars(ucwords(str_replace(',', ', ', $n['audience']))) ?></td>
                                <td><span class="badge <?= $priorityBadge[$n['priority']] ?? 'badge-secondary' ?>"><?= $priorityLabels[$n['priority']] ?? ucfirst($n['priority']) ?></span></td>
                                <td><?= date('d M Y', strtotime($n['notice_date'])) ?></td>
                                <td><?= !empty($n['expiry_date']) ? date('d M Y', strtotime($n['expiry_date'])) : '<span class="text-muted">—</span>' ?></td>
                                <td>
                                    <?php if (!empty($n['attachment'])): ?>
                                        <a href="<?= base_url('uploads/notices/' . $n['attachment']) ?>" target="_blank" class="btn btn-default btn-xs">
                                            <i class="fas fa-paperclip"></i> View
                                        </a>
                                    <?php else: echo '<span class="text-muted">—</span>'; endif; ?>
                                </td>
                                <td>
                                    <?php if (is_admin_loggedin() || is_superadmin_loggedin()): ?>
                                    <?= btn_delete('noticeboard/delete/' . $n['id']) ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>

<!-- Add Notice Modal -->
<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="add-notice-modal">
    <section class="panel">
        <header class="panel-heading" style="display:flex;align-items:center;justify-content:space-between;">
            <h4 class="panel-title"><i class="fas fa-plus-circle"></i> Post New Notice</h4>
            <button type="button" class="close" onclick="$.magnificPopup.close()" style="font-size:1.4rem;line-height:1;opacity:.7;" title="Close">&times;</button>
        </header>
        <?php echo form_open_multipart('noticeboard/add', array('class' => 'frm-submit')); ?>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Title <span class="required">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="Notice title" required>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Details</label>
                        <textarea name="details" class="form-control" rows="4" placeholder="Full notice details..."></textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Notice Date <span class="required">*</span></label>
                        <input type="date" name="notice_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Expiry Date</label>
                        <input type="date" name="expiry_date" class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Priority <span class="required">*</span></label>
                        <select name="priority" class="form-control">
                            <option value="normal">Normal</option>
                            <option value="important">Important</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Audience <span class="required">*</span></label>
                        <select name="audience[]" class="form-control" multiple style="height:120px;">
                            <option value="all" selected>Everyone</option>
                            <option value="students">Students</option>
                            <option value="parents">Parents</option>
                            <option value="staff">Staff</option>
                            <option value="teachers">Teachers</option>
                        </select>
                        <small class="text-muted">Hold Ctrl/Cmd to select multiple</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Attachment <small class="text-muted">(PDF/Image/Doc, max 5MB)</small></label>
                        <input type="file" name="attachment" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                    </div>
                </div>
            </div>
        </div>
        <footer class="panel-footer">
            <div class="row">
                <div class="col-md-12 text-right">
                    <button type="button" class="btn btn-default modal-dismiss">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Post Notice
                    </button>
                </div>
            </div>
        </footer>
        <?php echo form_close(); ?>
    </section>
</div>
