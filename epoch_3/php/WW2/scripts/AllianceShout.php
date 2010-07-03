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

class AllianceShout extends BaseClass {
	public
		$id         = 0,
		$allianceId = 0,
		$userId     = 0,
		$date       = 0,
		$message    = '';
	
	public function
	getUser() {
		return getCachedUser($this->userId);
	}
	
	public function
	getAlliance() {
		return getCachedAlliance($this->allianceId);
	}
	
	public function
	getTime($fmtShort = 'H:i', $fmtLong = 'M jS') {
		if ($this->date > time() - 60 * 60 * 24) {
			return date($fmtShort, $this->date);
		}
		return date($fmtLong, $this->date);
	}
	
	public function
	getText() {
		return nl2br(htmlentities($this->message, ENT_QUOTES, 'UTF-8'));
	}
	
	
	// statics
	public static function
	getShouts(Alliance $a) {
		global $conf;
	
		$ret = array();
	
		$q = mysql_query("SELECT * FROM `AllianceShout` WHERE allianceId = $a->id ORDER BY date DESC LIMIT " . $conf['max-alliance-shouts']) or die(mysql_error());
		while ($r = mysql_fetch_object($q, 'AllianceShout')) {
			$ret[] = $r;
		}
		
		return $ret;
	}
}
?>
