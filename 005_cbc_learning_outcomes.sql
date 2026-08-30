-- ================================================================
-- 005: CBC Learning Outcomes / Performance Indicators
-- Run AFTER 004_cbc_sub_strands.sql
-- ================================================================

-- 1. Learning outcomes table
CREATE TABLE IF NOT EXISTS `cbc_learning_outcomes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(20) DEFAULT NULL COMMENT 'e.g. LO1, PI2',
  `name` TEXT NOT NULL,
  `sub_strand_id` INT(11) DEFAULT NULL,
  `strand_id` INT(11) NOT NULL,
  `learning_area_id` INT(11) NOT NULL,
  `branch_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_sub_strand` (`sub_strand_id`),
  KEY `idx_strand` (`strand_id`),
  KEY `idx_la` (`learning_area_id`),
  KEY `idx_branch` (`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Add learning_outcome_id to cbc_assessment
ALTER TABLE `cbc_assessment`
  ADD COLUMN `learning_outcome_id` INT(11) DEFAULT NULL AFTER `sub_strand_id`;

-- 3. Permission (module_id 20 = CBC Assessment)
INSERT INTO `permission` (`module_id`, `name`, `prefix`, `show_view`, `show_add`, `show_edit`, `show_delete`, `created_at`)
VALUES (20, 'CBC Learning Outcomes', 'cbc_learning_outcomes', 1, 1, 1, 1, NOW());
