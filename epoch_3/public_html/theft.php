<?
require_once('scripts/vsys.php');
require_once('scripts/SpyLog.php');

new Privacy(PRIVACY_PRIVATE);

if (!Privacy::isIn())  {
	header('Location: index.php');
	exit;
}

$debugPath = BASEDIR . "/dbg/". me();
$debugString = '';

function DBG($str) {
	global $debugString;
	
	$debugString .= "\n<br><p>$str</p>";
}

function dwrite() {
	global $debugString, $debugPath;
	
	//$fp = fopen($debugPath, 'w+');
	//if ($fp) {
		//fwrite($fp, $debugString);
		//fclose($fp);
	//}
}


$filters = array(
	'uid'       => FILTER_VALIDATE_INT,
	'spies'     => FILTER_SANITIZE_NUMBER_INT,
	'saweapons' => FILTER_SANITIZE_STRING,
	'daweapons' => FILTER_SANITIZE_STRING,
	'gold'      => FILTER_SANITIZE_STRING,
);
$filtered  = filter_input_array(INPUT_POST, $filters);

$uid = $filtered['uid'] ? max($filtered['uid'], 0) : 0;
if (!$uid) {
	header('Location: base.php');
	exit;
}
else if (me($uid)) {
	//can't attack yourself
	header("Location: stats.php?uid=$uid&e=2");
	exit;
}
else if ($user->attackturns < $conf['theft-cost']) {
	// not enough gold
	header("Location: stats.php?uid=$uid&e=1");
	exit;
}

$spies = max($filtered['spies'], 1);
if ($spies > $user->spies) {
	$spies = $user->spies;
}

if (!$spies) {
	header("Location: stats.php?uid=$uid&e=6");
	exit;
}

// If can do it
$timeAgo = time() - $conf['max-theft-secs'];
$count   = SpyLog::getTheftOnUserCount($user->id, $uid, $timeAgo);

if ($count >= $conf['max-theft']) {
	header("Location: stats.php?uid=$uid&e=3");
	exit;
}

$theftType = 0;
if ($filtered['saweapons']) {
	$theftType = 1;
}
else if ($filtered['daweapons']) {
	$theftType = 2;
}
else {
	// gold
	$theftType = 3;
}

