-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 22, 2023 at 12:49 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `odss_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `record`
--

CREATE TABLE `record` (
  `id` int(11) NOT NULL,
  `last_name` varchar(200) NOT NULL,
  `clerk_id` int(30) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `first_name` varchar(15) NOT NULL,
  `middle_name` varchar(15) NOT NULL,
  `course_name` varchar(50) NOT NULL,
  `year_graduate` varchar(12) NOT NULL,
  `year_entry` varchar(12) NOT NULL,
  `grad_hd` varchar(20) NOT NULL,
  `student_no` int(10) NOT NULL,
  `record_status` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `record`
--

INSERT INTO `record` (`id`, `last_name`, `clerk_id`, `date_created`, `first_name`, `middle_name`, `course_name`, `year_graduate`, `year_entry`, `grad_hd`, `student_no`, `record_status`) VALUES
(36, 'Contreras', 6, '2023-05-18 03:06:37', 'Jericho', 'Casio', 'CPT-41', '1995', '1995', 'Graduated', 21, 'approved'),
(39, 'Student2', 14, '2023-05-18 17:45:15', 'Student2', 'Student2', 'student2', '1995', '1995', 'Graduated', 20, 'approved'),
(40, 'Student3', 7, '2023-05-19 00:21:19', 'Student3', 'Student3', 'student3', '1995', '1995', 'Graduated', 12, 'approved'),
(41, 'Clerk1sample', 13, '2023-05-19 02:09:39', 'Clerk1sample', 'Clerk1sample', 'clerk1sample', '1995', '1995', 'Graduated', 19, 'approved'),
(43, 'Ey', 13, '2023-05-19 02:13:29', 'Ey', 'Ey', 'ey', '1995', '1995', 'Graduated', 23, 'approved'),
(46, 'Empty', 13, '2023-05-19 02:25:17', 'Empty', 'Empty', 'empty', '1995', '1995', 'Graduated', 26, 'approved'),
(48, 'Notyet', 13, '2023-05-19 03:26:56', 'Notyet', 'Notyet', 'notyet', '1995', '1995', 'Graduated', 28, 'approved'),
(49, 'Recordsample', 13, '2023-05-19 03:35:32', 'Recordsample', 'Recordsample', 'RECORDSAMPLE', '1995', '1995', 'Graduated', 22, 'approved'),
(50, 'Robell', 13, '2023-05-19 04:22:11', 'Robell', 'Robell', 'robell', '1995', '1995', 'Graduated', 24, 'approved'),
(51, 'Monig', 13, '2023-05-19 04:57:13', 'Monig', 'Monig', 'monig', '1995', '1995', 'Graduated', 25, 'approved'),
(52, 'Monig', 13, '2023-05-19 04:57:13', 'Monig', 'Monig', 'monig', '1995', '1995', 'Graduated', 25, 'approved'),
(53, 'Robell', 13, '2023-05-19 04:22:11', 'Robell', 'Robell', 'robell', '1995', '1995', 'Graduated', 24, 'approved'),
(54, 'Recordsample', 13, '2023-05-19 03:35:32', 'Recordsample', 'Recordsample', 'RECORDSAMPLE', '1995', '1995', 'Graduated', 22, 'approved'),
(55, 'Notyet', 13, '2023-05-19 03:26:56', 'Notyet', 'Notyet', 'notyet', '1995', '1995', 'Graduated', 28, 'approved'),
(56, 'Empty', 13, '2023-05-19 02:25:17', 'Empty', 'Empty', 'empty', '1995', '1995', 'Graduated', 26, 'approved'),
(57, 'Ey', 13, '2023-05-19 02:13:29', 'Ey', 'Ey', 'ey', '1995', '1995', 'Graduated', 23, 'approved'),
(58, 'Clerk1sample', 13, '2023-05-19 02:09:39', 'Clerk1sample', 'Clerk1sample', 'clerk1sample', '1995', '1995', 'Graduated', 19, 'approved'),
(59, 'Student3', 7, '2023-05-19 00:21:19', 'Student3', 'Student3', 'student3', '1995', '1995', 'Graduated', 12, 'approved'),
(60, 'Student2', 14, '2023-05-18 17:45:15', 'Student2', 'Student2', 'student2', '1995', '1995', 'Graduated', 20, 'approved'),
(61, 'Contreras', 6, '2023-05-18 03:06:37', 'Jericho', 'Casio', 'CPT-41', '1995', '1995', 'Graduated', 21, 'approved'),
(62, 'Fmewofmew', 13, '2023-05-19 05:11:16', 'Fvweom', 'Fmoewfv', 'fmewofmweo', '1995', '1995', 'Graduated', 27, 'approved'),
(63, 'Wmpe', 13, '2023-05-19 05:11:27', 'Wmwv', 'Mpwe', 'wepm', '1995', '1995', 'Graduated', 29, 'notyetapproved');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(30) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `middlename` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 2 COMMENT '1=Admin,2= users',
  `avatar` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `token` varchar(65) NOT NULL,
  `token_expiration` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `middlename`, `email`, `password`, `type`, `avatar`, `date_created`, `token`, `token_expiration`) VALUES
(4, 'Admin', 'Admin', 'Admin', 'admin@admin.com', 'd41d8cd98f00b204e9800998ecf8427e', 1, '1684313940_Feb 28, 2023.jpg', '2023-05-14 23:33:20', '', NULL),
(6, 'Jolina', 'halog', 'gonzaga', 'joy@gmail.com', 'd41d8cd98f00b204e9800998ecf8427e', 1, '1684399020_Feb 28, 2023.jpg', '2023-05-17 13:22:19', '', NULL),
(7, 'adminf', 'adminl', 'adminm', 'robellnomorosa@yahoo.com', '$2y$10$V1sjRWLZMJTCPleK8fDV.epYCZRQcUPvHqdBTT.xT2cnBGjsn.7T.', 1, '1684346400_W2mRD_5c.jpg', '2023-05-18 02:00:23', 'a49d1547cfbedf1be8679639668e9e11', '2023-05-22 13:47:06'),
(8, 'adwmop', 'dmwapopo', 'wamdpo', 'adaiwodcwa@yahoo.com', '21232f297a57a5a743894a0e4a801fc3', 2, '', '2023-05-18 02:05:30', '', NULL),
(11, 'qweo', 'eiwqoeioq', 'eiwqoei', 'ewioqe@yahoo.com', '21232f297a57a5a743894a0e4a801fc3', 2, '', '2023-05-18 02:08:27', '', NULL),
(13, 'clerk1', 'clerk1', 'clerk1', 'clerk1@yahoo.com', '$2y$10$hhP4Ua7OeZBcPryFZ5sryeUZcoeLyL1HykbbLtcU9WQVxOb6DzZFq', 2, '1684402560_Feb 28, 2023.jpg', '2023-05-18 17:36:32', '', NULL),
(14, 'clerk2', 'clerk2', 'clerk2', 'clerk2@yahoo.com', '$2y$10$JtaY261VwfEPnBJDcpsazuyHxa4kh2cWoKUvDeJSKLmBqknvyuWRe', 2, '1684402620_Feb 28, 2023.jpg', '2023-05-18 17:37:18', '', NULL),
(15, 'gpt', 'gpt', 'gpt', 'gpt@yahoo.com', '$2y$10$aMkv8awueluOcbYTKk3kcefw27fbX.ahkqpzbr7VRS7BsT7Rh6FW2', 2, '1684429736_prev_a.png', '2023-05-19 01:08:56', '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_file`
--

