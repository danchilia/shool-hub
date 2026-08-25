<?php
$s = $stats;
$currency = 'KSh';
?>

<!-- Stat row -->
<div class="row g-3 mb-4">
  <div class="col-6 col-md-3">
    <div class="stat-card">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <div class="stat-val"><?= $s['total_schools'] ?></div>
          <div class="stat-lbl">Total Schools</div>
        </div>
        <div class="stat-icon" style="background:#e8f4fd"><i class="fas fa-school" style="color:#2980b9"></i></div>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat-card">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <div class="stat-val"><?= $s['total_visits'] ?></div>
          <div class="stat-lbl">Total Visits</div>
        </div>
        <div class="stat-icon" style="background:#eafaf1"><i class="fas fa-map-marker-alt" style="color:#27ae60"></i></div>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat-card">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <div class="stat-val"><?= $s['closed_won'] ?></div>
          <div class="stat-lbl">Conversions <small class="text-muted">(<?= $s['conversion_rate'] ?>%)</small></div>
        </div>
        <div class="stat-icon" style="background:#d5f5e3"><i class="fas fa-handshake" style="color:#186a3b"></i></div>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat-card">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <div class="stat-val"><?= $currency ?> <?= number_format($s['total_earned']) ?></div>
          <div class="stat-lbl">Total Earned</div>
        </div>
        <div class="stat-icon" style="background:#fef9e7"><i class="fas fa-wallet" style="color:#d4a017"></i></div>
      </div>
    </div>
  </div>
</div>

<!-- Second stat row -->
<div class="row g-3 mb-4">
  <div class="col-6 col-md-3">
    <div class="stat-card">
      <div class="stat-val text-warning"><?= $s['pending'] ?></div>
      <div class="stat-lbl">Active Pipeline</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat-card">
      <div class="stat-val" style="color:var(--ap-red)"><?= $s['overdue_followups'] ?></div>
      <div class="stat-lbl">Overdue Follow-ups</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat-card">
      <div class="stat-val text-success"><?= $currency ?> <?= number_format($s['total_paid']) ?></div>
      <div class="stat-lbl">Paid to You</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat-card">
      <div class="stat-val" style="color:#8e44ad"><?= $currency ?> <?= number_format($s['total_earned'] - $s['total_paid']) ?></div>
      <div class="stat-lbl">Pending Payout</div>
    </div>
  </div>
</div>

<div class="row g-4">
  <!-- Upcoming follow-ups -->
  <div class="col-md-6">
    <div class="ap-card">
      <div class="ap-card-header">
        <span><i class="fas fa-calendar-check me-2" style="color:var(--ap-accent)"></i>Upcoming Follow-ups</span>
        <a href="<?= base_url('agent_portal/followups') ?>" class="btn btn-sm btn-outline-secondary">View All</a>
      </div>
      <?php if (empty($followups)): ?>
        <div class="p-4 text-center text-muted">
          <i class="fas fa-check-circle fa-2x mb-2" style="color:var(--ap-green)"></i>
          <div>No pending follow-ups</div>
        </div>
      <?php else: ?>
        <div class="list-group list-group-flush">
          <?php foreach (array_slice($followups, 0, 5) as $f):
            $overdue = $f['next_followup_date'] < date('Y-m-d');
          ?>
          <a href="<?= base_url('agent_portal/view_school/' . $f['school_id']) ?>"
             class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
             style="font-size:.875rem">
            <div>
              <?php if ($overdue): ?><span class="overdue-dot me-1"></span><?php endif; ?>
              <strong><?= htmlspecialchars($f['school_name']) ?></strong>
              <div class="text-muted" style="font-size:.78rem">
                <?= htmlspecialchars($f['principal_name'] ?: '') ?> · <?= htmlspecialchars($f['county'] ?: '') ?>
              </div>
            </div>
            <span class="badge <?= $overdue ? 'bg-danger' : 'bg-warning text-dark' ?>" style="font-size:.72rem">
              <?= date('d M', strtotime($f['next_followup_date'])) ?>
            </span>
          </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Recent visits -->
  <div class="col-md-6">
    <div class="ap-card">
      <div class="ap-card-header">
        <span><i class="fas fa-history me-2" style="color:var(--ap-accent)"></i>Recent Visits</span>
        <a href="<?= base_url('agent_portal/schools') ?>" class="btn btn-sm btn-outline-secondary">All Schools</a>
      </div>
      <?php if (empty($recent_visits)): ?>
        <div class="p-4 text-center text-muted">
          <i class="fas fa-map-marker-alt fa-2x mb-2"></i>
          <div>No visits logged yet</div>
        </div>
      <?php else: ?>
        <div class="list-group list-group-flush">
          <?php foreach ($recent_visits as $v): ?>
          <a href="<?= base_url('agent_portal/view_school/' . $v['school_id']) ?>"
             class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
             style="font-size:.875rem">
            <div>
              <strong><?= htmlspecialchars($v['school_name']) ?></strong>
              <div class="text-muted" style="font-size:.78rem">
                <?= ucfirst($v['visit_type']) ?> · <?= date('d M Y', strtotime($v['visit_date'])) ?>
              </div>
            </div>
            <span class="pipeline-badge pip-<?= $v['outcome'] === 'signed_up' ? 'closed_won' : ($v['outcome'] === 'needs_followup' ? 'follow_up' : 'visited') ?>">
              <?= ucfirst(str_replace('_',' ',$v['outcome'])) ?>
            </span>
          </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Quick actions -->
<div class="mt-4">
  <a href="<?= base_url('agent_portal/add_school') ?>" class="btn btn-sm me-2" style="background:var(--ap-navy);color:#fff">
    <i class="fas fa-plus me-1"></i>Add School Lead
  </a>
  <a href="<?= base_url('agent_portal/earnings') ?>" class="btn btn-sm btn-outline-secondary me-2">
    <i class="fas fa-wallet me-1"></i>View Earnings
  </a>
  <a href="<?= base_url('agent_portal/expenses') ?>" class="btn btn-sm btn-outline-secondary">
    <i class="fas fa-receipt me-1"></i>Claim Expense
  </a>
</div>
