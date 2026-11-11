-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 30, 2025 at 11:26 PM
-- Server version: 10.6.23-MariaDB-cll-lve
-- PHP Version: 8.3.27

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
-- Table structure for table `properties`
--

CREATE TABLE `properties` (
  `id` int(10) UNSIGNED NOT NULL,
  `property_id` varchar(10) NOT NULL,
  `type` varchar(30) NOT NULL,
  `is_old_property` tinyint(1) NOT NULL DEFAULT 0,
  `unit_1` varchar(20) DEFAULT NULL,
  `value_1` decimal(12,2) DEFAULT NULL,
  `unit_2` varchar(20) DEFAULT NULL,
  `value_2` decimal(12,2) DEFAULT NULL,
  `village` varchar(100) DEFAULT NULL,
  `taluka` varchar(100) DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `landmark` varchar(255) DEFAULT NULL,
  `full_address` text DEFAULT NULL,
  `google_map_link` varchar(255) DEFAULT NULL,
  `rate_unit` varchar(20) DEFAULT NULL,
  `rate_value` decimal(12,2) DEFAULT NULL,
  `price_option` varchar(50) DEFAULT NULL,
  `owner_name` varchar(100) NOT NULL,
  `owner_contact` varchar(20) NOT NULL,
  `alt_contact` varchar(20) DEFAULT NULL,
  `is_direct_owner` tinyint(1) NOT NULL DEFAULT 0,
  `promotion_invoice_generated` tinyint(1) NOT NULL DEFAULT 0,
  `promotion_tag` varchar(50) DEFAULT NULL,
  `promotion_date` date DEFAULT NULL,
  `photos_json` text DEFAULT NULL,
  `main_photo` varchar(255) DEFAULT NULL,
  `video_link` varchar(255) DEFAULT NULL,
  `location_note` text DEFAULT NULL,
  `status` enum('draft','published') NOT NULL DEFAULT 'draft',
  `moderation_status` enum('pending','verified','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `area_type` varchar(64) DEFAULT NULL,
  `area_size` decimal(18,2) DEFAULT NULL,
  `total_rupees_amount` decimal(18,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`id`, `property_id`, `type`, `is_old_property`, `unit_1`, `value_1`, `unit_2`, `value_2`, `village`, `taluka`, `district`, `landmark`, `full_address`, `google_map_link`, `rate_unit`, `rate_value`, `price_option`, `owner_name`, `owner_contact`, `alt_contact`, `is_direct_owner`, `promotion_invoice_generated`, `promotion_tag`, `promotion_date`, `photos_json`, `main_photo`, `video_link`, `location_note`, `status`, `moderation_status`, `created_by`, `created_at`, `area_type`, `area_size`, `total_rupees_amount`) VALUES
(1, 'PL0001', 'Plot', 0, 'Sq Ft', 0.00, 'Sq Ft', 0.00, '3', '3', '3', '3', '3', 'https://fmv.95b.mytemp.website/assets/property_add.php', 'Sq Ft', 1.00, '10 lakh per guntha', 'j', 'k', '4', 1, 0, 'UNPAID', '0000-00-00', '[\"images\\/property_uploads\\/PL0001_1764348761_0.png\"]', 'images/property_uploads/PL0001_1764348761_0.png', 'https://fmv.95b.mytemp.website/assets/property_add.php', 'j', 'draft', 'rejected', 'staff', '2025-11-28 16:52:41', NULL, NULL, NULL),
(2, 'PL0002', 'Plot', 0, 'Sq Ft', 0.00, 'Sq Ft', 0.00, '3', '3', '3', '3', '3', 'https://fmv.95b.mytemp.website/assets/property_add.php', 'Sq Ft', 1.00, '10 lakh per guntha', 'j', 'k', '4', 1, 0, 'UNPAID', '0000-00-00', '[\"images\\/property_uploads\\/PL0002_1764348762_0.png\"]', 'images/property_uploads/PL0002_1764348762_0.png', 'https://fmv.95b.mytemp.website/assets/property_add.php', 'j', 'draft', 'rejected', 'staff', '2025-11-28 16:52:42', NULL, NULL, NULL),
(3, 'FL0001', 'Flat', 0, 'Sq Ft', 0.00, 'Sq Ft', 0.00, 'Bhose', 'Miraj', 'Sangli', 'Neal temple ', 'Ok', '', 'Sq Ft', 1700.00, '10 lakh per guntha', 'Maruti kamble ', '9172685662', '8978654322', 1, 1, 'PAID FOR PROMOTION', '2025-11-30', NULL, NULL, '', '', 'draft', 'rejected', 'staff', '2025-11-30 05:07:07', NULL, NULL, NULL),
(4, 'AG0001', 'Agriculture', 1, 'Office', 0.00, 'Sq Ft', 0.00, 'Soni', 'Miraj ', 'sangli', 'near laxmi temple', 'laxmi mandir, 100 mtr from temple', 'https://fmv.95b.mytemp.website/assets/property_add.php', 'Total', 60.00, '10 lakh per guntha', 'maruti ', '9172685662', '7709092063', 0, 0, NULL, '2025-02-20', '[\"images\\/property_uploads\\/AG0001_1764498094_0.jpeg\"]', 'images/property_uploads/AG0001_1764498094_0.jpeg', 'https://youtube.com/shorts/gulRU9JMCU0?si=Xl-DE1IDDfWIYMe6', '', 'draft', 'rejected', 'staff', '2025-11-30 10:21:34', NULL, NULL, NULL),
(5, 'PL0003', 'Plot', 0, 'Sq Ft', 0.00, 'Sq Ft', 0.00, 'Chinmay Park', 'Miraj ', 'sangli', 'near laxmi temple', 'laxmi mandir, 100 mtr from temple', 'https://fmv.95b.mytemp.website/assets/property_add.php', 'Sq Ft', 60.00, '', 'maruti ', '9172685662', '7709092063', 0, 0, NULL, '2025-02-20', NULL, NULL, 'https://youtube.com/shorts/gulRU9JMCU0?si=Xl-DE1IDDfWIYMe6', '', 'draft', 'approved', 'staff', '2025-11-30 10:25:54', NULL, NULL, NULL),
(6, 'PL0004', 'Plot', 0, 'Sq Ft', 0.00, 'Sq Ft', 0.00, 'Chinmay Park', 'Miraj ', 'sangli', 'near laxmi temple', 'laxmi mandir, 100 mtr from temple', 'https://fmv.95b.mytemp.website/assets/property_add.php', 'Sq Ft', 60.00, '', 'maruti ', '9172685662', '7709092063', 0, 0, NULL, '2025-02-20', NULL, NULL, 'https://youtube.com/shorts/gulRU9JMCU0?si=Xl-DE1IDDfWIYMe6', '', 'draft', 'approved', 'staff', '2025-11-30 10:25:54', NULL, NULL, NULL),
(8, 'PL0006', 'Plot', 0, 'Sq Ft', 0.00, 'Sq Ft', 0.00, 'Chinmay Park', 'Miraj ', 'sangli', 'near laxmi temple', 'laxmi mandir, 100 mtr from temple', 'https://fmv.95b.mytemp.website/assets/property_add.php', 'Sq Ft', 60.00, '', 'maruti ', '9172685662', '7709092063', 0, 0, NULL, '2025-02-20', NULL, NULL, 'https://youtube.com/shorts/gulRU9JMCU0?si=Xl-DE1IDDfWIYMe6', '', 'draft', 'approved', 'staff', '2025-11-30 10:25:54', NULL, NULL, NULL),
(9, 'PL0007', 'Plot', 0, 'Sq Ft', 0.00, 'Sq Ft', 0.00, 'Chinmay Park', 'Miraj ', 'sangli', 'near laxmi temple', 'laxmi mandir, 100 mtr from temple', 'https://fmv.95b.mytemp.website/assets/property_add.php', 'Sq Ft', 60.00, '', 'maruti ', '9172685662', '7709092063', 0, 0, NULL, '2025-02-20', NULL, NULL, 'https://youtube.com/shorts/gulRU9JMCU0?si=Xl-DE1IDDfWIYMe6', '', 'draft', 'approved', 'staff', '2025-11-30 10:25:54', NULL, NULL, NULL),
(10, 'PL0008', 'Plot', 0, 'Sq Ft', 0.00, 'Sq Ft', 0.00, 'Chinmay Park', 'Miraj ', 'sangli', 'near laxmi temple', 'laxmi mandir, 100 mtr from temple', 'https://fmv.95b.mytemp.website/assets/property_add.php', 'Sq Ft', 60.00, '', 'maruti ', '9172685662', '7709092063', 0, 0, NULL, '2025-02-20', NULL, NULL, 'https://youtube.com/shorts/gulRU9JMCU0?si=Xl-DE1IDDfWIYMe6', '', 'draft', 'rejected', 'staff', '2025-11-30 10:25:54', NULL, NULL, NULL),
(11, 'PL0009', 'Plot', 0, 'Sq Ft', 0.00, 'Sq Ft', 0.00, 'Chinmay Park', 'Miraj ', 'sangli', 'near laxmi temple', 'laxmi mandir, 100 mtr from temple', 'https://fmv.95b.mytemp.website/assets/property_add.php', 'Sq Ft', 60.00, '', 'maruti ', '9172685662', '7709092063', 0, 0, NULL, '2025-02-20', NULL, NULL, 'https://youtube.com/shorts/gulRU9JMCU0?si=Xl-DE1IDDfWIYMe6', '', 'draft', 'rejected', 'staff', '2025-11-30 10:25:54', NULL, NULL, NULL),
(12, 'PL0010', 'Plot', 0, 'Sq Ft', 0.00, 'Sq Ft', 0.00, 'Chinmay Park', 'Miraj ', 'sangli', 'near laxmi temple', 'laxmi mandir, 100 mtr from temple', 'https://fmv.95b.mytemp.website/assets/property_add.php', 'Sq Ft', 60.00, '', 'maruti ', '9172685662', '7709092063', 0, 0, NULL, '2025-02-20', NULL, NULL, 'https://youtube.com/shorts/gulRU9JMCU0?si=Xl-DE1IDDfWIYMe6', '', 'draft', 'rejected', 'staff', '2025-11-30 10:25:54', NULL, NULL, NULL),
(13, 'PL0011', 'Plot', 0, 'Sq Ft', 0.00, 'Sq Ft', 0.00, 'Chinmay Park', 'Miraj ', 'sangli', 'near laxmi temple', 'laxmi mandir, 100 mtr from temple', 'https://fmv.95b.mytemp.website/assets/property_add.php', 'Sq Ft', 60.00, '', 'maruti ', '9172685662', '7709092063', 0, 0, NULL, '2025-02-20', NULL, NULL, 'https://youtube.com/shorts/gulRU9JMCU0?si=Xl-DE1IDDfWIYMe6', '', 'draft', 'rejected', 'staff', '2025-11-30 10:25:54', NULL, NULL, NULL),
(14, 'PL0012', 'Plot', 0, 'Sq Ft', 0.00, 'Sq Ft', 0.00, 'Chinmay Park', 'Miraj ', 'sangli', 'near laxmi temple', 'laxmi mandir, 100 mtr from temple', 'https://fmv.95b.mytemp.website/assets/property_add.php', 'Sq Ft', 60.00, '', 'maruti ', '9172685662', '7709092063', 0, 0, NULL, '2025-02-20', NULL, NULL, 'https://youtube.com/shorts/gulRU9JMCU0?si=Xl-DE1IDDfWIYMe6', '', 'draft', 'rejected', 'staff', '2025-11-30 10:25:54', NULL, NULL, NULL),
(15, 'PL0013', 'Plot', 0, 'Sq Ft', 0.00, 'Sq Ft', 0.00, 'Chinmay Park', 'Miraj ', 'sangli', 'near laxmi temple', 'laxmi mandir, 100 mtr from temple', 'https://fmv.95b.mytemp.website/assets/property_add.php', 'Sq Ft', 60.00, '', 'maruti ', '9172685662', '7709092063', 0, 0, NULL, '2025-02-20', NULL, NULL, 'https://youtube.com/shorts/gulRU9JMCU0?si=Xl-DE1IDDfWIYMe6', '', 'draft', 'rejected', 'staff', '2025-11-30 10:25:55', NULL, NULL, NULL),
(16, 'FL0002', 'Flat', 0, 'Sq Ft', 0.00, 'Sq Ft', 0.00, 'gav', 'Miraj ', 'sangli', 'goti', 'lok', 'https://fmv.95b.mytemp.website/assets/property_add.php', 'Total', 4500000.00, 'Total Rupees', 'kk', '9172685662', '7709092063', 0, 1, NULL, '2025-02-20', '[\"images\\/property_uploads\\/FL0002_1764499192_0.jpeg\"]', 'images/property_uploads/FL0002_1764499192_0.jpeg', 'https://youtube.com/shorts/gulRU9JMCU0?si=Xl-DE1IDDfWIYMe6', '', 'draft', 'approved', 'staff', '2025-11-30 10:39:52', NULL, NULL, NULL),
(17, 'RR0001', 'Residential Rent', 0, 'Sq Ft', 0.00, 'Sq Ft', 1100.00, 'gavbhag', 'Miraj ', 'sangli', 'maruti chowk', 'sangli', '', 'Total', 12000.00, '', 'kisan patel', '9172685662', '9172685662', 1, 0, 'UNPAID', '0000-00-00', '[\"images\\/property_uploads\\/RR0001_1764502045_0.jpeg\"]', 'images/property_uploads/RR0001_1764502045_0.jpeg', '', '', 'draft', 'approved', 'staff', '2025-11-30 11:27:25', NULL, NULL, NULL),
(18, 'AG0002', 'Agriculture', 0, 'Sq Ft', 0.00, 'Sq Ft', 0.00, 'Vishrambagh', 'miraj', 'sangli', 'temple', 'ghotiv pada shaitan gakkli vodgaon', '', 'Sq Ft', 2000.00, '', 'farnado ', '9172685668', '7709092063', 0, 0, NULL, '0000-00-00', '[\"images\\/property_uploads\\/AG0002_1764516425_0.jpeg\",\"images\\/property_uploads\\/AG0002_1764516425_1.jpeg\",\"images\\/property_uploads\\/AG0002_1764516425_2.jpeg\",\"images\\/property_uploads\\/AG0002_1764516425_3.jpeg\",\"images\\/property_uploads\\/AG0002_1764516425_4.jpeg\",\"images\\/property_uploads\\/AG0002_1764516425_5.jpeg\",\"images\\/property_uploads\\/AG0002_1764516425_6.jpeg\",\"images\\/property_uploads\\/AG0002_1764516425_7.jpeg\",\"images\\/property_uploads\\/AG0002_1764516425_8.jpeg\",\"images\\/property_uploads\\/AG0002_1764516425_9.jpeg\"]', 'images/property_uploads/AG0002_1764516425_0.jpeg', '', '', 'published', 'approved', 'staff', '2025-11-30 15:27:05', NULL, NULL, NULL),
(19, 'AG0003', 'Agriculture', 0, 'Sq Ft', 0.00, 'Sq Ft', 0.00, 'Vishrambagh', 'miraj', 'sangli', 'temple', 'ghotiv pada shaitan gakkli vodgaon', '', 'Acre', 3500000.00, 'Total Rupees', 'mukund', '9172685668', '7709092063', 1, 0, 'UNPAID', '0000-00-00', '[\"images\\/property_uploads\\/AG0003_1764516954_0.jpeg\",\"images\\/property_uploads\\/AG0003_1764516954_1.jpeg\",\"images\\/property_uploads\\/AG0003_1764516954_2.jpeg\",\"images\\/property_uploads\\/AG0003_1764516954_3.jpeg\",\"images\\/property_uploads\\/AG0003_1764516954_4.jpeg\"]', 'images/property_uploads/AG0003_1764516954_0.jpeg', '', '', 'published', 'approved', 'staff', '2025-11-30 15:35:54', NULL, NULL, NULL),
(20, 'AG0004', 'Agriculture', 0, 'Sq Ft', 0.00, 'Sq Ft', 0.00, 'Gaurgaon', 'kalamb', 'kallamd', 'Maharashtra', 'asf', 'https://fmv.95b.mytemp.website/property/property_details.php', 'Acre', 0.00, '10 lakh per guntha', 'PRUTHVIRAJ', 'KJXZGHVRIPDFG', '132321', 1, 1, 'PAID FOR PROMOTION', '2005-05-10', '[\"images\\/property_uploads\\/AG0004_1764998005_0.png\"]', 'images/property_uploads/AG0004_1764998005_0.png', 'https://youtube.com/shorts/gulRU9JMCU0?si=Xl-DE1IDDfWIYMe6', 'mk', 'draft', 'approved', 'staff', '2025-12-06 05:13:25', NULL, NULL, NULL),
(21, 'AGR10001', 'Agriculture', 0, 'Sq Ft', 0.00, 'Sq Ft', 0.00, 'Gaurgaon', 'test', 'kallamd', 'Maharashtra', 'test', 'https://fmv.95b.mytemp.website/property/property_details.php', 'Acre', 1.00, 'thousand', 'PRUTHVIRAJ', '9898989898', '132321', 0, 0, NULL, '0000-00-00', '[\"images\\/property_uploads\\/AGR10001_1766658044_0.png\",\"images\\/property_uploads\\/AGR10001_1766658044_1.webp\",\"images\\/property_uploads\\/AGR10001_1766658044_2.png\",\"images\\/property_uploads\\/AGR10001_1766658044_3.png\",\"images\\/property_uploads\\/AGR10001_1766658044_4.png\",\"images\\/property_uploads\\/AGR10001_1766658044_5.png\",\"images\\/property_uploads\\/AGR10001_1766658044_6.png\",\"images\\/property_uploads\\/AGR10001_1766658044_7.png\",\"images\\/property_uploads\\/AGR10001_1766658044_8.jpeg\"]', 'images/property_uploads/AGR10001_1766658044_8.jpeg', 'https://youtube.com/shorts/gulRU9JMCU0?si=Xl-DE1IDDfWIYMe6', 'ui', 'draft', 'pending', 'staff', '2025-12-25 10:20:44', 'Sq Ft', 0.00, 0.00),
(22, 'AGRI1138', 'Agriculture', 1, 'Sq Ft', 0.00, 'Sq Ft', 0.00, 'Malgaon', 'Miraj', 'Sangli', 'Siddhewadi Road ', '3 km From malgaon dargah ', '', 'Sq Ft', 30.00, 'lakh', 'Sunil Patil', '7798971718', '8149116124', 0, 1, NULL, '2025-12-12', '[\"images\\/property_uploads\\/AGRI1138_1766921889_0.jpeg\",\"images\\/property_uploads\\/AGRI1138_1766921889_1.jpeg\",\"images\\/property_uploads\\/AGRI1138_1766921889_2.jpeg\",\"images\\/property_uploads\\/AGRI1138_1766921889_3.jpeg\",\"images\\/property_uploads\\/AGRI1138_1766921889_4.jpeg\",\"images\\/property_uploads\\/AGRI1138_1766921889_5.jpeg\",\"images\\/property_uploads\\/AGRI1138_1766921889_6.jpeg\",\"images\\/property_uploads\\/AGRI1138_1766921889_7.jpeg\"]', 'images/property_uploads/AGRI1138_1766921889_0.jpeg', '', '', 'draft', 'approved', 'staff', '2025-12-28 11:38:09', 'Sq Ft', 0.00, 0.00),
(23, 'AGR10002', 'Agriculture', 0, 'Sq Ft', 0.00, 'Sq Ft', 0.00, 'bhose', 'miraj', 'Sangli', 'Near temple', 'Bhose phata', '', 'Acre', 40.00, 'lakh', 'prathmesh lamdade', '8149116124', '7776025124', 0, 1, NULL, '2025-02-10', '[\"images\\/property_uploads\\/AGR10002_1767104369_0.jpeg\",\"images\\/property_uploads\\/AGR10002_1767104369_1.jpeg\",\"images\\/property_uploads\\/AGR10002_1767104369_2.jpeg\",\"images\\/property_uploads\\/AGR10002_1767104369_3.jpeg\",\"images\\/property_uploads\\/AGR10002_1767104369_4.jpeg\",\"images\\/property_uploads\\/AGR10002_1767104369_5.jpeg\",\"images\\/property_uploads\\/AGR10002_1767104369_6.jpeg\",\"images\\/property_uploads\\/AGR10002_1767104369_7.jpeg\"]', 'images/property_uploads/AGR10002_1767104369_0.jpeg', '', '', 'draft', 'approved', 'staff', '2025-12-30 14:19:29', 'Sq Ft', 0.00, 0.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_property_id` (`property_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
