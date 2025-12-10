-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 10, 2025 at 06:41 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `agriconnect_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `buyers`
--

CREATE TABLE `buyers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `business_type` varchar(255) DEFAULT NULL,
  `preferred_products` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `buyers`
--

INSERT INTO `buyers` (`id`, `user_id`, `company_name`, `business_type`, `preferred_products`, `address`, `verified`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, NULL, NULL, NULL, 1, '2025-12-09 19:29:35', '2025-12-09 19:29:35'),
(2, 3, NULL, 'retailer', 'Eggs', 'Purok 1C', 0, '2025-12-09 19:37:26', '2025-12-09 19:37:26');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conversation_threads`
--

CREATE TABLE `conversation_threads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `buyer_id` bigint(20) UNSIGNED NOT NULL,
  `farmer_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `conversation_threads`
--

INSERT INTO `conversation_threads` (`id`, `buyer_id`, `farmer_id`, `created_at`, `updated_at`) VALUES
(1, 6, 735, '2025-12-07 05:16:21', '2025-12-07 05:16:21'),
(2, 5, 6, '2025-12-07 23:48:52', '2025-12-07 23:48:52'),
(3, 6, 2047, '2025-12-08 07:12:54', '2025-12-08 07:12:54'),
(4, 2048, 2055, '2025-12-09 19:42:07', '2025-12-09 19:42:07'),
(5, 2, 2055, '2025-12-09 19:47:46', '2025-12-09 19:47:46');

-- --------------------------------------------------------

--
-- Table structure for table `demands`
--

CREATE TABLE `demands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `buyer_id` bigint(20) UNSIGNED NOT NULL,
  `egg_type` varchar(255) DEFAULT NULL,
  `egg_size` varchar(255) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `location` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `delivery_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `farmers`
--

CREATE TABLE `farmers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `farm_name` varchar(255) NOT NULL,
  `farm_size` decimal(8,2) DEFAULT NULL,
  `farm_size_unit` varchar(255) NOT NULL DEFAULT 'hectares',
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `total_chickens` int(11) DEFAULT NULL,
  `farming_method` varchar(255) DEFAULT NULL,
  `average_rating` decimal(3,2) NOT NULL DEFAULT 0.00,
  `total_reviews` int(11) NOT NULL DEFAULT 0,
  `completed_orders` int(11) NOT NULL DEFAULT 0,
  `success_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `verification_status` enum('unverified','pending','verified','rejected') NOT NULL DEFAULT 'unverified',
  `verified_at` timestamp NULL DEFAULT NULL,
  `verified_by` bigint(20) UNSIGNED DEFAULT NULL,
  `verification_notes` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `accepting_orders` tinyint(1) NOT NULL DEFAULT 1,
  `operation_start_time` time DEFAULT NULL,
  `operation_end_time` time DEFAULT NULL,
  `operation_days` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`operation_days`)),
  `experience_years` int(11) DEFAULT NULL,
  `certification` varchar(255) DEFAULT NULL,
  `organic_certification` varchar(255) DEFAULT NULL,
  `certification_expiry_date` date DEFAULT NULL,
  `food_safety_certification` varchar(255) DEFAULT NULL,
  `gmp_certified` tinyint(1) NOT NULL DEFAULT 0,
  `halal_certified` tinyint(1) NOT NULL DEFAULT 0,
  `farm_address` text DEFAULT NULL,
  `business_registration_number` varchar(255) DEFAULT NULL,
  `business_type` varchar(255) NOT NULL DEFAULT 'Individual',
  `tax_id_number` varchar(255) DEFAULT NULL,
  `secondary_phone` varchar(255) DEFAULT NULL,
  `whatsapp_number` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `bank_account_number` varchar(255) DEFAULT NULL,
  `bank_account_name` varchar(255) DEFAULT NULL,
  `mobile_wallet_provider` varchar(255) DEFAULT NULL,
  `mobile_wallet_number` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `farmers`
--

INSERT INTO `farmers` (`id`, `user_id`, `farm_name`, `farm_size`, `farm_size_unit`, `latitude`, `longitude`, `total_chickens`, `farming_method`, `average_rating`, `total_reviews`, `completed_orders`, `success_rate`, `verification_status`, `verified_at`, `verified_by`, `verification_notes`, `is_active`, `accepting_orders`, `operation_start_time`, `operation_end_time`, `operation_days`, `experience_years`, `certification`, `organic_certification`, `certification_expiry_date`, `food_safety_certification`, `gmp_certified`, `halal_certified`, `farm_address`, `business_registration_number`, `business_type`, `tax_id_number`, `secondary_phone`, `whatsapp_number`, `bank_name`, `bank_account_number`, `bank_account_name`, `mobile_wallet_provider`, `mobile_wallet_number`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Boehm-Heathcote', 98.36, 'hectares', NULL, NULL, NULL, NULL, 4.33, 3, 4, 40.00, 'unverified', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, 9, 'Conventional', NULL, NULL, NULL, 0, 0, '993 Dan Tunnel\nNevafurt, MO 47995-5103', NULL, 'Individual', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-07 03:05:36', '2025-12-08 10:43:23', NULL),
(1023, 2047, 'Acido\'s', 2.50, 'hectares', NULL, NULL, NULL, NULL, 4.56, 8, 10, 50.00, 'unverified', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, 0, 0, 'tapok ADN', NULL, 'Individual', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-08 03:04:13', '2025-12-08 12:36:58', NULL),
(1024, 2049, 'Unnamed Farm', 2.50, 'hectares', NULL, NULL, NULL, NULL, 0.00, 0, 0, 0.00, 'unverified', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, 0, 0, 'Cabadbaran City', NULL, 'Individual', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-09 15:51:09', '2025-12-09 15:51:09', NULL),
(1025, 2050, 'Unnamed Farm', 2.50, 'hectares', NULL, NULL, NULL, NULL, 0.00, 0, 0, 0.00, 'unverified', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, 0, 0, 'Cabadbaran City', NULL, 'Individual', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-09 15:51:27', '2025-12-09 15:51:27', NULL),
(1026, 2051, 'Unnamed Farm', 2.50, 'hectares', NULL, NULL, NULL, NULL, 0.00, 0, 0, 0.00, 'unverified', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, 0, 0, 'Cabadbaran City', NULL, 'Individual', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-09 15:51:44', '2025-12-09 15:51:44', NULL),
(1027, 2052, 'Unnamed Farm', 2.50, 'hectares', NULL, NULL, NULL, NULL, 0.00, 0, 0, 0.00, 'unverified', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, 0, 0, 'Cabadbaran City', NULL, 'Individual', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-09 15:51:58', '2025-12-09 15:51:58', NULL),
(1028, 2053, 'Unnamed Farm', 2.40, 'hectares', NULL, NULL, NULL, NULL, 0.00, 0, 0, 0.00, 'unverified', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, 4, NULL, NULL, NULL, NULL, 0, 0, 'Cabadbaran City', NULL, 'Individual', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-09 15:54:27', '2025-12-09 15:54:27', NULL),
(1029, 2055, 'Unnamed Farm', 24.00, 'hectares', NULL, NULL, NULL, NULL, 5.00, 1, 1, 33.33, 'unverified', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, 0, 0, 'Cabadbaran City', NULL, 'Individual', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-09 16:00:47', '2025-12-09 19:54:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `farmer_activity_logs`
--

CREATE TABLE `farmer_activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `farmer_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `action` varchar(255) NOT NULL,
  `entity_type` varchar(255) NOT NULL,
  `entity_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `device_type` varchar(255) DEFAULT NULL,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `severity` enum('info','warning','error','critical') NOT NULL DEFAULT 'info',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `farmer_activity_logs`
--

