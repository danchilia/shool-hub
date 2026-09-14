<style>
.script-card {
  background: var(--ap-white); border-radius: 10px;
  border: 1px solid var(--ap-border); margin-bottom: 20px; overflow: hidden;
}
.script-card-header {
  padding: 14px 20px; font-weight: 700; font-size: .88rem;
  display: flex; align-items: center; gap: 10px;
  border-bottom: 1px solid var(--ap-border);
}
.script-card-body { padding: 20px; }
.script-line {
  background: rgba(26,46,74,.04); border-left: 4px solid var(--ap-navy);
  border-radius: 0 6px 6px 0; padding: 14px 16px;
  font-size: .9rem; line-height: 1.75; color: var(--ap-text);
  margin-bottom: 12px; font-style: italic;
}
.script-line.accent { border-left-color: var(--ap-accent); background: rgba(243,156,18,.06); }
.script-line.green  { border-left-color: var(--ap-green);  background: rgba(39,174,96,.06); }
.script-note {
  font-size: .8rem; color: var(--ap-muted); margin: 6px 0 14px 4px;
  display: flex; align-items: flex-start; gap: 6px;
}
.script-note i { margin-top: 2px; color: var(--ap-accent); flex-shrink: 0; }
.objection-label {
  font-size: .78rem; font-weight: 700; text-transform: uppercase;
  letter-spacing: .6px; color: var(--ap-muted); margin: 16px 0 6px;
}
.objection-label:first-child { margin-top: 0; }
.tip-box {
  background: rgba(39,174,96,.07); border: 1px solid rgba(39,174,96,.2);
  border-radius: 8px; padding: 14px 18px; font-size: .84rem;
  color: var(--ap-text); margin-bottom: 20px; line-height: 1.7;
}
.tip-box i { color: var(--ap-green); margin-right: 6px; }
.stage-badge {
  font-size: .68rem; font-weight: 700; text-transform: uppercase;
  letter-spacing: .8px; padding: 3px 10px; border-radius: 20px;
  background: var(--ap-navy); color: #fff;
}
</style>

<!-- Script tabs -->
<div style="display:flex;gap:10px;margin-bottom:24px;flex-wrap:wrap;">
  <button onclick="showScript('phone')" id="tab-phone" class="script-tab active-tab">
    <i class="fas fa-phone"></i> Phone Call Script
  </button>
  <button onclick="showScript('visit')" id="tab-visit" class="script-tab">
    <i class="fas fa-handshake"></i> Physical Visit Script
  </button>
</div>

<style>
.script-tab {
  padding:10px 22px;border-radius:8px;border:2px solid var(--ap-border);
  background:var(--ap-white);color:var(--ap-muted);font-weight:600;font-size:.85rem;
  cursor:pointer;display:flex;align-items:center;gap:8px;transition:all .15s;
}
.script-tab.active-tab {
  background:var(--ap-navy);color:#fff;border-color:var(--ap-navy);
}
</style>

<!-- ═══════════════════════════════════════════════════════ -->
<!-- PHONE CALL SCRIPT -->
<!-- ═══════════════════════════════════════════════════════ -->
<div id="script-phone">

<div class="tip-box">
  <i class="fas fa-phone"></i>
  <strong>Key mindset:</strong> Most schools will sense a sales call within 10 seconds and switch off.
  Lead with their problem, ask questions, and let them do the talking. The best phone call feels like a conversation — not a pitch.
</div>

<!-- BEFORE YOU CALL -->
<div class="script-card">
  <div class="script-card-header">
    <span class="stage-badge" style="background:#7f8c8d">Before You Call</span>
    <i class="fas fa-clipboard-list" style="color:#7f8c8d"></i>
    Check the School Card First
  </div>
  <div class="script-card-body">
    <div class="script-note" style="margin:0">
      <i class="fas fa-info-circle"></i>
      <span>Check the school's area/location, type (primary/secondary), phone number, and any notes from previous contact before dialling. It helps you personalise the call.</span>
    </div>
  </div>
</div>

