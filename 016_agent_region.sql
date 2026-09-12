-- Add county and sub_county region fields to agent table
ALTER TABLE `agent`
  ADD COLUMN `county`     VARCHAR(100) DEFAULT NULL,
  ADD COLUMN `sub_county` VARCHAR(100) DEFAULT NULL;
