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

$t = new Template('train', intval($cgi['t']));
$t->merc = getMercs();

$filter = array(
	'train-train' => FILTER_SANITIZE_STRING,
	'train-sasoldiers' => FILTER_VALIDATE_INT | FILTER_SANITIZE_NUMBER_INT,
	'train-dasoldiers' => FILTER_VALIDATE_INT | FILTER_SANITIZE_NUMBER_INT,
	'train-spies' => FILTER_VALIDATE_INT | FILTER_SANITIZE_NUMBER_INT,
	'train-specialforces' => FILTER_VALIDATE_INT | FILTER_SANITIZE_NUMBER_INT,
	'train-samerc' => FILTER_VALIDATE_INT | FILTER_SANITIZE_NUMBER_INT,
	'train-damerc' => FILTER_VALIDATE_INT | FILTER_SANITIZE_NUMBER_INT,

	'train-untrain' => FILTER_SANITIZE_STRING,
	'untrain-sasoldiers' => FILTER_VALIDATE_INT | FILTER_SANITIZE_NUMBER_INT,
	'untrain-dasoldiers' => FILTER_VALIDATE_INT | FILTER_SANITIZE_NUMBER_INT,
	'untrain-spies' => FILTER_VALIDATE_INT | FILTER_SANITIZE_NUMBER_INT,
	'untrain-specialforces' => FILTER_VALIDATE_INT| FILTER_SANITIZE_NUMBER_INT,


);

$filtered = filter_input_array(INPUT_POST, $filter);
try {
	if ($filtered['train-train']) {
		
		$sasoldiers    = min(max(0, $filtered['train-sasoldiers'   ]), $user->uu);
		$dasoldiers    = min(max(0, $filtered['train-dasoldiers'   ]), $user->uu);
		$spies         = min(max(0, $filtered['train-spies'        ]), $user->uu);
		$specialforces = min(max(0, $filtered['train-specialforces']), $user->uu);
		$samercs       = min(max(0, $filtered['train-samerc'       ]), $t->merc->samercs);
		$damercs       = min(max(0, $filtered['train-damerc'       ]), $t->merc->damercs);

		$samcost  = $conf['cost']['samerc'] * $samercs;
		$damcost  = $conf['cost']['damerc'] * $damercs;

		$sacost            = $conf['cost']['sasoldier'    ] * $sasoldiers;
		$dacost            = $conf['cost']['dasoldier'    ] * $dasoldiers;
		$spycost           = $conf['cost']['spy'          ] * $spies;
		$specialforcescost = $conf['cost']['specialforces'] * $specialforces;

		if ($user->canBuy($sacost)) {
			$user->buy($sacost, 'sasoldiers', $sasoldiers);
			$user->uu -= $sasoldiers;
		}
		else {
			throw new Exception('Not enough gold');
		}

		if ($user->canBuy($dacost)) {
			$user->buy($dacost, 'dasoldiers', $dasoldiers);
			$user->uu -= $dasoldiers;
		}
		else {
			throw new Exception('Not enough gold');
		}

		if ($user->canBuy($spycost)) {
			$user->buy($spycost, 'spies', $spies);
			$user->uu -= $spies;
		}
		else {
			throw new Exception('Not enough gold');
		}

		if ($user->canBuy($specialforcescost)) {
			$user->buy($specialforcescost, 'specialforces', $specialforces);
			$user->uu -= $specialforces;
		}
		else {
			throw new Exception('Not enough gold');
		}

		$u = false;

		if ($user->canBuy($samcost)) {
			$user->buy($samcost, 'samercs', $samercs);
			$t->merc->samercs -= $samercs;
			$u = true;
		}
		else {
			throw new Exception('Not enough gold');
		}

		if ($user->canBuy($damcost)) {
			$user->buy($damcost, 'damercs', $damercs);
			$t->merc->damercs -= $damercs;
			$u = true;
		}
		else {
			throw new Exception('Not enough gold');
		}

		if ($u) {
			saveMercs($t->merc->samercs, $t->merc->damercs);
		}

		// calls save
		$user->cacheStats();
	}
	else if ($filtered['train-untrain']) {
		
		$sasoldiers    = max(0, $filtered['untrain-sasoldiers'   ]);
		$dasoldiers    = max(0, $filtered['untrain-dasoldiers'   ]);
		$spies         = max(0, $filtered['untrain-spies'        ]);
		$specialforces = max(0, $filtered['untrain-specialforces']);
		
		if ($sasoldiers) {
			if ($sasoldiers > $user->sasoldiers) {
				throw new Exception('Not enough attack soldiers to untrain');
			}
			$user->sasoldiers -= $sasoldiers;
			$user->uu += $sasoldiers;
		}

		if ($dasoldiers) {
			if ($dasoldiers > $user->dasoldiers) {
				throw new Exception('Not enough defense soldiers to untrain');
			}
			$user->dasoldiers -= $dasoldiers;
			$user->uu += $dasoldiers;
		}

		if ($spies) {			
			if ($spies > $user->spies) {
				throw new Exception('Not enough spies to untrain');
			}
			$user->spies -= $spies;
			$user->uu += $spies;
		}

		if ($specialforces) {
			if ($specialforces > $user->specialforces) {
				throw new Exception('Not enough special forces to untrain');
			}
			$user->specialforces -= $specialforces;
			$user->uu += $specialforces;
		}
		$user->cacheStats();
	}
}
catch (Exception $e) {
	$t->err = $e->getMessage();
}

$t->user = $user;

$t->pageTitle = 'Training';
$t->display();
?>
