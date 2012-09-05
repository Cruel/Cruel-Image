SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `images` (
  `id` varchar(4) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `title` varchar(90) DEFAULT NULL,
  `ip` varchar(16) NOT NULL,
  `type` varchar(3) NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;