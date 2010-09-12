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
function trainMax(box, costPerUnit) {
	document.getElementById('train-' + box).value = Math.min(Math.floor(user.primary / costPerUnit), user.uu);
	return false;
}

function untrainMax(box, total) {
	document.getElementById('untrain-' + box).value = total;
	return false;
}

function attackWMax(box, costPerUnit) {
	document.getElementById('attack-weapon-' + box).value = Math.floor(user.primary / costPerUnit);
	return false;
}

function defenseWMax(box, costPerUnit) {
	document.getElementById('defense-weapon-' + box).value = Math.floor(user.primary / costPerUnit);
	return false;
}

function startTimer() {
	setTimeout('updateTimer()', 1000);

	minBox = document.getElementById('menu-next-turn-min');
	secBox = document.getElementById('menu-next-turn-sec');

	if ($min < 0) {
		$min = minPerTurn;
		$sec = 0;
	}

	if ($sec < 10) {
		temp = '0';
	}
	else {
		temp = '';
	}

	minBox.innerHTML = $min;
	secBox.innerHTML = temp + $sec;
}

function updateTimer() {

	setTimeout("updateTimer()", 1000);

	minBox = document.getElementById('menu-next-turn-min');
	secBox = document.getElementById('menu-next-turn-sec');

	$sec--;
	if ($min <= 0 && $sec <= 0) {
		$min = 20;
		$sec = 0;
	}

	if ($sec <= 0) {
		$sec = 59;
		$min--;
	}

	minBox.innerHTML = $min;
	
	var temp;

	if ($sec < 10 && $sec > 0) {
		temp="0";
	}
	else {
		temp="";
	}

	temp = temp + $sec;
	secBox.innerHTML = temp;
}

function unhideAttackLogInfo(caller, logId) {

	function show(eId) {
		e = document.getElementById(eId);
		e.className = e.className.replace('attacklog-hidden-info', '');
		e.style.display = '';
	}

	function hide(eId) {
		e = document.getElementById(eId);
		e.style.display = 'none';
		if (!e.className.match(/attacklog-hidden-info/)) {
			e.className += ' attack-log-hidden-info';
		}
	}

	if (caller.innerHTML.match(/\[\ \+\ \]/)) {
		caller.innerHTML = '[ - ]';
		caller.setAttribute('alt', 'Hide hidden information');
		caller.setAttribute('title', 'Hide hidden information');
		for (i = 1; i <= 5; i++) {
			var id = 'hidden-' + i + '-' + logId
			show(id);
		}
	}
	else {
		caller.innerHTML = '[ + ]';
		caller.setAttribute('alt', 'Show hidden information');
		caller.setAttribute('title', 'Show hidden information');
		for (i = 1; i <= 5; i++) {
			hide('hidden-' + i + '-' + logId);
		}
	}
	return false;
}
