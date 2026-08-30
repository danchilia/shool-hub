<?php $m = isset($mpesa) ? $mpesa : array(); ?>

<div class="row">
  <div class="col-sm-12 col-md-8 col-lg-7">

    <section class="panel">
      <header class="panel-heading">
        <i class="fas fa-mobile-alt me-2"></i> M-Pesa Paybill & STK Push Settings
      </header>
      <div class="panel-body">

        <div class="alert alert-info" style="font-size:.87rem">
          <i class="fas fa-info-circle"></i>
          These settings control the <strong>M-Pesa Pay Now</strong> button on school admin dashboards.
          Schools will use this to pay their subscription fees via STK Push.
          Get your Daraja API credentials from <strong>developer.safaricom.co.ke</strong> after registering your Paybill.
        </div>

        <?php echo form_open('settings/mpesa'); ?>
        <input type="hidden" name="save_mpesa" value="1">

        <h6 style="font-weight:700;margin:0 0 14px;padding-bottom:8px;border-bottom:1px solid #eee">
          Display Settings (shown to schools)
        </h6>

        <div class="form-group">
          <label class="control-label">Paybill Number <span class="text-danger">*</span></label>
          <input type="text" name="paybill_number" class="form-control"
                 value="<?= htmlspecialchars($m['paybill_number'] ?? '') ?>"
                 placeholder="e.g. 400200">
          <small class="text-muted">The Safaricom Paybill number schools will pay to.</small>
        </div>

        <div class="form-group">
          <label class="control-label">Account Name / Info</label>
          <input type="text" name="account_info" class="form-control"
                 value="<?= htmlspecialchars($m['account_info'] ?? '') ?>"
                 placeholder="e.g. Use your school name as account">
          <small class="text-muted">Instructions shown to school admins when paying.</small>
        </div>

        <div class="form-group">
          <label class="control-label">Dashboard Notice (optional)</label>
          <textarea name="subscription_notice" class="form-control" rows="2"
                    placeholder="e.g. Pay before expiry to avoid service interruption."><?= htmlspecialchars($m['subscription_notice'] ?? '') ?></textarea>
          <small class="text-muted">Short message shown on the subscription alert banner.</small>
        </div>

        <h6 style="font-weight:700;margin:20px 0 14px;padding-bottom:8px;border-bottom:1px solid #eee">
          Daraja API Credentials (for STK Push)
        </h6>

        <div class="form-group">
          <label class="control-label">Business Shortcode</label>
          <input type="text" name="mpesa_shortcode" class="form-control"
                 value="<?= htmlspecialchars($m['mpesa_shortcode'] ?? '') ?>"
                 placeholder="e.g. 400200 (usually same as Paybill)">
        </div>

        <div class="form-group">
          <label class="control-label">Consumer Key</label>
          <input type="text" name="mpesa_consumer_key" class="form-control"
                 value="<?= htmlspecialchars($m['mpesa_consumer_key'] ?? '') ?>"
                 placeholder="From Daraja API portal">
        </div>

        <div class="form-group">
          <label class="control-label">Consumer Secret</label>
          <input type="password" name="mpesa_consumer_secret" class="form-control"
                 value="<?= htmlspecialchars($m['mpesa_consumer_secret'] ?? '') ?>"
                 placeholder="From Daraja API portal"
                 autocomplete="new-password">
        </div>

        <div class="form-group">
          <label class="control-label">Passkey</label>
          <input type="password" name="mpesa_passkey" class="form-control"
                 value="<?= htmlspecialchars($m['mpesa_passkey'] ?? '') ?>"
                 placeholder="From Daraja API portal"
                 autocomplete="new-password">
        </div>

        <div class="form-group">
          <label class="control-label">Environment</label>
          <div>
            <label class="radio-inline">
              <input type="radio" name="mpesa_sandbox" value="0"
                     <?= empty($m['mpesa_sandbox']) ? 'checked' : '' ?>> Production (Live)
            </label>
            &nbsp;&nbsp;
            <label class="radio-inline">
              <input type="radio" name="mpesa_sandbox" value="1"
                     <?= !empty($m['mpesa_sandbox']) ? 'checked' : '' ?>> Sandbox (Testing)
            </label>
          </div>
          <small class="text-muted">Use Sandbox only for testing. Switch to Production when you go live.</small>
        </div>

        <hr>

        <button type="submit" class="btn btn-primary">
          <i class="fas fa-save me-1"></i> Save M-Pesa Settings
        </button>
        <a href="<?= base_url('subscription_payment/payments') ?>" class="btn btn-default ms-2">
          <i class="fas fa-list me-1"></i> View Payment Logs
        </a>

        <?php echo form_close(); ?>

      </div>
    </section>

  </div>

  <div class="col-sm-12 col-md-4 col-lg-5">
    <section class="panel">
      <header class="panel-heading"><i class="fas fa-question-circle me-2"></i>Setup Guide</header>
      <div class="panel-body" style="font-size:.85rem;line-height:1.75">
        <p><strong>Step 1:</strong> Register a Paybill number with Safaricom Business.</p>
        <p><strong>Step 2:</strong> Go to <a href="https://developer.safaricom.co.ke" target="_blank">developer.safaricom.co.ke</a> and create an app to get your Consumer Key, Consumer Secret, and Passkey.</p>
        <p><strong>Step 3:</strong> Set the STK Push Callback URL in Daraja to:<br>
          <code style="word-break:break-all;font-size:.8rem"><?= base_url('mpesa-callback/subscription') ?></code>
        </p>
        <p><strong>Step 4:</strong> Enter all credentials above and click Save.</p>
        <hr>
        <p class="text-muted">Once configured, school admins will see a <strong>"Pay via M-Pesa"</strong> button on their dashboard. Clicking it sends an STK Push prompt to their phone. After payment, a VAT invoice (16%) is automatically generated and available for download.</p>
      </div>
    </section>

    <?php if (!empty($m['paybill_number'])): ?>
    <section class="panel">
      <header class="panel-heading" style="background:#27ae60;color:#fff"><i class="fas fa-check-circle me-2"></i>Current Settings</header>
      <div class="panel-body" style="font-size:.88rem">
        <table class="table table-condensed mb-none">
          <tr><td><strong>Paybill:</strong></td><td><?= htmlspecialchars($m['paybill_number']) ?></td></tr>
          <tr><td><strong>Shortcode:</strong></td><td><?= htmlspecialchars($m['mpesa_shortcode'] ?? '—') ?></td></tr>
          <tr><td><strong>Environment:</strong></td><td>
            <?php if (empty($m['mpesa_sandbox'])): ?>
              <span class="label label-success">Production (Live)</span>
            <?php else: ?>
              <span class="label label-warning">Sandbox (Testing)</span>
            <?php endif; ?>
          </td></tr>
          <tr><td><strong>STK Push:</strong></td><td>
            <?php $ready = !empty($m['mpesa_consumer_key']) && !empty($m['mpesa_consumer_secret']) && !empty($m['mpesa_passkey']); ?>
            <span class="label label-<?= $ready ? 'success' : 'danger' ?>"><?= $ready ? 'Configured' : 'Incomplete' ?></span>
          </td></tr>
        </table>
      </div>
    </section>
    <?php endif; ?>

  </div>
</div>
