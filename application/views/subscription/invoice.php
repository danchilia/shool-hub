<?php $inv = $invoice; $br = $branch ?? array(); ?>

<style>
@media print {
  .no-print, .panel-heading, nav, .dck-sidebar, .ap-topbar { display: none !important; }
  .panel { box-shadow: none !important; border: none !important; }
  body { background: #fff !important; }
}
.inv-box {
  max-width: 720px; margin: 0 auto; background: #fff;
  border: 1px solid #ddd; border-radius: 8px; padding: 40px;
  font-family: 'Segoe UI', sans-serif;
}
.inv-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 32px; }
.inv-logo { font-size: 1.4rem; font-weight: 800; color: #1a2e4a; }
.inv-logo small { display: block; font-size: .75rem; font-weight: 400; color: #7f8c8d; }
.inv-meta { text-align: right; font-size: .85rem; color: #555; }
.inv-meta strong { font-size: 1.1rem; color: #1a2e4a; display: block; }
.inv-divider { border: none; border-top: 2px solid #1a2e4a; margin: 0 0 24px; }
.inv-parties { display: flex; justify-content: space-between; margin-bottom: 28px; font-size: .85rem; }
.inv-party h6 { font-weight: 700; text-transform: uppercase; font-size: .7rem; letter-spacing: .8px; color: #7f8c8d; margin: 0 0 6px; }
.inv-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: .88rem; }
.inv-table th { background: #1a2e4a; color: #fff; padding: 10px 14px; text-align: left; }
.inv-table td { padding: 10px 14px; border-bottom: 1px solid #eee; }
.inv-table tr:last-child td { border-bottom: none; }
.inv-totals { max-width: 280px; margin-left: auto; font-size: .88rem; }
.inv-totals tr td { padding: 5px 10px; }
.inv-totals tr td:last-child { text-align: right; font-weight: 600; }
.inv-total-row td { border-top: 2px solid #1a2e4a; font-size: 1rem; font-weight: 800; color: #1a2e4a; padding-top: 10px; }
.inv-receipt { background: #f0faf0; border: 1px solid #5cb85c; border-radius: 6px; padding: 12px 16px; font-size: .85rem; margin-bottom: 24px; }
.inv-receipt i { color: #27ae60; }
.inv-footer { text-align: center; font-size: .78rem; color: #aaa; margin-top: 32px; border-top: 1px solid #eee; padding-top: 16px; }
</style>

<div class="no-print" style="margin-bottom:16px;display:flex;gap:10px;align-items:center">
  <button onclick="window.print()" class="btn btn-primary btn-sm">
    <i class="fas fa-print me-1"></i> Print / Save as PDF
  </button>
  <a href="<?= base_url('subscription_payment/my_invoices') ?>" class="btn btn-default btn-sm">
    <i class="fas fa-arrow-left me-1"></i> My Invoices
  </a>
</div>

<div class="inv-box">

  <!-- HEADER -->
  <div class="inv-header">
    <div class="inv-logo">
      CST SchoolHub
      <small>by DCK Solutions Ltd</small>
      <small style="font-size:.72rem;color:#aaa">P.O Box, Nairobi, Kenya | cstschoolhub.co.ke</small>
    </div>
    <div class="inv-meta">
      <strong>TAX INVOICE</strong>
      <?= htmlspecialchars($inv['invoice_number']) ?><br>
      Date: <?= date('d M Y', strtotime($inv['created_at'])) ?><br>
      <span style="color:#27ae60;font-weight:700">PAID</span>
    </div>
  </div>

  <hr class="inv-divider">

  <!-- PARTIES -->
  <div class="inv-parties">
    <div class="inv-party">
      <h6>Issued By</h6>
      <strong>CST SchoolHub / DCK Solutions Ltd</strong><br>
      cstschoolhub.co.ke<br>
      KRA PIN: [PIN]
    </div>
    <div class="inv-party" style="text-align:right">
      <h6>Billed To</h6>
      <strong><?= htmlspecialchars($inv['school_name']) ?></strong><br>
      <?php if (!empty($br['address'])): ?>
        <?= htmlspecialchars($br['address']) ?><br>
      <?php endif; ?>
      <?php if (!empty($br['email'])): ?>
        <?= htmlspecialchars($br['email']) ?><br>
      <?php endif; ?>
    </div>
  </div>

  <!-- ITEMS TABLE -->
  <table class="inv-table">
    <thead>
      <tr>
        <th style="width:60%">Description</th>
        <th>Period</th>
        <th style="text-align:right">Amount (KES)</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>
          <strong>CST SchoolHub Subscription</strong><br>
          <small style="color:#7f8c8d">
            Plan: <?= htmlspecialchars($inv['plan_name'] ?: 'Subscription') ?> |
            Billing: <?= ucfirst($inv['billing_cycle'] ?: 'Monthly') ?>
          </small>
        </td>
        <td style="color:#555"><?= date('M Y', strtotime($inv['created_at'])) ?></td>
        <td style="text-align:right"><?= number_format($inv['amount_before_vat'], 2) ?></td>
      </tr>
    </tbody>
  </table>

  <!-- TOTALS -->
  <table class="inv-totals">
    <tr>
      <td style="color:#555">Subtotal (excl. VAT)</td>
      <td>KES <?= number_format($inv['amount_before_vat'], 2) ?></td>
    </tr>
    <tr>
      <td style="color:#555">VAT (16%)</td>
      <td>KES <?= number_format($inv['vat_amount'], 2) ?></td>
    </tr>
    <tr class="inv-total-row">
      <td>Total Paid</td>
      <td>KES <?= number_format($inv['total_amount'], 2) ?></td>
    </tr>
  </table>

  <!-- RECEIPT -->
  <?php if (!empty($inv['mpesa_receipt'])): ?>
  <div class="inv-receipt">
    <i class="fas fa-check-circle"></i>
    <strong>Payment confirmed via M-Pesa</strong><br>
    M-Pesa Receipt: <strong><?= htmlspecialchars($inv['mpesa_receipt']) ?></strong> |
    Date: <?= date('d M Y H:i', strtotime($inv['created_at'])) ?>
  </div>
  <?php endif; ?>

  <div class="inv-footer">
    This is a computer-generated invoice. No signature required.<br>
    CST SchoolHub &mdash; Empowering Kenyan Schools &mdash; cstschoolhub.co.ke
  </div>

</div>
