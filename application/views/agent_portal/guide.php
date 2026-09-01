<style>
.guide-step {
  display: flex; gap: 20px; margin-bottom: 32px; align-items: flex-start;
}
.step-num {
  min-width: 40px; height: 40px; border-radius: 50%;
  background: var(--ap-navy); color: #fff;
  display: flex; align-items: center; justify-content: center;
  font-size: 1rem; font-weight: 700; flex-shrink: 0;
}
.step-num.done   { background: var(--ap-green); }
.step-num.orange { background: var(--ap-accent); }
.step-body { flex: 1; }
.step-title {
  font-weight: 700; font-size: .97rem; margin-bottom: 6px; color: var(--ap-text);
}
.step-desc { font-size: .87rem; color: var(--ap-muted); line-height: 1.65; }
.step-desc strong { color: var(--ap-text); }
.step-tip {
  margin-top: 10px; background: rgba(243,156,18,.08);
  border-left: 3px solid var(--ap-accent); border-radius: 4px;
  padding: 8px 12px; font-size: .82rem; color: var(--ap-text);
}
.step-tip i { color: var(--ap-accent); margin-right: 4px; }
.guide-section {
  background: var(--ap-white); border-radius: 10px;
  border: 1px solid var(--ap-border); padding: 28px 28px 8px; margin-bottom: 24px;
}
.guide-section-title {
  font-size: .7rem; text-transform: uppercase; letter-spacing: 1.2px;
  color: var(--ap-muted); font-weight: 700; margin-bottom: 24px;
  padding-bottom: 10px; border-bottom: 1px solid var(--ap-border);
}
.pipeline-flow {
  display: flex; flex-wrap: wrap; gap: 8px; margin: 12px 0 4px;
}
.pip-arrow {
  display: flex; align-items: center; gap: 6px; font-size: .78rem;
}
.pip-arrow span {
  padding: 3px 10px; border-radius: 20px; font-weight: 600;
}
.pip-arrow i { color: var(--ap-muted); font-size: .7rem; }
.alert-info-custom {
  background: rgba(41,128,185,.08); border: 1px solid rgba(41,128,185,.2);
  border-radius: 8px; padding: 14px 18px; font-size: .85rem;
  color: var(--ap-text); margin-bottom: 24px;
}
.alert-info-custom i { color: #2980b9; margin-right: 6px; }
</style>

<div class="alert-info-custom">
  <i class="fas fa-info-circle"></i>
  This guide walks you through everything from adding your first school to receiving your commission.
  Follow the steps in order. Each step builds on the previous one.
</div>

<!-- SECTION 1: GETTING STARTED -->
<div class="guide-section">
  <div class="guide-section-title"><i class="fas fa-play-circle me-2"></i>Part 1 Getting Started</div>

  <div class="guide-step">
    <div class="step-num">1</div>
    <div class="step-body">
      <div class="step-title">Log In to the Agent Portal</div>
      <div class="step-desc">
        Go to <strong>cstschoolhub.co.ke/agent_portal/login</strong> and enter your email and password provided by CST SchoolHub.
        If you have not received your login credentials, contact the CST team.
      </div>
    </div>
  </div>

  <div class="guide-step">
    <div class="step-num">2</div>
    <div class="step-body">
      <div class="step-title">Understand Your Dashboard</div>
      <div class="step-desc">
        After logging in you will see your <strong>Dashboard</strong>. It shows:
        <ul style="margin-top:6px;padding-left:18px;line-height:2">
          <li>Total schools in your pipeline</li>
          <li>Pending follow-ups (schools you need to visit again)</li>
          <li>Your recent visits</li>
          <li>Earnings summary</li>
        </ul>
        Always check the dashboard first overdue follow-ups are listed at the top.
      </div>
    </div>
  </div>

  <div class="guide-step">
    <div class="step-num">3</div>
    <div class="step-body">
      <div class="step-title">Download the Data Collection Form</div>
      <div class="step-desc">
        Before visiting any school, go to <strong>Onboarding → Download Form</strong> in the left sidebar and download the
        <strong>CST School Hub Data Collection Form</strong>. Print it or keep it on your device.
        You will fill this form together with the school when they agree to sign up.
      </div>
      <div class="step-tip">
        <i class="fas fa-lightbulb"></i>
        Always carry printed copies of this form to school visits. It collects everything needed to set up the school's account.
      </div>
    </div>
  </div>
</div>

<!-- SECTION 2: MANAGING SCHOOLS -->
<div class="guide-section">
  <div class="guide-section-title"><i class="fas fa-school me-2"></i>Part 2 Managing Your Schools</div>

  <div class="guide-step">
    <div class="step-num">4</div>
    <div class="step-body">
      <div class="step-title">Add a School Lead</div>
      <div class="step-desc">
        Every school you identify as a potential customer must be added to your portal.
        Go to <strong>Schools → Add School Lead</strong> and fill in:
        <ul style="margin-top:6px;padding-left:18px;line-height:2">
          <li>School name (required)</li>
          <li>Principal's name and phone</li>
          <li>County and sub-county</li>
          <li>Approximate number of students</li>
          <li>Whether they currently use any school system</li>
        </ul>
        The school is saved with status <strong>"Lead"</strong> meaning you have identified them but not yet visited.
      </div>
    </div>
  </div>

  <div class="guide-step">
    <div class="step-num">5</div>
    <div class="step-body">
      <div class="step-title">Log Every Visit</div>
      <div class="step-desc">
        Each time you visit or call a school, open that school from <strong>My Schools</strong> and click <strong>"Log Visit"</strong>.
        Record:
        <ul style="margin-top:6px;padding-left:18px;line-height:2">
          <li>Date of visit</li>
          <li>Type: physical visit, phone call, or demo</li>
          <li>Interest level: Hot, Warm, or Cold</li>
          <li>What happened (outcome)</li>
          <li>Next follow-up date if needed</li>
        </ul>
        Logging visits supports your <strong>expense claims</strong> — always log a visit before submitting an expense for that school.
      </div>
      <div class="step-tip">
        <i class="fas fa-lightbulb"></i>
        Always set a next follow-up date if the school did not make a decision yet. These will appear on your Dashboard so you never miss a school.
      </div>
    </div>
  </div>

  <div class="guide-step">
    <div class="step-num">6</div>
    <div class="step-body">
      <div class="step-title">Understand the School Pipeline</div>
      <div class="step-desc">
        Every school moves through stages as you work with them:
      </div>
      <div class="pipeline-flow">
        <div class="pip-arrow"><span class="pip-lead">Lead</span><i class="fas fa-chevron-right"></i></div>
        <div class="pip-arrow"><span class="pip-visited">Visited</span><i class="fas fa-chevron-right"></i></div>
        <div class="pip-arrow"><span class="pip-demo_done">Demo Done</span><i class="fas fa-chevron-right"></i></div>
        <div class="pip-arrow"><span class="pip-proposal_sent">Proposal Sent</span><i class="fas fa-chevron-right"></i></div>
        <div class="pip-arrow"><span class="pip-follow_up">Follow Up</span><i class="fas fa-chevron-right"></i></div>
        <div class="pip-arrow"><span class="pip-closed_won">Closed Won ✓</span></div>
      </div>
      <div class="step-desc" style="margin-top:10px">
        The stage updates automatically based on your visit outcome.
        <strong>Closed Won</strong> means the school has agreed to sign up this is when you proceed to the onboarding step below.
        <strong>Closed Lost</strong> means they declined you can re-approach them later.
      </div>
    </div>
  </div>
</div>

<!-- SECTION 3: ONBOARDING A SCHOOL -->
<div class="guide-section">
  <div class="guide-section-title"><i class="fas fa-clipboard-check me-2"></i>Part 3 Submitting a School for Setup (Onboarding)</div>

  <div class="guide-step">
    <div class="step-num orange">7</div>
    <div class="step-body">
      <div class="step-title">Fill the Data Collection Form with the School</div>
      <div class="step-desc">
        Once a school agrees to sign up, sit with the principal or school administrator and fill the
        <strong>Data Collection Form</strong> together. The form collects:
        <ul style="margin-top:6px;padding-left:18px;line-height:2">
          <li>School identity (name, registration number, type, category)</li>
          <li>Location (county, sub-county, ward, postal address)</li>
          <li>School contacts (phone, email, website)</li>
          <li>Principal details</li>
          <li>Number of students, teaching staff, non-teaching staff, and streams</li>
          <li>System administrator the person who will manage the system daily</li>
          <li>Subscription plan and billing preference</li>
        </ul>
        Make sure all information is accurate and the form is signed by the school.
      </div>
      <div class="step-tip">
        <i class="fas fa-exclamation-triangle"></i>
        The <strong>System Administrator</strong> details are very important this is the person who will receive their login
        credentials. Get their correct full name, phone number, and email address.
      </div>
    </div>
  </div>

  <div class="guide-step">
    <div class="step-num orange">8</div>
    <div class="step-body">
      <div class="step-title">Submit the School Through the Portal</div>
      <div class="step-desc">
        Go to <strong>My Schools</strong>, open the school (it should show <strong>Closed Won</strong> status),
        and click the orange <strong>"Submit for Setup"</strong> button.
        On the submission form:
        <ul style="margin-top:6px;padding-left:18px;line-height:2">
          <li>Fill in all the school details from the Data Collection Form</li>
          <li>Select the subscription plan the school chose</li>
          <li>Select billing cycle (monthly or yearly)</li>
          <li>At the bottom, <strong>upload the filled Data Collection Form</strong> (.docx or .pdf)</li>
        </ul>
        Click <strong>"Submit School for Setup"</strong> when done.
      </div>
      <div class="step-tip">
        <i class="fas fa-lightbulb"></i>
        Uploading the filled form is optional but strongly recommended it helps the CST team verify all details quickly.
      </div>
    </div>
  </div>

  <div class="guide-step">
    <div class="step-num orange">9</div>
    <div class="step-body">
      <div class="step-title">Wait for Superadmin Approval</div>
      <div class="step-desc">
        After you submit, CST SchoolHub will review the details. You can check the status anytime under
        <strong>Onboarding → My Submissions</strong>. The status will be one of:
        <ul style="margin-top:6px;padding-left:18px;line-height:2">
          <li><strong>Pending</strong> waiting for review</li>
          <li><strong>Reviewed</strong> CST team has looked at it, may have notes</li>
          <li><strong>Approved</strong> school has been created in the system</li>
          <li><strong>Rejected</strong> missing information or issue (check admin notes)</li>
        </ul>
      </div>
    </div>
  </div>
</div>

<!-- SECTION 4: HELPING WITH SETUP -->
<div class="guide-section">
  <div class="guide-section-title"><i class="fas fa-cogs me-2"></i>Part 4 Helping the School Set Up</div>

  <div class="guide-step">
    <div class="step-num done">10</div>
    <div class="step-body">
      <div class="step-title">Receive the School Admin Login Credentials</div>
      <div class="step-desc">
        Once the CST team approves the submission and creates the school's account, they will
        <strong>contact you with the school admin login credentials</strong> the email and password for the
        school's system administrator account.
        These are the same credentials you will use to help the school set up their system.
      </div>
    </div>
  </div>

  <div class="guide-step">
    <div class="step-num done">11</div>
    <div class="step-body">
      <div class="step-title">Log In as the School Admin and Enter All Data</div>
      <div class="step-desc">
        Go to <strong>cstschoolhub.co.ke</strong>, log in using the school admin credentials, and set up the school's account
        using the data you collected. This includes:
        <ul style="margin-top:6px;padding-left:18px;line-height:2">
          <li>Classes and streams</li>
          <li>Student enrollment</li>
          <li>Teaching and non-teaching staff</li>
          <li>Fee structure and payment categories</li>
          <li>Any other required configuration</li>
        </ul>
        Use your filled Data Collection Form as your reference during setup.
        Do this together with the school administrator if possible so they learn the system as you set it up.
      </div>
      <div class="step-tip">
        <i class="fas fa-exclamation-triangle"></i>
        <strong>Do not leave until the school is fully set up.</strong> Your commission is only released after
        CST SchoolHub confirms the setup is complete.
      </div>
    </div>
  </div>

  <div class="guide-step">
    <div class="step-num done">12</div>
    <div class="step-body">
      <div class="step-title">CST Marks Setup Complete You Get Paid</div>
      <div class="step-desc">
        After setup is confirmed complete by CST SchoolHub, your <strong>commission earning</strong> is automatically
        added to your account. You can see it under <strong>Finances → My Earnings</strong>.
        Earnings go through these statuses:
        <ul style="margin-top:6px;padding-left:18px;line-height:2">
          <li><strong>Pending</strong> logged, waiting for approval</li>
          <li><strong>Approved</strong> confirmed, being processed for payment</li>
          <li><strong>Paid</strong> money has been sent to you</li>
        </ul>
      </div>
    </div>
  </div>
</div>

<!-- SECTION 5: OTHER FEATURES -->
<div class="guide-section">
  <div class="guide-section-title"><i class="fas fa-tools me-2"></i>Part 5 Other Features</div>

  <div class="guide-step">
    <div class="step-num" style="background:var(--ap-navy2)">A</div>
    <div class="step-body">
      <div class="step-title">Expense Claims</div>
      <div class="step-desc">
        When you visit a school, you can claim transport and visit expenses. Go to <strong>Finances → Expense Claims</strong>,
        select the school you visited, describe the cost, and enter the amount.
        <ul style="margin-top:6px;padding-left:18px;line-height:2">
          <li>Maximum <strong>KSh 300 per school visit</strong></li>
          <li>Claims are reviewed and paid at the <strong>end of the month</strong></li>
          <li>You must select a school — expenses must be linked to a visit</li>
          <li>CST reserves the right to reject claims that are not legitimate visit costs</li>
        </ul>
      </div>
    </div>
  </div>

  <div class="guide-step">
    <div class="step-num" style="background:var(--ap-navy2)">B</div>
    <div class="step-body">
      <div class="step-title">Follow-Ups</div>
      <div class="step-desc">
        When logging a visit, always set a <strong>Next Follow-Up Date</strong> if the school has not yet decided.
        All pending follow-ups appear on your <strong>Dashboard</strong> and in <strong>Schools → Follow-ups</strong>.
        Overdue ones are highlighted in red so you know which schools need urgent attention.
      </div>
    </div>
  </div>

  <div class="guide-step">
    <div class="step-num" style="background:var(--ap-navy2)">C</div>
    <div class="step-body">
      <div class="step-title">Demo Tools</div>
      <div class="step-desc">
        Under <strong>Demo Tools</strong> in the sidebar, you will find:
        <ul style="margin-top:6px;padding-left:18px;line-height:2">
          <li><strong>Demo Credentials</strong> a sample school login you can show to prospects during demos</li>
          <li><strong>All Modules</strong> a list of all features available in the system to show schools what they get</li>
        </ul>
        Use these tools when presenting CST SchoolHub to a school for the first time.
      </div>
    </div>
  </div>

  <div class="guide-step">
    <div class="step-num" style="background:var(--ap-navy2)">D</div>
    <div class="step-body">
      <div class="step-title">My Submissions</div>
      <div class="step-desc">
        Under <strong>Onboarding → My Submissions</strong>, you can see all schools you have submitted for setup,
        their current status, and any notes from the CST team. Check this regularly to follow up on
        any submissions that have been rejected or need additional information.
      </div>
    </div>
  </div>

  <div class="guide-step">
    <div class="step-num" style="background:#c9a84c">E</div>
    <div class="step-body">
      <div class="step-title">My Level — Career Progression & Monthly Retainer</div>
      <div class="step-desc">
        Go to <strong>Finances → My Level</strong> to see your current career level and monthly retainer.
        As you grow your active school count, you move up levels and earn a higher monthly salary:
        <ul style="margin-top:6px;padding-left:18px;line-height:2">
          <li><strong>Starter (0–9 schools):</strong> Commission when a school signs up + visit expense reimbursement (max KSh 300/school). No monthly salary yet.</li>
          <li><strong>Level 1 (10 schools):</strong> KSh 10,000/month + permanent yearly contract</li>
          <li><strong>Level 2 (15 schools):</strong> KSh 15,000/month</li>
          <li><strong>Level 3 (20 schools):</strong> KSh 20,000/month</li>
          <li>Continues up to <strong>Legend (50+ schools): KSh 50,000/month</strong></li>
        </ul>
        Your monthly retainer is only active while your schools continue paying and using the system.
        If a school stops, it is removed from your active count — so always follow up and support your schools.
      </div>
      <div class="step-tip">
        <i class="fas fa-trophy"></i>
        <strong>Reaching Level 1 (10 active schools) qualifies you for a permanent yearly contract</strong>
        — renewable as long as you maintain your active school count.
        Your job does not stop at signup: keep visiting your schools, help them use the system,
        and ensure they keep their subscription active.
      </div>
    </div>
  </div>
</div>

<!-- QUICK REFERENCE -->
<div class="ap-card mb-4" style="border-left:3px solid var(--ap-green)">
  <div class="ap-card-header"><i class="fas fa-bolt me-2" style="color:var(--ap-green)"></i>Quick Reference Full Agent Workflow</div>
  <div class="ap-card-body" style="font-size:.85rem">
    <ol style="padding-left:20px;line-height:2.2;margin:0">
      <li>Download the Data Collection Form from the portal</li>
      <li>Identify a school → Add it as a <strong>School Lead</strong></li>
      <li>Visit the school → <strong>Log the Visit</strong> (earn visit fee)</li>
      <li>Do a demo → Log visit as "Demo"</li>
      <li>Follow up until school agrees → Log each interaction</li>
      <li>School agrees → Fill the Data Collection Form with them</li>
      <li>Portal → School → <strong>Submit for Setup</strong> → upload filled form</li>
      <li>Wait for CST approval → check <strong>My Submissions</strong></li>
      <li>Receive school admin credentials from CST team</li>
      <li>Log in as school admin → enter all data to set up the school</li>
      <li>CST marks setup complete → <strong>commission added to your earnings</strong></li>
      <li>Continue visiting & supporting your schools → check <strong>My Level</strong> as you grow</li>
      <li>Reach <strong>10 active schools</strong> → earn monthly retainer + permanent yearly contract</li>
    </ol>
  </div>
</div>

<p style="font-size:.8rem;color:var(--ap-muted);text-align:center;padding-bottom:12px">
  For any questions or issues, contact the CST SchoolHub team.
</p>