CREATE TABLE `user_file` (
  `file_id` int(10) NOT NULL,
  `file_name` varchar(200) NOT NULL,
  `file_type` varchar(200) NOT NULL,
  `date_uploaded` varchar(200) NOT NULL,
  `clerk_id` int(10) NOT NULL,
  `file_owner` varchar(256) NOT NULL,
  `student_no` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_file`
--

INSERT INTO `user_file` (`file_id`, `file_name`, `file_type`, `date_uploaded`, `clerk_id`, `file_owner`, `student_no`) VALUES
(1, 'Cruz_Ethernet_Cable.pptx', 'pptx', '2023-05-16, 01:56 PM', 4, '2023-05-16, 01:56 PM', 0),
(2, 'Cruz_Ethernet_Cable.pptx', 'pptx', '2023-05-16, 02:00 PM', 4, '2023-05-16, 02:00 PM', 7),
(3, '306236.docx', 'docx', '2023-05-16, 02:02 PM', 4, '2023-05-16, 02:02 PM', 0),
(4, 'Cruz_Ethernet_Cable.pptx', 'pptx', '2023-05-16, 02:26 PM', 4, '2023-05-16, 02:26 PM', 0),
(5, 'Cruz_Ethernet_Cable.pptx', 'pptx', '2023-05-16, 02:46 PM', 4, '2023-05-16, 02:46 PM', 3),
(6, 'Cruz_Ethernet_Cable.pptx', 'pptx', '2023-05-16, 02:48 PM', 4, '2023-05-16, 02:48 PM', 4),
(7, 'React_Documentation.docx', 'docx', '2023-05-16, 05:55 PM', 4, '2023-05-16, 05:55 PM', 0),
(8, 'OmarShah_Activity_PPT_Elements.pptx', 'pptx', '2023-05-16, 06:20 PM', 4, '2023-05-16, 06:20 PM', 0),
(9, 'react.txt', 'txt', '2023-05-17, 05:32 AM', 4, '2023-05-17, 05:32 AM', 0),
(10, 'react.txt', 'txt', '2023-05-17, 06:34 AM', 4, '2023-05-17, 06:34 AM', 1),
(11, 'wordpress.txt', 'txt', '2023-05-17, 06:36 AM', 4, '2023-05-17, 06:36 AM', 1),
(12, '.htaccess', 'htaccess', '2023-05-17, 06:37 AM', 4, '2023-05-17, 06:37 AM', 1),
(13, 'react.txt', 'txt', '2023-05-17, 06:38 AM', 4, '2023-05-17, 06:38 AM', 1),
(14, '.htaccess', 'htaccess', '2023-05-17, 07:01 AM', 4, '2023-05-17, 07:01 AM', 1),
(15, 'MIDTERM EXAM(system).docx', 'docx', '2023-05-17, 07:52 AM', 4, '2023-05-17, 07:52 AM', 0),
(16, '.htaccess', 'htaccess', '2023-05-17, 07:53 AM', 4, '2023-05-17, 07:53 AM', 0),
(17, 'React_Documentation.pdf.txt', 'txt', '2023-05-17, 07:54 AM', 4, '2023-05-17, 07:54 AM', 9),
(18, 'react.txt', 'txt', '2023-05-17, 07:55 AM', 4, '2023-05-17, 07:55 AM', 10),
(19, 'wordpress.txt', 'txt', '2023-05-17, 08:53 AM', 4, '2023-05-17, 08:53 AM', 11),
(20, 'OmarShah_Activity_PPT_Elements.pptx', 'pptx', '2023-05-17, 09:01 AM', 4, '2023-05-17, 09:01 AM', 6),
(21, 'MIDTERM EXAM(system).docx', 'docx', '2023-05-17, 09:03 AM', 4, '2023-05-17, 09:03 AM', 2),
(22, '.htaccess', 'htaccess', '2023-05-17, 09:14 AM', 4, '2023-05-17, 09:14 AM', 5),
(24, 'OmarShah_Activity_PPT_Elements.pptx', 'pptx', '2023-05-17, 09:16 AM', 4, '2023-05-17, 09:16 AM', 13),
(25, 'Cruz_Ethernet_Cable.pptx', 'pptx', '2023-05-17, 09:24 AM', 4, '2023-05-17, 09:24 AM', 14),
(26, '', '', '2023-05-17, 09:33 AM', 4, '2023-05-17, 09:33 AM', 15),
(27, 'OmarShah_Activity_PPT_Elements.pptx', 'pptx', '2023-05-17, 09:33 AM', 4, '2023-05-17, 09:33 AM', 16),
(28, 'OmarShah_Activity_PPT_Elements.pptx', 'pptx', '2023-05-17, 09:33 AM', 4, '2023-05-17, 09:33 AM', 17),
(29, 'MIDTERM EXAM(system).docx', 'docx', '2023-05-17, 09:34 AM', 4, '2023-05-17, 09:34 AM', 18),
(30, '23750.pdf', 'pdf', '2023-05-17, 07:02 PM', 4, '2023-05-17, 07:02 PM', 8),
(31, '', '', '2023-05-17, 08:09 PM', 4, '2023-05-17, 08:09 PM', 0),
(32, '', '', '2023-05-17, 08:30 PM', 4, '2023-05-17, 08:30 PM', 1),
(33, '', '', '2023-05-17, 08:30 PM', 4, '2023-05-17, 08:30 PM', 0),
(34, '23750.pdf', 'pdf', '2023-05-17, 09:05 PM', 4, '2023-05-17, 09:05 PM', 0),
(35, '117094.pdf', 'pdf', '2023-05-17, 09:09 PM', 4, '2023-05-17, 09:09 PM', 1),
(36, '123009.pdf', 'pdf', '2023-05-17, 09:09 PM', 4, '2023-05-17, 09:09 PM', 5),
(37, '68376.pdf', 'pdf', '2023-05-17, 09:10 PM', 4, '2023-05-17, 09:10 PM', 1),
(38, '91240.pdf', 'pdf', '2023-05-17, 09:12 PM', 4, '2023-05-17, 09:12 PM', 1),
(39, '161118.docx', 'docx', '2023-05-17, 09:12 PM', 4, '2023-05-17, 09:12 PM', 1),
(40, '163740.pdf', 'pdf', '2023-05-17, 09:13 PM', 4, '2023-05-17, 09:13 PM', 1),
(90, '1684428617_.htaccess', 'htaccess', '2023-05-19, 12:50 AM', 7, '2023-05-19, 12:50 AM', 21),
(91, '1684433379_slider_caption_bg.png', 'png', '2023-05-19, 02:09 AM', 13, '2023-05-19, 02:09 AM', 19),
(93, '1684433609_', '', '2023-05-19, 02:13 AM', 13, '2023-05-19, 02:13 AM', 23),
(96, '1684434317_', '', '2023-05-19, 02:25 AM', 13, '2023-05-19, 02:25 AM', 26);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `record`
--
ALTER TABLE `record`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_file`
--
ALTER TABLE `user_file`
  ADD PRIMARY KEY (`file_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `record`
--
ALTER TABLE `record`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user_file`
--
ALTER TABLE `user_file`
  MODIFY `file_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
