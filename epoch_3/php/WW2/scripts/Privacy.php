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

define('PRIVACY_PUBLIC',  1);
define('PRIVACY_USER',    2);
define('PRIVACY_PRIVATE', 2);
define('PRIVACY_ADMIN', 3);

class Privacy {
	public function
	__construct($privacy = PRIVACY_PUBLIC) {
		
		if (
			(!$_SESSION[SESS_NAME] and $privacy == PRIVACY_USER) ||
			($privacy == PRIVACY_ADMIN && !$_SESSION['admin'])
		) {
			header('Location: index.php?e=1');
			exit;
		}
	}

	public static function
	isAdmin() {
		return $_SESSION['admin'] == true;
	}

	public static function
	assertAdmin() {
		if ($_SESSION[SESS_NAME] != 1) {
			header('Location: index.php?e=1');
			exit;
		}
	}
	
	public static function
	getId() {
		if ($_SESSION[SESS_NAME]) {
			return $_SESSION[SESS_NAME];
		}
		else {
			return 0;
		}
	}	
	
	public static function
	login($id) {
		$_SESSION[SESS_NAME] = $id;
	}
	
	public static function
	isIn() {
		return ($_SESSION[SESS_NAME] > 0);
	}
	
	public static function
	logout() {
		$_SESSION[SESS_NAME] = 0;
	}
}
?>
