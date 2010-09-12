
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
<!-- Begin upgrades page -->
<div id="upgrades-container">
	<div class="panel">
		<div class="panel-title">
			Technology Upgrades
		</div>
		<form method="post">
			<!-- How I hate tables for layout -->
			<table class="large">
				<tr>
					<th>Upgrade</th>
					<th>Current</th>
					<th>Next</th>
				</tr>
				<tr>
					<td>Offensive Technology</td>
					<td><?= $user->getSAName() ?></td>
					<td>
						<? if ($user->salevel >= $conf['race'][$user->nation]['max-salevel']) { ?>
							[ No More ]
						<? }
							else { ?>
							<input type="submit" name="upgrade-sa" value="<?= numecho(Upgrades::saCost($user)) ?> Gold" />
						<? } ?>
					</td>
				</tr>
				<tr>
					<td>Defensive Technology</td>
					<td><?= $user->getDAName() ?></td>
					<td>
						<? if ($user->dalevel >= $conf['race'][$user->nation]['max-dalevel']) { ?>
							[ No More ]
						<? }
							else { ?>
							<input type="submit" name="upgrade-da" value="<?= numecho(Upgrades::daCost($user)) ?> Gold" />
						<? } ?>
					</td>
				</tr>
				<tr>
					<td>Covert Technology</td>
					<td><?= $user->calevel ?></td>
					<td><input type="submit" name="upgrade-ca" value="<?= numecho(Upgrades::caCost($user)) ?> Gold" /></td>
				</tr>
				<tr>
					<td>Retaliation Technology</td>
					<td><?= $user->ralevel ?></td>
					<td><input type="submit" name="upgrade-ra" value="<?= numecho(Upgrades::raCost($user)) ?> Gold" /></td>
				</tr>
				<tr>
					<td>Upgrade Unit Production</td>
					<td><?= numecho($user->up) ?></td>
					<td>
						<input type="submit" name="upgrade-up" value="<?= numecho(Upgrades::upCost($user)) ?> Gold" />
						<input type="submit" name="upgrade-up-max" value="Max" />
					</td>
				</tr>
				<tr>
					<td>Hand-to-Hand Training</td>
					<td><?= $user->hhlevel ?></td>
					<td><input type="submit" name="upgrade-hh" value="<?= numecho(Upgrades::hhCost($user)) ?> Gold" /></td>
				</tr>
				<? if ($user->getSupport('upgrades')) { ?>
					<tr>
						<td>Upgrade Officer Limit</td>
						<td><?= $user->maxofficers ?></td>
						<td><input type="submit" name="upgrade-of" value="<?= numecho(Upgrades::ofCost($user)) ?> Gold" /></td>
					</tr>
					<tr>
						<td>Upgrade Bank Deposit Percentage</td>
						<td><?= $user->bankper ?>%</td>
						<td><input type="submit" name="upgrade-bk" value="<?= numecho(Upgrades::bkCost($user)) ?> Gold" /></td>
					</tr>
				<? } ?>
			</table>
		</form>
	</div>
</div>
<!-- End upgrades page -->
