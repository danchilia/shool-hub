<?php
$div = 0;
if (get_permission('employee_count_widget', 'is_view')) {
	$div++;	
}
if (get_permission('student_count_widget', 'is_view')) {
	$div++;	
}
if (get_permission('parent_count_widget', 'is_view')) {
	$div++;	
}
if (get_permission('teacher_count_widget', 'is_view')) {
	$div++;	
}
if ($div == 0) {
	$widget1 = 0;
}else{
	$widget1 = 12 / $div;
}

$div2 = 0;
if (get_permission('admission_count_widget', 'is_view')) {
	$div2++;	
}
if (get_permission('voucher_count_widget', 'is_view')) {
	$div2++;	
}
if (get_permission('transport_count_widget', 'is_view')) {
	$div2++;	
}
if (get_permission('hostel_count_widget', 'is_view')) {
	$div2++;	
}
if ($div2 == 0) {
	$widget2 = 0;
}else{
	$widget2 = 12 / $div2;
}
?>
<div class="dashboard-page">
<?php if (isset($subscription) && !empty($subscription)):
	$endDate = strtotime($subscription['end_date']);
	$today = strtotime(date('Y-m-d'));
	$daysLeft = max(0, floor(($endDate - $today) / 86400));
	$planName = $subscription['plan_name'];
	$status = $subscription['status'];
	$payInfo = $this->db->select('paybill_number, account_info, subscription_notice')->where('id', 1)->get('global_settings')->row();

	if ($status == 'expired' || $daysLeft == 0):
