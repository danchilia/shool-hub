-- Migration 009: Add branch_type to identify university/college vs school
-- Run on server: mysql -u cstuser -pCSTschool@2026! cst_schoolhub < 009_branch_type.sql

ALTER TABLE `branch`
    ADD COLUMN IF NOT EXISTS `branch_type` VARCHAR(20) NOT NULL DEFAULT 'school'
    AFTER `address`;
