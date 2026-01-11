-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 11, 2026 at 07:51 PM
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
-- Database: `hr1_recruitment_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `applicants`
--

CREATE TABLE `applicants` (
  `applicant_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middlename` varchar(100) DEFAULT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `resume_path` varchar(255) DEFAULT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `job_id` int(11) NOT NULL,
  `position_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `job_title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `requirements` text DEFAULT NULL,
  `vacancies` int(11) DEFAULT 1,
  `filled_positions` int(11) DEFAULT 0,
  `status` enum('Open','Closed','On Hold') DEFAULT 'Open',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `employment_type` enum('Full-time','Part-time','Contract') DEFAULT 'Full-time'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`job_id`, `position_id`, `department_id`, `job_title`, `description`, `requirements`, `vacancies`, `filled_positions`, `status`, `created_at`, `updated_at`, `employment_type`) VALUES
(8, 501, 1, 'Front Desk Receptionist', 'Assist guests with check-ins, reservations, and inquiries, ensuring a smooth front desk operation.', 'Excellent communication skills, basic computer knowledge, experience in hospitality preferred.', 2, 1, 'Open', '2025-10-24 07:49:02', '2025-10-24 07:49:02', 'Full-time'),
(9, 502, 2, 'Restaurant Server', 'Serve food and beverages in the restaurant, ensure guest satisfaction, and maintain cleanliness of service areas.', 'Friendly personality, multitasking skills, and at least 6 months experience in food service.', 3, 1, 'Open', '2025-10-24 07:49:02', '2025-10-24 07:49:02', 'Part-time'),
(10, 503, 3, 'Housekeeping Attendant', 'Clean and organize guest rooms and public areas following hotel standards.', 'Physically fit, detail-oriented, with previous housekeeping experience preferred.', 4, 2, 'Closed', '2025-10-24 07:49:02', '2025-10-24 08:01:48', 'Full-time'),
(23, 504, 1, 'Front Desk Officer', 'Handles guest check-ins, reservations, and inquiries.', 'Excellent communication skills and experience with hotel software.', 2, 0, 'Open', '2025-10-24 08:43:24', '2025-10-24 08:43:24', 'Full-time'),
(24, 505, 2, 'Chef de Partie', 'Prepares meals and supervises kitchen operations.', '3+ years experience in professional kitchens.', 1, 0, 'Open', '2025-10-24 08:43:24', '2025-10-24 08:43:24', 'Full-time'),
(25, 506, 3, 'Waiter/Server', 'Serves food and beverages to guests, maintaining quality service.', 'Strong interpersonal skills and teamwork.', 3, 0, 'Open', '2025-10-24 08:43:24', '2025-10-24 08:43:24', 'Part-time');

-- --------------------------------------------------------

--
-- Table structure for table `job_applications`
--

CREATE TABLE `job_applications` (
  `application_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `applicant_id` int(11) NOT NULL,
  `application_date` datetime DEFAULT current_timestamp(),
  `status` enum('Pending','Under Review','Interview','Offered','Hired','Rejected') DEFAULT 'Pending',
  `remarks` text DEFAULT NULL,
  `reviewed_by` varchar(150) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_departments`
--

CREATE TABLE `job_departments` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `job_departments`
--

INSERT INTO `job_departments` (`department_id`, `department_name`, `description`, `created_at`) VALUES
(1, 'Front Office', 'Handles guest services, reservations, and lobby operations.', '2025-10-24 07:45:45'),
(2, 'Food & Beverage', 'Manages restaurant, bar, and kitchen operations.', '2025-10-24 07:45:45'),
(3, 'Housekeeping', 'Responsible for maintaining hotel cleanliness and guest room upkeep.', '2025-10-24 07:45:45'),
(4, 'Front Office', NULL, '2025-10-24 08:40:29'),
(5, 'Kitchen Department', NULL, '2025-10-24 08:40:29'),
(6, 'Restaurant Service', NULL, '2025-10-24 08:40:29');

-- --------------------------------------------------------

--
-- Table structure for table `job_positions`
--

CREATE TABLE `job_positions` (
  `position_id` int(11) NOT NULL,
  `position_title` varchar(150) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `level` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `job_positions`
--

INSERT INTO `job_positions` (`position_id`, `position_title`, `department_id`, `level`, `created_at`) VALUES
(501, 'Front Desk Receptionist', 1, 'Entry Level', '2025-10-24 07:48:40'),
(502, 'Restaurant Server', 2, 'Entry Level', '2025-10-24 07:48:40'),
(503, 'Housekeeping Attendant', 3, 'Entry Level', '2025-10-24 07:48:40'),
(504, 'Front Desk Officer', 1, 'Staff', '2025-10-24 08:40:44'),
(505, 'Chef de Partie', 2, 'Mid-Level', '2025-10-24 08:40:44'),
(506, 'Waiter/Server', 3, 'Staff', '2025-10-24 08:40:44');

-- --------------------------------------------------------

--
-- Table structure for table `recruitment_audit_log`
--

CREATE TABLE `recruitment_audit_log` (
  `log_id` bigint(20) NOT NULL,
  `application_id` int(11) DEFAULT NULL,
  `action` varchar(150) DEFAULT NULL,
  `performed_by` varchar(150) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applicants`
--
ALTER TABLE `applicants`
  ADD PRIMARY KEY (`applicant_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`job_id`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `position_id` (`position_id`);

--
-- Indexes for table `job_applications`
--
ALTER TABLE `job_applications`
  ADD PRIMARY KEY (`application_id`),
  ADD KEY `job_id` (`job_id`),
  ADD KEY `applicant_id` (`applicant_id`);

--
-- Indexes for table `job_departments`
--
ALTER TABLE `job_departments`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `job_positions`
--
ALTER TABLE `job_positions`
  ADD PRIMARY KEY (`position_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `recruitment_audit_log`
--
ALTER TABLE `recruitment_audit_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `application_id` (`application_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applicants`
--
ALTER TABLE `applicants`
  MODIFY `applicant_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `job_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `job_applications`
--
ALTER TABLE `job_applications`
  MODIFY `application_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_departments`
--
ALTER TABLE `job_departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `job_positions`
--
ALTER TABLE `job_positions`
  MODIFY `position_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=507;

--
-- AUTO_INCREMENT for table `recruitment_audit_log`
--
ALTER TABLE `recruitment_audit_log`
  MODIFY `log_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `fk_jobs_department` FOREIGN KEY (`department_id`) REFERENCES `job_departments` (`department_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_jobs_position` FOREIGN KEY (`position_id`) REFERENCES `job_positions` (`position_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `job_applications`
--
ALTER TABLE `job_applications`
  ADD CONSTRAINT `fk_application_applicant` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`applicant_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_application_job` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`job_id`) ON DELETE CASCADE;

--
-- Constraints for table `job_positions`
--
ALTER TABLE `job_positions`
  ADD CONSTRAINT `fk_positions_department` FOREIGN KEY (`department_id`) REFERENCES `job_departments` (`department_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `recruitment_audit_log`
--
ALTER TABLE `recruitment_audit_log`
  ADD CONSTRAINT `fk_recruitment_log` FOREIGN KEY (`application_id`) REFERENCES `job_applications` (`application_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
