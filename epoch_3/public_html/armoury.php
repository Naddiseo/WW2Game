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

require_once("scripts/vsys.php");
require_once('scripts/Template.php');

new Privacy(PRIVACY_PRIVATE);

$t = new Template('armoury', intval($cgi['t']));

$filter = array(
	'wId' => FILTER_VALIDATE_INT | FILTER_SANITIZE_NUMBER_INT,
	'damage' => FILTER_VALIDATE_INT | FILTER_SANITIZE_NUMBER_INT,
	'sell' => FILTER_VALIDATE_INT | FILTER_SANITIZE_NUMBER_INT,
	'repair-attack' => FILTER_SANITIZE_STRING,
	'repair-attack-max' => FILTER_SANITIZE_STRING,
	'repair-defense' => FILTER_SANITIZE_STRING,
	'repair-defense-max' => FILTER_SANITIZE_STRING,

	'attackweapon' => array(
		'filter' => FILTER_VALIDATE_INT | FILTER_SANITIZE_NUMBER_INT,
		'flags' => FILTER_REQUIRE_ARRAY,
		'options' => array('min_range' => 0)
	),

	'defenseweapon' => array(
		'filter' => FILTER_VALIDATE_INT | FILTER_SANITIZE_NUMBER_INT,
		'flags' => FILTER_REQUIRE_ARRAY,
		'options' => array('min_range' => 0)
	),

	'armoury-buy' => FILTER_SANITIZE_STRING,
	
);

$filtered = filter_input_array(INPUT_POST, $filter);


if ($filtered['repair-attack'] and $filtered['wId']) {
	$weapon = new Weapon();
	$weapon->get($filtered['wId']);
	if ($weapon->userId == $user->id and $weapon->getDamage() > 0) {
		$toRepair = max($filtered['damage'], 0);
		if ($toRepair > $weapon->getDamage()) {
			$toRepair = $weapon->getDamage();
		}

		// repairperpoint takes weapons count into account
		$cost = $toRepair * $weapon->getRepairPerPoint();
		if ($user->canBuy($cost)) {
			$user->buy($cost);
			$weapon->weaponStrength += $toRepair;
			$user->cacheStats();
			$weapon->save();
		}
		else {
			$t->err = 'Not enough gold';
		}
	}
}
else if ($filtered['repair-defense'] and $filtered['wId']) {
	$weapon = new Weapon();
	$weapon->get($filtered['wId']);
	if ($weapon->userId == $user->id and $weapon->getDamage() > 0) {
		$toRepair = max($filtered['damage'], 0);
		if ($toRepair > $weapon->getDamage()) {
			$toRepair = $weapon->getDamage();
		}

		// repairperpoint takes weapons count into account
		$cost = $toRepair * $weapon->getRepairPerPoint();
		if ($user->canBuy($cost)) {
			$user->buy($cost);
			$weapon->weaponStrength += $toRepair;
			$user->cacheStats();
			$weapon->save();
		}
		else {
			$t->err = 'Not enough gold';
		}
	}
}

else if ($filtered['repair-attack-max'] and $filtered['wId']) {
	$weapon = new Weapon();
	$weapon->get($filtered['wId']);
	if ($weapon->userId == $user->id and $weapon->getDamage() > 0) {
		$toRepair = floor($user->getPrimary() / $weapon->getRepairPerPoint());
		if ($toRepair > $weapon->getDamage()) {
			$toRepair = $weapon->getDamage();
		}

		// repairperpoint takes weapons count into account
		$cost = $toRepair * $weapon->getRepairPerPoint();
		if ($user->canBuy($cost)) {
			$user->buy($cost);
			$weapon->weaponStrength += $toRepair;
			$user->cacheStats();
			$weapon->save();
		}
		else {
			$t->err = 'Not enough gold';
		}
	}
}
else if ($filtered['repair-defense-max'] and $filtered['wId']) {
	$weapon = new Weapon();
	$weapon->get($filtered['wId']);
	if ($weapon->userId == $user->id and $weapon->getDamage() > 0) {
		$toRepair = floor($user->getPrimary() / $weapon->getRepairPerPoint());
		if ($toRepair > $weapon->getDamage()) {
			$toRepair = $weapon->getDamage();
		}

		// repairperpoint takes weapons count into account
		$cost = $toRepair * $weapon->getRepairPerPoint();
		if ($user->canBuy($cost)) {
			$user->buy($cost);
			$weapon->weaponStrength += $toRepair;
			$user->cacheStats();
			$weapon->save();
		}
		else {
			$t->err = 'Not enough gold';
		}
	}
}

