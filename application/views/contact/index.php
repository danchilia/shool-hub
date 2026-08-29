<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Contact Us | CST SchoolHub</title>
<meta name="description" content="Request a demo or get in touch with the CST SchoolHub team.">
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
body{font-family:'DM Sans',sans-serif;background:var(--surface);color:var(--text);font-size:16px;line-height:1.65;min-height:100vh}
a{color:inherit;text-decoration:none}
h1,h2,h3{font-family:'Cormorant Garamond',serif;line-height:1.15;text-wrap:balance}

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
@media(max-width:720px){.nav__links{display:none}}

/* PAGE */
.page{background:var(--navy);padding:80px 28px 0;min-height:260px;display:flex;flex-direction:column;align-items:center;justify-content:flex-end;text-align:center}
.page__eyebrow{font-size:.72rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--gold);margin-bottom:14px}
.page__title{font-size:clamp(2rem,4vw,3rem);font-weight:700;color:#fff;margin-bottom:12px}
.page__sub{color:rgba(255,255,255,.55);font-size:1rem;max-width:480px;margin:0 auto 52px}

/* FORM SECTION */
.contact-wrap{max-width:780px;margin:-56px auto 80px;padding:0 28px}
.cform{background:var(--surface);border-radius:8px;box-shadow:0 24px 64px rgba(0,0,0,.12);padding:48px 44px}
@media(max-width:600px){.cform{padding:32px 22px}}

.cform__grid{display:grid;grid-template-columns:1fr 1fr;gap:22px}
@media(max-width:600px){.cform__grid{grid-template-columns:1fr}}

.fg{display:flex;flex-direction:column;gap:6px}
.fg--full{grid-column:1/-1}
label{font-size:.82rem;font-weight:600;color:var(--text);letter-spacing:.01em}
label span{color:#c0392b;margin-left:2px}
input,select,textarea{font-family:'DM Sans',sans-serif;font-size:.9rem;padding:11px 14px;border:1.5px solid var(--border);border-radius:4px;color:var(--text);background:#fff;width:100%;transition:border-color .2s,box-shadow .2s;outline:none}
input:focus,select:focus,textarea:focus{border-color:var(--gold);box-shadow:0 0 0 3px rgba(201,162,39,.12)}
textarea{resize:vertical;min-height:110px}
select{appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%235a6a7a' d='M6 8L0 0h12z'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 14px center;padding-right:36px}
.error-text{font-size:.78rem;color:#c0392b;margin-top:2px}

.btn-submit{display:inline-flex;align-items:center;gap:10px;padding:14px 36px;background:var(--gold);color:var(--navy);font-family:'DM Sans',sans-serif;font-weight:700;font-size:.92rem;border:none;border-radius:3px;cursor:pointer;transition:background .2s;margin-top:8px}
.btn-submit:hover{background:#dbb230}
.btn-submit i{font-size:.85rem}

/* SUCCESS */
.success-box{text-align:center;padding:52px 28px}
.success-box .icon{width:72px;height:72px;border-radius:50%;background:var(--gold-dim);border:2px solid var(--gold);display:flex;align-items:center;justify-content:center;margin:0 auto 24px;font-size:1.8rem;color:var(--gold)}
.success-box h2{margin-bottom:10px;font-size:1.9rem}
.success-box p{color:var(--muted);max-width:380px;margin:0 auto 28px;font-size:.95rem}
.success-box a{display:inline-flex;align-items:center;gap:8px;padding:12px 28px;background:var(--gold);color:var(--navy);font-weight:700;font-size:.88rem;border-radius:3px;transition:background .2s}
.success-box a:hover{background:#dbb230}

/* FOOTER STRIP */
.foot-strip{background:var(--navy);padding:26px 28px;text-align:center;color:rgba(255,255,255,.3);font-size:.78rem}
.foot-strip a{color:rgba(255,255,255,.38)}
.foot-strip a:hover{color:rgba(255,255,255,.65)}
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
      <li><a href="<?= base_url() ?>#features">Features</a></li>
      <li><a href="<?= base_url() ?>#pricing">Pricing</a></li>
      <li><a href="<?= base_url('careers') ?>">Careers</a></li>
    </ul>
    <a href="<?= base_url('authentication') ?>" class="nav__sign">Sign In</a>
  </div>
</nav>

<!-- PAGE HEADER -->
<div class="page">
  <span class="page__eyebrow">Get in Touch</span>
  <h1 class="page__title">Request a Demo</h1>
  <p class="page__sub">Fill in your details and we will reach out within one business day to schedule a live walkthrough.</p>
</div>

<!-- FORM -->
<div class="contact-wrap">
  <div class="cform">
    <?php if ($success): ?>
      <div class="success-box">
        <div class="icon"><i class="fas fa-check"></i></div>
        <h2>Request Received</h2>
        <p>Thank you. We have received your details and will contact you within one business day.</p>
        <a href="<?= base_url() ?>"><i class="fas fa-arrow-left"></i> Back to Home</a>
      </div>
    <?php else: ?>
      <?php echo form_open(current_url()); ?>
        <div class="cform__grid">

          <div class="fg">
            <label>Full Name <span>*</span></label>
            <input type="text" name="full_name" value="<?= set_value('full_name') ?>" placeholder="Your full name">
            <?php if (form_error('full_name')): ?><span class="error-text"><?= form_error('full_name') ?></span><?php endif; ?>
          </div>

          <div class="fg">
            <label>School Name <span>*</span></label>
            <input type="text" name="school_name" value="<?= set_value('school_name') ?>" placeholder="Name of your school">
            <?php if (form_error('school_name')): ?><span class="error-text"><?= form_error('school_name') ?></span><?php endif; ?>
          </div>

          <div class="fg">
            <label>Phone Number <span>*</span></label>
            <input type="tel" name="phone" value="<?= set_value('phone') ?>" placeholder="+254 7XX XXX XXX">
            <?php if (form_error('phone')): ?><span class="error-text"><?= form_error('phone') ?></span><?php endif; ?>
          </div>

          <div class="fg">
            <label>Email Address</label>
            <input type="email" name="email" value="<?= set_value('email') ?>" placeholder="school@example.com">
            <?php if (form_error('email')): ?><span class="error-text"><?= form_error('email') ?></span><?php endif; ?>
          </div>

          <div class="fg fg--full">
            <label>Plan of Interest</label>
            <select name="plan">
              <option value="" <?= set_value('plan', $plan) === '' ? 'selected' : '' ?>>Not sure yet — show me all options</option>
              <option value="Basic"     <?= set_value('plan', $plan) === 'Basic'     ? 'selected' : '' ?>>Basic — KES 3,000/month (up to 500 students)</option>
              <option value="Standard"  <?= set_value('plan', $plan) === 'Standard'  ? 'selected' : '' ?>>Standard — KES 5,000/month (up to 800 students)</option>
              <option value="Premium"   <?= set_value('plan', $plan) === 'Premium'   ? 'selected' : '' ?>>Premium — KES 20,000/month (up to 2,000 students)</option>
              <option value="Unlimited" <?= set_value('plan', $plan) === 'Unlimited' ? 'selected' : '' ?>>Unlimited — KES 50,000/month (no limits)</option>
            </select>
          </div>

          <div class="fg fg--full">
            <label>Message (optional)</label>
            <textarea name="message" placeholder="Any specific requirements or questions..."><?= set_value('message') ?></textarea>
          </div>

        </div>
        <button type="submit" name="submit" value="1" class="btn-submit">
          <i class="fas fa-paper-plane"></i> Send Request
        </button>
      <?php echo form_close(); ?>
    <?php endif; ?>
  </div>
</div>

<!-- FOOTER STRIP -->
<div class="foot-strip">
  <span>&copy; <?= date('Y') ?> CST SchoolHub &bull; <a href="mailto:info@cstschoolhub.co.ke">info@cstschoolhub.co.ke</a> &bull; <a href="<?= base_url('authentication/privacy_policy') ?>">Privacy Policy</a></span>
</div>

</body>
</html>