?>
	<div class="row">
		<div class="col-md-12">
			<div class="alert alert-danger" style="border-left: 5px solid #d9534f; padding: 20px; background: #fdf2f2;">
				<h4 style="margin-top:0; color:#d9534f;"><i class="fas fa-exclamation-triangle"></i> Subscription Expired!</h4>
				<p style="font-size:16px;">Your <strong><?=$planName?></strong> subscription has expired. The system will be deactivated soon.</p>
				<hr>
				<div class="row">
					<div class="col-md-8">
						<p><strong>Manual M-Pesa Payment:</strong></p>
						<table style="font-size:15px;">
							<tr><td style="padding:3px 15px 3px 0;"><strong>Paybill Number:</strong></td><td><strong style="font-size:18px; color:#d9534f;"><?=isset($payInfo->paybill_number) ? $payInfo->paybill_number : 'N/A'?></strong></td></tr>
							<tr><td style="padding:3px 15px 3px 0;"><strong>Account Name:</strong></td><td><strong><?=get_type_name_by_id('branch', get_loggedin_branch_id(), 'school_name')?></strong></td></tr>
						</table>
						<?php if(!empty($payInfo->subscription_notice)): ?>
						<p class="mt-sm" style="font-size:.9rem"><?=htmlspecialchars($payInfo->subscription_notice)?></p>
						<?php endif; ?>
					</div>
					<div class="col-md-4 text-center" style="padding-top:10px;">
						<button onclick="openPayModal()" class="btn btn-danger btn-lg" style="font-size:1.05rem;padding:14px 28px">
							<i class="fas fa-mobile-alt me-2"></i>Pay via M-Pesa STK Push
						</button>
						<p style="font-size:.78rem;color:#888;margin-top:8px">Get the prompt directly on your phone</p>
						<a href="<?= base_url('subscription_payment/my_invoices') ?>" class="btn btn-link btn-sm">
							<i class="fas fa-file-invoice me-1"></i>My Invoices
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php elseif ($daysLeft <= 14): ?>
	<div class="row">
		<div class="col-md-12">
			<div class="alert alert-warning" style="border-left: 5px solid #f0ad4e; padding: 20px; background: #fef9e7;">
				<div class="row">
					<div class="col-md-7">
						<h4 style="margin-top:0; color:#f0ad4e;"><i class="fas fa-clock"></i> Subscription Expiring Soon</h4>
						<p style="font-size:16px;">Your <strong><?=$planName?></strong> plan expires in <strong style="font-size:22px; color:#d9534f;"><?=$daysLeft?></strong> day<?=$daysLeft != 1 ? 's' : ''?> (<?=date('d M Y', $endDate)?>).</p>
						<p><strong>Manual M-Pesa Payment:</strong></p>
						<table style="font-size:15px;">
							<tr><td style="padding:3px 15px 3px 0;"><strong>Paybill:</strong></td><td><strong style="font-size:18px; color:#333;"><?=isset($payInfo->paybill_number) ? $payInfo->paybill_number : 'N/A'?></strong></td></tr>
							<tr><td style="padding:3px 15px 3px 0;"><strong>Account Name:</strong></td><td><strong><?=get_type_name_by_id('branch', get_loggedin_branch_id(), 'school_name')?></strong></td></tr>
						</table>
					</div>
					<div class="col-md-2 text-center" style="padding-top:20px;">
						<div style="font-size:48px; font-weight:bold; color:<?=$daysLeft <= 7 ? '#d9534f' : '#f0ad4e'?>;"><?=$daysLeft?></div>
						<div style="font-size:14px; color:#666;">Days Left</div>
					</div>
					<div class="col-md-3 text-center" style="padding-top:24px;">
						<button onclick="openPayModal()" class="btn btn-warning btn-lg" style="font-size:1rem;padding:12px 20px;width:100%">
							<i class="fas fa-mobile-alt me-2"></i>Pay via M-Pesa
						</button>
						<p style="font-size:.78rem;color:#888;margin-top:8px">STK Push to your phone</p>
						<a href="<?= base_url('subscription_payment/my_invoices') ?>" class="btn btn-link btn-sm">
							<i class="fas fa-file-invoice me-1"></i>My Invoices
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php else: ?>
	<div class="row">
		<div class="col-md-12">
			<div class="alert alert-success" style="border-left: 5px solid #5cb85c; padding: 12px 20px; background: #f0faf0; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px">
				<span>
					<i class="fas fa-check-circle"></i>
					<strong><?=$planName?></strong> plan active.
					<strong><?=$daysLeft?></strong> days remaining (expires <?=date('d M Y', $endDate)?>).
				</span>
				<span>
					<button onclick="openPayModal()" class="btn btn-success btn-sm">
						<i class="fas fa-mobile-alt me-1"></i>Renew via M-Pesa
					</button>
					<a href="<?= base_url('subscription_payment/my_invoices') ?>" class="btn btn-link btn-sm">
						<i class="fas fa-file-invoice me-1"></i>Invoices
					</a>
				</span>
			</div>
		</div>
	</div>
<?php endif; endif; ?>

