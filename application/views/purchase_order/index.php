<?php $currency = $global_config['currency_symbol']; ?>
<!-- Summary Cards -->
<div class="row mb-md">
	<div class="col-md-2"><div class="panel text-center p-md"><h3 class="mt-none mb-xs"><?=$summary['total']?></h3><small>Total LPOs</small></div></div>
	<div class="col-md-2"><div class="panel text-center p-md" style="border-left:3px solid #f0ad4e;"><h3 class="mt-none mb-xs" style="color:#f0ad4e;"><?=$summary['pending']?></h3><small>Pending Approval</small></div></div>
	<div class="col-md-2"><div class="panel text-center p-md" style="border-left:3px solid #337ab7;"><h3 class="mt-none mb-xs" style="color:#337ab7;"><?=$summary['approved']?></h3><small>Approved/Sent</small></div></div>
	<div class="col-md-3"><div class="panel text-center p-md" style="border-left:3px solid #d9534f;"><h3 class="mt-none mb-xs" style="color:#d9534f;"><?=$currency?> <?=number_format($summary['committed_amount'])?></h3><small>Committed Spend</small></div></div>
	<div class="col-md-3"><div class="panel text-center p-md" style="border-left:3px solid #5cb85c;"><h3 class="mt-none mb-xs" style="color:#5cb85c;"><?=$currency?> <?=number_format($summary['paid_amount'])?></h3><small>Total Paid</small></div></div>
</div>

<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"><i class="fas fa-file-invoice"></i> Purchase Orders
			<div class="pull-right">
				<a href="<?=base_url('purchase-orders?status=pending_approval')?>" class="btn btn-warning btn-sm <?=$current_status=='pending_approval'?'active':''?>">Pending</a>
				<a href="<?=base_url('purchase-orders?status=approved')?>" class="btn btn-info btn-sm <?=$current_status=='approved'?'active':''?>">Approved</a>
				<a href="<?=base_url('purchase-orders?status=delivered')?>" class="btn btn-success btn-sm <?=$current_status=='delivered'?'active':''?>">Delivered</a>
				<a href="<?=base_url('purchase-orders')?>" class="btn btn-default btn-sm <?=empty($current_status)?'active':''?>">All</a>
				<?php if (get_permission('purchase_orders', 'is_add')): ?>
				<a href="<?=base_url('purchase-orders/create')?>" class="btn btn-default btn-sm"><i class="fas fa-plus"></i> New LPO</a>
				<?php endif; ?>
			</div>
		</h4>
	</header>
	<div class="panel-body">
		<div class="table-responsive">
			<table class="table table-bordered table-hover table-condensed table-export">
				<thead>
					<tr>
						<th>#</th>
						<th>LPO Number</th>
						<th>Supplier</th>
						<th>Date</th>
						<th>Amount (<?=$currency?>)</th>
						<th>Status</th>
						<th>Prepared By</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				<?php $c = 1; if (count($orders)): foreach ($orders as $o):
					$statusLabels = array(
						'draft' => '<span class="label label-default">Draft</span>',
						'pending_approval' => '<span class="label label-warning">Pending Approval</span>',
						'approved' => '<span class="label label-info">Approved</span>',
						'sent' => '<span class="label label-primary">Sent</span>',
						'delivered' => '<span class="label label-success">Delivered</span>',
						'partially_delivered' => '<span class="label" style="background:#e67e22;color:#fff;">Partial</span>',
						'paid' => '<span class="label" style="background:#27ae60;color:#fff;">Paid</span>',
						'closed' => '<span class="label" style="background:#2c3e50;color:#fff;">Closed</span>',
						'cancelled' => '<span class="label label-danger">Cancelled</span>',
					);
				?>
					<tr>
						<td><?=$c++?></td>
						<td><strong><a href="<?=base_url('purchase-orders/view/' . $o['id'])?>"><?=$o['lpo_number']?></a></strong></td>
						<td><?=$o['supplier_name']?></td>
						<td><?=_d($o['order_date'])?></td>
						<td style="text-align:right;"><?=number_format($o['total_amount'], 2)?></td>
						<td><?=isset($statusLabels[$o['status']]) ? $statusLabels[$o['status']] : $o['status']?></td>
						<td><?=$o['prepared_by_name']?></td>
						<td>
							<a href="<?=base_url('purchase-orders/view/' . $o['id'])?>" class="btn btn-default btn-circle icon"><i class="fas fa-eye"></i></a>
							<a href="<?=base_url('purchase-orders/print_lpo/' . $o['id'])?>" target="_blank" class="btn btn-default btn-circle icon"><i class="fas fa-print"></i></a>
						</td>
					</tr>
				<?php endforeach; else: ?>
					<tr><td colspan="8"><h5 class="text-danger text-center"><?=translate('no_information_available')?></h5></td></tr>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</section>
