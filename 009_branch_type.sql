-- Migration 009: Add branch_type to identify university/college vs school
-- Run on server: mysql -u cstuser -pCSTschool@2026! cst_schoolhub < 009_branch_type.sql

ALTER TABLE `branch`
    ADD COLUMN `branch_type` VARCHAR(20) NOT NULL DEFAULT 'school'
    AFTER `address`;

-- Mark branches that were seeded as universities (heuristic: they have programmes but no CBC learning areas)
UPDATE `branch` b
SET b.`branch_type` = 'university'
WHERE EXISTS (
    SELECT 1 FROM `programme` p WHERE p.branch_id = b.id LIMIT 1
)
AND NOT EXISTS (
    SELECT 1 FROM `cbc_learning_areas` c WHERE c.branch_id = b.id LIMIT 1
);
