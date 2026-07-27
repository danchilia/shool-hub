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
        <h6 class="mb-0"><i class="far fa-bell text-warning me-1"></i><?php echo translate('important'); ?></h6>
        <a href="<?php echo base_url('communication/mailbox/important'); ?>" class="btn btn-sm btn-outline-secondary"
           data-bs-toggle="tooltip" title="<?php echo translate('refresh_mail'); ?>">
            <i class="fas fa-sync"></i>
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0 table-export">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th><?php echo translate('type'); ?></th>
                        <th><?php echo translate('sender') . ' / ' . translate('receiver'); ?></th>
                        <th><?php echo translate('subjects'); ?></th>
                        <th><?php echo translate('message'); ?></th>
                        <th><?php echo translate('time'); ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $count = 1;
                $sql = "SELECT * FROM message WHERE (sender = " . $this->db->escape($active_user) . " AND fav_sent = 1 AND trash_sent = 0)
                        OR (reciever = " . $this->db->escape($active_user) . " AND fav_inbox = 1 AND trash_inbox = 0) ORDER BY id DESC";
                $messages = $this->db->query($sql)->result();

                foreach ($messages as $message):
                    if ($message->sender == $active_user) {
                        $type    = 'sent';
                        $getUser = explode('-', $message->reciever);
                    } else {
                        $type    = 'inbox';
                        $getUser = explode('-', $message->sender);
                    }
                    $userRoleID = $getUser[0];
                    $userID     = $getUser[1];
                ?>
                <tr>
                    <td><?php echo $count++; ?></td>
                    <td>
                        <?php echo ($type == 'sent')
                            ? '<i class="fas fa-share-square text-muted"></i>'
                            : '<i class="far fa-envelope text-muted"></i>'; ?>
                    </td>
                    <td><?php echo html_escape($this->application_model->getUserNameByRoleID($userRoleID, $userID)['name']); ?></td>
                    <td>
                        <a href="<?php echo base_url('communication/mailbox/read?type=' . $type . '&id=' . $message->id); ?>" class="mail-subj">
                            <?php echo html_escape($message->subject); ?>
                        </a>
                    </td>
                    <td class="text-muted small"><?php echo html_escape(mb_strimwidth(strip_tags($message->body), 0, 60, '...')); ?></td>
                    <td class="text-nowrap small text-muted"><?php echo get_nicetime(html_escape($message->created_at)); ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
