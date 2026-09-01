<!DOCTYPE html>
<html lang="en" data-theme="">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= isset($title) ? htmlspecialchars($title) . ' | CST Agent' : 'CST Agent Portal' ?></title>
<link rel="stylesheet" href="<?= base_url('assets/vendor/bootstrap5/css/bootstrap.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/vendor/font-awesome/css/all.min.css') ?>">
<style>
:root {
  --ap-navy:   #1a2e4a;
  --ap-navy2:  #243a5e;
  --ap-accent: #f39c12;
  --ap-green:  #27ae60;
  --ap-red:    #e74c3c;
  --ap-text:   #2c3e50;
  --ap-muted:  #7f8c8d;
  --ap-bg:     #f0f2f5;
  --ap-white:  #ffffff;
  --ap-border: #dde1e7;
  --sidebar-w: 240px;
}
@media (prefers-color-scheme: dark) {
  :root:not([data-theme="light"]) {
    --ap-bg:    #0f1923;
    --ap-white: #1a2535;
    --ap-text:  #e0e6f0;
    --ap-muted: #8a96a8;
    --ap-border:#2a3a50;
  }
}
:root[data-theme="dark"] {
  --ap-bg:    #0f1923;
  --ap-white: #1a2535;
  --ap-text:  #e0e6f0;
  --ap-muted: #8a96a8;
  --ap-border:#2a3a50;
}

* { box-sizing: border-box; }
body { margin: 0; background: var(--ap-bg); color: var(--ap-text); font-family: 'Segoe UI', system-ui, sans-serif; }

