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
				<a href="#list" class="nav-link active" data-bs-toggle="tab"><i class="fas fa-list-ul me-1"></i><?=translate('exam_list')?></a>
			</li>
<?php if (get_permission('exam', 'is_add')): ?>
			<li class="nav-item">
				<a href="#create" class="nav-link" data-bs-toggle="tab"><i class="far fa-edit me-1"></i><?=translate('create_exam')?></a>
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
								<th width="50"><?=translate('sl')?></th>
							<?php if (is_superadmin_loggedin()): ?>
								<th><?=translate('branch')?></th>
							<?php endif; ?>
								<th><?=translate('exam_name')?></th>
								<th><?=translate('exam_type')?></th>
								<th><?=translate('term')?></th>
								<th><?=translate('mark_distribution')?><?=help_tip('Select assessment components. Example: CAT 1, CAT 2, End Term Exam')?></th>
								<th><?=translate('remarks')?></th>
								<th><?=translate('action')?></th>
							</tr>
						</thead>
						<tbody>
							<?php $count = 1; foreach ($examlist as $row): ?>
							<tr>
								<td><?php echo $count++; ?></td>
							<?php if (is_superadmin_loggedin()): ?>
								<td><?php echo htmlspecialchars($row['branch_name']); ?></td>
							<?php endif; ?>
								<td><?php echo htmlspecialchars($row['name']); ?></td>
								<td><?php
								if ($row['type_id'] == 1) {
									echo translate('marks');
								} elseif ($row['type_id'] == 2) {
									echo translate('grade');
								} elseif ($row['type_id'] == 3) {
									echo translate('marks_and_grade');
								}
								?></td>
								<td><?php echo (empty($row['term_id']) ? 'N/A' : htmlspecialchars(get_type_name_by_id('exam_term', $row['term_id']))); ?></td>
								<td><?php
									$distribution = json_decode($row['mark_distribution'], true);
									if (!empty($distribution)) {
										foreach ($distribution as $id) {
											echo '- ' . htmlspecialchars(get_type_name_by_id('exam_mark_distribution', $id)) . '<br>';
										}
									}
								?></td>
								<td><?php echo htmlspecialchars($row['remark']); ?></td>
								<td class="min-w-xs">
								<?php if (get_permission('exam', 'is_edit')): ?>
									<a href="<?php echo base_url('exam/edit/' . $row['id']); ?>" class="btn btn-sm btn-outline-secondary">
										<i class="fas fa-pen-nib"></i>
									</a>
								<?php endif; if (get_permission('exam', 'is_delete')): ?>
									<?php echo btn_delete('exam/delete/' . $row['id']); ?>
								<?php endif; ?>
								</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
<?php if (get_permission('exam', 'is_add')): ?>
			<div class="tab-pane p-3" id="create">
				<?php echo form_open($this->uri->uri_string(), array('class' => 'frm-submit')); ?>
				<div class="row g-3">
					<?php if (is_superadmin_loggedin()): ?>
					<div class="col-md-6">
						<label class="form-label"><?=translate('branch')?> <span class="text-danger">*</span></label>
						<?php
							$arrayBranch = $this->app_lib->getSelectList('branch');
							echo form_dropdown("branch_id", $arrayBranch, $branch_id, "class='form-control' id='branch_id'
							data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
						?>
						<span class="error small text-danger d-block"></span>
					</div>
					<?php endif; ?>
					<div class="col-md-6">
						<label class="form-label"><?=translate('name')?> <span class="text-danger">*</span><?=help_tip('Exam name. Example: Form 1 Term 2 Exam 2026')?></label>
						<input type="text" class="form-control" name="name">
						<span class="error small text-danger d-block"></span>
					</div>
					<div class="col-md-6">
						<label class="form-label">Grading System <span class="text-danger">*</span></label>
						<?php
							$arrayGrading = array(
								'traditional' => 'Traditional (Marks / Grade)',
								'cbc'         => 'CBC (Competency Based)',
							);
							echo form_dropdown("grading_system", $arrayGrading, set_value('grading_system', 'traditional'), "class='form-control' id='grading_system'
							data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
						?>
						<span class="error small text-danger d-block"></span>
					</div>
					<div class="col-md-6">
						<label class="form-label"><?=translate('term')?></label>
						<?php
							$array = $this->app_lib->getSelectByBranch('exam_term', $branch_id);
							echo form_dropdown("term_id", $array, set_value('term_id'), "class='form-control' id='term_id'
							data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
						?>
						<span class="error small text-danger d-block"></span>
					</div>
					<div id="traditional_fields" class="col-12">
						<div class="row g-3">
							<div class="col-md-6">
								<label class="form-label"><?=translate('exam_type')?></label>
								<?php
									$arrayType = array(
										''  => translate('select'),
										'1' => translate('marks'),
										'2' => translate('grade'),
										'3' => translate('marks_and_grade'),
									);
									echo form_dropdown("type_id", $arrayType, set_value('type_id'), "class='form-control' id='type_id'
									data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
								?>
								<span class="error small text-danger d-block"></span>
							</div>
							<div class="col-md-6">
								<label class="form-label"><?=translate('mark_distribution')?></label>
								<?php
									$arraySection = array();
									if (!is_superadmin_loggedin()) {
										$result = $this->db->where('branch_id', get_loggedin_branch_id())->get('exam_mark_distribution')->result();
										foreach ($result as $row) {
											$arraySection[$row->id] = $row->name;
										}
									}
									echo form_dropdown("mark_distribution[]", $arraySection, set_value('mark_distribution[]'), "class='form-control' multiple id='mark_distribution'
									data-plugin-selectTwo data-width='100%'");
								?>
								<span class="error small text-danger d-block"></span>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<label class="form-label"><?=translate('remarks')?></label>
						<textarea rows="2" class="form-control" name="remark"></textarea>
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

<script type="text/javascript">
	function toggleGradingFields() {
		if ($('#grading_system').val() === 'cbc') {
			$('#traditional_fields').hide();
		} else {
			$('#traditional_fields').show();
		}
	}

	$(document).ready(function () {
		$('#grading_system').on('change', toggleGradingFields);
		toggleGradingFields();

		$(document).on('change', '#branch_id', function() {
			var branchID = $(this).val();
			$.ajax({
				url: "<?=base_url('ajax/getDataByBranch')?>",
				type: 'POST',
				data: $.extend({ branch_id: branchID, table: 'exam_term' }, csrfData),
				success: function(data) {
					$('#term_id').html(data);
				}
			});

			$.ajax({
				url: "<?=base_url('exam/getDistributionByBranch')?>",
				type: 'POST',
				data: $.extend({ branch_id: branchID }, csrfData),
				success: function(data) {
					$('#mark_distribution').html(data);
				}
			});
		});
	});
</script>
