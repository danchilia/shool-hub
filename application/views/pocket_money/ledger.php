<?php
$student      = isset($student)      ? $student      : array();
$transactions = isset($transactions) ? $transactions : array();
$balance      = isset($balance)      ? $balance      : 0;
?>
<div class="row">
    <div class="col-md-12">
        <section class="panel" style="border-top:3px solid #27ae60">
            <div class="panel-body" style="display:flex;align-items:center;gap:16px">
                <img src="<?= get_image_url('student', $student['photo'] ?? '') ?>" width="60" height="60" class="rounded" style="object-fit:cover">
                <div>
                    <h4 class="mb-none"><?= htmlspecialchars(($student['first_name'] ?? '') . ' ' . ($student['last_name'] ?? '')) ?></h4>
                    <small class="text-muted"><?= htmlspecialchars($student['register_no'] ?? '') ?> | <?= htmlspecialchars(($student['class'] ?? '') . ' ' . ($student['section_name'] ?? '')) ?></small>
                </div>
                <div class="ml-auto text-right">
                    <div style="font-size:1.6rem;font-weight:700;color:#27ae60">KES <?= number_format($balance, 2) ?></div>
                    <small class="text-muted">Current Balance</small>
                </div>
            </div>
        </section>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <div class="panel-btn">
                    <button class="btn btn-success btn-circle btn-sm" onclick="mfp_modal('#deposit-modal')"><i class="fas fa-plus"></i> Deposit</button>
                    <button class="btn btn-danger btn-circle btn-sm" onclick="mfp_modal('#withdraw-modal')"><i class="fas fa-minus"></i> Withdraw</button>
                    <a href="<?= base_url('pocket_money') ?>" class="btn btn-default btn-circle btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
                </div>
                <h4 class="panel-title"><i class="fas fa-list-ul"></i> Transaction Ledger</h4>
            </header>
            <div class="panel-body">
                <?php if (empty($transactions)): ?>
                    <p class="text-center text-muted mt-md">No transactions yet.</p>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-condensed table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Received From</th>
                                <th>Amount (KES)</th>
                                <th>Balance After (KES)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactions as $t): ?>
                            <tr>
                                <td><?= date('d M Y H:i', strtotime($t['created_at'])) ?></td>
                                <td>
                                    <?php if ($t['transaction_type'] == 'deposit'): ?>
                                        <span class="badge badge-success">Deposit</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Withdrawal</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($t['description'] ?? '') ?></td>
                                <td><?= htmlspecialchars($t['received_from'] ?? '—') ?></td>
                                <td class="<?= $t['transaction_type'] == 'deposit' ? 'text-success' : 'text-danger' ?>" style="font-weight:600">
                                    <?= $t['transaction_type'] == 'deposit' ? '+' : '-' ?><?= number_format($t['amount'], 2) ?>
                                </td>
                                <td><?= number_format($t['balance_after'], 2) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>

<!-- Deposit Modal -->
<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="deposit-modal">
    <section class="panel">
        <header class="panel-heading"><h4 class="panel-title"><i class="fas fa-plus-circle text-success"></i> Deposit Cash</h4><button type="button" class="close" onclick="$.magnificPopup.close()" style="float:right;font-size:1.4rem;line-height:1;opacity:.7;" title="Close">&times;</button></header>
        <?php echo form_open('pocket_money/deposit', array('class' => 'frm-submit')); ?>
        <input type="hidden" name="student_id" value="<?= $student['student_id'] ?? '' ?>">
        <div class="panel-body">
            <div class="form-group">
                <label>Amount (KES) <span class="required">*</span></label>
                <input type="number" name="amount" class="form-control" step="0.01" min="1" placeholder="0.00">
                <span class="error"></span>
            </div>
            <div class="form-group">
                <label>Received From</label>
                <input type="text" name="received_from" class="form-control" placeholder="Parent/guardian name">
            </div>
            <div class="form-group">
                <label>Description</label>
                <input type="text" name="description" class="form-control" placeholder="e.g. Weekly pocket money">
            </div>
            <div class="form-group">
                <label>Reference No.</label>
                <input type="text" name="reference_no" class="form-control" placeholder="e.g. M-Pesa transaction code">
            </div>
        </div>
        <footer class="panel-footer"><div class="text-right">
            <button type="button" class="btn btn-default modal-dismiss">Cancel</button>
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Deposit</button>
        </div></footer>
        <?php echo form_close(); ?>
    </section>
</div>

<!-- Withdraw Modal -->
<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="withdraw-modal">
    <section class="panel">
        <header class="panel-heading"><h4 class="panel-title"><i class="fas fa-minus-circle text-danger"></i> Withdraw Cash</h4><button type="button" class="close" onclick="$.magnificPopup.close()" style="float:right;font-size:1.4rem;line-height:1;opacity:.7;" title="Close">&times;</button></header>
        <?php echo form_open('pocket_money/withdraw', array('class' => 'frm-submit')); ?>
        <input type="hidden" name="student_id" value="<?= $student['student_id'] ?? '' ?>">
        <div class="panel-body">
            <p class="text-muted">Current balance: <strong>KES <?= number_format($balance, 2) ?></strong></p>
            <div class="form-group">
                <label>Amount (KES) <span class="required">*</span></label>
                <input type="number" name="amount" class="form-control" step="0.01" min="1" max="<?= $balance ?>" placeholder="0.00">
                <span class="error"></span>
            </div>
            <div class="form-group">
                <label>Reason</label>
                <input type="text" name="description" class="form-control" placeholder="e.g. Transport, food">
            </div>
        </div>
        <footer class="panel-footer"><div class="text-right">
            <button type="button" class="btn btn-default modal-dismiss">Cancel</button>
            <button type="submit" class="btn btn-danger"><i class="fas fa-save"></i> Withdraw</button>
        </div></footer>
        <?php echo form_close(); ?>
    </section>
</div>