INSERT INTO `farmer_activity_logs` (`id`, `farmer_id`, `user_id`, `action`, `entity_type`, `entity_id`, `description`, `old_values`, `new_values`, `metadata`, `ip_address`, `user_agent`, `device_type`, `tags`, `severity`, `created_at`) VALUES
(1, 1, 1, 'created', 'Product', 8, 'Created new product listing', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', 'Desktop', NULL, 'info', '2025-12-07 10:43:23'),
(2, 1, 1, 'updated', 'Product', 9, 'Updated product price', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', 'Desktop', NULL, 'info', '2025-12-06 10:43:23'),
(3, 1, 1, 'updated', 'Farmer', 2, 'Updated farm profile', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', 'Desktop', NULL, 'info', '2025-12-06 10:43:23'),
(4, 1, 1, 'viewed', 'Order', 3, 'Viewed order details', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', 'Desktop', NULL, 'info', '2025-12-05 10:43:23'),
(5, 1, 1, 'accepted', 'Order', 4, 'Accepted new order', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', 'Desktop', NULL, 'info', '2025-11-22 10:43:23'),
(6, 1, 1, 'replied', 'Review', 2, 'Replied to customer review', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', 'Desktop', NULL, 'info', '2025-11-12 10:43:23'),
(7, 1, 1, 'updated', 'Product', 2, 'Updated product stock', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', 'Desktop', NULL, 'info', '2025-11-08 10:43:23'),
(8, 1023, 2047, 'created', 'Product', 10, 'Created new product listing', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', 'Desktop', NULL, 'info', '2025-12-05 11:27:00'),
(9, 1023, 2047, 'updated', 'Product', 5, 'Updated product price', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', 'Desktop', NULL, 'info', '2025-11-26 11:27:00'),
(10, 1023, 2047, 'updated', 'Farmer', 1, 'Updated farm profile', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', 'Desktop', NULL, 'info', '2025-11-19 11:27:00'),
(11, 1023, 2047, 'viewed', 'Order', 8, 'Viewed order details', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', 'Desktop', NULL, 'info', '2025-11-11 11:27:00'),
(12, 1023, 2047, 'accepted', 'Order', 3, 'Accepted new order', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', 'Desktop', NULL, 'info', '2025-11-13 11:27:00'),
(13, 1023, 2047, 'replied', 'Review', 4, 'Replied to customer review', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', 'Desktop', NULL, 'info', '2025-11-24 11:27:00'),
(14, 1023, 2047, 'updated', 'Product', 1, 'Updated product stock', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', 'Desktop', NULL, 'info', '2025-12-08 11:27:00');

-- --------------------------------------------------------

--
-- Table structure for table `farmer_earnings`
--

CREATE TABLE `farmer_earnings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `farmer_id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` bigint(20) UNSIGNED NOT NULL,
  `gross_amount` decimal(10,2) NOT NULL,
  `platform_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_gateway_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `delivery_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `net_amount` decimal(10,2) NOT NULL,
  `platform_fee_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `platform_fee_type` varchar(255) NOT NULL DEFAULT 'percentage',
  `status` enum('pending','processing','completed','failed','refunded','disputed') NOT NULL DEFAULT 'pending',
  `payout_status` enum('unpaid','pending','paid','on_hold') NOT NULL DEFAULT 'unpaid',
  `payout_method` varchar(255) DEFAULT NULL,
  `payout_reference` varchar(255) DEFAULT NULL,
  `payout_amount` decimal(10,2) DEFAULT NULL,
  `payout_date` timestamp NULL DEFAULT NULL,
  `processed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `earning_date` date NOT NULL,
  `period` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `farmer_earnings`
--

INSERT INTO `farmer_earnings` (`id`, `farmer_id`, `transaction_id`, `gross_amount`, `platform_fee`, `payment_gateway_fee`, `delivery_fee`, `tax_amount`, `discount_amount`, `net_amount`, `platform_fee_percentage`, `platform_fee_type`, `status`, `payout_status`, `payout_method`, `payout_reference`, `payout_amount`, `payout_date`, `processed_by`, `earning_date`, `period`, `notes`, `metadata`, `created_at`, `updated_at`) VALUES
(1, 1029, 3, 7200.00, 360.00, 0.00, 0.00, 0.00, 0.00, 6840.00, 5.00, 'percentage', 'pending', 'unpaid', NULL, NULL, NULL, NULL, NULL, '2025-12-10', '2025-12', NULL, NULL, '2025-12-09 19:54:06', '2025-12-09 19:54:06');

-- --------------------------------------------------------

--
-- Table structure for table `farmer_reviews`
--

CREATE TABLE `farmer_reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `farmer_id` bigint(20) UNSIGNED NOT NULL,
  `buyer_id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `overall_rating` decimal(3,2) NOT NULL,
  `product_quality_rating` decimal(3,2) DEFAULT NULL,
  `delivery_rating` decimal(3,2) DEFAULT NULL,
  `communication_rating` decimal(3,2) DEFAULT NULL,
  `packaging_rating` decimal(3,2) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `reply` text DEFAULT NULL,
  `replied_at` timestamp NULL DEFAULT NULL,
  `status` enum('pending','approved','rejected','flagged') NOT NULL DEFAULT 'approved',
  `is_verified_purchase` tinyint(1) NOT NULL DEFAULT 1,
  `is_helpful` tinyint(1) NOT NULL DEFAULT 0,
  `helpful_count` int(11) NOT NULL DEFAULT 0,
  `moderated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `moderated_at` timestamp NULL DEFAULT NULL,
  `moderation_notes` text DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `farmer_reviews`
--

INSERT INTO `farmer_reviews` (`id`, `farmer_id`, `buyer_id`, `transaction_id`, `product_id`, `overall_rating`, `product_quality_rating`, `delivery_rating`, `communication_rating`, `packaging_rating`, `comment`, `reply`, `replied_at`, `status`, `is_verified_purchase`, `is_helpful`, `helpful_count`, `moderated_by`, `moderated_at`, `moderation_notes`, `images`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1029, 2, 3, 1, 5.00, 5.00, 5.00, 5.00, 5.00, 'good quality', NULL, NULL, 'approved', 1, 0, 0, NULL, NULL, NULL, NULL, '2025-12-09 19:54:56', '2025-12-09 19:54:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `inventory_logs`
--

CREATE TABLE `inventory_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `farmer_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `size_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` enum('initial_stock','restock','sale','reservation','cancellation','return','damage','expiry','adjustment','transfer') NOT NULL,
  `quantity_before` int(11) NOT NULL,
  `quantity_change` int(11) NOT NULL,
  `quantity_after` int(11) NOT NULL,
  `unit` varchar(255) NOT NULL DEFAULT 'trays',
  `transaction_id` bigint(20) UNSIGNED DEFAULT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `performed_by` bigint(20) UNSIGNED NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `reference_number` varchar(255) DEFAULT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `total_value` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `matches`
--

CREATE TABLE `matches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `demand_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `matched_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` bigint(20) UNSIGNED NOT NULL,
  `conversation_thread_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `receiver_id` bigint(20) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `transaction_id`, `conversation_thread_id`, `sender_id`, `receiver_id`, `message`, `is_read`, `read_at`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 2048, 2055, 'Is this available?\n\nEgg Type: White Eggs\nQuantity: 60 trays\nPrice: ₱7,200.00/trays', 1, NULL, '2025-12-09 19:42:07', '2025-12-09 19:47:14'),
(2, 2, 5, 2, 2055, 'Is this available?\n\nEgg Type: White Eggs\nQuantity: 60 trays\nPrice: ₱7,200.00/trays', 1, NULL, '2025-12-09 19:47:46', '2025-12-09 19:47:55'),
(3, 4, 5, 2, 2055, 'Is this available?\n\nEgg Type: Brown Eggs\nQuantity: 50 trays\nPrice: ₱7,000.00/trays', 0, NULL, '2025-12-09 20:15:33', '2025-12-09 20:15:33'),
(4, 7, 5, 2, 2055, 'Is this available?\n\nEgg Type: Brown Eggs\nQuantity: 22 trays\nPrice: ₱2,706.00/trays', 0, NULL, '2025-12-09 20:22:00', '2025-12-09 20:22:00');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_11_08_013602_remove_contact_location_email_verified_at_name_updated_at_from_users_table', 1),
(5, '2025_11_08_014518_add_f_name_l_namerole_phone_number_address_kyc_status_to_users_table', 1),
(6, '2025_11_09_042514_create_farmers_table', 1),
(7, '2025_11_09_045727_create_buyers_table', 1),
(8, '2025_11_19_020631_add_status_to_users_table', 1),
(9, '2025_11_19_021327_add_status_to_users_table', 1),
(10, '2025_11_19_035249_create_products_table', 1),
(11, '2025_11_19_042844_add_fields_to_products_table', 1),
(12, '2025_11_19_090400_add_timestamps_to_users_table', 1),
(13, '2025_11_19_095115_create_product_images_table', 1),
(14, '2025_11_19_100000_create_demands_table', 1),
(15, '2025_11_19_100100_create_matches_table', 1),
(16, '2025_11_20_140153_add_acceptance_fields_to_matches_table', 1),
(17, '2025_11_20_140543_create_transactions_table', 1),
(18, '2025_11_20_155240_create_notifications_table', 1),
(19, '2025_11_22_045551_update_notifications_table_for_enhanced_data', 1),
(20, '2025_11_22_135557_add_fields_to_transactions_table', 1),
(21, '2025_11_22_135630_create_messages_table', 1),
(22, '2025_11_23_025958_add_initiated_by_to_transactions_table', 1),
(23, '2025_11_23_031340_add_message_initiated_to_transactions_table', 1),
(24, '2025_11_23_031920_add_initiator_id_to_transactions_table', 1),
(25, '2025_11_23_042308_create_conversation_threads_table', 1),
(26, '2025_11_23_042457_add_conversation_thread_id_to_transactions_table', 1),
(27, '2025_11_23_042642_add_conversation_thread_id_to_messages_table', 1),
(28, '2025_11_23_055317_add_conversation_thread_id_to_messages_table_if_not_exists', 1),
(29, '2025_11_23_102233_add_buyer_info_and_payment_method_to_transactions_table', 1),
(30, '2025_11_23_120304_add_order_details_to_transactions_table', 1),
(31, '2025_11_24_030553_fix_demand_foreign_key_on_transactions_table', 1),
(32, '2025_11_25_000000_add_description_to_products_table', 1),
(33, '2025_11_28_102155_add_profile_picture_to_users_table', 1),
(34, '2025_11_28_115159_update_products_default_status', 1),
(35, '2025_11_29_000000_add_egg_fields_to_products_table', 1),
(36, '2025_11_29_000001_add_egg_fields_to_demands_table', 1),
(37, '2025_11_30_000002_add_address_to_products_and_demands_table', 1),
(38, '2025_11_30_000005_drop_product_name_from_products_table', 1),
(39, '2025_11_30_000006_drop_product_name_from_demands_table', 1),
(40, '2025_11_30_000007_create_sizes_table', 1),
(41, '2025_11_30_000008_modify_products_table_for_sizes_relationship', 1),
(42, '2025_11_30_050307_add_unique_constraint_to_conversation_threads_table', 2),
(43, '2025_11_30_100000_add_tray_counts_to_transactions_table', 2),
(44, '2025_11_30_120626_add_size_details_to_transactions_table', 2),
(45, '2025_11_30_150000_create_size_transactions_table', 2),
(46, '2025_12_08_000000_drop_product_type_from_farmers_table', 3),
(47, '2025_12_09_000001_enhance_farmers_table', 4),
(48, '2025_12_09_000002_enhance_products_table', 4),
(49, '2025_12_09_000003_enhance_sizes_table', 4),
(50, '2025_12_09_000004_create_farmer_reviews_table', 4),
(51, '2025_12_09_000005_create_inventory_logs_table', 4),
(52, '2025_12_09_000006_create_product_analytics_table', 4),
(53, '2025_12_09_000007_create_farmer_activity_logs_table', 4),
(54, '2025_12_09_000008_create_farmer_earnings_table', 4),
(56, '2025_12_09_000009_remove_target_price_from_demands_table', 5),
(57, '2025_12_09_235743_fix_add_updated_at_to_users_table', 6);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('106f79bb-3918-4417-bc3e-246d456435df', 'App\\Notifications\\OrderAcceptedNotification', 'App\\Models\\User', 2, '{\"message\":\"You have placed an order for \\\"\\\". The farmer will review your order shortly. Size details: small: 22 trays.\",\"transaction_id\":9,\"data\":{\"message\":\"You have placed an order for \\\"\\\". The farmer will review your order shortly. Size details: small: 22 trays.\",\"transaction_id\":9,\"product_name\":null,\"quantity\":22,\"total_amount\":2706}}', NULL, '2025-12-09 20:25:35', '2025-12-09 20:25:35'),
('126cd2e0-2994-4c76-8eca-783927e9b704', 'App\\Notifications\\OrderAcceptedNotification', 'App\\Models\\User', 2055, '{\"message\":\"A buyer has placed an order for your product \\\"\\\". Please check the order details. Size details: medium: 50 trays.\",\"transaction_id\":5,\"data\":{\"message\":\"A buyer has placed an order for your product \\\"\\\". Please check the order details. Size details: medium: 50 trays.\",\"transaction_id\":5,\"product_name\":null,\"quantity\":50,\"total_amount\":7000}}', NULL, '2025-12-09 20:16:13', '2025-12-09 20:16:13'),
('39934c4d-3f6c-46fc-b409-83be55fffd8c', 'App\\Notifications\\OrderAcceptedNotification', 'App\\Models\\User', 2, '{\"message\":\"You have placed an order for \\\"\\\". The farmer will review your order shortly. Size details: small: 22 trays.\",\"transaction_id\":8,\"data\":{\"message\":\"You have placed an order for \\\"\\\". The farmer will review your order shortly. Size details: small: 22 trays.\",\"transaction_id\":8,\"product_name\":null,\"quantity\":22,\"total_amount\":2706}}', NULL, '2025-12-09 20:22:12', '2025-12-09 20:22:12'),
('643dc957-8bad-4dc4-a730-1419177be657', 'App\\Notifications\\OrderAcceptedNotification', 'App\\Models\\User', 2055, '{\"message\":\"A buyer has placed an order for your product \\\"\\\". Please check the order details. Size details: small: 20 trays, medium: 20 trays, large: 20 trays.\",\"transaction_id\":3,\"data\":{\"message\":\"A buyer has placed an order for your product \\\"\\\". Please check the order details. Size details: small: 20 trays, medium: 20 trays, large: 20 trays.\",\"transaction_id\":3,\"product_name\":null,\"quantity\":60,\"total_amount\":7200}}', NULL, '2025-12-09 19:48:07', '2025-12-09 19:48:07'),
('7ae2a561-b9ee-4d3b-8af5-b299c0a7f0c1', 'App\\Notifications\\OrderAcceptedNotification', 'App\\Models\\User', 2, '{\"message\":\"Your order #8 has been prepared and is ready for pickup or delivery.\",\"transaction_id\":8,\"data\":{\"message\":\"Your order #8 has been prepared and is ready for pickup or delivery.\",\"transaction_id\":8}}', NULL, '2025-12-09 20:25:24', '2025-12-09 20:25:24'),
('7b951e5a-3b57-4ad3-83a6-0620b2ce8007', 'App\\Notifications\\OrderAcceptedNotification', 'App\\Models\\User', 2, '{\"message\":\"You have placed an order for \\\"\\\". The farmer will review your order shortly. Size details: small: 20 trays, medium: 20 trays, large: 20 trays.\",\"transaction_id\":3,\"data\":{\"message\":\"You have placed an order for \\\"\\\". The farmer will review your order shortly. Size details: small: 20 trays, medium: 20 trays, large: 20 trays.\",\"transaction_id\":3,\"product_name\":null,\"quantity\":60,\"total_amount\":7200}}', '2025-12-09 20:00:39', '2025-12-09 19:48:07', '2025-12-09 20:00:39'),
('85736d53-16c3-4a96-ac19-b5bc3abe7696', 'App\\Notifications\\OrderAcceptedNotification', 'App\\Models\\User', 2, '{\"message\":\"Your order #3 is now in transit. You will receive updates on the delivery status.\",\"transaction_id\":3,\"data\":{\"message\":\"Your order #3 is now in transit. You will receive updates on the delivery status.\",\"transaction_id\":3}}', '2025-12-09 19:53:07', '2025-12-09 19:52:21', '2025-12-09 19:53:07'),
('954e19bf-225f-41c2-b078-54cf33961b21', 'App\\Notifications\\OrderAcceptedNotification', 'App\\Models\\User', 2, '{\"message\":\"Your order #3 has been accepted. We\'re preparing your goods for delivery.\",\"transaction_id\":3,\"data\":{\"message\":\"Your order #3 has been accepted. We\'re preparing your goods for delivery.\",\"transaction_id\":3}}', '2025-12-09 20:00:39', '2025-12-09 19:51:39', '2025-12-09 20:00:39'),
('98dbe631-f3b5-474a-a0d3-17d275f2eaef', 'App\\Notifications\\OrderAcceptedNotification', 'App\\Models\\User', 2055, '{\"message\":\"Payment received for order #3. Please prepare the goods for delivery.\",\"transaction_id\":3,\"data\":{\"message\":\"Payment received for order #3. Please prepare the goods for delivery.\",\"transaction_id\":3}}', NULL, '2025-12-09 19:53:42', '2025-12-09 19:53:42'),
('9f8327ae-9a01-4bd5-b0bc-8bcfaaca44fc', 'App\\Notifications\\OrderAcceptedNotification', 'App\\Models\\User', 2055, '{\"message\":\"A buyer has placed an order for your product \\\"\\\". Please check the order details. Size details: small: 22 trays.\",\"transaction_id\":9,\"data\":{\"message\":\"A buyer has placed an order for your product \\\"\\\". Please check the order details. Size details: small: 22 trays.\",\"transaction_id\":9,\"product_name\":null,\"quantity\":22,\"total_amount\":2706}}', NULL, '2025-12-09 20:25:35', '2025-12-09 20:25:35'),
('aa411115-06cb-44b4-9fbe-51f9cc2a9799', 'App\\Notifications\\OrderAcceptedNotification', 'App\\Models\\User', 2055, '{\"message\":\"Order #3 has been marked as delivered by the buyer.\",\"transaction_id\":3,\"data\":{\"message\":\"Order #3 has been marked as delivered by the buyer.\",\"transaction_id\":3}}', NULL, '2025-12-09 19:54:06', '2025-12-09 19:54:06'),
('b4f07a7c-4937-400e-8d4b-f5ca1c3f59c7', 'App\\Notifications\\OrderAcceptedNotification', 'App\\Models\\User', 2, '{\"message\":\"You have placed an order for \\\"\\\". The farmer will review your order shortly. Size details: medium: 50 trays.\",\"transaction_id\":5,\"data\":{\"message\":\"You have placed an order for \\\"\\\". The farmer will review your order shortly. Size details: medium: 50 trays.\",\"transaction_id\":5,\"product_name\":null,\"quantity\":50,\"total_amount\":7000}}', NULL, '2025-12-09 20:16:13', '2025-12-09 20:16:13'),
('c0a9f588-33ab-43df-b917-821f82989fe0', 'App\\Notifications\\OrderAcceptedNotification', 'App\\Models\\User', 2, '{\"message\":\"Your order #3 has been prepared and is ready for pickup or delivery.\",\"transaction_id\":3,\"data\":{\"message\":\"Your order #3 has been prepared and is ready for pickup or delivery.\",\"transaction_id\":3}}', '2025-12-09 19:53:16', '2025-12-09 19:51:55', '2025-12-09 19:53:16'),
('dad3fa67-f2a7-4de8-9d36-16744b9c3566', 'App\\Notifications\\OrderAcceptedNotification', 'App\\Models\\User', 2, '{\"message\":\"Your order #5 has been rejected. Please contact the farmer for more information.\",\"transaction_id\":5,\"data\":{\"message\":\"Your order #5 has been rejected. Please contact the farmer for more information.\",\"transaction_id\":5}}', NULL, '2025-12-09 20:21:01', '2025-12-09 20:21:01'),
('e8f01692-12c5-4412-aea3-fceb53d246e1', 'App\\Notifications\\OrderAcceptedNotification', 'App\\Models\\User', 2055, '{\"message\":\"A buyer has placed an order for your product \\\"\\\". Please check the order details. Size details: small: 22 trays.\",\"transaction_id\":8,\"data\":{\"message\":\"A buyer has placed an order for your product \\\"\\\". Please check the order details. Size details: small: 22 trays.\",\"transaction_id\":8,\"product_name\":null,\"quantity\":22,\"total_amount\":2706}}', NULL, '2025-12-09 20:22:12', '2025-12-09 20:22:12'),
('f898eb94-75be-4d8f-9f68-84c2a9e4cbef', 'App\\Notifications\\OrderAcceptedNotification', 'App\\Models\\User', 2, '{\"message\":\"Your order #9 has been accepted. We\'re preparing your goods for delivery.\",\"transaction_id\":9,\"data\":{\"message\":\"Your order #9 has been accepted. We\'re preparing your goods for delivery.\",\"transaction_id\":9}}', NULL, '2025-12-09 20:26:48', '2025-12-09 20:26:48');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `farmer_id` bigint(20) UNSIGNED NOT NULL,
  `description` text DEFAULT NULL,
  `egg_type` varchar(255) DEFAULT NULL,
  `quality_grade` enum('AA','A','B','C') NOT NULL DEFAULT 'A',
  `color` varchar(255) DEFAULT NULL,
  `average_weight_grams` decimal(5,2) DEFAULT NULL,
  `is_organic` tinyint(1) NOT NULL DEFAULT 0,
  `is_free_range` tinyint(1) NOT NULL DEFAULT 0,
  `hen_breed` varchar(255) DEFAULT NULL,
  `jumbo` tinyint(1) NOT NULL DEFAULT 0,
  `quantity` int(11) NOT NULL,
  `initial_quantity` int(11) DEFAULT NULL,
  `sold_quantity` int(11) NOT NULL DEFAULT 0,
  `reserved_quantity` int(11) NOT NULL DEFAULT 0,
  `available_quantity` int(11) DEFAULT NULL,
  `minimum_order_quantity` int(11) NOT NULL DEFAULT 1,
  `maximum_order_quantity` int(11) DEFAULT NULL,
  `reorder_level` int(11) DEFAULT NULL,
  `low_stock_alert` tinyint(1) NOT NULL DEFAULT 0,
  `unit` varchar(255) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `original_price` decimal(10,2) DEFAULT NULL,
  `discount_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `is_negotiable` tinyint(1) NOT NULL DEFAULT 0,
  `minimum_acceptable_price` decimal(10,2) DEFAULT NULL,
  `harvest_date` date NOT NULL,
  `laying_date` date DEFAULT NULL,
  `shelf_life_days` int(11) NOT NULL DEFAULT 30,
  `expiry_date` date DEFAULT NULL,
  `storage_condition` varchar(255) NOT NULL DEFAULT 'Refrigerated',
  `storage_temperature` decimal(4,1) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `barangay` varchar(255) DEFAULT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `delivery_radius_km` decimal(6,2) DEFAULT NULL,
  `offers_delivery` tinyint(1) NOT NULL DEFAULT 0,
  `offers_pickup` tinyint(1) NOT NULL DEFAULT 1,
  `delivery_fee` decimal(8,2) DEFAULT NULL,
  `fda_approved` tinyint(1) NOT NULL DEFAULT 0,
  `batch_number` varchar(255) DEFAULT NULL,
  `certification_documents` varchar(255) DEFAULT NULL,
  `view_count` int(11) NOT NULL DEFAULT 0,
  `inquiry_count` int(11) NOT NULL DEFAULT 0,
  `order_count` int(11) NOT NULL DEFAULT 0,
  `conversion_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_promoted` tinyint(1) NOT NULL DEFAULT 0,
  `featured_until` timestamp NULL DEFAULT NULL,
  `priority_order` int(11) NOT NULL DEFAULT 0,
  `last_restocked_at` timestamp NULL DEFAULT NULL,
  `last_sold_at` timestamp NULL DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Available',
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `created_at`, `updated_at`, `deleted_at`, `farmer_id`, `description`, `egg_type`, `quality_grade`, `color`, `average_weight_grams`, `is_organic`, `is_free_range`, `hen_breed`, `jumbo`, `quantity`, `initial_quantity`, `sold_quantity`, `reserved_quantity`, `available_quantity`, `minimum_order_quantity`, `maximum_order_quantity`, `reorder_level`, `low_stock_alert`, `unit`, `price`, `original_price`, `discount_percentage`, `is_negotiable`, `minimum_acceptable_price`, `harvest_date`, `laying_date`, `shelf_life_days`, `expiry_date`, `storage_condition`, `storage_temperature`, `address`, `province`, `city`, `barangay`, `postal_code`, `delivery_radius_km`, `offers_delivery`, `offers_pickup`, `delivery_fee`, `fda_approved`, `batch_number`, `certification_documents`, `view_count`, `inquiry_count`, `order_count`, `conversion_rate`, `is_featured`, `is_promoted`, `featured_until`, `priority_order`, `last_restocked_at`, `last_sold_at`, `published_at`, `status`, `image`) VALUES
(1, '2025-12-09 19:32:16', '2025-12-09 20:44:11', '2025-12-09 20:44:11', 1029, 'healthy', 'white', 'A', NULL, NULL, 0, 0, NULL, 0, 0, NULL, 0, 0, NULL, 1, NULL, NULL, 0, 'trays', 0.00, NULL, 0.00, 0, NULL, '2025-12-10', NULL, 30, NULL, 'Refrigerated', NULL, 'tapok ADN', NULL, NULL, NULL, NULL, NULL, 0, 1, NULL, 0, NULL, NULL, 0, 0, 1, 0.00, 0, 0, NULL, 0, NULL, '2025-12-09 19:54:06', NULL, 'Sold Out', 'products/dT1UngG0ZckrPpesDcuCH4cMTvp8bS4zUyKjjuHJ.jpg'),
(2, '2025-12-09 20:05:20', '2025-12-09 20:16:13', NULL, 1029, NULL, 'brown', 'A', NULL, NULL, 0, 0, NULL, 0, 0, NULL, 0, 0, NULL, 1, NULL, NULL, 0, 'trays', 0.00, NULL, 0.00, 0, NULL, '2025-12-10', NULL, 30, NULL, 'Refrigerated', NULL, 'Cabadbaran City', NULL, NULL, NULL, NULL, NULL, 0, 1, NULL, 0, NULL, NULL, 0, 0, 0, 0.00, 0, 0, NULL, 0, NULL, NULL, NULL, 'Sold Out', 'products/yLgdT3AhQapR7QlRKkSDc6V73PoHw9u565uCcSSg.jpg'),
(3, '2025-12-09 20:21:53', '2025-12-09 20:26:48', NULL, 1029, NULL, 'brown', 'A', NULL, NULL, 0, 0, NULL, 0, 0, NULL, 0, 0, NULL, 1, NULL, NULL, 0, 'trays', 2706.00, NULL, 0.00, 0, NULL, '2025-12-10', NULL, 30, NULL, 'Refrigerated', NULL, 'Cabadbaran City', NULL, NULL, NULL, NULL, NULL, 0, 1, NULL, 0, NULL, NULL, 0, 0, 0, 0.00, 0, 0, NULL, 0, NULL, NULL, NULL, 'Sold Out', 'products/pAYk4C4ZbAXECp9zJM2MmvXvr4GrYKd1nERns9OV.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `product_analytics`
--

CREATE TABLE `product_analytics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `farmer_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `views` int(11) NOT NULL DEFAULT 0,
  `unique_views` int(11) NOT NULL DEFAULT 0,
  `detail_views` int(11) NOT NULL DEFAULT 0,
  `inquiries` int(11) NOT NULL DEFAULT 0,
  `messages_received` int(11) NOT NULL DEFAULT 0,
  `favorites` int(11) NOT NULL DEFAULT 0,
  `shares` int(11) NOT NULL DEFAULT 0,
  `orders_placed` int(11) NOT NULL DEFAULT 0,
  `orders_completed` int(11) NOT NULL DEFAULT 0,
  `orders_cancelled` int(11) NOT NULL DEFAULT 0,
  `revenue` decimal(10,2) NOT NULL DEFAULT 0.00,
  `units_sold` int(11) NOT NULL DEFAULT 0,
  `conversion_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `avg_order_value` decimal(10,2) NOT NULL DEFAULT 0.00,
  `avg_response_time_minutes` int(11) NOT NULL DEFAULT 0,
  `matches_generated` int(11) NOT NULL DEFAULT 0,
  `matches_accepted` int(11) NOT NULL DEFAULT 0,
  `match_conversion_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `avg_selling_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `price_at_date` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_analytics`
--

INSERT INTO `product_analytics` (`id`, `farmer_id`, `product_id`, `date`, `views`, `unique_views`, `detail_views`, `inquiries`, `messages_received`, `favorites`, `shares`, `orders_placed`, `orders_completed`, `orders_cancelled`, `revenue`, `units_sold`, `conversion_rate`, `avg_order_value`, `avg_response_time_minutes`, `matches_generated`, `matches_accepted`, `match_conversion_rate`, `avg_selling_price`, `price_at_date`, `created_at`, `updated_at`) VALUES
(1, 1029, 1, '2025-12-10', 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 7200.00, 60, 0.00, 7200.00, 0, 0, 0, 0.00, 0.00, 0.00, '2025-12-09 19:54:06', '2025-12-09 19:54:06');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `is_primary`, `created_at`, `updated_at`) VALUES
(1, 1, 'products/dT1UngG0ZckrPpesDcuCH4cMTvp8bS4zUyKjjuHJ.jpg', 1, '2025-12-09 19:32:16', '2025-12-09 19:32:16'),
(2, 1, 'products/lwLaMLl8LI4iany1fftIoIOiP16knMjUgvys4Rkp.jpg', 0, '2025-12-09 19:32:16', '2025-12-09 19:32:16'),
(3, 1, 'products/MWFW88d8nZUSQXc6VxbPexJL4zgeQMXaHpvKshBr.jpg', 0, '2025-12-09 19:32:16', '2025-12-09 19:32:16'),
(4, 1, 'products/K7flB4eAyRSaJCWK0mGOyGCLv8gPuCYnfVm2hB8R.jpg', 0, '2025-12-09 19:32:16', '2025-12-09 19:32:16'),
(5, 1, 'products/KjC2Xm5CdNOANUcnNOv2ZQqrEnC606FuERXcSpYP.jpg', 0, '2025-12-09 19:32:16', '2025-12-09 19:32:16'),
(6, 2, 'products/yLgdT3AhQapR7QlRKkSDc6V73PoHw9u565uCcSSg.jpg', 1, '2025-12-09 20:05:20', '2025-12-09 20:05:20'),
(7, 2, 'products/8V9EPLGF4F9zdfjG3micANtOweWFfMHDsB1m6rfj.jpg', 0, '2025-12-09 20:05:20', '2025-12-09 20:05:20'),
(8, 2, 'products/31DaE6DoIYaz5JLSswXMmi2AM5kFJVrPlzjHxH6C.jpg', 0, '2025-12-09 20:05:20', '2025-12-09 20:05:20'),
(9, 3, 'products/pAYk4C4ZbAXECp9zJM2MmvXvr4GrYKd1nERns9OV.jpg', 1, '2025-12-09 20:21:53', '2025-12-09 20:21:53'),
(10, 3, 'products/G4cq9XLWDGQFtuFVpklBoVONhV8voMcPcqsLoTmo.jpg', 0, '2025-12-09 20:21:53', '2025-12-09 20:21:53'),
(11, 3, 'products/HT9VwUqnY5vJh3ynDqyq1VeDvPLFCKCkCTIf3rPb.jpg', 0, '2025-12-09 20:21:53', '2025-12-09 20:21:53'),
(12, 3, 'products/7WLTzHHvRg91NbJjnL9F6a7Lsk17zfeAb9zUhb4c.jpg', 0, '2025-12-09 20:21:53', '2025-12-09 20:21:53'),
(13, 3, 'products/mpfIRvKcI9fWXJ2jqha6sPfX0b2sVuM6YuuWfDpQ.jpg', 0, '2025-12-09 20:21:53', '2025-12-09 20:21:53'),
(14, 3, 'products/vBCOvoYXVrgFsnWDbEGZlEvCTveXQvmJ2eyIhwLX.jpg', 0, '2025-12-09 20:21:53', '2025-12-09 20:21:53');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('3lYIoiyJH98gsdAH3jQ5dBh8HWtmZHt72nAaWuCC', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQ2lLMzdzZW1ibk53ems2bUZyN0J1TE95SkdBa0ZuS0pFejM0OW9zWSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9idXllciI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==', 1765340840),
('bDifSjFBETQXTBsZk2RL7bqcwgUBFedU2iIv5sYD', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoianl5S29mRGVRcno3NVliSzJXQTRYRThVNmdNZTMyTjJQRG1GUVhmNCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi90cmFuc2FjdGlvbnMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUyOiJsb2dpbl9hZG1pbl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjIwNDg7czo1MzoibG9naW5fZmFybWVyXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjA1NTtzOjUyOiJsb2dpbl9idXllcl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo1MzoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2J1eWVyL21lc3NhZ2VzP3RyYW5zYWN0aW9uX2lkPTEiO319', 1765342000),
('DSwsmYK3tU5XuzaRzEsoybXwLWa87Renarw0avxh', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiRjZpb3pBdEdnVGMwNTZaSTVXckdKZnhyaUpRWW1vemtHd2oyM3J4aCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9idXllciI7fXM6NTI6ImxvZ2luX2FkbWluXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjA0ODtzOjUzOiJsb2dpbl9mYXJtZXJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyMDU1O3M6NTI6ImxvZ2luX2J1eWVyXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9', 1765337606),
('k8P1UFaL4jwpoCtxxaw2lSvnciK0DNFkbS2Et0dD', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicWRZRkpyWE9saE1QVzNGTVNjVlpsU2hkRVlXT0l1cktuWm83TkpmNiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1765338184),
('thJ4JvdHbugWxjrHKfXllJ8c9rLUe2rwmWpMI8Ny', 2055, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoia0JZVE9XZzZ6SXU1dXJqc01qYUkyaEk5RDlhNzNkVVR1UDljem9QTSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9mYXJtZXIvZWFybmluZ3MiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyMDU1O30=', 1765341961),
('WWbNACCvajiqcFDbTtRCFvAzcR1zR3SEiKF2MO1m', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicWpzbzNuSWVwTVpNZXppc2FuNG1VWTQ1aWdJRDd4NUtqT25sUlVrNiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNToiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2Zhcm1lci9vcmRlcnMiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyNzoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1765338001);

-- --------------------------------------------------------

--
-- Table structure for table `sizes`
--

CREATE TABLE `sizes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `farmer_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `egg_type` varchar(255) NOT NULL,
  `size_name` varchar(255) NOT NULL,
  `eggs_per_tray` int(11) NOT NULL DEFAULT 30,
  `weight_per_egg_grams` decimal(6,2) DEFAULT NULL,
  `total_weight_kg` decimal(8,2) DEFAULT NULL,
  `tray_count` int(11) NOT NULL,
  `initial_tray_count` int(11) DEFAULT NULL,
  `sold_tray_count` int(11) NOT NULL DEFAULT 0,
  `reserved_tray_count` int(11) NOT NULL DEFAULT 0,
  `available_tray_count` int(11) DEFAULT NULL,
  `price_per_tray` decimal(8,2) NOT NULL,
  `original_price_per_tray` decimal(10,2) DEFAULT NULL,
  `discount_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `has_special_offer` tinyint(1) NOT NULL DEFAULT 0,
  `special_offer_until` timestamp NULL DEFAULT NULL,
  `availability_status` enum('available','low_stock','out_of_stock','discontinued') NOT NULL DEFAULT 'available',
  `low_stock_threshold` int(11) NOT NULL DEFAULT 5,
  `allow_backorder` tinyint(1) NOT NULL DEFAULT 0,
  `order_count` int(11) NOT NULL DEFAULT 0,
  `popularity_score` decimal(5,2) NOT NULL DEFAULT 0.00,
  `last_sold_at` timestamp NULL DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sizes`
--

INSERT INTO `sizes` (`id`, `farmer_id`, `product_id`, `egg_type`, `size_name`, `eggs_per_tray`, `weight_per_egg_grams`, `total_weight_kg`, `tray_count`, `initial_tray_count`, `sold_tray_count`, `reserved_tray_count`, `available_tray_count`, `price_per_tray`, `original_price_per_tray`, `discount_percentage`, `has_special_offer`, `special_offer_until`, `availability_status`, `low_stock_threshold`, `allow_backorder`, `order_count`, `popularity_score`, `last_sold_at`, `total_price`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1029, 1, 'white', 'small', 30, NULL, NULL, 0, NULL, 0, 0, NULL, 110.00, NULL, 0.00, 0, NULL, 'available', 5, 0, 0, 0.00, NULL, 0.00, '2025-12-09 19:32:16', '2025-12-09 19:48:07', NULL),
(2, 1029, 1, 'white', 'medium', 30, NULL, NULL, 0, NULL, 0, 0, NULL, 120.00, NULL, 0.00, 0, NULL, 'available', 5, 0, 0, 0.00, NULL, 0.00, '2025-12-09 19:32:16', '2025-12-09 19:48:07', NULL),
(3, 1029, 1, 'white', 'large', 30, NULL, NULL, 0, NULL, 0, 0, NULL, 130.00, NULL, 0.00, 0, NULL, 'available', 5, 0, 0, 0.00, NULL, 0.00, '2025-12-09 19:32:16', '2025-12-09 19:48:07', NULL),
(4, 1029, 2, 'brown', 'medium', 30, NULL, NULL, 0, NULL, 0, 0, NULL, 140.00, NULL, 0.00, 0, NULL, 'available', 5, 0, 0, 0.00, NULL, 0.00, '2025-12-09 20:05:20', '2025-12-09 20:16:13', NULL),
(5, 1029, 3, 'brown', 'small', 30, NULL, NULL, 0, NULL, 0, 0, NULL, 123.00, NULL, 0.00, 0, NULL, 'out_of_stock', 5, 0, 0, 0.00, NULL, 0.00, '2025-12-09 20:21:53', '2025-12-09 20:26:48', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `size_transactions`
--

CREATE TABLE `size_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` bigint(20) UNSIGNED NOT NULL,
  `size_id` bigint(20) UNSIGNED NOT NULL,
  `size_name` varchar(255) NOT NULL,
  `tray_count` int(11) NOT NULL,
  `price_per_tray` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `size_transactions`
--

INSERT INTO `size_transactions` (`id`, `transaction_id`, `size_id`, `size_name`, `tray_count`, `price_per_tray`, `total_price`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 'small', 20, 110.00, 2200.00, '2025-12-09 19:48:07', '2025-12-09 19:48:07'),
(2, 3, 2, 'medium', 20, 120.00, 2400.00, '2025-12-09 19:48:07', '2025-12-09 19:48:07'),
(3, 3, 3, 'large', 20, 130.00, 2600.00, '2025-12-09 19:48:07', '2025-12-09 19:48:07'),
(4, 5, 4, 'medium', 50, 140.00, 7000.00, '2025-12-09 20:16:13', '2025-12-09 20:16:13'),
(6, 8, 5, 'small', 22, 123.00, 2706.00, '2025-12-09 20:22:12', '2025-12-09 20:22:12'),
(7, 9, 5, 'small', 22, 123.00, 2706.00, '2025-12-09 20:25:35', '2025-12-09 20:25:35');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `buyer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `farmer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `demand_id` bigint(20) UNSIGNED DEFAULT NULL,
  `final_quantity` int(11) DEFAULT NULL,
  `final_price` decimal(10,2) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `tray_counts` text DEFAULT NULL,
  `payment_status` varchar(255) NOT NULL DEFAULT 'Pending',
  `delivery_status` varchar(255) NOT NULL DEFAULT 'Scheduled',
  `negotiation_messages` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Active',
  `initiator_id` bigint(20) UNSIGNED DEFAULT NULL,
  `conversation_thread_id` bigint(20) UNSIGNED DEFAULT NULL,
  `buyer_name` varchar(255) DEFAULT NULL,
  `buyer_email` varchar(255) DEFAULT NULL,
  `buyer_phone` varchar(255) DEFAULT NULL,
  `buyer_address` text DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `size_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`size_details`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `created_at`, `updated_at`, `buyer_id`, `farmer_id`, `product_id`, `demand_id`, `final_quantity`, `final_price`, `total_amount`, `tray_counts`, `payment_status`, `delivery_status`, `negotiation_messages`, `status`, `initiator_id`, `conversation_thread_id`, `buyer_name`, `buyer_email`, `buyer_phone`, `buyer_address`, `payment_method`, `size_details`) VALUES
(1, '2025-12-09 19:42:07', '2025-12-09 19:42:07', 2048, 2055, 1, NULL, 60, 7200.00, 432000.00, NULL, 'Pending', 'Scheduled', NULL, 'Active', 2048, 4, NULL, NULL, NULL, NULL, NULL, NULL),
(2, '2025-12-09 19:47:46', '2025-12-09 19:47:46', 2, 2055, 1, NULL, 60, 7200.00, 432000.00, NULL, 'Pending', 'Scheduled', NULL, 'Active', 2, 5, NULL, NULL, NULL, NULL, NULL, NULL),
(3, '2025-12-09 19:48:07', '2025-12-09 19:54:06', 2, 2055, 1, NULL, 60, 120.00, 7200.00, '\"{\\\"1\\\":\\\"20\\\",\\\"2\\\":\\\"20\\\",\\\"3\\\":\\\"20\\\"}\"', 'Paid', 'Delivered', NULL, 'Delivered', NULL, 5, 'buyer 1', 'buyer1@gmail.com', '09302178963', 'tapok ADN', 'cash_on_delivery', '\"[{\\\"size_id\\\":1,\\\"size_name\\\":\\\"small\\\",\\\"tray_count\\\":\\\"20\\\",\\\"price_per_tray\\\":\\\"110.00\\\",\\\"total_price\\\":2200},{\\\"size_id\\\":2,\\\"size_name\\\":\\\"medium\\\",\\\"tray_count\\\":\\\"20\\\",\\\"price_per_tray\\\":\\\"120.00\\\",\\\"total_price\\\":2400},{\\\"size_id\\\":3,\\\"size_name\\\":\\\"large\\\",\\\"tray_count\\\":\\\"20\\\",\\\"price_per_tray\\\":\\\"130.00\\\",\\\"total_price\\\":2600}]\"'),
(4, '2025-12-09 20:15:33', '2025-12-09 20:15:33', 2, 2055, 2, NULL, 50, 7000.00, 350000.00, NULL, 'Pending', 'Scheduled', NULL, 'Active', 2, 5, NULL, NULL, NULL, NULL, NULL, NULL),
(5, '2025-12-09 20:16:13', '2025-12-09 20:21:01', 2, 2055, 2, NULL, 50, 140.00, 7000.00, '\"{\\\"4\\\":\\\"50\\\"}\"', 'Pending', 'Scheduled', NULL, 'Rejected', NULL, 5, 'buyer 1', 'buyer1@gmail.com', '09302178963', 'tapok ADN', 'cash_on_delivery', '\"[{\\\"size_id\\\":4,\\\"size_name\\\":\\\"medium\\\",\\\"tray_count\\\":\\\"50\\\",\\\"price_per_tray\\\":\\\"140.00\\\",\\\"total_price\\\":7000}]\"'),
(7, '2025-12-09 20:22:00', '2025-12-09 20:22:00', 2, 2055, 3, NULL, 22, 2706.00, 59532.00, NULL, 'Pending', 'Scheduled', NULL, 'Active', 2, 5, NULL, NULL, NULL, NULL, NULL, NULL),
(8, '2025-12-09 20:22:12', '2025-12-09 20:25:24', 2, 2055, 3, NULL, 22, 123.00, 2706.00, '\"{\\\"5\\\":\\\"22\\\"}\"', 'Pending', 'Prepared', NULL, 'Prepared', NULL, 5, 'buyer 1', 'buyer1@gmail.com', '09302178963', 'tapok ADN', 'cash_on_delivery', '\"[{\\\"size_id\\\":5,\\\"size_name\\\":\\\"small\\\",\\\"tray_count\\\":\\\"22\\\",\\\"price_per_tray\\\":\\\"123.00\\\",\\\"total_price\\\":2706}]\"'),
(9, '2025-12-09 20:25:35', '2025-12-09 20:26:48', 2, 2055, 3, NULL, 22, 123.00, 2706.00, '\"{\\\"5\\\":\\\"22\\\"}\"', 'Pending', 'Scheduled', NULL, 'Accepted', NULL, 5, 'buyer 1', 'buyer1@gmail.com', '09302178963', 'tapok ADN', 'cash_on_delivery', '\"[{\\\"size_id\\\":5,\\\"size_name\\\":\\\"small\\\",\\\"tray_count\\\":\\\"22\\\",\\\"price_per_tray\\\":\\\"123.00\\\",\\\"total_price\\\":2706}]\"');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','farmer','buyer') NOT NULL DEFAULT 'buyer',
  `phone_number` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `kyc_status` enum('pending','verified','rejected') NOT NULL DEFAULT 'pending',
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `role`, `phone_number`, `address`, `kyc_status`, `profile_picture`, `created_at`, `updated_at`) VALUES
(1, 'farmer', '1', 'farmer1@gmail.com', '$2y$12$l55NDAHreBO3SxPm79G7uu8RY76sG5Luit/gOdgqE83IllGl.Aywi', 'farmer', '09632746936', 'Purok 1C', 'pending', NULL, '2025-11-30 19:23:13', '2025-11-30 19:23:13'),
(2, 'buyer', '1', 'buyer1@gmail.com', '$2y$12$ZnVjFxBc.6PO6SNvwmvutuxVCXryJzDbQKmsyuPS7rmPoPL555HE6', 'buyer', '09632746936', 'Purok 1C', 'verified', 'profile_pictures/MZ06xrHNKVuxU4dbAUGhbiqxMbIVXfIFPjZHkKbp.png', '2025-11-30 19:23:40', '2025-12-09 19:30:36'),
(3, 'buyer', '2', 'buyer2@gmail.com', '$2y$12$hiUZM94kmcmxH.CeVuVQvetFLPE9XpepvO/4VDuoJ4hWipggEmFDu', 'buyer', '09632746936', 'Purok 1C', 'pending', NULL, '2025-11-30 21:14:08', '2025-11-30 21:14:08'),
(2047, 'Dodo', 'Acido', 'jstincid01@gmail.com', '$2y$12$pX/zop1UUSFPXTPZPBpaQ.rYJHQYLeB4BAz/Brg0UPffJhf.SiWd2', 'farmer', '09302178963', 'tapok ADN', 'pending', 'profile_pictures/oMc9xZfoE8untWCM5zryPS0nBdrpCWSSolQllrYp.jpg', '2025-12-08 03:04:13', '2025-12-08 03:10:24'),
(2048, 'Admin', 'User', 'admin@agriconnect.com', '$2y$12$KKXjgkj2JQcuBPs24mv.P.L4fCpwCIMzMWchR.iXEg5Vb3791azRu', 'admin', NULL, NULL, 'pending', NULL, '2025-12-09 15:42:48', '2025-12-09 15:42:48'),
(2049, 'Justin', 'acido', 'jstin@csucc.edu.ph', '$2y$12$SHoMSq14KWMxkvj8z4dcuemeSl92JvV4SqHxKwSMt/qCXlbHdrBB2', 'farmer', '09123434453', 'Cabadbaran City', 'pending', NULL, '2025-12-09 15:51:09', '2025-12-09 15:51:09'),
(2050, 'Justin', 'acido', 'jstin@gmail.com', '$2y$12$OpwBbaf4m0hITqtQu7wCreJgUiqiujTNA5uwSoCPOzI8Hlzx5MxkC', 'farmer', '09123434453', 'Cabadbaran City', 'pending', NULL, '2025-12-09 15:51:27', '2025-12-09 15:51:27'),
(2051, 'Justin', 'acido', 'jstin01@gmail.com', '$2y$12$9uw0MO.sv7DI/x4UCmkCZ.gISeRHoErbNKpoI.wY8tL/QQts.2jUu', 'farmer', '09123434453', 'Cabadbaran City', 'pending', NULL, '2025-12-09 15:51:44', '2025-12-09 15:51:44'),
(2052, 'Justin', 'acido', 'justin@gmail.com', '$2y$12$LEU090Ij4./gBeimCiVupOlxTbiuPtHcm3.DDUqJ666LJ3tbVf2sO', 'farmer', '09123434453', 'Cabadbaran City', 'pending', NULL, '2025-12-09 15:51:58', '2025-12-09 15:51:58'),
(2053, 'Cris', 'Acido', 'jstin2003@csucc.edu.ph', '$2y$12$YrnL.6fqwH5MRlNWwyYlrOZaFtL9gWQwfADOWuebpqXOfFBaQqh0C', 'farmer', '09123434453', 'Cabadbaran City', 'pending', NULL, '2025-12-09 15:54:27', '2025-12-09 15:54:27'),
(2055, 'Justin', 'Acido', 'jstin011@gmail.com', '$2y$12$yn98H.J0LUACbvQMhej62uBMXjM0wReER0i0zRZ9ztk8xeGRCQ8uu', 'farmer', '09123434453', 'Cabadbaran City', 'verified', NULL, '2025-12-09 16:00:47', '2025-12-09 16:13:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buyers`
--
ALTER TABLE `buyers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `buyers_user_id_foreign` (`user_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `conversation_threads`
--
ALTER TABLE `conversation_threads`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `conversation_threads_buyer_id_farmer_id_unique` (`buyer_id`,`farmer_id`),
  ADD KEY `conversation_threads_farmer_id_foreign` (`farmer_id`);

--
-- Indexes for table `demands`
--
ALTER TABLE `demands`
  ADD PRIMARY KEY (`id`),
  ADD KEY `demands_buyer_id_foreign` (`buyer_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `farmers`
--
ALTER TABLE `farmers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `farmers_user_id_foreign` (`user_id`),
  ADD KEY `farmers_verification_status_index` (`verification_status`),
  ADD KEY `farmers_is_active_index` (`is_active`),
  ADD KEY `farmers_average_rating_index` (`average_rating`),
  ADD KEY `farmers_latitude_longitude_index` (`latitude`,`longitude`);

--
-- Indexes for table `farmer_activity_logs`
--
ALTER TABLE `farmer_activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `farmer_activity_logs_user_id_foreign` (`user_id`),
  ADD KEY `farmer_activity_logs_farmer_id_index` (`farmer_id`),
  ADD KEY `farmer_activity_logs_action_index` (`action`),
  ADD KEY `farmer_activity_logs_entity_type_index` (`entity_type`),
  ADD KEY `farmer_activity_logs_created_at_index` (`created_at`),
  ADD KEY `farmer_activity_logs_farmer_id_created_at_index` (`farmer_id`,`created_at`),
  ADD KEY `farmer_activity_logs_farmer_id_action_index` (`farmer_id`,`action`);

--
-- Indexes for table `farmer_earnings`
--
ALTER TABLE `farmer_earnings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `farmer_earnings_transaction_id_foreign` (`transaction_id`),
  ADD KEY `farmer_earnings_processed_by_foreign` (`processed_by`),
  ADD KEY `farmer_earnings_farmer_id_index` (`farmer_id`),
  ADD KEY `farmer_earnings_status_index` (`status`),
  ADD KEY `farmer_earnings_payout_status_index` (`payout_status`),
  ADD KEY `farmer_earnings_earning_date_index` (`earning_date`),
  ADD KEY `farmer_earnings_period_index` (`period`),
  ADD KEY `farmer_earnings_farmer_id_payout_status_index` (`farmer_id`,`payout_status`),
  ADD KEY `farmer_earnings_farmer_id_earning_date_index` (`farmer_id`,`earning_date`);

--
-- Indexes for table `farmer_reviews`
--
ALTER TABLE `farmer_reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `farmer_reviews_buyer_id_transaction_id_unique` (`buyer_id`,`transaction_id`),
  ADD KEY `farmer_reviews_transaction_id_foreign` (`transaction_id`),
  ADD KEY `farmer_reviews_product_id_foreign` (`product_id`),
  ADD KEY `farmer_reviews_farmer_id_index` (`farmer_id`),
  ADD KEY `farmer_reviews_overall_rating_index` (`overall_rating`),
  ADD KEY `farmer_reviews_status_index` (`status`),
  ADD KEY `farmer_reviews_farmer_id_overall_rating_index` (`farmer_id`,`overall_rating`);

--
-- Indexes for table `inventory_logs`
--
ALTER TABLE `inventory_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_logs_size_id_foreign` (`size_id`),
  ADD KEY `inventory_logs_transaction_id_foreign` (`transaction_id`),
  ADD KEY `inventory_logs_performed_by_foreign` (`performed_by`),
  ADD KEY `inventory_logs_farmer_id_index` (`farmer_id`),
  ADD KEY `inventory_logs_product_id_index` (`product_id`),
  ADD KEY `inventory_logs_type_index` (`type`),
  ADD KEY `inventory_logs_created_at_index` (`created_at`),
  ADD KEY `inventory_logs_farmer_id_product_id_created_at_index` (`farmer_id`,`product_id`,`created_at`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `matches`
--
ALTER TABLE `matches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `matches_product_id_foreign` (`product_id`),
  ADD KEY `matches_demand_id_foreign` (`demand_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_transaction_id_foreign` (`transaction_id`),
  ADD KEY `messages_sender_id_foreign` (`sender_id`),
  ADD KEY `messages_receiver_id_foreign` (`receiver_id`),
  ADD KEY `messages_conversation_thread_id_foreign` (`conversation_thread_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_farmer_id_foreign` (`farmer_id`),
  ADD KEY `products_status_index` (`status`),
  ADD KEY `products_egg_type_index` (`egg_type`),
  ADD KEY `products_quality_grade_index` (`quality_grade`),
  ADD KEY `products_is_organic_index` (`is_organic`),
  ADD KEY `products_is_featured_index` (`is_featured`),
  ADD KEY `products_published_at_index` (`published_at`),
  ADD KEY `products_farmer_id_status_index` (`farmer_id`,`status`),
  ADD KEY `products_province_city_index` (`province`,`city`);

--
-- Indexes for table `product_analytics`
--
ALTER TABLE `product_analytics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_analytics_product_id_date_unique` (`product_id`,`date`),
  ADD KEY `product_analytics_date_index` (`date`),
  ADD KEY `product_analytics_farmer_id_date_index` (`farmer_id`,`date`),
  ADD KEY `product_analytics_product_id_date_index` (`product_id`,`date`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_product_id_foreign` (`product_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `sizes`
--
ALTER TABLE `sizes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sizes_farmer_id_foreign` (`farmer_id`),
  ADD KEY `sizes_product_id_foreign` (`product_id`),
  ADD KEY `sizes_availability_status_index` (`availability_status`),
  ADD KEY `sizes_product_id_size_name_index` (`product_id`,`size_name`),
  ADD KEY `sizes_farmer_id_availability_status_index` (`farmer_id`,`availability_status`);

--
-- Indexes for table `size_transactions`
--
ALTER TABLE `size_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `size_transactions_transaction_id_foreign` (`transaction_id`),
  ADD KEY `size_transactions_size_id_foreign` (`size_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_initiator_id_foreign` (`initiator_id`),
  ADD KEY `transactions_conversation_thread_id_foreign` (`conversation_thread_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buyers`
--
ALTER TABLE `buyers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `conversation_threads`
--
ALTER TABLE `conversation_threads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `demands`
--
ALTER TABLE `demands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `farmers`
--
ALTER TABLE `farmers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1030;

--
-- AUTO_INCREMENT for table `farmer_activity_logs`
--
ALTER TABLE `farmer_activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `farmer_earnings`
--
ALTER TABLE `farmer_earnings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `farmer_reviews`
--
ALTER TABLE `farmer_reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `inventory_logs`
--
ALTER TABLE `inventory_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `matches`
--
ALTER TABLE `matches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `product_analytics`
--
ALTER TABLE `product_analytics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `sizes`
--
ALTER TABLE `sizes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `size_transactions`
--
ALTER TABLE `size_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2061;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `buyers`
--
ALTER TABLE `buyers`
  ADD CONSTRAINT `buyers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `conversation_threads`
--
ALTER TABLE `conversation_threads`
  ADD CONSTRAINT `conversation_threads_buyer_id_foreign` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `conversation_threads_farmer_id_foreign` FOREIGN KEY (`farmer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `demands`
--
ALTER TABLE `demands`
  ADD CONSTRAINT `demands_buyer_id_foreign` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `farmers`
--
ALTER TABLE `farmers`
  ADD CONSTRAINT `farmers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `farmer_activity_logs`
--
ALTER TABLE `farmer_activity_logs`
  ADD CONSTRAINT `farmer_activity_logs_farmer_id_foreign` FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `farmer_activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `farmer_earnings`
--
ALTER TABLE `farmer_earnings`
  ADD CONSTRAINT `farmer_earnings_farmer_id_foreign` FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `farmer_earnings_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `farmer_earnings_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `farmer_reviews`
--
ALTER TABLE `farmer_reviews`
  ADD CONSTRAINT `farmer_reviews_buyer_id_foreign` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `farmer_reviews_farmer_id_foreign` FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `farmer_reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `farmer_reviews_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory_logs`
--
ALTER TABLE `inventory_logs`
  ADD CONSTRAINT `inventory_logs_farmer_id_foreign` FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_logs_performed_by_foreign` FOREIGN KEY (`performed_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_logs_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_logs_size_id_foreign` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `inventory_logs_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `matches`
--
ALTER TABLE `matches`
  ADD CONSTRAINT `matches_demand_id_foreign` FOREIGN KEY (`demand_id`) REFERENCES `demands` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `matches_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_conversation_thread_id_foreign` FOREIGN KEY (`conversation_thread_id`) REFERENCES `conversation_threads` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_farmer_id_foreign` FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_analytics`
--
ALTER TABLE `product_analytics`
  ADD CONSTRAINT `product_analytics_farmer_id_foreign` FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_analytics_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sizes`
--
ALTER TABLE `sizes`
  ADD CONSTRAINT `sizes_farmer_id_foreign` FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sizes_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `size_transactions`
--
ALTER TABLE `size_transactions`
  ADD CONSTRAINT `size_transactions_size_id_foreign` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `size_transactions_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_conversation_thread_id_foreign` FOREIGN KEY (`conversation_thread_id`) REFERENCES `conversation_threads` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_initiator_id_foreign` FOREIGN KEY (`initiator_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
