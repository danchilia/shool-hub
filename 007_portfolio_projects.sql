-- 007: CBC Portfolio & Project Assessment

CREATE TABLE IF NOT EXISTS `cbc_portfolio` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `student_id` INT(11) NOT NULL,
  `learning_area_id` INT(11) NOT NULL,
  `strand_id` INT(11) DEFAULT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `competency_level` VARCHAR(10) DEFAULT NULL,
  `evidence_file` VARCHAR(255) DEFAULT NULL,
  `entry_date` DATE NOT NULL,
  `session_id` INT(11) NOT NULL,
  `branch_id` INT(11) NOT NULL,
  `created_by` INT(11) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `branch_id` (`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `cbc_projects` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `learning_area_id` INT(11) DEFAULT NULL,
  `class_id` INT(11) NOT NULL,
  `section_id` INT(11) NOT NULL,
  `due_date` DATE DEFAULT NULL,
  `max_score` DECIMAL(5,2) DEFAULT 100.00,
  `session_id` INT(11) NOT NULL,
  `branch_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `branch_id` (`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `cbc_project_scores` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `project_id` INT(11) NOT NULL,
  `student_id` INT(11) NOT NULL,
  `score` DECIMAL(5,2) DEFAULT NULL,
  `competency_level` VARCHAR(10) DEFAULT NULL,
  `remarks` TEXT,
  `branch_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `project_student` (`project_id`, `student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `permission` (`module_id`, `name`, `prefix`, `show_view`, `show_add`, `show_edit`, `show_delete`, `created_at`)
SELECT 20, 'CBC Portfolio', 'cbc_portfolio', 1, 1, 1, 1, NOW()
WHERE NOT EXISTS (SELECT 1 FROM `permission` WHERE `prefix` = 'cbc_portfolio');

INSERT INTO `permission` (`module_id`, `name`, `prefix`, `show_view`, `show_add`, `show_edit`, `show_delete`, `created_at`)
SELECT 20, 'CBC Projects', 'cbc_projects', 1, 1, 1, 1, NOW()
WHERE NOT EXISTS (SELECT 1 FROM `permission` WHERE `prefix` = 'cbc_projects');
