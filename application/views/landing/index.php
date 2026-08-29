<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="color-scheme" content="light">
<title>CST SchoolHub | School Management System</title>
<meta name="description" content="Cloud-based school management platform built for Kenyan schools. Manage students, fees, staff, exams and attendance from one dashboard.">
<link rel="shortcut icon" href="<?= base_url('assets/images/favicon.png') ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600&display=swap">
<link rel="stylesheet" href="<?= base_url('assets/vendor/font-awesome/css/all.min.css') ?>">
<style>
:root{
  --navy:#0c1f3f;--navy-mid:#152d54;--blue:#1a5276;
  --gold:#c9a227;--gold-dim:rgba(201,162,39,.13);
  --surface:#ffffff;--surface-alt:#f4f7fb;
  --text:#1e2d3d;--muted:#5a6a7a;--border:#dde4ec;
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{font-family:'DM Sans',sans-serif;background:var(--surface);color:var(--text);font-size:16px;line-height:1.65}
a{color:inherit;text-decoration:none}
img{max-width:100%;display:block}
h1,h2,h3{font-family:'Cormorant Garamond',serif;line-height:1.15;text-wrap:balance}
h1{font-size:clamp(2.5rem,5vw,4rem);font-weight:700}
h2{font-size:clamp(1.9rem,3.5vw,2.8rem);font-weight:600}
h3{font-size:1.1rem;font-weight:600;font-family:'DM Sans',sans-serif}
.wrap{max-width:1160px;margin:0 auto;padding:0 28px}
.section{padding:96px 0;background:var(--surface)}
.section--alt{background:var(--surface-alt)}
.gr{width:44px;height:2.5px;background:var(--gold);margin-bottom:18px}
.gr--c{margin-left:auto;margin-right:auto}
.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 30px;font-family:'DM Sans',sans-serif;font-weight:600;font-size:.9rem;border-radius:3px;border:none;cursor:pointer;transition:all .2s;text-decoration:none}
.btn--gold{background:var(--gold);color:var(--navy)}
.btn--gold:hover{background:#dbb230}
.btn--ghost{background:transparent;color:#fff;border:1.5px solid rgba(255,255,255,.3)}
.btn--ghost:hover{border-color:rgba(255,255,255,.7);background:rgba(255,255,255,.05)}

/* NAV */
.nav{position:sticky;top:0;z-index:200;background:var(--navy);border-bottom:1px solid rgba(255,255,255,.06)}
.nav__inner{max-width:1160px;margin:0 auto;padding:0 28px;display:flex;align-items:center;height:66px;gap:36px}
.nav__logo{display:flex;align-items:center;gap:12px}
.nav__logo img{height:36px;width:auto;flex-shrink:0}
.nav__name{font-weight:600;font-size:.93rem;color:#fff;letter-spacing:.01em}
.nav__links{display:flex;gap:28px;list-style:none;margin-left:auto}
.nav__links a{color:rgba(255,255,255,.62);font-size:.87rem;font-weight:500;transition:color .2s}
.nav__links a:hover{color:#fff}
.nav__sign{margin-left:16px;padding:8px 22px;background:var(--gold);color:var(--navy);font-weight:600;font-size:.87rem;border-radius:3px;transition:background .2s;white-space:nowrap}
.nav__sign:hover{background:#dbb230}

/* HERO */
.hero{background:var(--navy);min-height:90vh;display:flex;align-items:center;position:relative;overflow:hidden}
.hero::before{content:'';position:absolute;inset:0;background-image:repeating-linear-gradient(-45deg,transparent,transparent 60px,rgba(255,255,255,.012) 60px,rgba(255,255,255,.012) 61px)}
.hero__bg-img{position:absolute;right:0;top:0;height:100%;width:46%;object-fit:cover;object-position:center top;opacity:.18}
.hero__fade{position:absolute;right:0;top:0;bottom:0;width:46%;background:linear-gradient(to right,var(--navy) 0%,transparent 30%);z-index:1;pointer-events:none}
.hero__inner{position:relative;z-index:2;max-width:1160px;margin:0 auto;padding:90px 28px;display:grid;grid-template-columns:1fr 1fr;gap:64px;align-items:center}
.hero__eyebrow{display:inline-block;font-size:.75rem;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:var(--gold);margin-bottom:20px}
.hero__title{color:#fff;margin-bottom:22px}
.hero__title em{font-style:italic;color:var(--gold)}
.hero__body{color:rgba(255,255,255,.6);font-size:1.05rem;max-width:460px;margin-bottom:40px;line-height:1.78}
.hero__actions{display:flex;gap:14px;flex-wrap:wrap}
.hero__cards{display:flex;flex-direction:column;gap:14px}
.hero__card{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:6px;padding:18px 22px;display:flex;align-items:center;gap:16px;backdrop-filter:blur(6px)}
.hero__card-icon{width:40px;height:40px;border-radius:8px;background:var(--gold-dim);display:flex;align-items:center;justify-content:center;color:var(--gold);flex-shrink:0}
.hero__card-label{color:rgba(255,255,255,.85);font-size:.88rem;font-weight:500}
.hero__card-sub{color:rgba(255,255,255,.4);font-size:.76rem;margin-top:2px}
.hero__card-val{margin-left:auto;font-family:'Cormorant Garamond',serif;font-size:1.5rem;font-weight:700;color:var(--gold);font-variant-numeric:tabular-nums}

/* TRUST */
.trust{background:var(--navy-mid);padding:18px 28px;border-top:1px solid rgba(255,255,255,.05);border-bottom:1px solid rgba(255,255,255,.05)}
.trust__inner{max-width:1160px;margin:0 auto;display:flex;align-items:center;gap:32px;flex-wrap:wrap;justify-content:center}
.trust__label{color:rgba(255,255,255,.38);font-size:.72rem;letter-spacing:.1em;text-transform:uppercase;font-weight:500;padding-right:32px;border-right:1px solid rgba(255,255,255,.1)}
.trust__item{color:rgba(255,255,255,.5);font-size:.8rem;font-weight:500;display:flex;align-items:center;gap:8px}
.trust__dot{width:5px;height:5px;border-radius:50%;background:var(--gold);flex-shrink:0}

/* FEATURES */
.features__hd{text-align:center;max-width:580px;margin:0 auto 56px}
.features__hd h2{margin-bottom:12px}
.features__hd p{color:var(--muted)}
.features__grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px}
.fcard{background:var(--surface);border:1px solid var(--border);border-radius:5px;padding:30px 26px;transition:transform .22s,box-shadow .22s}
.fcard:hover{transform:translateY(-5px);box-shadow:0 14px 36px rgba(0,0,0,.08)}
.fcard__icon{width:42px;height:42px;background:var(--gold-dim);border-radius:7px;display:flex;align-items:center;justify-content:center;margin-bottom:18px;color:var(--gold)}
.fcard h3{margin-bottom:8px}
.fcard p{color:var(--muted);font-size:.87rem;line-height:1.62}

/* VALUE */
.value__inner{display:grid;grid-template-columns:1fr 1fr;gap:80px;align-items:center}
.value__badges{display:flex;flex-wrap:wrap;gap:9px;margin-top:30px}
.badge{display:inline-flex;align-items:center;padding:7px 15px;background:var(--gold-dim);border:1px solid rgba(201,162,39,.22);border-radius:3px;font-size:.8rem;font-weight:500;color:var(--text)}
.vcard{background:var(--surface);border:1px solid var(--border);border-radius:7px;padding:6px 0;box-shadow:0 8px 28px rgba(0,0,0,.06)}
.vrow{display:flex;align-items:center;gap:14px;padding:14px 24px;border-bottom:1px solid var(--border)}
.vrow:last-child{border-bottom:none}
.vcheck{width:26px;height:26px;border-radius:50%;background:#e6f4ed;display:flex;align-items:center;justify-content:center;flex-shrink:0;color:#2e7d52;font-size:.75rem;font-weight:700}
.vlabel{font-size:.87rem;font-weight:500}

/* HOW IT WORKS */
.how__hd{text-align:center;max-width:520px;margin:0 auto 56px}
.how__hd p{color:var(--muted);margin-top:10px}
.how__steps{display:grid;grid-template-columns:repeat(3,1fr);gap:0;position:relative}
.how__steps::before{content:'';position:absolute;top:27px;left:calc(16.67% + 26px);width:calc(66.67% - 52px);height:1px;background:var(--border)}
.hstep{padding:0 28px;text-align:center}
.hnum{width:54px;height:54px;border-radius:50%;border:2px solid var(--gold);display:flex;align-items:center;justify-content:center;margin:0 auto 22px;font-family:'Cormorant Garamond',serif;font-size:1.4rem;font-weight:700;color:var(--gold);background:var(--surface);position:relative;z-index:1}
.hstep h3{margin-bottom:8px;font-size:.95rem}
.hstep p{color:var(--muted);font-size:.84rem;line-height:1.65}

/* STATS */
.stats{background:var(--navy);padding:72px 0}
.stats__grid{display:grid;grid-template-columns:repeat(4,1fr);gap:0;text-align:center}
.stat{padding:0 24px;border-right:1px solid rgba(255,255,255,.08)}
.stat:last-child{border-right:none}
.stat__n{font-family:'Cormorant Garamond',serif;font-size:3rem;font-weight:700;color:var(--gold);line-height:1;margin-bottom:8px;font-variant-numeric:tabular-nums}
.stat__l{color:rgba(255,255,255,.5);font-size:.8rem;letter-spacing:.04em}

/* CTA */
.cta{background:var(--navy-mid);padding:96px 0;text-align:center;border-top:1px solid rgba(255,255,255,.05)}
.cta h2{color:#fff;max-width:540px;margin:0 auto 14px}
.cta p{color:rgba(255,255,255,.52);max-width:420px;margin:0 auto 38px}

/* FOOTER */
.footer{background:#07101d;padding:52px 0 30px;border-top:1px solid rgba(255,255,255,.05)}
.footer__inner{display:grid;grid-template-columns:2fr 1fr 1fr;gap:48px}
.footer__brand{font-weight:600;color:#fff;margin-bottom:10px;font-size:.95rem}
.footer__tag{color:rgba(255,255,255,.38);font-size:.82rem;line-height:1.65;max-width:270px;margin-bottom:18px}
.footer__email{color:var(--gold);font-size:.85rem}
.footer__col-title{color:rgba(255,255,255,.38);font-size:.68rem;letter-spacing:.11em;text-transform:uppercase;font-weight:600;margin-bottom:14px}
.footer__links{list-style:none;display:flex;flex-direction:column;gap:10px}
.footer__links a{color:rgba(255,255,255,.48);font-size:.84rem;transition:color .2s}
.footer__links a:hover{color:#fff}
.footer__bottom{margin-top:32px;padding-top:20px;border-top:1px solid rgba(255,255,255,.06);display:flex;justify-content:space-between;align-items:center;color:rgba(255,255,255,.25);font-size:.77rem}
.footer__bottom a{color:rgba(255,255,255,.36)}
.footer__bottom a:hover{color:rgba(255,255,255,.65)}

/* RESPONSIVE */
@media(max-width:960px){
  .hero__inner{grid-template-columns:1fr;gap:48px}
  .hero__bg-img,.hero__fade{display:none}
  .features__grid{grid-template-columns:1fr 1fr}
  .value__inner{grid-template-columns:1fr;gap:40px}
  .how__steps{grid-template-columns:1fr;gap:36px}
  .how__steps::before{display:none}
  .stats__grid{grid-template-columns:1fr 1fr;gap:40px}
  .stat{border-right:none}
  .footer__inner{grid-template-columns:1fr;gap:32px}
  .nav__links{display:none}
}
@media(max-width:600px){
  .features__grid{grid-template-columns:1fr}
  .hero__cards{display:none}
  .trust__label{display:none}
  .footer__bottom{flex-direction:column;gap:6px;text-align:center}
}
.fi{opacity:0;transform:translateY(18px);transition:opacity .5s ease,transform .5s ease}
.fi.v{opacity:1;transform:none}
@media(prefers-reduced-motion:reduce){.fi{opacity:1;transform:none;transition:none}}
</style>
</head>
<body>

<!-- NAV -->
<nav class="nav">
  <div class="nav__inner">
    <a href="<?= base_url() ?>" class="nav__logo">
      <img src="<?= base_url('assets/images/cst-logo.png') ?>" alt="CST SchoolHub" onerror="this.style.display='none'">
      <span class="nav__name">CST SchoolHub</span>
    </a>
    <ul class="nav__links">
      <li><a href="#features">Features</a></li>
      <li><a href="#how-it-works">How It Works</a></li>
      <li><a href="<?= base_url('careers') ?>">Careers</a></li>
      <li><a href="#contact">Contact</a></li>
    </ul>
    <a href="<?= base_url('authentication') ?>" class="nav__sign">Sign In</a>
  </div>
</nav>

<!-- HERO -->
<section class="hero">
  <img class="hero__bg-img" src="<?= base_url('assets/login_page/image/sidebox.png') ?>" alt="">
  <div class="hero__fade"></div>
  <div class="hero__inner">
    <div>
      <span class="hero__eyebrow">School Management Platform</span>
      <h1 class="hero__title">School Administration,<br><em>Fully in Control.</em></h1>
      <p class="hero__body">
        CST SchoolHub is a cloud-based platform built for Kenyan schools.
        Manage students, fees, staff, exams, and attendance from a single dashboard.
      </p>
      <div class="hero__actions">
        <a href="mailto:info@cstschoolhub.co.ke" class="btn btn--gold">Request a Demo</a>
        <a href="<?= base_url('authentication') ?>" class="btn btn--ghost">Sign In</a>
      </div>
    </div>
    <div class="hero__visual">
      <div class="hero__cards">
        <div class="hero__card">
          <div class="hero__card-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          </div>
          <div>
            <div class="hero__card-label">Active Students</div>
            <div class="hero__card-sub">Current term enrollment</div>
          </div>
          <div class="hero__card-val">1,284</div>
        </div>
        <div class="hero__card">
          <div class="hero__card-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
          </div>
          <div>
            <div class="hero__card-label">Fees Collected</div>
            <div class="hero__card-sub">This term</div>
          </div>
          <div class="hero__card-val" style="font-size:1.1rem">KES 4.2M</div>
        </div>
        <div class="hero__card">
          <div class="hero__card-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
          </div>
          <div>
            <div class="hero__card-label">Attendance Rate</div>
            <div class="hero__card-sub">Week average</div>
          </div>
          <div class="hero__card-val">94%</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- TRUST BAR -->
<div class="trust">
  <div class="trust__inner">
    <span class="trust__label">Built for Kenya</span>
    <span class="trust__item"><span class="trust__dot"></span>NEMIS Aligned</span>
    <span class="trust__item"><span class="trust__dot"></span>CBC Ready</span>
    <span class="trust__item"><span class="trust__dot"></span>M-Pesa Integrated</span>
    <span class="trust__item"><span class="trust__dot"></span>KNEC Compliant</span>
    <span class="trust__item"><span class="trust__dot"></span>Multi-Branch Support</span>
    <span class="trust__item"><span class="trust__dot"></span>Data Protection Act</span>
  </div>
</div>

<!-- FEATURES -->
<section class="section" id="features">
  <div class="wrap">
    <div class="features__hd fi">
      <div class="gr gr--c"></div>
      <h2>Everything Your School Needs</h2>
      <p>From student enrollment to financial reporting, every core function in one platform.</p>
    </div>
    <div class="features__grid">
      <div class="fcard fi">
        <div class="fcard__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
        <h3>Student Management</h3>
        <p>Complete student profiles, enrollment records, CBC assessments, and guardian communication.</p>
      </div>
      <div class="fcard fi">
        <div class="fcard__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg></div>
        <h3>Fee Collection</h3>
        <p>Automated invoicing, M-Pesa integration, payment receipts, and outstanding balance tracking.</p>
      </div>
      <div class="fcard fi">
        <div class="fcard__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
        <h3>Staff and Payroll</h3>
        <p>Staff records, payroll processing, leave management, and teacher-class assignments.</p>
      </div>
      <div class="fcard fi">
        <div class="fcard__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg></div>
        <h3>Exams and Results</h3>
        <p>Mark entry, automated grade computation, customizable report cards, and KNEC grade sheets.</p>
      </div>
      <div class="fcard fi">
        <div class="fcard__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div>
        <h3>Attendance Tracking</h3>
        <p>Daily attendance by class or stream, parent SMS alerts for absences, and monthly analytics.</p>
      </div>
      <div class="fcard fi">
        <div class="fcard__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg></div>
        <h3>Multi-Branch Dashboard</h3>
        <p>Manage multiple campuses from one superadmin account with unified reporting and controls.</p>
      </div>
    </div>
  </div>
</section>

<!-- VALUE PROP -->
<section class="section section--alt" id="about">
  <div class="wrap">
    <div class="value__inner">
      <div class="fi">
        <div class="gr"></div>
        <h2>Designed for Kenyan Schools</h2>
        <p style="color:var(--muted);margin-top:14px;line-height:1.78">
          Unlike generic school software, CST SchoolHub is built from the ground up for the Kenyan
          education context. Every feature reflects the specific requirements of local schools,
          from CBC curriculum to government compliance.
        </p>
        <div class="value__badges">
          <span class="badge">CBC Competency Tracking</span>
          <span class="badge">NEMIS-Aligned Records</span>
          <span class="badge">M-Pesa Fee Collection</span>
          <span class="badge">KNEC Grade Sheets</span>
          <span class="badge">Kenya Data Protection Act</span>
          <span class="badge">Multi-Branch Ready</span>
        </div>
      </div>
      <div class="fi">
        <div class="vcard">
          <div class="vrow"><div class="vcheck">&#10003;</div><span class="vlabel">NEMIS student number tracking and exports</span></div>
          <div class="vrow"><div class="vcheck">&#10003;</div><span class="vlabel">CBC strand and sub-strand assessments</span></div>
          <div class="vrow"><div class="vcheck">&#10003;</div><span class="vlabel">M-Pesa Daraja API fee collection</span></div>
          <div class="vrow"><div class="vcheck">&#10003;</div><span class="vlabel">KNEC exam timetable management</span></div>
          <div class="vrow"><div class="vcheck">&#10003;</div><span class="vlabel">Multi-branch school network support</span></div>
          <div class="vrow"><div class="vcheck">&#10003;</div><span class="vlabel">Kenya Data Protection Act compliance</span></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- HOW IT WORKS -->
<section class="section" id="how-it-works">
  <div class="wrap">
    <div class="how__hd fi">
      <div class="gr gr--c"></div>
      <h2>Up and Running in Days</h2>
      <p>Getting started is straightforward. No complex technical setup required.</p>
    </div>
    <div class="how__steps">
      <div class="hstep fi">
        <div class="hnum">1</div>
        <h3>Register Your School</h3>
        <p>Contact us to create your school account. Onboarding takes less than 24 hours with full support.</p>
      </div>
      <div class="hstep fi">
        <div class="hnum">2</div>
        <h3>Configure Your Setup</h3>
        <p>Add your classes, students, staff, and fee structure. Import existing records if needed.</p>
      </div>
      <div class="hstep fi">
        <div class="hnum">3</div>
        <h3>Run Your School</h3>
        <p>Everything goes live. Collect fees, record attendance, enter marks, and share reports.</p>
      </div>
    </div>
  </div>
</section>

<!-- STATS -->
<section class="stats">
  <div class="wrap">
    <div class="stats__grid">
      <div class="stat fi"><div class="stat__n">20+</div><div class="stat__l">Schools Onboarded</div></div>
      <div class="stat fi"><div class="stat__n">5,000+</div><div class="stat__l">Students Managed</div></div>
      <div class="stat fi"><div class="stat__n">30+</div><div class="stat__l">Platform Modules</div></div>
      <div class="stat fi"><div class="stat__n">99.9%</div><div class="stat__l">Uptime Guaranteed</div></div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta" id="contact">
  <div class="wrap fi">
    <div class="gr gr--c"></div>
    <h2>Ready to Modernize Your School?</h2>
    <p>Get in touch and we will set up a live demonstration tailored to your school's needs.</p>
    <div class="hero__actions" style="justify-content:center">
      <a href="mailto:info@cstschoolhub.co.ke" class="btn btn--gold">Contact Us</a>
      <a href="<?= base_url('authentication') ?>" class="btn btn--ghost">Sign In</a>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer class="footer">
  <div class="wrap">
    <div class="footer__inner">
      <div>
        <div class="footer__brand">CST SchoolHub</div>
        <p class="footer__tag">A cloud-based school management platform built for Kenyan schools. Simplifying administration so educators can focus on teaching.</p>
        <a href="mailto:info@cstschoolhub.co.ke" class="footer__email">info@cstschoolhub.co.ke</a>
      </div>
      <div>
        <div class="footer__col-title">Platform</div>
        <ul class="footer__links">
          <li><a href="#features">Features</a></li>
          <li><a href="#how-it-works">How It Works</a></li>
          <li><a href="mailto:info@cstschoolhub.co.ke">Request Demo</a></li>
          <li><a href="<?= base_url('authentication') ?>">Sign In</a></li>
        </ul>
      </div>
      <div>
        <div class="footer__col-title">Company</div>
        <ul class="footer__links">
          <li><a href="<?= base_url('careers') ?>">Careers</a></li>
          <li><a href="<?= base_url('authentication/privacy_policy') ?>">Privacy Policy</a></li>
          <li><a href="mailto:info@cstschoolhub.co.ke">Contact</a></li>
        </ul>
      </div>
    </div>
    <div class="footer__bottom">
      <span>&copy; <?= date('Y') ?> CST SchoolHub. All rights reserved.</span>
      <a href="<?= base_url('authentication/privacy_policy') ?>">Privacy Policy</a>
    </div>
  </div>
</footer>

<script>
const obs = new IntersectionObserver(entries => {
  entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('v'); });
}, { threshold: 0.1 });
document.querySelectorAll('.fi').forEach(el => obs.observe(el));
</script>
</body>
</html>