/* ── SIDEBAR ── */
.ap-sidebar {
  position: fixed; top: 0; left: 0; width: var(--sidebar-w);
  height: 100vh; background: var(--ap-navy); display: flex;
  flex-direction: column; z-index: 1000; transition: transform .25s;
}
.ap-sidebar-brand {
  padding: 20px 18px 14px;
  border-bottom: 1px solid rgba(255,255,255,.1);
}
.ap-sidebar-brand .brand-title {
  color: #fff; font-size: 1.15rem; font-weight: 700; letter-spacing: .5px;
}
.ap-sidebar-brand .brand-sub {
  color: rgba(255,255,255,.5); font-size: .72rem; text-transform: uppercase; letter-spacing: 1px;
}
.ap-nav { padding: 12px 0; flex: 1; overflow-y: auto; }
.ap-nav a {
  display: flex; align-items: center; gap: 11px;
  padding: 10px 18px; color: rgba(255,255,255,.72); text-decoration: none;
  font-size: .875rem; transition: background .15s, color .15s;
}
.ap-nav a:hover, .ap-nav a.active {
  background: rgba(255,255,255,.1); color: #fff;
}
.ap-nav a.active { border-left: 3px solid var(--ap-accent); }
.ap-nav a i { width: 18px; text-align: center; font-size: .9rem; }
.ap-nav .nav-section {
  padding: 12px 18px 4px;
  color: rgba(255,255,255,.35); font-size: .65rem;
  text-transform: uppercase; letter-spacing: 1.2px;
}
.ap-sidebar-footer {
  padding: 14px 18px;
  border-top: 1px solid rgba(255,255,255,.1);
  font-size: .8rem; color: rgba(255,255,255,.5);
}
.ap-sidebar-footer a { color: rgba(255,255,255,.5); text-decoration: none; }
.ap-sidebar-footer a:hover { color: #fff; }

/* ── MAIN AREA ── */
.ap-main { margin-left: var(--sidebar-w); min-height: 100vh; display: flex; flex-direction: column; }

/* ── TOPBAR ── */
.ap-topbar {
  background: var(--ap-white); border-bottom: 1px solid var(--ap-border);
  padding: 0 24px; height: 56px; display: flex; align-items: center;
  justify-content: space-between; position: sticky; top: 0; z-index: 500;
}
.ap-topbar .page-title { font-weight: 600; font-size: 1.05rem; }
.ap-topbar .agent-chip {
  display: flex; align-items: center; gap: 8px;
  background: var(--ap-bg); border-radius: 24px;
  padding: 5px 14px 5px 8px; font-size: .82rem; font-weight: 500;
}
.ap-topbar .agent-chip .avatar {
  width: 30px; height: 30px; border-radius: 50%;
  background: var(--ap-navy2); color: #fff;
  display: flex; align-items: center; justify-content: center;
  font-size: .8rem; font-weight: 700;
}

/* ── CONTENT ── */
.ap-content { padding: 24px; flex: 1; }

/* ── STAT CARDS ── */
.stat-card {
  background: var(--ap-white); border-radius: 10px;
  padding: 20px; border: 1px solid var(--ap-border);
}
.stat-card .stat-icon {
  width: 44px; height: 44px; border-radius: 10px;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.2rem;
}
.stat-card .stat-val { font-size: 1.75rem; font-weight: 700; line-height: 1; }
.stat-card .stat-lbl { color: var(--ap-muted); font-size: .8rem; margin-top: 2px; }

/* ── TABLE CARD ── */
.ap-card {
  background: var(--ap-white); border-radius: 10px;
  border: 1px solid var(--ap-border); overflow: hidden;
}
.ap-card-header {
  padding: 14px 20px; border-bottom: 1px solid var(--ap-border);
  font-weight: 600; font-size: .95rem; display: flex; align-items: center;
  justify-content: space-between;
}
.ap-card-body { padding: 20px; }

/* ── STATUS BADGES ── */
.pipeline-badge {
  font-size: .7rem; padding: 3px 9px; border-radius: 20px;
  font-weight: 600; text-transform: uppercase; letter-spacing: .5px;
}
.pip-lead           { background: #e8f4fd; color: #2980b9; }
.pip-visited        { background: #fef9e7; color: #d4a017; }
.pip-demo_done      { background: #eafaf1; color: #1e8449; }
.pip-proposal_sent  { background: #f5eef8; color: #7d3c98; }
.pip-follow_up      { background: #fdf2e9; color: #ca6f1e; }
.pip-closed_won     { background: #d5f5e3; color: #186a3b; }
.pip-closed_lost    { background: #fadbd8; color: #922b21; }

.int-hot    { background: #fadbd8; color: #922b21; }
.int-warm   { background: #fef9e7; color: #d4a017; }
.int-cold   { background: #eaf4fb; color: #1a5276; }
.int-unknown{ background: #f2f3f4; color: #5d6d7e; }

/* ── FORM ── */
.ap-form-card {
  background: var(--ap-white); border-radius: 10px;
  border: 1px solid var(--ap-border); padding: 28px;
  max-width: 680px;
}
.ap-btn-primary {
  background: var(--ap-navy); color: #fff; border: none;
  padding: 8px 22px; border-radius: 6px; font-size: .875rem;
  font-weight: 500; cursor: pointer; transition: background .15s;
}
.ap-btn-primary:hover { background: var(--ap-navy2); color: #fff; }
.ap-btn-accent {
  background: var(--ap-accent); color: #fff; border: none;
  padding: 8px 22px; border-radius: 6px; font-size: .875rem; font-weight: 500;
}

/* ── OVERDUE BADGE ── */
.overdue-dot { width: 8px; height: 8px; background: var(--ap-red); border-radius: 50%; display: inline-block; }

/* ── RESPONSIVE ── */
.ap-toggler { display: none; background: none; border: none; font-size: 1.3rem; cursor: pointer; }
@media (max-width: 768px) {
  .ap-sidebar { transform: translateX(-100%); }
  .ap-sidebar.open { transform: translateX(0); }
  .ap-main { margin-left: 0; }
  .ap-toggler { display: block; }
}
</style>
</head>
<body>

<?php
$agent    = isset($_agent) ? $_agent : array();
$initials = !empty($agent['first_name']) ? strtoupper(substr($agent['first_name'],0,1) . substr($agent['last_name'],0,1)) : 'AG';
$activeUrl = isset($_active_url) ? $_active_url : '';
function ap_active($segment) {
    global $activeUrl;
    return (strpos($activeUrl, $segment) !== false) ? 'active' : '';
}
?>

<!-- SIDEBAR -->
<aside class="ap-sidebar" id="apSidebar">
  <div class="ap-sidebar-brand">
    <div class="brand-title" style="display:flex;align-items:center;gap:8px">
      <img src="<?= base_url('assets/images/cst-logo.png') ?>" alt="CST" style="height:30px;width:30px;object-fit:contain;border-radius:4px">
      CST SchoolHub
    </div>
    <div class="brand-sub">Agent Portal</div>
  </div>

  <nav class="ap-nav">
    <div class="nav-section">Main</div>
    <a href="<?= base_url('agent_portal') ?>" class="<?= ap_active('agent_portal/index') || $activeUrl === 'agent_portal' ? 'active' : '' ?>">
      <i class="fas fa-th-large"></i> Dashboard
    </a>

    <div class="nav-section">Schools</div>
    <a href="<?= base_url('agent_portal/schools') ?>" class="<?= ap_active('agent_portal/schools') ?>">
      <i class="fas fa-school"></i> My Schools
    </a>
    <a href="<?= base_url('agent_portal/add_school') ?>" class="<?= ap_active('agent_portal/add_school') ?>">
      <i class="fas fa-plus-circle"></i> Add School Lead
    </a>
    <a href="<?= base_url('agent_portal/followups') ?>" class="<?= ap_active('agent_portal/followups') ?>">
      <i class="fas fa-calendar-check"></i> Follow-ups
    </a>

    <div class="nav-section">Finances</div>
    <a href="<?= base_url('agent_portal/my_level') ?>" class="<?= ap_active('agent_portal/my_level') ?>">
      <i class="fas fa-trophy"></i> My Level
    </a>
    <a href="<?= base_url('agent_portal/my_contract') ?>" class="<?= ap_active('agent_portal/my_contract') ?>">
      <i class="fas fa-file-contract"></i> My Contract
    </a>
    <a href="<?= base_url('agent_portal/earnings') ?>" class="<?= ap_active('agent_portal/earnings') ?>">
      <i class="fas fa-wallet"></i> My Earnings
    </a>
    <a href="<?= base_url('agent_portal/expenses') ?>" class="<?= ap_active('agent_portal/expenses') ?>">
      <i class="fas fa-receipt"></i> Expense Claims
    </a>

    <div class="nav-section">Onboarding</div>
    <a href="<?= base_url('agent_portal/my_submissions') ?>" class="<?= ap_active('agent_portal/my_submissions') ?>">
      <i class="fas fa-clipboard-check"></i> My Submissions
    </a>
    <a href="<?= base_url('agent_portal/download_form') ?>">
      <i class="fas fa-file-word"></i> Download Form
    </a>

    <div class="nav-section">Demo Tools</div>
    <a href="<?= base_url('agent_portal/download_brochure') ?>">
      <i class="fas fa-file-pdf"></i> Download Brochure
    </a>
    <a href="<?= base_url('agent_portal/script') ?>" class="<?= ap_active('agent_portal/script') ?>">
      <i class="fas fa-comment-dots"></i> Visit Script
    </a>
    <a href="<?= base_url('agent_portal/demo') ?>" class="<?= ap_active('agent_portal/demo') ?>">
      <i class="fas fa-school"></i> Demo Credentials
    </a>
    <a href="<?= base_url('agent_portal/modules') ?>" class="<?= ap_active('agent_portal/modules') ?>">
      <i class="fas fa-th-large"></i> All Modules
    </a>

    <div class="nav-section">Account</div>
    <a href="<?= base_url('agent_portal/guide') ?>" class="<?= ap_active('agent_portal/guide') ?>" style="color:var(--ap-accent) !important;font-weight:600">
      <i class="fas fa-book-open"></i> How to Use This Portal
    </a>
    <a href="<?= base_url('agent_portal/profile') ?>" class="<?= ap_active('agent_portal/profile') ?>">
      <i class="fas fa-user-circle"></i> My Profile
    </a>
  </nav>

  <div class="ap-sidebar-footer">
    <a href="<?= base_url('agent_portal/logout') ?>"><i class="fas fa-sign-out-alt me-1"></i> Sign Out</a>
  </div>
</aside>

<!-- MAIN -->
<div class="ap-main">
  <header class="ap-topbar">
    <div class="d-flex align-items-center gap-3">
      <button class="ap-toggler" onclick="document.getElementById('apSidebar').classList.toggle('open')">
        <i class="fas fa-bars"></i>
      </button>
      <span class="page-title"><?= isset($title) ? htmlspecialchars($title) : '' ?></span>
    </div>
    <div class="agent-chip">
      <div class="avatar"><?= $initials ?></div>
      <?= !empty($agent['first_name']) ? htmlspecialchars($agent['first_name'] . ' ' . $agent['last_name']) : 'Agent' ?>
    </div>
  </header>

  <div class="ap-content">
<?php
// Flash messages
$alert_type    = $this->session->flashdata('alert_type');
$alert_message = $this->session->flashdata('alert_message');
if ($alert_type && $alert_message):
    $bs = $alert_type === 'success' ? 'success' : 'danger';
?>
<div class="alert alert-<?= $bs ?> alert-dismissible fade show mb-3" role="alert">
  <?= $alert_message ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>
