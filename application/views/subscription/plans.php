<div class="row">
	<div class="col-md-5">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="far fa-edit"></i> Add Subscription Plan</h4>
			</header>
			<?php echo form_open($this->uri->uri_string());?>
				<div class="panel-body">
					<div class="form-group">
						<label class="control-label">Plan Name <span class="required">*</span></label>
						<input type="text" class="form-control" name="name" value="<?=set_value('name')?>" />
						<span class="error"><?=form_error('name')?></span>
					</div>
					<div class="form-group">
						<label class="control-label">Description</label>
						<textarea class="form-control" name="description" rows="2"><?=set_value('description')?></textarea>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">Max Students <span class="required">*</span></label>
								<input type="number" class="form-control" name="max_students" value="<?=set_value('max_students', '0')?>" />
								<small class="text-muted">0 = Unlimited</small>
								<span class="error"><?=form_error('max_students')?></span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">Max Staff <span class="required">*</span></label>
								<input type="number" class="form-control" name="max_staff" value="<?=set_value('max_staff', '0')?>" />
								<small class="text-muted">0 = Unlimited</small>
								<span class="error"><?=form_error('max_staff')?></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">Monthly Price (KES) <span class="required">*</span></label>
								<input type="number" step="0.01" class="form-control" name="monthly_price" value="<?=set_value('monthly_price', '0')?>" />
								<span class="error"><?=form_error('monthly_price')?></span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">Yearly Price (KES) <span class="required">*</span></label>
								<input type="number" step="0.01" class="form-control" name="yearly_price" value="<?=set_value('yearly_price', '0')?>" />
								<span class="error"><?=form_error('yearly_price')?></span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label><input type="checkbox" name="is_active" value="1" checked /> Active</label>
					</div>
				</div>
				<div class="panel-footer">
					<button class="btn btn-default pull-right" type="submit" name="save" value="1">
						<i class="fas fa-plus-circle"></i> <?=translate('save')?>
					</button>
				</div>
			<?php echo form_close();?>
		</section>
	</div>
	<div class="col-md-7">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-list-ul"></i> Subscription Plans</h4>
			</header>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-condensed mb-none">
						<thead>
							<tr>
								<th><?=translate('sl')?></th>
								<th>Plan</th>
								<th>Max Students</th>
								<th>Max Staff</th>
								<th>Monthly (KES)</th>
								<th>Yearly (KES)</th>
								<th>Status</th>
								<th><?=translate('action')?></th>
							</tr>
						</thead>
						<tbody>
						<?php $c = 1; if (count($plans)): foreach ($plans as $row): ?>
							<tr>
								<td><?=$c++?></td>
								<td><?=$row['name']?></td>
								<td><?=$row['max_students'] == 0 ? 'Unlimited' : $row['max_students']?></td>
								<td><?=$row['max_staff'] == 0 ? 'Unlimited' : $row['max_staff']?></td>
								<td><?=number_format($row['monthly_price'], 2)?></td>
								<td><?=number_format($row['yearly_price'], 2)?></td>
								<td><?=$row['is_active'] ? '<span class="label label-success">Active</span>' : '<span class="label label-default">Inactive</span>'?></td>
								<td><?php echo btn_delete('subscription/plan_delete/' . $row['id']);?></td>
							</tr>
						<?php endforeach; else: ?>
							<tr><td colspan="8"><h5 class="text-danger text-center"><?=translate('no_information_available')?></h5></td></tr>
						<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</section>
	</div>
</div>
