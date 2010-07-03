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


define('MSG_STATUS_UNREAD',  1);
define('MSG_STATUS_READ',    2);
define('MSG_STATUS_DELETED', 3);

class Message extends BaseClass {
	public
		$id           = 0,
		$targetId     = 0,
		$senderId     = 0,
		$subject      = '',
		$text         = '',
		$targetStatus = MSG_STATUS_UNREAD,
		$senderStatus = MSG_STATUS_READ,
		$date         = 0,
		$age          = 0,
		$fromadmin    = 0;

	public function
	save() {
		if (!$this->id) {
			return $this->create();
		}
		return parent::save();
	}

	public function
	getTime($fmtShort = 'H:i', $fmtLong = 'M jS') {
		if ($this->date > time() - 60 * 60 * 24) {
			return date($fmtShort, $this->date);
		}
		return date($fmtLong, $this->date);
	}
	
	public function
	getBB() {
		$max = 5;
		// TODO: nested quotes.
		$u = $t = $this->text;
		while (null !== $u and $max-- > 0) {
			$u = preg_replace("/\[quote\](.*)\[\/quote\]/is", "<span class=\"quote-title\">Quote:</span><div class=\"quote\">\\1</div>", $t);
			$t = $u;
		}
		$t = nl2br($t);
		return $t;
	}
	
	// statics
	
	public static function
	getInbox($userId = 0, $age = -1) {
		global $current_age, $first_age;
		$ret = array();
		
		$where = '';
		if ($age >= $first_age and $age <= $current_age) {
			$where = " AND age = $age ";
		}
		
		
		$q = mysql_query("SELECT * FROM Message WHERE targetId = $userId and targetstatus < 3 $where order by date desc") or die(mysql_error());
		while ($r = mysql_fetch_object($q, 'Message')) {
			$ret[] = $r;
		}
		
		return $ret;
	}
	
	public static function
	getOutbox($userId = 0, $age = -1) {
		global $current_age, $first_age;
		$ret = array();
		
		$where = '';
		if ($age >= $first_age and $age <= $current_age) {
			$where = " AND age = $age ";
		}
		
		$q = mysql_query("SELECT * FROM Message WHERE senderId = $userId and senderstatus < 3 $where order by date desc") or die(mysql_error());
		while ($r = mysql_fetch_object($q, 'Message')) {
			$ret[] = $r;
		}
		
		return $ret;
	}

	public static function
	deleteSenderMulti($senderId, array $msgIds) {
		if (!count($msgIds)) {
			return;
		}
		$ids = implode(', ', $msgIds);
		$q = mysql_query("
			UPDATE
				Message
			SET
				senderStatus = " . MSG_STATUS_DELETED . "
			WHERE
				senderId = $senderId AND
				id in ($ids);
		") or die(mysql_error());
		
		return;
	}
	
	public static function
	deleteTargetMulti($targetId, array $msgIds) {
		if (!count($msgIds)) {
			return;
		}
		$ids = implode(', ', $msgIds);
		$q = mysql_query("
			UPDATE
				Message
			SET
				targetStatus = " . MSG_STATUS_DELETED . "
			WHERE
				targetId = $targetId AND
				id in ($ids);
		") or die(mysql_error());
		
		return;
	}
	
	public static function
	getNewCount($userId) {
		$q = mysql_query("SELECT count(*) as retCode FROM Message WHERE targetId = $userId and targetStatus = " . MSG_STATUS_UNREAD) or die(mysql_error());
		$r = mysql_fetch_object($q);
		if ($r->retCode > 0) {
			return $r->retCode;
		}
		return 0;
	}
	
	public static function
	getCount($userId) {
		$q = mysql_query("SELECT count(*) as retCode FROM Message WHERE targetId = $userId and targetStatus < " . MSG_STATUS_DELETED) or die(mysql_error());
		$r = mysql_fetch_object($q);
		if ($r->retCode > 0) {
			return $r->retCode;
		}
		return 0;
	}
}

?>
