
-- -={AWG}=- Hive Admin
-- Author: Anzu
-- Desc: Items table


CREATE TABLE `items` (
	`ID` int(12) unsigned NOT NULL AUTO_INCREMENT,
	`classname` varchar(64) NOT NULL,
	`itemname` varchar(64) NOT NULL,
	`slots` tinyint(2) unsigned NOT NULL DEFAULT '1',
	`banned` tinyint(1) unsigned NULL DEFAULT '0',
	`viplevel` tinyint(2) unsigned NOT NULL DEFAULT '4',
	PRIMARY KEY (`ID`),
	UNIQUE KEY `classname` (`classname`),
	UNIQUE KEY `itemname` (`itemname`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
