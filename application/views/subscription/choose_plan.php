<style>
.cp-wrap {
  max-width: 960px; margin: 0 auto; padding: 10px 0 40px;
}
.cp-hero {
  text-align: center; margin-bottom: 36px;
}
.cp-hero h2 {
  font-size: 1.6rem; font-weight: 800; color: #1a2e4a; margin-bottom: 8px;
}
.cp-hero p {
  font-size: .95rem; color: #6c757d; max-width: 520px; margin: 0 auto;
}
.cp-cycle-toggle {
  display: flex; align-items: center; justify-content: center; gap: 14px;
  margin-bottom: 30px;
}
.cp-cycle-toggle label {
  font-weight: 600; font-size: .9rem; color: #555; cursor: pointer; margin: 0;
}
.cp-toggle-switch {
  position: relative; width: 52px; height: 26px;
}
.cp-toggle-switch input { opacity: 0; width: 0; height: 0; }
.cp-toggle-switch .slider {
  position: absolute; inset: 0; background: #1a5276; border-radius: 26px; cursor: pointer; transition: .3s;
}
.cp-toggle-switch .slider:before {
  content: ''; position: absolute; width: 20px; height: 20px;
  left: 3px; bottom: 3px; background: #fff; border-radius: 50%; transition: .3s;
}
.cp-toggle-switch input:checked + .slider:before { transform: translateX(26px); }
.yearly-badge {
  background: #27ae60; color: #fff; font-size: .68rem; font-weight: 700;
  padding: 2px 7px; border-radius: 20px; vertical-align: middle;
}
.cp-plans {
  display: flex; flex-wrap: wrap; gap: 22px; justify-content: center;
}
.cp-plan-card {
  background: #fff; border: 2px solid #e2e8f0; border-radius: 14px;
  padding: 28px 24px 24px; width: 280px; flex-shrink: 0;
  display: flex; flex-direction: column; transition: border-color .2s, box-shadow .2s;
  position: relative;
}
.cp-plan-card:hover {
  border-color: #1a5276; box-shadow: 0 6px 24px rgba(26,46,74,.12);
}
.cp-plan-card.popular {
  border-color: #1a5276;
}
.cp-popular-badge {
  position: absolute; top: -13px; left: 50%; transform: translateX(-50%);
  background: #1a5276; color: #fff; font-size: .7rem; font-weight: 700;
  padding: 3px 14px; border-radius: 20px; white-space: nowrap; letter-spacing: .5px;
}
.cp-plan-name {
  font-size: 1rem; font-weight: 800; color: #1a2e4a; margin-bottom: 4px;
}
.cp-plan-desc {
  font-size: .78rem; color: #7f8c8d; margin-bottom: 18px; min-height: 32px;
}
.cp-price {
  font-size: 2rem; font-weight: 800; color: #1a5276; line-height: 1;
}
.cp-price small {
  font-size: .8rem; font-weight: 400; color: #7f8c8d;
}
.cp-price-yearly {
  display: none; font-size: 2rem; font-weight: 800; color: #27ae60; line-height: 1;
}
.cp-price-yearly small { font-size: .8rem; font-weight: 400; color: #7f8c8d; }
.cp-yearly-saving {
  display: none; font-size: .75rem; color: #27ae60; font-weight: 600;
  margin-top: 4px;
}
.show-yearly .cp-price { display: none; }
.show-yearly .cp-price-yearly { display: block; }
.show-yearly .cp-yearly-saving { display: block; }
.cp-limits {
  font-size: .8rem; color: #555; margin: 14px 0 18px;
  display: flex; flex-direction: column; gap: 5px;
}
.cp-limits span { display: flex; align-items: center; gap: 7px; }
.cp-limits i { color: #27ae60; font-size: .75rem; width: 14px; }
.cp-pay-btn {
  margin-top: auto;
  width: 100%; padding: 12px; background: #1a2e4a; color: #fff;
  border: none; border-radius: 8px; font-weight: 700; font-size: .92rem;
  cursor: pointer; transition: background .2s;
}
.cp-pay-btn:hover { background: #1a5276; }
.cp-popular-btn { background: #1a5276; }
</style>

<div class="cp-wrap">
  <div class="cp-hero">
    <h2>Activate Your School Account</h2>
    <p>Choose a plan below, pay securely via M-Pesa, and your school gets instant access.</p>
  </div>

  <div class="cp-cycle-toggle">
    <label for="cycleToggle">Monthly</label>
    <label class="cp-toggle-switch">
      <input type="checkbox" id="cycleToggle" onchange="toggleCycle(this)">
      <span class="slider"></span>
    </label>
    <label for="cycleToggle">Yearly &nbsp;<span class="yearly-badge">Save ~14%</span></label>
  </div>

  <div class="cp-plans" id="planCards">
    <?php $i = 0; foreach ($plans as $p):
      $monthlyTotal = intval(ceil($p['monthly_price'] * 1.16));
      $yearlyTotal  = intval(ceil($p['yearly_price']  * 1.16));
      $yearlySaving = intval(($monthlyTotal * 12) - $yearlyTotal);
      $isPopular    = ($i == 1);
      $i++;
    ?>
    <div class="cp-plan-card <?= $isPopular ? 'popular' : '' ?>">
      <?php if ($isPopular): ?>
        <div class="cp-popular-badge">Most Popular</div>
      <?php endif; ?>
      <div class="cp-plan-name"><?= htmlspecialchars($p['name']) ?></div>
      <div class="cp-plan-desc"><?= htmlspecialchars($p['description'] ?: 'Full school management access') ?></div>

      <div class="cp-price">
        KES <?= number_format($monthlyTotal) ?><small>/mo</small>
      </div>
      <div class="cp-price-yearly">
        KES <?= number_format($yearlyTotal) ?><small>/yr</small>
      </div>
      <?php if ($yearlySaving > 0): ?>
      <div class="cp-yearly-saving">You save KES <?= number_format($yearlySaving) ?> vs monthly</div>
      <?php endif; ?>

      <div class="cp-limits">
        <span><i class="fas fa-users"></i> Up to <?= $p['max_students'] == 0 ? 'Unlimited' : number_format($p['max_students']) ?> students</span>
        <span><i class="fas fa-chalkboard-teacher"></i> Up to <?= $p['max_staff'] == 0 ? 'Unlimited' : number_format($p['max_staff']) ?> staff</span>
        <span><i class="fas fa-check"></i> All modules included</span>
        <span><i class="fas fa-check"></i> SMS & email alerts</span>
        <span><i class="fas fa-check"></i> CBC & KNEC reports</span>
      </div>

      <button class="cp-pay-btn <?= $isPopular ? 'cp-popular-btn' : '' ?>"
              onclick="openPayModal(<?= $p['id'] ?>, '<?= htmlspecialchars($p['name']) ?>', <?= $monthlyTotal ?>, <?= $yearlyTotal ?>)">
        <i class="fas fa-mobile-alt me-1"></i> Pay via M-Pesa
      </button>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- M-Pesa Payment Modal -->
<div id="cpPayModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:9999;align-items:center;justify-content:center">
  <div style="background:#fff;border-radius:14px;padding:32px;max-width:400px;width:92%;position:relative;text-align:center">

    <!-- Step 1: Phone input -->
    <div id="cpStep1">
      <div style="font-size:2.2rem;margin-bottom:10px">📱</div>
      <h5 style="font-weight:800;color:#1a2e4a;margin-bottom:4px" id="cpModalTitle">Pay via M-Pesa</h5>
      <p style="font-size:.83rem;color:#7f8c8d;margin-bottom:20px" id="cpModalSubtitle"></p>
      <div style="margin-bottom:16px;text-align:left">
        <label style="font-size:.82rem;font-weight:600;color:#555;margin-bottom:5px;display:block">M-Pesa Phone Number</label>
        <input type="tel" id="cpPhone" placeholder="07XXXXXXXX" maxlength="13"
               style="width:100%;padding:10px 14px;border:2px solid #e2e8f0;border-radius:8px;font-size:1rem;outline:none"
               onfocus="this.style.borderColor='#1a5276'" onblur="this.style.borderColor='#e2e8f0'">
      </div>
      <div id="cpPhoneErr" style="color:#e74c3c;font-size:.8rem;margin-bottom:10px;display:none"></div>
      <button onclick="cpInitiate()" id="cpSendBtn"
              style="width:100%;padding:12px;background:#27ae60;color:#fff;border:none;border-radius:8px;font-weight:700;font-size:.95rem;cursor:pointer">
        <i class="fas fa-paper-plane me-1"></i> Send Payment Request
      </button>
      <button onclick="cpCloseModal()" style="margin-top:10px;background:none;border:none;color:#aaa;cursor:pointer;font-size:.83rem">Cancel</button>
    </div>

    <!-- Step 2: Waiting for payment -->
    <div id="cpStep2" style="display:none">
      <div style="font-size:2.5rem;margin-bottom:12px">⏳</div>
      <h5 style="font-weight:800;color:#1a2e4a">Waiting for Payment</h5>
      <p style="font-size:.85rem;color:#555;margin-top:8px">
        A payment prompt has been sent to your phone.<br>
        Enter your M-Pesa PIN to confirm.
      </p>
      <div style="margin:20px auto;width:40px;height:40px;border:4px solid #e2e8f0;border-top-color:#1a5276;border-radius:50%;animation:spin 1s linear infinite"></div>
      <p style="font-size:.78rem;color:#aaa">Amount: <strong id="cpAmountDisplay"></strong></p>
    </div>

    <!-- Step 3: Payment received, awaiting admin activation -->
    <div id="cpStep3" style="display:none">
      <div style="font-size:2.5rem;margin-bottom:12px">✅</div>
      <h5 style="font-weight:800;color:#27ae60">Payment Received!</h5>
      <p style="font-size:.85rem;color:#555;margin-top:8px">
        Your M-Pesa payment has been confirmed.<br>
        <strong>CST Admin is activating your school account.</strong>
      </p>
      <div style="margin:14px auto;width:36px;height:36px;border:4px solid #e2e8f0;border-top-color:#f39c12;border-radius:50%;animation:spin 1s linear infinite"></div>
      <p style="font-size:.75rem;color:#aaa;margin-top:6px">Waiting for admin activation — this page will update automatically.</p>
    </div>

    <!-- Step 3b: Activated -->
    <div id="cpStep3b" style="display:none">
      <div style="font-size:2.5rem;margin-bottom:12px">🎉</div>
      <h5 style="font-weight:800;color:#27ae60">Account Activated!</h5>
      <p style="font-size:.85rem;color:#555;margin-top:8px">
        Your school is now live.<br>
        Taking you to your dashboard...
      </p>
      <div style="margin:14px auto;width:36px;height:36px;border:4px solid #e2e8f0;border-top-color:#27ae60;border-radius:50%;animation:spin 1s linear infinite"></div>
    </div>

    <!-- Step 4: Failed -->
    <div id="cpStep4" style="display:none">
      <div style="font-size:2.5rem;margin-bottom:12px">❌</div>
      <h5 style="font-weight:800;color:#e74c3c">Payment Failed</h5>
      <p id="cpFailMsg" style="font-size:.85rem;color:#555;margin-top:8px"></p>
      <button onclick="cpResetModal()"
              style="margin-top:16px;padding:10px 28px;background:#1a2e4a;color:#fff;border:none;border-radius:8px;font-weight:700;cursor:pointer">
        Try Again
      </button>
    </div>

  </div>
</div>

<style>
@keyframes spin { to { transform: rotate(360deg); } }
</style>

<script>
var cpSelectedPlanId   = null;
var cpSelectedBilling  = 'monthly';
var cpSelectedMonthly  = 0;
var cpSelectedYearly   = 0;
var cpCheckoutId       = null;
var cpPollTimer        = null;

function toggleCycle(el) {
  cpSelectedBilling = el.checked ? 'yearly' : 'monthly';
  if (el.checked) {
    document.getElementById('planCards').classList.add('show-yearly');
  } else {
    document.getElementById('planCards').classList.remove('show-yearly');
  }
}

function openPayModal(planId, planName, monthly, yearly) {
  cpSelectedPlanId  = planId;
  cpSelectedMonthly = monthly;
  cpSelectedYearly  = yearly;
  var amount = cpSelectedBilling === 'yearly' ? yearly : monthly;
  var cycle  = cpSelectedBilling === 'yearly' ? 'Yearly' : 'Monthly';
  document.getElementById('cpModalTitle').textContent = 'Pay via M-Pesa — ' + planName;
  document.getElementById('cpModalSubtitle').textContent = cycle + ' plan · KES ' + amount.toLocaleString() + ' (incl. 16% VAT)';
  document.getElementById('cpPayModal').style.display = 'flex';
  document.getElementById('cpStep1').style.display = 'block';
  document.getElementById('cpStep2').style.display = 'none';
  document.getElementById('cpStep3').style.display = 'none';
  document.getElementById('cpStep4').style.display = 'none';
  document.getElementById('cpPhone').value = '';
  document.getElementById('cpPhoneErr').style.display = 'none';
}

function cpCloseModal() {
  if (cpPollTimer) clearInterval(cpPollTimer);
  document.getElementById('cpPayModal').style.display = 'none';
}

function cpResetModal() {
  if (cpPollTimer) clearInterval(cpPollTimer);
  document.getElementById('cpStep1').style.display = 'block';
  document.getElementById('cpStep2').style.display = 'none';
  document.getElementById('cpStep3').style.display = 'none';
  document.getElementById('cpStep4').style.display = 'none';
}

function cpInitiate() {
  var phone = document.getElementById('cpPhone').value.trim();
  document.getElementById('cpPhoneErr').style.display = 'none';
  if (!phone) {
    document.getElementById('cpPhoneErr').textContent = 'Please enter your phone number.';
    document.getElementById('cpPhoneErr').style.display = 'block';
    return;
  }
  document.getElementById('cpSendBtn').disabled = true;
  document.getElementById('cpSendBtn').textContent = 'Sending...';

  var amount = cpSelectedBilling === 'yearly' ? cpSelectedYearly : cpSelectedMonthly;
  document.getElementById('cpAmountDisplay').textContent = 'KES ' + amount.toLocaleString();

  $.post('<?= base_url('subscription_payment/initiate_plan') ?>', {
    '<?= $this->security->get_csrf_token_name() ?>': '<?= $this->security->get_csrf_hash() ?>',
    plan_id:      cpSelectedPlanId,
    billing_cycle: cpSelectedBilling,
    phone:        phone
  }, function(res) {
    if (res.success) {
      cpCheckoutId = res.checkout_request_id;
      document.getElementById('cpStep1').style.display = 'none';
      document.getElementById('cpStep2').style.display = 'block';
      cpPollTimer = setInterval(cpPoll, 3000);
    } else {
      document.getElementById('cpPhoneErr').textContent = res.message || 'Failed to send payment request.';
      document.getElementById('cpPhoneErr').style.display = 'block';
      document.getElementById('cpSendBtn').disabled = false;
      document.getElementById('cpSendBtn').innerHTML = '<i class="fas fa-paper-plane me-1"></i> Send Payment Request';
    }
  }, 'json').fail(function() {
    document.getElementById('cpPhoneErr').textContent = 'Network error. Please try again.';
    document.getElementById('cpPhoneErr').style.display = 'block';
    document.getElementById('cpSendBtn').disabled = false;
    document.getElementById('cpSendBtn').innerHTML = '<i class="fas fa-paper-plane me-1"></i> Send Payment Request';
  });
}

function cpPoll() {
  $.post('<?= base_url('subscription_payment/check') ?>', {
    '<?= $this->security->get_csrf_token_name() ?>': '<?= $this->security->get_csrf_hash() ?>',
    checkout_request_id: cpCheckoutId
  }, function(res) {
    if (res.status === 'awaiting') {
      // M-Pesa confirmed, superadmin yet to activate
      clearInterval(cpPollTimer);
      document.getElementById('cpStep2').style.display = 'none';
      document.getElementById('cpStep3').style.display = 'block';
      // Keep polling every 8 seconds for activation
      cpPollTimer = setInterval(cpPollActivation, 8000);
    } else if (res.status === 'activated') {
      clearInterval(cpPollTimer);
      cpShowActivated();
    } else if (res.status === 'failed' || res.status === 'cancelled') {
      clearInterval(cpPollTimer);
      document.getElementById('cpStep2').style.display = 'none';
      document.getElementById('cpStep4').style.display = 'block';
      document.getElementById('cpFailMsg').textContent = res.status === 'cancelled'
        ? 'You cancelled the payment. Please try again.'
        : 'Payment failed. Please check your M-Pesa balance and try again.';
    }
  }, 'json');
}

function cpPollActivation() {
  $.post('<?= base_url('subscription_payment/check') ?>', {
    '<?= $this->security->get_csrf_token_name() ?>': '<?= $this->security->get_csrf_hash() ?>',
    checkout_request_id: cpCheckoutId
  }, function(res) {
    if (res.status === 'activated') {
      clearInterval(cpPollTimer);
      cpShowActivated();
    }
  }, 'json');
}

function cpShowActivated() {
  document.getElementById('cpStep3').style.display = 'none';
  document.getElementById('cpStep3b').style.display = 'block';
  setTimeout(function() { window.location.href = '<?= base_url('dashboard') ?>'; }, 2500);
}
</script>
