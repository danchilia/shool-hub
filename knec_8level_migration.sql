-- ============================================================
-- KNEC 8-Level CBC Grading Migration
-- Upgrades competency_level from 4 levels (EE/ME/AE/BE)
-- to 8 levels (EE1/EE2/ME1/ME2/AE1/AE2/BE1/BE2)
-- Run once; safe to re-run (IF NOT EXISTS guards)
-- ============================================================

-- Step 1: Expand competency_level to VARCHAR to hold 3-char codes
ALTER TABLE `cbc_assessment`
  MODIFY COLUMN `competency_level` VARCHAR(10) NOT NULL DEFAULT 'ME1';

ALTER TABLE `cbc_behaviour_assessment`
  MODIFY COLUMN `rating` VARCHAR(10) NOT NULL DEFAULT 'ME1';

-- Step 2: Migrate existing 4-level values to 8-level equivalents
--   EE → EE2 (was "Exceeding" — map to upper sub-level)
--   ME → ME2 (was "Meeting" — map to upper sub-level)
--   AE → AE1 (was "Approaching" — map to lower sub-level)
--   BE → BE1 (was "Below" — map to lower sub-level)
UPDATE `cbc_assessment` SET `competency_level` = 'EE2' WHERE `competency_level` = 'EE';
UPDATE `cbc_assessment` SET `competency_level` = 'ME2' WHERE `competency_level` = 'ME';
UPDATE `cbc_assessment` SET `competency_level` = 'AE1' WHERE `competency_level` = 'AE';
UPDATE `cbc_assessment` SET `competency_level` = 'BE1' WHERE `competency_level` = 'BE';

UPDATE `cbc_behaviour_assessment` SET `rating` = 'EE2' WHERE `rating` = 'EE';
UPDATE `cbc_behaviour_assessment` SET `rating` = 'ME2' WHERE `rating` = 'ME';
UPDATE `cbc_behaviour_assessment` SET `rating` = 'AE1' WHERE `rating` = 'AE';
UPDATE `cbc_behaviour_assessment` SET `rating` = 'BE1' WHERE `rating` = 'BE';
