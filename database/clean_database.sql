-- Clean Database Script
-- This script removes all transactional data while preserving user accounts
-- Run this in your MySQL database to reset the system

-- Disable foreign key checks temporarily
SET FOREIGN_KEY_CHECKS = 0;

-- Clear all notifications
TRUNCATE TABLE notifications;

-- Clear all messages and conversations
TRUNCATE TABLE messages;
TRUNCATE TABLE conversations;

-- Clear all transactions and related data
TRUNCATE TABLE size_transactions;
TRUNCATE TABLE transactions;

-- Clear all demand matches
TRUNCATE TABLE demand_matches;

-- Clear all demands
TRUNCATE TABLE demands;

-- Clear all product-related data
TRUNCATE TABLE product_images;
TRUNCATE TABLE sizes;
TRUNCATE TABLE products;

-- Clear inventory logs
TRUNCATE TABLE inventory_logs;

-- Clear product analytics
TRUNCATE TABLE product_analytics;

-- Clear farmer earnings
TRUNCATE TABLE farmer_earnings;

-- Clear reviews
TRUNCATE TABLE farmer_reviews;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- Display remaining users (should show admin, farmer, and buyer accounts)
SELECT id, name, email, role FROM users ORDER BY role, id;
