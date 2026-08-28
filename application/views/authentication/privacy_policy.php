<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <title>Privacy Policy — <?php echo isset($global_config['institute_name']) ? html_escape($global_config['institute_name']) : 'DCK Solutions'; ?></title>
    <link rel="shortcut icon" href="<?php echo base_url('assets/images/favicon.png');?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/bootstrap/css/bootstrap.css');?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/font-awesome/css/all.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/login_page/css/style.css');?>">
    <style>
        body { background: #f4f6f9; }
        .pp-wrap { max-width: 820px; margin: 40px auto; background: #fff; border-radius: 8px; padding: 40px 48px; box-shadow: 0 2px 12px rgba(0,0,0,.08); }
        .pp-wrap h1 { font-size: 26px; font-weight: 700; margin-bottom: 6px; }
        .pp-wrap .updated { font-size: 13px; color: #888; margin-bottom: 28px; }
        .pp-wrap h2 { font-size: 17px; font-weight: 600; margin-top: 28px; margin-bottom: 8px; }
        .pp-wrap p, .pp-wrap li { font-size: 14px; line-height: 1.75; color: #444; }
        .pp-wrap ul { padding-left: 20px; }
        .back-link { display: inline-block; margin-top: 32px; font-size: 13px; }
        @media (max-width: 600px) { .pp-wrap { padding: 24px 18px; } }
    </style>
</head>
<body>
<div class="pp-wrap">
    <h1>Privacy Policy</h1>
    <p class="updated">Last updated: <?php echo date('F j, Y'); ?></p>

    <p>
        This Privacy Policy explains how <strong>DCK Solutions</strong> ("<strong>we</strong>", "<strong>us</strong>", "<strong>our</strong>")
        collects, uses, stores, and protects personal data through the School Management System ("<strong>System</strong>") operated on behalf of
        client schools. It applies to students, parents/guardians, staff, and all other data subjects whose information is processed through the System.
        We operate in compliance with the <strong>Kenya Data Protection Act, 2019</strong> (No. 24 of 2019) and its subsidiary regulations.
    </p>

    <h2>1. Data Controller</h2>
    <p>
        Each client school using the System is the <strong>Data Controller</strong> for the personal data of its students, parents, and staff.
        DCK Solutions acts as a <strong>Data Processor</strong> on behalf of each school. Contact your school's administration for data-related requests.
    </p>

    <h2>2. Personal Data We Collect</h2>
    <ul>
        <li><strong>Students:</strong> Name, date of birth, gender, contact details, class enrolment, attendance records, academic results, and CBC assessment data.</li>
        <li><strong>Parents / Guardians:</strong> Name, national ID, relationship to student, phone number, and email address.</li>
        <li><strong>Staff:</strong> Name, staff ID, role, contact details, employment information, and payroll data.</li>
        <li><strong>Health Data (Special Category):</strong> Blood group, allergies, chronic conditions, disabilities, vaccination records, and clinic visit records. This data is encrypted at rest and accessible only to authorised health staff.</li>
        <li><strong>Financial Data:</strong> Fee payment records, invoice history, and M-Pesa transaction references. Card payments are processed through Stripe; no card numbers are stored on our servers.</li>
        <li><strong>Biometric Data (where enabled):</strong> Fingerprint attendance records. Stored only in hashed/tokenised form.</li>
    </ul>

    <h2>3. Purposes of Processing</h2>
    <ul>
        <li>Administration of academic records, enrolment, and timetabling.</li>
        <li>Fee collection and financial reporting.</li>
        <li>Attendance tracking and reporting to parents/guardians.</li>
        <li>Health monitoring and emergency contact management.</li>
        <li>Communication of notices, results, and school events via SMS and email.</li>
        <li>Compliance with Kenya's national education reporting requirements (NEMIS, KNEC, CBC).</li>
    </ul>

    <h2>4. Legal Basis for Processing</h2>
    <p>
        Processing is carried out on the basis of: (a) performance of a contract (student enrolment); (b) compliance with a legal obligation
        (NEMIS reporting, KNEC examinations); (c) legitimate interests of the school; and (d) consent, where required, including for bulk
        SMS/email communications.
    </p>

    <h2>5. Communication Opt-Out</h2>
    <p>
        Parents and guardians may opt out of bulk SMS or email communications at any time by contacting the school administration.
        Operational messages (fee invoices, examination timetables, emergency alerts) may still be sent regardless of opt-out status
        as they relate to the performance of the enrolment contract.
    </p>

    <h2>6. Data Sharing</h2>
    <p>
        We do not sell personal data. Data may be shared with: Safaricom (M-Pesa payment processing), Stripe (card payment processing),
        SMS gateway providers (for SMS delivery), and Kenya government bodies (NEMIS, KNEC) as required by law.
        All third-party processors are bound by data processing agreements.
    </p>

    <h2>7. Data Retention</h2>
    <p>
        Student academic records are retained for a minimum of 7 years after graduation in accordance with Kenya's Education Act.
        Health records are retained for 10 years. Financial records are retained for 7 years under the Income Tax Act.
        Data is securely deleted upon expiry of the retention period.
    </p>

    <h2>8. Data Subject Rights</h2>
    <p>Under the Kenya Data Protection Act, 2019, you have the right to:</p>
    <ul>
        <li>Access your personal data held by the school.</li>
        <li>Correct inaccurate or incomplete data.</li>
        <li>Request deletion of data (subject to legal retention requirements).</li>
        <li>Object to or restrict certain types of processing.</li>
        <li>Lodge a complaint with the <strong>Office of the Data Protection Commissioner (ODPC)</strong> at <em>www.odpc.go.ke</em>.</li>
    </ul>

    <h2>9. Security</h2>
    <p>
        We implement technical safeguards including: AES-256 encryption for sensitive health and financial credentials at rest;
        HTTPS in transit; session-based authentication; role-based access control; and regular security reviews.
        Despite these measures, no system is completely immune to risk. We will notify affected parties promptly in the event of a data breach
        as required by Section 43 of the Kenya Data Protection Act, 2019.
    </p>

    <h2>10. Cookies</h2>
    <p>
        The System uses a single session cookie to maintain your login session. No tracking or advertising cookies are used.
    </p>

    <h2>11. Changes to This Policy</h2>
    <p>
        We may update this policy from time to time. Material changes will be communicated through the System or by email.
        The "Last updated" date at the top of this page indicates when the policy was last revised.
    </p>

    <h2>12. Contact</h2>
    <p>
        For data-related enquiries, contact your school's administration or reach DCK Solutions at
        <strong>danchilia16@gmail.com</strong>.
    </p>

    <a href="<?php echo base_url('authentication'); ?>" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to Login
    </a>
</div>
</body>
</html>
