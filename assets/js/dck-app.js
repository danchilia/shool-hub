/* ═══════════════════════════════════════════════════════════════════════════
   DCK School Management System — Application JS v2.0
   Handles: BS3→5 shim · sidebar · DataTables · Select2 · tooltips · misc
   ═══════════════════════════════════════════════════════════════════════════ */

(function ($) {
  'use strict';

  /* ── 1. Bootstrap 3 → 5 DATA ATTRIBUTE SHIM ──────────────────────────── */
  // Runs synchronously before BS5 scans the DOM so existing views just work.
  var bs3Attrs = ['toggle', 'target', 'dismiss', 'ride', 'slide',
                  'parent', 'content', 'placement', 'offset', 'spy', 'interval'];

  function applyBS3Shim(root) {
    root = root || document;
    bs3Attrs.forEach(function (attr) {
      root.querySelectorAll('[data-' + attr + ']:not([data-bs-' + attr + '])').forEach(function (el) {
        el.setAttribute('data-bs-' + attr, el.getAttribute('data-' + attr));
      });
    });
  }
  // Apply immediately for elements already in the DOM
  if (document.readyState !== 'loading') {
    applyBS3Shim();
  } else {
    document.addEventListener('DOMContentLoaded', applyBS3Shim, { once: true });
  }

  /* ── 2. Bootstrap 3 $.fn.button() SHIM ───────────────────────────────── */
  // BS3 had $.fn.button('loading') / $.fn.button('reset').
  // app.fn.js uses this — we patch it for BS5 compatibility.
  if (!$.fn.button || typeof $.fn.button !== 'function') {
    $.fn.button = function (action) {
      return this.each(function () {
        var $btn = $(this);
        if (action === 'loading') {
          $btn.data('dck-orig-html', $btn.html())
              .prop('disabled', true)
              .html('<i class="fas fa-spinner fa-spin me-1"></i>' + ($btn.data('loading-text') || 'Loading…'));
        } else if (action === 'reset') {
          $btn.prop('disabled', false)
              .html($btn.data('dck-orig-html') || $btn.html());
        }
      });
    };
  }

  /* ── 3. Bootstrap 5 TOOLTIP / POPOVER INIT ───────────────────────────── */
  document.addEventListener('DOMContentLoaded', function () {
    if (typeof bootstrap !== 'undefined') {
      // Tooltips
      document.querySelectorAll('[data-toggle="tooltip"],[data-bs-toggle="tooltip"],[rel="tooltip"]')
        .forEach(function (el) { new bootstrap.Tooltip(el, { trigger: 'hover', container: 'body' }); });
      // Popovers
      document.querySelectorAll('[data-toggle="popover"],[data-bs-toggle="popover"]')
        .forEach(function (el) { new bootstrap.Popover(el); });
    }
  });

  /* ── 3b. BS3 TAB COMPAT ──────────────────────────────────────────────── */
  // BS3 markup puts class="active" on <li>, not <a>. BS5's Tab JS can't find
  // the currently active tab and silently fails. Handle it with jQuery instead.
  $(document).on('click', '[data-toggle="tab"]', function (e) {
    e.preventDefault();
    e.stopImmediatePropagation(); // stop BS5 handler also firing

    var $a  = $(this);
    var tgt = $a.attr('href') || $a.data('target') || $a.data('bs-target');
    if (!tgt || !$(tgt).length) return;

    var $nav = $a.closest('ul.nav-tabs, ul.nav-pills');
    // Find tab-content: first look inside the same .tabs-custom/.panel/.card
    var $tc = $nav.closest('.tabs-custom, .panel, .card').find('.tab-content').first();
    if (!$tc.length) $tc = $nav.next('.tab-content');

    // Deactivate all
    $nav.children('li').removeClass('active');
    $nav.find('a').removeClass('active');
    $tc.children('.tab-pane').removeClass('active show');

    // Activate selected
    $a.parent('li').addClass('active');
    $a.addClass('active');
    $(tgt).addClass('active show');
  });

  /* ── 4. SIDEBAR ACCORDION ─────────────────────────────────────────────── */
  $(document).on('click', '.nav-main .nav-parent > a', function (e) {
    var $li = $(this).closest('.nav-parent');
    var $children = $li.children('.nav-children');

    // If no children, treat as plain link — do nothing
    if (!$children.length) return;

    e.preventDefault();
    var wasExpanded = $li.hasClass('nav-expanded');

    // Collapse expanded siblings — remove class AFTER animation so CSS doesn't cut it short
    $li.siblings('.nav-parent.nav-expanded').each(function () {
      var $sib = $(this);
      $sib.children('.nav-children').slideUp(200, function () {
        $sib.removeClass('nav-expanded'); // class first → CSS display:none in effect
        $(this).css('display', '');       // then clear jQuery inline style
      });
    });

    if (wasExpanded) {
      // Animate first, change class in callback to avoid instant CSS collapse
      $children.slideUp(200, function () {
        $li.removeClass('nav-expanded'); // class first → CSS display:none in effect
        $(this).css('display', '');      // then clear jQuery inline style
      });
    } else {
      // Animate first, add class in callback so CSS display:block doesn't skip animation
      $children.slideDown(200, function () {
        $li.addClass('nav-expanded');    // class added → CSS display:block takes over
        $(this).css('display', '');     // clear jQuery inline; CSS .nav-expanded rule holds it open
      });
    }
  });

  /* ── 5. SIDEBAR TOGGLE ────────────────────────────────────────────────── */
  $(document).on('click', '#dckSidebarToggle', function () {
    var $sidebar = $('#dckSidebar');
    if (window.innerWidth < 992) {
      $sidebar.toggleClass('sidebar-open');
      $('.dck-sidebar-overlay').toggleClass('active');
    } else {
      $('html').toggleClass('sidebar-left-collapsed');
      try { localStorage.setItem('dck-sb-collapsed', $('html').hasClass('sidebar-left-collapsed')); } catch (err) {}
    }
  });

  // Close via overlay click
  $(document).on('click', '.dck-sidebar-overlay', function () {
    $('#dckSidebar').removeClass('sidebar-open');
    $('.dck-sidebar-overlay').removeClass('active');
  });

  // Close via ✕ button
  $(document).on('click', '#dckSidebarClose', function () {
    $('#dckSidebar').removeClass('sidebar-open');
    $('.dck-sidebar-overlay').removeClass('active');
  });

  /* ── 6. SIDEBAR STATE RESTORE ─────────────────────────────────────────── */
  $(function () {
    // Restore collapsed state on desktop
    try {
      if (localStorage.getItem('dck-sb-collapsed') === 'true' && window.innerWidth >= 992) {
        $('html').addClass('sidebar-left-collapsed');
      }
    } catch (err) {}

    // Show children of PHP-expanded parents without animation
    $('.nav-main .nav-parent.nav-expanded').children('.nav-children').show();

    // Scroll active item into view
    setTimeout(function () {
      var $active = $('.nav-main .nav-active').first();
      if ($active.length) {
        var sidebar = document.getElementById('dckSidebar');
        if (sidebar) sidebar.scrollTop = Math.max(0, $active[0].offsetTop - 120);
      }
    }, 150);
  });

  /* ── 7. TOPBAR DROPDOWN TOGGLES ───────────────────────────────────────── */
  $(document).on('click', '[data-dck-dropdown]', function (e) {
    e.stopPropagation();
    var target = $(this).data('dck-dropdown');
    var $dd = $(target);
    var visible = $dd.is(':visible');
    // Close all
    $('.dck-topbar-dropdown').hide();
    if (!visible) $dd.show();
  });

  // Close dropdowns when clicking outside
  $(document).on('click', function (e) {
    if (!$(e.target).closest('[data-dck-dropdown], .dck-topbar-dropdown').length) {
      $('.dck-topbar-dropdown').hide();
    }
  });

  /* ── 8. DATATABLES DEFAULTS ───────────────────────────────────────────── */
  $(function () {
    if (typeof $.fn.DataTable === 'undefined') return;

    // Default config shared by all tables
    var dtDefaults = {
      pageLength: 25,
      autoWidth: false,
      responsive: true,
      language: {
        search:          '',
        searchPlaceholder: 'Search…',
        lengthMenu:      'Show _MENU_',
        info:            '_START_–_END_ of _TOTAL_',
        infoEmpty:       '0 records',
        paginate: {
          first:    '<i class="fas fa-angle-double-left"></i>',
          last:     '<i class="fas fa-angle-double-right"></i>',
          next:     '<i class="fas fa-angle-right"></i>',
          previous: '<i class="fas fa-angle-left"></i>'
        }
      }
    };

    // Plain tables (no export buttons)
    $('.table_default').each(function () {
      if (!$.fn.DataTable.isDataTable(this)) {
        $(this).DataTable($.extend({}, dtDefaults, {
          dom: '<"row"<"col-sm-6"l><"col-sm-6"f>><"table-responsive"t><"row mt-2"<"col-sm-5"i><"col-sm-7"p>>'
        }));
      }
    });

    // Export-enabled tables
    var exportTitle = $('.export_title').html() || document.title;
    var exportOptions = { columns: ':visible' };

    $('.table-export').each(function () {
      if (!$.fn.DataTable.isDataTable(this)) {
        $(this).DataTable($.extend({}, dtDefaults, {
          dom: '<"row"<"col-sm-6 mb-xs"B><"col-sm-6"f>><"table-responsive"t><"row mt-2"<"col-sm-5"i><"col-sm-7"p>>',
          ordering: false,
          buttons: [
            { extend: 'copyHtml5',  text: '<i class="far fa-copy me-1"></i>Copy',     titleAttr: 'Copy to clipboard', title: exportTitle, exportOptions: exportOptions },
            { extend: 'excelHtml5', text: '<i class="fas fa-file-excel me-1"></i>Excel', titleAttr: 'Export to Excel', title: exportTitle, exportOptions: exportOptions },
            { extend: 'csvHtml5',   text: '<i class="fas fa-file-alt me-1"></i>CSV',    titleAttr: 'Export to CSV',   title: exportTitle, exportOptions: exportOptions },
            { extend: 'pdfHtml5',   text: '<i class="fas fa-file-pdf me-1"></i>PDF',    titleAttr: 'Export to PDF',   title: exportTitle, footer: true, exportOptions: exportOptions,
              customize: function (win) {
                win.styles.tableHeader.fontSize = 9;
                win.styles.tableFooter.fontSize = 9;
                win.styles.tableHeader.alignment = 'left';
              }
            },
            { extend: 'print',      text: '<i class="fas fa-print me-1"></i>Print',  titleAttr: 'Print table', title: exportTitle,
              customize: function (win) {
                $(win.document.body).css('font-size', '9pt')
                  .find('table').addClass('compact').css('font-size', 'inherit');
              }
            },
            { extend: 'colvis', text: '<i class="fas fa-columns me-1"></i>Columns', titleAttr: 'Show/hide columns', postfixButtons: ['colvisRestore'] }
          ]
        }));
      }
    });
  });

  /* ── 9. SELECT2 INIT ──────────────────────────────────────────────────── */
  $(function () {
    if (typeof $.fn.select2 === 'undefined') return;
    $('[data-plugin-selectTwo]').each(function () {
      if (!$(this).data('select2')) {
        $(this).select2({ width: $(this).data('width') || '100%' });
      }
    });
  });

  /* ── 10. DATEPICKER INIT ──────────────────────────────────────────────── */
  $(function () {
    if (typeof $.fn.datepicker === 'undefined') return;
    $('[data-plugin-datepicker]').each(function () {
      var $el = $(this);
      $el.datepicker($.extend({
        format:    $el.data('date-format')    || 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true
      }, $el.data()));
    });
  });

  /* ── 11. FULLSCREEN ───────────────────────────────────────────────────── */
  $(document).on('click', '.s-expand', function () {
    if (typeof screenfull !== 'undefined' && screenfull.isEnabled) {
      screenfull.toggle();
    } else if (document.documentElement.requestFullscreen) {
      document.fullscreenElement ? document.exitFullscreen() : document.documentElement.requestFullscreen();
    }
  });

  /* ── 12. PERMISSION PAGE — SELECT ALL CHECKBOXES ─────────────────────── */
  function bindSelectAll(masterId, childClass) {
    $(document).on('change', '#' + masterId, function () {
      $('.' + childClass).prop('checked', this.checked);
    });
  }
  bindSelectAll('all_view',   'cb_view');
  bindSelectAll('all_add',    'cb_add');
  bindSelectAll('all_edit',   'cb_edit');
  bindSelectAll('all_delete', 'cb_delete');

  /* ── 13. APPEAR ANIMATIONS ────────────────────────────────────────────── */
  function checkAppear() {
    var h = window.innerHeight;
    document.querySelectorAll('.appear-animation:not(.dck-appeared)').forEach(function (el) {
      if (el.getBoundingClientRect().top < h - 40) {
        el.classList.add('dck-appeared');
      }
    });
  }
  window.addEventListener('scroll', checkAppear, { passive: true });
  document.addEventListener('DOMContentLoaded', checkAppear);

  /* ── 14. CSRF + AJAX SETUP ────────────────────────────────────────────── */
  $(function () {
    if (typeof csrfData !== 'undefined') {
      $.ajaxSetup({ data: csrfData });
    }
  });

  /* ── 15. confirm_modal() GLOBAL FUNCTION ──────────────────────────────── */
  // Replaces the one in layout/index.php — using SweetAlert if available.
  window.confirm_modal = function (deleteUrl) {
    var areYouSure  = (typeof translate_are_you_sure  !== 'undefined') ? translate_are_you_sure  : 'Are you sure?';
    var deleteInfo  = (typeof translate_delete_info   !== 'undefined') ? translate_delete_info   : 'This will permanently delete the record.';
    var yesContinue = (typeof translate_yes_continue  !== 'undefined') ? translate_yes_continue  : 'Yes, delete it!';
    var cancelTxt   = (typeof translate_cancel        !== 'undefined') ? translate_cancel        : 'Cancel';

    if (typeof swal !== 'undefined') {
      swal({
        title: areYouSure,
        text:  deleteInfo,
        type:  'warning',
        showCancelButton: true,
        confirmButtonClass: 'btn btn-danger btn-sm me-1',
        cancelButtonClass:  'btn btn-default btn-sm',
        confirmButtonText: yesContinue,
        cancelButtonText:  cancelTxt,
        buttonsStyling: false
      }).then(function (result) {
        if (result.value) {
          var csrfName = (typeof csrfTokenName !== 'undefined') ? csrfTokenName : '';
          var csrfHash = (typeof csrfTokenHash !== 'undefined') ? csrfTokenHash : '';
          var postData = {};
          if (csrfName) postData[csrfName] = csrfHash;

          $.ajax({
            url: deleteUrl, type: 'POST', data: postData,
            success: function () {
              swal({
                title: 'Deleted!',
                type: 'success',
                showConfirmButton: true,
                confirmButtonClass: 'btn btn-primary btn-sm',
                buttonsStyling: false
              }).then(function () { location.reload(); });
            }
          });
        }
      });
    } else {
      if (confirm(deleteInfo)) window.location.href = deleteUrl;
    }
  };

  /* ── 16. MAGNIFIC POPUP HELPERS ──────────────────────────────────────── */
  // Close any open Magnific Popup when .modal-dismiss is clicked
  $(document).on('click', '.modal-dismiss', function (e) {
    e.preventDefault();
    if (typeof $.magnificPopup !== 'undefined') $.magnificPopup.close();
  });

}(jQuery));

