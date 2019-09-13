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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` char(60) NOT NULL,
  `profilesId` int(10) unsigned NOT NULL,
  `active` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `profilesId` (`profilesId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `profilesId`, `active`) VALUES
(1, 'Bob Burnquist', 'bob@phalconphp.com', '$2y$08$QXBpK3d6V1BDTUR3Tno5Mu81LK9GsLSuG19uwJROhBYL/OD.oQ/QC', 1, 'Y'),
(2, 'Erik', 'erik@phalconphp.com', '$2y$08$QXBpK3d6V1BDTUR3Tno5Mu81LK9GsLSuG19uwJROhBYL/OD.oQ/QC', 2, 'Y'),
(3, 'Veronica', 'veronica@phalconphp.com', '$2y$08$QXBpK3d6V1BDTUR3Tno5Mu81LK9GsLSuG19uwJROhBYL/OD.oQ/QC', 3, 'Y');

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
-- Table structure for table `clocks`
--

DROP TABLE IF EXISTS `hours`;
CREATE TABLE IF NOT EXISTS `hours` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`usersId` int(10) unsigned NOT NULL,
`total` time DEFAULT NULL,
`less` time DEFAULT NULL,
`createdAt` date NOT NULL,
PRIMARY KEY (`id`),
KEY `usersId` (`usersId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- Table structure for table `clocks`
--

DROP TABLE IF EXISTS `start_end`;
CREATE TABLE IF NOT EXISTS `start_end` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`hourId` int(10) unsigned NOT NULL,
`start` time DEFAULT NULL,
`end` time DEFAULT NULL,
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
