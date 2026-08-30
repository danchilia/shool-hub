<?php
// Role colours and labels
$roleConfig = array(
    'admin'      => array('label' => 'School Administrator',  'icon' => 'fas fa-user-shield',       'color' => '#1a5276', 'light' => '#eaf2f8'),
    'superadmin' => array('label' => 'Super Administrator',   'icon' => 'fas fa-crown',             'color' => '#512e5f', 'light' => '#f5eef8'),
    'teacher'    => array('label' => 'Teacher',               'icon' => 'fas fa-chalkboard-teacher', 'color' => '#117864', 'light' => '#e8f8f5'),
    'student'    => array('label' => 'Student',               'icon' => 'fas fa-user-graduate',     'color' => '#1f618d', 'light' => '#eaf2f8'),
    'parent'     => array('label' => 'Parent / Guardian',     'icon' => 'fas fa-users',             'color' => '#1e8449', 'light' => '#eafaf1'),
);
$cfg = $roleConfig[$role];
$c   = $cfg['color'];
$l   = $cfg['light'];
?>

<style>
.ug-wrap { max-width: 960px; margin: 0 auto; }
.ug-hero {
    background: <?=$c?>; color: #fff;
    border-radius: 10px; padding: 22px 28px; margin-bottom: 24px;
    display: flex; align-items: center; gap: 18px;
}
.ug-hero i { font-size: 2.4rem; opacity: .85; }
.ug-hero h2 { margin: 0 0 4px; font-size: 1.5rem; font-weight: 700; }
.ug-hero p  { margin: 0; opacity: .85; font-size: .92rem; }

