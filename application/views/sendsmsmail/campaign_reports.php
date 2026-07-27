<?php $widget = (is_superadmin_loggedin() ? 3 : 4); ?>
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

<div class="content-header">
    <div class="d-flex align-items-center justify-content-end flex-wrap gap-2">
        <a href="<?php echo base_url('sendsmsmail/sms'); ?>" class="btn btn-sm btn-outline-secondary">
            <i class="far fa-comment me-1"></i>Send SMS
        </a>
        <a href="<?php echo base_url('sendsmsmail/email'); ?>" class="btn btn-sm btn-outline-secondary">
            <i class="far fa-envelope me-1"></i>Send Email
        </a>
    </div>
</div>

<div class="container-fluid">

    <!-- Filter -->
    <div class="card mb-3">
        <div class="card-body">
            <?php echo form_open($this->uri->uri_string(), array('class' => 'validate')); ?>
            <div class="row g-3 align-items-end">
                <?php if (is_superadmin_loggedin()): ?>
                <div class="col-md-3">
                    <label class="form-label"><?php echo translate('branch'); ?> <span class="text-danger">*</span></label>
                    <?php
                    $arrayBranch = $this->app_lib->getSelectList('branch');
                    echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' required data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                    ?>
                </div>
                <?php endif; ?>
                <div class="col-md-<?php echo $widget; ?>">
                    <label class="form-label"><?php echo translate('campaign_type'); ?> <span class="text-danger">*</span></label>
                    <?php
                    $arrayType = array('' => translate('select'), '1' => 'Sms', '2' => 'Email');
                    echo form_dropdown("campaign_type", $arrayType, set_value('campaign_type'), "class='form-control' required data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                    ?>
                </div>
                <div class="col-md-<?php echo $widget; ?>">
                    <label class="form-label">Send <?php echo translate('type'); ?> <span class="text-danger">*</span></label>
                    <?php
                    $arraySendType = array('both' => translate('both'), '2' => translate('regular'), '1' => translate('Scheduled'));
                    echo form_dropdown("send_type", $arraySendType, set_value('send_type'), "class='form-control' required data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                    ?>
                </div>
                <div class="col-md-<?php echo $widget; ?>">
                    <label class="form-label"><?php echo translate('date'); ?> <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                        <input type="text" class="form-control daterange" name="daterange"
                            value="<?php echo set_value('daterange', date("Y/m/d", strtotime('-6day')) . ' - ' . date("Y/m/d")); ?>" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" name="search" value="1" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-filter me-1"></i><?php echo translate('filter'); ?>
                    </button>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>

    <?php if (isset($campaignlist)): ?>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i><?php echo translate('campaign') . ' ' . translate('reports'); ?></h5>
        </div>
        <div class="card-body p-0">
            <div class="export_title px-3 pt-2">
                <?php echo (set_value('campaign_type') == 1 ? 'Sms' : 'Email'); ?> Campaign Reports:
                <?php echo $startdate . ' to ' . $enddate; ?>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm mb-0 table-export mt-2" id="campaign-table">
                    <thead class="table-light">
                        <tr>
                            <th><?php echo translate('sl'); ?></th>
                            <th><?php echo translate('campaign_name'); ?></th>
                            <?php if (set_value('campaign_type') == 1): ?>
                            <th><?php echo translate('sms_gateway'); ?></th>
                            <?php endif; ?>
                            <th><?php echo translate('recipients_type'); ?></th>
                            <th><?php echo translate('recipients_count'); ?></th>
                            <th><?php echo translate('status'); ?></th>
                            <th><?php echo translate('created_at'); ?></th>
                            <th><?php echo translate('action'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $count = 1; foreach ($campaignlist as $row): ?>
                        <tr>
                            <td><?php echo $count++; ?></td>
                            <td><?php echo htmlspecialchars($row['campaign_name']); ?></td>
                            <?php if (set_value('campaign_type') == 1): ?>
                            <td><?php echo ucfirst($row['sms_gateway']); ?></td>
                            <?php endif; ?>
                            <td><?php
                            $rTypes = array('1' => translate('group'), '2' => translate('individual'), '3' => translate('class'));
                            echo $rTypes[$row['recipient_type']] ?? '—';
                            ?></td>
                            <td><?php echo $row['successfully_sent'] . ' / ' . $row['total_thread']; ?></td>
                            <td><?php
                            if ($row['posting_status'] == 0) echo '<span class="badge bg-warning text-dark">In Process</span>';
                            if ($row['posting_status'] == 1) echo '<span class="badge bg-info text-dark">Scheduled<br><small>' . date('Y-M-d h:i A', strtotime($row['schedule_time'])) . '</small></span>';
                            if ($row['posting_status'] == 2) echo '<span class="badge bg-success">Delivered</span>';
                            ?></td>
                            <td class="text-nowrap"><?php echo _d($row['created_at']); ?></td>
                            <td class="text-nowrap">
                                <a href="javascript:void(0);" onclick="getDetails('<?php echo $row['id']; ?>')" class="btn btn-sm btn-outline-secondary" title="<?php echo translate('details'); ?>">
                                    <i class="fas fa-list"></i>
                                </a>
                                <?php if (get_permission('sendsmsmail', 'is_delete')): ?>
                                <?php echo btn_delete('sendsmsmail/delete/' . $row['id']); ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>

<!-- Details modal -->
<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="details-modal" style="max-width:640px;width:100%;">
    <div class="card mb-0">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i><?php echo translate('details'); ?></h5>
            <button type="button" class="btn-close" onclick="$.magnificPopup.close()" title="Close"></button>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered table-sm mb-0" id="ev_table"></table>
        </div>
        <div class="card-footer text-end">
            <button class="btn btn-secondary btn-sm" onclick="$.magnificPopup.close()"><?php echo translate('close'); ?></button>
        </div>
    </div>
</div>

<script>
function getDetails(id) {
    $.ajax({
        url: base_url + 'sendsmsmail/getDetails',
        type: 'POST',
        data: $.extend({ id: id }, csrfData),
        success: function(data){
            $('#ev_table').html(data);
            mfp_modal('#details-modal');
        }
    });
}

$(function(){
    if ($('#campaign-table').length) {
        $('#campaign-table').DataTable({
            order: [[6, 'desc']],
            pageLength: 25,
            columnDefs: [{ orderable: false, targets: [-1] }]
        });
    }
});
</script>
