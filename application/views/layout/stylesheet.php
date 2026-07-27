<?php
/*
 * stylesheet.php — loaded inside <head> by header.php
 * Provides: Bootstrap 5 · Font Awesome 5 · all local (no CDN)
 *           jQuery · DataTables CSS · Select2 CSS
 *           Bootstrap Datepicker CSS · SweetAlert CSS · dck-theme.css
 */
?>
<!-- Bootstrap 5.3 CSS (local) -->
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/bootstrap5/css/bootstrap.min.css');?>">

<!-- Font Awesome 5.12 Free (local) -->
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/font-awesome/css/all.min.css');?>">

<!-- Simple Line Icons (keep for existing sidebar icons) -->
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/simple-line-icons/css/simple-line-icons.css');?>?v=2026">

<!-- DataTables CSS (local — styled via dck-theme.css overrides) -->
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/datatables/media/css/dataTables.bootstrap.min.css');?>">

<!-- Select2 CSS (local) -->
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/select2/css/select2.css');?>">

<!-- Bootstrap Datepicker CSS (local) -->
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css');?>">

<!-- SweetAlert CSS (local) -->
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/sweetalert/sweetalert-custom.css');?>">

<!-- Magnific Popup (inline modals used system-wide) -->
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/magnific-popup/magnific-popup.css');?>">

<!-- DCK Premium Theme (must come last to override everything) -->
<link rel="stylesheet" href="<?php echo base_url('assets/css/dck-theme.css');?>?v=20260726a">
<style>@keyframes sg-pulse{0%,100%{opacity:1}50%{opacity:.35}}</style>

<!-- jQuery (loaded in <head> — required for CSRF setup below) -->
<script src="<?php echo base_url('assets/vendor/jquery/jquery.min.js');?>"></script>
<script src="<?php echo base_url('assets/vendor/jquery-ui/jquery-ui.min.js');?>"></script>

<!-- Backward-compat stubs so existing custom.js / app.fn.js don't throw -->
<script>
/* Modernizr stub — custom.js references Modernizr.overflowscrolling */
window.Modernizr = window.Modernizr || { overflowscrolling: false, touch: false, csstransitions: true };
window.theme = window.theme || {
    PluginDatePicker:   { defaults: {} },
    PluginScrollToTop:  { initialize: function () {} },
    PluginNanoScroller: { initialize: function () {} }
};
// Expose CSRF token names for dck-app.js confirm_modal()
var csrfTokenName = '<?php echo $this->security->get_csrf_token_name(); ?>';
var csrfTokenHash = '<?php echo $this->security->get_csrf_hash(); ?>';
</script>
