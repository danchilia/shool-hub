<?php
$message      = $this->communication_model->getSingle('message', $message_id, true);
$getSender    = explode('-', $message->sender);
$senderRoleID = $getSender[0];
$senderUserID = $getSender[1];
$getReciever    = explode('-', $message->reciever);
$recieverRoleID = $getReciever[0];
$recieverUserID = $getReciever[1];
$status = ($message->sender == $active_user) ? $message->fav_sent : $message->fav_inbox;
$senderName   = html_escape($this->application_model->getUserNameByRoleID($senderRoleID, $senderUserID)['name']);
$receiverName = html_escape($this->application_model->getUserNameByRoleID($recieverRoleID, $recieverUserID)['name']);
?>
<style>
@media (prefers-color-scheme:dark) {
    .card        { background:#2b2b3a; border-color:#3a3a50; }
    .card-header { background:#232333; border-color:#3a3a50; }
}
:root[data-theme="dark"]  .card        { background:#2b2b3a; border-color:#3a3a50; }
:root[data-theme="dark"]  .card-header { background:#232333; border-color:#3a3a50; }
:root[data-theme="light"] .card        { background:#fff;    border-color:#dee2e6; }
:root[data-theme="light"] .card-header { background:#f8f9fa; border-color:#dee2e6; }
</style>

<!-- Subject header -->
<div class="card mb-3">
    <div class="card-body">
        <h5 class="mb-1">
            <?php echo html_escape($message->subject); ?>
            <a data-id="<?php echo $message_id; ?>" href="javascript:;" class="mailbox-fav text-warning ms-2"
               data-bs-toggle="tooltip" title="<?php echo translate('important'); ?>">
                <i class="<?php echo ($status == 0 ? 'far fa-bell' : 'fas fa-bell'); ?>"></i>
            </a>
        </h5>
        <p class="text-muted small mb-0">
            <?php echo translate('from'); ?> <strong><?php echo $senderName; ?></strong>
            <?php echo translate('to'); ?> <strong><?php echo $receiverName; ?></strong>
            &mdash; <?php echo date("d M Y", strtotime($message->created_at)); ?>
        </p>
    </div>
</div>

<!-- Original message -->
<div class="card mb-3">
    <div class="card-header">
        <h6 class="mb-0"><?php echo $senderName; ?></h6>
    </div>
    <div class="card-body">
        <?php echo $message->body; ?>
        <?php if (!empty($message->enc_name)): ?>
        <div class="alert alert-secondary mt-3 mb-0 d-flex align-items-center gap-2">
            <i class="fas fa-paperclip"></i>
            <span><?php echo translate('attachment_file'); ?></span>
            <a href="<?php echo base_url('communication/download?type=mailbox&file=' . $message->enc_name); ?>"
               class="btn btn-sm btn-outline-secondary ms-auto">
                <i class="fas fa-download me-1"></i>Download
            </a>
        </div>
        <?php endif; ?>
    </div>
    <div class="card-footer">
        <small class="text-muted"><?php echo date("d M Y - g:i A", strtotime($message->created_at)); ?></small>
    </div>
</div>

<?php
$repliesResult = $this->db->get_where('message_reply', array('message_id' => $message_id))->result();
$reply_status  = $this->db->select('sender,reciever')->where('id', $message_id)->get('message')->row();
foreach ($repliesResult as $reply):
    $user_to_show = ($reply->identity == 1)
        ? explode('-', $reply_status->sender)
        : explode('-', $reply_status->reciever);
    $replyRoleID = $user_to_show[0];
    $replyUserID = $user_to_show[1];
    $replyAuthor = html_escape($this->application_model->getUserNameByRoleID($replyRoleID, $replyUserID)['name']);
?>
<div class="card mb-3">
    <div class="card-header">
        <h6 class="mb-0"><?php echo $replyAuthor; ?></h6>
    </div>
    <div class="card-body">
        <?php echo $reply->body; ?>
        <?php if (!empty($reply->enc_name)): ?>
        <div class="alert alert-secondary mt-3 mb-0 d-flex align-items-center gap-2">
            <i class="fas fa-paperclip"></i>
            <span><?php echo translate('attachment_file'); ?></span>
            <a href="<?php echo base_url('communication/download?type=reply&file=' . $reply->enc_name); ?>"
               class="btn btn-sm btn-outline-secondary ms-auto">
                <i class="fas fa-download me-1"></i>Download
            </a>
        </div>
        <?php endif; ?>
    </div>
    <div class="card-footer">
        <small class="text-muted"><?php echo date("d M Y - g:i A", strtotime($reply->created_at)); ?></small>
    </div>
</div>
<?php endforeach; ?>

<!-- Reply form -->
<div class="card">
    <?php echo form_open_multipart('communication/message_reply', array('class' => 'frm-submit-data')); ?>
    <?php
    $user_identity = ($reply_status->sender == $active_user) ? 'sender' : 'reciever';
    echo form_hidden(array('user_identity' => $user_identity, 'message_id' => $message_id));
    ?>
    <div class="card-header">
        <h6 class="mb-0"><i class="far fa-envelope me-1"></i><?php echo translate('reply_message'); ?></h6>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <textarea name="message" class="form-control summernote" id="summernote" rows="8"></textarea>
            <span class="error small text-danger d-block"></span>
        </div>
        <div class="mb-0">
            <label class="form-label"><?php echo translate('attachment_file'); ?></label>
            <input type="file" name="attachment_file" class="dropify" data-height="80"
                data-allowed-file-extensions="pdf csv doc xls docx xlsx jpg jpeg png gif bmp">
            <span class="error small text-danger d-block"></span>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-end">
        <button type="submit" class="btn btn-primary" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
            <i class="fas fa-paper-plane me-1"></i><?php echo translate('send'); ?>
        </button>
    </div>
    <?php echo form_close(); ?>
</div>
