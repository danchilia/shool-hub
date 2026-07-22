<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="<?=(empty($validation_error) ? 'active' : '') ?>">
				<a href="#list" data-toggle="tab"><i class="fas fa-list-ul"></i> <?=translate('branch_list')?></a>
			</li>
			<li class="<?=(!empty($validation_error) ? 'active' : '') ?>">
				<a href="#create" data-toggle="tab"><i class="far fa-edit"></i> <?=translate('create_branch')?></a>
			</li>
		</ul>
		<div class="tab-content">
			<div id="list" class="tab-pane <?=(empty($validation_error) ? 'active' : '')?>">
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-condensed mb-none table-export">
						<thead>
							<tr>
								<th width="50"><?=translate('sl')?></th>
								<th><?=translate('branch_name')?></th>
								<th><?=translate('school_name')?></th>
								<th><?=translate('email')?></th>
								<th><?=translate('mobile_no')?></th>
								<th><?=translate('currency')?></th>
								<th><?=translate('symbol')?></th>
								<th><?=translate('city')?></th>
								<th><?=translate('state')?></th>
								<th><?=translate('address')?></th>
								<th><?=translate('action')?></th>
							</tr>
						</thead>
						<tbody>
							<?php 
								$count = 1;
								$branchs = $this->db->get('branch')->result();
								foreach($branchs as $row):
							?>
							<tr>
								<td><?php echo $count++; ?></td>
								<td><?php echo $row->name;?></td>
								<td><?php echo $row->school_name;?></td>
								<td><?php echo $row->email;?></td>
								<td><?php echo $row->mobileno;?></td>
								<td><?php echo $row->currency;?></td>
								<td><?php echo $row->symbol;?></td>
								<td><?php echo $row->city;?></td>
								<td><?php echo $row->state;?></td>
								<td><?php echo $row->address;?></td>
								<td class="min-w-c">
									<a href="<?=base_url('branch_health/check/'.$row->id)?>" class="btn btn-success btn-circle icon" title="Health Check">
										<i class="fas fa-heartbeat"></i>
									</a>
									<a href="<?=base_url('branch/edit/'.$row->id)?>" class="btn btn-default btn-circle icon" title="Edit">
										<i class="fas fa-pen-nib"></i>
									</a>
									<a href="<?=base_url('branch/delete_data/' . $row->id)?>" class="btn btn-danger btn-xs" title="Delete" onclick="return confirm('WARNING: This will permanently delete this entire school and ALL its data (students, staff, marks, fees, everything). Are you sure?')">
										<i class="fas fa-trash-alt"></i> Delete
									</a>
								</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="tab-pane <?=(!empty($validation_error) ? 'active' : '')?>" id="create">
				<?php echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal form-bordered validate')); ?>
					<div class="form-group mt-md">
						<label class="col-md-3 control-label"><?=translate('branch_name')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="branch_name" value="<?=set_value('branch_name')?>" />
							<span class="error"><?=form_error('branch_name') ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('school_name')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="school_name" value="<?=set_value('school_name')?>" />
							<span class="error"><?=form_error('school_name') ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('email')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="email" value="<?=set_value('email')?>" />
							<span class="error"><?=form_error('email') ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('mobile_no')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="mobileno" value="<?=set_value('mobileno')?>">
							<span class="error"><?=form_error('mobileno') ?></span>
						</div>
					</div>
					<div class="form-group">
						<label  class="col-md-3 control-label"><?=translate('currency')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="currency" value="<?=set_value('currency')?>" />
							<span class="error"><?=form_error('currency') ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('currency_symbol')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="currency_symbol" value="<?=set_value('currency_symbol')?>" />
							<span class="error"><?=form_error('currency_symbol'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('city')?></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="city" value="<?=set_value('city')?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('state')?></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="state" value="<?=set_value('state')?>">
						</div>
					</div>
					<div class="form-group">
						<label  class="col-md-3 control-label"><?=translate('address')?></label>
						<div class="col-md-6 mb-md">
							<textarea type="text" rows="3" class="form-control" name="address" ><?=set_value('address')?></textarea>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-3 col-md-6">
							<div class="alert alert-info" style="padding:10px;">
								<label style="margin-bottom:5px; cursor:pointer; display:block;">
									<input type="checkbox" name="kenya_template" value="1" id="kenya_tpl">
									<strong> Kenya School Template (PP1&ndash;Form 4)</strong>
								</label>
								<p style="margin:0 0 2px; font-size:11px; color:#555;"><strong>Academic:</strong> PP1&ndash;Form 4 classes (3 streams), CBC learning areas &amp; strands, 18 subjects, 3 exam terms, CAT &amp; End-Term distributions, 3 exam halls, KCSE grading, SMS &amp; email templates, Kenyan public holidays.</p>
								<p style="margin:0 0 2px; font-size:11px; color:#555;"><strong>Finance:</strong> 10 fee types, Day Scholar &amp; Boarding fee groups with amounts, fee fine, 2 reminders, 8 salary templates (PAYE/NHIF/NSSF), 4 bank accounts, voucher heads.</p>
								<p style="margin:0 0 2px; font-size:11px; color:#555;"><strong>Operations:</strong> 12 canteen items, 5 asset categories + 9 assets, 10 inventory items, 3 bus routes + 9 stopovers + 3 vehicles, 2 GPS buses, Boys &amp; Girls hostels with 6 rooms each, 3 notices.</p>
								<p style="margin:0 0 10px; font-size:11px; color:#555;"><strong>Admin:</strong> KCPE/KCSE/CBC Grade 9 exam centres, 2 bursary programmes, TSC appraisal template, 1 PTM session, 1 CBT quiz, 1 virtual class, 1-year trial subscription, 36 library books.</p>
								<label style="margin-bottom:5px; cursor:pointer; display:block;">
									<input type="checkbox" name="university_template" value="1" id="uni_tpl">
									<strong> University / College Template</strong>
								</label>
								<p style="margin:0 0 2px; font-size:11px; color:#555;"><strong>Academic:</strong> 25 programmes across 5 intakes/groups, 54 units/modules, 3 semesters, 5 assessment distributions, 8 exam &amp; lecture halls, GPA grading scale.</p>
								<p style="margin:0 0 2px; font-size:11px; color:#555;"><strong>Finance:</strong> 15 fee types, Undergraduate/Postgraduate/Residential fee groups with semester amounts, 18 salary templates, 5 bank accounts, voucher heads.</p>
								<p style="margin:0 0 2px; font-size:11px; color:#555;"><strong>Operations:</strong> 15 cafeteria items, 6 asset categories + 10 assets, 10 inventory items, campus shuttle routes + 2 buses, Male/Female/Postgrad hostels with rooms, 4 notices.</p>
								<p style="margin:0; font-size:11px; color:#555;"><strong>Admin:</strong> 3 bursary/HELB programmes, 3 appraisal templates, 1 consultation day, 1 CBT quiz, 1 virtual class, 1-year Premium trial subscription, 10 book categories.</p>
							</div>
						</div>
					</div>
					<footer class="panel-footer mt-lg">
						<div class="row">
							<div class="col-md-2 col-md-offset-3">
								<button type="submit" class="btn btn-default btn-block" name="submit" value="save">
									<i class="fas fa-plus-circle"></i> <?=translate('save')?>
								</button>
							</div>
						</div>
					</footer>
					<script>
					$('#kenya_tpl').change(function(){ if(this.checked) $('#uni_tpl').prop('checked', false); });
					$('#uni_tpl').change(function(){ if(this.checked) $('#kenya_tpl').prop('checked', false); });
					</script>
				<?php echo form_close();?>
			</div>
		</div>
	</div>
</section>