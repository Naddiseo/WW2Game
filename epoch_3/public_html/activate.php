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
require_once('scripts/Activation.php');

$t = new Template('activate');

$filter = array(
	'activation-id'        => FILTER_VALIDATE_INT | FILTER_SANITIZE_NUMBER_INT,
	'activation-username'  => FILTER_SANITIZE_STRING,
	'activation-apassword' => FILTER_UNSAFE_RAW,
	'key'                  => FILTER_SANITIZE_STRING,
	
	'activation-password'  => FILTER_UNSAFE_RAW,
	'activation-passwordv' => FILTER_UNSAFE_RAW,
	
	'activation-submit'    => FILTER_SANITIZE_STRING,
	
);

$filtered  = filter_input_array(INPUT_POST, $filter);
$filteredG = filter_input_array(INPUT_GET, $filter);

$aAccount = new Activation();

if ($filteredG['activation-id']) {
	$aAccount->get($filteredG['activation-id']);
	// The account they're trying to activate exists.
	if ($aAccount->id) {
		if ($aAccount->success) {
			// It's already activated
			header('Location: index.php?e=2');
			exit;
		}
		else {
			// Still needs to activate
			$t->activationId = $aAccount->id;
			$t->username     = $aAccount->username;
		}
	}
}
try {

if ($filtered['activation-submit'] and $filtered['activation-apassword']) {

	$apassword = $filtered['activation-apassword'];
	$password1 = $filtered['activation-password' ];
	$password2 = $filtered['activation-passwordv'];
	$username  = $filtered['activation-username' ];
	
	if (!$apassword) {
		throw new Exception('No Activation password specified');
	}
	
	if (!$password1 or $password1 != $password2) {
		throw new Exception('The new passwords you entered do not match');
	}
	
	$apassword = md5($apassword);
	$password1 = md5($password1);

	if ($filtered['activation-id']) {
		$aAccount->get($filtered['activation-id']);	
	}
	else {
		$aAccount = Activation::getByUsernamePassword($username, $apassword);
	}
	
	if (!$aAccount->id) {
		throw new Exception('That account does not exist');
	}
	
	if ($aAccount->success) {
		header('Location: index.php?e=2');
		exit;
	}
	
	// If we get this far, the account needs to be activated, 
	// and the user provided everything we need.
	$user = new User();
	$user->username = $aAccount->username;
	$user->email    = $aAccount->email;
	$user->password = $password1;
	$user->nation   = $aAccount->nation;
	$user->active   = 1;
	$user->area     = $conf['area-count'];
	$id = $user->create();
	
	if ($id) {
		$aAccount->userId = $id;
		$aAccount->success = 1;
		$aAccount->save();
		header('Location: index.php?e=3');
		exit;
	}
	else {
		throw new Exception('Could not create the user, please try again');
	}	
	
} //if 

if ($filteredG['key']) {
	$t->activationPassword = $filteredG['key'];
}

} // try
catch (Exception $e) {
	$t->err = $e->getMessage();
}

$t->pageTitle = 'Activate Account';
$t->display();
?>
