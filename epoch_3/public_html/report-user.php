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
require_once('scripts/Contact.php');

new Privacy(PRIVACY_PRIVATE);


$t = new Template('report-user', intval($cgi['t']));

$filter = array(
	'uid' => FILTER_VALIDATE_INT | FILTER_SANITIZE_NUMBER_INT,
	'reportuser-text'  => FILTER_UNSAFE_RAW,
	'reportuser-submit' => FILTER_UNSAFE_RAW
);

$filteredG = filter_input_array(INPUT_GET, $filter);
$filtered  = filter_input_array(INPUT_POST, $filter);

$t->uid = $filteredG['uid'] ? $filteredG['uid'] : $filtered['uid'];

if (!$t->uid) {
	header('Location: battlefield.php');
	exit;
}
try {
	if ($filtered['reportuser-text'] and $filtered['reportuser-submit']) {
		$c = new Contact();
		$c->email = $user->id;
		$c->type  = CONTACT_TYPE_REPORT_USER;
		$c->text  = base64_encode($filtered['reportuser-text']);
		$c->date  = time();
		$c->reference = $t->uid;
		$c->done  = 0;
		$id = $c->create();
	
		if (!$id) {
			throw new Exception('Error processing, please submit again');
		}
		else {
			$t->msg = 'Report submitted successfully';
		}
	}
}
catch (Exception $e) {
	$t->err = $e->getMessage();
}


$t->user = $user;
$t->pageTitle = 'Report User';
$t->display();
?>
