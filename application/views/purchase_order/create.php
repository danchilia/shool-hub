<?php echo form_open($this->uri->uri_string()); ?>
<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"><i class="fas fa-file-invoice"></i> Create Local Purchase Order (LPO)</h4>
	</header>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label>LPO Number<?=help_tip('Auto-generated. Format: SCHOOL/LPO/YEAR/NUMBER. Example: MAI/LPO/2026/001')?></label>
					<input type="text" class="form-control" name="lpo_number" value="<?=$lpo_number?>" readonly style="font-weight:bold; font-size:16px;">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>Supplier <span class="required">*</span><?=help_tip('Select the vendor/supplier. Add new suppliers at LPO > Suppliers. Example: Mwangi Stationery Supplies')?></label>
					<select name="supplier_id" class="form-control" data-plugin-selectTwo data-width="100%" required>
						<option value=""><?=translate('select')?></option>
						<?php foreach ($suppliers as $s): ?>
						<option value="<?=$s['id']?>"><?=$s['name']?> (<?=$s['phone']?>)</option>
						<?php endforeach; ?>
					</select>
					<span class="error"><?=form_error('supplier_id')?></span>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label>Order Date <span class="required">*</span></label>
					<input type="text" class="form-control" name="order_date" value="<?=date('Y-m-d')?>" data-plugin-datepicker data-plugin-options='{"todayHighlight":true}'>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Valid Until<?=help_tip('LPO expiry date. Supplier should deliver before this date. Usually 14-30 days.')?></label>
					<input type="text" class="form-control" name="valid_until" value="<?=date('Y-m-d', strtotime('+14 days'))?>" data-plugin-datepicker>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Expected Delivery<?=help_tip('When you expect the supplier to deliver. Example: 5 days from order date.')?></label>
					<input type="text" class="form-control" name="delivery_date" value="" data-plugin-datepicker>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Expense Category<?=help_tip('Which accounting category this falls under. Links to Office Accounting. Example: Stationery & Supplies')?></label>
					<select name="voucher_head_id" class="form-control" data-plugin-selectTwo data-width="100%">
						<option value="">Select (optional)</option>
						<?php foreach ($voucher_heads as $vh): ?>
						<option value="<?=$vh['id']?>"><?=$vh['name']?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label>Delivery Address</label>
			<input type="text" class="form-control" name="delivery_address" value="School Gate">
		</div>

		<!-- ITEMS TABLE -->
		<h5 class="mt-lg"><strong><i class="fas fa-list"></i> Order Items</strong><?=help_tip('Add each item you want to order. Click + Add Item for more rows. Example: A4 Reams, Qty: 10, Price: 500')?></h5>
		<div class="table-responsive">
			<table class="table table-bordered" id="itemsTable">
				<thead>
					<tr class="success">
						<th width="5%">#</th>
						<th width="30%">Item Name <span class="required">*</span></th>
						<th width="15%">Unit</th>
						<th width="10%">Qty <span class="required">*</span></th>
						<th width="15%">Unit Price (<?=$global_config['currency_symbol']?>)</th>
						<th width="15%">Total</th>
						<th width="10%">Action</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>1</td>
						<td><input type="text" class="form-control input-sm" name="items[0][item_name]" required></td>
						<td><input type="text" class="form-control input-sm" name="items[0][unit]" value="pcs"></td>
						<td><input type="number" class="form-control input-sm item-qty" name="items[0][quantity]" value="1" min="1" onchange="calcRow(this)"></td>
						<td><input type="number" class="form-control input-sm item-price" name="items[0][unit_price]" value="0" step="0.01" onchange="calcRow(this)"></td>
						<td class="item-total" style="text-align:right; font-weight:bold;">0.00</td>
						<td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)"><i class="fas fa-times"></i></button></td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="5" style="text-align:right; font-weight:bold;">SUBTOTAL:</td>
						<td style="text-align:right; font-weight:bold;" id="subtotal">0.00</td>
						<td></td>
					</tr>
					<tr>
						<td colspan="5" style="text-align:right;">Tax (if applicable):</td>
						<td><input type="number" class="form-control input-sm" name="tax_amount" value="0" step="0.01" id="taxInput" onchange="calcTotal()"></td>
						<td></td>
					</tr>
					<tr>
						<td colspan="5" style="text-align:right; font-weight:bold; font-size:16px;">GRAND TOTAL:</td>
						<td style="text-align:right; font-weight:bold; font-size:16px;" id="grandTotal">0.00</td>
						<td></td>
					</tr>
				</tfoot>
			</table>
			<button type="button" class="btn btn-default btn-sm" onclick="addRow()"><i class="fas fa-plus"></i> Add Item</button>
		</div>

		<div class="form-group mt-md">
			<label>Notes / Special Instructions</label>
			<textarea class="form-control" name="notes" rows="2" placeholder="Any special instructions for the supplier..."></textarea>
		</div>
	</div>
	<div class="panel-footer">
		<button type="submit" name="save" value="1" class="btn btn-default pull-right"><i class="fas fa-save"></i> Save LPO as Draft</button>
	</div>
</section>
<?php echo form_close(); ?>

<script>
var rowCount = 1;
function addRow() {
	var i = rowCount++;
	var row = '<tr><td>' + (i+1) + '</td>' +
		'<td><input type="text" class="form-control input-sm" name="items['+i+'][item_name]" required></td>' +
		'<td><input type="text" class="form-control input-sm" name="items['+i+'][unit]" value="pcs"></td>' +
		'<td><input type="number" class="form-control input-sm item-qty" name="items['+i+'][quantity]" value="1" min="1" onchange="calcRow(this)"></td>' +
		'<td><input type="number" class="form-control input-sm item-price" name="items['+i+'][unit_price]" value="0" step="0.01" onchange="calcRow(this)"></td>' +
		'<td class="item-total" style="text-align:right; font-weight:bold;">0.00</td>' +
		'<td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)"><i class="fas fa-times"></i></button></td></tr>';
	$('#itemsTable tbody').append(row);
}
function removeRow(btn) { $(btn).closest('tr').remove(); calcTotal(); }
function calcRow(el) {
	var row = $(el).closest('tr');
	var qty = parseFloat(row.find('.item-qty').val()) || 0;
	var price = parseFloat(row.find('.item-price').val()) || 0;
	row.find('.item-total').text((qty * price).toFixed(2));
	calcTotal();
}
function calcTotal() {
	var sub = 0;
	$('.item-total').each(function(){ sub += parseFloat($(this).text()) || 0; });
	$('#subtotal').text(sub.toFixed(2));
	var tax = parseFloat($('#taxInput').val()) || 0;
	$('#grandTotal').text((sub + tax).toFixed(2));
}
</script>
