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

$envpath = ini_get('ENVPATH');
if ($envpath) {
	require_once($envpath . '/env.php');
}
else {
	require_once('env/env.php');
}



$min  = 60;
$hour = $min*60;
$day  = $hour*24;
$week = $day*7;
 
$time    = time();

$allow_bonuses = false;
$game_offline  = false;
$allow_vacation = false;

if (time() > ($endtime - (2 * $week))) {
	$allow_bonuses = true;
}

if (time() < ($endtime - (5 * $day))) {

	$allow_vacation = true;
}

$diff       = $endtime - $time;
if ($time > $endtime) { 
	$game_offline = true;
}

$d = duration($diff);

$resetA = "Reset in: $d";

$conf_announcement = $announce  . ' ' .  $resetA;
 
// If changing these, update User
$conf['start-gold'           ] = 50000;
$conf['start-attackturns'    ] = 50;
$conf['start-uu'             ] = 0;
$conf['start-sasoldiers'     ] = 75;
$conf['start-dasoldiers'     ] = 75;

$conf['officers-per-page'    ] = 5;
$conf['max-officers'         ] = 15;
$conf['users-per-page'       ] = 30;
$conf['attacks-per-page'     ] = 10;
$conf['max-attacks'          ] = 10;
$conf['max-attacks-secs'     ] = 43200; // (60sec/min * 60min/hour * 12hour) = 43200 seconds
$conf['spying-cost'          ] = 2000;
$conf['attackturn-cap'       ] = 1000;
$conf['theft-cost'           ] = 3;
$conf['max-theft'            ] = 10;
$conf['max-theft-secs'       ] = 3600; // (60sec/min * 60min/hour * 1hour) = 3600 seconds
$conf['theft-magic-ratio'    ] = 130; // Need 30% more CA
$conf['theft-magic-ratio-max'] = 170; // and less than 70% more
$conf['recruit-seconds'      ] = 86400;  // 24hours
$conf['recruit-soldiers'     ] = 5;
$conf['max-recruit'          ] = 25;

$conf['max-alliance-shouts'  ] = 30;

$conf["sitename"] = "World War II";
$conf["users_per_page"]=30;
$conf["users_per_page_on_attack_log"]=10;
$conf["mercenaries_per_turn"] = 400;
$conf["days_to_hold_logs"]=5; //For Battle Logs
$conf["ips_to_hold_per_user"]=10;

$conf['area-count'] = 3;
$conf['area'] = array (
	1 => array(
		'name'       => 'Europe',
		'short-name' => 'E',
		'size'       => 90,
	),
	2 => array(
		'name'       => 'Africa',
		'short-name' => 'A',
		'size'       => 240
	),
	3 => array(
		'name'       => 'Graveyard',
		'short-name' => 'G',
		'size'       => 10000
	)
);


/*usa, uk,jap,german,ussr*/


$conf['gps'] = array(
	20, // 10 %
	20,
	20,
	20,
	20
);

$conf['dabonus0'] = 0.10;
$conf['dabonus1'] = 0.25;
$conf['dabonus2'] = 0;
$conf['dabonus3'] = 0.05;
$conf['dabonus4'] = 0.1;

$conf['cabonus0'] = 0.05;
$conf['cabonus1'] = 0.10;
$conf['cabonus2'] = 0.25;
$conf['cabonus3'] = 0;
$conf['cabonus4'] = 0.1;

$conf['sabonus0'] = 0;
$conf['sabonus1'] = 0.05;
$conf['sabonus2'] = 0.10;
$conf['sabonus3'] = 0.25;
$conf['sabonus4'] = 0.1;

$conf['rabonus0'] = 0.25;
$conf['rabonus1'] = 0;
$conf['rabonus2'] = 0.05;
$conf['rabonus3'] = 0.10;
$conf['rabonus4'] = 0.1;

$conf['cost'] = array(
	'sasoldier'     => 5000,
	'samerc'        => 10000,
	'damerc'        => 10000,
	'dasoldier'     => 5000,
	'spy'           => 3500,
	'specialforces' => 4000,
	'upgrades'      => array(
		0 => 0,
		1 => 10000,
		2 => 1000000,
		3 => 10000000,
		4 => 100000000,
	),
);

$conf['names'] = array(
	'upgrades' => array(
		0 => array (
			0 => 'None - Camouflage',
			1 => '1 - Trench',
			2 => '2 - Flack Jacket',
			3 => '3 - Anti-Tank Gun',
			4 => '4 - Flack Cannon',
		),
		1 => array (
			0 => 'None - Revolver',
			1 => '1 - Automatic Rifle',
			2 => '2 - Machine Gun',
			3 => '3 - Tank',
			4 => '4 - Plane',
		)
	),
	'weapons' => array(
		0 => array (
			0 => 'Camouflaged Uniforms',
			1 => 'Trenches',
			2 => 'Flack Jackets',
			3 => 'Anti-Tank Guns',
			4 => 'Flack Cannons',
		),
		1 => array (
			0 => 'Revolvers',
			1 => 'Automatic Rifles',
			2 => 'Machine Guns',
			3 => 'Tanks',
			4 => 'Planes',
		)
	),
);

$conf['strength'] = array(
	'sasoldier'    => 20,
	'samerc'       => 50,
	'dasoldier'    => 20,
	'damerc'       => 50,
	'untrained'    => 4
);


$conf['race'] = array(
	0 => array(
		'max-dalevel' => 4,
		'max-salevel'  => 4,
	),
	1 => array(
		'max-dalevel' => 4,
		'max-salevel'  => 4,
	),
	2 => array(
		'max-dalevel' => 4,
		'max-salevel'  => 4,
	),
	3 => array(
		'max-dalevel' => 4,
		'max-salevel'  => 4,
	),
	4 => array(
		'max-dalevel' => 4,
		'max-salevel'  => 4,
	),
);

?>
