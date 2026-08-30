-- ================================================================
-- 004: CBC Sub-Strands
-- Run this migration AFTER kenya_migration.sql and knec_8level_migration.sql
-- ================================================================

-- 1. Sub-strands table (3rd level: Learning Area → Strand → Sub-strand)
CREATE TABLE IF NOT EXISTS `cbc_sub_strands` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `strand_id` INT(11) NOT NULL,
  `learning_area_id` INT(11) NOT NULL,
  `branch_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_strand` (`strand_id`),
  KEY `idx_la` (`learning_area_id`),
  KEY `idx_branch` (`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Add sub_strand_id column to cbc_assessment
ALTER TABLE `cbc_assessment`
  ADD COLUMN `sub_strand_id` INT(11) DEFAULT NULL AFTER `strand_id`;

-- 3. Permission for cbc_sub_strands (module_id 20 = CBC Assessment)
INSERT INTO `permission` (`module_id`, `name`, `prefix`, `show_view`, `show_add`, `show_edit`, `show_delete`, `created_at`)
VALUES (20, 'CBC Sub-Strands', 'cbc_sub_strands', 1, 1, 1, 1, NOW());
