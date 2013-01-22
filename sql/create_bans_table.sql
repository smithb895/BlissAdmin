-- AWG Admin System
-- Create `bans` & `players` tables
-- Author: Anzu

create table `bans` (
	`ID` int(12) unsigned NOT NULL AUTO_INCREMENT,
	`GUID_IP` varchar(32) NOT NULL,
	`LENGTH` int(15) NOT NULL DEFAULT '-1',
	`REASON` varchar(64) NOT NULL DEFAULT 'Appeal at our website',
	`ADMIN` varchar(64) NULL DEFAULT 'AWG Ban System',
	`DATE_TIME` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
	`ACTIVE` smallint(1) unsigned NOT NULL DEFAULT '1',
	PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


CREATE TABLE `players` (
	`ID` int(15) unsigned NOT NULL AUTO_INCREMENT,
	`GUID` varchar(64) NOT NULL,
	`KNOWN_NAMES` varchar(512),
	`KNOWN_IPS` varchar(512),
	`LAST_SEEN` timestamp NULL DEFAULT NULL,
	`FIRST_SEEN` timestamp NULL DEFAULT NULL,
	`SERVER` varchar(50) NULL DEFAULT NULL,
	PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;