<?php
$ld      = $level_data;
$current = $ld['current'];
$active  = $ld['active_schools'];
$needsContract = $current['contract']; // true for Level 1+
$statusColors = array(
    'pending_upload' => array('color'=>'#e67e22','label'=>'Awaiting Upload'),
    'uploaded'       => array('color'=>'#3498db','label'=>'Uploaded — Under Review'),
    'verified'       => array('color'=>'#27ae60','label'=>'Verified & Active'),
    'rejected'       => array('color'=>'#e74c3c','label'=>'Rejected — Please Re-upload'),
);
$sc = $contract ? ($statusColors[$contract['status']] ?? $statusColors['pending_upload']) : null;
?>

<?php if ($this->session->flashdata('contract_success')): ?>
<div style="background:#eafaf1;border:1px solid #a9dfbf;border-radius:8px;padding:12px 18px;font-size:.85rem;color:#1e8449;margin-bottom:20px">
  <i class="fas fa-check-circle me-1"></i><?= $this->session->flashdata('contract_success') ?>
</div>
<?php endif; ?>

<?php if (!$needsContract): ?>
<!-- Starter — not yet eligible for contract -->
<div class="ap-card" style="max-width:600px">
  <div class="ap-card-body text-center" style="padding:40px 30px">
    <div style="font-size:2.5rem;margin-bottom:16px">📋</div>
    <h5 style="font-weight:700;margin-bottom:10px">Contract Not Yet Available</h5>
    <p style="color:var(--ap-muted);font-size:.88rem;line-height:1.7;max-width:420px;margin:0 auto 20px">
      You are currently at <strong>Starter level</strong> with <strong><?= $active ?> active school<?= $active !== 1 ? 's' : '' ?></strong>.
      Once you onboard <strong>10 active schools</strong> and reach Level 1, a formal employment contract will be generated for you to sign.
    </p>
    <a href="<?= base_url('agent_portal/my_level') ?>"
       style="display:inline-block;padding:9px 24px;background:var(--ap-navy);color:#fff;border-radius:8px;font-size:.86rem;font-weight:600;text-decoration:none">
      <i class="fas fa-trophy me-1"></i>View My Level Progress
    </a>
  </div>
</div>

