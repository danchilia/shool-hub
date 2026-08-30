<div class="row">
<?php if (get_permission('cbc_learning_outcomes', 'is_add')): ?>
	<div class="col-md-5">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="far fa-edit"></i> Add Learning Outcome</h4>
			</header>
			<?php echo form_open($this->uri->uri_string()); ?>
				<div class="panel-body">
					<div class="form-group">
						<label class="control-label">Learning Area <span class="required">*</span></label>
						<select name="learning_area_id" id="lo_la_id" class="form-control" data-plugin-selectTwo data-width="100%" onchange="loLoadStrands(this.value, 'lo_strand_id')">
							<option value=""><?=translate('select')?></option>
							<?php foreach ($learning_areas as $la): ?>
							<option value="<?=$la['id']?>" <?=set_select('learning_area_id', $la['id'])?>><?=$la['name']?> (<?=ucfirst(str_replace('_', ' ', $la['level']))?>)</option>
							<?php endforeach; ?>
						</select>
						<span class="error"><?=form_error('learning_area_id')?></span>
					</div>
					<div class="form-group">
						<label class="control-label">Strand <span class="required">*</span></label>
						<select name="strand_id" id="lo_strand_id" class="form-control" data-plugin-selectTwo data-width="100%" onchange="loLoadSubStrands(this.value, 'lo_sub_strand_id')">
							<option value=""><?=translate('select')?></option>
							<?php foreach ($strands as $s): ?>
							<option value="<?=$s['id']?>" <?=set_select('strand_id', $s['id'])?>><?=$s['name']?> — <?=$s['learning_area_name']?></option>
							<?php endforeach; ?>
						</select>
						<span class="error"><?=form_error('strand_id')?></span>
					</div>
					<div class="form-group">
						<label class="control-label">Sub-Strand <small class="text-muted">(optional)</small></label>
						<select name="sub_strand_id" id="lo_sub_strand_id" class="form-control" data-plugin-selectTwo data-width="100%">
							<option value="">— none —</option>
							<?php foreach ($sub_strands as $ss): ?>
							<option value="<?=$ss['id']?>" <?=set_select('sub_strand_id', $ss['id'])?>><?=$ss['name']?> — <?=$ss['strand_name']?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="form-group">
						<label class="control-label">Code <small class="text-muted">(e.g. LO1, PI2 — optional)</small></label>
						<input type="text" class="form-control" name="code" value="<?=set_value('code')?>" placeholder="LO1" style="max-width:120px" />
					</div>
					<div class="form-group">
						<label class="control-label">Learning Outcome <span class="required">*</span></label>
						<textarea class="form-control" name="name" rows="3" placeholder="e.g. The learner is able to count objects up to 999"><?=set_value('name')?></textarea>
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
			<?php echo form_close(); ?>
		</section>
	</div>
<?php endif; ?>

<?php if (get_permission('cbc_learning_outcomes', 'is_view')): ?>
	<div class="col-md-<?php echo get_permission('cbc_learning_outcomes', 'is_add') ? '7' : '12'; ?>">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-list-ul"></i> Learning Outcomes List</h4>
			</header>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-sm mb-0 table-export">
						<thead>
							<tr>
								<th><?=translate('sl')?></th>
								<th>Learning Area</th>
								<th>Strand</th>
								<th>Sub-Strand</th>
								<th>Code</th>
								<th>Learning Outcome</th>
								<th><?=translate('action')?></th>
							</tr>
						</thead>
						<tbody>
							<?php $count = 1; if (!empty($outcomes)): foreach ($outcomes as $row): ?>
							<tr>
								<td><?=$count++?></td>
								<td style="font-size:12px"><?=htmlspecialchars($row['learning_area_name'])?></td>
								<td style="font-size:12px"><?=htmlspecialchars($row['strand_name'])?></td>
								<td style="font-size:12px;color:#777"><?=!empty($row['sub_strand_name']) ? htmlspecialchars($row['sub_strand_name']) : '—'?></td>
								<td><span class="label label-info"><?=htmlspecialchars($row['code'] ?: '—')?></span></td>
								<td style="font-size:12px"><?=htmlspecialchars($row['name'])?></td>
								<td>
								<?php if (get_permission('cbc_learning_outcomes', 'is_edit')): ?>
									<a class="btn btn-default btn-circle icon" href="javascript:void(0);"
									   onclick="getLoModal(this)"
									   data-id="<?=$row['id']?>"
									   data-name="<?=htmlspecialchars($row['name'], ENT_QUOTES)?>"
									   data-code="<?=htmlspecialchars($row['code'] ?: '', ENT_QUOTES)?>"
									   data-la="<?=$row['learning_area_id']?>"
									   data-strand="<?=$row['strand_id']?>"
									   data-substrand="<?=$row['sub_strand_id']?>">
										<i class="fas fa-pen-nib"></i>
									</a>
								<?php endif; if (get_permission('cbc_learning_outcomes', 'is_delete')): ?>
									<?php echo btn_delete('cbc/learning_outcome_delete/' . $row['id']); ?>
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
<?php endif; ?>

