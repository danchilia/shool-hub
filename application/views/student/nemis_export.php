<?php
/** @var array $classes @var array $sections @var array $students @var string $class_id @var string $section_id @var string $branch_id @var array $branches */
$classes    = isset($classes)    ? $classes    : array();
$sections   = isset($sections)   ? $sections   : array();
$students   = isset($students)   ? $students   : array();
$branches   = isset($branches)   ? $branches   : array();
$class_id   = isset($class_id)   ? $class_id   : '';
$section_id = isset($section_id) ? $section_id : '';
$branch_id  = isset($branch_id)  ? $branch_id  : '';
?>
<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <h4 class="panel-title">
                    <i class="fas fa-file-csv" style="color:#10b981;"></i>
                    NEMIS Export &mdash; Generate CSV for NEMIS Portal Upload
                </h4>
            </header>
            <div class="panel-body">
                <div class="alert alert-info">
                    <strong><i class="fas fa-info-circle"></i> How to use:</strong>
                    Select a class/stream, preview the students, then download the CSV.
                    Upload the CSV file at <strong>nemis.go.ke</strong> under <em>Learner Registration</em>.
                    UPI Numbers must be filled in the student profiles before export.
                </div>

                <!-- Filter Form -->
                <form method="get" action="<?=base_url('student/nemis_export')?>" class="form-inline" style="gap:12px;display:flex;flex-wrap:wrap;margin-bottom:20px;">
                    <?php if (is_superadmin_loggedin()): ?>
                    <div class="form-group">
                        <label class="control-label mr-sm">Branch <span class="required">*</span></label>
                        <select name="branch_id" id="nemis_branch" class="form-control" required>
                            <option value="">— Select Branch —</option>
                            <?php foreach ($branches as $b): ?>
                            <option value="<?=$b['id']?>" <?=$branch_id == $b['id'] ? 'selected' : ''?>>
                                <?=htmlspecialchars($b['name'])?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <label class="control-label mr-sm">Class <span class="required">*</span></label>
                        <select name="class_id" id="nemis_class" class="form-control" required>
                            <option value="">— Select Class —</option>
                            <?php foreach ($classes as $c): ?>
                            <option value="<?=$c['id']?>" <?=$class_id == $c['id'] ? 'selected' : ''?>>
                                <?=htmlspecialchars($c['name'])?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label mr-sm">Stream</label>
                        <select name="section_id" id="nemis_section" class="form-control">
                            <option value="">All Streams</option>
                            <?php foreach ($sections as $sec): ?>
                            <option value="<?=$sec['id']?>" <?=$section_id == $sec['id'] ? 'selected' : ''?>>
                                <?=htmlspecialchars($sec['name'])?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-default">
                        <i class="fas fa-search"></i> Preview
                    </button>
                </form>

                <?php if (!empty($students)): ?>
                <!-- Download button -->
                <form method="post" action="<?=base_url('student/nemis_download')?>" style="margin-bottom:16px;">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" name="branch_id" value="<?=$branch_id?>">
                    <input type="hidden" name="class_id" value="<?=$class_id?>">
                    <input type="hidden" name="section_id" value="<?=$section_id?>">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-download"></i> Download NEMIS CSV (<?=count($students)?> learners)
                    </button>
                    <span class="text-muted" style="margin-left:10px;font-size:.8rem;">
                        CSV formatted for NEMIS portal upload
                    </span>
                </form>

                <!-- Preview Table -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" style="font-size:.82rem;">
                        <thead style="background:#0b1929;color:#fff;">
                            <tr>
                                <th>#</th>
                                <th>Surname</th>
                                <th>Other Names</th>
                                <th>DOB</th>
                                <th>Gender</th>
                                <th>Adm No</th>
                                <th style="background:<?=count(array_filter($students, fn($s) => empty($s['upi_number']))) > 0 ? '#7f1d1d' : '#155724'?>;color:#fff;">
                                    UPI Number
                                </th>
                                <th>Class</th>
                                <th>Stream</th>
                                <th>Parent/Guardian</th>
                                <th>Parent Phone</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $missingUpi = 0; foreach ($students as $i => $s): ?>
                            <?php if (empty($s['upi_number'])) $missingUpi++; ?>
                            <tr <?=empty($s['upi_number']) ? 'style="background:rgba(239,68,68,.08);"' : ''?>>
                                <td><?=$i+1?></td>
                                <td><strong><?=htmlspecialchars(strtoupper($s['last_name']))?></strong></td>
                                <td><?=htmlspecialchars($s['first_name'])?></td>
                                <td><?=!empty($s['birthday']) ? date('d/m/Y', strtotime($s['birthday'])) : '<span class="text-muted">—</span>'?></td>
                                <td><?=ucfirst($s['gender'])?></td>
                                <td><?=htmlspecialchars($s['register_no'])?></td>
                                <td>
                                    <?php if (!empty($s['upi_number'])): ?>
                                        <span style="font-family:monospace;color:#155724;font-weight:600;"><?=htmlspecialchars($s['upi_number'])?></span>
                                    <?php else: ?>
                                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> Missing</span>
                                    <?php endif; ?>
                                </td>
                                <td><?=htmlspecialchars($s['class_name'])?></td>
                                <td><?=htmlspecialchars($s['section_name'] ?: '—')?></td>
                                <td><?=htmlspecialchars($s['guardian_name'] ?: '—')?></td>
                                <td><?=htmlspecialchars($s['guardian_phone'] ?: '—')?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($missingUpi > 0): ?>
                <div class="alert alert-warning" style="margin-top:10px;">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong><?=$missingUpi?> learner(s) have no UPI number.</strong>
                    Go to <a href="<?=base_url('student')?>">Student Profiles</a> and enter UPI numbers before uploading to NEMIS.
                    Rows with missing UPI will be exported as empty — NEMIS will skip them.
                </div>
                <?php endif; ?>

                <?php elseif ($class_id): ?>
                <div class="alert alert-info">No students found for this selection.</div>
                <?php endif; ?>

            </div>
        </section>
    </div>
</div>

<script>
$(function() {
    // Superadmin: reload page with new branch so class list updates server-side
    $('#nemis_branch').on('change', function() {
        var bid = $(this).val();
        if (!bid) return;
        window.location.href = base_url + 'student/nemis_export?branch_id=' + bid;
    });

    // When class changes, reload section dropdown via AJAX
    $('#nemis_class').on('change', function() {
        var cid = $(this).val();
        var $sec = $('#nemis_section');
        $sec.html('<option value="">All Streams</option>');
        if (!cid) return;
        $.post(base_url + 'ajax/getSectionByClass', {class_id: cid, all: 0, multi: 0}, function(html) {
            var $opts = $('<div>').html(html).find('option').filter(function() { return $(this).val() !== ''; });
            $sec.append($opts);
        });
    });
});
</script>
