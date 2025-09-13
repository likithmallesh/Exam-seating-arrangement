-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 25, 2024 at 09:20 AM
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
-- Database: `seating`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `adminid` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`adminid`, `name`, `email`, `password`) VALUES
(1, 'Admin1002', 'admin1002@gmail.com', 'root12'),
(2, 'Admin', 'xyz@gmail.com', 'PjwnJTF6'),
(9, 'Admin1001', 'admin1001@gmail.com', 'AWlxauaL');

-- --------------------------------------------------------

--
-- Table structure for table `batch`
--

CREATE TABLE `batch` (
  `batch_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `startno` int(11) NOT NULL,
  `endno` int(11) NOT NULL,
  `date` date NOT NULL,
  `total` int(11) GENERATED ALWAYS AS (`endno` - `startno` + 1) VIRTUAL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `class_id` int(11) NOT NULL,
  `year` varchar(20) NOT NULL,
  `dept` varchar(30) NOT NULL,
  `division` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`class_id`, `year`, `dept`, `division`) VALUES
(33, 'TE', 'BCA', 'A'),
(35, 'TE', 'CS', 'A'),
(34, 'TE', 'IT', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `jeffrin`
--

CREATE TABLE `jeffrin` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `room_no` varchar(50) DEFAULT NULL,
  `floor` varchar(50) DEFAULT NULL,
  `seatno` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jeffrin`
--

INSERT INTO `jeffrin` (`id`, `name`, `email`, `room_no`, `floor`, `seatno`) VALUES
(1, 'jeffrin', '22suca08@tcarts.in', '1', '4', 1),
(2, 'karthick', '22suca09@tcarts.in', '1', '4', 2);

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `rid` int(11) NOT NULL,
  `room_no` int(11) NOT NULL,
  `floor` int(11) NOT NULL,
  `capacity` int(11) NOT NULL,
  `vacancy` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `class` int(11) NOT NULL,
  `rollno` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `password`, `name`, `email`, `class`, `rollno`) VALUES
(42, 'abishek01', 'abishek k.k', '22suca01@tcarts.in', 33, 1),
(43, 'abishek02', 'abishek m', '22suca02@tcarts.in', 33, 2),
(44, 'aravind03', 'aravind', '22suca03@tcarts.in', 33, 3),
(45, 'arul04', 'arul', '22suca04@tcarts.in', 33, 4),
(46, 'gurudhatshan05', 'gurudhatshan', '22suca05@tcarts.in', 33, 5),
(50, 'hari06', 'hari', '22suca06@tcarts.in', 33, 6),
(51, 'jeffrin08', 'jeffrin', '22suca08@tcarts.in', 33, 7),
(52, 'karthick09', 'karthick', '22suca09@tcarts.in', 33, 8),
(53, 'karthikeyan10', 'karthikeyan', '22suca10@tcarts.in', 33, 9),
(54, 'tharun11', 'tharun', '22suca11@tcarts.in', 33, 10),
(55, 'charanguru01', 'charanguru', '22suit01@tcarts.in', 34, 1),
(56, 'gnanaguru02', 'gnanaguru', '22suit02@tcarts.in', 34, 2),
(57, 'gukan03', 'gukan', '22suit03@tcarts.in', 34, 3),
(58, 'gurubairavar04', 'gurubairavar', '22suit04@tcarts.in', 34, 4),
(59, 'jeyanthan05', 'jeyanthan', '22suit05@tcarts.in', 34, 5),
(60, 'john06', 'john', '22suit06@tcarts.in', 34, 6),
(61, 'krishna07', 'krishna', '22suit07@tcarts.in', 34, 7),
(62, 'loganath08', 'loganath', '22suit08@tcarts.in', 34, 8),
(63, 'logesh09', 'logesh', '22suit09@tcarts.in', 34, 9),
(64, 'muthu10', 'muthu', '22suit10@tcarts.in', 34, 10),
(65, 'abdul01', 'abdul', '22sucg01@tcarts.in', 35, 1),
(66, 'dhanush02', 'dhanush', '22sucg02@tcarts.in', 35, 2),
(67, 'dibhakar03', 'dibhakar', '22sucg03@tcarts.in', 35, 3),
(68, 'sudhan04', 'sudhan', '22sucg04@tcarts.in', 35, 4),
(69, 'prasath05', 'prasath', '22sucg05@tcarts.in', 35, 5),
(70, 'jagannathan06', 'jagannathan', '22sucg06@tcarts.in', 35, 6),
(71, 'mathew07', 'mathew', '22sucg07@tcarts.in', 35, 7),
(72, 'mohan08', 'mohan', '22sucg08@tcarts.in', 35, 8),
(73, 'nantha09', 'nantha', '22sucg09@tcarts.in', 35, 9),
(74, 'sakthi10', 'sakthi', '22sucg10@tcarts.in', 35, 10);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`adminid`),
  ADD UNIQUE KEY `admin_email` (`email`);

--
-- Indexes for table `batch`
--
ALTER TABLE `batch`
  ADD PRIMARY KEY (`batch_id`),
  ADD KEY `batch_ibfk_1` (`room_id`),
  ADD KEY `batch_ibfk_2` (`class_id`);

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`class_id`),
  ADD UNIQUE KEY `uniqueclass` (`year`,`dept`,`division`);

--
-- Indexes for table `jeffrin`
--
ALTER TABLE `jeffrin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`rid`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `Student_email` (`email`),
  ADD KEY `students_ibfk_1` (`class`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `adminid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `batch`
--
ALTER TABLE `batch`
  MODIFY `batch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `class`
--
ALTER TABLE `class`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `jeffrin`
--
ALTER TABLE `jeffrin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `rid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `batch`
--
ALTER TABLE `batch`
  ADD CONSTRAINT `batch_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `room` (`rid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `batch_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`class`) REFERENCES `class` (`class_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
