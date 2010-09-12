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

class Upgrades {

	public static $isErr = false;

	private static function
	upgrade(User $user, $name, $cost = null, $what = false, $delta = 0) {
		$msg = '';
		
		if ($cost != null and $cost != Inf and $cost != 0 and $what and $delta) {
			if ($user->canBuy($cost)) {
				$user->buy($cost, $what, $delta);
				$msg = "You have increased your $name by $delta";
				$user->save();
				Upgrades::$isErr = false;
			}
			else {
				$msg = 'You do not have enough resources';
				Upgrades::$isErr = true;
			}
		}
		
		return $msg;
	}

	public static function
	upgradeSA(User $user) {
		global $conf;
		
		if ($user->salevel >= $conf['race'][$user->nation]['max-salevel']) {
			return false;
		}
		
		$cost = Upgrades::saCost($user);
		$what = 'salevel';
		$delta = 1;
		$name = 'Offensive Technology';

		return Upgrades::upgrade($user, $name, $cost, $what, $delta);
	}
	
	public static function
	upgradeDA(User $user) {
		global $conf;
		
		if ($user->dalevel >= $conf['race'][$user->nation]['max-dalevel']) {
			return false;
		}
		
		$cost = Upgrades::daCost($user);
		$what = 'dalevel';
		$delta = 1;
		$name = 'Defensive Technology';

		return Upgrades::upgrade($user, $name, $cost, $what, $delta);
	}
	
	public static function
	upgradeCA(User $user) {
		global $conf;
		
		$cost = Upgrades::caCost($user);
		$what = 'calevel';
		$delta = 1;
		$name = 'Covert Technology';

		return Upgrades::upgrade($user, $name, $cost, $what, $delta);
	}
	
	public static function
	upgradeRA(User $user) {
		global $conf;
		
		$cost = Upgrades::raCost($user);
		$what = 'ralevel';
		$delta = 1;
		$name = 'Retaliation Technology';

		return Upgrades::upgrade($user, $name, $cost, $what, $delta);
	}
	
	public static function
	upgradeUP(User $user) {
		global $conf;
		
		$cost = Upgrades::upCost($user);
		$what = 'up';
		$delta = 1;
		$name = 'Unit Production';

		return Upgrades::upgrade($user, $name, $cost, $what, $delta);
	}
	
	public static function
	upgradeOf(User $user) {
		global $conf;
		
		if (!$user->getSupport('upgrades')) {
			return false;
		}
		
		$cost = Upgrades::ofCost($user);
		$what = 'maxofficers';
		$delta = 1;
		$name = 'Maxium Officers';

		return Upgrades::upgrade($user, $name, $cost, $what, $delta);
	}
	
	public static function
	upgradeBk(User $user) {
		global $conf;
		
		if (!$user->getSupport('upgrades')) {
			return false;
		}
		
		$cost = Upgrades::bkCost($user);
		$what = 'bankper';
		$delta = -1;
		$name = 'Bank Percentage';

		return Upgrades::upgrade($user, $name, $cost, $what, $delta);
	}
	
	public static function
	upgradeHH(User $user) {
		global $conf;
		
		$cost = Upgrades::hhCost($user);
		$what = 'hhlevel';
		$delta = 1;
		$name = 'Hand-to-Hand Level';

		return Upgrades::upgrade($user, $name, $cost, $what, $delta);
	}
	
	public static function
	upgradeUPMax(User $user) {
		$Li = $user->up;
		$goldi = $user->getPrimary();
		$gold = $goldi;
		$cost = 0;
		$Lf = sqrt(0.25 + $Li * (1 + $Li) + $gold * 0.0002) - 0.5;
		$up = floor($Lf);
		$gold = 10000 * ($Lf - $up) + 5000 * ($Lf * ($Lf - 1) - $up * ($up - 1));

		$delta = $up - $Li;
		$cost = $goldi - $gold;
		$what = 'up';
		$name = 'Unit Production';

		return Upgrades::upgrade($user, $name, $cost, $what, $delta);
	}
	
	//-----------------------------------

	public static function
	saCost(User $user) {
		global $conf;

		return $conf['cost']['upgrades'][$user->salevel + 1];
	}

	public static function 
	daCost(User $user) {
		global $conf;

		return $conf['cost']['upgrades'][$user->dalevel + 1];
	}

	public static function 
	caCost(User $user) {
		global $conf;

		return pow(2, $user->calevel) * 12000;
	}

	public static function 
	raCost(User $user) {
		global $conf;

		return pow(2, $user->ralevel) * 100000 + 100000;
	}

	public static function 
	upCost(User $user) {
		global $conf;

		return $user->up * 10000 + 10000;
	}

	public static function 
	ofCost(User $user) {
		global $conf;
	
		if ($user->maxofficers < $conf['max-officers'] and $user->getSupport('upgrades')) {
			 return pow(2, floor($user->maxofficers )) * 300000;
		}
	
		return Inf;
	}

	public static function 
	bkCost(User $user) {
		global $conf;
	
		if ($user->bankper >= 2 and $user->getSupport('upgrades')) {
			return pow(3, (10 - $user->bankper)) * 1800000 + 1500000;
		}

		return Inf;
	}


	public static function 
	hhCost(User $user) {
		global $conf;

		return pow(2.5, $user->hhlevel) * 125000 + 112500; 
	}
}
?>
