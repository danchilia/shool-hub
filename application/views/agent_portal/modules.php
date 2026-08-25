<?php
$role_labels = [
    'A' => ['label' => 'Admin',   'class' => 'role-a'],
    'T' => ['label' => 'Teacher', 'class' => 'role-t'],
    'P' => ['label' => 'Parent',  'class' => 'role-p'],
    'S' => ['label' => 'Student', 'class' => 'role-s'],
];

$categories = [
    ['name'=>'Admission & Enrollment','icon'=>'fas fa-user-plus','color'=>'#4e73df','modules'=>[
        ['name'=>'Create Admission','icon'=>'fas fa-file-alt','roles'=>['A']],
        ['name'=>'Online Admission Portal','icon'=>'fas fa-globe','roles'=>['A']],
        ['name'=>'Admission Requests','icon'=>'fas fa-inbox','roles'=>['A']],
        ['name'=>'Bulk Student Import','icon'=>'fas fa-file-upload','roles'=>['A']],
        ['name'=>'Student Categories','icon'=>'fas fa-tags','roles'=>['A']],
        ['name'=>'NEMIS Data Export','icon'=>'fas fa-database','roles'=>['A']],
    ]],
    ['name'=>'Student Management','icon'=>'fas fa-user-graduate','color'=>'#1cc88a','modules'=>[
        ['name'=>'Student List','icon'=>'fas fa-list','roles'=>['A','T']],
        ['name'=>'Generate ID Cards','icon'=>'fas fa-id-card','roles'=>['A']],
        ['name'=>'Student Login Access','icon'=>'fas fa-user-lock','roles'=>['A']],
        ['name'=>'Parent / Guardian List','icon'=>'fas fa-users','roles'=>['A']],
        ['name'=>'Add Parent','icon'=>'fas fa-user-plus','roles'=>['A']],
        ['name'=>'Parent Login Access','icon'=>'fas fa-lock','roles'=>['A']],
    ]],
    ['name'=>'Human Resources (HRM)','icon'=>'fas fa-briefcase','color'=>'#f6c23e','modules'=>[
        ['name'=>'Employee List','icon'=>'fas fa-users','roles'=>['A']],
        ['name'=>'Departments','icon'=>'fas fa-building','roles'=>['A']],
        ['name'=>'Designations','icon'=>'fas fa-sitemap','roles'=>['A']],
        ['name'=>'Add Employee','icon'=>'fas fa-user-tie','roles'=>['A']],
        ['name'=>'Salary Templates','icon'=>'fas fa-file-invoice-dollar','roles'=>['A']],
        ['name'=>'Salary Assignment','icon'=>'fas fa-user-tag','roles'=>['A']],
        ['name'=>'Salary Payment','icon'=>'fas fa-money-check-alt','roles'=>['A']],
        ['name'=>'Advance Salary','icon'=>'fas fa-hand-holding-usd','roles'=>['A','T']],
        ['name'=>'Leave Category','icon'=>'fas fa-folder-open','roles'=>['A']],
        ['name'=>'Leave Applications','icon'=>'fas fa-calendar-times','roles'=>['A','T']],
        ['name'=>'Awards','icon'=>'fas fa-trophy','roles'=>['A']],
    ]],
    ['name'=>'Academic Management','icon'=>'fas fa-book-open','color'=>'#36b9cc','modules'=>[
        ['name'=>'Classes & Sections','icon'=>'fas fa-door-open','roles'=>['A']],
        ['name'=>'Assign Class Teacher','icon'=>'fas fa-chalkboard-teacher','roles'=>['A']],
        ['name'=>'Subjects','icon'=>'fas fa-book','roles'=>['A']],
        ['name'=>'Subject–Class Assign','icon'=>'fas fa-link','roles'=>['A']],
        ['name'=>'Subject–Teacher Assign','icon'=>'fas fa-user-edit','roles'=>['A']],
        ['name'=>'Class Timetable','icon'=>'fas fa-calendar-alt','roles'=>['A','T']],
        ['name'=>'Attachments','icon'=>'fas fa-paperclip','roles'=>['A','T']],
        ['name'=>'Student Promotion','icon'=>'fas fa-level-up-alt','roles'=>['A']],
    ]],
    ['name'=>'CBC Assessment','icon'=>'fas fa-tasks','color'=>'#6f42c1','modules'=>[
        ['name'=>'Learning Areas','icon'=>'fas fa-layer-group','roles'=>['A']],
        ['name'=>'Strands & Sub-strands','icon'=>'fas fa-project-diagram','roles'=>['A']],
        ['name'=>'Assessment Entry','icon'=>'fas fa-pen-alt','roles'=>['A','T']],
        ['name'=>'Behaviour Assessment','icon'=>'fas fa-heart','roles'=>['A','T']],
        ['name'=>'CBC Report Card','icon'=>'fas fa-file-alt','roles'=>['A','T']],
    ]],
    ['name'=>'Examinations','icon'=>'fas fa-pencil-alt','color'=>'#e74a3b','modules'=>[
        ['name'=>'Exam Terms','icon'=>'fas fa-calendar','roles'=>['A']],
        ['name'=>'Exam Halls','icon'=>'fas fa-building','roles'=>['A']],
        ['name'=>'Mark Distribution','icon'=>'fas fa-percentage','roles'=>['A']],
        ['name'=>'Exam Setup','icon'=>'fas fa-cog','roles'=>['A']],
        ['name'=>'Exam Timetable','icon'=>'fas fa-calendar-check','roles'=>['A','T']],
        ['name'=>'Mark Entry','icon'=>'fas fa-keyboard','roles'=>['A','T']],
        ['name'=>'Grade Ranges','icon'=>'fas fa-chart-bar','roles'=>['A']],
        ['name'=>'Report Card','icon'=>'fas fa-graduation-cap','roles'=>['A','T']],
        ['name'=>'Tabulation Sheet','icon'=>'fas fa-table','roles'=>['A','T']],
    ]],
    ['name'=>'Hostel Management','icon'=>'fas fa-hotel','color'=>'#fd7e14','modules'=>[
        ['name'=>'Hostel Master','icon'=>'fas fa-building','roles'=>['A']],
        ['name'=>'Hostel Rooms','icon'=>'fas fa-bed','roles'=>['A']],
        ['name'=>'Room Categories','icon'=>'fas fa-th-large','roles'=>['A']],
        ['name'=>'Allocation Report','icon'=>'fas fa-clipboard-list','roles'=>['A']],
    ]],
    ['name'=>'Transport Management','icon'=>'fas fa-bus','color'=>'#20c997','modules'=>[
        ['name'=>'Routes','icon'=>'fas fa-route','roles'=>['A']],
        ['name'=>'Vehicles','icon'=>'fas fa-bus-alt','roles'=>['A']],
        ['name'=>'Stoppages / Stops','icon'=>'fas fa-map-marker-alt','roles'=>['A']],
        ['name'=>'Assign Vehicle','icon'=>'fas fa-user-check','roles'=>['A']],
        ['name'=>'Allocation Report','icon'=>'fas fa-list-ol','roles'=>['A']],
        ['name'=>'GPS Bus Tracking','icon'=>'fas fa-satellite','roles'=>['A']],
    ]],
    ['name'=>'Attendance','icon'=>'fas fa-user-check','color'=>'#17a2b8','modules'=>[
        ['name'=>'Student Attendance','icon'=>'fas fa-user-check','roles'=>['A','T']],
        ['name'=>'Employee Attendance','icon'=>'fas fa-briefcase','roles'=>['A']],
        ['name'=>'Exam Attendance','icon'=>'fas fa-clipboard-check','roles'=>['A','T']],
        ['name'=>'Biometric Devices','icon'=>'fas fa-fingerprint','roles'=>['A']],
        ['name'=>'ID Mapping','icon'=>'fas fa-link','roles'=>['A']],
        ['name'=>'Biometric Import CSV','icon'=>'fas fa-file-import','roles'=>['A']],
        ['name'=>'Biometric Scan Logs','icon'=>'fas fa-list','roles'=>['A']],
    ]],
    ['name'=>'Library','icon'=>'fas fa-book','color'=>'#6610f2','modules'=>[
        ['name'=>'Book Management','icon'=>'fas fa-book','roles'=>['A']],
        ['name'=>'Book Categories','icon'=>'fas fa-tags','roles'=>['A']],
        ['name'=>'My Issued Books','icon'=>'fas fa-bookmark','roles'=>['A','T','S']],
        ['name'=>'Book Issue / Return','icon'=>'fas fa-exchange-alt','roles'=>['A']],
    ]],
    ['name'=>'Events','icon'=>'fas fa-calendar-alt','color'=>'#e83e8c','modules'=>[
        ['name'=>'Event Types','icon'=>'fas fa-tag','roles'=>['A']],
        ['name'=>'Events Calendar','icon'=>'fas fa-calendar-alt','roles'=>['A','T']],
    ]],
    ['name'=>'Student Accounting (Fees)','icon'=>'fas fa-calculator','color'=>'#28a745','modules'=>[
        ['name'=>'Fees Types','icon'=>'fas fa-tag','roles'=>['A']],
        ['name'=>'Fees Groups','icon'=>'fas fa-layer-group','roles'=>['A']],
        ['name'=>'Fine Setup','icon'=>'fas fa-gavel','roles'=>['A']],
        ['name'=>'Fees Allocation','icon'=>'fas fa-coins','roles'=>['A']],
        ['name'=>'Payment History','icon'=>'fas fa-history','roles'=>['A']],
        ['name'=>'Due Fees Invoice','icon'=>'fas fa-file-invoice','roles'=>['A']],
        ['name'=>'Fees Reminder','icon'=>'fas fa-bell','roles'=>['A']],
        ['name'=>'M-Pesa Transactions','icon'=>'fas fa-mobile-alt','roles'=>['A']],
    ]],
    ['name'=>'Office Accounting','icon'=>'fas fa-credit-card','color'=>'#dc3545','modules'=>[
        ['name'=>'Chart of Accounts','icon'=>'fas fa-book','roles'=>['A']],
        ['name'=>'New Deposit','icon'=>'fas fa-arrow-circle-down','roles'=>['A']],
        ['name'=>'New Expense','icon'=>'fas fa-arrow-circle-up','roles'=>['A']],
        ['name'=>'All Transactions','icon'=>'fas fa-exchange-alt','roles'=>['A']],
        ['name'=>'Voucher Heads','icon'=>'fas fa-folder','roles'=>['A']],
        ['name'=>'LPO / Purchase Orders','icon'=>'fas fa-shopping-cart','roles'=>['A']],
        ['name'=>'Suppliers','icon'=>'fas fa-truck','roles'=>['A']],
    ]],
    ['name'=>'Communication','icon'=>'fas fa-comments','color'=>'#007bff','modules'=>[
        ['name'=>'Bulk SMS / Email','icon'=>'fas fa-bullhorn','roles'=>['A']],
        ['name'=>'Campaign Reports','icon'=>'fas fa-chart-line','roles'=>['A']],
        ['name'=>'SMS Templates','icon'=>'fas fa-sms','roles'=>['A']],
        ['name'=>'Email Templates','icon'=>'fas fa-envelope','roles'=>['A']],
        ['name'=>'Notice Board','icon'=>'fas fa-bullhorn','roles'=>['A','T']],
        ['name'=>'Internal Messaging','icon'=>'fas fa-inbox','roles'=>['A','T','S','P']],
    ]],
    ['name'=>'Reports','icon'=>'fas fa-chart-pie','color'=>'#6c757d','modules'=>[
        ['name'=>'Fees Report','icon'=>'fas fa-file-invoice','roles'=>['A']],
        ['name'=>'Receipts Report','icon'=>'fas fa-receipt','roles'=>['A']],
        ['name'=>'Due Fees Report','icon'=>'fas fa-exclamation-circle','roles'=>['A']],
        ['name'=>'Fine Report','icon'=>'fas fa-gavel','roles'=>['A']],
        ['name'=>'Account Statement','icon'=>'fas fa-file-alt','roles'=>['A']],
        ['name'=>'Income Report','icon'=>'fas fa-arrow-up','roles'=>['A']],
        ['name'=>'Expense Report','icon'=>'fas fa-arrow-down','roles'=>['A']],
        ['name'=>'Balance Sheet','icon'=>'fas fa-balance-scale','roles'=>['A']],
        ['name'=>'Student Attendance Rpt','icon'=>'fas fa-user-check','roles'=>['A','T']],
        ['name'=>'Employee Attendance Rpt','icon'=>'fas fa-briefcase','roles'=>['A']],
        ['name'=>'Payroll Summary','icon'=>'fas fa-file-invoice-dollar','roles'=>['A']],
        ['name'=>'Leave Reports','icon'=>'fas fa-calendar-times','roles'=>['A']],
        ['name'=>'Report Card','icon'=>'fas fa-graduation-cap','roles'=>['A','T']],
        ['name'=>'Tabulation Sheet','icon'=>'fas fa-table','roles'=>['A','T']],
    ]],
    ['name'=>'Analytics & Special Modules','icon'=>'fas fa-star','color'=>'#6f42c1','modules'=>[
        ['name'=>'Analytics & Insights','icon'=>'fas fa-chart-line','roles'=>['A']],
        ['name'=>'Canteen / POS','icon'=>'fas fa-utensils','roles'=>['A']],
        ['name'=>'Staff Appraisal','icon'=>'fas fa-star','roles'=>['A']],
        ['name'=>'Assets & Inventory','icon'=>'fas fa-boxes','roles'=>['A']],
        ['name'=>'Virtual Classroom','icon'=>'fas fa-video','roles'=>['A','T']],
        ['name'=>'Live Class','icon'=>'fas fa-broadcast-tower','roles'=>['A','T']],
        ['name'=>'Alumni Portal','icon'=>'fas fa-user-graduate','roles'=>['A']],
        ['name'=>'CBT / Online Exams','icon'=>'fas fa-laptop','roles'=>['A','T','S']],
        ['name'=>'Visitor / Gate Log','icon'=>'fas fa-sign-in-alt','roles'=>['A']],
        ['name'=>'KNEC Index Numbers','icon'=>'fas fa-hashtag','roles'=>['A']],
        ['name'=>'Parent-Teacher Meeting','icon'=>'fas fa-handshake','roles'=>['A','T']],
        ['name'=>'Bursary & Scholarships','icon'=>'fas fa-hand-holding-heart','roles'=>['A']],
        ['name'=>'Pocket Money','icon'=>'fas fa-wallet','roles'=>['A','S']],
        ['name'=>'Health Records','icon'=>'fas fa-heartbeat','roles'=>['A','T']],
        ['name'=>'Health Clinic Log','icon'=>'fas fa-notes-medical','roles'=>['A','T']],
        ['name'=>'Homework','icon'=>'fas fa-pencil-ruler','roles'=>['A','T','S']],
    ]],
    ['name'=>'Settings & Configuration','icon'=>'fas fa-cog','color'=>'#495057','modules'=>[
        ['name'=>'Global Settings','icon'=>'fas fa-globe','roles'=>['A']],
        ['name'=>'School Settings','icon'=>'fas fa-school','roles'=>['A']],
        ['name'=>'Roles & Permissions','icon'=>'fas fa-shield-alt','roles'=>['A']],
        ['name'=>'Session Settings','icon'=>'fas fa-hourglass','roles'=>['A']],
        ['name'=>'Translations','icon'=>'fas fa-language','roles'=>['A']],
        ['name'=>'Cron Jobs','icon'=>'fas fa-clock','roles'=>['A']],
        ['name'=>'Custom Fields','icon'=>'fas fa-sliders-h','roles'=>['A']],
        ['name'=>'Database Backup','icon'=>'fas fa-database','roles'=>['A']],
    ]],
    ['name'=>'User Portals','icon'=>'fas fa-users-cog','color'=>'#343a40','modules'=>[
        ['name'=>'Student Dashboard','icon'=>'fas fa-tachometer-alt','roles'=>['S']],
        ['name'=>'My Timetable','icon'=>'fas fa-calendar-alt','roles'=>['S']],
        ['name'=>'My Attendance','icon'=>'fas fa-check-circle','roles'=>['S']],
        ['name'=>'My Transport','icon'=>'fas fa-bus','roles'=>['S','P']],
        ['name'=>'My Hostel','icon'=>'fas fa-bed','roles'=>['S','P']],
        ['name'=>'My Library Books','icon'=>'fas fa-book','roles'=>['S','T']],
        ['name'=>'Leave Application','icon'=>'fas fa-calendar-times','roles'=>['S','T']],
        ['name'=>'Online CBT Exams','icon'=>'fas fa-laptop','roles'=>['S']],
        ['name'=>'My Pocket Money','icon'=>'fas fa-wallet','roles'=>['S']],
        ['name'=>'Parent Dashboard','icon'=>'fas fa-home','roles'=>['P']],
        ['name'=>"Child's Timetable",'icon'=>'fas fa-calendar','roles'=>['P']],
        ['name'=>"Child's Attendance",'icon'=>'fas fa-user-check','roles'=>['P']],
        ['name'=>"Child's Fees",'icon'=>'fas fa-money-bill','roles'=>['P']],
        ['name'=>"Child's Results",'icon'=>'fas fa-graduation-cap','roles'=>['P']],
        ['name'=>'Teacher Dashboard','icon'=>'fas fa-chalkboard','roles'=>['T']],
        ['name'=>'Manage Homework','icon'=>'fas fa-pencil-ruler','roles'=>['T']],
        ['name'=>'Take Attendance','icon'=>'fas fa-clipboard-check','roles'=>['T']],
        ['name'=>'Enter Exam Marks','icon'=>'fas fa-keyboard','roles'=>['T']],
    ]],
];

