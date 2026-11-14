-- ====================================================================
-- IT0049 (Web System Technologies) - Technical Formative Assessment 7
-- Project: Laravel POS (Forms, Validation, and File Upload)
-- File: schema_export.sql
-- ====================================================================
--
-- SUBMISSION REQUIREMENT NOTE:
-- "A link to a GitHub repository containing your raw project files, including
-- your database export or migration and seeder files."
--
-- SCHEMA SPECIFICATION (TFA7 extends TFA6):
-- 1. Table `customers` (id, full_name, email UNIQUE, phone, timestamps)
-- 2. Table `users` (id, username UNIQUE, full_name, role, avatar, timestamps)
--
-- Populated with 5 canonical sample records per table.
-- ====================================================================

-- --------------------------------------------------------------------
-- Table structure for table `customers`
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customers_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- Dumping data for table `customers`
-- --------------------------------------------------------------------
LOCK TABLES `customers` WRITE;
INSERT INTO `customers` (`id`, `full_name`, `email`, `phone`, `created_at`, `updated_at`) VALUES
(1, 'Elena Rostova', 'elena.rostova@example.com', '+1 (555) 234-5678', NOW(), NOW()),
(2, 'Marcus Vance', 'marcus.vance@example.com', '+1 (555) 345-6789', NOW(), NOW()),
(3, 'Aria Thorne', 'aria.thorne@example.com', '+1 (555) 456-7890', NOW(), NOW()),
(4, 'Julian Mercer', 'julian.mercer@example.com', '+1 (555) 567-8901', NOW(), NOW()),
(5, 'Sophia Lin', 'sophia.lin@example.com', '+1 (555) 678-9012', NOW(), NOW());
UNLOCK TABLES;

-- --------------------------------------------------------------------
-- Table structure for table `users`
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'Staff Member',
  `avatar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- Dumping data for table `users`
-- --------------------------------------------------------------------
LOCK TABLES `users` WRITE;
INSERT INTO `users` (`id`, `username`, `full_name`, `role`, `avatar`, `created_at`, `updated_at`) VALUES
(1, 'admin.reyes', 'Carlos Reyes', 'Store Administrator', NULL, NOW(), NOW()),
(2, 'mgr.castro', 'Beatriz Castro', 'Shift Supervisor', NULL, NOW(), NOW()),
(3, 'cashier.valdez', 'Daniel Valdez', 'Cashier', NULL, NOW(), NOW()),
(4, 'cashier.santos', 'Camille Santos', 'Cashier', NULL, NOW(), NOW()),
(5, 'inv.navarro', 'Leo Navarro', 'Inventory Clerk', NULL, NOW(), NOW());
UNLOCK TABLES;
