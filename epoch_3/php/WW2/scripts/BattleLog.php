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

class BattleLog extends BaseClass {
	public
		$id = 0,
		$attackerId = 0,
		$targetId = 0,
		$attackType = 0,
		$time = 0,
		$isSuccess = 0,
		
		$attackerStrength = 0,
		$targetStrength = 0,
		
		$attackerStrikePercentage = 0.0,
		$targetDefensePercentage = 0.0,

		$attackerLosses = 0,
		$targetLosses = 0,
		
		// The damage the attacker did to the defender's weapons
		$attackerDamage = 0,
		// Damge the defender did to the attacker's weapons.
		$targetDamage   = 0,
		
		$satrained   = 0,     // trained with weapons
		$samercs     = 0,     // mercs with weapons
		$sauntrained = 0,     // untrained with weapons
		
		$datrained   = 0,	  // trained with weapons
		$damercs     = 0,     // mercs with weapons
		$dauntrained = 0,     // untrained with weapons
		
		$satrainednw   = 0,   // trained without weapons
		$samercsnw     = 0,   // mercs without weapons
		$sauntrainednw = 0,   // untrained without weapons
		
		$datrainednw   = 0,   // trained without weapons
		$damercsnw     = 0,   // mercs without weapons
		$dauntrainednw = 0,   // untrained without weapons
		

		$goldStolen = 0,
		$percentStolen = 0.0, // the percentage of the gold stolen
		
		$attackerExp = 0,     // The exp gained by the attacker
		$targetExp   = 0,     // The exp gained by the defender
		
		$attackerHostages = 0, // hostages taken by the attacker
		$targetHostages   = 0, // hostages taken by the defender
		
		$attackerRA       = 0.0, // the extra damage RA did
		$targetRA         = 0.0,
		
		$attackerRAPercentage = 0.0,
		$targetRAPercentage   = 0.0,
		$RADamage             = 0
		
		;
		
		

	public function
	save() {
		if (!$this->id) {
			return $this->create();
		}
		return parent::save();
	}

	public function
	getTime($fmtShort = 'H:i', $fmtLong = 'M jS') {
		if ($this->time > time() - 60 * 60 * 24) {
			return date($fmtShort, $this->time);
		}
		return date($fmtLong, $this->time);
	} 
	
	public function
	getSuccess() {
		return $this->isSuccess > 0;
	}


	// Static

	public static function
	getAllAttacks() {
		$ret = array();
		$q = mysql_query("SELECT * FROM BattleLog ORDER BY time ASC") or die(mysql_error());
		while ($log = mysql_fetch_object($q, 'BattleLog')) {
			$ret[] = $log;
		}

		return $ret;
	}

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


			
		$q = mysql_query("SELECT * FROM BattleLog WHERE targetId = $userId ORDER BY time DESC $pageSQL") or die(mysql_error());
		while ($log = mysql_fetch_object($q, 'BattleLog')) {
			$ret[] = $log;
		}

		return $ret;
	}

	public static function
	getDefenseLogsCount($userId) {
		$q = mysql_query("SELECT COUNT(*) as retCode FROM BattleLog WHERE targetId = $userId") or die(mysql_error());
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


			
		$q = mysql_query("SELECT * FROM BattleLog WHERE attackerId = $userId ORDER BY time DESC $pageSQL") or die(mysql_error());
		while ($log = mysql_fetch_object($q, 'BattleLog')) {
			$ret[] = $log;
		}

		return $ret;
	}

	public static function
	getAttackLogsCount($userId) {
		$q = mysql_query("SELECT COUNT(*) as retCode FROM BattleLog WHERE attackerId = $userId") or die(mysql_error());
		$ret = mysql_fetch_object($q);
		return $ret->retCode;
	}

	public static function
	getDefenseLogsOfUserSinceTimeCount($userId, $time) {
		$q = mysql_query("SELECT COUNT(*) as retCode FROM BattleLog WHERE targetId = $userId and time > $time") or die(mysql_error());
		$ret = mysql_fetch_object($q);
		return $ret->retCode;
	}

	public static function
	getAttackLogsOfUserSinceTimeCount($userId, $time) {
		$q = mysql_query("SELECT COUNT(*) as retCode FROM BattleLog WHERE attackerId = $userId and time > $time") or die(mysql_error());
		$ret = mysql_fetch_object($q);
		return $ret->retCode;
	}

	public static function
	getAttackLogsBetweenUsersSinceTimeCount($attackerId, $defenderId, $time) {
		$q = mysql_query("SELECT COUNT(*) as retCode FROM BattleLog WHERE attackerId = $attackerId and targetId = $defenderId and time > $time") or die(mysql_error());
		$ret = mysql_fetch_object($q);
		return $ret->retCode;
	}

	public static function
	getLogsSinceTime($time) {
		$ret = array();
		$q = mysql_query("SELECT * FROM BattleLog WHERE time > $time ORDER BY time ASC LIMIT 10") or die(mysql_error());
		while ($log = mysql_fetch_object($q, 'BattleLog')) {
			$ret[] = $log;
		}

		return $ret;
	}
}
?>
