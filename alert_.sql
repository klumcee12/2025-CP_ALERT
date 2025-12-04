-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3309
-- Generation Time: Dec 04, 2025 at 12:27 PM
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
-- Database: `alert+`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab', 'i:1;', 1757495284),
('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab:timer', 'i:1757495284;', 1757495284);

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
-- Table structure for table `children`
--

CREATE TABLE `children` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL DEFAULT 'regular',
  `device_id` varchar(255) NOT NULL,
  `sim_number` varchar(255) DEFAULT NULL,
  `signal_strength` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `battery_percent` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `last_seen_at` timestamp NULL DEFAULT NULL,
  `last_lat` decimal(10,7) DEFAULT NULL,
  `last_lng` decimal(10,7) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `children`
--

INSERT INTO `children` (`id`, `user_id`, `name`, `category`, `device_id`, `sim_number`, `signal_strength`, `battery_percent`, `last_seen_at`, `last_lat`, `last_lng`, `created_at`, `updated_at`) VALUES
(2, 22, 'dasdas', 'child_with_disability', 'asdasd', NULL, 0, 0, NULL, NULL, NULL, '2025-11-05 09:55:11', '2025-11-05 09:55:11'),
(4, 22, 'lusia solis', 'regular', 'asdas', NULL, 0, 0, NULL, NULL, NULL, '2025-11-14 00:11:20', '2025-11-14 00:11:20'),
(5, 22, 'jason pogi', 'regular', 'devicenumber1', '09261855655', 0, 0, NULL, NULL, NULL, '2025-11-14 00:18:29', '2025-11-14 00:18:29'),
(6, 22, 'lusia solis', 'regular', 'DEVICE001', '09261855655', 0, 0, NULL, NULL, NULL, '2025-12-03 06:13:12', '2025-12-03 06:13:12'),
(7, 22, 'lusia solis', 'regular', 'DEVICE0010', NULL, 0, 0, NULL, NULL, NULL, '2025-12-03 06:13:33', '2025-12-03 06:13:33');

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
-- Table structure for table `location_logs`
--

