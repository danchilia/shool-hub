<div class="d-flex justify-content-between align-items-center mb-3">
  <h6 class="mb-0">Demo / Contact Requests</h6>
  <span class="badge bg-danger" id="unread-badge" <?= $unread == 0 ? 'style="display:none"' : '' ?>>
    <?= $unread ?> unread
  </span>
</div>

<section class="panel">
  <div class="panel-body">
    <div class="table-responsive">
      <table class="table table-bordered table-hover table-condensed mb-none" id="reqTable">
        <thead>
          <tr>
            <th>Date</th>
            <th>Name</th>
            <th>School</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Plan</th>
            <th>Message</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($requests)): foreach ($requests as $r): ?>
          <tr <?= !$r['is_read'] ? 'style="font-weight:600;background:#fffbe6"' : '' ?>>
            <td style="white-space:nowrap"><?= date('d M Y H:i', strtotime($r['created_at'])) ?></td>
            <td><?= htmlspecialchars($r['full_name']) ?></td>
            <td><?= htmlspecialchars($r['school_name']) ?></td>
            <td><a href="tel:<?= htmlspecialchars($r['phone']) ?>"><?= htmlspecialchars($r['phone']) ?></a></td>
            <td><?= $r['email'] ? '<a href="mailto:'.htmlspecialchars($r['email']).'">'.htmlspecialchars($r['email']).'</a>' : '—' ?></td>
            <td><?= $r['plan'] ? '<span class="label label-info">'.htmlspecialchars($r['plan']).'</span>' : '—' ?></td>
            <td style="max-width:220px;white-space:pre-wrap;font-size:.82rem"><?= $r['message'] ? htmlspecialchars($r['message']) : '—' ?></td>
            <td>
              <?= $r['is_read']
                ? '<span class="label label-success">Read</span>'
                : '<span class="label label-warning">New</span>' ?>
            </td>
            <td style="white-space:nowrap">
              <?php if (!$r['is_read']): ?>
              <a href="<?= base_url('contact/mark_read/'.$r['id']) ?>" class="btn btn-xs btn-default" title="Mark as read">
                <i class="fas fa-check"></i>
              </a>
              <?php endif; ?>
              <a href="<?= base_url('contact/delete_request/'.$r['id']) ?>"
                 onclick="return confirm('Delete this request?')"
                 class="btn btn-xs btn-danger" title="Delete">
                <i class="fas fa-trash"></i>
              </a>
            </td>
          </tr>
          <?php endforeach; else: ?>
          <tr><td colspan="9"><h5 class="text-center text-muted">No requests yet.</h5></td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>
