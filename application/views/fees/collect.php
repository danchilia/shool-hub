<?php
$currency_symbol = $global_config['currency_symbol'];
$show_history = ($invoice['status'] != 'unpaid');
$show_collect = (get_permission('collect_fees', 'is_add') && $invoice['status'] != 'total');
?>
<style>
@media (prefers-color-scheme:dark) {
    .card        { background:#2b2b3a; border-color:#3a3a50; }
    .card-header { background:#232333; border-color:#3a3a50; }
}
:root[data-theme="dark"]  .card        { background:#2b2b3a; border-color:#3a3a50; }
:root[data-theme="dark"]  .card-header { background:#232333; border-color:#3a3a50; }
:root[data-theme="light"] .card        { background:#fff;    border-color:#dee2e6; }
:root[data-theme="light"] .card-header { background:#f8f9fa; border-color:#dee2e6; }
</style>

<div class="container-fluid">
    <div class="card">
        <div class="card-header p-0">
            <ul class="nav nav-tabs border-0 px-3 pt-2" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-invoice" role="tab">
                        <i class="far fa-credit-card me-1"></i><?php echo translate('invoice'); ?>
                    </button>
                </li>
                <?php if ($show_history): ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-history" role="tab">
                        <i class="fas fa-dollar-sign me-1"></i><?php echo translate('payment_history'); ?>
                    </button>
                </li>
                <?php endif; ?>
                <?php if ($show_collect): ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-collect" role="tab">
                        <i class="fas fa-hand-holding-usd me-1"></i><?php echo translate('collect_fees'); ?>
                    </button>
                </li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">

                <!-- Invoice tab -->
                <div class="tab-pane fade show active" id="tab-invoice" role="tabpanel">
                    <div id="invoice_print">
                        <div class="invoice">
                            <header class="clearfix">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="ib">
                                            <img src="<?php echo get_branch_logo($basic['branch_id'], 'printing_logo'); ?>" alt="Logo" style="max-height:80px;">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 text-end">
                                        <h4 class="mt-0 mb-0 text-dark">Invoice No #<?php echo $invoice['invoice_no']; ?></h4>
                                        <p class="mb-0">
                                            <span class="text-dark"><?php echo translate('date'); ?> : </span>
                                            <span class="value"><?php echo _d(date('Y-m-d')); ?></span>
                                        </p>
                                        <p class="mb-0">
                                            <span class="text-dark"><?php echo translate('status'); ?> : </span>
                                            <?php
                                            $labelmode = '';
                                            if ($invoice['status'] == 'unpaid') {
                                                $invStatus = translate('unpaid'); $labelmode = 'label-danger-custom';
                                            } elseif ($invoice['status'] == 'partly') {
                                                $invStatus = translate('partly_paid'); $labelmode = 'label-info-custom';
                                            } else {
                                                $invStatus = translate('total_paid'); $labelmode = 'label-success-custom';
                                            }
                                            echo "<span class='value label " . $labelmode . "'>" . $invStatus . "</span>";
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </header>
                            <div class="bill-info">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="bill-data">
                                            <p class="h5 mb-1 text-dark fw-semibold">Invoice To :</p>
                                            <address>
                                                <?php
                                                echo $basic['first_name'] . ' ' . $basic['last_name'] . '<br>';
                                                echo $basic['student_address'] . '<br>';
                                                echo translate('class') . ' : ' . $basic['class_name'] . '<br>';
                                                echo translate('email') . ' : ' . $basic['student_email'];
                                                ?>
                                            </address>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 text-end">
                                        <div class="bill-data">
                                            <p class="h5 mb-1 text-dark fw-semibold">Academic :</p>
                                            <address>
                                                <?php
                                                echo $basic['school_name'] . '<br>';
                                                echo $basic['school_address'] . '<br>';
                                                echo $basic['school_mobileno'] . '<br>';
                                                echo $basic['school_email'] . '<br>';
                                                ?>
                                            </address>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive br-none">
                                <table class="table invoice-items table-hover table-sm mb-0">
                                    <thead>
                                        <tr class="text-dark">
                                            <th class="fw-semibold">#</th>
                                            <th class="fw-semibold"><?php echo translate('fees_type'); ?></th>
                                            <th class="fw-semibold"><?php echo translate('due_date'); ?></th>
                                            <th class="fw-semibold"><?php echo translate('status'); ?></th>
                                            <th class="fw-semibold"><?php echo translate('amount'); ?></th>
                                            <th class="fw-semibold"><?php echo translate('discount'); ?></th>
                                            <th class="fw-semibold"><?php echo translate('fine'); ?></th>
                                            <th class="fw-semibold"><?php echo translate('paid'); ?></th>
                                            <th class="fw-semibold text-center"><?php echo translate('balance'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $count         = 1;
                                        $total_fine    = 0;
                                        $total_discount = 0;
                                        $total_paid    = 0;
                                        $total_balance = 0;
                                        $total_amount  = 0;
                                        $typeData = array('' => translate('select'));
                                        $allocations = $this->fees_model->getInvoiceDetails($basic['id']);
                                        foreach ($allocations as $row):
                                            $deposit       = $this->fees_model->getStudentFeeDeposit($row['allocation_id'], $row['fee_type_id']);
                                            $type_discount = $deposit['total_discount'];
                                            $type_fine     = $deposit['total_fine'];
                                            $type_amount   = $deposit['total_amount'];
                                            $balance       = $row['amount'] - ($type_amount + $type_discount);
                                            $total_discount += $type_discount;
                                            $total_fine    += $type_fine;
                                            $total_paid    += $type_amount;
                                            $total_balance += $balance;
                                            $total_amount  += $row['amount'];
                                            if ($balance != 0) {
                                                $typeData[$row['allocation_id'] . '|' . $row['fee_type_id']] = $row['name'];
                                            }
                                        ?>
                                        <tr>
                                            <td><?php echo $count++; ?></td>
                                            <td class="fw-semibold text-dark"><?php echo $row['name']; ?></td>
                                            <td><?php echo _d($row['due_date']); ?></td>
                                            <td><?php
                                                if ($type_amount == 0) {
                                                    $rs = translate('unpaid'); $rl = 'label-danger-custom';
                                                } elseif ($balance == 0) {
                                                    $rs = translate('total_paid'); $rl = 'label-success-custom';
                                                } else {
                                                    $rs = translate('partly_paid'); $rl = 'label-info-custom';
                                                }
                                                echo "<span class='label " . $rl . "'>" . $rs . "</span>";
                                            ?></td>
                                            <td><?php echo $currency_symbol . $row['amount']; ?></td>
                                            <td><?php echo $currency_symbol . $type_discount; ?></td>
                                            <td><?php echo $currency_symbol . $type_fine; ?></td>
                                            <td><?php echo $currency_symbol . $type_amount; ?></td>
                                            <td class="text-center"><?php echo $currency_symbol . number_format($balance, 2, '.', ''); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="invoice-summary text-end mt-4">
                                <div class="row">
                                    <div class="col-lg-5 ms-auto">
                                        <ul class="amounts">
                                            <li><strong><?php echo translate('grand_total'); ?> :</strong> <?php echo $currency_symbol . number_format($total_amount,  2, '.', ''); ?></li>
                                            <li><strong><?php echo translate('paid'); ?> :</strong> <?php echo $currency_symbol . number_format($total_paid,    2, '.', ''); ?></li>
                                            <li><strong><?php echo translate('discount'); ?> :</strong> <?php echo $currency_symbol . number_format($total_discount,2, '.', ''); ?></li>
                                            <li><strong><?php echo translate('fine'); ?> :</strong> <?php echo $currency_symbol . number_format($total_fine,    2, '.', ''); ?></li>
                                            <?php if ($total_balance != 0): ?>
                                            <li>
                                                <strong><?php echo translate('balance'); ?> : </strong>
                                                <?php
                                                $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                                                echo $currency_symbol . number_format($total_balance, 2, '.', '') . ' <br>( ' . ucwords($f->format($total_balance)) . ' )';
                                                ?>
                                            </li>
                                            <?php else: ?>
                                            <li>
                                                <strong><?php echo translate('total_paid'); ?> : </strong>
                                                <?php
                                                $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                                                echo $currency_symbol . number_format(($total_paid + $total_fine), 2, '.', '') . ' <br>( ' . ucwords($f->format(($total_paid + $total_fine))) . ' )';
                                                ?>
                                            </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-print-none text-end me-4 mt-2">
                            <button onclick="fn_printElem('invoice_print')" class="btn btn-outline-secondary ms-2">
                                <i class="fas fa-print"></i> <?php echo translate('print'); ?>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Payment history tab -->
                <?php if ($show_history): ?>
                <div class="tab-pane fade" id="tab-history" role="tabpanel">
                    <div id="payment_print">
                        <div class="invoice payment">
                            <header class="clearfix">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="ib">
                                            <img src="<?php echo get_branch_logo($basic['branch_id'], 'printing_logo'); ?>" alt="Logo" style="max-height:80px;">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 text-end">
                                        <h4 class="mt-0 mb-0 text-dark">Invoice No #<?php echo $invoice['invoice_no']; ?></h4>
                                        <p class="mb-0">
                                            <span class="text-dark"><?php echo translate('date'); ?> : </span>
                                            <span class="value"><?php echo _d(date('Y-m-d')); ?></span>
                                        </p>
                                        <p class="mb-0">
                                            <span class="text-dark"><?php echo translate('status'); ?> : </span>
                                            <?php echo "<span class='value label " . $labelmode . "'>" . $invStatus . "</span>"; ?>
                                        </p>
                                    </div>
                                </div>
                            </header>
                            <div class="bill-info">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="bill-data">
                                            <p class="h5 mb-1 text-dark fw-semibold">Invoice To :</p>
                                            <address>
                                                <?php
                                                echo $basic['first_name'] . ' ' . $basic['last_name'] . '<br>';
                                                echo $basic['student_address'] . '<br>';
                                                echo translate('class') . ' : ' . $basic['class_name'] . '<br>';
                                                echo translate('email') . ' : ' . $basic['student_email'];
                                                ?>
                                            </address>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 text-end">
                                        <div class="bill-data">
                                            <p class="h5 mb-1 text-dark fw-semibold">Academic :</p>
                                            <address>
                                                <?php
                                                echo $basic['school_name'] . '<br>';
                                                echo $basic['school_address'] . '<br>';
                                                echo $basic['school_mobileno'] . '<br>';
                                                echo $basic['school_email'] . '<br>';
                                                ?>
                                            </address>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table invoice-items table-sm">
                                    <thead>
                                        <tr class="h5 text-dark">
                                            <th class="fw-semibold d-print-none">
                                                <div class="form-check mb-0" data-bs-toggle="tooltip" title="Print Show / Hidden">
                                                    <input class="form-check-input" type="checkbox" id="selectAllchkbox" checked>
                                                </div>
                                            </th>
                                            <th class="fw-semibold"><?php echo translate('fees_type'); ?></th>
                                            <th class="fw-semibold"><?php echo translate('fees_code'); ?></th>
                                            <th class="fw-semibold"><?php echo translate('date'); ?></th>
                                            <th class="fw-semibold d-print-none"><?php echo translate('collect_by'); ?></th>
                                            <th class="fw-semibold"><?php echo translate('remarks'); ?></th>
                                            <th class="fw-semibold"><?php echo translate('method'); ?></th>
                                            <th class="fw-semibold"><?php echo translate('amount'); ?></th>
                                            <th class="fw-semibold"><?php echo translate('discount'); ?></th>
                                            <th class="fw-semibold"><?php echo translate('fine'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $histAllocs = $this->db->where(array('student_id' => $basic['id'], 'session_id' => get_session_id()))->get('fee_allocation')->result_array();
                                        foreach ($histAllocs as $allRow):
                                            $historys = $this->fees_model->getPaymentHistory($allRow['id'], $allRow['group_id']);
                                            foreach ($historys as $row):
                                        ?>
                                        <tr>
                                            <td class="d-print-none checked-area">
                                                <div class="form-check mb-0">
                                                    <input class="form-check-input chk-print" type="checkbox" name="chkPrint" checked>
                                                </div>
                                            </td>
                                            <td class="fw-semibold text-dark"><?php echo $row['name']; ?></td>
                                            <td><?php echo $row['fee_code']; ?></td>
                                            <td><?php echo _d($row['date']); ?></td>
                                            <td class="d-print-none"><?php
                                                if ($row['collect_by'] == 'online') {
                                                    echo translate('online');
                                                } else {
                                                    echo get_type_name_by_id('staff', $row['collect_by']);
                                                }
                                            ?></td>
                                            <td><?php echo $row['remarks']; ?></td>
                                            <td><?php echo $row['payvia']; ?></td>
                                            <td><?php echo $currency_symbol . $row['amount']; ?></td>
                                            <td><?php echo $currency_symbol . $row['discount']; ?></td>
                                            <td><?php echo $currency_symbol . $row['fine']; ?></td>
                                        </tr>
                                        <?php endforeach; endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="invoice-summary text-end mt-4">
                                <div class="row">
                                    <div class="col-lg-5 ms-auto">
                                        <ul class="amounts">
                                            <li><strong><?php echo translate('grand_total'); ?> :</strong> <?php echo $currency_symbol . number_format($total_amount,  2, '.', ''); ?></li>
                                            <li><strong><?php echo translate('paid'); ?> :</strong> <?php echo $currency_symbol . number_format($total_paid,    2, '.', ''); ?></li>
                                            <li><strong><?php echo translate('discount'); ?> :</strong> <?php echo $currency_symbol . number_format($total_discount,2, '.', ''); ?></li>
                                            <li><strong><?php echo translate('fine'); ?> :</strong> <?php echo $currency_symbol . number_format($total_fine,    2, '.', ''); ?></li>
                                            <?php if ($total_balance != 0): ?>
                                            <li>
                                                <strong><?php echo translate('balance'); ?> : </strong>
                                                <?php
                                                $fh = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                                                echo $currency_symbol . number_format($total_balance, 2, '.', '') . ' <br>( ' . ucwords($fh->format($total_balance)) . ' )';
                                                ?>
                                            </li>
                                            <?php else: ?>
                                            <li>
                                                <strong><?php echo translate('total_paid'); ?> : </strong>
                                                <?php
                                                $fh = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                                                echo $currency_symbol . number_format(($total_paid + $total_fine), 2, '.', '') . ' <br>( ' . ucwords($fh->format(($total_paid + $total_fine))) . ' )';
                                                ?>
                                            </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-print-none text-end me-4 mt-2">
                            <button onclick="fn_printElem('payment_print')" class="btn btn-outline-secondary">
                                <i class="fas fa-print"></i> <?php echo translate('print'); ?>
                            </button>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Collect fees tab -->
                <?php if ($show_collect): ?>
                <div class="tab-pane fade" id="tab-collect" role="tabpanel">
                    <?php echo form_open('fees/fee_add', array(
                        'class'                      => 'frm-submit-offline',
                        'data-offline-label'         => 'Fee Payment - ' . $basic['first_name'] . ' ' . $basic['last_name'],
                        'data-reload-on-success'     => '1',
                        'data-reset-on-offline-save' => '1',
                    )); ?>
                    <div class="row mb-3">
                        <label class="col-md-3 col-form-label">
                            <?php echo translate('fees_type'); ?> <span class="text-danger">*</span>
                            <?php echo help_tip('Select which fee the parent is paying for. Example: Tuition Fee, Lunch Programme, Activity Fee. The balance auto-fills.'); ?>
                        </label>
                        <div class="col-md-6">
                            <?php echo form_dropdown("fees_type", $typeData, set_value('fees_type'), "class='form-control' id='fees_type' data-plugin-selectTwo data-width='100%'"); ?>
                            <span class="error small text-danger d-block"></span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-md-3 col-form-label">
                            <?php echo translate('date'); ?> <span class="text-danger">*</span>
                            <?php echo help_tip('Date the payment was received. Can be backdated for past payments. Example: 2026-06-15'); ?>
                        </label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" data-plugin-datepicker data-plugin-options='{"todayHighlight":true}' name="date" value="<?php echo date('Y-m-d'); ?>" autocomplete="off">
                            <span class="error small text-danger d-block"></span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-md-3 col-form-label">
                            <?php echo translate('amount'); ?> <span class="text-danger">*</span>
                            <?php echo help_tip('Amount being paid. Can be partial - parent does not have to pay full amount at once. Example: 10000'); ?>
                        </label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="amount" id="feeAmount" value="" autocomplete="off">
                            <span class="error small text-danger d-block"></span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-md-3 col-form-label">
                            <?php echo translate('discount'); ?>
                            <?php echo help_tip('Enter discount amount if any. Leave as 0 if no discount. Example: 500 for KES 500 off'); ?>
                        </label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="discount_amount" value="0" autocomplete="off">
                            <span class="error small text-danger d-block"></span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-md-3 col-form-label"><?php echo translate('fine'); ?></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="fine_amount" id="fineAmount" value="0" autocomplete="off">
                            <span class="error small text-danger d-block"></span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-md-3 col-form-label">
                            <?php echo translate('payment_method'); ?> <span class="text-danger">*</span>
                            <?php echo help_tip('How the parent paid. Cash = physical cash, M-Pesa = mobile money (add M-Pesa code in remarks), Cheque = bank cheque, Bank Transfer = direct deposit'); ?>
                        </label>
                        <div class="col-md-6">
                            <?php
                            $payvia_list = $this->app_lib->getSelectList('payment_types');
                            echo form_dropdown("pay_via", $payvia_list, set_value('pay_via'), "class='form-control' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                            ?>
                            <span class="error small text-danger d-block"></span>
                        </div>
                    </div>
                    <?php
                    $links = $this->fees_model->get('transactions_links', array('branch_id' => $basic['branch_id']), true);
                    if ($links['status'] == 1):
                    ?>
                    <div class="row mb-3">
                        <label class="col-md-3 col-form-label"><?php echo translate('account'); ?> <span class="text-danger">*</span></label>
                        <div class="col-md-6">
                            <?php
                            $accounts_list = $this->app_lib->getSelectByBranch('accounts', $basic['branch_id']);
                            echo form_dropdown("account_id", $accounts_list, $links['deposit'], "class='form-control' id='account_id' required data-plugin-selectTwo data-width='100%'");
                            ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="row mb-3">
                        <label class="col-md-3 col-form-label"><?php echo translate('remarks'); ?></label>
                        <div class="col-md-6">
                            <textarea name="remarks" rows="2" class="form-control" placeholder="<?php echo translate('write_your_remarks'); ?>"></textarea>
                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" name="guardian_sms" id="chkGuardianSms" checked>
                                <label class="form-check-label" for="chkGuardianSms">Guardian Confirmation SMS</label>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="branch_id" value="<?php echo $basic['branch_id']; ?>">
                    <input type="hidden" name="student_id" value="<?php echo $basic['id']; ?>">
                    <div class="row mb-3">
                        <div class="col-md-6 offset-md-3">
                            <button type="submit" class="btn btn-primary" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
                                <?php echo translate('fee_payment'); ?>
                            </button>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<script>
$(function(){
    $('#selectAllchkbox').on('change', function(){
        var checked = this.checked;
        $('.chk-print').each(function(){
            $(this).prop('checked', checked).closest('tr').toggleClass('d-print-none', !checked);
        });
    });
    $('.chk-print').on('change', function(){
        $(this).closest('tr').toggleClass('d-print-none', !this.checked);
    });

    $('#fees_type').on('change', function(){
        $.ajax({
            url: base_url + 'fees/getBalanceByType',
            type: 'POST',
            data: $.extend({'typeID': $(this).val()}, csrfData),
            dataType: 'json',
            success: function(data){
                $('#feeAmount').val(data.balance.toFixed(2));
                $('#fineAmount').val(data.fine.toFixed(2));
            }
        });
    });
});
</script>
