
<!--

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

-->
<!-- Begin user stats -->
<div id="officers-list" class="panel">
	<div class="panel-title">
		Military Potential
	</div>
	<table>
		<tr>
			<th>Stat</th>
			<th>Amount</th>
			<th>Rank</th>
		</tr>
		<tr>
			<td>Offensive</td>
			<td><?= numecho($user->SA) ?></td>
			<td><?= ($user->sarank ?  numecho($user->sarank) : 'Unranked') ?></td>
		</tr>
		<tr>
			<td>Defensive</td>
			<td><?= numecho($user->DA) ?></td>
			<td><?= ($user->darank ?  numecho($user->darank) : 'Unranked') ?></td>
		</tr>
		<tr>
			<td>Covert</td>
			<td><?= numecho($user->CA) ?></td>
			<td><?= ($user->carank ?  numecho($user->carank) : 'Unranked') ?></td>
		</tr>
		<tr>
			<td>Retaliation</td>
			<td><?= numecho($user->RA) ?></td>
			<td><?= ($user->rarank ?  numecho($user->rarank) : 'Unranked') ?></td>
		</tr>



	</table>
</div>
<!-- End user stats -->
