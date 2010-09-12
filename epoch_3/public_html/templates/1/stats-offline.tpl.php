
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
<!-- Begin Stats page -->
<div id="statspage-container">

	<div class="panel">
		<div class="panel-title">
			User Info
		</div>
		<div class="large">
			<?
				$tag = '';
				if ($this->target->alliance) {
					$tag = '&nbsp;' . $this->target->getAlliance()->getTag();
				}
			?>
			<div class="line">
				<label>Username</label>
				<span><?= $this->target->getNameRecruit('', true) . $tag ?></span>
			</div>
			<div class="line">
				<label>Commander</label>
				<span>
					<? if($this->target->commander) { ?>
						<?= $this->target->getCommander()->getNameLink('', true) ?>
					<? }
						else { ?>
						None
					<? } ?>
				</span>
			</div>
			<div class="line">
				<label>Nation</label>
				<span><?= $this->target->getNation() ?></span>
			</div>
			<div class="line">
				<label>Area</label>
				<span><?= $this->target->getAreaName() ?></span>
			</div>
			<div class="line">
				<label>Rank</label>
				<span><?= numecho($this->target->rank) ?></span>
			</div>
			<div class="line">
				<label>Army Size</label>
				<span><?= numecho($this->target->getTFF()) ?></span>
			</div>
			<div class="clear flat"></div>
		</div>
	</div>

	<?
		$tmp = $user;
		$this->offargs = 'uid=' . $this->target->id;
		$this->user = $this->target;
		$this->load('officers-list');
		$this->user = $tmp;
	?>

</div>
<!-- End Stats Page -->
