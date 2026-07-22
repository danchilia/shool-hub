<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Apply for Admission — <?= htmlspecialchars($branch['name'] ?? '') ?></title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8}
.portal-header{background:linear-gradient(135deg,#1a5276,#2e86c1);color:#fff;padding:28px 24px}
.portal-header h1{font-size:1.5rem;margin-bottom:4px}
.portal-header p{opacity:.8;font-size:.9rem}
.container{max-width:760px;margin:32px auto;padding:0 16px 48px}
.card{background:#fff;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.08);margin-bottom:24px;overflow:hidden}
.card-header{background:#1a5276;color:#fff;padding:14px 20px;font-weight:700;font-size:1rem}
.card-body{padding:20px}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.form-group{margin-bottom:16px}
.form-group label{display:block;font-size:.85rem;font-weight:600;color:#444;margin-bottom:6px}
.form-group input,.form-group select,.form-group textarea{width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:.9rem;color:#333;background:#fff}
.form-group input:focus,.form-group select:focus{outline:none;border-color:#2e86c1;box-shadow:0 0 0 2px rgba(46,134,193,.15)}
.form-group .error{color:#c0392b;font-size:.8rem;margin-top:4px}
.required{color:#c0392b}
.submit-btn{background:#2e86c1;color:#fff;border:none;padding:14px 32px;border-radius:8px;font-size:1rem;font-weight:700;cursor:pointer;width:100%;margin-top:8px;transition:background .2s}
.submit-btn:hover{background:#1a5276}
.note{background:#eaf4fc;border-left:4px solid #2e86c1;padding:12px 16px;border-radius:4px;font-size:.85rem;color:#1a5276;margin-bottom:20px}
.back-link{color:#2e86c1;text-decoration:none;font-size:.88rem;display:inline-block;margin-bottom:16px}
@media(max-width:600px){.form-row{grid-template-columns:1fr}}
</style>
</head>
<body>

<div class="portal-header">
    <h1><?= htmlspecialchars($branch['name'] ?? '') ?></h1>
    <p>Online Admission Application Form</p>
</div>

<div class="container">
    <a href="<?= base_url('admission_portal') ?>" class="back-link">&larr; Back to Schools</a>

    <div class="note">
        <strong>Instructions:</strong> Fill in all required fields marked with <span style="color:#c0392b">*</span>.
        Your application will be reviewed by the admissions office. You will receive a tracking reference on submission.
    </div>

    <?php
    $errors = isset($validation_errors) ? $validation_errors : array();
    echo form_open('admission_portal/submit');
    ?>
    <input type="hidden" name="branch_id" value="<?= $branch['id'] ?? '' ?>">

    <!-- Student Details -->
    <div class="card">
        <div class="card-header">Student / Child Details</div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label>First Name <span class="required">*</span></label>
                    <input type="text" name="first_name" value="<?= set_value('first_name') ?>" placeholder="e.g. John">
                    <?php if (!empty($errors['first_name'])): ?><div class="error"><?= $errors['first_name'] ?></div><?php endif; ?>
                </div>
                <div class="form-group">
                    <label>Last Name <span class="required">*</span></label>
                    <input type="text" name="last_name" value="<?= set_value('last_name') ?>" placeholder="e.g. Mwangi">
                    <?php if (!empty($errors['last_name'])): ?><div class="error"><?= $errors['last_name'] ?></div><?php endif; ?>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Gender</label>
                    <select name="gender">
                        <option value="">— Select —</option>
                        <option value="male" <?= set_select('gender','male') ?>>Male</option>
                        <option value="female" <?= set_select('gender','female') ?>>Female</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Date of Birth</label>
                    <input type="date" name="birthday" value="<?= set_value('birthday') ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>UPI / NEMIS Number</label>
                    <input type="text" name="upi_number" value="<?= set_value('upi_number') ?>" placeholder="If already assigned">
                </div>
                <div class="form-group">
                    <label>Religion</label>
                    <input type="text" name="religion" value="<?= set_value('religion') ?>" placeholder="e.g. Christian">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Applying for Class <span class="required">*</span></label>
                    <select name="class_id" id="class_id" onchange="loadSections(this.value)">
                        <option value="">— Select Class —</option>
                        <?php foreach ($classes as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= set_select('class_id', $c['id']) ?>><?= htmlspecialchars($c['class']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($errors['class_id'])): ?><div class="error"><?= $errors['class_id'] ?></div><?php endif; ?>
                </div>
                <div class="form-group">
                    <label>Stream / Section</label>
                    <select name="section_id" id="section_id">
                        <option value="">— Select Section —</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>Home Address</label>
                <textarea name="address" rows="2" placeholder="Physical address"><?= set_value('address') ?></textarea>
            </div>
        </div>
    </div>

    <!-- Parent / Guardian Details -->
    <div class="card">
        <div class="card-header">Parent / Guardian Details</div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label>Guardian Full Name <span class="required">*</span></label>
                    <input type="text" name="grd_name" value="<?= set_value('grd_name') ?>" placeholder="e.g. Jane Mwangi">
                    <?php if (!empty($errors['grd_name'])): ?><div class="error"><?= $errors['grd_name'] ?></div><?php endif; ?>
                </div>
                <div class="form-group">
                    <label>Relationship to Child</label>
                    <select name="grd_relation">
                        <option value="Father" <?= set_select('grd_relation','Father') ?>>Father</option>
                        <option value="Mother" <?= set_select('grd_relation','Mother') ?>>Mother</option>
                        <option value="Guardian" <?= set_select('grd_relation','Guardian') ?>>Guardian</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Phone Number <span class="required">*</span></label>
                    <input type="tel" name="grd_mobileno" value="<?= set_value('grd_mobileno') ?>" placeholder="e.g. 0712345678">
                    <?php if (!empty($errors['grd_mobileno'])): ?><div class="error"><?= $errors['grd_mobileno'] ?></div><?php endif; ?>
                </div>
                <div class="form-group">
                    <label>Email Address <span class="required">*</span></label>
                    <input type="email" name="grd_email" value="<?= set_value('grd_email') ?>" placeholder="e.g. jane@example.com">
                    <?php if (!empty($errors['grd_email'])): ?><div class="error"><?= $errors['grd_email'] ?></div><?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="submit-btn">Submit Application &rarr;</button>
    <?php echo form_close(); ?>
</div>

<script>
function loadSections(classId) {
    if (!classId) return;
    fetch('<?= base_url('admission_portal/sections/') ?>' + classId)
        .then(r => r.json())
        .then(data => {
            var sel = document.getElementById('section_id');
            sel.innerHTML = '<option value="">— Select Section —</option>';
            data.forEach(function(s) {
                sel.innerHTML += '<option value="' + s.id + '">' + s.section_name + '</option>';
            });
        });
}
</script>
</body>
</html>
