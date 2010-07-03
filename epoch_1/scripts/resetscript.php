<?
//dummy function
function duration($d) {
	return 1;
}
include ('conf.php');
include ('db.php');
$tbl = '5';
$hf = '9';
//copy the tables do this manually
//mysql_query("DROP TABLE AttackLog{$tbl},Ranks{$tbl},SpyLog{$tbl},UserDetails{$tbl},Weapon{$tbl};");
//mysql_query("CREATE TABLE .`AttackLog{$tbl}` ( `ID` int( 11 ) NOT NULL auto_increment , `userID` int( 11 ) NOT NULL default '0', `toUserID` int( 11 ) NOT NULL default '0', `attackTurns` int( 11 ) NOT NULL default '0', `attackStrength` bigint( 15 ) unsigned NOT NULL default '0', `defStrength` bigint( 15 ) unsigned NOT NULL default '0', `gold` bigint( 15 ) unsigned NOT NULL default '0', `phrase` varchar( 255 ) NOT NULL default '', `attackUsersKilled` int( 11 ) NOT NULL default '0', `defUsersKilled` int( 11 ) NOT NULL default '0', `attackTrained` int( 11 ) NOT NULL default '0', `attackUnTrained` int( 11 ) NOT NULL default '0', `defTrained` int( 11 ) NOT NULL default '0', `defUnTrained` int( 11 ) NOT NULL default '0', `attackWeapons` varchar( 255 ) NOT NULL default '', `defWeapons` varchar( 255 ) NOT NULL default '', `time` int( 11 ) NOT NULL default '0', `attackWeaponCount` varchar( 255 ) NOT NULL default '', `defWeaponCount` varchar( 255 ) NOT NULL default '', `pergold` int( 11 ) NOT NULL default '0', `attackMercs` int( 11 ) NOT NULL default '0', `defMercs` int( 11 ) NOT NULL default '0', `defexp` smallint( 2 ) NOT NULL default '0', `attexp` smallint( 2 ) NOT NULL default '0', `attper` smallint( 3 ) NOT NULL default '0', `defper` smallint( 3 ) NOT NULL default '0', `userhost` int( 11 ) NOT NULL default '0', `defuserhost` int( 11 ) NOT NULL default '0', `type` smallint( 1 ) default '0', `raeff` float NOT NULL default '0', UNIQUE KEY `ID` ( `ID` ) , KEY `userID` ( `userID` , `toUserID` , `gold` , `time` ) );");
//mysql_query("INSERT INTO .`AttackLog{$tbl}` SELECT * FROM .`AttackLog`;");
//mysql_query("CREATE TABLE .`Ranks{$tbl}` (`userID` int( 11 ) NOT NULL AUTO_INCREMENT ,`rank` int( 11 ) NOT NULL default '0',`sarank` int( 11 ) NOT NULL default '0',`darank` int( 11 ) NOT NULL default '0',`carank` int( 11 ) NOT NULL default '0',`rarank` int( 11 ) NOT NULL default '0',`rankfloat` float NOT NULL default '0',`active` tinyint( 1 ) NOT NULL default '0',PRIMARY KEY ( `userID` ));");
//mysql_query("INSERT INTO .`Ranks{$tbl}` SELECT * FROM .`Ranks` ;");
//mysql_query("CREATE TABLE .`SpyLog{$tbl}` ( `ID` int( 11 ) NOT NULL auto_increment , `userID` int( 11 ) NOT NULL default '0', `toUserID` int( 11 ) NOT NULL default '0', `spyStrength` int( 11 ) NOT NULL default '0', `spyDefStrength` int( 11 ) NOT NULL default '0', `sasoldiers` varchar( 15 ) NOT NULL default '', `samercs` varchar( 15 ) NOT NULL default '', `dasoldiers` varchar( 15 ) NOT NULL default '', `damercs` varchar( 15 ) NOT NULL default '', `untrainedMerc` varchar( 15 ) NOT NULL default '', `untrainedSold` varchar( 15 ) NOT NULL default '', `strikeAction` varchar( 15 ) NOT NULL default '', `defenceAction` varchar( 15 ) NOT NULL default '', `covertSkill` varchar( 15 ) NOT NULL default '', `covertOperatives` varchar( 15 ) NOT NULL default '', `salevel` varchar( 15 ) NOT NULL default '', `attackTurns` varchar( 15 ) NOT NULL default '', `unitProduction` varchar( 15 ) NOT NULL default '', `weapons` varchar( 255 ) NOT NULL default '', `types` varchar( 255 ) NOT NULL default '', `types2` varchar( 255 ) NOT NULL default '', `quantities` varchar( 255 ) NOT NULL default '', `strengths` varchar( 255 ) NOT NULL default '', `allStrengths` varchar( 255 ) NOT NULL default '', `time` int( 11 ) NOT NULL default '0', `spies` int( 11 ) NOT NULL default '0', `isSuccess` tinyint( 1 ) NOT NULL default '0', `race` int( 11 ) NOT NULL default '0', `sf` int( 11 ) NOT NULL default '0', `sflevel` int( 11 ) NOT NULL default '0', `hh` int( 11 ) NOT NULL default '0', `weapontype` tinyint( 4 ) NOT NULL default '0', `type` tinyint( 4 ) NOT NULL default '0', `weaponamount` int( 11 ) NOT NULL default '0', `uu` int( 11 ) NOT NULL default '0', `weapontype2` tinyint( 4 ) NOT NULL default '0', `arace` tinyint( 4 ) NOT NULL default '0', `gold` int( 11 ) NOT NULL default '0', PRIMARY KEY ( `ID` ) );");
//mysql_query("INSERT INTO .`SpyLog{$tbl}` SELECT * FROM .`SpyLog`;");
//mysql_query("CREATE TABLE .`UserDetails{$tbl}` ( `ID` int( 11 ) NOT NULL auto_increment , `userName` varchar( 25 ) NOT NULL default '', `race` tinyint( 4 ) NOT NULL default '0', `email` varchar( 100 ) NOT NULL default '', `password` varchar( 32 ) NOT NULL default '', `passwdstrength` int( 11 ) NOT NULL default '0', `gclick` tinyint( 2 ) NOT NULL default '15', `commander` int( 11 ) NOT NULL default '0', `active` int( 1 ) NOT NULL default '0', `uniqueLink` varchar( 12 ) NOT NULL default '', `dalevel` int( 11 ) NOT NULL default '0', `salevel` int( 11 ) NOT NULL default '0', `gold` bigint( 15 ) NOT NULL default '0', `bank` bigint( 15 ) NOT NULL default '0', `savings` bigint( 15 ) NOT NULL default '0', `attackturns` int( 11 ) NOT NULL default '0', `up` int( 11 ) default NULL , `calevel` int( 11 ) default NULL , `sflevel` int( 11 ) NOT NULL default '0', `maxofficers` int( 11 ) NOT NULL default '25', `sasoldiers` int( 11 ) default NULL , `samercs` int( 11 ) default NULL , `dasoldiers` int( 11 ) default NULL , `damercs` int( 11 ) default NULL , `uu` float default NULL , `spies` int( 11 ) default NULL , `image` varchar( 255 ) default NULL , `lastturntime` int( 11 ) NOT NULL default '0', `accepted` tinyint( 1 ) NOT NULL default '0', `commandergold` bigint( 15 ) NOT NULL default '0', `exp` bigint( 15 ) NOT NULL default '0', `specialforces` bigint( 15 ) unsigned NOT NULL default '0', `bankper` int( 11 ) NOT NULL default '10', `SA` bigint( 15 ) NOT NULL default '0', `DA` bigint( 15 ) NOT NULL default '0', `CA` bigint( 15 ) NOT NULL default '0', `RA` bigint( 15 ) NOT NULL default '0', `alliance` int( 5 ) NOT NULL default '0', `hhlevel` int( 11 ) NOT NULL default '0', `officerup` bigint( 15 ) NOT NULL default '0', `changenick` tinyint( 4 ) NOT NULL default '0', `admin` int( 11 ) NOT NULL default '0', `vote` tinyint( 1 ) NOT NULL default '0', `clicks` int( 11 ) NOT NULL default '0', `weapper` int( 11 ) NOT NULL default '0', `supporter` tinyint( 2 ) NOT NULL default '0', `team` tinyint( 1 ) NOT NULL default '0', `teamacc` tinyint( 4 ) NOT NULL default '0', `reason` varchar( 255 ) NOT NULL default '', `nukelevel` smallint( 3 ) default '0', `scientists` int( 11 ) default '0', `plutonium` bigint( 15 ) default '0', `bunkers` bigint( 15 ) default '0', `nukes` int( 11 ) NOT NULL default '0', `nukesteps` tinyint( 2 ) NOT NULL default '0', `clickall` tinyint( 1 ) NOT NULL default '0', `bankimg` tinyint( 1 ) NOT NULL default '1', `nukesbutton` text NOT NULL , `ircnick` varchar( 20 ) NOT NULL default '', PRIMARY KEY ( `ID` ) )");
//mysql_query("INSERT INTO .`UserDetails{$tbl}` SELECT * FROM .`UserDetails`;");
//mysql_query("CREATE TABLE .`Weapon{$tbl}` (`ID` int( 11 ) NOT NULL AUTO_INCREMENT ,`weaponID` int( 11 ) NOT NULL default '0',`weaponStrength` int( 11 ) NOT NULL default '0',`weaponCount` int( 11 ) NOT NULL default '0',`isAttack` int( 11 ) NOT NULL default '0',`userID` int( 11 ) NOT NULL default '0',PRIMARY KEY ( `ID` )) ;");
//mysql_query("INSERT INTO .`Weapon{$tbl}` SELECT *FROM .`Weapon` ;");
//compile HOF
mysql_query("DROP TABLE hof{$hf};");
$hof_table = " CREATE TABLE `hof{$hf}` (
`ID` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`username` VARCHAR( 50 ) NOT NULL ,
`alliance` VARCHAR( 50 ) ,
`race` SMALLINT( 1 ) NOT NULL ,
`bankper` TINYINT( 2 ) NOT NULL ,
`offup` INT( 10 ) NOT NULL ,
`untrained` INT( 11 ) NOT NULL ,
`trainedsa` INT( 11 ) NOT NULL ,
`trainedda` INT( 11 ) NOT NULL ,
`samerc` INT( 11 ) NOT NULL ,
`damerc` INT( 11 ) NOT NULL ,
`unmerc` INT( 11 ) NOT NULL ,
`spies` INT( 11 ) NOT NULL ,
`sf` INT( 11 ) NOT NULL ,
`raupgrade` INT( 2 ) NOT NULL ,
`saupgrade` INT( 2 ) NOT NULL ,
`daupgrade` INT( 2 ) NOT NULL ,
`caupgrade` INT( 2 ) NOT NULL ,
`sa` BIGINT( 15 ) NOT NULL ,
`da` BIGINT( 15 ) NOT NULL ,
`ca` BIGINT( 15 ) NOT NULL ,
`ra` BIGINT( 15 ) NOT NULL ,
`sarank` INT( 11 ) NOT NULL ,
`darank` INT( 11 ) NOT NULL ,
`carank` INT( 11 ) NOT NULL ,
`rarank` INT( 11 ) NOT NULL ,
`goldwon` BIGINT( 15 ) NOT NULL ,
`goldlost` BIGINT( 15 ) NOT NULL ,
`battleswon` BIGINT( 15 ) NOT NULL ,
`battleslost` BIGINT( 15 ) NOT NULL ,
`battlesdefended` BIGINT( 15 ) NOT NULL ,
`thieftscore` BIGINT( 15 ) NOT NULL ,
`thieftuu` BIGINT( 15 ) NOT NULL ,
`thieftgold` BIGINT( 15 ) NOT NULL ,
`up` INT( 11 ) NOT NULL ,
`offierup` INT( 11 ) NOT NULL ,
`income` INT( 11 ) NOT NULL ,
`exp` INT( 11 ) NOT NULL ,
`numoffiers` INT( 11 ) NOT NULL ,
`nuked` INT(11) DEFAULT 0,
PRIMARY KEY ( `ID` ) ,
UNIQUE (
`username`
)
) TYPE = MYISAM ;";
mysql_query($hof_table) or die("2:" . mysql_error());
mysql_query("TRUNCATE hof{$hf};") or die("0:" . mysql_error());
$hofq = mysql_query("SELECT UserDetails{$tbl}.*,Ranks{$tbl}.sarank,Ranks{$tbl}.darank,Ranks{$tbl}.carank,Ranks{$tbl}.rarank FROM UserDetails{$tbl},Ranks{$tbl} WHERE UserDetails{$tbl}.active=1 AND UserDetails{$tbl}.ID=Ranks{$tbl}.userID") or die("1:" . mysql_error());
while ($hofa = mysql_fetch_array($hofq, MYSQL_ASSOC)) {
	ob_clean();
	$alliq = mysql_query("SELECT name FROM alliances WHERE id=$hofa[alliance]") or die(mysql_error());
	$allia = mysql_fetch_array($alliq, MYSQL_ASSOC);
	$alliance = addslashes($allia['name'] ? $allia['name'] : '');
	$offcountq = mysql_query("SELECT count(*) FROM UserDetails{$tbl} WHERE commander=$hofa[ID]") or die("3:" . mysql_error());
	$offcounta = mysql_fetch_array($offcountq);
	$offcount = $offcounta[0]; //Number of officers they have
	$offcountq = mysql_query("SELECT sum(gold) FROM AttackLog{$tbl} WHERE userID=$hofa[ID]") or die("4:" . mysql_error());
	$offcounta = mysql_fetch_array($offcountq);
	$sumgold = $offcounta[0]; // Amount of gold they've stolen
	$offcountq = mysql_query("SELECT sum(gold) FROM AttackLog{$tbl} WHERE toUserID=$hofa[ID]") or die("5:" . mysql_error());
	$offcounta = mysql_fetch_array($offcountq);
	$goldlost = $offcounta[0]; //gold lost
	$offcountq = mysql_query("SELECT count(*) FROM AttackLog{$tbl} WHERE toUserID=$hofa[ID] AND type=1") or die("5:" . mysql_error());
	$offcounta = mysql_fetch_array($offcountq);
	$nuked = $offcounta[0]; //gold
	$offcountq = mysql_query("SELECT count(*) FROM AttackLog{$tbl} WHERE attackStrength > defStrength AND userID=$hofa[ID]") or die("6:" . mysql_error());
	$offcounta = mysql_fetch_array($offcountq);
	$battleswon = $offcounta[0]; //battles won
	$offcountq = mysql_query("SELECT count(*) FROM AttackLog{$tbl} WHERE attackStrength < defStrength AND toUserID=$hofa[ID]") or die("7:" . mysql_error());
	$offcounta = mysql_fetch_array($offcountq);
	$battleswon+= $offcounta[0]; //battles defended
	$offcountq = mysql_query("SELECT count(*) FROM AttackLog{$tbl} WHERE attackStrength < defStrength AND userID=$hofa[ID]") or die("8:" . mysql_error());
	$offcounta = mysql_fetch_array($offcountq);
	$battleslost = $offcounta[0];
	$offcountq = mysql_query("SELECT count(*) FROM AttackLog{$tbl} WHERE attackStrength > defStrength AND toUserID=$hofa[ID]") or die("9:" . mysql_error());
	$offcounta = mysql_fetch_array($offcountq);
	$battleslost+= $offcounta[0];
	$offcountq = mysql_query("SELECT count(*) FROM AttackLog{$tbl} WHERE attackStrength < defStrength AND toUserID=$hofa[ID]") or die("10:" . mysql_error());
	$offcounta = mysql_fetch_array($offcountq);
	$battlesdef = $offcounta[0];
	$b = ($hofa[sasoldiers] + $hofa[sasoldiers] + $hofa[uu]) * ($conf['gps'][$hofa[race]]);
	$income = $b;
	$thieftscore = 0;
	$offcountq = mysql_query("SELECT (sum(weaponamount)*50) FROM SpyLog{$tbl} WHERE weapontype2=0 AND isSuccess=1 AND type=1 AND userID=$hofa[ID]") or die("20:" . mysql_error());
	$offcounta = mysql_fetch_array($offcountq);
	$thieftscore+= $offcounta[0];
	$offcountq = mysql_query("SELECT (sum(weaponamount)*100) FROM SpyLog{$tbl} WHERE weapontype2=1 AND isSuccess=1 AND type=1 AND userID=$hofa[ID]") or die("21:" . mysql_error());
	$offcounta = mysql_fetch_array($offcountq);
	$thieftscore+= $offcounta[0];
	$offcountq = mysql_query("SELECT (sum(weaponamount)*250) FROM SpyLog{$tbl} WHERE weapontype2=2 AND isSuccess=1 AND type=1 AND userID=$hofa[ID]") or die("22:" . mysql_error());
	$offcounta = mysql_fetch_array($offcountq);
	$thieftscore+= $offcounta[0];
	$offcountq = mysql_query("SELECT (sum(weaponamount)*500) FROM SpyLog{$tbl} WHERE weapontype2=3 AND isSuccess=1 AND type=1 AND userID=$hofa[ID]") or die("23:" . mysql_error());
	$offcounta = mysql_fetch_array($offcountq);
	$thieftscore+= $offcounta[0];
	$offcountq = mysql_query("SELECT (sum(weaponamount)*1500) FROM SpyLog{$tbl} WHERE weapontype2=4 AND isSuccess=1 AND type=1 AND userID=$hofa[ID]") or die("24:" . mysql_error());
	$offcounta = mysql_fetch_array($offcountq);
	$thieftscore+= $offcounta[0];
	$offcountq = mysql_query("SELECT (sum(weaponamount)*3000) FROM SpyLog{$tbl} WHERE weapontype2=5 AND isSuccess=1 AND type=1 AND userID=$hofa[ID]") or die("25:" . mysql_error());
	$offcounta = mysql_fetch_array($offcountq);
	$thieftscore+= $offcounta[0];
	$offcountq = mysql_query("SELECT (sum(weaponamount)*6000) FROM SpyLog{$tbl} WHERE weapontype2=6 AND isSuccess=1 AND type=1 AND userID=$hofa[ID]") or die("26:" . mysql_error());
	$offcounta = mysql_fetch_array($offcountq);
	$thieftscore+= $offcounta[0];
	$offcountq = mysql_query("SELECT (sum(weaponamount)*8000) FROM SpyLog{$tbl} WHERE weapontype2=7 AND isSuccess=1 AND type=1 AND userID=$hofa[ID]") or die("27:" . mysql_error());
	$offcounta = mysql_fetch_array($offcountq);
	$thieftscore+= $offcounta[0];
	$offcountq = mysql_query("SELECT (sum(weaponamount)*10000) FROM SpyLog{$tbl} WHERE weapontype2=8 AND isSuccess=1 AND type=1 AND userID=$hofa[ID]") or die("28:" . mysql_error());
	$offcounta = mysql_fetch_array($offcountq);
	$thieftscore+= $offcounta[0];
	$offcountq = mysql_query("SELECT sum(uu) FROM SpyLog{$tbl} WHERE isSuccess=1 AND type=1 AND userID=$hofa[ID]") or die("29:" . mysql_error());
	$offcounta = mysql_fetch_array($offcountq);
	$thieftuu = $offcounta[0];
	$offcountq = mysql_query("SELECT sum(gold) FROM SpyLog{$tbl} WHERE isSuccess=1 AND type=1 AND userID=$hofa[ID]") or die("30:" . mysql_error());
	$offcounta = mysql_fetch_array($offcountq);
	$thieftgold = $offcounta[0];
	$sql = "INSERT hof{$hf} (`username`,`alliance`,`race`,`bankper`,`offup`,`untrained`,`trainedsa`,`trainedda`,`samerc`,`damerc`,
`spies`,`sf`,`raupgrade`,`saupgrade`,`daupgrade`,`caupgrade`,`sa`,`da`,`ca`,`ra`,`sarank`,`darank`,`carank`,`rarank`,`goldwon`, 
`goldlost`,`battleswon`,`battleslost`,`battlesdefended`, `up`,`income`, `exp`, `numoffiers`,`thieftscore`,`thieftuu`,`thieftgold`,`nuked`) VALUES
 ('" . addslashes($hofa[userName]) . "','$alliance','$hofa[race]','$hofa[bankper]','$hofa[officerup]','$hofa[uu]','$hofa[sasoldiers]',
      '$hofa[dasoldiers]','$hofa[samercs]','$hofa[damercs]','$hofa[spies]','$hofa[specialforces]',
      '$hofa[sflevel]','$hofa[salevel]','$hofa[dalevel]','$hofa[calevel]','$hofa[SA]','$hofa[DA]','$hofa[CA]','$hofa[RA]',
      '$hofa[sarank]','$hofa[darank]','$hofa[carank]','$hofa[rarank]','$sumgold',
      '$goldlost','$battleswon','$battleslost', '$battlesdef','$hofa[up]','$income','$hofa[exp]','$offcount','$thieftscore','$thieftuu','$thieftgold','$nuked'); ";
	//echo "did $hofa[userName]<br>";
	$add = mysql_query($sql) or die("11:" . mysql_error());
}
//clean DB
/* $sql1="UPDATE UserDetails SET fortificationLevel=0,siegeLevel=0,
  gold=50000,bank=0,attackTurns=50,currentSpySkill=0,CurrentUnitProduction=0,
  sflevel=0,trainedAttackSold=0,trainedDefSold=0,trainedAttackMerc=0,
  trainedDefMerc=0,untrainedSold=5,untrainedMerc=0,spies=0,commandergold=0,specialforces=0,
  bankper=10,SA=0,DA=0,CA=0,RA=0,officerup=0";     
  mysql_query('TRUNCATE `AtackLog`');
        mysql_query( ' TRUNCATE `IPs`'); 
        mysql_query( ' TRUNCATE `Messages`'); 
        mysql_query( ' TRUNCATE `SpyLog`'); 
        mysql_query( ' TRUNCATE `Weapon`'); 
        mysql_query( ' TRUNCATE `click`'); 
        mysql_query( ' TRUNCATE `iplog`'); 
        mysql_query( ' TRUNCATE `log`'); 
        mysql_query( ' TRUNCATE `log_B`'); 
		mysql_query( ' TRUNCATE `log_C`');
		mysql_query( ' TRUNCATE `log_D`');
        mysql_query( ' TRUNCATE `lognew`'); 
        mysql_query( ' TRUNCATE `outbox`'); 
  
  
 // mysql_query($sql1) or die("12:".mysql_error());
 mysql_query("UPDATE Mercenaries SET lastTurnTime='".time()."',attackSpecCount=0,defSpecCount=0");
  
  //Enable
  
 // $configq=mysql_query("UPDATE `config` SET `value`='false' WHERE `name`='offline'") or die("14:".mysql_error());  */
mysql_query("update UserDetails set gclick=15,salevel=0,dalevel=0,gold=0,bank=50000,savings=0,attackturns=50,up=0,calevel=0,sflevel=0,sasoldiers=0,samercs=0,dasoldiers=0,damercs=0,uu=200,spies=0,specialforces=0,sa=0,da=0,ca=0,ra=0,hhlevel=0,changenick=0,clicks=0,weapper=0,nukelevel=0,scientists=0,plutonium=0,bunkers=0,nukes=0,nukesteps=0,clickall=0,bankimg=0");
mysql_query(' TRUNCATE `Messages`');
mysql_query(' TRUNCATE `SpyLog`');
mysql_query(' TRUNCATE `Weapon`');
mysql_query(' TRUNCATE `click`');
mysql_query(' TRUNCATE `log_A`');
mysql_query(' TRUNCATE `outbox`');
mysql_query(' TRUNCATE `AttackLog`');
mysql_query('update alliances set gold=0,exp=0,sa=0,da=0,ca=0,ra=0,up=0, ap=0,usedcash=0,donated=donated+usedcash');
mysql_query('update alliances set usedcash=0');
?>
