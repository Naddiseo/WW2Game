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
require_once('scripts/Recruit.php');

$t = new Template('recruit', intval($cgi['t']));

$filters = array(
	'uid'    => FILTER_VALIDATE_INT,
	'number' => FILTER_SANITIZE_NUMBER_INT,
	'e'      => FILTER_SANITIZE_NUMBER_INT,
);

$filteredG = filter_input_array(INPUT_GET, $filters);
$filtered  = filter_input_array(INPUT_POST, $filters);

$uid    = $filteredG['uid'] > 0 ? $filteredG['uid'] : ($filtered['uid'] ? $filtered['uid'] : 0);
$number = $filtered['number'];
$error  = $filteredG['e'];

$ip     = $_SERVER['REMOTE_ADDR'];

try {

	if ($uid) {
		$t->target = getCachedUser($uid);
		$count = Recruit::queryRecentCount($uid, $ip);
		if($t->target->clicks >= $conf['max-recruit']) {
			throw new Exception($t->target->getNameHTML() . ' has recruited enough people today');
		}
		else if ($count > 0) {
			throw new Exception('You have already been recruited into ' . $t->target->getNameHTML() . '\'s army today');
		}
		else if ($number > 0 and $number == $_SESSION['number']) {
			$r       = new Recruit();
			$r->uId  = $t->target->id;
			$r->IP   = $ip;
			$r->sId  = session_id();
			$r->time = time();
			$r->create();
			
			if ($r->id) {
				$t->target->clicks++;
				$t->target->samercs += $conf['recruit-soldiers'];
				$t->target->damercs += $conf['recruit-soldiers'];
				$rsoldiers = $conf['recruit-soldiers'] * 2;
				$t->target->save();
				$t->msg = 'Success, ' . $t->target->getNameHTML() . " recruited $rsoldiers more Mercenaries.";
			}
		}
		else if ($number and $number != $_SESSION['number']) {
			throw new Exception('Wrong Number');
		}
	
	} 
	else if ($e) {
		switch ($e) {
			default:
			case 1:
				throw new Exception('User not found');
		}
	}
	else {
		header("Location: stats.php?uid=$uid");
		exit;
	}

} // try
catch (Exception $e) {
	$t->err = $e->getMessage();
}

$t->css = true;
$t->pageTitle = 'Recruitment';
$t->display();
?>


