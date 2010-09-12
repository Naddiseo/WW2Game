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

$t = new Template('alliance-members', intval($cgi['t']));

$filters = array(
	'kick'                => array(
		'filter'  => FILTER_VALIDATE_INT,
		'flags'   => FILTER_FORCE_ARRAY,
		'options' => array('min_range' => 0)
	),
	'accept'              => array(
		'filter'  => FILTER_VALIDATE_INT,
		'flags'   => FILTER_FORCE_ARRAY,
		'options' => array('min_range' => 0)
	),
	'ban'              => array(
		'filter'  => FILTER_VALIDATE_INT,
		'flags'   => FILTER_FORCE_ARRAY,
		'options' => array('min_range' => 0)
	),
	'leader1'             => FILTER_VALIDATE_INT,
	'leader2'             => FILTER_VALIDATE_INT,
	'leader3'             => FILTER_VALIDATE_INT,
	'alliance-submit'    => FILTER_SANITIZE_STRING,
	
	'aid'                => FILTER_VALIDATE_INT,
);

$filteredG = filter_input_array(INPUT_GET, $filters);
$filtered  = filter_input_array(INPUT_POST, $filters);

$t->alliance    = new Alliance();

if ($user->alliance or !$filteredG['aid']) {
	$t->alliance->get($user->alliance);
	
}
else {
	header('Location: alliance-list.php');
	exit;
}

if (!$filteredG['aid'] and $filtered['alliance-submit'] and $t->alliance->isLeader($user)) {
	$leader   = array();
	$deleader = array();
	
	if (count($filtered['kick'])) {
		foreach ($filtered['kick'] as $k) {
			$u = getCachedUser($k);
			if ($u->alliance == $t->alliance->id) {
				$u->alliance  = 0;
				$u->aaccepted = 0;
				$u->save();
			}
		}
	}
	
	if (count($filtered['accept'])) {
		foreach ($filtered['accept'] as $k) {
			$u = getCachedUser($k);
			if ($u->alliance == $t->alliance->id) {
				$t->alliance->addMember($u, 1);
			}
		}
	}
	
	if (count($filtered['ban'])) {
		foreach ($filtered['ban'] as $k) {
			$u = getCachedUser($k);
			if ($u->alliance == $t->alliance->id) {
				$u->alliance  = 0;
				$u->aaccepted = 0;
				$u->save();
			}
			$ab = new AllianceBan();
			$ab->allianceId = $t->alliance->id;
			$ab->targetId   = $k;
			$ab->date       = time();
			$ab->create();
		}
	}
	
	if ($filtered['leader1'] and $filtered['leader1'] != $t->alliance->leaderId1) {
		$t->alliance->leaderId1 = $filtered['leader1'];
	}
	
	if ($filtered['leader2'] and $filtered['leader2'] != $t->alliance->leaderId2) {
		$t->alliance->leaderId2 = $filtered['leader2'];
	}
	
	if ($filtered['leader3'] and $filtered['leader3'] != $t->alliance->leaderId3) {
		$t->alliance->leaderId3 = $filtered['leader3'];
	}
	$t->alliance->save();
}
if ($filteredG['aid']) {
	$t->alliance->get($filteredG['aid']);
	if (!$t->alliance->id) {
		header('Location: alliance-list.php');
		exit;
	}
}

$t->allianceMembers = $t->alliance->getMembers();

$t->pageTitle = 'Alliance Members';
$t->display();
?>
