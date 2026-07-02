-- =====================================================
-- LPO (Local Purchase Order) Module Migration
-- =====================================================

-- 1. SUPPLIERS TABLE
CREATE TABLE `suppliers` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `contact_person` VARCHAR(255) DEFAULT NULL,
  `phone` VARCHAR(50) DEFAULT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `kra_pin` VARCHAR(50) DEFAULT NULL,
  `address` TEXT,
  `city` VARCHAR(100) DEFAULT NULL,
  `notes` TEXT,
  `branch_id` INT(11) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_branch` (`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 2. PURCHASE ORDERS TABLE
CREATE TABLE `purchase_orders` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `lpo_number` VARCHAR(50) NOT NULL,
  `supplier_id` INT(11) NOT NULL,
  `order_date` DATE NOT NULL,
  `valid_until` DATE DEFAULT NULL,
  `delivery_date` DATE DEFAULT NULL,
  `delivery_address` VARCHAR(500) DEFAULT 'School Gate',
  `subtotal` DECIMAL(12,2) NOT NULL DEFAULT 0,
  `tax_amount` DECIMAL(12,2) NOT NULL DEFAULT 0,
  `total_amount` DECIMAL(12,2) NOT NULL DEFAULT 0,
  `status` ENUM('draft','pending_approval','approved','sent','delivered','partially_delivered','paid','closed','cancelled') NOT NULL DEFAULT 'draft',
  `voucher_head_id` INT(11) DEFAULT NULL,
  `account_id` INT(11) DEFAULT NULL,
  `payment_date` DATE DEFAULT NULL,
  `payment_reference` VARCHAR(255) DEFAULT NULL,
  `payment_method` VARCHAR(50) DEFAULT NULL,
  `notes` TEXT,
  `prepared_by` INT(11) NOT NULL,
  `approved_by` INT(11) DEFAULT NULL,
  `approved_at` DATETIME DEFAULT NULL,
  `rejection_reason` TEXT,
  `received_by` INT(11) DEFAULT NULL,
  `received_at` DATETIME DEFAULT NULL,
  `paid_by` INT(11) DEFAULT NULL,
  `branch_id` INT(11) NOT NULL,
  `session_id` INT(11) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_lpo_number` (`lpo_number`, `branch_id`),
  KEY `idx_branch_status` (`branch_id`, `status`),
  KEY `idx_supplier` (`supplier_id`),
  KEY `idx_session` (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 3. PURCHASE ORDER ITEMS TABLE
CREATE TABLE `purchase_order_items` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `purchase_order_id` INT(11) NOT NULL,
  `item_name` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `quantity` DECIMAL(10,2) NOT NULL DEFAULT 1,
  `unit` VARCHAR(50) DEFAULT 'pcs',
  `unit_price` DECIMAL(12,2) NOT NULL DEFAULT 0,
  `total_price` DECIMAL(12,2) NOT NULL DEFAULT 0,
  `delivered_quantity` DECIMAL(10,2) NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_po` (`purchase_order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 4. PERMISSIONS
INSERT INTO `permission_modules` (`id`, `name`, `prefix`, `system`, `sorted`, `created_at`) VALUES
(22, 'LPO Management', 'lpo', 1, 22, NOW());

INSERT INTO `permission` (`id`, `module_id`, `name`, `prefix`, `show_view`, `show_add`, `show_edit`, `show_delete`, `created_at`) VALUES
(118, 22, 'Purchase Orders', 'purchase_orders', 1, 1, 1, 1, NOW()),
(119, 22, 'LPO Approval', 'lpo_approval', 1, 1, 0, 0, NOW()),
(120, 22, 'Suppliers', 'suppliers', 1, 1, 1, 1, NOW());

-- Admin (role 2) - full LPO access
INSERT INTO `staff_privileges` (`role_id`, `permission_id`, `is_add`, `is_edit`, `is_view`, `is_delete`) VALUES
(2, 118, 1, 1, 1, 1),
(2, 119, 1, 0, 1, 0),
(2, 120, 1, 1, 1, 1);

-- Accountant (role 4) - create LPOs, manage suppliers, no approval
INSERT INTO `staff_privileges` (`role_id`, `permission_id`, `is_add`, `is_edit`, `is_view`, `is_delete`) VALUES
(4, 118, 1, 1, 1, 0),
(4, 120, 1, 1, 1, 0);
