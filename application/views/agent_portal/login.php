<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Agent Login | CST SchoolHub</title>
<link rel="stylesheet" href="<?= base_url('assets/vendor/bootstrap5/css/bootstrap.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/vendor/font-awesome/css/all.min.css') ?>">
<style>
body {
  margin: 0; min-height: 100vh;
  background: linear-gradient(135deg, #1a2e4a 0%, #243a5e 60%, #1a5276 100%);
  display: flex; align-items: center; justify-content: center;
  font-family: 'Segoe UI', system-ui, sans-serif;
}
.login-card {
  background: #fff; border-radius: 14px; padding: 44px 40px;
  width: 100%; max-width: 400px; box-shadow: 0 20px 60px rgba(0,0,0,.3);
}
.brand-logo {
  width: 52px; height: 52px; background: #1a2e4a; border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.4rem; color: #f39c12; margin: 0 auto 14px;
}
.login-title { font-size: 1.4rem; font-weight: 700; color: #1a2e4a; text-align: center; }
.login-sub   { color: #7f8c8d; font-size: .85rem; text-align: center; margin-bottom: 28px; }
.form-control { border-radius: 8px; border: 1.5px solid #dde1e7; padding: 10px 14px; font-size: .9rem; }
.form-control:focus { border-color: #1a2e4a; box-shadow: 0 0 0 3px rgba(26,46,74,.12); }
.form-label { font-weight: 500; font-size: .85rem; color: #2c3e50; }
.btn-login {
  background: #1a2e4a; color: #fff; border: none; width: 100%;
  padding: 11px; border-radius: 8px; font-size: .95rem; font-weight: 600;
  cursor: pointer; transition: background .15s;
}
.btn-login:hover { background: #243a5e; }
.portal-tag {
  text-align: center; margin-top: 22px; font-size: .78rem; color: #aab;
}
</style>
</head>
<body>
<div class="login-card">
  <div class="brand-logo" style="padding:4px">
    <img src="<?= base_url('assets/images/cst-logo.png') ?>" alt="CST" style="height:40px;width:40px;object-fit:contain">
  </div>
  <div class="login-title">CST Agent Portal</div>
  <div class="login-sub">Sign in with your agent credentials</div>

  <?php if (!empty($error)): ?>
  <div class="alert alert-danger py-2 px-3 mb-3" style="font-size:.85rem;border-radius:8px">
    <i class="fas fa-exclamation-circle me-1"></i> <?= htmlspecialchars($error) ?>
  </div>
  <?php endif; ?>

  <form method="post" action="<?= base_url('agent_portal/login') ?>">
    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
    <div class="mb-3">
      <label class="form-label">Email Address</label>
      <input type="email" name="email" class="form-control" placeholder="agent@dck.co.ke"
             value="<?= set_value('email') ?>" required autocomplete="username">
    </div>
    <div class="mb-4">
      <label class="form-label">Password</label>
      <input type="password" name="password" class="form-control" placeholder="••••••••" required autocomplete="current-password">
    </div>
    <button type="submit" name="login" value="1" class="btn-login">
      <i class="fas fa-sign-in-alt me-2"></i>Sign In
    </button>
  </form>

  <div class="portal-tag">Chilia Select Technologies · Field Agent Portal</div>
</div>
<script src="<?= base_url('assets/vendor/bootstrap5/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
