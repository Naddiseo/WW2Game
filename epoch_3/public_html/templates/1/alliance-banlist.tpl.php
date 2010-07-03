
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
<!-- Begin Alliance Members -->
<div id="alliancemembers-container">
	<div class="panel">
		<div class="panel-title">
			Baned Users <? $this->load('alliance-header') ?>
		</div>
		<form method="post" class="large">
			<table class="large">
				<tr>
					<th>Ban Date</th>
					<th>Name</th>
					<th>Area</th>
					<th>Rank</th>
					<? if ($this->alliance->isLeader($user)) { ?>
						<th>Remove</th>
					<? } ?>
				</tr>
				<? foreach ($this->banned as $b) { ?>
					<? $member = getCachedUser($b->targetId); ?>
					<tr>
						<td><?= date('H:i d/M', $b->date) ?></td>
						<td><?= $member->getNameLink() ?></td>
						<td><?= $member->getAreaName() ?></td>
						<td><?= numecho($member->rank) ?></td>
						<? if ($this->alliance->isLeader($user)) { ?>
							<td>
								 <input type="checkbox" name="remove[]" value="<?= $b->id ?>" />
							</td>
						<? } ?>
					</tr>
				<? } ?>
			</table>
			<div class="line">
				<input type="submit" value="Update" name="alliance-submit" class="submit" />
			</div>
		</form>
	</div>
</div> 
<!-- End Alliance Members -->
