
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
<!-- Begin Training page -->
<div id="training-container">
	<form method="post">
		<div id="training-form" class="panel">
			<div class="panel-title">
				Train Your Troops
			</div>
			<table>
				<tr>
					<th>Training Program</th>
					<th>Current</th>
					<th>Cost Per Unit</th>
					<th>Quantity</th>
					<th>&nbsp;</th>
				</tr>
				<tr>
					<td>Attack Specialist</td>
					<td><?= numecho($user->sasoldiers) ?></td>
					<td><?= numecho($conf['cost']['sasoldier']) ?></td>
					<td><input type="text" id="train-sasoldiers" name="train-sasoldiers" value="0" /></td>
					<td><input type="button" onclick="javascript:return trainMax('sasoldiers', <?= $conf['cost']['sasoldier'] ?>)" value="Max" /></td>
				</tr>
				<tr>
					<td>Defense Specialist</td>
					<td><?= numecho($user->dasoldiers) ?></td>
					<td><?= numecho($conf['cost']['dasoldier']) ?></td>
					<td><input type="text" id="train-dasoldiers" name="train-dasoldiers" value="0" /></td>
					<td><input type="button" onclick="javascript:return trainMax('dasoldiers', <?= $conf['cost']['dasoldier'] ?>)" value="Max" /></td>
				</tr>
				<tr>
					<td>Spy</td>
					<td><?= numecho($user->spies) ?></td>
					<td><?= numecho($conf['cost']['spy']) ?></td>
					<td><input type="text" id="train-spies" name="train-spies" value="0" /></td>
					<td><input type="button" onclick="javascript:return trainMax('spies', <?= $conf['cost']['spy'] ?>)" value="Max" /></td>
				</tr>
				<tr>
					<td>Specialist Forces Operative</td>
					<td><?= numecho($user->specialforces) ?></td>
					<td><?= numecho($conf['cost']['specialforces']) ?></td>
					<td><input type="text" id="train-specialforces" name="train-specialforces" value="0" /></td>
					<td><input type="button" onclick="javascript:return trainMax('specialforces', <?= $conf['cost']['specialforces'] ?>)" value="Max" /></td>
				</tr>
			</table>
		</div>
		
		<div id="merc-form" class="panel">
			<table>
				<tr>
					<th>Mercenary Training</th>
					<th>Current</th>
					<th>Available</th>
					<th>Cost Per Unit</th>
					<th>Quantity</th>
				</tr>
				<tr>
					<td>Attack Specialist</td>
					<td><?= numecho($user->samercs) ?></td>
					<td><?= numecho($this->merc->samercs) ?></td>
					<td><?= numecho($conf['cost']['samerc']) ?></td>
					<td><input type="text" id="train-samerc" name="train-samerc" value="0" /></td>
				</tr>
				<tr>
					<td>Defense Specialist</td>
					<td><?= numecho($user->damercs) ?></td>
					<td><?= numecho($this->merc->damercs) ?></td>
					<td><?= numecho($conf['cost']['damerc']) ?></td>
					<td><input type="text" id="train-damerc" name="train-damerc" value="0" /></td>
				</tr>
				<tr>
					<td colspan="4">&nbsp;</td>
					<td style="text-align:right;padding-right: 10px;"><input type="submit" name="train-train" value="Train" /></td>
				</tr>
			</table>
		</div>
	</form>

	<form method="post">
		<div id="untraining-form" class="panel">
			<div class="panel-title">
				Reassign Troops
			</div>
			<table>
				<tr>
					<th>Soldier Type</th>
					<th>Current</th>
					<th>Quantity</th>
					<th>&nbsp;</th>
				</tr>
				<tr>
					<td>Attack Specialist</td>
					<td><?= numecho($user->sasoldiers) ?></td>
					<td><input type="text" id="untrain-sasoldiers" name="untrain-sasoldiers" value="0" /></td>
					<td><input type="button" onclick="javascript:return untrainMax('sasoldiers', <?= $user->sasoldiers ?>)" value="Max" /></td>
				</tr>
				<tr>
					<td>Defense Specialist</td>
					<td><?= numecho($user->dasoldiers) ?></td>
					<td><input type="text" id="untrain-dasoldiers" name="untrain-dasoldiers" value="0" /></td>
					<td><input type="button" onclick="javascript:return untrainMax('dasoldiers', <?= $user->dasoldiers ?>)" value="Max" /></td>
				</tr>
				<tr>
					<td>Spy</td>
					<td><?= numecho($user->spies) ?></td>
					<td><input type="text" id="untrain-spies" name="untrain-spies" value="0" /></td>
					<td><input type="button" onclick="javascript:return untrainMax('spies', <?= $user->spies ?>)" value="Max" /></td>
				</tr>
				<tr>
					<td>Specialist Forces Operative</td>
					<td><?= numecho($user->specialforces) ?></td>
					<td><input type="text" id="untrain-specialforces" name="untrain-specialforces" value="0" /></td>
					<td><input type="button" onclick="javascript:return untrainMax('specialforces', <?= $user->specialforces ?>)" value="Max" /></td>
				</tr>

<!--
	Removed because of links
				<tr>
					<td>Attack Mercenary</td>
					<td><?= numecho($user->samercs) ?></td>
					<td><input style="width:50%;" type="text" id="untrain-samercs" name="untrain-samercs" value="0" /> for <?= numecho($conf['cost']['sasoldier'] * 0.5) ?></td>
					<td><input type="button" onclick="javascript:return untrainMax('samercs', <?= $user->samercs ?>)" value="Max" /></td>
				</tr>
				<tr>
					<td>Defense Mercenary</td>
					<td><?= numecho($user->damercs) ?></td>
					<td><input style="width:50%;" type="text" id="untrain-damercs" name="untrain-damercs" value="0" /> for <?= numecho($conf['cost']['sasoldier'] * 0.5) ?></td>
					<td><input type="button" onclick="javascript:return untrainMax('damercs', <?= $user->damercs ?>)" value="Max" /></td>
				</tr>
-->
				<tr>
					<td colspan="3">&nbsp;</td>
					<td><input type="submit" name="train-untrain" value="Reassign" /></td>
				</tr>
				
			</table>
		</div>
	</form>
	
</div>
<!-- End Training page -->
