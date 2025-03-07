-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 07, 2025 at 07:47 AM
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
-- Database: `student_admin_login`
--

-- --------------------------------------------------------

--
-- Table structure for table `about_hostels`
--

CREATE TABLE `about_hostels` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `about_hostels`
--

INSERT INTO `about_hostels` (`id`, `title`, `content`) VALUES
(1, 'About the Hostels', 'The college has five hostels: Ennagam, Joachim Villa, Papa Duhayon Hostel (for boys), Arockia Annai Hostel (for girls), and Mamma Margret (for PG girls). The hostels accommodate nearly 500 students and offer various facilities for study, recreation, and common living.'),
(2, 'Mess Information', 'Savio Mess serves subsidized meals for hostel inmates and guests. Day Scholars who are unable to bring their lunch are provided with a subsidized Mid-Day meal at Savio Mess.');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(2, 'admin143', '143'),
(3, 'Nishanth', 'B22134'),
(9, 'admin', '$2y$10$Q9bXqZ/JLZ9ZPDRIX.sZteJYaK7/NxA5Oof9EM8sQoOFlDxNTYxse');

-- --------------------------------------------------------

--
-- Table structure for table `admin_responses`
--

CREATE TABLE `admin_responses` (
  `id` int(11) NOT NULL,
  `request_id` int(11) DEFAULT NULL,
  `response` text DEFAULT NULL,
  `response_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_responses`
--

INSERT INTO `admin_responses` (`id`, `request_id`, `response`, `response_date`) VALUES
(1, 1, 'Ok i will buy the things and give it to you..', '2025-02-26 13:02:09'),
(2, 2, 'OK i will buy', '2025-02-26 13:03:45'),
(3, 4, 'Ok thanks', '2025-03-01 07:15:41'),
(4, 4, 'Ok thanks', '2025-03-01 07:18:08'),
(5, 4, 'hi', '2025-03-06 12:44:05'),
(6, 4, 'hi', '2025-03-06 12:44:07'),
(7, 4, 'hi', '2025-03-06 12:44:21');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `status` enum('Present','Absent') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `student_id`, `date`, `status`) VALUES
(1, 'STU1001', '2025-03-01', 'Present'),
(2, 'STU1002', '2025-03-01', 'Absent'),
(3, 'STU1003', '2025-03-01', 'Present'),
(4, 'STU1001', '2025-03-05', 'Absent'),
(5, 'STU1002', '2025-03-05', 'Present'),
(6, 'STU1003', '2025-03-05', 'Present'),
(7, 'STU1001', '2025-02-27', 'Absent'),
(8, 'STU1002', '2025-02-27', 'Present'),
(9, 'STU1003', '2025-02-27', 'Absent'),
(10, 'STU1001', '2025-03-12', 'Absent'),
(11, 'STU1002', '2025-03-12', 'Absent'),
(12, 'STU1003', '2025-03-12', 'Present'),
(13, 'STU1001', '2025-03-12', 'Absent'),
(14, 'STU1002', '2025-03-12', 'Absent'),
(15, 'STU1003', '2025-03-12', 'Present'),
(16, 'STU1001', '2025-03-01', 'Present'),
(17, 'STU2001', '2025-03-01', 'Present'),
(18, 'STU1003', '2025-03-02', 'Present'),
(19, 'B22108', '2025-03-02', 'Present'),
(20, 'STU1003', '2025-03-03', 'Present'),
(21, 'B22108', '2025-03-03', 'Absent'),
(22, 'STU1003', '2025-03-03', 'Present'),
(23, 'B22108', '2025-03-03', 'Absent'),
(24, 'B22134', '2025-03-02', 'Present'),
(25, 'STU1003', '2025-03-07', 'Present'),
(26, 'B22108', '2025-03-07', 'Present'),
(27, 'B22134', '2025-03-07', 'Present'),
(28, 'B22101', '2025-03-07', 'Present'),
(29, 'B22102', '2025-03-07', 'Present'),
(30, 'B22103', '2025-03-07', 'Present'),
(31, 'B22104', '2025-03-07', 'Present'),
(32, 'B22105', '2025-03-07', 'Present'),
(33, 'B22106', '2025-03-07', 'Present'),
(34, 'B22107', '2025-03-07', 'Present'),
(35, 'B22109', '2025-03-07', 'Present'),
(36, 'B22110', '2025-03-07', 'Present'),
(37, 'B22111', '2025-03-07', 'Present'),
(38, 'B22112', '2025-03-07', 'Present'),
(39, 'B22113', '2025-03-07', 'Present'),
(40, 'B22114', '2025-03-07', 'Present'),
(41, 'B22115', '2025-03-07', 'Present'),
(42, 'B22116', '2025-03-07', 'Present'),
(43, 'B22117', '2025-03-07', 'Present'),
(44, 'B22118', '2025-03-07', 'Present'),
(45, 'B22119', '2025-03-07', 'Present'),
(46, 'B22120', '2025-03-07', 'Present'),
(47, 'B22121', '2025-03-07', 'Present'),
(48, 'B22122', '2025-03-07', 'Present'),
(49, 'B22123', '2025-03-07', 'Present'),
(50, 'B22124', '2025-03-07', 'Present'),
(51, 'B22125', '2025-03-07', 'Present'),
(52, 'B22126', '2025-03-07', 'Present'),
(53, 'B22127', '2025-03-07', 'Present'),
(54, 'B22128', '2025-03-07', 'Present'),
(55, 'B22129', '2025-03-07', 'Present'),
(56, 'B22130', '2025-03-07', 'Present'),
(57, 'B22131', '2025-03-07', 'Present'),
(58, 'B22132', '2025-03-07', 'Present'),
(59, 'B22133', '2025-03-07', 'Present'),
(60, 'B22135', '2025-03-07', 'Present'),
(61, 'B22136', '2025-03-07', 'Present'),
(62, 'B22137', '2025-03-07', 'Present'),
(63, 'B22138', '2025-03-07', 'Present'),
(64, 'B22139', '2025-03-07', 'Present'),
(65, 'B22140', '2025-03-07', 'Present'),
(66, 'B22141', '2025-03-07', 'Present'),
(67, 'B22142', '2025-03-07', 'Present'),
(68, 'B22143', '2025-03-07', 'Present'),
(69, 'B22144', '2025-03-07', 'Present'),
(70, 'B22145', '2025-03-07', 'Present'),
(71, 'B22146', '2025-03-07', 'Present'),
(72, 'B22147', '2025-03-07', 'Present'),
(73, 'B22148', '2025-03-07', 'Present'),
(74, 'B22149', '2025-03-07', 'Present'),
(75, 'B22150', '2025-03-07', 'Present'),
(76, 'B22151', '2025-03-07', 'Present'),
(77, 'B22152', '2025-03-07', 'Present'),
(78, 'B22153', '2025-03-07', 'Present'),
(79, 'STU1003', '2025-03-08', 'Present'),
(80, 'B22108', '2025-03-08', 'Present'),
(81, 'B22134', '2025-03-08', 'Present'),
(82, 'B22101', '2025-03-08', 'Present'),
(83, 'B22102', '2025-03-08', 'Present'),
(84, 'B22103', '2025-03-08', 'Present'),
(85, 'B22104', '2025-03-08', 'Present'),
(86, 'B22105', '2025-03-08', 'Present'),
(87, 'B22106', '2025-03-08', 'Present'),
(88, 'B22107', '2025-03-08', 'Present'),
(89, 'B22109', '2025-03-08', 'Present'),
(90, 'B22110', '2025-03-08', 'Present'),
(91, 'B22111', '2025-03-08', 'Present'),
(92, 'B22112', '2025-03-08', 'Present'),
(93, 'B22113', '2025-03-08', 'Present'),
(94, 'B22114', '2025-03-08', 'Present'),
(95, 'B22115', '2025-03-08', 'Present'),
(96, 'B22116', '2025-03-08', 'Present'),
(97, 'B22117', '2025-03-08', 'Present'),
(98, 'B22118', '2025-03-08', 'Present'),
(99, 'B22119', '2025-03-08', 'Present'),
(100, 'B22120', '2025-03-08', 'Present'),
(101, 'B22121', '2025-03-08', 'Present'),
(102, 'B22122', '2025-03-08', 'Present'),
(103, 'B22123', '2025-03-08', 'Present'),
(104, 'B22124', '2025-03-08', 'Present'),
(105, 'B22125', '2025-03-08', 'Present'),
(106, 'B22126', '2025-03-08', 'Present'),
(107, 'B22127', '2025-03-08', 'Present'),
(108, 'B22128', '2025-03-08', 'Present'),
(109, 'B22129', '2025-03-08', 'Present'),
(110, 'B22130', '2025-03-08', 'Present'),
(111, 'B22131', '2025-03-08', 'Present'),
(112, 'B22132', '2025-03-08', 'Present'),
(113, 'B22133', '2025-03-08', 'Present'),
(114, 'B22135', '2025-03-08', 'Present'),
(115, 'B22136', '2025-03-08', 'Present'),
(116, 'B22137', '2025-03-08', 'Present'),
(117, 'B22138', '2025-03-08', 'Present'),
(118, 'B22139', '2025-03-08', 'Present'),
(119, 'B22140', '2025-03-08', 'Present'),
(120, 'B22141', '2025-03-08', 'Present'),
(121, 'B22142', '2025-03-08', 'Present'),
(122, 'B22143', '2025-03-08', 'Present'),
(123, 'B22144', '2025-03-08', 'Absent'),
(124, 'B22145', '2025-03-08', 'Absent'),
(125, 'B22146', '2025-03-08', 'Present'),
(126, 'B22147', '2025-03-08', 'Absent'),
(127, 'B22148', '2025-03-08', 'Absent'),
(128, 'B22149', '2025-03-08', 'Present'),
(129, 'B22150', '2025-03-08', 'Present'),
(130, 'B22151', '2025-03-08', 'Present'),
(131, 'B22152', '2025-03-08', 'Absent'),
(132, 'B22153', '2025-03-08', 'Absent'),
(133, 'STU1003', '2025-03-09', 'Present'),
(134, 'B22108', '2025-03-09', 'Present'),
(135, 'B22134', '2025-03-09', 'Present'),
(136, 'B22101', '2025-03-09', 'Absent'),
(137, 'B22102', '2025-03-09', 'Present'),
(138, 'B22103', '2025-03-09', 'Present'),
(139, 'B22104', '2025-03-09', 'Present'),
(140, 'B22105', '2025-03-09', 'Present'),
(141, 'B22106', '2025-03-09', 'Present'),
(142, 'B22107', '2025-03-09', 'Present'),
(143, 'B22109', '2025-03-09', 'Present'),
(144, 'B22110', '2025-03-09', 'Present'),
(145, 'B22111', '2025-03-09', 'Present'),
(146, 'B22112', '2025-03-09', 'Present'),
(147, 'B22113', '2025-03-09', 'Present'),
(148, 'B22114', '2025-03-09', 'Present'),
(149, 'B22115', '2025-03-09', 'Present'),
(150, 'B22116', '2025-03-09', 'Present'),
(151, 'B22117', '2025-03-09', 'Present'),
(152, 'B22118', '2025-03-09', 'Present'),
(153, 'B22119', '2025-03-09', 'Present'),
(154, 'B22120', '2025-03-09', 'Present'),
(155, 'B22121', '2025-03-09', 'Present'),
(156, 'B22122', '2025-03-09', 'Present'),
(157, 'B22123', '2025-03-09', 'Present'),
(158, 'B22124', '2025-03-09', 'Present'),
(159, 'B22125', '2025-03-09', 'Present'),
(160, 'B22126', '2025-03-09', 'Present'),
(161, 'B22127', '2025-03-09', 'Present'),
(162, 'B22128', '2025-03-09', 'Present'),
(163, 'B22129', '2025-03-09', 'Present'),
(164, 'B22130', '2025-03-09', 'Present'),
(165, 'B22131', '2025-03-09', 'Present'),
(166, 'B22132', '2025-03-09', 'Present'),
(167, 'B22133', '2025-03-09', 'Present'),
(168, 'B22135', '2025-03-09', 'Present'),
(169, 'B22136', '2025-03-09', 'Present'),
(170, 'B22137', '2025-03-09', 'Present'),
(171, 'B22138', '2025-03-09', 'Present'),
(172, 'B22139', '2025-03-09', 'Present'),
(173, 'B22140', '2025-03-09', 'Present'),
(174, 'B22141', '2025-03-09', 'Present'),
(175, 'B22142', '2025-03-09', 'Present'),
(176, 'B22143', '2025-03-09', 'Present'),
(177, 'B22144', '2025-03-09', 'Present'),
(178, 'B22145', '2025-03-09', 'Present'),
(179, 'B22146', '2025-03-09', 'Present'),
(180, 'B22147', '2025-03-09', 'Present'),
(181, 'B22148', '2025-03-09', 'Present'),
(182, 'B22149', '2025-03-09', 'Present'),
(183, 'B22150', '2025-03-09', 'Present'),
(184, 'B22151', '2025-03-09', 'Present'),
(185, 'B22152', '2025-03-09', 'Present'),
(186, 'B22153', '2025-03-09', 'Present');

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_requests`
--

CREATE TABLE `maintenance_requests` (
  `id` int(11) NOT NULL,
  `secretary_name` varchar(255) NOT NULL,
  `hostel_name` varchar(255) NOT NULL,
  `issue_description` text NOT NULL,
  `items_to_buy` text NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `maintenance_requests`
--

INSERT INTO `maintenance_requests` (`id`, `secretary_name`, `hostel_name`, `issue_description`, `items_to_buy`, `email`) VALUES
(1, 'Ajay Antony', 'PDH', 'There is some lights are not borning', 'Lights', 'ajaybasker@gmail.com'),
(2, 'Nishanth', '', 'Lights are damaged', '2 Lights', 'nishanthclintona@gmail.com'),
(3, 'Ram', '', 'Both room is not clean', 'Bresh', 'ram123@gmail.com'),
(4, 'Ram', '', 'Lights are damaged', 'Lights', 'ram123@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_responses`
--

