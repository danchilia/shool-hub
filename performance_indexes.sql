-- DCK Performance Indexes
-- Run this once against your database in phpMyAdmin > SQL tab.
-- Uses IF NOT EXISTS — safe to re-run, skips indexes that already exist.
-- Verified against actual table structures in ramom database.

-- ============================================================
-- ENROLL (student-to-branch/class mapping — joins on every student query)
-- ============================================================
ALTER TABLE `enroll`
    ADD INDEX IF NOT EXISTS `idx_branch` (`branch_id`),
    ADD INDEX IF NOT EXISTS `idx_branch_class` (`branch_id`, `class_id`, `section_id`),
    ADD INDEX IF NOT EXISTS `idx_student` (`student_id`),
    ADD INDEX IF NOT EXISTS `idx_session` (`session_id`);

-- ============================================================
-- STUDENT (no branch_id here — branch is in enroll)
-- ============================================================
ALTER TABLE `student`
    ADD INDEX IF NOT EXISTS `idx_register_no` (`register_no`),
    ADD INDEX IF NOT EXISTS `idx_parent` (`parent_id`);

-- ============================================================
-- STUDENT ATTENDANCE (date column, not attendance_date)
-- ============================================================
ALTER TABLE `student_attendance`
    ADD INDEX IF NOT EXISTS `idx_branch_date` (`branch_id`, `date`),
    ADD INDEX IF NOT EXISTS `idx_student_date` (`student_id`, `date`);

-- ============================================================
-- STAFF ATTENDANCE
-- ============================================================
ALTER TABLE `staff_attendance`
    ADD INDEX IF NOT EXISTS `idx_branch_date` (`branch_id`, `date`),
    ADD INDEX IF NOT EXISTS `idx_staff_date` (`staff_id`, `date`);

-- ============================================================
-- FEES
-- fee_payment_history has no branch_id/student_id directly —
-- joins through fee_allocation, so index the join key
-- ============================================================
ALTER TABLE `fee_payment_history`
    ADD INDEX IF NOT EXISTS `idx_allocation` (`allocation_id`),
    ADD INDEX IF NOT EXISTS `idx_date` (`date`);

ALTER TABLE `fee_allocation`
    ADD INDEX IF NOT EXISTS `idx_branch` (`branch_id`),
    ADD INDEX IF NOT EXISTS `idx_student` (`student_id`),
    ADD INDEX IF NOT EXISTS `idx_branch_student` (`branch_id`, `student_id`),
    ADD INDEX IF NOT EXISTS `idx_session` (`session_id`);

-- ============================================================
-- EXAM
-- ============================================================
ALTER TABLE `exam`
    ADD INDEX IF NOT EXISTS `idx_branch_session` (`branch_id`, `session_id`);

-- ============================================================
-- MARKS (table is called `mark`, not mark_entry)
-- ============================================================
ALTER TABLE `mark`
    ADD INDEX IF NOT EXISTS `idx_branch` (`branch_id`),
    ADD INDEX IF NOT EXISTS `idx_branch_exam` (`branch_id`, `exam_id`),
    ADD INDEX IF NOT EXISTS `idx_student_exam` (`student_id`, `exam_id`),
    ADD INDEX IF NOT EXISTS `idx_class_exam` (`class_id`, `section_id`, `exam_id`);

-- ============================================================
-- STAFF
-- ============================================================
ALTER TABLE `staff`
    ADD INDEX IF NOT EXISTS `idx_branch` (`branch_id`);

-- ============================================================
-- CBC ASSESSMENT (branch_id, student_id, learning_area_id already indexed — add composites)
-- ============================================================
ALTER TABLE `cbc_assessment`
    ADD INDEX IF NOT EXISTS `idx_branch_exam` (`branch_id`, `exam_id`),
    ADD INDEX IF NOT EXISTS `idx_student_exam` (`student_id`, `exam_id`);

ALTER TABLE `cbc_behaviour_assessment`
    ADD INDEX IF NOT EXISTS `idx_branch` (`branch_id`),
    ADD INDEX IF NOT EXISTS `idx_branch_exam` (`branch_id`, `exam_id`),
    ADD INDEX IF NOT EXISTS `idx_student_exam` (`student_id`, `exam_id`);

-- ============================================================
-- BIOMETRIC LOGS (branch_id, processed already indexed — add composite + device)
-- ============================================================
ALTER TABLE `biometric_logs`
    ADD INDEX IF NOT EXISTS `idx_branch_date` (`branch_id`, `scan_time`),
    ADD INDEX IF NOT EXISTS `idx_device` (`device_id`);
