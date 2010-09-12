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

require_once('scripts/Template.php');
require_once('scripts/Privacy.php');
require_once('env/env.php');


$t = new Template('offline');

$nextage = $_AGE + 1;
$c = count($_START_TIME);
if ($nextage > $c) {
	$v = end($_START_TIME);
	$nextage = key($_START_TIME);
}

$starttime = str_replace('@', '', $_START_TIME[$nextage]);
$endtime   = str_replace('@', '', $_END_TIME[$nextage]);

# FIX for issue #1 : make sure there is a time for next age
if (isset($_START_TIME[$nextage]) and time() > $starttime) {
	// it's time to start


	if (updateAgefiles($nextage)) {
		header('Location: index.php?t=1');
		exit;
	}
}


$t->offline = true;
$t->pageTitle = 'Offline';
$t->display();
?>
