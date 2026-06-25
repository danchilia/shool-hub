-- =====================================================
-- Kenya School System Migration Script
-- Adapts the Multi-Branch School Management System
-- for Kenyan schools (CBC, M-Pesa, Subscriptions, etc.)
-- =====================================================

-- ---------------------------------------------------------
-- 1. ALTER EXISTING TABLES
-- ---------------------------------------------------------

ALTER TABLE `class`
  ADD COLUMN `curriculum_type` ENUM('cbc','844') NOT NULL DEFAULT '844' AFTER `name_numeric`,
  ADD COLUMN `level` ENUM('pp','lower_primary','upper_primary','junior_secondary','senior_secondary') DEFAULT NULL AFTER `curriculum_type`;

ALTER TABLE `exam`
  ADD COLUMN `grading_system` ENUM('traditional','cbc') NOT NULL DEFAULT 'traditional' AFTER `type_id`;

ALTER TABLE `student`
  ADD COLUMN `upi_number` VARCHAR(50) DEFAULT NULL AFTER `register_no`;

ALTER TABLE `payment_config`
  ADD COLUMN `mpesa_consumer_key` VARCHAR(255) DEFAULT NULL,
  ADD COLUMN `mpesa_consumer_secret` VARCHAR(255) DEFAULT NULL,
  ADD COLUMN `mpesa_shortcode` VARCHAR(20) DEFAULT NULL,
  ADD COLUMN `mpesa_passkey` VARCHAR(255) DEFAULT NULL,
  ADD COLUMN `mpesa_sandbox` TINYINT(4) DEFAULT 1,
  ADD COLUMN `mpesa_status` TINYINT(4) DEFAULT 0,
  ADD COLUMN `mpesa_callback_url` VARCHAR(500) DEFAULT NULL;

-- ---------------------------------------------------------
-- 2. CBC GRADING TABLES
-- ---------------------------------------------------------

CREATE TABLE `cbc_learning_areas` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `subject_id` INT(11) DEFAULT NULL,
  `level` ENUM('pp','lower_primary','upper_primary','junior_secondary') NOT NULL,
  `branch_id` INT(11) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_branch_level` (`branch_id`, `level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `cbc_strands` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `learning_area_id` INT(11) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `branch_id` INT(11) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_learning_area` (`learning_area_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `cbc_assessment` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `student_id` INT(11) NOT NULL,
  `exam_id` INT(11) NOT NULL,
  `learning_area_id` INT(11) NOT NULL,
  `strand_id` INT(11) DEFAULT NULL,
  `competency_level` ENUM('EE','ME','AE','BE') NOT NULL,
  `class_id` INT(11) NOT NULL,
  `section_id` INT(11) NOT NULL,
  `remarks` TEXT,
  `assessed_by` INT(11) DEFAULT NULL,
  `session_id` INT(11) NOT NULL,
  `branch_id` INT(11) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_student_exam` (`student_id`, `exam_id`),
  KEY `idx_branch_session` (`branch_id`, `session_id`),
  KEY `idx_learning_area` (`learning_area_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `cbc_behaviour_assessment` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `student_id` INT(11) NOT NULL,
  `exam_id` INT(11) NOT NULL,
  `category` VARCHAR(100) NOT NULL,
  `rating` ENUM('EE','ME','AE','BE') NOT NULL,
  `remarks` TEXT,
  `session_id` INT(11) NOT NULL,
  `branch_id` INT(11) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_student_exam` (`student_id`, `exam_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ---------------------------------------------------------
-- 3. ADMISSION REQUESTS TABLE
-- ---------------------------------------------------------

CREATE TABLE `admission_requests` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `request_data` LONGTEXT NOT NULL,
  `guardian_data` LONGTEXT DEFAULT NULL,
  `class_id` INT(11) NOT NULL,
  `section_id` INT(11) NOT NULL,
  `roll` INT(11) DEFAULT NULL,
  `session_id` INT(11) NOT NULL,
  `branch_id` INT(11) NOT NULL,
  `requested_by` INT(11) NOT NULL,
  `status` ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `reviewed_by` INT(11) DEFAULT NULL,
  `review_remarks` TEXT,
  `reviewed_at` DATETIME DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_branch_status` (`branch_id`, `status`),
  KEY `idx_requested_by` (`requested_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ---------------------------------------------------------
-- 4. M-PESA TRANSACTIONS TABLE
-- ---------------------------------------------------------

