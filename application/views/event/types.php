<div class="row">
<?php if (get_permission('event_type', 'is_add')): ?>
	<div class="col-md-5">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><?=translate('add') . " " . translate('event_type')?></h4>
			</header>
			<?php echo form_open($this->uri->uri_string());?>
				<div class="panel-body">
					<?php if (is_superadmin_loggedin()): ?>
					<div class="form-group">
						<label class="control-label"><?=translate('branch')?> <span class="required">*</span></label>
						<?php
							$arrayBranch = $this->app_lib->getSelectList('branch');
							echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' id='branch_id'
							data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
						?>
						<span class="error"><?=form_error('branch_id')?></span>
					</div>
					<?php endif; ?>
					<div class="form-group">
						<label class="control-label"><?=translate('name')?> <span class="required">*</span></label>
						<input type="text" class="form-control" name="type_name" value="<?=set_value('type_name')?>" />
						<span class="error"><?=form_error('type_name')?></span>
					</div>
					<div class="form-group mb-xs">
						<label class="control-label d-block mb-1">Event Icon</label>
						<div class="d-flex flex-wrap gap-1">
							<input type="radio" class="btn-check" name="event_icon" value="bullhorn" id="icon_bullhorn" autocomplete="off" checked>
							<label class="btn btn-outline-secondary" for="icon_bullhorn" title="Bullhorn"><i class="fas fa-fw fa-bullhorn"></i></label>

							<input type="radio" class="btn-check" name="event_icon" value="flag-checkered" id="icon_flag" autocomplete="off">
							<label class="btn btn-outline-secondary" for="icon_flag" title="Flag"><i class="fas fa-flag-checkered fa-fw"></i></label>

							<input type="radio" class="btn-check" name="event_icon" value="comments" id="icon_comments" autocomplete="off">
							<label class="btn btn-outline-secondary" for="icon_comments" title="Comments"><i class="fas fa-comments fa-fw"></i></label>

							<input type="radio" class="btn-check" name="event_icon" value="concierge-bell" id="icon_concierge" autocomplete="off">
							<label class="btn btn-outline-secondary" for="icon_concierge" title="Bell"><i class="fas fa-concierge-bell fa-fw"></i></label>

							<input type="radio" class="btn-check" name="event_icon" value="envelope" id="icon_envelope" autocomplete="off">
							<label class="btn btn-outline-secondary" for="icon_envelope" title="Envelope"><i class="fas fa-envelope fa-fw"></i></label>

							<input type="radio" class="btn-check" name="event_icon" value="comment-alt" id="icon_comment_alt" autocomplete="off">
							<label class="btn btn-outline-secondary" for="icon_comment_alt" title="Message"><i class="fas fa-comment-alt fa-fw"></i></label>

							<input type="radio" class="btn-check" name="event_icon" value="users" id="icon_users" autocomplete="off">
							<label class="btn btn-outline-secondary" for="icon_users" title="Users"><i class="fas fa-users fa-fw"></i></label>

							<input type="radio" class="btn-check" name="event_icon" value="star" id="icon_star" autocomplete="off">
							<label class="btn btn-outline-secondary" for="icon_star" title="Star"><i class="far fa-star fa-fw"></i></label>

							<input type="radio" class="btn-check" name="event_icon" value="stamp" id="icon_stamp" autocomplete="off">
							<label class="btn btn-outline-secondary" for="icon_stamp" title="Stamp"><i class="fas fa-stamp fa-fw"></i></label>

							<input type="radio" class="btn-check" name="event_icon" value="search" id="icon_search" autocomplete="off">
							<label class="btn btn-outline-secondary" for="icon_search" title="Search"><i class="fas fa-search fa-fw"></i></label>
						</div>
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
			<?php echo form_close();?>
		</section>
	</div>
