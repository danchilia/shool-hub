<?php $cur = 'KSh'; $s = $stats; ?>

<!-- Header -->
<div class="d-flex align-items-center gap-3 mb-4">
  <a href="<?= base_url('agents') ?>" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a>
  <div>
    <h5 class="mb-0"><?= htmlspecialchars($agent['first_name'] . ' ' . $agent['last_name']) ?></h5>
    <div class="text-muted small"><?= htmlspecialchars($agent['email']) ?> · <?= htmlspecialchars($agent['region'] ?: 'No region') ?></div>
  </div>
  <div class="ms-auto">
    <a href="<?= base_url('agents/edit/' . $agent['id']) ?>" class="btn btn-sm btn-outline-secondary">
      <i class="fas fa-edit me-1"></i>Edit
    </a>
    <a href="<?= base_url('agents/toggle/' . $agent['id']) ?>"
       onclick="return confirm('<?= $agent['active'] ? 'Deactivate' : 'Activate' ?> this agent?')"
       class="btn btn-sm <?= $agent['active'] ? 'btn-outline-danger' : 'btn-outline-success' ?> ms-1">
      <?= $agent['active'] ? 'Deactivate' : 'Activate' ?>
    </a>
  </div>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
  <div class="col-6 col-md-2"><div class="card border-0 shadow-sm text-center py-3"><div class="fs-5 fw-bold"><?= $s['total_schools'] ?></div><div class="small text-muted">Schools</div></div></div>
  <div class="col-6 col-md-2"><div class="card border-0 shadow-sm text-center py-3"><div class="fs-5 fw-bold"><?= $s['total_visits'] ?></div><div class="small text-muted">Visits</div></div></div>
  <div class="col-6 col-md-2"><div class="card border-0 shadow-sm text-center py-3"><div class="fs-5 fw-bold text-success"><?= $s['closed_won'] ?></div><div class="small text-muted">Conversions</div></div></div>
  <div class="col-6 col-md-2"><div class="card border-0 shadow-sm text-center py-3"><div class="fs-5 fw-bold"><?= $s['conversion_rate'] ?>%</div><div class="small text-muted">Conv. Rate</div></div></div>
  <div class="col-6 col-md-2"><div class="card border-0 shadow-sm text-center py-3"><div class="fs-5 fw-bold text-warning"><?= $cur ?> <?= number_format($s['total_earned']) ?></div><div class="small text-muted">Total Earned</div></div></div>
  <div class="col-6 col-md-2"><div class="card border-0 shadow-sm text-center py-3"><div class="fs-5 fw-bold text-primary"><?= $cur ?> <?= number_format($s['total_paid']) ?></div><div class="small text-muted">Paid Out</div></div></div>
</div>

<div class="row g-4">
  <!-- Schools table -->
  <div class="col-lg-6">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white fw-bold d-flex justify-content-between">
        Schools
        <a href="<?= base_url('agents/all_schools?agent_id=' . $agent['id']) ?>" class="btn btn-xs btn-sm btn-outline-secondary">All</a>
      </div>
      <div class="table-responsive">
        <table class="table table-hover mb-0" style="font-size:.82rem">
          <thead class="table-light">
            <tr><th>School</th><th>County</th><th>Status</th></tr>
          </thead>
          <tbody>
            <?php foreach (array_slice($schools, 0, 8) as $sch): ?>
            <tr>
              <td><?= htmlspecialchars($sch['school_name']) ?></td>
              <td><?= htmlspecialchars($sch['county'] ?: '—') ?></td>
              <td><span class="badge" style="font-size:.68rem;<?= $sch['status']==='closed_won'?'background:#27ae60':($sch['status']==='closed_lost'?'background:#e74c3c':'background:#aaa') ?>">
                <?= ucfirst(str_replace('_',' ',$sch['status'])) ?>
              </span></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($schools)): ?><tr><td colspan="3" class="text-center text-muted py-3">No schools yet</td></tr><?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Earnings table -->
  <div class="col-lg-6">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white fw-bold d-flex justify-content-between">
        Earnings
        <a href="<?= base_url('agents/earnings?agent_id=' . $agent['id']) ?>" class="btn btn-xs btn-sm btn-outline-secondary">Manage</a>
      </div>
      <div class="table-responsive">
        <table class="table table-hover mb-0" style="font-size:.82rem">
          <thead class="table-light">
            <tr><th>Date</th><th>Type</th><th class="text-end">Amount</th><th>Status</th></tr>
          </thead>
          <tbody>
            <?php foreach (array_slice($earnings, 0, 8) as $e): ?>
            <tr>
              <td><?= date('d M', strtotime($e['created_at'])) ?></td>
              <td><?= $e['type'] === 'commission' ? '<span class="badge bg-success">Comm.</span>' : '<span class="badge bg-info text-dark">Visit</span>' ?></td>
              <td class="text-end"><?= $cur ?> <?= number_format($e['amount']) ?></td>
              <td>
                <?php $bc = array('pending'=>'warning text-dark','approved'=>'primary','paid'=>'success','rejected'=>'danger'); ?>
                <span class="badge bg-<?= $bc[$e['status']] ?? 'secondary' ?>" style="font-size:.68rem"><?= ucfirst($e['status']) ?></span>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($earnings)): ?><tr><td colspan="4" class="text-center text-muted py-3">No earnings yet</td></tr><?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Recent visits -->
  <div class="col-12">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white fw-bold">Recent Visits</div>
      <div class="table-responsive">
        <table class="table table-hover mb-0" style="font-size:.82rem">
          <thead class="table-light">
            <tr><th>Date</th><th>School</th><th>Type</th><th>Interest</th><th>Outcome</th><th>Follow-up</th></tr>
          </thead>
          <tbody>
            <?php foreach ($visits as $v): ?>
            <tr>
              <td><?= date('d M Y', strtotime($v['visit_date'])) ?></td>
              <td><?= htmlspecialchars($v['school_name']) ?></td>
              <td><?= ucfirst($v['visit_type']) ?></td>
              <td><span style="font-size:.72rem;padding:2px 7px;border-radius:10px;<?= $v['interest_level']==='hot'?'background:#fadbd8;color:#922b21':($v['interest_level']==='warm'?'background:#fef9e7;color:#d4a017':'background:#f2f3f4;color:#666') ?>"><?= ucfirst($v['interest_level']) ?></span></td>
              <td><?= ucfirst(str_replace('_',' ',$v['outcome'])) ?></td>
              <td><?= $v['next_followup_date'] ? date('d M', strtotime($v['next_followup_date'])) : '—' ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($visits)): ?><tr><td colspan="6" class="text-center text-muted py-3">No visits logged</td></tr><?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
