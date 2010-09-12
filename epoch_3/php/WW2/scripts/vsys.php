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


error_reporting(E_ALL & ~E_NOTICE);

if (!$incron) {
	session_start();
	session_regenerate_id(false);

	$alert='';
}

//mb_internal_encoding('UTF-8');
//mb_regex_encoding('UTF-8');
setlocale(LC_ALL, 'UTF-8');

require_once('conf.php');

require_once('weaps.php');
require_once('db.php');

require_once('BaseClass.php');

require_once('User.php');
//require_once('Weapon.php');
require_once('gen-stats.php');
require_once('Privacy.php');
require_once('IP.php');

//$game_offline = true;
if ($game_offline == true and !$_SESSION['admin'] and !$incron) {
	header('Location: offline.php');
	exit;
}


//-----------------------------Convertions--------------------------------------------
function numecho ($str){
	if ($str=="unranked"){echo $str;}
	elseif($str=="None"){echo $str;}
	else{
		//echo $str;
		echo number_format($str,0,'.',',');
	}
}

function numecho2 ($str){
	return number_format($str, 0, '.', ',');
}

function getPassword($n = 10) {
	$ret = '';
	while ($n-- > 0) {
		if (rand(0, 1)) {
			$ret .= chr(rand(ord('A'), ord('Z')));
		}
		else {
			$ret .= chr(rand(ord('0'), ord('9')));
		}
	}
	return $ret;
}

if (!function_exists('ngettext')) {
	function ngettext($s, $p, $n) {
		return $n == 1 ? $s : $p;
	}
	
	function _($s) {
		return $s;
	}
}

function pngt($sing, $plur, $d) {
	return sprintf(ngettext($sing, $plur, (int)$d), numecho2($d));
}

function ngt($sing, $plur, $d) {
	return ngettext($sing, $plur, (int)$d);
}

function getCachedUser($id) {
	static $users = array();
	if ($users[$id]) {
		return $users[$id];
	}
	else {
		$u = new User();
		$u->get($id);
		$users[$id] = $u;
		return $u;
	}
}

function getCachedAlliance($id) {
	static $alliances = array();
	if ($alliances[$id]) {
		return $alliances[$id];
	}
	else {
		$a = new Alliance();
		$a->get($id);
		$alliances[$id] = $a;
		return $a;
	}
}

function me($id = null) {
	if ($id) {
		return intval($id) == Privacy::getId();
	}
	return Privacy::getId();
}

//-----------------------------End Convertions--------------------------------------------


function duration($time, $N = 1) {
	if ($N == 3) {
		$hour = floor($time / 3600);
		$time = $time - $hour * 3600;
		$min = floor($time / 60);
		$sec = $time - $min * 60;
		return str_pad($hour, 2, "0", STR_PAD_LEFT).":".str_pad($min, 2, "0", STR_PAD_LEFT).":".str_pad($sec, 2, "0", STR_PAD_LEFT);
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
			$output .= $input['week']."wks ";
		}
		else {
			$output .= $input['week']."wk ";
		}
	}
	if ($input['day']) {
		if ($input['day'] > 1) {
			$output .= $input['day']."days ";
		}
		else {
			$output .= $input['day']."day ";
		}
	}
	if ($input['hour']) {
		if ($input['hour'] > 1) {
			$output .= $input['hour']."hours ";
		}
		else {
			$output .= $input['hour']."hour ";
		}
	}
	if ($input['min']) {
		if ($input['min'] > 1) {
			$output .= $input['min']."mins ";
		}
		else {
			$output .= $input['min']."min ";
		}
	}
	if ($input['sec']) {
		if ($N == 1) {
			if ($input['sec'] > 1) {
				$output .= $input['sec']."secs ";
			}
			else {
				$output .= $input['sec']."sec ";
			}
		}
		elseif (($output == NULL) && ($N == 2)) {
			if ($input['sec'] > 1) {
				$output .= $input['sec']."secs ";
			}
			else {
				$output .= $input['sec']."sec ";
			}
		}
	}
	if (substr($output, -1) == chr(32)) {
		return substr($output, 0, strlen($output) - 1);
	}
	return $output;
}

function getMercs() {
	$q = mysql_query('SELECT attackSpecCount as samercs, defSpecCount as damercs FROM Mercenaries limit 1') or die(mysql_error());
	$ret = mysql_fetch_object($q);
	return $ret;
}
function saveMercs($samercs, $damercs) {
	mysql_query("UPDATE Mercenaries set attackSpecCount = $samercs, defSpecCount = $damercs") or die(mysql_error());
}

if (Privacy::isIn()){
	$user = getCachedUser(Privacy::getId());
	if (!$_SESSION['admin']) {
		$user->lastturntime = time();
		$user->currentIP  = $_SERVER['REMOTE_ADDR'];
		$user->save();

		IP::add($user);
	}
	if ($_GET['switch-primary']) {
		$s = $_GET['switch-primary'];
		unset($_GET['switch-primary']);
		$p = $user->primary ? 0 : 1;
		$user->primary = $p;
		$user->save();
		header('Location: ' . $s . '.php?' .  http_build_query($_GET));
		exit;
	}
	if ($_GET['admin-key'] and $user->admin) {
		switch ($_GET['admin-key']) {
			case 'on': $_SESSION['admin'] = true; break;
			case 'off': $_SESSION['admin'] = false; break;
			default: break;
		}
	}

}

?>
