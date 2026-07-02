<!DOCTYPE html>
<html>
<head>
<title>LPO <?=$order['lpo_number']?></title>
<style>
@media print { body { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; } }
body { font-family: 'Segoe UI', Tahoma, Arial, sans-serif; font-size: 13px; color: #222; margin: 0; padding: 20px; }
.lpo-container { max-width: 800px; margin: 0 auto; border: 2px solid #1a5276; padding: 0; }
.lpo-header { background: #1a5276; color: #fff; padding: 15px 20px; }
.lpo-header h2 { margin: 0; font-size: 20px; }
.lpo-header p { margin: 2px 0; font-size: 12px; opacity: 0.9; }
.lpo-title { background: #eaf2f8; padding: 10px 20px; text-align: center; border-bottom: 2px solid #1a5276; }
.lpo-title h3 { margin: 0; color: #1a5276; font-size: 18px; letter-spacing: 2px; }
.lpo-info { padding: 15px 20px; }
.lpo-info table { width: 100%; }
.lpo-info td { padding: 3px 8px; vertical-align: top; }
.lpo-info .label-col { font-weight: 600; color: #1a5276; width: 130px; }
.items-table { width: calc(100% - 40px); margin: 0 20px; border-collapse: collapse; }
.items-table th { background: #1a5276 !important; color: #fff; padding: 8px; text-align: left; border: 1px solid #1a5276; }
.items-table td { padding: 6px 8px; border: 1px solid #ccc; }
.items-table tfoot td { font-weight: bold; border-top: 2px solid #1a5276; }
.total-row { font-size: 16px; }
.signatures { padding: 30px 20px 15px; }
.sig-line { display: inline-block; width: 30%; text-align: center; margin-top: 40px; border-top: 2px solid #333; padding-top: 5px; font-weight: 600; font-size: 12px; }
.lpo-footer { background: #f5f5f5; padding: 8px 20px; text-align: center; font-size: 11px; color: #666; border-top: 1px solid #ddd; }
.status-badge { display: inline-block; padding: 3px 12px; border-radius: 3px; font-weight: bold; font-size: 12px; }
.print-btn { text-align: center; margin: 15px 0; }
@media print { .print-btn { display: none; } }
</style>
</head>
<body>
<div class="print-btn">
	<button onclick="window.print()" style="padding:10px 30px; font-size:16px; cursor:pointer;">Print LPO</button>
</div>
<div class="lpo-container">
	<!-- Header -->
	<div class="lpo-header">
		<table style="width:100%;">
			<tr>
				<td style="width:70%;">
					<h2><?=$order['school_name']?></h2>
					<p><?=$order['school_address']?></p>
					<p>Tel: <?=$order['school_phone']?> | Email: <?=$order['school_email']?></p>
				</td>
				<td style="width:30%; text-align:right; vertical-align:middle;">
					<img src="<?=get_branch_logo($order['branch_id'], 'printing_logo')?>" style="max-height:60px;">
				</td>
			</tr>
		</table>
	</div>

	<div class="lpo-title">
		<h3>LOCAL PURCHASE ORDER</h3>
	</div>

	<!-- LPO Info -->
	<div class="lpo-info">
		<table>
			<tr>
				<td>
					<table>
						<tr><td class="label-col">LPO Number:</td><td><strong style="font-size:15px;"><?=$order['lpo_number']?></strong></td></tr>
						<tr><td class="label-col">Date:</td><td><?=_d($order['order_date'])?></td></tr>
						<tr><td class="label-col">Valid Until:</td><td><?=!empty($order['valid_until']) ? _d($order['valid_until']) : '-'?></td></tr>
						<tr><td class="label-col">Delivery Date:</td><td><?=!empty($order['delivery_date']) ? _d($order['delivery_date']) : '-'?></td></tr>
					</table>
				</td>
				<td>
					<table>
						<tr><td class="label-col">Supplier:</td><td><strong><?=$order['supplier_name']?></strong></td></tr>
						<tr><td class="label-col">Contact:</td><td><?=$order['contact_person']?></td></tr>
						<tr><td class="label-col">Phone:</td><td><?=$order['supplier_phone']?></td></tr>
						<tr><td class="label-col">KRA PIN:</td><td><?=!empty($order['supplier_kra']) ? $order['supplier_kra'] : '-'?></td></tr>
					</table>
				</td>
			</tr>
		</table>
	</div>

	<!-- Items -->
	<table class="items-table">
		<thead>
			<tr>
				<th width="5%">#</th>
				<th width="40%">Description</th>
				<th width="10%">Unit</th>
				<th width="10%">Qty</th>
				<th width="15%">Unit Price</th>
				<th width="20%">Total (KES)</th>
			</tr>
		</thead>
		<tbody>
		<?php $n = 1; foreach ($items as $item): ?>
			<tr>
				<td><?=$n++?></td>
				<td><?=$item['item_name']?><?=!empty($item['description']) ? ' - ' . $item['description'] : ''?></td>
				<td><?=$item['unit']?></td>
				<td><?=intval($item['quantity'])?></td>
				<td style="text-align:right;"><?=number_format($item['unit_price'], 2)?></td>
				<td style="text-align:right;"><?=number_format($item['total_price'], 2)?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="5" style="text-align:right;">Subtotal:</td>
				<td style="text-align:right;"><?=number_format($order['subtotal'], 2)?></td>
			</tr>
			<?php if ($order['tax_amount'] > 0): ?>
			<tr>
				<td colspan="5" style="text-align:right;">Tax:</td>
				<td style="text-align:right;"><?=number_format($order['tax_amount'], 2)?></td>
			</tr>
			<?php endif; ?>
			<tr class="total-row">
				<td colspan="5" style="text-align:right;">TOTAL:</td>
				<td style="text-align:right;">KES <?=number_format($order['total_amount'], 2)?></td>
			</tr>
		</tfoot>
	</table>

	<?php if (!empty($order['notes'])): ?>
	<div style="padding:10px 20px; font-style:italic; color:#666;">
		<strong>Notes:</strong> <?=$order['notes']?>
	</div>
	<?php endif; ?>

	<!-- Signatures -->
	<div class="signatures">
		<div class="sig-line">Prepared by<br><small><?=$order['prepared_by_name']?></small></div>
		<div class="sig-line" style="margin-left:3%;">Approved by<br><small><?=!empty($order['approved_by_name']) ? $order['approved_by_name'] : '_______________'?></small></div>
		<div class="sig-line" style="margin-left:3%;">School Stamp<br><small>&nbsp;</small></div>
	</div>

	<div class="lpo-footer">
		This is a computer-generated Local Purchase Order from <?=$order['school_name']?> | Powered by DCK Solutions
	</div>
</div>
</body>
</html>