<!-- STEP 1 — REACH THE RIGHT PERSON -->
<div class="script-card">
  <div class="script-card-header">
    <span class="stage-badge" style="background:var(--ap-navy)">Step 1</span>
    <i class="fas fa-phone" style="color:var(--ap-accent)"></i>
    Reach the Right Person
  </div>
  <div class="script-card-body">
    <div class="script-line">
      "Good [morning/afternoon], I'm [Your Name]. Could I speak with the person who handles the school's administration — the Director or Head Teacher?"
    </div>
    <div class="objection-label">If not available:</div>
    <div class="script-line accent">
      "No problem. Could I get their name so I know who to ask for when I call back? And what time is usually best to reach them?"
    </div>
    <div class="script-note">
      <i class="fas fa-info-circle"></i>
      Log the name and best call-back time. Set a follow-up date in your portal and end the call politely.
    </div>
  </div>
</div>

<!-- STEP 2 — OPENING -->
<div class="script-card">
  <div class="script-card-header">
    <span class="stage-badge" style="background:#2980b9">Step 2</span>
    <i class="fas fa-comment-dots" style="color:#2980b9"></i>
    Opening — Lead With Their Problem, Not Your Product
  </div>
  <div class="script-card-body">
    <div class="objection-label">When speaking to the Director / Owner:</div>
    <div class="script-line">
      "Thank you for taking my call — I'll be quick. I visit schools around [area] and one thing I keep hearing from school owners is that collecting fees and keeping track of student records takes up so much time. Is that something you experience here as well?"
    </div>
    <div class="objection-label">When speaking to the Head Teacher:</div>
    <div class="script-line">
      "Thank you for taking my call — I'll be quick. I work with schools around [area] and teachers often tell me that things like attendance, exams and homework take a lot of manual work. Is that something your teachers deal with here?"
    </div>
    <div class="objection-label">When speaking to a Secretary / Receptionist:</div>
    <div class="script-line accent">
      "I understand — I just have a quick question. Does your school currently use any system for managing fees and student records, or is it mostly done manually?"
    </div>
    <div class="script-note">
      <i class="fas fa-info-circle"></i>
      End your opening with a question. If they answer, you have them. If they say no problem exists, ask what they use — you will still learn something useful.
    </div>
  </div>
</div>

<!-- STEP 3 — LISTEN AND ASK -->
<div class="script-card">
  <div class="script-card-header">
    <span class="stage-badge" style="background:#8e44ad">Step 3</span>
    <i class="fas fa-ear-listen" style="color:#8e44ad"></i>
    Listen and Ask Questions — Do Not Pitch Yet
  </div>
  <div class="script-card-body">
    <div class="script-line accent">"How many students do you currently have?"</div>
    <div class="script-line accent">"How do you handle fee collection right now — do parents pay at the office?"</div>
    <div class="script-line accent">"Do teachers fill in attendance registers manually?"</div>
    <div class="script-line accent">"When it comes to exams, how do you generate report cards?"</div>
    <div class="script-note">
      <i class="fas fa-info-circle"></i>
      Ask one question at a time. The more they share, the more you can connect your solution to their actual situation. The pain point they mention is your entry point.
    </div>
  </div>
</div>

<!-- STEP 4 — PITCH -->
<div class="script-card">
  <div class="script-card-header">
    <span class="stage-badge" style="background:var(--ap-green)">Step 4</span>
    <i class="fas fa-lightbulb" style="color:var(--ap-green)"></i>
    The Pitch — Tie It to What They Just Told You
  </div>
  <div class="script-card-body">
    <div class="objection-label">If fees are a problem:</div>
    <div class="script-line green">
      "That's exactly what we've helped other schools with. We have a system where parents pay directly through M-Pesa and it records automatically — the school can see who has paid and who hasn't in real time, without anyone having to chase anyone."
    </div>
    <div class="objection-label">If attendance is manual:</div>
    <div class="script-line green">
      "We have a module where teachers mark attendance on their phone or computer and it's saved instantly. Parents also get an SMS if their child is absent."
    </div>
    <div class="objection-label">If exams/report cards are manual:</div>
    <div class="script-line green">
      "Teachers enter marks once and the system generates all the report cards automatically — no more filling them by hand."
    </div>
    <div class="objection-label">If generally interested:</div>
    <div class="script-line green">
      "It basically replaces all the paperwork — admissions, fees, attendance, exams, homework, even library records — everything is in one place and the Director can see everything from their phone."
    </div>
    <div class="script-note">
      <i class="fas fa-info-circle"></i>
      Only use the lines that match what they told you. Do not list every feature — pick the one that solves their problem.
    </div>
  </div>
</div>

