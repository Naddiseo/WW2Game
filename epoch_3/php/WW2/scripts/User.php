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


require_once('conf.php');
require_once('Weapon.php');
require_once('Alliance.php');
require_once('BattleLog.php');
require_once('SpyLog.php');

class User extends BaseClass {
	public
		$id = 0,
		$username = 'None',
		$nation = 0,
		$email = '',
		$password = '',
		$key = '',
		$gclick = 15,
		$commander = 0,
		$active = 0,
		$area   = 3,
		$dalevel = 0,
		$salevel = 0,
		$gold = 50000,
		$bank = 0,
		$primary = 0,
		$attackturns = 50,
		$up = 0,
		$calevel = 0,
		$ralevel = 0,
		$maxofficers = 5,
		$sasoldiers = 75,
		$samercs = 0,
		$dasoldiers = 75,
		$damercs = 0,
		$uu = 0,
		$spies = 0,
		$lastturntime = 0,
		$vacation     = 0,
		$accepted = 0,
		$commandergold = 0,
		$gameSkill = 0,
		$specialforces = 0,
		$bankper = 10,
		$SA = 0,
		$DA = 0,
		$CA = 0,
		$RA = 0,
		$rank = 0,
		$sarank = 0,
		$darank = 0,
		$carank = 0,
		$rarank = 0,
		$alliance = 0,
		$hhlevel = 0,
		$officerup = 0,
		$changenick = 0,
		$admin = 0,
		$clicks = 0,
		$supporter = 0,
		$reason = '',
		$clickall = 0,
		$bankimg = 1,
		$cheatcount = 0,
		$status = '',
		$numofficers = 0,
		$irc = 0,
		$ircstatus = '',
		$ircnick = '',
		$currentIP = '',
		$unreadMsg = 0,
		$msgCount  = 0,
		$aaccepted = 0,
		$referrer = 0,
		$ircpass = '',
		$minattack = 0,
		$htmlColour = '';

	private
		$_cache = array();
		
	
	public function
	getByKey($id, $k) {
		$k   = mysql_real_escape_string($k);
		$id  = intval($id);
		
		$r   = mysql_query("SELECT * FROM User WHERE id='$id' and `key`=\"$k\" limit 1") or die(mysql_error());
		$obj = mysql_fetch_object($r, 'User');
		return $obj;
	}

	public function
	getByEmail($email) {
		$email = mysql_real_escape_string($email);
		$r = mysql_query("SELECT * FROM User WHERE email = \"$email\" LIMIT 1") or die(mysql_error());
		$a = mysql_fetch_array($r, MYSQL_ASSOC);
		if ($a['id']) {
			foreach ($a as $key => $value) {
				$this->$key = $value;
			}
		}
		return $this;
	}

	public function
	getNameHTML() {
		if ($this->active != 1) {
			return '{' . htmlentities($this->username, ENT_QUOTES, 'UTF-8') . '}';
		}
		return htmlentities($this->username, ENT_QUOTES, 'UTF-8');
	}
	
	public function
	getNameLink($title = '', $offline = false) {
		if ($this->active != 1) {
			return '{' . htmlentities($this->username, ENT_QUOTES, 'UTF-8') . '}';
		}
		$style   = ($this->htmlColour ? 'style="color: #' . $this->htmlColour . '"' : '');
		$title   = $title ? "title=\"$title\"" : '';
		$offline = $offline ? '-offline' : '';
		return "<a href=\"stats$offline.php?uid={$this->id}\" $title $style>" . htmlentities($this->username, ENT_QUOTES, 'UTF-8') . "</a>";
	}
	
	public function
	getNameRecruit($title = '') {
		if ($this->active != 1) {
			return '{' . htmlentities($this->username, ENT_QUOTES, 'UTF-8') . '}';
		}
		$style = ($this->htmlColour ? 'style="color: #' . $this->htmlColour . '"' : '');
		$title = $title ? "title=\"$title\"" : '';
		return "<a href=\"recruit.php?uid={$this->id}\" $title $style>" . htmlentities($this->username, ENT_QUOTES, 'UTF-8') . "</a>";
	}


