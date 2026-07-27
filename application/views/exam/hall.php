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
<?php if (get_permission('exam_hall', 'is_add')): ?>
	<div class="col-md-5">
		<div class="card mb-4">
			<div class="card-header">
				<h5 class="mb-0"><i class="far fa-edit me-2"></i><?=translate('add') . ' ' . translate('exam_hall')?></h5>
			</div>
			<?php echo form_open($this->uri->uri_string()); ?>
			<div class="card-body">
				<?php if (is_superadmin_loggedin()): ?>
				<div class="mb-3">
					<label class="form-label"><?=translate('branch')?> <span class="text-danger">*</span></label>
					<?php
						$arrayBranch = $this->app_lib->getSelectList('branch');
						echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control'
						data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
					?>
					<span class="small text-danger"><?=form_error('branch_id')?></span>
				</div>
				<?php endif; ?>
				<div class="mb-3">
					<label class="form-label"><?=translate('hall_no')?><?=help_tip('Hall name or number. Example: Hall A, Exam Room 1')?> <span class="text-danger">*</span></label>
					<input type="text" class="form-control" name="hall_no" value="<?=set_value('hall_no')?>">
					<span class="small text-danger"><?=form_error('hall_no')?></span>
				</div>
				<div class="mb-3">
					<label class="form-label"><?=translate('no_of_seats')?><?=help_tip('Maximum students that can sit exams here. Example: 50')?> <span class="text-danger">*</span></label>
					<input type="text" class="form-control" name="no_of_seats" value="<?=set_value('no_of_seats')?>">
					<span class="small text-danger"><?=form_error('no_of_seats')?></span>
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
<?php if (get_permission('exam_hall', 'is_view')): ?>
	<div class="col-md-<?php echo get_permission('exam_hall', 'is_add') ? '7' : '12'; ?>">
		<div class="card mb-4">
			<div class="card-header">
				<h5 class="mb-0"><i class="fas fa-list-ul me-2"></i><?=translate('exam_hall') . ' ' . translate('list')?></h5>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-sm mb-0">
						<thead>
							<tr>
								<th><?=translate('sl')?></th>
								<th><?=translate('branch')?></th>
								<th><?=translate('hall_no')?></th>
								<th><?=translate('no_of_seats')?></th>
								<th><?=translate('action')?></th>
							</tr>
						</thead>
						<tbody>
						<?php
						$count = 1;
						if (!empty($halllist)) {
							foreach ($halllist as $row):
						?>
						<tr>
							<td><?php echo $count++; ?></td>
							<td><?php echo htmlspecialchars($row['branch_name']); ?></td>
							<td><?php echo htmlspecialchars($row['hall_no']); ?></td>
							<td><?php echo htmlspecialchars($row['seats']); ?></td>
							<td class="min-w-xs">
							<?php if (get_permission('exam_hall', 'is_edit')): ?>
								<a class="btn btn-sm btn-outline-secondary" href="javascript:void(0);" onclick="getHallModal(this)"
								data-id="<?=$row['id']?>" data-number="<?=htmlspecialchars($row['hall_no'])?>" data-seats="<?=htmlspecialchars($row['seats'])?>" data-branch="<?=$row['branch_id']?>">
									<i class="fas fa-pen-nib"></i>
								</a>
							<?php endif; if (get_permission('exam_hall', 'is_delete')): ?>
								<?php echo btn_delete('exam/hall_delete/' . $row['id']); ?>
							<?php endif; ?>
							</td>
						</tr>
						<?php
							endforeach;
						} else {
							echo '<tr><td colspan="5" class="text-center"><h5 class="text-danger mb-0">' . translate('no_information_available') . '</h5></td></tr>';
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

<?php if (get_permission('exam_hall', 'is_edit')): ?>
<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="modal">
	<div class="card">
		<?php echo form_open('exam/hall_edit', array('class' => 'frm-submit')); ?>
		<div class="card-header">
			<h5 class="mb-0"><i class="far fa-edit me-2"></i><?=translate('edit') . ' ' . translate('exam_hall')?></h5>
		</div>
		<div class="card-body">
			<input type="hidden" name="hall_id" id="hall_id" value="">
			<?php if (is_superadmin_loggedin()): ?>
			<div class="mb-3">
				<label class="form-label"><?=translate('branch')?> <span class="text-danger">*</span></label>
				<?php
					$arrayBranch = $this->app_lib->getSelectList('branch');
					echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control'
					id='ebranch_id' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
				?>
				<span class="error small text-danger d-block"></span>
			</div>
			<?php endif; ?>
			<div class="mb-3">
				<label class="form-label"><?=translate('hall_no')?> <span class="text-danger">*</span></label>
				<input type="text" class="form-control" name="hall_no" id="ehall_no" value="">
				<span class="error small text-danger d-block"></span>
			</div>
			<div class="mb-3">
				<label class="form-label"><?=translate('no_of_seats')?> <span class="text-danger">*</span></label>
				<input type="number" class="form-control" name="no_of_seats" id="eno_of_seats" value="">
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
