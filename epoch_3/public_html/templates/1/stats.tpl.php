
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
			User Info {<a href="report-user.php?uid=<?= $this->target->id ?>">Report User</a>}
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
				<span><?= $this->target->getNameRecruit() . $tag ?></span>
			</div>
			<? if (Privacy::isAdmin()) { ?>
				<div class="line">
					<span>
						<a href="admin-stats.php?uid=<?= $this->target->id ?>">Stats</a>
						(<?= $this->target->currentIP ?>)
					</span>
				</div>
			<? } ?>
			<div class="line">
				<span colspan="2" style="text-align:center;margin-left:0;">
					<a href="support.php?uid=<?= $this->target->id ?>" title="Buy Supporter Status for <?= $this->target->getNameHTML() ?>">
						Buy <?= $this->target->getNameHTML() ?> Supporter Status
					</a>
				</span>
			</div>
			<div class="line">
				<label>Commander</label>
				<span>
					<? if($this->target->commander) { ?>
						<?= $this->target->getCommander()->getNameLink() ?>
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
			<div class="line">
				<label>Gold</label>
				<span><?= ( $user->canSpyOn($this->target) ? numecho2($this->target->gold) : '?????' ) ?></span>
			</div>
			<? if ($user->id) { ?>
				<? if ($this->target->id != $user->id) { ?>
					<? if ($this->target->area == $user->area and IP::canAttack($user, $this->target)) { ?>
						<div class="line">
							<label>Spy</label>
							<form action="spy.php" method="post" class="right-section">
								<input type="hidden" name="uid" value="<?= $this->target->id ?>" />
								<input type="submit" name="spy" value="<?= numecho($conf['spying-cost']) ?> gold to Spy!" />
							</form>
						</div>
						<div class="line">
							<label>Thieve</label>
							<form action="theft.php" method="post" class="right-section">
								<input type="hidden" name="id" value="<?= $this->target->id ?>" />
						
								<input type="hidden" name="uid" value="<?= $this->target->id ?>" />
								<? if (!$user->getSupport('theft-calc')) { ?>
									<label for="spies">Spies:</label>
									<input id="theft-spies" type="text" name="spies" value="" style="margin-bottom:5px;"/>
									<br />
								<? } ?>
								<input type="submit" name="saweapons" value="Attack Weapons" /> |
								<input type="submit" name="daweapons" value="Defense Weapons" /> |
								<? if ($user->nation != 1 and $user->nation != 3) { ?>
									<input type="submit" name="gold" value="Gold" />
								<? } ?>
							</form>
						</div>
						<div class="line">
							<label>Attack</label>
							<form action="attack.php" method="post" class="right-section">
								<input type="hidden" name="uid" value="<?= $this->target->id ?>" />
								<input type="submit" name="mass" value="1 Turn Mass!" />&nbsp;&nbsp;|&nbsp;
								<input type="submit" name="raid" value="6 Turn Raid!" />
							</form>
						</div>
					<? } ?>
					<div class="line">
						<label>&nbsp;</label>
						<form action="writemail.php" method="post" class="right-section">
							<input type="hidden" name="to[]" value="<?= $this->target->id ?>" />
							<input type="submit" name="message" value="Send a Message!" />
						</form>
					</div>
				<? } ?>

				<div class="line">
					<label>&nbsp;</label>
					<? if ($this->target->numofficers < $this->target->maxofficers) { ?>
						<? if ($user->commander != $this->target->id) { ?>
							<form method="post" class="right-section">
								<input type="hidden" name="uid" value="<?= $this->target->id ?>" />
								<input type="submit" name="mkcommander" value="Make this user my commander!" />
							</form>
						<? }
							else { ?>
							<span>[ This is your commander ]</span>
						<? } ?>
					<? }
						else { ?>
						<span>[ This user has enough officers ]</span>
					<? } ?>
				</div>
				
			<? } ?>
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
