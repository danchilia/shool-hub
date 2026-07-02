<div class="row">
<?php if (get_permission('biometric_mapping', 'is_add')): ?>
	<div class="col-md-5">
		<section class="panel">
			<header class="panel-heading"><h4 class="panel-title"><i class="far fa-edit"></i> Link Fingerprint ID</h4></header>
			<?php echo form_open($this->uri->uri_string()); ?>
				<div class="panel-body">
					<div class="form-group">
						<label>Biometric ID <span class="required">*</span><?=help_tip('The ID number assigned when registering this person\'s fingerprint on the device. Check the device screen/software for this number. Example: 23')?></label>
						<input type="text" class="form-control" name="biometric_id" value="<?=set_value('biometric_id')?>" placeholder="e.g. 23" />
						<span class="error"><?=form_error('biometric_id')?></span>
					</div>
					<div class="form-group">
						<label>Type <span class="required">*</span></label>
						<select name="person_type" id="person_type" class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" onchange="togglePersonType()">
							<option value="student">Student</option>
							<option value="staff">Staff</option>
						</select>
					</div>

					<div id="student_fields">
						<div class="form-group">
							<label><?=translate('class')?></label>
							<?php
								$arrayClass = $this->app_lib->getClass($branch_id);
								echo form_dropdown("class_id", $arrayClass, set_value('class_id'), "class='form-control' id='class_id' onchange='getSectionByClass(this.value,0); loadStudents();' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
							?>
						</div>
						<div class="form-group">
							<label><?=translate('section')?></label>
							<select name="section_id_filter" id="section_id" class="form-control" data-plugin-selectTwo data-width="100%" onchange="loadStudents()">
								<option value=""><?=translate('select')?></option>
							</select>
						</div>
						<div class="form-group">
							<label>Student <span class="required">*</span></label>
							<select name="person_id" id="student_select" class="form-control" data-plugin-selectTwo data-width="100%">
								<option value=""><?=translate('select')?></option>
							</select>
						</div>
					</div>

					<div id="staff_fields" style="display:none;">
						<div class="form-group">
							<label>Staff Member <span class="required">*</span></label>
							<select name="staff_person_id" id="staff_select" class="form-control" data-plugin-selectTwo data-width="100%">
								<option value=""><?=translate('select')?></option>
								<?php $staffList = $this->db->where('branch_id', $branch_id)->get('staff')->result_array(); foreach ($staffList as $st): ?>
								<option value="<?=$st['id']?>"><?=$st['name']?> (<?=$st['staff_id']?>)</option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
				</div>
				<div class="panel-footer">
					<button class="btn btn-default pull-right" type="submit" name="save" value="1"><i class="fas fa-link"></i> Link Fingerprint</button>
				</div>
			<?php echo form_close(); ?>
		</section>
	</div>
<?php endif; ?>
	<div class="col-md-<?=get_permission('biometric_mapping', 'is_add') ? '7' : '12'?>">
		<section class="panel">
			<header class="panel-heading"><h4 class="panel-title"><i class="fas fa-fingerprint"></i> Linked Fingerprints</h4></header>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-condensed table-export mb-none">
						<thead>
							<tr><th>#</th><th>Biometric ID</th><th>Name</th><th>Code</th><th>Type</th><th>Action</th></tr>
						</thead>
						<tbody>
						<?php $c = 1; if (count($mappings)): foreach ($mappings as $m): ?>
							<tr>
								<td><?=$c++?></td>
								<td><strong><?=$m['biometric_id']?></strong></td>
								<td><?=$m['person_name']?></td>
								<td><?=$m['person_code']?></td>
								<td><?=ucfirst($m['person_type'])?></td>
								<td>
								<?php if (get_permission('biometric_mapping', 'is_delete')): ?>
									<a class="btn btn-danger icon btn-circle" onclick="confirm_modal('<?=base_url('biometric/mapping_delete/' . $m['id'])?>')"><i class="fas fa-trash-alt"></i></a>
								<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; else: ?>
							<tr><td colspan="6"><h5 class="text-danger text-center"><?=translate('no_information_available')?></h5></td></tr>
						<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</section>
	</div>
</div>

<script>
function togglePersonType() {
	var type = $('#person_type').val();
	if (type == 'student') {
		$('#student_fields').show();
		$('#staff_fields').hide();
		$('#staff_select').prop('name', '');
		$('#student_select').prop('name', 'person_id');
	} else {
		$('#student_fields').hide();
		$('#staff_fields').show();
		$('#student_select').prop('name', '');
		$('#staff_select').prop('name', 'person_id');
	}
}
function loadStudents() {
	var classId = $('#class_id').val();
	var sectionId = $('#section_id').val();
	if (!classId || !sectionId) return;
	$.ajax({
		url: base_url + 'biometric/get_students_by_class',
		type: 'POST',
		data: {class_id: classId, section_id: sectionId},
		success: function(data) { $('#student_select').html(data); }
	});
}
$(document).ready(function() { togglePersonType(); });
</script>
