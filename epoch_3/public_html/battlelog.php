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
require_once('scripts/BattleLog.php');

new Privacy(PRIVACY_PRIVATE);

$t = new Template('battlelog', intval($cgi['t']));

$filter = array(
	'id'     => FILTER_VALIDATE_INT | FILTER_SANITIZE_NUMBER_INT,
	'isview' => FILTER_VALIDATE_INT | FILTER_SANITIZE_NUMBER_INT,
);

$filteredG = filter_input_array(INPUT_GET, $filter);


$bId    = $filteredG['id']     ? max($filteredG['id'],     0) : 0;
$isview = $filteredG['isview'] ? max($filteredG['isview'], 0) : 0;

if (!$bId) {
	header('Location: attacklog.php');
	exit;
}

$b = new BattleLog();
$b->get($bId);

try {
	if ($user->id != $b->targetId and $user->id == $b->attackerId) {
		$t->target = new User();
		$t->target->get($b->targetId);
		$t->attacker = $user;
	}
	else if($user->id != $b->attackerId and $user->id == $b->targetId) {
		$t->attacker = new User();
		$t->attacker->get($b->attackerId);
		$t->target = $user;
	}
	else {
		header('Location: attacklog.php?e=1');
		exit;
	}
}
catch (Exception $e) {
	$t->err = $e->getMessage();
}

$t->isview    = $isview;
$t->css       = true;
$t->b         = $b;
$t->pageTitle = 'Battle Log';
$t->display();
?>
