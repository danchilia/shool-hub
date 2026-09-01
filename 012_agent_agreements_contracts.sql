-- Migration 012: Agent agreements (digital acceptance) and contracts (uploaded signed copies)

CREATE TABLE IF NOT EXISTS `agent_agreements` (
    `id`          INT AUTO_INCREMENT PRIMARY KEY,
    `agent_id`    INT NOT NULL,
    `type`        VARCHAR(50) NOT NULL DEFAULT 'starter_terms',
    `accepted_at` DATETIME NOT NULL,
    `ip_address`  VARCHAR(45) DEFAULT NULL,
    UNIQUE KEY `unique_agent_agreement` (`agent_id`, `type`),
    CONSTRAINT `fk_agr_agent` FOREIGN KEY (`agent_id`) REFERENCES `agent`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `agent_contracts` (
    `id`          INT AUTO_INCREMENT PRIMARY KEY,
    `agent_id`    INT NOT NULL,
    `level_name`  VARCHAR(50) NOT NULL,
    `file_path`   VARCHAR(500) DEFAULT NULL,
    `uploaded_at` DATETIME DEFAULT NULL,
    `status`      ENUM('pending_upload','uploaded','verified','rejected') NOT NULL DEFAULT 'pending_upload',
    `review_note` TEXT DEFAULT NULL,
    `reviewed_by` INT DEFAULT NULL,
    `reviewed_at` DATETIME DEFAULT NULL,
    `created_at`  DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `unique_agent_contract` (`agent_id`),
    CONSTRAINT `fk_con_agent` FOREIGN KEY (`agent_id`) REFERENCES `agent`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
