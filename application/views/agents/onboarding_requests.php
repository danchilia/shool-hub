<?php if ($this->session->flashdata('msg')): ?>
<div class="alert alert-success alert-dismissible fade show mb-3">
  <?= $this->session->flashdata('msg') ?>
  <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<?php endif; ?>

<div class="d-flex flex-wrap align-items-center gap-2 mb-3">
  <h6 class="mb-0">School Onboarding Requests</h6>
  <?php if ($pending > 0): ?>
    <span class="badge bg-warning text-dark ms-2"><?= $pending ?> pending</span>
  <?php endif; ?>
  <div class="ms-auto d-flex gap-1">
    <?php foreach ([''=>'All','pending'=>'Pending','reviewed'=>'Reviewed','approved'=>'Approved','rejected'=>'Rejected'] as $v => $l): ?>
      <a href="<?= base_url('agents/onboarding_requests') . ($v ? '?status='.$v : '') ?>"
         class="btn btn-sm <?= $status === $v ? 'btn-primary' : 'btn-outline-secondary' ?>"><?= $l ?></a>
    <?php endforeach; ?>
  </div>
</div>

<section class="panel">
  <div class="panel-body p-0">
    <div class="table-responsive">
      <table class="table table-bordered table-hover table-condensed mb-none" style="font-size:.84rem">
        <thead>
          <tr>
            <th>Date</th>
            <th>School</th>
            <th>Location</th>
            <th>Agent</th>
            <th>Plan</th>
            <th>Students</th>
            <th>Admin Contact</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($requests)): ?>
            <tr><td colspan="9" class="text-center text-muted py-4">No requests found.</td></tr>
          <?php else: foreach ($requests as $r): ?>
          <tr>
            <td style="white-space:nowrap"><?= date('d M Y', strtotime($r['submitted_at'])) ?></td>
            <td>
              <strong><?= htmlspecialchars($r['school_name']) ?></strong><br>
              <small class="text-muted"><?= htmlspecialchars($r['school_type'] . ' · ' . $r['school_category']) ?></small><br>
              <?php if ($r['reg_number']): ?>
                <small class="text-muted">Reg: <?= htmlspecialchars($r['reg_number']) ?></small>
              <?php endif; ?>
            </td>
            <td>
              <?= htmlspecialchars($r['county']) ?><br>
              <small class="text-muted"><?= htmlspecialchars($r['sub_county']) ?></small>
            </td>
            <td><?= htmlspecialchars($r['agent_name'] ?: '—') ?></td>
            <td>
              <?= $r['plan_name'] ? '<span class="label label-info">'.htmlspecialchars($r['plan_name']).'</span>' : '—' ?>
              <?php if ($r['plan_name']): ?>
                <br><small class="text-muted"><?= ucfirst($r['billing_cycle']) ?> · KES <?= number_format($r['billing_cycle'] === 'yearly' ? $r['yearly_price'] : $r['monthly_price']) ?></small>
              <?php endif; ?>
            </td>
            <td><?= number_format($r['num_students']) ?></td>
            <td>
              <strong><?= htmlspecialchars($r['admin_name']) ?></strong><br>
              <small><?= htmlspecialchars($r['admin_phone']) ?></small><br>
              <small><?= htmlspecialchars($r['admin_email']) ?></small>
            </td>
            <td>
              <?php
                $cls = array('pending'=>'warning','reviewed'=>'info','approved'=>'success','rejected'=>'danger');
                $c = $cls[$r['status']] ?? 'default';
              ?>
              <span class="label label-<?= $c ?>"><?= ucfirst($r['status']) ?></span>
              <?php if ($r['admin_notes']): ?>
                <br><small class="text-muted"><?= htmlspecialchars($r['admin_notes']) ?></small>
              <?php endif; ?>
            </td>
            <td style="white-space:nowrap">
              <button type="button" class="btn btn-xs btn-outline-secondary mb-1" onclick="openModal(<?= $r['id'] ?>, '<?= htmlspecialchars($r['school_name'], ENT_QUOTES) ?>', '<?= $r['status'] ?>', '<?= addslashes($r['admin_notes']) ?>', <?= htmlspecialchars(json_encode($r), ENT_QUOTES) ?>)">
                <i class="fas fa-edit"></i> Review
              </button>
              <?php if (!empty($r['filled_form_path'])): ?>
              <a href="<?= base_url('agents/download_filled_form/' . $r['id']) ?>" class="btn btn-xs btn-outline-info mb-1" title="Download filled data collection form">
                <i class="fas fa-file-download"></i> Form
              </a>
              <?php endif; ?>
              <?php if ($r['status'] === 'approved' && empty($r['setup_completed_at'])): ?>
              <a href="<?= base_url('agents/complete_setup/' . $r['id']) ?>"
                 class="btn btn-xs btn-success mb-1"
                 onclick="return confirm('Mark setup as complete for <?= htmlspecialchars($r['school_name'], ENT_QUOTES) ?>? This will create the commission earning for the agent.')">
                <i class="fas fa-check-double"></i> Setup Complete
              </a>
              <?php elseif (!empty($r['setup_completed_at'])): ?>
              <span class="label label-success" title="Setup completed <?= date('d M Y', strtotime($r['setup_completed_at'])) ?>">
                <i class="fas fa-check"></i> Setup Done
              </span>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<!-- REVIEW MODAL -->
