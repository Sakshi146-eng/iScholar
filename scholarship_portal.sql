-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 08, 2025 at 07:29 AM
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
-- Database: `scholarship_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'Sakshi', '$2y$10$PDQnIwO3dV0SclItsXQAl.gvddDB8Ma8FPpiyUjyPGnjs4DDsTe66', '2025-05-19 09:36:42');

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `app_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `scholarship_id` int(11) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `applied_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`app_id`, `student_id`, `scholarship_id`, `status`, `applied_on`) VALUES
(3, 1, 5, 'Pending', '2025-05-24 11:25:34'),
(4, 1, 7, 'Pending', '2025-05-28 04:49:50');

-- --------------------------------------------------------

--
-- Table structure for table `application_responses`
--

CREATE TABLE `application_responses` (
  `id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `requirement_id` int(11) NOT NULL,
  `value` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_responses`
--

INSERT INTO `application_responses` (`id`, `application_id`, `requirement_id`, `value`, `file_path`) VALUES
(3, 3, 5, NULL, 'uploads/file_6831acae916b5_Screenshot 2025-05-24 163350.png'),
(4, 4, 8, NULL, 'uploads/file_683695eec7653_Screenshot 2025-05-12 183642.png');

-- --------------------------------------------------------

--
-- Table structure for table `scholarships`
--

CREATE TABLE `scholarships` (
  `scholarship_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `min_gpa` float DEFAULT NULL,
  `max_income` decimal(10,2) DEFAULT NULL,
  `category` varchar(20) DEFAULT NULL,
  `caste` varchar(50) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `last_date` date DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `scholarships`
--

INSERT INTO `scholarships` (`scholarship_id`, `name`, `type`, `min_gpa`, `max_income`, `category`, `caste`, `state`, `last_date`, `description`) VALUES
(5, 'SSP', 'Merit', 9, 100000.00, 'GEN', 'All', 'Karnataka', '2025-05-25', 'For Karnataka Undergraduate Students'),
(6, 'Kotak Kanya', 'Merit', 9, 100000.00, 'All', 'All', 'All', '2025-05-24', 'For meritious girl students'),
(7, 'Reliance Foundatio Scholarship', 'Merit', 9, 100000.00, 'All', 'All', 'All', '2025-05-29', 'For undergraduate students');

-- --------------------------------------------------------

--
-- Table structure for table `scholarship_requirements`
--

CREATE TABLE `scholarship_requirements` (
  `id` int(11) NOT NULL,
  `scholarship_id` int(11) NOT NULL,
  `field_label` varchar(255) NOT NULL,
  `field_type` enum('text','textarea','number','file') NOT NULL,
  `is_required` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `scholarship_requirements`
--

INSERT INTO `scholarship_requirements` (`id`, `scholarship_id`, `field_label`, `field_type`, `is_required`) VALUES
(5, 5, 'Caste Certificate', 'file', 1),
(7, 6, 'Income Certificate', 'file', 1),
(8, 7, 'Income Certificate', 'file', 1);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `aadhaar` varchar(12) DEFAULT NULL,
  `course` varchar(100) DEFAULT NULL,
  `gpa` float DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `income` decimal(10,2) DEFAULT NULL,
  `category` varchar(20) DEFAULT NULL,
  `caste` varchar(50) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `name`, `email`, `phone`, `dob`, `gender`, `aadhaar`, `course`, `gpa`, `year`, `income`, `category`, `caste`, `state`, `password`) VALUES
(1, 'Sakshi Shetty', 'sakshishetty146@gmail.com', '8660346308', '2005-05-14', 'Female', '123456', 'ISE', 9.9, 2, 70000.00, 'OBC', '3B', 'Karnataka', '$2y$10$tscbcn0O/hzQksi8HhhJguxlKFlnaS8W9UHTNPe2U.m37V4APiN4q');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`app_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `scholarship_id` (`scholarship_id`);

--
-- Indexes for table `application_responses`
--
ALTER TABLE `application_responses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`),
  ADD KEY `requirement_id` (`requirement_id`);

--
-- Indexes for table `scholarships`
--
ALTER TABLE `scholarships`
  ADD PRIMARY KEY (`scholarship_id`);

--
-- Indexes for table `scholarship_requirements`
--
ALTER TABLE `scholarship_requirements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `scholarship_id` (`scholarship_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `app_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `application_responses`
--
ALTER TABLE `application_responses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `scholarships`
--
ALTER TABLE `scholarships`
  MODIFY `scholarship_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `scholarship_requirements`
--
ALTER TABLE `scholarship_requirements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`scholarship_id`) REFERENCES `scholarships` (`scholarship_id`);

--
-- Constraints for table `application_responses`
--
ALTER TABLE `application_responses`
  ADD CONSTRAINT `application_responses_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `applications` (`app_id`),
  ADD CONSTRAINT `application_responses_ibfk_2` FOREIGN KEY (`requirement_id`) REFERENCES `scholarship_requirements` (`id`);

--
-- Constraints for table `scholarship_requirements`
--
ALTER TABLE `scholarship_requirements`
  ADD CONSTRAINT `scholarship_requirements_ibfk_1` FOREIGN KEY (`scholarship_id`) REFERENCES `scholarships` (`scholarship_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
