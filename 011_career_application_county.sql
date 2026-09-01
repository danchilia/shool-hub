-- Migration 011: Add county field to career_applications
ALTER TABLE `career_applications`
    ADD COLUMN `county` VARCHAR(100) DEFAULT NULL AFTER `applicant_id`;
