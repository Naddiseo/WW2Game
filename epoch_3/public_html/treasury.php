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

new Privacy(PRIVACY_PRIVATE);

$t = new Template('treasury', intval($cgi['t']));

$filters = array(
	$_SESSION['depbox'] => FILTER_SANITIZE_NUMBER_INT,
	'turing' => FILTER_SANITIZE_NUMBER_INT,
);
$filtered = filter_input_array(INPUT_POST, $filters);

if ($user->bot) {
	$user->bankimg = 0;
}

if ($filtered[$_SESSION['depbox']]) {
 
	if ($user->bankimg == 1 and (strtolower ($filtered['turing']) != strtolower ($_SESSION['number']))) {
		$t->err = 'Image not matched correctly';
	}
	else { 

		// The amount to deposit
		$gold = $filtered[$_SESSION['depbox']];

		// make sure there's something to deposit
		if ($gold < 1) {
			$gold = 1;
		}
		// make sure they have enough
		if ($gold > $user->gold) {
			$t->err = 'Not enough gold to deposit';
		}
		else {
			$user->gold -= $gold;
			$bankfee = round($user->bankper * 0.01 * $gold);
			if ($bankfee <= 0) {
				$bankfee = 1;
			}
			$gold -= $bankfee;
			$user->bank += $gold;
			$user->bankimg = 0;
			$user->save();
			
			$t->msg = 'You have just banked ' . numecho2($gold) . 'gold. <br />';
			$t->msg .= "Bank free was {$user->bankper}% ( " . numecho2($bankfee) . ' gold )';
			
		}
	}
}	



$t->user = $user;
$t->pageTitle = 'Treasury';
$t->display();

?>
