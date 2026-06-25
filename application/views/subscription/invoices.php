<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"><i class="fas fa-file-invoice"></i> Subscription Invoices</h4>
	</header>
	<div class="panel-body">
		<div class="table-responsive">
			<table class="table table-bordered table-hover table-condensed table-export">
				<thead>
					<tr>
						<th><?=translate('sl')?></th>
						<th>Branch</th>
						<th>Plan</th>
						<th>Amount (KES)</th>
						<th>Due Date</th>
						<th>Paid Date</th>
						<th>Reference</th>
						<th>Status</th>
						<th><?=translate('action')?></th>
					</tr>
				</thead>
				<tbody>
				<?php $c = 1; if (count($invoices)): foreach ($invoices as $row):
					$statusClass = '';
					switch ($row['status']) {
						case 'paid': $statusClass = 'label-success'; break;
						case 'pending': $statusClass = 'label-warning'; break;
						case 'overdue': $statusClass = 'label-danger'; break;
					}
				?>
					<tr>
						<td><?=$c++?></td>
						<td><?=$row['branch_name']?></td>
						<td><?=$row['plan_name']?></td>
						<td><?=number_format($row['amount'], 2)?></td>
						<td><?=_d($row['due_date'])?></td>
						<td><?=!empty($row['paid_date']) ? _d($row['paid_date']) : '-'?></td>
						<td><?=!empty($row['payment_reference']) ? $row['payment_reference'] : '-'?></td>
						<td><span class="label <?=$statusClass?>"><?=ucfirst($row['status'])?></span></td>
						<td>
							<?php if ($row['status'] != 'paid'): ?>
							<a href="javascript:void(0);" class="btn btn-success btn-circle icon" onclick="payInvoice(<?=$row['id']?>)">
								<i class="fas fa-check"></i>
							</a>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; else: ?>
					<tr><td colspan="9"><h5 class="text-danger text-center"><?=translate('no_information_available')?></h5></td></tr>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</section>

<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="payModal">
	<section class="panel">
		<?php echo form_open('subscription/invoice_pay', array('class' => 'frm-submit')); ?>
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-check-circle"></i> Mark Invoice as Paid</h4>
			</header>
			<div class="panel-body">
				<input type="hidden" name="invoice_id" id="pay_invoice_id" value="" />
				<div class="form-group">
					<label class="control-label">Payment Reference <span class="required">*</span></label>
					<input type="text" class="form-control" name="payment_reference" placeholder="M-Pesa code, bank transfer ref, etc." required />
					<span class="error"></span>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-success" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
							<i class="fas fa-check"></i> Mark as Paid
						</button>
						<button class="btn btn-default modal-dismiss"><?=translate('cancel')?></button>
					</div>
				</div>
			</footer>
		<?php echo form_close();?>
	</section>
</div>

<script type="text/javascript">
	function payInvoice(id) {
		$('#pay_invoice_id').val(id);
		mfp_modal('#payModal');
	}
</script>