<?php endif; ?>
<?php if (get_permission('event_type', 'is_view')): ?>
	<div class="col-md-<?php if (get_permission('event_type', 'is_add')){ echo "7"; }else{ echo "12"; } ?>">
		<section class="panel">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fas fa-list-ul"></i> <?=translate('event_type') . " " . translate('list')?></h4>
			</header>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-sm mb-0">
						<thead>
							<tr>
								<th><?=translate('sl')?></th>
								<th><?=translate('branch')?></th>
								<th><?=translate('icon')?></th>
								<th><?=translate('term_name')?></th>
								<th><?=translate('action')?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$count = 1;
							if (!empty($typelist)){
								foreach ($typelist as $row):
							?>
							<tr>
								<td><?php echo $count++;?></td>
								<td><?php echo $row['branch_name']; ?></td>
								<td><i class="fas fa-lg fa-<?=html_escape($row['icon'])?>"></i></td>
								<td><?php echo $row['name']; ?></td>
								<td>
								<?php if (get_permission('event_type', 'is_edit')): ?>
									<!-- update link -->
									<a class="btn btn-default btn-circle icon evt_modal" href="javascript:void(0);" data-id="<?=$row['id']?>" data-name="<?=$row['name']?>"
									data-branch="<?=$row['branch_id']?>" data-icon="<?=$row['icon']?>">
										<i class="fas fa-pen-nib"></i>
									</a>
								<?php endif; if (get_permission('event_type', 'is_delete')): ?>
									<!-- delete link -->
									<?php echo btn_delete('event/type_delete/' . $row['id']);?>
								<?php endif; ?>
								</td>
							</tr>
							<?php
								endforeach;
							}else{
								echo '<tr><td colspan="5"><h5 class="text-danger text-center">' . translate('no_information_available') . '</td></tr>';
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
<?php if (get_permission('event_type', 'is_edit')): ?>
<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="modal">
	<section class="panel">
		<?php echo form_open('event/types_edit', array('class' => 'frm-submit')); ?>
			<header class="panel-heading">
				<h4 class="panel-title"><i class="far fa-edit"></i> <?=translate('edit') . " " . translate('event_type')?></h4>
			</header>
			<div class="panel-body">
				<input type="hidden" name="type_id" id="etype_id" value="" />
				<?php if (is_superadmin_loggedin()): ?>
				<div class="form-group">
					<label class="control-label"><?=translate('branch')?> <span class="required">*</span></label>
					<?php
						$arrayBranch = $this->app_lib->getSelectList('branch');
						echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control' id='ebranch_id'
						id='branch_id' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
					?>
					<span class="error"></span>
				</div>
				<?php endif; ?>
				<div class="form-group mb-md">
					<label class="control-label"><?=translate('name')?> <span class="required">*</span></label>
					<input type="text" class="form-control" name="type_name" id="ename" value="" />
					<span class="error"></span>
				</div>
					<div class="form-group mb-xs">
						<label class="control-label d-block mb-1">Event Icon</label>
						<div class="d-flex flex-wrap gap-1">
							<input type="radio" class="btn-check" name="event_icon" value="bullhorn" id="eicon_bullhorn" autocomplete="off" checked>
							<label class="btn btn-outline-secondary" for="eicon_bullhorn" title="Bullhorn"><i class="fas fa-fw fa-bullhorn"></i></label>

							<input type="radio" class="btn-check" name="event_icon" value="flag-checkered" id="eicon_flag" autocomplete="off">
							<label class="btn btn-outline-secondary" for="eicon_flag" title="Flag"><i class="fas fa-flag-checkered fa-fw"></i></label>

							<input type="radio" class="btn-check" name="event_icon" value="comments" id="eicon_comments" autocomplete="off">
							<label class="btn btn-outline-secondary" for="eicon_comments" title="Comments"><i class="fas fa-comments fa-fw"></i></label>

							<input type="radio" class="btn-check" name="event_icon" value="concierge-bell" id="eicon_concierge" autocomplete="off">
							<label class="btn btn-outline-secondary" for="eicon_concierge" title="Bell"><i class="fas fa-concierge-bell fa-fw"></i></label>

							<input type="radio" class="btn-check" name="event_icon" value="envelope" id="eicon_envelope" autocomplete="off">
							<label class="btn btn-outline-secondary" for="eicon_envelope" title="Envelope"><i class="fas fa-envelope fa-fw"></i></label>

							<input type="radio" class="btn-check" name="event_icon" value="comment-alt" id="eicon_comment_alt" autocomplete="off">
							<label class="btn btn-outline-secondary" for="eicon_comment_alt" title="Message"><i class="fas fa-comment-alt fa-fw"></i></label>

							<input type="radio" class="btn-check" name="event_icon" value="users" id="eicon_users" autocomplete="off">
							<label class="btn btn-outline-secondary" for="eicon_users" title="Users"><i class="fas fa-users fa-fw"></i></label>

							<input type="radio" class="btn-check" name="event_icon" value="star" id="eicon_star" autocomplete="off">
							<label class="btn btn-outline-secondary" for="eicon_star" title="Star"><i class="far fa-star fa-fw"></i></label>

							<input type="radio" class="btn-check" name="event_icon" value="stamp" id="eicon_stamp" autocomplete="off">
							<label class="btn btn-outline-secondary" for="eicon_stamp" title="Stamp"><i class="fas fa-stamp fa-fw"></i></label>

							<input type="radio" class="btn-check" name="event_icon" value="search" id="eicon_search" autocomplete="off">
							<label class="btn btn-outline-secondary" for="eicon_search" title="Search"><i class="fas fa-search fa-fw"></i></label>
						</div>
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
		<?php echo form_close();?>
	</section>
</div>
<script type="text/javascript">
	$(document).ready(function () {
		$('.evt_modal').on('click', function() {
			var id = $(this).data('id');
			var name = $(this).data('name');
			var icon = $(this).data('icon');
			var branch = $(this).data('branch'); 
			$('#etype_id').val(id);
			$('#ename').val(name);
			$("#modal input[name=event_icon][value=" + icon + "]").prop('checked', true);
            if ($('#ebranch_id').length) {
                $('#ebranch_id').val(branch).trigger('change');
            }
			mfp_modal('#modal');
		});
	});
</script>
<?php endif; ?>