<div class="d-flex align-items-center flex-wrap gap-2 mb-3">
  <h6 class="mb-0">Subscription Payments</h6>
  <div class="ms-auto d-flex gap-2 align-items-center">
    <form method="get" class="d-flex gap-1 align-items-center" style="font-size:.85rem">
      <input type="date" name="from" value="<?= $from ?>" class="form-control form-control-sm" style="width:140px">
      <span>to</span>
      <input type="date" name="to" value="<?= $to ?>" class="form-control form-control-sm" style="width:140px">
      <button class="btn btn-sm btn-outline-secondary">Filter</button>
    </form>
    <a href="<?= base_url('settings/mpesa') ?>" class="btn btn-sm btn-primary">
      <i class="fas fa-cog me-1"></i>M-Pesa Settings
    </a>
  </div>
</div>

<?php if ($total_collected > 0): ?>
<div class="row mb-3">
  <div class="col-md-3">
    <div class="panel" style="border-left:3px solid #27ae60">
      <div class="panel-body text-center">
        <div style="font-size:1.5rem;font-weight:800;color:#27ae60">KES <?= number_format($total_collected) ?></div>
        <div style="font-size:.8rem;color:#7f8c8d">Total Collected (This Period)</div>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<section class="panel">
  <div class="panel-body p-0">
    <div class="table-responsive">
      <table class="table table-bordered table-hover table-condensed mb-none" style="font-size:.84rem">
        <thead>
          <tr>
            <th>Date & Time</th>
            <th>School</th>
            <th>Phone</th>
            <th>Amount (KES)</th>
            <th>M-Pesa Receipt</th>
            <th>Status</th>
            <th>Invoice</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($payments)): ?>
            <tr><td colspan="7" class="text-center text-muted py-4">No payments found for this period.</td></tr>
          <?php else: foreach ($payments as $p): ?>
          <tr>
            <td style="white-space:nowrap"><?= date('d M Y H:i', strtotime($p['created_at'])) ?></td>
            <td>
              <strong><?= htmlspecialchars($p['school_name'] ?: '—') ?></strong><br>
              <small class="text-muted"><?= htmlspecialchars($p['branch_name'] ?: '') ?></small>
            </td>
            <td><?= htmlspecialchars($p['phone']) ?></td>
            <td style="font-weight:600"><?= number_format($p['amount']) ?></td>
            <td>
              <?= $p['mpesa_receipt'] ? '<span class="label label-success">'.htmlspecialchars($p['mpesa_receipt']).'</span>' : '<span class="text-muted">—</span>' ?>
            </td>
            <td>
              <?php
                $cls = array('completed'=>'success','pending'=>'warning','failed'=>'danger','cancelled'=>'default');
                $c = $cls[$p['status']] ?? 'default';
              ?>
              <span class="label label-<?= $c ?>"><?= ucfirst($p['status']) ?></span>
            </td>
            <td>
              <?php if (!empty($p['invoice_id'])): ?>
              <a href="<?= base_url('subscription_payment/invoice/' . $p['invoice_id']) ?>" class="btn btn-xs btn-outline-success" target="_blank">
                <i class="fas fa-file-invoice"></i> View
              </a>
              <?php else: ?>
              <span class="text-muted" style="font-size:.78rem">—</span>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>
