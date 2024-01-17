-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 17, 2024 at 09:40 AM
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
-- Database: `record_db`
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
(7, 'Robell ', 'Nomorosa', 'De Guzman', 'admin@yahoo.com', '$2y$10$lDd8xD.jaRf26R4h5NFZYuvaz6dBrLtAxbL3Z9zgH/5qNsOf1BHya', 1, '1684346400_W2mRD_5c.jpg', '2023-05-18 02:00:23', 'a49d1547cfbedf1be8679639668e9e11', '2023-05-22 13:47:06'),
(13, 'clerk1', 'clerk1', 'clerk1', 'clerk1@yahoo.com', '$2y$10$hhP4Ua7OeZBcPryFZ5sryeUZcoeLyL1HykbbLtcU9WQVxOb6DzZFq', 2, '1684402560_Feb 28, 2023.jpg', '2023-05-18 17:36:32', '', NULL),
(14, 'clerk2', 'clerk2', 'clerk2', 'clerk2@yahoo.com', '$2y$10$JtaY261VwfEPnBJDcpsazuyHxa4kh2cWoKUvDeJSKLmBqknvyuWRe', 2, '1684402620_Feb 28, 2023.jpg', '2023-05-18 17:37:18', '', NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user_file`
--
ALTER TABLE `user_file`
  MODIFY `file_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
