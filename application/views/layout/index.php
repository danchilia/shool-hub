<!doctype html>
<html class="fixed sidebar-left-sm <?php echo ($theme_config['dark_skin'] == 'true' ? 'dark' : 'sidebar-light');?>">
<!-- html header -->
<?php $this->load->view('layout/header.php');?>

<body class="loading-overlay-showing" data-loading-overlay>
	<!-- page preloader -->
	<div class="loading-overlay dark">
		<div class="ring-loader">
			Loading <span></span>
		</div>
	</div>

	<!-- Offline / sync status banner (Attendance & Fee Collection keep working without internet) -->
	<style>
	#dck-offline-banner {
		display: none; position: fixed; top: 0; left: 0; right: 0; z-index: 99999;
		text-align: center; padding: 8px 12px; font-weight: 600; font-size: 13px;
	}
	#dck-offline-banner.dck-banner-danger { background: #e74c3c; color: #fff; }
	#dck-offline-banner.dck-banner-info { background: #2980b9; color: #fff; }
	#dck-offline-banner.dck-banner-success { background: #27ae60; color: #fff; }
	</style>
	<div id="dck-offline-banner"></div>
	<section class="body">
		<!-- top navbar-->
		<?php $this->load->view('layout/topbar.php');?>
		<div class="inner-wrapper">
			<!-- sidebar -->
			<?php 
			if (is_student_loggedin() || is_parent_loggedin()) {
				$this->load->view('userrole/sidebar'); 
			} else {
				$this->load->view('layout/sidebar'); 
			} 
			?>
			<!-- page main content -->
			<section role="main" class="content-body">
				<header class="page-header">
					<a class="page-title-icon" href="<?php echo base_url('dashboard');?>"><i class="fas fa-home"></i></a>
					<h2><?php echo $title;?></h2>
				</header>
				<?php $this->load->view($sub_page); ?>
			</section>
		</div>
	</section>

	<!-- JS Script -->
	<?php $this->load->view('layout/script.php');?>
	
	<?php
	$alertclass = "";
	if($this->session->flashdata('alert-message-success')){
		$alertclass = "success";
	} else if ($this->session->flashdata('alert-message-error')){
		$alertclass = "error";
	} else if ($this->session->flashdata('alert-message-info')){
		$alertclass = "info";
	}
	if($alertclass != ''):
		$alert_message = $this->session->flashdata('alert-message-'. $alertclass);
	?>
		<script type="text/javascript">
			swal({
				toast: true,
				position: 'top-end',
				type: '<?php echo $alertclass?>',
				title: '<?php echo $alert_message?>',
				confirmButtonClass: 'btn btn-default',
				buttonsStyling: false,
				timer: 8000
			})
		</script>
	<?php endif; ?>

	<!-- Help tooltip styles and init -->
	<style>
	.help-tip { color: #3498db; cursor: help; font-size: 13px; margin-left: 3px; }
	.help-tip:hover { color: #2175b5; }
	.tooltip-inner { max-width: 300px; text-align: left; font-size: 12px; padding: 8px 12px; background: #1a2b4a; }
	.tooltip.top .tooltip-arrow { border-top-color: #1a2b4a; }
	.tooltip.bottom .tooltip-arrow { border-bottom-color: #1a2b4a; }
	</style>
	<script>$(function(){ $('[data-toggle="tooltip"]').tooltip(); });</script>

	<!-- sweetalert box -->
	<script type="text/javascript">
		function confirm_modal(delete_url) {
			swal({
				title: "<?php echo translate('are_you_sure')?>",
				text: "<?php echo translate('delete_this_information')?>",
				type: "warning",
				showCancelButton: true,
				confirmButtonClass: "btn btn-default swal2-btn-default",
				cancelButtonClass: "btn btn-default swal2-btn-default",
				confirmButtonText: "<?php echo translate('yes_continue')?>",
				cancelButtonText: "<?php echo translate('cancel')?>",
				buttonsStyling: false,
				footer: "<?php echo translate('deleted_note')?>"
			}).then((result) => {
				if (result.value) {
					$.ajax({
						url: delete_url,
						type: "POST",
						data: {<?=$this->security->get_csrf_token_name()?>: "<?=$this->security->get_csrf_hash()?>"},
						success:function(data) {
							swal({
							title: "<?php echo translate('deleted')?>",
							text: "<?php echo translate('information_deleted')?>",
							buttonsStyling: false,
							showCloseButton: true,
							focusConfirm: false,
							confirmButtonClass: "btn btn-default swal2-btn-default",
							type: "success"
							}).then((result) => {
								if (result.value) {
									location.reload();
								}
							});
						}
					});
				}
			});
		}
	</script>
</body>
</html>