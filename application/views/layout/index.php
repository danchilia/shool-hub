<!doctype html>
<html lang="en" class="<?php echo ($theme_config['dark_skin'] == 'true' ? 'dark' : '');?>">
<?php $this->load->view('layout/header.php');?>

<body class="dck-body">

<!-- Page loader -->
<div class="dck-loader" id="dckLoader">
    <div class="dck-loader-spinner"></div>
    <div class="dck-loader-text">Loading…</div>
</div>

<!-- Offline / sync status banner -->
<div id="dck-offline-banner"></div>

<!-- Mobile sidebar overlay -->
<div class="dck-sidebar-overlay" id="dckSidebarOverlay"></div>

<div class="dck-wrapper">

    <!-- ── SIDEBAR ──────────────────────────────────────────────────── -->
    <?php
    if (is_student_loggedin() || is_parent_loggedin()) {
        $this->load->view('userrole/sidebar');
    } else {
        $this->load->view('layout/sidebar');
    }
    ?>

    <!-- ── MAIN AREA ─────────────────────────────────────────────────── -->
    <div class="dck-main" id="dckMain">

        <!-- Topbar -->
        <?php $this->load->view('layout/topbar.php'); ?>

        <!-- Page content -->
        <main class="dck-content">
            <div class="dck-page-header">
                <div class="dck-page-title">
                    <a href="<?php echo base_url('dashboard'); ?>" class="dck-page-icon text-decoration-none">
                        <i class="fas fa-home"></i>
                    </a>
                    <h1><?php echo html_escape($title); ?></h1>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="<?php echo base_url('dashboard'); ?>"><i class="fas fa-home"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo html_escape($title); ?></li>
                    </ol>
                </nav>
            </div>

            <?php $this->load->view($sub_page); ?>
        </main>
    </div><!-- /.dck-main -->

</div><!-- /.dck-wrapper -->

<!-- JS includes -->
<?php $this->load->view('layout/script.php'); ?>

<!-- ── FLASH MESSAGES (SweetAlert) ──────────────────────────────────────── -->
<?php
$alertClass   = '';
$alertMessage = '';
if ($this->session->flashdata('alert-message-success')) {
    $alertClass   = 'success';
    $alertMessage = $this->session->flashdata('alert-message-success');
} elseif ($this->session->flashdata('alert-message-error')) {
    $alertClass   = 'error';
    $alertMessage = $this->session->flashdata('alert-message-error');
} elseif ($this->session->flashdata('alert-message-info')) {
    $alertClass   = 'info';
    $alertMessage = $this->session->flashdata('alert-message-info');
}
if ($alertClass !== '' && $alertMessage !== ''):
?>
<script>
swal({
    toast: true,
    position: 'top-end',
    type: '<?php echo $alertClass; ?>',
    title: '<?php echo addslashes(html_escape($alertMessage)); ?>',
    confirmButtonClass: 'btn btn-default',
    buttonsStyling: false,
    timer: 8000
});
</script>
<?php endif; ?>

<!-- ── TOOLTIP STYLE OVERRIDES (keep existing help-tip pattern) ──────────── -->
<style>
.help-tip { color: var(--dck-primary); cursor: help; font-size: .8rem; margin-left: 3px; }
.help-tip:hover { color: var(--dck-primary-dark); }
</style>

<!-- ── CONFIRM MODAL (translations exposed for dck-app.js) ──────────────── -->
<script>
var translate_are_you_sure  = '<?php echo addslashes(translate('are_you_sure')); ?>';
var translate_delete_info   = '<?php echo addslashes(translate('delete_this_information')); ?>';
var translate_yes_continue  = '<?php echo addslashes(translate('yes_continue')); ?>';
var translate_cancel        = '<?php echo addslashes(translate('cancel')); ?>';
</script>

<!-- ── LOADER DISMISS ────────────────────────────────────────────────────── -->
<script>
window.addEventListener('load', function () {
    var loader = document.getElementById('dckLoader');
    if (loader) {
        loader.style.opacity = '0';
        loader.style.transition = 'opacity .3s ease';
        setTimeout(function () { loader.style.display = 'none'; }, 320);
    }
});
</script>

</body>
</html>
