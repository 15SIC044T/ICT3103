-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 23, 2017 at 10:46 AM
-- Server version: 10.1.26-MariaDB
-- PHP Version: 7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ict3103`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `accountID` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `accountStatus` varchar(50) NOT NULL,
  `verificationToken` varchar(255) DEFAULT NULL,
  `privateKey` varchar(255) NOT NULL,
  `publicKey` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`accountID`, `name`, `email`, `password`, `phone`, `accountStatus`, `verificationToken`, `privateKey`, `publicKey`) VALUES
(1, 'Xinyi', '15SIC044T@sit.singaporetech.edu.sg', '$2y$10$wLd1Bd9yZD5RDb4IAB1pSOkyaroR6Xum33jsa9j7G5asa6IF97V5m', '12345678', 'Verified', NULL, '../keys/rsa/Xinyi_2017-10-21_15-31-26_private.key', '../keys/rsa/Xinyi_2017-10-21_15-31-26_public.key'),
(2, 'Meng', 'menghwee.pek_2015@sit.singaporetech.edu.sg', '$2y$10$pF.oSYsqSo2Q0mD5J7.y8uCzi.v7YbXU0QPdN0/0X7W0jvfg4Ba3S', '98765432', 'Verified', NULL, '../keys/rsa/Meng_2017-10-21_15-33-38_private.key', '../keys/rsa/Meng_2017-10-21_15-33-38_public.key'),
(3, 'jeremy', '15SIS040D@sit.singaporetech.edu.sg', '$2y$10$YwCJDxYyE8hUotSFrH8T/OMOgzaHn8iDr1quLgtxbrDXDYxXgl7cW', '82332779', 'Verified', NULL, '../keys/rsa/jeremy_2017-10-21_03-47-10_private.key', '../keys/rsa/jeremy_2017-10-21_03-47-10_public.key');

-- --------------------------------------------------------

--
-- Table structure for table `accountstatus`
--

CREATE TABLE `accountstatus` (
  `accountStatus` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `accountstatus`
--

INSERT INTO `accountstatus` (`accountStatus`) VALUES
('Unverified'),
('Verified');

-- --------------------------------------------------------

--
-- Table structure for table `file`
--

CREATE TABLE `file` (
  `fileID` int(11) NOT NULL,
  `accountID` int(11) NOT NULL,
  `fileName` varchar(200) NOT NULL,
  `fileURL` varchar(255) NOT NULL,
  `publicURL` varchar(255) DEFAULT NULL,
  `fileType` varchar(10) NOT NULL,
  `fileSize` int(11) NOT NULL,
  `fileStatus` varchar(50) DEFAULT NULL,
  `filePermission` varchar(50) NOT NULL DEFAULT 'private',
  `uploadDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expiryDate` datetime DEFAULT NULL,
  `downloadTimes` int(11) NOT NULL DEFAULT '0',
  `aesKey` varchar(255) NOT NULL,
  `hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `file`
--

