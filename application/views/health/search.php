<?php $students = isset($students) ? $students : array(); ?>
<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <h4 class="panel-title"><i class="fas fa-heartbeat"></i> Student Health Records</h4>
            </header>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed table-hover table-export">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Reg. No.</th>
                                <th>Name</th>
                                <th>Class</th>
                                <th>Stream</th>
                                <th>Health Record</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $i => $s): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= htmlspecialchars($s['register_no']) ?></td>
                                <td><?= htmlspecialchars($s['first_name'] . ' ' . $s['last_name']) ?></td>
                                <td><?= htmlspecialchars($s['class'] ?? '') ?></td>
                                <td><?= htmlspecialchars($s['section_name'] ?? '') ?></td>
                                <td>
                                    <?php if ($s['has_record'] > 0): ?>
                                        <span class="badge badge-success">On file</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">None</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('health/student/' . $s['student_id']) ?>" class="btn btn-default btn-circle icon" data-toggle="tooltip" data-original-title="View Health Record">
                                        <i class="fas fa-heartbeat"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>
