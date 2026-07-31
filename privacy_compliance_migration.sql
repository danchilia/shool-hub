-- Privacy Compliance Migration
-- Kenya Data Protection Act 2019 — communication opt-out flags for parent/guardian accounts
-- Run once. Existing parents default to opted-in (0 = not opted out).

ALTER TABLE `parent`
    ADD COLUMN IF NOT EXISTS `sms_optout`   TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Kenya DPA 2019: 1 = parent has opted out of bulk SMS',
    ADD COLUMN IF NOT EXISTS `email_optout` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Kenya DPA 2019: 1 = parent has opted out of bulk email';
