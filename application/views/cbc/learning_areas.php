<div class="row">
<?php if (get_permission('cbc_learning_areas', 'is_add')): ?>
	<div class="col-md-5">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="far fa-edit"></i> Add Learning Area</h4>
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
						<label class="control-label">Learning Area Name <span class="required">*</span></label>
						<input type="text" class="form-control" name="name" value="<?=set_value('name')?>" />
						<span class="error"><?=form_error('name')?></span>
					</div>
					<div class="form-group">
						<label class="control-label">Curriculum Level <span class="required">*</span><?=help_tip('Pre-Primary = PP1-PP2&lt;br&gt;Lower Primary = Grade 1-3&lt;br&gt;Upper Primary = Grade 4-6&lt;br&gt;Junior Secondary = Grade 7-9. Each level has its own learning areas as defined by KICD.')?></label>
						<?php
							$levels = array(
								'' => translate('select'),
								'pp' => 'Pre-Primary (PP1-PP2)',
								'lower_primary' => 'Lower Primary (Grade 1-3)',
								'upper_primary' => 'Upper Primary (Grade 4-6)',
								'junior_secondary' => 'Junior Secondary (Grade 7-9)',
							);
							echo form_dropdown("level", $levels, set_value('level'), "class='form-control' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
						?>
						<span class="error"><?=form_error('level')?></span>
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
<?php if (get_permission('cbc_learning_areas', 'is_view')): ?>
	<div class="col-md-<?php echo get_permission('cbc_learning_areas', 'is_add') ? '7' : '12'; ?>">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-list-ul"></i> Learning Areas List</h4>
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
								<th>Level</th>
								<th><?=translate('action')?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$count = 1;
							if (!empty($areas)){
								$levelLabels = array('pp' => 'Pre-Primary', 'lower_primary' => 'Lower Primary', 'upper_primary' => 'Upper Primary', 'junior_secondary' => 'Junior Secondary');
								foreach ($areas as $row):
							?>
							<tr>
								<td><?php echo $count++;?></td>
								<?php if (is_superadmin_loggedin()): ?>
								<td><?php echo $row['branch_name']; ?></td>
								<?php endif; ?>
								<td><?php echo $row['name']; ?></td>
								<td><?php echo isset($levelLabels[$row['level']]) ? $levelLabels[$row['level']] : $row['level']; ?></td>
								<td>
								<?php if (get_permission('cbc_learning_areas', 'is_edit')): ?>
									<a class="btn btn-default btn-circle icon" href="javascript:void(0);" onclick="getEditModal(this)"
									data-id="<?=$row['id']?>" data-name="<?=$row['name']?>" data-level="<?=$row['level']?>" data-branch="<?=$row['branch_id']?>">
										<i class="fas fa-pen-nib"></i>
									</a>
								<?php endif; if (get_permission('cbc_learning_areas', 'is_delete')): ?>
									<?php echo btn_delete('cbc/learning_area_delete/' . $row['id']);?>
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

<?php if (get_permission('cbc_learning_areas', 'is_edit')): ?>
<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="modal">
	<section class="panel">
		<?php echo form_open('cbc/learning_area_edit', array('class' => 'frm-submit')); ?>
			<header class="panel-heading">
				<h4 class="panel-title"><i class="far fa-edit"></i> Edit Learning Area</h4>
			</header>
			<div class="panel-body">
				<input type="hidden" name="learning_area_id" id="edit_id" value="" />
				<?php if (is_superadmin_loggedin()): ?>
				<div class="form-group">
					<label class="control-label"><?=translate('branch')?> <span class="required">*</span></label>
					<?php
						$arrayBranch = $this->app_lib->getSelectList('branch');
						echo form_dropdown("branch_id", $arrayBranch, '', "class='form-control' id='edit_branch_id' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
					?>
					<span class="error"></span>
				</div>
				<?php endif; ?>
				<div class="form-group">
					<label class="control-label">Learning Area Name <span class="required">*</span></label>
					<input type="text" class="form-control" name="name" id="edit_name" value="" />
					<span class="error"></span>
				</div>
				<div class="form-group">
					<label class="control-label">Curriculum Level <span class="required">*</span><?=help_tip('Pre-Primary = PP1-PP2&lt;br&gt;Lower Primary = Grade 1-3&lt;br&gt;Upper Primary = Grade 4-6&lt;br&gt;Junior Secondary = Grade 7-9. Each level has its own learning areas as defined by KICD.')?></label>
					<?php
						$levels = array('' => translate('select'), 'pp' => 'Pre-Primary', 'lower_primary' => 'Lower Primary', 'upper_primary' => 'Upper Primary', 'junior_secondary' => 'Junior Secondary');
						echo form_dropdown("level", $levels, '', "class='form-control' id='edit_level' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
					?>
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
	function getEditModal(el) {
		$('#edit_id').val($(el).data('id'));
		$('#edit_name').val($(el).data('name'));
		$('#edit_level').val($(el).data('level')).trigger('change');
		$('#edit_branch_id').val($(el).data('branch')).trigger('change');
		mfp_modal('#modal');
	}
</script>
<?php endif; ?>