<?php if (get_permission('cbc_learning_outcomes', 'is_edit')): ?>
<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="modal">
	<section class="panel">
		<?php echo form_open('cbc/learning_outcome_edit', array('class' => 'frm-submit')); ?>
			<header class="panel-heading">
				<h4 class="panel-title"><i class="far fa-edit"></i> Edit Learning Outcome</h4>
			</header>
			<div class="panel-body">
				<input type="hidden" name="learning_outcome_id" id="edit_lo_id" />
				<div class="form-group">
					<label class="control-label">Learning Area <span class="required">*</span></label>
					<select name="learning_area_id" id="edit_lo_la_id" class="form-control" data-plugin-selectTwo data-width="100%" onchange="loLoadStrands(this.value, 'edit_lo_strand_id')">
						<option value=""><?=translate('select')?></option>
						<?php foreach ($learning_areas as $la): ?>
						<option value="<?=$la['id']?>"><?=$la['name']?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="form-group">
					<label class="control-label">Strand <span class="required">*</span></label>
					<select name="strand_id" id="edit_lo_strand_id" class="form-control" data-plugin-selectTwo data-width="100%" onchange="loLoadSubStrands(this.value, 'edit_lo_sub_strand_id')">
						<option value=""><?=translate('select')?></option>
						<?php foreach ($strands as $s): ?>
						<option value="<?=$s['id']?>"><?=$s['name']?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="form-group">
					<label class="control-label">Sub-Strand <small class="text-muted">(optional)</small></label>
					<select name="sub_strand_id" id="edit_lo_sub_strand_id" class="form-control" data-plugin-selectTwo data-width="100%">
						<option value="">— none —</option>
					</select>
				</div>
				<div class="form-group">
					<label class="control-label">Code</label>
					<input type="text" class="form-control" name="code" id="edit_lo_code" style="max-width:120px" />
				</div>
				<div class="form-group">
					<label class="control-label">Learning Outcome <span class="required">*</span></label>
					<textarea class="form-control" name="name" id="edit_lo_name" rows="3"></textarea>
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
		<?php echo form_close(); ?>
	</section>
</div>

<script type="text/javascript">
function loLoadStrands(laId, targetId) {
	if (!laId) return;
	$.post(base_url + 'cbc/getStrandsByLearningArea', {learning_area_id: laId}, function(html) {
		$('#' + targetId).html(html).trigger('change');
	});
}

function loLoadSubStrands(strandId, targetId) {
	var base = '<option value="">— none —</option>';
	if (!strandId) { $('#' + targetId).html(base); return; }
	$.post(base_url + 'cbc/getSubStrandsByStrand', {strand_id: strandId}, function(html) {
		$('#' + targetId).html(base + html.replace('<option value="">Select (optional)</option>', ''));
	});
}

function getLoModal(el) {
	var id       = $(el).data('id');
	var name     = $(el).data('name');
	var code     = $(el).data('code');
	var laId     = $(el).data('la');
	var strandId = $(el).data('strand');
	var ssId     = $(el).data('substrand');

	$('#edit_lo_id').val(id);
	$('#edit_lo_name').val(name);
	$('#edit_lo_code').val(code);
	$('#edit_lo_la_id').val(laId).trigger('change');

	$.post(base_url + 'cbc/getStrandsByLearningArea', {learning_area_id: laId}, function(html) {
		$('#edit_lo_strand_id').html(html).val(strandId).trigger('change');
		$.post(base_url + 'cbc/getSubStrandsByStrand', {strand_id: strandId}, function(ssHtml) {
			$('#edit_lo_sub_strand_id').html('<option value="">— none —</option>' + ssHtml.replace('<option value="">Select (optional)</option>', '')).val(ssId).trigger('change');
		});
	});

	mfp_modal('#modal');
}
</script>
<?php endif; ?>
