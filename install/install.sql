SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `images` (
  `id` char(4) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `title` varchar(90) DEFAULT NULL,
  `ip` varbinary(16) NOT NULL,
  `type` char(3) NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `users` (
  `name` varchar(30) NOT NULL,
  `password` text NOT NULL,
  `level` varchar(10) NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;