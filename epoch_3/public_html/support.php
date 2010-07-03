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
require_once('scripts/Transaction.php');
require_once('scripts/Paypal.php');

new Privacy(PRIVACY_PRIVATE);

$t = new Template('support', intval($cgi['t']));


$filter = array(
	'uid'               => FILTER_VALIDATE_INT | FILTER_SANITIZE_NUMBER_INT,

	'support-type'      => FILTER_VALIDATE_INT | FILTER_SANITIZE_NUMBER_INT,
	'support-userId'    => FILTER_VALIDATE_INT | FILTER_SANITIZE_NUMBER_INT,
	'paypal-submit_x'   => FILTER_VALIDATE_INT | FILTER_SANITIZE_NUMBER_INT,
	'e'                 => FILTER_SANITIZE_NUMBER_INT | FILTER_VALIDATE_INT,
	
	// for step 3
	'token'             => FILTER_SANITIZE_STRING,
	'PayerID'           => FILTER_SANITIZE_STRING,
	
	// step 4
	'paypal-verify-txn' => FILTER_SANITIZE_STRING,
);
$filteredG = filter_input_array(INPUT_GET, $filter);
$filtered  = filter_input_array(INPUT_POST, $filter);

$error = $filteredG['e'];

switch ($filtered['support-type']) {
	case 5:
		$paymentAmount = 5.00;
		break;
	case 1:
	default:
		$paymentAmount = 1.00;
}

// Generate Transaction stuff
$txn                   = new Transaction();

if ($filteredG['token'] and $filteredG['PayerID'] and !$filtered['paypal-verify-txn']) {
	$txn->getByToken($filteredG['token']);
	$txn->payerId = $filteredG['PayerID'];
	$txn->save();
	$t->ppVerify = $txn;
	$for = new User();
	$t->forName = '';
	if ($txn->id) {
		$for->get($txn->forId);
		$t->forName = $for->getNameLink();
	}
}
else if ($filtered['token'] and $filtered['paypal-verify-txn']) {
	$txn->getByToken($filtered['token']);
	$pp = new Paypal();
	if ($pp->step4($txn)) {
		// good, can update the user.
		$u = new User();
		$u->get($txn->forId);
		$u->supporter += floor($txn->amount);
		$u->save();
		header('Location: support.php?e=4');
		exit;
	}
	else {
		$t->err = "There was an error processing the transaction, please contact an admin in the forum with the following transaction ID: $txn->id";
	}

}
else {
	$txn->time       = time();
	$txn->amount     = $paymentAmount;
	$txn->userId     = Privacy::getId();
	if (!$filtered['support-userId']) {
		$txn->forId  = Privacy::getId();
	}
	else {
		$txn->forId      = $filtered['support-userId'];
	}
	$txn->isAlliance = 0;
	$t->uid = $filteredG['uid'];
	if ($filtered['paypal-submit_x']) {
	
		$txnId = $txn->create();
		$pp = new Paypal();
	
		if(!$pp->step1And2($txn)) {
			//Redirecting to APIError.php to display errors. 
			$error = 3;
		}
	}
}

switch ($error) {
	// return URL (success)
	case 1:
		
		break;
	case 2: // cancel
		$t->err = 'Your transaction was cancelled';
		break;
	case 3: // api error
		$t->err = "The was an error processing your transaction. Please contact an administrator in the forum with the following transaction ID: $txn->id.";
		break;
	case 4:	// end success
		$t->msg = 'Thank you for supporting WW2';
		break;
	default:
		// do nothing
}
$t->user = $user;
$t->pageTitle = 'Become a Supporter';
$t->display();
?>
