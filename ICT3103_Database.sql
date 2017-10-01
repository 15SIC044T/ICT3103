-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 01, 2017 at 02:59 AM
-- Server version: 5.6.26
-- PHP Version: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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

CREATE TABLE IF NOT EXISTS `account` (
  `accountID` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `accountStatus` varchar(50) NOT NULL,
  `profileImage` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`accountID`, `name`, `email`, `password`, `phone`, `accountStatus`, `profileImage`) VALUES
(1, 'Xinyi', '15SIC044T@sit.singaporetech.edu.sg', 'password', '12345678', 'Verified', NULL),
(2, 'Student', 'spammms@outlook.com', 'password', '12345678', 'Verified', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `accountstatus`
--

CREATE TABLE IF NOT EXISTS `accountstatus` (
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

CREATE TABLE IF NOT EXISTS `file` (
  `fileID` int(11) NOT NULL,
  `accountID` int(11) NOT NULL,
  `fileName` varchar(200) NOT NULL,
  `fileURL` varchar(255) NOT NULL,
  `fileType` varchar(10) NOT NULL,
  `fileSize` int(11) NOT NULL,
  `fileStatus` varchar(50) DEFAULT NULL,
  `filePermission` varchar(50) NOT NULL DEFAULT 'private',
  `uploadDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expiryDate` datetime DEFAULT NULL,
  `downloadTimes` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `file`
--

INSERT INTO `file` (`fileID`, `accountID`, `fileName`, `fileURL`, `fileType`, `fileSize`, `fileStatus`, `filePermission`, `uploadDate`, `expiryDate`, `downloadTimes`) VALUES
(1, 1, '1_20171001-010552am_zyra_LoL', 'uploads/1_20171001-010552am_zyra_LoL.png', 'png', 424618, NULL, 'private', '2017-10-01 07:05:52', NULL, 0),
(2, 1, 'Red_Rose_Black_BG', 'uploads/1_20171001-010719am_Red_Rose_Black_BG.png', 'png', 194232, NULL, 'private', '2017-10-01 07:07:19', NULL, 0),
(3, 1, 'Red_Rose_Book', 'uploads/1_20171001-011752am_Red_Rose_Book.png', 'png', 526864, NULL, 'private', '2017-10-01 07:17:52', NULL, 0),
(4, 1, 'Red-Rose-Pictures-1', 'uploads/1_20171001-011800am_Red-Rose-Pictures-1.png', 'png', 1048576, NULL, 'private', '2017-10-01 07:18:00', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `filepermission`
--

CREATE TABLE IF NOT EXISTS `filepermission` (
  `filePermission` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `filepermission`
--

INSERT INTO `filepermission` (`filePermission`) VALUES
('Private'),
('Public'),
('Unlist');

-- --------------------------------------------------------

--
-- Table structure for table `filesharing`
--

CREATE TABLE IF NOT EXISTS `filesharing` (
  `fileSharingID` int(11) NOT NULL,
  `fileID` int(11) NOT NULL,
  `accountID` int(11) NOT NULL,
  `invitationAccepted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  MODIFY `accountID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `file`
--
ALTER TABLE `file`
  MODIFY `fileID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `filesharing`
--
ALTER TABLE `filesharing`
  MODIFY `fileSharingID` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `account`
--
ALTER TABLE `account`
  ADD CONSTRAINT `fk_Account_AccountStatus1` FOREIGN KEY (`accountStatus`) REFERENCES `accountstatus` (`accountStatus`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `file`
--
ALTER TABLE `file`
  ADD CONSTRAINT `fk_File_Account1` FOREIGN KEY (`accountID`) REFERENCES `account` (`accountID`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_File_FilePermission1` FOREIGN KEY (`filePermission`) REFERENCES `filepermission` (`filePermission`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `filesharing`
--
ALTER TABLE `filesharing`
  ADD CONSTRAINT `fk_FileSharing_Account` FOREIGN KEY (`accountID`) REFERENCES `account` (`accountID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_FileSharing_File1` FOREIGN KEY (`fileID`) REFERENCES `file` (`fileID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
