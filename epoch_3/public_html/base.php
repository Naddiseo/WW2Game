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

new Privacy(PRIVACY_PRIVATE);

$t = new Template('base', intval($cgi['t']));
$saveUser = false;

$filters = array(
	'kick-officer'      => FILTER_VALIDATE_INT,
	'accept-officer'    => FILTER_VALIDATE_INT,
	'officer-list-page' => FILTER_SANITIZE_INT,
	'clickall'          => FILTER_SANITIZE_STRING,
	'leave-commander'   => FILTER_SANITIZE_STRING,
);

/// TODO: fix this page for filters
$filteredG = filter_input_array(INPUT_GET, $filters);
$filtered  = filter_input_array(INPUT_POST, $filters);

//======end vote
if ($filteredG['kick-officer'] > 0) {
	$id = intval($filteredG['kick-officer']);
	if (me($id)) {
		$user->commander = 0;
		$user->accepted = 0;
	}
	else {
		mysql_query("UPDATE User SET commander=0,accepted=0 WHERE id=$id") or die(mysql_error());
	}
	$user->numofficers--;
	$saveUser = true;
}

if ($filteredG['accept-officer'] > 0){
	$id = intval($filteredG['accept-officer']);
	//echo "here";
	if (me($id)) {
		$user->accepted = 1;
	}
	else {
		mysql_query("UPDATE User SET accepted=1 WHERE id=$id") or die(mysql_error());
	}
	$user->numofficers++;
	$saveUser = true;
}


if ($filtered['clickall'] and $user->clickall == 0 and Privacy::getId()){
	
	mysql_query("UPDATE User SET uu=uu+10,gclick=gclick-1 WHERE gclick>0 AND active=1 AND id!=$user->id");

	$user->clickall = 1;
	if ($user->gclick <15) {
		$user->gclick++;
	}
	$saveUser = true;
}
if($filteredG['leave-commander']){
	$user->commander = 0;
	$user->accepted = 0;
	$saveUser = true;
}

if ($saveUser) {
	$user->save();
}

$t->css = true;
$t->args['officer-list-page'] = $filteredG['officer-list-page'];
$t->user = $user;
$t->pageTitle = 'Base';
$t->display();
?>
