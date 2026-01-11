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
-- Database: `atiera`
--

-- --------------------------------------------------------

--
-- Table structure for table `applicants`
--

CREATE TABLE `applicants` (
  `applicant_id` int(11) NOT NULL,
  `applicant_code` varchar(30) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `middle_initial` char(1) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone_number` varchar(50) DEFAULT NULL,
  `contact_information` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `job_position` varchar(100) DEFAULT NULL,
  `resume_file` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `department_name`) VALUES
(1, 'Front Office'),
(2, 'Food & Beverage'),
(3, 'Housekeeping'),
(4, 'Human Resources'),
(5, 'Engineering');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `job_id` int(11) NOT NULL,
  `position_id` int(11) DEFAULT 0,
  `department_id` int(11) DEFAULT NULL,
  `job_title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `requirements` text DEFAULT NULL,
  `vacancies` int(11) DEFAULT NULL,
  `filled_positions` int(11) DEFAULT 0,
  `status` varchar(50) DEFAULT NULL,
  `employment_type` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`job_id`, `position_id`, `department_id`, `job_title`, `description`, `requirements`, `vacancies`, `filled_positions`, `status`, `employment_type`, `created_at`, `updated_at`) VALUES
(1, 101, 1, 'Front Desk Receptionist', 'Responsible for guest check-in, check-out, and ensuring a welcoming front desk experience.', 'Excellent communication skills, hospitality experience preferred, knowledge of booking software.', 3, 1, 'Open', 'Full-Time', '2025-11-12 22:27:31', '2025-11-12 22:27:31'),
(2, 102, 2, 'Sous Chef', 'Assist the Head Chef in managing kitchen operations, food quality, and staff supervision.', 'Culinary degree or 3+ years experience in hotel kitchen required.', 2, 0, 'Open', 'Full-Time', '2025-11-12 22:27:31', '2025-11-12 22:27:31'),
(3, 103, 3, 'Housekeeping Supervisor', 'Ensure rooms and public areas meet Atiera’s cleanliness and service standards.', 'Previous supervisory experience in housekeeping, strong attention to detail.', 4, 2, 'Closed', 'Full-Time', '2025-11-12 22:27:31', '2025-11-12 22:27:31');

-- --------------------------------------------------------

--
-- Table structure for table `job_requisitions`
--

CREATE TABLE `job_requisitions` (
  `request_id` int(11) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `job_title` varchar(255) DEFAULT NULL,
  `requester_name` varchar(100) DEFAULT NULL,
  `vacancies` int(11) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_requisitions`
--

INSERT INTO `job_requisitions` (`request_id`, `department_id`, `job_title`, `requester_name`, `vacancies`, `reason`, `status`, `created_at`) VALUES
(1, 4, 'Event Coordinator', 'Maria Lopez', 2, 'Needed for upcoming Q4 events and brand promotions.', 'Pending', '2025-11-12 22:27:51'),
(2, 2, 'Barista', 'John Cruz', 3, 'Additional staff required for weekend café operations.', 'Pending', '2025-11-12 22:27:51'),
(3, 5, 'Maintenance Staff', 'Carla Dizon', 5, 'Urgent manpower required for facility expansion project.', 'Declined', '2025-11-12 22:27:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applicants`
--
ALTER TABLE `applicants`
  ADD PRIMARY KEY (`applicant_id`),
  ADD UNIQUE KEY `applicant_code` (`applicant_code`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`job_id`);

--
-- Indexes for table `job_requisitions`
--
ALTER TABLE `job_requisitions`
  ADD PRIMARY KEY (`request_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applicants`
--
ALTER TABLE `applicants`
  MODIFY `applicant_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `job_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `job_requisitions`
--
ALTER TABLE `job_requisitions`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
