
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
<!-- Begin About Changing nation -->
<div id="changenation-container">
	<div class="panel">
		<div class="panel-title">
			About Changing nation
		</div>
		<p>When changing nation, you lose <b>ALL WEAPONS</b>, you lose <b>ALL GOLD ON HAND</b>, and <em>HALF</em> of <b>banked gold</b><p>
		<p>You <em>keep</em> all upgrades and units<p>
		<p>You can only change nation in the first 2 weeks of the age<p>
		<table>
			<caption>Nation Bonuses</caption>
			<tbody>
				<tr>
					<th>Nation</th>
					<th>Offensive Bonus</th>
					<th>Defensive Bonus</th>
					<th>Covert Bonus</th>
					<th>Retaliation Bonus</th>
				</tr>
				<tr>
					<td>USA</td>
					<td><?= ($conf['sabonus0'] * 100) ?>%</td>
					<td><?= ($conf['dabonus0'] * 100) ?>%</td>
					<td><?= ($conf['cabonus0'] * 100) ?>%</td>
					<td><?= ($conf['rabonus0'] * 100) ?>%</td>
				</tr>
				<tr>
					<td>UK</td>
					<td><?= ($conf['sabonus1'] * 100) ?>%</td>
					<td><?= ($conf['dabonus1'] * 100) ?>%</td>
					<td><?= ($conf['cabonus1'] * 100) ?>%</td>
					<td><?= ($conf['rabonus1'] * 100) ?>%</td>
				</tr>
				<tr>
					<td>Japan</td>
					<td><?= ($conf['sabonus2'] * 100) ?>%</td>
					<td><?= ($conf['dabonus2'] * 100) ?>%</td>
					<td><?= ($conf['cabonus2'] * 100) ?>%</td>
					<td><?= ($conf['rabonus2'] * 100) ?>%</td>
				</tr>
				<tr>
					<td>Germany</td>
					<td><?= ($conf['sabonus3'] * 100) ?>%</td>
					<td><?= ($conf['dabonus3'] * 100) ?>%</td>
					<td><?= ($conf['cabonus3'] * 100) ?>%</td>
					<td><?= ($conf['rabonus3'] * 100) ?>%</td>
				</tr>
				<tr>
					<td>USSR</td>
					<td><?= ($conf['sabonus4'] * 100) ?>%</td>
					<td><?= ($conf['dabonus4'] * 100) ?>%</td>
					<td><?= ($conf['cabonus4'] * 100) ?>%</td>
					<td><?= ($conf['rabonus4'] * 100) ?>%</td>
				</tr>
			
			
			</tbody>
		</table>
	</div>
</div>
<!-- End About changing nation -->