$total_modules = 0;
foreach ($categories as $cat) $total_modules += count($cat['modules']);
?>

<style>
.mod-search { margin-bottom: 20px; }
.mod-search .input-group-text { background: var(--ap-white); border-right: 0; border-color: var(--ap-border); }
.mod-search .form-control { border-left: 0; border-color: var(--ap-border); background: var(--ap-white); color: var(--ap-text); }
.mod-search .form-control:focus { box-shadow: none; border-color: var(--ap-accent); }

.mod-section { margin-bottom: 28px; }
.mod-section-head {
    display: flex; align-items: center; gap: 10px;
    padding: 9px 14px; border-radius: 6px; margin-bottom: 14px;
    color: #fff; font-size: .875rem; font-weight: 600; letter-spacing: .3px;
}
.mod-section-head i { font-size: 1rem; opacity: .9; }
.mod-count {
    background: rgba(255,255,255,.25); border-radius: 10px;
    padding: 1px 9px; font-size: .7rem; margin-left: 4px;
}
.mod-grid { display: flex; flex-wrap: wrap; margin: 0 -6px; }
.mod-item { padding: 0 6px 12px; width: 16.666%; }
@media (max-width: 1400px) { .mod-item { width: 20%; } }
@media (max-width: 1200px) { .mod-item { width: 25%; } }
@media (max-width: 992px)  { .mod-item { width: 33.333%; } }
@media (max-width: 768px)  { .mod-item { width: 50%; } }
@media (max-width: 480px)  { .mod-item { width: 100%; } }

