<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Branch_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function save($data)
    {
        $arrayBranch = array(
            'name' => $data['branch_name'],
            'school_name' => $data['school_name'],
            'email' => $data['email'],
            'mobileno' => $data['mobileno'],
            'currency' => $data['currency'],
            'symbol' => $data['currency_symbol'],
            'city' => $data['city'],
            'state' => $data['state'],
            'address' => $data['address'],
        );
        if (!isset($data['branch_id'])) {
            $this->db->insert('branch', $arrayBranch);
            $branchId = $this->db->insert_id();
            if (isset($data['kenya_template']) && $data['kenya_template']) {
                $this->seedKenyanDefaults($branchId);
            }
            if (isset($data['university_template']) && $data['university_template']) {
                $this->seedUniversityDefaults($branchId);
            }
        } else {
            $this->db->where('id', $data['branch_id']);
            $this->db->update('branch', $arrayBranch);
        }

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function seedKenyanDefaults($branchId)
    {
        $sessionId = $this->db->select('id')->order_by('id', 'DESC')->limit(1)->get('schoolyear')->row();
        $sid = $sessionId ? $sessionId->id : 1;

        // 1. SECTIONS (Streams)
        $sections = array('A', 'B', 'C');
        foreach ($sections as $sec) {
            $exists = $this->db->get_where('section', array('name' => $sec, 'branch_id' => $branchId))->num_rows();
            if ($exists == 0) {
                $this->db->insert('section', array('name' => $sec, 'capacity' => 40, 'branch_id' => $branchId));
            }
        }
        $sectionIds = array();
        foreach ($this->db->where('branch_id', $branchId)->get('section')->result() as $s) {
            $sectionIds[] = $s->id;
        }

        // 2. CLASSES (PP1 to Form 4)
        $classes = array(
            array('name' => 'PP1', 'name_numeric' => '0', 'curriculum_type' => 'cbc', 'level' => 'pp'),
            array('name' => 'PP2', 'name_numeric' => '0', 'curriculum_type' => 'cbc', 'level' => 'pp'),
            array('name' => 'Grade 1', 'name_numeric' => '1', 'curriculum_type' => 'cbc', 'level' => 'lower_primary'),
            array('name' => 'Grade 2', 'name_numeric' => '2', 'curriculum_type' => 'cbc', 'level' => 'lower_primary'),
            array('name' => 'Grade 3', 'name_numeric' => '3', 'curriculum_type' => 'cbc', 'level' => 'lower_primary'),
            array('name' => 'Grade 4', 'name_numeric' => '4', 'curriculum_type' => 'cbc', 'level' => 'upper_primary'),
            array('name' => 'Grade 5', 'name_numeric' => '5', 'curriculum_type' => 'cbc', 'level' => 'upper_primary'),
            array('name' => 'Grade 6', 'name_numeric' => '6', 'curriculum_type' => 'cbc', 'level' => 'upper_primary'),
            array('name' => 'Grade 7', 'name_numeric' => '7', 'curriculum_type' => 'cbc', 'level' => 'junior_secondary'),
            array('name' => 'Grade 8', 'name_numeric' => '8', 'curriculum_type' => 'cbc', 'level' => 'junior_secondary'),
            array('name' => 'Grade 9', 'name_numeric' => '9', 'curriculum_type' => 'cbc', 'level' => 'junior_secondary'),
            array('name' => 'Form 1', 'name_numeric' => '10', 'curriculum_type' => '844', 'level' => 'senior_secondary'),
            array('name' => 'Form 2', 'name_numeric' => '11', 'curriculum_type' => '844', 'level' => 'senior_secondary'),
            array('name' => 'Form 3', 'name_numeric' => '12', 'curriculum_type' => '844', 'level' => 'senior_secondary'),
            array('name' => 'Form 4', 'name_numeric' => '13', 'curriculum_type' => '844', 'level' => 'senior_secondary'),
        );
        foreach ($classes as $cls) {
            $cls['branch_id'] = $branchId;
            $this->db->insert('class', $cls);
            $classId = $this->db->insert_id();
            foreach ($sectionIds as $secId) {
                $this->db->insert('sections_allocation', array('class_id' => $classId, 'section_id' => $secId));
            }
        }

        // 3. STUDENT CATEGORIES
        $categories = array('Regular', 'Scholarship', 'Bursary', 'Special Needs');
        foreach ($categories as $cat) {
            $this->db->insert('student_category', array('name' => $cat, 'branch_id' => $branchId));
        }

        // 4. DEPARTMENTS
        $departments = array('Administration', 'Teaching - Lower Primary', 'Teaching - Upper Primary', 'Teaching - Junior Secondary', 'Teaching - Senior Secondary', 'Accounts', 'Support Staff');
        foreach ($departments as $dept) {
            $this->db->insert('staff_department', array('name' => $dept, 'branch_id' => $branchId));
        }

        // 5. DESIGNATIONS
        $designations = array('Head Teacher', 'Deputy Head Teacher', 'Class Teacher', 'Subject Teacher', 'Accountant', 'Librarian', 'Secretary');
        foreach ($designations as $des) {
            $this->db->insert('staff_designation', array('name' => $des, 'branch_id' => $branchId));
        }

        // 6. SUBJECTS (8-4-4 for Form 1-4)
        $subjects = array(
            array('English', 'ENG', 'Theory'), array('Kiswahili', 'KIS', 'Theory'),
            array('Mathematics', 'MAT', 'Theory'), array('Biology', 'BIO', 'Theory'),
            array('Chemistry', 'CHE', 'Theory'), array('Physics', 'PHY', 'Theory'),
            array('History', 'HIS', 'Theory'), array('Geography', 'GEO', 'Theory'),
            array('CRE', 'CRE', 'Theory'), array('IRE', 'IRE', 'Theory'),
            array('Business Studies', 'BUS', 'Theory'), array('Agriculture', 'AGR', 'Theory'),
            array('Computer Studies', 'COM', 'Practical'), array('Home Science', 'HOM', 'Practical'),
            array('Art and Design', 'ART', 'Practical'), array('Music', 'MUS', 'Practical'),
            array('French', 'FRE', 'Theory'), array('German', 'GER', 'Theory'),
        );
        foreach ($subjects as $sub) {
            $this->db->insert('subject', array('name' => $sub[0], 'subject_code' => $sub[1], 'subject_type' => $sub[2], 'subject_author' => '', 'branch_id' => $branchId));
        }

        // 7. CBC LEARNING AREAS (including PP)
        $learningAreas = array(
            'pp' => array('Language Activities', 'Mathematical Activities', 'Environmental Activities', 'Psychomotor and Creative Activities', 'Religious Education Activities', 'Nutrition and Hygiene Activities'),
            'lower_primary' => array('Literacy Activities', 'Kiswahili Language Activities', 'English Language Activities', 'Mathematics Activities', 'Environmental Activities', 'Hygiene and Nutrition Activities', 'Religious Education Activities', 'Movement and Creative Activities'),
            'upper_primary' => array('English', 'Kiswahili', 'Mathematics', 'Science and Technology', 'Social Studies', 'Religious Education', 'Creative Arts', 'Physical and Health Education', 'Agriculture'),
            'junior_secondary' => array('English', 'Kiswahili', 'Mathematics', 'Integrated Science', 'Social Studies', 'Pre-Technical Studies', 'Agriculture', 'Creative Arts and Sports', 'Religious Education'),
        );
        $areaIds = array();
        foreach ($learningAreas as $level => $areas) {
            foreach ($areas as $areaName) {
                $this->db->insert('cbc_learning_areas', array('name' => $areaName, 'level' => $level, 'branch_id' => $branchId));
                $areaIds[$level . '_' . $areaName] = $this->db->insert_id();
            }
        }

        // 7B. CBC STRANDS (sub-topics per learning area)
        $strands = array(
            'lower_primary_Literacy Activities' => array('Reading', 'Writing', 'Oral Communication', 'Handwriting'),
            'lower_primary_Kiswahili Language Activities' => array('Kusikiliza na Kuzungumza', 'Kusoma', 'Kuandika', 'Sarufi'),
            'lower_primary_English Language Activities' => array('Listening and Speaking', 'Reading', 'Writing', 'Grammar'),
            'lower_primary_Mathematics Activities' => array('Numbers', 'Measurement', 'Geometry', 'Data Handling'),
            'lower_primary_Environmental Activities' => array('Living Things', 'The Environment', 'Weather', 'Resources'),
            'lower_primary_Hygiene and Nutrition Activities' => array('Personal Hygiene', 'Food and Nutrition', 'Safety', 'Health'),
            'lower_primary_Religious Education Activities' => array('God\'s Creation', 'Moral Values', 'Prayer', 'Holy Books'),
            'lower_primary_Movement and Creative Activities' => array('Movement', 'Music', 'Art and Craft', 'Games'),
            'upper_primary_English' => array('Listening and Speaking', 'Reading', 'Writing', 'Grammar'),
            'upper_primary_Kiswahili' => array('Kusikiliza na Kuzungumza', 'Kusoma', 'Kuandika', 'Sarufi'),
            'upper_primary_Mathematics' => array('Numbers', 'Measurement', 'Geometry', 'Algebra', 'Statistics'),
            'upper_primary_Science and Technology' => array('Living Things', 'Energy', 'Matter', 'Environment', 'Technology'),
            'upper_primary_Social Studies' => array('History', 'Geography', 'Government', 'Citizenship'),
            'upper_primary_Agriculture' => array('Crop Farming', 'Animal Farming', 'Soil and Water', 'Farm Tools'),
            'junior_secondary_English' => array('Listening and Speaking', 'Reading', 'Writing', 'Grammar in Use'),
            'junior_secondary_Kiswahili' => array('Kusikiliza na Kuzungumza', 'Kusoma', 'Kuandika', 'Sarufi na Matumizi'),
            'junior_secondary_Mathematics' => array('Numbers', 'Algebra', 'Geometry', 'Measurements', 'Statistics and Probability'),
            'junior_secondary_Integrated Science' => array('Biology', 'Chemistry', 'Physics', 'Earth Science'),
        );
        foreach ($strands as $key => $strandList) {
            if (isset($areaIds[$key])) {
                foreach ($strandList as $strandName) {
                    $this->db->insert('cbc_strands', array('learning_area_id' => $areaIds[$key], 'name' => $strandName, 'branch_id' => $branchId));
                }
            }
        }

        // 8. EXAM TERMS
        $terms = array('Term 1', 'Term 2', 'Term 3');
        foreach ($terms as $term) {
            $this->db->insert('exam_term', array('name' => $term, 'branch_id' => $branchId, 'session_id' => $sid));
        }

        // 9. MARK DISTRIBUTIONS
        $distributions = array('CAT 1', 'CAT 2', 'End Term Exam');
        foreach ($distributions as $dist) {
            $this->db->insert('exam_mark_distribution', array('name' => $dist, 'branch_id' => $branchId));
        }

        // 10. EXAM HALLS
        $halls = array(array('Hall A', 50), array('Hall B', 50), array('Hall C', 30));
        foreach ($halls as $hall) {
            $this->db->insert('exam_hall', array('hall_no' => $hall[0], 'seats' => $hall[1], 'branch_id' => $branchId));
        }

        // 11. KCSE GRADING SCALE
        $grades = array(
            array('A', '12', 80, 100, 'Excellent'), array('A-', '11', 75, 79, 'Very Good'),
            array('B+', '10', 70, 74, 'Good'), array('B', '9', 65, 69, 'Good'),
            array('B-', '8', 60, 64, 'Above Average'), array('C+', '7', 55, 59, 'Average'),
            array('C', '6', 50, 54, 'Average'), array('C-', '5', 45, 49, 'Below Average'),
            array('D+', '4', 40, 44, 'Below Average'), array('D', '3', 35, 39, 'Weak'),
            array('D-', '2', 30, 34, 'Weak'), array('E', '1', 0, 29, 'Very Weak'),
        );
        foreach ($grades as $g) {
            $this->db->insert('grade', array('name' => $g[0], 'grade_point' => $g[1], 'lower_mark' => $g[2], 'upper_mark' => $g[3], 'remark' => $g[4], 'branch_id' => $branchId));
        }

        // 12. FEE TYPES
        $feeTypes = array(
            array('Tuition Fee', 'TUI', 'Termly tuition fee'),
            array('Activity Fee', 'ACT', 'Co-curricular activities'),
            array('Lunch Programme', 'LUN', 'School lunch per term'),
            array('Exam Fee', 'EXM', 'Examination charges'),
            array('Development Levy', 'DEV', 'Infrastructure development'),
            array('Transport Fee', 'TRN', 'School bus transport'),
            array('Boarding Fee', 'BRD', 'Hostel accommodation'),
            array('Stationery Fee', 'STN', 'Exercise books, pens'),
            array('Medical Fee', 'MED', 'School clinic'),
            array('Library Fee', 'LIB', 'Library services'),
        );
        foreach ($feeTypes as $ft) {
            $this->db->insert('fees_type', array('name' => $ft[0], 'fee_code' => $ft[1], 'description' => $ft[2], 'branch_id' => $branchId));
        }

        // 13. LEAVE CATEGORIES
        $leaves = array(
            array('Annual Leave', 21), array('Sick Leave', 14), array('Maternity Leave', 90),
            array('Paternity Leave', 14), array('Compassionate Leave', 5), array('Study Leave', 30),
        );
        foreach ($leaves as $lv) {
            $this->db->insert('leave_category', array('name' => $lv[0], 'role_id' => 0, 'days' => $lv[1], 'branch_id' => $branchId));
        }

        // 14. EVENT TYPES
        $events = array(
            array('Sports', 'fas fa-running'), array('Cultural', 'fas fa-music'),
            array('Academic', 'fas fa-book'), array('Parents Meeting', 'fas fa-users'),
            array('Holiday', 'fas fa-calendar'), array('Fundraising', 'fas fa-hand-holding-usd'),
        );
        foreach ($events as $ev) {
            $this->db->insert('event_types', array('name' => $ev[0], 'icon' => $ev[1], 'branch_id' => $branchId));
        }

        // 15. VOUCHER HEADS (Accounting categories)
        $vouchers = array(
            array('Student Fees Collection', 'income'), array('Government Grants', 'income'),
            array('Donations & Fundraising', 'income'), array('Other Income', 'income'),
            array('Staff Salaries', 'expense'), array('Stationery & Supplies', 'expense'),
            array('Electricity Bill', 'expense'), array('Water Bill', 'expense'),
            array('Internet & Telephone', 'expense'), array('Transport & Fuel', 'expense'),
            array('Maintenance & Repairs', 'expense'), array('Cleaning Supplies', 'expense'),
            array('Food & Catering', 'expense'), array('Textbooks & Learning Materials', 'expense'),
            array('Exam Materials', 'expense'), array('Sports Equipment', 'expense'),
            array('Medical Supplies', 'expense'), array('Insurance', 'expense'),
            array('Bank Charges', 'expense'), array('Miscellaneous', 'expense'),
        );
        foreach ($vouchers as $vh) {
            $this->db->insert('voucher_head', array('name' => $vh[0], 'type' => $vh[1], 'system' => 0, 'branch_id' => $branchId));
        }

        // 16. BOOK CATEGORIES
        $bookCats = array('Textbooks', 'Fiction', 'Reference', 'Kiswahili Literature', 'English Literature', 'Science', 'History & Geography', 'Religious Education', 'Magazines & Newspapers');
        foreach ($bookCats as $bc) {
            $this->db->insert('book_category', array('name' => $bc, 'branch_id' => $branchId));
        }

        // 17. SALARY TEMPLATES (with Kenyan statutory deductions)
        $salaryTemplates = array(
            array('Head Teacher', 85000), array('Deputy Head Teacher', 70000),
            array('Senior Teacher', 55000), array('Teacher', 45000),
            array('Accountant', 40000), array('Librarian', 35000),
            array('Secretary', 30000), array('Support Staff', 25000),
        );
        foreach ($salaryTemplates as $st) {
            $this->db->insert('salary_template', array('name' => $st[0], 'basic_salary' => $st[1], 'overtime_salary' => '0', 'branch_id' => $branchId));
            $templateId = $this->db->insert_id();
            $basic = $st[1];
            $paye = max(0, round(($basic - 24000) * 0.1));
            $this->db->insert('salary_template_details', array('salary_template_id' => $templateId, 'name' => 'House Allowance', 'amount' => round($basic * 0.15), 'type' => 1));
            $this->db->insert('salary_template_details', array('salary_template_id' => $templateId, 'name' => 'Transport Allowance', 'amount' => round($basic * 0.08), 'type' => 1));
            $this->db->insert('salary_template_details', array('salary_template_id' => $templateId, 'name' => 'NHIF', 'amount' => 1700, 'type' => 2));
            $this->db->insert('salary_template_details', array('salary_template_id' => $templateId, 'name' => 'NSSF', 'amount' => 200, 'type' => 2));
            $this->db->insert('salary_template_details', array('salary_template_id' => $templateId, 'name' => 'PAYE', 'amount' => $paye, 'type' => 2));
        }

        // 18. PAYMENT CONFIG (empty, ready for M-Pesa setup)
        $exists = $this->db->get_where('payment_config', array('branch_id' => $branchId))->num_rows();
        if ($exists == 0) {
            $this->db->insert('payment_config', array(
                'branch_id' => $branchId, 'paypal_username' => '', 'paypal_password' => '',
                'paypal_signature' => '', 'paypal_email' => '', 'paypal_sandbox' => 1, 'paypal_status' => 0,
                'stripe_secret' => '', 'stripe_demo' => 1, 'stripe_status' => 0,
                'mpesa_consumer_key' => '', 'mpesa_consumer_secret' => '', 'mpesa_shortcode' => '',
                'mpesa_passkey' => '', 'mpesa_sandbox' => 1, 'mpesa_status' => 0,
            ));
        }

        // 19. ATTACHMENTS TYPES
        $attachTypes = array('Homework', 'Notes', 'Past Papers', 'Syllabus', 'Announcements', 'Newsletters', 'Circulars');
        foreach ($attachTypes as $at) {
            $this->db->insert('attachments_type', array('name' => $at, 'branch_id' => $branchId));
        }

        // 20. HOSTEL CATEGORIES
        $hostelCheck = $this->db->get_where('hostel_category', array('branch_id' => $branchId))->num_rows();
        if ($hostelCheck == 0) {
            $this->db->insert('hostel_category', array('name' => 'Boys Hostel', 'description' => 'Male students dormitory', 'branch_id' => $branchId, 'type' => 'Boys'));
            $this->db->insert('hostel_category', array('name' => 'Girls Hostel', 'description' => 'Female students dormitory', 'branch_id' => $branchId, 'type' => 'Girls'));
        }

        // 21. SMS TEMPLATES
        $smsTemplates = $this->db->get('sms_template')->result();
        foreach ($smsTemplates as $tpl) {
            $exists = $this->db->get_where('sms_template_details', array('template_id' => $tpl->id, 'branch_id' => $branchId))->num_rows();
            if ($exists == 0) {
                $bodies = array(
                    1 => 'Dear {guardian_name}, {student_name} has been admitted to {school_name}. Class: {class}, Login: {email}. Welcome!',
                    2 => 'Dear {guardian_name}, Fee payment of KES {amount} received for {student_name} on {paid_date}. Thank you. - {school_name}',
                    3 => 'Dear {guardian_name}, {student_name} was marked {status} on {date}. - {school_name}',
                    4 => 'Dear {guardian_name}, {student_name} was marked {status} for the exam. - {school_name}',
                    5 => 'Dear {guardian_name}, Exam results for {student_name} are now available. Login to view. - {school_name}',
                    6 => 'Dear {guardian_name}, Homework assigned to {class}. Subject: {subject}. Due: {due_date}. - {school_name}',
                    7 => 'Dear {guardian_name}, Live class scheduled for {class} on {date} at {time}. - {school_name}',
                );
                $body = isset($bodies[$tpl->id]) ? $bodies[$tpl->id] : '';
                $this->db->insert('sms_template_details', array(
                    'template_id' => $tpl->id, 'notify_student' => 1, 'notify_parent' => 1,
                    'template_body' => $body, 'branch_id' => $branchId,
                ));
            }
        }

        // 22. EMAIL TEMPLATES
        $emailTemplates = $this->db->get('email_templates')->result();
        foreach ($emailTemplates as $etpl) {
            $exists = $this->db->get_where('email_templates_details', array('template_id' => $etpl->id, 'branch_id' => $branchId))->num_rows();
            if ($exists == 0) {
                $subjects = array(
                    1 => 'Account Created - {institute_name}',
                    2 => 'Password Reset - {institute_name}',
                    3 => 'Password Changed - {institute_name}',
                    4 => 'New Message - {institute_name}',
                    5 => 'Salary Payment - {institute_name}',
                    6 => 'Award Notification - {institute_name}',
                    7 => 'Leave Approved - {institute_name}',
                    8 => 'Leave Rejected - {institute_name}',
                );
                $bodies = array(
                    1 => '<p>Dear {name},</p><p>Your account has been created at {institute_name}.</p><p>Login Email: {email}<br>Password: {password}</p><p>Login at the school portal to access your dashboard.</p><p>Regards,<br>{institute_name}</p>',
                    2 => '<p>Dear {username},</p><p>You requested a password reset.</p><p>Click here to reset: <a href="{reset_url}">{reset_url}</a></p><p>If you did not request this, ignore this email.</p><p>Regards,<br>{institute_name}</p>',
                    3 => '<p>Dear {name},</p><p>Your password has been changed successfully.</p><p>Regards,<br>{institute_name}</p>',
                    4 => '<p>Dear {name},</p><p>You have a new message. Login to view it.</p><p>Regards,<br>{institute_name}</p>',
                    5 => '<p>Dear {name},</p><p>Your salary for {month} has been processed.</p><p>Net Amount: {amount}</p><p>Regards,<br>{institute_name}</p>',
                    6 => '<p>Dear {name},</p><p>Congratulations! You have received an award.</p><p>Regards,<br>{institute_name}</p>',
                    7 => '<p>Dear {name},</p><p>Your leave request has been approved.</p><p>Regards,<br>{institute_name}</p>',
                    8 => '<p>Dear {name},</p><p>Your leave request has been rejected.</p><p>Regards,<br>{institute_name}</p>',
                );
                $subj = isset($subjects[$etpl->id]) ? $subjects[$etpl->id] : $etpl->name;
                $body = isset($bodies[$etpl->id]) ? $bodies[$etpl->id] : '';
                $this->db->insert('email_templates_details', array(
                    'template_id' => $etpl->id, 'subject' => $subj,
                    'template_body' => $body, 'notified' => 1, 'branch_id' => $branchId,
                ));
            }
        }

        // 23. COMMON KENYAN TEXTBOOKS
        $bookCatIds = array();
        foreach ($this->db->where('branch_id', $branchId)->get('book_category')->result() as $bc) {
            $bookCatIds[$bc->name] = $bc->id;
        }
        $textbookCat = isset($bookCatIds['Textbooks']) ? $bookCatIds['Textbooks'] : 0;
        $fictionCat = isset($bookCatIds['Fiction']) ? $bookCatIds['Fiction'] : 0;
        $kiswCat = isset($bookCatIds['Kiswahili Literature']) ? $bookCatIds['Kiswahili Literature'] : 0;
        $refCat = isset($bookCatIds['Reference']) ? $bookCatIds['Reference'] : 0;

        $books = array(
            array('KLB Primary Mathematics Grade 4', 'KLB', $textbookCat, 'Kenya Literature Bureau', 750, 30),
            array('KLB Primary Mathematics Grade 5', 'KLB', $textbookCat, 'Kenya Literature Bureau', 750, 30),
            array('KLB Primary Mathematics Grade 6', 'KLB', $textbookCat, 'Kenya Literature Bureau', 750, 30),
            array('KLB Primary Science Grade 4', 'KLB', $textbookCat, 'Kenya Literature Bureau', 800, 25),
            array('KLB Primary Science Grade 5', 'KLB', $textbookCat, 'Kenya Literature Bureau', 800, 25),
            array('KLB Primary English Grade 4', 'KLB', $textbookCat, 'Kenya Literature Bureau', 700, 30),
            array('KLB Primary Kiswahili Grade 4', 'KLB', $textbookCat, 'Kenya Literature Bureau', 700, 30),
            array('Secondary English Form 1', 'KLB', $textbookCat, 'Kenya Literature Bureau', 850, 30),
            array('Secondary English Form 2', 'KLB', $textbookCat, 'Kenya Literature Bureau', 850, 30),
            array('Secondary English Form 3', 'KLB', $textbookCat, 'Kenya Literature Bureau', 850, 30),
            array('Secondary English Form 4', 'KLB', $textbookCat, 'Kenya Literature Bureau', 850, 30),
            array('Secondary Mathematics Form 1', 'KLB', $textbookCat, 'Kenya Literature Bureau', 900, 30),
            array('Secondary Mathematics Form 2', 'KLB', $textbookCat, 'Kenya Literature Bureau', 900, 30),
            array('Secondary Mathematics Form 3', 'KLB', $textbookCat, 'Kenya Literature Bureau', 900, 30),
            array('Secondary Mathematics Form 4', 'KLB', $textbookCat, 'Kenya Literature Bureau', 900, 30),
            array('KLB Biology Form 1', 'KLB', $textbookCat, 'Kenya Literature Bureau', 800, 25),
            array('KLB Biology Form 2', 'KLB', $textbookCat, 'Kenya Literature Bureau', 800, 25),
            array('KLB Chemistry Form 1', 'KLB', $textbookCat, 'Kenya Literature Bureau', 850, 25),
            array('KLB Chemistry Form 2', 'KLB', $textbookCat, 'Kenya Literature Bureau', 850, 25),
            array('KLB Physics Form 1', 'KLB', $textbookCat, 'Kenya Literature Bureau', 850, 25),
            array('KLB Physics Form 2', 'KLB', $textbookCat, 'Kenya Literature Bureau', 850, 25),
            array('KLB History Form 1', 'KLB', $textbookCat, 'Kenya Literature Bureau', 750, 25),
            array('KLB Geography Form 1', 'KLB', $textbookCat, 'Kenya Literature Bureau', 750, 25),
            array('KLB CRE Form 1', 'KLB', $textbookCat, 'Kenya Literature Bureau', 700, 25),
            array('Blossoms of the Savannah', 'Henry Ole Kulet', $fictionCat, 'Longhorn', 650, 20),
            array('The River and the Source', 'Margaret Ogola', $fictionCat, 'Focus Publishers', 550, 20),
            array('A Doll\'s House', 'Henrik Ibsen', $fictionCat, 'Penguin', 450, 15),
            array('Inheritance', 'David Mulwa', $fictionCat, 'Oxford', 500, 15),
            array('The Pearl', 'John Steinbeck', $fictionCat, 'Penguin', 400, 20),
            array('Chozi la Heri', 'Assumpta K. Matei', $kiswCat, 'Vide Muwa', 500, 20),
            array('Kidagaa Kimemwozea', 'Ken Walibora', $kiswCat, 'Longhorn', 550, 20),
            array('Kigogo', 'Pauline Kea', $kiswCat, 'Vide Muwa', 500, 15),
            array('Oxford English Dictionary', 'Oxford', $refCat, 'Oxford University Press', 1200, 10),
            array('Kamusi ya Kiswahili', 'TUKI', $refCat, 'Oxford University Press', 1000, 10),
            array('Atlas of Kenya', 'Survey of Kenya', $refCat, 'Survey of Kenya', 950, 10),
            array('Kenya Constitution 2010', 'Government', $refCat, 'Government Press', 300, 10),
        );
        foreach ($books as $bk) {
            $this->db->insert('book', array(
                'title' => $bk[0], 'author' => $bk[1], 'category_id' => $bk[2],
                'publisher' => $bk[3], 'price' => $bk[4], 'total_stock' => $bk[5],
                'issued_copies' => '0', 'purchase_date' => date('Y-m-d'),
                'isbn_no' => '', 'edition' => '', 'description' => '', 'branch_id' => $branchId,
            ));
        }

        // 24. KENYAN PUBLIC HOLIDAYS AS EVENTS
        $year = date('Y');
        $holidays = array(
            array("New Year's Day", "$year-01-01"), array('Mashujaa Day', "$year-10-20"),
            array('Jamhuri Day', "$year-12-12"), array('Madaraka Day', "$year-06-01"),
            array('Labour Day', "$year-05-01"), array('Christmas Day', "$year-12-25"),
            array('Boxing Day', "$year-12-26"), array('Good Friday', "$year-04-03"),
            array('Easter Monday', "$year-04-06"), array('Eid ul-Fitr (Tentative)', "$year-04-10"),
            array('Utamaduni Day', "$year-10-10"),
        );
        $holidayTypeId = $this->db->select('id')->where(array('name' => 'Holiday', 'branch_id' => $branchId))->get('event_types')->row();
        $htId = $holidayTypeId ? $holidayTypeId->id : 0;
        foreach ($holidays as $h) {
            $this->db->insert('event', array(
                'title' => $h[0], 'remark' => 'Public Holiday - ' . $h[0],
                'status' => 1, 'type' => $htId, 'audition' => '[]', 'selected_list' => '[]',
                'start_date' => $h[1], 'end_date' => $h[1], 'created_by' => '1', 'branch_id' => $branchId,
            ));
        }
    }

    public function seedUniversityDefaults($branchId)
    {
        $sessionId = $this->db->select('id')->order_by('id', 'DESC')->limit(1)->get('schoolyear')->row();
        $sid = $sessionId ? $sessionId->id : 1;

        // 1. SECTIONS (Intakes / Groups)
        $sections = array(
            array('January Intake', 100), array('May Intake', 100), array('September Intake', 100),
            array('Group A', 50), array('Group B', 50),
        );
        foreach ($sections as $sec) {
            $this->db->insert('section', array('name' => $sec[0], 'capacity' => $sec[1], 'branch_id' => $branchId));
        }
        $sectionIds = array();
        foreach ($this->db->where('branch_id', $branchId)->get('section')->result() as $s) {
            $sectionIds[] = $s->id;
        }

        // 2. CLASSES (Programmes / Courses - Year levels)
        $programmes = array(
            // School of Business
            array('Bachelor of Commerce - Year 1', '1', '844', 'senior_secondary'),
            array('Bachelor of Commerce - Year 2', '2', '844', 'senior_secondary'),
            array('Bachelor of Commerce - Year 3', '3', '844', 'senior_secondary'),
            array('Bachelor of Commerce - Year 4', '4', '844', 'senior_secondary'),
            // School of IT
            array('BSc. Information Technology - Year 1', '1', '844', 'senior_secondary'),
            array('BSc. Information Technology - Year 2', '2', '844', 'senior_secondary'),
            array('BSc. Information Technology - Year 3', '3', '844', 'senior_secondary'),
            array('BSc. Information Technology - Year 4', '4', '844', 'senior_secondary'),
            // School of Education
            array('Bachelor of Education - Year 1', '1', '844', 'senior_secondary'),
            array('Bachelor of Education - Year 2', '2', '844', 'senior_secondary'),
            array('Bachelor of Education - Year 3', '3', '844', 'senior_secondary'),
            array('Bachelor of Education - Year 4', '4', '844', 'senior_secondary'),
            // School of Engineering
            array('BSc. Engineering - Year 1', '1', '844', 'senior_secondary'),
            array('BSc. Engineering - Year 2', '2', '844', 'senior_secondary'),
            array('BSc. Engineering - Year 3', '3', '844', 'senior_secondary'),
            array('BSc. Engineering - Year 4', '4', '844', 'senior_secondary'),
            array('BSc. Engineering - Year 5', '5', '844', 'senior_secondary'),
            // Diploma Programmes
            array('Diploma in Business Management - Year 1', '1', '844', 'senior_secondary'),
            array('Diploma in Business Management - Year 2', '2', '844', 'senior_secondary'),
            array('Diploma in ICT - Year 1', '1', '844', 'senior_secondary'),
            array('Diploma in ICT - Year 2', '2', '844', 'senior_secondary'),
            // Certificate Programmes
            array('Certificate in IT - Semester 1', '1', '844', 'senior_secondary'),
            array('Certificate in IT - Semester 2', '2', '844', 'senior_secondary'),
            array('Certificate in Business - Semester 1', '1', '844', 'senior_secondary'),
            array('Certificate in Business - Semester 2', '2', '844', 'senior_secondary'),
        );
        foreach ($programmes as $cls) {
            $this->db->insert('class', array('name' => $cls[0], 'name_numeric' => $cls[1], 'curriculum_type' => $cls[2], 'level' => $cls[3], 'branch_id' => $branchId));
            $classId = $this->db->insert_id();
            foreach ($sectionIds as $secId) {
                $this->db->insert('sections_allocation', array('class_id' => $classId, 'section_id' => $secId));
            }
        }

        // 3. STUDENT CATEGORIES
        $categories = array('Regular (Self-Sponsored)', 'Government Sponsored (KUCCPS)', 'Scholarship', 'International Student', 'Part-Time', 'Distance Learning');
        foreach ($categories as $cat) {
            $this->db->insert('student_category', array('name' => $cat, 'branch_id' => $branchId));
        }

        // 4. DEPARTMENTS (Schools / Faculties)
        $departments = array(
            'Office of the Vice Chancellor', 'Academic Affairs', 'School of Business & Economics',
            'School of Information Technology', 'School of Education', 'School of Engineering',
            'School of Health Sciences', 'School of Arts & Social Sciences', 'School of Law',
            'School of Agriculture', 'Finance & Administration', 'Library Services',
            'Student Affairs', 'Research & Innovation', 'ICT Department', 'Support Staff',
        );
        foreach ($departments as $dept) {
            $this->db->insert('staff_department', array('name' => $dept, 'branch_id' => $branchId));
        }

        // 5. DESIGNATIONS
        $designations = array(
            'Vice Chancellor', 'Deputy Vice Chancellor', 'Dean', 'Associate Dean',
            'Head of Department', 'Professor', 'Associate Professor', 'Senior Lecturer',
            'Lecturer', 'Tutorial Fellow', 'Graduate Assistant', 'Lab Technician',
            'Registrar', 'Finance Officer', 'Librarian', 'ICT Officer', 'Administrator', 'Secretary',
        );
        foreach ($designations as $des) {
            $this->db->insert('staff_designation', array('name' => $des, 'branch_id' => $branchId));
        }

        // 6. SUBJECTS (Units / Modules)
        $units = array(
            // Common University Units
            array('Communication Skills', 'CMS 100', 'Theory'),
            array('HIV/AIDS Education', 'HIV 100', 'Theory'),
            array('Development Studies', 'DST 100', 'Theory'),
            array('Introduction to Computers', 'COM 100', 'Practical'),
            array('Entrepreneurship', 'ENT 100', 'Theory'),
            array('Research Methods', 'RES 300', 'Theory'),
            array('Business Ethics', 'BET 200', 'Theory'),
            // Business Units
            array('Principles of Management', 'BUS 101', 'Theory'),
            array('Financial Accounting I', 'ACC 101', 'Theory'),
            array('Financial Accounting II', 'ACC 201', 'Theory'),
            array('Business Mathematics', 'BMA 101', 'Theory'),
            array('Business Statistics', 'BST 201', 'Theory'),
            array('Microeconomics', 'ECO 101', 'Theory'),
            array('Macroeconomics', 'ECO 102', 'Theory'),
            array('Marketing Management', 'MKT 201', 'Theory'),
            array('Human Resource Management', 'HRM 201', 'Theory'),
            array('Business Law', 'BLW 101', 'Theory'),
            array('Corporate Finance', 'FIN 301', 'Theory'),
            array('Strategic Management', 'STM 401', 'Theory'),
            array('Operations Management', 'OPM 301', 'Theory'),
            // IT Units
            array('Introduction to Programming', 'ICS 101', 'Practical'),
            array('Data Structures & Algorithms', 'ICS 201', 'Practical'),
            array('Database Systems', 'ICS 202', 'Practical'),
            array('Computer Networks', 'ICS 203', 'Theory'),
            array('Web Development', 'ICS 204', 'Practical'),
            array('Object Oriented Programming', 'ICS 301', 'Practical'),
            array('Software Engineering', 'ICS 302', 'Theory'),
            array('Operating Systems', 'ICS 303', 'Theory'),
            array('Cyber Security', 'ICS 401', 'Theory'),
            array('Mobile Application Development', 'ICS 402', 'Practical'),
            array('Cloud Computing', 'ICS 403', 'Theory'),
            array('Artificial Intelligence', 'ICS 404', 'Theory'),
            array('IT Project Management', 'ICS 405', 'Theory'),
            // Education Units
            array('Philosophy of Education', 'EDU 101', 'Theory'),
            array('Psychology of Education', 'EDU 102', 'Theory'),
            array('Curriculum Development', 'EDU 201', 'Theory'),
            array('Educational Technology', 'EDU 202', 'Practical'),
            array('Teaching Practice', 'EDU 300', 'Practical'),
            array('Guidance & Counselling', 'EDU 301', 'Theory'),
            array('Special Needs Education', 'EDU 302', 'Theory'),
            // Engineering Units
            array('Engineering Mathematics I', 'EMA 101', 'Theory'),
            array('Engineering Mathematics II', 'EMA 201', 'Theory'),
            array('Engineering Drawing', 'EDR 101', 'Practical'),
            array('Mechanics of Machines', 'EME 201', 'Theory'),
            array('Thermodynamics', 'ETH 201', 'Theory'),
            array('Electrical Engineering', 'EEE 201', 'Theory'),
            array('Fluid Mechanics', 'EFM 301', 'Theory'),
            // Health Sciences Units
            array('Human Anatomy', 'HAN 101', 'Theory'),
            array('Human Physiology', 'HPH 101', 'Theory'),
            array('Biochemistry', 'BCH 101', 'Theory'),
            array('Pharmacology', 'PHA 201', 'Theory'),
            array('Community Health', 'CHE 201', 'Theory'),
            array('Nursing Fundamentals', 'NUR 101', 'Theory'),
        );
        foreach ($units as $sub) {
            $this->db->insert('subject', array('name' => $sub[0], 'subject_code' => $sub[1], 'subject_type' => $sub[2], 'subject_author' => '', 'branch_id' => $branchId));
        }

        // 7. EXAM TERMS (Semesters)
        $terms = array('Semester 1', 'Semester 2', 'Semester 3 (Trimester)');
        foreach ($terms as $term) {
            $this->db->insert('exam_term', array('name' => $term, 'branch_id' => $branchId, 'session_id' => $sid));
        }

        // 8. MARK DISTRIBUTIONS
        $distributions = array('CAT 1', 'CAT 2', 'Assignment', 'Practical', 'Final Exam');
        foreach ($distributions as $dist) {
            $this->db->insert('exam_mark_distribution', array('name' => $dist, 'branch_id' => $branchId));
        }

        // 9. EXAM HALLS
        $halls = array(
            array('Lecture Hall 1', 200), array('Lecture Hall 2', 200), array('Lecture Hall 3', 150),
            array('Exam Hall A', 300), array('Exam Hall B', 300),
            array('Computer Lab 1', 50), array('Computer Lab 2', 50),
            array('Science Lab', 40),
        );
        foreach ($halls as $hall) {
            $this->db->insert('exam_hall', array('hall_no' => $hall[0], 'seats' => $hall[1], 'branch_id' => $branchId));
        }

        // 10. UNIVERSITY GRADING SCALE
        $grades = array(
            array('A', '4.0', 70, 100, 'First Class Honours'),
            array('B+', '3.5', 65, 69, 'Second Class Honours (Upper)'),
            array('B', '3.0', 60, 64, 'Second Class Honours (Upper)'),
            array('B-', '2.7', 55, 59, 'Second Class Honours (Lower)'),
            array('C+', '2.3', 50, 54, 'Second Class Honours (Lower)'),
            array('C', '2.0', 45, 49, 'Pass'),
            array('C-', '1.7', 40, 44, 'Pass'),
            array('D', '1.0', 35, 39, 'Supplementary'),
            array('E', '0.0', 0, 34, 'Fail / Retake'),
        );
        foreach ($grades as $g) {
            $this->db->insert('grade', array('name' => $g[0], 'grade_point' => $g[1], 'lower_mark' => $g[2], 'upper_mark' => $g[3], 'remark' => $g[4], 'branch_id' => $branchId));
        }

        // 11. FEE TYPES
        $feeTypes = array(
            array('Tuition Fee', 'TUI', 'Semester tuition fee'),
            array('Registration Fee', 'REG', 'Annual registration'),
            array('Library Fee', 'LIB', 'Library and e-resources'),
            array('ICT Fee', 'ICT', 'Computer lab and internet'),
            array('Student Activity Fee', 'ACT', 'Clubs, sports, events'),
            array('Medical Fee', 'MED', 'University clinic/NHIF'),
            array('Examination Fee', 'EXM', 'Exam administration'),
            array('Caution Money', 'CAU', 'Refundable deposit'),
            array('ID Card Fee', 'IDC', 'Student ID card'),
            array('Accommodation Fee', 'ACC', 'Hostel per semester'),
            array('Meals/Catering Fee', 'CAT', 'Cafeteria meals'),
            array('Industrial Attachment Fee', 'ATT', 'Internship coordination'),
            array('Graduation Fee', 'GRD', 'Graduation ceremony'),
            array('Transcripts Fee', 'TRN', 'Academic transcripts'),
            array('Late Registration Fee', 'LRG', 'Late registration penalty'),
        );
        foreach ($feeTypes as $ft) {
            $this->db->insert('fees_type', array('name' => $ft[0], 'fee_code' => $ft[1], 'description' => $ft[2], 'branch_id' => $branchId));
        }

        // 12. LEAVE CATEGORIES
        $leaves = array(
            array('Annual Leave', 21), array('Sick Leave', 14), array('Maternity Leave', 90),
            array('Paternity Leave', 14), array('Sabbatical Leave', 180), array('Study Leave', 365),
            array('Compassionate Leave', 5), array('Conference Leave', 10),
        );
        foreach ($leaves as $lv) {
            $this->db->insert('leave_category', array('name' => $lv[0], 'role_id' => 0, 'days' => $lv[1], 'branch_id' => $branchId));
        }

        // 13. EVENT TYPES
        $events = array(
            array('Academic', 'fas fa-book'), array('Sports', 'fas fa-running'),
            array('Cultural', 'fas fa-music'), array('Graduation', 'fas fa-graduation-cap'),
            array('Orientation', 'fas fa-users'), array('Career Fair', 'fas fa-briefcase'),
            array('Research Seminar', 'fas fa-flask'), array('Workshop', 'fas fa-tools'),
            array('Alumni Event', 'fas fa-handshake'), array('Holiday', 'fas fa-calendar'),
        );
        foreach ($events as $ev) {
            $this->db->insert('event_types', array('name' => $ev[0], 'icon' => $ev[1], 'branch_id' => $branchId));
        }

        // 14. VOUCHER HEADS
        $vouchers = array(
            array('Student Tuition Collection', 'income'), array('Government Capitation', 'income'),
            array('Research Grants', 'income'), array('Donations & Endowments', 'income'),
            array('Facility Hire Income', 'income'), array('Consultancy Income', 'income'),
            array('Staff Salaries', 'expense'), array('Stationery & Office Supplies', 'expense'),
            array('Electricity & Water', 'expense'), array('Internet & Telecommunications', 'expense'),
            array('Equipment & Furniture', 'expense'), array('Maintenance & Repairs', 'expense'),
            array('Library Acquisitions', 'expense'), array('Research Expenditure', 'expense'),
            array('Student Welfare', 'expense'), array('Transport & Travel', 'expense'),
            array('Insurance', 'expense'), array('Marketing & Recruitment', 'expense'),
            array('Cleaning & Sanitation', 'expense'), array('Bank Charges', 'expense'),
            array('Lab Supplies & Chemicals', 'expense'), array('Sports & Recreation', 'expense'),
        );
        foreach ($vouchers as $vh) {
            $this->db->insert('voucher_head', array('name' => $vh[0], 'type' => $vh[1], 'system' => 0, 'branch_id' => $branchId));
        }

        // 15. BOOK CATEGORIES
        $bookCats = array(
            'Textbooks', 'Reference Books', 'Journals & Periodicals', 'E-Books',
            'Theses & Dissertations', 'Conference Proceedings', 'Law Reports',
            'Engineering Manuals', 'Medical References', 'Fiction & Literature',
        );
        foreach ($bookCats as $bc) {
            $this->db->insert('book_category', array('name' => $bc, 'branch_id' => $branchId));
        }

        // 16. SALARY TEMPLATES
        $salaryTemplates = array(
            array('Vice Chancellor', 500000), array('Deputy Vice Chancellor', 400000),
            array('Professor', 250000), array('Associate Professor', 200000),
            array('Senior Lecturer', 150000), array('Lecturer', 120000),
            array('Tutorial Fellow', 80000), array('Graduate Assistant', 55000),
            array('Registrar', 180000), array('Finance Officer', 150000),
            array('Dean', 200000), array('Head of Department', 170000),
            array('Librarian', 90000), array('ICT Officer', 100000),
            array('Lab Technician', 60000), array('Administrator', 70000),
            array('Secretary', 45000), array('Support Staff', 30000),
        );
        foreach ($salaryTemplates as $st) {
            $this->db->insert('salary_template', array('name' => $st[0], 'basic_salary' => $st[1], 'overtime_salary' => '0', 'branch_id' => $branchId));
            $templateId = $this->db->insert_id();
            $basic = $st[1];
            $nhif = min(1700, max(500, round($basic * 0.02)));
            $paye = max(0, round(($basic - 24000) * 0.25));
            $this->db->insert('salary_template_details', array('salary_template_id' => $templateId, 'name' => 'House Allowance', 'amount' => round($basic * 0.20), 'type' => 1));
            $this->db->insert('salary_template_details', array('salary_template_id' => $templateId, 'name' => 'Transport Allowance', 'amount' => round($basic * 0.10), 'type' => 1));
            $this->db->insert('salary_template_details', array('salary_template_id' => $templateId, 'name' => 'Research Allowance', 'amount' => round($basic * 0.05), 'type' => 1));
            $this->db->insert('salary_template_details', array('salary_template_id' => $templateId, 'name' => 'NHIF', 'amount' => 1700, 'type' => 2));
            $this->db->insert('salary_template_details', array('salary_template_id' => $templateId, 'name' => 'NSSF', 'amount' => 200, 'type' => 2));
            $this->db->insert('salary_template_details', array('salary_template_id' => $templateId, 'name' => 'PAYE', 'amount' => $paye, 'type' => 2));
        }

        // 17. HOSTEL CATEGORIES
        $hostelCats = array(
            array('Male Hostel', 'Male student accommodation', 'Male'),
            array('Female Hostel', 'Female student accommodation', 'Female'),
            array('Postgraduate Hostel', 'Masters and PhD students', 'Mixed'),
        );
        foreach ($hostelCats as $hc) {
            $this->db->insert('hostel_category', array('name' => $hc[0], 'description' => $hc[1], 'branch_id' => $branchId, 'type' => $hc[2]));
        }

        // 18. PAYMENT CONFIG
        $exists = $this->db->get_where('payment_config', array('branch_id' => $branchId))->num_rows();
        if ($exists == 0) {
            $this->db->insert('payment_config', array(
                'branch_id' => $branchId, 'paypal_username' => '', 'paypal_password' => '',
                'paypal_signature' => '', 'paypal_email' => '', 'paypal_sandbox' => 1, 'paypal_status' => 0,
                'stripe_secret' => '', 'stripe_demo' => 1, 'stripe_status' => 0,
                'mpesa_consumer_key' => '', 'mpesa_consumer_secret' => '', 'mpesa_shortcode' => '',
                'mpesa_passkey' => '', 'mpesa_sandbox' => 1, 'mpesa_status' => 0,
            ));
        }

        // 19. ATTACHMENTS TYPES
        $attachTypes = array('Lecture Notes', 'Assignment', 'Past Papers', 'Course Outline', 'Research Papers', 'Timetable', 'Announcements');
        foreach ($attachTypes as $at) {
            $this->db->insert('attachments_type', array('name' => $at, 'branch_id' => $branchId));
        }
    }
}
