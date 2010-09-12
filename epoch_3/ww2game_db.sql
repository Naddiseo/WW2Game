-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 04, 2010 at 04:55 PM
-- Server version: 5.0.91
-- PHP Version: 5.2.6

--    World War II MMORPG
--    Copyright (C) 2009-2010 Richard Eames
--
--    This program is free software: you can redistribute it and/or modify
--    it under the terms of the GNU General Public License as published by
--    the Free Software Foundation, either version 3 of the License, or
--    (at your option) any later version.
--
--    This program is distributed in the hope that it will be useful,
--    but WITHOUT ANY WARRANTY; without even the implied warranty of
--    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
--    GNU General Public License for more details.
--
--    You should have received a copy of the GNU General Public License
--    along with this program.  If not, see <http://www.gnu.org/licenses/>.

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ww2game_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `Activation`
--

CREATE TABLE IF NOT EXISTS `Activation` (
  `id` int(10) NOT NULL auto_increment,
  `username` varchar(25) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(32) NOT NULL,
  `nation` tinyint(2) NOT NULL,
  `IP` varchar(35) NOT NULL,
  `success` tinyint(4) NOT NULL,
  `userId` int(10) NOT NULL COMMENT 'Link to the UserDetails table',
  `referrerId` int(11) NOT NULL COMMENT 'Link to referring player',
  `time` int(10) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `userName` (`username`,`password`)
) TYPE=MyISAM ;

-- --------------------------------------------------------

--
-- Table structure for table `Alliance`
--

CREATE TABLE IF NOT EXISTS `Alliance` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(30) NOT NULL,
  `password` varchar(32) NOT NULL,
  `tag` varchar(8) NOT NULL default '0',
  `leaderId1` int(10) NOT NULL default '0',
  `leaderId2` int(10) NOT NULL default '0',
  `leaderId3` int(10) NOT NULL default '0',
  `rank` int(11) NOT NULL default '0',
  `creationdate` int(11) NOT NULL default '0',
  `status` tinyint(2) NOT NULL default '0',
  `url` varchar(255) NOT NULL default '',
  `irc` varchar(10) NOT NULL default '',
  `ircServer` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL,
  `UP` int(10) NOT NULL default '0',
  `gold` bigint(15) NOT NULL default '0',
  `donated` float NOT NULL,
  `usedcash` float NOT NULL,
  `tax` float NOT NULL default '0.01',
  `SA` float NOT NULL default '0',
  `DA` float NOT NULL default '0',
  `CA` float NOT NULL default '0',
  `RA` float NOT NULL default '0',
  `news` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `password` (`password`)
) TYPE=MyISAM ;

-- --------------------------------------------------------

--
-- Table structure for table `AllianceBan`
--

CREATE TABLE IF NOT EXISTS `AllianceBan` (
  `id` int(11) NOT NULL auto_increment,
  `allianceId` int(11) NOT NULL,
  `targetId` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `allianceId` (`allianceId`,`targetId`)
) TYPE=MyISAM ;

-- --------------------------------------------------------

--
-- Table structure for table `AllianceShout`
--