<!-- STEP 5 — OBJECTIONS -->
<div class="script-card">
  <div class="script-card-header">
    <span class="stage-badge" style="background:#e67e22">Step 5</span>
    <i class="fas fa-comments" style="color:#e67e22"></i>
    Handling Objections
  </div>
  <div class="script-card-body">

    <div class="objection-label">"We already have a system"</div>
    <div class="script-line accent">
      "That's good to hear — can I ask what system you use? The reason I ask is some schools have a system but it doesn't cover everything, especially M-Pesa integration or CBC assessments. If yours covers all of that, I won't waste your time. But if there are gaps, it might be worth a quick look."
    </div>

    <div class="objection-label">"We are not interested"</div>
    <div class="script-line accent">
      "I completely understand. Can I just ask — is it that you feel you don't need it right now, or is there something specific? I just want to make sure I'm not missing something."
    </div>

    <div class="objection-label">"How much does it cost?"</div>
    <div class="script-line accent">
      "The pricing depends on the size of the school and which features make sense for you. That's actually easier to explain in person because I can show you exactly what you'd be getting. It won't take long."
    </div>

    <div class="objection-label">"We don't have budget right now"</div>
    <div class="script-line accent">
      "I hear that — a lot of schools say that. What I usually find is that once they see how much time and money leaks through manual processes, the system actually pays for itself. Would you be open to just seeing it first, no commitment?"
    </div>

    <div class="objection-label">"Call back later / We're busy"</div>
    <div class="script-line accent">
      "Of course — when would be a good time? I want to make sure I call when you can actually give it a few minutes."
    </div>

    <div class="script-note" style="margin-top:8px">
      <i class="fas fa-info-circle"></i>
      Never argue with an objection. Acknowledge it first, then ask a gentle follow-up question. If they are firm, respect it and ask when to call back.
    </div>
  </div>
</div>

<!-- STEP 6 — CLOSE -->
<div class="script-card">
  <div class="script-card-header">
    <span class="stage-badge" style="background:var(--ap-navy2)">Step 6</span>
    <i class="fas fa-flag-checkered" style="color:var(--ap-accent)"></i>
    Close — Book the Visit
  </div>
  <div class="script-card-body">
    <div class="script-line">
      "I'm actually in [area] this week. It would only take about 20 minutes for me to come and show you how it works — I'll bring it on a laptop so you can see a real school using it. Would [suggest a day] work for you, or is there a better day?"
    </div>
    <div class="objection-label">If they agree:</div>
    <div class="script-line green">
      "Perfect. I'll come by on [day] at [time]. I'll also send you a message the day before to confirm. Thank you so much — I look forward to meeting you."
    </div>
    <div class="objection-label">If they are not ready:</div>
    <div class="script-line accent">
      "No problem. Let me leave you my number — [your number] — and I'll follow up with you in a few days. Thank you for your time."
    </div>
    <div class="script-note">
      <i class="fas fa-info-circle"></i>
      Always end with a specific next step — a visit date, a call-back date, or permission to follow up. Never end with "okay goodbye."
    </div>
  </div>
</div>

<!-- AFTER THE CALL -->
<div class="ap-card" style="border-left:3px solid var(--ap-green);margin-bottom:24px">
  <div class="ap-card-header"><i class="fas fa-clipboard-check me-2" style="color:var(--ap-green)"></i>After Every Call — Log It Immediately</div>
  <div class="ap-card-body" style="font-size:.86rem;line-height:2.1">
    <ul style="margin:0;padding-left:18px">
      <li>✅ Visit booked → set the visit date in your portal</li>
      <li>📞 Call back → note who to ask for and the agreed time</li>
      <li>❌ Not interested → note the reason in case things change later</li>
      <li>📵 No answer → set a follow-up to try again in 1–2 days</li>
    </ul>
  </div>
</div>

</div><!-- end #script-phone -->

<!-- ═══════════════════════════════════════════════════════ -->
<!-- VISIT SCRIPT (existing) -->
<!-- ═══════════════════════════════════════════════════════ -->
<div id="script-visit" style="display:none">

<div class="tip-box">
  <i class="fas fa-lightbulb"></i>
  <strong>Key mindset:</strong> You are not selling you are helping a school discover whether CST SchoolHub solves a problem they already have.
  Listen more than you speak. Let the school convince themselves.
</div>

