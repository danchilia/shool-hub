-- Global Settings: new Kenya-relevant columns
-- Run once against your database

ALTER TABLE `global_settings`
  ADD COLUMN IF NOT EXISTS `nemis_code`      varchar(50)  NOT NULL DEFAULT '' AFTER `institution_code`,
  ADD COLUMN IF NOT EXISTS `website_url`     varchar(255) NOT NULL DEFAULT '' AFTER `youtube_url`,
  ADD COLUMN IF NOT EXISTS `instagram_url`   varchar(255) NOT NULL DEFAULT '' AFTER `website_url`,
  ADD COLUMN IF NOT EXISTS `whatsapp_no`     varchar(30)  NOT NULL DEFAULT '' AFTER `instagram_url`,
  ADD COLUMN IF NOT EXISTS `mpesa_paybill`   varchar(30)  NOT NULL DEFAULT '' AFTER `whatsapp_no`;
