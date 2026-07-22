<?php $sessions = isset($sessions) ? $sessions : array(); ?>
<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <div class="panel-btn">
                    <button class="btn btn-default btn-circle" onclick="mfp_modal('#add-session-modal')">
                        <i class="fas fa-plus"></i> Schedule PTM
                    </button>
                </div>
                <h4 class="panel-title"><i class="fas fa-handshake"></i> Parent-Teacher Meeting Schedule</h4>
            </header>
            <div class="panel-body">
                <?php if (empty($sessions)): ?>
                    <p class="text-center text-muted mt-md">No PTM sessions scheduled yet.</p>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed table-hover table-export">
                        <thead>
                            <tr><th>#</th><th>Title</th><th>Date</th><th>Time</th><th>Venue</th><th>Slot (mins)</th><th>Bookings</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sessions as $i => $s): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><strong><?= htmlspecialchars($s['title']) ?></strong></td>
                                <td><?= date('d M Y', strtotime($s['session_date'])) ?></td>
                                <td><?= date('H:i', strtotime($s['start_time'])) ?> – <?= date('H:i', strtotime($s['end_time'])) ?></td>
                                <td><?= htmlspecialchars($s['venue'] ?? '—') ?></td>
                                <td><?= $s['slot_duration_mins'] ?></td>
                                <td><span class="badge badge-info"><?= $s['bookings'] ?></span></td>
                                <td>
                                    <a href="<?= base_url('ptm/bookings/' . $s['id']) ?>" class="btn btn-default btn-circle btn-xs" data-toggle="tooltip" data-original-title="Manage Bookings">
                                        <i class="fas fa-calendar-check"></i>
                                    </a>
                                    <?= btn_delete('ptm/delete_session/' . $s['id']) ?>
                                </td>
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

<!-- Add Session Modal -->
<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="add-session-modal">
    <section class="panel">
        <header class="panel-heading"><h4 class="panel-title">Schedule Parent-Teacher Meeting</h4><button type="button" class="close" onclick="$.magnificPopup.close()" style="float:right;font-size:1.4rem;line-height:1;opacity:.7;" title="Close">&times;</button></header>
        <?php echo form_open('ptm/save_session', array('class' => 'frm-submit')); ?>
        <input type="hidden" name="id" value="0">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12"><div class="form-group"><label>Title <span class="required">*</span></label><input type="text" name="title" class="form-control" placeholder="e.g. Term 2 PTM 2025"><span class="error"></span></div></div>
                <div class="col-md-4"><div class="form-group"><label>Date <span class="required">*</span></label><input type="date" name="session_date" class="form-control"><span class="error"></span></div></div>
                <div class="col-md-4"><div class="form-group"><label>Start Time <span class="required">*</span></label><input type="time" name="start_time" class="form-control" value="08:00"><span class="error"></span></div></div>
                <div class="col-md-4"><div class="form-group"><label>End Time <span class="required">*</span></label><input type="time" name="end_time" class="form-control" value="17:00"><span class="error"></span></div></div>
                <div class="col-md-6"><div class="form-group"><label>Venue</label><input type="text" name="venue" class="form-control" placeholder="e.g. School Hall, Classrooms"></div></div>
                <div class="col-md-6"><div class="form-group"><label>Slot Duration (minutes) <span class="required">*</span></label><input type="number" name="slot_duration_mins" class="form-control" value="15" min="5" max="60"><span class="error"></span></div></div>
                <div class="col-md-12"><div class="form-group"><label>Notes</label><textarea name="notes" class="form-control" rows="2"></textarea></div></div>
            </div>
        </div>
        <footer class="panel-footer"><div class="text-right">
            <button type="button" class="btn btn-default modal-dismiss">Cancel</button>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
        </div></footer>
        <?php echo form_close(); ?>
    </section>
</div>
