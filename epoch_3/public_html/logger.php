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

require_once('scripts/Privacy.php');
require_once('env/env.php');
if (!$_SESSION['admin']) {

	
	$save = array(
		'time' => time(),
		'METHOD' => $_SERVER['REQUEST_METHOD'],
		'REFERRER' => $_SERVER['HTTP_REFERRER'],
		'POST'=> $_POST,
		'GET'=> $_GET,
		'IP' => $_SERVER['REMOTE_ADDR'],
		'REQUEST_URI' => $_SERVER['REQUEST_URI'],
		'UA' => $_SERVER['HTTP_USER_AGENT'],
		'RESTORE'=> $restore_string,
		'SID' => session_id(),
	);
	$s = serialize($save) . "\n";
	unset($save);
	
	$userid = Privacy::getId();
	
	$dir = BASEDIR . '/logger/' . GAME;
	$filename = "$userid.log." . LOGGER_V;
	
	if (is_dir($dir)) {
	
		$f = fopen("$dir/$filename", 'a');
		fwrite($f, $s);
		fclose($f);
	
	}
	else {
		mkdir($dir, 666, true);
		
		$f = fopen("$dir/$filename", 'a');
		fwrite($f, $s);
		fclose($f);
	}
}

?>