<div id="reviewModal" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Review Request</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="modalBody"></div>
      <div class="modal-footer">
        <?php echo form_open('agents/update_onboarding/0', array('id'=>'reviewForm')); ?>
          <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
          <div class="d-flex gap-2 align-items-center w-100">
            <select name="status" class="form-select form-select-sm" style="max-width:160px" id="modalStatus">
              <option value="pending">Pending</option>
              <option value="reviewed">Reviewed</option>
              <option value="approved">Approved</option>
              <option value="rejected">Rejected</option>
            </select>
            <input type="text" name="admin_notes" class="form-control form-control-sm" placeholder="Admin notes (optional)" id="modalNotes">
            <button type="submit" class="btn btn-sm btn-primary">Save</button>
          </div>
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
</div>

<script>
function openModal(id, name, status, notes, data) {
  document.getElementById('modalTitle').textContent = name;
  document.getElementById('reviewForm').action = '<?= base_url('agents/update_onboarding/') ?>' + id;
  document.getElementById('modalStatus').value = status;
  document.getElementById('modalNotes').value  = notes;

  var body = '';
  var rows = [
    ['School','school_name'],['Type','school_type'],['Category','school_category'],
    ['Reg No','reg_number'],['County','county'],['Sub-County','sub_county'],
    ['Ward','ward'],['Physical Address','physical_address'],['Postal','postal_address'],
    ['School Phone','school_phone'],['School Email','school_email'],['Website','school_website'],
    ['Principal','principal_name'],['Principal Phone','principal_phone'],['Principal Email','principal_email'],
    ['Students','num_students'],['Teaching Staff','num_teaching_staff'],['Non-Teaching Staff','num_non_teaching_staff'],
    ['Streams','num_streams'],['Admin Name','admin_name'],['Admin Phone','admin_phone'],['Admin Email','admin_email'],
    ['Notes','notes'],['Billing','billing_cycle']
  ];
  body = '<table class="table table-sm table-bordered" style="font-size:.82rem">';
  rows.forEach(function(r) {
    if (data[r[1]]) body += '<tr><th style="width:35%">' + r[0] + '</th><td>' + data[r[1]] + '</td></tr>';
  });
  body += '</table>';
  document.getElementById('modalBody').innerHTML = body;

  var modal = new bootstrap.Modal(document.getElementById('reviewModal'));
  modal.show();
}
</script>
