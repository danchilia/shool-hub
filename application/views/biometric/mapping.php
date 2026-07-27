<style>
@media (prefers-color-scheme:dark) {
    .card        { background:#2b2b3a; border-color:#3a3a50; }
    .card-header { background:#232333; border-color:#3a3a50; }
    .table-light { --bs-table-bg:#232333; }
}
:root[data-theme="dark"]  .card        { background:#2b2b3a; border-color:#3a3a50; }
:root[data-theme="dark"]  .card-header { background:#232333; border-color:#3a3a50; }
:root[data-theme="dark"]  .table-light { --bs-table-bg:#232333; }
:root[data-theme="light"] .card        { background:#fff;    border-color:#dee2e6; }
:root[data-theme="light"] .card-header { background:#f8f9fa; border-color:#dee2e6; }
:root[data-theme="light"] .table-light { --bs-table-bg:#f8f9fa; }
</style>

<div class="container-fluid">
    <div class="row g-3">
        <?php if (get_permission('biometric_mapping', 'is_add')): ?>
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="far fa-edit me-2"></i>Link Fingerprint ID</h6>
                </div>
                <div class="card-body">
                    <?php echo form_open($this->uri->uri_string()); ?>
                    <div class="mb-3">
                        <label class="form-label">Biometric ID <span class="text-danger">*</span><?php echo help_tip('The ID number assigned when registering this person\'s fingerprint on the device. Check the device screen/software for this number. Example: 23'); ?></label>
                        <input type="text" class="form-control" name="biometric_id" value="<?php echo set_value('biometric_id'); ?>" placeholder="e.g. 23">
                        <span class="error text-danger small"><?php echo form_error('biometric_id'); ?></span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type <span class="text-danger">*</span></label>
                        <select name="person_type" id="person_type" class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" onchange="togglePersonType()">
                            <option value="student">Student</option>
                            <option value="staff">Staff</option>
                        </select>
                        <span class="error text-danger small"><?php echo form_error('person_type'); ?></span>
                    </div>

                    <div id="student_fields">
                        <div class="mb-3">
                            <label class="form-label"><?php echo translate('class'); ?></label>
                            <?php
                            $arrayClass = $this->app_lib->getClass($branch_id);
                            echo form_dropdown("class_id", $arrayClass, set_value('class_id'), "class='form-control' id='class_id' onchange='getSectionByClass(this.value,0); loadStudents();' data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
                            ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><?php echo translate('section'); ?></label>
                            <select name="section_id_filter" id="section_id" class="form-control" data-plugin-selectTwo data-width="100%" onchange="loadStudents()">
                                <option value=""><?php echo translate('select'); ?></option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Student <span class="text-danger">*</span></label>
                            <select name="person_id" id="student_select" class="form-control" data-plugin-selectTwo data-width="100%">
                                <option value=""><?php echo translate('select'); ?></option>
                            </select>
                            <span class="error text-danger small"><?php echo form_error('person_id'); ?></span>
                        </div>
                    </div>

                    <div id="staff_fields" style="display:none;">
                        <div class="mb-3">
                            <label class="form-label">Staff Member <span class="text-danger">*</span></label>
                            <select name="staff_person_id" id="staff_select" class="form-control" data-plugin-selectTwo data-width="100%">
                                <option value=""><?php echo translate('select'); ?></option>
                                <?php
                                $staffList = $this->db->where('branch_id', $branch_id)->get('staff')->result_array();
                                foreach ($staffList as $st):
                                ?>
                                <option value="<?php echo $st['id']; ?>"><?php echo html_escape($st['name']); ?> (<?php echo html_escape($st['staff_id']); ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="text-end">
                        <button class="btn btn-primary" type="submit" name="save" value="1">
                            <i class="fas fa-link me-1"></i>Link Fingerprint
                        </button>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="col-md-<?php echo get_permission('biometric_mapping', 'is_add') ? '7' : '12'; ?>">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-fingerprint me-2"></i>Linked Fingerprints</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm table-export mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Biometric ID</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $c = 1; if (count($mappings)): foreach ($mappings as $m): ?>
                                <tr>
                                    <td><?php echo $c++; ?></td>
                                    <td><strong><?php echo html_escape($m['biometric_id']); ?></strong></td>
                                    <td><?php echo html_escape($m['person_name']); ?></td>
                                    <td><?php echo html_escape($m['person_code']); ?></td>
                                    <td><?php echo ucfirst($m['person_type']); ?></td>
                                    <td>
                                        <?php if (get_permission('biometric_mapping', 'is_delete')): ?>
                                        <?php echo btn_delete('biometric/mapping_delete/' . $m['id']); ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr><td colspan="6" class="text-center text-danger"><?php echo translate('no_information_available'); ?></td></tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePersonType() {
    var type = $('#person_type').val();
    if (type === 'student') {
        $('#student_fields').show();
        $('#staff_fields').hide();
        $('#staff_select').prop('name', '');
        $('#student_select').prop('name', 'person_id');
    } else {
        $('#student_fields').hide();
        $('#staff_fields').show();
        $('#student_select').prop('name', '');
        $('#staff_select').prop('name', 'person_id');
    }
}
function loadStudents() {
    var classId   = $('#class_id').val();
    var sectionId = $('#section_id').val();
    if (!classId || !sectionId) return;
    $.ajax({
        url: base_url + 'biometric/get_students_by_class',
        type: 'POST',
        data: $.extend({class_id: classId, section_id: sectionId}, csrfData),
        success: function(data) { $('#student_select').html(data); }
    });
}
$(function(){ togglePersonType(); });
</script>