<!-- 1. OPENING -->
<div class="script-card">
  <div class="script-card-header">
    <span class="stage-badge" style="background:var(--ap-navy)">Step 1</span>
    <i class="fas fa-handshake" style="color:var(--ap-accent)"></i>
    Opening When You Meet the Principal or Administrator
  </div>
  <div class="script-card-body">
    <div class="script-line">
      "Good morning / good afternoon. My name is [Your Name], I work with CST SchoolHub.
      We help schools here in Kenya manage their day-to-day operations things like student records,
      fees, staff, and communication with parents all in one place.
      I am not here to sell you anything today. I just wanted to learn how your school currently
      handles these things, and share what we do in case it is useful to you."
    </div>
    <div class="script-note">
      <i class="fas fa-info-circle"></i>
      Keep your tone calm and warm. Shake hands, smile. Sit down only when invited. Do not rush into the pitch.
    </div>
  </div>
</div>

<!-- 2. DISCOVERY -->
<div class="script-card">
  <div class="script-card-header">
    <span class="stage-badge" style="background:#2980b9">Step 2</span>
    <i class="fas fa-ear-listen" style="color:#2980b9"></i>
    Discovery Let Them Talk
  </div>
  <div class="script-card-body">
    <div class="script-line accent">
      "How are you currently managing student fees and records is it mostly manual or do you use any system?"
    </div>
    <div class="script-line accent">
      "What takes up most of your administration time every week?"
    </div>
    <div class="script-line accent">
      "Have you had any challenges maybe with tracking payments, parent communication, or generating reports for the board?"
    </div>
    <div class="script-note">
      <i class="fas fa-info-circle"></i>
      <span>Ask one question at a time. <strong>Listen fully before responding.</strong>
      Take notes this shows respect and helps you tailor what you say next.
      The pain point they mention is your entry point.</span>
    </div>
  </div>
</div>

<!-- 3. INTRODUCING -->
<div class="script-card">
  <div class="script-card-header">
    <span class="stage-badge" style="background:#8e44ad">Step 3</span>
    <i class="fas fa-lightbulb" style="color:#8e44ad"></i>
    Introducing the System Only After They Share a Problem
  </div>
  <div class="script-card-body">
    <div class="script-line">
      "That is actually one of the things schools tell us most. What CST SchoolHub does is bring all of
      that into one simple system. Fees are tracked automatically, parents receive SMS notifications,
      teachers can mark attendance and enter grades from their phone, and you can pull any report in seconds —
      without touching a single spreadsheet."
    </div>
    <div class="script-line">
      "It is built specifically for Kenyan schools, so it follows CBC and the local school structure.
      Quite a number of schools are already using it."
    </div>
    <div class="script-note">
      <i class="fas fa-info-circle"></i>
      Only introduce the system after they have shared a problem. Never pitch before you listen.
    </div>
  </div>
</div>

<!-- 4. DEMO -->
<div class="script-card">
  <div class="script-card-header">
    <span class="stage-badge" style="background:var(--ap-green)">Step 4</span>
    <i class="fas fa-desktop" style="color:var(--ap-green)"></i>
    The Demo Offer, Never Push
  </div>
  <div class="script-card-body">
    <div class="script-line green">
      "I have a working demo I can show you it takes about ten minutes.
      Would that be okay, or would another time work better for you?"
    </div>
    <div class="script-note">
      <i class="fas fa-info-circle"></i>
      <span>Use the <strong>Demo Credentials</strong> in your portal to show a live school account.
      Let them click around. Ask: <em>"Does this look like it could work for your school?"</em></span>
    </div>
  </div>
</div>

<!-- 5. OBJECTIONS -->
<div class="script-card">
  <div class="script-card-header">
    <span class="stage-badge" style="background:#e67e22">Step 5</span>
    <i class="fas fa-comments" style="color:#e67e22"></i>
    Handling Common Responses
  </div>
  <div class="script-card-body">

    <div class="objection-label">If they say: "We cannot afford it right now"</div>
    <div class="script-line accent">
      "Understood completely. We have plans that start from a very affordable monthly rate,
      and there is a yearly option that reduces the cost significantly.
      But no pressure at all I can leave you the details and you can look at it
      when the timing makes sense for your school."
    </div>

    <div class="objection-label">If they say: "We already use something"</div>
    <div class="script-line accent">
      "That is good to hear it is always better to have something in place.
      May I ask is it handling everything you need, or are there still gaps?"
    </div>

    <div class="objection-label">If they say: "I need to consult the board / sponsor"</div>
    <div class="script-line accent">
      "Of course, that is exactly the right way to handle it.
      I can prepare a short summary you can share with them.
      What information would be most useful for them to see?"
    </div>

    <div class="objection-label">If they say: "We are not ready yet"</div>
    <div class="script-line accent">
      "No problem at all. When do you think would be a better time to revisit this?
      I can check back with you then there is no rush on our side."
    </div>

    <div class="script-note" style="margin-top:8px">
      <i class="fas fa-info-circle"></i>
      Never argue with an objection. Acknowledge it, then ask a gentle follow-up question.
    </div>
  </div>
