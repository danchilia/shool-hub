<?php
/*
 * stylesheet.php — loaded inside <head> by header.php
 * Provides: Inter font · Bootstrap 5 · Font Awesome 6
 *           jQuery (early, for CSRF setup) · DataTables CSS · Select2 CSS
 *           Bootstrap Datepicker CSS · SweetAlert CSS · dck-theme.css
 */
?>
<!-- Inter font (Google Fonts CDN) -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap">

<!-- Bootstrap 5.3 CSS (CDN) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

<!-- Font Awesome 6.5 Free (CDN) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W==" crossorigin="anonymous" referrerpolicy="no-referrer">

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
<link rel="stylesheet" href="<?php echo base_url('assets/css/dck-theme.css');?>?v=20260719e">

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
