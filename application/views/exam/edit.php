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
		<a href="<?=base_url('exam')?>" class="text-muted small">
			<i class="fas fa-arrow-left me-1"></i><?=translate('exam_list')?>
		</a>
	</div>
</div>

<div class="card">
	<div class="card-header">
		<h5 class="mb-0"><i class="far fa-edit me-2"></i><?=translate('edit_exam')?></h5>
	</div>
	<div class="card-body">
		<?php echo form_open($this->uri->uri_string(), array('class' => 'frm-submit')); ?>
		<input type="hidden" name="exam_id" value="<?=$exam['id']?>">
		<input type="hidden" name="branch_id" value="<?=$exam['branch_id']?>">
		<input type="hidden" name="grading_system" value="<?=htmlspecialchars($exam['grading_system'])?>">
		<div class="row g-3">
			<div class="col-md-6">
				<label class="form-label"><?=translate('name')?> <span class="text-danger">*</span></label>
				<input type="text" class="form-control" name="name" value="<?=htmlspecialchars($exam['name'])?>">
				<span class="error small text-danger d-block"></span>
			</div>
			<div class="col-md-6">
				<label class="form-label"><?=translate('term')?></label>
				<?php
					$array = $this->app_lib->getSelectByBranch('exam_term', $exam['branch_id']);
					echo form_dropdown("term_id", $array, $exam['term_id'], "class='form-control' id='term_id'
					data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
				?>
				<span class="error small text-danger d-block"></span>
			</div>
			<div class="col-md-6">
				<label class="form-label"><?=translate('exam_type')?></label>
				<?php
					$arrayType = array(
						''  => translate('select'),
						'1' => translate('marks'),
						'2' => translate('grade'),
						'3' => translate('marks_and_grade'),
					);
					echo form_dropdown("type_id", $arrayType, $exam['type_id'], "class='form-control' id='type_id'
					data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
				?>
				<span class="error small text-danger d-block"></span>
			</div>
			<div class="col-md-6">
				<label class="form-label"><?=translate('mark_distribution')?></label>
				<?php
					$sel = json_decode($exam['mark_distribution'], true);
					$arraySection = array();
					$result = $this->db->where('branch_id', $exam['branch_id'])->get('exam_mark_distribution')->result();
					foreach ($result as $row) {
						$arraySection[$row->id] = $row->name;
					}
					echo form_dropdown("mark_distribution[]", $arraySection, $sel, "class='form-control' multiple id='mark_distribution'
					data-plugin-selectTwo data-width='100%'");
				?>
				<span class="error small text-danger d-block"></span>
			</div>
			<div class="col-md-6">
				<label class="form-label"><?=translate('remarks')?></label>
				<textarea rows="2" class="form-control" name="remark"><?=htmlspecialchars($exam['remark'])?></textarea>
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