</div>

<!-- 6. CLOSING -->
<div class="script-card">
  <div class="script-card-header">
    <span class="stage-badge" style="background:var(--ap-navy2)">Step 6</span>
    <i class="fas fa-flag-checkered" style="color:var(--ap-accent)"></i>
    Closing No Pressure
  </div>
  <div class="script-card-body">
    <div class="script-line">
      "I really appreciate your time today. I will leave you our information.
      If after looking through it you would like to see a full demo or get a quote for your specific school size,
      just reach out my contact is on the card."
    </div>
    <div class="script-line">
      "Is it okay if I follow up with you in [one week / two weeks] to hear your thoughts?"
    </div>
    <div class="script-note">
      <i class="fas fa-info-circle"></i>
      Always leave with a specific follow-up date agreed upon. Log the visit in your portal before you leave the school compound.
    </div>
  </div>
</div>

<!-- 7. FOLLOW-UP CALL -->
<div class="script-card">
  <div class="script-card-header">
    <span class="stage-badge" style="background:#16a085">Follow-Up</span>
    <i class="fas fa-phone" style="color:#16a085"></i>
    Follow-Up Call Opener
  </div>
  <div class="script-card-body">
    <div class="script-line green">
      "Good morning [Name], this is [Your Name] from CST SchoolHub.
      I visited your school last [week / month] I just wanted to check whether
      you had a chance to look at what we shared and if you had any questions I can help with."
    </div>
    <div class="script-note">
      <i class="fas fa-info-circle"></i>
      Keep follow-up calls short. If they are not ready, ask when to call back and log it. Never call more than twice a week.
    </div>
  </div>
</div>

<!-- 8. PRINCIPAL NOT IN -->
<div class="script-card">
  <div class="script-card-header">
    <span class="stage-badge" style="background:#7f8c8d">Situation</span>
    <i class="fas fa-door-open" style="color:#7f8c8d"></i>
    When the Principal is Not Available
  </div>
  <div class="script-card-body">
    <div class="script-line">
      "No problem at all. My name is [Your Name] from CST SchoolHub — we work with schools on
      digital management systems. Could I leave some information for the principal?
      I would also really appreciate if you could let them know I came by.
      When would be the best time for me to come back and meet them briefly?"
    </div>
    <div class="script-note">
      <i class="fas fa-info-circle"></i>
      <span>Leave the brochure with the secretary or deputy. Be polite and warm — the secretary often influences
      the principal's first impression of you. Get a specific day and time to return, and log it in your portal immediately.</span>
    </div>
  </div>
</div>

<!-- 9. PRICING CONVERSATION -->
<div class="script-card">
  <div class="script-card-header">
    <span class="stage-badge" style="background:#c0392b">Step 8</span>
    <i class="fas fa-tag" style="color:#c0392b"></i>
    When They Ask "How Much Does It Cost?"
  </div>
  <div class="script-card-body">
    <div class="script-line">
      "It depends on the size of your school — we have plans designed for different school sizes
      so you only pay for what you need. For a school your size, it would be around
      KES [Basic: 3,000 / Standard: 5,000 / Premium: 20,000] per month.
      That covers everything — all the modules, SMS notifications, support, and updates.
      No hidden charges."
    </div>
    <div class="script-line accent">
      "And if you pay yearly, you save two months — so you get twelve months of the system
      for the price of ten. Many schools prefer that option."
    </div>
    <div class="script-note">
      <i class="fas fa-info-circle"></i>
      <span>State the price confidently — do not apologise for it or rush past it.
      Pause after saying it and let them respond. Do not fill the silence by offering a discount immediately.</span>
    </div>
  </div>
</div>

