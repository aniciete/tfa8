-- ====================================================================
-- IT0049 (Web System Technologies) - Technical Formative Assessment 8
-- Project: Laravel POS (Resource Controllers and Authentication)
-- File: schema_export.sql
-- ====================================================================
--
-- SUBMISSION REQUIREMENT NOTE:
-- "A link to a GitHub repository containing your raw project files, including
-- your database export or migration and seeder files."
--
-- SCHEMA SPECIFICATION (TFA8 extends TFA7 with Laravel Breeze Auth):
-- 1. Table `customers` (id, full_name, email UNIQUE, phone, timestamps)
-- 2. Table `users` (id, username UNIQUE, full_name, email UNIQUE,
--                   email_verified_at, password, role, avatar,
--                   remember_token, timestamps)
-- 3. Table `password_reset_tokens` & `sessions` for Laravel Auth/Session
--
-- Seeded demo staff credentials password: `password` (bcrypt hashed)
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
  `email` varchar(150) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'Staff Member',
  `avatar` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- Dumping data for table `users`
-- --------------------------------------------------------------------
LOCK TABLES `users` WRITE;
INSERT INTO `users` (`id`, `username`, `full_name`, `email`, `email_verified_at`, `password`, `role`, `avatar`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin.reyes', 'Carlos Reyes', 'admin.reyes@example.com', NOW(), '$2y$12$Oyy9tj2Pe3i/fRGzt2H0Ue9pO.slryKmmtZXomzQw5SOb/bqfpDzy', 'Store Administrator', NULL, NULL, NOW(), NOW()),
(2, 'mgr.castro', 'Beatriz Castro', 'mgr.castro@example.com', NOW(), '$2y$12$Oyy9tj2Pe3i/fRGzt2H0Ue9pO.slryKmmtZXomzQw5SOb/bqfpDzy', 'Shift Supervisor', NULL, NULL, NOW(), NOW()),
(3, 'cashier.valdez', 'Daniel Valdez', 'cashier.valdez@example.com', NOW(), '$2y$12$Oyy9tj2Pe3i/fRGzt2H0Ue9pO.slryKmmtZXomzQw5SOb/bqfpDzy', 'Cashier', NULL, NULL, NOW(), NOW()),
(4, 'cashier.santos', 'Camille Santos', 'cashier.santos@example.com', NOW(), '$2y$12$Oyy9tj2Pe3i/fRGzt2H0Ue9pO.slryKmmtZXomzQw5SOb/bqfpDzy', 'Cashier', NULL, NULL, NOW(), NOW()),
(5, 'inv.navarro', 'Leo Navarro', 'inv.navarro@example.com', NOW(), '$2y$12$Oyy9tj2Pe3i/fRGzt2H0Ue9pO.slryKmmtZXomzQw5SOb/bqfpDzy', 'Inventory Clerk', NULL, NULL, NOW(), NOW());
UNLOCK TABLES;

-- --------------------------------------------------------------------
-- Table structure for table `password_reset_tokens`
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- Table structure for table `sessions`
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
