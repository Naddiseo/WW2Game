
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
<!-- Begin Alliance Info -->
<div id="alliancehome-container">
	<div class="panel">
		<div class="panel-title">
			<?= $this->alliance->getNameHTML() ?> Alliance
			<? $this->load('alliance-header') ?>
		</div>
		<div class="large">
			<div class="line">
				<label>View Members</label>
				<span><a href="alliance-members.php?aid=<?= $this->alliance->id ?>">View</a><span>
			</div>
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
					<?= $this->alliance->getWelcome() ?>
				</p>
			</div>
		</div>
		
	</div>
</div>
<!-- End Alliance Info -->