CREATE TABLE IF NOT EXISTS `AllianceShout` (
  `id` int(11) NOT NULL auto_increment,
  `allianceId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `message` varchar(160) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `allianceId` (`allianceId`),
  KEY `date` (`date`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table structure for table `BattleLog`
--

CREATE TABLE IF NOT EXISTS `BattleLog` (
  `id` int(11) NOT NULL auto_increment,
  `attackerId` int(11) NOT NULL,
  `targetId` int(11) NOT NULL,
  `attackType` int(11) NOT NULL,
  `time` int(10) NOT NULL,
  `isSuccess` tinyint(1) NOT NULL,
  `attackerStrength` bigint(15) unsigned NOT NULL,
  `targetStrength` bigint(15) unsigned NOT NULL,
  `attackerStrikePercentage` smallint(4) NOT NULL,
  `targetDefensePercentage` smallint(4) NOT NULL,
  `attackerLosses` int(11) NOT NULL,
  `targetLosses` int(11) NOT NULL,
  `attackerDamage` bigint(20) NOT NULL,
  `targetDamage` bigint(20) NOT NULL,
  `satrained` int(11) NOT NULL,
  `samercs` int(11) NOT NULL,
  `sauntrained` int(11) NOT NULL,
  `datrained` int(11) NOT NULL,
  `damercs` int(11) NOT NULL,
  `dauntrained` int(11) NOT NULL,
  `satrainednw` int(11) NOT NULL,
  `samercsnw` int(11) NOT NULL,
  `sauntrainednw` int(11) NOT NULL,
  `datrainednw` int(11) NOT NULL,
  `damercsnw` int(11) NOT NULL,
  `dauntrainednw` int(11) NOT NULL,
  `goldStolen` int(11) NOT NULL,
  `percentStolen` smallint(4) NOT NULL,
  `attackerExp` int(11) NOT NULL,
  `targetExp` int(11) NOT NULL,
  `attackerHostages` int(11) NOT NULL,
  `targetHostages` int(11) NOT NULL,
  `attackerRA` bigint(20) NOT NULL,
  `targetRA` bigint(20) NOT NULL,
  `attackerRAPercentage` int(11) NOT NULL,
  `targetRAPercentage` int(11) NOT NULL,
  `RADamage` bigint(20) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `attackerId` (`attackerId`),
  KEY `targetId` (`targetId`),
  KEY `time` (`time`)
) TYPE=MyISAM ;

-- --------------------------------------------------------

--
-- Table structure for table `Contact`
--

CREATE TABLE IF NOT EXISTS `Contact` (
  `id` int(11) NOT NULL auto_increment,
  `email` varchar(50) NOT NULL,
  `type` int(11) NOT NULL,
  `text` text NOT NULL,
  `date` int(11) NOT NULL,
  `done` int(11) NOT NULL,
  `reference` int(11) NOT NULL,
  `replied` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM  ;

-- --------------------------------------------------------

--
-- Table structure for table `Ignore`
--

CREATE TABLE IF NOT EXISTS `Ignore` (
  `id` int(11) NOT NULL auto_increment,
  `userId` int(11) NOT NULL,
  `targetId` int(11) NOT NULL,
  `note` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `userId` (`userId`,`targetId`)
) TYPE=MyISAM ;

-- --------------------------------------------------------

--
-- Table structure for table `IP`
--

CREATE TABLE IF NOT EXISTS `IP` (
  `id` int(20) NOT NULL auto_increment,
  `IP` varchar(20) NOT NULL default '',
  `uid` int(10) NOT NULL default '0',
  `time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `ip` (`IP`),
  KEY `userID` (`uid`),
  KEY `time` (`time`),
  KEY `IP_2` (`IP`,`uid`)
) TYPE=InnoDB  PACK_KEYS=0  ;

-- --------------------------------------------------------

--
-- Table structure for table `Mercenaries`
--

CREATE TABLE IF NOT EXISTS `Mercenaries` (
  `attackSpecCount` int(11) NOT NULL default '0',
  `defSpecCount` int(11) NOT NULL default '0',
  `untrainedCount` int(11) NOT NULL default '0',
  `lastturntime` int(11) NOT NULL default '0',
  `avgarmy` bigint(15) NOT NULL default '0',
  `avgtbg` bigint(15) NOT NULL default '0',
  `avgup` bigint(15) NOT NULL default '0',
  `avgsa` bigint(15) NOT NULL default '0',
  `avgda` bigint(15) NOT NULL default '0',
  `avgra` bigint(15) NOT NULL default '0',
  `avgca` bigint(15) NOT NULL default '0',
  `avghit` int(11) NOT NULL default '0'
) TYPE=MyISAM;

-- 
-- Add the single needed row, all values set to default except turntime
--
INSERT INTO `Mercenaries` (`lastturntime`) VALUES (UNIX_TIMESTAMP());

-- --------------------------------------------------------

--
-- Table structure for table `Message`
--

