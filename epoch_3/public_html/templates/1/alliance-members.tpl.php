
<!--

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

-->
<!-- Begin Alliance Members -->
<div id="alliancemembers-container">
	<div class="panel">
		<div class="panel-title">
			Members <? $this->load('alliance-header') ?>
		</div>
		<form method="post" class="large">
			<table class="large">
				<tr>
					<th>Name</th>
					<th>Area</th>
					<th>Rank</th>
					<? if ($this->alliance->isLeader($user)) { ?>
						<th>Action</th>
						<th>Leader1</th>
						<th>Leader2</th>
						<th>Leader3</th>
					<? } ?>
				</tr>
				<? foreach ($this->allianceMembers as $member) { ?>
					<tr>
						<td><?= $member->getNameLink() ?></td>
						<td><?= $member->getAreaName() ?></td>
						<td><?= numecho($member->rank) ?></td>
						<? if ($this->alliance->isLeader($user)) { ?>
							<td>
								<? if ($member->aaccepted) { ?>
									Kick: <input type="checkbox" name="kick[]" value="<?= $member->id ?>" />
								<? }
									else { ?>
									Accept: <input type="checkbox" name="accept[]" value="<?= $member->id ?>" />
								<? } ?>
								Ban: <input type="checkbox" name="ban[]" value="<?= $member->id ?>" />
							</td>
							<td>
								<input 
									type="radio" 
									name="leader1" 
									value="<?= $member->id ?>" 
									<?= ($member->id == $this->alliance->leaderId1 ? 'checked="checked"' : '') ?>
								/>
							</td>
							<td>
								<input 
									type="radio" 
									name="leader2" 
									value="<?= $member->id ?>" 
									<?= ($member->id == $this->alliance->leaderId2 ? 'checked="checked"' : '') ?>
								/>
							</td>
							<td>
								<input 
									type="radio" 
									name="leader3" 
									value="<?= $member->id ?>" 
									<?= ($member->id == $this->alliance->leaderId3 ? 'checked="checked"' : '') ?>
								/>
							</td>
						<? } ?>
					</tr>
				<? } ?>
			</table>
			<? if ($this->alliance->isLeader($user)) { ?>
			<div class="line">
				<input type="submit" value="Update" name="alliance-submit" class="submit" />
			</div>
			<? } ?>
		</form>
	</div>
</div> 
<!-- End Alliance Members -->
