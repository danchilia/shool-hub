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

<div class="card">
	<div class="card-header p-0">
		<ul class="nav nav-tabs card-header-tabs ms-0">
			<li class="nav-item">
				<a href="#list" class="nav-link active" data-bs-toggle="tab"><i class="fas fa-list-ul me-1"></i><?=translate('grade_list')?></a>
			</li>
<?php if (get_permission('exam_grade', 'is_add')): ?>
			<li class="nav-item">
				<a href="#create" class="nav-link" data-bs-toggle="tab"><i class="far fa-edit me-1"></i><?=translate('create_grade')?></a>
			</li>
<?php endif; ?>
		</ul>
	</div>
	<div class="card-body p-0">
		<div class="tab-content">
			<div id="list" class="tab-pane active show p-3">
				<div class="table-responsive">
					<table class="table table-bordered table-hover mb-0 table-export">
						<thead>
							<tr>
								<th><?=translate('sl')?></th>
<?php if (is_superadmin_loggedin()): ?>
								<th><?=translate('branch')?></th>
<?php endif; ?>
								<th><?=translate('grade_name')?></th>
								<th><?=translate('grade_point')?><?=help_tip('Points for this grade. KCSE: A=12, B+=10, C=6, E=1')?></th>
								<th><?=translate('min_percentage')?></th>
								<th><?=translate('max_percentage')?></th>
								<th><?=translate('remarks')?></th>
								<th><?=translate('action')?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$count = 1;
							$grades = $this->db->get('grade')->result();
							foreach ($grades as $grade):
							?>
							<tr>
								<td><?php echo $count++; ?></td>
<?php if (is_superadmin_loggedin()): ?>
								<td><?php echo htmlspecialchars(get_type_name_by_id('branch', $grade->branch_id)); ?></td>
<?php endif; ?>
								<td><?php echo htmlspecialchars($grade->name); ?></td>
								<td><?php echo htmlspecialchars($grade->grade_point); ?></td>
								<td><?php echo htmlspecialchars($grade->lower_mark); ?>%</td>
								<td><?php echo htmlspecialchars($grade->upper_mark); ?>%</td>
								<td><?php echo htmlspecialchars($grade->remark); ?></td>
								<td class="min-w-xs">
								<?php if (get_permission('exam_grade', 'is_edit')): ?>
									<a href="<?php echo base_url('exam/grade_edit/' . $grade->id); ?>" class="btn btn-sm btn-outline-secondary">
										<i class="fas fa-pen-nib"></i>
									</a>
								<?php endif; if (get_permission('exam_grade', 'is_delete')): ?>
									<?php echo btn_delete('exam/grade_delete/' . $grade->id); ?>
								<?php endif; ?>
								</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
<?php if (get_permission('exam_grade', 'is_add')): ?>
			<div class="tab-pane p-3" id="create">
				<?php echo form_open($this->uri->uri_string(), array('class' => 'frm-submit')); ?>
				<div class="row g-3">
					<?php if (is_superadmin_loggedin()): ?>
					<div class="col-md-6">
						<label class="form-label"><?=translate('branch')?> <span class="text-danger">*</span></label>
						<?php
							$arrayBranch = $this->app_lib->getSelectList('branch');
							echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control'
							data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
						?>
						<span class="error small text-danger d-block"></span>
					</div>
					<?php endif; ?>
					<div class="col-md-6">
						<label class="form-label"><?=translate('name')?> <span class="text-danger">*</span></label>
						<input type="text" class="form-control" name="name">
						<span class="error small text-danger d-block"></span>
					</div>
					<div class="col-md-6">
						<label class="form-label"><?=translate('grade_point')?> <span class="text-danger">*</span></label>
						<input type="number" class="form-control" name="grade_point">
						<span class="error small text-danger d-block"></span>
					</div>
					<div class="col-md-6">
						<label class="form-label"><?=translate('min_percentage')?> <span class="text-danger">*</span></label>
						<input type="number" class="form-control" name="lower_mark">
						<span class="error small text-danger d-block"></span>
					</div>
					<div class="col-md-6">
						<label class="form-label"><?=translate('max_percentage')?> <span class="text-danger">*</span></label>
						<input type="number" class="form-control" name="upper_mark">
						<span class="error small text-danger d-block"></span>
					</div>
					<div class="col-md-6">
						<label class="form-label"><?=translate('remarks')?></label>
						<input type="text" class="form-control" name="remark">
					</div>
				</div>
				<div class="d-flex justify-content-end mt-4">
					<button type="submit" class="btn btn-primary" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
						<i class="fas fa-plus-circle me-1"></i><?=translate('save')?>
					</button>
				</div>
				<?php echo form_close(); ?>
			</div>
<?php endif; ?>
		</div>
	</div>
</div>
