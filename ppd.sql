-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 17, 2021 at 08:34 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ppd`
--

-- --------------------------------------------------------

--
-- Table structure for table `ppd_api`
--

CREATE TABLE `ppd_api` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `date` int(11) NOT NULL DEFAULT 0,
  `last_update` int(11) NOT NULL,
  `permissions` text DEFAULT NULL,
  `ip_address` mediumtext DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ppd_articles`
--

CREATE TABLE `ppd_articles` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `content` text DEFAULT NULL,
  `category` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL DEFAULT 0,
  `date` int(11) NOT NULL,
  `last_update` int(11) NOT NULL DEFAULT 0,
  `views` int(11) NOT NULL DEFAULT 0,
  `public` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ppd_attachments`
--

CREATE TABLE `ppd_attachments` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `enc` varchar(200) NOT NULL,
  `filetype` varchar(200) DEFAULT NULL,
  `article_id` int(11) NOT NULL DEFAULT 0,
  `ticket_id` int(11) NOT NULL DEFAULT 0,
  `msg_id` int(11) NOT NULL DEFAULT 0,
  `filesize` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ppd_calendar`
--

CREATE TABLE `ppd_calendar` (
  `calendar_date` varchar(10) NOT NULL,
  `staff_id` int(11) NOT NULL DEFAULT 0,
  `note` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ppd_canned_response`
--

CREATE TABLE `ppd_canned_response` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `message` mediumtext DEFAULT NULL,
  `position` int(11) NOT NULL DEFAULT 1,
  `date` int(11) NOT NULL DEFAULT 0,
  `last_update` int(11) NOT NULL DEFAULT 0,
  `staff_id` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ppd_category`
--

CREATE TABLE `ppd_category` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `position` int(11) NOT NULL,
  `parent` int(11) NOT NULL DEFAULT 0,
  `public` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ppd_config`
--

CREATE TABLE `ppd_config` (
  `id` int(11) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `site_name` varchar(255) DEFAULT NULL,
  `windows_title` varchar(255) DEFAULT NULL,
  `page_size` int(11) NOT NULL DEFAULT 0,
  `date_format` varchar(100) DEFAULT NULL,
  `timezone` varchar(100) DEFAULT NULL,
  `maintenance` tinyint(1) NOT NULL DEFAULT 0,
  `maintenance_message` text DEFAULT NULL,
  `recaptcha` tinyint(1) NOT NULL DEFAULT 0,
  `recaptcha_sitekey` varchar(255) DEFAULT NULL,
  `recaptcha_privatekey` varchar(255) DEFAULT NULL,
  `login_attempt` int(11) NOT NULL DEFAULT 0,
  `login_attempt_minutes` int(11) NOT NULL DEFAULT 1,
  `reply_order` enum('asc','desc') NOT NULL DEFAULT 'asc',
  `tickets_page` int(11) NOT NULL DEFAULT 1,
  `tickets_replies` int(11) NOT NULL DEFAULT 1,
  `overdue_time` int(11) NOT NULL DEFAULT 48,
  `ticket_autoclose` int(11) NOT NULL DEFAULT 96,
  `ticket_attachment` tinyint(1) NOT NULL DEFAULT 0,
  `ticket_attachment_number` int(11) NOT NULL DEFAULT 1,
  `ticket_file_size` double NOT NULL DEFAULT 2,
  `ticket_file_type` mediumtext DEFAULT NULL,
  `kb_articles` int(11) NOT NULL DEFAULT 4,
  `kb_maxchar` int(11) NOT NULL DEFAULT 200,
  `kb_popular` int(11) NOT NULL DEFAULT 4,
  `kb_latest` int(11) NOT NULL DEFAULT 4,
  `kb_attachment` tinyint(1) NOT NULL DEFAULT 0,
  `kb_attachment_number` int(11) NOT NULL DEFAULT 1,
  `kb_file_size` double NOT NULL DEFAULT 2,
  `kb_file_type` mediumtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ppd_custom_fields`
--

CREATE TABLE `ppd_custom_fields` (
  `id` int(11) NOT NULL,
  `type` varchar(100) NOT NULL,
  `topics` mediumtext DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `required` tinyint(1) NOT NULL DEFAULT 0,
  `display` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ppd_emails`
--

CREATE TABLE `ppd_emails` (
  `id` int(11) NOT NULL,
  `default` tinyint(1) NOT NULL DEFAULT 0,
  `name` varchar(200) DEFAULT NULL,
  `email` varchar(200) NOT NULL,
  `created` int(11) NOT NULL DEFAULT 0,
  `last_update` int(11) NOT NULL DEFAULT 0,
  `outgoing_type` enum('php','smtp') NOT NULL,
  `smtp_host` varchar(200) DEFAULT NULL,
  `smtp_port` varchar(10) DEFAULT NULL,
  `smtp_encryption` varchar(10) DEFAULT NULL,
  `smtp_username` varchar(200) DEFAULT NULL,
  `smtp_password` varchar(200) DEFAULT NULL,
  `incoming_type` varchar(10) DEFAULT NULL,
  `imap_host` varchar(200) DEFAULT NULL,
  `imap_port` varchar(10) DEFAULT NULL,
  `imap_username` varchar(200) DEFAULT NULL,
  `imap_password` varchar(200) DEFAULT NULL,
  `imap_minutes` double NOT NULL DEFAULT 5
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ppd_emails_tpl`
--

CREATE TABLE `ppd_emails_tpl` (
  `id` varchar(255) NOT NULL,
  `position` smallint(6) NOT NULL,
  `name` varchar(255) NOT NULL,
  `subject_id` varchar(255) NOT NULL,
  `message_id` mediumtext NOT NULL,
  `subject_en` varchar(255) NOT NULL,
  `message_en` mediumtext NOT NULL,
  `last_update` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ppd_login_attempt`
--

CREATE TABLE `ppd_login_attempt` (
  `ip` varchar(200) NOT NULL,
  `attempts` int(11) NOT NULL DEFAULT 0,
  `date` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ppd_login_log`
--

CREATE TABLE `ppd_login_log` (
  `id` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL DEFAULT 0,
  `ip` varchar(255) NOT NULL,
  `agent` varchar(255) NOT NULL,
  `success` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ppd_priority`
--

CREATE TABLE `ppd_priority` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `color` varchar(10) NOT NULL DEFAULT '#000000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ppd_staff`
--

CREATE TABLE `ppd_staff` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `registration` int(11) NOT NULL DEFAULT 0,
  `login` int(11) NOT NULL DEFAULT 0,
  `last_login` int(11) NOT NULL DEFAULT 0,
  `timezone` varchar(255) DEFAULT NULL,
  `signature` longtext DEFAULT NULL,
  `avatar` varchar(200) DEFAULT NULL,
  `two_factor` varchar(255) DEFAULT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT 0,
  `viewer` tinyint(1) NOT NULL DEFAULT 0,
  `supervisor` tinyint(1) NOT NULL DEFAULT 0,
  `operator` tinyint(1) NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ppd_staff`
--

INSERT INTO `ppd_staff` (`id`, `username`, `password`, `fullname`, `email`, `token`, `registration`, `login`, `last_login`, `timezone`, `signature`, `avatar`, `two_factor`, `admin`, `viewer`, `supervisor`, `operator`, `active`) VALUES
(1, 'admin', '$2y$10$rQ9nxGjErsCATJtm1Xfa..ZE3TEBneRTytLxkOQfB0b0YFrVfFzaK', 'Admin PPD Online', 'admin@admin.com', '454a48b706f3b687cc90e9bc1a5c99a535b8daf1', 1621221210, 1623379193, 1623121951, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ppd_status`
--

CREATE TABLE `ppd_status` (
  `id` int(11) NOT NULL,
  `status_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `ppd_tickets`
--

CREATE TABLE `ppd_tickets` (
  `id` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `topic` int(11) NOT NULL,
  `priority_id` int(11) NOT NULL DEFAULT 0,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `subject` varchar(255) NOT NULL,
  `date` int(11) NOT NULL DEFAULT 0,
  `last_update` int(11) NOT NULL DEFAULT 0,
  `status` smallint(6) NOT NULL DEFAULT 1,
  `staff_id` int(11) NOT NULL DEFAULT 0,
  `replies` int(11) NOT NULL DEFAULT 0,
  `last_replier` tinyint(1) DEFAULT 0,
  `custom_vars` mediumtext DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ppd_tickets_messages`
--

CREATE TABLE `ppd_tickets_messages` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL DEFAULT 0,
  `date` int(11) NOT NULL DEFAULT 0,
  `customer` int(11) NOT NULL DEFAULT 1,
  `staff_id` int(11) NOT NULL DEFAULT 0,
  `message` text DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `email` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ppd_ticket_notes`
--

CREATE TABLE `ppd_ticket_notes` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ppd_topic`
--

CREATE TABLE `ppd_topic` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `public` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ppd_users`
--

CREATE TABLE `ppd_users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(250) NOT NULL DEFAULT 'Guest',
  `phone` varchar(20) NOT NULL,
  `in_rokanhulu` tinyint(4) NOT NULL DEFAULT 0,
  `email` varchar(250) NOT NULL,
  `last_login` int(11) NOT NULL DEFAULT 0,
  `token` varchar(255) DEFAULT NULL,
  `timezone` varchar(200) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ppd_api`
--
ALTER TABLE `ppd_api`
  ADD PRIMARY KEY (`id`),
  ADD KEY `token` (`token`);

--
-- Indexes for table `ppd_articles`
--
ALTER TABLE `ppd_articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category` (`category`);

--
-- Indexes for table `ppd_attachments`
--
ALTER TABLE `ppd_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `article_id` (`article_id`),
  ADD KEY `ticket_id` (`ticket_id`),
  ADD KEY `msg_id` (`msg_id`);

--
-- Indexes for table `ppd_calendar`
--
ALTER TABLE `ppd_calendar`
  ADD PRIMARY KEY (`calendar_date`);

--
-- Indexes for table `ppd_canned_response`
--
ALTER TABLE `ppd_canned_response`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ppd_category`
--
ALTER TABLE `ppd_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ppd_config`
--
ALTER TABLE `ppd_config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ppd_custom_fields`
--
ALTER TABLE `ppd_custom_fields`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ppd_emails`
--
ALTER TABLE `ppd_emails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ppd_emails_tpl`
--
ALTER TABLE `ppd_emails_tpl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ppd_login_attempt`
--
ALTER TABLE `ppd_login_attempt`
  ADD UNIQUE KEY `ip` (`ip`);

--
-- Indexes for table `ppd_login_log`
--
ALTER TABLE `ppd_login_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `ppd_priority`
--
ALTER TABLE `ppd_priority`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ppd_staff`
--
ALTER TABLE `ppd_staff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ppd_status`
--
ALTER TABLE `ppd_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ppd_tickets`
--
ALTER TABLE `ppd_tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category` (`category`),
  ADD KEY `topic` (`topic`);

--
-- Indexes for table `ppd_tickets_messages`
--
ALTER TABLE `ppd_tickets_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_id` (`ticket_id`);

--
-- Indexes for table `ppd_ticket_notes`
--
ALTER TABLE `ppd_ticket_notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ppd_topic`
--
ALTER TABLE `ppd_topic`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ppd_users`
--
ALTER TABLE `ppd_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `email` (`email`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ppd_api`
--
ALTER TABLE `ppd_api`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ppd_articles`
--
ALTER TABLE `ppd_articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ppd_attachments`
--
ALTER TABLE `ppd_attachments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ppd_canned_response`
--
ALTER TABLE `ppd_canned_response`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ppd_category`
--
ALTER TABLE `ppd_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ppd_config`
--
ALTER TABLE `ppd_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ppd_custom_fields`
--
ALTER TABLE `ppd_custom_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ppd_emails`
--
ALTER TABLE `ppd_emails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ppd_login_log`
--
ALTER TABLE `ppd_login_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ppd_priority`
--
ALTER TABLE `ppd_priority`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ppd_staff`
--
ALTER TABLE `ppd_staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ppd_tickets`
--
ALTER TABLE `ppd_tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ppd_tickets_messages`
--
ALTER TABLE `ppd_tickets_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ppd_ticket_notes`
--
ALTER TABLE `ppd_ticket_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ppd_topic`
--
ALTER TABLE `ppd_topic`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ppd_users`
--
ALTER TABLE `ppd_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
