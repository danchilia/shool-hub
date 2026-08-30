<?php
$statuses = array(
  ''              => 'All Statuses',
  'lead'          => 'Lead',
  'visited'       => 'Visited',
  'demo_done'     => 'Demo Done',
  'proposal_sent' => 'Proposal Sent',
  'follow_up'     => 'Follow-up',
  'closed_won'    => 'Won',
  'closed_lost'   => 'Lost',
);
$pip_colors = array(
  'lead'          => '#aaa',
  'visited'       => '#d4a017',
  'demo_done'     => '#1e8449',
  'proposal_sent' => '#7d3c98',
  'follow_up'     => '#ca6f1e',
  'closed_won'    => '#186a3b',
  'closed_lost'   => '#922b21',
);
?>

<div class="d-flex flex-wrap gap-2 mb-3 align-items-center">
  <form method="get" class="d-flex gap-2 flex-wrap">
    <select name="agent_id" class="form-select form-select-sm" style="max-width:200px" onchange="this.form.submit()">
      <option value="">All Agents</option>
      <?php foreach ($agents as $ag): ?>
        <option value="<?= $ag['id'] ?>" <?= $agent_filter == $ag['id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($ag['first_name'] . ' ' . $ag['last_name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
    <select name="status" class="form-select form-select-sm" style="max-width:160px" onchange="this.form.submit()">
      <?php foreach ($statuses as $val => $lbl): ?>
        <option value="<?= $val ?>" <?= $status_filter === $val ? 'selected' : '' ?>><?= $lbl ?></option>
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
          <th>School</th>
          <th>Agent</th>
          <th>County</th>
          <th>Students</th>
          <th>Interest</th>
          <th>Status</th>
          <th>Plan</th>
          <th>GPS</th>
          <th>Added</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($schools)): ?>
          <tr><td colspan="9" class="text-center text-muted py-4">No schools found.</td></tr>
        <?php endif; ?>
        <?php foreach ($schools as $sch): ?>
        <tr>
          <td>
            <strong><?= htmlspecialchars($sch['school_name']) ?></strong>
            <?php if ($sch['phone']): ?>
              <div class="text-muted" style="font-size:.75rem"><?= htmlspecialchars($sch['phone']) ?></div>
            <?php endif; ?>
          </td>
          <td><?= htmlspecialchars($sch['first_name'] . ' ' . $sch['last_name']) ?></td>
          <td><?= htmlspecialchars($sch['county'] ?: '—') ?></td>
          <td><?= $sch['num_students'] ? number_format($sch['num_students']) : '—' ?></td>
          <td>
            <?php $ic = array('hot'=>'#fadbd8;color:#922b21','warm'=>'#fef9e7;color:#d4a017','cold'=>'#eaf4fb;color:#1a5276','unknown'=>'#f2f3f4;color:#666'); ?>
            <span style="font-size:.72rem;padding:2px 8px;border-radius:10px;background:<?= $ic[$sch['interest_level']] ?? '#eee;color:#666' ?>">
              <?= ucfirst($sch['interest_level']) ?>
            </span>
          </td>
          <td>
            <span style="font-size:.72rem;padding:2px 8px;border-radius:10px;background:<?= isset($pip_colors[$sch['status']]) ? $pip_colors[$sch['status']] . '22' : '#eee' ?>;color:<?= $pip_colors[$sch['status']] ?? '#666' ?>">
              <?= ucfirst(str_replace('_',' ',$sch['status'])) ?>
            </span>
          </td>
          <td><?= $sch['plan_name'] ? htmlspecialchars($sch['plan_name']) : '—' ?></td>
          <td>
            <?php if (!empty($sch['lat']) && !empty($sch['lng'])): ?>
              <a href="https://www.google.com/maps?q=<?= $sch['lat'] ?>,<?= $sch['lng'] ?>" target="_blank"
                 title="<?= $sch['lat'] ?>, <?= $sch['lng'] ?><?= !empty($sch['gps_accuracy']) ? ' (±'.round($sch['gps_accuracy']).'m)' : '' ?>"
                 style="color:#2b6cb0;font-size:.8rem">
                <i class="fas fa-map-marker-alt"></i> Map
              </a>
            <?php else: ?>
              <span class="text-muted" style="font-size:.75rem">—</span>
            <?php endif; ?>
          </td>
          <td><?= date('d M Y', strtotime($sch['created_at'])) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<div class="mt-2 text-muted small"><?= count($schools) ?> school(s)</div>
