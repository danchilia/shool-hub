<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Setup_guide extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $db = $this->db;

        // Superadmin picks a school via ?branch_id=X; school admin uses own branch
        if (is_superadmin_loggedin()) {
            $branchID = (int) $this->input->get('branch_id');
            if (empty($branchID)) {
                // Show branch picker — no steps yet
                $this->data['branches']  = $db->order_by('name')->get('branch')->result_array();
                $this->data['branchID']  = 0;
                $this->data['steps']     = array();
                $this->data['total']     = 0;
                $this->data['complete']  = 0;
                $this->data['percent']   = 0;
                $this->data['title']     = 'School Setup Guide';
                $this->data['sub_page']  = 'setup_guide/index';
                $this->data['main_menu'] = 'setup_guide';
                $this->load->view('layout/index', $this->data);
                return;
            }
            $sessionID = get_session_id();
            $this->data['branches'] = $db->order_by('name')->get('branch')->result_array();
        } else {
            $branchID  = get_loggedin_branch_id();
            $sessionID = get_session_id();
            $this->data['branches'] = array();
        }

        $this->data['branchID'] = $branchID;

        // Helper: count rows in a table for this branch
        $count = function ($table, $extra = array()) use ($db, $branchID) {
            $db->where('branch_id', $branchID);
            foreach ($extra as $k => $v) {
                $db->where($k, $v);
            }
            return $db->count_all_results($table);
        };

        // Count rows in a table for this branch + session
        $countSession = function ($table) use ($db, $branchID, $sessionID) {
            $db->where('branch_id', $branchID)->where('session_id', $sessionID);
            return $db->count_all_results($table);
        };

        $steps = array(

            /* ── PHASE 1: Foundation ─────────────────────────────────── */
            array(
                'phase'       => 1,
                'phase_label' => 'Foundation',
                'phase_color' => 'primary',
                'number'      => 1,
                'icon'        => 'fas fa-school',
                'title'       => 'School Profile',
                'desc'        => 'Set your school name, address, logo, currency, and contact details. Every receipt, report card, and letter uses this.',
                'example'     => 'e.g. Nairobi Academy · KES currency · 0700 000 000 · info@nairobi-academy.ac.ke',
                'url'         => base_url('school_settings'),
                'count'       => 1, // always at least the branch record exists
                'unit'        => 'profile',
                'permission'  => get_permission('school_settings', 'is_view'),
            ),
            array(
                'phase'       => 1,
                'phase_label' => 'Foundation',
                'phase_color' => 'primary',
                'number'      => 2,
                'icon'        => 'fas fa-tags',
                'title'       => 'Student Categories',
                'desc'        => 'Define the types of students your school has. Required before admitting any student.',
                'example'     => 'e.g. Day Scholar · Boarding · Bursary Student · Special Needs',
                'url'         => base_url('student/category'),
                'count'       => $count('student_category'),
                'unit'        => 'categories',
                'permission'  => get_permission('student_category', 'is_view'),
            ),
            array(
                'phase'       => 1,
                'phase_label' => 'Foundation',
                'phase_color' => 'primary',
                'number'      => 3,
                'icon'        => 'fas fa-door-open',
                'title'       => 'Sections',
                'desc'        => 'Create the section/stream names that will be reused across all classes.',
                'example'     => 'e.g. A · B · C — or Morning · Afternoon for double-shift schools',
                'url'         => base_url('sections'),
                'count'       => $count('section'),
                'unit'        => 'sections',
                'permission'  => get_permission('section', 'is_view'),
            ),

            /* ── PHASE 2: Academic Structure ─────────────────────────── */
            array(
                'phase'       => 2,
                'phase_label' => 'Academic Structure',
                'phase_color' => 'purple',
                'number'      => 4,
                'icon'        => 'fas fa-layer-group',
                'title'       => 'Classes',
                'desc'        => 'Create every class and attach sections to it. This is the backbone of the timetable, exams, and attendance.',
                'example'     => 'e.g. Form 1 (Sections A, B) · Form 2 (A, B) · PP1 · Grade 6',
                'url'         => base_url('classes'),
                'count'       => $count('class'),
                'unit'        => 'classes',
                'permission'  => get_permission('classes', 'is_view'),
            ),
            array(
                'phase'       => 2,
                'phase_label' => 'Academic Structure',
                'phase_color' => 'purple',
                'number'      => 5,
                'icon'        => 'fas fa-book',
                'title'       => 'Subjects',
                'desc'        => 'Add every subject taught in the school. Include a subject code for reporting.',
                'example'     => 'e.g. Mathematics (MAT) · English (ENG) · Kiswahili (KIS) · Biology (BIO)',
                'url'         => base_url('subject'),
                'count'       => $count('subject'),
                'unit'        => 'subjects',
                'permission'  => get_permission('subject', 'is_view'),
            ),
            array(
                'phase'       => 2,
                'phase_label' => 'Academic Structure',
                'phase_color' => 'purple',
                'number'      => 6,
                'icon'        => 'fas fa-link',
                'title'       => 'Assign Subjects to Classes',
                'desc'        => 'Map which subjects each class/section studies. Marks register and timetable depend on this.',
                'example'     => 'e.g. Form 1A → Maths, English, Kiswahili, Biology, Chemistry, History',
                'url'         => base_url('subject/class_assign'),
                'count'       => $countSession('subject_assign'),
                'unit'        => 'assignments',
                'permission'  => get_permission('subject_class_assign', 'is_view'),
            ),

            /* ── PHASE 3: Staff ──────────────────────────────────────── */
            array(
                'phase'       => 3,
                'phase_label' => 'Staff & HR',
                'phase_color' => 'teal',
                'number'      => 7,
                'icon'        => 'fas fa-sitemap',
                'title'       => 'Departments',
                'desc'        => 'Create the academic and administrative departments. Required before adding any staff member.',
                'example'     => 'e.g. Science Department · Arts Department · Administration · Physical Education',
                'url'         => base_url('employee/department'),
                'count'       => $count('staff_department'),
                'unit'        => 'departments',
                'permission'  => get_permission('department', 'is_view'),
            ),
            array(
                'phase'       => 3,
                'phase_label' => 'Staff & HR',
                'phase_color' => 'teal',
                'number'      => 8,
                'icon'        => 'fas fa-id-badge',
                'title'       => 'Designations',
                'desc'        => 'Define job titles for staff members. Required before adding any staff member.',
                'example'     => 'e.g. Class Teacher · Head of Department · Deputy Principal · Secretary · Librarian',
                'url'         => base_url('employee/designation'),
                'count'       => $count('staff_designation'),
                'unit'        => 'designations',
                'permission'  => get_permission('designation', 'is_view'),
            ),
            array(
                'phase'       => 3,
                'phase_label' => 'Staff & HR',
                'phase_color' => 'teal',
                'number'      => 9,
                'icon'        => 'fas fa-chalkboard-teacher',
                'title'       => 'Add Teachers & Staff',
                'desc'        => 'Register every teacher and support staff member with their department, designation, and login.',
                'example'     => 'e.g. Mr. James Kamau · Science Dept · Teacher · joining 01/01/2026 · email: jkamau@school.ke',
                'url'         => base_url('employee/add'),
                'count'       => $count('staff'),
                'unit'        => 'staff members',
                'permission'  => get_permission('employee', 'is_add'),
            ),
            array(
                'phase'       => 3,
                'phase_label' => 'Staff & HR',
                'phase_color' => 'teal',
                'number'      => 10,
                'icon'        => 'fas fa-user-tie',
                'title'       => 'Assign Class Teachers',
                'desc'        => 'Assign a class teacher to each class/section. They see the class on their dashboard.',
                'example'     => 'e.g. Mr. Kamau → Form 2A · Ms. Njeri → Form 1B',
                'url'         => base_url('subject/teacher_assign'),
                'count'       => $countSession('teacher_allocation'),
                'unit'        => 'assignments',
                'permission'  => get_permission('teacher_class_assign', 'is_view'),
            ),

            /* ── PHASE 4: Finance ────────────────────────────────────── */
            array(
                'phase'       => 4,
                'phase_label' => 'Finance Setup',
                'phase_color' => 'success',
                'number'      => 11,
                'icon'        => 'fas fa-receipt',
                'title'       => 'Fee Types',
                'desc'        => 'Create the categories of fees your school charges. These become the line items in fee groups.',
                'example'     => 'e.g. Tuition Fee · Transport Fee · Boarding Fee · Activity Fee · KCSE Registration',
                'url'         => base_url('fees/type'),
                'count'       => $count('fees_type'),
                'unit'        => 'fee types',
                'permission'  => get_permission('fees_type', 'is_view'),
            ),
            array(
                'phase'       => 4,
                'phase_label' => 'Finance Setup',
                'phase_color' => 'success',
                'number'      => 12,
                'icon'        => 'fas fa-folder-open',
                'title'       => 'Fee Groups',
                'desc'        => 'Bundle fee types with amounts and due dates into a group per term or year.',
                'example'     => 'e.g. Term 1 2026 (Tuition KES 25,000 due 15 Jan · Activity KES 2,000 due 20 Jan)',
                'url'         => base_url('fees/group'),
                'count'       => $countSession('fee_groups'),
                'unit'        => 'fee groups',
                'permission'  => get_permission('fees_group', 'is_view'),
            ),
            array(
                'phase'       => 4,
                'phase_label' => 'Finance Setup',
                'phase_color' => 'success',
                'number'      => 13,
                'icon'        => 'fas fa-file-invoice-dollar',
                'title'       => 'Assign Fees to Students',
                'desc'        => 'Allocate a fee group to a student or an entire class so the system knows what each student owes.',
                'example'     => 'e.g. Assign "Term 1 2026" to all Form 1 students · or assign individually',
                'url'         => base_url('fees/allocation'),
                'count'       => $countSession('fee_allocation'),
                'unit'        => 'allocations',
                'permission'  => get_permission('fees_allocation', 'is_view'),
            ),

            /* ── PHASE 5: Students ───────────────────────────────────── */
            array(
                'phase'       => 5,
                'phase_label' => 'Student Enrollment',
                'phase_color' => 'warning',
                'number'      => 14,
                'icon'        => 'fas fa-user-graduate',
                'title'       => 'Admit Students',
                'desc'        => 'Create student accounts with class, section, guardian details, and login credentials.',
                'example'     => 'e.g. John Kamau · Form 1A · Reg No: 2026/001 · Guardian: Mrs. Kamau 0712 345 678',
                'url'         => base_url('student/add'),
                'count'       => $countSession('enroll'),
                'unit'        => 'students',
                'permission'  => get_permission('student', 'is_add'),
            ),
            array(
                'phase'       => 5,
                'phase_label' => 'Student Enrollment',
                'phase_color' => 'warning',
                'number'      => 15,
                'icon'        => 'fas fa-file-upload',
                'title'       => 'Bulk Import Students (CSV)',
                'desc'        => 'Have 50+ students? Download the template CSV, fill it in, and upload all at once.',
                'example'     => 'e.g. Download template → fill names, classes, parents → upload → all accounts created',
                'url'         => base_url('student/csv_import'),
                'count'       => null, // informational only
                'unit'        => '',
                'permission'  => get_permission('multiple_import', 'is_add'),
            ),

            /* ── PHASE 6: Exams ──────────────────────────────────────── */
            array(
                'phase'       => 6,
                'phase_label' => 'Exam Setup',
                'phase_color' => 'danger',
                'number'      => 16,
                'icon'        => 'fas fa-calendar-alt',
                'title'       => 'Exam Terms',
                'desc'        => 'Define the exam periods or terms used in report cards and marksheets.',
                'example'     => 'e.g. Term 1 · Term 2 · Term 3 — or Mid-Term · End Term',
                'url'         => base_url('exam/term'),
                'count'       => $count('exam_term'),
                'unit'        => 'terms',
                'permission'  => get_permission('exam_term', 'is_view'),
            ),
            array(
                'phase'       => 6,
                'phase_label' => 'Exam Setup',
                'phase_color' => 'danger',
                'number'      => 17,
                'icon'        => 'fas fa-tasks',
                'title'       => 'Mark Distribution',
                'desc'        => 'Define the assessment components (CATs, End Term) with their full marks and pass marks.',
                'example'     => 'e.g. CAT 1 (30 marks, pass 15) · CAT 2 (30 marks) · End Term Exam (40 marks)',
                'url'         => base_url('exam/mark_distribution'),
                'count'       => $count('exam_mark_distribution'),
                'unit'        => 'components',
                'permission'  => get_permission('mark_distribution', 'is_view'),
            ),
            array(
                'phase'       => 6,
                'phase_label' => 'Exam Setup',
                'phase_color' => 'danger',
                'number'      => 18,
                'icon'        => 'fas fa-star-half-alt',
                'title'       => 'Grade Scale',
                'desc'        => 'Set the percentage bands that determine each letter grade and its grade point.',
                'example'     => 'e.g. A: 75–100 (4.0) · B+: 65–74 (3.5) · B: 60–64 (3.0) · C: 50–59 (2.0) · E: 0–39 (0.0)',
                'url'         => base_url('exam/grade'),
                'count'       => $count('grade'),
                'unit'        => 'grade bands',
                'permission'  => get_permission('exam_grade', 'is_view'),
            ),
            array(
                'phase'       => 6,
                'phase_label' => 'Exam Setup',
                'phase_color' => 'danger',
                'number'      => 19,
                'icon'        => 'fas fa-clipboard-list',
                'title'       => 'Create Exams',
                'desc'        => 'Create exam entries by linking a term, a mark distribution, and an exam type (Marks / Grade / Both).',
                'example'     => 'e.g. Form 1 Term 1 2026 · Type: Marks & Grade · Distribution: CAT 1, End Term',
                'url'         => base_url('exam'),
                'count'       => $count('exam'),
                'unit'        => 'exams',
                'permission'  => get_permission('exam', 'is_view'),
            ),

            /* ── PHASE 7: CBC Setup ──────────────────────────────────── */
            array(
                'phase'       => 7,
                'phase_label' => 'CBC Setup',
                'phase_color' => 'indigo',
                'number'      => 20,
                'icon'        => 'fas fa-book-open',
                'title'       => 'CBC Learning Areas',
                'desc'        => 'Define Learning Areas for each CBC level: PP, Lower Primary, Upper Primary, Junior Secondary, and Senior Secondary pathways.',
                'example'     => 'e.g. English · Mathematics · Integrated Science · Creative Arts and Sports (Grade 7–9)',
                'url'         => base_url('cbc/learning_areas'),
                'count'       => $count('cbc_learning_areas'),
                'unit'        => 'learning areas',
                'permission'  => get_permission('cbc_learning_areas', 'is_view'),
            ),
            array(
                'phase'       => 7,
                'phase_label' => 'CBC Setup',
                'phase_color' => 'indigo',
                'number'      => 21,
                'icon'        => 'fas fa-sitemap',
                'title'       => 'CBC Strands',
                'desc'        => 'Add Strands under each Learning Area. Strands are the major topic groupings within a learning area.',
                'example'     => 'e.g. Listening and Speaking · Reading · Writing · Grammar (under English)',
                'url'         => base_url('cbc/strands'),
                'count'       => $count('cbc_strands'),
                'unit'        => 'strands',
                'permission'  => get_permission('cbc_strands', 'is_view'),
            ),
            array(
                'phase'       => 7,
                'phase_label' => 'CBC Setup',
                'phase_color' => 'indigo',
                'number'      => 22,
                'icon'        => 'fas fa-list-ul',
                'title'       => 'Sub-Strands & Learning Outcomes',
                'desc'        => 'Break strands into sub-strands and attach specific learning outcomes (skills) used during assessment entry.',
                'example'     => 'e.g. Phonics · Fluency · Comprehension (sub-strands under Reading strand)',
                'url'         => base_url('cbc/sub_strands'),
                'count'       => $count('cbc_sub_strands'),
                'unit'        => 'sub-strands',
                'permission'  => get_permission('cbc_sub_strands', 'is_view'),
            ),
            array(
                'phase'       => 7,
                'phase_label' => 'CBC Setup',
                'phase_color' => 'indigo',
                'number'      => 23,
                'icon'        => 'fas fa-clipboard-check',
                'title'       => 'Enter CBC Assessments',
                'desc'        => 'Rate students on the 8-level KNEC competency scale (EE2–BE1) per learning area, strand, and sub-strand.',
                'example'     => 'e.g. Grade 5A · Term 1 2026 · Mathematics · Jane Wanjiku → ME2',
                'url'         => base_url('cbc/assessment'),
                'count'       => $count('cbc_assessment'),
                'unit'        => 'assessment records',
                'permission'  => get_permission('cbc_assessment', 'is_add'),
            ),
            array(
                'phase'       => 7,
                'phase_label' => 'CBC Setup',
                'phase_color' => 'indigo',
                'number'      => 24,
                'icon'        => 'fas fa-user-check',
                'title'       => 'Holistic Development Profile',
                'desc'        => 'Rate learners on 7 CBC competency domains: Communication, Creativity, Critical Thinking, Citizenship, Digital Literacy, Learning to Learn, Physical Health.',
                'example'     => 'e.g. Grade 4B · Term 1 · John Kamau → Communication: ME · Creativity: EE',
                'url'         => base_url('cbc/holistic'),
                'count'       => $count('cbc_holistic_ratings'),
                'unit'        => 'ratings',
                'permission'  => get_permission('cbc_holistic', 'is_view'),
            ),
        );

        // Calculate progress: steps with count > 0 (skip informational step 15)
        $total    = 0;
        $complete = 0;
        foreach ($steps as $s) {
            if ($s['count'] !== null && $s['permission']) {
                $total++;
                if ($s['count'] > 0) {
                    $complete++;
                }
            }
        }

        $this->data['steps']    = $steps;
        $this->data['total']    = $total;
        $this->data['complete'] = $complete;
        $this->data['percent']  = $total > 0 ? round(($complete / $total) * 100) : 0;
        $this->data['title']    = 'School Setup Guide';
        $this->data['sub_page'] = 'setup_guide/index';
        $this->data['main_menu'] = 'setup_guide';
        $this->load->view('layout/index', $this->data);
    }
}
