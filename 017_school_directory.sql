-- School Directory — master list of all Kenyan schools
CREATE TABLE IF NOT EXISTS `school_directory` (
    `id`            INT AUTO_INCREMENT PRIMARY KEY,
    `school_name`   VARCHAR(255) NOT NULL,
    `type`          VARCHAR(100) DEFAULT NULL,
    `ownership`     VARCHAR(100) DEFAULT NULL,
    `area`          VARCHAR(150) DEFAULT NULL,
    `region`        VARCHAR(100) DEFAULT NULL,
    `county`        VARCHAR(100) DEFAULT NULL,
    `sub_county`    VARCHAR(100) DEFAULT NULL,
    `phone`         VARCHAR(30)  DEFAULT NULL,
    `road_location` VARCHAR(255) DEFAULT NULL,
    `status`        ENUM('active','pending_review') NOT NULL DEFAULT 'active',
    `added_by`      INT DEFAULT NULL COMMENT 'agent_id if agent-added, NULL if superadmin upload',
    `name_normalized` VARCHAR(255) DEFAULT NULL COMMENT 'lowercase trimmed for duplicate detection',
    `created_at`    DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_region`     (`region`),
    INDEX `idx_county`     (`county`),
    INDEX `idx_type`       (`type`),
    INDEX `idx_status`     (`status`),
    INDEX `idx_name_norm`  (`name_normalized`),
    INDEX `idx_phone`      (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Link agent pipeline schools to the directory
ALTER TABLE `agent_school`
    ADD COLUMN `directory_id` INT DEFAULT NULL;
