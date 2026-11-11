-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 09, 2025 at 11:49 AM
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
-- Database: `property`
--

-- --------------------------------------------------------

--
-- Table structure for table `upload`
--

CREATE TABLE `upload` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `village` varchar(255) DEFAULT NULL,
  `business` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `contact_no` varchar(32) DEFAULT NULL,
  `customer_email` varchar(255) DEFAULT NULL,
  `client_id` varchar(64) DEFAULT NULL,
  `property_type` varchar(255) DEFAULT NULL,
  `sale_rent` varchar(50) DEFAULT NULL,
  `expected_rent` varchar(255) DEFAULT NULL,
  `deposit` varchar(255) DEFAULT NULL,
  `location_area` varchar(255) DEFAULT NULL,
  `city_location` varchar(255) DEFAULT NULL,
  `city_area` varchar(255) DEFAULT NULL,
  `agri_district` varchar(255) DEFAULT NULL,
  `agri_taluka` varchar(255) DEFAULT NULL,
  `agri_property_size` varchar(255) DEFAULT NULL,
  `land_property_type` varchar(255) DEFAULT NULL,
  `specification` text DEFAULT NULL,
  `by` varchar(255) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(255) NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `upload`
--
ALTER TABLE `upload`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `upload`
--
ALTER TABLE `upload`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- --------------------------------------------------------

-- Table structure for table `receipt`

CREATE TABLE `receipt` (
  `id` int(11) NOT NULL,
  `amount_head` varchar(255) DEFAULT NULL,
  `pay_to` varchar(255) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `property_id` varchar(64) DEFAULT NULL,
  `description` text,
  `registration_fee` decimal(10,2) DEFAULT NULL,
  `visit_fee` decimal(10,2) DEFAULT NULL,
  `promotional_charges` decimal(10,2) DEFAULT NULL,
  `deal_done_token` decimal(10,2) DEFAULT NULL,
  `transportation` decimal(10,2) DEFAULT NULL,
  `account` varchar(255) DEFAULT NULL,
  `payment_mode` varchar(20) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `amount_in_words` varchar(255) DEFAULT NULL,
  `approved_by` varchar(255) DEFAULT NULL,
  `received_by` varchar(255) DEFAULT NULL,
  `by` varchar(255) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

ALTER TABLE `receipt`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `receipt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
-- Property Details table for property management entries
CREATE TABLE IF NOT EXISTS `property_details` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `property_id` VARCHAR(100) NOT NULL,
  `details` TEXT NOT NULL,
  `price` VARCHAR(100) NOT NULL,
  `address` VARCHAR(255) NOT NULL,
  `owner_name` VARCHAR(100) NOT NULL,
  `owner_contact` VARCHAR(50) NOT NULL,
  `broker` VARCHAR(100) DEFAULT NULL,
  `website` VARCHAR(255) DEFAULT NULL,
  `photo_path` VARCHAR(255) DEFAULT NULL,
  `video_link` VARCHAR(255) DEFAULT NULL,
  `promotion` VARCHAR(255) DEFAULT NULL,
  `size` VARCHAR(100) DEFAULT NULL,
  `by` VARCHAR(100) DEFAULT NULL,
  `date` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Property Advarticement 0 table
CREATE TABLE IF NOT EXISTS `property_advarticement0` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `property_id` VARCHAR(100) DEFAULT NULL,
  `content` TEXT DEFAULT NULL,
  `owner_name` VARCHAR(100) DEFAULT NULL,
  `paid_video_amount` VARCHAR(100) DEFAULT NULL,
  `transportation` VARCHAR(100) DEFAULT NULL,
  `promotion_closing_executive_name` VARCHAR(150) DEFAULT NULL,
  `closing_date` DATE DEFAULT NULL,
  `work_order_video` VARCHAR(255) DEFAULT NULL,
  `regular_office_video` VARCHAR(255) DEFAULT NULL,
  `video_post_date` DATE DEFAULT NULL,
  `remark` TEXT DEFAULT NULL,
  `by` VARCHAR(100) DEFAULT NULL,
  `date` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
