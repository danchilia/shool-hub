<style>
@media (prefers-color-scheme:dark) {
    .card           { background:#2b2b3a; border-color:#3a3a50; }
    .card-header    { background:#232333; border-color:#3a3a50; }
    .table-light    { --bs-table-bg:#232333; }
    .student-banner { background:#2e1a1a !important; border-color:#e74c3c !important; }
}
:root[data-theme="dark"]  .card           { background:#2b2b3a; border-color:#3a3a50; }
:root[data-theme="dark"]  .card-header    { background:#232333; border-color:#3a3a50; }
:root[data-theme="dark"]  .table-light    { --bs-table-bg:#232333; }
:root[data-theme="dark"]  .student-banner { background:#2e1a1a !important; border-color:#e74c3c !important; }
:root[data-theme="light"] .card           { background:#fff;    border-color:#dee2e6; }
:root[data-theme="light"] .card-header    { background:#f8f9fa; border-color:#dee2e6; }
:root[data-theme="light"] .table-light    { --bs-table-bg:#f8f9fa; }
:root[data-theme="light"] .student-banner { background:#fef2f2 !important; border-color:#e74c3c !important; }
</style>

<?php
$student      = isset($student)      ? $student      : array();
$health       = isset($health)       ? $health       : array();
$vaccinations = isset($vaccinations) ? $vaccinations : array();
$visits       = isset($visits)       ? $visits       : array();
$studentId    = (int)($student['student_id'] ?? $student['id'] ?? 0);
?>

<div class="content-header">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <a href="<?php echo base_url('health/search'); ?>" class="text-muted small"><i class="fas fa-arrow-left me-1"></i>All Students</a>
        <a href="<?php echo base_url('health/clinic_log'); ?>" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-notes-medical me-1"></i>Clinic Log
        </a>
    </div>
</div>

<div class="container-fluid">

    <!-- Student banner -->
    <div class="card mb-3 student-banner border-start border-danger border-3">
        <div class="card-body py-2">
            <div class="d-flex align-items-center gap-3">
                <img src="<?php echo get_image_url('student', $student['photo'] ?? ''); ?>" width="56" height="56" class="rounded" style="object-fit:cover">
                <div class="flex-grow-1">
                    <div class="fw-bold"><?php echo htmlspecialchars(($student['first_name'] ?? '') . ' ' . ($student['last_name'] ?? '')); ?></div>
                    <small class="text-muted">
                        <?php echo htmlspecialchars($student['register_no'] ?? ''); ?>
                        <?php if (!empty($student['class'])): ?> | <?php echo htmlspecialchars(trim($student['class'] . ' ' . ($student['section_name'] ?? ''))); ?><?php endif; ?>
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">

        <!-- Health Profile -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0"><i class="fas fa-stethoscope me-2"></i>Health Profile</h5>
                    <button class="btn btn-xs btn-outline-secondary" onclick="mfp_modal('#edit-health-modal')">
                        <i class="fas fa-edit me-1"></i>Edit
                    </button>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-striped mb-0">
                        <tr><th width="45%" class="ps-3">Blood Group</th><td><?php echo htmlspecialchars($health['blood_group'] ?? 'N/A'); ?></td></tr>
                        <tr><th class="ps-3">Height</th><td><?php echo !empty($health['height_cm']) ? $health['height_cm'] . ' cm' : 'N/A'; ?></td></tr>
                        <tr><th class="ps-3">Weight</th><td><?php echo !empty($health['weight_kg']) ? $health['weight_kg'] . ' kg' : 'N/A'; ?></td></tr>
                        <tr><th class="ps-3">Vision (L/R)</th><td><?php echo htmlspecialchars(($health['vision_left'] ?? 'N/A') . ' / ' . ($health['vision_right'] ?? 'N/A')); ?></td></tr>
                        <tr><th class="ps-3">Hearing</th><td><?php echo htmlspecialchars($health['hearing'] ?? 'N/A'); ?></td></tr>
                        <tr><th class="ps-3">Allergies</th><td><?php echo !empty($health['allergies']) ? '<span class="text-danger">' . htmlspecialchars($health['allergies']) . '</span>' : 'None reported'; ?></td></tr>
                        <tr><th class="ps-3">Chronic Conditions</th><td><?php echo htmlspecialchars($health['chronic_conditions'] ?? 'None'); ?></td></tr>
                        <tr><th class="ps-3">Disabilities</th><td><?php echo htmlspecialchars($health['disabilities'] ?? 'None'); ?></td></tr>
                        <tr><th class="ps-3">NHIF No.</th><td><?php echo htmlspecialchars($health['nhif_number'] ?? 'N/A'); ?></td></tr>
                        <tr><th class="ps-3">Insurance</th><td><?php echo htmlspecialchars($health['insurance_provider'] ?? 'N/A'); ?></td></tr>
                        <tr><th class="ps-3">Emergency Contact</th><td><?php echo htmlspecialchars($health['emergency_contact_name'] ?? 'N/A'); ?></td></tr>
                        <tr><th class="ps-3">Emergency Phone</th><td><?php echo htmlspecialchars($health['emergency_contact_phone'] ?? 'N/A'); ?></td></tr>
                        <tr><th class="ps-3">Doctor</th><td><?php echo htmlspecialchars($health['doctor_name'] ?? 'N/A'); ?></td></tr>
                        <tr><th class="ps-3">Doctor Phone</th><td><?php echo htmlspecialchars($health['doctor_phone'] ?? 'N/A'); ?></td></tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Vaccinations -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0"><i class="fas fa-syringe me-2"></i>Vaccinations</h5>
                    <button class="btn btn-xs btn-outline-primary" onclick="mfp_modal('#add-vaccine-modal')">
                        <i class="fas fa-plus me-1"></i>Add
                    </button>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($vaccinations)): ?>
                    <p class="text-muted text-center py-4">No vaccination records.</p>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0" id="vaccine-table">
                            <thead class="table-light">
                                <tr><th>Vaccine</th><th>Dose</th><th>Date</th><th>Next Due</th><th></th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($vaccinations as $v): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($v['vaccine_name']); ?></td>
                                    <td><?php echo htmlspecialchars($v['dose'] ?? ''); ?></td>
                                    <td class="text-nowrap"><?php echo !empty($v['date_given']) ? date('d M Y', strtotime($v['date_given'])) : '—'; ?></td>
                                    <td class="text-nowrap">
                                        <?php if (!empty($v['next_due'])): ?>
                                            <?php $overdue = strtotime($v['next_due']) < time(); ?>
                                            <span class="<?php echo $overdue ? 'text-danger fw-semibold' : ''; ?>">
                                                <?php echo date('d M Y', strtotime($v['next_due'])); ?>
                                                <?php if ($overdue): ?><i class="fas fa-exclamation-triangle ms-1"></i><?php endif; ?>
                                            </span>
                                        <?php else: ?>—<?php endif; ?>
                                    </td>
                                    <td><?php echo btn_delete('health/delete_vaccination/' . $v['id']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>

    <!-- Clinic Visits -->
    <div class="card mt-3">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0"><i class="fas fa-notes-medical me-2"></i>Clinic Visits</h5>
            <button class="btn btn-xs btn-outline-primary" onclick="mfp_modal('#add-visit-modal')">
                <i class="fas fa-plus me-1"></i>Record Visit
            </button>
        </div>
        <div class="card-body p-0">
            <?php if (empty($visits)): ?>
            <p class="text-muted text-center py-4">No clinic visits recorded.</p>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0" id="visits-table">
                    <thead class="table-light">
                        <tr><th>Date</th><th>Complaint</th><th>Diagnosis</th><th>Treatment</th><th>Referred?</th><th></th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($visits as $v): ?>
                        <tr>
                            <td class="text-nowrap"><?php echo date('d M Y', strtotime($v['visit_date'])); ?></td>
                            <td><?php echo htmlspecialchars($v['complaint']); ?></td>
                            <td><?php echo htmlspecialchars($v['diagnosis'] ?? '—'); ?></td>
                            <td><?php echo htmlspecialchars($v['treatment'] ?? '—'); ?></td>
                            <td>
                                <?php if ($v['referred']): ?>
                                    <span class="badge bg-warning text-dark">Yes — <?php echo htmlspecialchars($v['referral_facility'] ?? ''); ?></span>
                                <?php else: ?>
                                    <span class="text-muted">No</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo btn_delete('health/delete_visit/' . $v['id']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<!-- Edit Health Profile Modal -->
<div id="edit-health-modal" class="mfp-hide">
    <div class="card mb-0" style="min-width:600px; max-width:720px;">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0"><i class="fas fa-stethoscope me-2"></i>Edit Health Profile</h5>
            <button type="button" class="btn-close ms-2" onclick="$.magnificPopup.close()" title="Close"></button>
        </div>
        <div class="card-body" style="max-height:70vh;overflow-y:auto">
            <?php echo form_open('health/save_profile', array('class' => 'frm-submit', 'id' => 'health-form')); ?>
            <input type="hidden" name="student_id" value="<?php echo $studentId; ?>">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Blood Group</label>
                    <select name="blood_group" class="form-select">
                        <?php foreach (['', 'A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $bg): ?>
                        <option value="<?php echo $bg; ?>" <?php echo ($health['blood_group'] ?? '') == $bg ? 'selected' : ''; ?>><?php echo $bg ?: '— Select —'; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Height (cm)</label>
                    <input type="number" name="height_cm" class="form-control" step="0.1" value="<?php echo htmlspecialchars($health['height_cm'] ?? ''); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Weight (kg)</label>
                    <input type="number" name="weight_kg" class="form-control" step="0.1" value="<?php echo htmlspecialchars($health['weight_kg'] ?? ''); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Vision Left</label>
                    <input type="text" name="vision_left" class="form-control" value="<?php echo htmlspecialchars($health['vision_left'] ?? ''); ?>" placeholder="e.g. 6/6">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Vision Right</label>
                    <input type="text" name="vision_right" class="form-control" value="<?php echo htmlspecialchars($health['vision_right'] ?? ''); ?>" placeholder="e.g. 6/6">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Hearing</label>
                    <input type="text" name="hearing" class="form-control" value="<?php echo htmlspecialchars($health['hearing'] ?? ''); ?>" placeholder="e.g. Normal">
                </div>
                <div class="col-12">
                    <label class="form-label">Allergies</label>
                    <textarea name="allergies" class="form-control" rows="2"><?php echo htmlspecialchars($health['allergies'] ?? ''); ?></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Chronic Conditions</label>
                    <textarea name="chronic_conditions" class="form-control" rows="2"><?php echo htmlspecialchars($health['chronic_conditions'] ?? ''); ?></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Disabilities / Special Needs</label>
                    <textarea name="disabilities" class="form-control" rows="2"><?php echo htmlspecialchars($health['disabilities'] ?? ''); ?></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">NHIF Number</label>
                    <input type="text" name="nhif_number" class="form-control" value="<?php echo htmlspecialchars($health['nhif_number'] ?? ''); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Insurance Provider</label>
                    <input type="text" name="insurance_provider" class="form-control" value="<?php echo htmlspecialchars($health['insurance_provider'] ?? ''); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Emergency Contact Name</label>
                    <input type="text" name="emergency_contact_name" class="form-control" value="<?php echo htmlspecialchars($health['emergency_contact_name'] ?? ''); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Emergency Contact Phone</label>
                    <input type="text" name="emergency_contact_phone" class="form-control" value="<?php echo htmlspecialchars($health['emergency_contact_phone'] ?? ''); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Family Doctor</label>
                    <input type="text" name="doctor_name" class="form-control" value="<?php echo htmlspecialchars($health['doctor_name'] ?? ''); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Doctor Phone</label>
                    <input type="text" name="doctor_phone" class="form-control" value="<?php echo htmlspecialchars($health['doctor_phone'] ?? ''); ?>">
                </div>
                <div class="col-12">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="2"><?php echo htmlspecialchars($health['notes'] ?? ''); ?></textarea>
                </div>
            </div>
            <div class="mt-3 text-end">
                <button type="button" class="btn btn-secondary me-1" onclick="$.magnificPopup.close()">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<!-- Add Vaccination Modal -->
<div id="add-vaccine-modal" class="mfp-hide">
    <div class="card mb-0" style="min-width:500px; max-width:580px;">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0"><i class="fas fa-syringe me-2"></i>Add Vaccination Record</h5>
            <button type="button" class="btn-close ms-2" onclick="$.magnificPopup.close()" title="Close"></button>
        </div>
        <div class="card-body">
            <?php echo form_open('health/add_vaccination', array('class' => 'frm-submit', 'id' => 'vaccine-form')); ?>
            <input type="hidden" name="student_id" value="<?php echo $studentId; ?>">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Vaccine Name <span class="text-danger">*</span></label>
                    <input type="text" name="vaccine_name" class="form-control" placeholder="e.g. BCG, DPT, Polio">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Dose</label>
                    <input type="text" name="dose" class="form-control" placeholder="e.g. 1st, 2nd, Booster">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Date Given</label>
                    <input type="date" name="date_given" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Next Due Date</label>
                    <input type="date" name="next_due" class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label">Given By</label>
                    <input type="text" name="given_by" class="form-control" placeholder="Health worker name or facility">
                </div>
                <div class="col-12">
                    <label class="form-label">Remarks</label>
                    <textarea name="remarks" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="mt-3 text-end">
                <button type="button" class="btn btn-secondary me-1" onclick="$.magnificPopup.close()">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-syringe me-1"></i>Save</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<!-- Add Clinic Visit Modal -->
<div id="add-visit-modal" class="mfp-hide">
    <div class="card mb-0" style="min-width:540px; max-width:640px;">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0"><i class="fas fa-notes-medical me-2"></i>Record Clinic Visit</h5>
            <button type="button" class="btn-close ms-2" onclick="$.magnificPopup.close()" title="Close"></button>
        </div>
        <div class="card-body">
            <?php echo form_open('health/add_visit', array('class' => 'frm-submit', 'id' => 'visit-form')); ?>
            <input type="hidden" name="student_id" value="<?php echo $studentId; ?>">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Visit Date <span class="text-danger">*</span></label>
                    <input type="date" name="visit_date" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Attended By</label>
                    <input type="text" name="attended_by" class="form-control" placeholder="Nurse / Doctor name">
                </div>
                <div class="col-12">
                    <label class="form-label">Complaint <span class="text-danger">*</span></label>
                    <textarea name="complaint" class="form-control" rows="2" placeholder="Chief complaint..."></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Diagnosis</label>
                    <textarea name="diagnosis" class="form-control" rows="2"></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Treatment Given</label>
                    <textarea name="treatment" class="form-control" rows="2"></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Medication Dispensed</label>
                    <input type="text" name="medication" class="form-control" placeholder="e.g. Paracetamol 500mg">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Referred to Facility?</label>
                    <select name="referred" class="form-select">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Referral Facility</label>
                    <input type="text" name="referral_facility" class="form-control" placeholder="Hospital name">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Follow-up Date</label>
                    <input type="date" name="follow_up_date" class="form-control">
                </div>
            </div>
            <div class="mt-3 text-end">
                <button type="button" class="btn btn-secondary me-1" onclick="$.magnificPopup.close()">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-notes-medical me-1"></i>Save Visit</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
$(function(){
    if ($('#vaccine-table').length) {
        $('#vaccine-table').DataTable({ order: [[2, 'desc']], pageLength: 25, columnDefs: [{ orderable: false, targets: [4] }] });
    }
    if ($('#visits-table').length) {
        $('#visits-table').DataTable({ order: [[0, 'desc']], pageLength: 10, columnDefs: [{ orderable: false, targets: [5] }] });
    }
});
</script>
