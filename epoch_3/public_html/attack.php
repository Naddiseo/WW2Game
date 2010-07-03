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
require_once('scripts/BattleLog.php');

new Privacy(PRIVACY_PRIVATE);

function debug($str) {
	//echo "$str<br/>";
}

function debug_save($o) {
	 $o->save();
}

function debug_exit() {
//	exit;
}

if (!Privacy::isIn())  {
	header('Location: index.php');
	exit;
}


$filters = array(
	'uid' => FILTER_VALIDATE_INT | FILTER_SANITIZE_NUMBER_INT,
	'mass' => FILTER_SANITIZE_STRING,
	'raid' => FILTER_SANITIZE_STRING
);
$filteredG = filter_input_array(INPUT_GET,  $filters);
$filtered  = filter_input_array(INPUT_POST, $filters);

if ($filteredG['uid'] and $filteredG['raid']) {
	$filtered['uid'] = $filteredG['uid'];
	$filtered['raid'] = true;
}

$attackTurns = Inf;
if ($filtered['mass']) {
	$attackTurns = 1;
}
else if ($filtered['raid']) {
	$attackTurns = 6;
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
if (Privacy::isAdmin()) {
	die("hi");
}

$someTimeAgo = time() - $conf['max-attacks-secs'];
$attackCount = BattleLog::getAttackLogsBetweenUsersSinceTimeCount($user->id, $target->id, $someTimeAgo);
if ($attackCount >= $conf['max-attacks']) {
	// Maxed out potential
	header("Location: stats.php?uid=$uid&e=3");
	exit;
}

if ($user->attackturns < $attackTurns) {
	// Not enough turns
	header("Location: stats.php?uid=$uid&e=1");
	exit;
}

$user->attackturns -= $attackTurns;

// Ok, can create the attack log
$attacklog             = new BattleLog();
$attacklog->attackerId = $user->id;
$attacklog->targetId   = $target->id;
$attacklog->attackType = $attackTurns;
$attacklog->time       = time();

// now invert the attack turns.
if ($attackTurns == 1) {
	$attackTurns = 15;
}

debug("<h1>This is debugging info, it will not affect either account</h1> Please ignore it while I debug the attacking<br/>");
$attackerCount = BattleLog::getAttackLogsOfUserSinceTimeCount($user->id,    $someTimeAgo);
$defenseCount  = BattleLog::getDefenseLogsOfUserSinceTimeCount($target->id, $someTimeAgo);

debug("attackerCount: $attackerCount<br>defenseCount: $defenseCount");

// Each attack in the last 12 hours will make them 1% less effective
$attackLowPer  = 50 + max(40 - $attackCount, 0);
// Each 20 defense in the last 12 will will make them 1% less effective
$defenseLowPer = 50 + max(40 - floor($defenseCount * 0.05), 0);


debug("attackLowPer: $attackLowPer<br>defenseLowPer: $defenseLowPer");
$attacklog->attackerStrikePercentage = rand($attackLowPer,  100);
$attacklog->targetDefensePercentage = rand($defenseLowPer, 100);



$attacklog->attackerStrength = floor(($user->SA   + 1) * $attacklog->attackerStrikePercentage * 0.01);
$attacklog->targetStrength   = floor(($target->DA + 1) * $attacklog->targetDefensePercentage  * 0.01);

$attackerWeapons    = Weapon::getUserAttackWeapons($user->id);
$attackWeaponsRatio = Weapon::getStrengthRatio($user->id, true); 

debug("attackerRatio: $attackWeaponsRatio->ratio");

$defenseWeapons      = Weapon::getUserDefenseWeapons($target->id);
$defenseWeaponsRatio = Weapon::getStrengthRatio($target->id, false);

debug("defenseRatio: $defenseWeaponsRatio->ratio");

$attacklog->attackerRA = $user->RA *  rand($attackLowPer, 100) * 0.01;
$attacklog->targetRA   = $target->RA * rand($defenseLowPer, 100) * 0.01;

debug("attacker RA: $attacklog->attackerRA, <Br>targetRA: $attacklog->targetRA");

$raWeapons      = array();
$raWeaponsRatio = 0;

if ($attacklog->attackerRA > $attacklog->targetRA) {
	// Attacker has more RA, so he can damage the target's attack weapons
	$attackWeaponsRatio->ratio  *= 1.03;
	$raWeapons                   = Weapon::getUserAttackWeapons($target->id);
	$raWeaponsRatio              = Weapon::getStrengthRatio($target->id, true);
	$raDmg                       = floor($raWeaponsRatio->ratio * 0.002 * $attackTurns);
	debug("Attacker won in RA, ratio: $raWeaponsRatio->ratio");
	debug("raDmg: floor($raWeaponsRatio->ratio * 0.002 * $attackTurns) = $raDmg");
	
}
else {
	// Target has more RA, so he can damage the attacker's defense weapons
	$defenseWeaponsRatio->ratio *= 1.03;
	$raWeapons                   = Weapon::getUserDefenseWeapons($user->id);
	$raWeaponsRatio              = Weapon::getStrengthRatio($user->id, false);
	$raDmg                       = floor($raWeaponsRatio->ratio * 0.002 * $attackTurns);
	debug("Target won in RA, ratio: $raWeaponsRatio->ratio");
	debug("raDmg: floor($raWeaponsRatio->ratio * 0.002 * $attackTurns) = $raDmg");
}

debug("raweapons: " . print_r($raWeapons, true));


$attackerAlloc = $user->getWeaponAlloacation(true, $attackWeaponsRatio->total    );
$targetAlloc   = $target->getWeaponAlloacation(false, $defenseWeaponsRatio->total);

debug("alloc1: " . print_r($attackerAlloc,true));
debug("alloc2: " . print_r($targetAlloc,true));

$attacklog->satrained   = $attackerAlloc['trained'  ]['weapons'];
$attacklog->samercs     = $attackerAlloc['mercs'    ]['weapons'];
$attacklog->sauntrained = $attackerAlloc['untrained']['weapons'];

$attacklog->datrained   = $targetAlloc['trained'  ]['weapons'];
$attacklog->damercs     = $targetAlloc['mercs'    ]['weapons'];
$attacklog->dauntrained = $targetAlloc['untrained']['weapons'];

$attacklog->satrainednw   = $attackerAlloc['trained'  ]['noweapons'];
$attacklog->samercsnw     = $attackerAlloc['mercs'    ]['noweapons'];
$attacklog->sauntrainednw = $attackerAlloc['untrained']['noweapons'];

$attacklog->datrainednw   = $targetAlloc['trained'  ]['noweapons'];
$attacklog->damercsnw     = $targetAlloc['mercs'    ]['noweapons'];
$attacklog->dauntrainednw = $targetAlloc['untrained']['noweapons'];

$isSuccess = $attacklog->attackerStrength > $attacklog->targetStrength ? 1 : 0;
$saDeath   = 0;
$daDeath   = 0;
$saDmg     = 0;
$daDmg     = 0;

$saunits = array('samercs', 'sasoldiers', 'uu');
$daunits = array('damercs', 'dasoldiers', 'uu');

foreach ($saunits as $unit) {
	$d = getDeath($user->$unit, $isSuccess);
	$user->$unit -= $d;
	$saDeath += $d;
}

foreach ($daunits as $unit) {
	$d = getDeath($target->$unit, !$isSuccess);
	$target->$unit -= $d;
	$daDeath += $d;
}

$saDmg = floor($attackWeaponsRatio->ratio * 0.001 * $attackTurns);
debug("saDmg: $saDmg = floor($attackWeaponsRatio->ratio * 0.001 * $attackTurns)");
$daDmg = floor($defenseWeaponsRatio->ratio * 0.001 * $attackTurns);
debug("daDmg: $daDmg =  floor($defenseWeaponsRatio->ratio * 0.001 * $attackTurns)");
if (!$isSuccess) {
	$saDmg = 5;
}

$defenseWeapons = array_reverse($defenseWeapons);
// damage the defenders weapons.
foreach ($defenseWeapons as $w) {
	$str = $w->weaponStrength;
	if ($saDmg > $str) {
		$w->weaponStrength = 0;
		$w->weaponCount    = 0;
	}
	else {
		$w->weaponStrength -= $saDmg;
	}
	$saDmg -= $str;
	debug_save($w);//->save();
	if ($saDmg <= 0) {
		break;
	}
}

$attackerWeapons = array_reverse($attackerWeapons);
// damage the attackers weapons.
foreach ($attackerWeapons as $w) {
	$str = $w->weaponStrength;
	if ($daDmg > $str) {
		$w->weaponStrength = 0;
		$w->weaponCount    = 0;
	}
	else {
		$w->weaponStrength -= $daDmg;
	}
	$daDmg -= $str;
	debug_save($w);//->save();
	if ($daDmg <= 0) {
		break;
	}
}

$attackLog->RADamage = $raDmg;
$raWeapons = array_reverse($raWeapons);
// damage the weapons from ra
foreach ($raWeapons as $w) {
	$str = $w->weaponStrength;
	if ($raDmg > $str) {
		$w->weaponStrength = 0;
		$w->weaponCount    = 0;
	}
	else {
		$w->weaponStrength -= $raDmg;
	}
	$raDmg -= $str;
	debug_save($w);
	if ($raDmg <= 0) {
		break;
	}
}



$attacklog->attackerLosses = $saDeath;
$attacklog->targetLosses   = $daDeath;


if ($isSuccess and $attackTurns < 15) {
	// The attacker won most of the battles, so he gets the gold
	
	$attacklog->percentStolen = rand(60, 90) * 0.01;
	
	if ($user->race == 3) {
		$attacklog->percentStolen += 0.1;
	}
	if ($target->race == 1) {
		$attacklog->percentStolen -= 0.1;
	}
	

	$attacklog->goldStolen    = floor($attacklog->percentStolen * $target->gold);


	$attacklog->percentStolen = floor($attacklog->goldStolen / ($target->gold + 1) * 100);

	$attacklog->attackerExp   = $isSuccess;
}
else {
	// The defender won at least half, he keeps his gold.
	if ($attackTurns < 15) {
		$attacklog->targetExp = -1 * ($isSuccess - 1);
	}
}

if ($isSuccess) {
	if (
		$attacklog->attackerRA > $attacklog->targetRA and 
		 $user->nation == 3 and 
		rand(0, 20) == 10
	) {
		$attacklog->attackerHostages = floor(rand(0, 20) * 0.0005 * $target->dasoldiers);		
		$target->dasoldiers -= $attacklog->attackerHostages;
		
		$uu = floor(rand(0, 20) * 0.00025 * $target->uu);
		$target->uu -= $uu;
		
		$attacklog->attackerHostages += $uu;
		
		$user->uu           += $attacklog->attackerHostages + $uu; 
	}
}
else {
	if (
		$attacklog->targetRA > $attacklog->attackerRA  and 
		$target->nation == 1 and 
		rand(0, 20) == 10
	) {
		$attacklog->targetHostages = floor(rand(0, 20) * 0.0005 * $user->sasoldiers);
		$user->sasoldiers   -= $attacklog->targetHostages;
		
		$uu        = floor(rand(0, 20) * 0.00025 * $user->uu);
		$user->uu -= $uu;
		
		$attacklog->targetHostages += $uu;
		
		$target->uu  += $attacklog->targetHostages + $uu;
	}
}	
		

$user->gold   += $attacklog->goldStolen;
$target->gold -= $attacklog->goldStolen;
$user->gameSkill    += $attacklog->attackerExp;
$target->gameSKill  += $attacklog->targetExp;

//debug_save($user);//->save();
//debug_save($target);//->save();
$user->cacheStats();
$target->cacheStats();


$attacklog->isSuccess = $isSuccess;
debug("Success: $isSuccess");
debug_exit();

$battleId = $attacklog->create();

// TODO: figure a better way to do this
require_once('logger.php');

Weapon::cleanupWeapons();

if ($battleId) {
	header("Location: battlelog.php?id=$battleId");
	exit;
}
else {
	// some kind of database error..
	header("Location: stats.php?uid=$uid&e=4");
	exit;
}

function getDeath($n, $isSuccess) {
	if ($isSuccess) {
		return floor(rand(0, 80 ) * 0.000001 * $n);
	}
	else {
		return floor(rand(0, 200) * 0.000001 * $n);
	}
}

?>