<!-- 10. EXTRA OBJECTIONS -->
<div class="script-card">
  <div class="script-card-header">
    <span class="stage-badge" style="background:#e67e22">More Responses</span>
    <i class="fas fa-comments" style="color:#e67e22"></i>
    More Common Situations
  </div>
  <div class="script-card-body">

    <div class="objection-label">If they ask: "How many schools are already using it?"</div>
    <div class="script-line accent">
      "We are actively growing across Kenya and schools in several counties are already on the platform.
      What I can tell you is that the schools using it have seen a big reduction in admin work —
      especially around fees and report generation.
      I would be happy to connect you with one of them if you would like to hear directly from another school."
    </div>

    <div class="objection-label">If they say: "We have no internet / poor connectivity"</div>
    <div class="script-line accent">
      "That is a concern we hear often and we have thought about it.
      The system works well on basic mobile data — teachers can use it on their phones without needing fast internet.
      For the office, a simple home router with a data bundle is usually enough.
      And if your school needs a computer and internet connection to get started,
      we can actually arrange that for you as an add-on — a computer and hotspot setup at an extra cost,
      so you have everything ready from day one."
    </div>

    <div class="objection-label">If they say: "We already use something else but it has gaps"</div>
    <div class="script-line accent">
      "That is actually the most common situation we see. A lot of schools are using one system for fees
      and something else for results — and nothing for parent communication.
      CST SchoolHub brings everything under one login, one place.
      Would it be useful if I showed you just the parts that cover those gaps?"
    </div>

    <div class="script-note" style="margin-top:8px">
      <i class="fas fa-info-circle"></i>
      Always acknowledge their concern first before responding. Never make them feel their objection was wrong.
    </div>
  </div>
</div>

<!-- 11. WHATSAPP TEMPLATES -->
<div class="script-card">
  <div class="script-card-header">
    <span class="stage-badge" style="background:#25d366">WhatsApp</span>
    <i class="fab fa-whatsapp" style="color:#25d366"></i>
    WhatsApp Message Templates — Copy and Send
  </div>
  <div class="script-card-body">

    <div class="objection-label">After a visit — same day</div>
    <div class="script-line green">
      "Good [morning/afternoon] [Name], this is [Your Name] from CST SchoolHub.
      It was a pleasure visiting [School Name] today.
      I have left our brochure with you — please take a look when you get a moment.
      Feel free to reach out if you have any questions.
      I will follow up with you on [agreed date]. Have a great day!"
    </div>

    <div class="objection-label">Follow-up after no response (3–5 days later)</div>
    <div class="script-line green">
      "Hello [Name], hope you are doing well.
      I am just following up on the CST SchoolHub information I shared with you during my visit.
      Have you had a chance to look through it?
      I am happy to arrange a short demo at your convenience — it takes about 10 minutes.
      Please let me know what works for you."
    </div>

    <div class="objection-label">When they are ready for a demo</div>
    <div class="script-line green">
      "That is great to hear! I can come by on [Day] at [Time] — does that work for you?
      I will bring everything needed to show you the full system live.
      Please make sure the principal or person who makes the decision is available if possible.
      Looking forward to it!"
    </div>

    <div class="script-note">
      <i class="fas fa-info-circle"></i>
      <span>Send WhatsApp messages during working hours — 8am to 6pm only.
      Use the school's official number if available, not a personal number.
      Keep messages short, professional and warm.</span>
    </div>
  </div>
</div>

<!-- GOLDEN RULES -->
<div class="ap-card" style="border-left:3px solid var(--ap-accent);margin-bottom:32px">
  <div class="ap-card-header"><i class="fas fa-star me-2" style="color:var(--ap-accent)"></i>Golden Rules to Always Remember</div>
  <div class="ap-card-body" style="font-size:.86rem;line-height:2.1">
    <ul style="margin:0;padding-left:18px">
      <li>Arrive on time and dressed professionally</li>
      <li>Always introduce yourself with your full name and company</li>
      <li>Ask questions first never pitch before you listen</li>
      <li>Speak clearly and at a calm pace confidence is not speed</li>
      <li>Never speak badly about other systems or competitors</li>
      <li>If you do not know the answer to a question, say "I will find out and get back to you"</li>
      <li>Always leave a business card or written contact</li>
      <li>Log every visit in your portal the same day</li>
      <li>A school that says no today can say yes in three months never burn a relationship</li>
    </ul>
  </div>
</div>

</div><!-- end #script-visit -->

<script>
function showScript(tab) {
  document.getElementById('script-phone').style.display = tab === 'phone' ? '' : 'none';
  document.getElementById('script-visit').style.display = tab === 'visit' ? '' : 'none';
  document.getElementById('tab-phone').classList.toggle('active-tab', tab === 'phone');
  document.getElementById('tab-visit').classList.toggle('active-tab', tab === 'visit');
}
</script>
