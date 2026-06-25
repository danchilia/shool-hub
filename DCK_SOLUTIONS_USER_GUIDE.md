# DCK Solutions - School Management System
# Complete User Guide

**System:** DCK Solutions School Management System
**Tagline:** Solving More, Building Better
**Version:** Kenya CBC Adapted

---

## TABLE OF CONTENTS

1. [System Overview](#1-system-overview)
2. [User Roles & Login](#2-user-roles--login)
3. [Superadmin Guide](#3-superadmin-guide)
4. [School Admin Guide](#4-school-admin-guide)
5. [Teacher Guide](#5-teacher-guide)
6. [Accountant Guide](#6-accountant-guide)
7. [Librarian Guide](#7-librarian-guide)
8. [Parent Guide](#8-parent-guide)
9. [Student Guide](#9-student-guide)

---

## 1. SYSTEM OVERVIEW

DCK Solutions School Management System is a multi-branch school management platform built for Kenyan schools. It supports both the **Competency Based Curriculum (CBC)** for PP1 to Grade 9 and the **8-4-4 system** for Form 1-4.

### What the system manages:
- Student admissions and enrollment
- CBC and traditional exam assessments
- Fee collection and tracking (including M-Pesa)
- Student and staff attendance
- Homework assignments
- Class timetables and exam schedules
- Library book management
- School transport and hostels
- Staff payroll with Kenyan deductions (NHIF, NSSF, PAYE)
- SMS and email communication to parents
- School accounting (income, expenses, reports)
- Multiple school branches under one platform

### How the system is structured:
```
DCK Solutions (Superadmin)
    |
    |--- Sunrise Academy Nairobi (Branch 1)
    |       |--- School Admin
    |       |--- Teachers
    |       |--- Accountant
    |       |--- Librarian
    |       |--- Parents
    |       |--- Students
    |
    |--- Another School (Branch 2)
    |       |--- School Admin
    |       |--- Teachers
    |       |--- ...
```

The **Superadmin** manages all schools. Each school has its own **School Admin** who manages that school's staff, students, and operations.

---

## 2. USER ROLES & LOGIN

### How to login:
1. Open your browser (Chrome recommended)
2. Go to the system URL (e.g., http://localhost/multibranchschoolmanagementsystem/)
3. Enter your email and password
4. Click Login

### User Roles:

| Role | Who they are | What they can do |
|------|-------------|-----------------|
| **Superadmin** | DCK Solutions owner/operator | Manage all schools, subscriptions, global settings |
| **School Admin** | School principal/head teacher | Full control of one school |
| **Teacher** | Class teacher or subject teacher | Teach, grade, take attendance, request admissions |
| **Accountant** | School bursar/finance officer | Collect fees, manage accounts, process payroll |
| **Librarian** | School librarian | Manage books, issue and return books |
| **Parent** | Student's parent/guardian | View child's progress, fees, attendance |
| **Student** | Enrolled learner | View own grades, timetable, homework, fees |

---

## 3. SUPERADMIN GUIDE

**Role:** Platform owner who manages all schools on the system.
**Login:** Use your superadmin email and password.

### 3.1 Dashboard
When you login, you see the **All Branches Dashboard** showing:
- Total students, staff, parents across all schools
- Monthly income vs expense chart
- Student distribution by class
- Attendance overview
- Event calendar

You can click on any specific branch to see that school's individual dashboard.

### 3.2 Managing Schools (Branches)

**Menu: Branch > Manage Branches**

This is where you create and manage schools on the platform.

**To create a new school:**
1. Go to Branch > Manage Branches
2. Fill in: Branch Name, School Name, Email, Phone, Currency (KES), City, County, Address
3. Check "Use Kenya Template" to auto-create: PP1-Form 4 classes, sections (East/West/North), CBC learning areas, exam terms (Term 1/2/3)
4. Click Save

**To edit a school:** Click the edit icon next to the school name.

**To delete a school:** Click the delete icon (this removes all data for that school).

### 3.3 Subscription Management

This lets you charge schools monthly for using the platform.

**Menu: Branch > Subscription Plans**

Create pricing tiers for schools:
- **Plan Name:** e.g., Basic, Standard, Premium
- **Max Students:** Maximum number of students allowed (0 = unlimited)
- **Max Staff:** Maximum number of staff allowed
- **Monthly Price (KES):** What the school pays per month
- **Yearly Price (KES):** Discounted annual price

**Menu: Branch > Branch Subscriptions**

See which schools are subscribed and their status (Active, Expired, Trial).

**Menu: Branch > Assign Plan**

Assign a subscription plan to a school:
1. Select the school (branch)
2. Select the plan
3. Choose billing cycle (Monthly or Yearly)
4. Click Assign

**Menu: Branch > Invoices**

Track payments from schools:
- See all invoices with status (Pending, Paid, Overdue)
- Click the green check icon to mark an invoice as paid
- Enter the payment reference (e.g., M-Pesa transaction code)

### 3.4 Academic Sessions

**Menu: Settings > Session Settings**

Create academic years (e.g., "2026", "2027"). The active session determines which year's data everyone sees.

### 3.5 Roles & Permissions

**Menu: Settings > Role & Permission**

Control exactly what each role can see and do:
1. Click "Manage" next to a role (e.g., Teacher)
2. You see a grid of all features with checkboxes: View, Add, Edit, Delete
3. Check or uncheck to grant or remove access
4. Click Update

**Example:** To let teachers request admissions:
- Find "Admission Request" in the grid
- Check: View ✓, Add ✓, Delete ✓
- Click Update

### 3.6 Global Settings

**Menu: Settings > Global Settings**

Configure system-wide settings:
- Institution Name and Code
- Logo upload
- Registration number prefix (On/Off)
- Timezone (Africa/Nairobi)
- Date format
- Footer text
- Social media links

### 3.7 Translations

**Menu: Settings > Translations**

Change the system language. The system stores all text labels in the database, so you can translate any word to Kiswahili or any other language.

### 3.8 Database Backup

**Menu: Settings > Backup**

Download a full backup of the database. Do this regularly for safety.

---

## 4. SCHOOL ADMIN GUIDE

**Role:** The principal or head teacher who manages one school.
**Login:** Use your school admin email (e.g., admin@sunriseacademy.co.ke) and password.

### 4.1 First-Time Setup Checklist

When setting up a new school for the first time, follow this order:

| Step | What to do | Menu |
|------|-----------|------|
| 1 | Create sections (streams) | Academics > Sections |
| 2 | Create classes with curriculum type | Academics > Classes |
| 3 | Create subjects | Academics > Subjects |
| 4 | Add staff (teachers, accountant, librarian) | Employee > Add Employee |
| 5 | Assign class teachers | Academics > Assign Class Teacher |
| 6 | Assign subjects to classes | Academics > Subject Class Assignment |
| 7 | Assign teachers to subjects | Academics > Subject Teacher Assignment |
| 8 | Set up class timetable | Academics > Class Timetable |
| 9 | Create fee types and groups | Student Accounting > Fees Type, Fees Group |
| 10 | Create exam terms (Term 1, 2, 3) | Exam Master > Exam Terms |
| 11 | Create grade scale | Marks > Grades Range |
| 12 | Set up CBC learning areas | CBC Assessment > Learning Areas |
| 13 | Admit students | Admission > Create Admission |
| 14 | Allocate fees to students | Student Accounting > Fees Allocation |

### 4.2 Student Admission

**Menu: Admission > Create Admission**

To admit a new student:
1. Select Academic Year, Class, and Section
2. Register No is auto-generated (you can change it)
3. Enter Roll Number (next available number in that class/section)
4. Enter student details: Name, Gender, Date of Birth, Phone, Email
5. Enter UPI Number (NEMIS) if available
6. Set a password for the student's login account
7. Fill guardian details (or select an existing guardian)
8. Click Save

The student is immediately enrolled and can login with their email and password.

**Menu: Admission > Multiple Import (CSV)**

To admit many students at once:
1. Click "Download Sample Import File"
2. Open the CSV in Excel
3. Fill in all student details row by row
4. Save as CSV
5. Select Class and Section
6. Upload the file and click Import

### 4.3 Reviewing Teacher Admission Requests

When teachers submit admission requests, they appear here for your review.

**Menu: Admission > Admission Requests**

You see a list of all requests with status badges:
- **Yellow (Pending):** Needs your review
- **Green (Approved):** Already approved
- **Red (Rejected):** Already rejected

**To approve a request:**
1. Click the eye icon to view details
2. Review student and guardian information
3. Click "Approve Admission" - the student is automatically enrolled

**To reject a request:**
1. Click the red X icon
2. Enter the reason for rejection
3. Click Reject

### 4.4 Managing Classes

**Menu: Academics > Classes**

When creating or editing a class, you set:
- **Class Name:** e.g., Grade 4, Form 1
- **Numeric Value:** Used for ordering
- **Curriculum Type:** CBC (for PP1-Grade 9) or 8-4-4 (for Form 1-4)
- **Level:** Pre-Primary, Lower Primary, Upper Primary, Junior Secondary, or Senior Secondary
- **Sections:** Which streams this class has (East, West, etc.)

The curriculum type determines whether exams use CBC competency levels or traditional marks.

### 4.5 Managing Employees

**Menu: Employee > Add Employee**

When adding staff, you assign them a role:
- **Admin (Role 2):** Can manage the school
- **Teacher (Role 3):** Can teach, grade, take attendance
- **Accountant (Role 4):** Can manage fees and accounts
- **Librarian (Role 5):** Can manage the library

Each staff member gets a login account with their email.

**Menu: Employee > Departments**

Create departments: Administration, Teaching - Lower Primary, Teaching - Upper Primary, Accounts, Support Staff.

**Menu: Employee > Designations**

Create job titles: Head Teacher, Deputy Head Teacher, Class Teacher, Subject Teacher, Accountant, Librarian.

### 4.6 Exam & Assessment Management

#### For Traditional Exams (Form 1-4):

**Step 1: Create Mark Distribution**
- Menu: Exam Master > Mark Distribution
- Create: CAT 1, CAT 2, End Term Exam

**Step 2: Create Exam**
- Menu: Exam Master > Exam Setup
- Name: "Form 1 Term 2 Exam 2026"
- Grading System: Traditional
- Term: Term 2
- Mark Distributions: Select CAT 1, CAT 2, End Term Exam

**Step 3: Create Exam Schedule**
- Menu: Exam Schedule > Add Schedule
- Set date, time, subject, hall for each exam paper

**Step 4: Enter Marks**
- Menu: Marks > Mark Entries
- Select exam, class, section, subject
- Enter marks for each student (CAT 1, CAT 2, End Term)
- Click Save

**Step 5: Generate Report Cards**
- Menu: Exam Reports > Report Card
- Select session, exam, class, section
- Check the students you want
- Click Print Report Card

#### For CBC Assessments (PP1 to Grade 9):

**Step 1: Create CBC Exam**
- Menu: Exam Master > Exam Setup
- Name: "Term 2 CBC Assessment 2026"
- Grading System: CBC
- Term: Term 2

**Step 2: Enter Competency Levels**
- Menu: CBC Assessment > Assessment Entry
- Select class, section, CBC exam, learning area
- For each student, select: EE, ME, AE, or BE
  - **EE** = Exceeding Expectations (excellent)
  - **ME** = Meeting Expectations (good)
  - **AE** = Approaching Expectations (needs improvement)
  - **BE** = Below Expectations (struggling)
- Add optional remarks per student

**Step 3: Enter Behaviour Assessment**
- Menu: CBC Assessment > Behaviour Assessment
- Select class, section, exam
- Rate each student on: Social, Spiritual, Emotional, Physical, Creative

**Step 4: Generate CBC Report Card**
- Menu: CBC Assessment > CBC Report Card
- Select session, exam, class, section
- Check students and click Print
- Report card shows competency levels with colour coding, behaviour ratings, and teacher remarks

### 4.7 Fee Management

**Step 1: Create Fee Types**
- Menu: Student Accounting > Fees Type
- Create: Tuition Fee, Activity Fee, Lunch Programme, Exam Fee, Transport Fee, Development Levy

**Step 2: Create Fee Groups**
- Menu: Student Accounting > Fees Group
- Group fees together with amounts and due dates
- Example: "Term 2 Fees - Primary" = Tuition KSh 15,000 + Activity KSh 2,000 + Lunch KSh 8,000

**Step 3: Allocate Fees to Students**
- Menu: Student Accounting > Fees Allocation
- Select class, section, and fee group
- Check students to assign fees to
- Click Save

**Step 4: Collect Fees**
- Menu: Student Accounting > Payments History
- Select class and section, click Filter
- Click "Collect" next to a student
- Click the "Collect Fees" tab
- Select fee type, enter amount, select payment method (Cash, M-Pesa, Bank Transfer)
- Click Fee Payment

**Step 5: Track Outstanding Fees**
- Menu: Student Accounting > Due Fees Invoice
- See all students with unpaid balances

**Step 6: Set Up Fee Reminders**
- Menu: Student Accounting > Fees Reminder
- Configure automatic SMS reminders to parents

### 4.8 Attendance

**Menu: Attendance > Student Attendance**

1. Select class, section, and date
2. Mark each student: P (Present), A (Absent), L (Late)
3. Add remarks for absent students (e.g., "Sick", "Family event")
4. Click Save

**Menu: Attendance > Employee Attendance**

Same process for staff attendance.

### 4.9 Timetable

**Menu: Academics > Class Timetable**

1. Select class and section
2. For each day (Monday-Friday), add time slots:
   - Start time, End time
   - Subject and Teacher
   - Classroom
   - Check "Break" for tea/lunch breaks
3. Save

Students and parents can view this from their dashboards.

### 4.10 Homework

**Menu: Homework**

1. Click Add Homework
2. Select class, section, subject
3. Enter description of the assignment
4. Set homework date and submission deadline
5. Optionally attach a file
6. Click Save and Publish

Students and parents see the homework in their dashboard. After the due date, go to Homework > Evaluation to grade each student's work.

### 4.11 Transport

**Menu: Supervision > Transport**

Set up school transport:
1. **Route Master:** Create routes (e.g., "Westlands Route", "Karen Route")
2. **Vehicle Master:** Add school buses with driver details
3. **Stoppage:** Add pickup/dropoff points with times and fares
4. **Assign Vehicle:** Link vehicles to routes

Students assigned to routes can see their transport details in their dashboard.

### 4.12 Hostel

**Menu: Supervision > Hostel**

Set up boarding facilities:
1. **Categories:** Boys Hostel, Girls Hostel
2. **Hostel Master:** Create hostel buildings
3. **Rooms:** Add rooms with bed count and fees

### 4.13 Events

**Menu: Events > Events**

Create school events (Sports Day, Cultural Week, Parents Meeting, etc.) with dates. These appear on everyone's calendar.

### 4.14 Communication

**Menu: Communication > Send SMS/Email**

Send messages to parents and students:
1. Select recipients: All, by class, or individual
2. Choose SMS or Email
3. Write your message
4. Click Send

**Menu: Message > Mailbox**

Internal messaging system for staff-to-staff or staff-to-parent communication.

### 4.15 Office Accounting

**Menu: Office Accounting**

Track school income and expenses:
- **Accounts:** Create bank accounts (Main Account, Petty Cash)
- **Voucher Heads:** Create categories (Stationery, Electricity, Water, Salary)
- **New Deposit:** Record income (government grants, donations)
- **New Expense:** Record expenses (bills, supplies, repairs)
- **All Transactions:** View complete financial ledger

### 4.16 Payroll

**Menu: HRM > Payroll**

Process staff salaries:
1. **Salary Template:** Create salary structures with Kenyan deductions:
   - Basic Salary
   - Allowances: House, Transport
   - Deductions: NHIF, NSSF, PAYE
2. **Salary Assignment:** Link templates to staff members
3. **Salary Payment:** Process monthly payment, generate payslips

### 4.17 Reports

| Report Menu | What it shows |
|-------------|--------------|
| Fees Report | Per-student fee payment details |
| Receipts Report | All payments received in a date range |
| Due Fees Report | Students with outstanding balances |
| Fine Report | Late payment penalties |
| Student Attendance Report | Attendance percentage per student |
| Employee Attendance Report | Staff attendance record |
| Report Card | Individual exam results |
| Tabulation Sheet | Class-wide marks comparison |
| Payroll Summary | Monthly salary breakdown |
| Leave Report | Staff leave usage |

### 4.18 School Settings

**Menu: School Settings**

Configure your school's settings:
- **Payment Gateway:** Set up M-Pesa (Consumer Key, Secret, Shortcode, Passkey)
- **SMS Configuration:** Set up SMS provider (Africa's Talking, Twilio)
- **Email Configuration:** SMTP settings for email notifications
- **SMS/Email Templates:** Customize notification messages

---

## 5. TEACHER GUIDE

**Role:** Class teacher or subject teacher. Can teach, assess students, take attendance, and request admissions.
**Login:** Use your teacher email (e.g., mary.wanjiku@sunriseacademy.co.ke) and password.

### 5.1 What Teachers See

When you login, you see your dashboard with your assigned classes and upcoming events.

Your menu includes:
- Admission (Request Admission, Bulk Request CSV, View Requests)
- Homework
- Attendance
- Exams and CBC Assessment
- Library
- Leave requests
- Messages

### 5.2 Requesting Student Admission

**Single Student:**
1. Go to Admission > Request Admission
2. Fill in the student's details (name, class, section, parent info)
3. Click Submit Admission Request
4. The request goes to the school admin for approval
5. You can track your requests at Admission > Admission Requests

**Bulk Import (CSV):**
1. Go to Admission > Bulk Request (CSV)
2. Download the sample CSV file
3. Fill it with student details in Excel
4. Select the class and section
5. Upload the CSV file
6. Click Submit Admission Requests
7. All students are saved as pending requests for admin approval

### 5.3 Taking Attendance

1. Go to Attendance > Student Attendance
2. Select your class and section
3. Select today's date
4. Mark each student: Present (P), Absent (A), or Late (L)
5. Add remarks for absent students
6. Click Save

### 5.4 Assigning Homework

1. Go to Homework
2. Click Add
3. Select class, section, and subject
4. Write the homework description
5. Set the homework date and submission deadline
6. Click Save

After the deadline, go back to Homework and click "Evaluation" to grade each student.

### 5.5 Entering Exam Marks (Traditional - Form 1-4)

1. Go to Marks > Mark Entries
2. Select exam, class, section, and subject
3. Enter marks for each student (CAT 1, CAT 2, End Term)
4. Check "Absent" for students who didn't sit the exam
5. Click Save

### 5.6 Entering CBC Assessments (PP1 to Grade 9)

1. Go to CBC Assessment > Assessment Entry
2. Select class, section, CBC exam, and learning area
3. For each student, select the competency level:
   - **EE** - Exceeding Expectations
   - **ME** - Meeting Expectations
   - **AE** - Approaching Expectations
   - **BE** - Below Expectations
4. Add optional remarks
5. Click Save Assessment

For behaviour ratings:
1. Go to CBC Assessment > Behaviour Assessment
2. Select class, section, and exam
3. Rate each student on Social, Spiritual, Emotional, Physical, Creative
4. Click Save

### 5.7 Viewing Timetable

Go to Academics > Class Timetable to see your teaching schedule.

### 5.8 Requesting Leave

1. Go to HRM > Leave > My Application
2. Click Add
3. Select leave type (Annual, Sick, Compassionate)
4. Enter start date, end date, and reason
5. Attach supporting document if needed (e.g., doctor's note)
6. Click Submit

You can track the status of your leave applications here.

### 5.9 Library

Go to Library > Books to browse the school library. You can request books for yourself.

### 5.10 Messages

Go to Message > Mailbox to communicate with other staff or with parents.

---

## 6. ACCOUNTANT GUIDE

**Role:** School bursar or finance officer. Manages all money matters.
**Login:** Use your accountant email (e.g., samuel.kamau@sunriseacademy.co.ke) and password.

### 6.1 Daily Fee Collection

This is your primary daily task.

**Step 1: View who needs to pay**
1. Go to Student Accounting > Payments History
2. Select class and section
3. Click Filter
4. You see all students with their fee status: Unpaid (red), Partly Paid (blue), Fully Paid (green)

**Step 2: Collect payment**
1. Click "Collect" next to a student
2. You see the Invoice page with 3 tabs:
   - **Invoice:** Shows fee breakdown and balances
   - **Payment History:** Shows past payments (if any)
   - **Collect Fees:** Where you enter the payment
3. Click the "Collect Fees" tab
4. Select the fee type (e.g., Tuition Fee)
5. The balance auto-fills
6. Enter the amount being paid
7. Select payment method: Cash, M-Pesa, Bank Transfer, Cheque
8. Add remarks (e.g., "M-Pesa ref: QKL2XY789")
9. Click Fee Payment

**Step 3: Check outstanding fees**
- Go to Student Accounting > Due Fees Invoice to see all students with unpaid balances

### 6.2 Fee Setup

**Fees Type** (Student Accounting > Fees Type):
Create fee categories your school charges:
- Tuition Fee
- Activity Fee
- Lunch Programme
- Transport Fee
- Exam Fee
- Development Levy

**Fees Group** (Student Accounting > Fees Group):
Bundle fees into groups with amounts and due dates:
- "Term 2 Fees - Primary": Tuition KSh 15,000 (due 01-05-2026) + Activity KSh 2,000 + Lunch KSh 8,000
- "Term 2 Fees - Secondary": Tuition KSh 25,000 + Activity KSh 3,000 + Lunch KSh 10,000

**Fees Allocation** (Student Accounting > Fees Allocation):
Assign fee groups to students by class:
1. Select class, section, and fee group
2. Check the students
3. Click Save

**Fine Setup** (Student Accounting > Fine Setup):
Set penalties for late payment:
- Select fee group and fee type
- Set fine type: Fixed amount (e.g., KSh 50) or Percentage (e.g., 2%)
- Set frequency: How often the fine applies (e.g., every 7 days)

### 6.3 Office Accounting

**Accounts** (Office Accounting > Accounts):
Create your school's bank accounts:
- Main Operating Account (KCB Bank)
- Petty Cash
- Development Fund

**Recording Income:**
1. Go to Office Accounting > New Deposit
2. Select account, voucher head (e.g., "Government Grant")
3. Enter amount, date, payment method
4. Add description and reference number
5. Click Save

**Recording Expenses:**
1. Go to Office Accounting > New Expense
2. Select account, voucher head (e.g., "Electricity Bill")
3. Enter amount, date
4. Add description (e.g., "Kenya Power bill - June 2026")
5. Click Save

**Viewing All Transactions:**
Go to Office Accounting > All Transactions to see the complete financial ledger.

### 6.4 Payroll Processing

**Step 1: Create Salary Templates** (HRM > Payroll > Salary Template)
- Template Name: "Teacher Salary"
- Basic Salary: KSh 45,000
- Add Allowances: House KSh 8,000, Transport KSh 4,000
- Add Deductions: NHIF KSh 1,700, NSSF KSh 200, PAYE KSh 4,500

**Step 2: Assign Templates** (HRM > Payroll > Salary Assignment)
- Select each staff member and assign their salary template

**Step 3: Process Monthly Salary** (HRM > Payroll > Salary Payment)
- Select month and year
- Review each staff member's salary breakdown
- Click Process to generate payslips
- Print payslips for distribution

### 6.5 Financial Reports

| Menu | What you see |
|------|-------------|
| Fees Report > Student Fees Report | Per-student payment details by date range |
| Fees Report > Receipts Report | All payment receipts in a period |
| Fees Report > Due Fees Report | All students with outstanding balances |
| Fees Report > Fine Report | Late payment penalties collected |
| Accounting > Account Statement | Bank account transaction history |
| Accounting > Income vs Expense | Monthly comparison chart |

---

## 7. LIBRARIAN GUIDE

**Role:** Manages the school library - books, issues, returns.
**Login:** Use your librarian email (e.g., ruth.wambui@sunriseacademy.co.ke) and password.

### 7.1 Setting Up the Library

**Book Categories** (Library > Categories):
Create categories: Textbooks, Fiction, Reference, Kiswahili Literature, Science, History.

**Adding Books** (Library > Books):
1. Click Add Book
2. Enter: Title, Author, ISBN, Category, Publisher, Edition
3. Enter purchase date and price
4. Enter total stock (number of copies)
5. Click Save

### 7.2 Issuing Books

**Menu: Library > Manage Books**

When a student or teacher wants to borrow a book:
1. Select the book
2. Select the borrower (student or staff)
3. Set issue date and return date (usually 2 weeks)
4. Click Issue

### 7.3 Returning Books

When someone returns a book:
1. Go to Library > Manage Books
2. Find the issued book
3. Click Return
4. If returned late, the system calculates a fine automatically

### 7.4 Book Requests

Students can request books from their dashboard. You see these requests at Library > Book Requests and can approve or reject them.

### 7.5 Tracking

- View all currently issued books and who has them
- See overdue books
- Track total stock vs. issued copies

---

## 8. PARENT GUIDE

**Role:** View your child's academic progress, fees, and school activities.
**Login:** Use your parent email (e.g., john.otieno@gmail.com) and password.

### 8.1 Dashboard

When you login, you see your child's dashboard showing:
- **Child's name, class, and section**
- **Fee payment chart:** How much has been paid vs. how much is due
- **Attendance chart:** Monthly Present/Absent/Late percentage
- **Recent school events**
- **Calendar** with upcoming events

If you have multiple children in the school, you can switch between them from the dashboard.

### 8.2 Checking Your Child's Attendance

**Menu: Attendance**

See your child's attendance record for any month:
- Green = Present
- Red = Absent
- Yellow = Late
- Shows total working days, days attended, and attendance percentage

### 8.3 Viewing Exam Results

**Menu: Report Card**

See your child's exam results:
- For **CBC classes (PP1-Grade 9):** Competency levels (EE, ME, AE, BE) per learning area
- For **Form 1-4:** Marks, grades, GPA, rank, pass/fail

### 8.4 Checking Fees

**Menu: Fees Invoice**

See your child's fee status:
- Total fees charged
- Amount paid so far
- Outstanding balance
- Payment history with dates and receipt references

### 8.5 Viewing Homework

**Menu: Homework**

See homework assignments given to your child with:
- Subject
- Description of the assignment
- Date given and submission deadline

### 8.6 Class Schedule

**Menu: Class Schedule**

See your child's weekly timetable:
- Monday to Friday
- Subject, teacher, and time for each period

### 8.7 Exam Schedule

**Menu: Exam Schedule**

See upcoming exam dates, times, subjects, and venues.

### 8.8 School Events

**Menu: Events**

See school events and activities on the calendar.

### 8.9 Transport

**Menu: Route**

If your child uses school transport, see:
- Bus route and vehicle details
- Pickup/dropoff points and times
- Driver's name and phone number

### 8.10 Communicating with School

**Menu: Message > Mailbox**

Send and receive messages with teachers and school admin. Use this for:
- Reporting your child's absence
- Asking about school activities
- Discussing your child's progress

### 8.11 Library

**Menu: Library**

Browse the school library catalog and request books for your child.

---

## 9. STUDENT GUIDE

**Role:** View your own academic progress, assignments, and school information.
**Login:** Use your student email (e.g., brian.otieno@student.sunrise.ke) and password.

### 9.1 Dashboard

Your dashboard shows:
- Your name, class, and section
- Fee payment status (how much is paid vs. due)
- Attendance chart (Present/Absent/Late for the year)
- Recent school events
- Calendar

### 9.2 Class Schedule

**Menu: Class Schedule**

See your weekly timetable with subjects, teachers, and times for Monday through Friday.

### 9.3 Homework

**Menu: Homework**

See all homework assigned to your class:
- Subject name
- Assignment description
- Date given and submission deadline

### 9.4 Exam Schedule

**Menu: Exam Schedule**

See your upcoming exams:
- Date and time for each subject
- Exam hall/room

### 9.5 Report Card

**Menu: Report Card**

View your exam results after marks are entered:
- **CBC (PP1-Grade 9):** Your competency levels per learning area
- **Traditional (Form 1-4):** Your marks, grades, and GPA

### 9.6 Attendance

**Menu: Attendance**

Check your attendance record by month. See which days you were Present, Absent, or Late.

### 9.7 Fees

**Menu: Fees Invoice**

See your fee status:
- What fees are charged
- What has been paid
- Outstanding balance

### 9.8 Library

**Menu: Library > Books**

Browse available books in the school library. You can:
- Search for books by title or author
- Request to borrow a book
- See your currently borrowed books and return dates

### 9.9 Leave Request

**Menu: Leave Request**

If you need to be absent:
1. Click Apply for Leave
2. Select leave type
3. Enter start date and end date
4. Write the reason
5. Attach supporting document (optional)
6. Submit

Your class teacher or admin will approve or reject the request.

### 9.10 Transport & Hostel

**Menu: Route** - See your school bus route, pickup point, and driver details.

**Menu: Hostel** - If you're a boarder, see your hostel, room number, and bed assignment.

### 9.11 Live Classes

**Menu: Live Class**

If your school uses virtual classes (Zoom), you can join them from here. You'll see:
- Class title
- Date and time
- Meeting link

### 9.12 Messages

**Menu: Message > Mailbox**

Send messages to your teachers or school admin. Use this for:
- Asking questions about assignments
- Reporting issues
- General communication

---

## QUICK REFERENCE

### Common Tasks by Role

| Task | Who does it |
|------|------------|
| Create a new school | Superadmin |
| Add a teacher | School Admin |
| Admit a new student | School Admin or Teacher (with approval) |
| Bulk import students | School Admin or Teacher (with approval) |
| Take daily attendance | Teacher |
| Assign homework | Teacher |
| Enter exam marks | Teacher |
| Enter CBC assessments | Teacher |
| Collect fees | Accountant or School Admin |
| Issue library books | Librarian |
| Process salary | Accountant or School Admin |
| Record expenses | Accountant |
| View child's report card | Parent |
| View own grades | Student |
| Send SMS to parents | School Admin or Teacher |

### System URLs

| Page | URL |
|------|-----|
| Login | /authentication |
| Dashboard | /dashboard |
| Student List | /student/view |
| Create Admission | /student/add |
| Request Admission | /admission_request/add |
| Bulk Request CSV | /admission_request/csv_import |
| Pending Requests | /admission_request |
| Fee Collection | /fees/invoice_list |
| CBC Assessment | /cbc/assessment |
| CBC Report Card | /cbc/report_card |
| Mark Entry | /exam/mark_entry |
| Attendance | /attendance (depends on type) |
| Homework | /homework |
| Library | /library |

---

*DCK Solutions - Solving More, Building Better*
*System adapted for Kenyan schools with CBC and 8-4-4 curriculum support*
