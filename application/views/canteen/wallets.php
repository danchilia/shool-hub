<div class="content-header">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h4 class="mb-0"><i class="fas fa-wallet me-2 text-info"></i>Canteen Wallets</h4>
        <div class="d-flex gap-2">
            <a href="<?php echo base_url('canteen/pos'); ?>" class="btn btn-sm btn-success"><i class="fas fa-cash-register me-1"></i>POS</a>
            <button class="btn btn-sm btn-primary" onclick="mfp_modal('#modal-topup')"><i class="fas fa-plus me-1"></i>Top Up Wallet</button>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="wallets-table">
                    <thead class="table-light">
                        <tr><th>#</th><th>Adm No.</th><th>Student Name</th><th>Balance (KES)</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <?php if (empty($wallets)): ?>
                        <tr><td colspan="5" class="text-center py-4 text-muted">No wallets yet. Top up a student to create one.</td></tr>
                        <?php else: foreach ($wallets as $i => $w): ?>
                        <tr>
                            <td><?php echo $i+1; ?></td>
                            <td><?php echo html_escape($w->admission_no); ?></td>
                            <td><?php echo html_escape($w->student_name); ?></td>
                            <td>
                                <strong class="<?php echo $w->balance < 50 ? 'text-danger' : 'text-success'; ?>">
                                    <?php echo number_format($w->balance, 2); ?>
                                </strong>
                            </td>
                            <td>
                                <button class="btn btn-xs btn-outline-success"
                                        onclick="openTopup(<?php echo $w->student_id; ?>, '<?php echo html_escape(addslashes($w->student_name)); ?>')">
                                    <i class="fas fa-plus"></i> Top Up
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="modal-topup" class="mfp-hide">
    <div class="card mb-0" style="min-width:420px; max-width:480px;">
        <div class="card-header d-flex align-items-center justify-content-between"><h5 class="mb-0" id="topup-title"><i class="fas fa-wallet me-2"></i>Top Up Wallet</h5><button type="button" class="btn-close ms-2" onclick="$.magnificPopup.close()" title="Close"></button></div>
        <div class="card-body">
            <?php echo form_open('canteen/topup', ['class'=>'frm-submit']); ?>
            <input type="hidden" name="student_id" id="topup-student-id">
            <div class="mb-3">
                <label class="form-label">Student</label>
                <select id="topup-student-select" name="student_id_sel" class="form-select select2">
                    <option value="">-- Select Student --</option>
                    <?php foreach ($students as $s): ?>
                    <option value="<?php echo $s->id; ?>"><?php echo html_escape($s->first_name.' '.$s->last_name); ?> (<?php echo html_escape($s->register_no); ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Amount (KES) <span class="text-danger">*</span></label>
                <input type="number" name="amount" class="form-control" step="50" min="1" required placeholder="e.g. 500">
            </div>
            <div class="mb-3">
                <label class="form-label">Notes</label>
                <input type="text" name="notes" class="form-control" placeholder="e.g. Cash received from parent">
            </div>
            <div class="text-end"><button class="btn btn-success" type="submit"><i class="fas fa-plus me-1"></i>Top Up</button></div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
$(function(){ $('#wallets-table').DataTable({order:[[3,'desc']],pageLength:50}); });
function openTopup(id, name) {
    document.getElementById('topup-student-id').value = id;
    document.getElementById('topup-title').innerHTML = '<i class="fas fa-wallet me-2"></i>Top Up: '+name;
    if ($('#topup-student-select').length) {
        $('#topup-student-select').val(id).trigger('change.select2');
    }
    mfp_modal('#modal-topup');
}

// Override the hidden field from select change
$('#topup-student-select').on('change', function(){
    document.getElementById('topup-student-id').value = this.value;
});
</script>
