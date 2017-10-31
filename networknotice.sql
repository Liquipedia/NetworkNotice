SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


-- --------------------------------------------------------
-- Table structure for table `networknotice`
--

CREATE TABLE IF NOT EXISTS `networknotice` (
  `notice_id` int(11) NOT NULL AUTO_INCREMENT,
  `label` tinyblob NOT NULL,
  `wiki` blob NOT NULL,
  `namespace` tinyblob NOT NULL,
  `notice_text` blob NOT NULL,
  `bgcolor` tinyblob NOT NULL,
  `bordercolor` tinyblob NOT NULL,
  `category` blob NOT NULL,
  `prefix` blob NOT NULL,
  `action` blob NOT NULL,
  `temporary` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`notice_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=binary;


