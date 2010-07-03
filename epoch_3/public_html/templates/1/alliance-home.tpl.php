
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
<!-- Begin Alliance Home -->
<div id="alliancehome-container">
	<div class="panel">
		<div class="panel-title">
			<?= $this->alliance->getNameHTML() ?> Alliance
			<? $this->load('alliance-header') ?>
		</div>
		<div class="large">
			<div class="line">
				<label>View Members</label>
				<span><a href="alliance-members.php">View</a><span>
			</div>
			<? if ($this->alliance->isLeader($user)) { ?>
				<div class="line">
					<label>Banned Users</label>
					<span><a href="alliance-banlist.php">View</a></span>
				</div>
			<? } ?>
			<div class="line">
				<label>URL:</label>
				<span><?= $this->alliance->getURLLink() ?><span>
			</div>
			<div class="line">
				<label>IRC:</label>
				<span><?= $this->alliance->getIRCLink() ?></span>
			</div>
		
			<div class="line">
				<p class="info">	
					<? if ($user->aaccepted) { ?>
						<?= $this->alliance->getNews() ?>
					<? }
					else { ?>
						<?= $this->alliance->getWelcome() ?>
					<? } ?>
				</p>
			</div>
		</div>
		<form class="large" method="post">
			<div class="line">
				<label for="alliance-shout">Shout</label>
				<textarea name="alliance-shout" cols="80" maxlength="160" rows="4"></textarea>
			</div>
			<div class="line">
				<input type="submit" class="submit" value="Shout!" name="alliance-shout-submit" />
			</div>
			<? foreach($this->shouts as $shout) { ?>
				<div class="line">
					<label class="shout-left">
						<?= $shout->getUser()->getNameLink() ?><br />
						<?= $shout->getTime('H:i:s') ?>
						
					</label>
					<div class="shout">
						<?= $shout->getText() ?>
					</div>					
				</div>
			<? } ?>
		</form>
	</div>
</div>
<!-- End Alliance Home -->
