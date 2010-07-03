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

class IP extends BaseClass {
	public
		$id   = 0,
		$IP   = '',
		$uid  = 0,
		$time = 0;

	public static function
	add(User $user) {
		if (!$user->id or !$user->currentIP) {
			return false;
		}
		
		$count = IP::getCount($user, $user->currentIP);

		if ($count == 0) {
			$ip = new IP();
			$ip->IP   = $user->currentIP;
			$ip->uid  = $user->id;
			$ip->time = time();
			$ip->create();
		}

		return true;
	}

	public static function
	getCount(User $user, $ip) {
		$userIP = mysql_real_escape_string($ip);
		
		$q = mysql_query("SELECT COUNT(*) as retCode FROM IP WHERE IP =\"$userIP\" and uid = $user->id") or die (mysql_error());
		$r = mysql_fetch_object($q);

		return $r->retCode;
	}

	public static function
	canAttack(User $user, User $target) {
		if (!$user->id or !$target->id) {
			return false;
		}
		
		$count = IP::getCount($target, $user->currentIP);
		return ($count == 0);
	}
}
?>
