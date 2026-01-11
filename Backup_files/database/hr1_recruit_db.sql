-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 23, 2025 at 08:53 AM
-- Server version: 10.11.14-MariaDB-ubu2204
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hr1_recruite_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `Profile_Picture` varchar(255) DEFAULT NULL,
  `Last_Name` varchar(100) DEFAULT NULL,
  `First_Name` varchar(100) DEFAULT NULL,
  `Middle_Initial` varchar(10) DEFAULT NULL,
  `Employee_ID` varchar(50) DEFAULT NULL,
  `Gender` enum('Male','Female','Other') DEFAULT NULL,
  `Birthday` date DEFAULT NULL,
  `Contact_Information` varchar(255) DEFAULT NULL,
  `Email` varchar(150) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `Role` varchar(50) DEFAULT NULL,
  `Phone_Number` varchar(20) DEFAULT NULL,
  `Address` text DEFAULT NULL,
  `Position` varchar(100) DEFAULT NULL,
  `Department` varchar(100) DEFAULT NULL,
  `Date_Hired` date DEFAULT NULL,
  `Employment_Status` enum('Active','Inactive','Resigned','Terminated','Probationary') DEFAULT NULL,
  `Supervisor` varchar(100) DEFAULT NULL,
  `Work_Location` varchar(150) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `user_id`, `Profile_Picture`, `Last_Name`, `First_Name`, `Middle_Initial`, `Employee_ID`, `Gender`, `Birthday`, `Contact_Information`, `Email`, `Password`, `Role`, `Phone_Number`, `Address`, `Position`, `Department`, `Date_Hired`, `Employment_Status`, `Supervisor`, `Work_Location`, `created_at`) VALUES
(1, 1, 'profile_john.png', 'Doe', 'John', 'A', 'EMP-001', 'Male', '1995-04-12', '123 Main Street, Cityville', 'john.doe@example.com', 'hashed_password_123', 'Employee', '09171234567', '123 Main Street, Cityville', 'Software Engineer', 'IT Department', '2025-10-01', 'Active', 'Jane Smith', 'Head Office', '2025-10-23 06:38:47'),
(2, 2, 'profile2.jpg', 'Doe', 'Jane', 'A', 'EMP-0002', 'Female', '1990-05-15', 'jane.doe@example.com, +1234567890', 'jane.doe@example.com', '$2y$10$abcdefghijk1234567890mnopqrstuv', 'Staff', '+1234567890', '456 Maple Street, Springfield', 'Marketing Specialist', 'Marketing', '2024-07-01', 'Active', 'John Smith', 'Head Office', '2025-10-23 07:01:40');

-- --------------------------------------------------------

--
-- Table structure for table `employee_onboarding`
--

