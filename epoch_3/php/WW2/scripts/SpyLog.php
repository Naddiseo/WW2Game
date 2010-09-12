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

class SpyLog extends BaseClass {
	public
		$id = 0,
		$attackerId = 0,
		$targetId = 0,
		$attackerStrength = 0,
		$targetStrength = 0,
		$sasoldiers = '',
		$samercs = '',
		$dasoldiers = '',
		$damercs = '',
		$uu = '',
		$SA = '',
		$DA = '',
		$calevel = '',
		$targetSpies = '',
		$salevel = '',
		$dalevel = '',
		$attackTurns = '',
		$unitProduction = '',
		$weapons = '',
		$types = '',
		$types2 = '',
		$quantities = '',
		$strengths = '',
		$allStrengths = '',
		$time = 0,
		$spies = 0,
		$isSuccess = 0,
		$sf = '',
		$ralevel = '',
		$hhlevel = '',
		$gold = '',
		$weapontype = 0, // attack or defense
		$type = 0, // 0 = spy, 1 = theft, 2 = gold theft
		$weaponamount = 0, // weapons taken
		$hostages = 0,     // 
		$weapontype2 = 0,  // weapon type take
		$goldStolen = 0;
	public function
	getTime($fmtShort = 'H:i', $fmtLong = 'M jS') {
		if ($this->time > time() - 60 * 60 * 24) {
			return date($fmtShort, $this->time);
		}
		return date($fmtLong, $this->time);
	} 
		

	// Statics

	public static function
	getDefenseLogs($userId, $page = NULL) {
		global $conf;

		$ret = array();
		
		$pageSQL = '';
		if ($page) {
			$page = max(0, $page - 1) * $conf['attacks-per-page'];
			$limit = $conf['attacks-per-page'];
			$pageSQL = " LIMIT $page, $limit ";
		}

			
		$q = mysql_query("SELECT * FROM SpyLog WHERE targetId = $userId and (isSuccess = 0 or type != 0) ORDER BY time DESC $pageSQL") or die(mysql_error());
		while ($log = mysql_fetch_object($q, 'SpyLog')) {
			$ret[] = $log;
		}

		return $ret;
	}

	public static function
	getDefenseLogsCount($userId) {
		$q = mysql_query("SELECT COUNT(*) as retCode FROM SpyLog WHERE targetId = $userId and (isSuccess = 0 or type != 0)") or die(mysql_error());
		$ret = mysql_fetch_object($q);
		return $ret->retCode;
	}

	public static function
	getAttackLogs($userId, $page = NULL) {
		global $conf;

		$ret = array();
		
		$pageSQL = '';
		if ($page) {
			$page = max(0, $page - 1) * $conf['attacks-per-page'];
			$limit = $conf['attacks-per-page'];
			$pageSQL = " LIMIT $page, $limit ";
		}


			
		$q = mysql_query("SELECT * FROM SpyLog WHERE attackerId = $userId ORDER BY time DESC $pageSQL") or die(mysql_error());
		while ($log = mysql_fetch_object($q, 'SpyLog')) {
			$ret[] = $log;
		}

		return $ret;
	}

	public static function
	getAttackLogsCount($userId) {
		$q = mysql_query("SELECT COUNT(*) as retCode FROM SpyLog WHERE attackerId = $userId") or die(mysql_error());
		$ret = mysql_fetch_object($q);
		return $ret->retCode;
	}
	
	public static function
	getTheftOnUserCount($fromId, $userId, $time = 0) {
		$q = mysql_query("SELECT COUNT(*) as retCode FROM SpyLog WHERE attackerId = $fromId AND targetId = $userId AND time > $time AND type > 0") or die(mysql_error());
		$ret = mysql_fetch_object($q);
		return $ret->retCode;
	}

}
?>
