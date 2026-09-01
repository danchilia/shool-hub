<style>
.terms-wrap { max-width: 760px; margin: 40px auto; padding: 0 16px; }
.terms-box {
    background: var(--ap-white); border: 1px solid var(--ap-border);
    border-radius: 14px; padding: 36px 40px;
}
.terms-logo { text-align: center; margin-bottom: 28px; }
.terms-logo img { height: 52px; object-fit: contain; }
.terms-title { font-size: 1.25rem; font-weight: 800; color: var(--ap-navy); margin-bottom: 4px; text-align: center; }
.terms-subtitle { text-align: center; color: var(--ap-muted); font-size: .83rem; margin-bottom: 28px; }
.terms-body {
    background: var(--ap-bg); border: 1px solid var(--ap-border);
    border-radius: 8px; padding: 20px 24px;
    max-height: 360px; overflow-y: auto;
    font-size: .84rem; line-height: 1.75; color: var(--ap-text);
    margin-bottom: 24px;
}
.terms-body h5 { font-size: .88rem; font-weight: 700; margin: 20px 0 6px; color: var(--ap-navy); }
.terms-body h5:first-child { margin-top: 0; }
.terms-body p, .terms-body ul { margin: 0 0 10px; }
.terms-body ul { padding-left: 18px; }
.terms-scroll-hint { font-size: .75rem; color: var(--ap-muted); text-align: center; margin-bottom: 14px; }
.terms-agree-row {
    display: flex; align-items: flex-start; gap: 12px;
    background: rgba(41,128,185,.07); border-radius: 8px;
    padding: 14px 18px; margin-bottom: 20px;
}
.terms-agree-row input[type=checkbox] { width: 18px; height: 18px; margin-top: 2px; flex-shrink: 0; cursor: pointer; }
.terms-agree-row label { font-size: .87rem; line-height: 1.5; cursor: pointer; }
</style>

<div class="terms-wrap">
  <div class="terms-box">

    <div class="terms-logo">
      <img src="<?= base_url('assets/images/cst-logo.png') ?>" alt="CST">
    </div>
    <div class="terms-title">Agent Terms & Conditions</div>
    <div class="terms-subtitle">Please read the full agreement before you can access the portal.</div>

    <?php if (!empty($error)): ?>
    <div style="background:#fdf2f2;border:1px solid #f5c6cb;border-radius:8px;padding:10px 14px;font-size:.83rem;color:#c0392b;margin-bottom:16px">
      <i class="fas fa-exclamation-circle me-1"></i><?= $error ?>
    </div>
    <?php endif; ?>

    <div class="terms-body" id="termsBody">

      <h5>1. Parties</h5>
      <p>This agreement is between <strong>CST Solutions</strong> ("the Company") and the individual named in the agent account ("the Agent").</p>

      <h5>2. Nature of Engagement</h5>
      <p>The Agent is engaged on a <strong>performance basis</strong> and represents CST Solutions as an independent field representative responsible for identifying and onboarding schools onto the CST SchoolHub platform.</p>

      <h5>3. Earnings Structure</h5>
      <ul>
        <li><strong>Starter Level (0–9 active schools):</strong> The Agent earns a one-time commission of KSh 500 per school successfully onboarded and active on the platform, plus expense reimbursement for school visits (maximum KSh 300 per school visit, paid at end of month).</li>
        <li><strong>Level 1 and above (10+ active schools):</strong> The Agent qualifies for a monthly retainer of KSh 1,000 per active school, up to a maximum of KSh 50,000 per month. A one-year employment contract (renewable) is issued at Level 1.</li>
        <li>A school is considered "active" as long as it continues paying and using the CST SchoolHub platform. Schools that stop paying are removed from the active count.</li>
      </ul>

      <h5>4. Expense Claims</h5>
      <ul>
        <li>Expense claims are limited to school visit costs (e.g., transport fare).</li>
        <li>The maximum claimable amount is <strong>KSh 300 per school visit</strong>.</li>
        <li>Expenses are reviewed and paid at the <strong>end of each month</strong>.</li>
        <li>Claims must be linked to a specific school visit. False claims will result in immediate termination.</li>
      </ul>

      <h5>5. Agent Responsibilities</h5>
      <ul>
        <li>Identify and visit potential schools in the assigned region.</li>
        <li>Present and demonstrate the CST SchoolHub platform honestly and professionally.</li>
        <li>Submit accurate visit reports and follow-up updates.</li>
        <li>Provide post-onboarding customer care and follow-up support to all schools in their portfolio.</li>
        <li>Maintain professional conduct and uphold the reputation of CST Solutions at all times.</li>
      </ul>

      <h5>6. Confidentiality</h5>
      <p>The Agent agrees not to disclose any proprietary information, client data, pricing, or business strategies of CST Solutions to any third party during or after the engagement.</p>

      <h5>7. Termination</h5>
      <p>Either party may terminate this agreement with 14 days written notice. CST Solutions reserves the right to terminate immediately in cases of misconduct, dishonesty, or breach of these terms.</p>

      <h5>8. Commission Eligibility</h5>
      <p>Commission is only released after CST Solutions confirms a school's setup is complete and verified. The Agent has no claim to commission for schools that have not been fully onboarded and confirmed.</p>

      <h5>9. Contract at Level 1</h5>
      <p>Upon reaching Level 1 (10 active schools), the Agent will be issued a formal one-year employment contract. This contract must be printed, signed, scanned, and uploaded to the portal for verification before it takes effect. The contract is renewable based on performance.</p>

      <h5>10. Governing Law</h5>
      <p>This agreement is governed by the laws of Kenya. Any disputes shall be resolved through mutual negotiation or, if necessary, through the appropriate Kenyan courts.</p>

    </div>

    <p class="terms-scroll-hint"><i class="fas fa-arrow-down me-1"></i>Scroll through the full document above before accepting</p>

    <form method="post" action="<?= base_url('agent_portal/terms') ?>">
      <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
      <input type="hidden" name="accept" value="1">

      <div class="terms-agree-row">
        <input type="checkbox" name="i_agree" value="1" id="i_agree">
        <label for="i_agree">
          I confirm that I have <strong>read and understood</strong> the full Agent Terms & Conditions above.
          I agree to abide by all the rules and conditions set out by CST Solutions.
        </label>
      </div>

      <button type="submit"
              style="width:100%;padding:12px;background:var(--ap-navy);color:#fff;border:none;border-radius:8px;font-size:.92rem;font-weight:700;cursor:pointer">
        <i class="fas fa-check-circle me-2"></i>I Accept — Access My Portal
      </button>
    </form>

  </div>
</div>
