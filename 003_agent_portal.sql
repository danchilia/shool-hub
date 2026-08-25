-- =============================================================
-- 003_agent_portal.sql
-- DCK Solutions Field Agent Portal
-- Run once against the `ramom` database
-- =============================================================
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;

-- ── DCK subscription plans (separate from school modules) ──
CREATE TABLE IF NOT EXISTS `dck_plan` (
  `id`                int(11)        NOT NULL AUTO_INCREMENT,
  `name`              varchar(100)   NOT NULL,
  `description`       text           DEFAULT NULL,
  `price`             decimal(10,2)  NOT NULL DEFAULT 0.00,
  `visit_fee`         decimal(10,2)  NOT NULL DEFAULT 0.00  COMMENT 'Flat amount paid per qualified visit',
  `commission_amount` decimal(10,2)  NOT NULL DEFAULT 0.00  COMMENT 'Fixed commission when school signs this plan',
  `active`            tinyint(1)     NOT NULL DEFAULT 1,
  `created_by`        int(11)        DEFAULT NULL,
  `created_at`        datetime       DEFAULT NULL,
  `updated_at`        datetime       DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT IGNORE INTO `dck_plan` (`name`, `description`, `price`, `visit_fee`, `commission_amount`, `active`, `created_at`) VALUES
('Basic',    'Core modules: student management, attendance, fees',          15000.00, 500.00, 2000.00, 1, NOW()),
('Standard', 'Full-featured: all Basic + exams, library, transport',        25000.00, 500.00, 4000.00, 1, NOW()),
('Premium',  'Everything + CBC/KNEC, M-Pesa, multi-branch, priority support',45000.00, 500.00, 7000.00, 1, NOW());

-- ── Field agents (role 8 in login_credential) ──────────────
CREATE TABLE IF NOT EXISTS `agent` (
  `id`         int(11)      NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name`  varchar(100) NOT NULL,
  `email`      varchar(150) NOT NULL,
  `phone`      varchar(20)  DEFAULT NULL,
  `region`     varchar(100) DEFAULT NULL,
  `photo`      varchar(200) DEFAULT 'default.png',
  `active`     tinyint(1)   NOT NULL DEFAULT 1,
  `created_by` int(11)      DEFAULT NULL,
  `created_at` datetime     DEFAULT NULL,
  `updated_at` datetime     DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `agent_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ── Prospect schools that agents manage ────────────────────
CREATE TABLE IF NOT EXISTS `agent_school` (
  `id`               int(11)      NOT NULL AUTO_INCREMENT,
  `agent_id`         int(11)      NOT NULL,
  `school_name`      varchar(200) NOT NULL,
  `principal_name`   varchar(150) DEFAULT NULL,
  `phone`            varchar(20)  DEFAULT NULL,
  `email`            varchar(150) DEFAULT NULL,
  `county`           varchar(100) DEFAULT NULL,
  `sub_county`       varchar(100) DEFAULT NULL,
  `num_students`     int(11)      DEFAULT 0,
  `current_system`   varchar(200) DEFAULT NULL,
  `interest_level`   enum('hot','warm','cold','unknown')                                       NOT NULL DEFAULT 'unknown',
  `status`           enum('lead','visited','demo_done','proposal_sent','follow_up','closed_won','closed_lost') NOT NULL DEFAULT 'lead',
  `assigned_plan_id` int(11)      DEFAULT NULL,
  `notes`            text         DEFAULT NULL,
  `created_at`       datetime     DEFAULT NULL,
  `updated_at`       datetime     DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ags_agent` (`agent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ── Visit / demo logs ──────────────────────────────────────
CREATE TABLE IF NOT EXISTS `agent_visit` (
  `id`                 int(11)   NOT NULL AUTO_INCREMENT,
  `agent_id`           int(11)   NOT NULL,
  `school_id`          int(11)   NOT NULL,
  `visit_date`         date      NOT NULL,
  `visit_type`         enum('demo','follow_up','setup','other') NOT NULL DEFAULT 'demo',
  `interest_level`     enum('hot','warm','cold','unknown')      NOT NULL DEFAULT 'unknown',
  `modules_demoed`     text      DEFAULT NULL,
  `notes`              text      DEFAULT NULL,
  `outcome`            enum('interested','not_interested','needs_followup','signed_up','pending') NOT NULL DEFAULT 'pending',
  `next_followup_date` date      DEFAULT NULL,
  `plan_id`            int(11)   DEFAULT NULL,
  `created_at`         datetime  DEFAULT NULL,
  `updated_at`         datetime  DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `agv_agent`  (`agent_id`),
  KEY `agv_school` (`school_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ── Agent earnings (visit fees + commissions) ──────────────
CREATE TABLE IF NOT EXISTS `agent_earning` (
  `id`          int(11)       NOT NULL AUTO_INCREMENT,
  `agent_id`    int(11)       NOT NULL,
  `visit_id`    int(11)       DEFAULT NULL,
  `school_id`   int(11)       DEFAULT NULL,
  `type`        enum('visit_fee','commission') NOT NULL,
  `amount`      decimal(10,2) NOT NULL DEFAULT 0.00,
  `description` varchar(255)  DEFAULT NULL,
  `status`      enum('pending','approved','paid','rejected') NOT NULL DEFAULT 'pending',
  `paid_date`   date          DEFAULT NULL,
  `paid_by`     int(11)       DEFAULT NULL,
  `created_at`  datetime      DEFAULT NULL,
  `updated_at`  datetime      DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `age_agent` (`agent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ── Agent expense claims ───────────────────────────────────
CREATE TABLE IF NOT EXISTS `agent_expense` (
  `id`           int(11)       NOT NULL AUTO_INCREMENT,
  `agent_id`     int(11)       NOT NULL,
  `visit_id`     int(11)       DEFAULT NULL,
  `school_id`    int(11)       DEFAULT NULL,
  `description`  varchar(255)  NOT NULL,
  `amount`       decimal(10,2) NOT NULL DEFAULT 0.00,
  `status`       enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `reviewed_by`  int(11)       DEFAULT NULL,
  `reviewed_at`  datetime      DEFAULT NULL,
  `review_note`  varchar(255)  DEFAULT NULL,
  `created_at`   datetime      DEFAULT NULL,
  `updated_at`   datetime      DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `agex_agent` (`agent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

COMMIT;
