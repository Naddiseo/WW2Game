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

$t = new Template('message-ignore', intval($cgi['t']));

$filters = array(
	'ignore-username'   => FILTER_SANITIZE_STRING,
	'ignore-note'       => FILTER_SANITIZE_STRING,
	'ignore-add-submit' => FILTER_SANITIZE_STRING,
	
	'ignore-remove-submit' => FILTER_SANITIZE_STRING,
	'ignore-remove'        => array(
		'filter'  => FILTER_VALIDATE_INT,
		'flags'   => FILTER_FORCE_ARRAY,
		'options' => array('min_range' => 1)
	),
);

$filtered  = filter_input_array(INPUT_POST, $filters);


try {
	if ($filtered['ignore-add-submit'] and $filtered['ignore-username']) {
		// find user.
		$t->searchUsers = User::queryIDByUsername($filtered['ignore-username']);
		if (count($t->searchUsers) != 1) {
			throw new Exception('Could not find user');
		}
		
		if (Ignore::isBlocked($user, $t->searchUsers[0])) {
			// User already blocked
		}
		else {
		
			$ignore           = new Ignore();
			$ignore->time     = time();
			$ignore->userId   = $user->id;
			$ignore->targetId = $t->searchUsers[0]->id;
			$ignore->note     = $filtered['ignore-note'];
			$id = $ignore->create();
			
			if ($id) {
				$t->msg = 'User is now blocked';
			}
			else {
				throw new Exception('Could not block user, please try again');
			}
		}
		
	}
	
	if ($filtered['ignore-remove-submit'] and is_array($filtered['ignore-remove'])) {
		Ignore::removeIds($user, $filtered['ignore-remove']);
	}

	$t->ignoreList = Ignore::getAll($user);

}
catch (Exception $e) {
	$t->err = $e->getMessage();
}


switch ($e) {
	case 1:
		$t->msg = 'User is now blocked';
		break;
	default:
		break;
}

$t->user      = $user;
$t->pageTitle = 'Ignore List';
$t->display();
?>
