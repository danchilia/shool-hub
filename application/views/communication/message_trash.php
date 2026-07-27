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

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h6 class="mb-0"><i class="far fa-trash-alt me-1"></i><?php echo translate('trash'); ?></h6>
        <div class="d-flex gap-2">
            <a href="<?php echo base_url('communication/mailbox/trash'); ?>" class="btn btn-sm btn-outline-secondary"
               data-bs-toggle="tooltip" title="<?php echo translate('refresh_mail'); ?>">
                <i class="fas fa-sync"></i>
            </a>
            <button class="btn btn-sm btn-outline-secondary msg-action" id="msgRestore" data-type="restore"
                    data-bs-toggle="tooltip" title="<?php echo translate('restore'); ?>">
                <i class="fas fa-reply"></i>
            </button>
            <button class="btn btn-sm btn-danger msg-action" id="msgDeleteForever" data-type="forever"
                    data-bs-toggle="tooltip" title="<?php echo translate('delete_forever'); ?>">
                <i class="far fa-trash-alt"></i>
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0 table-export">
                <thead class="table-light">
                    <tr>
                        <th width="30">
                            <div class="form-check mb-0">
                                <input class="form-check-input" type="checkbox" id="selectAllchkbox">
                            </div>
                        </th>
                        <th><?php echo translate('receiver'); ?></th>
                        <th><?php echo translate('subjects'); ?></th>
                        <th><?php echo translate('message'); ?></th>
                        <th><?php echo translate('time'); ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $sql = "SELECT * FROM message WHERE (sender = " . $this->db->escape($active_user) . " AND trash_sent = 1)
                        OR (reciever = " . $this->db->escape($active_user) . " AND trash_inbox = 1) ORDER BY id DESC";
                $messages = $this->db->query($sql)->result();
                foreach ($messages as $message):
                    if ($message->sender == $active_user) {
                        $getUser = explode('-', $message->reciever);
                    } else {
                        $getUser = explode('-', $message->sender);
                    }
                    $userRoleID = $getUser[0];
                    $userID     = $getUser[1];
                ?>
                <tr>
                    <td>
                        <div class="form-check mb-0">
                            <input class="form-check-input msg_checkbox" type="checkbox" id="<?php echo $message->id; ?>">
                        </div>
                    </td>
                    <td><?php echo html_escape($this->application_model->getUserNameByRoleID($userRoleID, $userID)['name']); ?></td>
                    <td><?php echo html_escape($message->subject); ?></td>
                    <td class="text-muted small"><?php echo html_escape(mb_strimwidth(strip_tags($message->body), 0, 60, '...')); ?></td>
                    <td class="text-nowrap small text-muted"><?php echo get_nicetime($message->created_at); ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
