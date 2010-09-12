<?
/***

    World War II MMORPG
    Copyright (C) 2009-2010 Richard Eames

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

$incron = true;
ini_set('include_path', '.:/home/ww2game/public_html/scripts/:/home/ww2game/public_html/');

include('vsys.php');
require_once('Message.php');
$errcount = 0;


//Updates the Ranks.active
$b=mysql_query('UPDATE User SET rank=0,sarank=0,darank=0,carank=0,rarank=0 WHERE active != 1') or die(mysql_error());



mysql_query('UPDATE User u1,User u2 SET u2.commandergold=0, u2.commander=0 WHERE u2.commander=u1.id AND u1.active!=1') or die(mysql_error());


$avgsa   = 0;
$avgda   = 0; 
$avgca   = 0; 
$avgra   = 0; 
$avgtbg  = 0; 
$avgarmy = 0; 
$avgup   = 0; 

$catchup = 1;

$d = floor((time() - $conf['last-turn']) / 900) - 1;

if ($d > 2){
    //$catchup = $d;
}



mysql_query("DELETE FROM Weapon WHERE weaponStrength <=0 or weaponCount <=0") or die(mysql_error());


if($allow_bonuses){
	mysql_query("UPDATE User u,Alliance a SET u.uu=u.uu+a.up WHERE u.alliance=a.id AND u.alliance>0 AND u.aaccepted>0") or die(mysql_error()); 
}

$users = User::getActiveUsers();
$c = count($users);

$alliances = array();

foreach ($users as $user) {
	// Update the user, and only the user.
	$income = $user->getIncome() * $catchup;
	
	// TODO: alliance tax;	
	
	// Get his officers
	$q = mysql_query("SELECT count(*) as offCount, sum(sasoldiers+dasoldiers+uu) unitCount from User WHERE commander = $user->id AND active=1 AND accepted=1") or die(mysql_error());
	$ret = mysql_fetch_object($q);


	$user->clickall = 0;
	$user->bankimg = 1;
	if ($user->gclick == 0) {
		$user->gclick = 1;
	}

	$user->numofficers = $ret->offCount;
	$user->commandergold = floor($ret->unitCount * $conf['gps'][0] * 0.01);
	#$user->uu += $user->officerup + ($user->up * $catchup);
	$user->uu += ($user->up * $catchup);
	$income += $user->commandergold;
	$user->attackturns += 2 * $catchup;
	if ($user->attackturns > $conf['attackturn-cap']) {
		$user->attackturns = $conf['attackturn-cap'];
	}
	if ($catchup > 4) {
		$user->bank += $income;
	}
	else {
		$user->gold += $income;
	}
	
	if ($user->commander) {
		// get his up
		// XXX todo, make sure he's active.
		$up = floor($user->getCommander()->up * 0.03);
		$user->officerup = $up;
		$user->uu       += $up;
	}

	$avgtbg += $income / $c;  //add to counter
	$avgarmy += $user->getTFF() / $c; //Army size
	$avgup += $user->up / $c;  //UP
	
	$user->unreadMsg = Message::getNewCount($user->id);
	$user->msgCount  = Message::getCount($user->id);
	
	$user->cacheStats();
		
}


$avgsql="SELECT floor(sum(sa)/count(*)) as avgsa, floor(sum(da)/count(*)) as avgda, floor(sum(ca)/count(*)) as avgca, floor(sum(ra)/count(*)) as avgra FROM User where active=1";
				
$avq=mysql_query($avgsql) or die(mysql_error());
$avr=mysql_fetch_array($avq,MYSQL_ASSOC);
$avgsa=0;
$avgda=0; 
$avgca=0; 
$avgra =0; 


$avghit=0;
$t=time()-(60*60*24);
$q=mysql_query("SELECT (SUM(goldStolen)/count(*)) as avghit FROM BattleLog WHERE goldStolen>0 AND time>$t") or die(mysql_error());
$aha=mysql_fetch_object($q);
$avghit=$aha->avghit;

$avgarmy=floor($avgarmy);
$avgsa=$avr['avgsa'];
$avgda=$avr['avgda'];
$avgca=$avr['avgca'];
$avgra=$avr['avgra'];
$avgtbg=floor($avgtbg);
$avgup=floor($avgup);
$UpdateSQL="UPDATE Mercenaries ";
$UpdateSQL.=" SET attackSpecCount=attackSpecCount+'{$conf['mercenaries_per_turn']}', defSpecCount =defSpecCount +'{$conf['mercenaries_per_turn']}',  ";
$UpdateSQL.=" lastturntime='".time()."', ";
$UpdateSQL.=" avgarmy='$avgarmy',avghit='$avghit',avgtbg='$avgtbg',avgup='$avgup',avgsa='$avgsa',avgda='$avgda',avgca='$avgca', ";
$UpdateSQL.=" avgra='$avgra';";
mysql_query($UpdateSQL)or die(mysql_error());


$q=mysql_query("SELECT id,area FROM User WHERE active=1 ORDER BY area,SA DESC")or die(mysql_error());
$i=1;
$area = 0;

while($row=mysql_fetch_array($q, MYSQL_ASSOC)) {
	if ($area != $row['area']) {
		$i = 1;
	}

	$update = mysql_query("UPDATE User SET sarank=$i WHERE id=".$row['id'])or die(mysql_error());
	$i++;
	$area = $row['area'];
}

$q=mysql_query("SELECT id,area FROM User WHERE active=1 ORDER BY area,DA DESC")or die(mysql_error());
$i=1;
$area = 0;
while($row=mysql_fetch_array($q, MYSQL_ASSOC)){
	if ($area != $row['area']) {
		$i = 1;
	}
	$update=mysql_query("UPDATE User SET darank=$i WHERE id=".$row['id'])or die(mysql_error());
	$i++;
	$area = $row['area'];
}

$q=mysql_query("SELECT id,area FROM User WHERE active=1 ORDER BY area,CA DESC")or die(mysql_error());
$i=1;
$area = 0;
while($row=mysql_fetch_array($q,MYSQL_ASSOC)){
	if ($area != $row['area']) {
		$i = 1;
	}
	$update=mysql_query("UPDATE User SET carank=$i WHERE id=".$row['id'])or die(mysql_error());
	$i++;
	$area = $row['area'];
	
}

$q=mysql_query("SELECT id,active,area FROM User WHERE active=1 ORDER BY area,RA DESC")or die(mysql_error());
$i=1;
$area = 0;
while($row=mysql_fetch_array($q,MYSQL_ASSOC)){
	if ($area != $row['area']) {
		$i = 1;
	}
	$update=mysql_query("UPDATE User SET rarank=$i,active=".$row[active]." WHERE id=".$row['id'])or die(mysql_error());
	$i++;
	$area = $row['area'];
	
}

$q=mysql_query("SELECT ((sarank+darank+carank+rarank)/4) as avg, id, area FROM User where active=1 order by area, avg asc")or die(mysql_error());

$i = 1;
$area = 0;
while($row=mysql_fetch_array($q,MYSQL_ASSOC)){
	if ($area != $row['area']) {
		$i = 1;
	}
	
	$update=mysql_query("UPDATE User SET rank=$i WHERE id=".$row['id'])or die(mysql_error());
	$i++;
	$area = $row['area'];
	
}


//====== Alliance stuff ====

mysql_query("
				UPDATE Alliance a,User u 
				SET 
					a.leaderid3=0 
				WHERE a.leaderid3=u.id and u.active!=1
			") or die(mysql_error());

mysql_query("
				UPDATE Alliance a,User u 
				SET 
					a.leaderid2=a.leaderid3,
					a.leaderid3=0 
				WHERE a.leaderid2=u.id and u.active!=1
			") or die(mysql_error());

mysql_query("
				UPDATE Alliance a,User u 
				SET 
					a.leaderid1=a.leaderid2,
					a.leaderid2=a.leaderid3,
					a.leaderid3=0 
				WHERE a.leaderid1=u.id and u.active!=1
			") or die(mysql_error());
			
mysql_query("
UPDATE
	Alliance
SET
	status = 2
WHERE
	leaderid1 = 0 AND
	leaderid2 = 0 AND
	leaderid3 = 0
") or die(mysql_error());

$t = time();

$fp = fopen(INCDIR . '/scripts/gen-stats-l.php','w+') or die('Could not open file');
$str = "<?\$conf['last-turn'        ] = $time;?>";

fwrite($fp,$str);
fclose($fp);

?>
