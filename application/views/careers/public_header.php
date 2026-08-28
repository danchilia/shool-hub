<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light">
    <title><?php echo isset($page_title) ? html_escape($page_title) : 'CST SchoolHub Careers'; ?></title>
    <link rel="shortcut icon" href="<?php echo base_url('assets/images/favicon.png'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/bootstrap5/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/font-awesome/css/all.min.css'); ?>">
    <style>
        body { background: #f0f4f8; font-family: 'Segoe UI', Arial, sans-serif; }
        .careers-navbar { background: #1a5276; }
        .careers-navbar .navbar-brand { color: #fff; font-weight: 700; font-size: 1.2rem; line-height: 1.2; }
        .careers-navbar .navbar-brand small { display: block; color: #aed6f1; font-size: 0.7rem; font-weight: 400; }
        .careers-navbar .nav-link { color: rgba(255,255,255,0.85) !important; }
        .careers-navbar .nav-link:hover { color: #fff !important; }
        .job-card { background: #fff; border-radius: 8px; border: 1px solid #dde6f0; }
        .job-card:hover { box-shadow: 0 4px 20px rgba(26,82,118,0.10); }
        .badge-open { background: #1abc9c; color: #fff; padding: 4px 10px; border-radius: 20px; font-size: 12px; }
        .badge-closed { background: #95a5a6; color: #fff; padding: 4px 10px; border-radius: 20px; font-size: 12px; }
        .status-pill { display: inline-block; padding: 3px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .status-pending     { background: #fff3cd; color: #856404; }
        .status-shortlisted { background: #cfe2ff; color: #084298; }
        .status-interview   { background: #e8d5f7; color: #6f2da8; }
        .status-rejected    { background: #f8d7da; color: #721c24; }
        .status-hired       { background: #d1e7dd; color: #0a3622; }
        .msg-bubble { border-radius: 10px; padding: 12px 16px; margin-bottom: 12px; }
        .msg-admin     { background: #eaf4fb; border-left: 3px solid #1a5276; }
        .msg-applicant { background: #f0f9f5; border-left: 3px solid #1abc9c; }
        .flash-success { background: #d1e7dd; border: 1px solid #a3cfbb; color: #0a3622; border-radius: 6px; padding: 12px 16px; margin-bottom: 16px; }
        .flash-error   { background: #f8d7da; border: 1px solid #f1aeb5; color: #58151c; border-radius: 6px; padding: 12px 16px; margin-bottom: 16px; }
        .flash-info    { background: #cff4fc; border: 1px solid #9eeaf9; color: #055160; border-radius: 6px; padding: 12px 16px; margin-bottom: 16px; }
    </style>
</head>
<body>

<nav class="careers-navbar navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="<?php echo base_url('careers'); ?>">
            <i class="fas fa-briefcase me-2"></i>CST SchoolHub
            <small>Careers Portal</small>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#careersNav">
            <i class="fas fa-bars text-white"></i>
        </button>
        <div class="collapse navbar-collapse" id="careersNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2 mt-2 mt-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url('careers'); ?>">
                        <i class="fas fa-list-ul me-1"></i>Jobs
                    </a>
                </li>
                <?php if (isset($applicant_id) && $applicant_id): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url('careers/dashboard'); ?>">
                            <i class="fas fa-folder-open me-1"></i>My Applications
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-sm btn-outline-light ms-lg-2" href="<?php echo base_url('careers/logout'); ?>">
                            <i class="fas fa-sign-out-alt me-1"></i>Logout
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url('careers/login'); ?>">
                            <i class="fas fa-sign-in-alt me-1"></i>Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-sm btn-outline-light ms-lg-2" href="<?php echo base_url('careers/register'); ?>">
                            <i class="fas fa-user-plus me-1"></i>Register
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container py-4">
<?php
$_sf = $this->session->flashdata('success');
$_ef = $this->session->flashdata('error');
$_if = $this->session->flashdata('info');
if ($_sf): ?><div class="flash-success"><i class="fas fa-check-circle me-2"></i><?php echo $_sf; ?></div><?php endif;
if ($_ef): ?><div class="flash-error"><i class="fas fa-exclamation-circle me-2"></i><?php echo $_ef; ?></div><?php endif;
if ($_if): ?><div class="flash-info"><i class="fas fa-info-circle me-2"></i><?php echo $_if; ?></div><?php endif;
?>
