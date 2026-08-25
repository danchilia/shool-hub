<?php $s = $stats; $cur = 'KSh'; ?>

<!-- Stats row -->
<div class="row g-3 mb-4">
  <div class="col-6 col-md-2">
    <div class="card border-0 shadow-sm text-center py-3">
      <div class="fs-4 fw-bold"><?= $s['total_agents'] ?></div>
      <div class="text-muted small">Agents</div>
    </div>
  </div>
  <div class="col-6 col-md-2">
    <div class="card border-0 shadow-sm text-center py-3">
      <div class="fs-4 fw-bold"><?= $s['total_schools'] ?></div>
      <div class="text-muted small">Prospects</div>
    </div>
  </div>
  <div class="col-6 col-md-2">
    <div class="card border-0 shadow-sm text-center py-3">
      <div class="fs-4 fw-bold"><?= $s['total_visits'] ?></div>
      <div class="text-muted small">Total Visits</div>
    </div>
  </div>
  <div class="col-6 col-md-2">
    <div class="card border-0 shadow-sm text-center py-3">
      <div class="fs-4 fw-bold text-success"><?= $s['total_won'] ?></div>
      <div class="text-muted small">Conversions</div>
    </div>
  </div>
  <div class="col-6 col-md-2">
    <div class="card border-0 shadow-sm text-center py-3">
      <div class="fs-4 fw-bold text-warning"><?= $cur ?> <?= number_format($s['pending_payout']) ?></div>
      <div class="text-muted small">Pending Payout</div>
    </div>
  </div>
  <div class="col-6 col-md-2">
    <div class="card border-0 shadow-sm text-center py-3">
      <div class="fs-4 fw-bold text-primary"><?= $cur ?> <?= number_format($s['total_commission']) ?></div>
      <div class="text-muted small">Total Commission</div>
    </div>
  </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h6 class="mb-0">Field Agents</h6>
  <div class="d-flex gap-2">
    <a href="<?= base_url('agents/all_schools') ?>" class="btn btn-sm btn-outline-secondary">
      <i class="fas fa-school me-1"></i>All Prospects
    </a>
    <a href="<?= base_url('agents/earnings') ?>" class="btn btn-sm btn-outline-warning">
      <i class="fas fa-wallet me-1"></i>Earnings
    </a>
    <a href="<?= base_url('dck_plans') ?>" class="btn btn-sm btn-outline-info">
      <i class="fas fa-tags me-1"></i>Plans
    </a>
    <a href="<?= base_url('agents/add') ?>" class="btn btn-sm btn-primary">
      <i class="fas fa-plus me-1"></i>Add Agent
    </a>
  </div>
</div>

<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table table-hover mb-0">
      <thead class="table-light">
        <tr>
          <th>Agent</th>
          <th>Phone</th>
          <th>Region</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($agents)): ?>
        <tr><td colspan="5" class="text-center text-muted py-4">No agents yet. <a href="<?= base_url('agents/add') ?>">Add the first agent.</a></td></tr>
        <?php endif; ?>
        <?php foreach ($agents as $ag): ?>
        <tr>
          <td>
            <strong><?= htmlspecialchars($ag['first_name'] . ' ' . $ag['last_name']) ?></strong>
            <div class="text-muted small"><?= htmlspecialchars($ag['email']) ?></div>
          </td>
          <td><?= htmlspecialchars($ag['phone'] ?: '—') ?></td>
          <td><?= htmlspecialchars($ag['region'] ?: '—') ?></td>
          <td>
            <?php if ($ag['active']): ?>
              <span class="badge bg-success">Active</span>
            <?php else: ?>
              <span class="badge bg-secondary">Inactive</span>
            <?php endif; ?>
          </td>
          <td>
            <a href="<?= base_url('agents/view/' . $ag['id']) ?>" class="btn btn-xs btn-sm btn-outline-primary" title="View">
              <i class="fas fa-eye"></i>
            </a>
            <a href="<?= base_url('agents/edit/' . $ag['id']) ?>" class="btn btn-xs btn-sm btn-outline-secondary ms-1" title="Edit">
              <i class="fas fa-edit"></i>
            </a>
            <a href="<?= base_url('agents/toggle/' . $ag['id']) ?>"
               onclick="return confirm('<?= $ag['active'] ? 'Deactivate' : 'Activate' ?> this agent?')"
               class="btn btn-xs btn-sm <?= $ag['active'] ? 'btn-outline-danger' : 'btn-outline-success' ?> ms-1" title="<?= $ag['active'] ? 'Deactivate' : 'Activate' ?>">
              <i class="fas fa-<?= $ag['active'] ? 'ban' : 'check' ?>"></i>
            </a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
