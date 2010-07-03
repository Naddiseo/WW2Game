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

$t = new Template('alliance-home', intval($cgi['t']));

$filters = array(
	'leave-alliance'        => FILTER_SANITIZE_STRING,
	'join'                  => FILTER_VALIDATE_INT,
	
	'alliance-shout'        => FILTER_SANITIZE_STRING,
	'alliance-shout-submit' => FILTER_SANITIZE_STRING,
);
$filtered  = filter_input_array(INPUT_GET, $filters);
$filteredP = filter_input_array(INPUT_POST, $filters);

$t->alliance    = new Alliance();
$t->shouts      = array();

if ($user->alliance) {
	$t->alliance->get($user->alliance);
	
	if ($t->alliance->id) {
		$t->shouts = AllianceShout::getShouts($t->alliance);
	}
	
}
else if ($filtered['join']) {
	$t->alliance->get($filtered['join']);
	if ($t->alliance->id) {
		if (!AllianceBan::isBlocked($t->alliance, $user)) {
			$t->alliance->addMember($user);
		}
		else {
			$t->err = 'You are blocked from joining that alliance';
		}
	}
}
else {
	header('Location: alliance-list.php');
	exit;
}

if ($filtered['leave-alliance']) {
	$user->alliance = 0;
	$user->aaccepted = 0;
	$user->save();
	header('Location: alliance-list.php');
	exit;
}

if ($filteredP['alliance-shout-submit'] and $filteredP['alliance-shout']) {
	if ($t->alliance->id and $user->aaccepted and $user->alliance == $t->alliance->id) {
		$s = new AllianceShout();
		$s->allianceId = $t->alliance->id;
		$s->userId     = $user->id;
		$s->message    = substr($filteredP['alliance-shout'],0, 160);
		$s->date       = time();
		$s->create();
		header('Location: alliance-home.php');
		exit;
	}
}


$t->css = true;
$t->pageTitle = 'Alliance Home';
$t->display();
?>
