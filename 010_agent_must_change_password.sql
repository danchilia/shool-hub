-- Migration 010: Add must_change_password flag to agent table
ALTER TABLE `agent`
    ADD COLUMN `must_change_password` TINYINT(1) NOT NULL DEFAULT 0 AFTER `active`;
