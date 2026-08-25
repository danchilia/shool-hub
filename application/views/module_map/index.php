<?php
$role_labels = [
    'A' => ['label' => 'Admin',   'class' => 'bg-danger'],
    'T' => ['label' => 'Teacher', 'class' => 'bg-info'],
    'P' => ['label' => 'Parent',  'class' => 'bg-success'],
    'S' => ['label' => 'Student', 'class' => 'bg-warning text-dark'],
];

$categories = [
    [
        'name'    => 'Admission & Enrollment',
        'icon'    => 'fas fa-user-plus',
        'color'   => '#4e73df',
        'modules' => [
            ['name' => 'Create Admission',         'url' => 'student/add',                'icon' => 'fas fa-file-alt',           'roles' => ['A']],
            ['name' => 'Online Admission Portal',  'url' => 'admission_portal',           'icon' => 'fas fa-globe',              'roles' => ['A']],
            ['name' => 'Admission Requests',       'url' => 'admission_request',          'icon' => 'fas fa-inbox',              'roles' => ['A']],
            ['name' => 'Bulk Student Import',      'url' => 'student/csv_import',         'icon' => 'fas fa-file-upload',        'roles' => ['A']],
            ['name' => 'Student Categories',       'url' => 'student/category',           'icon' => 'fas fa-tags',               'roles' => ['A']],
            ['name' => 'NEMIS Data Export',        'url' => 'student/nemis_export',       'icon' => 'fas fa-database',           'roles' => ['A']],
        ],
    ],
    [
        'name'    => 'Student Management',
        'icon'    => 'fas fa-user-graduate',
        'color'   => '#1cc88a',
        'modules' => [
            ['name' => 'Student List',             'url' => 'student/view',               'icon' => 'fas fa-list',               'roles' => ['A', 'T']],
            ['name' => 'Generate ID Cards',        'url' => 'student/generate_idcard',    'icon' => 'fas fa-id-card',            'roles' => ['A']],
            ['name' => 'Student Login Access',     'url' => 'student/disable_authentication','icon' => 'fas fa-user-lock',       'roles' => ['A']],
            ['name' => 'Parent / Guardian List',   'url' => 'parents/view',               'icon' => 'fas fa-users',              'roles' => ['A']],
            ['name' => 'Add Parent',               'url' => 'parents/add',                'icon' => 'fas fa-user-plus',          'roles' => ['A']],
            ['name' => 'Parent Login Access',      'url' => 'parents/disable_authentication','icon' => 'fas fa-lock',            'roles' => ['A']],
        ],
    ],
    [
        'name'    => 'Human Resources (HRM)',
        'icon'    => 'fas fa-briefcase',
        'color'   => '#f6c23e',
        'modules' => [
            ['name' => 'Employee List',            'url' => 'employee/view',              'icon' => 'fas fa-users',              'roles' => ['A']],
            ['name' => 'Departments',              'url' => 'employee/department',        'icon' => 'fas fa-building',           'roles' => ['A']],
            ['name' => 'Designations',             'url' => 'employee/designation',       'icon' => 'fas fa-sitemap',            'roles' => ['A']],
            ['name' => 'Add Employee',             'url' => 'employee/add',               'icon' => 'fas fa-user-tie',           'roles' => ['A']],
            ['name' => 'Salary Templates',         'url' => 'payroll/salary_template',    'icon' => 'fas fa-file-invoice-dollar','roles' => ['A']],
            ['name' => 'Salary Assignment',        'url' => 'payroll/salary_assign',      'icon' => 'fas fa-user-tag',           'roles' => ['A']],
            ['name' => 'Salary Payment',           'url' => 'payroll',                    'icon' => 'fas fa-money-check-alt',    'roles' => ['A']],
            ['name' => 'Advance Salary',           'url' => 'advance_salary',             'icon' => 'fas fa-hand-holding-usd',   'roles' => ['A', 'T']],
            ['name' => 'Leave Category',           'url' => 'leave/category',             'icon' => 'fas fa-folder-open',        'roles' => ['A']],
            ['name' => 'Leave Applications',       'url' => 'leave',                      'icon' => 'fas fa-calendar-times',     'roles' => ['A', 'T']],
            ['name' => 'Awards',                   'url' => 'award',                      'icon' => 'fas fa-trophy',             'roles' => ['A']],
        ],
    ],
    [
        'name'    => 'Academic Management',
        'icon'    => 'fas fa-book-open',
        'color'   => '#36b9cc',
        'modules' => [
            ['name' => 'Classes & Sections',       'url' => 'classes',                    'icon' => 'fas fa-door-open',          'roles' => ['A']],
            ['name' => 'Assign Class Teacher',     'url' => 'classes/teacher_allocation', 'icon' => 'fas fa-chalkboard-teacher', 'roles' => ['A']],
            ['name' => 'Subjects',                 'url' => 'subject/index',              'icon' => 'fas fa-book',               'roles' => ['A']],
            ['name' => 'Subject–Class Assign',     'url' => 'subject/class_assign',       'icon' => 'fas fa-link',               'roles' => ['A']],
            ['name' => 'Subject–Teacher Assign',   'url' => 'subject/teacher_assign',     'icon' => 'fas fa-user-edit',          'roles' => ['A']],
            ['name' => 'Class Timetable',          'url' => 'timetable/viewclass',        'icon' => 'fas fa-calendar-alt',       'roles' => ['A', 'T']],
            ['name' => 'Attachments',              'url' => 'attachments',                'icon' => 'fas fa-paperclip',          'roles' => ['A', 'T']],
            ['name' => 'Student Promotion',        'url' => 'student/transfer',           'icon' => 'fas fa-level-up-alt',       'roles' => ['A']],
        ],
    ],
    [
        'name'    => 'CBC Assessment',
        'icon'    => 'fas fa-tasks',
        'color'   => '#6f42c1',
        'modules' => [
            ['name' => 'Learning Areas',           'url' => 'cbc/learning_areas',         'icon' => 'fas fa-layer-group',        'roles' => ['A']],
            ['name' => 'Strands & Sub-strands',    'url' => 'cbc/strands',                'icon' => 'fas fa-project-diagram',    'roles' => ['A']],
            ['name' => 'Assessment Entry',         'url' => 'cbc/assessment',             'icon' => 'fas fa-pen-alt',            'roles' => ['A', 'T']],
            ['name' => 'Behaviour Assessment',     'url' => 'cbc/behaviour_assessment',   'icon' => 'fas fa-heart',              'roles' => ['A', 'T']],
            ['name' => 'CBC Report Card',          'url' => 'cbc/report_card',            'icon' => 'fas fa-file-alt',           'roles' => ['A', 'T']],
        ],
    ],
    [
        'name'    => 'Examinations',
        'icon'    => 'fas fa-pencil-alt',
        'color'   => '#e74a3b',
        'modules' => [
            ['name' => 'Exam Terms',               'url' => 'exam/term',                  'icon' => 'fas fa-calendar',           'roles' => ['A']],
            ['name' => 'Exam Halls',               'url' => 'exam/hall',                  'icon' => 'fas fa-building',           'roles' => ['A']],
            ['name' => 'Mark Distribution',        'url' => 'exam/mark_distribution',     'icon' => 'fas fa-percentage',         'roles' => ['A']],
            ['name' => 'Exam Setup',               'url' => 'exam',                       'icon' => 'fas fa-cog',                'roles' => ['A']],
            ['name' => 'Exam Timetable',           'url' => 'timetable/viewexam',         'icon' => 'fas fa-calendar-check',     'roles' => ['A', 'T']],
            ['name' => 'Mark Entry',               'url' => 'exam/mark_entry',            'icon' => 'fas fa-keyboard',           'roles' => ['A', 'T']],
            ['name' => 'Grade Ranges',             'url' => 'exam/grade',                 'icon' => 'fas fa-chart-bar',          'roles' => ['A']],
            ['name' => 'Report Card',              'url' => 'exam/marksheet',             'icon' => 'fas fa-graduation-cap',     'roles' => ['A', 'T']],
            ['name' => 'Tabulation Sheet',         'url' => 'exam/tabulation_sheet',      'icon' => 'fas fa-table',              'roles' => ['A', 'T']],
        ],
    ],
    [
        'name'    => 'Hostel Management',
        'icon'    => 'fas fa-hotel',
        'color'   => '#fd7e14',
        'modules' => [
            ['name' => 'Hostel Master',            'url' => 'hostels',                    'icon' => 'fas fa-building',           'roles' => ['A']],
            ['name' => 'Hostel Rooms',             'url' => 'hostels/room',               'icon' => 'fas fa-bed',                'roles' => ['A']],
            ['name' => 'Room Categories',          'url' => 'hostels/category',           'icon' => 'fas fa-th-large',           'roles' => ['A']],
            ['name' => 'Allocation Report',        'url' => 'hostels/allocation_report',  'icon' => 'fas fa-clipboard-list',     'roles' => ['A']],
        ],
    ],
    [
        'name'    => 'Transport Management',
        'icon'    => 'fas fa-bus',
        'color'   => '#20c997',
        'modules' => [
            ['name' => 'Routes',                   'url' => 'transport/route',            'icon' => 'fas fa-route',              'roles' => ['A']],
            ['name' => 'Vehicles',                 'url' => 'transport/vehicle',          'icon' => 'fas fa-bus-alt',            'roles' => ['A']],
            ['name' => 'Stoppages / Stops',        'url' => 'transport/stoppage',         'icon' => 'fas fa-map-marker-alt',     'roles' => ['A']],
            ['name' => 'Assign Vehicle',           'url' => 'transport/assign',           'icon' => 'fas fa-user-check',         'roles' => ['A']],
            ['name' => 'Allocation Report',        'url' => 'transport/report',           'icon' => 'fas fa-list-ol',            'roles' => ['A']],
            ['name' => 'GPS Bus Tracking',         'url' => 'bus_tracking',               'icon' => 'fas fa-satellite',          'roles' => ['A']],
        ],
    ],
    [
        'name'    => 'Attendance',
        'icon'    => 'fas fa-user-check',
        'color'   => '#17a2b8',
        'modules' => [
            ['name' => 'Student Attendance',       'url' => 'attendance/student_entry',   'icon' => 'fas fa-user-check',         'roles' => ['A', 'T']],
            ['name' => 'Employee Attendance',      'url' => 'attendance/employees_entry', 'icon' => 'fas fa-briefcase',          'roles' => ['A']],
            ['name' => 'Exam Attendance',          'url' => 'attendance/exam_entry',      'icon' => 'fas fa-clipboard-check',    'roles' => ['A', 'T']],
            ['name' => 'Biometric Devices',        'url' => 'biometric/devices',          'icon' => 'fas fa-fingerprint',        'roles' => ['A']],
            ['name' => 'ID Mapping',               'url' => 'biometric/mapping',          'icon' => 'fas fa-link',               'roles' => ['A']],
            ['name' => 'Biometric Import CSV',     'url' => 'biometric/import',           'icon' => 'fas fa-file-import',        'roles' => ['A']],
            ['name' => 'Biometric Scan Logs',      'url' => 'biometric/logs',             'icon' => 'fas fa-list',               'roles' => ['A']],
        ],
    ],
    [
        'name'    => 'Library',
        'icon'    => 'fas fa-book',
        'color'   => '#6610f2',
        'modules' => [
            ['name' => 'Book Management',          'url' => 'library/book',               'icon' => 'fas fa-book',               'roles' => ['A']],
            ['name' => 'Book Categories',          'url' => 'library/category',           'icon' => 'fas fa-tags',               'roles' => ['A']],
            ['name' => 'My Issued Books',          'url' => 'library/request',            'icon' => 'fas fa-bookmark',           'roles' => ['A', 'T', 'S']],
            ['name' => 'Book Issue / Return',      'url' => 'library/book_manage',        'icon' => 'fas fa-exchange-alt',       'roles' => ['A']],
        ],
    ],
    [
        'name'    => 'Events',
        'icon'    => 'fas fa-calendar-alt',
        'color'   => '#e83e8c',
        'modules' => [
            ['name' => 'Event Types',              'url' => 'event/types',                'icon' => 'fas fa-tag',                'roles' => ['A']],
            ['name' => 'Events Calendar',          'url' => 'event',                      'icon' => 'fas fa-calendar-alt',       'roles' => ['A', 'T']],
        ],
    ],
    [
        'name'    => 'Student Accounting (Fees)',
        'icon'    => 'fas fa-calculator',
        'color'   => '#28a745',
        'modules' => [
            ['name' => 'Fees Types',               'url' => 'fees/type',                  'icon' => 'fas fa-tag',                'roles' => ['A']],
            ['name' => 'Fees Groups',              'url' => 'fees/group',                 'icon' => 'fas fa-layer-group',        'roles' => ['A']],
            ['name' => 'Fine Setup',               'url' => 'fees/fine_setup',            'icon' => 'fas fa-gavel',              'roles' => ['A']],
            ['name' => 'Fees Allocation',          'url' => 'fees/allocation',            'icon' => 'fas fa-coins',              'roles' => ['A']],
            ['name' => 'Payment History',          'url' => 'fees/invoice_list',          'icon' => 'fas fa-history',            'roles' => ['A']],
            ['name' => 'Due Fees Invoice',         'url' => 'fees/due_invoice',           'icon' => 'fas fa-file-invoice',       'roles' => ['A']],
            ['name' => 'Fees Reminder',            'url' => 'fees/reminder',              'icon' => 'fas fa-bell',               'roles' => ['A']],
            ['name' => 'M-Pesa Transactions',      'url' => 'mpesa',                      'icon' => 'fas fa-mobile-alt',         'roles' => ['A']],
        ],
    ],
    [
        'name'    => 'Office Accounting',
        'icon'    => 'fas fa-credit-card',
        'color'   => '#dc3545',
        'modules' => [
            ['name' => 'Chart of Accounts',        'url' => 'accounting',                 'icon' => 'fas fa-book',               'roles' => ['A']],
            ['name' => 'New Deposit',              'url' => 'accounting/voucher_deposit',  'icon' => 'fas fa-arrow-circle-down',  'roles' => ['A']],
            ['name' => 'New Expense',              'url' => 'accounting/voucher_expense',  'icon' => 'fas fa-arrow-circle-up',    'roles' => ['A']],
            ['name' => 'All Transactions',         'url' => 'accounting/all_transactions', 'icon' => 'fas fa-exchange-alt',       'roles' => ['A']],
            ['name' => 'Voucher Heads',            'url' => 'accounting/voucher_head',     'icon' => 'fas fa-folder',             'roles' => ['A']],
            ['name' => 'LPO / Purchase Orders',    'url' => 'purchase-orders',             'icon' => 'fas fa-shopping-cart',      'roles' => ['A']],
            ['name' => 'Suppliers',                'url' => 'purchase-orders/suppliers',   'icon' => 'fas fa-truck',              'roles' => ['A']],
        ],
    ],
    [
        'name'    => 'Communication',
        'icon'    => 'fas fa-comments',
        'color'   => '#007bff',
        'modules' => [
            ['name' => 'Bulk SMS / Email',         'url' => 'sendsmsmail/sms',             'icon' => 'fas fa-bullhorn',           'roles' => ['A']],
            ['name' => 'Campaign Reports',         'url' => 'sendsmsmail/campaign_reports','icon' => 'fas fa-chart-line',         'roles' => ['A']],
            ['name' => 'SMS Templates',            'url' => 'sendsmsmail/template/sms',    'icon' => 'fas fa-sms',                'roles' => ['A']],
            ['name' => 'Email Templates',          'url' => 'sendsmsmail/template/email',  'icon' => 'fas fa-envelope',           'roles' => ['A']],
            ['name' => 'Notice Board',             'url' => 'noticeboard',                 'icon' => 'fas fa-bullhorn',           'roles' => ['A', 'T']],
            ['name' => 'Internal Messaging',       'url' => 'communication/mailbox/inbox', 'icon' => 'fas fa-inbox',              'roles' => ['A', 'T', 'S', 'P']],
        ],
    ],
    [
        'name'    => 'Reports',
        'icon'    => 'fas fa-chart-pie',
        'color'   => '#6c757d',
        'modules' => [
            ['name' => 'Fees Report',              'url' => 'fees/student_fees_report',    'icon' => 'fas fa-file-invoice',       'roles' => ['A']],
            ['name' => 'Receipts Report',          'url' => 'fees/payment_history',        'icon' => 'fas fa-receipt',            'roles' => ['A']],
            ['name' => 'Due Fees Report',          'url' => 'fees/due_report',             'icon' => 'fas fa-exclamation-circle', 'roles' => ['A']],
            ['name' => 'Fine Report',              'url' => 'fees/fine_report',            'icon' => 'fas fa-gavel',              'roles' => ['A']],
            ['name' => 'Account Statement',        'url' => 'accounting/account_statement','icon' => 'fas fa-file-alt',           'roles' => ['A']],
            ['name' => 'Income Report',            'url' => 'accounting/income_repots',    'icon' => 'fas fa-arrow-up',           'roles' => ['A']],
            ['name' => 'Expense Report',           'url' => 'accounting/expense_repots',   'icon' => 'fas fa-arrow-down',         'roles' => ['A']],
            ['name' => 'Transactions Report',      'url' => 'accounting/transitions_repots','icon' => 'fas fa-exchange-alt',      'roles' => ['A']],
            ['name' => 'Balance Sheet',            'url' => 'accounting/balance_sheet',    'icon' => 'fas fa-balance-scale',      'roles' => ['A']],
            ['name' => 'Income vs Expense',        'url' => 'accounting/incomevsexpense',  'icon' => 'fas fa-chart-bar',          'roles' => ['A']],
            ['name' => 'Student Attendance Rpt',   'url' => 'attendance/studentwise_report','icon' => 'fas fa-user-check',        'roles' => ['A', 'T']],
            ['name' => 'Employee Attendance Rpt',  'url' => 'attendance/employeewise_report','icon' => 'fas fa-briefcase',        'roles' => ['A']],
            ['name' => 'Exam Attendance Report',   'url' => 'attendance/examwise_report',  'icon' => 'fas fa-clipboard-check',    'roles' => ['A', 'T']],
            ['name' => 'Payroll Summary',          'url' => 'payroll/salary_statement',    'icon' => 'fas fa-file-invoice-dollar','roles' => ['A']],
            ['name' => 'Leave Reports',            'url' => 'leave/reports',               'icon' => 'fas fa-calendar-times',     'roles' => ['A']],
            ['name' => 'Report Card',              'url' => 'exam/marksheet',              'icon' => 'fas fa-graduation-cap',     'roles' => ['A', 'T']],
            ['name' => 'Tabulation Sheet',         'url' => 'exam/tabulation_sheet',       'icon' => 'fas fa-table',              'roles' => ['A', 'T']],
        ],
    ],
    [
        'name'    => 'Analytics & Special Modules',
        'icon'    => 'fas fa-star',
        'color'   => '#6f42c1',
        'modules' => [
            ['name' => 'Analytics & Insights',     'url' => 'analytics',                  'icon' => 'fas fa-chart-line',         'roles' => ['A']],
            ['name' => 'Canteen / POS',            'url' => 'canteen/pos',                'icon' => 'fas fa-utensils',           'roles' => ['A']],
            ['name' => 'Staff Appraisal',          'url' => 'appraisal',                  'icon' => 'fas fa-star',               'roles' => ['A']],
            ['name' => 'Assets & Inventory',       'url' => 'assets',                     'icon' => 'fas fa-boxes',              'roles' => ['A']],
            ['name' => 'Virtual Classroom',        'url' => 'virtual_class',              'icon' => 'fas fa-video',              'roles' => ['A', 'T']],
            ['name' => 'Live Class',               'url' => 'live_class',                 'icon' => 'fas fa-broadcast-tower',    'roles' => ['A', 'T']],
            ['name' => 'Alumni Portal',            'url' => 'alumni',                     'icon' => 'fas fa-user-graduate',      'roles' => ['A']],
            ['name' => 'CBT / Online Exams',       'url' => 'cbt',                        'icon' => 'fas fa-laptop',             'roles' => ['A', 'T', 'S']],
            ['name' => 'Visitor / Gate Log',       'url' => 'visitor',                    'icon' => 'fas fa-sign-in-alt',        'roles' => ['A']],
            ['name' => 'KNEC Index Numbers',       'url' => 'knec',                       'icon' => 'fas fa-hashtag',            'roles' => ['A']],
            ['name' => 'Parent-Teacher Meeting',   'url' => 'ptm',                        'icon' => 'fas fa-handshake',          'roles' => ['A', 'T']],
            ['name' => 'Bursary & Scholarships',   'url' => 'bursary',                    'icon' => 'fas fa-hand-holding-heart', 'roles' => ['A']],
            ['name' => 'Pocket Money',             'url' => 'pocket_money',               'icon' => 'fas fa-wallet',             'roles' => ['A', 'S']],
            ['name' => 'Health Records',           'url' => 'health/search',              'icon' => 'fas fa-heartbeat',          'roles' => ['A', 'T']],
            ['name' => 'Health Clinic Log',        'url' => 'health/clinic_log',          'icon' => 'fas fa-notes-medical',      'roles' => ['A', 'T']],
            ['name' => 'Homework',                 'url' => 'homework',                   'icon' => 'fas fa-pencil-ruler',       'roles' => ['A', 'T', 'S']],
        ],
    ],
    [
        'name'    => 'Settings & Configuration',
        'icon'    => 'fas fa-cog',
        'color'   => '#495057',
        'modules' => [
            ['name' => 'Global Settings',          'url' => 'settings/universal',         'icon' => 'fas fa-globe',              'roles' => ['A']],
            ['name' => 'School Settings',          'url' => 'school_settings',            'icon' => 'fas fa-school',             'roles' => ['A']],
            ['name' => 'Roles & Permissions',      'url' => 'role',                       'icon' => 'fas fa-shield-alt',         'roles' => ['A']],
            ['name' => 'Session Settings',         'url' => 'sessions',                   'icon' => 'fas fa-hourglass',          'roles' => ['A']],
            ['name' => 'Translations',             'url' => 'translations',               'icon' => 'fas fa-language',           'roles' => ['A']],
            ['name' => 'Cron Jobs',                'url' => 'cron_api',                   'icon' => 'fas fa-clock',              'roles' => ['A']],
            ['name' => 'Custom Fields',            'url' => 'custom_field',               'icon' => 'fas fa-sliders-h',          'roles' => ['A']],
            ['name' => 'Database Backup',          'url' => 'backup',                     'icon' => 'fas fa-database',           'roles' => ['A']],
        ],
    ],
    [
        'name'    => 'User Portals',
        'icon'    => 'fas fa-users-cog',
        'color'   => '#343a40',
        'modules' => [
            ['name' => 'Student Dashboard',        'url' => 'dashboard',                  'icon' => 'fas fa-tachometer-alt',     'roles' => ['S']],
            ['name' => 'My Timetable',             'url' => 'timetable/viewclass',        'icon' => 'fas fa-calendar-alt',       'roles' => ['S']],
            ['name' => 'My Attendance',            'url' => 'attendance/studentwise_report','icon' => 'fas fa-check-circle',     'roles' => ['S']],
            ['name' => 'My Transport',             'url' => 'transport/report',           'icon' => 'fas fa-bus',                'roles' => ['S', 'P']],
            ['name' => 'My Hostel',                'url' => 'hostels/allocation_report',  'icon' => 'fas fa-bed',                'roles' => ['S', 'P']],
            ['name' => 'My Library Books',         'url' => 'library/request',            'icon' => 'fas fa-book',               'roles' => ['S', 'T']],
            ['name' => 'Leave Application',        'url' => 'leave/request',              'icon' => 'fas fa-calendar-times',     'roles' => ['S', 'T']],
            ['name' => 'Online CBT Exams',         'url' => 'cbt',                        'icon' => 'fas fa-laptop',             'roles' => ['S']],
            ['name' => 'My Pocket Money',          'url' => 'pocket_money',               'icon' => 'fas fa-wallet',             'roles' => ['S']],
            ['name' => 'Parent Dashboard',         'url' => 'dashboard',                  'icon' => 'fas fa-home',               'roles' => ['P']],
            ['name' => "Child's Timetable",        'url' => 'timetable/viewclass',        'icon' => 'fas fa-calendar',           'roles' => ['P']],
            ['name' => "Child's Attendance",       'url' => 'attendance/studentwise_report','icon' => 'fas fa-user-check',       'roles' => ['P']],
            ['name' => "Child's Fees",             'url' => 'fees/due_invoice',           'icon' => 'fas fa-money-bill',         'roles' => ['P']],
            ['name' => "Child's Results",          'url' => 'exam/marksheet',             'icon' => 'fas fa-graduation-cap',     'roles' => ['P']],
            ['name' => 'Teacher Dashboard',        'url' => 'dashboard',                  'icon' => 'fas fa-chalkboard',         'roles' => ['T']],
            ['name' => 'Manage Homework',          'url' => 'homework',                   'icon' => 'fas fa-pencil-ruler',       'roles' => ['T']],
            ['name' => 'Take Attendance',          'url' => 'attendance/student_entry',   'icon' => 'fas fa-clipboard-check',    'roles' => ['T']],
            ['name' => 'Enter Exam Marks',         'url' => 'exam/mark_entry',            'icon' => 'fas fa-keyboard',           'roles' => ['T']],
        ],
    ],
];

