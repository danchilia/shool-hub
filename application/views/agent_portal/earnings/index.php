<?php $cur = 'KSh'; ?>

<!-- Summary -->
<div class="row g-3 mb-4">
  <div class="col-md-4">
    <div class="stat-card">
      <div class="stat-val"><?= $cur ?> <?= number_format($summary['total']) ?></div>
      <div class="stat-lbl">Total Earned</div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="stat-card">
      <div class="stat-val" style="color:#e67e22"><?= $cur ?> <?= number_format($summary['pending']) ?></div>
      <div class="stat-lbl">Pending Payout</div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="stat-card">
      <div class="stat-val text-success"><?= $cur ?> <?= number_format($summary['paid']) ?></div>
      <div class="stat-lbl">Paid to You</div>
    </div>
  </div>
</div>

<div class="ap-card">
  <div class="ap-card-header">Earnings Breakdown</div>
  <?php if (empty($earnings)): ?>
    <div class="ap-card-body text-center text-muted py-4">No earnings recorded yet. Start logging school visits!</div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-hover mb-0" style="font-size:.875rem">
        <thead style="background:var(--ap-bg)">
          <tr>
            <th>Date</th>
            <th>Type</th>
            <th>School</th>
            <th>Description</th>
            <th class="text-end">Amount</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($earnings as $e): ?>
          <tr>
            <td><?= date('d M Y', strtotime($e['created_at'])) ?></td>
            <td>
              <?php if ($e['type'] === 'commission'): ?>
                <span class="badge bg-success">Commission</span>
              <?php else: ?>
                <span class="badge bg-info text-dark">Visit Fee</span>
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($e['school_name'] ?: '—') ?></td>
            <td style="color:var(--ap-muted)"><?= htmlspecialchars($e['description']) ?></td>
            <td class="text-end fw-bold"><?= $cur ?> <?= number_format($e['amount']) ?></td>
            <td>
              <?php
              $bc = array('pending' => 'warning text-dark', 'approved' => 'primary', 'paid' => 'success', 'rejected' => 'danger');
              $cls = $bc[$e['status']] ?? 'secondary';
              ?>
              <span class="badge bg-<?= $cls ?>"><?= ucfirst($e['status']) ?></span>
              <?php if ($e['paid_date']): ?>
                <div style="font-size:.72rem;color:var(--ap-muted)">Paid <?= date('d M', strtotime($e['paid_date'])) ?></div>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot style="background:var(--ap-bg)">
          <tr>
            <td colspan="4" class="fw-bold text-end">Total</td>
            <td class="text-end fw-bold"><?= $cur ?> <?= number_format($summary['total']) ?></td>
            <td></td>
          </tr>
        </tfoot>
      </table>
    </div>
  <?php endif; ?>
</div>
<div class="mt-3" style="font-size:.8rem;color:var(--ap-muted)">
  <i class="fas fa-info-circle me-1"></i>
  Earnings are auto-generated when you log visits. The superadmin approves and marks them as paid.
</div>
