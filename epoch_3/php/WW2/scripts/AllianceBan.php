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

require_once('scripts/Alliance.php');

class AllianceBan extends BaseClass {
	public
		$id         = 0,
		$allianceId = 0,
		$targetId   = 0,
		$date       = 0;

	//statics
	public static function
	getByPair(Alliance $a, User $target) {
		$ret = null;
		
		$q = mysql_query("SELECT * FROM `AllianceBan` WHERE allianceId = $a->id and targetId = $target->id") or die(mysql_error());
		$r = mysql_fetch_object($q, 'AllianceBan');
		if ($r) {
			$ret = $r;
		}
		
		return $ret;
	}
	
	public static function
	isBlocked(Alliance $a, User $target) {
		$ret = false;
		
		$q = mysql_query("SELECT count(*) as retCode FROM `AllianceBan` WHERE allianceId = $a->id and targetId = $target->id") or die(mysql_error());
		$r = mysql_fetch_object($q);
		if ($r->retCode > 0) {
			$ret = true;
		}	
		
		return $ret;
	}	
	

	public static function
	getAll(Alliance $a) {
		$ret = array();
		
		$q = mysql_query("SELECT * FROM `AllianceBan` WHERE allianceId = $a->id ORDER BY id DESC") or die(mysql_error());
		while ($r = mysql_fetch_object($q, 'AllianceBan')) {
			$ret[] = $r;
		}
		
		return $ret;
	}
	
	public static function
	getAllianceIdsWhoBlockedUser(User $u) {
		$ret = array();
		
		$q = mysql_query("SELECT allianceId FROM `AllianceBan` WHERE targetId = $u->id ORDER BY id DESC") or die(mysql_error());
		while ($r = mysql_fetch_object($q)) {
			$ret[$r->allianceId] = true;
		}
		
		return $ret;
	}
	
	public static function
	removeIds(Alliance $a, array $ids) {
		if (!count($ids)) return;
		
		$ids = implode(',', $ids);
		mysql_query("DELETE FROM `AllianceBan` WHERE allianceId = $a->id and id in ($ids)") or die(mysql_error());
	}
}
?>
