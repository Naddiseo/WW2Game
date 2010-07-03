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

require_once('scripts/vsys.php');
require_once('scripts/Template.php');

new Privacy(PRIVACY_PRIVATE);

$t = new Template('attacklog', intval($cgi['t']));


$filter = array(
	'defense-page' => FILTER_VALIDATE_INT | FILTER_SANITIZE_NUMBER_INT,
	'attack-page'  => FILTER_VALIDATE_INT | FILTER_SANITIZE_NUMBER_INT,
	'e'            => FILTER_VALIDATE_INT | FILTER_SANITIZE_NUMBER_INT,
);

$filtered = filter_input_array(INPUT_GET, $filter);

switch($filtered['e']) {
	case '1':
		$t->err = 'You cannot view other people\'s battle logs';
		break;
	
	default:
}

$t->user = $user;

$t->dpage = max($filtered['defense-page'], 1);
$t->defenseLogs = $user->getDefenseLogs($t->dpage);
$t->totaldAttacks = $user->getDefenseLogsCount();
$t->totalDPages = ceil($t->totaldAttacks / $conf['attacks-per-page']);

$t->apage = max($filtered['attack-page'], 1);
$t->attackLogs = $user->getAttackLogs($t->apage);
$t->totalAttacks = $user->getAttackLogsCount();
$t->totalPages = ceil($t->totalAttacks / $conf['attacks-per-page']);

$t->css = true;
$t->js = false;

$t->pageTitle = 'Attack Log';
$t->display();
?>