	public function
	getSupport($type) {
		switch ($type) {
			case 'combined-gold':
			case 'upgrades':
			case 'quick-attack':
				return $this->supporter >= 5;
				break;
			case 'minhit':
			case 'attacklog-info':
			case 'theft-calc':
				return $this->supporter > 0;
							
			default:
				return false;
		}
		return false;
	}

	public function
	getTFF() {
		return
			$this->sasoldiers +
			$this->samercs +
			$this->dasoldiers +
			$this->damercs +
			$this->spies +
			$this->specialforces +
			$this->uu;
	}

	public function
	getIncome() {
		global $conf;

		$income = 0;
		
		if ($this->active != 1) {
			return $income;
		}
		
		$income += $this->sasoldiers;
		$income += $this->dasoldiers;
		$income += $this->uu;


		$income *= $conf['gps'][$this->nation];
		
		return $income;
	}

	public function
	getOfficerUP() {
		return $this->officerup;
	}

	public function
	getAllianceUP() {
		return $this->aaccepted ? $this->getAlliance()->UP : 0;
	}

	public function
	getWeaponAlloacation($isAttack, $count = null) {

		$ret = array(
			'trained'   => array(
				'weapons'   => 0,
				'noweapons' => 0,
			),
			'mercs'     => array(
				'weapons'   => 0,
				'noweapons' => 0,
			),
			'untrained' => array(
				'weapons'   => 0,
				'noweapons' => 0,
			),
		);

		$weaponCount = $count;

		// If the count isn't provided, grab it
		if ($weaponCount == null) {

			$weaponCount = $isAttack ?
				Weapon::getUserAttackWeaponsCount($this->id) :
				Weapon::getUserDefenseWeaponsCount($this->id);
		}

		$trainedCount   = $isAttack ? $this->sasoldiers : $this->dasoldiers;
		$mercCount      = $isAttack ? $this->samercs    : $this->damercs;
		$untrainedCount = $this->uu;

		// Can't have a merc-only army
		if ($trainedCount == 0) {
			// they'll be unarmed
			$ret['mercs']['noweapons'] = $mercCount;
			$mercCount = 0;
		}
		// Mercs can only be a max of 30% of the trained force
		if ($mercCount > $thirty = floor($trainedCount * 0.3)) {
			$ret['mercs']['noweapons'] = $mercCount - $thirty;
			$mercCount = $thirty;
		}

		// There are more weapons than trained soldiers,
		// so all soldiers will have weapons
		if ($weaponCount > $trainedCount) {
			$ret['trained']['weapons'  ] = $trainedCount;
			$weaponCount -= $trainedCount;
		}
		// Not enough weapons for any mercs or untrained
		else {
			$ret['trained']['weapons'  ] = $weaponCount;
			$ret['trained']['noweapons'] = $trainedCount - $weaponCount;
			$weaponCount = 0;
		}

		// There's enough weapons for the mercs
		if ($weaponCount > $mercCount) {
			$ret['mercs']['weapons'  ]   = $mercCount;
			$weaponCount -= $mercCount;
		}
		// only some or none of the mercs will have weapons
		else {
			$ret['mercs']['weapons'  ] = $weaponCount;
			$ret['mercs']['noweapons'] = $mercCount - $weaponCount;
			$weaponCount = 0;
		}

		// There's enough for the untrained
		if ($weaponCount > $untrainedCount) {
			$ret['untrained']['weapons'] = $untrainedCount;
		}
		// some or none of the untrained will have weapons
		else {
			$ret['untrained']['weapons'  ] = $weaponCount;
			$ret['untrained']['noweapons'] = $untrainedCount - $weaponCount;
		}

		return $ret;
	}

