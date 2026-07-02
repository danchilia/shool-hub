<?php
$currency = $global_config['currency_symbol'];
$statusLabels = array(
    'draft' => array('Draft', 'label-default'), 'pending_approval' => array('Pending Approval', 'label-warning'),
    'approved' => array('Approved', 'label-info'), 'sent' => array('Sent to Supplier', 'label-primary'),
    'delivered' => array('Delivered', 'label-success'), 'partially_delivered' => array('Partially Delivered', 'label-warning'),
    'paid' => array('Paid & Closed', 'label-success'), 'cancelled' => array('Cancelled', 'label-danger'),
);
$sl = isset($statusLabels[$order['status']]) ? $statusLabels[$order['status']] : array($order['status'], 'label-default');
?>
<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title">
			<i class="fas fa-file-invoice"></i> <?=$order['lpo_number']?>
			<span class="label <?=$sl[1]?>" style="font-size:13px; margin-left:10px;"><?=$sl[0]?></span>
			<div class="pull-right">
				<a href="<?=base_url('purchase-orders/print_lpo/' . $order['id'])?>" target="_blank" class="btn btn-default btn-sm"><i class="fas fa-print"></i> Print LPO</a>
				<a href="<?=base_url('purchase-orders')?>" class="btn btn-default btn-sm"><i class="fas fa-arrow-left"></i> All LPOs</a>
			</div>
		</h4>
	</header>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<table class="table table-condensed">
					<tr><th width="40%">LPO Number</th><td><strong><?=$order['lpo_number']?></strong></td></tr>
					<tr><th>Supplier</th><td><?=$order['supplier_name']?><br><small><?=$order['supplier_phone']?> | <?=$order['supplier_email']?></small></td></tr>
					<tr><th>Order Date</th><td><?=_d($order['order_date'])?></td></tr>
					<tr><th>Valid Until</th><td><?=!empty($order['valid_until']) ? _d($order['valid_until']) : '-'?></td></tr>
					<tr><th>Delivery Date</th><td><?=!empty($order['delivery_date']) ? _d($order['delivery_date']) : '-'?></td></tr>
					<tr><th>Expense Category</th><td><?=!empty($order['voucher_head_name']) ? $order['voucher_head_name'] : '-'?></td></tr>
				</table>
			</div>
			<div class="col-md-6">
				<table class="table table-condensed">
					<tr><th width="40%">Prepared By</th><td><?=$order['prepared_by_name']?></td></tr>
					<tr><th>Approved By</th><td><?=!empty($order['approved_by_name']) ? $order['approved_by_name'] . ' (' . _d($order['approved_at']) . ')' : '-'?></td></tr>
					<tr><th>Received By</th><td><?=!empty($order['receiver_name']) ? $order['receiver_name'] . ' (' . _d($order['received_at']) . ')' : '-'?></td></tr>
					<tr><th>Paid By</th><td><?=!empty($order['paid_by_name']) ? $order['paid_by_name'] . ' (' . _d($order['payment_date']) . ')' : '-'?></td></tr>
					<?php if (!empty($order['payment_reference'])): ?>
					<tr><th>Payment Ref</th><td><?=$order['payment_reference']?> (<?=$order['payment_method']?>)</td></tr>
					<?php endif; ?>
					<?php if (!empty($order['rejection_reason'])): ?>
					<tr><th>Rejection Reason</th><td class="text-danger"><?=$order['rejection_reason']?></td></tr>
					<?php endif; ?>
					<?php if (!empty($order['notes'])): ?>
					<tr><th>Notes</th><td><?=$order['notes']?></td></tr>
					<?php endif; ?>
				</table>
			</div>
		</div>

		<!-- ITEMS -->
		<table class="table table-bordered table-condensed mt-md">
			<thead>
				<tr class="success">
					<th>#</th><th>Item</th><th>Unit</th><th>Qty</th><th>Unit Price (<?=$currency?>)</th><th>Total (<?=$currency?>)</th>
				</tr>
			</thead>
			<tbody>
			<?php $n = 1; foreach ($items as $item): ?>
				<tr>
					<td><?=$n++?></td>
					<td><?=$item['item_name']?><?=!empty($item['description']) ? '<br><small class="text-muted">' . $item['description'] . '</small>' : ''?></td>
					<td><?=$item['unit']?></td>
					<td><?=intval($item['quantity'])?></td>
					<td style="text-align:right;"><?=number_format($item['unit_price'], 2)?></td>
					<td style="text-align:right;"><?=number_format($item['total_price'], 2)?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
			<tfoot>
				<tr><td colspan="5" style="text-align:right; font-weight:bold;">Subtotal:</td><td style="text-align:right; font-weight:bold;"><?=$currency?> <?=number_format($order['subtotal'], 2)?></td></tr>
				<?php if ($order['tax_amount'] > 0): ?>
				<tr><td colspan="5" style="text-align:right;">Tax:</td><td style="text-align:right;"><?=$currency?> <?=number_format($order['tax_amount'], 2)?></td></tr>
				<?php endif; ?>
				<tr><td colspan="5" style="text-align:right; font-weight:bold; font-size:16px;">TOTAL:</td><td style="text-align:right; font-weight:bold; font-size:16px;"><?=$currency?> <?=number_format($order['total_amount'], 2)?></td></tr>
			</tfoot>
		</table>

		<!-- ACTION BUTTONS based on status -->
		<div class="text-center mt-lg">
		<?php if ($order['status'] == 'draft'): ?>
			<a href="<?=base_url('purchase-orders/submit_for_approval/' . $order['id'])?>" class="btn btn-warning btn-lg" onclick="return confirm('Submit this LPO for Head Teacher approval?')">
				<i class="fas fa-paper-plane"></i> Submit for Approval
			</a>
		<?php endif; ?>

		<?php if ($order['status'] == 'pending_approval' && get_permission('lpo_approval', 'is_add')): ?>
			<a href="<?=base_url('purchase-orders/approve/' . $order['id'])?>" class="btn btn-success btn-lg" onclick="return confirm('Approve this LPO?')">
				<i class="fas fa-check"></i> Approve
			</a>
			<button class="btn btn-danger btn-lg" onclick="mfp_modal('#rejectModal')"><i class="fas fa-times"></i> Reject</button>
		<?php endif; ?>

		<?php if ($order['status'] == 'approved'): ?>
			<?php if (!empty($order['supplier_email'])): ?>
			<a href="<?=base_url('purchase-orders/send_email/' . $order['id'])?>" class="btn btn-primary btn-lg" onclick="return confirm('Send this LPO to <?=$order['supplier_name']?> (<?=$order['supplier_email']?>) via email?')">
				<i class="fas fa-envelope"></i> Send LPO via Email
			</a>
			<?php endif; ?>
			<a href="<?=base_url('purchase-orders/mark_sent/' . $order['id'])?>" class="btn btn-default btn-lg" onclick="return confirm('Mark as sent (manually delivered/printed)?')">
				<i class="fas fa-check"></i> Mark as Sent (Manual)
			</a>
		<?php endif; ?>

		<?php if ($order['status'] == 'sent' || $order['status'] == 'approved'): ?>
			<a href="<?=base_url('purchase-orders/mark_delivered/' . $order['id'])?>" class="btn btn-success btn-lg" onclick="return confirm('Confirm goods have been delivered?')">
				<i class="fas fa-truck"></i> Mark as Delivered
			</a>
		<?php endif; ?>

		<?php if ($order['status'] == 'delivered'): ?>
			<button class="btn btn-success btn-lg" onclick="mfp_modal('#payModal')"><i class="fas fa-money-bill-wave"></i> Record Payment</button>
		<?php endif; ?>

		<?php if (in_array($order['status'], array('draft', 'pending_approval', 'approved'))): ?>
			<a href="<?=base_url('purchase-orders/cancel/' . $order['id'])?>" class="btn btn-default" onclick="return confirm('Cancel this LPO?')">
				<i class="fas fa-ban"></i> Cancel LPO
			</a>
		<?php endif; ?>
		</div>
	</div>
