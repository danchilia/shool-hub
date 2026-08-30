-- Migration 003: Subscription paywall — first-time plan selection
-- Run once on the server: mysql -u cstuser -p cst_schoolhub < 003_subscription_paywall.sql

-- Add plan_id and billing_cycle to subscription_payments so the callback
-- knows which plan to activate after a first-time M-Pesa payment
ALTER TABLE subscription_payments
  ADD COLUMN plan_id INT NULL DEFAULT NULL AFTER branch_id,
  ADD COLUMN billing_cycle ENUM('monthly','yearly') NULL DEFAULT NULL AFTER plan_id,
  ADD COLUMN updated_at DATETIME NULL DEFAULT NULL AFTER created_at;