CREATE TABLE `maintenance_responses` (
  `id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `response` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `maintenance_responses`
--

INSERT INTO `maintenance_responses` (`id`, `request_id`, `response`) VALUES
(1, 2, 'Ok i will buy the lights and give it to you'),
(2, 1, 'Ok Ajay i will buy the lights.\r\n'),
(3, 1, 'Ok Ajay i will buy the lights.\r\n'),
(4, 1, 'Ok Ajay i will buy the lights.\r\n'),
(5, 1, 'Ok Ajay i will buy the lights.\r\n'),
(6, 3, 'OK I will provide the bresh..'),
(7, 4, 'I will Make it.. ');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `message` text NOT NULL,
  `admin_reply` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `year` varchar(10) DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `student_name`, `email`, `message`, `admin_reply`, `status`, `created_at`, `year`, `department`, `username`, `is_read`) VALUES
(1, 'Nishanth', 'nishanth@example.com', 'I need a gate pass for tomorrow', NULL, 'pending', '2025-03-06 10:00:00', NULL, 'Gatepass', NULL, 0),
(2, 'Ram', 'ram@example.com', 'Fix the leak in Room 101', NULL, 'pending', '2025-03-06 10:05:00', NULL, 'Maintenance', NULL, 0),
(3, 'Ajay', 'ajay@example.com', 'Sign up for football match', NULL, 'pending', '2025-03-06 10:10:00', NULL, 'Sports', NULL, 0),
(4, 'Britt', 'britt@example.com', 'Need a library book on physics', NULL, 'pending', '2025-03-06 10:15:00', NULL, 'Reading', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `message` text NOT NULL,
  `admin_reply` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `year` varchar(10) DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `student_name`, `email`, `message`, `admin_reply`, `status`, `created_at`, `year`, `department`, `username`, `is_read`) VALUES
(1, 'Nishanth', 'nishanth@example.com', 'I need a gate pass for tomorrow', NULL, 'pending', '2025-03-06 10:00:00', NULL, 'Gatepass', NULL, 0),
(2, 'Ram', 'ram@example.com', 'Fix the leak in Room 101', NULL, 'pending', '2025-03-06 10:05:00', NULL, 'Maintenance', NULL, 0),
(3, 'Ajay', 'ajay@example.com', 'Sign up for football match', NULL, 'pending', '2025-03-06 10:10:00', NULL, 'Sports', NULL, 0),
(4, 'Britt', 'britt@example.com', 'Need a library book on physics', NULL, 'pending', '2025-03-06 10:15:00', NULL, 'Reading', NULL, 0),
(5, 'Nishanth', 'nishanth@gmail.com', 'I want gatepass ', 'Ok you can go...', 'Replied', '2025-03-07 11:33:32', '3rd Year', 'Information Technology', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `reading_requests`
--

CREATE TABLE `reading_requests` (
  `id` int(11) NOT NULL,
  `student_name` varchar(100) DEFAULT NULL,
  `hostel` varchar(50) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `admin_response` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reading_requests`
--

INSERT INTO `reading_requests` (`id`, `student_name`, `hostel`, `reason`, `email`, `admin_response`, `created_at`) VALUES
(1, 'Ram', 'Boys', 'Today is my birthday', 'ram@gmail.com', 'OK ram go and meet brother', '2025-02-26 16:49:35'),
(2, 'Nishanth', 'Boys', 'Just i need to read the lord\'s word', 'nishanthclintona@gmail.com', 'Come and meet me', '2025-02-26 17:10:13'),
(3, 'Nishanth', 'Boys', 'Nothing', 'nishanthclintona@gmail.com', 'Thanks\r\n', '2025-02-26 17:15:42'),
(4, 'Nishanth', 'Boys', 'AAA', 'nishanthclintona@gmail.com', 'MMM', '2025-03-01 07:49:08');

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `year` varchar(20) NOT NULL,
  `reason` text NOT NULL,
  `request_to` varchar(255) NOT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `admin_reply` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`id`, `name`, `year`, `reason`, `request_to`, `status`, `admin_reply`) VALUES
