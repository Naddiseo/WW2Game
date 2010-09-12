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
require_once('scripts/SpyLog.php');

$t = new Template('spylog', intval($cgi['t']));

$filter = array(
	'id'     => FILTER_VALIDATE_INT | FILTER_SANITIZE_NUMBER_INT,
	'isview' => FILTER_VALIDATE_INT | FILTER_SANITIZE_NUMBER_INT,
);

$filtered  = filter_input_array(INPUT_POST, $filter);
$filteredG = filter_input_array(INPUT_GET, $filter);

$id        = $filteredG['id'];
$t->isView = $filteredG['isview'] ? true : false;

try {

	if ($id) {
		$spylog = new SpyLog();
		$spylog->get($id);
		
		$attackerCanSee = $user->id == $spylog->attackerId;
		$targetCanSee   = ($user->id == $spylog->targetId) and ($spylog->type == 1 or $spylog->isSuccess == 0);
		
		if ($attackerCanSee) {
			$t->target = new User();
			$t->target->get($spylog->targetId);
			$t->attacker = $user;
		}
		else if($targetCanSee) {
			$t->attacker = new User();
			$t->attacker->get($spylog->attackerId);
			$t->target = $user;
		}
		else {
			header('Location: intel.php?e=1');
			exit;
		}
		$t->s = $spylog;
	
	} 
	else {
		header('Location: intel.php?e=1');
		exit;
	}

} // try
catch (Exception $e) {
	$t->err = $e->getMessage();
}

$t->css = true;
$t->user = $user;
$t->pageTitle = 'Spy Log';
$t->display();
?>
