<style type="text/css">
	@media print {
		.pagebreak { page-break-before: always; }
	}
</style>
<?php
$currency_symbol = $global_config['currency_symbol'];
foreach ($print_data as $item):
	$invoice     = $item['invoice'];
	$basic       = $item['basic'];
	$allocations = $item['allocations'];

	$total_fine     = 0;
	$total_discount = 0;
	$total_paid     = 0;
	$total_balance  = 0;
	$total_amount   = 0;
	$typeData = array('' => translate('select'));
	foreach ($allocations as $row) {
		$deposit      = $row['deposit'];
		$type_discount = $deposit['total_discount'];
		$type_fine     = $deposit['total_fine'];
		$type_amount   = $deposit['total_amount'];
		$balance       = $row['amount'] - ($type_amount + $type_discount);
		$total_discount += $type_discount;
		$total_fine     += $type_fine;
		$total_paid     += $type_amount;
		$total_balance  += $balance;
		$total_amount   += $row['amount'];
	}

	if ($invoice['status'] == 'unpaid') {
		$inv_status    = translate('unpaid');
		$inv_labelmode = 'label-danger-custom';
	} elseif ($invoice['status'] == 'partly') {
		$inv_status    = translate('partly_paid');
		$inv_labelmode = 'label-info-custom';
	} else {
		$inv_status    = translate('total_paid');
		$inv_labelmode = 'label-success-custom';
	}
?>
<div class="invoice">
	<header class="clearfix">
		<div class="row">
			<div class="col-sm-6">
				<div class="ib">
					<img src="<?=get_branch_logo($basic['branch_id'], 'printing_logo')?>" alt="School Logo" />
				</div>
			</div>
			<div class="col-sm-6 text-end">
				<h4 class="mt-0 mb-0 text-dark">Invoice No #<?=$invoice['invoice_no']?></h4>
				<p class="mb-0">
					<span class="text-dark"><?=translate('date')?> : </span>
					<span class="value"><?=_d(date('Y-m-d'))?></span>
				</p>
				<p class="mb-0">
					<span class="text-dark"><?=translate('status')?> : </span>
					<span class="value label <?=$inv_labelmode?>"><?=$inv_status?></span>
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
			<div class="col-sm-6">
				<div class="bill-data text-end">
					<p class="h5 mb-1 text-dark fw-semibold">Academic :</p>
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
		<table class="table invoice-items table-hover mb-0">
			<thead>
				<tr class="text-dark">
					<th class="fw-semibold">#</th>
					<th class="fw-semibold"><?=translate("fees_type")?></th>
					<th class="fw-semibold"><?=translate("due_date")?></th>
					<th class="fw-semibold"><?=translate("status")?></th>
					<th class="fw-semibold"><?=translate("amount")?></th>
					<th class="fw-semibold"><?=translate("discount")?></th>
					<th class="fw-semibold"><?=translate("fine")?></th>
					<th class="fw-semibold"><?=translate("paid")?></th>
					<th class="text-center fw-semibold"><?=translate("balance")?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$count = 1;
				foreach ($allocations as $row):
					$deposit       = $row['deposit'];
					$type_discount = $deposit['total_discount'];
					$type_fine     = $deposit['total_fine'];
					$type_amount   = $deposit['total_amount'];
					$balance       = $row['amount'] - ($type_amount + $type_discount);
					if ($type_amount == 0) {
						$row_status    = translate('unpaid');
						$row_label     = 'label-danger-custom';
					} elseif ($balance == 0) {
						$row_status    = translate('total_paid');
						$row_label     = 'label-success-custom';
					} else {
						$row_status    = translate('partly_paid');
						$row_label     = 'label-info-custom';
					}
				?>
				<tr>
					<td><?php echo $count++;?></td>
					<td class="fw-semibold text-dark"><?=$row['name']?></td>
					<td><?=_d($row['due_date'])?></td>
					<td><span class="label <?=$row_label?>"><?=$row_status?></span></td>
					<td><?=$currency_symbol . $row['amount']?></td>
					<td><?=$currency_symbol . $type_discount?></td>
					<td><?=$currency_symbol . $type_fine?></td>
					<td><?=$currency_symbol . $type_amount?></td>
					<td class="text-center"><?=$currency_symbol . number_format($balance, 2, '.', '')?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<div class="invoice-summary text-end mt-4">
		<div class="row justify-content-end">
			<div class="col-lg-5">
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
						echo $currency_symbol . number_format($total_balance, 2, '.', '') . ' </br>( ' . ucwords($f->format($total_balance)) . ' )';
						?>
					</li>
					<?php else: ?>
					<li>
						<strong><?=translate('total_paid')?> : </strong>
						<?php
						$f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
						echo $currency_symbol . number_format(($total_paid + $total_fine), 2, '.', '') . ' </br>( ' . ucwords($f->format(($total_paid + $total_fine))) . ' )';
						?>
					</li>
					<?php endif; ?>
				</ul>
			</div>
		</div>
	</div>
</div>
<div class="text-end me-4 d-print-none">
	<button onClick="fn_printElem('invoice_print')" class="btn btn-secondary ms-2"><i class="fas fa-print"></i> <?=translate('print')?></button>
</div>

<div class="pagebreak"> </div>
<?php endforeach; ?>