	public function
	getSA() {
		global $conf;
		
		$ret = 0;

		/// TODO: add alliance stuff

		// get some stuff
		$ratio = Weapon::getStrengthRatio($this->id, 1);
		$alloc = $this->getWeaponAlloacation(true, $ratio->total);

		// apply the weapon ratios to the units with weapons
		$alloc['mercs'    ]['weapons']   *= 50 * $ratio->ratio;
		$alloc['trained'  ]['weapons']   *= 20 * $ratio->ratio;
		$alloc['untrained']['weapons']   *= 3  * $ratio->ratio;

		// apply hand-to-hand to the units without weapons
		$alloc['mercs'    ]['noweapons'] *= $this->hhlevel * 10;
		$alloc['trained'  ]['noweapons'] *= $this->hhlevel * 25;
		$alloc['untrained']['noweapons'] *= $this->hhlevel * 15;

		// and sum them.
		$sum =
			$alloc['mercs'    ]['weapons'  ] +
			$alloc['trained'  ]['weapons'  ] +
			$alloc['untrained']['weapons'  ] +
			$alloc['mercs'    ]['noweapons'] +
			$alloc['trained'  ]['noweapons'] +
			$alloc['untrained']['noweapons'];

		// apply the sa level
		$ret = $sum * pow(1.25, $this->salevel + 1);
		// apply nation bonus
		$ret *= (1 + $conf["sabonus{$this->nation}"]);

		// finally add the units
		$ret += $this->sasoldiers + $this->samercs + $this->uu;
	
		return round($ret);
	}

	public function
	getSAName() {
		global $conf;
		return $conf['names']['upgrades'][1][$this->salevel];
	}

	public function
	getDA() {
		global $conf;
		
		$ret = 0;

		/// TODO: add alliance stuff

		// get some stuff
		$ratio = Weapon::getStrengthRatio($this->id, 0);
		$alloc = $this->getWeaponAlloacation(false, $ratio->total);

		// apply the weapon ratios to the units with weapons
		$alloc['mercs'    ]['weapons']   *= 50 * $ratio->ratio;
		$alloc['trained'  ]['weapons']   *= 20 * $ratio->ratio;
		$alloc['untrained']['weapons']   *= 3  * $ratio->ratio;

		// apply hand-to-hand to the units without weapons
		$alloc['mercs'    ]['noweapons'] *= $this->hhlevel * 10;
		$alloc['trained'  ]['noweapons'] *= $this->hhlevel * 25;
		$alloc['untrained']['noweapons'] *= $this->hhlevel * 15;

		// and sum them.
		$sum =
			$alloc['mercs'    ]['weapons'  ] +
			$alloc['trained'  ]['weapons'  ] +
			$alloc['untrained']['weapons'  ] +
			$alloc['mercs'    ]['noweapons'] +
			$alloc['trained'  ]['noweapons'] +
			$alloc['untrained']['noweapons'];

		// apply the da level
		$ret = $sum * pow(1.25, $this->dalevel + 1);
		// apply nation bonus
		$ret *= (1 + $conf["dabonus{$this->nation}"]);

		// finally add the units
		$ret += $this->dasoldiers + $this->damercs + $this->uu;
	
		return round($ret);
	}
	
	public function
	getDAName() {
		global $conf;
		return $conf['names']['upgrades'][0][$this->dalevel];
	}

	public function
	getCA() {
		global $conf;
		$ret = 0;

		if (!$this->spies) {
			return 0;
		}

		/// TODO: alliance bonuses
		
		$ret = ($this->spies * 0.01) * pow(2, $this->calevel) * (1 + $conf["cabonus{$this->nation}"]);
		
		return round($ret) + $this->spies;
	}

	public function
	getRA() {
		global $conf;
		$ret = 0;

		if (!$this->specialforces) {
			return 0;
		}

		///TODO: alliance bonuses
		$ret = pow(2.4, $this->ralevel) * ($this->specialforces * 0.0055) * (1 + $conf["rabonus{$this->nation}"]);

		return round($ret) + $this->specialforces;
	}

	public function
	cacheStats() {
		$this->SA = $this->getSA();
		$this->DA = $this->getDA();
		$this->CA = $this->getCA();
		$this->RA = $this->getRA();
		$this->save();
	}

