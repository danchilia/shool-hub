<style>
@media (prefers-color-scheme: dark) {
    .card        { background: #2b2b3a; border-color: #3a3a50; }
    .card-header { background: #232333; border-color: #3a3a50; }
}
:root[data-theme="dark"]  .card        { background: #2b2b3a; border-color: #3a3a50; }
:root[data-theme="dark"]  .card-header { background: #232333; border-color: #3a3a50; }
:root[data-theme="light"] .card        { background: #fff;    border-color: #dee2e6; }
:root[data-theme="light"] .card-header { background: #f8f9fa; border-color: #dee2e6; }
</style>

<div class="row">
<?php if (get_permission('mark_distribution', 'is_add')): ?>
	<div class="col-md-5">
		<div class="card mb-4">
			<div class="card-header">
				<h5 class="mb-0"><i class="far fa-edit me-2"></i><?=translate('add') . ' ' . translate('mark_distribution')?></h5>
			</div>
			<?php echo form_open($this->uri->uri_string()); ?>
			<div class="card-body">
				<?php if (is_superadmin_loggedin()): ?>
				<div class="mb-3">
					<label class="form-label"><?=translate('branch')?> <span class="text-danger">*</span></label>
					<?php
						$arrayBranch = $this->app_lib->getSelectList('branch');
						echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' id='branch_id'
						data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
					?>
					<span class="small text-danger"><?=form_error('branch_id')?></span>
				</div>
				<?php endif; ?>
				<div class="mb-3">
					<label class="form-label"><?=translate('name')?> <span class="text-danger">*</span></label>
					<input type="text" class="form-control" name="name" value="<?=set_value('name')?>">
					<span class="small text-danger"><?=form_error('name')?></span>
				</div>
			</div>
			<div class="card-footer text-end">
				<button class="btn btn-primary" type="submit" name="save" value="1">
					<i class="fas fa-plus-circle me-1"></i><?=translate('save')?>
				</button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
<?php endif; ?>
<?php if (get_permission('mark_distribution', 'is_view')): ?>
	<div class="col-md-<?php echo get_permission('mark_distribution', 'is_add') ? '7' : '12'; ?>">
		<div class="card mb-4">
			<div class="card-header">
				<h5 class="mb-0"><i class="fas fa-list-ul me-2"></i><?=translate('mark_distribution') . ' ' . translate('list')?></h5>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-sm mb-0">
						<thead>
							<tr>
								<th><?=translate('sl')?></th>
								<th><?=translate('branch')?></th>
								<th><?=translate('name')?></th>
								<th><?=translate('action')?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$count = 1;
							if (!empty($termlist)) {
								foreach ($termlist as $row):
							?>
							<tr>
								<td><?php echo $count++; ?></td>
								<td><?php echo htmlspecialchars($row['branch_name']); ?></td>
								<td><?php echo htmlspecialchars($row['name']); ?></td>
								<td class="min-w-xs">
								<?php if (get_permission('mark_distribution', 'is_edit')): ?>
									<a class="btn btn-sm btn-outline-secondary" href="javascript:void(0);" onclick="getCategoryModal(this)"
									data-id="<?=$row['id']?>" data-name="<?=htmlspecialchars($row['name'])?>" data-branch="<?=$row['branch_id']?>">
										<i class="fas fa-pen-nib"></i>
									</a>
								<?php endif; if (get_permission('mark_distribution', 'is_delete')): ?>
									<?php echo btn_delete('exam/mark_distribution_delete/' . $row['id']); ?>
								<?php endif; ?>
								</td>
							</tr>
							<?php
								endforeach;
							} else {
								echo '<tr><td colspan="4" class="text-center"><h5 class="text-danger mb-0">' . translate('no_information_available') . '</h5></td></tr>';
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

<?php if (get_permission('mark_distribution', 'is_edit')): ?>
<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="modal">
	<div class="card">
		<?php echo form_open('exam/mark_distribution_edit', array('class' => 'frm-submit')); ?>
		<div class="card-header">
			<h5 class="mb-0"><i class="far fa-edit me-2"></i><?=translate('edit') . ' ' . translate('mark_distribution')?></h5>
		</div>
		<div class="card-body">
			<input type="hidden" name="distribution_id" id="ecategory_id" value="">
			<?php if (is_superadmin_loggedin()): ?>
			<div class="mb-3">
				<label class="form-label"><?=translate('branch')?> <span class="text-danger">*</span></label>
				<?php
					$arrayBranch = $this->app_lib->getSelectList('branch');
					echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' id='ebranch_id'
					data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
				?>
				<span class="error small text-danger d-block"></span>
			</div>
			<?php endif; ?>
			<div class="mb-3">
				<label class="form-label"><?=translate('name')?> <span class="text-danger">*</span></label>
				<input type="text" class="form-control" name="name" id="ename" value="">
				<span class="error small text-danger d-block"></span>
			</div>
		</div>
		<div class="card-footer text-end">
			<button type="submit" class="btn btn-primary" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
				<i class="fas fa-save me-1"></i><?=translate('update')?>
			</button>
			<button type="button" class="btn btn-outline-secondary modal-dismiss"><?=translate('cancel')?></button>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>
<?php endif; ?>
