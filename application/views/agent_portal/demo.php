<?php
$login_url = base_url('authentication');

$roles = array(
    array(
        'role'     => 'Admin',
        'icon'     => 'fas fa-user-shield',
        'color'    => '#e74c3c',
        'bg'       => '#fadbd8',
        'email'    => 'admin@sunriseacademy.co.ke',
        'password' => 'Sunrise@Admin',
        'desc'     => 'Full access — student records, fees, staff, reports, settings. Best for showing the complete system.',
    ),
    array(
        'role'     => 'Teacher',
        'icon'     => 'fas fa-chalkboard-teacher',
        'color'    => '#2980b9',
        'bg'       => '#d6eaf8',
        'email'    => 'faith.njeri@sunriseacademy.co.ke',
        'password' => 'Sunrise@Teacher',
        'desc'     => 'Class attendance, CBC assessments, exam marks, timetable, leave requests. Show teaching staff view.',
    ),
    array(
        'role'     => 'Accountant',
        'icon'     => 'fas fa-calculator',
        'color'    => '#8e44ad',
        'bg'       => '#e8daef',
        'email'    => 'samuel.kamau@sunriseacademy.co.ke',
        'password' => 'Sunrise@Accountant',
        'desc'     => 'Fee collection, payment history, financial reports, expense tracking.',
    ),
    array(
        'role'     => 'Parent',
        'icon'     => 'fas fa-users',
        'color'    => '#27ae60',
        'bg'       => '#d5f5e3',
        'email'    => 'mary.simiyu@gmail.com',
        'password' => 'Sunrise@Parent',
        'desc'     => 'Child\'s attendance, fees balance, exam results, notices, communication with school.',
    ),
    array(
        'role'     => 'Student',
        'icon'     => 'fas fa-user-graduate',
        'color'    => '#e67e22',
        'bg'       => '#fdebd0',
        'email'    => 'brian.achieng@student.sunrise.ke',
        'password' => 'Sunrise@Student',
        'desc'     => 'Timetable, exam results, library, assignments, notifications.',
    ),
);

$extra_teachers = array(
    'peter.ochieng@sunriseacademy.co.ke',
    'grace.akinyi@sunriseacademy.co.ke',
    'david.kipchoge@sunriseacademy.co.ke',
    'mary.wanjiku@sunriseacademy.co.ke',
);
$extra_parents = array(
    'agnes.otieno@gmail.com','henry.cheruiyot@gmail.com','stephen.mutua@gmail.com',
    'irene.kamau@gmail.com','margaret.mwangi@gmail.com',
);
$extra_students = array(
    'evans.rotich@student.sunrise.ke','njeri.nyambura@student.sunrise.ke',
    'joel.kimani@student.sunrise.ke','diana.kiplagat@student.sunrise.ke',
    'aisha.otieno@student.sunrise.ke',
);
?>

<!-- Header banner -->
<div class="ap-card mb-4" style="background:linear-gradient(135deg,#1a2e4a,#243a5e);color:#fff;border:none">
  <div class="ap-card-body">
    <div class="d-flex align-items-center gap-3">
      <div style="width:52px;height:52px;background:rgba(255,255,255,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.5rem">
        🏫
      </div>
      <div>
        <div style="font-size:1.15rem;font-weight:700">Sunrise Academy Nairobi</div>
        <div style="opacity:.7;font-size:.85rem">Demo School · Branch ID 3 · info@sunriseacademy.co.ke</div>
      </div>
      <a href="<?= $login_url ?>" target="_blank"
         class="btn btn-sm ms-auto" style="background:var(--ap-accent);color:#fff;font-weight:600">
        <i class="fas fa-external-link-alt me-1"></i>Open Login Page
      </a>
    </div>
  </div>
</div>

<!-- How to use tip -->
<div class="alert alert-info d-flex gap-2 align-items-start mb-4" style="font-size:.85rem">
  <i class="fas fa-lightbulb mt-1"></i>
  <div>
    <strong>How to run a demo:</strong>
    Click <em>Open Login Page</em> above (opens in new tab), copy the email + password for the role you want to show,
    paste into the login form, and walk the school through that user's dashboard live.
    Switch roles to show different perspectives.
  </div>
</div>