/* ── GLOBAL POPUP HELPERS (used by many views) ──────────────────────────── */

// Open an inline element (#selector) as a Magnific Popup modal
function mfp_modal(src) {
  if (typeof $.magnificPopup === 'undefined') return;
  $.magnificPopup.open({
    items: { src: src, type: 'inline' },
    fixedContentPos: false,
    fixedBgPos: true,
    overflowY: 'auto',
    closeBtnInside: true,
    preloader: false,
    midClick: true,
    removalDelay: 300,
    mainClass: 'my-mfp-zoom-in',
    modal: true
  });
}

// Load a URL via AJAX and open the response as an inline popup
function ajaxModal(url) {
  if (typeof $.magnificPopup === 'undefined') return;
  $.ajax({
    url: url,
    success: function (data) {
      $.magnificPopup.open({
        items: { src: data, type: 'inline' },
        fixedContentPos: false,
        fixedBgPos: true,
        overflowY: 'auto',
        closeBtnInside: true,
        preloader: false,
        midClick: true,
        removalDelay: 300,
        mainClass: 'my-mfp-zoom-in',
        modal: true
      });
    }
  });
}

// Fetch event details and show in the dashboard #modal popup
function viewEvent(id) {
  if (typeof base_url === 'undefined') return;
  jQuery.ajax({
    url: base_url + 'event/getDetails',
    type: 'POST',
    data: { event_id: id },
    success: function (data) {
      jQuery('#ev_table').html(data);
      mfp_modal('#modal');
    }
  });
}