CREATE TABLE `mpesa_transactions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `merchant_request_id` VARCHAR(100) DEFAULT NULL,
  `checkout_request_id` VARCHAR(100) DEFAULT NULL,
  `transaction_id` VARCHAR(50) DEFAULT NULL,
  `phone_number` VARCHAR(15) NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `status` ENUM('pending','completed','failed','cancelled') NOT NULL DEFAULT 'pending',
  `result_code` INT(11) DEFAULT NULL,
  `result_desc` TEXT,
  `student_id` INT(11) DEFAULT NULL,
  `allocation_id` INT(11) DEFAULT NULL,
  `type_id` INT(11) DEFAULT NULL,
  `branch_id` INT(11) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_checkout_request` (`checkout_request_id`),
  KEY `idx_transaction_id` (`transaction_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ---------------------------------------------------------
-- 5. SUBSCRIPTION TABLES
-- ---------------------------------------------------------

CREATE TABLE `subscription_plans` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `max_students` INT(11) NOT NULL DEFAULT 0,
  `max_staff` INT(11) NOT NULL DEFAULT 0,
  `monthly_price` DECIMAL(10,2) NOT NULL DEFAULT 0,
  `yearly_price` DECIMAL(10,2) NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `branch_subscriptions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `branch_id` INT(11) NOT NULL,
  `plan_id` INT(11) NOT NULL,
  `billing_cycle` ENUM('monthly','yearly') NOT NULL DEFAULT 'monthly',
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `status` ENUM('active','expired','cancelled','trial') NOT NULL DEFAULT 'trial',
  `auto_renew` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_branch` (`branch_id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `subscription_invoices` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `subscription_id` INT(11) NOT NULL,
  `branch_id` INT(11) NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `due_date` DATE NOT NULL,
  `paid_date` DATE DEFAULT NULL,
  `status` ENUM('pending','paid','overdue') NOT NULL DEFAULT 'pending',
  `payment_reference` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_branch` (`branch_id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ---------------------------------------------------------
-- 6. PERMISSION ENTRIES
-- ---------------------------------------------------------

INSERT INTO `permission_modules` (`id`, `name`, `prefix`, `system`, `sorted`, `created_at`) VALUES
(20, 'CBC Assessment', 'cbc_assessment', 1, 20, NOW()),
(21, 'Subscriptions', 'subscriptions', 1, 21, NOW());

INSERT INTO `permission` (`id`, `module_id`, `name`, `prefix`, `show_view`, `show_add`, `show_edit`, `show_delete`, `created_at`) VALUES
(110, 20, 'CBC Learning Areas', 'cbc_learning_areas', 1, 1, 1, 1, NOW()),
(111, 20, 'CBC Strands', 'cbc_strands', 1, 1, 1, 1, NOW()),
(112, 20, 'CBC Assessment Entry', 'cbc_assessment', 1, 1, 1, 0, NOW()),
(113, 20, 'CBC Report Card', 'cbc_report_card', 1, 0, 0, 0, NOW()),
(114, 20, 'CBC Behaviour Assessment', 'cbc_behaviour', 1, 1, 1, 0, NOW()),
(115, 2, 'Admission Request', 'admission_request', 1, 1, 0, 1, NOW()),
(116, 2, 'Admission Approval', 'admission_approval', 1, 1, 0, 0, NOW()),
(117, 21, 'Subscription Management', 'subscription_management', 1, 1, 1, 1, NOW());

-- Grant admission_request permission to Teacher role (id=3)
INSERT INTO `staff_privileges` (`role_id`, `permission_id`, `is_add`, `is_edit`, `is_view`, `is_delete`) VALUES
(3, 115, 1, 0, 1, 1),
(3, 112, 0, 0, 0, 0),
(3, 114, 0, 0, 0, 0);

-- Grant admission_approval permission to Admin role (id=2)
INSERT INTO `staff_privileges` (`role_id`, `permission_id`, `is_add`, `is_edit`, `is_view`, `is_delete`) VALUES
(2, 116, 1, 0, 1, 0);

-- ---------------------------------------------------------
-- 7. NEW PAYMENT TYPE
-- ---------------------------------------------------------

INSERT INTO `payment_types` (`id`, `name`) VALUES (11, 'M-Pesa');

-- ---------------------------------------------------------
-- 8. NEW SMS PROVIDER
-- ---------------------------------------------------------

INSERT INTO `sms_api` (`id`, `name`) VALUES (6, 'africastalking');

-- ---------------------------------------------------------
-- 9. DEFAULT SUBSCRIPTION PLANS
-- ---------------------------------------------------------

INSERT INTO `subscription_plans` (`name`, `description`, `max_students`, `max_staff`, `monthly_price`, `yearly_price`, `is_active`) VALUES
('Basic', 'Up to 200 students, 20 staff', 200, 20, 5000.00, 50000.00, 1),
('Standard', 'Up to 500 students, 50 staff', 500, 50, 10000.00, 100000.00, 1),
('Premium', 'Up to 2000 students, 200 staff', 2000, 200, 25000.00, 250000.00, 1),
('Unlimited', 'Unlimited students and staff', 0, 0, 50000.00, 500000.00, 1);
