<?php $cur = 'KSh'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h6 class="mb-0">DCK Subscription Plans</h6>
  <div class="d-flex gap-2">
    <a href="<?= base_url('agents') ?>" class="btn btn-sm btn-outline-secondary">← Agents</a>
    <a href="<?= base_url('dck_plans/add') ?>" class="btn btn-sm btn-primary">
      <i class="fas fa-plus me-1"></i>Add Plan
    </a>
  </div>
</div>

<div class="row g-3">
  <?php foreach ($plans as $p): ?>
  <div class="col-md-4">
    <div class="card border-0 shadow-sm h-100" style="<?= !$p['active'] ? 'opacity:.6' : '' ?>">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <div>
            <h6 class="mb-0 fw-bold"><?= htmlspecialchars($p['name']) ?></h6>
            <div class="text-muted small"><?= htmlspecialchars($p['description'] ?: '') ?></div>
          </div>
          <?php if ($p['active']): ?>
            <span class="badge bg-success">Active</span>
          <?php else: ?>
            <span class="badge bg-secondary">Inactive</span>
          <?php endif; ?>
        </div>
        <hr>
        <div class="mb-1 d-flex justify-content-between" style="font-size:.875rem">
          <span class="text-muted">Plan Price</span>
          <strong><?= $cur ?> <?= number_format($p['price']) ?></strong>
        </div>
        <div class="mb-1 d-flex justify-content-between" style="font-size:.875rem">
          <span class="text-muted">Visit Fee (per visit)</span>
          <strong style="color:#e67e22"><?= $cur ?> <?= number_format($p['visit_fee']) ?></strong>
        </div>
        <div class="mb-3 d-flex justify-content-between" style="font-size:.875rem">
          <span class="text-muted">Commission (on signup)</span>
          <strong style="color:#27ae60"><?= $cur ?> <?= number_format($p['commission_amount']) ?></strong>
        </div>
        <div class="d-flex gap-2">
          <a href="<?= base_url('dck_plans/edit/' . $p['id']) ?>" class="btn btn-sm btn-outline-secondary flex-grow-1">
            <i class="fas fa-edit me-1"></i>Edit
          </a>
          <a href="<?= base_url('dck_plans/toggle/' . $p['id']) ?>"
             onclick="return confirm('<?= $p['active'] ? 'Deactivate' : 'Activate' ?> this plan?')"
             class="btn btn-sm <?= $p['active'] ? 'btn-outline-danger' : 'btn-outline-success' ?>">
            <?= $p['active'] ? 'Deactivate' : 'Activate' ?>
          </a>
        </div>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
  <?php if (empty($plans)): ?>
    <div class="col-12">
      <div class="card border-0 shadow-sm text-center py-5 text-muted">
        No plans defined yet. <a href="<?= base_url('dck_plans/add') ?>">Add the first plan.</a>
      </div>
    </div>
  <?php endif; ?>
</div>

<div class="mt-4 card border-0 shadow-sm p-3" style="background:#f8f9fa;max-width:600px">
  <div class="small text-muted">
    <i class="fas fa-info-circle me-1"></i>
    <strong>Visit Fee</strong> is paid to the agent for every qualified visit, regardless of whether the school signs up.<br>
    <strong>Commission</strong> is paid when a school signs up for this plan. Earnings are auto-created when an agent logs a visit with outcome "Signed Up".
  </div>
</div>