	public function
	getOfficersCount() {
		$q = mysql_query("SELECT count(*) as retCode FROM User where commander = $this->id") or die(mysql_error());
		$r = mysql_fetch_object($q);

		return $r->retCode;
	}

	public function
	getOfficers($page = 0) {
		global $conf;
		$ret = array();

		if ($this->_cache['officers']) {
			$ret = $this->_cache['officers'];
		}
		else {
			$pageSQL = '';
			
			if ($page) {
				$page = max(0, $page - 1) * $conf['officers-per-page'];
				$limit = $conf['officers-per-page'];
				$pageSQL = " LIMIT $page, $limit ";
			}

			$q = mysql_query("SELECT * FROM User where commander = $this->id ORDER BY RANK ASC  $pageSQL") or die(mysql_error());
			while ($u = mysql_fetch_object($q, 'User')) {
				$ret[] = $u;
			}
			$this->_cache['officers'] = $ret;
		}

		return $ret;
	}

	public function
	getCommander() {
		$ret = $this->_cache['commander'];
		if (!$ret and $this->commander) {
			$this->_cache['commander'] = $ret = new User();
			$ret->get($this->commander);
		}
		return $ret;
	}

	public function
	getAlliance() {
		$ret = null;
		if (!$ret and $this->alliance) {
			$ret = getCachedAlliance($this->alliance);
		}
		return $ret;
	}

	public function
	getNation() {
		switch ($this->nation) {
			case 0:
				return 'USA';
			case 1:
				return 'Britain';
			case 2:
				return 'Japan';
			case 3:
				return 'Germany';
			case 4:
				return 'USSR';
		}
	}
	
	public function
	getNationFlag() {
		return 'nation' . $this->nation . '.gif';
	}
	
	public function
	getArea() {
		return $this->area;
	}
	
	public function
	getAreaName() {
		global $conf;
		return $conf['area'][$this->area]['name'];
	}
	
	public function
	getAreaNameShort() {
		global $conf;
		return $conf['area'][$this->area]['short-name'];
	}

	public function
	canBuy($amount) {
		if ($this->getSupport('combined-gold')) {
			$total = $this->gold + $this->bank;
			if ($total >= $amount) {
				// Use gold first
				if ($this->gold >= $amount) {
					return true;
				}
				else {
					$amount -= $this->gold;
					return true;
				}
			}
		}
		else if ($this->primary == 1) {
			if ($this->bank >= $amount) {
				return true;
			}
		}
		else {
			if ($this->gold >= $amount) {
				return true;
			}
		}
		// doesn't have enough
		return false;
	}

	public function
	buy($amount, $what = NULL, $delta = NULL) {
		if ($this->getSupport('combined-gold')) {
			$total = $this->gold + $this->bank;
			if ($total >= $amount) {
				// Use gold first
				if ($this->gold >= $amount) {
					$this->gold -= $amount;
					if ($what) {
						$this->$what += $delta;
					}
					return true;
				}
				else {
					$amount -= $this->gold;
					$this->gold = 0;
					$this->bank -= $amount;
					if ($what) {
						$this->$what += $delta;
					}
					return true;
				}
			}
		}
		else if ($this->primary == 1) {
			if ($this->bank >= $amount) {
				$this->bank -= $amount;
				if ($what) {
					$this->$what += $delta;
				}
				return true;
			}
		}
		else {
			if ($this->gold >= $amount) {
				$this->gold -= $amount;
				if ($what) {
					$this->$what += $delta;
				}
				return true;
			}
		}
		// doesn't have enough
		return false;
	}

	public function
	getPrimary() {
		if ($this->getSupport('combined-gold')) {
			return $this->bank + $this->gold;
		}
		else {
			return $this->primary ? $this->bank : $this->gold;
		}
	}

	public function
	getSecondary() {
		if ($this->getSupport('combined-gold')) {
			return $this->bank + $this->gold;
		}
		else {
			return $this->primary == 0 ? $this->bank : $this->gold;
		}
	}

	public function
	getDefenseLogs($page = NULL) {
		return BattleLog::getDefenseLogs($this->id, $page);
	}
	
