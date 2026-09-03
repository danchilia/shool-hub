-- Company-level SMTP settings for superadmin (fallback when a school has no email configured)
-- Run once against the database

ALTER TABLE `global_settings`
  ADD COLUMN `company_email`           varchar(255) NOT NULL DEFAULT '',
  ADD COLUMN `company_smtp_host`       varchar(255) NOT NULL DEFAULT '',
  ADD COLUMN `company_smtp_port`       varchar(10)  NOT NULL DEFAULT '587',
  ADD COLUMN `company_smtp_user`       varchar(255) NOT NULL DEFAULT '',
  ADD COLUMN `company_smtp_pass`       varchar(255) NOT NULL DEFAULT '',
  ADD COLUMN `company_smtp_encryption` varchar(10)  NOT NULL DEFAULT 'tls';
