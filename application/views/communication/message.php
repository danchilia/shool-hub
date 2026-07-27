<?php $active = html_escape($this->input->get('type')); ?>
<style>
@media (prefers-color-scheme:dark) {
    .card        { background:#2b2b3a; border-color:#3a3a50; }
    .card-header { background:#232333; border-color:#3a3a50; }
    .nav-pills .nav-link:not(.active) { color:inherit; }
}
:root[data-theme="dark"]  .card        { background:#2b2b3a; border-color:#3a3a50; }
:root[data-theme="dark"]  .card-header { background:#232333; border-color:#3a3a50; }
:root[data-theme="dark"]  .nav-pills .nav-link:not(.active) { color:inherit; }
:root[data-theme="light"] .card        { background:#fff;    border-color:#dee2e6; }
:root[data-theme="light"] .card-header { background:#f8f9fa; border-color:#dee2e6; }
</style>

<div class="container-fluid">
    <div class="row g-3">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body p-2">
                    <a href="<?php echo base_url('communication/mailbox/compose'); ?>" class="btn btn-primary w-100 mb-3">
                        <i class="fas fa-envelope me-1"></i><?php echo translate('compose'); ?>
                    </a>
                    <ul class="nav flex-column nav-pills gap-1">
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center <?php echo ($inside_subview == 'message_inbox' || $active == 'inbox') ? 'active' : ''; ?>"
                               href="<?php echo base_url('communication/mailbox/inbox'); ?>">
                                <i class="far fa-envelope me-2"></i><?php echo translate('inbox'); ?>
                                <?php $unread = $this->application_model->count_unread_message(); if ($unread > 0): ?>
                                <span class="badge bg-danger ms-auto"><?php echo $unread; ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center <?php echo ($inside_subview == 'message_sent' || $active == 'sent') ? 'active' : ''; ?>"
                               href="<?php echo base_url('communication/mailbox/sent'); ?>">
                                <i class="fas fa-share-square me-2"></i><?php echo translate('sent'); ?>
                                <?php $unreplied = $this->application_model->reply_count_unread_message(); if ($unreplied > 0): ?>
                                <span class="badge bg-secondary ms-auto"><?php echo $unreplied; ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center <?php echo ($inside_subview == 'message_important') ? 'active' : ''; ?>"
                               href="<?php echo base_url('communication/mailbox/important'); ?>">
                                <i class="far fa-bell text-warning me-2"></i><?php echo translate('important'); ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center <?php echo ($inside_subview == 'message_trash') ? 'active' : ''; ?>"
                               href="<?php echo base_url('communication/mailbox/trash'); ?>">
                                <i class="far fa-trash-alt me-2"></i><?php echo translate('trash'); ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <?php $this->load->view('communication/' . $inside_subview . '.php') ?>
        </div>
    </div>
</div>
