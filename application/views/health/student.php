<?php
$student      = isset($student)      ? $student      : array();
$health       = isset($health)       ? $health       : array();
$vaccinations = isset($vaccinations) ? $vaccinations : array();
$visits       = isset($visits)       ? $visits       : array();
?>
<div class="row">
    <div class="col-md-12">
        <!-- Student Banner -->
        <section class="panel" style="border-top:3px solid #e74c3c">
            <div class="panel-body" style="display:flex;align-items:center;gap:16px">
                <img src="<?= get_image_url('student', $student['photo'] ?? '') ?>" width="60" height="60" class="rounded" style="object-fit:cover">
                <div>
                    <h4 class="mb-none"><?= htmlspecialchars(($student['first_name'] ?? '') . ' ' . ($student['last_name'] ?? '')) ?></h4>
                    <small class="text-muted"><?= htmlspecialchars($student['register_no'] ?? '') ?> | <?= htmlspecialchars(($student['class'] ?? '') . ' ' . ($student['section_name'] ?? '')) ?></small>
                </div>
                <div class="ml-auto">
                    <a href="<?= base_url('health/clinic_log') ?>" class="btn btn-default btn-sm"><i class="fas fa-list"></i> Clinic Log</a>
                    <a href="<?= base_url('health/search') ?>" class="btn btn-default btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
                </div>
            </div>
        </section>
    </div>
</div>

