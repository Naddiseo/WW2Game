
<!--

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

-->
<!-- Begin Offline page -->
<? global $_AGE, $_START_TIME, $_END_TIME; ?>
<!--
<?
$d = false;

if ($d) {
	var_dump($_AGE);
	var_dump($_START_TIME);
	var_dump($_END_TIME);
}

$nextage = $_AGE+1;

$starttime = intval(str_replace('@', '', $_START_TIME[$nextage]));
$endtime   = intval(str_replace('@', '', $_END_TIME[$nextage]));

if ($d) {
	echo sprintf("current time ($nextage): %i %s\n", time(), date('r', time()));
	echo sprintf("start time ($nextage): %i %s\n", $starttime, date('r', $starttime));
	echo sprintf("end time ($nextage): %i %s\n", $endtime, date('r', $endtime));

	if (time() > $starttime) {
		echo "time for next age " . time() - $starttime . "\n";
	}
	else {
		echo "there are still " . ($starttime - time()) / 60 . ' minutes left';
	}
}

?>
-->
<div id="offline-container">
	<p>World War II is in the process of resetting.</p>
	<p>Thank you for playing Age <?= $_AGE ?>. We will be down for about 24 hours to reset.</p>
	<p>Online in <b><?= ceil(($starttime - time()) / 60) ?> minutes</b></p>
	<p>In the mean time, join us on <a href="/irc/">IRC</a>, or the <a href="http://forum.ww2game.net">forum</a> and discuss WW2</a>
</div>
<!-- End offline page -->

