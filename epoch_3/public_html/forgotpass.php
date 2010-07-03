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
require_once('scripts/Email.php');
require_once('scripts/Activation.php');

$t = new Template('forgotpass', intval($cgi['t']));

$filters = array(
	'forgotpass-email'  => FILTER_VALIDATE_EMAIL,
	'forgotpass-submit' => FILTER_VALIDATE_STRING,
);

$filtered  = filter_input_array(INPUT_POST, $filters);
try {
	if ($filtered['forgotpass-submit']) {
		$email    = $filtered['forgotpass-email'];
		$password = getPassword(); 
	
		if (!$email or !verifyEmail($email)) {
			throw new Exception('Invalid Email address');
		}
		
		$user = new User();
		$user->getByEmail($email);
		if ($user->id and $user->active != 4) {
			// good, they exist.
			$user->password = md5($password);
			$user->active   = 3;
			$user->save();
			
			if (!doEmail($user->username, $password, $user->email)) {
				throw new Exception('Could not send the activation email. Please contact an admin on the forum, or by email');
			}
			
			header('Location: index.php?e=6');
			exit;		 
			
		}
		else {
			// need to check the Activatoin table.
			$a = new Activation();
			$a->getByEmail($email);
			if ($a->id and !$a->success) {
				// Good
				$a->password = md5($password);
				$a->save();
				
				if (!doEmail($a->username, $password, $a->email)) {
					throw new Exception('Could not send the activation email. Please contact an admin on the forum, or by email');
				}
				
				header('Location: index.php?e=6');
				exit;		
			}
			else {
				throw new Exception('That email address does not exists');			
			}
		}
		
		
	} //if 
} // try
catch (Exception $e) {
	$t->err = $e->getMessage();
}

$t->user = $user;
$t->pageTitle = 'Password Recovery';
$t->display();


function doEmail($username, $password, $email) {
	$et = new Template('activation-email', 1);
	$et->username = $username;
	$et->email    = $email;
	$et->password = $password;
	$text = $et->getContents('change-prefs-email-text');
	$html = $et->getContents('change-prefs-email-html');

	$e = new Email($email, "Login credential change notification for $username", $html, $text);
	return $e->send();
}

?>