CREATE TABLE `employee_onboarding` (
  `onboarding_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `onboarding_status` enum('Not Started','In Progress','Completed','Cancelled') DEFAULT 'Not Started',
  `start_date` date DEFAULT curdate(),
  `expected_completion_date` date DEFAULT NULL,
  `actual_completion_date` date DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `employee_onboarding`
--

INSERT INTO `employee_onboarding` (`onboarding_id`, `employee_id`, `onboarding_status`, `start_date`, `expected_completion_date`, `actual_completion_date`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 1, 'In Progress', '2025-10-01', '2025-10-31', NULL, 'New hire onboarding in progress.', '2025-10-23 06:38:47', '2025-10-23 06:38:47');

-- --------------------------------------------------------

--
-- Table structure for table `employee_onboarding_tasks`
--

CREATE TABLE `employee_onboarding_tasks` (
  `employee_task_id` int(11) NOT NULL,
  `onboarding_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `assigned_date` datetime DEFAULT current_timestamp(),
  `due_date` datetime DEFAULT NULL,
  `completion_date` datetime DEFAULT NULL,
  `status` enum('Assigned','In Progress','Completed','Overdue') DEFAULT 'Assigned',
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `employee_onboarding_tasks`
--

INSERT INTO `employee_onboarding_tasks` (`employee_task_id`, `onboarding_id`, `task_id`, `assigned_date`, `due_date`, `completion_date`, `status`, `remarks`) VALUES
(1, 1, 1, '2025-10-23 06:38:47', '2025-10-04 00:00:00', NULL, 'Completed', NULL),
(2, 1, 2, '2025-10-23 06:38:47', '2025-10-06 00:00:00', NULL, 'In Progress', NULL),
(3, 1, 3, '2025-10-23 06:38:47', '2025-10-10 00:00:00', NULL, 'Assigned', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `onboarding_assets`
--

CREATE TABLE `onboarding_assets` (
  `asset_id` int(11) NOT NULL,
  `onboarding_id` int(11) NOT NULL,
  `asset_name` varchar(150) NOT NULL,
  `asset_tag` varchar(100) DEFAULT NULL,
  `assigned_date` datetime DEFAULT current_timestamp(),
  `return_date` datetime DEFAULT NULL,
  `status` enum('Assigned','Returned','Lost','Damaged') DEFAULT 'Assigned'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `onboarding_assets`
--

INSERT INTO `onboarding_assets` (`asset_id`, `onboarding_id`, `asset_name`, `asset_tag`, `assigned_date`, `return_date`, `status`) VALUES
(1, 1, 'Laptop Dell XPS 13', 'ASSET-IT-1001', '2025-10-23 06:38:47', NULL, 'Assigned');

-- --------------------------------------------------------

--
-- Table structure for table `onboarding_audit_log`
--

CREATE TABLE `onboarding_audit_log` (
  `log_id` bigint(20) NOT NULL,
  `onboarding_id` int(11) DEFAULT NULL,
  `action` varchar(150) DEFAULT NULL,
  `performed_by` varchar(150) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `onboarding_audit_log`
--

INSERT INTO `onboarding_audit_log` (`log_id`, `onboarding_id`, `action`, `performed_by`, `details`, `created_at`) VALUES
(1, 1, 'Task Completed', 'John Doe', 'Completed task: Submit ID Documents', '2025-10-23 06:38:47');

-- --------------------------------------------------------

--
-- Table structure for table `onboarding_documents`
--

CREATE TABLE `onboarding_documents` (
  `document_id` int(11) NOT NULL,
  `onboarding_id` int(11) NOT NULL,
  `document_name` varchar(150) NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `uploaded_at` datetime DEFAULT current_timestamp(),
  `verified_by` varchar(100) DEFAULT NULL,
  `verified_at` datetime DEFAULT NULL,
  `status` enum('Pending','Uploaded','Verified','Rejected') DEFAULT 'Pending',
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `onboarding_documents`
--

INSERT INTO `onboarding_documents` (`document_id`, `onboarding_id`, `document_name`, `file_path`, `uploaded_at`, `verified_by`, `verified_at`, `status`, `remarks`) VALUES
(1, 1, 'Government ID', '/uploads/docs/john_doe_id.png', '2025-10-23 06:38:47', NULL, NULL, 'Verified', NULL),
(2, 1, 'Signed Contract', '/uploads/docs/john_contract.pdf', '2025-10-23 06:38:47', NULL, NULL, 'Uploaded', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `onboarding_feedback`
--

CREATE TABLE `onboarding_feedback` (
  `feedback_id` int(11) NOT NULL,
  `onboarding_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comments` text DEFAULT NULL,
  `submitted_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `onboarding_feedback`
--

INSERT INTO `onboarding_feedback` (`feedback_id`, `onboarding_id`, `rating`, `comments`, `submitted_at`) VALUES
(1, 1, 5, 'Smooth onboarding process and clear instructions.', '2025-10-23 06:38:47');

-- --------------------------------------------------------

--
-- Table structure for table `onboarding_notes`
--

CREATE TABLE `onboarding_notes` (
  `note_id` int(11) NOT NULL,
  `onboarding_id` int(11) NOT NULL,
  `author_name` varchar(150) DEFAULT NULL,
  `note_text` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `onboarding_notes`
--

INSERT INTO `onboarding_notes` (`note_id`, `onboarding_id`, `author_name`, `note_text`, `created_at`) VALUES
(1, 1, 'Jane Smith', 'John is progressing well in his onboarding tasks.', '2025-10-23 06:38:47');

-- --------------------------------------------------------

--
-- Table structure for table `onboarding_tasks`
--

CREATE TABLE `onboarding_tasks` (
  `task_id` int(11) NOT NULL,
  `task_name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `category` enum('Administrative','Orientation','Training','Compliance','Other') DEFAULT 'Other',
  `expected_completion_days` int(11) DEFAULT 7,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `onboarding_tasks`
--

INSERT INTO `onboarding_tasks` (`task_id`, `task_name`, `description`, `category`, `expected_completion_days`, `is_active`, `created_at`) VALUES
(1, 'Submit ID Documents', 'Employee must submit government-issued IDs.', 'Administrative', 3, 1, '2025-10-23 06:38:47'),
(2, 'Company Orientation', 'Attend company-wide orientation session.', 'Orientation', 5, 1, '2025-10-23 06:38:47'),
(3, 'Complete IT Setup', 'Setup email, Slack, and developer tools.', 'Training', 7, 1, '2025-10-23 06:38:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Employee_ID` (`Employee_ID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `employee_onboarding`
--
ALTER TABLE `employee_onboarding`
  ADD PRIMARY KEY (`onboarding_id`),
  ADD KEY `fk_employee` (`employee_id`),
  ADD KEY `idx_onboarding_status` (`onboarding_status`);

--
-- Indexes for table `employee_onboarding_tasks`
--
ALTER TABLE `employee_onboarding_tasks`
  ADD PRIMARY KEY (`employee_task_id`),
  ADD KEY `onboarding_id` (`onboarding_id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `idx_task_status` (`status`);

--
-- Indexes for table `onboarding_assets`
--
ALTER TABLE `onboarding_assets`
  ADD PRIMARY KEY (`asset_id`),
  ADD KEY `onboarding_id` (`onboarding_id`);

--
-- Indexes for table `onboarding_audit_log`
--
ALTER TABLE `onboarding_audit_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `onboarding_id` (`onboarding_id`);

--
-- Indexes for table `onboarding_documents`
--
ALTER TABLE `onboarding_documents`
  ADD PRIMARY KEY (`document_id`),
  ADD KEY `onboarding_id` (`onboarding_id`),
  ADD KEY `idx_document_status` (`status`);

--
-- Indexes for table `onboarding_feedback`
--
ALTER TABLE `onboarding_feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `onboarding_id` (`onboarding_id`);

--
-- Indexes for table `onboarding_notes`
--
ALTER TABLE `onboarding_notes`
  ADD PRIMARY KEY (`note_id`),
  ADD KEY `onboarding_id` (`onboarding_id`);

--
-- Indexes for table `onboarding_tasks`
--
ALTER TABLE `onboarding_tasks`
  ADD PRIMARY KEY (`task_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `employee_onboarding`
--
ALTER TABLE `employee_onboarding`
  MODIFY `onboarding_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employee_onboarding_tasks`
--
ALTER TABLE `employee_onboarding_tasks`
  MODIFY `employee_task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `onboarding_assets`
--
ALTER TABLE `onboarding_assets`
  MODIFY `asset_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `onboarding_audit_log`
--
ALTER TABLE `onboarding_audit_log`
  MODIFY `log_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `onboarding_documents`
--
ALTER TABLE `onboarding_documents`
  MODIFY `document_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `onboarding_feedback`
--
ALTER TABLE `onboarding_feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `onboarding_notes`
--
ALTER TABLE `onboarding_notes`
  MODIFY `note_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `onboarding_tasks`
--
ALTER TABLE `onboarding_tasks`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employee_onboarding`
--
ALTER TABLE `employee_onboarding`
  ADD CONSTRAINT `fk_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_onboarding_tasks`
--
ALTER TABLE `employee_onboarding_tasks`
  ADD CONSTRAINT `employee_onboarding_tasks_ibfk_1` FOREIGN KEY (`onboarding_id`) REFERENCES `employee_onboarding` (`onboarding_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `employee_onboarding_tasks_ibfk_2` FOREIGN KEY (`task_id`) REFERENCES `onboarding_tasks` (`task_id`) ON DELETE CASCADE;

--
-- Constraints for table `onboarding_assets`
--
ALTER TABLE `onboarding_assets`
  ADD CONSTRAINT `onboarding_assets_ibfk_1` FOREIGN KEY (`onboarding_id`) REFERENCES `employee_onboarding` (`onboarding_id`) ON DELETE CASCADE;

--
-- Constraints for table `onboarding_audit_log`
--
ALTER TABLE `onboarding_audit_log`
  ADD CONSTRAINT `onboarding_audit_log_ibfk_1` FOREIGN KEY (`onboarding_id`) REFERENCES `employee_onboarding` (`onboarding_id`) ON DELETE SET NULL;

--
-- Constraints for table `onboarding_documents`
--
ALTER TABLE `onboarding_documents`
  ADD CONSTRAINT `onboarding_documents_ibfk_1` FOREIGN KEY (`onboarding_id`) REFERENCES `employee_onboarding` (`onboarding_id`) ON DELETE CASCADE;

--
-- Constraints for table `onboarding_feedback`
--
ALTER TABLE `onboarding_feedback`
  ADD CONSTRAINT `onboarding_feedback_ibfk_1` FOREIGN KEY (`onboarding_id`) REFERENCES `employee_onboarding` (`onboarding_id`) ON DELETE CASCADE;

--
-- Constraints for table `onboarding_notes`
--
ALTER TABLE `onboarding_notes`
  ADD CONSTRAINT `onboarding_notes_ibfk_1` FOREIGN KEY (`onboarding_id`) REFERENCES `employee_onboarding` (`onboarding_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