// Make sure the user can actually thieve gold
if ($theftType == 3 and ($user->nation == 1 or $user->nation == 3)) {
	header("Location: stats.php?uid=$uid");
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
$spylog->type             = 1; // theft


// Update this before we forget
$user->attackturns -= $conf['theft-cost'];

$spylog->targetStrength   = $target->getCA();


// Calculate the percent difference in the sent CA vs the def CA

if ($user->getSupport('theft-calc')) {
	$spylog->attackerStrength = $user->getCA();
	$per = floor($spylog->attackerStrength / ($spylog->targetStrength + 1) * 100);
	if ($per >  $conf['theft-magic-ratio']) {
		$per = $conf['theft-magic-ratio'];
	}
	$spies = $user->spies;
	DBG("User is supporter");
}
else {
	$tspies = $user->spies; // Save it away
	$user->spies = $spies;
	$spylog->attackerStrength = $user->getCA();
	$user->spies = $tspies; // restore.
	
	$per = floor($spylog->attackerStrength / ($spylog->targetStrength + 1) * 100);
	DBG("User is <b>not</b> supporter. per = $per");
}

$spylog->spies            = $spies;

$spiesKilled = 0;

$weapons      = array();
$weaponAmount = 0;
$weaponType   = 0; // attack or da
$weaponType2  = 0;
$goldStolen   = 0;
$hostages     = 0;

if ($per < $conf['theft-magic-ratio']) {
	// Sent too few spies
	$isSuccess = 0;
	
	// How many too few?
	if ($per < ($conf['theft-magic-ratio'] * 0.5)) {
		$spiesKilled = floor($spies * rand(0, 10) * 0.01);
	}
	else if ($per >= (0.5 * $conf['theft-magic-ratio']) and $per < ($conf['theft-magic-ratio'] * 0.8)) {
		$spiesKilled = floor($spies * rand(0, 8 ) * 0.01); 
	}
	else {
		$spiesKilled = floor($spies * rand(0, 5 ) * 0.01);
	}
	$spylog->uu = $spiesKilled;
	$spylog->SA = $per;
}
else if ($per > $conf['theft-magic-ratio-max'] and $spies > 100) { // 1 spy should be sent if they have 0 covert.
	// Sent way too many
	$isSuccess = 0;
	
	// How many more?
	if ($per > $conf['theft-magic-ratio-max'] and $per < ($$conf['theft-magic-ratio-max'] * 2)) {
		$spiesKilled = floor($spies * rand(0, 8 ) * 0.01);
	}
	else {
		// Oh oh.. way too many
		$spiesKilled = floor($spies * rand(0, 30) * 0.01);
	}
}
else {
	// Sent between the min and max
	$isSuccess = 1;
	
	
	if ($theftType == 3) {
		// Gold, it's 3 turns so 20-40% forUSA/USSR, and 30-50% for Japan
		if ($user->nation != 1 and $user->nation != 3) {
			// UK and Ger can't gold theft.
			
			$per = rand(20, 40) * 0.01;
			if ($user->nation == 2) {
				$per += 0.1; // Japan can steal 10% more.
			}
			
			$goldStolen = floor($target->gold * $per);
			$spylog->type = 2;
		}
	}
	else {
		DBG("Type = $theftType");
		switch ($theftType) {
			case 1: // saweapons
				$weapons     = Weapon::getUserAttackWeapons($target->id, 'weaponCount');
				$weaponType  = 1;
				break;
			case 2: // da weapons
				$weapons     = Weapon::getUserDefenseWeapons($target->id, 'weaponCount');
				$weaponType  = 0;
				break;
		}
	
		if ($weapons[0]) {
			DBG("There is weapons");
			$weaponType2 = $weapons[0]->weaponId;
			if ($weapons[0]->weaponCount > 15) {
				$weaponAmount = floor(rand(0, 55)  * 0.001 * $weapons[0]->weaponCount);
			}
			
			DBG("type2 = $weaponType2");
			DBG("amount = $weaponAmount");
		
			$weapons[0]->weaponCount -= $weaponAmount;
			
			DBG("target new amount = {$weapons[0]->weaponCount}");
			
			$userWeapon = Weapon::queryWeapon($user->id, $weaponType, $weaponType2);
			if ($userWeapon->weaponCount) {
				DBG("user has this weapon. was {$userWeapon->weaponCount}");
				// Good, they already have this type.
				$userWeapon->weaponCount += $weaponAmount;
				DBG("now: {$userWeapon->weaponCount}");
				$r = $userWeapon->save();
			}
			else {
				DBG("Have to Create this weapon");
				// We have to create them
				$userWeapon = new Weapon();
				$userWeapon->weaponId       = $weaponType2;
				$userWeapon->weaponStrength = $userWeapon->getFullStrength();
				$userWeapon->isAttack       = $weaponType;
				$userWeapon->userId         = $user->id;
				$userWeapon->weaponCount    = $weaponAmount;
				$r = $userWeapon->create();
				
				DBG("<pre>" . print_r($userWeapon, true) . "</pre>");
				
			}
			DBG("r=$r");
			
			$s = $weapons[0]->save();
			DBG("s=$s");
		}
		else {
			DBG("target has no weapons");
		}
	}
	
	if ($user->nation != 1 and $user->nation != 3) {
		// hostages
		$rand = rand(0, 20);
		$spylog->DA = $rand;
		DBG("User can have hostages ($rand)");
		if ($user->RA > $target->RA and $rand == 10) {
			$hostages = floor(rand(0, 20) * 0.00025 * $target->uu);
		}
	}
}


$spylog->isSuccess    = $isSuccess;
$spylog->weaponamount = $weaponAmount;
$spylog->weapontype   = $weaponType;
$spylog->weapontype2  = $weaponType2;
$spylog->goldStolen   = $goldStolen;
$spylog->hostages     = $hostages;
$spylog->salevel      = $user->RA;
$spylog->dalevel      = $target->RA;

DBG("RA = {$user->RA} vs {$target->RA}");

$target->gold        -= $goldStolen;
$target->uu          -= $hostages;
$user->gold          += $goldStolen;
$user->uu            += $hostages;
$user->spies         -= $spiesKilled;
$target->save();
$user->save();

$id = $spylog->create();

$debugPath .= "-$id.html";
dwrite();

if ($id) {
	header("Location: spylog.php?id=$id");
	exit;
}
else {
	// some kind of database error..
	header("Location: stats.php?uid=$uid&e=4");
	exit;
}

?>
