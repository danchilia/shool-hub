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
/* Hamburger */
.nav__burger{display:none;flex-direction:column;gap:5px;background:none;border:none;cursor:pointer;padding:4px;margin-left:auto}
.nav__burger span{display:block;width:24px;height:2px;background:#fff;transition:all .3s}
.nav__burger.open span:nth-child(1){transform:translateY(7px) rotate(45deg)}
.nav__burger.open span:nth-child(2){opacity:0}
.nav__burger.open span:nth-child(3){transform:translateY(-7px) rotate(-45deg)}
.nav__mobile{display:none;position:fixed;top:66px;left:0;right:0;background:var(--navy);border-bottom:1px solid rgba(255,255,255,.1);padding:20px 28px 24px;z-index:199;flex-direction:column;gap:0}
.nav__mobile.open{display:flex}
.nav__mobile a{color:rgba(255,255,255,.72);padding:13px 0;font-size:.95rem;font-weight:500;border-bottom:1px solid rgba(255,255,255,.07);display:block;transition:color .2s}
.nav__mobile a:last-child{border-bottom:none;margin-top:12px;padding:12px 20px;background:var(--gold);color:var(--navy);border-radius:3px;text-align:center;font-weight:700}
.nav__mobile a:hover{color:#fff}

/* HERO */
.hero{background:var(--navy);min-height:90vh;display:flex;align-items:center;position:relative;overflow:hidden}
.hero__bg{position:absolute;inset:0;background-image:url('<?= base_url('assets/images/hero-bg.png') ?>');background-size:cover;background-position:center top;opacity:.45}
.hero::after{content:'';position:absolute;inset:0;background:linear-gradient(90deg,rgba(12,31,63,.82) 35%,rgba(12,31,63,.35) 100%)}
@media(max-width:960px){
  .hero__bg{opacity:.38}
  .hero::after{background:linear-gradient(180deg,rgba(12,31,63,.78) 0%,rgba(12,31,63,.68) 100%)}
}
.hero__inner{position:relative;z-index:2;max-width:1160px;margin:0 auto;padding:90px 28px;display:grid;grid-template-columns:1fr 1fr;gap:64px;align-items:center}
.hero__eyebrow{display:inline-block;font-size:.75rem;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:var(--gold);margin-bottom:20px}
.hero__title{color:#fff;margin-bottom:22px}
.hero__title em{font-style:italic;color:var(--gold)}
.hero__body{color:rgba(255,255,255,.6);font-size:1.05rem;max-width:460px;margin-bottom:40px;line-height:1.78}
.hero__actions{display:flex;gap:14px;flex-wrap:wrap}
/* SLIDER */
.slider{position:relative;border-radius:10px;overflow:hidden;box-shadow:0 32px 64px rgba(0,0,0,.5);aspect-ratio:4/3}
.slider__track{display:flex;width:100%;height:100%}
.slider__slide{min-width:100%;height:100%;position:relative;transition:opacity .8s ease}
.slider__slide img{width:100%;height:100%;object-fit:cover;object-position:center}
.slider__dots{position:absolute;bottom:14px;left:50%;transform:translateX(-50%);display:flex;gap:8px;z-index:10}
.slider__dot{width:8px;height:8px;border-radius:50%;background:rgba(255,255,255,.4);border:none;cursor:pointer;padding:0;transition:background .3s,transform .3s}
.slider__dot.active{background:var(--gold);transform:scale(1.2)}

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
.vdot{width:10px;height:10px;border-radius:50%;background:var(--gold);flex-shrink:0}
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

/* PRICING */
.pricing__hd{text-align:center;max-width:560px;margin:0 auto 52px}
.pricing__hd p{color:var(--muted);margin-top:12px}
.pricing__grid{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;align-items:start}
.pcard{background:var(--surface);border:1px solid var(--border);border-radius:6px;overflow:hidden;transition:transform .22s,box-shadow .22s}
.pcard:hover{transform:translateY(-6px);box-shadow:0 18px 40px rgba(0,0,0,.09)}
.pcard--featured{border-color:var(--gold);box-shadow:0 0 0 2px var(--gold)}
.pcard__head{padding:24px 24px 20px;border-bottom:1px solid var(--border)}
.pcard--featured .pcard__head{background:var(--navy);color:#fff}
.pcard__badge{display:inline-block;font-size:.68rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;padding:3px 10px;background:var(--gold);color:var(--navy);border-radius:20px;margin-bottom:10px}
.pcard__name{font-size:1.05rem;font-weight:700;font-family:'DM Sans',sans-serif;margin-bottom:4px}
.pcard--featured .pcard__name{color:#fff}
.pcard__price{margin-top:14px}
.pcard__amount{font-family:'Cormorant Garamond',serif;font-size:2.4rem;font-weight:700;color:var(--gold);line-height:1}
.pcard__period{font-size:.78rem;color:var(--muted);margin-top:2px}
.pcard--featured .pcard__period{color:rgba(255,255,255,.5)}
.pcard__body{padding:22px 24px}
.pcard__row{display:flex;justify-content:space-between;align-items:center;padding:9px 0;border-bottom:1px solid var(--border);font-size:.84rem}
.pcard__row:last-child{border-bottom:none}
.pcard__row span:first-child{color:var(--muted)}
.pcard__row strong{font-weight:600}
.pcard__cta{display:block;text-align:center;margin:18px 24px 22px;padding:11px;border-radius:3px;font-size:.87rem;font-weight:600;transition:all .2s}
.pcard--featured .pcard__cta{background:var(--gold);color:var(--navy)}
.pcard--featured .pcard__cta:hover{background:#dbb230}
.pcard:not(.pcard--featured) .pcard__cta{background:transparent;border:1.5px solid var(--border);color:var(--text)}
.pcard:not(.pcard--featured) .pcard__cta:hover{border-color:var(--gold);color:var(--gold)}
@media(max-width:960px){.pricing__grid{grid-template-columns:1fr 1fr}}
@media(max-width:600px){.pricing__grid{grid-template-columns:1fr}}

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

/* WhatsApp float */
.wa-float{position:fixed;bottom:28px;right:28px;z-index:300;width:56px;height:56px;background:#25d366;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 6px 20px rgba(0,0,0,.22);transition:transform .2s,box-shadow .2s;text-decoration:none}
.wa-float:hover{transform:scale(1.1);box-shadow:0 10px 28px rgba(0,0,0,.3)}
.wa-float svg{width:30px;height:30px;fill:#fff}

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
  .nav__sign{display:none}
  .nav__burger{display:flex}
}
@media(max-width:600px){
  .features__grid{grid-template-columns:1fr}
  .trust__label{display:none}
  .footer__bottom{flex-direction:column;gap:6px;text-align:center}
  .wa-float{bottom:18px;right:18px;width:50px;height:50px}
  .hero__inner{padding:70px 28px 60px}
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
      <li><a href="#pricing">Pricing</a></li>
      <li><a href="<?= base_url('careers') ?>">Careers</a></li>
      <li><a href="#contact">Contact</a></li>
    </ul>
    <a href="<?= base_url('authentication') ?>" class="nav__sign">Sign In</a>
    <button class="nav__burger" id="navBurger" aria-label="Open menu">
      <span></span><span></span><span></span>
    </button>
  </div>
  <!-- Mobile menu -->
  <div class="nav__mobile" id="navMobile">
    <a href="#features">Features</a>
    <a href="#how-it-works">How It Works</a>
    <a href="#pricing">Pricing</a>
    <a href="<?= base_url('careers') ?>">Careers</a>
    <a href="#contact">Contact</a>
    <a href="<?= base_url('authentication') ?>">Sign In</a>
  </div>
</nav>

<!-- HERO -->
<section class="hero">
  <div class="hero__bg"></div>
  <div class="hero__inner">
    <div>
      <span class="hero__eyebrow">School Management Platform</span>
      <h1 class="hero__title">School Administration,<br><em>Fully in Control.</em></h1>
      <p class="hero__body">
        CST SchoolHub is a cloud-based platform built for Kenyan schools.
        Manage students, fees, staff, exams, and attendance from a single dashboard.
      </p>
      <div class="hero__actions">
        <a href="<?= base_url('contact') ?>" class="btn btn--gold">Request a Demo</a>
        <a href="#features" class="btn btn--ghost">View Features <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-left:2px"><polyline points="6 9 12 15 18 9"/></svg></a>
      </div>
    </div>
    <div class="hero__visual">
      <div class="slider" id="heroSlider">
        <div class="slider__track" id="sliderTrack">
          <div class="slider__slide">
            <img src="<?= base_url('uploads/login_image/slider_1.jpg') ?>" alt="School Management">
          </div>
          <div class="slider__slide">
            <img src="<?= base_url('uploads/login_image/slider_2.jpg') ?>" alt="School Administration">
          </div>
          <div class="slider__slide">
            <img src="<?= base_url('uploads/login_image/slider_3.jpg') ?>" alt="School Dashboard">
          </div>
        </div>
        <div class="slider__dots" id="sliderDots">
          <button class="slider__dot active" data-i="0"></button>
          <button class="slider__dot" data-i="1"></button>
          <button class="slider__dot" data-i="2"></button>
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
          <div class="vrow"><span class="vdot"></span><span class="vlabel">NEMIS student number tracking and exports</span></div>
          <div class="vrow"><span class="vdot"></span><span class="vlabel">CBC strand and sub-strand assessments</span></div>
          <div class="vrow"><span class="vdot"></span><span class="vlabel">M-Pesa Daraja API fee collection</span></div>
          <div class="vrow"><span class="vdot"></span><span class="vlabel">KNEC exam timetable management</span></div>
          <div class="vrow"><span class="vdot"></span><span class="vlabel">Multi-branch school network support</span></div>
          <div class="vrow"><span class="vdot"></span><span class="vlabel">Kenya Data Protection Act compliance</span></div>
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

<!-- PRICING -->
<section class="section section--alt" id="pricing">
  <div class="wrap">
    <div class="pricing__hd fi">
      <div class="gr gr--c"></div>
      <h2>Simple, Transparent Pricing</h2>
      <p>Choose the plan that fits your school. All plans include full platform access with no hidden fees.</p>
    </div>
    <div class="pricing__grid">

      <!-- Basic -->
      <div class="pcard fi">
        <div class="pcard__head">
          <div class="pcard__name">Basic</div>
          <div class="pcard__price">
            <div class="pcard__amount">3,000</div>
            <div class="pcard__period">KES / month &nbsp;&bull;&nbsp; KES 30,000/yr</div>
          </div>
        </div>
        <div class="pcard__body">
          <div class="pcard__row"><span>Max Students</span><strong>500</strong></div>
          <div class="pcard__row"><span>Max Staff</span><strong>50</strong></div>
          <div class="pcard__row"><span>All Modules</span><strong>Included</strong></div>
          <div class="pcard__row"><span>Support</span><strong>Email</strong></div>
        </div>
        <a href="<?= base_url('contact?plan=Basic') ?>" class="pcard__cta">Get Started</a>
      </div>

      <!-- Standard -->
      <div class="pcard fi">
        <div class="pcard__head">
          <div class="pcard__name">Standard</div>
          <div class="pcard__price">
            <div class="pcard__amount">5,000</div>
            <div class="pcard__period">KES / month &nbsp;&bull;&nbsp; KES 50,000/yr</div>
          </div>
        </div>
        <div class="pcard__body">
          <div class="pcard__row"><span>Max Students</span><strong>800</strong></div>
          <div class="pcard__row"><span>Max Staff</span><strong>50</strong></div>
          <div class="pcard__row"><span>All Modules</span><strong>Included</strong></div>
          <div class="pcard__row"><span>Support</span><strong>Email + Phone</strong></div>
        </div>
        <a href="<?= base_url('contact?plan=Standard') ?>" class="pcard__cta">Get Started</a>
      </div>

      <!-- Premium (featured) -->
      <div class="pcard pcard--featured fi">
        <div class="pcard__head">
          <div class="pcard__badge">Most Popular</div>
          <div class="pcard__name">Premium</div>
          <div class="pcard__price">
            <div class="pcard__amount">20,000</div>
            <div class="pcard__period">KES / month &nbsp;&bull;&nbsp; KES 200,000/yr</div>
          </div>
        </div>
        <div class="pcard__body">
          <div class="pcard__row"><span>Max Students</span><strong>2,000</strong></div>
          <div class="pcard__row"><span>Max Staff</span><strong>200</strong></div>
          <div class="pcard__row"><span>All Modules</span><strong>Included</strong></div>
          <div class="pcard__row"><span>Support</span><strong>Priority</strong></div>
        </div>
        <a href="<?= base_url('contact?plan=Premium') ?>" class="pcard__cta">Get Started</a>
      </div>

      <!-- Unlimited -->
      <div class="pcard fi">
        <div class="pcard__head">
          <div class="pcard__name">Unlimited</div>
          <div class="pcard__price">
            <div class="pcard__amount">50,000</div>
            <div class="pcard__period">KES / month &nbsp;&bull;&nbsp; KES 500,000/yr</div>
          </div>
        </div>
        <div class="pcard__body">
          <div class="pcard__row"><span>Max Students</span><strong>Unlimited</strong></div>
          <div class="pcard__row"><span>Max Staff</span><strong>Unlimited</strong></div>
          <div class="pcard__row"><span>All Modules</span><strong>Included</strong></div>
          <div class="pcard__row"><span>Support</span><strong>Dedicated</strong></div>
        </div>
        <a href="<?= base_url('contact?plan=Unlimited') ?>" class="pcard__cta">Get Started</a>
      </div>

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
      <a href="<?= base_url('contact') ?>" class="btn btn--gold">Contact Us</a>
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
          <li><a href="<?= base_url('contact') ?>">Request Demo</a></li>
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
// fade-in on scroll
const obs = new IntersectionObserver(entries => {
  entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('v'); });
}, { threshold: 0.1 });
document.querySelectorAll('.fi').forEach(el => obs.observe(el));

// image slider
(function(){
  var track = document.getElementById('sliderTrack');
  var dots = document.querySelectorAll('.slider__dot');
  var current = 0;
  var total = 3;

  function goTo(n) {
    current = (n + total) % total;
    track.style.transform = 'translateX(-' + (current * 100 / total) + '%)';
    track.style.transition = 'transform .7s ease';
    dots.forEach(function(d, i){ d.classList.toggle('active', i === current); });
  }

  dots.forEach(function(d){
    d.addEventListener('click', function(){ goTo(parseInt(this.dataset.i)); });
  });

  var timer = setInterval(function(){ goTo(current + 1); }, 5000);
  document.getElementById('heroSlider').addEventListener('mouseenter', function(){ clearInterval(timer); });
  document.getElementById('heroSlider').addEventListener('mouseleave', function(){ timer = setInterval(function(){ goTo(current + 1); }, 5000); });

  track.style.display = 'flex';
  track.style.width = (total * 100) + '%';
  track.querySelectorAll('.slider__slide').forEach(function(s){ s.style.minWidth = (100/total) + '%'; });
})();

// Hamburger menu
(function(){
  var burger = document.getElementById('navBurger');
  var mobile = document.getElementById('navMobile');
  burger.addEventListener('click', function(){
    burger.classList.toggle('open');
    mobile.classList.toggle('open');
  });
  mobile.querySelectorAll('a').forEach(function(a){
    a.addEventListener('click', function(){
      burger.classList.remove('open');
      mobile.classList.remove('open');
    });
  });
})();

// Animated stat counters
(function(){
  var counted = false;
  var stats = [
    { el: document.querySelectorAll('.stat__n')[0], target: 20,   suffix: '+',   duration: 1400 },
    { el: document.querySelectorAll('.stat__n')[1], target: 5000, suffix: '+',   duration: 1800 },
    { el: document.querySelectorAll('.stat__n')[2], target: 30,   suffix: '+',   duration: 1200 },
    { el: document.querySelectorAll('.stat__n')[3], target: 99.9, suffix: '%',   duration: 1600, decimal: true }
  ];

  function animateCount(item) {
    var start = 0;
    var startTime = null;
    function step(ts) {
      if (!startTime) startTime = ts;
      var progress = Math.min((ts - startTime) / item.duration, 1);
      var ease = 1 - Math.pow(1 - progress, 3);
      var val = item.decimal ? (ease * item.target).toFixed(1) : Math.floor(ease * item.target);
      if (item.el) item.el.textContent = (item.target >= 1000 ? Number(val).toLocaleString() : val) + item.suffix;
      if (progress < 1) requestAnimationFrame(step);
    }
    requestAnimationFrame(step);
  }

  var statsSection = document.querySelector('.stats');
  if (statsSection) {
    var countObs = new IntersectionObserver(function(entries){
      if (entries[0].isIntersecting && !counted) {
        counted = true;
        stats.forEach(function(s){ animateCount(s); });
      }
    }, { threshold: 0.3 });
    countObs.observe(statsSection);
  }
})();
</script>
<!-- WhatsApp float -->
<a href="https://wa.me/254700000000?text=Hi%2C%20I%27d%20like%20to%20learn%20more%20about%20CST%20SchoolHub"
   class="wa-float" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
  <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
  </svg>
</a>

</body>
</html>
