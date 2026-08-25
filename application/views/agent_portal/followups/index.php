<?php if (empty($followups)): ?>
  <div class="ap-card">
    <div class="ap-card-body text-center py-5">
      <i class="fas fa-check-circle fa-3x mb-3" style="color:var(--ap-green);opacity:.6"></i>
      <div class="fw-bold mb-1">All caught up!</div>
      <div class="text-muted" style="font-size:.875rem">No pending follow-ups.</div>
    </div>
  </div>
<?php else: ?>
  <div class="mb-3 text-muted" style="font-size:.85rem">
    <?= count($followups) ?> follow-up(s) pending
  </div>
  <div class="ap-card">
    <div class="table-responsive">
      <table class="table table-hover mb-0" style="font-size:.875rem">
        <thead style="background:var(--ap-bg)">
          <tr>
            <th>School</th>
            <th>Contact</th>
            <th>County</th>
            <th>Follow-up Date</th>
            <th>Notes from Last Visit</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($followups as $f):
            $overdue  = $f['next_followup_date'] < date('Y-m-d');
            $today    = $f['next_followup_date'] === date('Y-m-d');
          ?>
          <tr class="<?= $overdue ? 'table-danger' : ($today ? 'table-warning' : '') ?>">
            <td><strong><?= htmlspecialchars($f['school_name']) ?></strong></td>
            <td>
              <?= htmlspecialchars($f['principal_name'] ?: '—') ?>
              <?php if ($f['phone']): ?>
                <div class="text-muted" style="font-size:.75rem"><?= htmlspecialchars($f['phone']) ?></div>
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($f['county'] ?: '—') ?></td>
            <td>
              <?php if ($overdue): ?>
                <span class="badge bg-danger"><i class="fas fa-exclamation-circle me-1"></i>Overdue</span>
                <div style="font-size:.75rem;color:#e74c3c"><?= date('d M Y', strtotime($f['next_followup_date'])) ?></div>
              <?php elseif ($today): ?>
                <span class="badge bg-warning text-dark"><i class="fas fa-bell me-1"></i>Today</span>
              <?php else: ?>
                <?= date('d M Y', strtotime($f['next_followup_date'])) ?>
              <?php endif; ?>
            </td>
            <td style="max-width:200px">
              <div style="font-size:.8rem;color:var(--ap-muted)">
                <?= $f['notes'] ? htmlspecialchars(mb_strimwidth($f['notes'], 0, 80, '…')) : '—' ?>
              </div>
            </td>
            <td>
              <a href="<?= base_url('agent_portal/log_visit/' . $f['school_id']) ?>" class="btn btn-sm" style="background:var(--ap-green);color:#fff">
                <i class="fas fa-map-marker-alt me-1"></i>Log Visit
              </a>
              <a href="<?= base_url('agent_portal/view_school/' . $f['school_id']) ?>" class="btn btn-sm btn-outline-secondary ms-1">
                <i class="fas fa-eye"></i>
              </a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
<?php endif; ?>
