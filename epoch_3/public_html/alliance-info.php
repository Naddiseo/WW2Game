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
require_once('scripts/Alliance.php');
require_once('scripts/AllianceBan.php');
require_once('scripts/AllianceShout.php');

new Privacy(PRIVACY_USER);

$t = new Template('alliance-info', intval($cgi['t']));

$filters = array(
	'aid'                  => FILTER_VALIDATE_INT,
);
$filtered  = filter_input_array(INPUT_GET, $filters);

$t->alliance    = new Alliance();
$t->alliance->get($filtered['aid']);
if (!$t->alliance->id) {
	header('Location: alliance-list.php');
	exit;
}



$t->css = true;
$t->pageTitle = 'Alliance Home';
$t->display();
?>