<!-- Role credential cards -->
<div class="row g-3 mb-4">
  <?php foreach ($roles as $r): ?>
  <div class="col-md-6 col-lg-4">
    <div class="ap-card h-100">
      <div class="ap-card-body">
        <!-- Role header -->
        <div class="d-flex align-items-center gap-2 mb-3">
          <div style="width:38px;height:38px;background:<?= $r['bg'] ?>;border-radius:9px;display:flex;align-items:center;justify-content:center">
            <i class="<?= $r['icon'] ?>" style="color:<?= $r['color'] ?>"></i>
          </div>
          <div>
            <div style="font-weight:700;font-size:.95rem"><?= $r['role'] ?></div>
            <div style="font-size:.72rem;color:var(--ap-muted)">Demo Account</div>
          </div>
        </div>

        <div style="font-size:.8rem;color:var(--ap-muted);margin-bottom:14px">
          <?= $r['desc'] ?>
        </div>

        <!-- Credentials -->
        <div class="mb-2">
          <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.8px;color:var(--ap-muted);margin-bottom:3px">Email</div>
          <div class="d-flex align-items-center gap-2">
            <code style="font-size:.8rem;background:var(--ap-bg);padding:5px 10px;border-radius:6px;flex:1;word-break:break-all">
              <?= $r['email'] ?>
            </code>
            <button class="btn btn-sm btn-outline-secondary copy-btn" data-copy="<?= $r['email'] ?>" title="Copy">
              <i class="fas fa-copy"></i>
            </button>
          </div>
        </div>

        <div class="mb-3">
          <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.8px;color:var(--ap-muted);margin-bottom:3px">Password</div>
          <div class="d-flex align-items-center gap-2">
            <code style="font-size:.85rem;background:var(--ap-bg);padding:5px 10px;border-radius:6px;flex:1;font-weight:700">
              <?= $r['password'] ?>
            </code>
            <button class="btn btn-sm btn-outline-secondary copy-btn" data-copy="<?= $r['password'] ?>" title="Copy">
              <i class="fas fa-copy"></i>
            </button>
          </div>
        </div>

        <a href="<?= $login_url ?>" target="_blank"
           class="btn btn-sm w-100" style="background:<?= $r['color'] ?>;color:#fff;font-size:.82rem">
          <i class="fas fa-sign-in-alt me-1"></i>Log in as <?= $r['role'] ?>
        </a>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- Extra accounts -->
<div class="row g-4">
  <div class="col-md-4">
    <div class="ap-card">
      <div class="ap-card-header" style="font-size:.85rem">
        <i class="fas fa-chalkboard-teacher me-1" style="color:#2980b9"></i>More Teacher Accounts
      </div>
      <div class="ap-card-body p-0">
        <?php foreach ($extra_teachers as $em): ?>
        <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom" style="font-size:.8rem">
          <span><?= $em ?></span>
          <button class="btn btn-xs copy-btn" data-copy="<?= $em ?>" style="padding:2px 8px;font-size:.72rem;border:1px solid #ddd;border-radius:4px">
            <i class="fas fa-copy"></i>
          </button>
        </div>
        <?php endforeach; ?>
        <div class="px-3 py-2" style="font-size:.75rem;color:var(--ap-muted)">Password: <strong>Sunrise@Teacher</strong></div>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="ap-card">
      <div class="ap-card-header" style="font-size:.85rem">
        <i class="fas fa-users me-1" style="color:#27ae60"></i>More Parent Accounts
      </div>
      <div class="ap-card-body p-0">
        <?php foreach ($extra_parents as $em): ?>
        <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom" style="font-size:.8rem">
          <span><?= $em ?></span>
          <button class="btn btn-xs copy-btn" data-copy="<?= $em ?>" style="padding:2px 8px;font-size:.72rem;border:1px solid #ddd;border-radius:4px">
            <i class="fas fa-copy"></i>
          </button>
        </div>
        <?php endforeach; ?>
        <div class="px-3 py-2" style="font-size:.75rem;color:var(--ap-muted)">Password: <strong>Sunrise@Parent</strong></div>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="ap-card">
      <div class="ap-card-header" style="font-size:.85rem">
        <i class="fas fa-user-graduate me-1" style="color:#e67e22"></i>More Student Accounts
      </div>
      <div class="ap-card-body p-0">
        <?php foreach ($extra_students as $em): ?>
        <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom" style="font-size:.8rem">
          <span><?= $em ?></span>
          <button class="btn btn-xs copy-btn" data-copy="<?= $em ?>" style="padding:2px 8px;font-size:.72rem;border:1px solid #ddd;border-radius:4px">
            <i class="fas fa-copy"></i>
          </button>
        </div>
        <?php endforeach; ?>
        <div class="px-3 py-2" style="font-size:.75rem;color:var(--ap-muted)">Password: <strong>Sunrise@Student</strong></div>
      </div>
    </div>
  </div>
</div>

<!-- Copy toast -->
<div id="copyToast" style="
  position:fixed;bottom:24px;right:24px;
  background:#1a2e4a;color:#fff;
  padding:10px 18px;border-radius:8px;
  font-size:.85rem;font-weight:500;
  opacity:0;transition:opacity .2s;
  pointer-events:none;z-index:9999">
  <i class="fas fa-check me-1" style="color:#2ecc71"></i> Copied!
</div>

<script>
document.querySelectorAll('.copy-btn').forEach(function(btn) {
  btn.addEventListener('click', function() {
    var text = this.getAttribute('data-copy');
    navigator.clipboard.writeText(text).then(function() {
      var toast = document.getElementById('copyToast');
      toast.style.opacity = '1';
      setTimeout(function() { toast.style.opacity = '0'; }, 1800);
    });
  });
});
</script>
