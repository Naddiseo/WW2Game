<?

/***

    World War II MMORPG
    Copyright (C) 2009 Richard Eames

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

***/


/** Changelog
29th May, 10   Update for age 18
24th Apr, 10   Update for age 17
---- Mar, 10
26th Feb, 10   Update for age 15 added area stuff to hof
26th Dec, 09   Update for age 14
28th Nov, 09   Update for age 13
1st  Nov, 09.  Update for age 12
28th Sept, 09. Update for age 11
30th Aug, 09.  Update for age 10
26th July, 09. Update for age 9 or eight...
28th June, 09. Ran for age 7. User.sflevel -> User.ralevel
24th June, 09 - click -> Recruit, hof.race -> hof.nation
23rd June, 09 (pre age 8, initial)
 
*/


/*

// move top 30 up from area 2->1
update User set area=4 where area=2 and rank <=30 and  rank >=1 and active=1;
// move top 60 up from area 2->2
update User set area=5 where area=3 and rank <= 60 and rank >= 1 and active=1;
//move bottom 60 down from area 2->3
update User set area=6 where area=2 and rank >= 181 and rank <= 240 and active=1;
update User set area=1 where area=4;
update User set area=2 where area=5;
update User set area=3 where area=6;
*/

// First, tell all the scripts that we're running without HTML
$incron = true;
// Include everything we need
require_once('vsys.php');

$hof = $_AGE;
die("MAKE SURE THE AGE IS CORRECT");
/*
	Stage 1 : Back up the tables
*/
// A list of tables that we'll use, so need to back up
$tableList = array(
	'BattleLog',
	'SpyLog',
	'User',
	'Weapon'
);

foreach ($tableList as $table) {
	// Copy the tables
	echo "resetscript: dropping $table$hof if exists\n";
	@mysql_query("drop table if exists hof$hof");
	echo "resetscript: backing up table $table as $table$hof\n";
	$q = mysql_query("CREATE TABLE IF NOT EXISTS $table$hof SELECT * FROM $table") or die(mysql_error());
}

// TODO: update for table column names.

// create the new HOF table
$hofTable = "CREATE TABLE IF NOT EXISTS `hof{$hof}` (
	`id` INT( 10 ) NOT NULL AUTO_INCREMENT ,
	`uid` INT(10) NOT NULL DEFAULT 0,
	`area` TINYINT(2) NOT NULL DEFAULT 2,
	`username` VARCHAR( 50 ) NOT NULL ,
	`alliance` VARCHAR( 50 )  DEFAULT 0,
	`nation` SMALLINT( 1 ) NOT NULL  DEFAULT 0,
	`bankper` TINYINT( 2 ) NOT NULL  DEFAULT 0,
	`officerup` INT( 10 ) NOT NULL  DEFAULT 0,
	`untrained` INT( 11 ) NOT NULL  DEFAULT 0,
	`trainedsa` INT( 11 ) NOT NULL  DEFAULT 0,
	`trainedda` INT( 11 ) NOT NULL  DEFAULT 0,
	`samerc` INT( 11 ) NOT NULL  DEFAULT 0,
	`damerc` INT( 11 ) NOT NULL  DEFAULT 0,
	`spies` INT( 11 ) NOT NULL  DEFAULT 0,
	`sf` INT( 11 ) NOT NULL  DEFAULT 0,
	`raupgrade` INT( 2 ) NOT NULL  DEFAULT 0,
	`saupgrade` INT( 2 ) NOT NULL  DEFAULT 0,
	`daupgrade` INT( 2 ) NOT NULL  DEFAULT 0,
	`caupgrade` INT( 2 ) NOT NULL  DEFAULT 0,
	`sa` BIGINT( 15 ) NOT NULL  DEFAULT 0,
	`da` BIGINT( 15 ) NOT NULL  DEFAULT 0,
	`ca` BIGINT( 15 ) NOT NULL  DEFAULT 0,
	`ra` BIGINT( 15 ) NOT NULL  DEFAULT 0,
	`sarank` INT( 11 ) NOT NULL  DEFAULT 0,
	`darank` INT( 11 ) NOT NULL  DEFAULT 0,
	`carank` INT( 11 ) NOT NULL  DEFAULT 0,
	`rarank` INT( 11 ) NOT NULL  DEFAULT 0,
	`goldwon` BIGINT( 15 ) NOT NULL  DEFAULT 0,
	`goldlost` BIGINT( 15 ) NOT NULL  DEFAULT 0,
	`battleswon` BIGINT( 15 ) NOT NULL  DEFAULT 0,
	`battleslost` BIGINT( 15 ) NOT NULL  DEFAULT 0,
	`battlesdefended` BIGINT( 15 ) NOT NULL  DEFAULT 0,
	`battlesuuwon` BIGINT(15) NOT NULL DEFAULT 0,
	`battlesuulost` BIGINT(15) NOT NULL DEFAULT 0,
	`theftscore` BIGINT( 15 ) NOT NULL  DEFAULT 0,
	`theftuu` BIGINT( 15 ) NOT NULL  DEFAULT 0,
	`theftgold` BIGINT( 15 ) NOT NULL  DEFAULT 0,
	`up` INT( 11 ) NOT NULL  DEFAULT 0,
	`income` INT( 11 ) NOT NULL  DEFAULT 0,
	`numofficers` INT( 11 ) NOT NULL  DEFAULT 0,
	PRIMARY KEY ( `ID` ),
	UNIQUE (
	`username`
	)
) TYPE = MYISAM ;";