<div class="row">
    <!-- Health Profile -->
    <div class="col-md-6">
        <section class="panel">
            <header class="panel-heading">
                <div class="panel-btn">
                    <button class="btn btn-default btn-circle btn-sm" onclick="mfp_modal('#edit-health-modal')"><i class="fas fa-edit"></i></button>
                </div>
                <h4 class="panel-title"><i class="fas fa-stethoscope"></i> Health Profile</h4>
            </header>
            <div class="panel-body">
                <table class="table table-condensed table-striped mb-none">
                    <tr><th width="45%">Blood Group</th><td><?= htmlspecialchars($health['blood_group'] ?? 'N/A') ?></td></tr>
                    <tr><th>Height</th><td><?= !empty($health['height_cm']) ? $health['height_cm'] . ' cm' : 'N/A' ?></td></tr>
                    <tr><th>Weight</th><td><?= !empty($health['weight_kg']) ? $health['weight_kg'] . ' kg' : 'N/A' ?></td></tr>
                    <tr><th>Vision (L/R)</th><td><?= htmlspecialchars(($health['vision_left'] ?? 'N/A') . ' / ' . ($health['vision_right'] ?? 'N/A')) ?></td></tr>
                    <tr><th>Hearing</th><td><?= htmlspecialchars($health['hearing'] ?? 'N/A') ?></td></tr>
                    <tr><th>Allergies</th><td><?= !empty($health['allergies']) ? '<span class="text-danger">' . htmlspecialchars($health['allergies']) . '</span>' : 'None reported' ?></td></tr>
                    <tr><th>Chronic Conditions</th><td><?= htmlspecialchars($health['chronic_conditions'] ?? 'None') ?></td></tr>
                    <tr><th>Disabilities</th><td><?= htmlspecialchars($health['disabilities'] ?? 'None') ?></td></tr>
                    <tr><th>NHIF No.</th><td><?= htmlspecialchars($health['nhif_number'] ?? 'N/A') ?></td></tr>
                    <tr><th>Insurance</th><td><?= htmlspecialchars($health['insurance_provider'] ?? 'N/A') ?></td></tr>
                    <tr><th>Emergency Contact</th><td><?= htmlspecialchars($health['emergency_contact_name'] ?? 'N/A') ?></td></tr>
                    <tr><th>Emergency Phone</th><td><?= htmlspecialchars($health['emergency_contact_phone'] ?? 'N/A') ?></td></tr>
                    <tr><th>Doctor</th><td><?= htmlspecialchars($health['doctor_name'] ?? 'N/A') ?></td></tr>
                    <tr><th>Doctor Phone</th><td><?= htmlspecialchars($health['doctor_phone'] ?? 'N/A') ?></td></tr>
                </table>
            </div>
        </section>
    </div>

    <!-- Vaccination Records -->
    <div class="col-md-6">
        <section class="panel">
            <header class="panel-heading">
                <div class="panel-btn">
                    <button class="btn btn-default btn-circle btn-sm" onclick="mfp_modal('#add-vaccine-modal')"><i class="fas fa-plus"></i></button>
                </div>
                <h4 class="panel-title"><i class="fas fa-syringe"></i> Vaccinations</h4>
            </header>
            <div class="panel-body">
                <?php if (empty($vaccinations)): ?>
                    <p class="text-muted text-center">No vaccination records.</p>
                <?php else: ?>
                <table class="table table-condensed table-bordered table-hover">
                    <thead><tr><th>Vaccine</th><th>Dose</th><th>Date</th><th>Next Due</th><th></th></tr></thead>
                    <tbody>
                        <?php foreach ($vaccinations as $v): ?>
                        <tr>
                            <td><?= htmlspecialchars($v['vaccine_name']) ?></td>
                            <td><?= htmlspecialchars($v['dose'] ?? '') ?></td>
                            <td><?= !empty($v['date_given']) ? date('d M Y', strtotime($v['date_given'])) : '—' ?></td>
                            <td>
                                <?php if (!empty($v['next_due'])): ?>
                                    <?php $overdue = strtotime($v['next_due']) < time(); ?>
                                    <span class="<?= $overdue ? 'text-danger' : '' ?>"><?= date('d M Y', strtotime($v['next_due'])) ?><?= $overdue ? ' <i class="fas fa-exclamation-triangle"></i>' : '' ?></span>
                                <?php else: ?>—<?php endif; ?>
                            </td>
                            <td><?= btn_delete('health/delete_vaccination/' . $v['id']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>

<!-- Clinic Visits -->
<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <div class="panel-btn">
                    <button class="btn btn-default btn-circle btn-sm" onclick="mfp_modal('#add-visit-modal')"><i class="fas fa-plus"></i> Record Visit</button>
                </div>
                <h4 class="panel-title"><i class="fas fa-notes-medical"></i> Clinic Visits</h4>
            </header>
            <div class="panel-body">
                <?php if (empty($visits)): ?>
                    <p class="text-muted text-center">No clinic visits recorded.</p>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-condensed table-bordered table-hover">
                        <thead>
                            <tr><th>Date</th><th>Complaint</th><th>Diagnosis</th><th>Treatment</th><th>Referred?</th><th></th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($visits as $v): ?>
                            <tr>
                                <td><?= date('d M Y', strtotime($v['visit_date'])) ?></td>
                                <td><?= htmlspecialchars($v['complaint']) ?></td>
                                <td><?= htmlspecialchars($v['diagnosis'] ?? '—') ?></td>
                                <td><?= htmlspecialchars($v['treatment'] ?? '—') ?></td>
                                <td>
                                    <?php if ($v['referred']): ?>
                                        <span class="badge badge-warning">Yes — <?= htmlspecialchars($v['referral_facility'] ?? '') ?></span>
                                    <?php else: echo 'No'; endif; ?>
                                </td>
                                <td><?= btn_delete('health/delete_visit/' . $v['id']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>

<!-- Edit Health Profile Modal -->
<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="edit-health-modal">
    <section class="panel">
        <header class="panel-heading"><h4 class="panel-title">Edit Health Profile</h4><button type="button" class="close" onclick="$.magnificPopup.close()" style="float:right;font-size:1.4rem;line-height:1;opacity:.7;" title="Close">&times;</button></header>
        <?php echo form_open('health/save_profile', array('class' => 'frm-submit')); ?>
        <input type="hidden" name="student_id" value="<?= $student['student_id'] ?? '' ?>">
        <div class="panel-body" style="max-height:70vh;overflow-y:auto">
            <div class="row">
                <div class="col-md-4"><div class="form-group"><label>Blood Group</label><select name="blood_group" class="form-control"><?php foreach(['','A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg): ?><option value="<?=$bg?>" <?= $health['blood_group']==$bg?'selected':'' ?>><?=$bg?:'-'?></option><?php endforeach; ?></select></div></div>
                <div class="col-md-4"><div class="form-group"><label>Height (cm)</label><input type="number" name="height_cm" class="form-control" step="0.1" value="<?= $health['height_cm'] ?? '' ?>"></div></div>
                <div class="col-md-4"><div class="form-group"><label>Weight (kg)</label><input type="number" name="weight_kg" class="form-control" step="0.1" value="<?= $health['weight_kg'] ?? '' ?>"></div></div>
                <div class="col-md-4"><div class="form-group"><label>Vision Left</label><input type="text" name="vision_left" class="form-control" value="<?= $health['vision_left'] ?? '' ?>" placeholder="e.g. 6/6"></div></div>
                <div class="col-md-4"><div class="form-group"><label>Vision Right</label><input type="text" name="vision_right" class="form-control" value="<?= $health['vision_right'] ?? '' ?>" placeholder="e.g. 6/6"></div></div>
                <div class="col-md-4"><div class="form-group"><label>Hearing</label><input type="text" name="hearing" class="form-control" value="<?= $health['hearing'] ?? '' ?>" placeholder="e.g. Normal"></div></div>
                <div class="col-md-12"><div class="form-group"><label>Allergies</label><textarea name="allergies" class="form-control" rows="2"><?= htmlspecialchars($health['allergies'] ?? '') ?></textarea></div></div>
                <div class="col-md-12"><div class="form-group"><label>Chronic Conditions</label><textarea name="chronic_conditions" class="form-control" rows="2"><?= htmlspecialchars($health['chronic_conditions'] ?? '') ?></textarea></div></div>
                <div class="col-md-12"><div class="form-group"><label>Disabilities / Special Needs</label><textarea name="disabilities" class="form-control" rows="2"><?= htmlspecialchars($health['disabilities'] ?? '') ?></textarea></div></div>
                <div class="col-md-6"><div class="form-group"><label>NHIF Number</label><input type="text" name="nhif_number" class="form-control" value="<?= $health['nhif_number'] ?? '' ?>"></div></div>
                <div class="col-md-6"><div class="form-group"><label>Insurance Provider</label><input type="text" name="insurance_provider" class="form-control" value="<?= $health['insurance_provider'] ?? '' ?>"></div></div>
                <div class="col-md-6"><div class="form-group"><label>Emergency Contact Name</label><input type="text" name="emergency_contact_name" class="form-control" value="<?= $health['emergency_contact_name'] ?? '' ?>"></div></div>
                <div class="col-md-6"><div class="form-group"><label>Emergency Contact Phone</label><input type="text" name="emergency_contact_phone" class="form-control" value="<?= $health['emergency_contact_phone'] ?? '' ?>"></div></div>
                <div class="col-md-6"><div class="form-group"><label>Family Doctor</label><input type="text" name="doctor_name" class="form-control" value="<?= $health['doctor_name'] ?? '' ?>"></div></div>
                <div class="col-md-6"><div class="form-group"><label>Doctor Phone</label><input type="text" name="doctor_phone" class="form-control" value="<?= $health['doctor_phone'] ?? '' ?>"></div></div>
                <div class="col-md-12"><div class="form-group"><label>Notes</label><textarea name="notes" class="form-control" rows="2"><?= htmlspecialchars($health['notes'] ?? '') ?></textarea></div></div>
            </div>
        </div>
        <footer class="panel-footer"><div class="text-right"><button type="button" class="btn btn-default modal-dismiss">Cancel</button> <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button></div></footer>
        <?php echo form_close(); ?>
    </section>
</div>

<!-- Add Vaccination Modal -->
<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="add-vaccine-modal">
    <section class="panel">
        <header class="panel-heading"><h4 class="panel-title">Add Vaccination Record</h4><button type="button" class="close" onclick="$.magnificPopup.close()" style="float:right;font-size:1.4rem;line-height:1;opacity:.7;" title="Close">&times;</button></header>
        <?php echo form_open('health/add_vaccination', array('class' => 'frm-submit')); ?>
        <input type="hidden" name="student_id" value="<?= $student['student_id'] ?? '' ?>">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6"><div class="form-group"><label>Vaccine Name <span class="required">*</span></label><input type="text" name="vaccine_name" class="form-control" placeholder="e.g. BCG, DPT, Polio"></div></div>
                <div class="col-md-6"><div class="form-group"><label>Dose</label><input type="text" name="dose" class="form-control" placeholder="e.g. 1st, 2nd, Booster"></div></div>
                <div class="col-md-6"><div class="form-group"><label>Date Given</label><input type="date" name="date_given" class="form-control"></div></div>
                <div class="col-md-6"><div class="form-group"><label>Next Due Date</label><input type="date" name="next_due" class="form-control"></div></div>
                <div class="col-md-12"><div class="form-group"><label>Given By</label><input type="text" name="given_by" class="form-control" placeholder="Health worker name or facility"></div></div>
                <div class="col-md-12"><div class="form-group"><label>Remarks</label><textarea name="remarks" class="form-control" rows="2"></textarea></div></div>
            </div>
        </div>
        <footer class="panel-footer"><div class="text-right"><button type="button" class="btn btn-default modal-dismiss">Cancel</button> <button type="submit" class="btn btn-primary"><i class="fas fa-syringe"></i> Save</button></div></footer>
        <?php echo form_close(); ?>
    </section>
</div>

<!-- Add Clinic Visit Modal -->
<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="add-visit-modal">
    <section class="panel">
        <header class="panel-heading"><h4 class="panel-title">Record Clinic Visit</h4><button type="button" class="close" onclick="$.magnificPopup.close()" style="float:right;font-size:1.4rem;line-height:1;opacity:.7;" title="Close">&times;</button></header>
        <?php echo form_open('health/add_visit', array('class' => 'frm-submit')); ?>
        <input type="hidden" name="student_id" value="<?= $student['student_id'] ?? '' ?>">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6"><div class="form-group"><label>Visit Date <span class="required">*</span></label><input type="date" name="visit_date" class="form-control" value="<?= date('Y-m-d') ?>"></div></div>
                <div class="col-md-6"><div class="form-group"><label>Attended By</label><input type="text" name="attended_by" class="form-control" placeholder="Nurse / Doctor name"></div></div>
                <div class="col-md-12"><div class="form-group"><label>Complaint <span class="required">*</span></label><textarea name="complaint" class="form-control" rows="2" placeholder="Chief complaint..."></textarea></div></div>
                <div class="col-md-12"><div class="form-group"><label>Diagnosis</label><textarea name="diagnosis" class="form-control" rows="2"></textarea></div></div>
                <div class="col-md-12"><div class="form-group"><label>Treatment Given</label><textarea name="treatment" class="form-control" rows="2"></textarea></div></div>
                <div class="col-md-12"><div class="form-group"><label>Medication Dispensed</label><input type="text" name="medication" class="form-control" placeholder="e.g. Paracetamol 500mg"></div></div>
                <div class="col-md-6"><div class="form-group"><label>Referred to Facility?</label><select name="referred" class="form-control"><option value="0">No</option><option value="1">Yes</option></select></div></div>
                <div class="col-md-6"><div class="form-group"><label>Referral Facility</label><input type="text" name="referral_facility" class="form-control" placeholder="Hospital name"></div></div>
                <div class="col-md-6"><div class="form-group"><label>Follow-up Date</label><input type="date" name="follow_up_date" class="form-control"></div></div>
            </div>
        </div>
        <footer class="panel-footer"><div class="text-right"><button type="button" class="btn btn-default modal-dismiss">Cancel</button> <button type="submit" class="btn btn-primary"><i class="fas fa-notes-medical"></i> Save Visit</button></div></footer>
        <?php echo form_close(); ?>
    </section>
</div>
