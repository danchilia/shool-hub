<style>
.await-wrap {
  max-width: 520px; margin: 60px auto; text-align: center; padding: 0 20px;
}
.await-icon { font-size: 4rem; margin-bottom: 16px; }
.await-box {
  background: #e8f8f0; border: 2px solid #27ae60; border-radius: 12px;
  padding: 20px 24px; margin: 20px 0 28px;
}
.await-box .step { display: flex; align-items: center; gap: 12px; margin: 10px 0; text-align: left; }
.await-box .step .icon { width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: .85rem; font-weight: 700; }
.await-box .step .icon.done { background: #27ae60; color: #fff; }
.await-box .step .icon.pending { background: #f39c12; color: #fff; }
.await-box .step .icon.todo { background: #e2e8f0; color: #999; }
.await-box .step .label { font-size: .88rem; color: #444; }
</style>

<div class="await-wrap">
  <div class="await-icon">⏳</div>
  <h2 style="font-weight:800;color:#1a2e4a;margin-bottom:8px">Payment Confirmed!</h2>
  <p style="color:#555;font-size:.95rem;margin-bottom:24px">
    Your M-Pesa payment of <strong>KES <?= number_format($pending['amount']) ?></strong>
    was received. CST Admin will activate your school account shortly.
  </p>

  <div class="await-box">
    <div class="step">
      <div class="icon done"><i class="fas fa-check"></i></div>
      <div class="label"><strong>Plan selected</strong> — <?= ucfirst($pending['billing_cycle'] ?: '') ?> billing</div>
    </div>
    <div class="step">
      <div class="icon done"><i class="fas fa-check"></i></div>
      <div class="label"><strong>Payment received</strong> — KES <?= number_format($pending['amount']) ?> via M-Pesa</div>
    </div>
    <div class="step">
      <div class="icon pending"><i class="fas fa-clock"></i></div>
      <div class="label"><strong>Admin activation</strong> — CST Admin is reviewing your payment</div>
    </div>
    <div class="step">
      <div class="icon todo">4</div>
      <div class="label" style="color:#aaa">Full dashboard access</div>
    </div>
  </div>

  <p style="font-size:.82rem;color:#999;margin-bottom:20px">
    Activation is usually done within a few hours during business hours.<br>
    Need help? Contact CST Solutions.
  </p>

  <button onclick="window.location.reload()"
          style="padding:11px 28px;background:#1a2e4a;color:#fff;border:none;border-radius:8px;font-weight:700;cursor:pointer;font-size:.93rem">
    <i class="fas fa-sync-alt"></i> Check Activation Status
  </button>
</div>
