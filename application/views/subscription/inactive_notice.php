<style>
.inactive-wrap {
  max-width: 480px; margin: 80px auto; text-align: center; padding: 0 20px;
}
.inactive-icon { font-size: 4rem; margin-bottom: 16px; }
.inactive-box {
  background: #fdf2f2; border: 2px solid #e74c3c; border-radius: 12px;
  padding: 24px 28px; margin: 20px 0;
}
</style>

<div class="inactive-wrap">
  <div class="inactive-icon">🔒</div>
  <h2 style="font-weight:800;color:#1a2e4a;margin-bottom:8px">Subscription Inactive</h2>
  <div class="inactive-box">
    <p style="color:#555;font-size:.97rem;margin:0">
      Your school's subscription has <strong>expired or is not active</strong>.<br><br>
      Please contact your <strong>School Administrator</strong> to renew the subscription
      so you can access the system.
    </p>
  </div>
  <p style="font-size:.82rem;color:#999;margin-top:16px">
    If you believe this is an error, ask your admin to log in and check the subscription status.
  </p>
  <a href="<?= base_url('authentication/logout') ?>"
     style="display:inline-block;margin-top:20px;padding:11px 28px;background:#1a2e4a;color:#fff;border-radius:8px;font-weight:700;font-size:.93rem;text-decoration:none;">
    <i class="fas fa-sign-out-alt"></i> Logout
  </a>
</div>
