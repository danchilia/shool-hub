<div class="row">
<?php if (get_permission('cbc_sub_strands', 'is_add')): ?>
	<div class="col-md-5">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="far fa-edit"></i> Add Sub-Strand</h4>
			</header>
			<?php echo form_open($this->uri->uri_string()); ?>
				<div class="panel-body">
					<div class="form-group">
						<label class="control-label">Learning Area <span class="required">*</span></label>
						<select name="learning_area_id" id="la_id" class="form-control" data-plugin-selectTwo data-width="100%" onchange="loadStrandsForLA(this.value, 'strand_id')">
							<option value=""><?=translate('select')?></option>
							<?php foreach ($learning_areas as $la): ?>
							<option value="<?=$la['id']?>" <?=set_select('learning_area_id', $la['id'])?>><?=$la['name']?> (<?=ucfirst(str_replace('_', ' ', $la['level']))?>)</option>
							<?php endforeach; ?>
						</select>
						<span class="error"><?=form_error('learning_area_id')?></span>
					</div>
					<div class="form-group">
						<label class="control-label">Strand <span class="required">*</span></label>
						<select name="strand_id" id="strand_id" class="form-control" data-plugin-selectTwo data-width="100%">
							<option value=""><?=translate('select')?></option>
							<?php foreach ($strands as $s): ?>
							<option value="<?=$s['id']?>" <?=set_select('strand_id', $s['id'])?>><?=$s['name']?> — <?=$s['learning_area_name']?></option>
							<?php endforeach; ?>
						</select>
						<span class="error"><?=form_error('strand_id')?></span>
					</div>
					<div class="form-group">
						<label class="control-label">Sub-Strand Name <span class="required">*</span></label>
						<input type="text" class="form-control" name="name" value="<?=set_value('name')?>" placeholder="e.g. Whole Numbers" />
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

<?php if (get_permission('cbc_sub_strands', 'is_view')): ?>
	<div class="col-md-<?php echo get_permission('cbc_sub_strands', 'is_add') ? '7' : '12'; ?>">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-list-ul"></i> Sub-Strands List</h4>
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
								<th><?=translate('action')?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$count = 1;
							if (!empty($sub_strands)) {
								foreach ($sub_strands as $row):
							?>
							<tr>
								<td><?php echo $count++; ?></td>
								<td><?php echo htmlspecialchars($row['learning_area_name']); ?></td>
								<td><?php echo htmlspecialchars($row['strand_name']); ?></td>
								<td><?php echo htmlspecialchars($row['name']); ?></td>
								<td>
								<?php if (get_permission('cbc_sub_strands', 'is_edit')): ?>
									<a class="btn btn-default btn-circle icon" href="javascript:void(0);"
									   onclick="getSubStrandModal(this)"
									   data-id="<?=$row['id']?>"
									   data-name="<?=htmlspecialchars($row['name'], ENT_QUOTES)?>"
									   data-la="<?=$row['learning_area_id']?>"
									   data-strand="<?=$row['strand_id']?>">
										<i class="fas fa-pen-nib"></i>
									</a>
								<?php endif; if (get_permission('cbc_sub_strands', 'is_delete')): ?>
									<?php echo btn_delete('cbc/sub_strand_delete/' . $row['id']); ?>
								<?php endif; ?>
								</td>
							</tr>
							<?php
								endforeach;
							} else {
								echo '<tr><td colspan="5"><h5 class="text-danger text-center">' . translate('no_information_available') . '</h5></td></tr>';
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

<?php if (get_permission('cbc_sub_strands', 'is_edit')): ?>
<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="modal">
	<section class="panel">
		<?php echo form_open('cbc/sub_strand_edit', array('class' => 'frm-submit')); ?>
			<header class="panel-heading">
				<h4 class="panel-title"><i class="far fa-edit"></i> Edit Sub-Strand</h4>
			</header>
			<div class="panel-body">
				<input type="hidden" name="sub_strand_id" id="edit_sub_strand_id" value="" />
				<div class="form-group">
					<label class="control-label">Learning Area <span class="required">*</span></label>
					<select name="learning_area_id" id="edit_la_id" class="form-control" data-plugin-selectTwo data-width="100%" onchange="loadStrandsForLA(this.value, 'edit_strand_id')">
						<option value=""><?=translate('select')?></option>
						<?php foreach ($learning_areas as $la): ?>
						<option value="<?=$la['id']?>"><?=$la['name']?></option>
						<?php endforeach; ?>
					</select>
					<span class="error"></span>
				</div>
				<div class="form-group">
					<label class="control-label">Strand <span class="required">*</span></label>
					<select name="strand_id" id="edit_strand_id" class="form-control" data-plugin-selectTwo data-width="100%">
						<option value=""><?=translate('select')?></option>
						<?php foreach ($strands as $s): ?>
						<option value="<?=$s['id']?>"><?=$s['name']?></option>
						<?php endforeach; ?>
					</select>
					<span class="error"></span>
				</div>
				<div class="form-group">
					<label class="control-label">Sub-Strand Name <span class="required">*</span></label>
					<input type="text" class="form-control" name="name" id="edit_sub_strand_name" value="" />
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
		<?php echo form_close(); ?>
	</section>
</div>

<script type="text/javascript">
function loadStrandsForLA(laId, targetId) {
	if (!laId) return;
	$.post(base_url + 'cbc/getStrandsByLearningArea', { learning_area_id: laId }, function(html) {
		$('#' + targetId).html(html);
		if (typeof $('#' + targetId).data('plugin-selectTwo') !== 'undefined') {
			$('#' + targetId).trigger('change');
		}
	});
}

function getSubStrandModal(el) {
	var id     = $(el).data('id');
	var name   = $(el).data('name');
	var laId   = $(el).data('la');
	var strand = $(el).data('strand');

	$('#edit_sub_strand_id').val(id);
	$('#edit_sub_strand_name').val(name);
	$('#edit_la_id').val(laId).trigger('change');

	// Load strands for that LA then set strand
	$.post(base_url + 'cbc/getStrandsByLearningArea', { learning_area_id: laId }, function(html) {
		$('#edit_strand_id').html(html).val(strand).trigger('change');
	});

	mfp_modal('#modal');
}
</script>
<?php endif; ?>
