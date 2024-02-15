-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 29, 2017 at 01:23 PM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cis`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `Adm_id` int(11) NOT NULL,
  `username` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `password` varchar(32) COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`Adm_id`, `username`, `password`) VALUES
(1, 'Admin', '0192023a7bbd73250516f069df18b500');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `your_name` varchar(20) NOT NULL,
  `your_email` varchar(20) NOT NULL,
  `your_message` varchar(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `incidents`
--

CREATE TABLE `incidents` (
  `inc_id` int(11) NOT NULL,
  `username` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `reg_no` varchar(15) COLLATE latin1_general_ci NOT NULL,
  `time` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `type` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `place` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `Timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `incidents`
--

INSERT INTO `incidents` (`inc_id`, `username`, `reg_no`, `time`, `type`, `place`, `Timestamp`) VALUES
(41, 'kare', 'CI/00021/013', '01hrs - 02hrs', 'Fire Accident', 'College', '2017-07-29 10:56:57'),
(42, 'kare', 'CI/00021/013', '11hrs - 12hrs', 'Theft', 'Siriba', '2017-07-29 10:39:23');

-- --------------------------------------------------------

--
-- Table structure for table `incident_list`
--

CREATE TABLE `incident_list` (
  `inc_id` int(11) NOT NULL,
  `inc_name` varchar(20) COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `incident_list`
--

INSERT INTO `incident_list` (`inc_id`, `inc_name`) VALUES
(5, 'Quicker');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `m_id` int(11) NOT NULL,
  `m_name` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `m_message` text COLLATE latin1_general_ci NOT NULL,
  `m_reply` text COLLATE latin1_general_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`m_id`, `m_name`, `m_message`, `m_reply`, `timestamp`) VALUES
(15, 'sister', 'Will you or will you not?', 'Awaiting Reply....', '2017-04-26 10:01:11'),
(14, 'sister', 'Hey be Serious with your work.', 'Ok. Am sorry.', '2017-04-26 11:36:10'),
(13, 'sister', 'I thought you will reply ASAP.', 'Awaiting Reply....', '2017-04-26 10:00:25'),
(12, 'sister', 'Hey', 'Awaiting Reply....', '2017-04-26 09:50:13'),
(16, 'STANLEY', 'Hallo Admin.', 'Hey Stan', '2017-04-26 11:36:40'),
(17, 'STANLEY', 'Hallo Admin.', 'Hey STANLEY', '2017-04-26 11:29:22'),
(18, 'STANLEY', 'How are you doing?', 'Am good. How are you?', '2017-04-26 11:44:29'),
(19, 'STANLEY', 'Am good too. I would like to know what actions are usually', 'are usually?', '2017-04-26 11:48:41'),
(20, 'STANLEY', 'Am good too. I would like to know what actions are usually', 'Awaiting Reply....', '2017-04-26 11:48:05'),
(21, 'STANLEY', 'Oh sorry sent that before i finish. Taken when you receive the incident.', 'So what we do is we ascertain it reaches the relevant authority.', '2017-04-26 11:50:12'),
(22, 'kare', 'hae', 'hae', '2017-07-29 11:20:18');

-- --------------------------------------------------------

--
-- Table structure for table `registration`
--

CREATE TABLE `registration` (
  `reg_id` int(11) NOT NULL,
  `first` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `other` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `fuculty` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `reg_no` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `phone` int(15) NOT NULL,
  `Dob` date NOT NULL,
  `username` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `password` varchar(32) COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `registration`
--

INSERT INTO `registration` (`reg_id`, `first`, `other`, `fuculty`, `reg_no`, `phone`, `Dob`, `username`, `password`) VALUES
(31, 'STANLEY', 'STANO', 'MEDICINE', 'MED/0091/014', 721456789, '2017-01-01', 'STANLEY', '782845044ebd41971d36c95ad5605d80'),
(29, 'sister', 'sister', 'sister', 'sister', 9472836, '2017-04-12', 'sister', 'daffd55e1b8020c7a60a7b6e36afb775'),
(32, 'COOL', 'NAME', 'MEDICINE', 'MED/0912/2015', 711223344, '2017-05-01', 'COOL', '79ce8508b165746597039bd1dbeb6957'),
(33, 'EISA', 'ILS', 'COMP', 'PH/LL/098', 711111111, '1995-09-12', 'eisa', '9abc8ddaf9d5394eb21f694b4fe14ea8'),
(34, 'Kare', 'Ann', 'Computing & Informat', 'CI/00021/013', 7234567, '2017-06-25', 'kare', '67a1cf39c092df26f7146b6917c274aa');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`Adm_id`);

--
-- Indexes for table `incidents`
--
ALTER TABLE `incidents`
  ADD PRIMARY KEY (`inc_id`);

--
-- Indexes for table `incident_list`
--
ALTER TABLE `incident_list`
  ADD PRIMARY KEY (`inc_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`m_id`);

--
-- Indexes for table `registration`
--
ALTER TABLE `registration`
  ADD PRIMARY KEY (`reg_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `Adm_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `incidents`
--
ALTER TABLE `incidents`
  MODIFY `inc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;
--
-- AUTO_INCREMENT for table `incident_list`
--
ALTER TABLE `incident_list`
  MODIFY `inc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `m_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `registration`
--
ALTER TABLE `registration`
  MODIFY `reg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