mysql_query($hofTable) or die("2:".mysql_error());

mysql_query("truncate hof$hof") or die(mysql_error());

echo "resetscript: inserting primary values into hof$hof\n";
// insert the basic values
$hofq = mysql_query("
	INSERT INTO
		hof$hof
	(
		uid,
		area,
		username,
		alliance,
		nation,
		bankper,
		officerup,
		untrained,
		trainedsa,
		trainedda,
		samerc,
		damerc,
		spies,
		sf,
		raupgrade,
		saupgrade,
		daupgrade,
		caupgrade,
		sa,
		da,
		ca,
		ra,
		sarank,
		darank,
		carank,
		rarank,
		up,
		numofficers
	)
	SELECT
		id,
		area,
		username,
		alliance,
		nation,
		bankper,
		officerup,
		uu,
		sasoldiers,
		dasoldiers,
		samercs,
		damercs,
		spies,
		specialforces,
		ralevel,
		salevel,
		dalevel,
		calevel,
		sa,
		da,
		ca,
		ra,
		sarank,
		darank,
		carank,
		rarank,
		up,
		numofficers
	FROM
		User
	WHERE
		active = 1;
") or die("1:".mysql_error());

$users = User::getActiveUsers(false, false);

$nusers = count($users);
$i = 0;
foreach($users as $user) {
	echo "resetscript: (" . round(($i / $nusers)*100, 2) . "%) calculating user ($user->id:$user->username)\n";
	$i++;
	$ret = null;
	
	$q = mysql_query("select sum(goldStolen) as retCode from BattleLog where attackerId = $user->id") or die(mysql_error());
	$a = mysql_fetch_object($q);
	$ret->goldTaken = (float)$a->retCode;
	
	$q = mysql_query("select sum(goldStolen) as retCode from BattleLog where targetId = $user->id") or die(mysql_error());
	$a = mysql_fetch_object($q);
	$ret->goldLost = (float)$a->retCode;
	
	$q = mysql_query("select count(*) as retCode from BattleLog where isSuccess = 1 and attackerId = $user->id") or die(mysql_error());
	$a = mysql_fetch_object($q);
	$ret->battlesWon = (float)$a->retCode;
	
	$q = mysql_query("select count(*) as retCode from BattleLog where isSuccess = 1 and targetId = $user->id") or die(mysql_error());
	$a = mysql_fetch_object($q);
	$ret->battlesDefended = (float)$a->retCode;
	
	$q = mysql_query("select count(*) as retCode from BattleLog where isSuccess = 0 and attackerId = $user->id") or die(mysql_error());
	$a = mysql_fetch_object($q);
	$ret->battlesLost = (float)$a->retCode;
	
	$q = mysql_query("select count(*) as retCode from BattleLog where isSuccess = 0 and targetId = $user->id") or die(mysql_error());
	$a = mysql_fetch_object($q);
	$ret->battlesNotDefended = (float)$a->retCode;
	
	$q = mysql_query("select sum(attackerHostages) as retCode from BattleLog where attackerId = $user->id") or die(mysql_error());
	$a = mysql_fetch_object($q);
	$ret->powTaken = (float)$a->retCode;
	
	$q = mysql_query("select sum(targetHostages) as retCode from BattleLog where targetId = $user->id") or die(mysql_error());
	$a = mysql_fetch_object($q);
	$ret->powTaken += (float)$a->retCode;
	
	$q = mysql_query("select sum(attackerHostages) as retCode from BattleLog where  targetId = $user->id") or die(mysql_error());
	$a = mysql_fetch_object($q);
	$ret->powLost = (float)$a->retCode;
	
	$q = mysql_query("select sum(targetHostages) as retCode from BattleLog where attackerId = $user->id") or die(mysql_error());
	$a = mysql_fetch_object($q);
	$ret->powLost += (float)$a->retCode;
	
	
	$ret->income = $user->getIncome();
	
	$ret->theftScore = 0;
	$q = mysql_query("select * from SpyLog where type=1 and attackerId = $user->id and isSuccess = 1 and weaponamount > 0") or die(mysql_error());
	while ($r = mysql_fetch_object($q)) {
		$ret->theftScore += ($r->weaponamount * $conf['weapon' . $r->weapontype2 . 'strength']);
	}
	
	$q = mysql_query("select sum(goldStolen) as retCode from SpyLog where type=2 and attackerId = $user->id") or die(mysql_error());
	$a = mysql_fetch_object($q);
	$ret->theftGold = (float)$a->retCode;
	
	$q = mysql_query("select sum(hostages) as retCode from SpyLog where type > 0 and attackerId = $user->id") or die(mysql_error());
	$a = mysql_fetch_object($q);
	$ret->theftUU = (float)$a->retCode;
	
	$sql = "
		update hof$hof
		set
			goldwon         = $ret->goldTaken,
			goldlost        = $ret->goldLost,
			battleswon      = $ret->battlesWon,
			battleslost     = $ret->battlesLost,
			battlesdefended = $ret->battlesDefended,
			battlesuuwon    = $ret->powTaken,
			battlesuulost   = $ret->powLost,
			theftscore      = $ret->theftScore,
			theftuu         = $ret->theftUU,
			theftgold       = $ret->theftGold,
			income          = $ret->income
		where
			uid = $user->id
	";
	mysql_query($sql) or die(mysql_error());
}

// Tables to Truncate
$clean = array(
	'BattleLog',
	'Recruit',
	'SpyLog',
	'Weapon',
);

foreach ($clean as $tbl) {
	echo "resetscript: truncating table $tbl\n";
	$sql = "TRUNCATE $tbl";
	mysql_query($sql) or die(mysql_error());
}

// Now reset the Mercenaries table
$sql = "
update 
	Mercenaries 
set
	attackspeccount = 0,
	defspeccount    = 0,
	untrainedcount  = 0;
";
mysql_query($sql) or die(mysql_error());

echo "resetscript: resetting User\n";
// reset User
$sql = "
update
	User
set
	gclick        = 15,
	dalevel       = 0,
	salevel       = 0,
	gold          = {$conf['start-gold']},
	bank          = 0,
	attackturns   = {$conf['start-attackturns']},
	up            = 0,
	calevel       = 0,
	ralevel       = 0,
	sasoldiers    = {$conf['start-sasoldiers']},
	samercs       = 0,
	dasoldiers    = {$conf['start-dasoldiers']},
	damercs       = 0,
	uu            = {$conf['start-uu']},
	spies         = 0,
	commandergold = 0,
	gameSkill     = gameSkill + 3000,
	specialforces = 0,
	SA            = 0,
	DA            = 0,
	CA            = 0,
	RA            = 0,
	hhlevel       = 0,
	officerup     = 0,
	changenick    = 0,
	clicks        = 0,
	clickall      = 0,
	bankimg       = 0	
";
mysql_query($sql) or die(mysql_error());

?>
