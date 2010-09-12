
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
<!-- Begin Armoury page -->
<div id="armoury-container">
	<div class="panel">
		<div class="panel-title">
			Current Attack Weapons
		</div>
		<table>
			<tr>
				<th>Weapon</th>
				<th>Quantity</th>
				<th>Strength</th>
				<th>Sell</th>
			</tr>
			<? foreach ($this->saWeapons as $weapon) { ?>
				<tr>
					<td><?= $weapon->getName($user) ?></td>
					<td><?= numecho($weapon->weaponCount)?></td>
					<td>
					<? if ($weapon->weaponStrength == $weapon->getFullStrength()) {?>
						<?= numecho($weapon->weaponStrength) ?> / <?= numecho($weapon->getFullStrength()) ?></td>
					<? }
						else { ?>
						<form method="post">
							<input type="hidden" name="wId" value="<?= $weapon->id ?>" />
							<input style="width: 35px;" type="text" name="damage" value="<?= $weapon->getDamage() ?>" />
							<input type="submit" name ="repair-attack" value="<?= numecho($weapon->getRepairPerPoint()) ?> gold / point" />
							<input type="submit" name="repair-attack-max" value="Max" />
						</form>
						<? } ?>
					<td>
						<form method="post">
							<input type="hidden" name="wId" value="<?= $weapon->id ?>" />
							<input style="width:35px;" type="text" name="sell" value="0" />
							<input type="submit" value="Sell for <?= numecho($weapon->getSellCost($user)) ?>" />
						</form>
					</td>
				</tr>
			<? } ?>
			<tr>
				<td>&nbsp;</td>
				<td>Total: <?= numecho($this->saRatio->total) ?><br /> Ratio: <?= number_format(round($this->saRatio->ratio, 2)) ?></td>
				<td>
					&nbsp;
					<? if ($user->supporter) { ?>
						<!-- <form method="post">
							<input type="submit" value="Repair All" name="repair-all-attack" />
						</form> -->
					<? } ?>
				</td>
				<td>
					&nbsp;
					<? if ($user->supporter) { ?>
					<!--<form method="post">
						<input type="submit" value="Sell All" name="sell-all-attack" />
					</form>-->
					<? } ?>
				</td>
			</tr>
		</table>
	</div>

	<div class="panel">
		<div class="panel-title">
			Current Defense Weapons
		</div>
		<table>
			<tr>
				<th>Weapon</th>
				<th>Quantity</th>
				<th>Strength</th>
				<th>Sell</th>
			</tr>
			<? foreach ($this->daWeapons as $weapon) { ?>
				<tr>
					<td><?= $weapon->getName($user) ?></td>
					<td><?= numecho($weapon->weaponCount) ?></td>
					<td>
					<? if ($weapon->weaponStrength == $weapon->getFullStrength()) {?>
						<?= numecho($weapon->weaponStrength) ?> / <?= numecho($weapon->getFullStrength()) ?></td>
					<? }
						else { ?>
							<form method="post">
								<input type="hidden" name="wId" value="<?= $weapon->id ?>" />
								<input style="width: 35px;" type="text" name="damage" value="<?= $weapon->getDamage() ?>" />
								<input type="submit" name="repair-defense" value="<?= numecho($weapon->getRepairPerPoint()) ?> gold / point" />
								<input type="submit" name="repair-defense-max" value="Max" />
							</form>
						<? } ?>
					<td>
						<form method="post">
							<input type="hidden" name="wId" value="<?= $weapon->id ?>" />
							<input style="width:35px;" type="text" name="sell" value="0" />
							<input type="submit" value="Sell for <?= numecho($weapon->getSellCost($user)) ?>" />
						</form>
					</td>
				</tr>
			<? } ?>
			<tr>
				<td>&nbsp;</td>
				<td>Total: <?= numecho($this->daRatio->total) ?><br /> Ratio: <?= number_format(round($this->daRatio->ratio, 2)) ?></td>
				<td>
					&nbsp;
					<? if ($user->supporter) { ?>
						<!--<form method="post">
							<input type="submit" value="Repair All" name="repair-all-defense" />
						</form> -->
					<? } ?>
				</td>
				<td>
					&nbsp;
					<? if ($user->supporter) { ?>
						<!-- <form method="post">
							<input type="submit" value="Sell All" name="Sell-all-defense" />
						</form> -->
					<? } ?>
				</td>
			</tr>
		</table>
	</div>

	<form method="post">
		<div class="panel">
			<div class="panel-title">
				Buy Weapons
			</div>
			<table>
				<tr>
					<th>Attack Weapons</th>
					<th>Strength</th>
					<th>Cost</th>
					<th>Amount</th>
					<th>&nbsp;</th>
				</tr>
				<? for ($i = 0; $i <= $user->salevel; $i++) { ?>
					<tr>
						<td><?= $conf['names']['weapons'][1][$i] ?></td>
						<td><?= numecho($conf['weapon' . $i . 'strength']) ?></td>
						<td><?= numecho($conf['weapon' . $i . 'price']) ?></td>
						<td><input type="text" name="attackweapon[]" value="0" id="attack-weapon-<?= $i ?>" /></td>
						<td><input type="button" onclick="javascript:return attackWMax(<?= $i ?>, <?= $conf['weapon' . $i . 'price'] ?>);" value="Max" /></td>
					</tr>
				<? } ?>
				<tr>
					<th>Defense Weapons</th>
					<th>Strength</th>
					<th>Cost</th>
					<th>Amount</th>
					<th>&nbsp;</th>
				</tr>
				<? for ($i = 0; $i <= $user->dalevel; $i++) { ?>
					<tr>
						<td><?= $conf['names']['weapons'][0][$i] ?></td>
						<td><?= numecho($conf['weapon' . $i . 'strength']) ?></td>
						<td><?= numecho($conf['weapon' . $i . 'price']) ?></td>
						<td><input type="text" name="defenseweapon[]" value="0" id="defense-weapon-<?= $i ?>" /></td>
						<td><input type="button" onclick="javascript:return defenseWMax(<?= $i ?>, <?= $conf['weapon' . $i . 'price'] ?>);" value="Max" /></td>
					</tr>
				<? } ?>
				<tr>
					<td colspan="4">&nbsp;</td>
					<td><input type="submit" name="armoury-buy" value="Buy" /></td>
				</tr>
			</table>
		</div>
	</form>
	
</div>
<!-- End Armoury Page -->
