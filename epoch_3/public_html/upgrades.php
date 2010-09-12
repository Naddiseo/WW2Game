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
require_once('scripts/Upgrades.php');

$t = new Template('upgrades', intval($cgi['t']));

new Privacy(PRIVACY_PRIVATE);

$filters = array(
	'upgrade-sa'     => FILTER_SANITIZE_STRING,
	'upgrade-da'     => FILTER_SANITIZE_STRING,
	'upgrade-ca'     => FILTER_SANITIZE_STRING,
	'upgrade-ra'     => FILTER_SANITIZE_STRING,
	'upgrade-up'     => FILTER_SANITIZE_STRING,
	'upgrade-hh'     => FILTER_SANITIZE_STRING,
	'upgrade-of'     => FILTER_SANITIZE_STRING,
	'upgrade-bk'     => FILTER_SANITIZE_STRING,
	'upgrade-up-max' => FILTER_SANITIZE_STRING,
);

$filtered = filter_input_array(INPUT_POST, $filters);


$msg = '';

if ($filtered['upgrade-sa']) {
	$msg = Upgrades::upgradeSA($user);
}
else if ($filtered['upgrade-da']) {
	$msg = Upgrades::upgradeDA($user);
}
else if ($filtered['upgrade-ca']) {
	$msg = Upgrades::upgradeCA($user);
}
else if ($filtered['upgrade-ra']) {
	$msg = Upgrades::upgradeRA($user);
}
else if ($filtered['upgrade-up']) {
	$msg = Upgrades::upgradeUP($user);
}
else if ($filtered['upgrade-of']) {
	$msg = Upgrades::upgradeOf($user);
}
else if ($filtered['upgrade-bk']) {
	$msg = Upgrades::upgradeBk($user);
}
else if ($filtered['upgrade-hh']) {
	$msg = Upgrades::upgradeHH($user);
}
else if ($filtered['upgrade-up-max']) {
	$msg = Upgrades::upgradeUPMax($user);
}

if ($msg) {
	if (Upgrades::$isErr) {
		$t->err = $msg;
	}
	else {
		$t->msg = $msg;
	}
}

$t->user = $user;
$t->pageTitle = 'Upgrades';
$t->display();

?>
