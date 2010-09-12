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
require_once('scripts/SpyLog.php');

new Privacy(PRIVACY_PRIVATE);

if (!Privacy::isIn())  {
	header('Location: index.php');
	exit;
}


$filters = array(
	'uid' => FILTER_VALIDATE_INT | FILTER_SANITIZE_NUMBER_INT,
	'spy' => FILTER_SANITIZE_STRING,
);

$filteredG = filter_input_array(INPUT_GET, $filters);
$filtered  = filter_input_array(INPUT_POST, $filters);

if ($filteredG['uid'] and $filteredG['spy']) {
	$filtered['uid'] = $filteredG['uid'];
	$filtered['spy'] = true;
}

$uid = $filtered['uid'] ? max($filtered['uid'], 0) : 0;
if (!$uid) {
	header('Location: base.php??'.$uid);
	exit;
}
else if ($uid == $user->id) {
	//can't attack yourself
	header("Location: stats.php?uid=$uid&e=2");
	exit;
}
else if ($user->getPrimary() < $conf['spying-cost']) {
	// not enough gold
	header("Location: stats.php?uid=$uid&e=5");
	exit;
}

$target = new User();
$target->get($uid);

if ($target->area != $user->area) {
	header("Location: stats.php?uid=$uid&e=7");
	exit;
}
else if (!IP::canAttack($user, $target)) {
	header("Location: stats.php?uid=$uid&e=8");
	exit;
}

$spylog = new SpyLog();
$spylog->attackerId       = $user->id;
$spylog->targetId         = $target->id;
$spylog->time             = time();
$spylog->attackerStrength = $user->CA;
$spylog->targetStrength   = $target->CA;
$spylog->spies            = 1;

if ($user->CA > $target->CA) {
		$spylog->isSuccess = 1;
		
		$spylog->sasoldiers      = spiedValue($target->sasoldiers, $user, $target);
		$spylog->samercs         = spiedValue($target->samercs, $user, $target);
		$spylog->dasoldiers      = spiedValue($target->dasoldiers, $user, $target);
		$spylog->damercs         = spiedValue($target->damercs, $user, $target);
		$spylog->uu              = spiedValue($target->uu, $user, $target);
		$spylog->SA              = spiedValue($target->SA, $user, $target);
		$spylog->DA              = spiedValue($target->DA, $user, $target);
		$spylog->calevel         = spiedValue($target->calevel, $user, $target);
		$spylog->targetSpies     = spiedValue($target->spies, $user, $target);
		$spylog->salevel         = spiedValue($target->salevel, $user, $target, $conf['names']['upgrades'][1][$target->salevel]);
		$spylog->dalevel         = spiedValue($target->dalevel, $user, $target, $conf['names']['upgrades'][0][$target->dalevel]);
		$spylog->attackTurns     = spiedValue($target->attackturns, $user, $target);
		$spylog->unitProduction  = spiedValue($target->up, $user, $target);
		$spylog->sf              = spiedValue($target->specialforces, $user, $target);
		$spylog->ralevel         = spiedValue($target->ralevel, $user, $target);
		$spylog->hhlevel         = spiedValue($target->hhlevel, $user, $target);
		$spylog->gold            = spiedValue($target->gold, $user, $target);

		require_once('scripts/Weapon.php');
		
		$attackWeapons        = Weapon::getUserAttackWeapons($target->id);
		$defenseWeapons       = Weapon::getUserDefenseWeapons($target->id);
		
		$spylog->weapons      = array();
		$spylog->types        = array();
		$spylog->types2       = array();
		$spylog->quantities   = array();
		$spylog->strengths    = array();
		$spylog->allStrengths = array();
		
		foreach ($attackWeapons as $w) {
			$spylog->weapons[]      = $w->weaponId;
			$spylog->types[]        = 1;
			$spylog->types2[]       = 1;
			$spylog->quantities[]   = spiedValue($w->weaponCount, $user, $target);
			$spylog->strengths[]    = spiedValue($w->weaponStrength, $user, $target);
			$spylog->allStrengths[] = spiedValue($w->getFullStrength(), $user, $target);
		}
		
		foreach ($defenseWeapons as $w) {
			$spylog->weapons[]      = $w->weaponId;
			$spylog->types[]        = 0;
			$spylog->types2[]       = 0;
			$spylog->quantities[]   = spiedValue($w->weaponCount, $user, $target);
			$spylog->strengths[]    = spiedValue($w->weaponStrength, $user, $target);
			$spylog->allStrengths[] = spiedValue($w->getFullStrength(), $user, $target);
		}
		
		$spylog->weapons      = implode(';', $spylog->weapons);
		$spylog->types        = implode(';', $spylog->types);
		$spylog->types2       = implode(';', $spylog->types2);
		$spylog->quantities   = implode(';', $spylog->quantities);
		$spylog->strengths    = implode(';', $spylog->strengths);
		$spylog->allStrengths = implode(';', $spylog->allStrengths);		
}
else {
	
	$user->spies--;
}

$user->buy($conf['spying-cost']);
$user->cacheStats();
$spylogId = $spylog->create();

if ($spylogId) {
	header("Location: spylog.php?id=$spylogId");
	exit;
}
else {
	// some kind of database error..
	header("Location: stats.php?uid=$uid&e=4");
	exit;
}

function spiedValue($value, $user, $target, $op = false){
	$ret = '???';
	$rdefCA = (float)(rand(80, 105) * 0.01 * $target->CA);
	$rattCA = (float)(rand(95, 105) * 0.01 * $user->CA);
	if ($rattCA > $rdefCA) {
		if ($op) {
			$ret = $op;
		}
		else {
			$ret = numecho2($value);
		}
	}
	return $ret;
}
?>
