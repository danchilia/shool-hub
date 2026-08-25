<?php $cur = 'KSh'; ?>

<div class="d-flex flex-wrap gap-2 mb-3 align-items-center">
  <form method="get" class="d-flex gap-2">
    <select name="status" class="form-select form-select-sm" style="max-width:160px" onchange="this.form.submit()">
      <option value="">All Statuses</option>
      <option value="pending"  <?= $filter === 'pending'  ? 'selected' : '' ?>>Pending</option>
      <option value="approved" <?= $filter === 'approved' ? 'selected' : '' ?>>Approved</option>
      <option value="rejected" <?= $filter === 'rejected' ? 'selected' : '' ?>>Rejected</option>
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
          <th>Description</th>
          <th>School</th>
          <th class="text-end">Amount</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($expenses)): ?>
          <tr><td colspan="7" class="text-center text-muted py-4">No expense claims found.</td></tr>
        <?php endif; ?>
        <?php foreach ($expenses as $ex): ?>
        <tr>
          <td><?= date('d M Y', strtotime($ex['created_at'])) ?></td>
          <td>
            <a href="<?= base_url('agents/view/' . $ex['agent_id']) ?>">
              <?= htmlspecialchars($ex['first_name'] . ' ' . $ex['last_name']) ?>
            </a>
          </td>
          <td><?= htmlspecialchars($ex['description']) ?></td>
          <td><?= htmlspecialchars($ex['school_name'] ?: '—') ?></td>
          <td class="text-end fw-bold"><?= $cur ?> <?= number_format($ex['amount']) ?></td>
          <td>
            <?php $bc = array('pending'=>'warning text-dark','approved'=>'success','rejected'=>'danger'); ?>
            <span class="badge bg-<?= $bc[$ex['status']] ?? 'secondary' ?>"><?= ucfirst($ex['status']) ?></span>
            <?php if ($ex['review_note']): ?>
              <div style="font-size:.72rem;color:#aaa"><?= htmlspecialchars($ex['review_note']) ?></div>
            <?php endif; ?>
          </td>
          <td>
            <?php if ($ex['status'] === 'pending'): ?>
              <a href="<?= base_url('agents/approve_expense/' . $ex['id']) ?>"
                 onclick="return confirm('Approve this expense claim?')"
                 class="btn btn-xs btn-sm btn-outline-success" title="Approve">
                <i class="fas fa-check"></i>
              </a>
              <a href="<?= base_url('agents/reject_expense/' . $ex['id']) ?>"
                 onclick="return confirm('Reject this expense claim?')"
                 class="btn btn-xs btn-sm btn-outline-danger ms-1" title="Reject">
                <i class="fas fa-times"></i>
              </a>
            <?php else: ?>
              <span class="text-muted small">—</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
