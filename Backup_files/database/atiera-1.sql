-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 07, 2025 at 09:55 PM
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
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `resume_path` varchar(255) DEFAULT NULL,
  `application_date` date DEFAULT NULL,
  `status` enum('In Progress','Hired','Rejected') DEFAULT 'In Progress'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applicants`
--

INSERT INTO `applicants` (`applicant_id`, `first_name`, `last_name`, `email`, `phone`, `resume_path`, `application_date`, `status`) VALUES
(1, 'James', 'Vergara', 'james@email.com', '09944967965', 'uploads/1757977880_HR1 SYSTEM PROCESS.pdf', '2025-09-15', ''),
(2, 'James', 'Sabandal', 'test@email.com', '1231231231', 'uploads/1757981011_HR1 SYSTEM PROCESS.pdf', '2025-09-15', '');

-- --------------------------------------------------------

--
-- Table structure for table `applicant_assessments`
--

CREATE TABLE `applicant_assessments` (
  `assessment_id` int(11) NOT NULL,
  `applicant_id` int(11) NOT NULL,
  `type` enum('Technical','Aptitude','Psychometric') NOT NULL,
  `score` decimal(5,2) DEFAULT NULL,
  `result` enum('Pass','Fail') DEFAULT 'Fail'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `applicant_communication`
--

CREATE TABLE `applicant_communication` (
  `message_id` int(11) NOT NULL,
  `applicant_id` int(11) NOT NULL,
  `sent_by` varchar(50) DEFAULT NULL,
  `message_type` enum('Email','SMS') NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `body` text DEFAULT NULL,
  `sent_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `application_id` int(11) NOT NULL,
  `applicant_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `stage` enum('Screening','Interview','Offer','Hired','Rejected') DEFAULT 'Screening',
  `status` enum('Active','Hired','Rejected') DEFAULT 'Active',
  `applied_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`application_id`, `applicant_id`, `job_id`, `stage`, `status`, `applied_date`) VALUES
(1, 1, 3, 'Screening', 'Active', '2025-09-15 23:11:20'),
(2, 2, 3, 'Screening', 'Active', '2025-09-16 00:03:31'),
(3, 2, 3, 'Screening', '', '2025-09-17 01:25:36'),
(4, 2, 3, 'Screening', '', '2025-09-17 01:27:16');

--
-- Triggers `applications`
--
DELIMITER $$
CREATE TRIGGER `trg_update_filled_positions` AFTER UPDATE ON `applications` FOR EACH ROW BEGIN
    -- When an application is marked as Hired
    IF NEW.stage = 'Hired' AND NEW.status = 'Hired' THEN
        UPDATE jobs
        SET filled_positions = filled_positions + 1
        WHERE job_id = NEW.job_id;

        -- Close job automatically if vacancies are filled
        UPDATE jobs
        SET status = 'Closed'
        WHERE job_id = NEW.job_id
          AND filled_positions >= vacancies;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `appraisals`
--

CREATE TABLE `appraisals` (
  `appraisal_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `reviewer_id` int(11) NOT NULL,
  `period_start` date DEFAULT NULL,
  `period_end` date DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT NULL,
  `comments` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `manager_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `name`, `manager_id`) VALUES