	public function
	getDefenseLogsCount() {
		return BattleLog::getDefenseLogsCount($this->id);
	}

	public function
	getAttackLogs($page = NULL) {
		return BattleLog::getAttackLogs($this->id, $page);
	}
	
	public function
	getAttackLogsCount() {
		return BattleLog::getAttackLogsCount($this->id);
	}


	public function
	getSpyDefenseLogs($page = NULL) {
		return SpyLog::getDefenseLogs($this->id, $page);
	}
	
	public function
	getSpyDefenseLogsCount() {
		return SpyLog::getDefenseLogsCount($this->id);
	}

	public function
	getSpyAttackLogs($page = NULL) {
		return SpyLog::getAttackLogs($this->id, $page);
	}
	
	public function
	getSpyAttackLogsCount() {
		return SpyLog::getAttackLogsCount($this->id);
	}

	public function
	canSpyOn($target) {
		return ($this->CA * rand(80, 100) * 0.01 > $target->CA) and $target->area == $this->area;
	}

	// ==== Statics
	
	public static function
	login($username, $password) {
		$ret->id = false;
		$password = md5($password);
		$ori_username = $username;

		if (strlen($username) <= 25 and strlen($username) >= 3) {

			$username = mysql_real_escape_string($username);
			$r = mysql_query("select * from User where username LIKE \"$username\" and password =\"$password\";") or die(mysql_error());
			$ret = mysql_fetch_object($r, 'User');
			if (!$ret) {
				require_once('Activation.php');
				
				// check activations				
				$a = Activation::getByUsernamePassword($ori_username, $password);
				if ($a->id and $a->success == 0) {
					header('Location: activate.php?activation-id=' . $a->id);
					exit;
				}
				
				$ret->id = false;
			}
			return $ret;
		}
		return $ret;
	}
	
	public static function
	getActiveUsers($page = NULL, $allowUnranked = true, $search = NULL, $searchType = NULL, $area = NULL) {
		global $conf;
		$ret = array();

		$pageSQL = '';
		$where = '';

		if ($page) {
			$page = max(0, $page - 1) * $conf['users-per-page'];
			$limit = $conf['users-per-page'];
			$pageSQL = " LIMIT $page, $limit ";
		}

		if (!$allowUnranked) {
			$where = ' AND rank > 0 ';
		}
		
		if ($search and $searchType) {
			$search = mysql_real_escape_string($search);
			switch ($searchType) {
				case 1:
					$where .= " AND username LIKE \"$search%\" ";
					break;
				case 2:
					$where .= " AND username LIKE \"%$search\" ";
					break;
				default:
				case 3:
					$where .= " AND username LIKE \"%$search%\" ";
					break;
			}
		}
		
		if ($area and $area != '*') {
			$where .= " AND area = $area ";
		}
		
		$q = mysql_query("SELECT * FROM User WHERE active = 1 $where ORDER BY rank ASC $pageSQL") or die(mysql_error());
		while ($u = mysql_fetch_object($q, 'User')) {
			$ret[] = $u;
		}

		return $ret;
	}

	public static function
	getActiveUsersCount($allowUnranked = true, $search = NULL, $searchType = NULL, $area = NULL) {
		global $conf;
		$ret = 0;

		$where = '';

		if (!$allowUnranked) {
			$where = ' AND rank > 0 ';
		}
		
		if ($search and $searchType) {
			$search = mysql_real_escape_string($search);
			switch ($searchType) {
				case 1:
					$where .= " AND username LIKE \"$search%\" ";
					break;
				case 2:
					$where .= " AND username LIKE \"%$search\" ";
					break;
				default:
				case 3:
					$where .= " AND username LIKE \"%$search%\" ";
					break;
			}
		}
		
		if ($area and $area != '*') {
			$where .= " AND area = $area ";
		}

		$q = mysql_query("SELECT count(*) as retCode FROM User WHERE active = 1 $where ORDER BY rank ASC") or die(mysql_error());
		$ret = mysql_fetch_object($q);
		return $ret->retCode;
	}