(1, 'NISHANTH CLINTON A', '', 'I want to buy somthing', 'Incharge', 'Approved', 'ok'),
(2, 'NISHANTH CLINTON A', '', 'I want to buy somthing', 'Incharge', 'Rejected', 'No'),
(3, 'John Doe', '1st Year', 'I have some reason', 'Father', 'Rejected', 'No you should not go out');

-- --------------------------------------------------------

--
-- Table structure for table `sports_requests`
--

CREATE TABLE `sports_requests` (
  `id` int(11) NOT NULL,
  `leader_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `items` text DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sports_requests`
--

INSERT INTO `sports_requests` (`id`, `leader_name`, `email`, `items`, `message`, `created_at`) VALUES
(1, 'Nishanth', 'nishanthclintona@gmail.com', 'Foodball and Volleyball', 'For the 3rd years', '2025-02-26 12:59:58'),
(2, 'Nishanth', 'nishanthclintona@gmail.com', 'Balls', '', '2025-02-26 13:03:27'),
(3, 'Ajay', 'ram123@gmail.com', 'mm', 'jhh', '2025-02-28 10:23:20'),
(4, 'Nishi', 'nishanthnishanth@gmail.com', 'Something', 'Nothing', '2025-03-01 07:14:50');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `name`, `role`, `contact`, `phone`) VALUES
(2, 'Joshwa', 'Warden', 'joshwa@gmail.com', '06381766666'),
(3, 'Antony', 'sub warden', 'antony@gmai.com', '06381761010'),
(4, 'Nishanth Clinton A', 'sub warden', 'nishanthclintona@gmail.com', '6381766310');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `year` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `student_id`, `username`, `name`, `password`, `year`) VALUES
(1, 'STU1001', 'student1', 'Rahul Sharma', 'pass123', 1),
(2, 'STU1002', 'student2', 'Ananya Reddy', 'pass456', 2),
(3, 'STU1003', 'student3', 'Vikram Kumar', 'pass789', 3),
(5, 'STU2001', '', 'Naveen', '', 1),
(6, 'B22108', 'Gilbrit', 'Arokiya Gilbrit', 'pass108', 3),
(21, 'B22134', 'admin143', 'NISHANTH CLINTON A', '', 3),
(22, 'B24101', 'studentB24101', 'Aarav Sharma', 'pass123', 1),
(23, 'B24102', 'studentB24102', 'Priya Patel', 'pass123', 1),
(24, 'B24103', 'studentB24103', 'Rohan Singh', 'pass123', 1),
(25, 'B24104', 'studentB24104', 'Neha Gupta', 'pass123', 1),
(26, 'B24105', 'studentB24105', 'Karan Mehta', 'pass123', 1),
(27, 'B24106', 'studentB24106', 'Sneha Reddy', 'pass123', 1),
(28, 'B24107', 'studentB24107', 'Vikram Joshi', 'pass123', 1),
(29, 'B24108', 'studentB24108', 'Anjali Nair', 'pass123', 1),
(30, 'B24109', 'studentB24109', 'Arjun Iyer', 'pass123', 1),
(31, 'B24110', 'studentB24110', 'Divya Kapoor', 'pass123', 1),
(32, 'B24111', 'studentB24111', 'Suresh Kumar', 'pass123', 1),
(33, 'B24112', 'studentB24112', 'Pooja Malhotra', 'pass123', 1),
(34, 'B24113', 'studentB24113', 'Rahul Verma', 'pass123', 1),
(35, 'B24114', 'studentB24114', 'Aishwarya Rao', 'pass123', 1),
(36, 'B24115', 'studentB24115', 'Nikhil Desai', 'pass123', 1),
(37, 'B24116', 'studentB24116', 'Kavita Singh', 'pass123', 1),
(38, 'B24117', 'studentB24117', 'Amitabh Yadav', 'pass123', 1),
(39, 'B24118', 'studentB24118', 'Shreya Tiwari', 'pass123', 1),
(40, 'B24119', 'studentB24119', 'Rakesh Sharma', 'pass123', 1),
(41, 'B24120', 'studentB24120', 'Meera Jain', 'pass123', 1),
(42, 'B24121', 'studentB24121', 'Sanjay Gupta', 'pass123', 1),
(43, 'B24122', 'studentB24122', 'Lakshmi Nair', 'pass123', 1),
(44, 'B24123', 'studentB24123', 'Vijay Kumar', 'pass123', 1),
(45, 'B24124', 'studentB24124', 'Anita Patel', 'pass123', 1),
(46, 'B24125', 'studentB24125', 'Prakash Reddy', 'pass123', 1),
(47, 'B24126', 'studentB24126', 'Sunita Joshi', 'pass123', 1),
(48, 'B24127', 'studentB24127', 'Manoj Iyer', 'pass123', 1),
(49, 'B24128', 'studentB24128', 'Rita Kapoor', 'pass123', 1),
(50, 'B24129', 'studentB24129', 'Hari Singh', 'pass123', 1),
(51, 'B24130', 'studentB24130', 'Geeta Malhotra', 'pass123', 1),
(52, 'B24131', 'studentB24131', 'Rajesh Verma', 'pass123', 1),
(53, 'B24132', 'studentB24132', 'Komal Rao', 'pass123', 1),
(54, 'B24133', 'studentB24133', 'Deepak Desai', 'pass123', 1),
(55, 'B24134', 'studentB24134', 'Poonam Singh', 'pass123', 1),
(56, 'B24135', 'studentB24135', 'Arvind Yadav', 'pass123', 1),
(57, 'B24136', 'studentB24136', 'Jyoti Tiwari', 'pass123', 1),
(58, 'B24137', 'studentB24137', 'Sanjay Sharma', 'pass123', 1),
(59, 'B24138', 'studentB24138', 'Nisha Jain', 'pass123', 1),
(60, 'B24139', 'studentB24139', 'Ravi Gupta', 'pass123', 1),
(61, 'B24140', 'studentB24140', 'Anita Nair', 'pass123', 1),
(62, 'B24141', 'studentB24141', 'Vikram Kumar', 'pass123', 1),
(63, 'B24142', 'studentB24142', 'Priya Patel', 'pass123', 1),
(64, 'B24143', 'studentB24143', 'Rohan Joshi', 'pass123', 1),
(65, 'B24144', 'studentB24144', 'Neha Iyer', 'pass123', 1),
(66, 'B24145', 'studentB24145', 'Karan Kapoor', 'pass123', 1),
(67, 'B24146', 'studentB24146', 'Sneha Singh', 'pass123', 1),
(68, 'B24147', 'studentB24147', 'Vikram Reddy', 'pass123', 1),
(69, 'B24148', 'studentB24148', 'Anjali Malhotra', 'pass123', 1),
(70, 'B24149', 'studentB24149', 'Arjun Verma', 'pass123', 1),
(71, 'B24150', 'studentB24150', 'Divya Rao', 'pass123', 1),
(72, 'B24151', 'studentB24151', 'Suresh Desai', 'pass123', 1),
(73, 'B24152', 'studentB24152', 'Pooja Yadav', 'pass123', 1),
(74, 'B24153', 'studentB24153', 'Rahul Tiwari', 'pass123', 1),
(75, 'B23101', 'studentB23101', 'Aditya Sharma', 'pass123', 2),
(76, 'B23102', 'studentB23102', 'Swati Patel', 'pass123', 2),
(77, 'B23103', 'studentB23103', 'Kunal Singh', 'pass123', 2),
(78, 'B23104', 'studentB23104', 'Ritu Gupta', 'pass123', 2),
(79, 'B23105', 'studentB23105', 'Aman Mehta', 'pass123', 2),
(80, 'B23106', 'studentB23106', 'Tina Reddy', 'pass123', 2),
(81, 'B23107', 'studentB23107', 'Sanjay Joshi', 'pass123', 2),
(82, 'B23108', 'studentB23108', 'Lata Nair', 'pass123', 2),
(83, 'B23109', 'studentB23109', 'Rohan Iyer', 'pass123', 2),
(84, 'B23110', 'studentB23110', 'Kajal Kapoor', 'pass123', 2),
(85, 'B23111', 'studentB23111', 'Vijay Kumar', 'pass123', 2),
(86, 'B23112', 'studentB23112', 'Neeta Malhotra', 'pass123', 2),
(87, 'B23113', 'studentB23113', 'Arun Verma', 'pass123', 2),
(88, 'B23114', 'studentB23114', 'Pooja Rao', 'pass123', 2),
(89, 'B23115', 'studentB23115', 'Siddharth Desai', 'pass123', 2),
(90, 'B23116', 'studentB23116', 'Rani Singh', 'pass123', 2),
(91, 'B23117', 'studentB23117', 'Bharat Yadav', 'pass123', 2),
(92, 'B23118', 'studentB23118', 'Sunita Tiwari', 'pass123', 2),
(93, 'B23119', 'studentB23119', 'Mohan Sharma', 'pass123', 2),
(94, 'B23120', 'studentB23120', 'Deepika Jain', 'pass123', 2),
(95, 'B23121', 'studentB23121', 'Rakesh Gupta', 'pass123', 2),
(96, 'B23122', 'studentB23122', 'Anita Nair', 'pass123', 2),
(97, 'B23123', 'studentB23123', 'Suresh Kumar', 'pass123', 2),
(98, 'B23124', 'studentB23124', 'Kavita Patel', 'pass123', 2),
(99, 'B23125', 'studentB23125', 'Prakash Reddy', 'pass123', 2),
(100, 'B23126', 'studentB23126', 'Sunil Joshi', 'pass123', 2),
(101, 'B23127', 'studentB23127', 'Meena Iyer', 'pass123', 2),
(102, 'B23128', 'studentB23128', 'Ravi Kapoor', 'pass123', 2),
(103, 'B23129', 'studentB23129', 'Hema Singh', 'pass123', 2),
(104, 'B23130', 'studentB23130', 'Gaurav Malhotra', 'pass123', 2),
(105, 'B23131', 'studentB23131', 'Rekha Verma', 'pass123', 2),
(106, 'B23132', 'studentB23132', 'Karan Rao', 'pass123', 2),
(107, 'B23133', 'studentB23133', 'Deepa Desai', 'pass123', 2),
(108, 'B23134', 'studentB23134', 'Pankaj Singh', 'pass123', 2),
(109, 'B23135', 'studentB23135', 'Aruna Yadav', 'pass123', 2),
(110, 'B23136', 'studentB23136', 'Jyoti Tiwari', 'pass123', 2),
(111, 'B23137', 'studentB23137', 'Sanjay Sharma', 'pass123', 2),
(112, 'B23138', 'studentB23138', 'Nisha Jain', 'pass123', 2),
(113, 'B23139', 'studentB23139', 'Ravi Gupta', 'pass123', 2),
(114, 'B23140', 'studentB23140', 'Anita Nair', 'pass123', 2),
(115, 'B23141', 'studentB23141', 'Vikram Kumar', 'pass123', 2),
(116, 'B23142', 'studentB23142', 'Priya Patel', 'pass123', 2),
(117, 'B23143', 'studentB23143', 'Rohan Joshi', 'pass123', 2),
(118, 'B23144', 'studentB23144', 'Neha Iyer', 'pass123', 2),
(119, 'B23145', 'studentB23145', 'Karan Kapoor', 'pass123', 2),
(120, 'B23146', 'studentB23146', 'Sneha Singh', 'pass123', 2),
(121, 'B23147', 'studentB23147', 'Vikram Reddy', 'pass123', 2),
(122, 'B23148', 'studentB23148', 'Anjali Malhotra', 'pass123', 2),
(123, 'B23149', 'studentB23149', 'Arjun Verma', 'pass123', 2),
(124, 'B23150', 'studentB23150', 'Divya Rao', 'pass123', 2),
(125, 'B23151', 'studentB23151', 'Suresh Desai', 'pass123', 2),
(126, 'B23152', 'studentB23152', 'Pooja Yadav', 'pass123', 2),
(127, 'B23153', 'studentB23153', 'Rahul Tiwari', 'pass123', 2),
(181, 'B22101', 'studentB22101', 'Ajay Sharma', 'pass123', 3),
(182, 'B22102', 'studentB22102', 'Sonali Patel', 'pass123', 3),
(183, 'B22103', 'studentB22103', 'Vivek Singh', 'pass123', 3),
(184, 'B22104', 'studentB22104', 'Rina Gupta', 'pass123', 3),
(185, 'B22105', 'studentB22105', 'Anil Mehta', 'pass123', 3),
(186, 'B22106', 'studentB22106', 'Tara Reddy', 'pass123', 3),
(187, 'B22107', 'studentB22107', 'Sandeep Joshi', 'pass123', 3),
(188, 'B22109', 'studentB22109', 'Raju Iyer', 'pass123', 3),
(189, 'B22110', 'studentB22110', 'Kiran Kapoor', 'pass123', 3),
(190, 'B22111', 'studentB22111', 'Vinod Kumar', 'pass123', 3),
(191, 'B22112', 'studentB22112', 'Nita Malhotra', 'pass123', 3),
(192, 'B22113', 'studentB22113', 'Ashok Verma', 'pass123', 3),
(193, 'B22114', 'studentB22114', 'Priya Rao', 'pass123', 3),
(194, 'B22115', 'studentB22115', 'Suresh Desai', 'pass123', 3),
(195, 'B22116', 'studentB22116', 'Rupa Singh', 'pass123', 3),
(196, 'B22117', 'studentB22117', 'Brijesh Yadav', 'pass123', 3),
(197, 'B22118', 'studentB22118', 'Sumitra Tiwari', 'pass123', 3),
(198, 'B22119', 'studentB22119', 'Mahesh Sharma', 'pass123', 3),
(199, 'B22120', 'studentB22120', 'Dipti Jain', 'pass123', 3),
(200, 'B22121', 'studentB22121', 'Ramesh Gupta', 'pass123', 3),
(201, 'B22122', 'studentB22122', 'Anju Nair', 'pass123', 3),
(202, 'B22123', 'studentB22123', 'Satish Kumar', 'pass123', 3),
(203, 'B22124', 'studentB22124', 'Kavita Patel', 'pass123', 3),
(204, 'B22125', 'studentB22125', 'Pradeep Reddy', 'pass123', 3),
(205, 'B22126', 'studentB22126', 'Sunita Joshi', 'pass123', 3),
(206, 'B22127', 'studentB22127', 'Mohan Iyer', 'pass123', 3),
(207, 'B22128', 'studentB22128', 'Rani Kapoor', 'pass123', 3),
(208, 'B22129', 'studentB22129', 'Harish Singh', 'pass123', 3),
(209, 'B22130', 'studentB22130', 'Geeta Malhotra', 'pass123', 3),
(210, 'B22131', 'studentB22131', 'Rajkumar Verma', 'pass123', 3),
(211, 'B22132', 'studentB22132', 'Komal Rao', 'pass123', 3),
(212, 'B22133', 'studentB22133', 'Deepak Desai', 'pass123', 3),
(213, 'B22135', 'studentB22135', 'Arvind Yadav', 'pass123', 3),
(214, 'B22136', 'studentB22136', 'Jyoti Tiwari', 'pass123', 3),
(215, 'B22137', 'studentB22137', 'Sanjay Sharma', 'pass123', 3),
(216, 'B22138', 'studentB22138', 'Nisha Jain', 'pass123', 3),
(217, 'B22139', 'studentB22139', 'Ravi Gupta', 'pass123', 3),
(218, 'B22140', 'studentB22140', 'Anita Nair', 'pass123', 3),
(219, 'B22141', 'studentB22141', 'Vikram Kumar', 'pass123', 3),
(220, 'B22142', 'studentB22142', 'Priya Patel', 'pass123', 3),
(221, 'B22143', 'studentB22143', 'Rohan Joshi', 'pass123', 3),
(222, 'B22144', 'studentB22144', 'Neha Iyer', 'pass123', 3),
(223, 'B22145', 'studentB22145', 'Karan Kapoor', 'pass123', 3),
(224, 'B22146', 'studentB22146', 'Sneha Singh', 'pass123', 3),
(225, 'B22147', 'studentB22147', 'Vikram Reddy', 'pass123', 3),
(226, 'B22148', 'studentB22148', 'Anjali Malhotra', 'pass123', 3),
(227, 'B22149', 'studentB22149', 'Arjun Verma', 'pass123', 3),
(228, 'B22150', 'studentB22150', 'Divya Rao', 'pass123', 3),
(229, 'B22151', 'studentB22151', 'Suresh Desai', 'pass123', 3),
(230, 'B22152', 'studentB22152', 'Pooja Yadav', 'pass123', 3),
(231, 'B22153', 'studentB22153', 'Rahul Tiwari', 'pass123', 3);