else if ($filtered['sell'] and $filtered['wId']) {
	$weapon = new Weapon();
	$weapon->get($filtered['wId']);
	if ($weapon->userId == $user->id) {
		$toSell = min($filtered['sell'], $weapon->weaponCount);
		$cost = $weapon->getSellCost($user, $toSell);
		$user->bank += $cost;
		$weapon->weaponCount -= $toSell;
		if ($weapon->weaponCount <= 0) {
			$weapon->delete();
		}
		else {
			$weapon->save();
		}
		$user->cacheStats();
	}
}

else if($filtered['armoury-buy']) {

	/// TODO: make this section better

	$t->saWeapons = Weapon::getUserAttackWeapons($user->id);
	$t->daWeapons = Weapon::getUserDefenseWeapons($user->id);
	
	$cost = 0;
	$tobuy = array();

	for ($i = 0; $i <= $user->salevel; $i++) {
		$tobuy[1][$i] = max(intval($filtered['attackweapon'][$i]), 0);
		$cost += $conf['weapon' . $i . 'price'] * $tobuy[1][$i];
	}
	
	for ($i = 0; $i <= $user->dalevel; $i++) {
		$tobuy[0][$i] = max(intval($filtered['defenseweapon'][$i]), 0);
		$cost += $conf['weapon' . $i . 'price'] * $tobuy[0][$i];
	}

	if ($user->canBuy($cost)) {
		$user->buy($cost);

		foreach ($t->saWeapons as $weapon) {
			if ($tobuy[1][$weapon->weaponId]) {
				$weapon->weaponCount += $tobuy[1][$weapon->weaponId];
				$found = true;
				$weapon->save();
				unset($tobuy[1][$weapon->weaponId]);
			}
		}
		
		foreach ($t->daWeapons as $weapon) {
			if ($tobuy[0][$weapon->weaponId]) {
				$weapon->weaponCount += $tobuy[0][$weapon->weaponId];
				$found = true;
				$weapon->save();
				unset($tobuy[0][$weapon->weaponId]);
			}
		}

		
		
		foreach ($tobuy[1] as $wId => $amount) {
			$weapon = new Weapon();
			$weapon->userId = $user->id;
			$weapon->weaponId = $wId;
			$weapon->isAttack = 1;
			$weapon->weaponCount = $amount;
			$weapon->weaponStrength = $conf['weapon' . $wId . 'strength'];
			if ($amount and $weapon->create()) {
				$t->saWeapons[] = $weapon;
			}
		}

		foreach ($tobuy[0] as $wId => $amount) {
			$weapon = new Weapon();
			$weapon->userId = $user->id;
			$weapon->weaponId = $wId;
			$weapon->isAttack = 0;
			$weapon->weaponCount = $amount;
			$weapon->weaponStrength = $conf['weapon' . $wId . 'strength'];
			if ($amount and $weapon->create()) {
				$t->daWeapons[] = $weapon;
			}
		}
		$user->cacheStats();
	}
	else {
		$t->err = 'Not enough Gold';
	}
	
	
}

$t->user = $user;

if (!$t->saWeapons and !$t->daWeapons) {
	$t->saWeapons = Weapon::getUserAttackWeapons($user->id);
	$t->daWeapons = Weapon::getUserDefenseWeapons($user->id);
}

$t->saRatio = Weapon::getStrengthRatio($user->id, 1);
$t->daRatio = Weapon::getStrengthRatio($user->id, 0);

$t->pageTitle = 'Armoury';
$t->display();
?>
