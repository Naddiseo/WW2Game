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
require_once('scripts/Alliance.php');

new Privacy(PRIVACY_USER);

$t = new Template('alliance-edit', intval($cgi['t']));

$filters = array(
	// edit create
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

try {
	$t->alliance    = new Alliance();
	if ($user->alliance and $user->aaccepted) {
		$t->alliance->get($user->alliance);
	
		if (!$t->alliance->isLeader($user)) {
			header('Location: alliance-home.php');
			exit;
		}
	
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
		
			$alliances = Alliance::queryNameTag($name, $tag);
		
			if (count($alliances) != 1 or $alliances[0]->id != $t->alliance->id) {
				throw new Exception('The name or tag you gave is already in use');
			}
			
			$t->alliance->name         = $name;
			$t->alliance->password     = $password;
			$t->alliance->tag          = $tag;
			$t->alliance->status       = $status;
			$t->alliance->url          = $url;
			$t->alliance->irc          = $channel;
			$t->alliance->ircServer    = $server;
			$t->alliance->message      = $message;
			$t->alliance->news         = $news;
			
			$t->alliance->save();
		}
	}
	else {
		header('Location: alliance-list.php');
		exit;
	}
} // try
catch (Exception $e) {
	$t->err = $e->getMessage();
}

$t->pageTitle = 'Alliance Edit';
$t->display();
?>
