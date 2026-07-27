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

<div class="content-header">
	<div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
		<a href="<?=base_url('exam/grade')?>" class="text-muted small">
			<i class="fas fa-arrow-left me-1"></i><?=translate('grade_list')?>
		</a>
	</div>
</div>

<div class="card">
	<div class="card-header">
		<h5 class="mb-0"><i class="far fa-edit me-2"></i><?=translate('edit_grade')?></h5>
	</div>
	<div class="card-body">
		<?php echo form_open($this->uri->uri_string(), array('class' => 'frm-submit')); ?>
		<input type="hidden" name="grade_id" value="<?=$grade['id']?>">
		<div class="row g-3">
			<?php if (is_superadmin_loggedin()): ?>
			<div class="col-md-6">
				<label class="form-label"><?=translate('branch')?> <span class="text-danger">*</span></label>
				<?php
					$arrayBranch = $this->app_lib->getSelectList('branch');
					echo form_dropdown("branch_id", $arrayBranch, $grade['branch_id'], "class='form-control'
					data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
				?>
				<span class="error small text-danger d-block"></span>
			</div>
			<?php endif; ?>
			<div class="col-md-6">
				<label class="form-label"><?=translate('name')?> <span class="text-danger">*</span></label>
				<input type="text" class="form-control" name="name" value="<?=htmlspecialchars($grade['name'])?>">
				<span class="error small text-danger d-block"></span>
			</div>
			<div class="col-md-6">
				<label class="form-label"><?=translate('grade_point')?> <span class="text-danger">*</span></label>
				<input type="text" class="form-control" name="grade_point" value="<?=htmlspecialchars($grade['grade_point'])?>">
				<span class="error small text-danger d-block"></span>
			</div>
			<div class="col-md-6">
				<label class="form-label"><?=translate('min_percentage')?> <span class="text-danger">*</span></label>
				<input type="text" class="form-control" name="lower_mark" value="<?=htmlspecialchars($grade['lower_mark'])?>">
				<span class="error small text-danger d-block"></span>
			</div>
			<div class="col-md-6">
				<label class="form-label"><?=translate('max_percentage')?> <span class="text-danger">*</span></label>
				<input type="text" class="form-control" name="upper_mark" value="<?=htmlspecialchars($grade['upper_mark'])?>">
				<span class="error small text-danger d-block"></span>
			</div>
			<div class="col-md-6">
				<label class="form-label"><?=translate('remarks')?></label>
				<input type="text" class="form-control" name="remark" value="<?=htmlspecialchars($grade['remark'])?>">
			</div>
		</div>
		<div class="d-flex justify-content-end mt-4">
			<button type="submit" class="btn btn-primary" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
				<i class="fas fa-save me-1"></i><?=translate('update')?>
			</button>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>
