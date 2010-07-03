
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
<!-- Begin user personnel -->
<div id="officers-list" class="panel">
	<div class="panel-title">
		Personnel
	</div>
	<table>
		<tr>
			<th>Unit</th>
			<th>Quantity</th>
		</tr>
		<tr>
			<td>Offensive Soldiers</td>
			<td><?= numecho($user->sasoldiers) ?></td>
		</tr>
		<tr>
			<td>Offensive Mercenaries</td>
			<td><?= numecho($user->samercs) ?></td>
		</tr>
		<tr>
			<td>Defensive Soldiers</td>
			<td><?= numecho($user->dasoldiers) ?></td>
		</tr>
		<tr>
			<td>Defensive Mercenaries</td>
			<td><?= numecho($user->damercs) ?></td>
		</tr>
		<tr>
			<td>Spies</td>
			<td><?= numecho($user->spies) ?></td>
		</tr>
		<tr>
			<td>Special Forces</td>
			<td><?= numecho($user->specialforces) ?></td>
		</tr>
		<tr>
			<td>Untrained</td>
			<td><?= numecho($user->uu) ?></td>
		</tr>
		<tr>
			<td>Total</td>
			<td><?= numecho($user->getTFF()) ?></td>
		</tr>



	</table>
</div>
<!-- End user personnel -->
