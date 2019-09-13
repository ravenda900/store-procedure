-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 13, 2019 at 11:03 PM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `store_procedure`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `AvailableInstruments` ()  NO SQL
SELECT * FROM instruments LEFT JOIN student_instrument ON instruments.InstrumentID = student_instrument.InstrumentID WHERE StudentID IS NULL$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `BorrowedInstruments` ()  NO SQL
SELECT * FROM students INNER JOIN student_instrument ON student_instrument.StudentID = students.StudentID INNER JOIN instruments ON student_instrument.InstrumentID = instruments.InstrumentID$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `StudentsWithBorrowCredits` ()  NO SQL
SELECT * FROM students LEFT JOIN student_instrument ON students.StudentID = student_instrument.StudentID GROUP BY students.StudentID HAVING COUNT(student_instrument.InstrumentID) < 2$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `instruments`
--

CREATE TABLE `instruments` (
  `InstrumentID` int(11) NOT NULL,
  `Model` varchar(255) NOT NULL,
  `InstrName` varchar(255) NOT NULL,
  `Category` varchar(255) NOT NULL,
  `DateAcquired` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `EstimatedValue` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `StudentID` int(11) NOT NULL,
  `StudFname` varchar(255) NOT NULL,
  `StudMname` varchar(255) NOT NULL,
  `StudLname` varchar(255) NOT NULL,
  `Address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `student_instrument`
--

CREATE TABLE `student_instrument` (
  `StudentID` int(11) NOT NULL,
  `InstrumentID` int(11) NOT NULL,
  `CheckOutDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `CheckInDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `instruments`
--
ALTER TABLE `instruments`
  ADD PRIMARY KEY (`InstrumentID`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`StudentID`);

--
-- Indexes for table `student_instrument`
--
ALTER TABLE `student_instrument`
  ADD PRIMARY KEY (`StudentID`,`InstrumentID`),
  ADD KEY `InstrumentID` (`InstrumentID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `instruments`
--
ALTER TABLE `instruments`
  MODIFY `InstrumentID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `StudentID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `student_instrument`
--
ALTER TABLE `student_instrument`
  ADD CONSTRAINT `student_instrument_ibfk_1` FOREIGN KEY (`InstrumentID`) REFERENCES `instruments` (`InstrumentID`),
  ADD CONSTRAINT `student_instrument_ibfk_2` FOREIGN KEY (`StudentID`) REFERENCES `students` (`StudentID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
