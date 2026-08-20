<?php
$widget = (is_superadmin_loggedin() ? 4 : 6);
$currency_symbol = $global_config['currency_symbol'];
?>

<div class="container-fluid">

    <!-- Filter Panel -->
    <section class="panel">
        <div class="panel-body">
            <?php echo form_open($this->uri->uri_string(), array('class' => 'validate')); ?>
            <div class="row">
                <?php if (is_superadmin_loggedin()): ?>
                <div class="col-md-4 form-group">
                    <label><?php echo translate('branch'); ?> <span class="required">*</span></label>
                    <?php
                    $arrayBranch = $this->app_lib->getSelectList('branch');
                    echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' id='branch_id' required data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                    ?>
                </div>
                <?php endif; ?>

                <div class="col-md-<?php echo $widget; ?> form-group">
                    <label><?php echo translate('class'); ?></label>
                    <?php
                    $arrayClass = $this->app_lib->getClass($branch_id);
                    echo form_dropdown("class_id", $arrayClass, set_value('class_id'), "class='form-control' id='class_id' onchange='getSectionByClass(this.value,0)' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                    ?>
                </div>

                <div class="col-md-<?php echo $widget; ?> form-group">
                    <label><?php echo translate('section'); ?></label>
                    <?php
                    $arraySection = $this->app_lib->getSections(set_value('class_id'), false);
                    echo form_dropdown("section_id", $arraySection, set_value('section_id'), "class='form-control' id='section_id' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                    ?>
                </div>

                <div class="col-md-4 form-group">
                    <label><?php echo translate('fees_type'); ?></label>
                    <select data-plugin-selectTwo class="form-control" name="fees_type" id="feesType"></select>
                </div>

                <div class="col-md-4 form-group">
                    <label><?php echo translate('student'); ?></label>
                    <select data-plugin-selectTwo class="form-control" name="student_id" id="student_id"></select>
                </div>

                <div class="col-md-4 form-group">
                    <label><?php echo translate('date'); ?> <span class="required">*</span></label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fas fa-calendar-check"></i></span>
                        <input type="text" class="form-control daterange" name="daterange"
                            value="<?php echo set_value('daterange', date('Y/m/d') . ' - ' . date('Y/m/d')); ?>" required>
                    </div>
                </div>

                <div class="col-md-2 form-group">
                    <label>&nbsp;</label><br>
                    <button type="submit" name="search" value="1" class="btn btn-primary btn-block">
                        <i class="fas fa-filter mr-1"></i><?php echo translate('filter'); ?>
                    </button>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </section>

    <?php if (isset($invoicelist)): ?>
    <section class="panel">
        <div class="panel-heading">
            <h4 class="panel-title">
                <i class="fas fa-list-ol mr-sm"></i><?php echo translate('student_fees_reports'); ?>
            </h4>
        </div>
        <div class="panel-body no-padding">
            <div class="export_title" style="padding:10px 15px 0;"><?php echo translate('student_fees_reports'); ?></div>
            <div class="table-responsive">
                <table class="table table-bordered table-condensed table-hover mb-none" id="rowGroup">
                    <thead>
                        <tr>
                            <th><?php echo translate('student'); ?></th>
                            <th><?php echo translate('register_no'); ?></th>
                            <th><?php echo translate('roll'); ?></th>
                            <th><?php echo translate('fees_type'); ?></th>
                            <th><?php echo translate('due_date'); ?></th>
                            <th><?php echo translate('payment_date'); ?></th>
                            <th><?php echo translate('payment_via'); ?></th>
                            <th><?php echo translate('paid_amount'); ?></th>
                            <th><?php echo translate('discount'); ?></th>
                            <th><?php echo translate('fine'); ?></th>
                            <th><?php echo translate('total'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $totalamount   = 0;
                        $totaldiscount = 0;
                        $totalfine     = 0;
                        $total         = 0;
                        foreach ($invoicelist as $row):
                            $totalamount   += $row['amount'];
                            $totaldiscount += $row['discount'];
                            $totalfine     += $row['fine'];
                            $totalp = ($row['amount'] + $row['fine']) - $row['discount'];
                            $total += $totalp;
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['register_no']); ?></td>
                            <td><?php echo htmlspecialchars($row['roll']); ?></td>
                            <td><?php echo htmlspecialchars($row['type_name']); ?></td>
                            <td><?php echo _d($row['due_date']); ?></td>
                            <td><?php echo _d($row['date']); ?></td>
                            <td><?php echo htmlspecialchars($row['pay_via']); ?></td>
                            <td><?php echo $currency_symbol . number_format($row['amount'], 2, '.', ''); ?></td>
                            <td><?php echo $currency_symbol . number_format($row['discount'], 2, '.', ''); ?></td>
                            <td><?php echo $currency_symbol . number_format($row['fine'], 2, '.', ''); ?></td>
                            <td><?php echo $currency_symbol . number_format($totalp, 2, '.', ''); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="text-weight-semibold">
                            <th colspan="7"><?php echo translate('total'); ?></th>
                            <th><?php echo $currency_symbol . number_format($totalamount,   2, '.', ''); ?></th>
                            <th><?php echo $currency_symbol . number_format($totaldiscount, 2, '.', ''); ?></th>
                            <th><?php echo $currency_symbol . number_format($totalfine,     2, '.', ''); ?></th>
                            <th><?php echo $currency_symbol . number_format($total,         2, '.', ''); ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </section>
    <?php endif; ?>

</div>

<script>
$(function(){
    $('#rowGroup').DataTable({
        dom: '<"row"<"col-sm-6 mb-sm"B><"col-sm-6"f>><"table-responsive"t>p',
        autoWidth: false,
        pageLength: 25,
        order: [[0, 'asc']],
        rowGroup: { dataSrc: 0 },
        columnDefs: [{ targets: [0], visible: false }],
        buttons: [
            { extend:'copyHtml5',  text:'<i class="far fa-copy"></i>',     titleAttr:'Copy',    title:$('.export_title').text(), exportOptions:{columns:':visible'} },
            { extend:'excelHtml5', text:'<i class="fa fa-file-excel"></i>', titleAttr:'Excel',   title:$('.export_title').text(), exportOptions:{columns:':visible'} },
            { extend:'csvHtml5',   text:'<i class="fa fa-file-alt"></i>',   titleAttr:'CSV',     title:$('.export_title').text(), exportOptions:{columns:':visible'} },
            { extend:'pdfHtml5',   text:'<i class="fa fa-file-pdf"></i>',   titleAttr:'PDF',     title:$('.export_title').text(), footer:true, exportOptions:{columns:':visible'},
              customize:function(win){ win.styles.tableHeader.fontSize=10; win.styles.tableFooter.fontSize=10; win.styles.tableHeader.alignment='left'; } },
            { extend:'print',      text:'<i class="fa fa-print"></i>',      titleAttr:'Print',   title:$('.export_title').text(), footer:true, exportOptions:{columns:':visible'},
              customize:function(win){ $(win.document.body).css('font-size','9pt').find('table').addClass('compact').css('font-size','inherit'); $(win.document.body).find('h1').css('font-size','14pt'); } },
            { extend:'colvis',     text:'<i class="fas fa-columns"></i>',   titleAttr:'Columns', postfixButtons:['colvisRestore'] }
        ]
    });

    var branchID  = "<?php echo $branch_id; ?>";
    var typeID    = "<?php echo set_value('fees_type'); ?>";
    var classID   = "<?php echo set_value('class_id'); ?>";
    var sectionID = "<?php echo set_value('section_id'); ?>";
    getTypeByBranch(branchID, typeID);
    getStudentByClass(branchID, classID, sectionID);

    $('#branch_id').on('change', function(){
        var branchID = $(this).val();
        getClassByBranch(branchID);
        getTypeByBranch(branchID);
    });

    $('#class_id').on('change', function(){
        var class_id  = $(this).val();
        var branch_id = ($('#branch_id').length ? $('#branch_id').val() : '<?php echo $branch_id; ?>');
        getSectionByClass(class_id, 0);
        getStudentByClass(branch_id, class_id, '');
    });

    $('#section_id').on('change', function(){
        var section_id = $(this).val();
        var class_id   = $('#class_id').val();
        var branch_id  = ($('#branch_id').length ? $('#branch_id').val() : '<?php echo $branch_id; ?>');
        getStudentByClass(branch_id, class_id, section_id);
    });

    function getStudentByClass(branch_id, class_id, section_id) {
        var student_id = "<?php echo set_value('student_id'); ?>";
        $.ajax({
            url: base_url + 'ajax/getStudentByClass',
            type: 'POST',
            data: $.extend({branch_id:branch_id, class_id:class_id, section_id:section_id, student_id:student_id}, csrfData),
            success: function(data){ $('#student_id').html(data); }
        });
    }

    function getTypeByBranch(branchID, typeID) {
        $.ajax({
            url: base_url + 'fees/getTypeByBranch',
            type: 'POST',
            data: $.extend({'branch_id':branchID, 'type_id':typeID || ''}, csrfData),
            success: function(data){ $('#feesType').html(data); }
        });
    }
});
</script>
