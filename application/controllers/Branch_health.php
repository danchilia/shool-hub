<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Branch_health extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!is_superadmin_loggedin()) {
            redirect(base_url('dashboard'));
        }
    }

    public function check($branchId = '')
    {
        if (empty($branchId)) {
            redirect(base_url('branch'));
        }

        $branch = $this->db->get_where('branch', array('id' => $branchId))->row_array();
        if (!$branch) {
            show_404();
        }

        $sessionId = get_session_id();
        $checks = array();

        // 1. SCHOOL INFO
        $checks['school_info'] = array(
            'title' => 'School Information',
            'icon' => 'fas fa-school',
            'items' => array()
        );
        $checks['school_info']['items'][] = $this->checkItem('School Name', !empty($branch['school_name']), $branch['school_name']);
        $checks['school_info']['items'][] = $this->checkItem('Email', !empty($branch['email']), $branch['email']);
        $checks['school_info']['items'][] = $this->checkItem('Phone Number', !empty($branch['mobileno']), $branch['mobileno']);
        $checks['school_info']['items'][] = $this->checkItem('Address', !empty($branch['address']), $branch['address']);
        $checks['school_info']['items'][] = $this->checkItem('Currency', !empty($branch['currency']), $branch['currency'] . ' (' . $branch['symbol'] . ')');

        // 2. ACADEMIC SETUP
        $checks['academic'] = array(
            'title' => 'Academic Setup',
            'icon' => 'fas fa-graduation-cap',
            'items' => array()
        );
        $classCount = $this->db->where('branch_id', $branchId)->count_all_results('class');
        $sectionCount = $this->db->where('branch_id', $branchId)->count_all_results('section');
        $subjectCount = $this->db->where('branch_id', $branchId)->count_all_results('subject');
        $learningAreaCount = $this->db->where('branch_id', $branchId)->count_all_results('cbc_learning_areas');
        $strandCount = $this->db->where('branch_id', $branchId)->count_all_results('cbc_strands');
        $termCount = $this->db->where('branch_id', $branchId)->count_all_results('exam_term');
        $gradeCount = $this->db->where('branch_id', $branchId)->count_all_results('grade');
        $markDistCount = $this->db->where('branch_id', $branchId)->count_all_results('exam_mark_distribution');
        $hallCount = $this->db->where('branch_id', $branchId)->count_all_results('exam_hall');

        $checks['academic']['items'][] = $this->checkItem('Classes Created', $classCount > 0, $classCount . ' classes', $classCount >= 10 ? '' : 'Kenyan schools need at least PP1-Form 4 (15 classes)');
        $checks['academic']['items'][] = $this->checkItem('Sections/Streams', $sectionCount > 0, $sectionCount . ' sections');
        $checks['academic']['items'][] = $this->checkItem('Subjects (8-4-4)', $subjectCount > 0, $subjectCount . ' subjects');
        $checks['academic']['items'][] = $this->checkItem('CBC Learning Areas', $learningAreaCount > 0, $learningAreaCount . ' learning areas');
        $checks['academic']['items'][] = $this->checkItem('CBC Strands', $strandCount > 0, $strandCount . ' strands');
        $checks['academic']['items'][] = $this->checkItem('Exam Terms', $termCount >= 3, $termCount . ' terms', $termCount < 3 ? 'Need Term 1, 2, 3' : '');
        $checks['academic']['items'][] = $this->checkItem('Grading Scale', $gradeCount > 0, $gradeCount . ' grades');
        $checks['academic']['items'][] = $this->checkItem('Mark Distributions', $markDistCount > 0, $markDistCount . ' distributions');
        $checks['academic']['items'][] = $this->checkItem('Exam Halls', $hallCount > 0, $hallCount . ' halls');

        // 3. STAFF
        $checks['staff'] = array(
            'title' => 'Staff / Employees',
            'icon' => 'fas fa-users',
            'items' => array()
        );
        $totalStaff = $this->db->where('branch_id', $branchId)->count_all_results('staff');
        $adminCount = $this->db->select('s.id')->from('staff s')->join('login_credential lc', 'lc.user_id = s.id', 'inner')->where(array('s.branch_id' => $branchId, 'lc.role' => 2, 'lc.active' => 1))->count_all_results();
        $teacherCount = $this->db->select('s.id')->from('staff s')->join('login_credential lc', 'lc.user_id = s.id', 'inner')->where(array('s.branch_id' => $branchId, 'lc.role' => 3, 'lc.active' => 1))->count_all_results();
        $accountantCount = $this->db->select('s.id')->from('staff s')->join('login_credential lc', 'lc.user_id = s.id', 'inner')->where(array('s.branch_id' => $branchId, 'lc.role' => 4, 'lc.active' => 1))->count_all_results();
        $deptCount = $this->db->where('branch_id', $branchId)->count_all_results('staff_department');
        $desigCount = $this->db->where('branch_id', $branchId)->count_all_results('staff_designation');
        $classTeacherCount = $this->db->where(array('branch_id' => $branchId, 'session_id' => $sessionId))->count_all_results('teacher_allocation');
        $salaryAssigned = $this->db->where(array('branch_id' => $branchId, 'salary_template_id !=' => 0))->count_all_results('staff');

        $checks['staff']['items'][] = $this->checkItem('Total Staff', $totalStaff > 0, $totalStaff . ' staff members', $totalStaff == 0 ? 'Add at least 1 admin and teachers' : '');
        $checks['staff']['items'][] = $this->checkItem('School Admin', $adminCount > 0, $adminCount . ' admin(s)', $adminCount == 0 ? 'Need at least 1 School Admin (Role: Admin)' : '');
        $checks['staff']['items'][] = $this->checkItem('Teachers', $teacherCount > 0, $teacherCount . ' teacher(s)', $teacherCount == 0 ? 'Need teachers to enter marks and attendance' : '');
        $checks['staff']['items'][] = $this->checkItem('Accountant', $accountantCount > 0, $accountantCount . ' accountant(s)', $accountantCount == 0 ? 'Recommended: Add an accountant for fee collection' : '');
        $checks['staff']['items'][] = $this->checkItem('Departments', $deptCount > 0, $deptCount . ' departments');
        $checks['staff']['items'][] = $this->checkItem('Designations', $desigCount > 0, $desigCount . ' designations');
        $checks['staff']['items'][] = $this->checkItem('Class Teachers Assigned', $classTeacherCount > 0, $classTeacherCount . ' assignments', $classTeacherCount == 0 ? 'Assign class teachers in Academics > Assign Class Teacher' : '');
        $checks['staff']['items'][] = $this->checkItem('Salary Templates Assigned', $salaryAssigned > 0, $salaryAssigned . ' of ' . $totalStaff . ' staff', $salaryAssigned < $totalStaff ? ($totalStaff - $salaryAssigned) . ' staff without salary template' : '');

        // 4. STUDENTS
        $checks['students'] = array(
            'title' => 'Students & Parents',
            'icon' => 'fas fa-user-graduate',
            'items' => array()
        );
        $totalStudents = $this->db->where(array('branch_id' => $branchId, 'session_id' => $sessionId))->count_all_results('enroll');
        $parentCount = $this->db->where('branch_id', $branchId)->count_all_results('parent');
        $categoryCount = $this->db->where('branch_id', $branchId)->count_all_results('student_category');

        $classesWithStudents = $this->db->select('class_id, COUNT(*) as cnt')->where(array('branch_id' => $branchId, 'session_id' => $sessionId))->group_by('class_id')->get('enroll')->num_rows();
        $emptyClasses = $classCount - $classesWithStudents;

        $dupRolls = $this->db->query(
            "SELECT class_id, section_id, roll, COUNT(*) as cnt FROM enroll WHERE branch_id = ? AND session_id = ? GROUP BY class_id, section_id, roll HAVING cnt > 1",
            array((int)$branchId, (int)$sessionId)
        )->num_rows();

        $studentsNoParent = $this->db->where(array('parent_id' => 0))->count_all_results('student');

        $checks['students']['items'][] = $this->checkItem('Total Students Enrolled', $totalStudents > 0, $totalStudents . ' students', $totalStudents == 0 ? 'Admit students via Admission > Create Admission' : '');
        $checks['students']['items'][] = $this->checkItem('Total Parents', $parentCount > 0, $parentCount . ' parents');
        $checks['students']['items'][] = $this->checkItem('Student Categories', $categoryCount > 0, $categoryCount . ' categories');
        $checks['students']['items'][] = $this->checkItem('Classes with Students', $emptyClasses == 0, $classesWithStudents . ' of ' . $classCount . ' classes', $emptyClasses > 0 ? $emptyClasses . ' classes have no students enrolled' : '');
        $checks['students']['items'][] = $this->checkItem('No Duplicate Roll Numbers', $dupRolls == 0, $dupRolls == 0 ? 'All unique' : $dupRolls . ' duplicates found!', $dupRolls > 0 ? 'Fix duplicate roll numbers in same class/section' : '');
        $checks['students']['items'][] = $this->checkItem('Students Linked to Parents', $studentsNoParent == 0, $studentsNoParent == 0 ? 'All linked' : $studentsNoParent . ' without parent', $studentsNoParent > 0 ? 'Some students have no guardian assigned' : '');

        // 5. FEES
        $checks['fees'] = array(
            'title' => 'Fee Management',
            'icon' => 'fas fa-money-bill-wave',
            'items' => array()
        );
        $feeTypeCount = $this->db->where('branch_id', $branchId)->count_all_results('fees_type');
        $feeGroupCount = $this->db->where(array('branch_id' => $branchId, 'session_id' => $sessionId))->count_all_results('fee_groups');
        $feeAllocCount = $this->db->where(array('branch_id' => $branchId, 'session_id' => $sessionId))->count_all_results('fee_allocation');
        $studentsNoFee = $totalStudents - $this->db->select('DISTINCT student_id')->where(array('branch_id' => $branchId, 'session_id' => $sessionId))->count_all_results('fee_allocation');
        $paymentCount = $this->db->query(
            "SELECT COUNT(*) as c FROM fee_payment_history fph JOIN fee_allocation fa ON fa.id = fph.allocation_id WHERE fa.branch_id = ? AND fa.session_id = ?",
            array((int)$branchId, (int)$sessionId)
        )->row()->c;

        $checks['fees']['items'][] = $this->checkItem('Fee Types Created', $feeTypeCount > 0, $feeTypeCount . ' fee types');
        $checks['fees']['items'][] = $this->checkItem('Fee Groups Created', $feeGroupCount > 0, $feeGroupCount . ' fee groups', $feeGroupCount == 0 ? 'Create fee groups with amounts in Student Accounting > Fees Group' : '');
        $checks['fees']['items'][] = $this->checkItem('Fees Allocated to Students', $feeAllocCount > 0, $feeAllocCount . ' allocations', $feeAllocCount == 0 ? 'Allocate fees in Student Accounting > Fees Allocation' : '');
        $checks['fees']['items'][] = $this->checkItem('All Students Have Fees', $studentsNoFee <= 0, $studentsNoFee <= 0 ? 'All allocated' : $studentsNoFee . ' students without fees', $studentsNoFee > 0 ? 'Some students have no fees allocated' : '');
        $checks['fees']['items'][] = $this->checkItem('Fee Payments Recorded', $paymentCount > 0, $paymentCount . ' payments', $paymentCount == 0 ? 'No payments recorded yet (normal if starting fresh)' : '');

        // 6. EXAMS & MARKS
        $checks['exams'] = array(
            'title' => 'Exams & Assessment',
            'icon' => 'fas fa-clipboard-check',
            'items' => array()
        );
        $examCount = $this->db->where(array('branch_id' => $branchId, 'session_id' => $sessionId))->count_all_results('exam');
        $cbcExamCount = $this->db->where(array('branch_id' => $branchId, 'session_id' => $sessionId, 'grading_system' => 'cbc'))->count_all_results('exam');
        $tradExamCount = $this->db->where(array('branch_id' => $branchId, 'session_id' => $sessionId, 'grading_system' => 'traditional'))->count_all_results('exam');
        $markCount = $this->db->where(array('branch_id' => $branchId, 'session_id' => $sessionId))->count_all_results('mark');
        $cbcAssessCount = $this->db->where(array('branch_id' => $branchId, 'session_id' => $sessionId))->count_all_results('cbc_assessment');
        $behaviourCount = $this->db->where(array('branch_id' => $branchId, 'session_id' => $sessionId))->count_all_results('cbc_behaviour_assessment');

        $checks['exams']['items'][] = $this->checkItem('Exams Created', $examCount > 0, $examCount . ' exams (' . $cbcExamCount . ' CBC + ' . $tradExamCount . ' traditional)', $examCount == 0 ? 'Create exams in Exam Master > Exam' : '');
        $checks['exams']['items'][] = $this->checkItem('Traditional Marks Entered', $markCount > 0, $markCount . ' mark entries', $markCount == 0 ? 'Enter marks in Marks > Mark Entries (for Form 1-4)' : '');
        $checks['exams']['items'][] = $this->checkItem('CBC Assessments Entered', $cbcAssessCount > 0, $cbcAssessCount . ' assessments', $cbcAssessCount == 0 ? 'Enter assessments in CBC Assessment > Assessment Entry' : '');
        $checks['exams']['items'][] = $this->checkItem('Behaviour Assessments', $behaviourCount > 0, $behaviourCount . ' entries', $behaviourCount == 0 ? 'Enter behaviour ratings in CBC Assessment > Behaviour Assessment' : '');

        // 7. COMMUNICATION
        $checks['communication'] = array(
            'title' => 'Communication & Settings',
            'icon' => 'fas fa-cog',
            'items' => array()
        );
        $emailConfig = $this->db->get_where('email_config', array('branch_id' => $branchId))->row();
        $emailOk = ($emailConfig && !empty($emailConfig->smtp_host));
        $smsConfig = $this->db->where(array('branch_id' => $branchId, 'is_active' => 1))->count_all_results('sms_credential');
        $payConfig = $this->db->get_where('payment_config', array('branch_id' => $branchId))->row();
        $mpesaOk = ($payConfig && !empty($payConfig->mpesa_consumer_key));
        $smsTemplateCount = $this->db->where('branch_id', $branchId)->count_all_results('sms_template_details');
        $emailTemplateCount = $this->db->where('branch_id', $branchId)->count_all_results('email_templates_details');
        $eventCount = $this->db->where('branch_id', $branchId)->count_all_results('event');
        $bookCount = $this->db->where('branch_id', $branchId)->count_all_results('book');
        $accountCount = $this->db->where('branch_id', $branchId)->count_all_results('accounts');
        $voucherCount = $this->db->where('branch_id', $branchId)->count_all_results('voucher_head');
        $timetableCount = $this->db->where(array('branch_id' => $branchId, 'session_id' => $sessionId))->count_all_results('timetable_class');

        $checks['communication']['items'][] = $this->checkItem('Email Configured (SMTP)', $emailOk, $emailOk ? 'Configured' : 'Not set up', !$emailOk ? 'Set up in School Settings > Email Config (needed for forgot password)' : '');
        $checks['communication']['items'][] = $this->checkItem('SMS Provider Configured', $smsConfig > 0, $smsConfig > 0 ? 'Active' : 'Not set up', $smsConfig == 0 ? 'Set up in School Settings > SMS Config (Africa\'s Talking)' : '');
        $checks['communication']['items'][] = $this->checkItem('M-Pesa Configured', $mpesaOk, $mpesaOk ? 'Configured' : 'Not set up', !$mpesaOk ? 'Optional: School Settings > Payment Gateway > M-Pesa' : '');
        $checks['communication']['items'][] = $this->checkItem('SMS Templates', $smsTemplateCount > 0, $smsTemplateCount . ' templates');
        $checks['communication']['items'][] = $this->checkItem('Email Templates', $emailTemplateCount > 0, $emailTemplateCount . ' templates');
        $checks['communication']['items'][] = $this->checkItem('School Events', $eventCount > 0, $eventCount . ' events');
        $checks['communication']['items'][] = $this->checkItem('Library Books', $bookCount > 0, $bookCount . ' books');
        $checks['communication']['items'][] = $this->checkItem('Bank Accounts', $accountCount > 0, $accountCount . ' accounts', $accountCount == 0 ? 'Create in Office Accounting > Accounts' : '');
        $checks['communication']['items'][] = $this->checkItem('Voucher Heads', $voucherCount > 0, $voucherCount . ' categories');
        $checks['communication']['items'][] = $this->checkItem('Class Timetable', $timetableCount > 0, $timetableCount . ' entries', $timetableCount == 0 ? 'Create in Academics > Class Timetable' : '');

        // 8. SUBSCRIPTION
        $checks['subscription'] = array(
            'title' => 'Subscription',
            'icon' => 'fas fa-id-card',
            'items' => array()
        );
        $sub = $this->db->where('branch_id', $branchId)->order_by('id', 'DESC')->limit(1)->get('branch_subscriptions')->row();
        $subOk = ($sub && ($sub->status == 'active' || $sub->status == 'trial'));
        $daysLeft = $sub ? max(0, floor((strtotime($sub->end_date) - time()) / 86400)) : 0;
        $checks['subscription']['items'][] = $this->checkItem('Subscription Active', $subOk, $subOk ? 'Active (' . $daysLeft . ' days left)' : 'No active subscription', !$subOk ? 'Assign a plan in Branch > Subscriptions > Assign Plan' : '');

        // 9. SCHOOL OPERATIONS (New Modules)
        $checks['operations'] = array(
            'title' => 'School Operations',
            'icon' => 'fas fa-cogs',
            'items' => array()
        );
        $canteenItems    = $this->db->where('branch_id', $branchId)->count_all_results('canteen_items');
        $canteenWallets  = $this->db->where('branch_id', $branchId)->count_all_results('canteen_wallet');
        $assetCategories = $this->db->where('branch_id', $branchId)->count_all_results('asset_categories');
        $assetsCount     = $this->db->where('branch_id', $branchId)->count_all_results('assets');
        $inventoryCount  = $this->db->where('branch_id', $branchId)->count_all_results('inventory_items');
        $noticeCount     = $this->db->where('branch_id', $branchId)->count_all_results('notice_board');
        $pocketMoneyTx   = $this->db->where('branch_id', $branchId)->count_all_results('pocket_money');
        $pmBalances      = $this->db->where('branch_id', $branchId)->count_all_results('pocket_money_balance');
        $bursaryProg     = $this->db->where('branch_id', $branchId)->count_all_results('bursary_programmes');
        $bursaryAwards   = $this->db->where('branch_id', $branchId)->count_all_results('bursary_awards');

        $checks['operations']['items'][] = $this->checkItem('Canteen Menu Items', $canteenItems > 0, $canteenItems . ' items', $canteenItems == 0 ? 'Add menu items in Canteen / POS > Menu Items' : '');
        $checks['operations']['items'][] = $this->checkItem('Canteen Student Wallets', $canteenWallets > 0, $canteenWallets . ' wallets loaded', $canteenWallets == 0 ? 'Load wallets in Canteen / POS > Student Wallets' : '');
        $checks['operations']['items'][] = $this->checkItem('Asset Categories', $assetCategories > 0, $assetCategories . ' categories', $assetCategories == 0 ? 'Create asset categories in Assets & Inventory > Categories' : '');
        $checks['operations']['items'][] = $this->checkItem('Assets Registered', $assetsCount > 0, $assetsCount . ' assets', $assetsCount == 0 ? 'Register assets in Assets & Inventory > Asset Register' : '');
        $checks['operations']['items'][] = $this->checkItem('Inventory Items', $inventoryCount > 0, $inventoryCount . ' items', $inventoryCount == 0 ? 'Add inventory in Assets & Inventory > Inventory' : '');
        $checks['operations']['items'][] = $this->checkItem('Notice Board Posts', $noticeCount > 0, $noticeCount . ' notices', $noticeCount == 0 ? 'Post notices in Notice Board > Manage Notices' : '');
        $checks['operations']['items'][] = $this->checkItem('Pocket Money Transactions', $pocketMoneyTx > 0, $pocketMoneyTx . ' transactions', $pocketMoneyTx == 0 ? 'Start transactions in Pocket Money' : '');
        $checks['operations']['items'][] = $this->checkItem('Pocket Money Balances', $pmBalances > 0, $pmBalances . ' students', $pmBalances == 0 ? 'Deposit funds in Pocket Money > Student wallets' : '');
        $checks['operations']['items'][] = $this->checkItem('Bursary Programmes', $bursaryProg > 0, $bursaryProg . ' programmes', $bursaryProg == 0 ? 'Create bursary programmes in Bursary & Scholarships' : '');
        $checks['operations']['items'][] = $this->checkItem('Bursary Awards Given', $bursaryAwards > 0, $bursaryAwards . ' awards', $bursaryAwards == 0 ? 'Award bursaries in Bursary & Scholarships > (select programme)' : '');

        // 10. STUDENT WELFARE (New Modules)
        $checks['welfare'] = array(
            'title' => 'Student Welfare & Records',
            'icon' => 'fas fa-heart',
            'items' => array()
        );
        $healthRecords  = $this->db->where('branch_id', $branchId)->count_all_results('student_health');
        $vaccinations   = $this->db->where('branch_id', $branchId)->count_all_results('student_vaccinations');
        $clinicVisits   = $this->db->where('branch_id', $branchId)->count_all_results('clinic_visits');
        $alumniCount    = $this->db->where('branch_id', $branchId)->count_all_results('alumni');
        $ptmSessions    = $this->db->where('branch_id', $branchId)->count_all_results('ptm_sessions');
        $ptmBookings    = $this->db->where('branch_id', $branchId)->count_all_results('ptm_bookings');
        $appraisalTpl   = $this->db->where('branch_id', $branchId)->count_all_results('appraisal_templates');
        $appraisalCount = $this->db->where('branch_id', $branchId)->count_all_results('appraisals');

        $checks['welfare']['items'][] = $this->checkItem('Student Health Records', $healthRecords > 0, $healthRecords . ' records', $healthRecords == 0 ? 'Add health records in Health Records > Student Health' : '');
        $checks['welfare']['items'][] = $this->checkItem('Vaccination Records', $vaccinations > 0, $vaccinations . ' entries', $vaccinations == 0 ? 'Log vaccinations in Health Records > Student Health' : '');
        $checks['welfare']['items'][] = $this->checkItem('Clinic Visit Logs', $clinicVisits > 0, $clinicVisits . ' visits', $clinicVisits == 0 ? 'Log visits in Health Records > Clinic Log' : '');
        $checks['welfare']['items'][] = $this->checkItem('Alumni Records', $alumniCount > 0, $alumniCount . ' alumni', $alumniCount == 0 ? 'Add alumni in Alumni > Add Alumni or Import Students' : '');
        $checks['welfare']['items'][] = $this->checkItem('PTM Sessions Created', $ptmSessions > 0, $ptmSessions . ' sessions', $ptmSessions == 0 ? 'Schedule meetings in PTM (Parent-Teacher) > New Session' : '');
        $checks['welfare']['items'][] = $this->checkItem('PTM Bookings Made', $ptmBookings > 0, $ptmBookings . ' bookings', $ptmBookings == 0 ? 'Book slots in PTM (Parent-Teacher) > Bookings' : '');
        $checks['welfare']['items'][] = $this->checkItem('Appraisal Templates', $appraisalTpl > 0, $appraisalTpl . ' templates', $appraisalTpl == 0 ? 'Create templates in Staff Appraisal > Templates' : '');
        $checks['welfare']['items'][] = $this->checkItem('Staff Appraisals Conducted', $appraisalCount > 0, $appraisalCount . ' appraisals', $appraisalCount == 0 ? 'Start appraisals in Staff Appraisal > Start Appraisal' : '');

        // 11. ADMINISTRATION & TECH (New Modules)
        $checks['admin_tech'] = array(
            'title' => 'Administration & Technology',
            'icon' => 'fas fa-shield-alt',
            'items' => array()
        );
        $busCount       = $this->db->where('branch_id', $branchId)->count_all_results('school_buses');
        $busLocations   = $this->db->where('branch_id', $branchId)->count_all_results('bus_locations');
        $visitorCount   = $this->db->where('branch_id', $branchId)->count_all_results('visitor_log');
        $knecCentres    = $this->db->where('branch_id', $branchId)->count_all_results('knec_centres');
        $knecCandidates = $this->db->where('branch_id', $branchId)->count_all_results('knec_candidates');
        $cbtQuizzes     = $this->db->where('branch_id', $branchId)->count_all_results('cbt_quiz');
        $cbtQuestions   = $this->db->where('branch_id', $branchId)->count_all_results('cbt_questions');
        $virtualClasses = $this->db->where('branch_id', $branchId)->count_all_results('virtual_classes');
        $admReqs        = $this->db->where('branch_id', $branchId)->count_all_results('admission_requests');

        $checks['admin_tech']['items'][] = $this->checkItem('Buses Registered', $busCount > 0, $busCount . ' buses', $busCount == 0 ? 'Register buses in GPS Bus Tracking > Manage Buses' : '');
        $checks['admin_tech']['items'][] = $this->checkItem('GPS Locations Logged', $busLocations > 0, $busLocations . ' pings', $busLocations == 0 ? 'Buses need to report location via GPS Tracking > Live Map' : '');
        $checks['admin_tech']['items'][] = $this->checkItem('Visitor Log Entries', $visitorCount > 0, $visitorCount . ' visitors', $visitorCount == 0 ? 'Log visitors in Visitor / Gate Log > Today\'s Visitors' : '');
        $checks['admin_tech']['items'][] = $this->checkItem('KNEC Exam Centres', $knecCentres > 0, $knecCentres . ' centres', $knecCentres == 0 ? 'Add centres in KNEC Index Numbers > Candidates & Centres' : '');
        $checks['admin_tech']['items'][] = $this->checkItem('KNEC Candidates Registered', $knecCandidates > 0, $knecCandidates . ' candidates', $knecCandidates == 0 ? 'Register candidates in KNEC Index Numbers' : '');
        $checks['admin_tech']['items'][] = $this->checkItem('CBT Quizzes Created', $cbtQuizzes > 0, $cbtQuizzes . ' quizzes', $cbtQuizzes == 0 ? 'Create online exams in CBT / Online Exams > Manage Exams' : '');
        $checks['admin_tech']['items'][] = $this->checkItem('CBT Questions Added', $cbtQuestions > 0, $cbtQuestions . ' questions', $cbtQuestions == 0 ? 'Add questions to quizzes in CBT / Online Exams' : '');
        $checks['admin_tech']['items'][] = $this->checkItem('Virtual Classes Scheduled', $virtualClasses > 0, $virtualClasses . ' sessions', $virtualClasses == 0 ? 'Schedule in Virtual Classroom > Scheduled Classes' : '');
        $checks['admin_tech']['items'][] = $this->checkItem('Online Admission Requests', $admReqs > 0, $admReqs . ' requests', $admReqs == 0 ? 'Online portal at Admission > Online Admission Portal' : '');

        // Calculate totals
        $totalChecks = 0;
        $passedChecks = 0;
        foreach ($checks as &$section) {
            $section['passed'] = 0;
            $section['total'] = count($section['items']);
            foreach ($section['items'] as $item) {
                $totalChecks++;
                if ($item['pass']) {
                    $passedChecks++;
                    $section['passed']++;
                }
            }
        }

        $this->data['branch'] = $branch;
        $this->data['checks'] = $checks;
        $this->data['totalChecks'] = $totalChecks;
        $this->data['passedChecks'] = $passedChecks;
        $this->data['percentage'] = $totalChecks > 0 ? round(($passedChecks / $totalChecks) * 100) : 0;
        $this->data['title'] = 'Branch Health Check - ' . $branch['school_name'];
        $this->data['sub_page'] = 'branch/health_check';
        $this->data['main_menu'] = 'branch';
        $this->load->view('layout/index', $this->data);
    }

    private function checkItem($name, $pass, $detail = '', $fix = '')
    {
        return array('name' => $name, 'pass' => $pass, 'detail' => $detail, 'fix' => $fix);
    }
}
