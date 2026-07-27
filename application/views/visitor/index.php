<style>
.visitor-stat { background:#fff; border-radius:8px; padding:18px 22px; display:flex; align-items:center; gap:14px; box-shadow:0 1px 4px rgba(0,0,0,.08); }
.visitor-stat .vs-icon { width:46px; height:46px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:1.3rem; flex-shrink:0; }
.visitor-stat .vs-val  { font-size:1.6rem; font-weight:700; line-height:1; }
.visitor-stat .vs-lbl  { font-size:.78rem; color:#888; }
.badge-in  { background:#d4edda; color:#155724; }
.badge-out { background:#f8d7da; color:#721c24; }
.v-duration { font-size:.8rem; color:#888; }
@media (prefers-color-scheme:dark){
  .visitor-stat { background:#2b2b3a; }
  .visitor-stat .vs-lbl { color:#aaa; }
}
:root[data-theme="dark"]  .visitor-stat { background:#2b2b3a; }
:root[data-theme="dark"]  .visitor-stat .vs-lbl { color:#aaa; }
:root[data-theme="light"] .visitor-stat { background:#fff; }
:root[data-theme="light"] .visitor-stat .vs-lbl { color:#888; }
</style>

<div class="content-header">
    <div class="d-flex align-items-center justify-content-end flex-wrap gap-2">
        <?php if (is_admin_loggedin() || is_superadmin_loggedin()): ?>
        <div class="d-flex gap-2">
            <a href="<?php echo base_url('visitor/report'); ?>" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-chart-bar me-1"></i>Report
            </a>
            <button class="btn btn-sm btn-primary" onclick="mfp_modal('#modal-checkin')">
                <i class="fas fa-sign-in-alt me-1"></i>Check In Visitor
            </button>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="container-fluid">

    <!-- Stats row -->
    <div class="row g-3 mb-4">
        <div class="col-sm-4">
            <div class="visitor-stat">
                <div class="vs-icon" style="background:#cfe2ff; color:#0d6efd;"><i class="fas fa-users"></i></div>
                <div>
                    <div class="vs-val"><?php echo count($visitors); ?></div>
                    <div class="vs-lbl">Visitors Today</div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="visitor-stat">
                <div class="vs-icon" style="background:#d1e7dd; color:#0f5132;"><i class="fas fa-door-open"></i></div>
                <div>
                    <div class="vs-val"><?php echo $still_in; ?></div>
                    <div class="vs-lbl">Still Inside</div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="visitor-stat">
                <div class="vs-icon" style="background:#f8d7da; color:#842029;"><i class="fas fa-door-closed"></i></div>
                <div>
                    <div class="vs-val"><?php echo count($visitors) - $still_in; ?></div>
                    <div class="vs-lbl">Checked Out</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Date filter -->
    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" action="" class="d-flex align-items-center gap-3">
                <label class="mb-0 fw-semibold">Date:</label>
                <input type="date" name="date" class="form-control form-control-sm" style="width:160px" value="<?php echo html_escape($date); ?>">
                <button class="btn btn-sm btn-secondary" type="submit">Filter</button>
                <?php if ($date !== date('Y-m-d')): ?>
                <a href="<?php echo base_url('visitor'); ?>" class="btn btn-sm btn-link">Today</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Visitor table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="visitor-table">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Visitor</th>
                            <th>ID No.</th>
                            <th>Phone</th>
                            <th>Purpose</th>
                            <th>Person to Visit</th>
                            <th>Badge</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($visitors)): ?>
                        <tr><td colspan="12" class="text-center py-4 text-muted">No visitors recorded for this date.</td></tr>
                        <?php else: foreach ($visitors as $i => $v): ?>
                        <tr>
                            <td><?php echo $i + 1; ?></td>
                            <td><strong><?php echo html_escape($v->visitor_name); ?></strong></td>
                            <td><?php echo html_escape($v->visitor_id_no); ?></td>
                            <td><?php echo html_escape($v->visitor_phone); ?></td>
                            <td><?php echo html_escape($v->purpose); ?></td>
                            <td><?php echo html_escape($v->person_to_visit); ?></td>
                            <td><?php echo html_escape($v->badge_no); ?></td>
                            <td><?php echo date('h:i A', strtotime($v->check_in)); ?></td>
                            <td><?php echo $v->check_out ? date('h:i A', strtotime($v->check_out)) : '—'; ?></td>
                            <td class="v-duration">
                                <?php
                                if ($v->check_out) {
                                    $mins = (strtotime($v->check_out) - strtotime($v->check_in)) / 60;
                                    if ($mins < 60) echo round($mins) . ' min';
                                    else echo round($mins/60, 1) . ' hr';
                                } else {
                                    $mins = (time() - strtotime($v->check_in)) / 60;
                                    if ($mins < 60) echo round($mins) . ' min (ongoing)';
                                    else echo round($mins/60, 1) . ' hr (ongoing)';
                                }
                                ?>
                            </td>
                            <td>
                                <?php if (!$v->check_out): ?>
                                <span class="badge badge-in"><i class="fas fa-circle me-1" style="font-size:.5rem"></i>In</span>
                                <?php else: ?>
                                <span class="badge badge-out"><i class="fas fa-circle me-1" style="font-size:.5rem"></i>Out</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!$v->check_out): ?>
                                <button class="btn btn-xs btn-warning" title="Check Out"
                                        onclick="doCheckout(<?php echo (int)$v->id; ?>, <?php echo htmlspecialchars(json_encode($v->visitor_name), ENT_QUOTES); ?>)">
                                    <i class="fas fa-sign-out-alt"></i>
                                </button>
                                <?php endif; ?>
                                <?php if (is_admin_loggedin() || is_superadmin_loggedin()): ?>
                                <?php echo btn_delete('visitor/delete/' . $v->id); ?>
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

<!-- Check-In Modal -->
<div id="modal-checkin" class="mfp-hide">
    <div class="card mb-0" style="min-width:520px; max-width:580px;">
        <div class="card-header d-flex align-items-center justify-content-between"><h5 class="mb-0"><i class="fas fa-sign-in-alt me-2"></i>Visitor Check-In</h5><button type="button" class="btn-close ms-2" onclick="$.magnificPopup.close()" title="Close"></button></div>
        <div class="card-body">
            <?php echo form_open('visitor/checkin', ['class' => 'frm-submit', 'id' => 'checkin-form']); ?>
            <div class="row g-3">
                <div class="col-sm-6">
                    <label class="form-label">Visitor Name <span class="text-danger">*</span></label>
                    <input type="text" name="visitor_name" class="form-control" required>
                </div>
                <div class="col-sm-6">
                    <label class="form-label">ID / Passport No.</label>
                    <input type="text" name="visitor_id_no" class="form-control" placeholder="National ID or Passport">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="visitor_phone" class="form-control" placeholder="07xx...">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Badge No.</label>
                    <input type="text" name="badge_no" class="form-control" placeholder="Badge issued">
                </div>
                <div class="col-12">
                    <label class="form-label">Purpose of Visit <span class="text-danger">*</span></label>
                    <input type="text" name="purpose" class="form-control" required placeholder="e.g. Parent-teacher meeting">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Person / Dept to Visit</label>
                    <input type="text" name="person_to_visit" class="form-control" placeholder="Teacher/Office name">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Vehicle Reg. (if any)</label>
                    <input type="text" name="vehicle_reg" class="form-control" placeholder="KXX 000A">
                </div>
            </div>
            <div class="mt-3 text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt me-1"></i> Check In
                </button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
$(function(){
    $('#visitor-table').DataTable({
        order: [[7,'desc']],
        pageLength: 25,
        columnDefs:[{orderable:false, targets:[11]}]
    });
});

function doCheckout(id, name) {
    if (!confirm('Check out ' + name + '?')) return;
    $.post(base_url + 'visitor/checkout/' + id, csrfData, function(r) {
        if (typeof r === 'string') r = JSON.parse(r);
        if (r.status === 'success') location.reload();
        else alert(r.msg || 'Error checking out visitor');
    });
}
</script>
