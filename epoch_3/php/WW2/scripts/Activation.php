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

class Activation extends BaseClass {
	public
		$id = 0,
		$username   = '',
		$email      = '',
		$password   = '',
		$nation     = 0,
		$IP         = '',
		$success    = 0,
		$userId     = 0,
		$referrerId = 0,
		$time       = 0;

	public function
	getByEmail($email) {
		$email = mysql_real_escape_string($email);
		$r = mysql_query("SELECT * FROM Activation WHERE email = \"$email\" LIMIT 1") or die(mysql_error());
		$a = mysql_fetch_array($r, MYSQL_ASSOC);
		if ($a['id']) {
			foreach ($a as $key => $value) {
				$this->$key = $value;
			}
		}
		return $this;
	}
	
	public static function
	getByUsernamePassword($username, $password, $md5 = 0) {
		$ret->id = false;
		$ret->success = 0;
		if ($md5) {
			$password = md5($password);
		}
		$l = strlen($username);
		
		if ($l >= 2 and $l <= 25) {
			$username = mysql_real_escape_string($username);

			$r = mysql_query("select * from Activation where username LIKE \"$username\" and password = \"$password\";") or die(mysql_error());
			$ret = mysql_fetch_object($r, 'Activation');
			if (!$ret) {
				$ret->id = false;
			}
			return $ret;
		}
		
		return $ret;
	}
	
	public static function
	getByUsernameEmailCount($username, $email) {
		$username = mysql_real_escape_string($username);
		$email = mysql_real_escape_string($email);
		$r = mysql_query("select count(*) as retCode from Activation where (username LIKE \"$username\" or email LIKE \"$email\") and success = 0") or die(mysql_error());
		$ret = mysql_fetch_object($r);
		return $ret->retCode;
	}
	
	/* Tricky function
		Have to check to see if OTHER people have the username or email and not me
	*/
	public static function
	checkUsernameEmailAndNotMe($userId, $username, $email) {
		$username = mysql_real_escape_string($username);
		$email = mysql_real_escape_string($email);
		$r = mysql_query("select count(*) as retCode from Activation where (username LIKE \"$username\" or email LIKE \"$email\") and userId != $userId") or die(mysql_error());
		$ret = mysql_fetch_object($r);
		return $ret->retCode;
	}
	
	public static function
	searchUsernameEmailIp($username, $email, $ip, $oa = 'AND') {
		$ret = array();
	
		$username = mysql_real_escape_string($username);
		$email    = mysql_real_escape_string($email);
		$ip       = mysql_real_escape_string($ip);
		
		$q = mysql_query("SELECT * FROM Activation WHERE username LIKE \"$username\" $oa email LIKE \"$email\" $oa ip LIKE \"$ip\" ORDER BY id asc") or die(mysql_error());
		while ($r = mysql_fetch_object($q, 'Activation')) {
			$ret[] = $r;
		}	
		
		return $ret;
	}
	
}
?>
