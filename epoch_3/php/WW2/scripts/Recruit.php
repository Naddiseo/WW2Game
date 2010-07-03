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

require_once('scripts/BaseClass.php');

class Recruit extends BaseClass {
	public
		$id  = 0,
		$IP  = '',
		$uId = 0,
		$time = 0,
		$sId = '';
	
	public static function
	queryRecentCount($uid, $IP) {
		global $conf;
		$t = time() - $conf['recruit-seconds'];
		$IP = mysql_real_escape_string($IP);
		$q = mysql_query("SELECT count(*) as retCode FROM Recruit WHERE uId = $uid and IP = \"$IP\" and time > $t") or die(mysql_error());
		$o = mysql_fetch_object($q);
		
		if ($o->retCode) {
			return $o->retCode;
		}
		return 0;
	}
}
?>
