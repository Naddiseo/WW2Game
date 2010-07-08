-- phpMyAdmin SQL Dump
-- version 3.3.3deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 02, 2010 at 08:38 PM
-- Server version: 5.1.48
-- PHP Version: 5.3.2-1ubuntu5



/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ww2game_epoch1`
--

-- --------------------------------------------------------

--
-- Table structure for table `alliances`
--

CREATE TABLE IF NOT EXISTS `alliances` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `password` varchar(32) NOT NULL,
  `tag` varchar(8) NOT NULL DEFAULT '0',
  `leaderId1` int(10) NOT NULL DEFAULT '0',
  `leaderId2` int(10) NOT NULL DEFAULT '0',
  `leaderId3` int(10) NOT NULL DEFAULT '0',
  `rank` int(11) NOT NULL DEFAULT '0',
  `creationdate` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(2) NOT NULL DEFAULT '0',
  `url` varchar(255) NOT NULL DEFAULT '',
  `irc` varchar(10) NOT NULL DEFAULT '',
  `ircServer` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL,
  `UP` int(10) NOT NULL DEFAULT '0',
  `gold` bigint(15) NOT NULL DEFAULT '0',
  `exp` int(11) NOT NULL,
  `donated` float NOT NULL,
  `usedcash` float NOT NULL,
  `tax` float NOT NULL DEFAULT '0.01',
  `SA` float NOT NULL DEFAULT '0',
  `DA` float NOT NULL DEFAULT '0',
  `CA` float NOT NULL DEFAULT '0',
  `RA` float NOT NULL DEFAULT '0',
  `news` text NOT NULL,
  `bunkers` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `password` (`password`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table structure for table `AttackLog`
--

CREATE TABLE IF NOT EXISTS `AttackLog` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `toUserID` int(11) NOT NULL,
  `attackturns` int(11) NOT NULL,
  `attackStrength` int(11) NOT NULL,
  `defStrength` int(11) NOT NULL,
  `gold` int(11) NOT NULL,
  `attackUsersKilled` int(11) NOT NULL,
  `defUsersKilled` int(11) NOT NULL,
  `attackTrained` int(11) NOT NULL,
  `attackUnTrained` int(11) NOT NULL,
  `defTrained` int(11) NOT NULL,
  `defUnTrained` int(11) NOT NULL,
  `attackWeapons` varchar(2048) NOT NULL,
  `defWeapons` varchar(2048) NOT NULL,
  `time` int(11) NOT NULL,
  `attackWeaponCount` int(11) NOT NULL,
  `defWeaponCount` int(11) NOT NULL,
  `pergold` int(11) NOT NULL,
  `attackMercs` int(11) NOT NULL,
  `defMercs` int(11) NOT NULL,
  `defexp` int(11) NOT NULL,
  `attexp` int(11) NOT NULL,
  `attper` int(11) NOT NULL,
  `defper` int(11) NOT NULL,
  `userhost` int(11) NOT NULL,
  `defuserhost` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `raeff` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `type` (`type`)
) TYPE=MyISAM ;

-- --------------------------------------------------------

--
-- Table structure for table `IPs`
--

CREATE TABLE IF NOT EXISTS `IPs` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(50) NOT NULL,
  `userID` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `userID` (`userID`),
  KEY `ip` (`ip`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table structure for table `log_A`
--

CREATE TABLE IF NOT EXISTS `log_A` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `ip` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM COMMENT='Basic indexing for the logging system' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `Mercenaries`
--

CREATE TABLE IF NOT EXISTS `Mercenaries` (
  `attackSpecCount` int(11) NOT NULL DEFAULT '0',
  `defSpecCount` int(11) NOT NULL DEFAULT '0',
  `untrainedCount` int(11) NOT NULL DEFAULT '0',
  `lastturntime` int(11) NOT NULL DEFAULT '0',
  `avgarmy` bigint(15) NOT NULL DEFAULT '0',
  `avgtbg` bigint(15) NOT NULL DEFAULT '0',
  `avgup` bigint(15) NOT NULL DEFAULT '0',
  `avgsa` bigint(15) NOT NULL DEFAULT '0',
  `avgda` bigint(15) NOT NULL DEFAULT '0',
  `avgra` bigint(15) NOT NULL DEFAULT '0',
  `avgca` bigint(15) NOT NULL DEFAULT '0',
  `avghit` int(11) NOT NULL
) TYPE=MyISAM;

INSERT INTO `Mercenaries` (`lastturntime`) VALUES (UNIX_TIMESTAMP());

-- --------------------------------------------------------

--
-- Table structure for table `Messages`
--

CREATE TABLE IF NOT EXISTS `Messages` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `fromID` int(10) NOT NULL DEFAULT '0',
  `userID` int(10) NOT NULL DEFAULT '0',
  `subject` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `read` tinyint(1) NOT NULL DEFAULT '1',
  `senderStatus` tinyint(1) NOT NULL,
  `date` int(10) NOT NULL DEFAULT '0',
  `age` int(11) NOT NULL,
  `fromadmin` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `targetId` (`fromID`),
  KEY `subject_2` (`subject`),
  KEY `age` (`age`),
  FULLTEXT KEY `subject` (`subject`),
  FULLTEXT KEY `text` (`text`)
) TYPE=MyISAM  PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Table structure for table `outbox`
--

CREATE TABLE IF NOT EXISTS `outbox` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `toID` int(10) NOT NULL DEFAULT '0',
  `userID` int(10) NOT NULL DEFAULT '0',
  `subject` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `read` tinyint(1) NOT NULL DEFAULT '1',
  `senderStatus` tinyint(1) NOT NULL,
  `date` int(10) NOT NULL DEFAULT '0',
  `age` int(11) NOT NULL,
  `fromadmin` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `targetId` (`toID`),
  KEY `subject_2` (`subject`),
  KEY `age` (`age`),
  FULLTEXT KEY `subject` (`subject`),
  FULLTEXT KEY `text` (`text`)
) TYPE=MyISAM  PACK_KEYS=0 ;

-- --------------------------------------------------------

--
-- Table structure for table `proxylist`
--

CREATE TABLE IF NOT EXISTS `proxylist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM COMMENT='List of IPs known to be proxy servers' ;

