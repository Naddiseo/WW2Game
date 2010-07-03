
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
<!-- Begin base page -->
<div id="base-container">
	<table  style="width: 100%;vertical-align: top;">
		<tr>
			<td id="left-pane">
				<div id="officers-list" class="panel">
					<div class="panel-title">
						User Info
					</div>
					<table>
						<tr><td>Name</td><td><?= $user->getNameLink() ?></td></tr>
						<!--<tr><td>Social</td>
							<td><fb:login-button v="2" size="medium" onlogin="window.location.reload(true);">Connect</fb:login-button>
							</td>
						</tr>-->
						<tr>
							<td>Supporter Level</td>
							<td><a href="support.php"><?= ($user->supporter? "\$$user->supporter" : 'Become A Supporter') ?></a><td>
						</tr>
						<tr><td>Alliance</td><td>
							<? if ($user->alliance > 0) { ?>
								<a href="alliance-home.php?"><?= $user->getAlliance()->getNameHTML() ?></a>
								[<a href="alliance-home.php?leave-alliance=yes"> leave </a>]
							<? }
								else { ?>
								<a href="alliance-list.php">None</a>
							<? } ?>
						</td></tr>
						<tr><td>Email</td><td><?= $user->email ?></td></tr>
						<tr><td>Nation</td><td><?= $user->getNation() ?></td></tr>
						<tr><td>Rank</td><td><?= numecho($user->rank) ?></td></tr>
						<tr><td>Area</td><td><?= $user->getAreaName() ?></td></tr>
						<tr><td>Commander</td><td>
							<? if ($user->commander) { ?>
								<?= $user->getCommander()->getNameLink() ?>
								[<a href="base.php?leave-commander=yes"> leave </a>]
							<? }
								else { ?>
								None
							<? } ?>
						</td></tr>
						<tr><td>Game Skill</td><td><?= numecho($user->gameSkill) ?></td></tr>
						<tr><td>Defensive Tech</td><td><?= $user->getDAName() ?></td></tr>
						<tr><td>Offensive Tech</td><td><?= $user->getSAName() ?></td></tr>
						<tr><td>Covert Tech</td><td><?= $user->calevel ?></td></tr>
						<tr><td>Retaliation Tech</td><td><?= $user->ralevel ?></td></tr>
						<tr><td>Hand-to-hand Level</td><td><?= $user->hhlevel ?></td></tr>
						<tr>
							<td>Unit Production</td>
							<td><?= numecho($user->up) ?> (+ <? numecho($user->getOfficerUP());echo ' +';numecho($user->getAllianceUP()); ?>)</td>
						</tr>
						<tr><td>Gold</td><td><?= numecho($user->gold) ?> </td></tr>
						<tr><td>Income</td><td><?= numecho($user->getIncome()) ?> gold / turn</td></tr>
						<tr><td>Commander Bonus</td><td><? numecho($user->commandergold) ?> gold / turn</td></tr>
						<tr><td>Attack Turns</td><td><?= numecho($user->attackturns) ?></td></tr>
						<tr><td>Click Credits</td><td><?= $user->gclick ?></td></tr>
						
					</table>
				</div>
			</td>
			<td id="right-pane">
				<? $this->load('user-stats') ?>
				<? $this->load('personnel') ?>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<? $this->load('officers-list') ?>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<? if($user->clickall == 0) {?>
                	<form method="POST">
                		<input type="submit" name="clickall" value="Global Click" /><br>
                		<small>Adds 10 soldiers to everyone.</small>
                	</form>
                <? } ?>
			</td>
		</tr>
	</table>
</div>
<!-- End base page -->