CREATE TABLE IF NOT EXISTS `Message` (
  `id` int(10) NOT NULL auto_increment,
  `targetId` int(10) NOT NULL default '0',
  `senderId` int(10) NOT NULL default '0',
  `subject` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `targetStatus` tinyint(1) NOT NULL default '1',
  `senderStatus` tinyint(1) NOT NULL,
  `date` int(10) NOT NULL default '0',
  `age` int(11) NOT NULL,
  `fromadmin` tinyint(1) default '0',
  PRIMARY KEY  (`id`),
  KEY `targetId` (`targetId`),
  KEY `subject_2` (`subject`),
  KEY `age` (`age`),
  FULLTEXT KEY `subject` (`subject`),
  FULLTEXT KEY `text` (`text`)
) TYPE=MyISAM  PACK_KEYS=0 ;

-- --------------------------------------------------------

--
-- Table structure for table `Report`
--

CREATE TABLE IF NOT EXISTS `Report` (
  `id` int(10) NOT NULL auto_increment,
  `userId` int(10) NOT NULL COMMENT 'the user who reported it',
  `targetId` int(10) NOT NULL COMMENT 'the suspected user',
  `type` smallint(3) NOT NULL COMMENT 'type ',
  `time` int(10) NOT NULL,
  `info` varchar(160) NOT NULL COMMENT 'reason why the suspect was reported.',
  PRIMARY KEY  (`id`),
  KEY `userId` (`userId`,`targetId`)
) TYPE=MyISAM ;

-- --------------------------------------------------------

--
-- Table structure for table `SpyLog`
--

CREATE TABLE IF NOT EXISTS `SpyLog` (
  `id` int(10) NOT NULL auto_increment,
  `attackerId` int(10) NOT NULL default '0',
  `targetId` int(10) NOT NULL default '0',
  `attackerStrength` bigint(15) NOT NULL default '0',
  `targetStrength` bigint(15) NOT NULL default '0',
  `sasoldiers` varchar(15) NOT NULL default '',
  `samercs` varchar(15) NOT NULL default '',
  `dasoldiers` varchar(15) NOT NULL default '',
  `damercs` varchar(15) NOT NULL default '',
  `uu` varchar(15) NOT NULL,
  `SA` varchar(15) NOT NULL,
  `DA` varchar(15) NOT NULL,
  `calevel` varchar(15) NOT NULL,
  `targetSpies` varchar(15) NOT NULL,
  `salevel` varchar(15) NOT NULL default '',
  `dalevel` varchar(15) NOT NULL,
  `attackTurns` varchar(15) NOT NULL default '',
  `unitProduction` varchar(15) NOT NULL default '',
  `weapons` varchar(255) NOT NULL default '',
  `types` varchar(255) NOT NULL default '',
  `types2` varchar(255) NOT NULL default '',
  `quantities` varchar(255) NOT NULL default '',
  `strengths` varchar(255) NOT NULL default '',
  `allStrengths` varchar(255) NOT NULL default '',
  `time` int(11) NOT NULL default '0',
  `spies` int(11) NOT NULL default '0',
  `isSuccess` tinyint(1) NOT NULL default '0',
  `sf` varchar(11) NOT NULL default '0',
  `ralevel` varchar(11) NOT NULL default '0',
  `hhlevel` varchar(11) NOT NULL default '',
  `gold` varchar(15) NOT NULL,
  `weapontype` tinyint(4) NOT NULL default '0',
  `type` tinyint(4) NOT NULL default '0',
  `weaponamount` int(11) NOT NULL default '0',
  `hostages` int(11) NOT NULL default '0',
  `weapontype2` tinyint(4) NOT NULL default '0',
  `goldStolen` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `userID` (`attackerId`),
  KEY `toUserID` (`targetId`),
  KEY `time` (`time`)
) TYPE=MyISAM  PACK_KEYS=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `TFF`
--

