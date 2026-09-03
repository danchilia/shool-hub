-- Company-level SMTP settings for superadmin (fallback when a school has no email configured)
-- Run once against the database

ALTER TABLE `global_settings`
  ADD COLUMN IF NOT EXISTS `company_email`           varchar(255) NOT NULL DEFAULT '' AFTER `youtube_url`,
  ADD COLUMN IF NOT EXISTS `company_smtp_host`       varchar(255) NOT NULL DEFAULT '' AFTER `company_email`,
  ADD COLUMN IF NOT EXISTS `company_smtp_port`       varchar(10)  NOT NULL DEFAULT '587' AFTER `company_smtp_host`,
  ADD COLUMN IF NOT EXISTS `company_smtp_user`       varchar(255) NOT NULL DEFAULT '' AFTER `company_smtp_port`,
  ADD COLUMN IF NOT EXISTS `company_smtp_pass`       varchar(255) NOT NULL DEFAULT '' AFTER `company_smtp_user`,
  ADD COLUMN IF NOT EXISTS `company_smtp_encryption` varchar(10)  NOT NULL DEFAULT 'tls' AFTER `company_smtp_pass`;
