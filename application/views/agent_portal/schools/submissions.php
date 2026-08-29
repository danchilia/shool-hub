<div class="ap-card">
  <div class="ap-card-header">My School Submissions</div>
  <?php if (empty($submissions)): ?>
    <div class="ap-card-body text-center text-muted py-5">
      No submissions yet. When a school agrees to sign up, open the school record and click <strong>Submit for Setup</strong>.
    </div>
  <?php else: ?>
  <div class="table-responsive">
    <table class="table mb-0" style="font-size:.84rem">
      <thead style="background:var(--ap-bg)">
        <tr>
          <th>Date</th>
          <th>School</th>
          <th>Plan</th>
          <th>Admin Contact</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($submissions as $s): ?>
        <tr>
          <td style="white-space:nowrap"><?= date('d M Y', strtotime($s['submitted_at'])) ?></td>
          <td>
            <div style="font-weight:600"><?= htmlspecialchars($s['school_name']) ?></div>
            <div style="font-size:.76rem;color:var(--ap-muted)"><?= htmlspecialchars($s['county']) ?><?= $s['sub_county'] ? ', '.$s['sub_county'] : '' ?></div>
          </td>
          <td>
            <?php if ($s['plan_name']): ?>
              <span class="badge" style="background:var(--ap-navy2);font-size:.75rem"><?= htmlspecialchars($s['plan_name']) ?></span>
              <div style="font-size:.75rem;color:var(--ap-muted)"><?= ucfirst($s['billing_cycle']) ?></div>
            <?php else: ?>
              <span class="text-muted">—</span>
            <?php endif; ?>
          </td>
          <td>
            <div><?= htmlspecialchars($s['admin_name']) ?></div>
            <div style="font-size:.76rem;color:var(--ap-muted)"><?= htmlspecialchars($s['admin_phone']) ?></div>
          </td>
          <td>
            <?php
              $badges = array(
                'pending'  => 'warning',
                'reviewed' => 'info',
                'approved' => 'success',
                'rejected' => 'danger',
              );
              $b = $badges[$s['status']] ?? 'secondary';
            ?>
            <span class="badge bg-<?= $b ?>"><?= ucfirst($s['status']) ?></span>
            <?php if ($s['admin_notes']): ?>
              <div style="font-size:.75rem;color:var(--ap-muted);margin-top:3px"><?= htmlspecialchars($s['admin_notes']) ?></div>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>
