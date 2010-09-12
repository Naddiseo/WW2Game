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
require_once('scripts/Email.php');

$t = new Template('contact', intval($cgi['t']));

$filter = array(
	'contact-email'  => FILTER_VALIDATE_EMAIL,
	'contact-text'   => FILTER_UNSAFE_RAW,
	'contact-submit' => FILTER_SANITIZE_STRING,
	'e'              => FILTER_VALIDATE_INT
);

$filtered  = filter_input_array(INPUT_POST, $filter);
$filteredG = filter_input_array(INPUT_GET, $filter);

try {
	if ($filtered['contact-submit']) {
		if ($filtered['contact-email'] and verifyEmail($filtered['contact-email'])) {
			$c        = new Contact();
			$c->email = $filtered['contact-email'];
			$c->type  = CONTACT_TYPE_CONTACT_PAGE;
			$c->text  = base64_encode($filtered['contact-text']);
			$c->date  = time();
			$c->done  = 0;
			$id = $c->create();
			
			if (!$id) {
				throw new Exception('Error processing, please submit again');
			}
			else {
				header('Location: contact.php?e=1');
				exit;
			}
		}
		else {
			throw new Exception('A contact email must be submitted');
		}
	}
}
catch (Exception $e) {
	$t->err = $e->getMessage();
}

switch ($filteredG['e']) {
	case 1:
		$t->msg = "Your request was submitted successfully, it may take some time for us to get back to you";
		break;
	default:
		$t->msg = '';	
}

$t->user = $user;
$t->display();
?>