<!-- M-PESA STK PUSH MODAL -->
<div id="mpesaPayModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.55);z-index:9999;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:10px;padding:32px;max-width:420px;width:90%;position:relative;box-shadow:0 10px 40px rgba(0,0,0,.3)">
    <button onclick="closePayModal()" style="position:absolute;top:14px;right:16px;background:none;border:none;font-size:1.3rem;cursor:pointer;color:#aaa">&times;</button>

    <!-- Step 1: Enter phone -->
    <div id="mpesaStep1">
      <div style="text-align:center;margin-bottom:20px">
        <div style="font-size:2.5rem;color:#27ae60"><i class="fas fa-mobile-alt"></i></div>
        <h5 style="margin:8px 0 4px;font-weight:700">Pay via M-Pesa STK Push</h5>
        <p style="font-size:.85rem;color:#7f8c8d;margin:0">Enter the M-Pesa phone number to receive the payment prompt</p>
      </div>
      <div style="margin-bottom:16px">
        <label style="font-size:.82rem;font-weight:600;display:block;margin-bottom:4px">Phone Number</label>
        <input type="tel" id="mpesaPhone" class="form-control" placeholder="e.g. 0712345678"
               style="font-size:1.1rem;padding:10px;text-align:center;letter-spacing:2px">
        <small style="color:#7f8c8d;font-size:.78rem">Format: 07XXXXXXXX or 01XXXXXXXX</small>
      </div>
      <div id="mpesaError" style="display:none;color:#d9534f;font-size:.84rem;margin-bottom:12px;padding:8px;background:#fdf2f2;border-radius:4px"></div>
      <button onclick="initiateStk()" id="mpesaPayBtn" class="btn btn-success btn-block" style="font-size:1rem;padding:12px">
        <i class="fas fa-paper-plane me-2"></i>Send Payment Request
      </button>
    </div>

    <!-- Step 2: Waiting for payment -->
    <div id="mpesaStep2" style="display:none;text-align:center">
      <div style="font-size:2.5rem;color:#f39c12;margin-bottom:12px">
        <i class="fas fa-spinner fa-spin"></i>
      </div>
      <h5 style="font-weight:700;margin-bottom:8px">Check Your Phone</h5>
      <p style="font-size:.88rem;color:#555;margin-bottom:4px">
        An M-Pesa payment request has been sent to <strong id="mpesaPhoneDisplay"></strong>
      </p>
      <p style="font-size:.85rem;color:#7f8c8d">
        Enter your M-Pesa PIN to complete payment of <strong id="mpesaAmountDisplay"></strong>
      </p>
      <div style="margin-top:16px;height:4px;background:#eee;border-radius:2px;overflow:hidden">
        <div id="mpesaProgress" style="height:4px;background:#f39c12;width:0%;transition:width .5s"></div>
      </div>
      <p style="font-size:.78rem;color:#aaa;margin-top:8px">Waiting for confirmation...</p>
      <button onclick="closePayModal()" class="btn btn-link btn-sm text-muted">Cancel</button>
    </div>

    <!-- Step 3: Success -->
    <div id="mpesaStep3" style="display:none;text-align:center">
      <div style="font-size:3rem;color:#27ae60;margin-bottom:12px"><i class="fas fa-check-circle"></i></div>
      <h5 style="font-weight:700;color:#27ae60">Payment Successful!</h5>
      <p style="font-size:.88rem;color:#555">Your subscription payment has been received. A VAT invoice has been generated.</p>
      <a id="mpesaInvoiceLink" href="#" class="btn btn-success mt-2" target="_blank">
        <i class="fas fa-file-invoice me-2"></i>Download Invoice
      </a>
      <button onclick="location.reload()" class="btn btn-default mt-2 ms-2">Refresh Page</button>
    </div>

    <!-- Step 4: Failed -->
    <div id="mpesaStep4" style="display:none;text-align:center">
      <div style="font-size:3rem;color:#d9534f;margin-bottom:12px"><i class="fas fa-times-circle"></i></div>
      <h5 style="font-weight:700;color:#d9534f">Payment Not Completed</h5>
      <p style="font-size:.88rem;color:#555">The payment was cancelled or failed. Please try again.</p>
      <button onclick="resetPayModal()" class="btn btn-danger mt-2">Try Again</button>
      <button onclick="closePayModal()" class="btn btn-default mt-2 ms-2">Close</button>
    </div>

  </div>
</div>

<script>
var mpesaCheckoutId = null;
var mpesaPolling = null;
var mpesaProgress = 0;

function openPayModal() {
  document.getElementById('mpesaPayModal').style.display = 'flex';
  resetPayModal();
}
function closePayModal() {
  document.getElementById('mpesaPayModal').style.display = 'none';
  if (mpesaPolling) clearInterval(mpesaPolling);
}
function resetPayModal() {
  document.getElementById('mpesaStep1').style.display = 'block';
  document.getElementById('mpesaStep2').style.display = 'none';
  document.getElementById('mpesaStep3').style.display = 'none';
  document.getElementById('mpesaStep4').style.display = 'none';
  document.getElementById('mpesaError').style.display = 'none';
  document.getElementById('mpesaPhone').value = '';
  document.getElementById('mpesaPayBtn').disabled = false;
  mpesaProgress = 0;
  document.getElementById('mpesaProgress').style.width = '0%';
}