CREATE ALGORITHM=UNDEFINED DEFINER=`ww2game`@`localhost` SQL SECURITY DEFINER VIEW 
`ww2game_db`.`TFF` AS 
	select 
		`ww3game_db`.`User`.`id` AS `id`,
		`ww3game_db`.`User`.`username` AS `username`,
		`ww2game_db`.`User`.`up` AS `up`,
		`ww2game_db`.`User`.`officerup` AS `officerup`,
		(`ww2game_db`.`User`.`up` + `ww2game_db`.`User`.`officerup`) AS `totalup`,
		(
			(
				(
					(
						(
							(`ww2game_db`.`User`.`uu` + `ww2game_db`.`User`.`samercs`) + 
							`ww2game_db`.`User`.`damercs`
						) + 
						`ww2game_db`.`User`.`spies`
					) + 
				`ww2game_db`.`User`.`specialforces`
			) + `ww2game_db`.`User`.`sasoldiers`
		) + `ww2game_db`.`User`.`dasoldiers`) AS `tff`,
		`ww2game_db`.`User`.`uu` AS `uu`,
		`ww2game_db`.`User`.`sasoldiers` AS `sasoldiers`,
		`ww2game_db`.`User`.`samercs` AS `samercs`,
		`ww2game_db`.`User`.`dasoldiers` AS `dasoldiers`,
		`ww2game_db`.`User`.`damercs` AS `damercs`,
		`ww2game_db`.`User`.`spies` AS `spies`,
		`ww2game_db`.`User`.`specialforces` AS `specialforces` 
	from 
		`ww2game_db`.`User` 
	where 
		(`ww2game_db`.`User`.`active` = 1) 
	order by 
		((((((`ww2game_db`.`User`.`uu` + `ww2game_db`.`User`.`samercs`) + `ww2game_db`.`User`.`damercs`) + `ww2game_db`.`User`.`spies`) + `ww2game_db`.`User`.`specialforces`) + `ww2game_db`.`User`.`sasoldiers`) + `ww2game_db`.`User`.`dasoldiers`) desc;

-- --------------------------------------------------------

--
-- Table structure for table `Transaction`
--

