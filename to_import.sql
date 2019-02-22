-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 22, 2019 at 06:00 PM
-- Server version: 10.1.28-MariaDB
-- PHP Version: 7.1.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blank`
--

-- --------------------------------------------------------

--
-- Table structure for table `app_list`
--

CREATE TABLE `app_list` (
  `id` int(11) NOT NULL,
  `name` text COLLATE utf8mb4_bin NOT NULL,
  `author` text COLLATE utf8mb4_bin NOT NULL,
  `path_title` text COLLATE utf8mb4_bin NOT NULL,
  `description` text COLLATE utf8mb4_bin NOT NULL,
  `changelog` text COLLATE utf8mb4_bin NOT NULL,
  `icon` text COLLATE utf8mb4_bin NOT NULL,
  `path` text COLLATE utf8mb4_bin NOT NULL,
  `type` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `version` text COLLATE utf8mb4_bin NOT NULL,
  `down_total` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `app_log`
--

CREATE TABLE `app_log` (
  `id` int(11) NOT NULL,
  `username` text COLLATE utf8mb4_bin NOT NULL,
  `app_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `chat_dev`
--

CREATE TABLE `chat_dev` (
  `id` bigint(20) NOT NULL,
  `colour` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `username` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `sender` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `recep` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `ip` char(120) COLLATE utf8mb4_bin NOT NULL DEFAULT '00.00.00',
  `message` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `bot` int(11) NOT NULL,
  `time` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `chat_gamer`
--

CREATE TABLE `chat_gamer` (
  `id` bigint(20) NOT NULL,
  `colour` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `username` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `sender` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `recep` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `ip` char(120) COLLATE utf8mb4_bin NOT NULL DEFAULT '00.00.00',
  `message` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `bot` int(11) NOT NULL,
  `time` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `chat_original`
--

CREATE TABLE `chat_original` (
  `id` bigint(20) NOT NULL,
  `colour` text COLLATE utf8mb4_bin NOT NULL,
  `username` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `sender` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `recep` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `ip` char(120) COLLATE utf8mb4_bin NOT NULL DEFAULT '00.00.00',
  `message` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `bot` int(11) NOT NULL,
  `time` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `chat_rp`
--

CREATE TABLE `chat_rp` (
  `id` bigint(20) NOT NULL,
  `colour` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `username` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `sender` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `recep` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `ip` char(120) COLLATE utf8mb4_bin NOT NULL DEFAULT '00.00.00',
  `message` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `bot` int(11) NOT NULL,
  `time` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `chat_staff`
--

CREATE TABLE `chat_staff` (
  `id` bigint(20) NOT NULL,
  `colour` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `username` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `sender` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `recep` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `ip` char(120) COLLATE utf8mb4_bin NOT NULL DEFAULT '00.00.00',
  `message` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `bot` int(11) NOT NULL,
  `time` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `link_verification`
--

CREATE TABLE `link_verification` (
  `id` int(11) NOT NULL,
  `site` text COLLATE utf8mb4_bin NOT NULL,
  `message` text COLLATE utf8mb4_bin NOT NULL,
  `ver_type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `logger`
--

CREATE TABLE `logger` (
  `id` int(11) NOT NULL,
  `user` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `userinvolved` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `activity` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `reason` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` bigint(20) NOT NULL,
  `username` text COLLATE utf8mb4_bin NOT NULL,
  `password` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `ip` char(120) COLLATE utf8mb4_bin NOT NULL DEFAULT '00.00.00',
  `token` text COLLATE utf8mb4_bin NOT NULL,
  `rbe_token` text COLLATE utf8mb4_bin NOT NULL,
  `pin` text COLLATE utf8mb4_bin NOT NULL,
  `useragent` text COLLATE utf8mb4_bin NOT NULL,
  `profile` text COLLATE utf8mb4_bin NOT NULL,
  `grouptype` text COLLATE utf8mb4_bin NOT NULL,
  `website` text COLLATE utf8mb4_bin NOT NULL,
  `avatar` text COLLATE utf8mb4_bin NOT NULL,
  `points` bigint(20) NOT NULL,
  `pointcount` bigint(11) NOT NULL,
  `pms` int(11) NOT NULL,
  `icon` text COLLATE utf8mb4_bin NOT NULL,
  `colour` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `colourdis` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `warns` int(11) NOT NULL,
  `grammar` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `disabled` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `disabledreason` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `disabledtime` text COLLATE utf8mb4_bin NOT NULL,
  `disableduser` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `disabledcode` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `flood` int(11) NOT NULL,
  `admin` int(11) NOT NULL DEFAULT '5',
  `priv` set('chat_mod','links_mod','community_manager','owner') COLLATE utf8mb4_bin DEFAULT NULL,
  `verification` int(11) NOT NULL,
  `postcount` int(11) NOT NULL DEFAULT '0',
  `kicked` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `status_auto` int(11) NOT NULL,
  `time` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `origchattime` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `rpchattime` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `staffchattime` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `devchattime` mediumtext COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `notification_system`
--

CREATE TABLE `notification_system` (
  `id` int(11) NOT NULL,
  `username` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `message` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `receiverusername` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `readyet` int(11) NOT NULL,
  `url` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `time` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `pm_system`
--

CREATE TABLE `pm_system` (
  `id` bigint(20) NOT NULL,
  `senderuid` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `sender` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `receiveruid` int(11) NOT NULL,
  `receiver` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `subject` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `message` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `read_pm` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `profile_comments`
--

CREATE TABLE `profile_comments` (
  `id` bigint(20) NOT NULL,
  `userprofile` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `uid` int(11) NOT NULL,
  `user` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `ip` varchar(20) COLLATE utf8mb4_bin NOT NULL DEFAULT '0.0.0',
  `colour` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `message` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `time` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `date` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `status_comments`
--

CREATE TABLE `status_comments` (
  `id` bigint(20) NOT NULL,
  `sid` bigint(20) NOT NULL,
  `uid` bigint(20) NOT NULL,
  `user` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `ip` varchar(100) COLLATE utf8mb4_bin NOT NULL DEFAULT '0.0.0.0.0',
  `colour` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `message` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `comnt` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `time` bigint(20) NOT NULL,
  `date` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `status_likes`
--

CREATE TABLE `status_likes` (
  `id` bigint(20) NOT NULL,
  `sid` bigint(20) NOT NULL,
  `uid` bigint(20) NOT NULL,
  `username` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `ip` varchar(20) COLLATE utf8mb4_bin NOT NULL DEFAULT '00.00.00.00',
  `comnt` int(11) NOT NULL,
  `time` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `status_log`
--

CREATE TABLE `status_log` (
  `id` bigint(20) NOT NULL,
  `username` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `ip` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `colour` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `message` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `time` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `date` mediumtext COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `app_list`
--
ALTER TABLE `app_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_log`
--
ALTER TABLE `app_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chat_dev`
--
ALTER TABLE `chat_dev`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chat_gamer`
--
ALTER TABLE `chat_gamer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chat_original`
--
ALTER TABLE `chat_original`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chat_rp`
--
ALTER TABLE `chat_rp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chat_staff`
--
ALTER TABLE `chat_staff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `link_verification`
--
ALTER TABLE `link_verification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logger`
--
ALTER TABLE `logger`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_system`
--
ALTER TABLE `notification_system`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pm_system`
--
ALTER TABLE `pm_system`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profile_comments`
--
ALTER TABLE `profile_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `status_comments`
--
ALTER TABLE `status_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `status_likes`
--
ALTER TABLE `status_likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `status_log`
--
ALTER TABLE `status_log`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `app_list`
--
ALTER TABLE `app_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `app_log`
--
ALTER TABLE `app_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `chat_dev`
--
ALTER TABLE `chat_dev`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `chat_gamer`
--
ALTER TABLE `chat_gamer`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_original`
--
ALTER TABLE `chat_original`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=745;

--
-- AUTO_INCREMENT for table `chat_rp`
--
ALTER TABLE `chat_rp`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `chat_staff`
--
ALTER TABLE `chat_staff`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `link_verification`
--
ALTER TABLE `link_verification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `logger`
--
ALTER TABLE `logger`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `notification_system`
--
ALTER TABLE `notification_system`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pm_system`
--
ALTER TABLE `pm_system`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `profile_comments`
--
ALTER TABLE `profile_comments`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `status_comments`
--
ALTER TABLE `status_comments`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `status_likes`
--
ALTER TABLE `status_likes`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `status_log`
--
ALTER TABLE `status_log`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
