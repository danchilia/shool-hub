-- 006: CBC Senior Secondary (Grade 10-12) Pathways
-- Run once on each environment

-- Pathways table
CREATE TABLE IF NOT EXISTS `cbc_pathways` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `branch_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `branch_id` (`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Link learning areas to a pathway (senior secondary only)
ALTER TABLE `cbc_learning_areas`
  ADD COLUMN `pathway_id` INT(11) DEFAULT NULL AFTER `level`;

-- Record which pathway each student is in (senior secondary only)
ALTER TABLE `student`
  ADD COLUMN `cbc_pathway_id` INT(11) DEFAULT NULL;

-- Permission entry
INSERT INTO `permission` (`module_id`, `name`, `prefix`, `show_view`, `show_add`, `show_edit`, `show_delete`, `created_at`)
SELECT 20, 'CBC Pathways', 'cbc_pathways', 1, 1, 1, 1, NOW()
WHERE NOT EXISTS (SELECT 1 FROM `permission` WHERE `prefix` = 'cbc_pathways');
