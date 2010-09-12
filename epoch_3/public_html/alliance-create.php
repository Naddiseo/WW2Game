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
require_once('scripts/Alliance.php');

new Privacy(PRIVACY_USER);

$t = new Template('alliance-create', intval($cgi['t']));

$filters = array(
	// create
	'alliance-name'       => FILTER_SANITIZE_STRING,
	'alliance-password'   => FILTER_UNSAFE_RAW,
	'alliance-tag'        => FILTER_SANITIZE_STRING,
	'alliance-url'        => FILTER_VALIDATE_URL,
	'alliance-ircserver'  => FILTER_SANITIZE_STRING,
	'alliance-ircchannel' => FILTER_SANITIZE_STRING,
	'alliance-status'     => FILTER_VALIDATE_INT,
	'alliance-message'    => FILTER_SANITIZE_STRING,
	'alliance-news'       => FILTER_UNSAFE_RAW,
	'alliance-submit'     => FILTER_SANITIZE_STRING,
);

$filtered = filter_input_array(INPUT_POST, $filters);

$t->alliance = new Alliance();

try {
	if ($filtered['alliance-submit']) {
		$name     = $filtered['alliance-name'        ];
		$password = md5($filtered['alliance-password']);
		$tag      = $filtered['alliance-tag'         ];
		$url      = $filtered['alliance-url'         ];
		$server   = $filtered['alliance-ircserver'   ];
		$channel  = $filtered['alliance-ircchannel'  ];
		$status   = $filtered['alliance-status'      ];
		$status   = max(min($status, 1), 0);
		$message  = htmlentities($filtered['alliance-message'], ENT_QUOTES, 'UTF-8');
		$news     = htmlentities($filtered['alliance-news'   ], ENT_QUOTES, 'UTF-8');
		
		if ($alliances = Alliance::queryNameTag($name, $tag)) {
			// They might be trying to resurrect an alliance, check for dead alliances
			
			foreach ($alliances as $a) {
				if ($name == $a->name and $a->isDead($user)) {
					// Okay, try to resurrect
					if ($password == $a->password) {
						// Okay, they have the name and password right
						// Check if the leaders are null
						if ($a->hasNullLeaders()) {
							// Make the user the leader
							$t->alliance = $a;
							$a->status = 0;
							$a->addLeader($user);
							$a->save();
							header('Location: alliance-home.php');
							exit;
						}
						else if (!$a->hasFullLeaders()) {
							if ($a->isLeader($user)) {
								// check if they are already a leader
								$a->status = 0;
								$t->alliance = $a;
								$a->addMember($user, 1);
								$a->save();
								header('Location: alliance-home.php');
								exit;
							}
							else if ($a->addLeader($user)) {
								// Try to add them
								$a->status = 0;
								$a->save();
								header('Location: alliance-home.php');
								exit;
							}
							else {
								// user wasn't a leader, and we couldn't add them
								continue;
							}
						}
						else {
							// All the leader spots are full
							// So we kick #1
							$a->leaderId = 0;
							$a->addLeader($user);
							$a->save();
							header('Location: alliance-home.php');
							exit;
						}
						
					}
					else {
						throw new Exception('Alliance password incorrect');
					}
				} // else, go to next
			}
			
			// if we get here, the alliance doesn't exist, and we shouldn't get here			
			throw new Exception('Alliance with that name or tag does not exist');
		}
		else {
			// No name or tag, create a new alliance.
			$t->alliance->name         = $name;
			$t->alliance->password     = $password;
			$t->alliance->tag          = $tag;
			$t->alliance->leaderId1    = $user->id;
			$t->alliance->status       = $status;
			$t->alliance->url          = $url;
			$t->alliance->irc          = $channel;
			$t->alliance->ircServer    = $server;
			$t->alliance->message      = $message;
			$t->alliance->news         = $news;
			$t->alliance->creationdate = time();
			$id = $t->alliance->create();
			if ($id) {
				$t->alliance->addMember($user, 1);
				header('Location: alliance-home.php');
				exit;
			}
			// Shouldn't get here
			throw new Exception('Error creating alliance, please try again');
			
		}
		
	}
} // try
catch (Exception $e) {
	$t->err = $e->getMessage();
}

$t->pageTitle = 'Alliance Create';
$t->display();
?>
