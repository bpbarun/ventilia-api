-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 10, 2020 at 07:22 PM
-- Server version: 5.7.32-0ubuntu0.18.04.1
-- PHP Version: 7.2.24-0ubuntu0.18.04.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blinker`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE `activity` (
  `act_id` int(11) NOT NULL,
  `act_name` varchar(255) NOT NULL,
  `act_desc` text NOT NULL,
  `pdept_id` text NOT NULL,
  `sdept_id` text NOT NULL,
  `start_date` datetime NOT NULL,
  `alert_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `close_date` datetime NOT NULL,
  `state` int(2) NOT NULL,
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  `modified_on` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `modified_by` int(11) NOT NULL,
  `is_active` tinyint(3) NOT NULL,
  `is_deleted` tinyint(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `activity`
--

INSERT INTO `activity` (`act_id`, `act_name`, `act_desc`, `pdept_id`, `sdept_id`, `start_date`, `alert_date`, `end_date`, `close_date`, `state`, `created_on`, `created_by`, `modified_on`, `modified_by`, `is_active`, `is_deleted`) VALUES
(1, 'Fresher Party', 'Fresher Party for 2020 Bathch', '1,2', '3,4', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, '2020-11-10 17:18:34', 0, '0000-00-00 00:00:00', 0, 0, 0),
(2, 'Fresher Party2', 'Fresher Party for 2021 Bathch', '1,2', '3,4', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, '2020-11-10 17:19:10', 0, '2020-11-10 17:19:27', 0, 0, 0),
(3, 'Fresher Party', 'Fresher Party for 2020 Bathch', '1,2', '3,4', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, '2020-11-10 18:46:16', 0, '0000-00-00 00:00:00', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `auth_token`
--

CREATE TABLE `auth_token` (
  `token_id` int(11) NOT NULL,
  `token_code` varchar(30) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL,
  `expire_on` datetime NOT NULL,
  `is_active` int(3) NOT NULL,
  `is_deleted` int(3) NOT NULL,
  `last_login` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `auth_token`
--

INSERT INTO `auth_token` (`token_id`, `token_code`, `user_id`, `created_on`, `expire_on`, `is_active`, `is_deleted`, `last_login`, `ip`) VALUES
(323, 'b847a7d40410ce34', 100, '2019-11-25 12:15:47', '2020-11-25 12:15:47', 0, 0, '2019-11-25 12:15:47', '::1'),
(533, '394a464f897e1035', 5, '2020-05-24 14:57:54', '2020-05-24 20:58:30', 0, 0, '2020-05-24 13:57:54', '127.0.0.1'),
(618, '728ea2b35c663ab9', 1, '2020-11-10 11:47:03', '2020-11-10 17:47:03', 0, 0, '2020-11-10 11:47:03', '::1'),
(619, '4fc6b6817d6ac053', 1, '2020-11-10 11:50:21', '2020-11-10 17:50:21', 0, 0, '2020-11-10 11:50:21', '::1'),
(620, '90f7afc101164b45', 1, '2020-11-10 11:50:33', '2020-11-10 17:51:27', 0, 0, '2020-11-10 11:50:33', '::1'),
(621, 'd47ca7ac5a6ccdfc', 1, '2020-11-10 11:51:18', '2020-11-10 17:51:18', 0, 0, '2020-11-10 11:51:18', '::1'),
(622, '6d7f7d5720facca8', 1, '2020-11-10 17:11:36', '2020-11-11 01:19:25', 0, 0, '2020-11-10 17:11:36', '::1');

-- --------------------------------------------------------

--
-- Table structure for table `auth_user`
--

CREATE TABLE `auth_user` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(30) NOT NULL,
  `user_role` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL,
  `is_active` tinyint(3) NOT NULL,
  `is_delete` tinyint(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `auth_user`
--

INSERT INTO `auth_user` (`user_id`, `user_name`, `user_role`, `password`, `is_active`, `is_delete`) VALUES
(1, 'admin', 'admin', '12345', 1, 0),
(2, 'barun', 'admin', '12345', 1, 0),
(3, 'displayfort', 'admin', 'displayfort', 0, 0),
(4, 'token', 'admin', 'token', 1, 0),
(5, 'wayfinding', 'admin', '1234', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `dept`
--

CREATE TABLE `dept` (
  `dept_id` int(11) NOT NULL,
  `dept_name` varchar(50) NOT NULL,
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  `modified_on` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `modified_by` int(11) NOT NULL,
  `is_active` tinyint(3) NOT NULL,
  `is_deleted` tinyint(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL,
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  `modified_on` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `is_active` tinyint(3) NOT NULL,
  `is_deleted` tinyint(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `user_fname` varchar(30) NOT NULL,
  `user_lname` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `role_id` int(11) NOT NULL,
  `dept_id` int(11) NOT NULL,
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  `modified_on` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `modified_by` int(11) NOT NULL,
  `is_active` tinyint(3) NOT NULL,
  `is_deleted` tinyint(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_access`
--

CREATE TABLE `user_access` (
  `access_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `module` varchar(50) NOT NULL,
  `access` text NOT NULL,
  `is_active` tinyint(3) NOT NULL,
  `is_delete` tinyint(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_access`
--

INSERT INTO `user_access` (`access_id`, `user_id`, `module`, `access`, `is_active`, `is_delete`) VALUES
(1, 4, 'token', '{\"Token Detail\":\"token/allToken\"}', 1, 0),
(2, 2, 'report', '{\"vehicle Report\":\"report/vehicleReport\",\"Export Data\":\"report/export\"}', 1, 0),
(3, 1, 'token', '{\"Generate Token\":\"token/allToken\",\"Token Screen\":\"token/serveToken\",\"Subcounter\":\"token/allSubCounter\",\"counter\":\"token/allCounter\"}', 1, 0),
(5, 1, 'feedback', '{\"Feedback\":\"feedback/\",\"Manage Feedback\":\"feedback/allFeedbackType\"}', 1, 0),
(6, 2, 'feedback', '{\"Feedback\":\"feedback/\",\"Manage Feedback\":\"feedback/allFeedbackType\"}', 1, 0),
(7, 1, 'Multilingual', '{\"Multilingual\":\"multilingual/\"}', 1, 0),
(8, 2, 'Multilingual', '{\"Multilingual\":\"multilingual/\"}', 1, 0),
(9, 3, 'feedback', '{\"Feedback\":\"feedback/\",\"Manage Feedback\":\"feedback/allFeedbackType\"}', 1, 0),
(10, 5, 'Event', '{\"Event\":\"event/\"}', 1, 0),
(11, 5, 'Offer', '{\"Offer\":\"offer/\"}', 1, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`act_id`);

--
-- Indexes for table `auth_token`
--
ALTER TABLE `auth_token`
  ADD PRIMARY KEY (`token_id`);

--
-- Indexes for table `auth_user`
--
ALTER TABLE `auth_user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `dept`
--
ALTER TABLE `dept`
  ADD PRIMARY KEY (`dept_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_access`
--
ALTER TABLE `user_access`
  ADD PRIMARY KEY (`access_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity`
--
ALTER TABLE `activity`
  MODIFY `act_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `auth_token`
--
ALTER TABLE `auth_token`
  MODIFY `token_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=623;
--
-- AUTO_INCREMENT for table `auth_user`
--
ALTER TABLE `auth_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `dept`
--
ALTER TABLE `dept`
  MODIFY `dept_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_access`
--
ALTER TABLE `user_access`
  MODIFY `access_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
