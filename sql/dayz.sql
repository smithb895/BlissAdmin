-- phpMyAdmin SQL Dump
-- version 3.4.10.2deb1.lucid~ppa.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июл 22 2012 г., 09:27
-- Версия сервера: 5.5.25
-- Версия PHP: 5.2.10-2ubuntu6.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `dayz`
--

-- --------------------------------------------------------

--
-- Структура таблицы `logs`
--

CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `action` varchar(255) DEFAULT NULL,
  `user` varchar(255) NOT NULL DEFAULT '',
  `timestamp` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=221 ;

-- --------------------------------------------------------

--
-- Структура таблицы `object_classes`
--

CREATE TABLE IF NOT EXISTS `object_classes` (
  `Classname` varchar(128) NOT NULL DEFAULT '',
  `Type` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`Classname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `object_classes`
--

INSERT INTO `object_classes` (`Classname`, `Type`) VALUES
('ATV_CZ_EP1', 'ATV'),
('ATV_US_EP1', 'ATV'),
('BAF_Offroad_W', 'Car'),
('car_hatchback', 'Car'),
('car_sedan', 'Car'),
('datsun1_civil_3_open', 'Car'),
('Fishing_Boat', 'large Boat'),
('Hedgehog_DZ', 'Hedgehog'),
('hilux1_civil_1_open', 'Car'),
('hilux1_civil_2_covered', 'Car'),
('hilux1_civil_3_open', 'Car'),
('HMMWV', 'Car'),
('Ikarus', 'Bus'),
('Ikarus_TK_CIV_EP1', 'Bus'),
('Kamaz', 'Truck'),
('LandRover_TK_CIV_EP1', 'Car'),
('MH6J_EP1', 'Helicopter'),
('Old_bike_TK_CIV_EP1', 'Bike'),
('Old_bike_TK_INS_EP1', 'Bike'),
('PBX', 'small Boat'),
('S1203_TK_CIV_EP1', 'Bus'),
('Skoda', 'Car'),
('SkodaBlue', 'Car'),
('SkodaGreen', 'Car'),
('Smallboat_1', 'medium Boat'),
('Smallboat_2', 'medium Boat'),
('SUV_TK_CIV_EP1', 'Car'),
('TentStorage', 'Tent'),
('Tractor', 'Farmvehicle'),
('TT650_Ins', 'Motorcycle'),
('TT650_TK_CIV_EP1', 'Motorcycle'),
('TT650_TK_EP1', 'Motorcycle'),
('UAZ_INS', 'Car'),
('UAZ_RU', 'Car'),
('UAZ_Unarmed_TK_CIV_EP1', 'Car'),
('UAZ_Unarmed_TK_EP1', 'Car'),
('UAZ_Unarmed_UN_EP1', 'Car'),
('UH1H_DZ', 'Helicopter'),
('UralCivil', 'Truck'),
('UralCivil2', 'Truck'),
('V3S_Civ', 'Truck'),
('V3S_Gue', 'Truck'),
('V3S_TK_GUE_EP1', 'Truck'),
('VolhaLimo_TK_CIV_EP1', 'Car'),
('Volha_1_TK_CIV_EP1', 'Car'),
('Volha_2_TK_CIV_EP1', 'Car'),
('VWGolf', 'Car'),
('Wire_cat1', 'Wire');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `hive_admins` (
  `id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `hive_user` varchar(64) NOT NULL DEFAULT '',
  `hive_password` varchar(512) NOT NULL DEFAULT '',
  `salt` varchar(64) NOT NULL DEFAULT '',
  `salt2` varchar(64) NOT NULL DEFAULT '',
  `tier` smallint(2) NOT NULL DEFAULT 2,
  `lastlogin` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hive_user` (`hive_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Дамп данных таблицы `hive_admins`
--
INSERT INTO `hive_admins` (`id`, `hive_user`, `hive_password`, `salt`, `salt2`, `tier`, `lastlogin`) VALUES
(1, 'admin', 'e039e0e6ceda6ba431303077f218937a8a24489e9fc6075918ce61b1f797a8b917250b5a91ef1ac12723ae29d4943cc04bfd29b0c42cfdcacebac9ea90653719', 'ld78J4380f&*(#nhfdghlH1xzpq590&DqQlH349302&lm!qweeruSl^21@Xirtnv', 'EycR3pswG$d82mn3ucv378g02&$vjpiZsdA-#43894fdkWMVBu423493$#!^OVc8', 1, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
