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

$t = new Template('index', intval($cgi['t']));

$filters = array(
	'login-username' => FILTER_VALIDATE_STRING,
	'login-password' => FILTER_UNSAFE_RAW,
	'login-submit' => FILTER_VALIDATE_STRING,
	'e' => FILTER_VALIDATE_INT | FILTER_SANITIZE_NUMBER_INT
);

$filtered  = filter_input_array(INPUT_POST, $filters);
$filteredG = filter_input_array(INPUT_GET, $filters);

if ($filtered['login-submit']) {
	// User::login does the sql escape
	$ret = User::login($filtered['login-username'], $filtered['login-password']);


	if (!$ret->id) {
		$t->err = "The username and password do not match any in our database";
	}
	else {
		switch ($ret->active) {
			case 0:
				$_SESSION['activationId'] = $ret->id;
				header('Location: activate.php');
				exit;
			case 1:
				Privacy::login($ret->id);
				// Update the lastturntime
				$ret->save();
				header('Location: base.php');
				exit;
			case 2:
				if ($ret->vacation < time()) {					
					User::setActive($ret->id, 1);
					Privacy::login($ret->id);
					header('Location:base.php');
					exit;
				}
				else {
					$t->msg = 'You are set to vacation mode until ' . date("M d Y, H:i", $ret->vacation); 
				}
				
				break;
			case 3:
				// Must verify login credentials
				header('Location: loginverify.php');
				exit;
				break;
			case 4:
				$t->err = "You account has been suspended.";
				break;
			case 5:
				$t->err = "The username and password do not match any in our database";
				break;
		}
	}
}

switch ($filteredG['e']) {
	case 1:
		$t->err = 'You must be logged in to view that page';
		break;
	case 2:
		$t->err = 'That account has already been activated';
		break;
	case 3:
		$t->msg = 'You account is now activated, please login with your new password';
		break;
	case 4:
		$t->msg = 'You have already signed up for an account. If you have not yet activated and are having trouble, please contact an admin in the forum';
		break;
	case 5:
		$t->msg = 'You have registered your account. Please wait up to 24 hours for it to come. It may end up in your junk or spam folder. If you have trouble contact an admin by email or in the forum';
		break;
	case 6:
		$t->msg = 'You have changed your login credentials, a verification email was sent to you. Please wait up to 24 hours for it to come. It may end up in your junk or spam folder. If you have trouble contact an admin by email or in the forum';
		break;
	default:
		break;
}

$t->user = $user;
$t->pageTitle = 'Game';
$t->display();
?>
