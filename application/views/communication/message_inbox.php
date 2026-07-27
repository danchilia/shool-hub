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
        <h6 class="mb-0"><i class="far fa-envelope me-1"></i><?php echo translate('inbox'); ?></h6>
        <div class="d-flex gap-2">
            <a href="<?php echo base_url('communication/mailbox/inbox'); ?>" class="btn btn-sm btn-outline-secondary"
               data-bs-toggle="tooltip" title="<?php echo translate('refresh_mail'); ?>">
                <i class="fas fa-sync"></i>
            </a>
            <button class="btn btn-sm btn-danger" id="msgAction" data-type="delete"
                    data-bs-toggle="tooltip" title="<?php echo translate('delete'); ?>">
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
                        <th><?php echo translate('sender'); ?></th>
                        <th><?php echo translate('subjects'); ?></th>
                        <th><?php echo translate('message'); ?></th>
                        <th><?php echo translate('time'); ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $this->db->order_by('id', 'desc');
                $messages = $this->db->get_where('message', array('reciever' => $active_user, 'trash_inbox' => 0))->result();
                foreach ($messages as $message):
                    $get_sender  = explode('-', $message->sender);
                    $senderRoleID = $get_sender[0];
                    $senderUserID = $get_sender[1];
                ?>
                <tr class="<?php echo ($message->read_status == 0 ? 'fw-bold' : ''); ?>">
                    <td>
                        <div class="form-check mb-0">
                            <input class="form-check-input msg_checkbox" type="checkbox" id="<?php echo $message->id; ?>">
                        </div>
                    </td>
                    <td>
                        <a data-id="<?php echo $message->id; ?>" href="javascript:void(0);" class="mailbox-fav text-warning me-1"
                           data-bs-toggle="tooltip" title="<?php echo translate('important'); ?>">
                            <i class="<?php echo ($message->fav_inbox == 0 ? 'far fa-bell' : 'fas fa-bell'); ?>"></i>
                        </a>
                        <?php echo html_escape($this->application_model->getUserNameByRoleID($senderRoleID, $senderUserID)['name']); ?>
                    </td>
                    <td>
                        <?php echo (!empty($message->file_name) ? '<i class="fas fa-paperclip me-1"></i>' : ''); ?>
                        <a href="<?php echo base_url('communication/mailbox/read?type=inbox&id=' . $message->id); ?>" class="mail-subj">
                            <?php echo html_escape($message->subject); ?>
                        </a>
                    </td>
                    <td class="text-muted small"><?php echo html_escape(mb_strimwidth(strip_tags($message->body), 0, 60, '...')); ?></td>
                    <td class="text-nowrap small text-muted"><?php echo get_nicetime($message->created_at); ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
