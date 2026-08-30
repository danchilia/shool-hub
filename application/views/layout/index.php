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

<?php if (!is_superadmin_loggedin()): ?>
<!-- PWA Install Banner -->
<div id="pwa-banner" style="display:none;position:fixed;bottom:0;left:0;right:0;z-index:9990;
     background:#1a2e4a;color:#fff;padding:12px 20px;
     display:none;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;
     box-shadow:0 -3px 15px rgba(0,0,0,.25)">
  <div style="display:flex;align-items:center;gap:12px">
    <img src="<?php echo base_url('assets/images/favicon.png'); ?>" style="width:36px;height:36px;border-radius:8px" onerror="this.style.display='none'">
    <div>
      <div style="font-weight:700;font-size:.95rem">Install CST SchoolHub</div>
      <div style="font-size:.78rem;color:#94a3b8">Add to your desktop or home screen for quick access</div>
    </div>
  </div>
  <div style="display:flex;gap:10px;align-items:center">
    <button id="pwa-install-btn" onclick="installPWA()"
            style="background:#c9a84c;color:#fff;border:none;padding:9px 20px;border-radius:6px;font-weight:700;font-size:.88rem;cursor:pointer">
      <i class="fas fa-download me-1"></i> Install App
    </button>
    <button onclick="dismissPwaBanner()"
            style="background:transparent;border:1px solid #475569;color:#94a3b8;padding:8px 14px;border-radius:6px;cursor:pointer;font-size:.82rem">
      Not now
    </button>
  </div>
</div>
<script>
(function() {
  if (localStorage.getItem('pwa-banner-dismissed') === '1') return;
  var banner = document.getElementById('pwa-banner');
  window.addEventListener('beforeinstallprompt', function(e) {
    e.preventDefault();
    window.deferredPrompt = e;
    banner.style.display = 'flex';
  });
  window.addEventListener('appinstalled', function() {
    banner.style.display = 'none';
    localStorage.setItem('pwa-banner-dismissed', '1');
  });
})();
function installPWA() {
  if (window.deferredPrompt) {
    window.deferredPrompt.prompt();
    window.deferredPrompt.userChoice.then(function(r) {
      if (r.outcome === 'accepted') {
        document.getElementById('pwa-banner').style.display = 'none';
        localStorage.setItem('pwa-banner-dismissed', '1');
      }
      window.deferredPrompt = null;
    });
  }
}
function dismissPwaBanner() {
  document.getElementById('pwa-banner').style.display = 'none';
  localStorage.setItem('pwa-banner-dismissed', '1');
}
</script>
<?php endif; ?>

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
Swal.fire({
    toast: true,
    position: 'top-end',
    icon: '<?php echo $alertClass; ?>',
    title: '<?php echo addslashes(html_escape($alertMessage)); ?>',
    showConfirmButton: false,
    timer: 5000,
    timerProgressBar: true
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
(function () {
    var loader = document.getElementById('dckLoader');
    if (loader) {
        loader.style.opacity = '0';
        loader.style.transition = 'opacity .3s ease';
        setTimeout(function () { loader.style.display = 'none'; }, 320);
    }
})();
</script>

</body>
</html>
