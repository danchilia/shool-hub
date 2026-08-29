<?php $sch = $school; ?>

<div class="d-flex flex-wrap align-items-center gap-2 mb-3">
  <a href="<?= base_url('agent_portal/schools') ?>" class="btn btn-sm btn-outline-secondary">
    <i class="fas fa-arrow-left me-1"></i>Back
  </a>
  <a href="<?= base_url('agent_portal/log_visit/' . $sch['id']) ?>" class="btn btn-sm ms-auto" style="background:var(--ap-green);color:#fff">
    <i class="fas fa-map-marker-alt me-1"></i>Log Visit
  </a>
  <?php if ($sch['status'] === 'closed_won'): ?>
  <a href="<?= base_url('agent_portal/submit_school/' . $sch['id']) ?>" class="btn btn-sm" style="background:var(--ap-accent);color:#fff">
    <i class="fas fa-paper-plane me-1"></i>Submit for Setup
  </a>
  <?php endif; ?>
</div>

<div class="row g-4">
  <!-- School card -->
  <div class="col-md-5">
    <div class="ap-card">
      <div class="ap-card-header">
        School Details
        <span class="pipeline-badge pip-<?= $sch['status'] ?>"><?= ucfirst(str_replace('_',' ',$sch['status'])) ?></span>
      </div>
      <div class="ap-card-body" style="font-size:.875rem">
        <div class="mb-2"><strong><?= htmlspecialchars($sch['school_name']) ?></strong></div>
        <?php if ($sch['principal_name']): ?>
        <div class="text-muted mb-1"><i class="fas fa-user fa-xs me-1"></i><?= htmlspecialchars($sch['principal_name']) ?></div>
        <?php endif; ?>
        <?php if ($sch['phone']): ?>
        <div class="text-muted mb-1"><i class="fas fa-phone fa-xs me-1"></i><?= htmlspecialchars($sch['phone']) ?></div>
        <?php endif; ?>
        <?php if ($sch['email']): ?>
        <div class="text-muted mb-1"><i class="fas fa-envelope fa-xs me-1"></i><?= htmlspecialchars($sch['email']) ?></div>
        <?php endif; ?>
        <?php if ($sch['county']): ?>
        <div class="text-muted mb-1"><i class="fas fa-map-pin fa-xs me-1"></i><?= htmlspecialchars($sch['county']) ?><?= $sch['sub_county'] ? ', ' . htmlspecialchars($sch['sub_county']) : '' ?></div>
        <?php endif; ?>
        <?php if ($sch['num_students']): ?>
        <div class="text-muted mb-1"><i class="fas fa-users fa-xs me-1"></i><?= number_format($sch['num_students']) ?> students</div>
        <?php endif; ?>
        <?php if ($sch['current_system']): ?>
        <div class="text-muted mb-1"><i class="fas fa-laptop fa-xs me-1"></i>Currently: <?= htmlspecialchars($sch['current_system']) ?></div>
        <?php endif; ?>
        <div class="mt-2">
          <span class="pipeline-badge int-<?= $sch['interest_level'] ?>">
            <i class="fas fa-fire fa-xs me-1"></i><?= ucfirst($sch['interest_level']) ?> interest
          </span>
        </div>
        <?php if ($sch['plan_name']): ?>
        <div class="mt-2 badge bg-success"><?= htmlspecialchars($sch['plan_name']) ?> Plan</div>
        <?php endif; ?>
        <?php if ($sch['notes']): ?>
        <hr>
        <div style="font-size:.8rem;color:var(--ap-muted)"><?= nl2br(htmlspecialchars($sch['notes'])) ?></div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Edit form -->
    <div class="ap-card mt-3">
      <div class="ap-card-header">Update School</div>
      <div class="ap-card-body">
        <form method="post" action="<?= base_url('agent_portal/view_school/' . $sch['id']) ?>">
          <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
          <input type="hidden" name="update_school" value="1">
          <div class="mb-2">
            <label class="form-label" style="font-size:.8rem">Contact Name</label>
            <input type="text" name="principal_name" class="form-control form-control-sm" value="<?= htmlspecialchars($sch['principal_name']) ?>">
          </div>
          <div class="mb-2">
            <label class="form-label" style="font-size:.8rem">Phone</label>
            <input type="text" name="phone" class="form-control form-control-sm" value="<?= htmlspecialchars($sch['phone']) ?>">
          </div>
          <div class="mb-2">
            <label class="form-label" style="font-size:.8rem">Email</label>
            <input type="email" name="email" class="form-control form-control-sm" value="<?= htmlspecialchars($sch['email']) ?>">
          </div>
          <div class="row g-2 mb-2">
            <div class="col-6">
              <label class="form-label" style="font-size:.8rem">Interest Level</label>
              <select name="interest_level" class="form-select form-select-sm">
                <?php foreach (array('unknown','cold','warm','hot') as $lv): ?>
                  <option value="<?= $lv ?>" <?= $sch['interest_level'] === $lv ? 'selected' : '' ?>><?= ucfirst($lv) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-6">
              <label class="form-label" style="font-size:.8rem">Pipeline Status</label>
              <select name="status" class="form-select form-select-sm" id="statusSel">
                <?php foreach (array('lead','visited','demo_done','proposal_sent','follow_up','closed_won','closed_lost') as $st): ?>
                  <option value="<?= $st ?>" <?= $sch['status'] === $st ? 'selected' : '' ?>><?= ucfirst(str_replace('_',' ',$st)) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div id="planRow" class="mb-2" style="<?= $sch['status'] === 'closed_won' ? '' : 'display:none' ?>">
            <label class="form-label" style="font-size:.8rem">Signed Plan</label>
            <select name="assigned_plan_id" class="form-select form-select-sm">
              <option value="">— select plan —</option>
              <?php foreach ($plans as $p): ?>
                <option value="<?= $p['id'] ?>" <?= $sch['assigned_plan_id'] == $p['id'] ? 'selected' : '' ?>><?= htmlspecialchars($p['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-2">
            <label class="form-label" style="font-size:.8rem">Notes</label>
            <textarea name="notes" class="form-control form-control-sm" rows="2"><?= htmlspecialchars($sch['notes']) ?></textarea>
          </div>
          <button class="ap-btn-primary" style="font-size:.8rem;padding:6px 16px">Save Changes</button>
        </form>
      </div>
    </div>
  </div>

  <!-- Visit history -->
  <div class="col-md-7">
    <div class="ap-card">
      <div class="ap-card-header">
        <span><i class="fas fa-history me-2"></i>Visit History</span>
        <span class="badge bg-secondary"><?= count($visits) ?></span>
      </div>
      <?php if (empty($visits)): ?>
        <div class="ap-card-body text-center text-muted py-4">
          No visits logged yet.
          <a href="<?= base_url('agent_portal/log_visit/' . $sch['id']) ?>">Log the first visit.</a>
        </div>
      <?php else: ?>
        <div class="list-group list-group-flush">
          <?php foreach ($visits as $v): ?>
          <div class="list-group-item" style="font-size:.855rem">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <strong><?= date('d M Y', strtotime($v['visit_date'])) ?></strong>
                <span class="text-muted ms-2"><?= ucfirst($v['visit_type']) ?></span>
                <span class="pipeline-badge int-<?= $v['interest_level'] ?> ms-2"><?= ucfirst($v['interest_level']) ?></span>
              </div>
              <span class="pipeline-badge pip-<?= $v['outcome'] === 'signed_up' ? 'closed_won' : ($v['outcome'] === 'needs_followup' ? 'follow_up' : ($v['outcome'] === 'not_interested' ? 'closed_lost' : 'visited')) ?>">
                <?= ucfirst(str_replace('_',' ',$v['outcome'])) ?>
              </span>
            </div>
            <?php if ($v['modules_demoed']): ?>
            <div class="text-muted mt-1" style="font-size:.78rem"><i class="fas fa-desktop fa-xs me-1"></i><?= htmlspecialchars($v['modules_demoed']) ?></div>
            <?php endif; ?>
            <?php if ($v['notes']): ?>
            <div class="mt-1" style="font-size:.8rem;color:var(--ap-muted)"><?= nl2br(htmlspecialchars($v['notes'])) ?></div>
            <?php endif; ?>
            <?php if ($v['next_followup_date']): ?>
            <div class="mt-1" style="font-size:.78rem">
              <i class="fas fa-calendar me-1 text-warning"></i>Follow-up: <strong><?= date('d M Y', strtotime($v['next_followup_date'])) ?></strong>
            </div>
            <?php endif; ?>
            <?php if ($v['plan_name']): ?>
            <div class="mt-1"><span class="badge bg-success" style="font-size:.72rem"><?= htmlspecialchars($v['plan_name']) ?> Plan signed</span></div>
            <?php endif; ?>
          </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<script>
document.getElementById('statusSel').addEventListener('change', function() {
  document.getElementById('planRow').style.display = this.value === 'closed_won' ? '' : 'none';
});
</script>
