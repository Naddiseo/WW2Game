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

$t = new Template('loginverify', intval($cgi['t']));

$filters = array(
	'verify-username' => FILTER_VALIDATE_STRING,
	'verify-email'    => FILTER_VALIDATE_EMAIL,
	'verify-password' => FILTER_UNSAFE_RAW,
	'verify-submit'   => FILTER_VALIDATE_STRING,
	'key'   => FILTER_VALIDATE_STRING,
	'id'    => FILTER_VALIDATE_INT,
);

$filteredG = filter_input_array(INPUT_GET, $filters);
$filtered  = filter_input_array(INPUT_POST, $filters);

$ret = false;

if ($filteredG['key'] and $filteredG['id']) {
	$ret = User::getByKey($filteredG['id'], $filteredG['key']);

	if (!$ret or !$ret->id) {
		$ret = false;
	}
}

if ($filtered['verify-submit']) {
	// User::login does the sql escape
	$ret = User::login($filtered['verify-username'], $filtered['verify-password']);


	if (!$ret->id or $ret->email != $filtered['verify-email']) {
		$t->err = "The username, email, or password do not match any in our database";
		$ret = false;
	}
}

if ($ret) {
	switch ($ret->active) {
		case 0:
			$_SESSION['activationId'] = $ret->id;
			header('Location: activate.php');
			exit;
		case 1:
		case 3:
			Privacy::login($ret->id);
			$ret->active = 1;
			// Update the lastturntime
			$ret->save();
			header('Location: base.php');
			exit;
		case 2:
			$t->msg = "You are set to vacation mode.<br>  Please try to log in again to reactivate your account.";
			User::setActive($ret->id, 1);
			break;
		case 4:
			$t->err = "You account has been suspended.";
			break;
		case 5:
			$t->err = "The username and password do not match any in our database";
			break;
	}
}


$t->user = $user;
$t->pageTitle = 'Login Verification';
$t->display();
?>
