<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Online Admission Portal</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8;min-height:100vh}
.portal-hero{background:linear-gradient(135deg,#1a5276,#2e86c1);color:#fff;padding:48px 24px;text-align:center}
.portal-hero h1{font-size:2rem;margin-bottom:8px}
.portal-hero p{opacity:.85;font-size:1rem}
.container{max-width:900px;margin:0 auto;padding:32px 16px}
.school-cards{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:24px;margin-top:24px}
.school-card{background:#fff;border-radius:10px;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,.08);border-top:4px solid #2e86c1}
.school-card h3{color:#1a5276;margin-bottom:8px;font-size:1.1rem}
.school-card p{color:#666;font-size:.88rem;margin-bottom:4px}
.school-card p i{width:16px;text-align:center;color:#2e86c1;margin-right:6px}
.apply-btn{display:inline-block;margin-top:16px;background:#2e86c1;color:#fff;padding:10px 22px;border-radius:6px;text-decoration:none;font-weight:600;font-size:.9rem;transition:background .2s}
.apply-btn:hover{background:#1a5276}
.empty{text-align:center;padding:60px 20px;color:#999}
@media(max-width:600px){.portal-hero h1{font-size:1.4rem}}
</style>
</head>
<body>

<div class="portal-hero">
    <h1>Online Admission Portal</h1>
    <p>Apply for admission at any of our campuses. Applications are reviewed by the admissions office.</p>
</div>

<div class="container">
    <?php if (empty($branches)): ?>
        <div class="empty"><p>No schools currently accepting online applications.</p></div>
    <?php else: ?>
    <h2 style="color:#1a5276;margin-bottom:4px">Select a School to Apply</h2>
    <p style="color:#666;font-size:.9rem;margin-bottom:4px">Choose the campus you want to apply to:</p>
    <div class="school-cards">
        <?php foreach ($branches as $b): ?>
        <div class="school-card">
            <h3><?= htmlspecialchars($b['name']) ?></h3>
            <?php if (!empty($b['address'])): ?>
            <p><i class="fas fa-map-marker-alt"></i><?= htmlspecialchars($b['address']) ?></p>
            <?php endif; ?>
            <?php if (!empty($b['mobileno'])): ?>
            <p><i class="fas fa-phone"></i><?= htmlspecialchars($b['mobileno']) ?></p>
            <?php endif; ?>
            <?php if (!empty($b['email'])): ?>
            <p><i class="fas fa-envelope"></i><?= htmlspecialchars($b['email']) ?></p>
            <?php endif; ?>
            <a href="<?= base_url('admission_portal/apply/' . $b['id']) ?>" class="apply-btn">Apply Now &rarr;</a>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <p style="text-align:center;margin-top:40px;color:#aaa;font-size:.8rem">
        Already applied? Track your application using the reference number emailed to you.
    </p>
</div>
</body>
</html>
