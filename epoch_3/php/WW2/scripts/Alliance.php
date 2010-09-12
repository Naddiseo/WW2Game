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

class Alliance extends BaseClass {
	public
		$id           = 0,
		$name         = '',
		$password     = '', // For leaders to resurrect an alliance
		$tag          = '', // 8 chars
		$leaderId1    = 0,
		$leaderId2    = 0,
		$leaderId3    = 0,
		$rank         = 0,  // alliance rank
		$creationdate = 0, 
		$status       = 0,  // 0 = open, 1 = closed, 2 = dead
		$url          = '', // Forum url or something
		$irc          = '', // IRC channel
		$ircServer    = '', // The irc server
		$message      = '', // Joining message
		$UP           = 0,  // Bonus UP
		$gold         = 0,
		$donated      = 0.0,
		$usedcash     = 0.0,
		$tax          = 0.0,
		$SA           = 0.0,
		$DA           = 0.0,
		$CA           = 0.0,
		$RA           = 0.0,
		$news         = '';
		

	public function
	getNameHTML() {
		if ($this->active == 2) {
			return '{' . htmlentities($this->name, ENT_QUOTES, 'UTF-8') . '}';
		}
		return htmlentities($this->name, ENT_QUOTES, 'UTF-8');
	}
	
	public function
	getTag() {
		return '[' . $this->tag . ']';
	}
	
	public function
	getTagLink() {
		return "<a href='alliance-home.php?uid";
	}
	
	public function
	getLeader($n = 1) {
		$ret = null;
		switch ($n) {
			case 2:
				$ret = getCachedUser($this->leaderId2);
				break;
			case 3:
				$ret = getCachedUser($this->leaderId3);
				break;
			default:
			case 1:
				$ret = getCachedUser($this->leaderId1);
				break;
				
		}
		
		return $ret;
	}
	
	public function
	hasFullLeaders()  {
		return (
			$this->leaderId1 > 0 and
			$this->leaderId2 > 0 and
			$this->leaderId3 > 0
		);
	}
	
	public function
	hasNullLeaders() {
		return (
			$this->leaderId1 == 0 and
			$this->leaderId2 == 0 and
			$this->leaderId3 == 0
		);
	}
	
	public function
	getFreeNumber() {
		$ret = null;
		
		if ($this->leaderId1 == 0) {
			$ret = 1;
		}
		else if ($this->leaderId2 == 0) {
			$ret = 2;
		}
		else if ($this->leaderID3 == 0) {
			$ret = 3;
		}
		
		return ret;
	}
	
	public function
	addMember(User $u, $accept = 0) {
		if ($this->status != 0) {
			return;
		}
		$u->alliance = $this->id;
		$u->aaccepted = $accept;
		$u->save();

	}
	
	public function
	addLeader(User $u) {
		$n = $this->getFreeNumber();
		if ($n) {
			$f = "leaderId$n";
			$this->$f = $u->id;
			$this->addMember($u, 1);
		}
		
		return $n;
	}
	
	public function 
	getURL() {
		$ret = '{NONE}';
		if (strlen($this->url) > 4) {
			$ret = $this->url;
			if (strpos($ret, 'http://') === false) {
				$ret = 'http://' . $ret;
			}
		}
		
		return $ret;
	}
	
	public function
	getURLLink() {
		$ret = $this->getURL();
		if ($ret != '{NONE}') {
			$ret = sprintf('<a href="%s" title="%s">%s</a>', $ret, $this->getNameHTML(), $ret);
		}
		return $ret;
	}
	
	public function
	getMembers() {
		$ret = array();
		$q = mysql_query("SELECT * FROM User WHERE Alliance = $this->id and active = 1") or die(mysql_error());
		while ($r = mysql_fetch_object($q, 'User')) {
			$ret[] = $r;
		}
		return $ret; 
	}
	
	public function
	getChannel() {
		$ret = '#ww2';
		if ($this->irc) {
			$ret = $this->irc;
		}
		return $ret;
	}
	
	public function
	getServer() {
		$ret = 'irc.cyanide-x.net';
		if ($this->ircServer) {
			$ret = $this->ircServer;
		}
		return $ret;
	}
	
	public function
	getIRCLink() {
		$channel = $this->getChannel();
		$server  = $this->getServer();
		
		$ret = sprintf(
			'<a href="irc://%s/%s" title="IRC Chat">irc://%s/%s</a>', 
			$server, urlencode($channel), 
			$server,
			$channel
		);
		
		return $ret;
	}
	
	public function
	getNews() {
		$ret = _('No news at this time');
		if ($this->news) {
			$ret = $this->news;
		}
		return $ret;
	}
	
	public function
	getWelcome() {
		$ret = _('Welcome new members');
		if ($this->message) {
			$ret = $this->message;
		}
		return $ret;
	}
	
	public function
	isLeader(User $u) {
		return (
			$u->id == $this->leaderId1 or
			$u->id == $this->leaderId2 or
			$u->id == $this->leaderId3
		);
	}
	
	public function 
	isDead (User $u) {
		$nullIds = $this->hasNullLeaders();
	
		$status = false;
		
		if ($this->status == 2 and $this->isLeader($u)) {
			$status = true;
		}
		# No leaders, or closed.
		return $status or $nullIds;
	}

	// Statics
	public static function
	queryNameTag($name, $tag) {
		$ret = array();
		
		$name = mysql_real_escape_string($name);
		$tag  = mysql_real_escape_string($tag);
		$q = mysql_query("SELECT * FROM Alliance WHERE name like \"$name\" or tag like \"$tag\"") or die(mysql_error());
		while ($r = mysql_fetch_object($q, 'Alliance')) {
			$ret[] = $r;
		}
		
		return $ret;
	}
	
	public static function
	queryNameTagCount($name, $tag) {
		$name = mysql_real_escape_string($name);
		$tag  = mysql_real_escape_string($tag);
		$q = mysql_query("SELECT count(*) as retCode FROM Alliance WHERE name like \"$name\" or tag like \"$tag\"") or die(mysql_error());
		$r = mysql_fetch_object($q);
		if ($r->retCode) {
			return $r->retCode;
		}
		return 0;
	}

	public static function
	getAll() {
		$ret = array();
		$q = mysql_query("SELECT * FROM Alliance WHERE status < 2 ORDER BY id asc") or die(mysql_error());
		while ($r = mysql_fetch_object($q, 'Alliance')) {
			$ret[] = $r;
		}
		return $ret;
	}
}
?>
