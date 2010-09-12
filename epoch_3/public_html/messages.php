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
require_once('scripts/Message.php');
new Privacy(PRIVACY_PRIVATE);

$t = new Template('messages', intval($cgi['t']));

$filters = array(
	'id'          => FILTER_VALIDATE_INT | FILTER_SANITIZE_NUMBER_INT,
	'view'        => FILTER_VALIDATE_INT | FILTER_SANITIZE_NUMBER_INT,
	'e'           => FILTER_VALIDATE_INT | FILTER_SANITIZE_NUMBER_INT,
	'delete'      => array(
		'filter'  => FILTER_VALIDATE_INT,
		'flags'   => FILTER_FORCE_ARRAY,
		'options' => array('min_range' => 1)
	),
	'age'         => FILTER_VALIDATE_INT | FILTER_SANITIZE_NUMBER_INT,

);


$filteredG = filter_input_array(INPUT_GET,  $filters);
$filtered  = filter_input_array(INPUT_POST, $filters);

$age      = $filteredG['age'] ? $filteredG['age'] : ($filtered['age'] ? $filtered['age'] : $current_age); 
$t->view  = ($filteredG['view'] == 1  or $filtered['view'] == 1) ? 1 : 0;
$msgId    = $filteredG['id']; 
$e        = $filteredG['e'];
$delete   = $filtered['delete'];

if (!$age) {
	$age = $current_age;
}

try {
	
	if (is_array($delete) and count($delete)) {
		if ($t->view == 1) {
			Message::deleteSenderMulti($user->id, $delete);
		}
		else {
			Message::deleteTargetMulti($user->id, $delete);
		}
	}
	
	if ($msgId > 0) {
		$t->message = new Message();
		$t->message->get($msgId);
		if ($t->message->targetId != $user->id and $t->message->senderId != $user->id) {
			$t->message = null;
			throw new Exception('You cannot view that message');
		}
		if (me($t->message->targetId) and $t->message->targetStatus == MSG_STATUS_UNREAD) {
			if (!$_SESSION['admin']) {
				$user->unreadMsg--;
				$user->save();
				$t->message->targetStatus = MSG_STATUS_READ;
				$t->message->save();
			}
		}
		//$t->onload    = 'Message.bb2html(document.getElementById("message-text-pre"));';
	}

}
catch (Exception $e) {
	$t->err = $e->getMessage();
}


if (!$t->message) {
	$t->messages = ($t->view == 0) ? Message::getInbox($user->id, $age) : Message::getOutbox($user->id, $age);
}

switch ($e) {
	case 1:
		$t->msg = 'Your message was sent';
		break;
	default:
		break;
}

$t->age       = $age;

$t->js        = true;
$t->css       = true;

$t->user      = $user;
$t->pageTitle = ($t->view == 1 ? 'Outbox' : 'Inbox');
if ($msgId) {
	$t->pageTitle = 'Message';
}
$t->display();
?>