CREATE TABLE IF NOT EXISTS `Transaction` (
  `id` int(10) NOT NULL auto_increment,
  `time` int(10) NOT NULL,
  `amount` float NOT NULL,
  `userId` int(10) NOT NULL,
  `forId` int(10) NOT NULL,
  `isAlliance` tinyint(1) NOT NULL,
  `token` varchar(50) NOT NULL,
  `timestamp` varchar(50) NOT NULL,
  `correlationId` varchar(50) NOT NULL,
  `ack` varchar(25) NOT NULL,
  `version` varchar(25) NOT NULL,
  `build` varchar(25) NOT NULL,
  `part1Success` tinyint(1) NOT NULL,
  `part4Success` tinyint(1) NOT NULL,
  `payerId` varchar(25) NOT NULL,
  `transactionId` varchar(50) NOT NULL,
  `transactionType` varchar(50) NOT NULL,
  `paymentType` varchar(50) NOT NULL,
  `orderTime` varchar(50) NOT NULL,
  `fee` float NOT NULL,
  `tax` float NOT NULL,
  `currencyCode` varchar(5) NOT NULL,
  `paymentStatus` varchar(50) NOT NULL,
  `pendingReason` varchar(255) NOT NULL,
  `reasonCode` varchar(50) NOT NULL,
  `errorInfo` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `token` (`token`)
) TYPE=MyISAM ;

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE IF NOT EXISTS `User` (
  `id` int(10) NOT NULL auto_increment,
  `username` varchar(25) NOT NULL,
  `nation` tinyint(4) NOT NULL default '0',
  `email` varchar(100) NOT NULL,
  `password` varchar(32) NOT NULL default '',
  `key` varchar(32) NOT NULL,
  `gclick` tinyint(2) NOT NULL default '15',
  `commander` int(10) NOT NULL default '0',
  `active` int(1) NOT NULL default '0',
  `area` int(11) NOT NULL,
  `dalevel` int(10) NOT NULL default '0',
  `salevel` int(10) NOT NULL default '0',
  `gold` bigint(15) NOT NULL default '0',
  `bank` bigint(10) unsigned NOT NULL default '0',
  `primary` tinyint(1) NOT NULL,
  `attackturns` bigint(15) NOT NULL default '0',
  `up` bigint(15) unsigned NOT NULL default '0',
  `calevel` int(10) unsigned NOT NULL default '0',
  `ralevel` int(10) unsigned NOT NULL default '0',
  `maxofficers` smallint(2) NOT NULL default '5',
  `sasoldiers` bigint(15) NOT NULL default '0',
  `samercs` bigint(15) NOT NULL default '0',
  `dasoldiers` bigint(15) NOT NULL default '0',
  `damercs` bigint(15) NOT NULL default '0',
  `uu` bigint(15) unsigned NOT NULL default '0',
  `spies` bigint(15) unsigned NOT NULL default '0',
  `lastturntime` int(10) unsigned NOT NULL default '0',
  `vacation` int(11) NOT NULL,
  `accepted` tinyint(1) NOT NULL default '0',
  `commandergold` bigint(15) NOT NULL default '0',
  `gameSkill` int(10) NOT NULL default '0',
  `specialforces` bigint(15) unsigned NOT NULL default '0',
  `bankper` int(10) NOT NULL default '10',
  `SA` bigint(15) unsigned NOT NULL default '0',
  `DA` bigint(15) unsigned NOT NULL default '0',
  `CA` bigint(15) unsigned NOT NULL default '0',
  `RA` bigint(15) unsigned NOT NULL default '0',
  `rank` int(10) NOT NULL,
  `sarank` int(10) NOT NULL,
  `darank` int(10) NOT NULL,
  `carank` int(10) NOT NULL,
  `rarank` int(10) NOT NULL,
  `alliance` int(5) NOT NULL default '0',
  `hhlevel` int(10) NOT NULL default '0',
  `officerup` float NOT NULL default '0',
  `changenick` tinyint(4) NOT NULL default '0',
  `admin` int(10) NOT NULL default '0',
  `clicks` int(10) NOT NULL default '0',
  `supporter` smallint(5) NOT NULL default '0' COMMENT 'Number of dollars',
  `reason` varchar(255) NOT NULL,
  `clickall` tinyint(1) NOT NULL default '0',
  `bankimg` tinyint(1) NOT NULL default '1',
  `cheatcount` int(10) NOT NULL default '0',
  `status` varchar(50) NOT NULL,
  `numofficers` int(10) NOT NULL default '0',
  `irc` int(10) NOT NULL default '0',
  `ircstatus` varchar(40) NOT NULL default '',
  `ircnick` varchar(50) NOT NULL,
  `currentIP` varchar(30) default NULL,
  `unreadMsg` int(10) NOT NULL,
  `msgCount` int(10) NOT NULL,
  `aaccepted` tinyint(1) NOT NULL default '0',
  `referrer` int(10) NOT NULL default '0',
  `ircpass` varchar(30) NOT NULL,
  `minattack` bigint(15) NOT NULL,
  `htmlColour` varchar(6) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `commander` (`commander`),
  KEY `alliance` (`alliance`),
  KEY `currentIP` (`currentIP`),
  KEY `referrer` (`referrer`),
  KEY `rank` (`rank`),
  KEY `area` (`area`),
  KEY `key` (`key`)
) TYPE=MyISAM  PACK_KEYS=0  ;

-- --------------------------------------------------------

--
-- Table structure for table `Weapon`
--

CREATE TABLE IF NOT EXISTS `Weapon` (
  `id` int(11) NOT NULL auto_increment,
  `weaponId` int(11) NOT NULL default '0',
  `weaponStrength` int(11) NOT NULL default '0',
  `weaponCount` int(11) NOT NULL default '0',
  `isAttack` int(11) NOT NULL default '0',
  `userId` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `userID` (`userId`)
) TYPE=MyISAM  PACK_KEYS=0 ;
