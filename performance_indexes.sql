-- DCK Performance Indexes
-- Run this once against your database.
-- All are CREATE INDEX IF NOT EXISTS — safe to re-run, skips existing indexes.
-- These target the most-queried tables in a multi-branch school system.

-- ============================================================
-- STUDENT TABLE
-- ============================================================
ALTER TABLE `student`
    ADD INDEX IF NOT EXISTS `idx_branch` (`branch_id`),
    ADD INDEX IF NOT EXISTS `idx_branch_class` (`branch_id`, `class_id`, `section_id`),
    ADD INDEX IF NOT EXISTS `idx_admission` (`admission_no`);

-- ============================================================
-- ATTENDANCE
-- ============================================================
ALTER TABLE `student_attendance`
    ADD INDEX IF NOT EXISTS `idx_branch_date` (`branch_id`, `attendance_date`),
    ADD INDEX IF NOT EXISTS `idx_class_date` (`class_id`, `section_id`, `attendance_date`),
    ADD INDEX IF NOT EXISTS `idx_student_date` (`student_id`, `attendance_date`),
    ADD INDEX IF NOT EXISTS `idx_session` (`session_id`);

ALTER TABLE `staff_attendance`
    ADD INDEX IF NOT EXISTS `idx_branch_date` (`branch_id`, `attendance_date`),
    ADD INDEX IF NOT EXISTS `idx_staff_date` (`staff_id`, `attendance_date`),
    ADD INDEX IF NOT EXISTS `idx_session` (`session_id`);

-- ============================================================
-- FEES
-- ============================================================
ALTER TABLE `fee_payment_history`
    ADD INDEX IF NOT EXISTS `idx_branch` (`branch_id`),
    ADD INDEX IF NOT EXISTS `idx_student` (`student_id`),
    ADD INDEX IF NOT EXISTS `idx_branch_date` (`branch_id`, `paid_date`);

ALTER TABLE `fee_allocation`
    ADD INDEX IF NOT EXISTS `idx_branch` (`branch_id`),
    ADD INDEX IF NOT EXISTS `idx_student` (`student_id`);

ALTER TABLE `fee_invoice`
    ADD INDEX IF NOT EXISTS `idx_branch` (`branch_id`),
    ADD INDEX IF NOT EXISTS `idx_student` (`student_id`);

-- ============================================================
-- EXAM / MARKS
-- ============================================================
ALTER TABLE `mark_entry`
    ADD INDEX IF NOT EXISTS `idx_branch_exam` (`branch_id`, `exam_id`),
    ADD INDEX IF NOT EXISTS `idx_student_exam` (`student_id`, `exam_id`),
    ADD INDEX IF NOT EXISTS `idx_class_exam` (`class_id`, `section_id`, `exam_id`);

ALTER TABLE `exam`
    ADD INDEX IF NOT EXISTS `idx_branch` (`branch_id`),
    ADD INDEX IF NOT EXISTS `idx_session` (`session_id`);

-- ============================================================
-- CBC ASSESSMENT
-- ============================================================
ALTER TABLE `cbc_assessment`
    ADD INDEX IF NOT EXISTS `idx_branch_exam` (`branch_id`, `exam_id`),
    ADD INDEX IF NOT EXISTS `idx_student` (`student_id`, `exam_id`);

ALTER TABLE `cbc_behaviour_assessment`
    ADD INDEX IF NOT EXISTS `idx_branch_exam` (`branch_id`, `exam_id`),
    ADD INDEX IF NOT EXISTS `idx_student` (`student_id`, `exam_id`);

-- ============================================================
-- BIOMETRIC
-- ============================================================
ALTER TABLE `biometric_logs`
    ADD INDEX IF NOT EXISTS `idx_branch_date` (`branch_id`, `scan_time`),
    ADD INDEX IF NOT EXISTS `idx_device` (`device_id`),
    ADD INDEX IF NOT EXISTS `idx_processed` (`processed`, `branch_id`);

-- ============================================================
-- NOTIFICATIONS / SMS / MESSAGES
-- ============================================================
ALTER TABLE `notification`
    ADD INDEX IF NOT EXISTS `idx_branch` (`branch_id`),
    ADD INDEX IF NOT EXISTS `idx_student` (`student_id`);

-- ============================================================
-- EMPLOYEE
-- ============================================================
ALTER TABLE `employee`
    ADD INDEX IF NOT EXISTS `idx_branch` (`branch_id`),
    ADD INDEX IF NOT EXISTS `idx_role` (`role_id`, `branch_id`);

-- ============================================================
-- ADMISSION REQUESTS
-- ============================================================
ALTER TABLE `admission_requests`
    ADD INDEX IF NOT EXISTS `idx_branch_status` (`branch_id`, `status`),
    ADD INDEX IF NOT EXISTS `idx_requested_by` (`requested_by`);

-- ============================================================
-- SESSIONS (heavily joined in every report)
-- ============================================================
ALTER TABLE `sessions`
    ADD INDEX IF NOT EXISTS `idx_branch_active` (`branch_id`, `is_active`);
