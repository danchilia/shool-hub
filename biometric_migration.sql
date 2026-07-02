-- =====================================================
-- Biometric Attendance Module Migration
-- Generic - works with any fingerprint device brand
-- =====================================================

-- 1. BIOMETRIC DEVICES (registered scanners per branch)
CREATE TABLE `biometric_devices` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `device_name` VARCHAR(255) NOT NULL,
  `device_serial` VARCHAR(100) DEFAULT NULL,
  `location` VARCHAR(255) DEFAULT NULL,
  `device_type` ENUM('student','staff','both') NOT NULL DEFAULT 'both',
  `api_token` VARCHAR(64) NOT NULL,
  `last_seen` DATETIME DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `branch_id` INT(11) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_token` (`api_token`),
  KEY `idx_branch` (`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 2. BIOMETRIC ID MAPPING (links device fingerprint ID to student/staff)
CREATE TABLE `biometric_id_map` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `biometric_id` VARCHAR(50) NOT NULL,
  `person_type` ENUM('student','staff') NOT NULL,
  `person_id` INT(11) NOT NULL,
  `branch_id` INT(11) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_biometric_branch` (`biometric_id`, `branch_id`),
  KEY `idx_person` (`person_type`, `person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 3. RAW SCAN LOGS (every fingerprint scan received)
CREATE TABLE `biometric_logs` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `device_id` INT(11) DEFAULT NULL,
  `biometric_id` VARCHAR(50) NOT NULL,
  `person_type` ENUM('student','staff') DEFAULT NULL,
  `person_id` INT(11) DEFAULT NULL,
  `scan_time` DATETIME NOT NULL,
  `scan_type` ENUM('in','out','unknown') NOT NULL DEFAULT 'unknown',
  `matched` TINYINT(1) NOT NULL DEFAULT 0,
  `processed` TINYINT(1) NOT NULL DEFAULT 0,
  `source` ENUM('api_push','csv_import') NOT NULL DEFAULT 'api_push',
  `branch_id` INT(11) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_branch_date` (`branch_id`, `scan_time`),
  KEY `idx_person` (`person_type`, `person_id`),
  KEY `idx_processed` (`processed`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 4. PERMISSIONS
INSERT INTO `permission_modules` (`id`, `name`, `prefix`, `system`, `sorted`, `created_at`) VALUES
(23, 'Biometric Attendance', 'biometric', 1, 23, NOW());

INSERT INTO `permission` (`id`, `module_id`, `name`, `prefix`, `show_view`, `show_add`, `show_edit`, `show_delete`, `created_at`) VALUES
(121, 23, 'Biometric Devices', 'biometric_devices', 1, 1, 1, 1, NOW()),
(122, 23, 'Biometric ID Mapping', 'biometric_mapping', 1, 1, 1, 1, NOW()),
(123, 23, 'Biometric Logs', 'biometric_logs', 1, 1, 0, 0, NOW());

-- Admin (role 2) full access
INSERT INTO `staff_privileges` (`role_id`, `permission_id`, `is_add`, `is_edit`, `is_view`, `is_delete`) VALUES
(2, 121, 1, 1, 1, 1),
(2, 122, 1, 1, 1, 1),
(2, 123, 1, 0, 1, 0);