CREATE TABLE `location_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `child_id` bigint(20) UNSIGNED NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `address` varchar(255) DEFAULT NULL,
  `source` varchar(255) NOT NULL,
  `status` enum('success','fail') NOT NULL,
  `lat` decimal(10,7) DEFAULT NULL,
  `lng` decimal(10,7) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(4, '2025_09_12_000100_create_children_table', 2),
(5, '2025_09_12_000110_create_location_logs_table', 2),
(6, '2025_09_12_000120_create_presence_calls_table', 2);

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
-- Table structure for table `presence_calls`
--

CREATE TABLE `presence_calls` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `child_id` bigint(20) UNSIGNED NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `sequence` varchar(255) NOT NULL,
  `strength` tinyint(3) UNSIGNED NOT NULL,
  `duration_seconds` smallint(5) UNSIGNED NOT NULL,
  `dnd_mode` enum('respect','ignore') NOT NULL DEFAULT 'respect',
  `status` enum('sent','failed') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `presence_calls`
--

INSERT INTO `presence_calls` (`id`, `child_id`, `timestamp`, `sequence`, `strength`, `duration_seconds`, `dnd_mode`, `status`, `created_at`, `updated_at`) VALUES
(1, 4, '2025-12-03 06:24:35', 'gentle', 3, 12, 'respect', 'sent', '2025-12-03 06:24:35', '2025-12-03 06:24:35'),
(2, 7, '2025-12-03 06:24:55', 'gentle', 3, 12, 'respect', 'sent', '2025-12-03 06:24:55', '2025-12-03 06:24:55'),
(3, 7, '2025-12-03 06:36:40', 'gentle', 3, 12, 'ignore', 'sent', '2025-12-03 06:36:40', '2025-12-03 06:36:40'),
(4, 7, '2025-12-03 06:36:50', 'gentle', 5, 12, 'ignore', 'sent', '2025-12-03 06:36:50', '2025-12-03 06:36:50'),
(5, 7, '2025-12-03 06:36:54', 'gentle', 5, 12, 'ignore', 'sent', '2025-12-03 06:36:54', '2025-12-03 06:36:54'),
(6, 7, '2025-12-03 06:36:58', 'gentle', 5, 12, 'ignore', 'sent', '2025-12-03 06:36:58', '2025-12-03 06:36:58'),
(7, 7, '2025-12-03 06:37:19', 'standard', 5, 12, 'ignore', 'sent', '2025-12-03 06:37:19', '2025-12-03 06:37:19'),
(8, 7, '2025-12-03 07:20:25', 'standard', 5, 12, 'ignore', 'sent', '2025-12-03 07:20:25', '2025-12-03 07:20:25'),
(9, 7, '2025-12-03 07:20:33', 'standard', 5, 30, 'ignore', 'sent', '2025-12-03 07:20:33', '2025-12-03 07:20:33'),
(10, 7, '2025-12-04 02:58:55', 'gentle', 3, 12, 'respect', 'sent', '2025-12-04 02:58:55', '2025-12-04 02:58:55');

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
('0KragCAtmDKysnhBBIkJgbrXF1PvVcb0raYD1pYa', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRU1pRUtiQkNubmE1cVQ3NHhBMTNrOWFyenYzZzMxVTNJdnVORDB1RiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQvbG9jYXRpb24tbG9ncyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1763138939),
('BckY7WqxzXXKvqw1RInjeGwPlRlpzwhln79UJmKR', 22, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiREhhb2dmRGlGZm1kRkVyZktvTXR2TEFvWTFRazVFU08ya3VHajFRVCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9maWxlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjI7fQ==', 1763907605),
('c38TMODoyoFQnogJjblSsjlpbmtTxtQvpwLDZZFE', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibno3bzEwVVc0SWNUbjZxSm5EOGY3VDRuVHA4QVo4bEJnRDBadUQ1NSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1764521006),
('cJ9a8xduP6C2zciW3jghzFv9zSrcNOioouDwKoCh', 22, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibkpOU3JIWjBsbzV3WWJYUkx4Rm9VR2hnMlhuekpjeDFsZUtQd0EwQSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zZXNzaW9uL2NoZWNrIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjI7fQ==', 1764775341),
('MxkKl7v6jcnmiBgVCb1i9heBFlCO5lXrHDSqkvj0', 22, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMExUaWRHeHIyOFBaTHkwWDI5RWNwazV4M3dVVzRoV0dmcmxRb0pyTSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9maWxlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjI7fQ==', 1763924380),
('n9KUyMY62y0i8Lc8Cy1hD34GsmxDwlRKZqkLE90o', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Cursor/2.1.46 Chrome/138.0.7204.251 Electron/37.7.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiT0RrQXNPOXBiUXozR1FtbkhSY3ExdnhCQTlvQThhNkxYalFTZktUZiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1764845301),
('OkS52p7gDpGmmIzWGeT4N15bOdXls0jlj8U664fd', 22, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQzdVVmZYaXptYmZCRWJrRkZZMXJXN2NtaklzMVJrSlRDWDNSRjl4QyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zZXNzaW9uL2NoZWNrIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjI7fQ==', 1764847370),
('wCtQtvjs6Y0wAiO1EIqeDymFKX2ESEv86znbZnnQ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMEROMzNkQVB1Um9XVW43NmJiWGM5NmxYVEFuV0hLZmNxQXFKdVh1ayI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1764521010),
('xpjQXbd8TA9GF63e7sjpkBAb1cdyfuNtPVifUavN', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Cursor/2.1.39 Chrome/138.0.7204.251 Electron/37.7.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWTc5WGZVOWI3ZENBa29NWVU5eTJZYjJnUmFWbzhWWjV0NGN3ZjFaYiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1764769807),
('zVTvkZ1qj2CLZfmHNRzd7yfOgUkJhtrVLrkir5Nz', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibGNWbld4YUZPbnJxRjNveENNWTM2amwxSlhQbjd3N1gxOGVFemdSWSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQvcHJlc2VuY2UtY2FsbHMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1763138943);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `middle_name` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `middle_name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(3, 'jel Solis', '', 'jeluiose14.2021@gmail.com', '2025-09-12 08:11:41', '$2y$12$qq85H2jHMWI4vc9oVb9QDOhfOIPFwcoB//wN/.9zZ7lhBbsxf4D6S', NULL, '2025-09-12 08:09:33', '2025-09-12 08:11:41'),
(4, 'Tianno Palautog', '', 'ltianni18@gmail.com', NULL, '$2y$12$eNmkIP.nz1b8fLMQlEDn5uL6lz.o720zdcnh368Yi9BRhKVtd.lQi', NULL, '2025-09-12 08:57:00', '2025-09-12 08:57:00'),
(8, 'test test1', '', 'vinczarj@gmail.com', '2025-09-01 07:08:43', '$2y$12$oL2VcZLXRcot2NmZ4JIsVeovmXi019CiPjBZsC5cFJx0Z3DjnGLk2', NULL, '2025-09-15 23:02:23', '2025-09-15 23:02:23'),
(10, 'test test middle test last', 'test middle', 'eh202201432@wmsu.edu.ph', NULL, '$2y$12$Q7XRmXMuchqYQj4F/Q3ZL.cuHOEYUrT8bwRSD/zNl2wXS0DLMs/be', NULL, '2025-09-24 08:04:45', '2025-09-24 08:04:45'),
(13, 'asdasd asdasd asdasd', 'asdasd', 'corpuzasiul1@gmail.com', NULL, '$2y$12$u549IOyKuxMD5F6beieEIe8VeibQaE7aSMYV94mjOPy5Bxq.wekfm', NULL, '2025-09-24 08:18:20', '2025-09-24 08:18:20'),
(22, 'hello world hello', 'world', 'solis2003asiul@gmail.com', '2025-10-27 16:51:33', '$2y$12$z8EEjWuni07sMaW4uYVmEuJrTQvt6i25fHWCL.EqYrzQEIITM2mgW', NULL, '2025-10-27 16:51:15', '2025-11-14 05:01:43');

--
-- Indexes for dumped tables
--

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
-- Indexes for table `children`
--
ALTER TABLE `children`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `children_device_id_unique` (`device_id`),
  ADD KEY `children_user_id_foreign` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `location_logs`
--
ALTER TABLE `location_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `location_logs_child_id_foreign` (`child_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `presence_calls`
--
ALTER TABLE `presence_calls`
  ADD PRIMARY KEY (`id`),
  ADD KEY `presence_calls_child_id_foreign` (`child_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

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
-- AUTO_INCREMENT for table `children`
--
ALTER TABLE `children`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `location_logs`
--
ALTER TABLE `location_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `presence_calls`
--
ALTER TABLE `presence_calls`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `children`
--
ALTER TABLE `children`
  ADD CONSTRAINT `children_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `location_logs`
--
ALTER TABLE `location_logs`
  ADD CONSTRAINT `location_logs_child_id_foreign` FOREIGN KEY (`child_id`) REFERENCES `children` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `presence_calls`
--
ALTER TABLE `presence_calls`
  ADD CONSTRAINT `presence_calls_child_id_foreign` FOREIGN KEY (`child_id`) REFERENCES `children` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
