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
require_once('scripts/Alliance.php');
require_once('scripts/AllianceBan.php');

new Privacy(PRIVACY_USER);

$t = new Template('alliance-banlist', intval($cgi['t']));

$filters = array(
	'remove'                => array(
		'filter'  => FILTER_VALIDATE_INT,
		'flags'   => FILTER_FORCE_ARRAY,
		'options' => array('min_range' => 0)
	),

	'alliance-submit'    => FILTER_SANITIZE_STRING,
);
$filtered = filter_input_array(INPUT_POST, $filters);

$t->alliance    = new Alliance();

if ($user->alliance) {
	$t->alliance->get($user->alliance);
	if (!$t->alliance->id or !$t->alliance->isLeader($user)) {
		header('Location: alliance-home.php');
		exit;
	}	
}
else {
	header('Location: alliance-list.php');
	exit;
}

if ($filtered['alliance-submit'] and $t->alliance->isLeader($user)) {
	
	if (count($filtered['remove'])) {
		AllianceBan::removeIds($t->alliance, $filtered['remove']);
	}
}


$t->banned = AllianceBan::getAll($t->alliance);

$t->pageTitle = 'Alliance Banned Users';
$t->display();
?>
