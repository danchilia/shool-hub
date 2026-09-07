-- Add interview scheduling fields to career_applications
ALTER TABLE `career_applications`
  ADD COLUMN `interview_date`  DATE         DEFAULT NULL,
  ADD COLUMN `interview_time`  TIME         DEFAULT NULL,
  ADD COLUMN `interview_link`  VARCHAR(500) DEFAULT NULL,
  ADD COLUMN `interview_notes` TEXT         DEFAULT NULL;
