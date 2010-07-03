<?
/*
All code under "World War II Game" is copyright of Naddiseo and SilentWarrior


*/
//ini_set("precision","20");
error_reporting(E_ALL & ~E_NOTICE);
if (!$incron) {
	session_start();
	session_regenerate_id(false);
	$alert = '';
	$red = "http://www.google.com/search?hl=en&q=STOP+REFRESHING+the+server+is+down";
	$loadavg = file_get_contents('/proc/loadavg');
	$loads = explode(' ', $loadavg);
	if ($loads[0] > 12 AND !$_SESSION[admin]) {
		$red = "http://www.google.com/search?hl=en&q=STOP+REFRESHING+the+server+is+down&btnG=Google+Search";
		header("Location: $red");
		exit();
	}
}
include 'conf.php';
include 'weaps.php';
include 'db.php';
include 'anticheat.php';
include 'class_email.php';
$cgi = array();

foreach ($_GET as $k => $v) {
	if (is_numeric($v)) {
		//if($v<1.0){$v=1;}
		$cgi[$k] = round(abs($v), 0);
	} else {
		$cgi[$k] = str_replace('script', 'scr[i]pt', $v);
	}
}
foreach ($_POST as $k => $v) {
	if (is_numeric($v)) {
		//if($v<1.0){$v=1;}
		$cgi[$k] = round(abs($v), 0);
	} else {
		$cgi[$k] = str_replace('script', 'scr[i]pt', $v);
	}
}
if ($game_offline == 'true' AND !$_SESSION['admin'] AND !$incron) {
	header('Location: offline.php');
	exit;
}
if ($_SESSION['isLogined']) {
	$user = getUserDetails($_SESSION['isLogined']);
}
if ($cgi[$_SESSION['uname']] != null AND !$_SESSION['banpass']) {
	$isLogined1 = isLogined($cgi[$_SESSION['uname']], $cgi[$_SESSION['psword']]);
	if ($isLogined1) {
		$usT = getUserDetails($isLogined1, " active,id ");
		logIP($isLogined1);
		//==========
		//== actives
		// 0  Not activated
		// 1 Normal User
		// 2 Vaction mode
		// 3 Flagged/suspected cheater
		// 4 banned
		// 5 Deleted - Just so the user doesn't think he's on vacation
		//==============
		if ($usT->active == 1) {
			$_SESSION["isLogined"] = $isLogined1;
			$sN = $HTTP_SERVER_VARS['SCRIPT_NAME'];
			//echo "==".$sN."==";
			if (($sN == $conf["path"] . '/index.php') || ($sN == $conf["path"] . '/battlefield.php') || ($sN == $conf["path"] . '/help.php') || ($sN == $conf["path"] . '/aboutus.php') || ($sN == $conf["path"] . '/register.php') || ($sN == $conf["path"] . '/forgotpass.php') || ($sN == $conf["path"] . '/spam.php') || ($sN == $conf["path"] . '/privacy.php') || ($sN == $conf["path"] . '/advertising.php') || ($sN == $conf["path"] . '/tos.php') || ($sN == $conf["path"] . '/activate.php') || ($sN == $conf["path"] . '/forgotpass.php')) {
				header("Location: base.php");
				exit;
			}
		} elseif ($usT->active == 2) {
			$MessageStr = "You are set to vacation mode.<br>  Please try to log in again to reactivate your account.";
			include "message.php";
			updateUser($usT->id, "active=1");
			exit;
		} elseif ($usT->active == 3) {
			$MessageStr = "You have been red flagged for cheating.<br> This has not been proven yet, but will be looked into.<br> Try to contact admins of the site to get to know why this has taken place.";
			include "message.php";
			exit;
		} elseif ($usT->active == 4) {
			$MessageStr = "You have been Banned.<br> Try to contact admins of the site to get to know why this has taken place.";
			include "message.php";
			exit;
		} elseif ($usT->active == 5) {
			$MessageStr = "Account Not Found. It is possible that you deleted your account.";
			include "message.php";
			exit;
		} else {
			$_SESSION["activationID"] = $isLogined1;
			header("Location: activate.php");
		}
	} else {
		$MessageStr = "The login and password you have entered do not match with the ones we have on our database.<br> Try to retype them again.";
		include "message.php";
		exit;
	}
}
if (!$_SESSION['isLogined']) {
} else {
	$u = getUserDetails($_SESSION['isLogined'], 'active');
	if ($u->active != 1 AND !isset($_SESSION['admin'])) {
		$_SESSION['isLogined'] = 0;
		header("Location: index.php");
		exit;
	}
	setLastSeen($_SESSION['isLogined'], time());
	$sN = $HTTP_SERVER_VARS['SCRIPT_NAME'];
	if (($sN == $conf["path"] . '/index.php') || ($sN == $conf["path"] . '/register.php') || ($sN == $conf["path"] . '/activate.php') || ($sN == $conf["path"] . '/recruit.php') || ($sN == $conf["path"] . '/forgotpass.php')) {
		header("Location: base.php");
		exit;
	}
}
//-------------------------------------------- FUNCTIONS --------------------------------------------------------
function ToPositive($number) {
	if ($number < 1.0 or !is_numeric($number)) {
		$number = 1;
	}
	return $number;
}
function valchar($uname) { //Makes sure there is no SQL injection (One word length)
	$exp = "/[a-zA-Z0-9]+/i";
	if (preg_match($exp, $uname)) {
		return true;
	} else {
		return false;
	}
}
function isLogined($uname, $psword) {
	$psword = md5($psword);
	if (!valchar($uname)) {
		return;
	}
	$str = "select * from `UserDetails` where  userName='$uname' and password='$psword'";
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
	if (!@mysql_num_rows($q)) {
		return 0;
	} else {
		$st = "";
		$st = mysql_fetch_object($q);
		return $st->ID;
	}
}
function getUserByUniqId($uniqueLink, $fields = " ID ") {
	if (!valchar($uniqueLink)) {
		return;
	}
	$str = "select $fields from `UserDetails` where  uniqueLink ='$uniqueLink' ";
	//echo $str;
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
	if (!@mysql_num_rows($q)) {
		return 0;
	} else {
		$st = "";
		$st = mysql_fetch_object($q);
		$st->uu = floor($st->uu);
		return $st;
	}
}
function getUserDetailsByName($name, $fields = " ID ") {
	if (!valchar($name)) {
		return;
	}
	$str = "select $fields from `UserDetails` where  userName='$name' ";
	//echo $str;
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
	if (!@mysql_num_rows($q)) {
		return 0;
	} else {
		$st = "";
		$st = mysql_fetch_object($q);
		$st->uu = floor($st->uu);
		return $st;
	}
}
function getUserDetailsByEmail($email, $fields = " ID ") {
	$str = "select $fields from `UserDetails` where  email='$email' ";
	//echo $str;
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
	if (!@mysql_num_rows($q)) {
		return 0;
	} else {
		$st = "";
		$st = mysql_fetch_object($q);
		$st->uu = floor($st->uu);
		return $st;
	}
}
function getUserDetails($id, $fields = "*") {
	if (!valchar($id)) {
		return;
	}
	if (!strpos($fields, 'alliance') AND $fields != '*') {
		$fields.= ',alliance';
	}
	$str = "select $fields from `UserDetails` where  UserDetails.ID='$id'  ";
	//echo $str;
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
	if (!@mysql_num_rows($q)) {
		return 0;
	} else {
		/*if($st->alliance){
			$q=mysql_query("SELECT * FROM alliances WHERE id={$st->alliance}") or die(mysql_error());
			$a=mysql_fetch_object($q);
			$st->SABONUS=$a->SA; 
			$st->DABONUS=$a->DA; 
			$st->CABONUS=$a->CA; 
			$st->RABONUS=$a->RA; 
		}
		if(floatval($st->RABONUS)<=0){ $st->SABONUS=0; }
		if(floatval($st->RABONUS)<=0){ $st->DABONUS=0; }
		if(floatval($st->RABONUS)<=0){ $st->CABONUS=0; }
		if(floatval($st->RABONUS)<=0){ $st->RABONUS=0; }*/
		$st = "";
		$st = mysql_fetch_object($q);
		$st->uu = floor($st->uu);
		return $st;
	}
}
function getUserIncome($user) {
	global $conf;
	$income = 0;
	$income+= $user->sasoldiers;
	$income+= $user->dasoldiers;
	$income+= $user->uu;
	if ($user->race == 4) {
		$income = (16 * $income);
	} else {
		$income = ($conf["gold_from_soldier"] * $income);
	}
	if ($conf["race"][$user->race]["income"]) {
		$income+= round(($income * $conf["race"][$user->race]["income"]) / 100);
	}
	return $income;
}
function getWeaponAllocation($user, $weps, $trainedCount, $mercCount, $untrainedCount) {
	if (is_array($weps)) {
		$weps = count($weps);
	}
	if ($trainedCount == 0) {
		$mercCount = 0;
	}
	if ($mercCount > ($trainedCount * 0.3)) {
		$mercCount = floor($trainedCount * 0.3);
	}
	if ($weps > $trainedCount) {
		$trainedW = $trainedCount;
		$trainedUnW = 0;
		$weps-= $trainedCount;
	} else {
		$trainedW = $weps;
		$weps = 0;
		$trainedUnW = $trainedCount - $trainedW;
	}
	if ($weps > $mercCount) {
		$mercW = $mercCount;
		$weps-= $mercW;
		$mercUnW = 0;
	} else {
		$mercW = $weps;
		$weps = 0;
		$mercUnW = $mercCount - $mercW;
	}
	if ($weps > $untrainedCount) {
		$untrainedW = $untrainedCount;
		$untrainedUnW = 0;
	} else {
		$untrainedW = $weps;
		$weps = 0;
		$untrainedUnW = $untrainedCount - $untrainedW;
	}
	$wepAlloc["trainedW"] = $trainedW;
	$wepAlloc["mercW"] = $mercW;
	$wepAlloc["mercUnW"] = $mercUnW;
	$wepAlloc["untrainedUnW"] = $untrainedUnW;
	$wepAlloc["untrainedW"] = $untrainedW;
	$wepAlloc["trainedUnW"] = $trainedUnW;
	//$wepAlloc["weps"]=count($weaponA);
	//print_r($wepAlloc);
	return $wepAlloc;
}
function getWeaponArray($weaponA1) {
	$k = 0;
	for ($i = 0;$i < count($weaponA1);$i++) {
		for ($j = 0;$j < $weaponA1[$i]->weaponCount;$j++) {
			$weaponA[$k] = $weaponA1[$i];
			$k++;
		}
	}
	return $weaponA;
}
function getStrikeAction($user) {
	global $conf, $allow_bonuses;
	$q = mysql_query("SELECT a.SA,u.aaccepted FROM alliances a,UserDetails u WHERE u.alliance=a.ID AND u.ID={$user->ID}") or die(mysql_error());
	$a = mysql_fetch_object($q);
	mysql_free_result($q);
	if ($allow_bonuses and $a->aaccepted == 1) {
		$user->SABONUS = $a->SA;
	} else {
		$user->SABONUS = 0;
	}
	$q = mysql_query("SELECT (sum(weaponstrength*weaponCount)/(sum(weaponcount))),sum(weaponcount) FROM Weapon WHERE userid='{$user->ID}' AND isAttack='1'") or die(mysql_error());
	$r = mysql_fetch_array($q);
	mysql_free_result($q);
	$wepAlloc = getWeaponAllocation($user, $r[1], $user->sasoldiers, $user->samercs, $user->uu);
	return (float)round((($r[0] * $wepAlloc['mercW'] * 50) + ($r[0] * $wepAlloc['trainedW'] * 20) + ($r[0] * $wepAlloc["untrainedW"] * 3) + ($wepAlloc["mercUnW"] * $user->hhlevel) + ($wepAlloc["untrainedUnW"] * $user->hhlevel) + ($wepAlloc["trainedUnW"] * 2 * $user->hhlevel)) * pow(1.25, $user->salevel + 1) * (1 + ($conf["sabonus{$user->race}"])) + $user->sasoldiers + $user->samercs + $user->uu) * (1 + (float)$user->SABONUS);
}
function getDefenseAction($user) {
	global $conf, $allow_bonuses;
	$q = mysql_query("SELECT a.DA,u.aaccepted FROM alliances a,UserDetails u WHERE u.alliance=a.ID AND u.ID={$user->ID}") or die(mysql_error());
	$a = mysql_fetch_object($q);
	mysql_free_result($q);
	if ($allow_bonuses and $a->aaccepted == 1) {
		$user->DABONUS = $a->DA;
	} else {
		$user->DABONUS = 0;
	}
	$q = mysql_query("SELECT (sum(weaponstrength*weaponCount)/(sum(weaponcount))),sum(weaponcount) FROM Weapon WHERE userid='{$user->ID}'  AND isAttack='0'") or die(mysql_error());
	$r = mysql_fetch_array($q);
	mysql_free_result($q);
	$wepAlloc = getWeaponAllocation($user, $r[1], $user->dasoldiers, $user->damercs, $user->uu);
	$part1 = (float)round((($r[0] * $wepAlloc['mercW'] * 50) + ($r[0] * $wepAlloc['trainedW'] * 20) + ($r[0] * $wepAlloc["untrainedW"] * 3) + ($wepAlloc["mercUnW"] * $user->hhlevel) + ($wepAlloc["untrainedUnW"] * $user->hhlevel) + ($wepAlloc["trainedUnW"] * 2 * $user->hhlevel)) * pow(1.25, $user->dalevel + 1) * (1 + ($conf["dabonus{$user->race}"])) * (1 + (float)$user->DABONUS));
	$part2 = $user->dasoldiers + $user->damercs + $user->uu;
	//die($part2.' '.$part1.' Just testing something, sorry');
	return $part1 + $part2;
}
function getCovertAction($user) {
	global $conf, $allow_bonuses;
	$num = 0;
	if (!$user->spies) {
		return 0;
	}
	$q = mysql_query("SELECT a.CA,u.aaccepted FROM alliances a,UserDetails u WHERE u.alliance=a.ID AND u.ID={$user->ID}") or die(mysql_error());
	$a = mysql_fetch_object($q);
	mysql_free_result($q);
	if ($allow_bonuses and $a->aaccepted == 1) {
		$user->CABONUS = $a->CA;
	} else {
		$user->CABONUS = 0;
	}
	$num = ((($user->spies / 100 * pow(2, $user->calevel))) * (1 + ($conf["cabonus{$user->race}"])));
	return (float)round($num * (1 + $user->CABONUS)) + $user->spies;
}
function getRetaliationAction($user) {
	global $conf, $allow_bonuses;
	if (!$user->specialforces) {
		return 0;
	}
	$q = mysql_query("SELECT a.RA,u.aaccepted FROM alliances a,UserDetails u WHERE u.alliance=a.ID AND u.ID={$user->ID}") or die(mysql_error());
	$a = mysql_fetch_object($q);
	mysql_free_result($q);
	if ($allow_bonuses and $a->aaccepted == 1) {
		$user->RABONUS = $a->RA;
	} else {
		$user->RABONUS = 0;
	}
	$ra = pow(1.8, $user->sflevel) * ($user->specialforces / 5);
	return (float)round($ra * (1 + ($conf["rabonus{$user->race}"])) * (1 + $user->RABONUS)) + $user->specialforces;
}
function updateUserStats($user) {
	$user = getUserDetails($user->ID, "ID,race,alliance,specialforces,sflevel,spies,calevel,dalevel,salevel,sasoldiers,samercs,dasoldiers,damercs,uu,hhlevel");
	$str = "UPDATE UserDetails SET SA='" . getStrikeAction($user) . "',
								DA='" . getDefenseAction($user) . "',
								CA='" . getCovertAction($user) . "',
								RA='" . getRetaliationAction($user) . "' WHERE ID='{$user->ID}'";
	mysql_query($str) or die(mysql_error());
}
function setWeapon($id, $fields) {
	if (!valchar($id)) {
		return;
	}
	$str = "update `Weapon` set $fields WHERE ID='$id' ";
	//echo "$str<br>";
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
}
function delWeapon($id) {
	$str = "DELETE FROM  `Weapon` WHERE ID='$id'";
	//echo $str;
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
}
function getUserAllWeapon($user) {
	$str = "SELECT * FROM `Weapon` Where  userID='{$user->ID}' ORDER BY `weaponStrength` DESC ";
	//print $str;
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
	if (!@mysql_num_rows($q)) {
		return;
	} else {
		$st = "";
		$i = 0;
		while ($row = mysql_fetch_object($q)) {
			$st[$i] = $row;
			$i++;
		}
		return $st;
	}
}
function getUserWeapon($user, $order = "weaponStrength") {
	$str = "SELECT * FROM `Weapon` Where isAttack='1' and  userID='{$user->ID}' ORDER BY `$order` DESC ";
	//print $str;
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
	if (!@mysql_num_rows($q)) {
		return;
	} else {
		$st = "";
		$i = 0;
		while ($row = mysql_fetch_object($q)) {
			$st[$i] = $row;
			$i++;
		}
		return $st;
	}
}
function getDefUserWeapon($user, $order = "weaponStrength") {
	$str = "SELECT * FROM `Weapon` Where isAttack='0' and  userID='{$user->ID}' ORDER BY `$order` DESC";
	//print $str;
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
	if (!@mysql_num_rows($q)) {
		return;
	} else {
		$st = "";
		$i = 0;
		while ($row = mysql_fetch_object($q)) {
			$st[$i] = $row;
			$i++;
		}
		return $st;
	}
}
function getTotalFightingForce($user) {
	$count = 0;
	$count+= $user->sasoldiers;
	$count+= $user->samercs;
	$count+= $user->dasoldiers;
	$count+= $user->damercs;
	$count+= $user->uu;
	$count+= $user->spies;
	$count+= $user->specialforces;
	$count+= $user->scientists;
	return $count;
}
function getActiveUsers($fields = "*") {
	$str = "SELECT $fields FROM `UserDetails` WHERE active='1'";
	//print $str;
	$q = mysql_query($str);
	if (!$q) {
		mail($conf["mail"], "query $str no getactiveusers", mysql_error());
		echo ('Query failed: ' . mysql_error());
		return;
	}
	if (!@mysql_num_rows($q)) {
		return;
	} else {
		$st = "";
		$i = 0;
		while ($row = mysql_fetch_object($q)) {
			$row->uu = floor($row->uu);
			$st[$i] = $row;
			$i++;
		}
		return $st;
	}
}
function getActiveUsers2($fields = "*") {
	$str = "SELECT $fields FROM UserDetails,Ranks WHERE UserDetails.active='1' and Ranks.userID=UserDetails.ID";
	//print $str;
	$q = mysql_query($str);
	if (!$q) {
		mail($conf["mail"], "query $str no getactiveusers", mysql_error());
		echo ('Query failed: ' . mysql_error());
		return;
	}
	if (!@mysql_num_rows($q)) {
		return;
	} else {
		$st = "";
		$i = 0;
		while ($row = mysql_fetch_object($q)) {
			$row->uu = floor($row->uu);
			$st[$i] = $row;
			$i++;
		}
		return $st;
	}
}
function getRanksList($page) {
	global $conf;
	$start = ($page - 1) * $conf['users_per_page'];
	$str = "SELECT  userID,rank FROM `Ranks` WHERE rank!=0  ORDER BY `rank` ASC LIMIT $start,{$conf['users_per_page']}  ";
	//print $str;
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
	if (!@mysql_num_rows($q)) {
		return;
	} else {
		$st = "";
		$i = 0;
		while ($row = mysql_fetch_object($q)) {
			$st[$i] = $row;
			$i++;
		}
		return $st;
	}
}
function getRanksUsersList($page, $fields = " ID, userName,sasoldiers ,samercs ,dasoldiers ,dasoldiers ,uu, spies,specialforces,scientists, race ,gold,salevel,alliance,aaccepted,CA ") {
	//global $conf;
	$users = getRanksList($page);
	for ($i = 0;$i < count($users);$i++) {
		$usersA[$i] = getUserDetails($users[$i]->userID, $fields);
		$usersA[$i]->rank = $users[$i]->rank;
	}
	return $usersA;
}
function getRanksUsersList2($page) {
	global $conf;
	$start = ($page - 1) * $conf['users_per_page'];
	$SQL = " SELECT Ranks.rank,UserDetails.ID, UserDetails.userName ,UserDetails.sasoldiers ,UserDetails.samercs ,";
	$SQL.= " UserDetails.dasoldiers , UserDetails.aaccepted, UserDetails.dasoldiers ,UserDetails.uu, UserDetails.alliance, UserDetails.spies,UserDetails.specialforces, UserDetails.race ,UserDetails.gold,UserDetails.calevel,";
	$SQL.= "UserDetails.CA FROM UserDetails,Ranks WHERE Ranks.rank!=0 AND UserDetails.Active=1 AND UserDetails.ID=Ranks.UserID ORDER BY Ranks.rank ASC LIMIT $start,{$conf['users_per_page']}  ";
	$q = mysql_query($SQL);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
	if (!@mysql_num_rows($q)) {
		return;
	} else {
		$st = "";
		$i = 0;
		while ($row = mysql_fetch_object($q)) {
			$st[$i] = $row;
			$i++;
		}
		return $st;
	}
}
function searchRanksUsersListCount($str) {
	$str = "SELECT COUNT(*) FROM `UserDetails`,`Ranks` WHERE UserDetails.ID = Ranks.userID AND rank<>0  AND UserDetails.active='1' AND userName LIKE '$str' ";
	//echo $str;
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
	if ($q) {
		$st = mysql_fetch_array($q);
		return $st[0];
	} else {
		return 0;
	}
}
function searchRanksUsersList($page, $str, $fields = " UserDetails.ID, userName ,sasoldiers ,samercs ,dasoldiers ,damercs ,uu, calevel,alliance,spies,specialforces, race ,gold, rank,CA,aaccepted ") {
	global $conf;
	$start = ($page - 1) * $conf['users_per_page'];
	$str = "SELECT  $fields FROM `UserDetails`,`Ranks` WHERE UserDetails.ID = Ranks.userID AND rank<>0 AND UserDetails.active='1' AND userName LIKE '$str'  ORDER BY `rank` ASC LIMIT $start,{$conf['users_per_page']}  ";
	//print $str;
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
	if (!@mysql_num_rows($q)) {
		return;
	} else {
		$st = "";
		$i = 0;
		while ($row = mysql_fetch_object($q)) {
			$row->uu = floor($row->uu);
			$st[$i] = $row;
			$i++;
		}
		return $st;
	}
}
function getUserRanks($id) {
	$str = "select * from `Ranks` where  userID='$id' ";
	//echo $str;
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
	if (!@mysql_num_rows($q)) {
		$st->rank = 'unranked';
		$st->sarank = 'unranked';
		$st->darank = 'unranked';
		$st->carank = 'unranked';
		$st->rarank = 'unranked';
		return $st;
	} else {
		$st = "";
		$st = mysql_fetch_object($q);
		if (!$st->rank) {
			$st->rank = 'unranked';
		}
		if (!$st->sarank) {
			$st->sarank = 'unranked';
		}
		if (!$st->darank) {
			$st->darank = 'unranked';
		}
		if (!$st->carank) {
			$st->carank = 'unranked';
		}
		if (!$st->rarank) {
			$st->rarank = 'unranked';
		}
		return $st;
	}
}
function createUser($userName, $race, $email, $password, $commander, $active = 0, $uniqueLink = "", $dalevel = 0, $salevel = 0, $gold = 50000, $lastturntime = 0, $attackturns = 30, $up = 0, $calevel = 0, $sasoldiers = 0, $samercs = 0, $dasoldiers = 0, $damercs = 0, $uu = 200, $spies = 0) {
	if (!$lastturntime) {
		$lastturntime = time();
	}
	$uniqueLink = genUniqueLink();
	if (!valchar($userName)) {
		return;
	}
	$str = "INSERT INTO `UserDetails` (userName,race,email,password,commander,active,uniqueLink,dalevel,salevel,
	gold,lastturntime,attackturns,up,calevel,
	sasoldiers,samercs,dasoldiers,damercs,uu,spies) VALUES ('$userName','$race','$email','" . md5($password) . "','$commander','$active','$uniqueLink','$dalevel','$salevel',
	'$gold','$lastturntime','$attackturns','$up','$calevel',
	'$sasoldiers','$samercs','$dasoldiers','$damercs','$uu','$spies')";
	//echo "<center>Your temporary password is: $password</center><br>";
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return $password;
	}
	$us = getUserDetailsByName($userName);
	$userID = $us->ID;
	$str = "INSERT INTO `Ranks` (userID) VALUES ('$userID') ";
	$q = mysql_query($str);
	return $password;
}
function getActiveUsersCount() {
	$str = "SELECT COUNT(*) FROM `UserDetails` where active='1'";
	//echo $str;
	$q = mysql_query($str);
	if ($q) {
		$st = mysql_fetch_array($q);
		return $st[0];
	} else {
		return 0;
	}
	//echo "--{$st[0]}---";
	
}
function getOnlineUsersCount() {
	global $conf;
	$time = time() - $conf["minutes_per_turn"] * 60;
	$str = "SELECT COUNT(*) FROM `UserDetails` where lastturntime>'$time' and active='1'";
	//echo time()."<br>";
	//echo $str;
	$q = mysql_query($str);
	if ($q) {
		$st = mysql_fetch_array($q);
		return $st[0];
	} else {
		return 0;
	}
	//echo "--{$st[0]}---";
	
}
function getOnlineUsers() {
	global $conf;
	$time = time() - $conf["minutes_per_turn"] * 60;
	$str = "SELECT * FROM UserDetails,Ranks where UserDetails.lastturntime>'$time' and UserDetails.active='1' AND UserDetails.ID=Ranks.UserID ORDER BY Ranks.rank ASC";
	//echo time()."<br>";
	//echo $str;
	$q = mysql_query($str);
	if ($q) {
		$st = "";
		$i = 0;
		while ($row = mysql_fetch_object($q)) {
			$st[$i] = $row;
			$i++;
		}
		return $st;
	} else {
		return 0;
	}
	//echo "--{$st[0]}---";
	
}
function getOldUsers() {
	global $conf;
	$time = time() - $conf["days_of_inactivity_before_delete_this_user"] * 24 * 60 * 60;
	$str = "SELECT ID, active FROM `UserDetails` where lastturntime<'$time'";
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
	if (!@mysql_num_rows($q)) {
		return;
	} else {
		$st = "";
		$i = 0;
		while ($row = mysql_fetch_object($q)) {
			$st[$i] = $row;
			$i++;
		}
		return $st;
	}
}
function deleteoldusers() {
	global $conf;
	$time = time() - 5 * 24 * 60 * 60; //$conf["days_of_inactivity_before_delete_this_user"]*24*60*60;
	$users = getOldUsers();
	$i = count($users);
	for ($x = 0;$x <= $i;$x++) {
		deleteUser($users[$i]->ID);
	}
	return;
}
function updateUser($id, $str) {
	$str = "update `UserDetails` set $str WHERE ID='$id' ";
	//echo "$str<br>";
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
	return $q;
}
function updateMercenary($str) {
	$str = "update `Mercenaries` set $str  ";
	//echo "$str<br>";
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
}
function setUserRank($id, $rank, $sarank, $darank, $carank, $rarank) {
	$str = "update `Ranks` SET rank='$rank' , 
	sarank='$sarank', 
	darank='$darank', 
	carank='$carank', 
	rarank='$rarank'  WHERE userID='$id' ";
	$q = mysql_query($str);
	if (!$q) {
		return;
	}
}
function setLastSeen($id, $date) {
	if (!$_SESSION['admin']) {
		updateUser($id, " lastturntime = '$date' ");
	}
}
function setLastTurnTime($date) {
	updateMercenary(" lastturntime = '$date' ");
}
function deleteUserWeapon($id, $weaponID = "") {
	if ($weaponID) {
		$str2 = " AND weaponID='$weaponID' ";
	}
	$str = "DELETE FROM  `Weapon` WHERE userID='$id' $str2";
	//echo $str;
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
}
function clearRanks($id) {
	$str = "update `Ranks` set rank ='0', sarank  ='0',darank   ='0',carank   ='0',rarank='0' WHERE userID='$id' ";
	//echo $str;
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
}
function deleteUser($id) {
	//$str = "DELETE FROM  `UserDetails` WHERE ID='$id'";
	$str = "update `UserDetails` set active=2,commander=0 WHERE ID='$id'";
	//echo $str;
	$q = mysql_query($str);
	$str = "update `UserDetails` set commander=0 WHERE commander='$id' ";
	$q = mysql_query($str);
}
function addTurns($id, $addTurns, $lastturntime) {
	//$str = "INSERT INTO `UserDetails` (attackturns ,lastturntime ) VALUES ('/banners/$bname')";
	$str = "update `UserDetails` set attackturns='$addTurns', lastturntime='$lastturntime' where ID='$id'";
	//echo $str;
	mysql_query($str);
}
function getNextTurn($user) {
	global $conf;
	$info = getCommonInfo();
	$lastturntime = $info->lastturntime;
	$thisTime = time();
	$dif = $thisTime - $lastturntime;
	$nextTurn = $conf["minutes_per_turn"] * 60 - ($dif);
	$min = round($nextTurn / 60);
	$sec = ($nextTurn) - ($min * 60);
	if ($sec < 0) {
		$min = $min - 1;
		$sec = 60 + $sec;
	}
	if ($sec < 10) {
		$sec = "0$sec";
	}
	if ($min < 10) {
		$min = "0$min";
	}
	$timeTurn = "$min:$sec";
	return $timeTurn;
}
function getCommonInfo() {
	$str = "select * from `Mercenaries`";
	//echo $str;
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
	if (!@mysql_num_rows($q)) {
		return 0;
	} else {
		$st = "";
		$st = mysql_fetch_object($q);
		return $st;
	}
}
//----------------------------Messages-----------------------------------------------
include ('message.functions.php');
//----------------------------END Messages-----------------------------------------------
//----------------------------Officers-----------------------------------------------
function getOfficers($id, $page, $fields = "userID,userName, rank, sasoldiers,samercs ,dasoldiers ,damercs ,uu, spies, race,accepted,lastturntime") {
	global $conf;
	$start = ($page - 1) * $conf['users_per_page'];
	$str = "SELECT *  FROM `UserDetails`,`Ranks` WHERE Ranks.userID=UserDetails.ID  AND commander='$id' AND Ranks.active='1' AND rank<>'0' ORDER BY `rank` ASC LIMIT $start,{$conf['users_per_page']}  ";
	//print $str;
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
	if (!@mysql_num_rows($q)) {
		return;
	} else {
		$st = "";
		$i = 0;
		while ($row = mysql_fetch_object($q)) {
			$st[$i] = $row;
			$i++;
		}
		return $st;
	}
}
function getOfficersCount($id) {
	$str = "SELECT COUNT(*) FROM `UserDetails`,`Ranks` where Ranks.userID=UserDetails.ID AND commander='$id' AND Ranks.active='1' AND rank<>'0'";
	//echo $str;
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
	if ($q) {
		$st = mysql_fetch_array($q);
		return $st[0];
	} else {
		return 0;
	}
	//echo "--{$st[0]}---";
	
}
//----------------------------END Officers-----------------------------------------------
//-----------------------------Convertions--------------------------------------------
function numecho($str) {
	if ($str == "unranked") {
		echo $str;
	} elseif ($str == "None") {
		echo $str;
	} else {
		//echo $str;
		echo number_format($str, 0, '.', ',');
	}
}
function numecho2($str) {
	//echo $str;
	return number_format($str);
}
function ccomma($str) {
	$str2 = str_replace(",", "", $str);
	$str2 = str_replace(".", "", $str2);
	return $str2;
}
function vDate($time) {
	$timenow = time();
	$timenow = date("M d, Y", $timenow);
	$time1 = date("M d, Y", $time);
	if ($timenow == $time1) {
		$time1 = date("H:i", $time);
	}
	return $time1;
}
function genRandomPas() {
	$pas = rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);
	return $pas;
}
function genUniqueLink() {
	$time = time();
	$str = chr(rand(ord('a'), ord('z'))) . chr(rand(ord('a'), ord('z'))) . $time;
	return $str;
	/*
	for ($i=0; $i<strlen($time);$i++){
		//$str.=chr(ord('a'))
	}
	*/
}
function genUniqueTxt($n) {
	for ($i = 0;$i < $n;$i++) {
		if (rand(0, 1)) {
			$str.= chr(rand(ord('A'), ord('Z')));
		} else {
			$str.= chr(rand(ord('0'), ord('9')));
		}
	}
	return $str;
	/*
	for ($i=0; $i<strlen($time);$i++){
		//$str.=chr(ord('a'))
	}
	*/
}
//-----------------------------End Convertions--------------------------------------------
//------------------------------Security---------------------------------------------
function addIP($ip, $userID) {
	$time = time();
	$str = "INSERT INTO `IPs` (ip,userID,time) VALUES ('$ip','$userID','$time') ";
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
}
function isIP($ip) {
	return 0;
	$str = "SELECT * FROM `IPs` WHERE ip='$ip' ";
	//echo $str;
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
	if ($q) {
		$st = mysql_fetch_array($q);
		return $st[0];
	} else {
		return 0;
	}
}
function isIPandUser($ip, $id) {
	$str = "SELECT * FROM `IPs` WHERE ip='$ip' AND userID='$id' ";
	//echo $str;
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
	if ($q) {
		$st = mysql_fetch_array($q);
		return $st[0];
	} else {
		return 0;
	}
}
function isIPNewerThen($ip, $time) {
	//return 0;
	$time = time() - $time;
	$str = "SELECT * FROM `IPs` WHERE ip='$ip' AND time>'$time' ";
	//echo $str;
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
	if ($q) {
		$st = mysql_fetch_object($q);
		return $st;
	} else {
		return 0;
	}
}
function getIP($id) {
	$str = "select * from `IPs` where  userID='$id' ";
	//echo $str;
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
	if (!@mysql_num_rows($q)) {
		return 0;
	} else {
		$st = "";
		$st = mysql_fetch_object($q);
		return $st;
	}
}
function getUserIPs($id) {
	$str = "SELECT * FROM `IPs` Where  userID='$id' ORDER BY `time` DESC ";
	//print $str;
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
	if (!@mysql_num_rows($q)) {
		return;
	} else {
		$st = "";
		$i = 0;
		while ($row = mysql_fetch_object($q)) {
			$st[$i] = $row;
			$i++;
		}
		return $st;
	}
}
function deleteIP($id) {
	$str = "DELETE FROM  `IPs` WHERE userID='$id'";
	//echo $str;
	//$q = mysql_query($str);
	//if (!$q) {print ('Query failed: '.mysql_error());	return;	}
	
}
function deleteIPByIP($ip) {
	$str = "DELETE FROM  `IPs` WHERE ip='$ip'";
	//echo $str;
	//$q = mysql_query($str);
	//if (!$q) {print ('Query failed: '.mysql_error());	return;	}
	
}
function deleteIPByID($id) {
	$str = "DELETE FROM  `IPs` WHERE ID='$id'";
	//echo $str;
	//$q = mysql_query($str);
	//if (!$q) {print ('Query failed: '.mysql_error());	return;	}
	
}
function logIP($id) {
	global $HTTP_SERVER_VARS, $conf;
	$ip = $HTTP_SERVER_VARS['REMOTE_ADDR'];
	addIP($ip, $id);
}
//------------------------------END Security---------------------------------------------
//-----------------------------Attack---------------------------------------------------
function getAttackCount($userID) {
	$str = "SELECT COUNT(*) FROM `AttackLog` where userID='$userID' ";
	$q = mysql_query($str);
	if ($q) {
		$st = mysql_fetch_array($q);
		return $st[0];
	} else {
		return 0;
	}
}
function getDefenceCount($userID) {
	$str = "SELECT COUNT(*) FROM `AttackLog` where toUserID='$userID' ";
	$q = mysql_query($str);
	if ($q) {
		$st = mysql_fetch_array($q);
		return $st[0];
	} else {
		return 0;
	}
}
function getAllAttacks($userID) {
	$str = "SELECT * FROM `AttackLog` WHERE userID='$userID'";
	//print $str;
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
	if (!@mysql_num_rows($q)) {
		return;
	} else {
		$st = "";
		$i = 0;
		while ($row = mysql_fetch_object($q)) {
			$st[$i] = $row;
			$i++;
		}
		return $st;
	}
}
function getAllDefences($userID) {
	$str = "SELECT * FROM `AttackLog` WHERE toUserID='$userID'";
	//print $str;
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
	if (!@mysql_num_rows($q)) {
		return;
	} else {
		$st = "";
		$i = 0;
		while ($row = mysql_fetch_object($q)) {
			$st[$i] = $row;
			$i++;
		}
		return $st;
	}
}
function getAttack($id) {
	$str = "select * from `AttackLog` where  ID='$id' ";
	//echo $str;
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
	if (!@mysql_num_rows($q)) {
		return 0;
	} else {
		$st = "";
		$st = mysql_fetch_object($q);
		return $st;
	}
}
function getAttackByAttackerCount($id) {
	$str = "SELECT COUNT(*) FROM `AttackLog` where  userID='$id'";
	//echo $str;
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
	if ($q) {
		$st = mysql_fetch_array($q);
		return $st[0];
	} else {
		return 0;
	}
}
function getAttackByAttacker($id, $page) {
	global $conf;
	$start = ($page - 1) * $conf['users_per_page_on_attack_log'];
	//ORDER BY `rank` ASC LIMIT $start,{$conf['users_per_page']}
	$str = "select * from `AttackLog` where  userID='$id' ORDER BY `time` DESC LIMIT $start,{$conf['users_per_page_on_attack_log']}";
	//echo $str;
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
	if (!@mysql_num_rows($q)) {
		return;
	} else {
		$st = "";
		$i = 0;
		while ($row = mysql_fetch_object($q)) {
			$st[$i] = $row;
			$i++;
		}
		return $st;
	}
}
function getAttackByDefenderCount($id) {
	$str = "SELECT COUNT(*) FROM `AttackLog` where  toUserID='$id'";
	//echo $str;
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
	if ($q) {
		$st = mysql_fetch_array($q);
		return $st[0];
	} else {
		return 0;
	}
}
function getAttackByDefender($id, $page) {
	global $conf;
	$start = ($page - 1) * $conf['users_per_page_on_attack_log'];
	$str = "select * from `AttackLog` where  toUserID ='$id' ORDER BY `time` DESC LIMIT $start,{$conf['users_per_page_on_attack_log']}";
	//echo $str;
	$q = mysql_query($str);
	if (!@mysql_num_rows($q)) {
		return;
	} else {
		$st = "";
		$i = 0;
		while ($row = mysql_fetch_object($q)) {
			$st[$i] = $row;
			$i++;
		}
		return $st;
	}
}
function getAttackByUser1User2AndTime($User1, $User2, $time, $fields = "*") {
	$str = "select $fields from `AttackLog` where  userID='$User1' AND toUserID='$User2' AND time='$time' ";
	//echo $str;
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
	if (!@mysql_num_rows($q)) {
		return 0;
	} else {
		$st = "";
		$st = mysql_fetch_object($q);
		return $st;
	}
}
function addAttack($id, $toid, $fields, $values) {
	$text = urlencode($text);
	$subject = urlencode($subject);
	$date = time();
	$str = "INSERT INTO `AttackLog` (userID, toUserID, $fields ) VALUES ($id,$toid, $values )";
	//echo $str;
	$q = mysql_query($str);
	return $q;
}
function deleteAttack($id) {
	$str = "DELETE FROM  `AttackLog` WHERE ID='$id'";
	//echo $str;
	$q = mysql_query($str);
}
function deleteOldAttacks() {
	$time = time() - $conf["days_to_hold_logs"] * 24 * 60 * 60;
	$str = "DELETE FROM  `AttackLog` where time<'$time'";
}
function deleteAttacksOfUser($userID) {
	$str = "DELETE FROM  `AttackLog` WHERE userID='$userID'";
	//echo $str;
	$q = mysql_query($str);
}
//-----------------------------End Attack---------------------------------------------------
//-----------------------------Spy---------------------------------------------------
function getSpyCount($userID) {
	$str = "SELECT COUNT(*) FROM `SpyLog` where userID='$userID' ";
	$q = mysql_query($str);
	if ($q) {
		$st = mysql_fetch_array($q);
		return $st[0];
	} else {
		return 0;
	}
}
function getSpyDefenceCount($userID) {
	$str = "SELECT COUNT(*) FROM `SpyLog` where toUserID='$userID' ";
	$q = mysql_query($str);
	if ($q) {
		$st = mysql_fetch_array($q);
		return $st[0];
	} else {
		return 0;
	}
}
function getAllSpys($userID) {
	$str = "SELECT * FROM `SpyLog` WHERE userID='$userID'";
	//print $str;
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
	if (!mysql_num_rows($q)) {
		return;
	} else {
		$st = "";
		$i = 0;
		while ($row = mysql_fetch_object($q)) {
			$st[$i] = $row;
			$i++;
		}
		return $st;
	}
}
function getAllSpyDefences($userID) {
	$str = "SELECT * FROM `SpyLog` WHERE toUserID='$userID'";
	//print $str;
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
	if (!mysql_num_rows($q)) {
		return;
	} else {
		$st = "";
		$i = 0;
		while ($row = mysql_fetch_object($q)) {
			$st[$i] = $row;
			$i++;
		}
		return $st;
	}
}
function getSpy($id) {
	$str = "select * from `SpyLog` where  ID='$id' ";
	//echo $str;
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
	if (!mysql_num_rows($q)) {
		return 0;
	} else {
		$st = "";
		$st = mysql_fetch_object($q);
		return $st;
	}
}
function getSpyBySpyerCount($id) {
	$str = "SELECT COUNT(*) FROM `SpyLog` where  userID='$id'";
	//echo $str;
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
	if ($q) {
		$st = mysql_fetch_array($q);
		return $st[0];
	} else {
		return 0;
	}
}
function getSpyBySpyer($id, $page) {
	global $conf;
	$start = ($page - 1) * $conf['users_per_page'];
	//ORDER BY `rank` ASC LIMIT $start,{$conf['users_per_page']}
	$str = "select * from `SpyLog` where  userID='$id' ORDER BY `time` DESC LIMIT $start,{$conf['users_per_page']}";
	//echo $str;
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
	if (!mysql_num_rows($q)) {
		return;
	} else {
		$st = "";
		$i = 0;
		while ($row = mysql_fetch_object($q)) {
			$st[$i] = $row;
			$i++;
		}
		return $st;
	}
}
function getSpyByDefenderCount($id) {
	$str = "SELECT COUNT(*) FROM `SpyLog` where  toUserID='$id' AND isSuccess='0'";
	//echo $str;
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
	if ($q) {
		$st = mysql_fetch_array($q);
		return $st[0];
	} else {
		return 0;
	}
}
function getSpyByDefender($id, $page) {
	global $conf;
	$start = ($page - 1) * $conf['users_per_page'];
	$str = "select * from `SpyLog` where  toUserID ='$id' AND (isSuccess='0' OR type='1') ORDER BY `time` DESC LIMIT $start,{$conf['users_per_page']}";
	//echo $str;
	$q = mysql_query($str);
	if (!$q) {
		return 0;
	}
	if (!mysql_num_rows($q)) {
		return;
	} else {
		$st = "";
		$i = 0;
		while ($row = mysql_fetch_object($q)) {
			$st[$i] = $row;
			$i++;
		}
		return $st;
	}
}
function getSpyByUser1User2AndTime($User1, $User2, $time, $fields = "*") {
	$str = "select $fields from `SpyLog` where  userID='$User1' AND toUserID='$User2' AND time='$time' ";
	//echo $str;
	$q = mysql_query($str); //exit;
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
	if (!@mysql_num_rows($q)) {
		return 0;
	} else {
		return mysql_fetch_object($q);
	}
}
function addSpy($id, $toid, $fields, $values) {
	$str = "INSERT INTO `SpyLog` (userID, toUserID, $fields ) VALUES ($id,$toid, $values )";
	//echo $str;
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
	return $q;
}
function deleteSpy($id) {
	$str = "DELETE FROM  `SpyLog` WHERE ID='$id'";
	//echo $str;
	$q = mysql_query($str);
}
function deleteOldSpyLogs() {
	$time = time() - $conf["days_to_hold_logs"] * 24 * 60 * 60;
	$str = "DELETE FROM `SpyLog` where time<'$time'";
}
function deleteSpyLogsOfUser($userID) {
	$str = "DELETE FROM  `SpyLog` WHERE userID='$userID'";
	//echo $str;
	$q = mysql_query($str);
}
//-----------------------------End Spy---------------------------------------------------
function RepairWeaponMax($wepid, $user, $type) {
	global $conf, $cgi, $conf_use_savings;
	$user = getUserDetails($user->ID, 'gold,ID');
	$q = mysql_query("SELECT weaponCount,weaponStrength FROM Weapon WHERE weaponID='$wepid' AND userID='$user->ID' AND isAttack='$type'") or die(mysql_error());
	$r = mysql_fetch_array($q, MYSQL_ASSOC);
	if (!$r['weaponCount']) {
		return;
	}
	if ($conf["weapon{$wepid}strength"] != 0) {
		$pris = round($conf["weapon{$wepid}pp"] * $r["weaponCount"] * ($conf["weapon{$wepid}strength"] - $r['weaponStrength']));
		if ($pris > $user->gold) {
			$x = $r['weaponStrength'];
			$y = $conf["weapon{$wepid}strength"];
			$torepair = $torepairm = $y - $x;
			$cost = $costm = round($conf["weapon{$wepid}pp"] * $r['weaponCount'] * $torepair);
			if ($cost > $user->gold) {
				$torepairm = floor($user->gold / ($conf["weapon{$wepid}pp"] * $r['weaponCount']));
				$costm = floor($conf["weapon{$wepid}pp"] * $r['weaponCount'] * $torepair);
			}
			updateUser($user->ID, "gold=gold-$costm");
			mysql_query("UPDATE Weapon SET weaponStrength='" . $conf["weapon{$wepid}strength"] . "' WHERE weaponID='$wepid' AND userID='$user->ID' AND isAttack='$type'") or die(mysql_error());
		} else {
			updateUser($user->ID, "gold=gold-$pris");
			mysql_query("UPDATE Weapon SET weaponStrength='" . $conf["weapon{$wepid}strength"] . "' WHERE weaponID='$wepid' AND userID='$user->ID' AND isAttack='$type'") or die(mysql_error());
		}
	}
}
function RepairWeapon($wepid, $wal, $user, $type) {
	global $cgi, $conf_use_savings, $conf;
	//$wepid=ToPositive($wepid);
	$wal = ToPositive($wal);
	$tp = 'weapon';
	$q = mysql_query("select weaponCount,weaponStrength from `Weapon` where weaponID='$wepid' and userID='$user->ID' and isAttack='$type' ");
	$el = mysql_fetch_array($q, MYSQL_ASSOC);
	if ($conf["weapon{$wepid}strength"] != 0) $pris = round($conf["weapon{$wepid}pp"] * $el["weaponCount"] * $wal);
	else return;
	if ($pris <= ($user->gold)) {
		if (($wal + $el["weaponStrength"]) <= $conf["weapon{$wepid}strength"]) {
			$q = mysql_query("update `Weapon` set weaponStrength=weaponStrength+'$wal' where weaponID='$wepid' and userID='$user->ID' and isAttack='$type' ");
			$q = mysql_query("update `UserDetails` set gold=gold-'$pris' where ID='$user->ID' ");
			updateUserStats($user);
		}
		return "Repaired $wal Damage From Weapons";
	} elseif ($pris <= $user->savings AND $cgi['c_savings'] AND $conf_use_savings) {
		if (($wal + $el["weaponStrength"]) <= $conf["weapon{$wepid}strength"]) {
			$q = mysql_query("update `Weapon` set weaponStrength=weaponStrength+'$wal' where weaponID='$wepid' and userID='$user->ID' and isAttack='$type' ");
			$q = mysql_query("update `UserDetails` set savings=savings-'$pris' where ID='$user->ID' ");
			updateUserStats($user);
		}
		return "Repaired $wal Damage From Weapons";
	} else {
		return 'NO GOLD LEFT!!!';
	}
}
function ScrapSell($wepid, $wal, $a, $user, $type) {
	//$wepid=ToPositive($wepid);
	$wal = ToPositive($wal);
	global $conf;
	$q = mysql_query("select weaponCount,weaponStrength from `Weapon` where weaponID='$wepid' and userID='$user->ID' and isAttack='$type' ");
	$el = mysql_fetch_array($q, MYSQL_ASSOC);
	if ($wal >= $el["weaponCount"]) {
		$wal = $el["weaponCount"];
	}
	if ($conf["weapon{$wepid}strength"] != 0) {
		$pris = round($conf["weapon{$wepid}price"] * (1 + ($user->weapper / 100)) * ($el["weaponStrength"] / $conf["weapon{$wepid}strength"] - 0.2)) * $wal;
		$pris = ToPositive($pris);
	} else return;
	if ($wal < $el["weaponCount"]) {
		$q = mysql_query("update `Weapon` set weaponCount=weaponCount-'$wal' where weaponID='$wepid' and userID='$user->ID' and isAttack='$type' ");
		if ($a != 'Scrap') {
			$q = mysql_query("update `UserDetails` set gold=gold+'$pris' where ID='$user->ID' ");
		}
	}
	if ($wal >= $el["weaponCount"]) {
		$q = mysql_query("delete from `Weapon` where weaponID='$wepid' and userID='$user->ID' and isAttack='$type' ");
		if ($a != 'Scrap') {
			$q = mysql_query("update `UserDetails` set gold=gold+'$pris' where ID='$user->ID' ");
		}
	}
	updateUserStats($user);
}
function BuyWeapon($wepid, $wal, $at, $user) {
	global $cgi, $conf_use_savings, $conf;
	//$wepid=ToPositive($wepid);
	$wal = ToPositive($wal);
	if ($at == 1) {
		$pris = ($conf["weapon{$wepid}price"] * (($user->salevel / 2) + 1)) * $wal;
	} else {
		$pris = ($conf["weapon{$wepid}price"] * (($user->dalevel / 2) + 1)) * $wal;
	}
	$stren = $conf["weapon{$wepid}strength"];
	if ($pris <= ($user->gold)) {
		$q = mysql_query("select weaponCount from `Weapon` where weaponID='$wepid' and userID='$user->ID' and isAttack='$at' ");
		$el = mysql_fetch_array($q, MYSQL_ASSOC);
		if ($el['weaponCount'] >= 200000 OR ($wal + $el['weaponCount']) > 200000) {
			return "You cannot buy anymore of that weapon";
		}
		if (@mysql_num_rows($q)) {
			$q = mysql_query("update `Weapon` set weaponCount=weaponCount+'$wal' where weaponID='$wepid' and userID='$user->ID' and isAttack='$at' ");
			$q = mysql_query("update `UserDetails` set gold=gold-'$pris' where ID='$user->ID' ");
		} else {
			$q = mysql_query("insert into `Weapon` (weaponID, weaponStrength, weaponCount, isAttack, userID) values ('$wepid', '$stren', '$wal', '$at', '$user->ID')");
			$q = mysql_query("update `UserDetails` set gold=gold-'$pris' where ID='$user->ID' ");
		}
		updateUserStats($user);
	} elseif ($pris <= ($user->savings) AND $conf_use_savings AND $cgi['c_savings']) {
		$q = mysql_query("select weaponCount from `Weapon` where weaponID='$wepid' and userID='$user->ID' and isAttack='$at' ");
		$el = mysql_fetch_array($q, MYSQL_ASSOC);
		if ($el['weaponCount'] >= 200000 OR ($wal + $el['weaponCount']) > 200000) {
			return "You cannot buy anymore of that weapon";
		}
		if (@mysql_num_rows($q)) {
			$q = mysql_query("update `Weapon` set weaponCount=weaponCount+'$wal' where weaponID='$wepid' and userID='$user->ID' and isAttack='$at' ");
			$q = mysql_query("update `UserDetails` set savings=savings-'$pris' where ID='$user->ID' ");
		} else {
			$q = mysql_query("insert into `Weapon` (weaponID, weaponStrength, weaponCount, isAttack, userID) values ('$wepid', '$stren', '$wal', '$at', '$user->ID')");
			$q = mysql_query("update `UserDetails` set savings=savings-'$pris' where ID='$user->ID' ");
		}
		updateUserStats($user);
	} else {
		return 'NO GOLD LEFT!!!';
	}
}
function Upgrade($user, $type, $wtd) {
	global $cgi, $conf_use_savings, $conf;
	$user = getUserDetails($user->ID);
	if ($wtd == 'No more upgrades available') return;
	else {
		if ($type == 'fortification') {
			$pris = $conf["race"][$user->race]["fortification"][$user->dalevel + 1]["price"]; // die("da price: $pris");
			if ($pris <= ($user->gold) AND $pris > 0) {
				$q = mysql_query("update `UserDetails` set dalevel=dalevel+'1', gold=gold-'$pris' where ID='$user->ID' ") or die(mysql_error());
				updateUserStats($user);
			} elseif ($pris <= ($user->savings) AND $conf_use_savings AND $cgi['c_savings'] AND $pris > 0) {
				$q = mysql_query("update `UserDetails` set dalevel=dalevel+'1', savings=savings-'$pris' where ID='$user->ID' ") or die(mysql_error());
				updateUserStats($user);
			} else {
				return 'NO GOLD LEFT!!!';
			}
		}
		if ($type == 'siege') {
			$pris = $conf["race"][$user->race]["siege"][$user->salevel + 1]["price"];
			// die("sa price: $pris".print_r($user));
			if ($pris <= ($user->gold) AND $pris > 0) {
				$q = mysql_query("update `UserDetails` set salevel=salevel+'1', gold=gold-'$pris' where ID='$user->ID' ") or die(mysql_error());
				updateUserStats($user);
			} elseif ($pris <= ($user->savings) AND $conf_use_savings AND $cgi['c_savings'] AND $pris > 0) {
				$q = mysql_query("update `UserDetails` set salevel=salevel+'1', savings=savings-'$pris' where ID='$user->ID' ") or die(mysql_error());
				updateUserStats($user);
			} else {
				return 'NO GOLD LEFT!!!';
			}
		}
	}
}
function Train($user, $wal, $type) {
	global $cgi, $conf_use_savings;
	$wal = ToPositive($wal);
	$nogold = "Not enough gold!";
	$nosold = "Not enough untrained soldies!";
	$user = getUserDetails($_SESSION["isLogined"], 'gold,ID,exp,savings');
	if ($type == 0) {
		$pris = 2000 * $wal;
		if ($pris <= ($user->gold)) {
			$q = mysql_query("select uu from `UserDetails` where ID='$user->ID' ");
			$el = mysql_fetch_array($q, MYSQL_ASSOC);
			if ($el[uu] >= $wal) {
				$q = mysql_query("update `UserDetails` set sasoldiers=sasoldiers+'$wal', uu=uu-'$wal', gold=gold-'$pris' where ID='$user->ID' ");
				if (!$q) {
					print ('Query failed: ' . mysql_error());
					return;
				}
				updateUserStats($user);
			} else return $nosold;
		} elseif ($pris <= ($user->savings) AND $conf_use_savings AND $cgi['c_savings']) {
			$q = mysql_query("select uu from `UserDetails` where ID='$user->ID' ");
			$el = mysql_fetch_array($q, MYSQL_ASSOC);
			if ($el[uu] >= $wal) {
				$q = mysql_query("update `UserDetails` set sasoldiers=sasoldiers+'$wal', uu=uu-'$wal', savings=savings-'$pris' where ID='$user->ID' ");
				if (!$q) {
					print ('Query failed: ' . mysql_error());
					return;
				}
				updateUserStats($user);
			} else return $nosold;
		} else {
			return $nogold;
		}
	} elseif ($type == 1) {
		$pris = 2000 * $wal;
		if ($pris <= ($user->gold)) {
			$q = mysql_query("select uu from `UserDetails` where ID='$user->ID' ");
			$el = mysql_fetch_array($q, MYSQL_ASSOC);
			if ($el[uu] >= $wal) {
				$q = mysql_query("update `UserDetails` set dasoldiers=dasoldiers+'$wal', uu=uu-'$wal', gold=gold-'$pris' where ID='$user->ID' ");
				if (!$q) {
					print ('Query failed: ' . mysql_error());
					return;
				}
				updateUserStats($user);
			} else return $nosold;
		} elseif ($pris <= ($user->savings) AND $conf_use_savings AND $cgi['c_savings']) {
			$q = mysql_query("select uu from `UserDetails` where ID='$user->ID' ");
			$el = mysql_fetch_array($q, MYSQL_ASSOC);
			if ($el[uu] >= $wal) {
				$q = mysql_query("update `UserDetails` set dasoldiers=dasoldiers+'$wal', uu=uu-'$wal', savings=savings-'$pris' where ID='$user->ID' ");
				if (!$q) {
					print ('Query failed: ' . mysql_error());
					return;
				}
				updateUserStats($user);
			} else return $nosold;
		} else {
			return $nogold;
		}
	} elseif ($type == 2) {
		$pris = 3500 * $wal;
		if ($pris <= ($user->gold)) {
			$q = mysql_query("select uu from `UserDetails` where ID='$user->ID' ");
			$el = mysql_fetch_array($q, MYSQL_ASSOC);
			if ($el[uu] >= $wal) {
				$q = mysql_query("update `UserDetails` set spies=spies+'$wal', uu=uu-'$wal', gold=gold-'$pris' where ID='$user->ID' ");
				updateUserStats($user);
				if (!$q) {
					print ('Query failed: ' . mysql_error());
					return;
				}
			} else return $nosold;
		} elseif ($pris <= ($user->savings) AND $conf_use_savings AND $cgi['c_savings']) {
			$q = mysql_query("select uu from `UserDetails` where ID='$user->ID' ");
			$el = mysql_fetch_array($q, MYSQL_ASSOC);
			if ($el[uu] >= $wal) {
				$q = mysql_query("update `UserDetails` set spies=spies+'$wal', uu=uu-'$wal', savings=savings-'$pris' where ID='$user->ID' ");
				updateUserStats($user);
				if (!$q) {
					print ('Query failed: ' . mysql_error());
					return;
				}
			} else return $nosold;
		} else {
			return $nogold;
		}
	} elseif ($type == 3) {
		$pris = 100000 * $wal;
		if ($pris <= ($user->gold)) {
			$q = mysql_query("select uu from `UserDetails` where ID='$user->ID' ");
			$el = mysql_fetch_array($q, MYSQL_ASSOC);
			if ($el[uu] >= $wal) {
				$q = mysql_query("update `UserDetails` set specialforces=specialforces+'$wal', uu=uu-'$wal', gold=gold-'$pris' where ID='$user->ID' ");
				if (!$q) {
					print ('Query failed: ' . mysql_error());
					return;
				}
				updateUserStats($user);
			} else return $nosold;
		} elseif ($pris <= ($user->savings) AND $cgi['c_savings'] AND $conf_use_savings) {
			$q = mysql_query("select uu from `UserDetails` where ID='$user->ID' ");
			$el = mysql_fetch_array($q, MYSQL_ASSOC);
			if ($el[uu] >= $wal) {
				$q = mysql_query("update `UserDetails` set specialforces=specialforces+'$wal', uu=uu-'$wal', savings=savings-'$pris' where ID='$user->ID' ");
				if (!$q) {
					print ('Query failed: ' . mysql_error());
					return;
				}
				updateUserStats($user);
			} else return $nosold;
		} else {
			return $nogold;
		}
	} elseif ($type == 4) {
		$q = mysql_query("select sasoldiers from `UserDetails` where ID='$user->ID' ");
		$el = mysql_fetch_array($q, MYSQL_ASSOC);
		if ($el[sasoldiers]) $q = mysql_query("update `UserDetails` set sasoldiers=sasoldiers-'$wal', uu=uu+'$wal' where ID='$user->ID' ");
		updateUserStats($user);
	} elseif ($type == 5) {
		$q = mysql_query("select dasoldiers from `UserDetails` where ID='$user->ID' ");
		$el = mysql_fetch_array($q, MYSQL_ASSOC);
		if ($el[dasoldiers]) $q = mysql_query("update `UserDetails` set dasoldiers=dasoldiers-'$wal', uu=uu+'$wal' where ID='$user->ID' ");
		updateUserStats($user);
	} elseif ($type == 6) {
		$q = mysql_query("select spies from `UserDetails` where ID='$user->ID' ");
		$el = mysql_fetch_array($q, MYSQL_ASSOC);
		if ($el[spies]) $q = mysql_query("update `UserDetails` set spies=spies-'$wal', uu=uu+'$wal' where ID='$user->ID' ");
		updateUserStats($user);
	} elseif ($type == 7) {
		$q = mysql_query("select specialforces from `UserDetails` where ID='$user->ID' ");
		$el = mysql_fetch_array($q, MYSQL_ASSOC);
		if ($el[specialforces]) $q = mysql_query("update `UserDetails` set specialforces=specialforces-'$wal', uu=uu+'$wal' where ID='$user->ID' ");
		updateUserStats($user);
	} elseif ($type == 8) {
		$pris = 300000 * $wal;
		$exp = 100 * $wal;
		if ($pris <= ($user->gold) AND $exp <= ($user->exp)) {
			$q = mysql_query("select uu from `UserDetails` where ID='$user->ID' ");
			$el = mysql_fetch_array($q, MYSQL_ASSOC);
			if ($el[uu] >= $wal) {
				$q = mysql_query("update `UserDetails` set scientists=scientists+'$wal', uu=uu-'$wal', gold=gold-'$pris',exp=exp-'$exp' where ID='$user->ID' ");
				updateUserStats($user);
				if (!$q) {
					print ('Query failed: ' . mysql_error());
					return;
				}
			} else return $nosold;
		} elseif ($pris <= ($user->savings) AND $conf_use_savings AND $cgi['c_savings'] AND $exp <= ($user->exp)) {
			$q = mysql_query("select uu from `UserDetails` where ID='$user->ID' ");
			$el = mysql_fetch_array($q, MYSQL_ASSOC);
			if ($el[uu] >= $wal) {
				$q = mysql_query("update `UserDetails` set scientists=scientists+'$wal', uu=uu-'$wal', savings=savings-'$pris',exp=exp-'$exp' where ID='$user->ID' ");
				updateUserStats($user);
				if (!$q) {
					print ('Query failed: ' . mysql_error());
					return;
				}
			} else return $nosold;
		} else {
			return $nogold;
		}
	} elseif ($type == 9) {
		$q = mysql_query("select scientists from `UserDetails` where ID='$user->ID' ");
		$el = mysql_fetch_array($q, MYSQL_ASSOC);
		if ($el[scientists]) $q = mysql_query("update `UserDetails` set scientists=scientists-'$wal', uu=uu+'$wal' where ID='$user->ID' ");
		updateUserStats($user);
	}
}
function Trainupgrade($user, $type) {
	global $cgi, $conf_use_savings;
	$user = getUserDetails($_SESSION["isLogined"], 'ID,calevel,gold,race,up,savings,sflevel,maxofficers,exp,bankper,weapper,hhlevel,nukelevel,scientists');
	if ($type == 'spy') {
		$pris = pow(2, $user->calevel) * 12000;
		if ($pris <= ($user->gold)) {
			$q = mysql_query("update `UserDetails` set calevel=calevel+'1', gold=gold-'$pris' where ID='$user->ID' ");
			updateUserStats($user);
		} elseif ($pris <= ($user->savings) AND $conf_use_savings AND $cgi['c_savings']) {
			$q = mysql_query("update `UserDetails` set calevel=calevel+'1', savings=savings-'$pris' where ID='$user->ID' ");
			updateUserStats($user);
		} else {
			return 'NO GOLD LEFT!!!';
		}
	}
	if ($type == 'unit') {
		if ($user->race == 4) {
			$pris = ($user->up * 8500) + 10000;
		} else {
			$pris = ($user->up * 10000) + 10000;
		}
		if ($pris <= $user->gold) {
			$q = mysql_query("update `UserDetails` set up=up+'1', gold=gold-'$pris' where ID='$user->ID' ");
		} elseif ($pris <= $user->savings AND $conf_use_savings AND $cgi['c_savings']) {
			$q = mysql_query("update `UserDetails` set up=up+'1', savings=savings-'$pris' where ID='$user->ID' ");
		} else {
			return 'NO GOLD LEFT!!!';
		}
	}
	if ($type == 'sf') {
		$pris = pow(2, $user->sflevel) * 100000 + 100000;
		if ($pris <= ($user->gold)) {
			$q = mysql_query("update `UserDetails` set sflevel=sflevel+'1', gold=gold-'$pris' where ID='$user->ID' ");
			updateUserStats($user);
		} elseif ($pris <= ($user->savings) AND $conf_use_savings AND $cgi['c_savings']) {
			$q = mysql_query("update `UserDetails` set sflevel=sflevel+'1', savings=savings-'$pris' where ID='$user->ID' ");
			updateUserStats($user);
		} else {
			return 'NO GOLD LEFT!!!';
		}
	}
	if ($type == 'of') {
		if ($user->maxofficers >= 15) {
			return 'You cannot have more than 15 officers';
		}
		$pris = pow(2, floor($user->maxofficers / 2)) * 1000;
		if ($pris <= ($user->exp)) {
			$q = mysql_query("update `UserDetails` set maxofficers=maxofficers+'1', exp=exp-'$pris' where ID='$user->ID' ");
		}
		else {
			return 'NOT ENOUGH EXPERIENCE!!!';
		}
	}
	if ($type == 'b') {
		$pris = pow(3, (10 - $user->bankper)) * 1800 + 1500;
		if ($user->bankper == 1) {
			return "YOU CANNOT UPGRADE AGAIN!!!";
		}
		if ($pris <= ($user->exp)) $q = mysql_query("update `UserDetails` set bankper=bankper-'1', exp=exp-'$pris' where ID='$user->ID' ");
		else return 'NOT ENOUGH EXPERIENCE!!!';
	}
	if ($type == 'w') {
		$pris = pow(4, $user->weapper) * 1800 + 1500;
		if ($user->weapper == 4) {
			return "YOU CANNOT UPGRADE AGAIN!!!";
		}
		if ($pris <= ($user->exp)) {
			$q = mysql_query("update `UserDetails` set weapper=weapper+'1', exp=exp-'$pris' where ID='$user->ID' ");
			return 'Purchase successful';
		} else {
			return 'NOT ENOUGH EXPERIENCE!!!';
		}
	}
	if ($type == 'hh') {
		$pris = pow(2.5, $user->hhlevel) * 125000 + 112500;
		if ($pris <= ($user->gold)) {
			$q = mysql_query("update `UserDetails` set hhlevel=hhlevel+'1', gold=gold-'$pris' where ID='$user->ID' ");
			updateUserStats($user);
		} elseif ($pris <= ($user->savings) AND $conf_use_savings AND $cgi['c_savings']) {
			$q = mysql_query("update `UserDetails` set hhlevel=hhlevel+'1', savings=savings-'$pris' where ID='$user->ID' ");
			updateUserStats($user);
		} else {
			return 'NOT ENOUGH GOLD!!!';
		}
	}
	return 'Success';
}
function duration($time, $N = 1) {
	if ($N == 3) {
		$hour = floor($time / 3600);
		$time = $time - $hour * 3600;
		$min = floor($time / 60);
		$sec = $time - $min * 60;
		return str_pad($hour, 2, "0", STR_PAD_LEFT) . ":" . str_pad($min, 2, "0", STR_PAD_LEFT) . ":" . str_pad($sec, 2, "0", STR_PAD_LEFT);
	}
	$input['week'] = floor($time / 604800);
	$time = $time - $input['week'] * 604800;
	$input['day'] = floor($time / 86400);
	$time = $time - $input['day'] * 86400;
	$input['hour'] = floor($time / 3600);
	$time = $time - $input['hour'] * 3600;
	$input['min'] = floor($time / 60);
	$input['sec'] = $time - $input['min'] * 60;
	$output = "";
	if ($input['week']) {
		if ($input['week'] > 1) {
			$output.= $input['week'] . "wks ";
		} else {
			$output.= $input['week'] . "wk ";
		}
	}
	if ($input['day']) {
		if ($input['day'] > 1) {
			$output.= $input['day'] . "days ";
		} else {
			$output.= $input['day'] . "day ";
		}
	}
	if ($input['hour']) {
		if ($input['hour'] > 1) {
			$output.= $input['hour'] . "hours ";
		} else {
			$output.= $input['hour'] . "hour ";
		}
	}
	if ($input['min']) {
		if ($input['min'] > 1) {
			$output.= $input['min'] . "mins ";
		} else {
			$output.= $input['min'] . "min ";
		}
	}
	if ($input['sec']) {
		if ($N == 1) {
			if ($input['sec'] > 1) {
				$output.= $input['sec'] . "secs ";
			} else {
				$output.= $input['sec'] . "sec ";
			}
		} elseif (($output == NULL) && ($N == 2)) {
			if ($input['sec'] > 1) {
				$output.= $input['sec'] . "secs ";
			} else {
				$output.= $input['sec'] . "sec ";
			}
		}
	}
	if (substr($output, -1) == chr(32)) {
		return substr($output, 0, strlen($output) - 1);
	}
	return $output;
}
function getarea($num) {
	switch ($num) {
		default:
		case 0:
			return 'U.S.A';
		case 1:
			return 'U.K';
		case 2:
			return 'Japan';
		case 3:
			return 'Germany';
		case 4:
			return 'Russia';
	}
}
if ($_SESSION['isLogined']) {
	$user = getUserDetails($_SESSION['isLogined']);
	if (!$user) {
		echo "ERROR getting user";
	}
}
?>
