<div class="row">
<?php if (get_permission('suppliers', 'is_add')): ?>
	<div class="col-md-5">
		<section class="panel">
			<header class="panel-heading"><h4 class="panel-title"><i class="far fa-edit"></i> Add Supplier</h4></header>
			<?php echo form_open($this->uri->uri_string()); ?>
				<div class="panel-body">
					<div class="form-group">
						<label>Supplier Name <span class="required">*</span><?=help_tip('Company or individual name. Example: Mwangi Stationery Supplies, Kenya Power')?></label>
						<input type="text" class="form-control" name="name" value="<?=set_value('name')?>" />
						<span class="error"><?=form_error('name')?></span>
					</div>
					<div class="form-group">
						<label>Contact Person<?=help_tip('Person to call at the supplier. Example: John Kamau')?></label>
						<input type="text" class="form-control" name="contact_person" value="<?=set_value('contact_person')?>" />
					</div>
					<div class="form-group">
						<label>Phone <span class="required">*</span></label>
						<input type="text" class="form-control" name="phone" value="<?=set_value('phone')?>" placeholder="+254..." />
						<span class="error"><?=form_error('phone')?></span>
					</div>
					<div class="form-group">
						<label>Email</label>
						<input type="email" class="form-control" name="email" value="<?=set_value('email')?>" />
					</div>
					<div class="form-group">
						<label>KRA PIN<?=help_tip('Kenya Revenue Authority PIN for tax purposes. Example: A012345678B')?></label>
						<input type="text" class="form-control" name="kra_pin" value="<?=set_value('kra_pin')?>" />
					</div>
					<div class="form-group">
						<label>Address</label>
						<input type="text" class="form-control" name="address" value="<?=set_value('address')?>" />
					</div>
					<div class="form-group">
						<label>City</label>
						<input type="text" class="form-control" name="city" value="<?=set_value('city')?>" />
					</div>
				</div>
				<div class="panel-footer">
					<button class="btn btn-default pull-right" type="submit" name="save" value="1"><i class="fas fa-plus-circle"></i> <?=translate('save')?></button>
				</div>
			<?php echo form_close(); ?>
		</section>
	</div>
<?php endif; ?>
	<div class="col-md-<?=get_permission('suppliers', 'is_add') ? '7' : '12'?>">
		<section class="panel">
			<header class="panel-heading"><h4 class="panel-title"><i class="fas fa-address-book"></i> Supplier Directory</h4></header>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-condensed table-export mb-none">
						<thead>
							<tr>
								<th>#</th>
								<th>Supplier Name</th>
								<th>Contact</th>
								<th>Phone</th>
								<th>Email</th>
								<th>KRA PIN</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
						<?php $c = 1; if (count($suppliers)): foreach ($suppliers as $s): ?>
							<tr>
								<td><?=$c++?></td>
								<td><strong><?=$s['name']?></strong></td>
								<td><?=$s['contact_person']?></td>
								<td><?=$s['phone']?></td>
								<td><?=$s['email']?></td>
								<td><?=$s['kra_pin']?></td>
								<td>
								<?php if (get_permission('suppliers', 'is_delete')): ?>
									<a class="btn btn-danger icon btn-circle" onclick="confirm_modal('<?=base_url('purchase-orders/supplier_delete/' . $s['id'])?>')"><i class="fas fa-trash-alt"></i></a>
								<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; else: ?>
							<tr><td colspan="7"><h5 class="text-danger text-center"><?=translate('no_information_available')?></h5></td></tr>
						<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</section>
	</div>
</div>
