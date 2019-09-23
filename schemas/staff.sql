--
-- Table structure for table `profiles`
--

DROP TABLE IF EXISTS `profiles`;
CREATE TABLE IF NOT EXISTS `profiles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `name`) VALUES
(1, 'admin'),
(2, 'users'),
(3, 'guest');

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `profilesId` int(10) unsigned NOT NULL,
  `resource` varchar(16) NOT NULL,
  `action` varchar(16) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `profilesId` (`profilesId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `profilesId`, `resource`, `action`) VALUES
(1, 1, 'users', 'index'),
(2, 1, 'users', 'edit'),
(3, 1, 'users', 'profile'),
(4, 1, 'users', 'create'),
(5, 1, 'users', 'updateActivity'),
(6, 1, 'users', 'deleteUploads'),
(7, 1, 'users', 'changePassword'),
(8, 1, 'hours', 'index'),
(9, 1, 'hours', 'update'),
(10, 1, 'hours', 'updateTotal'),
(11, 1, 'notWorkingDays', 'index'),
(12, 1, 'notWorkingDays', 'create'),
(13, 1, 'notWorkingDays', 'delete'),
(14, 1, 'settings', 'createOrUpdate'),
(15, 1, 'admin', 'index'),
(16, 1, 'admin', 'updateStartEnd'),
(17, 1, 'permissions', 'index'),
(18, 2, 'users', 'index'),
(19, 2, 'users', 'deleteUploads'),
(20, 2, 'users', 'changePassword'),
(21, 2, 'hours', 'index'),
(22, 2, 'hours', 'update'),
(23, 2, 'hours', 'updateTotal');
(24, 1, 'admin', 'createCounter');

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `login` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `password` char(60) NOT NULL,
  `profilesId` int(10) unsigned NOT NULL,
  `active` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `profilesId` (`profilesId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `login`, `email`, `password`, `profilesId`, `active`) VALUES
(1, 'Bob Burnquist', 'bob-admin', 'bob@phalconphp.com', '$2y$08$QXBpK3d6V1BDTUR3Tno5Mu81LK9GsLSuG19uwJROhBYL/OD.oQ/QC', 1, 'Y'),
(2, 'Erik', 'erik-user', 'erik@phalconphp.com', '$2y$08$QXBpK3d6V1BDTUR3Tno5Mu81LK9GsLSuG19uwJROhBYL/OD.oQ/QC', 2, 'Y'),
(3, 'Veronica', 'veronica-guest', 'veronica@phalconphp.com', '$2y$08$QXBpK3d6V1BDTUR3Tno5Mu81LK9GsLSuG19uwJROhBYL/OD.oQ/QC', 3, 'Y');

--
-- Table structure for table `remember_tokens`
--

DROP TABLE IF EXISTS `remember_tokens`;
CREATE TABLE IF NOT EXISTS `remember_tokens` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `usersId` int(10) unsigned NOT NULL,
  `token` char(32) NOT NULL,
  `userAgent` varchar(120) NOT NULL,
  `createdAt` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Table structure for table `reset_passwords`
--

DROP TABLE IF EXISTS `reset_passwords`;
CREATE TABLE IF NOT EXISTS `reset_passwords` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `usersId` int(10) unsigned NOT NULL,
  `code` varchar(48) NOT NULL,
  `createdAt` int(10) unsigned NOT NULL,
  `modifiedAt` int(10) unsigned DEFAULT NULL,
  `reset` char(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `usersId` (`usersId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Table structure for table `hours`
--

DROP TABLE IF EXISTS `hours`;
CREATE TABLE IF NOT EXISTS `hours` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`usersId` int(10) unsigned NOT NULL,
`total` varchar(12) DEFAULT NULL,
`less` varchar(12) DEFAULT NULL,
`late` boolean NOT NULL DEFAULT 0,
`createdAt` date NOT NULL,
PRIMARY KEY (`id`),
KEY `usersId` (`usersId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Table structure for table `start_end`
--

DROP TABLE IF EXISTS `start_end`;
CREATE TABLE IF NOT EXISTS `start_end` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`hourId` int(10) unsigned NOT NULL,
`start` varchar(10) DEFAULT NULL,
`stop` varchar(10) DEFAULT NULL,
PRIMARY KEY (`id`),
KEY `hourId` (`hourId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Table structure for table `not_working_days`
--

DROP TABLE IF EXISTS `not_working_days`;
CREATE TABLE IF NOT EXISTS `not_working_days` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `month` int(2) NOT NULL,
  `day` int(2) NOT NULL,
  `repeat` ENUM('Y', 'N') DEFAULT 'Y',
  `holiday` ENUM('Y', 'N') DEFAULT 'N',
  `createdAt` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `not_working_days`
--

INSERT INTO `not_working_days` (`id`, `month`, `day`, `repeat`, `holiday`, `createdAt`) VALUES
(1, 1, 1, 'Y', 'Y', '2019'),
(2, 3, 8, 'Y', 'Y', '2019'),
(3, 5, 1, 'Y', 'Y', '2019'),
(4, 5, 9, 'Y', 'Y', '2019'),
(5, 8, 31, 'Y', 'Y', '2019'),
(6, 12, 31, 'Y', 'Y', '2019');

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
