-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 15, 2026 at 12:13 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `qr_base`
--

-- --------------------------------------------------------

--
-- Table structure for table `company_info`
--

CREATE TABLE `company_info` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `short_form` varchar(20) NOT NULL,
  `website_url` varchar(255) NOT NULL,
  `full_address` text NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `email_address` varchar(100) NOT NULL,
  `company_logo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `company_info`
--

INSERT INTO `company_info` (`id`, `user_id`, `company_name`, `short_form`, `website_url`, `full_address`, `phone_number`, `email_address`, `company_logo`, `created_at`) VALUES
(5, 8, 'new', 'test', 'https://leetcode.com/', 'Thanponnankala vadakkethu puthen veedu', '9567225114', 'test@gmail.com', 'uploads/company_logos/logo_8_1760764655.jpg', '2025-10-18 05:17:35');

-- --------------------------------------------------------

--
-- Table structure for table `loginregister`
--

CREATE TABLE `loginregister` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `login_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `logout_time` timestamp NULL DEFAULT NULL,
  `session_code` varchar(255) NOT NULL,
  `log_status` enum('login','logout') DEFAULT 'login'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loginregister`
--

INSERT INTO `loginregister` (`id`, `user_id`, `login_time`, `logout_time`, `session_code`, `log_status`) VALUES
(7, 2, '2025-10-04 13:16:20', NULL, '$2y$10$BEatMg1w9vIUFSoUDz.UQeAkVtk/tZBYvxwbGn5XK512dbXiUZhES', 'login'),
(8, 2, '2025-10-04 13:17:20', NULL, '$2y$10$.m0gRsWrWedWAOCPgrDNGuoSmZE4z/mIz5WQnp2GC3r.a6PUhGj8G', 'login'),
(9, 2, '2025-10-04 13:18:08', NULL, '$2y$10$CGI7WWKPOiZilkPQX/zH7.TRDITGYD4NMKRhe/yPRNA1IwWEgsr8a', 'login'),
(10, 2, '2025-10-04 13:18:41', '2025-10-04 13:19:34', '$2y$10$2PvioTEpdvTN6SpY9bG74e4uPf.hhO7fVBUq0UqK1C6fgZjlWN4hK', 'logout'),
(11, 2, '2025-10-04 13:21:33', '2025-10-04 14:17:34', '$2y$10$SRxCcNmEgiMt/0GcjYT9Xe5a56x3oVnUaHlNAS8LzrTTUyk05/Fle', 'logout'),
(16, 2, '2025-10-05 06:11:56', '2025-10-05 06:18:02', '0919682750695695', 'logout'),
(18, 2, '2025-10-05 06:55:23', '2025-10-05 07:02:59', '3061339275850475', 'logout'),
(20, 2, '2025-10-05 07:06:34', NULL, '8786469586299888', 'login'),
(21, 2, '2025-10-14 02:33:36', NULL, '4485894548138363', 'login'),
(22, 2, '2025-10-18 04:56:12', '2025-10-18 05:21:55', '3707480417549553', 'logout'),
(23, 8, '2025-10-18 05:22:05', '2025-10-18 05:23:44', '1468387108220096', 'logout'),
(24, 8, '2025-10-18 05:23:55', '2025-10-18 05:39:24', '6667105661197851', 'logout'),
(25, 8, '2025-10-18 05:39:34', '2025-10-18 05:46:12', '8534858158616200', 'logout'),
(26, 8, '2025-10-18 05:46:26', '2025-10-18 05:46:32', '7456386531520987', 'logout'),
(27, 8, '2025-10-18 05:51:09', '2025-10-18 05:55:37', '5103946914683627', 'logout'),
(28, 8, '2025-10-18 06:30:52', NULL, '7630751976401581', 'login'),
(29, 2, '2025-11-25 10:13:42', '2025-11-25 10:14:07', '1923104844841263', 'logout'),
(30, 8, '2025-11-25 10:16:01', '2025-11-25 10:23:50', '7452294658119086', 'logout'),
(31, 8, '2025-11-25 10:46:57', '2025-11-25 10:48:41', '9121005430787442', 'logout'),
(32, 8, '2025-11-25 10:53:18', '2025-11-25 11:01:57', '7538208607611761', 'logout'),
(33, 8, '2025-11-25 11:02:52', NULL, '8092426969023851', 'login'),
(34, 8, '2025-11-26 09:54:49', NULL, '3084692114335439', 'login'),
(35, 8, '2025-11-29 03:03:25', '2025-11-29 05:37:27', '9592384800337079', 'logout'),
(36, 8, '2025-11-29 05:52:40', '2025-11-29 06:10:32', '3681701740863689', 'logout'),
(37, 2, '2025-11-29 06:10:46', '2025-11-29 06:34:10', '0066067828672650', 'logout'),
(38, 8, '2025-11-29 06:39:21', '2025-11-29 06:40:30', '3388837757503151', 'logout'),
(39, 8, '2025-11-29 06:48:23', '2025-11-29 06:48:34', '4508270273198276', 'logout'),
(40, 2, '2025-11-29 06:48:42', NULL, '8121130625031800', 'login'),
(41, 8, '2026-09-15 09:48:24', NULL, '8976740261515872', 'login');

-- --------------------------------------------------------

--
-- Table structure for table `patient_qrcodes`
--

CREATE TABLE `patient_qrcodes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `patient_name` varchar(150) NOT NULL,
  `session_code` varchar(100) NOT NULL,
  `qr_image_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient_qrcodes`
--

INSERT INTO `patient_qrcodes` (`id`, `user_id`, `patient_name`, `session_code`, `qr_image_name`, `created_at`) VALUES
(1, 8, 'Renjish', '9592384800337079', 'qr_9592384800337079_1764394266.png', '2025-11-29 05:31:06'),
(2, 8, 'MBDC-Renjish K S', '8976740261515872', 'qr_8976740261515872_1789465718.png', '2026-09-15 09:48:38');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('0','1') DEFAULT '1',
  `status` enum('0','1','100') DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `password_hash`, `role`, `status`, `created_at`) VALUES
(2, 'admin@gmail.com', '$2y$10$LGK7GgASqd/HqF160IgA4.wg3IJ425GUd98861C9JENyFrGnmcNUm', '0', '0', '2025-10-04 13:15:43'),
(8, 'test@gmail.com', '$2y$10$LGK7GgASqd/HqF160IgA4.wg3IJ425GUd98861C9JENyFrGnmcNUm', '1', '0', '2025-10-18 05:17:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `company_info`
--
ALTER TABLE `company_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `loginregister`
--
ALTER TABLE `loginregister`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `patient_qrcodes`
--
ALTER TABLE `patient_qrcodes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_session_code` (`session_code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `company_info`
--
ALTER TABLE `company_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `loginregister`
--
ALTER TABLE `loginregister`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `patient_qrcodes`
--
ALTER TABLE `patient_qrcodes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `company_info`
--
ALTER TABLE `company_info`
  ADD CONSTRAINT `company_info_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `loginregister`
--
ALTER TABLE `loginregister`
  ADD CONSTRAINT `loginregister_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
