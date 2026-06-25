<?php echo form_open($this->uri->uri_string(), array('enctype' => 'multipart/form-data'));?>
<section class="panel">
	<header class="panel-heading">
		<h4 class="panel-title"><i class="fas fa-user-plus"></i> New Admission Request</h4>
	</header>
	<div class="panel-body">
		<div class="alert alert-info">
			<i class="fas fa-info-circle"></i> This admission request will be submitted for admin approval. The student will be enrolled once approved.
		</div>

		<div class="headers-line mt-md"><i class="far fa-user"></i> Student Information</div>
		<div class="row">
			<?php if (is_superadmin_loggedin()): ?>
			<div class="col-md-4">
				<div class="form-group">
					<label class="control-label"><?=translate('branch')?> <span class="required">*</span></label>
					<?php
						$arrayBranch = $this->app_lib->getSelectList('branch');
						echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' id='branch_id' onchange='getClassByBranch(this.value)' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
					?>
					<span class="error"><?=form_error('branch_id')?></span>
				</div>
			</div>
			<?php endif; ?>
			<div class="col-md-4">
				<div class="form-group">
					<label class="control-label"><?=translate('academic_year')?> <span class="required">*</span></label>
					<?php
						$arrayYear = array('' => translate('select'));
						$years = $this->db->get('schoolyear')->result();
						foreach ($years as $year) {
							$arrayYear[$year->id] = $year->school_year;
						}
						echo form_dropdown("year_id", $arrayYear, set_value('year_id', get_session_id()), "class='form-control' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
					?>
					<span class="error"><?=form_error('year_id')?></span>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label class="control-label">Register No</label>
					<input type="text" class="form-control" name="register_no" value="<?=set_value('register_no', $register_id)?>" />
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label class="control-label"><?=translate('class')?> <span class="required">*</span></label>
					<?php
						$arrayClass = $this->app_lib->getClass($branch_id);
						echo form_dropdown("class_id", $arrayClass, set_value('class_id'), "class='form-control' id='class_id' onchange='getSectionByClass(this.value,0)' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
					?>
					<span class="error"><?=form_error('class_id')?></span>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label class="control-label"><?=translate('section')?> <span class="required">*</span></label>
					<select name="section_id" id="section_id" class="form-control" data-plugin-selectTwo data-width="100%">
						<option value=""><?=translate('select')?></option>
					</select>
					<span class="error"><?=form_error('section_id')?></span>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label class="control-label"><?=translate('category')?> <span class="required">*</span></label>
					<?php
						$arrayCategory = $this->app_lib->getSelectByBranch('student_category', $branch_id);
						echo form_dropdown("category_id", $arrayCategory, set_value('category_id'), "class='form-control' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
					?>
					<span class="error"><?=form_error('category_id')?></span>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label class="control-label"><?=translate('first_name')?> <span class="required">*</span></label>
					<input type="text" class="form-control" name="first_name" value="<?=set_value('first_name')?>" />
					<span class="error"><?=form_error('first_name')?></span>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label class="control-label"><?=translate('last_name')?> <span class="required">*</span></label>
					<input type="text" class="form-control" name="last_name" value="<?=set_value('last_name')?>" />
					<span class="error"><?=form_error('last_name')?></span>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label class="control-label"><?=translate('gender')?></label>
					<?php
						$genderArray = array('' => translate('select'), 'male' => translate('male'), 'female' => translate('female'));
						echo form_dropdown("gender", $genderArray, set_value('gender'), "class='form-control' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
					?>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label class="control-label"><?=translate('mobile_no')?> <span class="required">*</span></label>
					<input type="text" class="form-control" name="mobileno" value="<?=set_value('mobileno')?>" placeholder="+254..." />
					<span class="error"><?=form_error('mobileno')?></span>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label class="control-label"><?=translate('email')?> <span class="required">*</span></label>
					<input type="email" class="form-control" name="email" value="<?=set_value('email')?>" />
					<span class="error"><?=form_error('email')?></span>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label class="control-label">UPI Number (NEMIS)</label>
					<input type="text" class="form-control" name="upi_number" value="<?=set_value('upi_number')?>" />
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label class="control-label"><?=translate('birthday')?></label>
					<input type="text" class="form-control" name="birthday" value="<?=set_value('birthday')?>" data-plugin-datepicker data-plugin-options='{"todayHighlight": true, "autoclose": true, "format": "dd-mm-yyyy"}' />
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label class="control-label">Admission Date</label>
					<input type="text" class="form-control" name="admission_date" value="<?=set_value('admission_date', date('d-m-Y'))?>" data-plugin-datepicker data-plugin-options='{"todayHighlight": true, "autoclose": true, "format": "dd-mm-yyyy"}' />
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label class="control-label">Roll Number</label>
					<input type="number" class="form-control" name="roll" value="<?=set_value('roll')?>" />
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label"><?=translate('password')?> <span class="required">*</span></label>
					<input type="password" class="form-control" name="password" value="" />
					<span class="error"><?=form_error('password')?></span>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label"><?=translate('retype_password')?> <span class="required">*</span></label>
					<input type="password" class="form-control" name="retype_password" value="" />
					<span class="error"><?=form_error('retype_password')?></span>
				</div>
			</div>
		</div>

		<!-- Guardian Section -->
		<div class="headers-line mt-lg"><i class="fas fa-user-friends"></i> Guardian Information</div>
		<div class="form-group">
			<label>
				<input type="checkbox" name="guardian_chk" id="guardian_chk" value="1" <?=set_value('guardian_chk') ? 'checked' : ''?> />
				Select existing guardian/parent
			</label>
		</div>

		<div id="existing_guardian" style="display:none;">
			<div class="form-group">
				<label class="control-label"><?=translate('guardian')?></label>
				<?php
					$arrayParent = $this->app_lib->getSelectByBranch('parent', $branch_id, false);
					echo form_dropdown("parent_id", $arrayParent, set_value('parent_id'), "class='form-control' data-plugin-selectTwo data-width='100%'");
				?>
				<span class="error"><?=form_error('parent_id')?></span>
			</div>
		</div>

		<div id="new_guardian">
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label"><?=translate('name')?> <span class="required">*</span></label>
						<input type="text" class="form-control" name="grd_name" value="<?=set_value('grd_name')?>" />
						<span class="error"><?=form_error('grd_name')?></span>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label"><?=translate('relation')?> <span class="required">*</span></label>
						<?php
							$relArray = array('' => translate('select'), 'Father' => 'Father', 'Mother' => 'Mother', 'Brother' => 'Brother', 'Sister' => 'Sister', 'Uncle' => 'Uncle', 'Aunt' => 'Aunt', 'Other' => 'Other');
							echo form_dropdown("grd_relation", $relArray, set_value('grd_relation'), "class='form-control' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
						?>
						<span class="error"><?=form_error('grd_relation')?></span>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label">Occupation</label>
						<input type="text" class="form-control" name="grd_occupation" value="<?=set_value('grd_occupation')?>" />
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label"><?=translate('mobile_no')?> <span class="required">*</span></label>
						<input type="text" class="form-control" name="grd_mobileno" value="<?=set_value('grd_mobileno')?>" placeholder="+254..." />
						<span class="error"><?=form_error('grd_mobileno')?></span>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label"><?=translate('email')?> <span class="required">*</span></label>
						<input type="email" class="form-control" name="grd_email" value="<?=set_value('grd_email')?>" />
						<span class="error"><?=form_error('grd_email')?></span>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label"><?=translate('password')?> <span class="required">*</span></label>
						<input type="password" class="form-control" name="grd_password" value="" />
						<span class="error"><?=form_error('grd_password')?></span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="panel-footer">
		<div class="row">
			<div class="col-md-12">
				<button class="btn btn-default pull-right" type="submit" name="save" value="1">
					<i class="fas fa-paper-plane"></i> Submit Admission Request
				</button>
			</div>
		</div>
	</div>
</section>
<?php echo form_close();?>

<script type="text/javascript">
	$('#guardian_chk').change(function() {
		if ($(this).is(':checked')) {
			$('#existing_guardian').show();
			$('#new_guardian').hide();
		} else {
			$('#existing_guardian').hide();
			$('#new_guardian').show();
		}
	}).trigger('change');
</script>