.mod-card {
    display: flex; flex-direction: column; align-items: center;
    background: var(--ap-white); border: 1px solid var(--ap-border);
    border-top: 3px solid transparent;
    border-radius: 7px; padding: 14px 10px 12px;
    text-align: center; transition: transform .12s, box-shadow .12s;
    height: 100%;
}
.mod-card:hover { transform: translateY(-2px); box-shadow: 0 4px 14px rgba(0,0,0,.1); }
.mod-card-icon {
    width: 40px; height: 40px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: .95rem; margin-bottom: 9px;
}
.mod-card-name { font-size: .76rem; font-weight: 600; color: var(--ap-text); line-height: 1.3; margin-bottom: 8px; flex: 1; }
.mod-roles { display: flex; flex-wrap: wrap; gap: 3px; justify-content: center; }
.mod-roles span { font-size: .62rem; padding: 1px 6px; border-radius: 10px; font-weight: 600; }
.role-a { background: #fde8e6; color: #c0392b; }
.role-t { background: #d6eaf8; color: #1a5276; }
.role-p { background: #d5f5e3; color: #186a3b; }
.role-s { background: #fef9e7; color: #9a7d0a; }

.mod-hidden { display: none !important; }
.mod-section.all-hidden { display: none !important; }
</style>

<!-- Top bar -->
<div class="ap-card mb-4" style="background:linear-gradient(135deg,#1a2e4a,#243a5e);color:#fff;border:none">
  <div class="ap-card-body py-3">
    <div class="d-flex align-items-center gap-3 flex-wrap">
      <div style="width:48px;height:48px;background:rgba(255,255,255,.15);border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:1.4rem">
        <i class="fas fa-th-large"></i>
      </div>
      <div>
        <div style="font-size:1.1rem;font-weight:700">All System Modules</div>
        <div style="opacity:.65;font-size:.83rem"><?= $total_modules ?> features across <?= count($categories) ?> categories — use during demos to show the full system</div>
      </div>
      <div class="ms-auto d-flex gap-2 flex-wrap">
        <a href="<?= base_url('agent_portal/demo') ?>" class="btn btn-sm" style="background:var(--ap-accent);color:#fff;font-weight:600">
          <i class="fas fa-user-shield me-1"></i>Demo Credentials
        </a>
        <a href="<?= base_url('authentication') ?>" target="_blank" class="btn btn-sm" style="background:rgba(255,255,255,.15);color:#fff;border:1px solid rgba(255,255,255,.3)">
          <i class="fas fa-external-link-alt me-1"></i>Open Demo School
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Role legend + search -->
<div class="d-flex align-items-center gap-3 flex-wrap mb-4">
  <div class="d-flex align-items-center gap-2">
    <span style="font-size:.78rem;color:var(--ap-muted);font-weight:600">ROLES:</span>
    <span class="mod-roles"><span class="role-a">Admin</span></span>
    <span class="mod-roles"><span class="role-t">Teacher</span></span>
    <span class="mod-roles"><span class="role-p">Parent</span></span>
    <span class="mod-roles"><span class="role-s">Student</span></span>
  </div>
  <div class="ms-auto mod-search" style="max-width:300px;margin-bottom:0">
    <div class="input-group">
      <span class="input-group-text"><i class="fas fa-search" style="color:var(--ap-muted)"></i></span>
      <input type="text" id="modSearch" class="form-control" placeholder="Search modules…">
    </div>
  </div>
</div>

<!-- Categories -->
<?php foreach ($categories as $cat): ?>
<div class="mod-section" data-section="<?= htmlspecialchars(strtolower($cat['name'])) ?>">
  <div class="mod-section-head" style="background:<?= $cat['color'] ?>">
    <i class="<?= $cat['icon'] ?>"></i>
    <?= htmlspecialchars($cat['name']) ?>
    <span class="mod-count"><?= count($cat['modules']) ?></span>
  </div>
  <div class="mod-grid">
    <?php foreach ($cat['modules'] as $mod): ?>
    <div class="mod-item" data-name="<?= htmlspecialchars(strtolower($mod['name'])) ?>">
      <div class="mod-card" style="border-top-color:<?= $cat['color'] ?>">
        <div class="mod-card-icon" style="background:<?= $cat['color'] ?>22;color:<?= $cat['color'] ?>">
          <i class="<?= $mod['icon'] ?>"></i>
        </div>
        <div class="mod-card-name"><?= htmlspecialchars($mod['name']) ?></div>
        <div class="mod-roles">
          <?php foreach ($mod['roles'] as $r): ?>
            <span class="<?= $role_labels[$r]['class'] ?>"><?= $role_labels[$r]['label'] ?></span>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
<?php endforeach; ?>

<!-- Empty state -->
<div id="noResults" class="text-center py-5" style="display:none">
  <i class="fas fa-search" style="font-size:2.5rem;color:var(--ap-border)"></i>
  <div style="margin-top:12px;color:var(--ap-muted);font-size:.9rem">No modules match your search.</div>
</div>

<script>
(function () {
    var searchEl = document.getElementById('modSearch');
    searchEl.addEventListener('input', function () {
        var q = this.value.toLowerCase().trim();
        var anyVisible = false;

        document.querySelectorAll('.mod-section').forEach(function (section) {
            var items = section.querySelectorAll('.mod-item');
            var sectionVisible = false;
            items.forEach(function (item) {
                var name = item.getAttribute('data-name') || '';
                if (!q || name.indexOf(q) !== -1) {
                    item.classList.remove('mod-hidden');
                    sectionVisible = true;
                    anyVisible = true;
                } else {
                    item.classList.add('mod-hidden');
                }
            });
            if (sectionVisible) {
                section.classList.remove('all-hidden');
            } else {
                section.classList.add('all-hidden');
            }
        });

        document.getElementById('noResults').style.display = anyVisible ? 'none' : 'block';
    });
})();
</script>
