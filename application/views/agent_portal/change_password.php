<div style="min-height:80vh;display:flex;align-items:center;justify-content:center;padding:24px">
  <div style="background:var(--ap-white);border-radius:14px;border:1px solid var(--ap-border);padding:40px 36px;max-width:420px;width:100%">

    <div style="text-align:center;margin-bottom:28px">
      <div style="width:56px;height:56px;border-radius:50%;background:#fff3cd;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:1.6rem">
        🔑
      </div>
      <h4 style="font-weight:700;margin:0 0 6px">Set Your Password</h4>
      <p style="color:var(--ap-muted);font-size:.875rem;margin:0">
        You are using a temporary password. Please set a new password to continue.
      </p>
    </div>

    <?php if (!empty($error)): ?>
    <div style="background:#fdf2f2;border:1px solid #f5c6cb;border-radius:8px;padding:10px 14px;font-size:.85rem;color:#c0392b;margin-bottom:16px">
      <i class="fas fa-exclamation-circle me-1"></i><?= $error ?>
    </div>
    <?php endif; ?>

    <form method="post">
      <div style="margin-bottom:16px">
        <label style="font-size:.82rem;font-weight:600;display:block;margin-bottom:5px">New Password</label>
        <input type="password" name="new_password" required minlength="6"
               style="width:100%;padding:10px 13px;border:1.5px solid var(--ap-border);border-radius:8px;font-size:.9rem;background:var(--ap-bg);color:var(--ap-text)"
               placeholder="Minimum 6 characters">
      </div>
      <div style="margin-bottom:24px">
        <label style="font-size:.82rem;font-weight:600;display:block;margin-bottom:5px">Confirm Password</label>
        <input type="password" name="confirm_password" required minlength="6"
               style="width:100%;padding:10px 13px;border:1.5px solid var(--ap-border);border-radius:8px;font-size:.9rem;background:var(--ap-bg);color:var(--ap-text)"
               placeholder="Re-enter your password">
      </div>
      <button type="submit" name="save" value="1"
              style="width:100%;padding:11px;background:var(--ap-navy);color:#fff;border:none;border-radius:8px;font-size:.9rem;font-weight:700;cursor:pointer">
        <i class="fas fa-lock me-1"></i> Set Password & Continue
      </button>
    </form>
  </div>
</div>
