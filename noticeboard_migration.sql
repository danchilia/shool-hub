-- ============================================================
-- Notice Board Module Migration
-- ============================================================

CREATE TABLE IF NOT EXISTS `notice_board` (
  `id`          INT(11) NOT NULL AUTO_INCREMENT,
  `title`       VARCHAR(300) NOT NULL,
  `details`     TEXT,
  `notice_date` DATE NOT NULL,
  `expiry_date` DATE DEFAULT NULL,
  `audience`    SET('all','students','parents','staff','teachers') NOT NULL DEFAULT 'all',
  `priority`    ENUM('normal','important','urgent') NOT NULL DEFAULT 'normal',
  `attachment`  VARCHAR(255) DEFAULT NULL,
  `posted_by`   INT(11) DEFAULT NULL,
  `branch_id`   INT(11) NOT NULL,
  `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_branch_date` (`branch_id`, `notice_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