.ug-section { margin-bottom: 10px; border-radius: 8px; overflow: hidden; border: 1.5px solid #e0e8f0; }
.ug-section-head {
    display: flex; align-items: center; gap: 12px; cursor: pointer;
    padding: 13px 18px; background: <?=$l?>;
    border: none; width: 100%; text-align: left;
    font-weight: 600; font-size: .95rem; color: <?=$c?>;
    transition: background .15s;
}
.ug-section-head:hover { filter: brightness(.96); }
.ug-section-head i.sec-icon { font-size: 1.1rem; width: 22px; text-align: center; }
.ug-section-head .ug-chevron { margin-left: auto; transition: transform .25s; font-size: .8rem; }
.ug-section-head.collapsed .ug-chevron { transform: rotate(-90deg); }
.ug-section-body { padding: 16px 20px 18px; background: #fff; border-top: 1.5px solid #e0e8f0; }

.ug-step-list { list-style: none; margin: 0 0 14px; padding: 0; }
.ug-step-list li {
    display: flex; gap: 12px; align-items: flex-start;
    padding: 7px 0; border-bottom: 1px solid #f4f6f8;
    font-size: .88rem; color: #2c3e50;
}
.ug-step-list li:last-child { border-bottom: none; }
.ug-step-num {
    flex-shrink: 0; width: 22px; height: 22px; border-radius: 50%;
    background: <?=$c?>; color: #fff; font-size: .72rem; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    margin-top: 1px;
}
.ug-step-text strong { display: block; font-weight: 600; color: #1a1d23; margin-bottom: 1px; }
.ug-step-text span   { color: #6b7280; font-size: .82rem; }

.ug-tip {
    background: #fffbeb; border: 1px solid #fde68a; border-radius: 6px;
    padding: 8px 12px; font-size: .82rem; color: #92400e; margin-bottom: 14px;
}
.ug-tip i { margin-right: 5px; }

.ug-go { display: inline-block; padding: 6px 18px; border-radius: 20px; font-size: .82rem;
    font-weight: 600; color: #fff; background: <?=$c?>; text-decoration: none; transition: opacity .15s; }
.ug-go:hover { opacity: .85; color: #fff; text-decoration: none; }
</style>

<div class="ug-wrap">

    <!-- Hero -->
    <div class="ug-hero">
        <i class="<?=$cfg['icon']?>"></i>
        <div>
            <h2><?=$cfg['label']?>'s Guide</h2>
            <p>Step-by-step instructions for every feature you use. Click any section to expand it.</p>
        </div>
    </div>

<?php /* ══════════════ ADMIN GUIDE ══════════════ */ if ($role === 'admin' || $role === 'superadmin'): ?>

    <?php
    $sections = array(
        array(
            'icon'  => 'fas fa-school',
            'title' => 'School Profile & Settings',
            'tip'   => 'Do this first — your school name and logo appear on every report card and receipt.',
            'steps' => array(
                array('Go to Settings', 'Click <strong>Settings → School Settings</strong> in the left sidebar.'),
                array('Fill school details', 'Enter the school name, address, phone, email, and motto.'),
                array('Upload your logo', 'Upload the school logo (PNG/JPG). It appears on report cards and receipts.'),
                array('Set currency', 'Choose KES (Kenyan Shilling) as your currency.'),
                array('Save', 'Click <strong>Update</strong> to save all changes.'),
            ),
            'url' => 'school_settings', 'btn' => 'Open School Settings',
        ),
        array(
            'icon'  => 'fas fa-user-graduate',
            'title' => 'Admitting Students',
            'tip'   => 'Assign each student to a class and section. You can also bulk-import via CSV.',
            'steps' => array(
                array('Go to Students', 'Click <strong>Student → Add Student</strong>.'),
                array('Fill student details', 'Enter first name, last name, date of birth, gender, and photo.'),
                array('Assign class & section', 'Select the class (e.g. Grade 5) and section (e.g. A).'),
                array('Add guardian', 'Enter parent/guardian name, phone, and email. They will receive an SMS login.'),
                array('Save', 'Click <strong>Save</strong>. The student gets a portal login automatically.'),
                array('Bulk import', 'For many students, use <strong>Student → CSV Import</strong>. Download the template, fill it, and upload.'),
            ),
            'url' => 'student/add', 'btn' => 'Add a Student',
        ),
        array(
            'icon'  => 'fas fa-money-bill-wave',
            'title' => 'Setting Up & Collecting Fees',
            'tip'   => 'Create fee types first, then group them with amounts, then allocate to classes.',
            'steps' => array(
                array('Create fee types', 'Go to <strong>Fees → Fee Types</strong>. Add each category: Tuition, Activity, Transport, etc.'),
                array('Create fee groups', 'Go to <strong>Fees → Fee Groups</strong>. Create a group (e.g. "Term 1 2026") and add each fee type with its amount.'),
                array('Allocate fees', 'Go to <strong>Fees → Fee Allocation</strong>. Assign the fee group to a whole class or individual students.'),
                array('Record payment', 'When a student pays, go to <strong>Fees → Collect Fees</strong>, search the student, and enter the amount paid.'),
                array('Print receipt', 'After collecting, click <strong>Print Receipt</strong> to give the parent a copy.'),
                array('View balance', 'Go to <strong>Fees → Invoice</strong> to see each student\'s balance.'),
            ),
            'url' => 'fees/type', 'btn' => 'Go to Fees',
        ),
        array(
            'icon'  => 'fas fa-chalkboard-teacher',
            'title' => 'Managing Staff',
            'tip'   => 'Create departments and designations before adding employees.',
            'steps' => array(
                array('Add departments', 'Go to <strong>Employee → Departments</strong>. Add departments like Science, Administration, etc.'),
                array('Add designations', 'Go to <strong>Employee → Designations</strong>. Add job titles: Class Teacher, Head of Department, etc.'),
                array('Add staff', 'Go to <strong>Employee → Add Employee</strong>. Fill in personal details, select department and designation.'),
                array('Assign class teacher', 'Go to <strong>Subjects → Assign Class Teacher</strong> to link a teacher to a specific class/section.'),
                array('Staff login', 'Teachers get a login to the portal to enter marks, CBC assessments, and attendance.'),
            ),
            'url' => 'employee/add', 'btn' => 'Add Staff',
        ),
        array(
            'icon'  => 'fas fa-clipboard-list',
            'title' => 'Exams & Report Cards',
            'tip'   => 'Create exam terms first, then mark distribution, then the exam itself.',
            'steps' => array(
                array('Create exam terms', 'Go to <strong>Exam → Exam Terms</strong>. Add Term 1, Term 2, Term 3.'),
                array('Mark distribution', 'Go to <strong>Exam → Mark Distribution</strong>. Define components: CAT (30 marks), End Term (70 marks).'),
                array('Create exam', 'Go to <strong>Exam → Create Exam</strong>. Link it to a term and a mark distribution.'),
                array('Teachers enter marks', 'Teachers go to <strong>Exam → Marks Entry</strong> and fill in each student\'s scores.'),
                array('Print report cards', 'Go to <strong>Exam → Report Card</strong>. Select the class and exam, then print.'),
            ),
            'url' => 'exam', 'btn' => 'Go to Exams',
        ),
        ...(!empty($is_uni) ? [] : [array(
            'icon'  => 'fas fa-book-open',
            'title' => 'CBC Curriculum Setup',
            'tip'   => 'This is needed for CBC assessments. The Kenya template pre-seeds all learning areas and strands.',
            'steps' => array(
                array('Check Learning Areas', 'Go to <strong>CBC → Learning Areas</strong>. Verify they are seeded (PP to Grade 12 levels).'),
                array('Check Strands', 'Go to <strong>CBC → Strands</strong>. Strands should be pre-filled from the template.'),
                array('Enter CBC Assessments', 'Go to <strong>CBC → Assessment Entry</strong>. Select class, learning area, and exam. Rate each student EE2–BE1.'),
                array('Holistic Profile', 'Go to <strong>CBC → Holistic Profile</strong>. Rate students on 7 competency domains per term.'),
                array('Portfolio', 'Go to <strong>CBC → Portfolio</strong>. Add evidence (photos/documents) of student learning.'),
                array('Print CBC Report Card', 'Go to <strong>CBC → Report Card</strong>. Select students and exam to print the grouped report.'),
                array('CBC Analytics', 'Go to <strong>CBC → Analytics</strong> to see class-wide competency distribution charts.'),
            ),
            'url' => 'cbc/learning_areas', 'btn' => 'Go to CBC',
        )]),
        array(
            'icon'  => 'fas fa-bullhorn',
            'title' => 'Notice Board & Communication',
            'tip'   => 'Use the notice board for school-wide announcements. Use SMS/email for urgent messages.',
            'steps' => array(
                array('Post a notice', 'Go to <strong>Notice Board → Add Notice</strong>. Set title, details, audience (All/Staff/Parents/Students), and expiry date.'),
                array('Send SMS', 'Go to <strong>Communication → SMS</strong>. Select recipients (all parents, a class, etc.) and type your message.'),
                array('Send email', 'Go to <strong>Communication → Email</strong> to send bulk emails to parents or staff.'),
                array('Internal messages', 'Use <strong>Communication → Messages</strong> to send messages directly to teachers or parents.'),
            ),
            'url' => 'noticeboard', 'btn' => 'Notice Board',
        ),
        array(
            'icon'  => 'fas fa-book',
            'title' => 'Library Management',
            'tip'   => 'Add books first, then students can request them from the portal.',
            'steps' => array(
                array('Add book categories', 'Go to <strong>Library → Book Categories</strong>. E.g. Fiction, Reference, Kiswahili.'),
                array('Add books', 'Go to <strong>Library → Add Book</strong>. Enter title, author, publisher, quantity.'),
                array('Issue a book', 'Go to <strong>Library → Issue Book</strong>. Search the student and select the book.'),
                array('Return a book', 'Go to <strong>Library → Return Book</strong>. Find the issued record and mark as returned.'),
            ),
            'url' => 'library', 'btn' => 'Go to Library',
        ),
    );
    ?>

<?php /* ══════════════ TEACHER GUIDE ══════════════ */ elseif ($role === 'teacher'): ?>
    <?php
    $sections = array(
        array(
            'icon'  => 'fas fa-calendar-check',
            'title' => 'Taking Daily Attendance',
            'tip'   => 'Take attendance every day for your assigned class. Parents see it in real time on the portal.',
            'steps' => array(
                array('Go to Attendance', 'Click <strong>Attendance → Student Attendance</strong> in the sidebar.'),
                array('Select class & date', 'Choose your class, section, and today\'s date.'),
                array('Mark each student', 'Click P (Present), A (Absent), L (Late), or E (Excused) for each student.'),
                array('Save', 'Click <strong>Save Attendance</strong>. Parents receive an SMS if their child is absent.'),
            ),
            'url' => 'attendance', 'btn' => 'Take Attendance',
        ),
        array(
            'icon'  => 'fas fa-marker',
            'title' => 'Entering Exam Marks',
            'tip'   => 'Marks are entered per subject per exam. The system calculates totals and grades automatically.',
            'steps' => array(
                array('Go to Marks Entry', 'Click <strong>Exam → Marks Entry</strong>.'),
                array('Select filters', 'Choose class, section, subject, and exam from the dropdowns.'),
                array('Enter marks', 'Type each student\'s score in the input boxes. You can see the max marks for each component.'),
                array('Save', 'Click <strong>Save Marks</strong>. The report card will update automatically.'),
                array('Tip', 'You can save partially and return later — previous entries are remembered.'),
            ),
            'url' => 'exam/marks', 'btn' => 'Enter Marks',
        ),
        array(
            'icon'  => 'fas fa-clipboard-check',
            'title' => 'CBC Assessment Entry',
            'tip'   => 'Rate each student on the 8-level KNEC scale: EE2, EE1, ME2, ME1, AE2, AE1, BE2, BE1.',
            'steps' => array(
                array('Go to Assessment', 'Click <strong>CBC → Assessment Entry</strong>.'),
                array('Select filters', 'Choose class, section, CBC exam, and learning area. Optionally narrow to a specific strand.'),
                array('Click Search', 'Your class list appears.'),
                array('Rate each student', 'Select a competency level (EE2–BE1) for each student. Add remarks if needed.'),
                array('Save', 'Click <strong>Save Assessment</strong>. Results appear on the CBC report card.'),
            ),
            'url' => 'cbc/assessment', 'btn' => 'CBC Assessment',
        ),
        array(
            'icon'  => 'fas fa-user-check',
            'title' => 'Holistic Development Profile',
            'tip'   => '7 domains covering the full learner — beyond academics. Rate once per term per student.',
            'steps' => array(
                array('Go to Holistic Profile', 'Click <strong>CBC → Holistic Profile</strong>.'),
                array('Select class, section, exam', 'Use the filter form and click <strong>Load Students</strong>.'),
                array('Click Enter Profile', 'Next to any student, click the blue <strong>Enter Profile</strong> button.'),
                array('Rate each indicator', 'For each of the 21 indicators across 7 domains, choose EE / ME / AE / BE. Add optional remarks.'),
                array('Save', 'Click <strong>Save Holistic Profile</strong>. Students can view their ratings on the portal.'),
            ),
            'url' => 'cbc/holistic', 'btn' => 'Holistic Profile',
        ),
        array(
            'icon'  => 'fas fa-folder-open',
            'title' => 'Portfolio Management',
            'tip'   => 'Upload photos, documents, or any evidence of student learning to build their digital portfolio.',
            'steps' => array(
                array('Go to Portfolio', 'Click <strong>CBC → Portfolio</strong>.'),
                array('Search a student', 'Use the student search to select who you\'re adding evidence for.'),
                array('Add entry', 'Click <strong>Add Portfolio Entry</strong>. Fill in the title, learning area, description.'),
                array('Upload evidence', 'Attach a photo or document (JPG, PNG, PDF). Max 5MB.'),
                array('Save', 'Click <strong>Save Entry</strong>. The student sees it on their portal under Portfolio.'),
            ),
            'url' => 'cbc/portfolio', 'btn' => 'Portfolio',
        ),
        array(
            'icon'  => 'fas fa-project-diagram',
            'title' => 'Project Assessment',
            'tip'   => 'Create a project, then enter individual student scores when they submit.',
            'steps' => array(
                array('Go to Projects', 'Click <strong>CBC → Projects</strong>.'),
                array('Create a project', 'Click <strong>Add Project</strong>. Enter name, learning area, max score, and due date.'),
                array('Enter scores', 'When students submit, click <strong>Enter Scores</strong> next to the project.'),
                array('Rate & remark', 'Enter each student\'s score, competency level, and optional remarks.'),
                array('Save', 'Click <strong>Save Scores</strong>. Students see results on their portal.'),
            ),
            'url' => 'cbc/projects', 'btn' => 'Projects',
        ),
        array(
            'icon'  => 'fas fa-book-reader',
            'title' => 'Assigning Homework',
            'tip'   => 'Students see homework on their dashboard and portal. Attach files if needed.',
            'steps' => array(
                array('Go to Homework', 'Click <strong>Academic → Homework</strong>.'),
                array('Add homework', 'Click <strong>Add Homework</strong>. Select class, subject, and due date.'),
                array('Write instructions', 'Describe the task clearly. Attach a PDF or image if needed.'),
                array('Save', 'Click <strong>Save</strong>. Students see it immediately on their portal.'),
            ),
            'url' => 'homework', 'btn' => 'Homework',
        ),
        array(
            'icon'  => 'fas fa-calendar-alt',
            'title' => 'Viewing Your Timetable',
            'tip'   => 'Your class timetable is set by the admin. Contact admin if it looks wrong.',
            'steps' => array(
                array('Go to Timetable', 'Click <strong>Academic → Class Schedule</strong>.'),
                array('Select your class', 'Pick your class and section from the dropdown.'),
                array('View schedule', 'See all periods across the week for that class.'),
            ),
            'url' => 'timetable', 'btn' => 'View Timetable',
        ),
    );
    ?>

<?php /* ══════════════ STUDENT GUIDE ══════════════ */ elseif ($role === 'student'): ?>
    <?php
    $sections = array(
        array(
            'icon'  => 'fas fa-tachometer-alt',
            'title' => 'Your Dashboard',
            'tip'   => 'The dashboard shows your latest results, attendance, fee balance, and upcoming exams at a glance.',
            'steps' => array(
                array('Open dashboard', 'Click the <strong>Dashboard</strong> link in the left sidebar.'),
                array('Check widgets', 'See your attendance %, fee balance, latest exam results, and notice board.'),
                array('Quick links', 'Use the cards to jump directly to your report card, timetable, or homework.'),
            ),
            'url' => 'dashboard', 'btn' => 'Go to Dashboard',
        ),
        array(
            'icon'  => 'fas fa-file-alt',
            'title' => 'Viewing Your Report Card',
            'tip'   => 'You can view and print your report card for any exam at any time.',
            'steps' => array(
                array('Go to Report Card', 'Click <strong>Exam → Report Card</strong> in the sidebar.'),
                array('Select exam', 'Choose the exam from the dropdown (e.g. Term 1 2026).'),
                array('View & print', 'Click <strong>View Report Card</strong>. Use <strong>Print</strong> to get a PDF copy.'),
            ),
            'url' => 'userrole/report_card', 'btn' => 'View Report Card',
        ),
        ...(!empty($is_uni) ? [] : [
        array(
            'icon'  => 'fas fa-clipboard-check',
            'title' => 'CBC Progress Report',
            'tip'   => 'Shows your competency levels across all learning areas using the 8-level KNEC scale.',
            'steps' => array(
                array('Go to CBC Report', 'Click <strong>Exam → CBC Report</strong> in the sidebar.'),
                array('Select term/exam', 'Choose the CBC exam to view (e.g. Term 2 2026).'),
                array('Read your levels', 'EE2 is the highest (Exceeding Expectations, Advanced). BE1 is the lowest. Aim for ME and above.'),
                array('Understand ratings', 'EE = Exceeding · ME = Meeting · AE = Approaching · BE = Below Expectations.'),
            ),
            'url' => 'userrole/cbc_report', 'btn' => 'CBC Report',
        ),
        array(
            'icon'  => 'fas fa-user-check',
            'title' => 'Holistic Development Profile',
            'tip'   => 'Your teacher rates you on 7 life skills every term. These appear on your report card.',
            'steps' => array(
                array('Go to Holistic Profile', 'Click <strong>Exam → Holistic Profile</strong> in the sidebar.'),
                array('View by term', 'Each term\'s ratings are shown in a separate panel.'),
                array('7 domains', 'Communication, Creativity, Critical Thinking, Citizenship, Digital Literacy, Learning to Learn, Physical Health.'),
            ),
            'url' => 'userrole/holistic', 'btn' => 'Holistic Profile',
        ),
        array(
            'icon'  => 'fas fa-folder-open',
            'title' => 'My Portfolio',
            'tip'   => 'Your teacher uploads evidence of your best work here — photos, projects, achievements.',
            'steps' => array(
                array('Go to Portfolio', 'Click <strong>Exam → Portfolio</strong> in the sidebar.'),
                array('Browse entries', 'Each card shows a piece of evidence your teacher added — title, learning area, and competency level.'),
                array('View evidence', 'Click <strong>View Evidence</strong> on a card to open any attached file.'),
            ),
            'url' => 'userrole/portfolio', 'btn' => 'My Portfolio',
        ),
        ]),
        array(
            'icon'  => 'fas fa-chart-bar',
            'title' => 'Attendance Record',
            'tip'   => 'Check your attendance percentage and which days you were absent.',
            'steps' => array(
                array('Go to Attendance', 'Click <strong>Attendance</strong> in the sidebar.'),
                array('View record', 'See a calendar/list of present, absent, and late days.'),
                array('Apply for leave', 'If you need time off, click <strong>Leave → Apply for Leave</strong> and submit a request.'),
            ),
            'url' => 'userrole/attendance', 'btn' => 'My Attendance',
        ),
        array(
            'icon'  => 'fas fa-receipt',
            'title' => 'Fee Balance & Invoices',
            'tip'   => 'Check what you owe and what has been paid. Share the invoice with your parent if needed.',
            'steps' => array(
                array('Go to Fees', 'Click <strong>Fees History</strong> in the sidebar.'),
                array('View invoice', 'See all fee lines, amounts due, and payments received.'),
                array('Outstanding balance', 'The balance at the bottom shows what is still unpaid.'),
            ),
            'url' => 'userrole/invoice', 'btn' => 'Fee Invoice',
        ),
        array(
            'icon'  => 'fas fa-book',
            'title' => 'Library — Borrow Books',
            'tip'   => 'Browse available books and request the ones you need. The librarian will prepare them for you.',
            'steps' => array(
                array('Go to Library', 'Click <strong>Library</strong> in the sidebar.'),
                array('Browse books', 'Click <strong>Books List</strong> to see all available titles.'),
                array('Request a book', 'Click <strong>Request</strong> next to a book. The librarian approves and issues it.'),
                array('Check issued books', 'Click <strong>Issued Book</strong> to see books currently borrowed by you.'),
            ),
            'url' => 'userrole/book', 'btn' => 'Library',
        ),
        array(
            'icon'  => 'fas fa-dna',
            'title' => 'Class Timetable',
            'tip'   => 'See your full weekly timetable including all subjects and periods.',
            'steps' => array(
                array('Go to Academic', 'Click <strong>Academic → Class Schedule</strong> in the sidebar.'),
                array('View timetable', 'Your weekly timetable is shown with subjects, rooms, and teachers per period.'),
            ),
            'url' => 'userrole/class_schedule', 'btn' => 'View Timetable',
        ),
    );
    ?>

<?php /* ══════════════ PARENT GUIDE ══════════════ */ else: ?>
    <?php
    $sections = array(
        array(
            'icon'  => 'fas fa-child',
            'title' => 'Switching Between Children',
            'tip'   => 'If you have more than one child in this school, you can switch between them from the dashboard.',
            'steps' => array(
                array('Go to My Children', 'Click <strong>Dashboard → My Children</strong> in the sidebar.'),
                array('Select a child', 'Click your child\'s name to switch to their profile.'),
                array('Navigate', 'All pages now show data for the selected child.'),
            ),
            'url' => 'parents/my_children', 'btn' => 'My Children',
        ),
        array(
            'icon'  => 'fas fa-file-alt',
            'title' => "Child's Report Card",
            'tip'   => 'View and print your child\'s academic report card for any exam.',
            'steps' => array(
                array('Select your child', 'First switch to your child\'s profile (see above).'),
                array('Go to Report Card', 'Click <strong>Exam → Report Card</strong>.'),
                array('Select exam', 'Choose the exam term from the dropdown.'),
                array('Print', 'Click <strong>Print</strong> to save or print the report card.'),
            ),
            'url' => 'userrole/report_card', 'btn' => 'Report Card',
        ),
        array(
            'icon'  => 'fas fa-clipboard-check',
            'title' => 'CBC Progress Report',
            'tip'   => 'Understand your child\'s competency levels across all CBC learning areas.',
            'steps' => array(
                array('Go to CBC Report', 'Click <strong>Exam → CBC Report</strong>.'),
                array('Read the levels', 'EE = Exceeding Expectations (best) · ME = Meeting · AE = Approaching · BE = Below.'),
                array('Talk to teacher', 'If your child has many AE or BE ratings, speak with their class teacher.'),
            ),
            'url' => 'userrole/cbc_report', 'btn' => 'CBC Report',
        ),
        array(
            'icon'  => 'fas fa-user-check',
            'title' => 'Holistic Development Profile',
            'tip'   => "See how your child is developing beyond academics — 7 life competency domains.",
            'steps' => array(
                array('Go to Holistic Profile', 'Click <strong>Exam → Holistic Profile</strong>.'),
                array('View by term', 'Each term\'s ratings appear in a panel.'),
                array('7 domains covered', 'Communication, Creativity, Critical Thinking, Citizenship, Digital Literacy, Learning to Learn, Physical Health.'),
            ),
            'url' => 'userrole/holistic', 'btn' => 'Holistic Profile',
        ),
        array(
            'icon'  => 'fas fa-folder-open',
            'title' => 'Portfolio & Projects',
            'tip'   => "Evidence your child's teacher has collected of their best learning moments.",
            'steps' => array(
                array('Go to Portfolio', 'Click <strong>Exam → Portfolio</strong>.'),
                array('View evidence', 'Browse all entries your child\'s teacher has added — photos, documents, project work.'),
                array('Project scores', 'Scroll down to see how your child scored on class projects.'),
            ),
            'url' => 'userrole/portfolio', 'btn' => 'Portfolio',
        ),
        array(
            'icon'  => 'fas fa-money-check-alt',
            'title' => 'Fee Balance & Payments',
            'tip'   => 'Always know exactly what is owed before visiting the school.',
            'steps' => array(
                array('Go to Fees', 'Click <strong>Fees History</strong> in the sidebar.'),
                array('Check balance', 'The invoice shows each fee line, amount due, and how much has been paid.'),
                array('Outstanding amount', 'The balance at the bottom shows what still needs to be paid.'),
                array('Contact bursar', 'Call the school bursar with the reference number shown on the invoice when paying.'),
            ),
            'url' => 'userrole/invoice', 'btn' => 'Fee Invoice',
        ),
        array(
            'icon'  => 'fas fa-calendar-check',
            'title' => "Child's Attendance",
            'tip'   => 'You receive an SMS when your child is marked absent. Check the full record here.',
            'steps' => array(
                array('Go to Attendance', 'Click <strong>Attendance</strong> in the sidebar.'),
                array('View history', 'See a full list of all days: Present, Absent, Late, or Excused.'),
                array('Contact school', 'If an absence is incorrect, contact the class teacher directly.'),
            ),
            'url' => 'userrole/attendance', 'btn' => 'Attendance',
        ),
        array(
            'icon'  => 'fas fa-envelope',
            'title' => 'Messages & Communication',
            'tip'   => 'Send messages directly to teachers or admin from within the portal.',
            'steps' => array(
                array('Go to Messages', 'Click <strong>Message</strong> in the sidebar.'),
                array('Compose', 'Click <strong>Compose</strong>, select the recipient (teacher or admin), and type your message.'),
                array('Send', 'Click <strong>Send</strong>. They will see your message when they log in.'),
            ),
            'url' => 'communication/mailbox/inbox', 'btn' => 'Messages',
        ),
    );
    ?>
<?php endif; ?>

    <!-- Render sections -->
    <?php foreach ($sections as $i => $sec): ?>
    <div class="ug-section">
        <button class="ug-section-head <?=$i===0?'':'collapsed'?>" data-ugs="ugs<?=$i?>">
            <i class="<?=$sec['icon']?> sec-icon"></i>
            <?=htmlspecialchars($sec['title'])?>
            <i class="fas fa-chevron-down ug-chevron"></i>
        </button>
        <div id="ugs<?=$i?>" class="ug-body" style="display:<?=$i===0?'block':'none'?>">
            <div class="ug-section-body">
                <?php if (!empty($sec['tip'])): ?>
                <div class="ug-tip"><i class="fas fa-lightbulb"></i><?=htmlspecialchars($sec['tip'])?></div>
                <?php endif; ?>

                <ul class="ug-step-list">
                <?php foreach ($sec['steps'] as $n => $step): ?>
                    <li>
                        <div class="ug-step-num"><?=$n+1?></div>
                        <div class="ug-step-text">
                            <strong><?=htmlspecialchars($step[0])?></strong>
                            <span><?=$step[1]?></span>
                        </div>
                    </li>
                <?php endforeach; ?>
                </ul>

                <a href="<?=base_url($sec['url'])?>" class="ug-go">
                    <i class="fas fa-arrow-right" style="margin-right:6px;font-size:.8rem;"></i><?=htmlspecialchars($sec['btn'])?>
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

</div>

<script>
document.querySelectorAll('.ug-section-head').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var body = document.getElementById(this.getAttribute('data-ugs'));
        var open = body.style.display !== 'none';
        body.style.display = open ? 'none' : 'block';
        this.classList.toggle('collapsed', open);
    });
});
</script>
