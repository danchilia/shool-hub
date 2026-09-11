<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
<div class="col-md-8 col-md-offset-2">

    <div class="panel">
        <header class="panel-heading">
            <h4 class="panel-title"><i class="fas fa-user-shield me-2"></i>Shortlist Portal Emails — Results</h4>
        </header>
        <div class="panel-body">

            <div class="row text-center" style="margin-bottom:20px;">
                <div class="col-xs-4">
                    <div style="background:#27ae60;color:#fff;border-radius:8px;padding:16px;">
                        <div style="font-size:28px;font-weight:700;"><?php echo $created; ?></div>
                        <div style="font-size:12px;margin-top:4px;">Accounts Created &amp; Emailed</div>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div style="background:#3498db;color:#fff;border-radius:8px;padding:16px;">
                        <div style="font-size:28px;font-weight:700;"><?php echo $skipped; ?></div>
                        <div style="font-size:12px;margin-top:4px;">Already Had Account</div>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div style="background:#e74c3c;color:#fff;border-radius:8px;padding:16px;">
                        <div style="font-size:28px;font-weight:700;"><?php echo $failed; ?></div>
                        <div style="font-size:12px;margin-top:4px;">Email Failed</div>
                    </div>
                </div>
            </div>

            <table class="table table-condensed table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Position</th>
                        <th>Result</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($log as $i => $row): ?>
                    <tr>
                        <td><?php echo $i + 1; ?></td>
                        <td><?php echo html_escape($row['name']); ?></td>
                        <td><?php echo html_escape($row['email']); ?></td>
                        <td><?php echo html_escape($row['job']); ?></td>
                        <td>
                            <?php if ($row['status'] === 'sent'): ?>
                                <span class="label label-success">Created &amp; Sent</span>
                            <?php elseif ($row['status'] === 'skipped'): ?>
                                <span class="label label-info">Already Exists</span>
                            <?php else: ?>
                                <span class="label label-danger">Email Failed</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <a href="<?php echo base_url('careers/manage'); ?>" class="btn btn-default">
                <i class="fas fa-arrow-left me-1"></i>Back to Careers
            </a>
        </div>
    </div>

</div>
</div>
