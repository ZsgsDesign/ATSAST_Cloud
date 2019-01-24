/*!40101 SET NAMES utf8 */;

CREATE TABLE IF NOT EXISTS `users` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `OPENID` char(40) NOT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `OPENID` (`OPENID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `disk_file` (
  `fid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `path` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `hash` char(28) NOT NULL,
  `is_dir` tinyint(1) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `filesize` int(11) NOT NULL,
  PRIMARY KEY (`fid`),
  KEY `uid` (`uid`,`deleted`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `disk_share` (
  `sid` char(8) NOT NULL,
  `uid` int(11) NOT NULL,
  `fid` int(11) NOT NULL,
  `pswd` int(11) DEFAULT NULL,
  `expire` int(11) DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `sid` (`sid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE  `users` ADD  `capacity` INT NOT NULL DEFAULT  '5242880',
ADD  `used` INT NOT NULL DEFAULT  '0' ;
