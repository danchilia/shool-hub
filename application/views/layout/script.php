<?php /* Bottom-of-body JS includes */ ?>

<!-- Bootstrap 5.3 JS bundle (includes Popper) — CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc4s9bIOgUxi8T/jzmVGMzHBPdM0zOwNGNKpwHEGzKtV" crossorigin="anonymous"></script>

<!-- jQuery Browser plugin (kept for legacy) -->
<script src="<?php echo base_url('assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js');?>"></script>

<!-- Bootstrap Datepicker JS -->
<script src="<?php echo base_url('assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js');?>"></script>

<!-- Select2 JS -->
<script src="<?php echo base_url('assets/vendor/select2/js/select2.js');?>"></script>

<!-- DataTables core -->
<script src="<?php echo base_url('assets/vendor/datatables/media/js/jquery.dataTables.min.js');?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables/media/js/dataTables.bootstrap.min.js');?>"></script>

<!-- DataTables Buttons extension -->
<script src="<?php echo base_url('assets/vendor/datatables/extras/TableTools/Buttons-1.4.2/js/dataTables.buttons.min.js');?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables/extras/TableTools/Buttons-1.4.2/js/buttons.bootstrap.min.js');?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables/extras/TableTools/Buttons-1.4.2/js/buttons.html5.min.js');?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables/extras/TableTools/Buttons-1.4.2/js/buttons.print.min.js');?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables/extras/TableTools/Buttons-1.4.2/js/buttons.colVis.min.js');?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables/extras/TableTools/JSZip-2.5.0/jszip.min.js');?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables/extras/TableTools/pdfmake-0.1.32/pdfmake.min.js');?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables/extras/TableTools/pdfmake-0.1.32/vfs_fonts.js');?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables/extras/TableTools/RowGroup-1.0.2/js/dataTables.rowGroup.min.js');?>"></script>

<!-- jQuery Validation -->
<script src="<?php echo base_url('assets/vendor/jquery-validation/jquery.validate.js');?>"></script>

<!-- Screenfull (fullscreen API) -->
<script src="<?php echo base_url('assets/vendor/screenfull/screenfull.min.js');?>"></script>

<!-- SweetAlert -->
<script src="<?php echo base_url('assets/vendor/sweetalert/sweetalert.min.js');?>"></script>

<!-- ECharts (pie/bar charts on dashboard) -->
<script src="<?php echo base_url('assets/vendor/echarts/echarts.common.min.js');?>"></script>

<!-- Chart.js (line/bar charts on dashboard) -->
<script src="<?php echo base_url('assets/vendor/chartjs/chart.min.js');?>"></script>

<!-- Magnific Popup (inline modals used system-wide: event details, AJAX forms, etc.) -->
<script src="<?php echo base_url('assets/vendor/magnific-popup/jquery.magnific-popup.js');?>"></script>

<!-- app.fn.js (AJAX form helpers — uses $.fn.button shim from dck-app.js) -->
<script src="<?php echo base_url('assets/js/app.fn.js');?>"></script>

<!-- plug.init.js (datepicker init, scroll-to-top) -->
<script src="<?php echo base_url('assets/js/plug.init.js');?>"></script>

<!-- DCK Application JS (sidebar · BS3→5 shim · DataTables · confirm_modal) -->
<script src="<?php echo base_url('assets/js/dck-app.js');?>?v=20260719c"></script>

<!-- custom.js (per-module helpers) -->
<script src="<?php echo base_url('assets/js/custom.js');?>?v=20260719c"></script>

<!-- Offline queue (attendance + fees offline support) -->
<script src="<?php echo base_url('assets/js/offline-queue.js');?>"></script>

<!-- jQuery Validation messages i18n -->
<script>
if (typeof jQuery !== 'undefined' && typeof jQuery.validator !== 'undefined') {
    jQuery.extend(jQuery.validator.messages, {
        required:     "<?php echo translate('this_value_is_required'); ?>",
        email:        "<?php echo translate('enter_valid_email'); ?>",
        url:          "Please enter a valid URL.",
        date:         "Please enter a valid date.",
        number:       "Please enter a valid number.",
        digits:       "Please enter only digits.",
        equalTo:      "Please enter the same value again.",
        maxlength:    jQuery.validator.format("Please enter no more than {0} characters."),
        minlength:    jQuery.validator.format("Please enter at least {0} characters."),
        rangelength:  jQuery.validator.format("Please enter a value between {0} and {1} characters long."),
        range:        jQuery.validator.format("Please enter a value between {0} and {1}."),
        max:          jQuery.validator.format("Please enter a value less than or equal to {0}."),
        min:          jQuery.validator.format("Please enter a value greater than or equal to {0}.")
    });
}
</script>

<!-- Service Worker (offline PWA) -->
<script>
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('<?php echo base_url('service-worker.js'); ?>', { scope: '<?php echo base_url(); ?>' })
        .catch(function (err) { console.warn('SW registration failed:', err); });
}
// PWA install prompt
var deferredPrompt;
window.addEventListener('beforeinstallprompt', function(e) {
    e.preventDefault();
    deferredPrompt = e;
    var btn = document.getElementById('pwa-install-btn');
    if (btn) { btn.style.display = 'inline-block'; }
});
function installPWA() {
    if (deferredPrompt) {
        deferredPrompt.prompt();
        deferredPrompt.userChoice.then(function(){ deferredPrompt = null; });
    }
}
</script>
