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
require_once('scripts/Message.php');
require_once('scripts/Ignore.php');


new Privacy(PRIVACY_PRIVATE);

$t = new Template('writemail', intval($cgi['t']));

$filters = array(
	'to'          => array(
		'filter'  => FILTER_VALIDATE_INT,
		'flags'   => FILTER_FORCE_ARRAY,
		'options' => array('min_range' => 1)
	),
	'officers'    => FILTER_SANITIZE_STRING,
	'alliance'    => FILTER_SANITIZE_STRING,
	'msg-id'      => FILTER_VALIDATE_INT | FILTER_SANITIZE_NUMBER_INT,
	'subject'     => FILTER_UNSAFE_RAW,
	'text'        => FILTER_UNSAFE_RAW,
	'msg-submit'  => FILTER_SANITIZE_STRING,
	'admin'       => FILTER_SANITIZE_STRING,
	
	'user-search' => FILTER_SANITIZE_STRING,
	'search-name' => FILTER_SANITIZE_STRING,
);


$filteredG = filter_input_array(INPUT_GET, $filters);
$filtered  = filter_input_array(INPUT_POST, $filters);

$toG = count($filteredG['to']) ? $filteredG['to'] : array();
$toP = count($filtered['to'])  ? $filtered['to']  : array();

$to      = array_unique(array_merge($toG, $toP));
$subject = substr($filtered['subject'], 0, 255);
$text    = $filtered['text'   ];

$msgId   = $filteredG['msg-id'] ? $filteredG['msg-id'] : ($filtered['msg-id'] ? $filtered['msg-id'] : 0);

$searchName = $filtered['search-name'];

$blockedMe = Ignore::getUserIdsWhoBlockedUser($user);

$t->targets     = array();
$t->searchUsers = array();
$t->toArray     = '';
$t->quote       = '';
$t->alliance    = isset($filtered['alliance']);
$t->officers    = isset($filtered['officers']);
$t->msgId       = $msgId;

try {
	
	if (!strlen($subject)) {
		$subject = _('(No Subject)');
	}
	
	if ($msgId and !($subject and $text)) {
		$msg = new Message();
		$msg->get($msgId);
		if (me($msg->targetId) or me($msg->senderId)) {
			$t->quote          = $msg;
			$t->quote->subject = 'RE: ' . $msg->subject;
			$t->quote->text    = "[quote]$msg->text[/quote]\n\n";
		}
	}
	else if ($subject or $text) {
		$t->quote->subject = $subject;
		$t->quote->text    = $text;
	}
	
	if (count($to)) {
		foreach ($to as $toId) {
			if (!$t->targets[$toId]) {
				$u = new User();
				$u->get($toId);
				$t->targets[$toId] = $u;
				$t->toArray .= "&amp;to[]=$toId";
			}
		}
	}


	if ($filtered['user-search'] and $searchName) {
		$t->searchUsers = User::queryIDByUsername($searchName);
	}

	// 11 Nov, 09: added aaccepted, make sure the user is accepted in the alliance
	// first before allowing him to message everyone
	if ($t->alliance and $user->alliance and $user->aaccepted and $filtered['msg-submit']) {
		// grab alliance
		$members = $user->getAlliance()->getMembers();
		foreach ($members as $member) {
			if (!$t->targets[$member->id]) {
			 	$t->targets[$member->id] = $member;
			 	$to[]                    = $member->id;
			 }
		}
	}
	if ($t->officers and $filtered['msg-submit']) {
		$officers = $user->getOfficers();
		foreach ($officers as $officer) {
			if (!$t->targets[$officer->id]) {
			 	$t->targets[$officer->id] = $officer;
			 	$to[]                     = $officer->id;
			 }
		}
	}


	
	if (count($to) > 0 and $filtered['msg-submit']) {

		// Loop through targets and remove the ones who have blocked me	
		$to         = array_filter($to, 'blocked_cb1');
		$t->targets = array_filter($t->targets, 'blocked_cb2');
	

		foreach ($t->targets as $toId) {

			
			$t->message = new Message();
			$t->message->targetId     = $toId->id;
			$t->message->senderId     = $user->id;
			$t->message->subject      = htmlentities($subject, ENT_QUOTES, 'UTF-8');
			$t->message->text         = htmlentities($text, ENT_QUOTES, 'UTF-8');
			$t->message->targetStatus = MSG_STATUS_UNREAD;
			$t->message->senderStatus = MSG_STATUS_READ;
			$t->message->date         = time();
			$t->message->fromadmin    = (($filtered['admin'] and $user->admin) ? 1: 0);
			$t->message->age          = $current_age;
			$t->message->create();
			
		}
		// Increment the mail/unread counts for every user in the list
		// This function does array_unique on the to array.
		User::incrMailForIds($to);
		
		header('Location: messages.php?e=1');
		exit;		
	}

}
catch (Exception $e) {
	$t->err = $e->getMessage();
}


// get officers.

//$t->onload = 'Message.init();';
$t->css = 1;
$t->js = true;
$t->user = $user;
$t->pageTitle = ($t->view == 1 ? _('Outbox') : _('Inbox'));
if ($msgId) {
	$t->pageTitle = 'Message';
}
$t->display();

function blocked_cb1($var) {
	global $blockedMe;
	
	return !$blockedMe[$var];
}

function blocked_cb2($var) {
	global $blockedMe;
	
	return !$blockedMe[$var->id];
}
?>
