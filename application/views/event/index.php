<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="nav-item">
                <a href="#list" class="nav-link active" data-bs-toggle="tab">
                    <i class="fas fa-list-ul"></i> <?=translate('event_list')?>
                </a>
			</li>
<?php if (get_permission('event', 'is_add')): ?>
			<li class="nav-item">
                <a href="#add" class="nav-link" data-bs-toggle="tab">
                   <i class="far fa-edit"></i> <?=translate('create_event')?>
                </a>
			</li>
<?php endif; ?>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active show" id="list">
				<table class="table table-bordered table-hover mb-0 tbr-top table-export">
					<thead>
						<tr>
							<th>#</th>
							<th><?=translate('branch')?></th>
							<th><?=translate('title')?></th>
							<th><?=translate('type')?></th>
							<th><?=translate('date_of_start')?></th>
							<th><?=translate('date_of_end')?></th>
							<th><?=translate('audience')?></th>
							<th><?=translate('created_by')?></th>
							<th><?=translate('publish')?></th>
							<th><?=translate('action')?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$count = 1;
						if (!is_superadmin_loggedin()) {
							$this->db->where('branch_id', get_loggedin_branch_id());
						}
						$this->db->order_by('id', 'desc');
						$events = $this->db->get('event')->result();
						foreach ($events as $event):
						?>
						<tr>
							<td><?php echo $count++; ?></td>
							<td><?php echo get_type_name_by_id('branch', $event->branch_id);?></td>
							<td><?php echo $event->title; ?></td>
							<td><?php
									if($event->type != 'holiday'){
										echo get_type_name_by_id('event_types', $event->type);
									}else{
										echo translate('holiday'); 
									}
								?></td>
							<td><?php echo _d($event->start_date);?></td>
							<td><?php echo _d($event->end_date);?></td>
							<td><?php
								$auditions = array(
									"1" => "everybody",
									"2" => "class",
									"3" => "section",
								);
								$audition = isset($auditions[$event->audition]) ? $auditions[$event->audition] : 'everybody';
								echo translate($audition);
								if($event->audition != 1){
									$selecteds = json_decode($event->selected_list);
									if (!empty($selecteds)) {
										foreach ($selecteds as $selected) {
											$row = $this->db->get_where($audition, array('id' => $selected))->row();
											if ($row) echo "<br><small> - " . html_escape($row->name) . '</small>';
										}
									}
								}
							?></td>
							<td><?php echo get_type_name_by_id('staff', $event->created_by); ?></td>
							<td>
							<?php if (get_permission('event', 'is_edit')) { ?>
								<div class="form-check form-switch ms-2">
									<input class="form-check-input event-switch" type="checkbox" role="switch"
									id="switch_<?=$event->id?>" data-id="<?=$event->id?>"
									<?php echo ($event->status == 1 ? 'checked' : ''); ?>>
								</div>
							<?php } ?>
							</td>
							<td>
								<!-- view modal link -->
								<a href="javascript:void(0);" class="btn btn-circle btn-default icon" onclick="viewEvent('<?=$event->id?>');">
									<i class="far fa-eye"></i>
								</a>
							<?php if (get_permission('event', 'is_delete')) { ?>
								<!-- deletion link -->
								<?php echo btn_delete('event/delete/'.$event->id);?>
							<?php } ?>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