-- --------------------------------------------------------

--
-- Table structure for table `Ranks`
--

CREATE TABLE IF NOT EXISTS `Ranks` (
  `userID` int(11) NOT NULL,
  `active` int(11) NOT NULL,
  `rank` int(11) NOT NULL,
  `rankfloat` float NOT NULL,
  `sarank` int(11) NOT NULL,
  `darank` int(11) NOT NULL,
  `carank` int(11) NOT NULL,
  `rarank` int(11) NOT NULL,
  PRIMARY KEY (`userID`),
  KEY `rank` (`rank`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table structure for table `SpyLog`
--

CREATE TABLE IF NOT EXISTS `SpyLog` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `toUserID` int(11) NOT NULL,
  `spyStrength` varchar(50) NOT NULL,
  `spyDefStrength` varchar(50) NOT NULL,
  `sasoldiers` varchar(50) NOT NULL,
  `samercs` varchar(50) NOT NULL,
  `dasoldiers` varchar(50) NOT NULL,
  `damercs` varchar(50) NOT NULL,
  `untrainedMerc` varchar(50) NOT NULL,
  `uu` varchar(50) NOT NULL,
  `strikeAction` varchar(50) NOT NULL,
  `defenceAction` varchar(50) NOT NULL,
  `covertSkill` varchar(50) NOT NULL,
  `covertOperatives` varchar(50) NOT NULL,
  `salevel` varchar(10) NOT NULL,
  `attackTurns` varchar(10) NOT NULL,
  `unitProduction` varchar(10) NOT NULL,
  `weapons` varchar(1024) NOT NULL,
  `type` int(11) NOT NULL,
  `types` varchar(1024) NOT NULL,
  `types2` varchar(1024) NOT NULL,
  `quantities` varchar(1024) NOT NULL,
  `strengths` varchar(1024) NOT NULL,
  `allStrengths` varchar(1024) NOT NULL,
  `time` int(11) NOT NULL,
  `spies` int(11) NOT NULL,
  `isSuccess` int(11) NOT NULL,
  `race` int(11) NOT NULL,
  `arace` int(11) NOT NULL,
  `sf` varchar(10) NOT NULL,
  `sflevel` varchar(10) NOT NULL,
  `hh` varchar(10) NOT NULL,
  `gold` bigint(20) NOT NULL,
  `weapontype` int(11) NOT NULL,
  `weapontype2` int(11) NOT NULL,
  `weaponamount` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) TYPE=MyISAM ;

-- --------------------------------------------------------

--
-- Table structure for table `UserDetails`
--

CREATE TABLE IF NOT EXISTS `UserDetails` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `userName` varchar(25) NOT NULL DEFAULT '',
  `race` tinyint(4) NOT NULL DEFAULT '0',
  `email` varchar(100) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `active` int(1) NOT NULL DEFAULT '0',
  `gold` bigint(20) NOT NULL,
  `attackturns` int(11) NOT NULL,
  `bank` bigint(20) NOT NULL,
  `exp` int(11) NOT NULL,
  `uu` int(11) NOT NULL,
  `up` int(11) NOT NULL,
  `sasoldiers` int(11) NOT NULL,
  `samercs` int(11) NOT NULL,
  `dasoldiers` int(11) NOT NULL,
  `damercs` int(11) NOT NULL,
  `spies` int(11) NOT NULL,
  `specialforces` int(11) NOT NULL,
  `scientists` int(11) NOT NULL,
  `salevel` int(11) NOT NULL,
  `dalevel` int(11) NOT NULL,
  `calevel` int(11) NOT NULL,
  `sflevel` int(11) NOT NULL,
  `hhlevel` int(11) NOT NULL,
  `nukelevel` int(11) NOT NULL,
  `bankper` int(11) NOT NULL,
  `bunkers` int(11) NOT NULL,
  `nukes` int(11) NOT NULL,
  `SA` bigint(20) NOT NULL,
  `DA` bigint(20) NOT NULL,
  `CA` bigint(20) NOT NULL,
  `RA` bigint(20) NOT NULL,
  `maxofficers` smallint(2) NOT NULL DEFAULT '5',
  `commander` int(11) NOT NULL,
  `accepted` int(11) NOT NULL,
  `lastturntime` int(10) unsigned NOT NULL DEFAULT '0',
  `clickall` int(11) NOT NULL,
  `gclick` int(11) NOT NULL,
  `bankimg` int(11) NOT NULL,
  `alliance` int(5) NOT NULL DEFAULT '0',
  `supporter` tinyint(2) NOT NULL DEFAULT '0',
  `reason` varchar(255) NOT NULL DEFAULT '',
  `cheatcount` int(10) NOT NULL DEFAULT '0',
  `status` varchar(50) NOT NULL DEFAULT '',
  `donatorgold` bigint(15) unsigned NOT NULL DEFAULT '0',
  `vaulttime` bigint(15) unsigned NOT NULL DEFAULT '0',
  `lastvault` bigint(15) unsigned NOT NULL DEFAULT '0',
  `numofficers` int(10) NOT NULL DEFAULT '0',
  `commandergold` int(11) NOT NULL,
  `officerup` int(11) NOT NULL,
  `offline` int(10) NOT NULL DEFAULT '0',
  `weapper` int(11) NOT NULL,
  `savings` bigint(15) NOT NULL DEFAULT '0',
  `aaccepted` tinyint(1) NOT NULL DEFAULT '0',
  `referrer` int(10) NOT NULL DEFAULT '0',
  `treasuryAttack` tinyint(1) NOT NULL DEFAULT '0',
  `donatorType` tinyint(2) NOT NULL DEFAULT '0',
  `isTop15` tinyint(2) NOT NULL DEFAULT '0',
  `gameSkill` int(5) NOT NULL DEFAULT '0',
  `goldRush` int(5) NOT NULL DEFAULT '0',
  `onlineTotal` int(5) NOT NULL DEFAULT '0',
  `gcCount` int(3) NOT NULL DEFAULT '0',
  `spyCredits` int(4) NOT NULL DEFAULT '0',
  `CrimeTicks` int(1) NOT NULL DEFAULT '0',
  `CrimeCommitted` int(1) NOT NULL DEFAULT '0',
  `CrimeCode` int(1) NOT NULL DEFAULT '0',
  `frozen` int(1) NOT NULL DEFAULT '0',
  `tc` int(4) NOT NULL DEFAULT '0',
  `vacation` int(11) NOT NULL DEFAULT '0',
  `graceday` int(1) NOT NULL DEFAULT '1',
  `globMercs` int(3) NOT NULL DEFAULT '5',
  `SpentGC` int(1) NOT NULL DEFAULT '1',
  `supportP1` int(1) NOT NULL DEFAULT '0',
  `supportP2` int(1) NOT NULL DEFAULT '0',
  `supportP3` int(1) NOT NULL DEFAULT '0',
  `supportGD` int(1) NOT NULL DEFAULT '0',
  `template` int(1) NOT NULL DEFAULT '1',
  `holdingGR` int(1) NOT NULL DEFAULT '0',
  `GRattacks` int(1) NOT NULL DEFAULT '0',
  `changenick` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `alliance` (`alliance`),
  KEY `referrer` (`referrer`)
) TYPE=InnoDB  PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Table structure for table `Weapon`
--

CREATE TABLE IF NOT EXISTS `Weapon` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `weaponID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `isAttack` int(11) NOT NULL,
  `weaponStrength` int(11) NOT NULL,
  `weaponCount` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `weaponID` (`weaponID`,`userID`)
) TYPE=MyISAM ;
