<div class="row">
<?php if (get_permission('cbc_strands', 'is_add')): ?>
	<div class="col-md-5">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="far fa-edit"></i> Add Strand</h4>
			</header>
			<?php echo form_open($this->uri->uri_string());?>
				<div class="panel-body">
					<?php if (is_superadmin_loggedin()): ?>
					<div class="form-group">
						<label class="control-label"><?=translate('branch')?> <span class="required">*</span></label>
						<?php
							$arrayBranch = $this->app_lib->getSelectList('branch');
							echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' id='branch_id' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
						?>
						<span class="error"><?=form_error('branch_id')?></span>
					</div>
					<?php endif; ?>
					<div class="form-group">
						<label class="control-label">Learning Area <span class="required">*</span></label>
						<select name="learning_area_id" class="form-control" data-plugin-selectTwo data-width="100%">
							<option value=""><?=translate('select')?></option>
							<?php foreach ($learning_areas as $la): ?>
							<option value="<?=$la['id']?>" <?=set_select('learning_area_id', $la['id'])?>><?=$la['name']?> (<?=ucfirst(str_replace('_', ' ', $la['level']))?>)</option>
							<?php endforeach; ?>
						</select>
						<span class="error"><?=form_error('learning_area_id')?></span>
					</div>
					<div class="form-group">
						<label class="control-label">Strand Name <span class="required">*</span></label>
						<input type="text" class="form-control" name="name" value="<?=set_value('name')?>" />
						<span class="error"><?=form_error('name')?></span>
					</div>
				</div>
				<div class="panel-footer">
					<div class="row">
						<div class="col-md-12">
							<button class="btn btn-default float-end" type="submit" name="save" value="1">
								<i class="fas fa-plus-circle"></i> <?=translate('save')?>
							</button>
						</div>
					</div>
				</div>
			<?php echo form_close();?>
		</section>
	</div>
<?php endif; ?>
<?php if (get_permission('cbc_strands', 'is_view')): ?>
	<div class="col-md-<?php echo get_permission('cbc_strands', 'is_add') ? '7' : '12'; ?>">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-list-ul"></i> Strands List</h4>
			</header>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-sm mb-0">
						<thead>
							<tr>
								<th><?=translate('sl')?></th>
								<?php if (is_superadmin_loggedin()): ?>
								<th><?=translate('branch')?></th>
								<?php endif; ?>
								<th>Learning Area</th>
								<th>Strand Name</th>
								<th><?=translate('action')?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$count = 1;
							if (!empty($strands)){
								foreach ($strands as $row):
							?>
							<tr>
								<td><?php echo $count++;?></td>
								<?php if (is_superadmin_loggedin()): ?>
								<td><?php echo $row['branch_name']; ?></td>
								<?php endif; ?>
								<td><?php echo $row['learning_area_name']; ?></td>
								<td><?php echo $row['name']; ?></td>
								<td>
								<?php if (get_permission('cbc_strands', 'is_edit')): ?>
									<a class="btn btn-default btn-circle icon" href="javascript:void(0);" onclick="getStrandModal(this)"
									data-id="<?=$row['id']?>" data-name="<?=$row['name']?>" data-la="<?=$row['learning_area_id']?>" data-branch="<?=$row['branch_id']?>">
										<i class="fas fa-pen-nib"></i>
									</a>
								<?php endif; if (get_permission('cbc_strands', 'is_delete')): ?>
									<?php echo btn_delete('cbc/strand_delete/' . $row['id']);?>
								<?php endif; ?>
								</td>
							</tr>
							<?php
								endforeach;
							}else{
								echo '<tr><td colspan="5"><h5 class="text-danger text-center">' . translate('no_information_available') . '</td></tr>';
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</section>
	</div>
</div>
<?php endif; ?>

<?php if (get_permission('cbc_strands', 'is_edit')): ?>
<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="modal">
	<section class="panel">
		<?php echo form_open('cbc/strand_edit', array('class' => 'frm-submit')); ?>
			<header class="panel-heading">
				<h4 class="panel-title"><i class="far fa-edit"></i> Edit Strand</h4>
			</header>
			<div class="panel-body">
				<input type="hidden" name="strand_id" id="edit_strand_id" value="" />
				<?php if (is_superadmin_loggedin()): ?>
				<div class="form-group">
					<label class="control-label"><?=translate('branch')?> <span class="required">*</span></label>
					<?php
						echo form_dropdown("branch_id", $arrayBranch, '', "class='form-control' id='edit_branch_id' data-plugin-selectTwo data-width='100%'");
					?>
					<span class="error"></span>
				</div>
				<?php endif; ?>
				<div class="form-group">
					<label class="control-label">Learning Area <span class="required">*</span></label>
					<select name="learning_area_id" id="edit_la_id" class="form-control" data-plugin-selectTwo data-width="100%">
						<option value=""><?=translate('select')?></option>
						<?php foreach ($learning_areas as $la): ?>
						<option value="<?=$la['id']?>"><?=$la['name']?></option>
						<?php endforeach; ?>
					</select>
					<span class="error"></span>
				</div>
				<div class="form-group">
					<label class="control-label">Strand Name <span class="required">*</span></label>
					<input type="text" class="form-control" name="name" id="edit_strand_name" value="" />
					<span class="error"></span>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-default" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
							<i class="fas fa-plus-circle"></i> <?=translate('update')?>
						</button>
						<button class="btn btn-default modal-dismiss"><?=translate('cancel')?></button>
					</div>
				</div>
			</footer>
		<?php echo form_close();?>
	</section>
</div>

<script type="text/javascript">
	function getStrandModal(el) {
		$('#edit_strand_id').val($(el).data('id'));
		$('#edit_strand_name').val($(el).data('name'));
		$('#edit_la_id').val($(el).data('la')).trigger('change');
		$('#edit_branch_id').val($(el).data('branch')).trigger('change');
		mfp_modal('#modal');
	}
</script>
<?php endif; ?>
