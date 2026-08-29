<aside class="dck-sidebar" id="dckSidebar">

    <!-- Brand / Logo -->
    <div class="dck-sidebar-brand">
        <a href="<?php echo base_url('dashboard'); ?>" class="dck-sidebar-logo">
            <img src="<?php echo base_url('uploads/app_image/logo-small.png'); ?>" alt="CST SchoolHub"
                 onerror="this.src='<?php echo base_url('assets/images/cst-logo.png'); ?>'">
            <div class="dck-sidebar-brand-name">
                CST SchoolHub
                <small>Management System</small>
            </div>
        </a>
        <button class="dck-sidebar-close" id="dckSidebarClose" title="Close">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="dck-sidebar-nav">
        <nav id="menu" class="nav-main" role="navigation">
            <ul class="nav-main">
                    <!-- dashboard -->
                    <?php if (is_superadmin_loggedin()) { ?>
                    <li class="nav-parent <?php if ($main_menu == 'dashboard') echo 'nav-active nav-expanded';?>">
                        <a>
                            <i class="icons icon-grid"></i><span><?=translate('dashboard')?></span>
                        </a>
                        <ul class="nav nav-children">
                        <?php $school_id = $this->input->get('school_id'); ?>
                            <li class="<?php if ($main_menu == 'dashboard' && empty($school_id)) echo 'nav-active';?>">
                                <a href="<?=base_url('dashboard')?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i> <?=translate('all_branches')?></span>
                                </a>
                            </li>
                            <?php
                                $branches = $this->db->get('branch')->result();
                                foreach($branches as $row){
                            ?>
                                <li class="<?php if ($school_id == $row->id) echo 'nav-active';?>">
                                    <a href="<?=base_url('dashboard/index?school_id='.$row->id)?>">
                                        <span><i class="fas fa-caret-right" aria-hidden="true"></i> <?=html_escape($row->name)?></span>
                                    </a>
                                </li>
                        <?php } ?>
                        </ul>
                    </li>
                    <?php } else { ?>
                            <li class="<?php if ($main_menu == 'dashboard') echo 'nav-active'; ?>">
                                <a href="<?=base_url('dashboard')?>">
                                    <i class="icons icon-grid"></i><span><?=translate('dashboard')?></span>
                                </a>
                            </li>
                            <?php
                            /* Setup Guide — only for school admins, not superadmin */
                            if (is_admin_loggedin()):
                                $CI =& get_instance();
                                $sg_branch = get_loggedin_branch_id();
                                $sg_session = get_session_id();
                                $sg_done = 0;
                                $sg_total = 9; // core steps checked here
                                if ($CI->db->where('branch_id',$sg_branch)->count_all_results('student_category') > 0)   $sg_done++;
                                if ($CI->db->where('branch_id',$sg_branch)->count_all_results('section') > 0)            $sg_done++;
                                if ($CI->db->where('branch_id',$sg_branch)->count_all_results('class') > 0)              $sg_done++;
                                if ($CI->db->where('branch_id',$sg_branch)->count_all_results('subject') > 0)            $sg_done++;
                                if ($CI->db->where('branch_id',$sg_branch)->count_all_results('staff') > 0)              $sg_done++;
                                if ($CI->db->where('branch_id',$sg_branch)->count_all_results('fees_type') > 0)          $sg_done++;
                                if ($CI->db->where('branch_id',$sg_branch)->where('session_id',$sg_session)->count_all_results('fee_groups') > 0) $sg_done++;
                                if ($CI->db->where('branch_id',$sg_branch)->where('session_id',$sg_session)->count_all_results('enroll') > 0) $sg_done++;
                                if ($CI->db->where('branch_id',$sg_branch)->count_all_results('exam') > 0)               $sg_done++;
                                $sg_incomplete = $sg_done < $sg_total;
                            ?>
                            <li class="<?php if ($main_menu == 'setup_guide') echo 'nav-active'; ?>" style="<?=$sg_incomplete ? 'border-left:3px solid #f59e0b;' : ''?>">
                                <a href="<?=base_url('setup_guide')?>" style="<?=$sg_incomplete ? 'font-weight:600;' : ''?>">
                                    <i class="fas fa-compass" style="<?=$sg_incomplete ? 'color:#f59e0b' : ''?>"></i>
                                    <span>
                                        Setup Guide
                                        <?php if ($sg_incomplete): ?>
                                            <span style="display:inline-block;width:7px;height:7px;border-radius:50%;background:#f59e0b;vertical-align:middle;margin-left:4px;animation:sg-pulse 1.6s infinite"></span>
                                        <?php endif; ?>
                                    </span>
                                </a>
                            </li>
                            <?php endif; ?>
                    <?php } ?>
                    <!-- all available modules (training/demo page) -->
                    <?php if (is_admin_loggedin() || is_superadmin_loggedin()): ?>
                    <li class="<?php if ($main_menu == 'module_map') echo 'nav-active';?>">
                        <a href="<?=base_url('module_map')?>">
                            <i class="fas fa-th-large"></i><span>All Available Modules</span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <!-- agent management (superadmin only) -->
                    <?php if (is_superadmin_loggedin()): ?>
                    <li class="nav-parent <?php if ($main_menu == 'agents') echo 'nav-expanded nav-active';?>">
                        <a>
                            <i class="fas fa-user-tie"></i><span>Agent Management</span>
                        </a>
                        <ul class="nav-children">
                            <li class="<?php if ($sub_page == 'agents/index') echo 'nav-active';?>">
                                <a href="<?=base_url('agents')?>">
                                    <span><i class="fas fa-caret-right"></i> Field Agents</span>
                                </a>
                            </li>
                            <li class="<?php if ($sub_page == 'agents/earnings') echo 'nav-active';?>">
                                <a href="<?=base_url('agents/earnings')?>">
                                    <span><i class="fas fa-caret-right"></i> Agent Earnings</span>
                                </a>
                            </li>
                            <li class="<?php if ($sub_page == 'agents/all_schools') echo 'nav-active';?>">
                                <a href="<?=base_url('agents/all_schools')?>">
                                    <span><i class="fas fa-caret-right"></i> Prospect Schools</span>
                                </a>
                            </li>
                            <li class="<?php if ($sub_page == 'agents/expenses') echo 'nav-active';?>">
                                <a href="<?=base_url('agents/expenses')?>">
                                    <span><i class="fas fa-caret-right"></i> Expense Claims</span>
                                </a>
                            </li>
                            <li class="<?php if ($main_menu == 'dck_plans') echo 'nav-active';?>">
                                <a href="<?=base_url('dck_plans')?>">
                                    <span><i class="fas fa-caret-right"></i> DCK Plans</span>
                                </a>
                            </li>
                            <li class="<?php if ($main_menu == 'contact_requests') echo 'nav-active';?>">
                                <a href="<?=base_url('contact/requests')?>">
                                    <span><i class="fas fa-caret-right"></i> Demo Requests</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <?php if (is_superadmin_loggedin()) : ?>
                    <!-- branch -->
                    <li class="nav-parent <?php if ($main_menu == 'branch' || $main_menu == 'subscription') echo 'nav-expanded nav-active';?>">
                        <a>
                            <i class="icons icon-directions"></i><span><?=translate('branch')?></span>
                        </a>
                        <ul class="nav nav-children">
                            <li class="<?php if ($main_menu == 'branch') echo 'nav-active';?>">
                                <a href="<?=base_url('branch')?>">
                                    <span><i class="fas fa-caret-right"></i> Manage Branches</span>
                                </a>
                            </li>
                            <li class="<?php if ($sub_page == 'subscription/plans') echo 'nav-active';?>">
                                <a href="<?=base_url('subscription/plans')?>">
                                    <span><i class="fas fa-caret-right"></i> Subscription Plans</span>
                                </a>
                            </li>
                            <li class="<?php if ($sub_page == 'subscription/index') echo 'nav-active';?>">
                                <a href="<?=base_url('subscription')?>">
                                    <span><i class="fas fa-caret-right"></i> Branch Subscriptions</span>
                                </a>
                            </li>
                            <li class="<?php if ($sub_page == 'subscription/invoices') echo 'nav-active';?>">
                                <a href="<?=base_url('subscription/invoices')?>">
                                    <span><i class="fas fa-caret-right"></i> Invoices</span>
                                </a>
                            </li>
                            <li class="<?php if ($main_menu == 'setup_guide') echo 'nav-active';?>">
                                <a href="<?=base_url('setup_guide')?>">
                                    <span><i class="fas fa-caret-right"></i> School Setup Guide</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <?php if (is_superadmin_loggedin()): ?>
                    <!-- careers portal -->
                    <li class="nav-parent <?php if ($main_menu == 'careers') echo 'nav-expanded nav-active'; ?>">
                        <a>
                            <i class="fas fa-briefcase"></i><span>Careers Portal</span>
                        </a>
                        <ul class="nav nav-children">
                            <li class="<?php if ($sub_page == 'careers/manage') echo 'nav-active'; ?>">
                                <a href="<?=base_url('careers/manage')?>">
                                    <span><i class="fas fa-caret-right"></i> Manage Jobs</span>
                                </a>
                            </li>
                            <li class="<?php if ($sub_page == 'careers/add_job') echo 'nav-active'; ?>">
                                <a href="<?=base_url('careers/add_job')?>">
                                    <span><i class="fas fa-caret-right"></i> Post New Job</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?=base_url('careers')?>" target="_blank">
                                    <span><i class="fas fa-caret-right"></i> View Public Page</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <?php
                    if (get_permission('student', 'is_add') ||
                    get_permission('multiple_import', 'is_add') ||
                    get_permission('student_category', 'is_view') ||
                    get_permission('admission_request', 'is_add') ||
                    get_permission('admission_approval', 'is_view')) {
                    ?>
                    <!-- admission -->
                    <li class="nav-parent <?php if ($main_menu == 'admission') echo 'nav-expanded nav-active';?>">
                        <a>
                            <i class="far fa-edit"></i><span><?=translate('admission')?></span>
                        </a>
                        <ul class="nav nav-children">
                        <?php if(get_permission('student', 'is_add')){ ?>
                            <li class="<?php if ($sub_page == 'student/add') echo 'nav-active';?>">
                                <a href="<?=base_url('student/add')?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?=translate('create_admission')?></span>
                                </a>
                            </li>
                            <li>
                                <a href="<?=base_url('admission_portal')?>" target="_blank">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i> Online Admission Portal <i class="fas fa-external-link-alt" style="font-size:.7rem;opacity:.6"></i></span>
                                </a>
                            </li>
                        <?php } if(get_permission('admission_request', 'is_add')){ ?>
                            <li class="<?php if ($sub_page == 'admission_request/add') echo 'nav-active';?>">
                                <a href="<?=base_url('admission_request/add')?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i> Request Admission</span>
                                </a>
                            </li>
                            <li class="<?php if ($sub_page == 'admission_request/csv_import') echo 'nav-active';?>">
                                <a href="<?=base_url('admission_request/csv_import')?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i> Bulk Request (CSV)</span>
                                </a>
                            </li>
                        <?php } if(get_permission('admission_approval', 'is_view') || get_permission('admission_request', 'is_view')){ ?>
                            <li class="<?php if ($sub_page == 'admission_request/index') echo 'nav-active';?>">
                                <a href="<?=base_url('admission_request')?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i> Admission Requests
                                    <?php
                                        if (get_permission('admission_approval', 'is_view') && !is_superadmin_loggedin()) {
                                            $CI =& get_instance();
                                            $CI->load->model('admission_request_model');
                                            $pendingCount = $CI->admission_request_model->getPendingCount(get_loggedin_branch_id());
                                            if ($pendingCount > 0) {
                                                echo '<span class="badge badge-danger">' . $pendingCount . '</span>';
                                            }
                                        }
                                    ?>
                                    </span>
                                </a>
                            </li>
                        <?php } if(get_permission('multiple_import', 'is_add')){ ?>
                            <li class="<?php if ($sub_page == 'student/multi_add') echo 'nav-active';?>">
                                <a href="<?=base_url('student/csv_import')?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?=translate('multiple_import')?></span>
                                </a>
                            </li>
                        <?php } if(get_permission('student_category', 'is_view')){ ?>
                            <li class="<?php if ($sub_page == 'student/category') echo 'nav-active';?>">
                                <a href="<?=base_url('student/category')?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?=translate('category')?></span>
                                </a>
                            </li>
                        <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>

                    <?php
                    if (get_permission('student', 'is_view') ||
                    get_permission('student_disable_authentication', 'is_view') ||
                    get_permission('student_id_card', 'is_view')) {
                    ?>
                    <!-- student details -->
                    <li class="nav-parent <?php if ($main_menu == 'student') echo 'nav-expanded nav-active';?>">
                        <a>
                             <i class="icon-graduation icons"></i><span><?=translate('student_details')?></span>
                        </a>
                        <ul class="nav nav-children">
                        <?php if(get_permission('student', 'is_view')){ ?>
                            <li class="<?php if ($sub_page == 'student/view' || $sub_page == 'student/profile') echo 'nav-active';?>">
                                <a href="<?=base_url('student/view')?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?=translate('student_list')?></span>
                                </a>
                            </li>
                        <?php } if(get_permission('student_id_card', 'is_view')){ ?>
                            <li class="<?php if ($sub_page == 'student/idcard') echo 'nav-active';?>">
                                <a href="<?=base_url('student/generate_idcard')?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?=translate('id_card_generate')?></span>
                                </a>
                            </li>
                        <?php } if(get_permission('student_disable_authentication', 'is_view')){ ?>
                            <li class="<?php if ($sub_page == 'student/disable_authentication') echo 'nav-active';?>">
                                <a href="<?=base_url('student/disable_authentication')?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?=translate('login_deactivate')?></span>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if (is_admin_loggedin() || is_superadmin_loggedin()): ?>
                            <li class="<?php if ($sub_page == 'student/nemis_export') echo 'nav-active';?>">
                                <a href="<?=base_url('student/nemis_export')?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i> NEMIS Export</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php
                    if (get_permission('parent', 'is_view') ||
                    get_permission('parent', 'is_add') ||
                    get_permission('parent_disable_authentication', 'is_view')) {
                    ?>
                    <!-- parents -->
                    <li class="nav-parent <?php if ($main_menu == 'parents') echo 'nav-expanded nav-active';?>">
                        <a>
                            <i class="icons icon-user-follow"></i><span><?=translate('parents')?></span>
                        </a>
                        <ul class="nav nav-children">
                        <?php if(get_permission('parent', 'is_view')){ ?>
                            <li class="<?php if ($sub_page == 'parents/view' || $sub_page == 'parents/profile') echo 'nav-active';?>">
                                <a href="<?=base_url('parents/view')?>">
                                    <span><i class="fas fa-caret-right"></i><?=translate('parents_list')?></span>
                                </a>
                            </li>
                        <?php } if(get_permission('parent', 'is_add')){ ?>
                            <li class="<?php if ($sub_page == 'parents/add') echo 'nav-active';?>">
                                <a href="<?=base_url('parents/add')?>">
                                    <span><i class="fas fa-caret-right"></i><?=translate('add_parent')?></span>
                                </a>
                            </li>
                        <?php } if(get_permission('parent_disable_authentication', 'is_view')){ ?>
                            <li class="<?php if ($sub_page == 'parents/disable_authentication') echo 'nav-active';?>">
                                <a href="<?=base_url('parents/disable_authentication')?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?=translate('login_deactivate')?></span>
                                </a>
                            </li>
                        <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php
                    if(get_permission('employee', 'is_view') ||
                    get_permission('employee', 'is_add') ||
                    get_permission('designation', 'is_view') ||
                    get_permission('designation', 'is_add') ||
                    get_permission('department', 'is_view') ||
                    get_permission('employee_disable_authentication', 'is_view')) {
                    ?>
                    <!-- Employees -->
                    <li class="nav-parent <?php if ($main_menu == 'employee') echo 'nav-expanded nav-active'; ?>">
                        <a><i class="fas fa-users"></i><span><?php echo translate('employee'); ?></span></a>
                        <ul class="nav nav-children">
                        <?php if(get_permission('employee', 'is_view')){ ?>
                            <li class="<?php if ($sub_page == 'employee/view' ||  $sub_page == 'employee/profile' ) echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('employee/view'); ?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?php echo translate('employee_list'); ?></span>
                                </a>
                            </li>
                        <?php } if(get_permission('department', 'is_view') || get_permission('department', 'is_add')){ ?>
                            <li class="<?php if ($sub_page == 'employee/department') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('employee/department'); ?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?php echo translate('add_department'); ?></span>
                                </a>
                            </li>
                        <?php }  if(get_permission('designation', 'is_view') || get_permission('designation', 'is_add')){ ?>
                            <li class="<?php if ($sub_page == 'employee/designation') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('employee/designation'); ?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?php echo translate('add_designation'); ?></span>
                                </a>
                            </li>
                        <?php } if(get_permission('employee', 'is_add')){ ?>
                            <li class="<?php if ($sub_page == 'employee/add') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('employee/add'); ?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?php echo translate('add_employee'); ?></span>
                                </a>
                            </li>
                        <?php } if(get_permission('employee_disable_authentication', 'is_view')){ ?>
                            <li class="<?php if ($sub_page == 'employee/disable_authentication') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('employee/disable_authentication'); ?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?php echo translate('login_deactivate'); ?></span>
                                </a>
                            </li>
                        <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php
                    if(get_permission('salary_template', 'is_view') ||
                    get_permission('salary_assign', 'is_view') ||
                    get_permission('salary_payment', 'is_view') ||
                    get_permission('advance_salary_manage', 'is_view') ||
                    get_permission('advance_salary_request', 'is_view') ||
                    get_permission('leave_category', 'is_view') ||
                    get_permission('leave_category', 'is_add') ||
                    get_permission('leave_request', 'is_view') ||
                    get_permission('leave_manage', 'is_view') ||
                    get_permission('award', 'is_view')) {
                    ?>
                    <!-- human resource -->
                    <li class="nav-parent <?php if ($main_menu == 'payroll' || $main_menu == 'advance_salary' || $main_menu == 'leave' || $main_menu == 'award') echo 'nav-expanded nav-active';?>">
                        <a>
                            <i class="icons icon-loop"></i><span><?=translate('hrm')?></span>
                        </a>
                        <ul class="nav nav-children">
                            <?php
                            if(get_permission('salary_template', 'is_view') ||
                            get_permission('salary_assign', 'is_view') ||
                            get_permission('salary_payment', 'is_view')) {
                            ?>
                            <!-- payroll -->
                            <li class="nav-parent <?php if($main_menu == 'payroll') echo 'nav-expanded nav-active';?>">
                                <a>
                                    <i class="far fa-address-card" aria-hidden="true"></i>
                                    <span><?=translate('payroll')?></span>
                                </a>
                                <ul class="nav nav-children">
                                    <?php if(get_permission('salary_template', 'is_view')){ ?>
                                    <li class="<?php if ($sub_page == 'payroll/salary_templete' || $sub_page == 'payroll/salary_templete_edit') echo 'nav-active';?>">
                                        <a href="<?=base_url('payroll/salary_template')?>">
                                            <span><?=translate('salary_template')?></span>
                                        </a>
                                    </li>
                                    <?php } if(get_permission('salary_assign', 'is_view')){ ?>
                                    <li class="<?php if ($sub_page == 'payroll/salary_assign') echo 'nav-active';?>">
                                        <a href="<?=base_url('payroll/salary_assign')?>">
                                            <span><?=translate('salary_assign')?></span>
                                        </a>
                                    </li>
                                    <?php } if(get_permission('salary_payment', 'is_view')){ ?>
                                    <li class="<?php if ($sub_page == 'payroll/salary_payment' || $sub_page == 'payroll/create' || $sub_page == 'payroll/invoice') echo 'nav-active';?>">
                                        <a href="<?=base_url('payroll')?>">
                                            <span><?=translate('salary_payment')?></span>
                                        </a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </li>
                            <?php } ?>
                            <?php
                            if(get_permission('advance_salary_manage', 'is_view') ||
                            get_permission('advance_salary_request', 'is_view')) {
                            ?>
                            <!-- advance salary managements -->
                            <li class="nav-parent <?php
                            if ($main_menu == 'advance_salary') echo 'nav-expanded nav-active';?>">
                                <a>
                                    <i class="fas fa-funnel-dollar" aria-hidden="true"></i>
                                    <span><?=translate('advance_salary')?></span>
                                </a>
                                <ul class="nav nav-children">
                                    <?php if(get_permission('advance_salary_request', 'is_view')){ ?>
                                    <li class="<?php if ($sub_page == 'advance_salary/request') echo 'nav-active';?>">
                                        <a href="<?=base_url('advance_salary/request')?>">
                                            <span><?=translate('my_application')?></span>
                                        </a>
                                    </li>
                                    <?php } if(get_permission('advance_salary_manage', 'is_view')){ ?>
                                    <li class="<?php if ($sub_page == 'advance_salary/index') echo 'nav-active';?>">
                                        <a href="<?=base_url('advance_salary')?>">
                                            <span><?=translate('manage_application')?></span>
                                        </a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </li>
                            <?php } ?>
                            <?php
                            if(get_permission('leave_category', 'is_view') ||
                            get_permission('leave_manage', 'is_view') ||
                            get_permission('leave_request', 'is_view')) {
                            ?>
                            <!-- leave managements -->
                            <li class="nav-parent <?php
                            if ($main_menu == 'leave') echo 'nav-expanded nav-active';?>">
                                <a>
                                    <i class="fas fa-umbrella-beach" aria-hidden="true"></i>
                                    <span><?=translate('leave')?></span>
                                </a>
                                <ul class="nav nav-children">
                                <?php if(get_permission('leave_category', 'is_view')){ ?>
                                    <li class="<?php if ($sub_page == 'leave/category') echo 'nav-active';?>">
                                        <a href="<?=base_url('leave/category')?>">
                                            <span><?=translate('category')?></span>
                                        </a>
                                    </li>
                                <?php } if(get_permission('leave_request', 'is_view')){ ?>
                                    <li class="<?php if ($sub_page == 'leave/request') echo 'nav-active';?>">
                                        <a href="<?=base_url('leave/request')?>">
                                            <span><?=translate('my_application')?></span>
                                        </a>
                                    </li>
                                <?php } if(get_permission('leave_manage', 'is_view')){ ?>
                                    <li class="<?php if ($sub_page == 'leave/index') echo 'nav-active';?>">
                                        <a href="<?=base_url('leave')?>">
                                            <span><?=translate('manage_application')?></span>
                                        </a>
                                    </li>
                                <?php } ?>
                                </ul>
                            </li>
                            <?php } ?>
                            <?php if(get_permission('award', 'is_view')){ ?>
                            <li class="<?php if ($sub_page == 'award/index' || $sub_page == 'award/edit') echo 'nav-active';?>">
                                 <a href="<?=base_url('award')?>">
                                     <i class="fas fa-crown"></i>
                                     <span><?=translate('award')?></span>
                                 </a>
                            </li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php
                    if(get_permission('classes', 'is_view') ||
                    get_permission('section', 'is_view') ||
                    get_permission('assign_class_teacher', 'is_view') ||
                    get_permission('subject', 'is_view') ||
                    get_permission('subject_class_assign', 'is_view') ||
                    get_permission('subject_teacher_assign', 'is_view') ||
                    get_permission('class_timetable', 'is_view')) {
                    ?>
                    <!-- academic -->
                    <li class="nav-parent <?php if ($main_menu == 'classes' ||
                                                        $main_menu == 'sections' ||
                                                            $main_menu == 'timetable' ||
                                                                $main_menu == 'subject' ||
                                                                    $main_menu == 'transfer') echo 'nav-expanded nav-active';?>">
                        <a>
                            <i class="icons icon-home" aria-hidden="true"></i><span><?=translate('academic')?></span>
                        </a>

                        <ul class="nav nav-children">
                            <?php
                            if(get_permission('classes', 'is_view') ||
                            get_permission('section', 'is_view') ||
                            get_permission('assign_class_teacher', 'is_view')) {
                            ?>
                            <!-- class -->
                            <li class="nav-parent <?php
                            if ($main_menu == 'classes' || $main_menu == 'sections' || $main_menu == 'class_teacher_allocation') echo 'nav-expanded nav-active'; ?>">
                                <a>
                                    <i class="fas fa-tasks" aria-hidden="true"></i>
                                    <span><?=translate('class') . " & ". translate('section')?></span>
                                </a>
                                <ul class="nav nav-children">
                                    <?php if(get_permission('classes', 'is_view') ||  get_permission('section', 'is_view')) { ?>
                                    <li class="<?php if ($sub_page == 'classes/index' ||
                                                            $sub_page == 'classes/edit' ||
                                                                $sub_page == 'sections/index' ||
                                                                    $sub_page == 'sections/edit') echo 'nav-active';?>">
                                        <a href="<?=get_permission('classes', 'is_view') ? base_url('classes') : base_url('sections'); ?>">
                                            <span><?=translate('control_classes')?></span>
                                        </a>
                                    </li>
                                    <?php } ?>
                                    <?php if(get_permission('assign_class_teacher', 'is_view')) { ?>
                                    <li class="<?php if ($sub_page == 'classes/teacher_allocation') echo 'nav-active';?>">
                                        <a href="<?=base_url('classes/teacher_allocation')?>">
                                            <span><?=translate('assign_class_teacher')?></span>
                                        </a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </li>
                            <?php } ?>
                            <?php
                            if(get_permission('subject', 'is_view') ||
                            get_permission('subject_class_assign', 'is_view') ||
                            get_permission('subject_teacher_assign', 'is_view')) {
                            ?>
                            <!-- subject -->
                            <li class="nav-parent <?php if ($main_menu == 'subject') echo 'nav-expanded';?>">
                                <a>
                                    <i class="fas fa-book-reader"></i><?=translate('subject')?>
                                </a>
                                <ul class="nav nav-children">
                                    <?php if(get_permission('subject', 'is_view')) { ?>
                                    <li class="<?php if ($sub_page == 'subject/index' || $sub_page == 'subject/edit') echo 'nav-active';?>">
                                        <a href="<?=base_url('subject/index')?>">
                                            <span><?=translate('subject')?></span>
                                        </a>
                                    </li>
                                    <?php } if(get_permission('subject_class_assign', 'is_view')) { ?>
                                    <li class="<?php if ($sub_page == 'subject/class_assign') echo 'nav-active';?>">
                                        <a href="<?=base_url('subject/class_assign')?>">
                                            <span><?=translate('class_assign')?></span>
                                        </a>
                                    </li>
                                    <?php } if(get_permission('subject_teacher_assign', 'is_view')) { ?>
                                    <li class="<?php if ($sub_page == 'subject/teacher_assign') echo 'nav-active';?>">
                                        <a href="<?=base_url('subject/teacher_assign')?>">
                                            <span><?=translate('teacher') . ' ' . translate('assign')?></span>
                                        </a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </li>
                            <?php } ?>
                            <?php if(get_permission('class_timetable', 'is_view')) { ?>
                            <li class="<?php if ($sub_page == 'timetable/viewclass' || $sub_page == 'timetable/update_classwise' || $sub_page == 'timetable/set_classwise') echo 'nav-active';?>">
                                <a href="<?=base_url('timetable/viewclass')?>">
                                    <span><i class="fas fa-dna" aria-hidden="true"></i><?=translate('class') . " " . translate('schedule')?></span>
                                </a>
                            </li>
                            <?php } ?>
                            <?php if(get_permission('student_promotion', 'is_view')) { ?>
                            <!-- student promotion -->
                            <li class="<?php if ($sub_page == 'student/transfer') echo 'nav-active';?>">
                                <a href="<?=base_url('student/transfer')?>">
                                    <span><i class="fab fa-deviantart" aria-hidden="true"></i><?=translate('promotion')?></span>
                                </a>
                            </li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if(get_permission('live_class', 'is_view')) { ?>
                    <li class="<?php if ($main_menu == 'live_class') echo 'nav-active';?>">
                        <a href="<?=base_url('live_class')?>">
                            <i class="icons icon-earphones-alt"></i><span><?=translate('live_class_rooms')?></span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php
                    if(get_permission('attachments', 'is_view') ||
                    get_permission('attachment_type', 'is_view')) {
                    ?>
                    <!-- attachments upload -->
                    <li class="nav-parent <?php if ($main_menu == 'attachments') echo 'nav-expanded nav-active';?>">
                        <a>
                            <i class="icons icon-cloud-upload"></i><span><?=translate('attachments_book')?></span>
                        </a>
                        <ul class="nav nav-children">
                            <?php if(get_permission('attachments', 'is_view')) { ?>
                            <li class="<?php if ($sub_page == 'attachments/index') echo 'nav-active';?>">
                                <a href="<?=base_url('attachments')?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?=translate('upload_content')?></span>
                                </a>
                            </li>
                            <?php } if(get_permission('attachment_type', 'is_view')) { ?>
                            <li class="<?php if ($sub_page == 'attachments/type') echo 'nav-active';?>">
                                <a href="<?=base_url('attachments/type')?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?=translate('attachment_type')?></span>
                                </a>
                            </li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php
                    if(get_permission('homework', 'is_view') ||
                    get_permission('evaluation_report', 'is_view')) {
                    ?>
                    <!-- attachments upload -->
                    <li class="nav-parent <?php if ($main_menu == 'homework') echo 'nav-expanded nav-active';?>">
                        <a>
                            <i class="icons icon-note"></i><span><?=translate('homework')?></span>
                        </a>
                        <ul class="nav nav-children">
                            <?php if(get_permission('homework', 'is_view')) { ?>
                            <li class="<?php if ($sub_page == 'homework/index' || $sub_page == 'homework/add' || $sub_page == 'homework/evaluate_list' || $sub_page == 'homework/edit') echo 'nav-active';?>">
                                <a href="<?=base_url('homework')?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?=translate('homework')?></span>
                                </a>
                            </li>
                            <?php } if(get_permission('evaluation_report', 'is_view')) { ?>
                            <li class="<?php if ($sub_page == 'homework/report') echo 'nav-active';?>">
                                <a href="<?=base_url('homework/report')?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?=translate('evaluation_report')?></span>
                                </a>
                            </li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php
                    if (get_permission('cbc_assessment', 'is_view') ||
                    get_permission('cbc_learning_areas', 'is_view') ||
                    get_permission('cbc_strands', 'is_view') ||
                    get_permission('cbc_report_card', 'is_view') ||
                    get_permission('cbc_behaviour', 'is_view')) {
                    ?>
                    <!-- CBC Assessment -->
                    <li class="nav-parent <?php if ($main_menu == 'cbc') echo 'nav-expanded nav-active';?>">
                        <a>
                            <i class="fas fa-clipboard-check"></i><span>CBC Assessment</span>
                        </a>
                        <ul class="nav nav-children">
                            <?php if (get_permission('cbc_learning_areas', 'is_view')): ?>
                            <li class="<?php if ($sub_page == 'cbc/learning_areas') echo 'nav-active';?>">
                                <a href="<?=base_url('cbc/learning_areas')?>">
                                    <span><i class="fas fa-caret-right"></i> Learning Areas</span>
                                </a>
                            </li>
                            <?php endif; if (get_permission('cbc_strands', 'is_view')): ?>
                            <li class="<?php if ($sub_page == 'cbc/strands') echo 'nav-active';?>">
                                <a href="<?=base_url('cbc/strands')?>">
                                    <span><i class="fas fa-caret-right"></i> Strands</span>
                                </a>
                            </li>
                            <?php endif; if (get_permission('cbc_assessment', 'is_add')): ?>
                            <li class="<?php if ($sub_page == 'cbc/assessment') echo 'nav-active';?>">
                                <a href="<?=base_url('cbc/assessment')?>">
                                    <span><i class="fas fa-caret-right"></i> Assessment Entry</span>
                                </a>
                            </li>
                            <?php endif; if (get_permission('cbc_behaviour', 'is_add')): ?>
                            <li class="<?php if ($sub_page == 'cbc/behaviour_assessment') echo 'nav-active';?>">
                                <a href="<?=base_url('cbc/behaviour_assessment')?>">
                                    <span><i class="fas fa-caret-right"></i> Behaviour Assessment</span>
                                </a>
                            </li>
                            <?php endif; if (get_permission('cbc_report_card', 'is_view')): ?>
                            <li class="<?php if ($sub_page == 'cbc/report_card') echo 'nav-active';?>">
                                <a href="<?=base_url('cbc/report_card')?>">
                                    <span><i class="fas fa-caret-right"></i> CBC Report Card</span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php } ?>

                    <?php
                    if(get_permission('exam', 'is_view') ||
                    get_permission('exam_term', 'is_view') ||
                    get_permission('mark_distribution', 'is_view') ||
                    get_permission('exam_hall', 'is_view') ||
                    get_permission('exam_timetable', 'is_view') ||
                    get_permission('exam_mark', 'is_view') ||
                    get_permission('exam_grade', 'is_view')) {
                    ?>
                    <!-- exam master -->
                    <li class="nav-parent <?php if ($main_menu == 'exam' || $main_menu == 'mark' || $main_menu == 'exam_timetable') echo 'nav-expanded nav-active';?>">
                        <a>
                            <i class="icons icon-book-open" aria-hidden="true"></i><span><?=translate('exam_master')?></span>
                        </a>
                        <ul class="nav nav-children">
                            <?php
                            if(get_permission('exam', 'is_view') ||
                            get_permission('exam_term', 'is_view') ||
                            get_permission('mark_distribution', 'is_view') ||
                            get_permission('exam_hall', 'is_view')) {
                            ?>
                            <!-- exam -->
                            <li class="nav-parent <?php if ($main_menu == 'exam' || $main_menu == 'exam_term' || $main_menu == 'exam_hall') echo 'nav-expanded nav-active';?>">
                                <a>
                                    <i class="fas fa-flask"></i> <span><?=translate('exam')?></span>
                                </a>
                                <ul class="nav nav-children">
                                    <?php if (get_permission('exam_term', 'is_view')) {  ?>
                                    <li class="<?php if ($sub_page == 'exam/term') echo 'nav-active';?>">
                                        <a href="<?=base_url('exam/term')?>">
                                            <span><?=translate('exam_term')?></span>
                                        </a>
                                    </li>
                                    <?php } if (get_permission('exam_hall', 'is_view')) { ?>
                                    <li class="<?php if ($sub_page == 'exam/hall') echo 'nav-active';?>">
                                        <a href="<?=base_url('exam/hall')?>">
                                            <span><?=translate('exam_hall')?></span>
                                        </a>
                                    </li>
                                    <?php } if (get_permission('mark_distribution', 'is_view')) { ?>
                                    <li class="<?php if ($sub_page == 'exam/mark_distribution') echo 'nav-active';?>">
                                        <a href="<?=base_url('exam/mark_distribution')?>">
                                            <span><?=translate('distribution')?></span>
                                        </a>
                                    </li>
                                    <?php } if (get_permission('exam', 'is_view')) { ?>
                                    <li class="<?php if ($sub_page == 'exam/index') echo 'nav-active';?>">
                                        <a href="<?=base_url('exam')?>">
                                            <span><?=translate('exam_setup')?></span>
                                        </a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </li>
                            <?php } ?>
                            <?php
                            if(get_permission('exam_timetable', 'is_view')) {
                            ?>
                            <!-- exam schedule -->
							<li class="nav-parent <?php if ($main_menu == 'exam_timetable') echo 'nav-expanded nav-active';?>">
                                <a>
                                    <i class="fas fa-dna"></i> <span><?=translate('exam') . " " . translate('schedule')?></span>
                                </a>
                                <ul class="nav nav-children">
                                    <?php if(get_permission('exam_timetable', 'is_view')) { ?>
                                    <li class="<?php if ($sub_page == 'timetable/viewexam') echo 'nav-active';?>">
                                        <a href="<?=base_url('timetable/viewexam')?>">
                                            <span><?=translate('schedule')?></span>
                                        </a>
                                    </li>
                                    <?php } if(get_permission('exam_timetable', 'is_view')) { ?>
                                    <li class="<?php if ($sub_page == 'timetable/set_examwise') echo 'nav-active';?>">
                                        <a href="<?=base_url('timetable/set_examwise')?>">
                                            <span><?=translate('add') . " " . translate('schedule')?></span>
                                        </a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </li>
                            <?php } ?>
                            <?php
                            if(get_permission('exam_mark', 'is_view') ||
                            get_permission('exam_grade', 'is_view')) {
                            ?>
                            <!-- marks -->
                            <li class="nav-parent <?php if ($main_menu == 'mark') echo 'nav-expanded nav-active';?>">
                                <a>
                                    <i class="fas fa-marker"></i><span><?=translate('marks')?></span>
                                </a>
                                <ul class="nav nav-children">
                                    <?php if(get_permission('exam_mark', 'is_view')) { ?>
                                    <li class="<?php if ($sub_page == 'exam/marks_register') echo 'nav-active';?>">
                                        <a href="<?=base_url('exam/mark_entry')?>">
                                            <span><?=translate('mark_entries')?></span>
                                        </a>
                                    </li>
                                    <?php } if(get_permission('exam_grade', 'is_view')) { ?>
                                    <li class="<?php if ($sub_page == 'exam/grade') echo 'nav-active';?>">
                                        <a href="<?=base_url('exam/grade')?>">
                                            <span><?=translate('grades_range')?></span>
                                        </a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php
                    if(get_permission('hostel', 'is_view') ||
                    get_permission('hostel_category', 'is_view') ||
                    get_permission('hostel_room', 'is_view') ||
                    get_permission('hostel_allocation', 'is_view') ||
                    get_permission('transport_route', 'is_view') ||
                    get_permission('transport_vehicle', 'is_view') ||
                    get_permission('transport_stoppage', 'is_view') ||
                    get_permission('transport_assign', 'is_view') ||
                    get_permission('transport_allocation', 'is_view')) {
                    ?>
                    <!-- supervision -->
                    <li class="nav-parent <?php if ($main_menu == 'hostels' || $main_menu == 'transport') echo 'nav-expanded nav-active';?>">
                        <a>
                            <i class="icons icon-feed" aria-hidden="true"></i><span><?=translate('supervision')?></span>
                        </a>
                        <ul class="nav nav-children">
                            <?php
                            if(get_permission('hostel', 'is_view') ||
                            get_permission('hostel_category', 'is_view') ||
                            get_permission('hostel_room', 'is_view') ||
                            get_permission('hostel_allocation', 'is_view')) {
                            ?>
                            <!-- hostels -->
                            <li class="nav-parent <?php if ($main_menu == 'hostels') echo 'nav-expanded nav-active';?>">
                                <a>
                                    <i class="fas fa-store-alt"></i><span><?=translate('hostel')?></span>
                                </a>
                                <ul class="nav nav-children">
                                    <?php  if(get_permission('hostel', 'is_view')) { ?>
                                    <li class="<?php if ($sub_page == 'hostels/index' || $sub_page == 'hostels/edit') echo 'nav-active';?>">
                                        <a href="<?=base_url('hostels')?>">
                                            <span><?=translate('hostel_master')?></span>
                                        </a>
                                    </li>
                                    <?php } if(get_permission('hostel_room', 'is_view')) { ?>
                                    <li class="<?php if ($sub_page == 'hostels/room' || $sub_page == 'hostels/room_edit') echo 'nav-active';?>">
                                        <a href="<?=base_url('hostels/room')?>">
                                            <span><?=translate('hostel_room')?></span>
                                        </a>
                                    </li>
                                    <?php } if(get_permission('hostel_category', 'is_view')) { ?>
                                    <li class="<?php if ($sub_page == 'hostels/category') echo 'nav-active';?>">
                                        <a href="<?=base_url('hostels/category')?>">
                                            <span><?=translate('category')?></span>
                                        </a>
                                    </li>
                                    <?php } if(get_permission('hostel_allocation', 'is_view')) { ?>
                                    <li class="<?php if ($sub_page == 'hostels/allocation') echo 'nav-active';?>">
                                        <a href="<?=base_url('hostels/allocation_report')?>">
                                            <span><?=translate('allocation_report')?></span>
                                        </a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </li>
                            <?php } ?>
                            <?php
                            if(get_permission('transport_route', 'is_view') ||
                            get_permission('transport_vehicle', 'is_view') ||
                            get_permission('transport_stoppage', 'is_view') ||
                            get_permission('transport_assign', 'is_view') ||
                            get_permission('transport_allocation', 'is_view')) {
                            ?>
                            <!-- transport -->
                            <li class="nav-parent <?php if ($main_menu == 'transport') echo 'nav-expanded nav-active';?>">
                                <a>
                                    <i class="fas fa-bus"></i><span><?=translate('transport')?></span>
                                </a>
                                <ul class="nav nav-children">
                                    <?php if(get_permission('transport_route', 'is_view')) { ?>
                                    <li class="<?php if ($sub_page == 'transport/route' || $sub_page == 'transport/route_edit') echo 'nav-active';?>">
                                        <a href="<?=base_url('transport/route')?>">
                                            <span><?=translate('route_master')?></span>
                                        </a>
                                    </li>
                                    <?php } if(get_permission('transport_vehicle', 'is_view')) { ?>
                                    <li class="<?php if ($sub_page == 'transport/vehicle' || $sub_page == 'transport/vehicle_edit') echo 'nav-active';?>">
                                        <a href="<?=base_url('transport/vehicle')?>">
                                            <span><?=translate('vehicle_master')?></span>
                                        </a>
                                    </li>
                                    <?php } if(get_permission('transport_stoppage', 'is_view')) { ?>
                                    <li class="<?php if ($sub_page == 'transport/stoppage' || $sub_page == 'transport/stoppage_edit') echo 'nav-active';?>">
                                        <a href="<?=base_url('transport/stoppage')?>">
                                            <span><?=translate('stoppage')?></span>
                                        </a>
                                    </li>
                                    <?php } if(get_permission('transport_assign', 'is_view')) { ?>
                                    <li class="<?php if ($sub_page == 'transport/assign' || $sub_page == 'transport/assign_edit') echo 'nav-active';?>">
                                        <a href="<?=base_url('transport/assign')?>">
                                            <span><?=translate('assign_vehicle')?></span>
                                        </a>
                                    </li>
                                    <?php } if(get_permission('transport_allocation', 'is_view')) { ?>
                                    <li class="<?php if ($sub_page == 'transport/allocation') echo 'nav-active';?>">
                                        <a href="<?=base_url('transport/report')?>">
                                            <span><?=translate('allocation_report')?></span>
                                        </a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php
                    if(get_permission('student_attendance', 'is_add') ||
                    get_permission('employee_attendance', 'is_add') ||
                    get_permission('exam_attendance', 'is_add')) {
                    ?>
                    <!-- attendance control -->
                    <li class="nav-parent <?php if ($main_menu == 'attendance') echo 'nav-expanded nav-active';?>">
                        <a>
                            <i class="icons icon-chart"></i><span><?=translate('attendance')?></span>
                        </a>
                        <ul class="nav nav-children">
                            <?php if(get_permission('student_attendance', 'is_add')) { ?>
                            <li class="<?php if ($sub_page == 'attendance/student_entries') echo 'nav-active';?>">
                                <a href="<?=base_url('attendance/student_entry')?>">
                                    <span><i class="fas fa-caret-right"></i><?=translate('student')?></span>
                                </a>
                            </li>
                            <?php } if(get_permission('employee_attendance', 'is_add')) { ?>
                            <li class="<?php if ($sub_page == 'attendance/employees_entries') echo 'nav-active';?>">
                                <a href="<?=base_url('attendance/employees_entry')?>">
                                    <span><i class="fas fa-caret-right"></i><?=translate('employee')?></span>
                                </a>
                            </li>
                            <?php } if(get_permission('exam_attendance', 'is_add')) { ?>
                            <li class="<?php if ($sub_page == 'attendance/exam_entries') echo 'nav-active';?>">
                                <a href="<?=base_url('attendance/exam_entry')?>">
                                    <span><i class="fas fa-caret-right"></i><?=translate('exam')?></span>
                                </a>
                            </li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php
                    if(get_permission('book', 'is_view') ||
                    get_permission('book_category', 'is_view') ||
                    get_permission('book_manage', 'is_view') ||
                    get_permission('book_request', 'is_view')) {
                    ?>
                    <!-- library -->
                    <li class="nav-parent <?php if ($main_menu == 'library') echo 'nav-expanded nav-active';?>">
                        <a>
                            <i class="icons icon-notebook"></i><span><?=translate('library')?></span>
                        </a>
                        <ul class="nav nav-children">
                            <?php if (get_permission('book', 'is_view')) {  ?>
                            <li class="<?php if ($sub_page == 'library/book') echo 'nav-active';?>">
                                <a href="<?=base_url('library/book')?>">
                                    <span><i class="fas fa-caret-right"></i><?=translate('books')?></span>
                                </a>
                            </li>
                            <?php } if (get_permission('book_category', 'is_view')) {  ?>
                            <li class="<?php if ($sub_page == 'library/category') echo 'nav-active';?>">
                                <a href="<?=base_url('library/category')?>">
                                    <span><i class="fas fa-caret-right"></i><?=translate('books_category')?></span>
                                </a>
                            </li>
                            <?php } if (get_permission('book_request', 'is_view')) {  ?>
                            <li class="<?php if ($sub_page == 'library/request') echo 'nav-active';?>">
                                <a href="<?=base_url('library/request')?>">
                                    <span><i class="fas fa-caret-right"></i>My Issued Book</span>
                                </a>
                            </li>
                            <?php } if (get_permission('book_manage', 'is_view')) {  ?>
                            <li class="<?php if ($sub_page == 'library/book_manage') echo 'nav-active';?>">
                                <a href="<?=base_url('library/book_manage')?>">
                                    <span><i class="fas fa-caret-right"></i>Book Issue/Return</span>
                                </a>
                            </li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php
                    if(get_permission('event', 'is_view') ||
                    get_permission('event_type', 'is_view')) {
                    ?>
                    <!-- envant -->
                    <li class="nav-parent <?php if ($main_menu == 'event') echo 'nav-expanded nav-active';?>">
                        <a>
                            <i class="icons icon-speech"></i><span><?=translate('events')?></span>
                        </a>
                        <ul class="nav nav-children">
                            <?php if (get_permission('event_type', 'is_view')) { ?>
                            <li class="<?php if ($sub_page == 'event/types') echo 'nav-active';?>">
                                <a href="<?=base_url('event/types')?>">
                                    <span><i class="fas fa-caret-right"></i><?=translate('event_type')?></span>
                                </a>
                            </li>
                            <?php } if (get_permission('event', 'is_view')) {  ?>
                            <li class="<?php if ($sub_page == 'event/index') echo 'nav-active';?>">
                                <a href="<?=base_url('event')?>">
                                    <span><i class="fas fa-caret-right"></i><?=translate('events')?></span>
                                </a>
                            </li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>
                    <!-- analytics -->
                    <?php if (is_admin_loggedin() || is_superadmin_loggedin()): ?>
                    <li class="<?php if ($main_menu == 'analytics') echo 'nav-active';?>">
                        <a href="<?=base_url('analytics')?>">
                            <i class="icons icon-speedometer"></i><span>Analytics &amp; Insights</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <!-- canteen pos -->
                    <?php if (is_admin_loggedin() || is_superadmin_loggedin()): ?>
                    <li class="nav-parent <?php if ($main_menu == 'canteen') echo 'nav-expanded nav-active';?>">
                        <a>
                            <i class="icons icon-basket"></i><span>Canteen / POS</span>
                        </a>
                        <ul class="nav nav-children">
                            <li class="<?php if ($sub_page == 'canteen/pos') echo 'nav-active';?>">
                                <a href="<?=base_url('canteen/pos')?>">
                                    <span><i class="fas fa-caret-right"></i> POS Terminal</span>
                                </a>
                            </li>
                            <li class="<?php if ($sub_page == 'canteen/wallets') echo 'nav-active';?>">
                                <a href="<?=base_url('canteen/wallets')?>">
                                    <span><i class="fas fa-caret-right"></i> Student Wallets</span>
                                </a>
                            </li>
                            <li class="<?php if ($sub_page == 'canteen/menu') echo 'nav-active';?>">
                                <a href="<?=base_url('canteen/menu')?>">
                                    <span><i class="fas fa-caret-right"></i> Menu Items</span>
                                </a>
                            </li>
                            <li class="<?php if ($sub_page == 'canteen/report') echo 'nav-active';?>">
                                <a href="<?=base_url('canteen/report')?>">
                                    <span><i class="fas fa-caret-right"></i> Sales Report</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <!-- staff appraisal -->
                    <?php if (is_admin_loggedin() || is_superadmin_loggedin()): ?>
                    <li class="nav-parent <?php if ($main_menu == 'appraisal') echo 'nav-expanded nav-active';?>">
                        <a>
                            <i class="icons icon-like"></i><span>Staff Appraisal</span>
                        </a>
                        <ul class="nav nav-children">
                            <li class="<?php if ($sub_page == 'appraisal/index') echo 'nav-active';?>">
                                <a href="<?=base_url('appraisal')?>">
                                    <span><i class="fas fa-caret-right"></i> Appraisals</span>
                                </a>
                            </li>
                            <li class="<?php if ($sub_page == 'appraisal/templates') echo 'nav-active';?>">
                                <a href="<?=base_url('appraisal/templates')?>">
                                    <span><i class="fas fa-caret-right"></i> Templates</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <!-- assets & inventory -->
                    <?php if (is_admin_loggedin() || is_superadmin_loggedin()): ?>
                    <li class="nav-parent <?php if ($main_menu == 'assets') echo 'nav-expanded nav-active';?>">
                        <a>
                            <i class="icons icon-briefcase"></i><span>Assets &amp; Inventory</span>
                        </a>
                        <ul class="nav nav-children">
                            <li class="<?php if ($sub_page == 'assets/index') echo 'nav-active';?>">
                                <a href="<?=base_url('assets')?>">
                                    <span><i class="fas fa-caret-right"></i> Asset Register</span>
                                </a>
                            </li>
                            <li class="<?php if ($sub_page == 'assets/inventory') echo 'nav-active';?>">
                                <a href="<?=base_url('assets/inventory')?>">
                                    <span><i class="fas fa-caret-right"></i> Inventory</span>
                                </a>
                            </li>
                            <li class="<?php if ($sub_page == 'assets/categories') echo 'nav-active';?>">
                                <a href="<?=base_url('assets/categories')?>">
                                    <span><i class="fas fa-caret-right"></i> Categories</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <!-- virtual classroom -->
                    <?php if (is_admin_loggedin() || is_superadmin_loggedin() || is_teacher_loggedin()): ?>
                    <li class="nav-parent <?php if ($main_menu == 'virtual_class') echo 'nav-expanded nav-active';?>">
                        <a>
                            <i class="icons icon-screen-desktop"></i><span>Virtual Classroom</span>
                        </a>
                        <ul class="nav nav-children">
                            <li class="<?php if ($sub_page == 'virtual_class/index') echo 'nav-active';?>">
                                <a href="<?=base_url('virtual_class')?>">
                                    <span><i class="fas fa-caret-right"></i> Scheduled Classes</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <!-- gps bus tracking -->
                    <?php if (is_admin_loggedin() || is_superadmin_loggedin()): ?>
                    <li class="nav-parent <?php if ($main_menu == 'bus_tracking') echo 'nav-expanded nav-active';?>">
                        <a>
                            <i class="icons icon-map"></i><span>GPS Bus Tracking</span>
                        </a>
                        <ul class="nav nav-children">
                            <li class="<?php if ($sub_page == 'bus_tracking/index') echo 'nav-active';?>">
                                <a href="<?=base_url('bus_tracking')?>">
                                    <span><i class="fas fa-caret-right"></i> Live Map</span>
                                </a>
                            </li>
                            <li class="<?php if ($sub_page == 'bus_tracking/manage') echo 'nav-active';?>">
                                <a href="<?=base_url('bus_tracking/manage')?>">
                                    <span><i class="fas fa-caret-right"></i> Manage Buses</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <!-- alumni -->
                    <?php if (is_admin_loggedin() || is_superadmin_loggedin()): ?>
                    <li class="nav-parent <?php if ($main_menu == 'alumni') echo 'nav-expanded nav-active';?>">
                        <a>
                            <i class="icons icon-trophy"></i><span>Alumni</span>
                        </a>
                        <ul class="nav nav-children">
                            <li class="<?php if ($sub_page == 'alumni/index') echo 'nav-active';?>">
                                <a href="<?=base_url('alumni')?>">
                                    <span><i class="fas fa-caret-right"></i> Alumni Directory</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <!-- cbt online exams -->
                    <?php if (is_admin_loggedin() || is_superadmin_loggedin() || is_teacher_loggedin()): ?>
                    <li class="nav-parent <?php if ($main_menu == 'cbt') echo 'nav-expanded nav-active';?>">
                        <a>
                            <i class="icons icon-chemistry"></i><span>CBT / Online Exams</span>
                        </a>
                        <ul class="nav nav-children">
                            <li class="<?php if ($sub_page == 'cbt/index') echo 'nav-active';?>">
                                <a href="<?=base_url('cbt')?>">
                                    <span><i class="fas fa-caret-right"></i> Manage Exams</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <!-- visitor gate management -->
                    <?php if (is_admin_loggedin() || is_superadmin_loggedin()): ?>
                    <li class="nav-parent <?php if ($main_menu == 'visitor') echo 'nav-expanded nav-active';?>">
                        <a>
                            <i class="icons icon-people"></i><span>Visitor / Gate Log</span>
                        </a>
                        <ul class="nav nav-children">
                            <li class="<?php if ($sub_page == 'visitor/index') echo 'nav-active';?>">
                                <a href="<?=base_url('visitor')?>">
                                    <span><i class="fas fa-caret-right"></i> Today's Visitors</span>
                                </a>
                            </li>
                            <li class="<?php if ($sub_page == 'visitor/report') echo 'nav-active';?>">
                                <a href="<?=base_url('visitor/report')?>">
                                    <span><i class="fas fa-caret-right"></i> Visitor Report</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <!-- knec -->
                    <?php if (is_admin_loggedin() || is_superadmin_loggedin()): ?>
                    <li class="nav-parent <?php if ($main_menu == 'knec') echo 'nav-expanded nav-active';?>">
                        <a>
                            <i class="icons icon-docs"></i><span>KNEC Index Numbers</span>
                        </a>
                        <ul class="nav nav-children">
                            <li class="<?php if ($sub_page == 'knec/index') echo 'nav-active';?>">
                                <a href="<?=base_url('knec')?>">
                                    <span><i class="fas fa-caret-right"></i> Candidates &amp; Centres</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <!-- ptm -->
                    <?php if (is_admin_loggedin() || is_superadmin_loggedin()): ?>
                    <li class="nav-parent <?php if ($main_menu == 'ptm') echo 'nav-expanded nav-active';?>">
                        <a>
                            <i class="icons icon-bubbles"></i><span>PTM (Parent-Teacher)</span>
                        </a>
                        <ul class="nav nav-children">
                            <li class="<?php if ($sub_page == 'ptm/index') echo 'nav-active';?>">
                                <a href="<?=base_url('ptm')?>">
                                    <span><i class="fas fa-caret-right"></i> PTM Sessions</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <!-- bursary -->
                    <?php if (is_admin_loggedin() || is_superadmin_loggedin()): ?>
                    <li class="nav-parent <?php if ($main_menu == 'bursary') echo 'nav-expanded nav-active';?>">
                        <a>
                            <i class="icons icon-diamond"></i><span>Bursary &amp; Scholarships</span>
                        </a>
                        <ul class="nav nav-children">
                            <li class="<?php if ($sub_page == 'bursary/index') echo 'nav-active';?>">
                                <a href="<?=base_url('bursary')?>">
                                    <span><i class="fas fa-caret-right"></i> Programmes</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <!-- pocket money -->
                    <?php if (is_admin_loggedin() || is_superadmin_loggedin()): ?>
                    <li class="nav-parent <?php if ($main_menu == 'pocket_money') echo 'nav-expanded nav-active';?>">
                        <a>
                            <i class="icons icon-wallet"></i><span>Pocket Money</span>
                        </a>
                        <ul class="nav nav-children">
                            <li class="<?php if ($sub_page == 'pocket_money/index') echo 'nav-active';?>">
                                <a href="<?=base_url('pocket_money')?>">
                                    <span><i class="fas fa-caret-right"></i> All Students</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <!-- health -->
                    <?php if (is_admin_loggedin() || is_superadmin_loggedin() || is_teacher_loggedin()): ?>
                    <li class="nav-parent <?php if ($main_menu == 'health') echo 'nav-expanded nav-active';?>">
                        <a>
                            <i class="icons icon-heart"></i><span>Health Records</span>
                        </a>
                        <ul class="nav nav-children">
                            <li class="<?php if ($sub_page == 'health/search') echo 'nav-active';?>">
                                <a href="<?=base_url('health/search')?>">
                                    <span><i class="fas fa-caret-right"></i> Student Health</span>
                                </a>
                            </li>
                            <li class="<?php if ($sub_page == 'health/clinic_log') echo 'nav-active';?>">
                                <a href="<?=base_url('health/clinic_log')?>">
                                    <span><i class="fas fa-caret-right"></i> Clinic Log</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <!-- notice board -->
                    <li class="nav-parent <?php if ($main_menu == 'noticeboard') echo 'nav-expanded nav-active';?>">
                        <a>
                            <i class="icons icon-bubble"></i><span>Notice Board</span>
                        </a>
                        <ul class="nav nav-children">
                            <li class="<?php if ($sub_page == 'noticeboard/index') echo 'nav-active';?>">
                                <a href="<?=base_url('noticeboard')?>">
                                    <span><i class="fas fa-caret-right"></i> View Notices</span>
                                </a>
                            </li>
                            <?php if (is_admin_loggedin() || is_superadmin_loggedin() || is_teacher_loggedin()): ?>
                            <li class="<?php if ($sub_page == 'noticeboard/manage') echo 'nav-active';?>">
                                <a href="<?=base_url('noticeboard/manage')?>">
                                    <span><i class="fas fa-caret-right"></i> Manage Notices</span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php
                    if(get_permission('sendsmsmail', 'is_add') ||
                    get_permission('sendsmsmail_template', 'is_view') ||
                    get_permission('sendsmsmail_reports', 'is_view')) {
                    ?>
                    <!-- SMS -->
                    <li class="nav-parent <?php if ($main_menu == 'sendsmsmail') echo 'nav-expanded nav-active';?>">
                        <a>
                            <i class="icons icon-bell"></i><span>Bulk Sms And Email</span>
                        </a>
                        <ul class="nav nav-children">
                            <?php if (get_permission('sendsmsmail', 'is_add')) {  ?>
                            <li class="<?php if ($sub_page == 'sendsmsmail/sms' || $sub_page == 'sendsmsmail/email') echo 'nav-active';?>">
                                <a href="<?=base_url('sendsmsmail/sms')?>">
                                    <span><i class="fas fa-caret-right"></i>Send Sms / Email</span>
                                </a>
                            </li>
                            <li class="<?php if ($sub_page == 'sendsmsmail/campaign_reports') echo 'nav-active';?>">
                                <a href="<?=base_url('sendsmsmail/campaign_reports')?>">
                                    <span><i class="fas fa-caret-right"></i>Sms / Email Reports</span>
                                </a>
                            </li>
                            <?php } if (get_permission('sendsmsmail_template', 'is_view')) {  ?>
                            <li class="<?php if ($sub_page == 'sendsmsmail/template_sms' || $sub_page == 'sendsmsmail/template_edit_sms') echo 'nav-active';?>">
                                <a href="<?=base_url('sendsmsmail/template/sms')?>">
                                    <span><i class="fas fa-caret-right"></i> <?=translate('sms') . " " . translate('template')?></span>
                                </a>
                            </li>
                            <li class="<?php if ($sub_page == 'sendsmsmail/template_email' || $sub_page == 'sendsmsmail/template_edit_email') echo 'nav-active';?>">
                                <a href="<?=base_url('sendsmsmail/template/email')?>">
                                    <span><i class="fas fa-caret-right"></i> <?=translate('email') . " " . translate('template')?></span>
                                </a>
                            </li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php
                    if(get_permission('fees_type', 'is_view') ||
                    get_permission('fees_group', 'is_view') ||
                    get_permission('fees_fine_setup', 'is_view') ||
                    get_permission('fees_allocation', 'is_view') ||
                    get_permission('invoice', 'is_view') ||
                    get_permission('due_invoice', 'is_view') ||
                    get_permission('fees_reminder', 'is_view')) {
                    ?>
                    <!-- student accounting -->
                    <li class="nav-parent <?php if ($main_menu == 'fees') echo 'nav-expanded nav-active';?>">
                        <a>
                            <i class="icons icon-calculator"></i><span><?=translate('student_accounting')?></span>
                        </a>
                        <ul class="nav nav-children">
                            <?php if(get_permission('fees_type', 'is_view')) { ?>
                            <li class="<?php if ($sub_page == 'fees/type') echo 'nav-active';?>">
                                <a href="<?=base_url('fees/type')?>"><span><i class="fas fa-caret-right"></i><?=translate('fees_type')?></span></a>
                            </li>
                            <?php } if(get_permission('fees_group', 'is_view')) { ?>
                            <li class="<?php if ($sub_page == 'fees/group') echo 'nav-active';?>">
                                <a href="<?=base_url('fees/group')?>"><span><i class="fas fa-caret-right"></i><?=translate('fees_group')?></span></a>
                            </li>
                            <?php } if(get_permission('fees_fine_setup', 'is_view')) { ?>
                            <li class="<?php if ($sub_page == 'fees/fine_setup') echo 'nav-active';?>">
                                <a href="<?=base_url('fees/fine_setup')?>"><span><i class="fas fa-caret-right"></i><?=translate('fine_setup')?></span></a>
                            </li>
                            <?php } if(get_permission('fees_allocation', 'is_view')) { ?>
                            <li class="<?php if ($sub_page == 'fees/allocation') echo 'nav-active';?>">
                                <a href="<?=base_url('fees/allocation')?>"><span><i class="fas fa-caret-right"></i><?=translate('fees_allocation')?></span></a>
                            </li>
                            <?php } if(get_permission('invoice', 'is_view')) { ?>
                            <li class="<?php if ($sub_page == 'fees/invoice_list' || $sub_page == 'fees/collect') echo 'nav-active';?>">
                                <a href="<?=base_url('fees/invoice_list')?>"><span><i class="fas fa-caret-right"></i><?=translate('payments_history')?></span></a>
                            </li>
                            <?php } if(get_permission('due_invoice', 'is_view')) { ?>
                            <li class="<?php if ($sub_page == 'fees/due_invoice') echo 'nav-active';?>">
                                <a href="<?=base_url('fees/due_invoice')?>"><span><i class="fas fa-caret-right"></i><?=translate('due_fees_invoice')?></span></a>
                            </li>
                            <?php } if(get_permission('fees_reminder', 'is_view')) { ?>
                            <li class="<?php if ($sub_page == 'fees/reminder') echo 'nav-active';?>">
                                <a href="<?=base_url('fees/reminder')?>"><span><i class="fas fa-caret-right"></i><?=translate('fees_reminder')?></span></a>
                            </li>
                            <?php } ?>
                            <?php if (is_admin_loggedin() || is_superadmin_loggedin()): ?>
                            <li class="<?php if ($sub_page == 'mpesa/index') echo 'nav-active';?>">
                                <a href="<?=base_url('mpesa')?>"><span><i class="fas fa-caret-right"></i> M-Pesa Transactions</span></a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php
                    if(get_permission('account', 'is_view') ||
                    get_permission('voucher_head', 'is_view') ||
                    get_permission('deposit', 'is_view') ||
                    get_permission('expense', 'is_view') ||
                    get_permission('all_transactions', 'is_view')) {
                    ?>
                    <!-- office accounting -->
                    <li class="nav-parent <?php if ($main_menu == 'accounting') echo 'nav-expanded nav-active';?>">
                        <a>
                            <i class="icon-credit-card icons"></i><span><?=translate('office_accounting')?></span>
                        </a>
                        <ul class="nav nav-children">
                            <?php if(get_permission('account', 'is_view')){ ?>
                                <li class="<?php if ($sub_page == 'accounting/index' || $sub_page == 'accounting/edit') echo 'nav-active'; ?>">
                                    <a href="<?php echo base_url('accounting'); ?>">
                                        <span><i class="fas fa-caret-right" aria-hidden="true"></i><?php echo translate('account'); ?></span>
                                    </a>
                                </li>
                            <?php } if(get_permission('deposit', 'is_view')){ ?>
                            <li class="<?php if ($sub_page == 'accounting/voucher_deposit' || $sub_page == 'accounting/voucher_deposit_edit') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('accounting/voucher_deposit'); ?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?php echo translate('new_deposit'); ?></span>
                                </a>
                            </li>
                            <?php } if(get_permission('expense', 'is_view')){ ?>
                            <li class="<?php if ($sub_page == 'accounting/voucher_expense' || $sub_page == 'accounting/voucher_expense_edit') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('accounting/voucher_expense'); ?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?php echo translate('new_expense'); ?></span>
                                </a>
                            </li>
                            <?php } if(get_permission('all_transactions', 'is_view')){ ?>
                            <li class="<?php if ($sub_page == 'accounting/all_transactions') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('accounting/all_transactions'); ?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?php echo translate('all_transactions'); ?></span>
                                </a>
                            </li>
                            <?php } if(get_permission('voucher_head', 'is_view') || get_permission('voucher_head', 'is_add')){ ?>
                            <li class="<?php if ($sub_page == 'accounting/voucher_head') echo 'nav-active'; ?>">
                                <a href="<?php echo base_url('accounting/voucher_head'); ?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?php echo translate('voucher') . " " . translate('head'); ?></span>
                                </a>
                            </li>
                            <?php } ?>
                            <?php if (get_permission('purchase_orders', 'is_view')): ?>
                            <li class="<?php if ($sub_page == 'purchase_order/index' || $sub_page == 'purchase_order/view' || $sub_page == 'purchase_order/create') echo 'nav-active'; ?>">
                                <a href="<?=base_url('purchase-orders')?>">
                                    <span><i class="fas fa-caret-right"></i> LPO / Purchase Orders</span>
                                </a>
                            </li>
                            <?php endif; if (get_permission('suppliers', 'is_view')): ?>
                            <li class="<?php if ($sub_page == 'purchase_order/suppliers') echo 'nav-active'; ?>">
                                <a href="<?=base_url('purchase-orders/suppliers')?>">
                                    <span><i class="fas fa-caret-right"></i> Suppliers</span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if (get_permission('biometric_devices', 'is_view') || get_permission('biometric_mapping', 'is_view') || get_permission('biometric_logs', 'is_view')): ?>
                    <!-- biometric attendance -->
                    <li class="nav-parent <?php if ($main_menu == 'biometric') echo 'nav-expanded nav-active';?>">
                        <a>
                            <i class="fas fa-fingerprint"></i><span>Biometric Attendance</span>
                        </a>
                        <ul class="nav nav-children">
                            <?php if (get_permission('biometric_devices', 'is_view')): ?>
                            <li class="<?php if ($sub_page == 'biometric/devices') echo 'nav-active';?>">
                                <a href="<?=base_url('biometric/devices')?>"><span><i class="fas fa-caret-right"></i> Devices</span></a>
                            </li>
                            <?php endif; if (get_permission('biometric_mapping', 'is_view')): ?>
                            <li class="<?php if ($sub_page == 'biometric/mapping') echo 'nav-active';?>">
                                <a href="<?=base_url('biometric/mapping')?>"><span><i class="fas fa-caret-right"></i> ID Mapping</span></a>
                            </li>
                            <?php endif; if (get_permission('biometric_logs', 'is_add')): ?>
                            <li class="<?php if ($sub_page == 'biometric/import') echo 'nav-active';?>">
                                <a href="<?=base_url('biometric/import')?>"><span><i class="fas fa-caret-right"></i> Import CSV</span></a>
                            </li>
                            <?php endif; if (get_permission('biometric_logs', 'is_view')): ?>
                            <li class="<?php if ($sub_page == 'biometric/logs') echo 'nav-active';?>">
                                <a href="<?=base_url('biometric/logs')?>"><span><i class="fas fa-caret-right"></i> Scan Logs</span></a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <!-- message -->
                    <li class="<?php if ($main_menu == 'message') echo 'nav-active';?>">
                        <a href="<?=base_url('communication/mailbox/inbox')?>">
                            <i class="icons icon-envelope-open"></i><span><?=translate('message')?></span>
                        </a>
                    </li>

                    <?php 
                    $attendance_report = false;
                    if (get_permission('student_attendance_report', 'is_view') ||
                    get_permission('employee_attendance_report', 'is_view') ||
                    get_permission('exam_attendance_report', 'is_view')) {
                        $attendance_report = true;
                    }

                    if(get_permission('fees_reports', 'is_view') ||
                    get_permission('accounting_reports', 'is_view') ||
                    get_permission('salary_summary_report', 'is_view') ||
                    get_permission('leave_reports', 'is_view') ||
                    ($attendance_report == true) ||
                    get_permission('report_card', 'is_view') ||
                    get_permission('tabulation_sheet', 'is_view')) {
                    ?>
                    <!-- reports -->
                    <li class="nav-parent <?php if ($main_menu == 'accounting_repots' ||
                                                        $main_menu == 'fees_repots' ||
                                                            $main_menu == 'attendance_report' ||
                                                                $main_menu == 'payroll_reports' ||
                                                                    $main_menu == 'leave_reports' ||
                                                                        $main_menu == 'exam_reports') echo 'nav-expanded nav-active';?>">
                        <a>
                            <i class="icons icon-pie-chart icons"></i><span><?=translate('reports')?></span>
                        </a>
                        <ul class="nav nav-children">
                        <?php  if(get_permission('fees_reports', 'is_view')){ ?>
                            <li class="nav-parent <?php if ($main_menu == 'fees_repots') echo 'nav-expanded nav-active'; ?>">
                                <a><i class="fas fa-print"></i><span><?php echo translate('fees_reports'); ?></span></a>
                                <ul class="nav nav-children">
                                    <li class="<?php if ($sub_page == 'fees/student_fees_report') echo 'nav-active';?>">
                                        <a href="<?=base_url('fees/student_fees_report')?>"><?=translate('fees_report')?></a>
                                    </li>
                                    <li class="<?php if ($sub_page == 'fees/payment_history') echo 'nav-active';?>">
                                        <a href="<?=base_url('fees/payment_history')?>"><?=translate('receipts_report')?></a>
                                    </li>
                                    <li class="<?php if ($sub_page == 'fees/due_report') echo 'nav-active';?>">
                                        <a href="<?=base_url('fees/due_report')?>"><?=translate('due_fees_report')?></a>
                                    </li>
                                    <li class="<?php if ($sub_page == 'fees/fine_report') echo 'nav-active';?>">
                                        <a href="<?=base_url('fees/fine_report')?>"><?=translate('fine_report')?></a>
                                    </li>


                                </ul>
                            </li>
                        <?php } ?>
                        <?php  if(get_permission('accounting_reports', 'is_view')){ ?>
                            <li class="nav-parent <?php if ($main_menu == 'accounting_repots') echo 'nav-expanded nav-active'; ?>">
                                <a><i class="fas fa-print"></i><span><?php echo translate('financial_reports'); ?></span></a>
                                <ul class="nav nav-children">
                                    <li class="<?php if ($sub_page == 'accounting/account_statement') echo 'nav-active'; ?>">
                                        <a href="<?php echo base_url('accounting/account_statement'); ?>"><?php echo translate('account') . " " . translate('statement'); ?></a>
                                    </li>
                                    <li class="<?php if ($sub_page == 'accounting/income_repots') echo 'nav-active'; ?>">
                                        <a href="<?php echo base_url('accounting/income_repots'); ?>"><?php echo translate('income') . " " . translate('repots'); ?></a>
                                    </li>
                                    <li class="<?php if ($sub_page == 'accounting/expense_repots') echo 'nav-active'; ?>">
                                        <a href="<?php echo base_url('accounting/expense_repots'); ?>"> <?php echo translate('expense') . " " . translate('repots'); ?></a>
                                    </li>
                                    <li class="<?php if ($sub_page == 'accounting/transitions_repots') echo 'nav-active'; ?>">
                                        <a href="<?php echo base_url('accounting/transitions_repots'); ?>"> <?php echo translate('transitions') . " " . translate('reports'); ?></a>
                                    </li>
                                    <li class="<?php if ($sub_page == 'accounting/balance_sheet') echo 'nav-active'; ?>">
                                        <a href="<?php echo base_url('accounting/balance_sheet'); ?>"><?php echo translate('balance') . " " . translate('sheet'); ?></a>
                                    </li>
                                    <li class="<?php if ($sub_page == 'accounting/income_vs_expense') echo 'nav-active'; ?>">
                                        <a href="<?php echo base_url('accounting/incomevsexpense'); ?>"> <?php echo translate('income_vs_expense'); ?></a>
                                    </li>

                                </ul>
                            </li>
                        <?php } ?>
                        <?php if($attendance_report == true) { ?>
                            <li class="nav-parent <?php if ($main_menu == 'attendance_report') echo 'nav-expanded nav-active'; ?>">
                                <a><i class="fas fa-print"></i><span><?php echo translate('attendance_reports'); ?></span></a>
                                <ul class="nav nav-children">
                                    <?php if(get_permission('student_attendance_report', 'is_view')) { ?>
                                    <li class="<?php if ($sub_page == 'attendance/student_report') echo 'nav-active';?>">
                                        <a href="<?=base_url('attendance/studentwise_report')?>">
                                            <?=translate('student') . ' ' . translate('reports')?>
                                        </a>
                                    </li>
                                    <?php } if(get_permission('employee_attendance_report', 'is_view')) { ?>
                                    <li class="<?php if ($sub_page == 'attendance/employees_report') echo 'nav-active';?>">
                                        <a href="<?=base_url('attendance/employeewise_report')?>">
                                            <?=translate('employee') . ' ' . translate('reports')?>
                                        </a>
                                    </li>
                                    <?php } if(get_permission('exam_attendance_report', 'is_view')) { ?>
                                    <li class="<?php if ($sub_page == 'attendance/exam_report') echo 'nav-active';?>">
                                        <a href="<?=base_url('attendance/examwise_report')?>">
                                            <?=translate('exam') . ' ' . translate('reports')?>
                                        </a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>
                    
                        <?php  if(get_permission('salary_summary_report', 'is_view') || get_permission('leave_reports', 'is_view')){ ?>
                            <li class="nav-parent <?php if ($main_menu == 'payroll_reports' || $main_menu == 'leave_reports') echo 'nav-expanded nav-active'; ?>">
                                <a><i class="fas fa-print"></i><span><?php echo translate('hrm'); ?></span></a>
                                <ul class="nav nav-children">
                                    <?php if(get_permission('salary_summary_report', 'is_view')){ ?>
                                    <li class="<?php if ($sub_page == 'payroll/salary_statement') echo 'nav-active';?>">
                                        <a href="<?=base_url('payroll/salary_statement')?>">
                                            <span><?=translate('payroll_summary')?></span>
                                        </a>
                                    </li>
                                    <?php } if (get_permission('leave_reports', 'is_view')) { ?>
                                    <li class="<?php if ($sub_page == 'leave/reports') echo 'nav-active';?>">
                                        <a href="<?=base_url('leave/reports')?>">
                                            <span><?=translate('leave') . " " . translate('reports')?></span>
                                        </a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>
                        <?php if(get_permission('report_card', 'is_view') || get_permission('tabulation_sheet', 'is_view')) { ?>
                            <li class="nav-parent <?php if ($main_menu == 'exam_reports') echo 'nav-expanded nav-active'; ?>">
                                <a><i class="fas fa-print"></i><span><?php echo translate('examination'); ?></span></a>
                                <ul class="nav nav-children">
                                    <?php if(get_permission('report_card', 'is_view')) { ?>
                                    <li class="<?php if ($sub_page == 'exam/marksheet') echo 'nav-active';?>">
                                        <a href="<?=base_url('exam/marksheet')?>">
                                            <span><?=translate('report_card')?></span>
                                        </a>
                                    </li>
                                    <?php } if(get_permission('tabulation_sheet', 'is_view')) { ?>
                                    <li class="<?php if ($sub_page == 'exam/tabulation_sheet') echo 'nav-active';?>">
                                        <a href="<?=base_url('exam/tabulation_sheet')?>">
                                            <span><?=translate('tabulation_sheet')?></span>
                                        </a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php

                    $schoolSettings = false;
                    if (get_permission('school_settings', 'is_view') ||
                    get_permission('live_class_config', 'is_view') ||
                    get_permission('payment_settings', 'is_view') ||
                    get_permission('sms_settings', 'is_view') ||
                    get_permission('email_settings', 'is_view') ||
                    get_permission('accounting_links', 'is_view')) {
                        $schoolSettings = true;
                    }
                    if (get_permission('global_settings', 'is_view') ||
                    ($schoolSettings == true) ||
                    get_permission('translations', 'is_view') ||
                    get_permission('cron_job', 'is_view') ||
                    get_permission('custom_field', 'is_view') ||
                    get_permission('backup', 'is_view')) {
                    ?>
                    <!-- setting -->
                    <li class="nav-parent <?php if ($main_menu == 'settings' || $main_menu == 'school_m') echo 'nav-expanded nav-active';?>">
                        <a>
                            <i class="icons icon-briefcase"></i><span><?=translate('settings')?></span>
                        </a>
                        <ul class="nav nav-children">
                            <?php if(get_permission('global_settings', 'is_view')){ ?>
                            <li class="<?php if($sub_page == 'settings/universal') echo 'nav-active';?>">
                                <a href="<?=base_url('settings/universal')?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?=translate('global_settings')?></span>
                                </a>
                            </li>
                            <?php } if($schoolSettings == true){ ?>
                            <li class="<?php if($main_menu == 'school_m') echo 'nav-active';?>">
                                <a href="<?=base_url('school_settings')?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?=translate('school_settings')?></span>
                                </a>
                            </li>
                            <?php } if (is_superadmin_loggedin()) { ?>
                            <li class="<?php if ($sub_page == 'role/index' || $sub_page == 'role/permission') echo 'nav-active';?>">
                                <a href="<?=base_url('role')?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?=translate('role_permission')?></span>
                                </a>
                            </li>
                            <?php } if (is_superadmin_loggedin()) { ?>
                            <li class="<?php if ($sub_page == 'sessions/index') echo 'nav-active';?>">
                                <a href="<?=base_url('sessions')?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?=translate('session_settings')?></span>
                                </a>
                            </li>
                            <?php } if(get_permission('translations', 'is_view')){ ?>
                            <li class="<?php if ($sub_page == 'language/index') echo 'nav-active';?>">
                                <a href="<?=base_url('translations')?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?=translate('translations')?></span>
                                </a>
                            </li>
                            <?php } if(get_permission('cron_job', 'is_view')){ ?>
                            <li class="<?php if ($sub_page == 'cron_api/index') echo 'nav-active';?>">
                                <a href="<?=base_url('cron_api')?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?=translate('cron_job')?></span>
                                </a>
                            </li>
                            <?php } if(get_permission('custom_field', 'is_view')){ ?>
                            <li class="<?php if ($sub_page == 'custom_field/index') echo 'nav-active';?>">
                                <a href="<?=base_url('custom_field')?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?=translate('custom_field')?></span>
                                </a>
                            </li>
                            <?php } if(get_permission('backup', 'is_view')){ ?>
                            <li class="<?php if ($sub_page == 'database_backup/index') echo 'nav-active';?>">
                                <a href="<?=base_url('backup')?>">
                                    <span><i class="fas fa-caret-right" aria-hidden="true"></i><?=translate('database_backup')?></span>
                                </a>
                            </li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>
                </ul>
            </nav>
    </div><!-- /.dck-sidebar-nav -->

</aside><!-- /.dck-sidebar -->