</section>

<!-- Reject Modal -->
<?php if ($order['status'] == 'pending_approval'): ?>
<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="rejectModal">
	<section class="panel">
		<?php echo form_open('purchase-orders/reject/' . $order['id']); ?>
			<header class="panel-heading"><h4 class="panel-title"><i class="fas fa-times-circle"></i> Reject LPO</h4></header>
			<div class="panel-body">
				<div class="form-group">
					<label>Reason for Rejection <span class="required">*</span></label>
					<textarea name="rejection_reason" class="form-control" rows="3" required></textarea>
				</div>
			</div>
			<footer class="panel-footer text-right">
				<button type="submit" class="btn btn-danger"><i class="fas fa-times"></i> Reject</button>
				<button class="btn btn-default modal-dismiss"><?=translate('cancel')?></button>
			</footer>
		<?php echo form_close(); ?>
	</section>
</div>
<?php endif; ?>

<!-- Pay Modal -->
<?php if ($order['status'] == 'delivered'): ?>
<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="payModal">
	<section class="panel">
		<?php echo form_open('purchase-orders/mark_paid/' . $order['id']); ?>
			<header class="panel-heading"><h4 class="panel-title"><i class="fas fa-money-bill-wave"></i> Record Payment - <?=$currency?> <?=number_format($order['total_amount'], 2)?></h4></header>
			<div class="panel-body">
				<div class="form-group">
					<label>Payment Method <span class="required">*</span></label>
					<select name="payment_method" class="form-control" required>
						<option value="Cash">Cash</option>
						<option value="M-Pesa">M-Pesa</option>
						<option value="Bank Transfer">Bank Transfer</option>
						<option value="Cheque">Cheque</option>
					</select>
				</div>
				<div class="form-group">
					<label>Payment Reference<?=help_tip('Cheque number, M-Pesa code, or bank transfer ref. Example: QKL2XY789')?></label>
					<input type="text" class="form-control" name="payment_reference" placeholder="e.g. M-Pesa code, Cheque No.">
				</div>
				<div class="form-group">
					<label>Deduct from Account</label>
					<select name="account_id" class="form-control">
						<option value="">Select (optional)</option>
						<?php foreach ($accounts as $acc): ?>
						<option value="<?=$acc['id']?>"><?=$acc['name']?> (<?=$acc['number']?>)</option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<footer class="panel-footer text-right">
				<button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Confirm Payment</button>
				<button class="btn btn-default modal-dismiss"><?=translate('cancel')?></button>
			</footer>
		<?php echo form_close(); ?>
	</section>
</div>
<?php endif; ?>
