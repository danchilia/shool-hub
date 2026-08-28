-- Careers Portal — run this on both local (ramom) and production (cst_schoolhub) databases

CREATE TABLE IF NOT EXISTS `career_positions` (
    `id`           INT AUTO_INCREMENT PRIMARY KEY,
    `title`        VARCHAR(255) NOT NULL,
    `department`   VARCHAR(100) DEFAULT NULL,
    `description`  TEXT DEFAULT NULL,
    `requirements` TEXT DEFAULT NULL,
    `deadline`     DATE DEFAULT NULL,
    `status`       ENUM('open','closed') NOT NULL DEFAULT 'open',
    `created_at`   DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `career_applicants` (
    `id`         INT AUTO_INCREMENT PRIMARY KEY,
    `full_name`  VARCHAR(255) NOT NULL,
    `email`      VARCHAR(255) NOT NULL,
    `phone`      VARCHAR(20) DEFAULT NULL,
    `password`   VARCHAR(200) NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `unique_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `career_applications` (
    `id`           INT AUTO_INCREMENT PRIMARY KEY,
    `position_id`  INT NOT NULL,
    `applicant_id` INT NOT NULL,
    `cover_letter` TEXT DEFAULT NULL,
    `cv_orig_name` VARCHAR(255) DEFAULT NULL,
    `cv_enc_name`  VARCHAR(255) DEFAULT NULL,
    `status`       ENUM('pending','shortlisted','interview','rejected','hired') NOT NULL DEFAULT 'pending',
    `created_at`   DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `unique_application` (`position_id`, `applicant_id`),
    CONSTRAINT `fk_ca_position`  FOREIGN KEY (`position_id`)  REFERENCES `career_positions`(`id`)  ON DELETE CASCADE,
    CONSTRAINT `fk_ca_applicant` FOREIGN KEY (`applicant_id`) REFERENCES `career_applicants`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `career_replies` (
    `id`             INT AUTO_INCREMENT PRIMARY KEY,
    `application_id` INT NOT NULL,
    `sender`         ENUM('admin','applicant') NOT NULL DEFAULT 'admin',
    `message`        TEXT NOT NULL,
    `created_at`     DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_cr_application` FOREIGN KEY (`application_id`) REFERENCES `career_applications`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
