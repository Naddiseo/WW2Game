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
require_once('scripts/Activation.php');
require_once('scripts/Email.php');

$t = new Template('register', intval($cgi['t']));

$filter = array(
	'register-username' => FILTER_UNSAFE_RAW,
	'register-email'    => FILTER_VALIDATE_EMAIL,
	'register-emailv'   => FILTER_VALIDATE_EMAIL,
	
	'register-nation'   => FILTER_VALIDATE_INT | FILTER_SANITIZE_NUMBER_INT,
	
	'register-rules'    => FILTER_VALIDATE_BOOLEAN,
	
	'register-captcha'  => FILTER_VALIDATE_INT | FILTER_SANITIZE_NUMBER_INT,
	
	'referrer'          => FILTER_VALIDATE_INT | FILTER_SANITIZE_NUMBER_INT,
	
	'register-referrer' => FILTER_VALIDATE_INT | FILTER_SANITIZE_NUMBER_INT,
	
	'register-submit'   => FILTER_SANITIZE_STRING,
	
);

$filtered  = filter_input_array(INPUT_POST, $filter);
$filteredG = filter_input_array(INPUT_GET, $filter);

if ($filteredG['register-referrer'] or $filteredG['referrer']) {
	$t->referrerId = $filteredG['register-referrer'] ? $filteredG['register-referrer'] : $filteredG['referrer'];
}

try {
	if ($filtered['register-submit']) {	

		$t->username   = $username  = $filtered['register-username'];
		$t->email      = $email     = $filtered['register-email'   ];
		$t->emailv     = $emailv    = $filtered['register-emailv'  ];
		$t->nation     = $nation    = max(0, min($filtered['register-nation'], 4));
		$t->rules      = $rules     = $filtered['register-rules'   ];
		$t->referrerId = $filtered['register-referrer'];
		$captcha       = $filtered['register-captcha' ];
		
		$uLength = strlen($username);
		
		if (!User::validateUsername($username)) {
			throw new Exception('Invalid Username');
		}
		
		if ($email != $emailv) {
			throw new Exception('The Email addresses do not match'); 
		}
	
		if (!$email or !verifyEmail($email)) {
			throw new Exception('Invalid Email address');
		}

		if (stripos($email, "ww2game.net") !== false) {
			throw new Exception('Invalid Email address');
		}
	
		if (!$rules) {
			throw new Exception('You must read, and agree to, the Rules and Terms of Service');
		}
		
		if ($captcha != $_SESSION['number']) {
			throw new Exception('You failed to match the captcha to the correct number');
		}
		
		
		if (Activation::getByUsernameEmailCount($username, $email) > 0) {
			header('Location: index.php?e=4');
			exit;
		}
		
		if (User::getByUsernameEmailCount($username, $email) > 0) {
			throw new Exception('That username or email already exists. If you have forgotten your password, please use the resend password link');
		}
	
		// if we get here, we can add the user.
		$password      = getPassword();
		$a             = new Activation();
		$a->username   = $username;
		$a->email      = $email;
		$a->password   = md5($password);
		$a->nation     = $nation;
		$a->IP         = $_SERVER['REMOTE_ADDR'];
		$a->referrerId = $t->referrerId;
		$a->time       = time();
		$id            = $a->create();
	
		if ($id) {
			// Can send the email
			$et           = new Template('activation-email', 1);
			$et->username = $username;
			$et->password = $password;
			$text         = $et->getContents('activation-email-text');
			$html         = $et->getContents('activation-email-html');
				
			$e = new Email($email, "Welome to WWII::Game $username", $html, $text);
			
			if (!$e->send()) {
				throw new Exception('Could not send the activation email. Please contact an admin on the forum, or by email');
			}
			
			header('Location: index.php?e=5');
			exit;
		}
		else {
			throw new Exception('Could not create the user, please try again');
		}	
	
	} //if
	


} // try
catch (Exception $e) {
	$t->err = $e->getMessage();
}

if ($t->referrerId) {
	$t->referrer   = new User();
	$t->referrer->get($t->referrerId);
}

$t->css       = true;
$t->user      = $user;
$t->pageTitle = 'Register Account';
$t->display();
?>
