-- AWG Ban System
-- Create Bans table Script
-- Author: Anzu

create table `bans` (
	`ID` int(12) unsigned NOT NULL AUTO_INCREMENT,
	`GUID_IP` varchar(32) NOT NULL,
	`LENGTH` int(15) NOT NULL DEFAULT '-1',
	`REASON` varchar(64) NOT NULL DEFAULT 'Appeal at AnzusWarGames.info/forums',
	`ADMIN` varchar(64) NULL DEFAULT 'AWG Ban System',
	`DATE_TIME` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
	`ACTIVE` smallint(1) unsigned NOT NULL DEFAULT '1',
	PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;