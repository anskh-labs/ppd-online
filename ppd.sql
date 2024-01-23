SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
-- --------------------------------------------------------

--
-- Table structure for table `ppd_api`
--

CREATE TABLE `ppd_api` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `date` int NOT NULL DEFAULT '0',
  `last_update` int NOT NULL,
  `permissions` text,
  `ip_address` mediumtext,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `ppd_articles`
--

CREATE TABLE `ppd_articles` (
  `id` int NOT NULL,
  `title` varchar(200) NOT NULL,
  `content` text,
  `category` int NOT NULL,
  `staff_id` int NOT NULL DEFAULT '0',
  `date` int NOT NULL,
  `last_update` int NOT NULL DEFAULT '0',
  `views` int NOT NULL DEFAULT '0',
  `public` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `ppd_attachments`
--

CREATE TABLE `ppd_attachments` (
  `id` int NOT NULL,
  `name` varchar(200) NOT NULL,
  `enc` varchar(200) NOT NULL,
  `filetype` varchar(200) DEFAULT NULL,
  `article_id` int NOT NULL DEFAULT '0',
  `ticket_id` int NOT NULL DEFAULT '0',
  `msg_id` int NOT NULL DEFAULT '0',
  `filesize` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `ppd_calendar`
--

CREATE TABLE `ppd_calendar` (
  `calendar_date` varchar(10) NOT NULL,
  `staff_id` int NOT NULL DEFAULT '0',
  `note` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `ppd_canned_response`
--

CREATE TABLE `ppd_canned_response` (
  `id` int NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `message` mediumtext,
  `position` int NOT NULL DEFAULT '1',
  `date` int NOT NULL DEFAULT '0',
  `last_update` int NOT NULL DEFAULT '0',
  `staff_id` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `ppd_category`
--

CREATE TABLE `ppd_category` (
  `id` int NOT NULL,
  `name` varchar(200) NOT NULL,
  `position` int NOT NULL,
  `parent` int NOT NULL DEFAULT '0',
  `public` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `ppd_category`
--

INSERT INTO `ppd_category` (`id`, `name`, `position`, `parent`, `public`) VALUES
(1, 'Ekspor-Impor', 1, 0, 1),
(2, 'Energi', 2, 0, 1),
(3, 'Gender', 3, 0, 1),
(4, 'Geografi', 4, 0, 1),
(5, 'Harga Eceran', 5, 0, 1),
(6, 'Harga Perdagangan Besar', 6, 0, 1),
(7, 'Harga Produsen', 7, 0, 1),
(8, 'Hortikultura', 8, 0, 1),
(9, 'Inflasi', 9, 0, 1),
(10, 'Input-Output', 10, 0, 1),
(11, 'Indeks Pembangunan Manusia', 11, 0, 1),
(12, 'ITB-ITK', 12, 0, 1),
(13, 'Industri Besar Sedang', 13, 0, 1),
(14, 'Industri Mikro Kecil', 14, 0, 1),
(15, 'Kemiskinan dan Ketimpangan', 15, 0, 1),
(16, 'Kependudukan', 16, 0, 1),
(17, 'Kesehatan', 17, 0, 1),
(18, 'Konsumsi dan Pengeluaran', 18, 0, 1),
(19, 'Keuangan', 19, 0, 1),
(20, 'Komunikasi', 20, 0, 1),
(21, 'Konstruksi', 21, 0, 1),
(22, 'Kehutanan', 22, 0, 1),
(23, 'Lingkungan Hidup', 23, 0, 1),
(24, 'Neraca Arus Dana', 24, 0, 1),
(25, 'Neraca Sosial Ekonomi', 25, 0, 1),
(26, 'Nilai Tukar Petani', 26, 0, 1),
(27, 'Pemerintahan', 27, 0, 1),
(28, 'Pendidikan', 28, 0, 1),
(29, 'Perumahan', 29, 0, 1),
(30, 'Politik dan Keamanan', 30, 0, 1),
(31, 'Potensi Desa', 31, 0, 1),
(32, 'Pariwisata', 32, 0, 1),
(33, 'Perdagangan Dalam Negeri', 33, 0, 1),
(34, 'Produk Domestik Regional Bruto (Lapangan Usaha)', 34, 0, 1),
(35, 'Produk Domestik Regional Bruto (Pengeluaran)', 35, 0, 1),
(36, 'Perikanan', 36, 0, 1),
(37, 'Perkebunan', 37, 0, 1),
(38, 'Pertambangan', 38, 0, 1),
(39, 'Peternakan', 39, 0, 1),
(40, 'Sosial Budaya', 40, 0, 1),
(41, 'Tenaga Kerja', 41, 0, 1),
(42, 'Transportasi', 42, 0, 1),
(43, 'Tanaman Pangan', 43, 0, 1),
(44, 'Upah Buruh', 44, 0, 1),
(45, 'Usaha Mikro Kecil', 45, 0, 1),
(46, 'Umum', 46, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ppd_config`
--

CREATE TABLE `ppd_config` (
  `id` int NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `site_name` varchar(255) DEFAULT NULL,
  `windows_title` varchar(255) DEFAULT NULL,
  `page_size` int NOT NULL DEFAULT '0',
  `date_format` varchar(100) DEFAULT NULL,
  `timezone` varchar(100) DEFAULT NULL,
  `maintenance` tinyint(1) NOT NULL DEFAULT '0',
  `maintenance_message` text,
  `login_attempt` int NOT NULL DEFAULT '0',
  `login_attempt_minutes` int NOT NULL DEFAULT '1',
  `reply_order` enum('asc','desc') NOT NULL DEFAULT 'asc',
  `tickets_page` int NOT NULL DEFAULT '1',
  `tickets_replies` int NOT NULL DEFAULT '1',
  `overdue_time` int NOT NULL DEFAULT '48',
  `ticket_autoclose` int NOT NULL DEFAULT '96',
  `ticket_attachment` tinyint(1) NOT NULL DEFAULT '0',
  `ticket_attachment_number` int NOT NULL DEFAULT '1',
  `ticket_file_size` double NOT NULL DEFAULT '2',
  `ticket_file_type` mediumtext,
  `kb_articles` int NOT NULL DEFAULT '4',
  `kb_maxchar` int NOT NULL DEFAULT '200',
  `kb_popular` int NOT NULL DEFAULT '4',
  `kb_latest` int NOT NULL DEFAULT '4',
  `kb_attachment` tinyint(1) NOT NULL DEFAULT '0',
  `kb_attachment_number` int NOT NULL DEFAULT '1',
  `kb_file_size` double NOT NULL DEFAULT '2',
  `kb_file_type` mediumtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `ppd_config`
--

INSERT INTO `ppd_config` (`id`, `logo`, `site_name`, `windows_title`, `page_size`, `date_format`, `timezone`, `maintenance`, `maintenance_message`, `login_attempt`, `login_attempt_minutes`, `reply_order`, `tickets_page`, `tickets_replies`, `overdue_time`, `ticket_autoclose`, `ticket_attachment`, `ticket_attachment_number`, `ticket_file_size`, `ticket_file_type`, `kb_articles`, `kb_maxchar`, `kb_popular`, `kb_latest`, `kb_attachment`, `kb_attachment_number`, `kb_file_size`, `kb_file_type`) VALUES
(1, '', 'PPD-Online', 'Pusat Permintaan Data secara Elektronik', 10, 'd F Y H:i', 'Asia/Jakarta', 0, '<p>Maaf sedang dalam perbaikan/penambahan fitur. Akan tersedia sekitar 30 menit lagi.</p>', 3, 5, 'desc', 15, 15, 120, 144, 1, 1, 1, 'a:5:{i:0;s:3:\"jpg\";i:1;s:3:\"png\";i:2;s:3:\"pdf\";i:3;s:3:\"xls\";i:4;s:4:\"xlsx\";}', 2, 200, 3, 3, 1, 1, 1, 'a:5:{i:0;s:3:\"jpg\";i:1;s:3:\"png\";i:2;s:3:\"pdf\";i:3;s:3:\"xls\";i:4;s:4:\"xlsx\";}');

-- --------------------------------------------------------

--
-- Table structure for table `ppd_emails`
--

CREATE TABLE `ppd_emails` (
  `id` int NOT NULL,
  `default` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(200) DEFAULT NULL,
  `email` varchar(200) NOT NULL,
  `created` int NOT NULL DEFAULT '0',
  `last_update` int NOT NULL DEFAULT '0',
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
  `imap_minutes` double NOT NULL DEFAULT '5'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `ppd_emails`
--

INSERT INTO `ppd_emails` (`id`, `default`, `name`, `email`, `created`, `last_update`, `outgoing_type`, `smtp_host`, `smtp_port`, `smtp_encryption`, `smtp_username`, `smtp_password`, `incoming_type`, `imap_host`, `imap_port`, `imap_username`, `imap_password`, `imap_minutes`) VALUES
(1, 1, 'PST BPS Kabupaten Kampar', 'pst1406@bps.go.id', 1621221210, 1622876654, 'smtp', 'smtp.bps.go.id', '587', 'tls', 'pst1406', 'mboh', 'pop', 'pop3.bps.go.id', '995', 'pst1406', 'mboh', 5);

-- --------------------------------------------------------

--
-- Table structure for table `ppd_emails_tpl`
--

CREATE TABLE `ppd_emails_tpl` (
  `id` varchar(255) NOT NULL,
  `position` smallint NOT NULL,
  `name` varchar(255) NOT NULL,
  `subject_id` varchar(255) NOT NULL,
  `message_id` mediumtext NOT NULL,
  `subject_en` varchar(255) NOT NULL,
  `message_en` mediumtext NOT NULL,
  `last_update` int NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `ppd_emails_tpl`
--

INSERT INTO `ppd_emails_tpl` (`id`, `position`, `name`, `subject_id`, `message_id`, `subject_en`, `message_en`, `last_update`, `status`) VALUES
('autoresponse', 4, 'New Message Autoresponse', '[#%ticket_id%] %ticket_subject%', '<p>Halo %client_name%,</p>\r\n<p>Balasan Anda untuk permintaan dukungan #%ticket_id% telah dicatat.</p>\r\n<p>Rincian tiket <br />--------------------<br />ID Tiket: %ticket_id% <br />Subyek: %ticket_subject% <br />Topik: %ticket_topic% <br />Status: %ticket_status% <br />Prioritas: %ticket_priority% <br />Helpdesk: %support_url%</p>', '[#%ticket_id%] %ticket_subject%', '<p>Dear %client_name%,</p>\r\n<p>Your reply to support request #%ticket_id% has been noted.</p>\r\n<p>Ticket Details <br />--------------------<br />Ticket ID: %ticket_id% <br />Subject: %ticket_subject% <br />Topic: %ticket_topic% <br />Status: %ticket_status% <br />Priority: %ticket_priority% <br />Helpdesk: %support_url%</p>', 1622903573, 1),
('lost_password', 2, 'Lost password confirmation', 'Pemulihan kata sandi untuk %company_name%', '<p>Kami telah menerima permintaan untuk mengatur ulang kata sandi akun Anda untuk %company_name% helpdesk (%helpdesk_url%).</p>\r\n<p>Password baru Anda adalah: %client_password%</p>\r\n<p>Terimakasih, <br />%company_name% <br />Helpdesk: %support_url%</p>', 'Password recovery for %company_name%', '<p>We have received a request to reset your account password for the %company_name% helpdesk (%helpdesk_url%).</p>\r\n<p>Your new passsword is: %client_password%</p>\r\n<p>Thank you, <br />%company_name% <br />Helpdesk: %support_url%</p>', 1622902604, 0),
('new_ticket', 3, 'New ticket creation', '[#%ticket_id%] %ticket_subject%', '<p>Halo %client_name%,</p>\r\n<p>Terimakasih telah menghubungi kami. Ini adalah jawaban otomatis sebagai konfirmasi bahwa permintaan Anda telah diterima. Petugas kami akan memprosesnya sesegera mungkin.</p>\r\n<p>Untuk catatan Anda, rincian tiket tercantum di bawah ini. Saat membalas, pastikan bahwa ID tiket disimpan di baris subjek untuk memastikan bahwa balasan Anda dilacak dengan tepat.</p>\r\n<p>ID Tiket: %ticket_id% <br />Subjek: %ticket_subject% <br />Topik: %ticket_topic% <br />Status: %ticket_status% <br />Prioritas: %ticket_priority%</p>\r\n<p>Anda dapat memeriksa status atau membalas tiket ini secara online di: %support_url%</p>\r\n<p>Terimakasih, <br />%company_name%</p>', '[#%ticket_id%] %ticket_subject%', '<p>Dear %client_name%,</p>\r\n<p>Thank you for contacting us. This is an automated response confirming the receipt of your ticket. One of our agents will get back to you as soon as possible.</p>\r\n<p>For your records, the details of the ticket are listed below. When replying, please make sure that the ticket ID is kept in the subject line to ensure that your replies are tracked appropriately.</p>\r\n<p>Ticket ID: %ticket_id% <br />Subject: %ticket_subject% <br />Topic: %ticket_topic% <br />Status: %ticket_status% <br />Priority: %ticket_priority%</p>\r\n<p>You can check the status of or reply to this ticket online at: %support_url%</p>\r\n<p>Regards, <br />%company_name%</p>', 1622902954, 1),
('new_user', 1, 'Welcome email registration', 'Selamat datang di %company_name%', '<p>Halo %client_name%,</p>\r\n<p>Email ini adalah konfirmasi bahwa Anda telah terdaftar di pusat bantuan kami.</p>\r\n<p><strong>Alamat email:</strong> %client_email% <br /><strong>Nomor HP:</strong> %client_phone%</p>\r\n<p>Anda dapat mengunjungi kami untuk mencari artikel dan menghubungi kami setiap saat di:</p>\r\n<p>%support_url%</p>\r\n<p>Terimakasih telah mendaftar!</p>\r\n<p>%company_name%<br />Helpdesk: %support_url%</p>', 'Welcome to %company_name% helpdesk', '<p>Hello %client_name%,</p>\r\n<p>This email is confirmation that you are now registered at our helpdesk.</p>\r\n<p><strong>Registered email:</strong> %client_email% <br /><strong>Phone Number:</strong> %client_phone%</p>\r\n<p>You can visit the helpdesk to browse articles and contact us at any time:</p>\r\n<p>%support_url%</p>\r\n<p>Thank you for registering!</p>\r\n<p>%company_name%<br />Helpdesk: %support_url%</p>', 1640751706, 1),
('staff_reply', 5, 'Staff Reply', 'Re: [#%ticket_id%] %ticket_subject%', '<p>%message%</p>\r\n<p>-------------------------------------------------------------<br />Rincian Tiket<br />-------------------------------------------------------------<br /><strong>ID Tiket:</strong> %ticket_id% <br /><strong>Topik:</strong> %ticket_topic% <br /><strong>Status:</strong> %ticket_status% <br /><strong>Prioritas:</strong> %ticket_priority% <br /><strong>Helpdesk:</strong> %support_url%</p>', 'Re: [#%ticket_id%] %ticket_subject%', '<p>%message%</p>\r\n<p>-------------------------------------------------------------<br />Ticket Details<br />-------------------------------------------------------------<br /><strong>Ticket ID:</strong> %ticket_id% <br /><strong>Topic:</strong> %ticket_topic% <br /><strong>Status:</strong> %ticket_status% <br /><strong>Priority:</strong> %ticket_priority% <br /><strong>Helpdesk:</strong> %support_url%</p>', 1622903041, 1),
('staff_ticketnotification', 6, 'New ticket notification to staff', 'Pemberitahuan tiket baru', '<p>Halo %staff_name%,</p>\r\n<p>Tiket baru telah dibuat di departemen yang ditugaskan untuk Anda, silakan masuk ke panel staf untuk menjawabnya.</p>\r\n<p>Rincian Tiket<br />-------------------<br />ID Tiket: %ticket_id% <br />Subyek: %ticket_subject% <br />Topik: %ticket_topic% <br />Status: %ticket_status% <br />Prioritas: %ticket_priority% <br />Helpdesk: %support_url%<br />Staff URL: %support_url%id/staff</p>', 'New ticket notification', '<p>Dear %staff_name%,</p>\r\n<p>A new ticket has been created in department assigned for you, please login to staff panel to answer it.</p>\r\n<p>Ticket Details<br />-------------------<br />Ticket ID: %ticket_id% <br />Subject: %ticket_subject% <br />Topic: %ticket_topic% <br />Status: %ticket_status% <br />Priority: %ticket_priority% <br />Helpdesk: %support_url%<br />Staff URL: %support_url%en/staff</p>', 1640751660, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ppd_login_attempt`
--

CREATE TABLE `ppd_login_attempt` (
  `ip` varchar(200) NOT NULL,
  `attempts` int NOT NULL DEFAULT '0',
  `date` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `ppd_login_log`
--

CREATE TABLE `ppd_login_log` (
  `id` int NOT NULL,
  `date` int NOT NULL,
  `staff_id` int NOT NULL DEFAULT '0',
  `ip` varchar(255) NOT NULL,
  `agent` varchar(255) NOT NULL,
  `success` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Table structure for table `ppd_priority`
--

CREATE TABLE `ppd_priority` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `color` varchar(10) NOT NULL DEFAULT '#000000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `ppd_priority`
--

INSERT INTO `ppd_priority` (`id`, `name`, `color`) VALUES
(1, 'Low', '#8A8A8A'),
(2, 'Medium', '#000000'),
(3, 'High', '#F07D18'),
(4, 'Urgent', '#E826C6'),
(5, 'Emergency', '#E06161'),
(6, 'Critical', '#FF0000');

-- --------------------------------------------------------

--
-- Table structure for table `ppd_roles`
--

CREATE TABLE `ppd_roles` (
  `role_id` int NOT NULL,
  `role_name` varchar(50) NOT NULL,
  `view_dashboard` tinyint(1) NOT NULL DEFAULT '0',
  `view_ticket` tinyint(1) NOT NULL DEFAULT '0',
  `change_ticket` tinyint(1) NOT NULL DEFAULT '0',
  `create_ticket` tinyint(1) NOT NULL DEFAULT '0',
  `assign_ticket` tinyint(1) NOT NULL DEFAULT '0',
  `view_canned_responses` tinyint(1) NOT NULL DEFAULT '0',
  `change_canned_responses` tinyint(1) NOT NULL DEFAULT '0',
  `create_canned_responses` tinyint(1) NOT NULL DEFAULT '0',
  `view_kb` tinyint(1) NOT NULL DEFAULT '0',
  `change_kb` tinyint(1) NOT NULL DEFAULT '0',
  `create_kb` tinyint(1) NOT NULL DEFAULT '0',
  `view_users` tinyint(1) NOT NULL DEFAULT '0',
  `change_users` tinyint(1) NOT NULL DEFAULT '0',
  `create_users` tinyint(1) NOT NULL DEFAULT '0',
  `view_categories` tinyint(1) NOT NULL DEFAULT '0',
  `change_categories` tinyint(1) NOT NULL DEFAULT '0',
  `create_categories` tinyint(1) NOT NULL DEFAULT '0',
  `view_topics` tinyint(1) NOT NULL DEFAULT '0',
  `change_topics` tinyint(1) NOT NULL DEFAULT '0',
  `create_topics` tinyint(1) NOT NULL DEFAULT '0',
  `view_agents` tinyint(1) NOT NULL DEFAULT '0',
  `change_agents` tinyint(1) NOT NULL DEFAULT '0',
  `create_agents` tinyint(1) NOT NULL DEFAULT '0',
  `view_schedule` tinyint(1) NOT NULL DEFAULT '0',
  `change_schedule` tinyint(1) NOT NULL DEFAULT '0',
  `view_setup` tinyint(1) NOT NULL DEFAULT '0',
  `change_setup` tinyint(1) NOT NULL DEFAULT '0',
  `view_attachments` tinyint(1) NOT NULL DEFAULT '0',
  `change_attachments` tinyint(1) NOT NULL DEFAULT '0',
  `view_roles` tinyint(1) NOT NULL DEFAULT '0',
  `change_roles` tinyint(1) NOT NULL DEFAULT '0',
  `create_roles` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `ppd_roles`
--

INSERT INTO `ppd_roles` (`role_id`, `role_name`, `view_dashboard`, `view_ticket`, `change_ticket`, `create_ticket`, `assign_ticket`, `view_canned_responses`, `change_canned_responses`, `create_canned_responses`, `view_kb`, `change_kb`, `create_kb`, `view_users`, `change_users`, `create_users`, `view_categories`, `change_categories`, `create_categories`, `view_topics`, `change_topics`, `create_topics`, `view_agents`, `change_agents`, `create_agents`, `view_schedule`, `change_schedule`, `view_setup`, `change_setup`, `view_attachments`, `change_attachments`, `view_roles`, `change_roles`, `create_roles`) VALUES
(1, 'admin', 1, 1, 0, 0, 1, 0, 1, 0, 1, 0, 0, 1, 0, 0, 1, 0, 0, 1, 0, 0, 1, 1, 1, 1, 0, 1, 1, 1, 0, 1, 1, 1),
(2, 'operator', 1, 1, 1, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 1, 0, 0, 1, 0, 1, 0, 1, 1, 1, 0, 0),
(3, 'supervisor', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 1, 1, 1, 0, 1, 1, 1, 0, 0),
(4, 'viewer', 1, 1, 0, 0, 1, 0, 0, 0, 1, 0, 0, 1, 0, 0, 1, 0, 0, 1, 0, 0, 1, 0, 0, 1, 0, 0, 0, 1, 0, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `ppd_staff`
--

CREATE TABLE `ppd_staff` (
  `id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `registration` int NOT NULL DEFAULT '0',
  `login` int NOT NULL DEFAULT '0',
  `last_login` int NOT NULL DEFAULT '0',
  `avatar` varchar(200) DEFAULT NULL,
  `role` int NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `ppd_staff`
--

INSERT INTO `ppd_staff` (`id`, `username`, `password`, `fullname`, `email`, `token`, `registration`, `login`, `last_login`, `avatar`, `role`, `active`) VALUES
(1, 'admin', '$2y$10$n1YJxnEkCtbagZT86ohm0ehw/lI2VZVLmeiuEHvlMq8LNNAM8Z1cO', 'Admin PPD-Online', 'pst1406@bps.go.id', '829851409a18fe963da4a00ac5dbd5e434399466', 1621221210, 1703215422, 1703214812, NULL, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ppd_tickets`
--

CREATE TABLE `ppd_tickets` (
  `id` int NOT NULL,
  `category` int NOT NULL,
  `topic` int NOT NULL,
  `priority_id` int NOT NULL DEFAULT '0',
  `user_id` int NOT NULL DEFAULT '0',
  `subject` varchar(255) NOT NULL,
  `date` int NOT NULL DEFAULT '0',
  `last_update` int NOT NULL DEFAULT '0',
  `status` smallint NOT NULL DEFAULT '1',
  `staff_id` int NOT NULL DEFAULT '0',
  `replies` int NOT NULL DEFAULT '0',
  `last_replier` tinyint(1) DEFAULT '0',
  `start_update` int NOT NULL DEFAULT '0',
  `end_update` int NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `ppd_tickets_messages`
--

CREATE TABLE `ppd_tickets_messages` (
  `id` int NOT NULL,
  `ticket_id` int NOT NULL DEFAULT '0',
  `date` int NOT NULL DEFAULT '0',
  `customer` int NOT NULL DEFAULT '1',
  `staff_id` int NOT NULL DEFAULT '0',
  `message` text,
  `ip` varchar(255) DEFAULT NULL,
  `email` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `ppd_ticket_notes`
--

CREATE TABLE `ppd_ticket_notes` (
  `id` int NOT NULL,
  `ticket_id` int NOT NULL,
  `staff_id` int NOT NULL,
  `date` int NOT NULL,
  `message` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `ppd_topic`
--

CREATE TABLE `ppd_topic` (
  `id` int NOT NULL,
  `name` varchar(200) NOT NULL,
  `public` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `ppd_topic`
--

INSERT INTO `ppd_topic` (`id`, `name`, `public`) VALUES
(1, 'Permintaan Data', 1),
(2, 'Konsultasi Statistik', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ppd_users`
--

CREATE TABLE `ppd_users` (
  `id` int NOT NULL,
  `fullname` varchar(250) NOT NULL DEFAULT 'Guest',
  `phone` varchar(20) NOT NULL,
  `in_wilayah` tinyint NOT NULL DEFAULT '0',
  `email` varchar(250) NOT NULL,
  `last_login` int NOT NULL DEFAULT '0',
  `registration` int NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
-- Indexes for table `ppd_roles`
--
ALTER TABLE `ppd_roles`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `ppd_staff`
--
ALTER TABLE `ppd_staff`
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
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ppd_articles`
--
ALTER TABLE `ppd_articles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ppd_attachments`
--
ALTER TABLE `ppd_attachments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `ppd_canned_response`
--
ALTER TABLE `ppd_canned_response`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ppd_category`
--
ALTER TABLE `ppd_category`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `ppd_config`
--
ALTER TABLE `ppd_config`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ppd_emails`
--
ALTER TABLE `ppd_emails`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ppd_login_log`
--
ALTER TABLE `ppd_login_log`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=401;

--
-- AUTO_INCREMENT for table `ppd_priority`
--
ALTER TABLE `ppd_priority`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `ppd_roles`
--
ALTER TABLE `ppd_roles`
  MODIFY `role_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ppd_staff`
--
ALTER TABLE `ppd_staff`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `ppd_tickets`
--
ALTER TABLE `ppd_tickets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=156;

--
-- AUTO_INCREMENT for table `ppd_tickets_messages`
--
ALTER TABLE `ppd_tickets_messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=370;

--
-- AUTO_INCREMENT for table `ppd_ticket_notes`
--
ALTER TABLE `ppd_ticket_notes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ppd_topic`
--
ALTER TABLE `ppd_topic`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ppd_users`
--
ALTER TABLE `ppd_users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=223;
COMMIT;