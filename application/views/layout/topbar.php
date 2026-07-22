<?php
$unreadMessages = $this->application_model->unread_message_alert();
$msgCount       = count($unreadMessages);

$sessions  = $this->db->get('schoolyear')->result();
$set_lang  = $this->session->has_userdata('set_lang')
             ? $this->session->userdata('set_lang')
             : get_global_setting('translation');
$languages = $this->db->select('id,lang_field,name')->where('status', 1)->get('language_list')->result();

$userPhoto = get_image_url(get_loggedin_user_type(), $this->session->userdata('logger_photo'));
$userName  = $this->session->userdata('name');
$userRole  = ucfirst(loggedin_role_name());
?>
<header class="dck-topbar">

    <!-- Sidebar toggle -->
    <button class="dck-topbar-toggle" id="dckSidebarToggle" title="Toggle sidebar">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Search bar -->
    <?php if (get_permission('student', 'is_view')): ?>
    <div class="dck-topbar-search d-none d-sm-block">
        <?php echo form_open('student/search', ['class' => 'search-form']); ?>
            <div class="input-group">
                <input type="text" class="form-control" name="search_text"
                       placeholder="<?php echo translate('search'); ?> students…">
                <button class="btn" type="submit"><i class="fas fa-search"></i></button>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <!-- Right actions -->
    <div class="dck-topbar-actions">

        <!-- ── Quick links ───────────────────────────────────────────── -->
        <?php
        $showQuick = get_permission('student', 'is_add') ||
                     get_permission('salary_payment', 'is_add') ||
                     get_permission('leave_manage', 'is_view') ||
                     get_permission('live_class', 'is_view') ||
                     get_permission('due_invoice', 'is_view') ||
                     get_permission('invoice', 'is_view');
        if ($showQuick):
        ?>
        <div class="position-relative">
            <button class="dck-tb-btn" data-dck-dropdown="#dckQkDropdown" title="Quick Links">
                <i class="fas fa-th"></i>
            </button>
            <div class="dck-topbar-dropdown" id="dckQkDropdown" style="display:none; width:240px;">
                <div class="dck-td-header"><i class="fas fa-bolt"></i> Quick Links</div>
                <div class="dck-qk-grid">
                    <?php if (get_permission('student', 'is_add')): ?>
                    <a href="<?php echo base_url('student/add'); ?>">
                        <i class="fas fa-user-plus"></i>
                        <?php echo translate('student_admission'); ?>
                    </a>
                    <?php endif; if (get_permission('salary_payment', 'is_add')): ?>
                    <a href="<?php echo base_url('payroll'); ?>">
                        <i class="fas fa-donate"></i>
                        <?php echo translate('salary_payment'); ?>
                    </a>
                    <?php endif; if (get_permission('leave_manage', 'is_view')): ?>
                    <a href="<?php echo base_url('leave'); ?>">
                        <i class="fas fa-umbrella-beach"></i>
                        <?php echo translate('leave_application'); ?>
                    </a>
                    <?php endif; if (get_permission('live_class', 'is_view')): ?>
                    <a href="<?php echo base_url('live_class'); ?>">
                        <i class="fas fa-video"></i>
                        <?php echo translate('live_class_rooms'); ?>
                    </a>
                    <?php endif; if (get_permission('due_invoice', 'is_view')): ?>
                    <a href="<?php echo base_url('fees/due_invoice'); ?>">
                        <i class="fas fa-hand-holding-usd"></i>
                        <?php echo translate('due_fees_invoice'); ?>
                    </a>
                    <?php endif; if (get_permission('invoice', 'is_view')): ?>
                    <a href="<?php echo base_url('fees/invoice_list'); ?>">
                        <i class="fas fa-file-invoice"></i>
                        <?php echo translate('payments_history'); ?>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- ── Academic session switcher ────────────────────────────── -->
        <div class="position-relative">
            <button class="dck-tb-btn" data-dck-dropdown="#dckSessionDropdown" title="<?php echo translate('academic_session'); ?>">
                <i class="far fa-calendar-alt"></i>
            </button>
            <div class="dck-topbar-dropdown" id="dckSessionDropdown" style="display:none;">
                <div class="dck-td-header"><i class="far fa-calendar-alt"></i> <?php echo translate('academic_session'); ?></div>
                <div class="dck-td-body">
                    <ul>
                        <?php foreach ($sessions as $session): ?>
                        <li>
                            <a href="<?php echo base_url('sessions/set_academic/' . $session->id); ?>">
                                <i class="fas fa-<?php echo get_session_id() == $session->id ? 'check-circle text-success' : 'circle'; ?>"></i>
                                <?php echo html_escape($session->school_year); ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- ── Language switcher ────────────────────────────────────── -->
        <div class="position-relative">
            <button class="dck-tb-btn" data-dck-dropdown="#dckLangDropdown" title="<?php echo translate('language'); ?>">
                <i class="far fa-flag"></i>
            </button>
            <div class="dck-topbar-dropdown" id="dckLangDropdown" style="display:none;">
                <div class="dck-td-header"><i class="far fa-flag"></i> <?php echo translate('language'); ?></div>
                <div class="dck-td-body">
                    <ul>
                        <?php foreach ($languages as $lang): ?>
                        <li>
                            <a href="<?php echo base_url('translations/set_language/' . html_escape($lang->lang_field)); ?>">
                                <img src="<?php echo $this->application_model->getLangImage($lang->id); ?>"
                                     alt="<?php echo html_escape($lang->lang_field); ?>"
                                     width="20" height="14" style="border-radius:2px; flex-shrink:0;">
                                <?php echo ucfirst(html_escape($lang->name)); ?>
                                <?php if ($set_lang == $lang->lang_field): ?>
                                <i class="fas fa-check ms-auto" style="color:var(--dck-success); font-size:.7rem;"></i>
                                <?php endif; ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- ── Message / Notification bell ─────────────────────────── -->
        <div class="position-relative">
            <button class="dck-tb-btn" data-dck-dropdown="#dckMsgDropdown" title="<?php echo translate('message'); ?>">
                <i class="far fa-bell"></i>
                <?php if ($msgCount > 0): ?>
                <span class="dck-tb-badge"><?php echo $msgCount; ?></span>
                <?php endif; ?>
            </button>
            <div class="dck-topbar-dropdown" id="dckMsgDropdown" style="display:none; min-width:300px;">
                <div class="dck-td-header"><i class="far fa-bell"></i> <?php echo translate('message'); ?></div>
                <div class="dck-td-body">
                    <ul>
                        <?php if ($msgCount > 0): foreach ($unreadMessages as $msg): ?>
                        <li>
                            <a href="<?php echo base_url('communication/mailbox/read?type=' . $msg['msg_type'] . '&id=' . $msg['id']); ?>">
                                <img class="dck-msg-img" src="<?php echo $msg['message_details']['imgPath']; ?>" alt="avatar">
                                <div class="dck-msg-body">
                                    <div class="dck-msg-from"><?php echo html_escape($msg['message_details']['userName']); ?></div>
                                    <div class="dck-msg-preview"><?php echo mb_strimwidth(strip_tags($msg['body']), 0, 48, '…'); ?></div>
                                    <div class="dck-msg-time"><?php echo get_nicetime($msg['created_at']); ?></div>
                                </div>
                            </a>
                        </li>
                        <?php endforeach; else: ?>
                        <li class="empty-state"><i class="far fa-bell-slash d-block mb-1" style="font-size:1.4rem;color:var(--dck-muted);"></i>No new messages</li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="dck-td-footer">
                    <a href="<?php echo base_url('communication/mailbox/inbox'); ?>">View all messages <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <!-- PWA Install Button (hidden until browser fires beforeinstallprompt) -->
        <button id="pwa-install-btn" onclick="installPWA()" class="dck-icon-btn" title="Install App" style="display:none;background:none;border:none;cursor:pointer;padding:6px 8px;color:inherit">
            <i class="fas fa-download"></i>
        </button>

        <div class="dck-topbar-divider"></div>

        <!-- ── User profile dropdown ─────────────────────────────────── -->
        <div class="position-relative">
            <button class="dck-user-btn" data-dck-dropdown="#dckUserDropdown">
                <img class="dck-user-avatar" src="<?php echo $userPhoto; ?>" alt="avatar">
                <div class="text-start d-none d-md-block">
                    <div class="dck-user-name"><?php echo html_escape($userName); ?></div>
                    <div class="dck-user-role"><?php echo html_escape($userRole); ?></div>
                </div>
                <i class="fas fa-chevron-down ms-1" style="font-size:.6rem; color:var(--dck-muted);"></i>
            </button>

            <div class="dck-topbar-dropdown dck-user-dropdown" id="dckUserDropdown" style="display:none; right:0;">
                <div class="dck-user-dropdown-profile">
                    <img src="<?php echo $userPhoto; ?>" alt="avatar">
                    <h6><?php echo html_escape($userName); ?></h6>
                    <small><?php echo html_escape($userRole); ?></small>
                </div>
                <div class="dck-td-body">
                    <ul>
                        <li><a href="<?php echo base_url('profile'); ?>"><i class="fas fa-user-circle"></i> <?php echo translate('profile'); ?></a></li>
                        <li><a href="<?php echo base_url('profile/password'); ?>"><i class="fas fa-key"></i> <?php echo translate('reset_password'); ?></a></li>
                        <li><a href="<?php echo base_url('communication/mailbox/inbox'); ?>"><i class="far fa-envelope"></i> <?php echo translate('mailbox'); ?></a></li>
                        <?php if (get_permission('global_settings', 'is_view')): ?>
                        <li><hr class="my-1"></li>
                        <li><a href="<?php echo base_url('settings/universal'); ?>"><i class="fas fa-toolbox"></i> <?php echo translate('global_settings'); ?></a></li>
                        <?php endif; ?>
                        <?php if (get_permission('school_settings', 'is_view') && !is_superadmin_loggedin()): ?>
                        <li><a href="<?php echo base_url('settings/school'); ?>"><i class="fas fa-school"></i> <?php echo translate('school_settings'); ?></a></li>
                        <?php endif; ?>
                        <li><hr class="my-1"></li>
                        <li>
                            <a href="<?php echo base_url('authentication/logout'); ?>" style="color:var(--dck-danger);">
                                <i class="fas fa-sign-out-alt"></i> <?php echo translate('logout'); ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div><!-- /.dck-topbar-actions -->

</header>
