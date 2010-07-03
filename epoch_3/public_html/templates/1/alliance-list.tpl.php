
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
<!-- Begin Alliance listing -->
<div id="alliance-listing-container">
	<div class="panel">
		<div class="panel-title">
			Alliances <? $this->load('alliance-header') ?>
		</div>
		<table class="large">
			<tr>
				<th>Name</th>
				<th>Leader</th>
				<th>Leader</th>
				<th>Leader</th>
				<th>Join</th>
			</tr>
			<? foreach ($this->alliances as $alliance) { ?>
				<tr>
					<td>
						<a href="alliance-info.php?aid=<?= $alliance->id ?>">
							<?= $alliance->getNameHTML() ?>
						</a>
					</td>
					<td><?= $alliance->getLeader(1)->getNameLink() ?></td>
					<td><?= $alliance->getLeader(2)->getNameLink() ?></td>
					<td><?= $alliance->getLeader(3)->getNameLink() ?></td>
					<td>
						<? if ($alliance->status == 0) { ?>
							<a href="alliance-home.php?join=<?= $alliance->id ?>">Join</a>
						<? } 
							else { ?>
							{Closed}
						<? } ?>
					</td>
				</tr>
			<? } ?>
		</table>
	</div>
</div>
<!-- End Alliance listing -->