-- --------------------------------------------------------

--
-- Table structure for table `students_details`
--

CREATE TABLE `students_details` (
  `student_id` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `department` varchar(100) NOT NULL,
  `year` varchar(10) NOT NULL,
  `batch` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students_details`
--

INSERT INTO `students_details` (`student_id`, `name`, `department`, `year`, `batch`) VALUES
('B22101', 'Ajay Basker S', 'cs', '3rd year', '2022-2025'),
('B22102', 'Student 1', 'CS', '3rd Year', '2022-2025'),
('B22103', 'Student 2', 'EE', '3rd Year', '2022-2025'),
('B22104', 'Student 3', 'ME', '3rd Year', '2022-2025'),
('B22105', 'Student 4', 'CS', '3rd Year', '2022-2025'),
('B22106', 'Student 5', 'EE', '3rd Year', '2022-2025'),
('B22107', 'Student 6', 'ME', '3rd Year', '2022-2025'),
('B22108', 'Student 7', 'CS', '3rd Year', '2022-2025'),
('B22109', 'Student 8', 'EE', '3rd Year', '2022-2025'),
('B22110', 'Student 9', 'ME', '3rd Year', '2022-2025'),
('B22111', 'Student 10', 'CS', '3rd Year', '2022-2025'),
('B22112', 'Student 11', 'EE', '3rd Year', '2022-2025'),
('B22113', 'Student 12', 'ME', '3rd Year', '2022-2025'),
('B22114', 'Student 13', 'CS', '3rd Year', '2022-2025'),
('B22115', 'Student 14', 'EE', '3rd Year', '2022-2025'),
('B22116', 'Student 15', 'ME', '3rd Year', '2022-2025'),
('B22117', 'Student 16', 'CS', '3rd Year', '2022-2025'),
('B22118', 'Student 17', 'EE', '3rd Year', '2022-2025'),
('B22119', 'Student 18', 'ME', '3rd Year', '2022-2025'),
('B22120', 'Student 19', 'CS', '3rd Year', '2022-2025'),
('B22121', 'Student 20', 'EE', '3rd Year', '2022-2025'),
('B22122', 'Student 21', 'ME', '3rd Year', '2022-2025'),
('B22123', 'Student 22', 'CS', '3rd Year', '2022-2025'),
('B22124', 'Student 23', 'EE', '3rd Year', '2022-2025'),
('B22125', 'Student 24', 'ME', '3rd Year', '2022-2025'),
('B22126', 'Student 25', 'CS', '3rd Year', '2022-2025'),
('B22127', 'Student 26', 'EE', '3rd Year', '2022-2025'),
('B22128', 'Student 27', 'ME', '3rd Year', '2022-2025'),
('B22129', 'Student 28', 'CS', '3rd Year', '2022-2025'),
('B22130', 'Student 29', 'EE', '3rd Year', '2022-2025'),
('B22131', 'Student 30', 'ME', '3rd Year', '2022-2025'),
('B22132', 'Student 31', 'CS', '3rd Year', '2022-2025'),
('B22133', 'Student 32', 'EE', '3rd Year', '2022-2025'),
('B22134', 'Student 33', 'ME', '3rd Year', '2022-2025'),
('B22135', 'Student 34', 'CS', '3rd Year', '2022-2025'),
('B22136', 'Student 35', 'EE', '3rd Year', '2022-2025'),
('B22137', 'Student 36', 'ME', '3rd Year', '2022-2025'),
('B22138', 'Student 37', 'CS', '3rd Year', '2022-2025'),
('B22139', 'Student 38', 'EE', '3rd Year', '2022-2025'),
('B22140', 'Student 39', 'ME', '3rd Year', '2022-2025'),
('B22141', 'Student 40', 'CS', '3rd Year', '2022-2025'),
('B22142', 'Student 41', 'EE', '3rd Year', '2022-2025'),
('B22143', 'Student 42', 'ME', '3rd Year', '2022-2025'),
('B22144', 'Student 43', 'CS', '3rd Year', '2022-2025'),
('B22145', 'Student 44', 'EE', '3rd Year', '2022-2025'),
('B22146', 'Student 45', 'ME', '3rd Year', '2022-2025'),
('B22147', 'Student 46', 'CS', '3rd Year', '2022-2025'),
('B22148', 'Student 47', 'EE', '3rd Year', '2022-2025'),
('B22149', 'Student 48', 'ME', '3rd Year', '2022-2025'),
('B22150', 'Student 49', 'CS', '3rd Year', '2022-2025'),
('B22151', 'Student 50', 'EE', '3rd Year', '2022-2025'),
('B22152', 'Student 51', 'ME', '3rd Year', '2022-2025'),
('B22153', 'Student 52', 'CS', '3rd Year', '2022-2025'),
('B23101', 'Amit Sharma', 'CS', '2nd Year', '2023-2026'),
('B23102', 'Priya Singh', 'EE', '2nd Year', '2023-2026'),
('B23103', 'Rohan Verma', 'ME', '2nd Year', '2023-2026'),
('B23104', 'Neha Patel', 'CE', '2nd Year', '2023-2026'),
('B23105', 'Kunal Desai', 'CS', '2nd Year', '2023-2026'),
('B23106', 'Sneha Gupta', 'EE', '2nd Year', '2023-2026'),
('B23107', 'Vikram Yadav', 'ME', '2nd Year', '2023-2026'),
('B23108', 'Anjali Rao', 'CE', '2nd Year', '2023-2026'),
('B23109', 'Rahul Mehta', 'CS', '2nd Year', '2023-2026'),
('B23110', 'Pooja Malhotra', 'EE', '2nd Year', '2023-2026'),
('B23111', 'Suresh Kumar', 'ME', '2nd Year', '2023-2026'),
('B23112', 'Meera Joshi', 'CE', '2nd Year', '2023-2026'),
('B23113', 'Arjun Singh', 'CS', '2nd Year', '2023-2026'),
('B23114', 'Kavita Sharma', 'EE', '2nd Year', '2023-2026'),
('B23115', 'Dinesh Patel', 'ME', '2nd Year', '2023-2026'),
('B23116', 'Rita Desai', 'CE', '2nd Year', '2023-2026'),
('B23117', 'Manoj Gupta', 'CS', '2nd Year', '2023-2026'),
('B23118', 'Sunita Yadav', 'EE', '2nd Year', '2023-2026'),
('B23119', 'Vikas Rao', 'ME', '2nd Year', '2023-2026'),
('B23120', 'Anita Mehta', 'CE', '2nd Year', '2023-2026'),
('B23121', 'Rakesh Malhotra', 'CS', '2nd Year', '2023-2026'),
('B23122', 'Geeta Joshi', 'EE', '2nd Year', '2023-2026'),
('B23123', 'Sanjay Kumar', 'ME', '2nd Year', '2023-2026'),
('B23124', 'Lata Singh', 'CE', '2nd Year', '2023-2026'),
('B23125', 'Ajay Verma', 'CS', '2nd Year', '2023-2026'),
('B23126', 'Pooja Patel', 'EE', '2nd Year', '2023-2026'),
('B23127', 'Ravi Desai', 'ME', '2nd Year', '2023-2026'),
('B23128', 'Seema Gupta', 'CE', '2nd Year', '2023-2026'),
('B23129', 'Vijay Yadav', 'CS', '2nd Year', '2023-2026'),
('B23130', 'Anita Rao', 'EE', '2nd Year', '2023-2026'),
('B23131', 'Rahul Mehta', 'ME', '2nd Year', '2023-2026'),
('B23132', 'Komal Malhotra', 'CE', '2nd Year', '2023-2026'),
('B23133', 'Suresh Joshi', 'CS', '2nd Year', '2023-2026'),
('B23134', 'Meena Kumar', 'EE', '2nd Year', '2023-2026'),
('B23135', 'Arun Singh', 'ME', '2nd Year', '2023-2026'),
('B23136', 'Kiran Patel', 'CE', '2nd Year', '2023-2026'),
('B23137', 'Deepak Desai', 'CS', '2nd Year', '2023-2026'),
('B23138', 'Sunita Gupta', 'EE', '2nd Year', '2023-2026'),
('B23139', 'Vikram Yadav', 'ME', '2nd Year', '2023-2026'),
('B23140', 'Anju Rao', 'CE', '2nd Year', '2023-2026'),
('B23141', 'Rakesh Mehta', 'CS', '2nd Year', '2023-2026'),
('B23142', 'Geeta Malhotra', 'EE', '2nd Year', '2023-2026'),
('B23143', 'Sanjay Joshi', 'ME', '2nd Year', '2023-2026'),
('B23144', 'Lata Kumar', 'CE', '2nd Year', '2023-2026'),
('B23145', 'Ajay Singh', 'CS', '2nd Year', '2023-2026'),
('B23146', 'Poonam Patel', 'EE', '2nd Year', '2023-2026'),
('B23147', 'Raju Desai', 'ME', '2nd Year', '2023-2026'),
('B23148', 'Seema Gupta', 'CE', '2nd Year', '2023-2026'),
('B23149', 'Vijay Yadav', 'CS', '2nd Year', '2023-2026'),
('B23150', 'Anita Rao', 'EE', '2nd Year', '2023-2026'),
('B23151', 'Rahul Mehta', 'ME', '2nd Year', '2023-2026'),
('B23152', 'Komal Joshi', 'CE', '2nd Year', '2023-2026'),
('B23153', 'Suresh Kumar', 'CS', '2nd Year', '2023-2026'),
('B23154', 'Meena Singh', 'EE', '2nd Year', '2023-2026'),
('B24101', 'Rahul Gupta', 'CS', '1st Year', '2024-2027'),
('B24102', 'Sneha Kapoor', 'EE', '1st Year', '2024-2027'),
('B24103', 'Vikas Jain', 'ME', '1st Year', '2024-2027'),
('B24104', 'Anita Choudhary', 'CE', '1st Year', '2024-2027'),
('B24105', 'Karan Seth', 'CS', '1st Year', '2024-2027'),
('B24106', 'Priyanka Reddy', 'EE', '1st Year', '2024-2027'),
('B24107', 'Amitabh Roy', 'ME', '1st Year', '2024-2027'),
('B24108', 'Deepika Nair', 'CE', '1st Year', '2024-2027'),
('B24109', 'Sanjay Bhatt', 'CS', '1st Year', '2024-2027'),
('B24110', 'Megha Iyer', 'EE', '1st Year', '2024-2027'),
('B24111', 'Rakesh Tiwari', 'ME', '1st Year', '2024-2027'),
('B24112', 'Nidhi Sharma', 'CE', '1st Year', '2024-2027'),
('B24113', 'Vijay Pandey', 'CS', '1st Year', '2024-2027'),
('B24114', 'Kritika Bose', 'EE', '1st Year', '2024-2027'),
('B24115', 'Arun Das', 'ME', '1st Year', '2024-2027'),
('B24116', 'Pooja Sen', 'CE', '1st Year', '2024-2027'),
('B24117', 'Mohan Singh', 'CS', '1st Year', '2024-2027'),
('B24118', 'Rekha Mishra', 'EE', '1st Year', '2024-2027'),
('B24119', 'Suman Chakraborty', 'ME', '1st Year', '2024-2027'),
('B24120', 'Anil Kumar', 'CE', '1st Year', '2024-2027'),
('B24121', 'Ritu Banerjee', 'CS', '1st Year', '2024-2027'),
('B24122', 'Gaurav Sinha', 'EE', '1st Year', '2024-2027'),
('B24123', 'Shalini Patel', 'ME', '1st Year', '2024-2027'),
('B24124', 'Lokesh Yadav', 'CE', '1st Year', '2024-2027'),
('B24125', 'Aarti Mehra', 'CS', '1st Year', '2024-2027'),
('B24126', 'Rohan Agarwal', 'EE', '1st Year', '2024-2027'),
('B24127', 'Swati Dixit', 'ME', '1st Year', '2024-2027'),
('B24128', 'Vivek Sharma', 'CE', '1st Year', '2024-2027'),
('B24129', 'Neeta Pandey', 'CS', '1st Year', '2024-2027'),
('B24130', 'Ashish Bose', 'EE', '1st Year', '2024-2027'),
('B24131', 'Pinky Roy', 'ME', '1st Year', '2024-2027'),
('B24132', 'Kunal Nair', 'CE', '1st Year', '2024-2027'),
('B24133', 'Sonia Bhatt', 'CS', '1st Year', '2024-2027'),
('B24134', 'Manish Iyer', 'EE', '1st Year', '2024-2027'),
('B24135', 'Rekha Tiwari', 'ME', '1st Year', '2024-2027'),
('B24136', 'Anuj Sharma', 'CE', '1st Year', '2024-2027'),
('B24137', 'Lata Gupta', 'CS', '1st Year', '2024-2027'),
('B24138', 'Vikram Kapoor', 'EE', '1st Year', '2024-2027'),
('B24139', 'Sanjay Jain', 'ME', '1st Year', '2024-2027'),
('B24140', 'Meera Choudhary', 'CE', '1st Year', '2024-2027'),
('B24141', 'Ravi Seth', 'CS', '1st Year', '2024-2027'),
('B24142', 'Geeta Reddy', 'EE', '1st Year', '2024-2027'),
('B24143', 'Amit Roy', 'ME', '1st Year', '2024-2027'),
('B24144', 'Deepa Nair', 'CE', '1st Year', '2024-2027'),
('B24145', 'Suresh Bhatt', 'CS', '1st Year', '2024-2027'),
('B24146', 'Mita Iyer', 'EE', '1st Year', '2024-2027'),
('B24147', 'Raju Tiwari', 'ME', '1st Year', '2024-2027'),
('B24148', 'Nita Sharma', 'CE', '1st Year', '2024-2027'),
('B24149', 'Vijay Pandey', 'CS', '1st Year', '2024-2027'),
('B24150', 'Kavita Bose', 'EE', '1st Year', '2024-2027'),
('B24151', 'Arun Das', 'ME', '1st Year', '2024-2027'),
('B24152', 'Poonam Sen', 'CE', '1st Year', '2024-2027'),
('B24153', 'Manoj Singh', 'CS', '1st Year', '2024-2027'),
('B24154', 'Rina Mishra', 'EE', '1st Year', '2024-2027');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about_hostels`
--
ALTER TABLE `about_hostels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `admin_responses`
--
ALTER TABLE `admin_responses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_id` (`request_id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `maintenance_requests`
--
ALTER TABLE `maintenance_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `maintenance_responses`
--
ALTER TABLE `maintenance_responses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_id` (`request_id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reading_requests`
--
ALTER TABLE `reading_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sports_requests`
--
ALTER TABLE `sports_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `students_details`
--
ALTER TABLE `students_details`
  ADD PRIMARY KEY (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about_hostels`
--
ALTER TABLE `about_hostels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `admin_responses`
--
ALTER TABLE `admin_responses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=187;

--
-- AUTO_INCREMENT for table `maintenance_requests`
--
ALTER TABLE `maintenance_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `maintenance_responses`
--
ALTER TABLE `maintenance_responses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reading_requests`
--
ALTER TABLE `reading_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sports_requests`
--
ALTER TABLE `sports_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=232;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_responses`
--
ALTER TABLE `admin_responses`
  ADD CONSTRAINT `admin_responses_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `sports_requests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `maintenance_responses`
--
ALTER TABLE `maintenance_responses`
  ADD CONSTRAINT `maintenance_responses_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `maintenance_requests` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
