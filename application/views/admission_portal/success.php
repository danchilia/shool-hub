<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Application Submitted</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px}
.card{background:#fff;border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,.1);max-width:540px;width:100%;padding:40px;text-align:center}
.icon{font-size:3.5rem;color:#27ae60;margin-bottom:16px}
h1{color:#1a5276;font-size:1.6rem;margin-bottom:8px}
p{color:#555;font-size:.95rem;line-height:1.6;margin-bottom:12px}
.token-box{background:#f8f9fa;border:2px dashed #2e86c1;border-radius:8px;padding:16px;margin:20px 0;font-family:monospace;font-size:1rem;color:#1a5276;word-break:break-all}
.token-label{font-size:.8rem;color:#666;margin-bottom:8px;display:block}
.status{display:inline-block;background:#fef9e7;color:#7d6608;border:1px solid #f9e79f;padding:6px 18px;border-radius:20px;font-size:.88rem;font-weight:600;margin:12px 0}
.btn{display:inline-block;margin-top:20px;background:#2e86c1;color:#fff;padding:12px 28px;border-radius:8px;text-decoration:none;font-weight:600;font-size:.95rem}
.steps{text-align:left;margin:20px 0;background:#eaf4fc;border-radius:8px;padding:16px 20px}
.steps h3{color:#1a5276;font-size:.95rem;margin-bottom:10px}
.steps ol{padding-left:20px;color:#444;font-size:.88rem;line-height:2}
</style>
</head>
<body>
<div class="card">
    <div class="icon">&#10003;</div>
    <h1>Application Submitted!</h1>
    <p>Thank you for applying to <strong><?= htmlspecialchars($branch['name'] ?? '') ?></strong>.</p>
    <p>Your application for <strong><?= htmlspecialchars(($student['first_name'] ?? '') . ' ' . ($student['last_name'] ?? '')) ?></strong> has been received and is now pending review.</p>

    <div class="status">&#9679; Pending Review</div>

    <div class="token-box">
        <span class="token-label">Your Application Reference Number (save this!):</span>
        <?= htmlspecialchars($token) ?>
    </div>

    <div class="steps">
        <h3>What happens next?</h3>
        <ol>
            <li>The admissions office will review your application</li>
            <li>You will be contacted via phone or email</li>
            <li>If approved, you will receive login credentials</li>
        </ol>
    </div>

    <p style="font-size:.82rem;color:#999">
        Please save the reference number above. The admissions office may ask for it when following up.
    </p>

    <a href="<?= base_url('admission_portal') ?>" class="btn">Apply for Another School</a>
</div>
</body>
</html>