function initiateStk() {
  var phone = document.getElementById('mpesaPhone').value.trim();
  var errEl = document.getElementById('mpesaError');
  if (!phone) {
    errEl.textContent = 'Please enter your phone number.';
    errEl.style.display = 'block';
    return;
  }
  document.getElementById('mpesaPayBtn').disabled = true;
  errEl.style.display = 'none';

  var xhr = new XMLHttpRequest();
  xhr.open('POST', '<?= base_url('subscription_payment/initiate') ?>', true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
  xhr.onload = function() {
    var res = JSON.parse(xhr.responseText);
    if (res.success) {
      mpesaCheckoutId = res.checkout_request_id;
      document.getElementById('mpesaPhoneDisplay').textContent = phone;
      document.getElementById('mpesaAmountDisplay').textContent = 'KES ' + res.amount;
      document.getElementById('mpesaStep1').style.display = 'none';
      document.getElementById('mpesaStep2').style.display = 'block';
      startPolling();
    } else {
      errEl.textContent = res.message || 'Something went wrong. Please try again.';
      errEl.style.display = 'block';
      document.getElementById('mpesaPayBtn').disabled = false;
    }
  };
  xhr.send('phone=' + encodeURIComponent(phone) + '&<?= $this->security->get_csrf_token_name() ?>=<?= $this->security->get_csrf_hash() ?>');
}

function startPolling() {
  var polls = 0;
  mpesaPolling = setInterval(function() {
    polls++;
    mpesaProgress = Math.min(95, polls * 4);
    document.getElementById('mpesaProgress').style.width = mpesaProgress + '%';

    if (polls > 35) { // ~105 seconds timeout
      clearInterval(mpesaPolling);
      document.getElementById('mpesaStep2').style.display = 'none';
      document.getElementById('mpesaStep4').style.display = 'block';
      return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '<?= base_url('subscription_payment/check') ?>', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.onload = function() {
      var res = JSON.parse(xhr.responseText);
      if (res.status === 'completed') {
        clearInterval(mpesaPolling);
        document.getElementById('mpesaProgress').style.width = '100%';
        document.getElementById('mpesaStep2').style.display = 'none';
        document.getElementById('mpesaStep3').style.display = 'block';
        if (res.invoice_id) {
          document.getElementById('mpesaInvoiceLink').href = '<?= base_url('subscription_payment/invoice/') ?>' + res.invoice_id;
        }
      } else if (res.status === 'failed' || res.status === 'cancelled') {
        clearInterval(mpesaPolling);
        document.getElementById('mpesaStep2').style.display = 'none';
        document.getElementById('mpesaStep4').style.display = 'block';
      }
    };
    xhr.send('checkout_request_id=' + encodeURIComponent(mpesaCheckoutId) + '&<?= $this->security->get_csrf_token_name() ?>=<?= $this->security->get_csrf_hash() ?>');
  }, 3000);
}
</script>

	<div class="row">
<?php if (get_permission('monthly_income_vs_expense_chart', 'is_view')) { ?>
		<!-- monthly cash book transaction -->
		<div class="<?php echo get_permission('annual_student_fees_summary_chart', 'is_view') ? 'col-md-12 col-lg-4 col-xl-3' : 'col-md-12'; ?>">
			<section class="panel pg-fw">
				<div class="panel-body">
					<h4 class="chart-title mb-xs"><?=translate('income_vs_expense_of') . " " . date('F')?></h4>
					<div id="cash_book_transaction"></div>
					<div class="round-overlap"><i class="fab fa-sellcast"></i></div>
					<div class="text-center">
						<ul class="list-inline">
							<li>
								<h6 class="text-muted"><i class="fa fa-circle text-blue"></i> <?=translate('income')?></h6>
							</li>
							<li>
								<h6 class="text-muted"><i class="fa fa-circle text-danger"></i> <?=translate('expense')?></h6>
							</li>
						</ul>
					</div>
				</div>
			</section>
		</div>
<?php } ?>
<?php if (get_permission('annual_student_fees_summary_chart', 'is_view')) { ?>
		<!-- student fees summary graph -->
		<div class="<?php echo get_permission('monthly_income_vs_expense_chart', 'is_view') ? 'col-md-12 col-lg-8 col-xl-9' : 'col-md-12'; ?>">
			<section class="panel">
				<div class="panel-body">
					<h4 class="chart-title mb-md"><?=translate('annual_fee_summary')?></h4>
					<div class="pe-chart">
						<canvas id="fees_graph" style="height: 322px;"></canvas>
					</div>
				</div>
			</section>
		</div>
<?php } ?>
	</div>
<?php if ($widget1 > 0) { ?>
	<div class="row">
		<div class="col-md-12 col-lg-12 col-sm-12">
			<div class="panel">
				<div class="row widget-row-in">
				<?php if (get_permission('employee_count_widget', 'is_view')) { ?>
					<div class="col-lg-<?php echo $widget1; ?> col-sm-6 ">
						<div class="panel-body">
							<div class="widget-col-in row">
								<div class="col-md-6 col-sm-6 col-xs-6"> <i class="fas fa-users"></i>
									<h5 class="text-muted"><?php echo translate('employee'); ?></h5>
								</div>
								<div class="col-md-6 col-sm-6 col-xs-6">
									<h3 class="counter text-right mt-md text-primary"><?php
									$staff = $this->dashboard_model->getstaffcounter('', $school_id);
									echo $staff['snumber'];
									?></h3>
								</div>
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="box-top-line line-color-primary">
										<span class="text-muted text-uppercase"><?php echo translate('total_strength'); ?></span>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
				<?php if (get_permission('student_count_widget', 'is_view')) { ?>
					<div class="col-lg-<?php echo $widget1; ?> col-sm-6">
						<div class="panel-body">
							<div class="widget-col-in row">
								<div class="col-md-6 col-sm-6 col-xs-6"> <i class="fas fa-user-graduate"></i>
									<h5 class="text-muted"><?php echo translate('students'); ?></h5> </div>
								<div class="col-md-6 col-sm-6 col-xs-6">
									<h3 class="counter text-right mt-md text-primary"><?=$get_total_student?></h3>
								</div>
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="box-top-line line-color-primary">
											<span class="text-muted text-uppercase"><?php echo translate('total_strength'); ?></span>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
				<?php if (get_permission('parent_count_widget', 'is_view')) { ?>
					<div class="col-lg-<?php echo $widget1; ?> col-sm-6 ">
						<div class="panel-body">
							<div class="widget-col-in row">
								<div class="col-md-6 col-sm-6 col-xs-6"> <i class="fas fa-user-tie" ></i>
									<h5 class="text-muted"><?php echo translate('parents'); ?></h5></div>
								<div class="col-md-6 col-sm-6 col-xs-6">
									<h3 class="counter text-right mt-md text-primary"><?php
										if (!empty($school_id))
											$this->db->where('branch_id', $school_id);
										echo $this->db->select('id')->get('parent')->num_rows();
									?></h3>
								</div>
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="box-top-line line-color-primary">
										<span class="text-muted text-uppercase"><?php echo translate('total_strength'); ?></span>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
				<?php if (get_permission('teacher_count_widget', 'is_view')) { ?>
					<div class="col-lg-<?php echo $widget1; ?> col-sm-6 ">
						<div class="panel-body">
							<div class="widget-col-in row">
								<div class="col-md-6 col-sm-6 col-xs-6"> <i class="fas fa-chalkboard-teacher" ></i>
									<h5 class="text-muted"><?php echo translate('teachers'); ?></h5></div>
								<div class="col-md-6 col-sm-6 col-xs-6">
									<h3 class="counter text-right mt-md text-primary"><?php
									$staff = $this->dashboard_model->getstaffcounter(3, $school_id);
									echo $staff['snumber'];
									?></h3>
								</div>
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="box-top-line line-color-primary">
										<span class="text-muted text-uppercase"><?=translate('total_strength')?></span>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
	<!-- student quantity chart -->
	<div class="row">
<?php if (get_permission('student_quantity_pie_chart', 'is_view')) { ?>
		<div class="<?php echo get_permission('weekend_attendance_inspection_chart', 'is_view') ? 'col-md-12 col-lg-4 col-xl-3' : 'col-md-12'; ?>">
			<section class="panel pg-fw">
				<div class="panel-body">
					<h4 class="chart-title mb-xs"><?=translate('student_quantity')?></h4>
					<div id="student_strength"></div>
					<div class="round-overlap"><i class="fas fa-school"></i></div>
				</div>
			</section>
		</div>
<?php } ?>
<?php if (get_permission('weekend_attendance_inspection_chart', 'is_view')) { ?>
		<div class="<?php echo get_permission('student_quantity_pie_chart', 'is_view') ? 'col-md-12 col-lg-8 col-xl-9' : 'col-md-12'; ?>">
			<section class="panel">
				<div class="panel-body">
					<h4 class="chart-title mb-md"><?=translate('weekend_attendance_inspection')?></h4>
					<div class="pg-fw">
						<canvas id="weekend_attendance" style="height: 340px;"></canvas>
					</div>
				</div>
			</section>
		</div>
<?php } ?>
	</div>
<?php if ($widget2 > 0) { ?>
	<div class="row">
		<div class="col-md-12 col-lg-12 col-sm-12">
			<div class="panel">
				<div class="row widget-row-in">
				<?php if (get_permission('admission_count_widget', 'is_view')) { ?>
					<div class="col-lg-<?php echo $widget2; ?> col-sm-6 ">
						<div class="panel-body">
							<div class="widget-col-in row">
								<div class="col-md-6 col-sm-6 col-xs-6"> <i class="far fa-address-card"></i>
									<h5 class="text-muted"><?php echo translate('admission'); ?></h5>
								</div>
								<div class="col-md-6 col-sm-6 col-xs-6">
									<h3 class="counter text-right mt-md text-primary"><?=$get_monthly_admission;?></h3>
								</div>
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="box-top-line line-color-primary">
										<span class="text-muted text-uppercase"><?php echo translate('interval_month'); ?></span>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
				<?php if (get_permission('voucher_count_widget', 'is_view')) { ?>
					<div class="col-lg-<?php echo $widget2; ?> col-sm-6">
						<div class="panel-body">
							<div class="widget-col-in row">
								<div class="col-md-6 col-sm-6 col-xs-6"> <i class="fas fa-money-check-alt"></i>
									<h5 class="text-muted"><?php echo translate('voucher'); ?></h5> </div>
								<div class="col-md-6 col-sm-6 col-xs-6">
									<h3 class="counter text-right mt-md text-primary"><?=$get_voucher?></h3>
								</div>
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="box-top-line line-color-primary">
											<span class="text-muted text-uppercase"><?php echo translate('total_number'); ?></span>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
				<?php if (get_permission('transport_count_widget', 'is_view')) { ?>
					<div class="col-lg-<?php echo $widget2; ?> col-sm-6 ">
						<div class="panel-body">
							<div class="widget-col-in row">
								<div class="col-md-6 col-sm-6 col-xs-6"> <i class="fas fa-road" ></i>
									<h5 class="text-muted"><?php echo translate('transport'); ?></h5></div>
								<div class="col-md-6 col-sm-6 col-xs-6">
									<h3 class="counter text-right mt-md text-primary"><?=$get_transport_route?></h3>
								</div>
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="box-top-line line-color-primary">
										<span class="text-muted text-uppercase"><?php echo translate('total_route'); ?></span>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
				<?php if (get_permission('hostel_count_widget', 'is_view')) { ?>
					<div class="col-lg-<?php echo $widget2; ?> col-sm-6 ">
						<div class="panel-body">
							<div class="widget-col-in row">
								<div class="col-md-6 col-sm-6 col-xs-6"> <i class="fas fa-warehouse" ></i>
									<h5 class="text-muted"><?php echo translate('hostel'); ?></h5></div>
								<div class="col-md-6 col-sm-6 col-xs-6">
									<h3 class="counter text-right mt-md text-primary"><?php
										if (!empty($school_id))
											$this->db->where('branch_id', $school_id);
										$hostel_room = $this->db->select('id')->get('hostel_room')->num_rows();
										echo $hostel_room;
										?></h3>
								</div>
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="box-top-line line-color-primary">
										<span class="text-muted text-uppercase"><?=translate('total_room')?></span>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
	<div class="row">
	    <!-- event calendar -->
		<div class="col-md-12">
			<section class="panel">
				<div class="panel-body">
					<div id="event_calendar"></div>
				</div>
			</section>
		</div>
	</div>
</div>

<div class="zoom-anim-dialog modal-block modal-block-primary mfp-hide" id="modal">
	<section class="panel">
		<header class="panel-heading">
			<div class="panel-btn">
				<button onclick="fn_printElem('printResult')" class="btn btn-default btn-circle icon" ><i class="fas fa-print"></i></button>
			</div>
			<h4 class="panel-title"><i class="fas fa-info-circle"></i> <?=translate('event_details')?></h4>
		</header>
		<div class="panel-body">
			<div id="printResult" class=" pt-sm pb-sm">
				<div class="table-responsive">						
					<table class="table table-bordered table-condensed text-dark tbr-top" id="ev_table">
						
					</table>
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button class="btn btn-default modal-dismiss">
						<?=translate('close')?>
					</button>
				</div>
			</div>
		</footer>
	</section>
</div>

<script type="application/javascript">
(function($) {
	$('#event_calendar').fullCalendar({
		header: {
		left: 'prev,next,today',
		center: 'title',
			right: 'month,agendaWeek,agendaDay,listWeek'
		},
		firstDay: 1,
		height: 720,
		droppable: false,
		editable: true,
		events: {
			url: "<?=base_url('event/get_events_list/'. $school_id)?>"
		},
		buttonText: {
			today:    'Today',
			month:    'Month',
			week:     'Week',
			day:      'Day',
			list:     'List'
		},
		eventRender: function(event, element) {
			$(element).on("click", function() {
				viewEvent(event.id);
			});
			if(event.icon){          
				element.find(".fc-title").prepend("<i class='fas fa-"+event.icon+"'></i> ");
			}
		}
	});

	// Annual Fee Summary JS
	var total_fees = <?php echo json_encode($fees_summary["total_fee"]);?>;
	var total_paid = <?php echo json_encode($fees_summary["total_paid"]);?>;
	var total_due = <?php echo json_encode($fees_summary["total_due"]);?>;
	var feesGraph = {
		type: 'line',
		data: {
			labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
			datasets: [{
				label: '<?php echo translate("total");?>',
				data: total_fees,
				backgroundColor: 'rgba(216, 27, 96, .6)',
				borderColor: '#F5F5F5',
				borderWidth: 1
			},{
				label: '<?php echo translate("collected");?>',
				data: total_paid,
				backgroundColor: 'rgba(0, 136, 204, .6)',
				borderColor: '#F5F5F5',
				borderWidth: 1
			},{
				label: '<?php echo translate("remaining");?>',
				data: total_due,
				backgroundColor: 'rgba(204, 102, 102, .6)',
				borderColor: '#F5F5F5',
				borderWidth: 1
			}]
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			circumference: Math.PI,
			tooltips: {
				mode: 'index',
				bodySpacing: 4
			},
			legend: {
				position: 'bottom',
				labels: {
				boxWidth: 12
			}
			},
			scales: {
				xAxes: [{
					scaleLabel: {
					display: false
					}
				}],
				yAxes: [{
					stacked: true,
					scaleLabel: {
						display: false,
					}
				}]
			}
		}
	}

	var days = <?php echo json_encode($weekend_attendance["days"]);?>;
	var employees_att = <?php echo json_encode($weekend_attendance["employee_att"]);?>;
	var student_att = <?php echo json_encode($weekend_attendance["student_att"]);?>;
	var weekendAttendanceChart = {
		type: 'bar',
		data: {
			labels: days,
			datasets: [{
				label: '<?php echo translate("employee");?>',
				data: employees_att,
				backgroundColor: 'rgba(0, 136, 204, .6)',
				borderColor: '#F5F5F5',
				borderWidth: 1,
				fill: false,
			},{
				label: '<?php echo translate("student");?>',
				data: student_att,
				backgroundColor: 'rgba(204, 102, 102, .6)',
				borderColor: '#F5F5F5',
				borderWidth: 1,
				fill: false,
			}]
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			circumference: Math.PI,
			tooltips: {
				mode: 'index',
				bodySpacing: 4
			},
			legend: {
				position: 'bottom',
				labels: {
				boxWidth: 12
			}
			},
			scales: {
				xAxes: [{
					scaleLabel: {
					display: false
					}
				}],
				yAxes: [{
					scaleLabel: {
						display: false,
					}
				}]
			}
		}
	};

<?php if (get_permission('annual_student_fees_summary_chart', 'is_view')) { ?>
	var ctx = document.getElementById('fees_graph').getContext('2d');
	window.myLine =new Chart(ctx, feesGraph);
<?php } ?>
<?php if (get_permission('weekend_attendance_inspection_chart', 'is_view')) { ?>
	var ctx2 = document.getElementById('weekend_attendance').getContext('2d');
	window.myLine =new Chart(ctx2, weekendAttendanceChart);
<?php } ?>
<?php if (get_permission('monthly_income_vs_expense_chart', 'is_view')) { ?>
	// monthly income vs expense chart
	var cash_book_transaction = document.getElementById("cash_book_transaction");
	var cashbookchart = echarts.init(cash_book_transaction);
	cashbookchart.setOption({
		tooltip: {
			trigger: 'item',
			formatter: "{a} <br/>{b} : <?php echo $global_config["currency_symbol"];?> {c} ({d}%)"
		}, 
		legend: {
			show: false
		},
		color: ["#d81b60", "#009efb"],
		series: [{
			name: 'Transaction',
			type: 'pie',
			radius: ['75%', '90%'],
			itemStyle: {
				normal: {
					label: {
						show: false
					},
					labelLine: {
						show: false
					}
				},
				emphasis: {
					label: {
						show: false
					}
				}
			},
			data: <?=json_encode($income_vs_expense)?>
		}]
	});
<?php } ?>
<?php if (get_permission('student_quantity_pie_chart', 'is_view')) { ?>
	// Student Strength Doughnut Chart
	var color = ['#546570', '#c4ccd3', '#c23531', '#2f4554', '#61a0a8', '#d48265', '#91c7ae', '#749f83',  '#ca8622', '#bda29a', '#6e7074'];
	var strength_data = <?php echo json_encode($student_by_class);?>;
	var student_strength = document.getElementById("student_strength");
	var studentchart = echarts.init(student_strength);
	studentchart.setOption( {
		tooltip: {
			trigger: 'item',
			formatter: "{a} <br/>{b} : {c} ({d}%)"
		}, 
		legend: {
			type: 'scroll',
			x: 'center',
			y: 'bottom',
			itemWidth: 14,
<?php if($theme_config["dark_skin"] == "true"): ?>
			inactiveColor: '#4b4b4b',
			textStyle: {
				color: '#6b6b6c'
			}
<?php endif; ?>
		},
		series: [{
			name: 'Strength',
			type: 'pie',
			color: color,
			radius: ['70%', '85%'],
			center: ['50%', '46%'],
			itemStyle: {
				normal: {
					label: {
						show: false
					},
					labelLine: {
						show: false
					}
				},
				emphasis: {
					label: {
						show: false
					}
				}
			},
			data: strength_data
		}]
	});
<?php } ?>
	// charts resize
	$(".sidebar-toggle").on("click",function(event){
		echartsresize();
	});

	$(window).on("resize", echartsresize);

	function echartsresize() {
		setTimeout(function () {
			if ($("#student_strength").length) {
				studentchart.resize();
			}
			if ($("#cash_book_transaction").length) {
				cashbookchart.resize();
			}
		}, 350);
	}
})(jQuery);
</script>