<?php else: ?>
<!-- Level 1+ — contract required -->
<div class="row g-4">
  <div class="col-md-7">

    <!-- Status card -->
    <div class="ap-card" style="margin-bottom:20px">
      <div class="ap-card-header">Contract Status</div>
      <div class="ap-card-body">
        <?php if ($contract): ?>
        <div style="display:flex;align-items:center;gap:14px;margin-bottom:<?= !empty($contract['review_note']) ? '14px' : '0' ?>">
          <div style="width:14px;height:14px;border-radius:50%;background:<?= $sc['color'] ?>;flex-shrink:0"></div>
          <div>
            <div style="font-weight:700;font-size:.95rem"><?= $sc['label'] ?></div>
            <div style="font-size:.78rem;color:var(--ap-muted)">
              Level: <?= htmlspecialchars($contract['level_name']) ?>
              <?php if ($contract['uploaded_at']): ?>
               · Uploaded <?= date('d M Y', strtotime($contract['uploaded_at'])) ?>
              <?php endif; ?>
            </div>
          </div>
          <?php if ($contract['status'] === 'verified'): ?>
          <span style="margin-left:auto;background:#eafaf1;color:#1e8449;border-radius:20px;padding:4px 14px;font-size:.78rem;font-weight:700">
            <i class="fas fa-check-circle me-1"></i>ACTIVE
          </span>
          <?php endif; ?>
        </div>
        <?php if (!empty($contract['review_note'])): ?>
        <div style="background:#fdf2f2;border-left:3px solid #e74c3c;border-radius:6px;padding:10px 14px;font-size:.82rem;color:var(--ap-text)">
          <strong>Review note:</strong> <?= htmlspecialchars($contract['review_note']) ?>
        </div>
        <?php endif; ?>
        <?php else: ?>
        <div style="color:var(--ap-muted);font-size:.86rem">No contract uploaded yet.</div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Upload form -->
    <?php if (!$contract || in_array($contract['status'], array('pending_upload','rejected'))): ?>
    <div class="ap-card">
      <div class="ap-card-header">Upload Signed Contract</div>
      <div class="ap-card-body">
        <div style="background:rgba(243,156,18,.09);border-left:3px solid var(--ap-accent);border-radius:6px;padding:10px 14px;font-size:.81rem;margin-bottom:16px;line-height:1.65">
          <i class="fas fa-info-circle me-1" style="color:var(--ap-accent)"></i>
          <strong>How to sign your contract:</strong><br>
          1. Download the contract below &nbsp;→&nbsp; 2. Print it &nbsp;→&nbsp; 3. Sign all pages &nbsp;→&nbsp;
          4. Scan or photograph the signed copy &nbsp;→&nbsp; 5. Upload here (PDF, JPG, or PNG, max 10 MB)
        </div>
        <?php if (!empty($upload_error)): ?>
        <div style="background:#fdf2f2;border:1px solid #f5c6cb;border-radius:8px;padding:10px 14px;font-size:.83rem;color:#c0392b;margin-bottom:14px">
          <i class="fas fa-exclamation-circle me-1"></i><?= $upload_error ?>
        </div>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data" action="<?= base_url('agent_portal/my_contract') ?>">
          <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
          <input type="hidden" name="upload" value="1">
          <div style="margin-bottom:16px">
            <label style="font-size:.82rem;font-weight:600;display:block;margin-bottom:5px">Signed Contract File <span style="color:#e74c3c">*</span></label>
            <input type="file" name="contract_file" accept=".pdf,.jpg,.jpeg,.png" required
                   style="width:100%;padding:9px 12px;border:1.5px solid var(--ap-border);border-radius:8px;font-size:.86rem;background:var(--ap-bg)">
            <div style="font-size:.74rem;color:var(--ap-muted);margin-top:4px">Accepted: PDF, JPG, PNG · Max 10 MB</div>
          </div>
          <button type="submit"
                  style="width:100%;padding:11px;background:var(--ap-navy);color:#fff;border:none;border-radius:8px;font-size:.88rem;font-weight:700;cursor:pointer">
            <i class="fas fa-upload me-2"></i>Upload Signed Contract
          </button>
        </form>
      </div>
    </div>
    <?php endif; ?>

  </div>

  <!-- Right panel: download + info -->
  <div class="col-md-5">
    <div class="ap-card">
      <div class="ap-card-header">Download Contract Template</div>
      <div class="ap-card-body">
        <p style="font-size:.84rem;color:var(--ap-muted);line-height:1.65;margin-bottom:16px">
          Download your contract, print it, sign it, then upload the signed copy on the left.
        </p>
        <a href="<?= base_url('agent_portal/download_contract') ?>"
           style="display:block;text-align:center;padding:11px;background:var(--ap-green);color:#fff;border-radius:8px;font-size:.87rem;font-weight:700;text-decoration:none;margin-bottom:14px">
          <i class="fas fa-file-pdf me-2"></i>Download Contract (PDF)
        </a>
        <div style="font-size:.78rem;color:var(--ap-muted);line-height:1.7;border-top:1px solid var(--ap-border);padding-top:12px">
          <div style="margin-bottom:4px"><i class="fas fa-print me-1"></i> Print the downloaded document</div>
          <div style="margin-bottom:4px"><i class="fas fa-pen me-1"></i> Sign all pages where indicated</div>
          <div style="margin-bottom:4px"><i class="fas fa-camera me-1"></i> Scan or photograph clearly</div>
          <div><i class="fas fa-upload me-1"></i> Upload the signed copy here</div>
        </div>
      </div>
    </div>

    <?php if ($contract && $contract['status'] === 'verified'): ?>
    <div style="background:#eafaf1;border:1px solid #a9dfbf;border-radius:10px;padding:16px 18px;margin-top:16px;font-size:.83rem;color:#1e8449;line-height:1.7">
      <i class="fas fa-check-circle me-1"></i>
      Your contract has been <strong>verified by CST Solutions</strong>. Your yearly contract is active and renewable based on performance.
    </div>
    <?php endif; ?>
  </div>
</div>
<?php endif; ?>
