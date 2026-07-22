<?php
/** @var array $student @var array $branch @var float $fees_balance @var string $issue_date */
$student      = isset($student)      ? $student      : array();
$branch       = isset($branch)       ? $branch       : array();
$fees_balance = isset($fees_balance) ? $fees_balance : 0;
$issue_date   = isset($issue_date)   ? $issue_date   : date('Y-m-d');
?>
<div class="row">
    <div class="col-md-12">
        <div class="text-right mb-sm" style="margin-bottom:14px;">
            <button onclick="fn_printElem('lc-print')" class="btn btn-default">
                <i class="fas fa-print"></i> Print Certificate
            </button>
        </div>

        <div id="lc-print" style="max-width:800px;margin:auto;background:#fff;color:#000;font-family:'Times New Roman',serif;">
            <style>
                @media print {
                    body * { visibility: hidden; }
                    #lc-print, #lc-print * { visibility: visible; }
                    #lc-print { position: absolute; left: 0; top: 0; width: 100%; }
                }
                #lc-print { border: 2px solid #333; padding: 30px 40px; }
                #lc-print .lc-header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 15px; margin-bottom: 20px; }
                #lc-print .lc-header .school-name { font-size: 1.5rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
                #lc-print .lc-header .school-sub { font-size: .9rem; color: #333; margin-top: 4px; }
                #lc-print .lc-title { text-align: center; font-size: 1.3rem; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; margin: 18px 0; text-decoration: underline; }
                #lc-print .serial { text-align: right; font-size: .82rem; color: #555; margin-bottom: 14px; }
                #lc-print table.lc-table { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
                #lc-print table.lc-table td { padding: 6px 10px; vertical-align: top; font-size: .92rem; }
                #lc-print table.lc-table td:first-child { width: 38%; font-weight: 600; color: #333; }
                #lc-print table.lc-table td:last-child { border-bottom: 1px dotted #999; }
                #lc-print .lc-remarks { border: 1px solid #ccc; padding: 12px; margin: 16px 0; font-size: .88rem; min-height: 60px; }
                #lc-print .lc-footer { margin-top: 40px; display: flex; justify-content: space-between; }
                #lc-print .sig-block { text-align: center; min-width: 180px; }
                #lc-print .sig-line { border-top: 1px solid #333; padding-top: 4px; font-size: .8rem; margin-top: 40px; }
                #lc-print .clearance { font-size: .82rem; color: #555; margin-top: 12px; border: 1px solid #ddd; padding: 10px; }
            </style>

            <div class="lc-header">
                <img src="<?=get_branch_logo($branch['id'] ?? '', 'printing_logo')?>" style="max-height:70px;margin-bottom:8px;"><br>
                <div class="school-name"><?=htmlspecialchars($branch['school_name'] ?? 'School Name')?></div>
                <div class="school-sub">
                    <?=htmlspecialchars($branch['address'] ?? '')?><br>
                    Tel: <?=htmlspecialchars($branch['mobileno'] ?? '')?> &nbsp;|&nbsp; Email: <?=htmlspecialchars($branch['email'] ?? '')?>
                </div>
            </div>

            <div class="lc-title">Leaving Certificate</div>
            <div class="serial">Serial No: LC-<?=str_pad($student['id'] ?? 0, 5, '0', STR_PAD_LEFT)?> &nbsp;|&nbsp; Date of Issue: <?=date('d F Y', strtotime($issue_date))?></div>

            <table class="lc-table">
                <tr>
                    <td>Full Name:</td>
                    <td><?=htmlspecialchars(strtoupper(($student['first_name'] ?? '') . ' ' . ($student['last_name'] ?? '')))?></td>
                </tr>
                <tr>
                    <td>Admission Number:</td>
                    <td><?=htmlspecialchars($student['register_no'] ?? '')?></td>
                </tr>
                <tr>
                    <td>UPI Number (NEMIS):</td>
                    <td><?=htmlspecialchars(!empty($student['upi_number']) ? $student['upi_number'] : 'N/A')?></td>
                </tr>
                <tr>
                    <td>Date of Birth:</td>
                    <td><?=!empty($student['birthday']) ? date('d F Y', strtotime($student['birthday'])) : 'N/A'?></td>
                </tr>
                <tr>
                    <td>Gender:</td>
                    <td><?=ucfirst($student['gender'] ?? '')?></td>
                </tr>
                <tr>
                    <td>Class / Stream:</td>
                    <td><?=htmlspecialchars(($student['class_name'] ?? '') . ($student['section_name'] ? ' — ' . $student['section_name'] : ''))?></td>
                </tr>
                <tr>
                    <td>Parent / Guardian:</td>
                    <td><?=htmlspecialchars($student['guardian_name'] ?? 'N/A')?></td>
                </tr>
                <tr>
                    <td>Guardian Phone:</td>
                    <td><?=htmlspecialchars($student['guardian_phone'] ?? 'N/A')?></td>
                </tr>
                <tr>
                    <td>Date of Admission:</td>
                    <td><?=!empty($student['admission_date']) ? date('d F Y', strtotime($student['admission_date'])) : 'N/A'?></td>
                </tr>
                <tr>
                    <td>Date of Leaving:</td>
                    <td><?=date('d F Y', strtotime($issue_date))?></td>
                </tr>
                <tr>
                    <td>Reason for Leaving:</td>
                    <td style="border-bottom: 1px dotted #999; min-height: 24px;">&nbsp;</td>
                </tr>
            </table>

            <div class="clearance">
                <strong>Fee Clearance Status:</strong>
                <?php if ($fees_balance <= 0): ?>
                    <span style="color:#155724;font-weight:700;">&#10003; CLEARED — No outstanding fees balance.</span>
                <?php else: ?>
                    <span style="color:#721c24;font-weight:700;">&#10005; NOT CLEARED — Outstanding balance: KES <?=number_format($fees_balance, 2)?></span>
                <?php endif; ?>
            </div>

            <div class="lc-remarks">
                <strong>Remarks:</strong><br><br>
                &nbsp;
            </div>

            <div class="lc-footer">
                <div class="sig-block">
                    <div class="sig-line">Class Teacher's Signature</div>
                </div>
                <div class="sig-block">
                    <div class="sig-line">Head Teacher's Signature &amp; School Stamp</div>
                </div>
                <div class="sig-block">
                    <div class="sig-line">Date</div>
                </div>
            </div>
        </div>

    </div>
</div>
