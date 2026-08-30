<div class="d-flex align-items-center mb-3">
  <h6 class="mb-0">My Subscription Invoices</h6>
</div>

<section class="panel">
  <div class="panel-body p-0">
    <div class="table-responsive">
      <table class="table table-bordered table-hover table-condensed mb-none" style="font-size:.85rem">
        <thead>
          <tr>
            <th>Invoice #</th>
            <th>Date</th>
            <th>Plan</th>
            <th>Subtotal</th>
            <th>VAT (16%)</th>
            <th>Total Paid</th>
            <th>M-Pesa Receipt</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($invoices)): ?>
            <tr><td colspan="8" class="text-center text-muted py-4">No invoices yet. Invoices are generated automatically after M-Pesa payment.</td></tr>
          <?php else: foreach ($invoices as $inv): ?>
          <tr>
            <td><strong><?= htmlspecialchars($inv['invoice_number']) ?></strong></td>
            <td style="white-space:nowrap"><?= date('d M Y', strtotime($inv['created_at'])) ?></td>
            <td><?= htmlspecialchars($inv['plan_name'] ?: '—') ?> <small class="text-muted"><?= ucfirst($inv['billing_cycle'] ?: '') ?></small></td>
            <td>KES <?= number_format($inv['amount_before_vat'], 2) ?></td>
            <td>KES <?= number_format($inv['vat_amount'], 2) ?></td>
            <td style="font-weight:700;color:#27ae60">KES <?= number_format($inv['total_amount'], 2) ?></td>
            <td>
              <?= !empty($inv['mpesa_receipt']) ? '<span class="label label-success">'.htmlspecialchars($inv['mpesa_receipt']).'</span>' : '—' ?>
            </td>
            <td>
              <a href="<?= base_url('subscription_payment/invoice/' . $inv['id']) ?>" class="btn btn-xs btn-success" target="_blank">
                <i class="fas fa-print me-1"></i>Print
              </a>
            </td>
          </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>