(1, 'Human Resources', NULL),
(2, 'Accounting Department', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employee_id` int(11) NOT NULL,
  `applicant_id` int(11) DEFAULT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `job_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `status` enum('Active','Inactive','Resigned') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employee_id`, `applicant_id`, `first_name`, `last_name`, `email`, `phone`, `hire_date`, `job_id`, `department_id`, `status`) VALUES
(1, NULL, 'System', 'Admin', 'admin@example.com', NULL, '2025-09-14', NULL, NULL, 'Active'),
(2, NULL, 'HR', 'Officer', 'hr@example.com', NULL, '2025-09-14', NULL, NULL, 'Active'),
(3, NULL, 'General', 'Manager', 'manager@example.com', NULL, '2025-09-14', NULL, NULL, 'Active'),
(4, NULL, 'Staff', 'Employee', 'employee@example.com', NULL, '2025-09-14', NULL, NULL, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `given_by` int(11) NOT NULL,
  `feedback_text` text DEFAULT NULL,
  `feedback_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `goals`
--

CREATE TABLE `goals` (
  `goal_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `goal_description` text DEFAULT NULL,
  `kpi` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('In Progress','Completed') DEFAULT 'In Progress'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `interviews`
--

CREATE TABLE `interviews` (
  `interview_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `interviewer_id` int(11) DEFAULT NULL,
  `schedule_date` datetime DEFAULT NULL,
  `result` enum('Pending','Passed','Failed') DEFAULT 'Pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `interviews`
--

INSERT INTO `interviews` (`interview_id`, `application_id`, `interviewer_id`, `schedule_date`, `result`, `notes`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, '2025-10-10 18:03:31', 'Pending', NULL, '2025-09-16 00:03:31', '2025-09-16 00:03:31'),
(2, 3, NULL, '2025-09-26 03:25:36', 'Pending', NULL, '2025-09-17 01:25:36', '2025-09-17 01:25:36'),
(3, 4, NULL, '2025-10-12 03:27:16', 'Pending', NULL, '2025-09-17 01:27:16', '2025-09-17 01:27:16');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `job_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `employment_type` enum('Full-time','Part-time','Contract') NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `status` enum('Open','Closed') DEFAULT 'Open',
  `vacancies` int(11) NOT NULL DEFAULT 1,
  `filled_positions` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`job_id`, `title`, `department_id`, `description`, `employment_type`, `created_by`, `status`, `vacancies`, `filled_positions`, `created_at`) VALUES
(1, 'HR Specialist', 1, 'Handles HasdasdasR functions', 'Full-time', NULL, 'Open', 5, 0, '2025-09-14 05:05:49'),
(2, 'Cashier', 2, 'Handles front desks', 'Full-time', 1, 'Open', 3, 0, '2025-09-15 20:47:13'),
(3, 'Waiter', 1, 'Patient and Good at serving customers', 'Full-time', 1, 'Open', 5, 0, '2025-09-15 20:51:28'),
(4, 'Janitor', 1, 'Handle the cleanliness of the facilities', 'Full-time', 1, 'Closed', 10, 0, '2025-09-15 21:57:56'),
(5, 'Department Manager', 1, 'About Us:\r\nWe are a leading hotel and restaurant group dedicated to delivering exceptional guest experiences. With a culture built on teamwork, innovation, and integrity, we pride ourselves on creating an environment where both our employees and guests thrive.\r\n\r\nResponsibilities:\r\nOversee daily operations to ensure efficiency and high service standards.\r\n\r\nLead, mentor, and develop a team of department staff.\r\n\r\nManage budgets, financial reports, and performance metrics.\r\n\r\nCoordinate with other departments to ensure smooth workflow and guest satisfaction.\r\n\r\nHandle customer concerns and resolve issues in a professional manner.\r\n\r\nQualifications:\r\nBachelor’s degree in Business Administration, Hospitality Management, or a related field.\r\n\r\n5+ years of management experience, preferably in the hotel or restaurant industry.\r\n\r\nStrong leadership, organizational, and communication skills.\r\n\r\nAbility to manage multiple priorities in a fast-paced environment.\r\n\r\nProficiency in MS Office and basic financial management.\r\n\r\nBenefits:\r\nCompetitive salary and performance-based bonuses.\r\n\r\nHealth and wellness benefits package.\r\n\r\nEmployee discounts on hotel and restaurant services.\r\n\r\nOpportunities for career growth and professional development.\r\n\r\nPaid vacation and holidays.\r\n\r\nHow to Apply:\r\nInterested candidates should click the Apply Now button to complete the application form and upload their resume.', 'Full-time', 1, 'Open', 11, 0, '2025-10-07 10:43:46');

-- --------------------------------------------------------

--
-- Stand-in structure for view `job_hiring_progress_view`
-- (See below for the actual view)
--
CREATE TABLE `job_hiring_progress_view` (
`job_id` int(11)
,`title` varchar(150)
,`department_name` varchar(100)
,`vacancies` int(11)
,`filled_positions` int(11)
,`progress_percentage` decimal(16,2)
,`status` enum('Open','Closed')
);

-- --------------------------------------------------------

--
-- Table structure for table `job_requisitions`
--

CREATE TABLE `job_requisitions` (
  `requisition_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `requested_by` int(11) NOT NULL,
  `approval_status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `open_date` date DEFAULT NULL,
  `close_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_tbl`
--

CREATE TABLE `login_tbl` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `account_type` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_tbl`
--

INSERT INTO `login_tbl` (`id`, `email`, `password`, `account_type`, `created_at`) VALUES
(1, 'admin@hr1.com', '$2y$10$WzZzhY6B6a4FczUFeZTo7emNnkW/7z6pTQItBQbKAd3SfEzzjGd0S', 'admin', '2025-10-07 12:39:06');

-- --------------------------------------------------------

--
-- Table structure for table `onboarding_documents`
--

CREATE TABLE `onboarding_documents` (
  `document_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `doc_type` varchar(100) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `verified` enum('Yes','No') DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `onboarding_tasks`
--

CREATE TABLE `onboarding_tasks` (
  `task_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `task_name` varchar(255) NOT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `status` enum('Pending','Completed') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `open_jobs_view`
-- (See below for the actual view)
--
CREATE TABLE `open_jobs_view` (
`job_id` int(11)
,`title` varchar(150)
,`department_name` varchar(100)
,`employment_type` enum('Full-time','Part-time','Contract')
,`vacancies` int(11)
,`filled_positions` int(11)
,`available_slots` bigint(12)
,`status` enum('Open','Closed')
,`created_at` timestamp
);

-- --------------------------------------------------------

--
-- Table structure for table `recognition`
--

CREATE TABLE `recognition` (
  `recognition_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `given_by` int(11) NOT NULL,
  `recognition_type` enum('Peer','Manager','Award') NOT NULL,
  `message` text DEFAULT NULL,
  `date_given` timestamp NOT NULL DEFAULT current_timestamp(),
  `points_awarded` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rewards`
--

CREATE TABLE `rewards` (
  `reward_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `reward_name` varchar(100) DEFAULT NULL,
  `points_used` int(11) DEFAULT NULL,
  `date_redeemed` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('Admin','HR','Manager','Employee') DEFAULT 'Employee',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `employee_id`, `username`, `password_hash`, `role`, `created_at`) VALUES
(1, 0, 'admin_user', '$2y$10$e9pM..89n9mDy1BPQ.5RuO7mZP5S8iK0s6pTgDacBRpt4pPVb9Ui2', 'Admin', '2025-09-14 22:14:12'),
(2, 0, 'hr_user', '$2y$10$1LqZgHOPQz6nP.5lmUy1fOkINZPnZX2AGhOBh4nTH4xNSVZrwnu2K', 'HR', '2025-09-14 22:14:12'),
(3, 0, 'manager_user', '$2y$10$R6wEIrG1u.1Tw1D93uv7uONLo1a5D4XEqjB4hFUG3GsmHYcCLkwiG', 'Manager', '2025-09-14 22:14:12'),
(4, 0, 'employee_user', '$2y$10$JdeQ.Cu7MKkUZYy/hQMyUehZK42J6lHp45rj.CZLMtV3G4I6F0Gte', 'Employee', '2025-09-14 22:14:12');

-- --------------------------------------------------------

--
-- Structure for view `job_hiring_progress_view`
--
DROP TABLE IF EXISTS `job_hiring_progress_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `job_hiring_progress_view`  AS SELECT `j`.`job_id` AS `job_id`, `j`.`title` AS `title`, `d`.`name` AS `department_name`, `j`.`vacancies` AS `vacancies`, `j`.`filled_positions` AS `filled_positions`, round(`j`.`filled_positions` / `j`.`vacancies` * 100,2) AS `progress_percentage`, `j`.`status` AS `status` FROM (`jobs` `j` left join `departments` `d` on(`j`.`department_id` = `d`.`department_id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `open_jobs_view`
--
DROP TABLE IF EXISTS `open_jobs_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `open_jobs_view`  AS SELECT `j`.`job_id` AS `job_id`, `j`.`title` AS `title`, `d`.`name` AS `department_name`, `j`.`employment_type` AS `employment_type`, `j`.`vacancies` AS `vacancies`, `j`.`filled_positions` AS `filled_positions`, `j`.`vacancies`- `j`.`filled_positions` AS `available_slots`, `j`.`status` AS `status`, `j`.`created_at` AS `created_at` FROM (`jobs` `j` left join `departments` `d` on(`j`.`department_id` = `d`.`department_id`)) WHERE `j`.`status` = 'Open' AND `j`.`vacancies` - `j`.`filled_positions` > 0 ;

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
-- Indexes for table `applicant_assessments`
--
ALTER TABLE `applicant_assessments`
  ADD PRIMARY KEY (`assessment_id`),
  ADD KEY `applicant_id` (`applicant_id`);

--
-- Indexes for table `applicant_communication`
--
ALTER TABLE `applicant_communication`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `applicant_id` (`applicant_id`);

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`application_id`),
  ADD KEY `applicant_id` (`applicant_id`),
  ADD KEY `job_id` (`job_id`);

--
-- Indexes for table `appraisals`
--
ALTER TABLE `appraisals`
  ADD PRIMARY KEY (`appraisal_id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `reviewer_id` (`reviewer_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `job_id` (`job_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `given_by` (`given_by`);

--
-- Indexes for table `goals`
--
ALTER TABLE `goals`
  ADD PRIMARY KEY (`goal_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `interviews`
--
ALTER TABLE `interviews`
  ADD PRIMARY KEY (`interview_id`),
  ADD KEY `application_id` (`application_id`),
  ADD KEY `interviewer_id` (`interviewer_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`job_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `job_requisitions`
--
ALTER TABLE `job_requisitions`
  ADD PRIMARY KEY (`requisition_id`),
  ADD KEY `job_id` (`job_id`),
  ADD KEY `requested_by` (`requested_by`);

--
-- Indexes for table `login_tbl`
--
ALTER TABLE `login_tbl`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `onboarding_documents`
--
ALTER TABLE `onboarding_documents`
  ADD PRIMARY KEY (`document_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `onboarding_tasks`
--
ALTER TABLE `onboarding_tasks`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `assigned_to` (`assigned_to`);

--
-- Indexes for table `recognition`
--
ALTER TABLE `recognition`
  ADD PRIMARY KEY (`recognition_id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `given_by` (`given_by`);

--
-- Indexes for table `rewards`
--
ALTER TABLE `rewards`
  ADD PRIMARY KEY (`reward_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `employee_id` (`employee_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applicants`
--
ALTER TABLE `applicants`
  MODIFY `applicant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `applicant_assessments`
--
ALTER TABLE `applicant_assessments`
  MODIFY `assessment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `applicant_communication`
--
ALTER TABLE `applicant_communication`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `application_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `appraisals`
--
ALTER TABLE `appraisals`
  MODIFY `appraisal_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `goals`
--
ALTER TABLE `goals`
  MODIFY `goal_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `interviews`
--
ALTER TABLE `interviews`
  MODIFY `interview_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `job_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `job_requisitions`
--
ALTER TABLE `job_requisitions`
  MODIFY `requisition_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login_tbl`
--
ALTER TABLE `login_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `onboarding_documents`
--
ALTER TABLE `onboarding_documents`
  MODIFY `document_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `onboarding_tasks`
--
ALTER TABLE `onboarding_tasks`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recognition`
--
ALTER TABLE `recognition`
  MODIFY `recognition_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rewards`
--
ALTER TABLE `rewards`
  MODIFY `reward_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applicant_assessments`
--
ALTER TABLE `applicant_assessments`
  ADD CONSTRAINT `applicant_assessments_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`applicant_id`);

--
-- Constraints for table `applicant_communication`
--
ALTER TABLE `applicant_communication`
  ADD CONSTRAINT `applicant_communication_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`applicant_id`);

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`applicant_id`),
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`job_id`);

--
-- Constraints for table `appraisals`
--
ALTER TABLE `appraisals`
  ADD CONSTRAINT `appraisals_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`),
  ADD CONSTRAINT `appraisals_ibfk_2` FOREIGN KEY (`reviewer_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`job_id`),
  ADD CONSTRAINT `employees_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`);

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`),
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`given_by`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `goals`
--
ALTER TABLE `goals`
  ADD CONSTRAINT `goals_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `interviews`
--
ALTER TABLE `interviews`
  ADD CONSTRAINT `interviews_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `applications` (`application_id`),
  ADD CONSTRAINT `interviews_ibfk_2` FOREIGN KEY (`interviewer_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`);

--
-- Constraints for table `job_requisitions`
--
ALTER TABLE `job_requisitions`
  ADD CONSTRAINT `job_requisitions_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`job_id`),
  ADD CONSTRAINT `job_requisitions_ibfk_2` FOREIGN KEY (`requested_by`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `onboarding_documents`
--
ALTER TABLE `onboarding_documents`
  ADD CONSTRAINT `onboarding_documents_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `onboarding_tasks`
--
ALTER TABLE `onboarding_tasks`
  ADD CONSTRAINT `onboarding_tasks_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`),
  ADD CONSTRAINT `onboarding_tasks_ibfk_2` FOREIGN KEY (`assigned_to`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `recognition`
--
ALTER TABLE `recognition`
  ADD CONSTRAINT `recognition_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`),
  ADD CONSTRAINT `recognition_ibfk_2` FOREIGN KEY (`given_by`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `rewards`
--
ALTER TABLE `rewards`
  ADD CONSTRAINT `rewards_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
