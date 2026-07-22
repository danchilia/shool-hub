<?php
$currency_symbol = $global_config['currency_symbol'];
$allocations = $this->fees_model->getInvoiceDetails($basic['id']);
if (count($allocations)) {
	?>
	<section class="panel">
		<div class="tabs-custom">
			<ul class="nav nav-tabs">
				<li class="active">
					<a href="#invoice" data-toggle="tab"><i class="far fa-credit-card"></i> <?=translate('invoice')?></a>
				</li>
	<?php if ($invoice['status'] != 'unpaid'): ?>
				<li>
					<a href="#history" data-toggle="tab"><i class="fas fa-dollar-sign"></i> <?=translate('payment_history')?></a>
				</li>
	<?php endif; ?>
	<?php if ($invoice['status'] != 'total'): ?>
				<li>
					<a href="#collect_fees" data-toggle="tab"><i class="far fa-credit-card"></i> <?=translate('online_pay')?></a>
				</li>
	<?php endif; ?>
			</ul>
			<div class="tab-content">
				<div id="invoice" class="tab-pane <?=empty($this->session->flashdata('pay_tab')) ? 'active' : ''; ?>">
					<div id="invoice_print">
						<div class="invoice">
							<header class="clearfix">
								<div class="row">
									<div class="col-xs-6">
										<div class="ib">
											<img src="<?=get_branch_logo(get_loggedin_branch_id(), 'printing_logo')?>" alt="RamomCoder Img" />
										</div>
									</div>
									<div class="col-md-6 text-right">
										<h4 class="mt-none mb-none text-dark">Invoice No #<?=$invoice['invoice_no']?></h4>
										<p class="mb-none">
											<span class="text-dark"><?=translate('date')?> : </span>
											<span class="value"><?=_d(date('Y-m-d'))?></span>
										</p>
										<p class="mb-none">
											<span class="text-dark"><?=translate('status')?> : </span>
											<?php
												$labelmode = '';
												if($invoice['status'] == 'unpaid') {
													$status = translate('unpaid');
													$labelmode = 'label-danger-custom';
												} elseif($invoice['status'] == 'partly') {
													$status = translate('partly_paid');
													$labelmode = 'label-info-custom';
												} elseif($invoice['status'] == 'total') {
													$status = translate('total_paid');
													$labelmode = 'label-success-custom';
												}
												echo "<span class='value label " . $labelmode . " '>" . $status . "</span>";
											?>
										</p>
									</div>
								</div>
							</header>
							<div class="bill-info">
								<div class="row">
									<div class="col-xs-6">
										<div class="bill-data">
											<p class="h5 mb-xs text-dark text-weight-semibold">Invoice To :</p>
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
									<div class="col-xs-6">
										<div class="bill-data text-right">
											<p class="h5 mb-xs text-dark text-weight-semibold">Academic :</p>
											<address>
												<?php 
												echo $basic['school_name'] . "<br/>";
												echo $basic['school_address'] . "<br/>";
												echo $basic['school_mobileno'] . "<br/>";
												echo $basic['school_email'] . "<br/>";
												?>
											</address>
										</div>
									</div>
								</div>
							</div>

							<div class="table-responsive br-none">
								<table class="table invoice-items table-hover mb-none">
									<thead>
										<tr class="text-dark">
											<th id="cell-id" class="text-weight-semibold">#</th>
											<th id="cell-item" class="text-weight-semibold"><?=translate("fees_type")?></th>
											<th id="cell-id" class="text-weight-semibold"><?=translate("due_date")?></th>
											<th id="cell-price" class="text-weight-semibold"><?=translate("status")?></th>
											<th id="cell-price" class="text-weight-semibold"><?=translate("amount")?></th>
											<th id="cell-price" class="text-weight-semibold"><?=translate("discount")?></th>
											<th id="cell-price" class="text-weight-semibold"><?=translate("fine")?></th>
											<th id="cell-price" class="text-weight-semibold"><?=translate("paid")?></th>
											<th id="cell-total" class="text-center text-weight-semibold"><?=translate("balance")?></th>
										</tr>
									</thead>
									<tbody>
										<?php
											$count = 1;
											$total_fine = 0;
											$total_discount = 0;
											$total_paid = 0;
											$total_balance = 0;
											$total_amount = 0;
											$typeData = array('' => translate('select'));
											foreach ($allocations as $row) {
												$deposit = $this->fees_model->getStudentFeeDeposit($row['allocation_id'], $row['fee_type_id']);
												$type_discount = $deposit['total_discount'];
												$type_fine = $deposit['total_fine'];
												$type_amount = $deposit['total_amount'];
												$balance = $row['amount'] - ($type_amount + $type_discount);
												$total_discount += $type_discount;
												$total_fine += $type_fine;
												$total_paid += $type_amount;
												$total_balance += $balance;
												$total_amount += $row['amount'];
												if ($balance != 0) {
												 	$typeData[$row['allocation_id'] . "|" . $row['fee_type_id']] = $row['name'];
												}
											?>
										<tr>
											<td><?php echo $count++;?></td>
											<td class="text-weight-semibold text-dark"><?=$row['name']?></td>
											<td><?=_d($row['due_date'])?></td>
											<td><?php 
												$status = 0;
												$labelmode = '';
												if($type_amount == 0) {
													$status = translate('unpaid');
													$labelmode = 'label-danger-custom';
												} elseif($balance == 0) {
													$status = translate('total_paid');
													$labelmode = 'label-success-custom';
												} else {
													$status = translate('partly_paid');
													$labelmode = 'label-info-custom';
												}
												echo "<span class='label ".$labelmode." '>".$status."</span>";
											?></td>
											<td><?php echo $currency_symbol . $row['amount'];?></td>
											<td><?php echo $currency_symbol . $type_discount;?></td>
											<td><?php echo $currency_symbol . $type_fine;?></td>
											<td><?php echo $currency_symbol . $type_amount;?></td>
											<td class="text-center"><?php echo $currency_symbol . number_format($balance, 2, '.', '');?></td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
							<div class="invoice-summary text-right mt-lg">
								<div class="row">
									<div class="col-lg-5 pull-right">
										<ul class="amounts">
											<li><strong><?=translate('grand_total')?> :</strong> <?=$currency_symbol . number_format($total_amount, 2, '.', ''); ?></li>
											<li><strong><?=translate('paid')?> :</strong> <?=$currency_symbol . number_format($total_paid, 2, '.', ''); ?></li>
											<li><strong><?=translate('discount')?> :</strong> <?=$currency_symbol . number_format($total_discount, 2, '.', ''); ?></li>
											<li><strong><?=translate('fine')?> :</strong> <?=$currency_symbol . number_format($total_fine, 2, '.', ''); ?></li>
											<?php if ($total_balance != 0): ?>
											<li>
												<strong><?=translate('balance')?> : </strong> 
												<?php
												$f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
												echo $currency_symbol . number_format($total_balance, 2, '.', '') . ' </br>( ' . ucwords($f->format($total_balance)) . ' )' ;
												?>
											</li>
											<?php else: ?>
											<li>
												<strong><?=translate('total_paid')?> : </strong> 
												<?php
												$f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
												echo $currency_symbol . number_format(($total_paid + $total_fine), 2, '.', '') . ' </br>( ' . ucwords($f->format(($total_paid + $total_fine))) . ' )' ;
												?>
											</li>
											<?php endif; ?>
										</ul>
									</div>
								</div>
							</div>
						</div>
						<div class="text-right mr-lg hidden-print">
							<button onClick="fn_printElem('invoice_print')" class="btn btn-default ml-sm"><i class="fas fa-print"></i> <?=translate('print')?></button>
						</div>
					</div>
				</div>
				<?php if ($invoice['status'] != 'unpaid'): ?>
				<div class="tab-pane" id="history">
					<div id="payment_print">
						<div class="invoice payment">
							<header class="clearfix">
								<div class="row">
									<div class="col-xs-6">
										<div class="ib">
											<img src="<?=get_branch_logo(get_loggedin_branch_id(), 'printing_logo')?>" alt="techtune Img" />
										</div>
									</div>
									<div class="col-md-6 text-right">
										<h4 class="mt-none mb-none text-dark">Invoice No #<?= $invoice['invoice_no']?></h4>
										<p class="mb-none">
											<span class="text-dark"><?=translate('date')?> : </span>
											<span class="value"><?php echo _d(date('Y-m-d'));?></span>
										</p>
										<p class="mb-none">
											<span class="text-dark"><?=translate('status')?> : </span>
											<?php
												$labelmode = '';
												if($invoice['status'] == 'unpaid') {
													$status = translate('unpaid');
													$labelmode = 'label-danger-custom';
												} elseif($invoice['status'] == 'partly') {
													$status = translate('partly_paid');
													$labelmode = 'label-info-custom';
												} elseif($invoice['status'] == 'total') {
													$status = translate('total_paid');
													$labelmode = 'label-success-custom';
												}
												echo "<span class='value label ".$labelmode." '>".$status."</span>";
											?>
										</p>
									</div>
								</div>
							</header>
							<div class="bill-info">
								<div class="row">
									<div class="col-xs-6">
										<div class="bill-data">
											<p class="h5 mb-xs text-dark text-weight-semibold">Invoice To :</p>
											<address>
												<?php 
												echo $basic['first_name'] . ' '. $basic['last_name'] . '<br>';
												echo $basic['student_address'] . '<br>';
												echo translate('class').' : '. $basic['class_name'] . '<br>';
												echo translate('email').' : '. $basic['student_email']; 
												?>
											</address>
										</div>
									</div>
									<div class="col-xs-6">
										<div class="bill-data text-right">
											<p class="h5 mb-xs text-dark text-weight-semibold">Academic :</p>
											<address>
												<?php 
												echo $basic['school_name'] . "<br/>";
												echo $basic['school_address'] . "<br/>";
												echo $basic['school_mobileno'] . "<br/>";
												echo $basic['school_email'] . "<br/>";
												?>
											</address>
										</div>
									</div>
								</div>
							</div>

							<table class="table invoice-items" id="invoice">
								<thead>
									<tr class="h5 text-dark">
										<th id="cell-id" class="text-weight-semibold hidden-print">
											<div class="checkbox-replace" >
												<label class="i-checks" data-toggle="tooltip" data-original-title="Print Show / Hidden">
													<input type="checkbox" name="select-all" id="selectAllchkbox" checked> <i></i>
												</label>
											</div>
										</th>
										<th id="cell-item" class="text-weight-semibold"><?=translate('fees_type')?></th>
										<th id="cell-item" class="text-weight-semibold"><?=translate('fees_code')?></th>
										<th id="cell-item" class="text-weight-semibold"><?=translate('date')?></th>
										<th id="cell-item" class="text-weight-semibold hidden-print"><?=translate('collect_by')?></th>
										<th id="cell-desc" class="text-weight-semibold"><?=translate('remarks')?></th>
										<th id="cell-qty" class="text-weight-semibold"><?=translate('method')?></th>
										<th id="cell-price" class="text-weight-semibold"><?=translate('amount')?></th>
										<th id="cell-price" class="text-weight-semibold"><?=translate('discount')?></th>
										<th id="cell-price" class="text-weight-semibold"><?=translate('fine')?></th>
									</tr>
								</thead>
								<tbody>
									<?php
									$allocations = $this->db->where(array('student_id' => $basic['id'], 'session_id' => get_session_id()))->get('fee_allocation')->result_array();
									foreach ($allocations as $allRow) {
										$historys = $this->fees_model->getPaymentHistory($allRow['id'], $allRow['group_id']);
										foreach ($historys as $row) {
									?>
									<tr>
										<td class="hidden-print checked-area">
											<div class="checkbox-replace">
												<label class="i-checks"><input type="checkbox" name="chkPrint" checked ><i></i></label>
											</div>
										</td>
										<td class="text-weight-semibold text-dark"><?=$row['name']?></td>
										<td><?=$row['fee_code']?></td>
										<td><?=_d($row['date'])?></td>
										<td class="hidden-print">
											<?php
												if ($row['collect_by'] == 'online') {
													echo translate('online');
												}else{
													echo get_type_name_by_id('staff', $row['collect_by']);
												}
											?>
										</td>
										
										<td><?=$row['remarks']?></td>
										<td><?=$row['payvia']?></td>
										<td><?=$currency_symbol . $row['amount']?></td>
										<td><?=$currency_symbol . $row['discount']?></td>
										<td><?=$currency_symbol . $row['fine']?></td>
									</tr>
									 <?php } } ?>
								</tbody>
							</table>
							<div class="invoice-summary text-right mt-lg">
								<div class="row">
									<div class="col-lg-5 pull-right">
										<ul class="amounts">
											<li><strong><?=translate('grand_total')?> :</strong> <?=$currency_symbol . number_format($total_amount, 2, '.', ''); ?></li>
											<li><strong><?=translate('paid')?> :</strong> <?=$currency_symbol . number_format($total_paid, 2, '.', ''); ?></li>
											<li><strong><?=translate('discount')?> :</strong> <?=$currency_symbol . number_format($total_discount, 2, '.', ''); ?></li>
											<li><strong><?=translate('fine')?> :</strong> <?=$currency_symbol . number_format($total_fine, 2, '.', ''); ?></li>
											<?php if ($total_balance != 0): ?>
											<li>
												<strong><?=translate('balance')?> : </strong> 
												<?php
												$f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
												echo $currency_symbol . number_format($total_balance, 2, '.', '') . ' </br>( ' . ucwords($f->format($total_balance)) . ' )' ;
												?>
											</li>
											<?php else: ?>
											<li>
												<strong><?=translate('total_paid')?> : </strong> 
												<?php
												$f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
												echo $currency_symbol . number_format(($total_paid + $total_fine), 2, '.', '') . ' </br>( ' . ucwords($f->format(($total_paid + $total_fine))) . ' )' ;
												?>
											</li>
											<?php endif; ?>
										</ul>
									</div>
								</div>
							</div>
						</div>
						<div class="text-right mr-lg hidden-print">
							<button onClick="fn_printElem('payment_print')" class="btn btn-default"><i class="fas fa-print"></i> <?=translate('print')?></button>
						</div>
					</div>
				</div>
				<?php endif; ?>
				
				<!--add fees form-->
				<?php if($invoice['status'] != 'total'): ?>
					<div id="collect_fees" class="tab-pane">
						<?php echo form_open('feespayment/checkout', array('class' => 'form-horizontal frm-submit' )); ?>
							<input type="hidden" name="invoice_no" value="<?=$invoice['invoice_no']?>">
							<div class="form-group">
								<label class="col-md-3 control-label"><?=translate('fees_type')?> <span class="required">*</span></label>
								<div class="col-md-6">
								<?php
									echo form_dropdown("fees_type", $typeData, set_value('fees_type'), "class='form-control' id='fees_type'
									data-plugin-selectTwo data-width='100%' ");
								?>
								<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?=translate('amount')?> <span class="required">*</span></label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="fee_amount" id="feeAmount" value="" autocomplete="off" />
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?=translate('fine')?></label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="fine_amount" id="fineAmount" value="0" autocomplete="off" readonly="" />
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?=translate('payment_method')?> <span class="required">*</span></label>
								<div class="col-md-6">
		    						<?php
										$payvia_list = array('' => translate('select_payment_method'));
										if (!empty($config['mpesa_status']) && $config['mpesa_status'] == 1)
											$payvia_list['mpesa'] = 'M-Pesa (Lipa Na M-Pesa)';
										if ($config['paypal_status'] == 1)
											$payvia_list['paypal'] = 'Paypal';
										if ($config['stripe_status'] == 1)
											$payvia_list['stripe'] = 'Stripe';
										if ($config['payumoney_status'] == 1)
											$payvia_list['payumoney'] = 'PayUmoney';
										if ($config['paystack_status'] == 1)
											$payvia_list['paystack'] = 'Paystack';
										if ($config['razorpay_status'] == 1)
											$payvia_list['razorpay'] = 'Razorpay';
		    							echo form_dropdown("pay_via", $payvia_list, set_value('pay_via'), "class='form-control' data-plugin-selectTwo data-width='100%' id='pay_via'
		    							data-minimum-results-for-search='Infinity' ");
		    						?>
									<span class="error"></span>
								</div>
							</div>

							<div class="form-group stripe" style="display: none;">
								<label class="col-md-3 control-label"><?php echo translate('card_number');?> <span class="required">*</span></label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="card_number" autocomplete="off" />
									<span class="error"></span>
								</div>
							</div>
							
							<div class="form-group stripe" style="display: none;">
								<label class="col-md-3 control-label">CVV <span class="required">*</span></label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="cvv" autocomplete="off" />
									<span class="error"></span>
								</div>
							</div>
							
							<div class="form-group stripe" style="display: none;">
								<label class="col-md-3 control-label">Card Expire <span class="required">*</span></label>
								<div class="col-md-3">
									<input type="text" class="form-control" autocomplete="off" name="expire_month" data-plugin-datepicker data-plugin-options='{ "format": "mm", "viewMode": "months", "minViewMode":
									"months" }' placeholder="Expire Month" />
									<span class="error"></span>
								</div>
								<div class="col-md-3">
									<input type="text" class="form-control" autocomplete="off" name="expire_year" data-plugin-datepicker data-plugin-options='{ "format": "yyyy", "viewMode": "years", "minViewMode":
									"years" }' placeholder="Expire Years" />
								</div>
							</div>
						
							<div class="form-group payu"  style="display: none;">
								<label class="col-md-3 control-label">Name <span class="required">*</span></label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="payer_name" autocomplete="off" />
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group payu" style="display: none;">
								<label class="col-md-3 control-label">Email <span class="required">*</span></label>
								<div class="col-md-6">
									<input type="email" class="form-control" name="email" autocomplete="off" />
									<span class="error"></span>
								</div>
							</div>
							<div class="form-group payu" style="display: none;">
								<label class="col-md-3 control-label">Phone <span class="required">*</span></label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="phone" autocomplete="off" />
									<span class="error"></span>
								</div>
							</div>

							<div class="form-group mpesa-field" style="display: none;">
								<label class="col-md-3 control-label">M-Pesa Phone <span class="required">*</span></label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="mpesa_phone" id="mpesa_phone" placeholder="e.g. 0712345678" autocomplete="off" />
									<small class="text-muted">Enter the Safaricom number to receive the STK push</small>
									<span class="error"></span>
								</div>
							</div>
							<footer class="panel-footer">
								<div class="row">
									<div class="col-md-offset-3 col-md-3">
										<button type="submit" class="btn btn-default" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
											<i class="fas fa-credit-card"></i> <?=translate('fees_pay_now')?>
										</button>
									</div>
								</div>
							</footer>
						<?php echo form_close();?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
<?php }else{ ?>
	<section class="panel">
		<div class="panel-body">
			<div class="alert alert-subl mb-none text-center">
				<i class="fas fa-exclamation-triangle"></i> <?=translate('no_fees_have_been_allocated')?>
			</div>
		</div>
	</section>
<?php } ?>
<script type="text/javascript">
    $('#fees_type').on("change", function(){
        var typeID = $(this).val();
	    $.ajax({
	        url: base_url + 'fees/getBalanceByType',
	        type: 'POST',
	        data: {
	        	'typeID': typeID
	        },
	        dataType: "json",
	        success: function (data) {
	            $('#feeAmount').val(data.balance.toFixed(2));
	            $('#fineAmount').val(data.fine.toFixed(2));
	        }
	    });
    });
</script>

<script type="text/javascript">
	$(document).ready(function () {
		$(document).on('change', '#pay_via', function(){
			var method = $(this).val();
			$('.stripe').hide(400);
			$('.payu').hide(400);
			$('.mpesa-field').hide(400);
			if (method == "stripe") {
				$('.stripe').show(400);
			} else if (method == "payumoney") {
				$('.payu').show(400);
			} else if (method == "mpesa") {
				$('.mpesa-field').show(400);
			}
		});

		/* ── M-Pesa STK Push: intercept form submit ── */
		var mpesaPollTimer = null;

		$('form.frm-submit').on('submit.mpesa', function(e) {
			if ($('#pay_via').val() !== 'mpesa') return; // let default handler run for others
			e.preventDefault();
			e.stopImmediatePropagation();

			var $form = $(this);
			var $btn = $form.find('[type="submit"]');
			var phone = $('#mpesa_phone').val().trim();

			if (!phone) {
				$form.find('[name="mpesa_phone"]').closest('.form-group').find('.error').html('M-Pesa phone number is required.');
				return;
			}

			$btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Sending STK Push...');

			$.ajax({
				url: $form.attr('action'),
				type: 'POST',
				data: $form.serialize(),
				dataType: 'json',
				success: function(data) {
					if (data.status === 'mpesa_pending') {
						showMpesaWaiting(data.checkout_request_id, data.message, $btn);
					} else if (data.status === 'fail') {
						$.each(data.error || {}, function(k, v) {
							$form.find("[name='" + k + "']").closest('.form-group').find('.error').html(v);
						});
						$btn.prop('disabled', false).html('<i class="fas fa-credit-card"></i> <?=translate('fees_pay_now')?>');
					} else {
						location.reload(true);
					}
				},
				error: function() {
					$btn.prop('disabled', false).html('<i class="fas fa-credit-card"></i> <?=translate('fees_pay_now')?>');
				}
			});
		});

		function showMpesaWaiting(checkoutId, msg, $btn) {
			$('#mpesa-modal-msg').text(msg);
			$('#mpesa-modal-ref').text(checkoutId);
			mfp_modal('#mpesa-waiting-modal');
			mpesaPollTimer = setInterval(function() {
				$.post(base_url + 'feespayment/mpesa_status', {checkout_request_id: checkoutId}, function(data) {
					if (data.status === 'completed') {
						clearInterval(mpesaPollTimer);
						$('#mpesa-wait-spinner').hide();
						$('#mpesa-wait-success').show();
						setTimeout(function(){ location.reload(true); }, 2000);
					} else if (data.status === 'failed' || data.status === 'cancelled') {
						clearInterval(mpesaPollTimer);
						$('#mpesa-wait-spinner').hide();
						$('#mpesa-wait-failed').show().text(data.message || 'Payment failed or cancelled.');
						$btn.prop('disabled', false).html('<i class="fas fa-credit-card"></i> <?=translate('fees_pay_now')?>');
					}
				}, 'json');
			}, 4000);

			$('#mpesa-cancel-poll').on('click', function() {
				clearInterval(mpesaPollTimer);
				if (typeof $.magnificPopup !== 'undefined') $.magnificPopup.close();
				$btn.prop('disabled', false).html('<i class="fas fa-credit-card"></i> <?=translate('fees_pay_now')?>');
			});
		}
	});
</script>

<!-- M-Pesa Waiting Modal -->
<div id="mpesa-waiting-modal" class="white-popup mfp-hide" style="max-width:420px;margin:auto;padding:32px;border-radius:12px;text-align:center;">
	<div style="font-size:3rem;margin-bottom:12px;">📱</div>
	<h4 style="font-weight:700;margin-bottom:8px;">M-Pesa Payment Request Sent</h4>
	<p id="mpesa-modal-msg" style="color:#555;margin-bottom:16px;"></p>
	<div id="mpesa-wait-spinner" style="margin:16px 0;">
		<i class="fas fa-spinner fa-spin fa-2x" style="color:#10b981;"></i>
		<p style="margin-top:8px;font-size:.85rem;color:#888;">Waiting for confirmation<span id="mpesa-dots">...</span></p>
	</div>
	<div id="mpesa-wait-success" style="display:none;color:#10b981;font-weight:700;font-size:1.1rem;">
		<i class="fas fa-check-circle fa-2x"></i><br>Payment confirmed! Refreshing...
	</div>
	<div id="mpesa-wait-failed" style="display:none;color:#ef4444;font-weight:600;"></div>
	<p style="font-size:.75rem;color:#aaa;margin-top:16px;">Reference: <span id="mpesa-modal-ref" style="font-family:monospace;"></span></p>
	<button id="mpesa-cancel-poll" class="btn btn-default btn-sm" style="margin-top:12px;">
		<i class="fas fa-times"></i> Cancel
	</button>
</div>