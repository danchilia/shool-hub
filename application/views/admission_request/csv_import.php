<div class="row">
	<div class="col-md-12">
		<section class="panel">
		<?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'form-horizontal form-bordered validate'));?>
			<header class="panel-heading">
				<h4 class="panel-title">
					<i class="fas fa-file-csv"></i> Bulk Admission Request (CSV Import)
				</h4>
			</header>
			<div class="panel-body">
				<div class="alert alert-info">
					<i class="fas fa-info-circle"></i> <strong>How it works:</strong> Upload a CSV file with student details.
					Each student will be submitted as a <strong>pending admission request</strong>.
					The school admin will review and approve/reject each one.
				</div>
			<?php if ($this->session->flashdata('csvimport')): ?>
				<div class="alert alert-danger"><?php echo $this->session->flashdata('csvimport'); ?></div>
			<?php endif; ?>
				<div class="form-group mt-md">
					<div class="col-md-12 mb-md">
						<a class="btn btn-default pull-right" href="<?=base_url('admission_request/csv_sample_download')?>">
							<i class="fas fa-file-download"></i> Download Sample CSV File
						</a>
					</div>
					<div class="col-md-12">
						<div class="alert alert-subl">
							<strong>Instructions:</strong><br/>
							1. Download the sample CSV file above.<br/>
							2. Open it in Excel and fill in the student details carefully.<br/>
							3. For "Birthday" and "AdmissionDate" use format: Y-m-d (<?=date('Y-m-d')?>).<br/>
							4. Each roll number must be unique within the class + section.<br/>
							5. For "CategoryID", enter the category number (e.g. 10=Regular, 11=Scholarship, 12=Bursary).<br/>
							6. "UPINumber" (NEMIS) is optional - leave blank if not available.<br/>
							7. If the guardian already exists in the system, just enter their email - the system will link them automatically.
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3"><?=translate('class')?> <span class="required">*</span></label>
					<div class="col-md-6">
						<?php
							$arrayClass = $this->app_lib->getClass($branch_id);
							echo form_dropdown("class_id", $arrayClass, set_value('class_id'), "class='form-control' id='class_id' onchange='getSectionByClass(this.value,0)' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
						?>
						<span class="error"><?=form_error('class_id')?></span>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3"><?=translate('section')?> <span class="required">*</span></label>
					<div class="col-md-6">
						<?php
							$arraySection = $this->app_lib->getSections(set_value('class_id'));
							echo form_dropdown("section_id", $arraySection, set_value('section_id'), "class='form-control' id='section_id' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
						?>
						<span class="error"><?=form_error('section_id')?></span>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3">CSV File <span class="required">*</span></label>
					<div class="col-md-6 mb-lg">
						<input type="file" name="userfile" class="dropify" data-height="140" data-allowed-file-extensions="csv" />
						<?php echo form_error('userfile', '<label class="error">', '</label>'); ?>
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-offset-3 col-md-3">
						<button type="submit" name="save" value="1" class="btn btn-default btn-block">
							<i class="fas fa-paper-plane"></i> Submit Admission Requests
						</button>
					</div>
				</div>
			</footer>
		<?php echo form_close();?>
		</section>
	</div>
</div>
