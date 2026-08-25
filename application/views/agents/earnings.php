<?php $cur = 'KSh'; ?>

<div class="d-flex flex-wrap gap-2 mb-3 align-items-center">
  <form method="get" class="d-flex gap-2 flex-wrap align-items-center">
    <select name="status" class="form-select form-select-sm" style="max-width:160px" onchange="this.form.submit()">
      <option value="">All Statuses</option>
      <option value="pending"  <?= $filter === 'pending'  ? 'selected' : '' ?>>Pending</option>
      <option value="approved" <?= $filter === 'approved' ? 'selected' : '' ?>>Approved</option>
      <option value="paid"     <?= $filter === 'paid'     ? 'selected' : '' ?>>Paid</option>
      <option value="rejected" <?= $filter === 'rejected' ? 'selected' : '' ?>>Rejected</option>
    </select>
    <select name="agent_id" class="form-select form-select-sm" style="max-width:200px" onchange="this.form.submit()">
      <option value="">All Agents</option>
      <?php foreach ($agents as $ag): ?>
        <option value="<?= $ag['id'] ?>" <?= $agent_filter == $ag['id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($ag['first_name'] . ' ' . $ag['last_name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </form>
  <a href="<?= base_url('agents') ?>" class="btn btn-sm btn-outline-secondary ms-auto">← Back to Agents</a>
</div>

<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table table-hover mb-0" style="font-size:.875rem">
      <thead class="table-light">
        <tr>
          <th>Date</th>
          <th>Agent</th>
          <th>Type</th>
          <th>School</th>
          <th>Description</th>
          <th class="text-end">Amount</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($earnings)): ?>
        <tr><td colspan="8" class="text-center text-muted py-4">No earnings records found.</td></tr>
        <?php endif; ?>
        <?php foreach ($earnings as $e): ?>
        <tr>
          <td><?= date('d M Y', strtotime($e['created_at'])) ?></td>
          <td>
            <a href="<?= base_url('agents/view/' . $e['agent_id']) ?>">
              <?= htmlspecialchars($e['first_name'] . ' ' . $e['last_name']) ?>
            </a>
          </td>
          <td>
            <?= $e['type'] === 'commission'
              ? '<span class="badge bg-success">Commission</span>'
              : '<span class="badge bg-info text-dark">Visit Fee</span>' ?>
          </td>
          <td><?= htmlspecialchars($e['school_name'] ?: '—') ?></td>
          <td class="text-muted"><?= htmlspecialchars($e['description']) ?></td>
          <td class="text-end fw-bold"><?= $cur ?> <?= number_format($e['amount']) ?></td>
          <td>
            <?php
              $bc = array('pending'=>'warning text-dark','approved'=>'primary','paid'=>'success','rejected'=>'danger');
            ?>
            <span class="badge bg-<?= $bc[$e['status']] ?? 'secondary' ?>"><?= ucfirst($e['status']) ?></span>
            <?php if ($e['paid_date']): ?>
              <div style="font-size:.72rem;color:#aaa"><?= date('d M', strtotime($e['paid_date'])) ?></div>
            <?php endif; ?>
          </td>
          <td>
            <?php if ($e['status'] === 'pending'): ?>
              <a href="<?= base_url('agents/approve_earning/' . $e['id']) ?>" class="btn btn-xs btn-sm btn-outline-success" title="Approve">
                <i class="fas fa-check"></i>
              </a>
              <a href="<?= base_url('agents/reject_earning/' . $e['id']) ?>"
                 onclick="return confirm('Reject this earning?')"
                 class="btn btn-xs btn-sm btn-outline-danger ms-1" title="Reject">
                <i class="fas fa-times"></i>
              </a>
            <?php elseif ($e['status'] === 'approved'): ?>
              <a href="<?= base_url('agents/mark_paid/' . $e['id']) ?>"
                 onclick="return confirm('Mark KSh <?= number_format($e['amount']) ?> as paid to <?= htmlspecialchars($e['first_name']) ?>?')"
                 class="btn btn-xs btn-sm btn-success" title="Mark Paid">
                <i class="fas fa-money-bill me-1"></i>Mark Paid
              </a>
            <?php else: ?>
              <span class="text-muted small">—</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
      <?php if (!empty($earnings)): ?>
      <tfoot class="table-light">
        <tr>
          <td colspan="5" class="fw-bold text-end">Shown Total</td>
          <td class="text-end fw-bold"><?= $cur ?> <?= number_format(array_sum(array_column($earnings, 'amount'))) ?></td>
          <td colspan="2"></td>
        </tr>
      </tfoot>
      <?php endif; ?>
    </table>
  </div>
</div>