<?php if (get_permission('event', 'is_add')): ?>
			<div class="tab-pane" id="add">
					<?php echo form_open($this->uri->uri_string(), array('class' => 'form-bordered form-horizontal frm-submit'));?>
					<?php if (is_superadmin_loggedin()): ?>
						<div class="form-group">
							<label class="control-label col-md-3"><?=translate('branch')?> <span class="required">*</span></label>
							<div class="col-md-6">
								<?php
									$arrayBranch = $this->app_lib->getSelectList('branch');
									echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' data-width='100%' id='branch_id'
									data-plugin-selectTwo  data-minimum-results-for-search='Infinity'");
								?>
								<span class="error"></span>
							</div>
						</div>
					<?php endif; ?>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('title')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="title" value="" />
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-9 offset-md-3">
							<div class="ms-2 checkbox-replace">
								<label class="i-checks"><input type="checkbox" name="holiday" id="chk_holiday"><i></i> Holiday</label>
							</div>
						</div>
					</div>
					<div class="form-group" id="typeDiv">
						<label class="col-md-3 control-label"><?=translate('type')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								$array = $this->app_lib->getSelectByBranch('event_types', $branch_id);
								echo form_dropdown("type_id", $array, set_value('type_id'), "class='form-control' id='type_id'
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('audience')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								$arrayAudition = array(
									"" => translate('select'),
									"1" => translate('everybody'),
									"2" => translate('selected_class'),
									"3" => translate('selected_section'),
								);
								echo form_dropdown("audition", $arrayAudition, set_value('audition'), "class='form-control' id='audition'
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity' ");
							?>
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group" id="selected_user" style="display: none;">
						<label class="col-md-3 control-label" id="selected_label"> <?=translate('audience')?> <span class="required">*</span> </label>
						<div class="col-md-6">
							<?php
								$placeholder = '{"placeholder": "' . translate('select') . '"}';
								echo form_dropdown("selected_audience[]", $array, set_value('selected_audience'), "class='form-control' data-plugin-selectTwo multiple
								data-plugin-options='$placeholder' data-plugin-selectTwo data-width='100%' id='selected_audience' ");
							?>
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('date')?> <span class="required">*</span></label>
						<div class="col-md-6">
							<div class="input-group">
								<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
								<input type="text" class="form-control" name="daterange" id="daterange" 
								value="<?=set_value('daterange', date("Y/m/d") . ' - ' . date("Y/m/d", strtotime("+2 day")))?>" />
							</div>
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=translate('description')?></label>
						<div class="col-md-6">
							<textarea name="remarks" class="summernote"></textarea>
						</div>
					</div>
				
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-2 offset-md-3">
								<button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
									<i class="fas fa-plus-circle"></i> <?=translate('save')?>
								</button>
							</div>
						</div>
					</footer>
				<?php echo form_close(); ?>
			</div>
<?php endif; ?>
		</div>
	</div>
</section>
<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="modal">
	<section class="panel">
		<header class="panel-heading">
			<div class="panel-btn">
				<button onclick="fn_printElem('printResult')" class="btn btn-default btn-circle icon" ><i class="fas fa-print"></i></button>
			</div>
			<h4 class="panel-title"><i class="fas fa-info-circle"></i> <?=translate('event_details')?></h4>
		</header>
		<div class="panel-body">
			<div id="printResult" class="pt-sm pb-sm">
				<div class="table-responsive">						
					<table class="table table-bordered table-sm tbr-top" id="ev_table"></table>
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-end">
					<button class="btn btn-default modal-dismiss">
						<?=translate('close')?>
					</button>
				</div>
			</div>
		</footer>
	</section>
</div>

<script type="text/javascript">
	$(document).ready(function () {
		// Tab switching — delegate from .tabs-custom to intercept before doc-level shim/BS5
		$('.tabs-custom').on('click', '.nav-link[data-bs-toggle="tab"]', function(e) {
			e.preventDefault();
			e.stopImmediatePropagation();
			var tgt = $(this).attr('href');
			if (!tgt || !$(tgt).length) return;
			$('.tabs-custom .nav-link').removeClass('active');
			$(this).addClass('active');
			$('.tabs-custom .tab-pane').removeClass('active show');
			$(tgt).addClass('active show');
			// Reinitialise Select2 inside the newly shown pane
			$(tgt).find('[data-plugin-selectTwo]').each(function() {
				if (!$(this).data('select2')) {
					$(this).select2({ width: '100%' });
				}
			});
		});

		$(document).on('change', '.event-switch', function() {
			var id    = $(this).data('id');
			var state = $(this).prop('checked');
			$.ajax({
				type: 'POST',
				url: base_url + 'event/status',
				data: { id: id, status: state },
				dataType: 'json',
				success: function(data) {
					if (data.status) alertMsg(data.msg);
				}
			});
		});

		$('#daterange').daterangepicker({
			opens: 'left',
		    locale: {format: 'YYYY/MM/DD'}
		});

		$('#branch_id').on('change', function() {
			var branchID = $(this).val();
			$.ajax({
				url: "<?=base_url('ajax/getDataByBranch')?>",
				type: 'POST',
				data: {
					branch_id: branchID,
					table : 'event_types'
				},
				success: function (data) {
					$('#type_id').html(data);
				}
			});
			$("#selected_audience").empty();
		});
		
		// Holiday toggle — hide/show Type field
		$('#chk_holiday').on('change', function() {
			if ($(this).is(':checked')) {
				$('#typeDiv').hide('slow');
			} else {
				$('#typeDiv').show('slow');
			}
		});

		// Summernote richtext editor
		if (typeof $.fn.summernote !== 'undefined' && $('.summernote').length) {
			$('.summernote').summernote({
				height: 220,
				toolbar: [
					['style',  ['style']],
					['font',   ['bold','italic','underline','clear']],
					['color',  ['color']],
					['para',   ['ul','ol','paragraph']],
					['insert', ['link','table']],
					['misc',   ['fullscreen','undo','codeview']]
				]
			});
		}

		$('#audition').on('change', function() {
			var audition = $(this).val();
			var branchID = ($('#branch_id').length ? $('#branch_id').val() : "");
			if(!audition || audition == "1") {
				$("#selected_user").hide("slow");
			}
			if(audition == "2") {
			    $.ajax({
			        url: base_url + 'ajax/getClassByBranch',
			        type: 'POST',
			        data:{ branch_id: branchID },
			        success: function (data){
			            $('#selected_audience').html(data);
			        }
			    });
				$("#selected_user").show('slow');
				$("#selected_label").html("<?=translate('class')?> <span class='required'>*</span>");
			}
			if(audition == "3"){
				$.ajax({
					url: "<?=base_url('event/getSectionByBranch')?>",
					type: 'POST',
					data: {branch_id: branchID},
					success: function (data) {
						$('#selected_audience').html(data);
					}
				});
				$("#selected_user").show('slow');
				$("#selected_label").html("<?=translate('section')?> <span class='required'>*</span>");
			}
		});
	});
</script>