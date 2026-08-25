  </div><!-- /.ap-content -->
</div><!-- /.ap-main -->

<script src="<?= base_url('assets/vendor/bootstrap5/js/bootstrap.bundle.min.js') ?>"></script>
<script>
// Close sidebar overlay on outside click (mobile)
document.addEventListener('click', function(e) {
  var sb = document.getElementById('apSidebar');
  if (sb && sb.classList.contains('open') && !sb.contains(e.target) && !e.target.closest('.ap-toggler')) {
    sb.classList.remove('open');
  }
});
</script>
</body>
</html>
