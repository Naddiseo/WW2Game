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
require_once('scripts/Email.php');

new Privacy(PRIVACY_PRIVATE);

$t = new Template('prefs', intval($cgi['t']));


$filter = array(
	'prefs-username'      => FILTER_UNSAFE_RAW,
	
	'prefs-email'         => FILTER_VALIDATE_EMAIL,
	'prefs-emailv'        => FILTER_VALIDATE_EMAIL,
	
	'prefs-old-password'  => FILTER_UNSAFE_RAW,
	'prefs-new-password'  => FILTER_UNSAFE_RAW,
	'prefs-new-passwordv' => FILTER_UNSAFE_RAW,
	
	'prefs-nation'        => FILTER_VALIDATE_INT,
	
	'prefs-minhit'        => FILTER_SANITIZE_NUMBER_INT,
	
	'prefs-vacation'      => FILTER_SANITIZE_STRING,
	
	'prefs-submit'        => FILTER_SANITIZE_STRING
);

$filtered = filter_input_array(INPUT_POST, $filter);

try {

	if ($filtered['prefs-submit']) {
		$username     = $filtered['prefs-username'     ];
		$email        = $filtered['prefs-email'        ];
		$emailv       = $filtered['prefs-emailv'       ];
		$oldpassword  = $filtered['prefs-old-password' ];
		$newpassword  = $filtered['prefs-new-password' ];
		$newpasswordv = $filtered['prefs-new-passwordv'];
		$nation       = intval($filtered['prefs-nation']);
		$minhit       = $filtered['prefs-minhit'       ];

		$nation       = max(0, min($nation, 4));
	
		$save         = false;
	
		if (!$user->changenick and $username != $user->username) {
			if (User::validateUserName($username)) {
				if (User::getByUsernameEmailCount($username, '') == 0) {
					require_once('scripts/Activation.php');
					
					// now check the activation table..
					if (Activation::checkUsernameEmailAndNotMe($user->id, $username, '') == 0) {
						$user->username = $username;
						$user->changenick = 1;
						$user->active = 3;
						$user->key    = md5($username . time());
						$user->save();
						Privacy::logout();
						
						$et = new Template('activation-email', 1);
						$et->key = $user->key;
						$et->user = $user;
						$et->username = $username;
						$text = $et->getContents('change-prefs-email-text');
						$html = $et->getContents('change-prefs-email-html');
				
						$e = new Email($email, "Login credential change notification for $username", $html, $text);
			
						if (!$e->send()) {
							throw new Exception('Could not send the email. Please contact an admin on the forum, or by email');
						}
									
						header('Location: index.php?e=6');
						exit;
					}
					else {
						throw new Exception('That username is in use');
					}
				}
				else {
					throw new Exception('That username is in use');
				}
			}
			else {
				throw new Exception('Invalid Username');
			}
		} 
		
		if ($oldpassword) {
			if (md5($oldpassword) == $user->password) {
				if ($newpassword == $newpasswordv) {
					$user->password = md5($newpassword);
					$user->active = 3;
					$user->key    = md5($newpassword . time());
					$user->save();
					Privacy::logout();
					
					$et = new Template('activation-email', 1);
					$et->key = $user->key;
					$et->user = $user;
					$et->username = $username;
					$et->password = $newpassword;
					$text = $et->getContents('change-prefs-email-text');
					$html = $et->getContents('change-prefs-email-html');
			
					$e = new Email($email, "Login credential change notification for $username", $html, $text);
		
					if (!$e->send()) {
						throw new Exception('Could not send the email. Please contact an admin on the forum, or by email');
					}
								
					header('Location: index.php?e=6');
					exit;				
				}
				else {
					throw new Exception('Passwords do not match');
				}
			}
			else {
				throw new Exception('Your old password is incorrect');
			}
		}
		
		if ($nation != $user->nation) {
			if ($conf['can-change-nation']) {
				if (time() > $starttime + (60 * 60 * 48)) {
					$user->bank = floor($user->bank * 0.5);
					$user->gold = 0;
					Weapon::deleteAllUserWeapons($user->id);
				}
				$user->nation = $nation;
				$save = true;
			}
			else {
				throw new Exception('You cannot change nation this late into the age');
			}
		}
		
		if ($email != $user->email) {
			if ($email == $emailv and verifyEmail($email)) {
				if (User::getByUsernameEmailCount('', $email) == 0) {
					require_once('scripts/Activation.php');
					
					// now check the activation table..
					if (Activation::checkUsernameEmailAndNotMe($user->id, '', $email) == 0) {
						$user->email  = $email;
						$user->active = 3;
						$user->key    = md5($email . time());
						$user->save();
						
						Privacy::logout();
						
						$et           = new Template('activation-email', 1);
						$et->user     = $user;
						$et->key      = $user->key;
						$et->username = $username;
						$et->email    = $email;
						$text         = $et->getContents('change-prefs-email-text');
						$html         = $et->getContents('change-prefs-email-html');
				
						$e            = new Email($email, "Login credential change notification for $username", $html, $text);
			
						if (!$e->send()) {
							throw new Exception('Could not send the email. Please contact an admin on the forum, or by email');
						}
									
						header('Location: index.php?e=6');
						exit;
					}
					else {
						throw new Exception('That email is in use');
					}
				}
				else {
					throw new Exception('That email is in use');
				}
			}
			else {
				throw new Exception('Invalid Emails');
			}
		}
		
		if ($minhit) {
			$user->minattack = $minhit;
			$save = true;
		}
		
		
		
		if ($save) {
			$user->cacheStats();
			$t->msg = 'Your account was updated';
		}
		
	} //if submitted
	
	if ($filtered['prefs-vacation'] == "yes" and $allow_vacation) {
		$user->vacation = time() + (2 * 24 * 60 * 60);
		$user->active = 2;
		$user->cacheStats();
		Privacy::logout();
		header('Location: index.php');
		exit;
	}
}
catch (Exception $e) {
	$t->err = $e->getMessage();
}

$t->allow_vacation = $allow_vacation;
$t->user = $user;
$t->pageTitle = 'Preferences';
$t->display();
?>