	public static function
	getOnlineUsersCount($allowUnranked = true) {
		global $conf;
		$time = time() - $conf["minutes_per_turn"] * 60;

		$where = '';
		
		if (!$allowUnranked) {
			$where = ' AND rank > 0 ';
		}

		$str = "SELECT COUNT(*) as retCode FROM `User` where lastturntime>'$time' and active='1' $where";

		$q = @mysql_query($str) or die(mysql_error());
		if ($q) {
			$st = mysql_fetch_object($q);
			return $st->retCode;
		}
		
		return 0;
	}
	
	public static function
	getOnlineUsers($areaSort = false){
		global $conf;
		$ret = array();

		$time = time() - $conf["minutes_per_turn"] * 60;
		
		if ($areaSort) {
			$str = "SELECT *  FROM User where lastturntime>'$time' and active='1' order by area, rank asc";
		}
		else {
			$str = "SELECT * FROM User where lastturntime>'$time' and active='1' ORDER BY rank ASC";
		}
		$q = @mysql_query($str) or die(mysql_error());
		while ($u = mysql_fetch_object($q, 'User')) {
			$ret[] = $u;
		}
		return $ret;
	}

	public static function
	setActive($userId, $active) {
		mysql_query("update User set active=$active where id = $userId LIMIT 1") or die(mysql_error());
	}
	
	public static function
	getByUsernameEmailCount($username, $email) {
		$username = mysql_real_escape_string($username);
		$email    = mysql_real_escape_string($email);
		$r = mysql_query("select count(*) as retCode from User where (username LIKE \"$username\" or email LIKE \"$email\")") or die(mysql_error());
		$ret = mysql_fetch_object($r);
		return $ret->retCode;
	}
	
	public static function
	validateUsername($username) {
		$l = strlen($username);
		
		return ($l <= 25 and $l >= 3);
	}
	
	public static function
	incrMailForIds(array $ids = array()) {
		if (count($ids) > 0) {
			$ids = implode(',', array_unique($ids));
			mysql_query("UPDATE User set msgCount = msgCount + 1, unreadMsg = unreadMsg + 1 WHERE id in ($ids)") or die(mysql_error());
		}
	}
	
	public static function
	queryIDByUsername($username) {
		$username = mysql_real_escape_string($username);
		$q = mysql_query("SELECT * from User WHERE active = 1 and username like \"$username%\" ORDER BY username ASC LIMIT 5") or die(mysql_error());
		$ret = array();
		while ($r = mysql_fetch_object($q, 'User')) {
			$ret [] = $r;
		}
		return $ret;
	}
	
	public static function
	queryIDByAlliance($alliance) {
		$alliance = mysql_real_escape_string($alliance);
		$q = mysql_query("SELECT * from User WHERE active = 1 and alliance = $alliance ORDER BY id ASC") or die(mysql_error());
		$ret = array();
		while ($r = mysql_fetch_object($q, 'User')) {
			$ret [] = $r;
		}
		return $ret;
	}
	
	public static function
	queryIDByCommanderId($id) {
		$id = mysql_real_escape_string($id);
		$q = mysql_query("SELECT * from User WHERE active = 1 and commander = $id ORDER BY id ASC") or die(mysql_error());
		$ret = array();
		while ($r = mysql_fetch_object($q, 'User')) {
			$ret [] = $r;
		}
		return $ret;
	}
	
	public static function
	searchUsernameEmailIP($username, $email, $ip, $oa = 'and') {
		$ret = array();
		
		$username = mysql_real_escape_string($username);
		$email    = mysql_real_escape_string($email);
		$ip       = mysql_real_escape_string($ip);
		
		$q = mysql_query("SELECT * FROM User WHERE username LIKE \"$username\" $oa email LIKE \"$email\" $oa currentip LIKE \"$ip\" ORDER BY id asc") or die(mysql_error());
		while ($r = mysql_fetch_object($q, 'User')) {
			$ret[] = $r;
		}	
		
		return $ret;
	}
}
?>
