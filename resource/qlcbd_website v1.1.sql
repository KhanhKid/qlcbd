-- phpMyAdmin SQL Dump
-- version 4.1.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 28, 2014 at 03:18 PM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `qlcbd_website`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `ocs_users_login`(
    IN i_username VARCHAR(255),
    IN i_password VARCHAR(255),
    OUT o_errcode INT,
    OUT o_errmsg NVARCHAR(255),
    OUT o_userid INT,
    OUT o_fullname VARCHAR(100))
BEGIN
 
    /*
        YOUR CODE HERE
    */
 
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `controller`
--

CREATE TABLE IF NOT EXISTS `controller` (
  `Controller_Name` varchar(32) NOT NULL,
  `Module_Name` varchar(32) DEFAULT NULL,
  `Controller_Display_Name` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`Controller_Name`),
  KEY `FK_of_module` (`Module_Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `module`
--

CREATE TABLE IF NOT EXISTS `module` (
  `Module_Name` varchar(32) NOT NULL,
  `Module_Display_Name` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`Module_Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `privilege`
--

CREATE TABLE IF NOT EXISTS `privilege` (
  `Privilege_Name` varchar(32) NOT NULL,
  `Controller_Name` varchar(32) DEFAULT NULL,
  `Privilege_Display_Name` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`Privilege_Name`),
  KEY `FK_of_controller` (`Controller_Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE IF NOT EXISTS `profile` (
  `UserID` int(11) NOT NULL,
  `Avatar_URL` varchar(254) DEFAULT NULL,
  `Surname` varchar(254) DEFAULT NULL,
  `Firstname` varchar(254) DEFAULT NULL,
  `Email` varchar(254) DEFAULT NULL,
  `Description` varchar(254) DEFAULT NULL,
  `Phone` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `Role_Name` varchar(32) NOT NULL,
  `Role_Display_Name` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`Role_Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`Role_Name`, `Role_Display_Name`) VALUES
('admin', 'Admin'),
('cadre', 'Cán Bộ'),
('chief', 'Trưởng/Phó Ban'),
('guest', 'Khách'),
('manager', 'Phó Bí Thư Khối XĐ Đoàn'),
('organizer_cadre', 'Cán Bộ Ban Tổ Chức'),
('permanent_cadre', 'Thường Trực');

-- --------------------------------------------------------

--
-- Table structure for table `role_privilege_relation`
--

CREATE TABLE IF NOT EXISTS `role_privilege_relation` (
  `Role_Name` varchar(32) NOT NULL,
  `Privilege_Name` varchar(32) NOT NULL,
  `isAllowed` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`Role_Name`,`Privilege_Name`),
  KEY `FK_for_role` (`Privilege_Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE IF NOT EXISTS `status` (
  `Status_Code` tinyint(4) NOT NULL,
  `Status_Name` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`Status_Code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`Status_Code`, `Status_Name`) VALUES
(-1, 'blocked'),
(0, 'unconfirmed'),
(1, 'actived');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `UserID` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(254) NOT NULL,
  `Password` varchar(254) DEFAULT NULL,
  `Identifier_Info` varchar(254) DEFAULT NULL,
  `Status_Code` tinyint(4) DEFAULT '1',
  `Role_Name` varchar(32) DEFAULT NULL,
  `Password_Key` varchar(254) NOT NULL DEFAULT 'dk',
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `Username` (`Username`),
  KEY `FK_have_role` (`Role_Name`),
  KEY `FK_have_status` (`Status_Code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `Username`, `Password`, `Identifier_Info`, `Status_Code`, `Role_Name`, `Password_Key`) VALUES
(1, 'admin', '66579284dd25f62cde52b036108c4bd6', NULL, 1, 'admin', 'dk'),
(10, 'manager', '5d03757598faa106b9d0d6c2a08eea43', NULL, 1, 'manager', '28/12/2013 09:12:21:1221'),
(15, 'dangkhoa', '337ab2818447d7027e59d462317c3105', NULL, 1, 'manager', '31/12/2013 10:12:16:1216'),
(16, 'phobithu', '8880d22d7f12eeabe27802c41532315e', '1', 1, 'admin', '31/12/2013 10:12:03:1203'),
(17, 'dinhthi', '55da665bf56dfa44015e440aa331e643', '4', 1, 'cadre', '31/12/2013 10:12:28:1228'),
(18, 'tuananh', 'a73b90b4555d62137a448d0a66743116', NULL, 1, 'permanent_cadre', '31/12/2013 10:12:47:1247'),
(19, 'xuanthu', 'ec0619b0129a36e676e70a5fb117e58f', NULL, 1, 'organizer_cadre', '13/01/2014 12:01:44:0144');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `controller`
--
ALTER TABLE `controller`
  ADD CONSTRAINT `FK_of_module` FOREIGN KEY (`Module_Name`) REFERENCES `module` (`Module_Name`);

--
-- Constraints for table `privilege`
--
ALTER TABLE `privilege`
  ADD CONSTRAINT `FK_of_controller` FOREIGN KEY (`Controller_Name`) REFERENCES `controller` (`Controller_Name`);

--
-- Constraints for table `profile`
--
ALTER TABLE `profile`
  ADD CONSTRAINT `FK_of_user` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`);

--
-- Constraints for table `role_privilege_relation`
--
ALTER TABLE `role_privilege_relation`
  ADD CONSTRAINT `FK_for_role` FOREIGN KEY (`Privilege_Name`) REFERENCES `privilege` (`Privilege_Name`),
  ADD CONSTRAINT `FK_have_privilege` FOREIGN KEY (`Role_Name`) REFERENCES `role` (`Role_Name`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_have_role` FOREIGN KEY (`Role_Name`) REFERENCES `role` (`Role_Name`),
  ADD CONSTRAINT `FK_have_status` FOREIGN KEY (`Status_Code`) REFERENCES `status` (`Status_Code`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