INSERT INTO `file` (`fileID`, `accountID`, `fileName`, `fileURL`, `publicURL`, `fileType`, `fileSize`, `fileStatus`, `filePermission`, `uploadDate`, `expiryDate`, `downloadTimes`, `aesKey`, `hash`) VALUES
(1, 2, 'car', 'uploads/2_20171021-035907pm_car.jpg', NULL, 'jpg', 75653, NULL, 'private', '2017-10-21 21:59:07', NULL, 0, 'keys/aes/car_2017-10-21_15-59-07_aes.key', '5179ca8229edfa9be5fc6d6b2621493518fec5c63a1fc7b009d2ada81c5cb4bf'),
(2, 2, 'Red_Rose_Black_BG', 'uploads/2_20171021-035910pm_Red_Rose_Black_BG.png', NULL, 'png', 259013, NULL, 'private', '2017-10-21 21:59:10', NULL, 0, 'keys/aes/Red_Rose_Black_BG_2017-10-21_15-59-10_aes.key', 'c7f0920c119c7a111aa46ce87e18e1ffdb77583c2b5fd1a903968cc5da20c4a9'),
(3, 3, 'report', 'uploads/3_20171021-040054pm_report.txt', NULL, 'txt', 69, NULL, 'private', '2017-10-21 22:00:54', NULL, 0, 'keys/aes/report_2017-10-21_16-00-54_aes.key', 'a6a590cc2e49fd4d2494c6734f5d938c3095f7af8c9d505b4e04849235922bcd'),
(4, 3, 'Wet-red-rose', 'uploads/3_20171021-040056pm_Wet-red-rose.png', NULL, 'png', 1536581, NULL, 'private', '2017-10-21 22:00:56', NULL, 0, 'keys/aes/Wet-red-rose_2017-10-21_16-00-56_aes.key', '6c2bf469e47f19e944003d3fb342b07ed5aecb5e078b3ee2640d4b1c50f9f513'),
(5, 2, 'cat', 'uploads/2_20171023-104421am_cat.jpg', 'uploads/public/2_20171023-104421am_cat.jpg', 'jpg', 86469, NULL, 'Public', '2017-10-23 16:44:21', '2017-10-31 16:44:00', 0, 'keys/aes/cat_2017-10-23_10-44-21_aes.key', '9361dd2ba71d3b05b81d6a0e27b65fd3fa2ce3bb9073e20b1688ce86c6b29219');

-- --------------------------------------------------------

--
-- Table structure for table `filepermission`
--

CREATE TABLE `filepermission` (
  `filePermission` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `filepermission`
--

INSERT INTO `filepermission` (`filePermission`) VALUES
('Private'),
('Public');

-- --------------------------------------------------------

--
-- Table structure for table `filesharing`
--

CREATE TABLE `filesharing` (
  `fileSharingID` int(11) NOT NULL,
  `fileID` int(11) NOT NULL,
  `accountID` int(11) NOT NULL,
  `invitationAccepted` tinyint(1) NOT NULL DEFAULT '0',
  `eAesKey` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `filesharing`
--

INSERT INTO `filesharing` (`fileSharingID`, `fileID`, `accountID`, `invitationAccepted`, `eAesKey`) VALUES
(1, 1, 3, 1, 'keys/eAes/1_3_2017-10-21_16-05-49_eAes.key'),
(2, 3, 2, 1, 'keys/eAes/3_2_2017-10-21_16-07-31_eAes.key');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`accountID`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`),
  ADD KEY `fk_Account_AccountStatus1_idx` (`accountStatus`);

--
-- Indexes for table `accountstatus`
--
ALTER TABLE `accountstatus`
  ADD PRIMARY KEY (`accountStatus`);

--
-- Indexes for table `file`
--
ALTER TABLE `file`
  ADD PRIMARY KEY (`fileID`),
  ADD KEY `fk_File_Account1_idx` (`accountID`),
  ADD KEY `fk_File_FilePermission1_idx` (`filePermission`);

--
-- Indexes for table `filepermission`
--
ALTER TABLE `filepermission`
  ADD PRIMARY KEY (`filePermission`);

--
-- Indexes for table `filesharing`
--
ALTER TABLE `filesharing`
  ADD PRIMARY KEY (`fileSharingID`),
  ADD KEY `fk_FileSharing_Account_idx` (`accountID`),
  ADD KEY `fk_FileSharing_File1_idx` (`fileID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `accountID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `file`
--
ALTER TABLE `file`
  MODIFY `fileID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `filesharing`
--
ALTER TABLE `filesharing`
  MODIFY `fileSharingID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account`
--
ALTER TABLE `account`
  ADD CONSTRAINT `fk_Account_AccountStatus1` FOREIGN KEY (`accountStatus`) REFERENCES `accountstatus` (`accountStatus`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `filesharing`
--
ALTER TABLE `filesharing`
  ADD CONSTRAINT `fk_FileSharing_Account` FOREIGN KEY (`accountID`) REFERENCES `account` (`accountID`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_FileSharing_File1` FOREIGN KEY (`fileID`) REFERENCES `file` (`fileID`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
