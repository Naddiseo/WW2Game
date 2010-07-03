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

new Privacy(PRIVACY_PRIVATE, 'battlefield-offline.php');

$t = new Template('battlefield', intval($cgi['t']));

$filter = array(
	'page'        => FILTER_VALIDATE_INT | FILTER_SANITIZE_NUMBER_INT,
	'search'      => FILTER_SANITIZE_STRING,
	'search-type' => FILTER_VALIDATE_INT | FILTER_SANITIZE_NUMBER_INT,
	'area'        => FILTER_VALIDATE_INT | FILTER_SANITIZE_NUMBER_INT,
);

$filteredG = filter_input_array(INPUT_GET, $filter);

if ($filteredG['area']) {
	$area      = $filteredG['area'] ? max(1, min($filteredG['area'], $conf['area-count'] + 1 )) : 1;
}
else {
	$area      = $user->area;
}

if ($area == $conf['area-count'] + 1) {
	$area = '*';
}

$t->page       = $filteredG['page'] ? max($filteredG['page'], 1) : 1;
$t->user       = $user;
$t->users      = User::getActiveUsers($t->page, 0, $filteredG['search'], $filteredG['search-type'], $area);
$t->usersCount = User::getActiveUsersCount(0, $filteredG['search'], $filteredG['search-type'], $area);
$t->totalPages = ceil($t->usersCount / $conf['users-per-page']);
$t->search     = $filteredG['search'     ];
$t->searchType = $filteredG['search-type'];
$t->area       = $area;
$t->pageTitle  = 'Battlefield';
$t->display();
?>
