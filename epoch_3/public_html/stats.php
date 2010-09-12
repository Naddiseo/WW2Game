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

require_once('scripts/vsys.php');
require_once('scripts/Template.php');

new Privacy(PRIVACY_PRIVATE, 'stats-offline.php');

$t = new Template('stats', intval($cgi['t']));


$filter = array(
	'uid' => FILTER_VALIDATE_INT,
	'mkcommander' => FILTER_SANITIZE_STRING,
	'e' => FILTER_SANITIZE_NUMBER_INT | FILTER_VALIDATE_INT,
);

$filteredG = filter_input_array(INPUT_GET, $filter);
$filtered  = filter_input_array(INPUT_POST, $filter);

$uid   = $filtered['uid'] ? $filtered['uid'] : ($filteredG['uid'] ? $filteredG['uid'] : 0);
$error = $filtered['e']   ? $filtered['e']   : ($filteredG['e']   ? $filteredG['e']   : 0);

$uid = max($uid, 0);

if (!$uid) {
	header('Location: base.php');
	exit;
}

$t->user = $user;

$t->target = getCachedUser($uid);

if ($filtered['mkcommander'] and $t->target->numofficers < $t->target->maxofficers) {
	$user->commander = $uid;
	$user->accepted = 0;
	$user->save();
}

switch ($error) {
	case 1:
		$t->err = 'You do not have enough attack turns';
		break;
	case 2:
		$t->err = 'You cannot attack yourself';
		break;
	case 3:
		$t->err = 'You have reached your maximum attacking potential on this user';
		break;
	case 4:
		$t->err = 'There was an error attacking, please try again.';
		break;
	case 5:
		$t->err = 'You do not have enough gold.';
		break;
	case 6:
		$t->err = 'You do not have enough spies.';
		break;
	case 7:
		$t->err = 'This player is not in your area.';
		break;
	case 8:
		$t->err = 'You cannot attack this player';
	default:
		// do nothing
}

$t->pageTitle = 'User Stats';
$t->display();
?>