$total_modules = 0;
foreach ($categories as $cat) {
    $total_modules += count($cat['modules']);
}
?>
<style>
.module-search-wrap { margin-bottom: 18px; }
.module-search-wrap .input-group-text { background: #fff; border-right: 0; }
.module-search-wrap .form-control { border-left: 0; }
.mm-category { margin-bottom: 28px; }
.mm-category-header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 14px;
    border-radius: 4px;
    margin-bottom: 14px;
    color: #fff;
    font-size: 14px;
    font-weight: 600;
    letter-spacing: .3px;
}
.mm-category-header i { font-size: 17px; opacity: .9; }
.mm-category-count {
    background: rgba(255,255,255,.25);
    border-radius: 10px;
    padding: 1px 9px;
    font-size: 11px;
    margin-left: 4px;
}
.mm-grid { display: flex; flex-wrap: wrap; margin: 0 -7px; }
.mm-card-wrap { padding: 0 7px 14px; width: 16.666%; }
@media (max-width: 1400px) { .mm-card-wrap { width: 20%; } }
@media (max-width: 1200px) { .mm-card-wrap { width: 25%; } }
@media (max-width: 992px)  { .mm-card-wrap { width: 33.333%; } }
@media (max-width: 768px)  { .mm-card-wrap { width: 50%; } }
@media (max-width: 480px)  { .mm-card-wrap { width: 100%; } }
.mm-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    background: #fff;
    border: 1px solid #e3e6f0;
    border-top: 3px solid transparent;
    border-radius: 5px;
    padding: 14px 10px 10px;
    text-align: center;
    text-decoration: none;
    color: #495057;
    height: 100%;
    transition: box-shadow .18s, transform .18s, border-color .18s;
}
.mm-card:hover {
    box-shadow: 0 4px 18px rgba(0,0,0,.13);
    transform: translateY(-2px);
    text-decoration: none;
    color: #222;
}
.mm-card-icon { font-size: 24px; margin-bottom: 8px; }
.mm-card-name {
    font-size: 11.5px;
    font-weight: 600;
    line-height: 1.35;
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 8px;
}
.mm-card-roles { display: flex; flex-wrap: wrap; justify-content: center; gap: 3px; }
.mm-card-roles .badge { font-size: 9.5px; padding: 2px 5px; }
.mm-no-results { display: none; padding: 20px; text-align: center; color: #aaa; font-size: 13px; width: 100%; }
.mm-card-wrap.mm-hidden { display: none; }
.mm-role-btn.mm-role-active { font-weight: 700; box-shadow: 0 0 0 2px rgba(0,0,0,.15); }
</style>

<div class="container-fluid">

    <!-- Header -->
    <div class="card" style="border-left: 4px solid #4e73df; margin-bottom: 18px;">
        <div class="card-body" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;padding:14px 18px;">
            <div>
                <h4 style="margin:0;font-weight:700;color:#333;">
                    <i class="fas fa-th-large" style="color:#4e73df;margin-right:8px;"></i>All Available Modules
                </h4>
                <p style="margin:4px 0 0;color:#888;font-size:12px;">
                    Training &amp; demo reference — <strong><?php echo $total_modules; ?> modules</strong> across
                    <strong><?php echo count($categories); ?> categories</strong> — click any card to open the module
                </p>
            </div>
            <div style="display:flex;gap:6px;align-items:center;flex-wrap:wrap;">
                <span class="badge bg-danger"               style="font-size:11px;padding:4px 9px;">Admin</span>
                <span class="badge bg-info"                 style="font-size:11px;padding:4px 9px;">Teacher</span>
                <span class="badge bg-success"              style="font-size:11px;padding:4px 9px;">Parent</span>
                <span class="badge bg-warning text-dark"    style="font-size:11px;padding:4px 9px;">Student</span>
            </div>
        </div>
    </div>

    <!-- Search -->
    <div class="module-search-wrap">
        <div class="input-group" style="max-width:420px;">
            <span class="input-group-text"><i class="fas fa-search text-muted"></i></span>
            <input type="text" id="mmSearch" class="form-control" placeholder="Search modules by name…" autocomplete="off">
        </div>
    </div>
    <p id="mmSearchInfo" style="color:#888;font-size:12px;margin-bottom:18px;display:none;"></p>

    <!-- Role filter -->
    <div style="margin-bottom:20px;display:flex;flex-wrap:wrap;gap:6px;align-items:center;">
        <span style="font-size:12px;color:#666;margin-right:4px;">Filter by role:</span>
        <button class="btn btn-sm btn-outline-secondary mm-role-btn mm-role-active" data-role="ALL">All Roles</button>
        <button class="btn btn-sm btn-danger  mm-role-btn" data-role="A">Admin</button>
        <button class="btn btn-sm btn-info    mm-role-btn" data-role="T">Teacher</button>
        <button class="btn btn-sm btn-success mm-role-btn" data-role="P">Parent</button>
        <button class="btn btn-sm btn-warning mm-role-btn text-dark" data-role="S">Student</button>
    </div>

    <!-- Categories -->
    <?php foreach ($categories as $cat): ?>
    <div class="mm-category" data-category="<?php echo htmlspecialchars($cat['name']); ?>">
        <div class="mm-category-header" style="background:<?php echo $cat['color']; ?>;">
            <i class="<?php echo $cat['icon']; ?>"></i>
            <?php echo htmlspecialchars($cat['name']); ?>
            <span class="mm-category-count"><?php echo count($cat['modules']); ?></span>
        </div>
        <div class="mm-grid">
            <?php foreach ($cat['modules'] as $mod):
                $role_str = implode(' ', $mod['roles']);
            ?>
            <div class="mm-card-wrap" data-name="<?php echo strtolower(htmlspecialchars($mod['name'])); ?>" data-roles="<?php echo $role_str; ?>">
                <a href="<?php echo base_url($mod['url']); ?>" class="mm-card" style="border-top-color:<?php echo $cat['color']; ?>;">
                    <div class="mm-card-icon" style="color:<?php echo $cat['color']; ?>;">
                        <i class="<?php echo $mod['icon']; ?>"></i>
                    </div>
                    <div class="mm-card-name"><?php echo htmlspecialchars($mod['name']); ?></div>
                    <div class="mm-card-roles">
                        <?php foreach ($mod['roles'] as $r):
                            $rl = $role_labels[$r] ?? ['label' => $r, 'class' => 'bg-secondary'];
                        ?>
                        <span class="badge <?php echo $rl['class']; ?>"><?php echo $rl['label']; ?></span>
                        <?php endforeach; ?>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
            <div class="mm-no-results"><i class="fas fa-search"></i> No modules match in this category.</div>
        </div>
    </div>
    <?php endforeach; ?>

</div><!-- /.container-fluid -->

<script>
$(function(){
    var activeRole = 'ALL';
    var searchQ    = '';

    function applyFilters() {
        var matched = 0;
        $('.mm-category').each(function(){
            var $cat = $(this);
            var catVisible = 0;
            $cat.find('.mm-card-wrap').each(function(){
                var $card  = $(this);
                var name   = $card.data('name') || '';
                var roles  = ($card.data('roles') || '').split(' ');
                var nameOk = !searchQ || name.indexOf(searchQ) !== -1;
                var roleOk = activeRole === 'ALL' || roles.indexOf(activeRole) !== -1;
                if (nameOk && roleOk) { $card.removeClass('mm-hidden'); catVisible++; matched++; }
                else { $card.addClass('mm-hidden'); }
            });
            $cat.find('.mm-no-results').toggle(catVisible === 0);
        });

        if (searchQ || activeRole !== 'ALL') {
            $('#mmSearchInfo').show().text(matched + ' module(s) shown');
        } else {
            $('#mmSearchInfo').hide();
        }
    }

    $('#mmSearch').on('input', function(){
        searchQ = $(this).val().toLowerCase().trim();
        applyFilters();
    });

    $('.mm-role-btn').on('click', function(){
        activeRole = $(this).data('role');
        $('.mm-role-btn').removeClass('mm-role-active');
        $(this).addClass('mm-role-active');
        applyFilters();
    });
});
</script>
