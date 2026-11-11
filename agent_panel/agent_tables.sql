-- phpMyAdmin SQL Dump
-- Agent Panel Tables with _ap suffix
-- Database: `property`

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
--
-- Table structure for table `users_ap`
--
CREATE TABLE `users_ap` (
  `id` int(11) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `address` varchar(255) DEFAULT NULL,
  `occupation` varchar(255) DEFAULT NULL,
  `approval_status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for table `users_ap`
--
ALTER TABLE `users_ap`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for table `users_ap`
--
ALTER TABLE `users_ap`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------

--
-- Table structure for table `otp_ap`
--

CREATE TABLE `otp_ap` (
  `id` int(11) NOT NULL,
  `uuid` varchar(100) NOT NULL,
  `otp` varchar(10) NOT NULL,
  `status` enum('pending','verified') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for table `otp_ap`
--
ALTER TABLE `otp_ap`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for table `otp_ap`
--
ALTER TABLE `otp_ap`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

COMMIT;
