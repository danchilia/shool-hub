<?php
$widget = (is_superadmin_loggedin() ? 3 : 4);
$currency_symbol = $global_config['currency_symbol'];
?>
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

<div class="container-fluid">
    <div class="card mb-3">
        <div class="card-body">
            <?php echo form_open($this->uri->uri_string(), array('class' => 'validate')); ?>
            <div class="row g-3 align-items-end">
                <?php if (is_superadmin_loggedin()): ?>
                <div class="col-md-3">
                    <label class="form-label"><?php echo translate('branch'); ?> <span class="text-danger">*</span></label>
                    <?php
                    $arrayBranch = $this->app_lib->getSelectList('branch');
                    echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' id='branch_id' required data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                    ?>
                </div>
                <?php endif; ?>
                <div class="col-md-<?php echo $widget; ?>">
                    <label class="form-label"><?php echo translate('class'); ?> <span class="text-danger">*</span></label>
                    <?php
                    $arrayClass = $this->app_lib->getClass($branch_id);
                    echo form_dropdown("class_id", $arrayClass, set_value('class_id'), "class='form-control' id='class_id' onchange='getSectionByClass(this.value,1)' required data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                    ?>
                </div>
                <div class="col-md-<?php echo $widget; ?>">
                    <label class="form-label"><?php echo translate('section'); ?> <span class="text-danger">*</span></label>
                    <?php
                    $arraySection = $this->app_lib->getSections(set_value('class_id'), true);
                    echo form_dropdown("section_id", $arraySection, set_value('section_id'), "class='form-control' id='section_id' required data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                    ?>
                </div>
                <div class="col-md-<?php echo $widget; ?>">
                    <label class="form-label"><?php echo translate('fees_type'); ?> <span class="text-danger">*</span></label>
                    <select data-plugin-selectTwo class="form-control" name="fees_type" id="feesType" required></select>
                </div>
                <div class="col-md-2">
                    <button type="submit" name="search" value="1" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i><?php echo translate('filter'); ?>
                    </button>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>

    <?php if (isset($invoicelist)): ?>
    <div class="card">
        <?php echo form_open('fees/invoicePrint', array('class' => 'printIn')); ?>
        <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h6 class="mb-0"><i class="fas fa-list-ol me-2"></i><?php echo translate('due_invoice') . ' ' . translate('list'); ?></h6>
            <button type="submit" class="btn btn-sm btn-outline-secondary" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
                <i class="fas fa-print me-1"></i><?php echo translate('generate'); ?>
            </button>
        </div>
        <div class="card-body p-0">
            <div class="export_title px-3 pt-2"><?php echo translate('due_invoice') . ' ' . translate('list'); ?></div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm mb-0 table-export">
                    <thead class="table-light">
                        <tr>
                            <th class="d-print-none" width="50">
                                <div class="form-check mb-0" data-bs-toggle="tooltip" title="Print Show / Hidden">
                                    <input class="form-check-input" type="checkbox" id="selectAllchkbox">
                                </div>
                            </th>
                            <th><?php echo translate('student'); ?></th>
                            <th><?php echo translate('register_no'); ?></th>
                            <th><?php echo translate('roll'); ?></th>
                            <th><?php echo translate('mobile_no'); ?></th>
                            <th><?php echo translate('fee_group'); ?></th>
                            <th><?php echo translate('due_date'); ?></th>
                            <th><?php echo translate('amount'); ?></th>
                            <th><?php echo translate('paid'); ?></th>
                            <th><?php echo translate('discount'); ?></th>
                            <th><?php echo translate('balance'); ?></th>
                            <th><?php echo translate('action'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($invoicelist as $row):
                            $paid = $row['total_amount'] + $row['total_discount'];
                            if ((float)$row['full_amount'] <= (float)$paid) { continue; }
                        ?>
                        <tr>
                            <td class="d-print-none checked-area">
                                <div class="form-check mb-0">
                                    <input class="form-check-input row-chkbox" type="checkbox" name="student_id[]" value="<?php echo $row['student_id']; ?>">
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['register_no']); ?></td>
                            <td><?php echo htmlspecialchars($row['roll']); ?></td>
                            <td><?php echo htmlspecialchars($row['mobileno']); ?></td>
                            <td><?php
                                foreach ($row['feegroup'] as $value) {
                                    echo '- ' . htmlspecialchars($value['name']) . '<br>';
                                }
                            ?></td>
                            <td><?php echo _d($row['due_date']); ?></td>
                            <td><?php echo $currency_symbol . $row['full_amount']; ?></td>
                            <td><?php echo $currency_symbol . $row['total_amount']; ?></td>
                            <td><?php echo $currency_symbol . $row['total_discount']; ?></td>
                            <td><?php echo $currency_symbol . ($row['full_amount'] - $paid); ?></td>
                            <td class="text-nowrap">
                                <?php if (get_permission('collect_fees', 'is_add')): ?>
                                <a href="<?php echo base_url('fees/invoice/' . $row['student_id']); ?>" class="btn btn-sm btn-outline-secondary">
                                    <i class="far fa-arrow-alt-circle-right"></i> <?php echo translate('collect'); ?>
                                </a>
                                <?php endif; ?>
                                <a class="btn btn-sm btn-danger" onclick="confirm_modal('<?php echo base_url('fees/invoice_delete/' . $row['student_id']); ?>')">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
    <?php endif; ?>
</div>

<script>
$(function(){
    var branchID = "<?php echo $branch_id; ?>";
    var typeID   = "<?php echo set_value('fees_type'); ?>";
    getTypeByBranch(branchID, typeID);

    $('#selectAllchkbox').on('change', function(){
        var checked = this.checked;
        $('.row-chkbox').each(function(){
            $(this).prop('checked', checked).closest('tr').toggleClass('d-print-none', !checked);
        });
    });
    $('.row-chkbox').on('change', function(){
        $(this).closest('tr').toggleClass('d-print-none', !this.checked);
    });

    $('form.printIn').on('submit', function(e){
        e.preventDefault();
        var btn = $(this).find('[type="submit"]');
        var origHtml = btn.html();
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $.extend($(this).serializeArray().reduce(function(o,f){ o[f.name]=f.value; return o; }, {}), csrfData),
            dataType: 'html',
            beforeSend: function(){ btn.prop('disabled', true).html("<i class='fas fa-spinner fa-spin'></i> Processing"); },
            success: function(data){ fn_printElem(data, true); },
            error: function(){ alert('An error occurred, please try again'); },
            complete: function(){ btn.prop('disabled', false).html(origHtml); }
        });
    });

    $('#branch_id').on('change', function(){
        var branchID = $(this).val();
        getClassByBranch(branchID);
        getTypeByBranch(branchID);
    });

    function getTypeByBranch(branchID, typeID) {
        $.ajax({
            url: base_url + 'fees/getTypeByBranch',
            type: 'POST',
            data: $.extend({'branch_id': branchID, 'type_id': typeID || ''}, csrfData),
            success: function(data){ $('#feesType').html(data); }
        });
    }
});
</script>
