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

new Privacy(PRIVACY_PUBLIC);

$t = new Template('stats-offline', intval($cgi['t']));


$filter = array(
	'uid' => FILTER_VALIDATE_INT,
);

$filteredG = filter_input_array(INPUT_GET, $filter);
$filtered  = filter_input_array(INPUT_POST, $filter);

$uid   = $filtered['uid'] ? $filtered['uid'] : ($filteredG['uid'] ? $filteredG['uid'] : 0);

$uid = max($uid, 0);

if (!$uid) {
	header('Location: battlefield-offline.php');
	exit;
}

$t->user = $user;

$t->target = getCachedUser($uid);



$t->pageTitle = 'User Stats';
$t->display();
?>
