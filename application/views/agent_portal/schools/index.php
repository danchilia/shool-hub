<?php
$statuses = array(
  ''             => 'All',
  'lead'         => 'Lead',
  'visited'      => 'Visited',
  'demo_done'    => 'Demo Done',
  'proposal_sent'=> 'Proposal Sent',
  'follow_up'    => 'Follow-up',
  'closed_won'   => 'Won',
  'closed_lost'  => 'Lost',
);
?>

<div class="d-flex flex-wrap align-items-center gap-2 mb-3">
  <form method="get" class="d-flex gap-2 flex-wrap">
    <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Search school…"
           class="form-control form-control-sm" style="max-width:200px">
    <select name="status" class="form-select form-select-sm" style="max-width:160px" onchange="this.form.submit()">
      <?php foreach ($statuses as $val => $lbl): ?>
        <option value="<?= $val ?>" <?= $status === $val ? 'selected' : '' ?>><?= $lbl ?></option>
      <?php endforeach; ?>
    </select>
    <button class="btn btn-sm btn-outline-secondary">Search</button>
    <?php if ($status || $search): ?>
      <a href="<?= base_url('agent_portal/schools') ?>" class="btn btn-sm btn-outline-danger">Clear</a>
    <?php endif; ?>
  </form>
  <a href="<?= base_url('agent_portal/add_school') ?>" class="btn btn-sm ms-auto" style="background:var(--ap-navy);color:#fff">
    <i class="fas fa-plus me-1"></i>Add School
  </a>
</div>

<?php if (empty($schools)): ?>
  <div class="ap-card">
    <div class="ap-card-body text-center py-5 text-muted">
      <i class="fas fa-school fa-3x mb-3" style="opacity:.3"></i>
      <div>No schools found.
        <?php if (!$status && !$search): ?>
          <a href="<?= base_url('agent_portal/add_school') ?>">Add your first school lead.</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
<?php else: ?>
  <div class="ap-card">
    <div class="table-responsive">
      <table class="table table-hover mb-0" style="font-size:.875rem">
        <thead style="background:var(--ap-bg)">
          <tr>
            <th>School</th>
            <th>Principal</th>
            <th>County</th>
            <th>Interest</th>
            <th>Pipeline</th>
            <th>Plan</th>
            <th>Added</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($schools as $sch): ?>
          <tr>
            <td>
              <strong><?= htmlspecialchars($sch['school_name']) ?></strong>
              <?php if ($sch['phone']): ?>
                <div class="text-muted" style="font-size:.75rem"><i class="fas fa-phone fa-xs me-1"></i><?= htmlspecialchars($sch['phone']) ?></div>
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($sch['principal_name'] ?: '—') ?></td>
            <td><?= htmlspecialchars($sch['county'] ?: '—') ?></td>
            <td><span class="pipeline-badge int-<?= $sch['interest_level'] ?>"><?= ucfirst($sch['interest_level']) ?></span></td>
            <td><span class="pipeline-badge pip-<?= $sch['status'] ?>"><?= ucfirst(str_replace('_',' ',$sch['status'])) ?></span></td>
            <td><?= $sch['plan_name'] ? htmlspecialchars($sch['plan_name']) : '—' ?></td>
            <td><?= date('d M Y', strtotime($sch['created_at'])) ?></td>
            <td>
              <a href="<?= base_url('agent_portal/view_school/' . $sch['id']) ?>" class="btn btn-xs btn-sm btn-outline-primary" title="View">
                <i class="fas fa-eye"></i>
              </a>
              <a href="<?= base_url('agent_portal/log_visit/' . $sch['id']) ?>" class="btn btn-xs btn-sm btn-outline-success ms-1" title="Log Visit">
                <i class="fas fa-map-marker-alt"></i>
              </a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <div class="mt-2 text-muted" style="font-size:.8rem"><?= count($schools) ?> school(s)</div>
<?php endif; ?>
