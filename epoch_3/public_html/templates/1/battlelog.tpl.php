
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
<!-- Begin Battle log -->
<div id="battlelog-container">
	
	<p><?= $this->attacker->getNameLink() ?> attacked <?= $this->target->getNameLink() ?>.</p>

	<p id="force"><?= $this->attacker->getNameLink() ?> striked at <?= $this->b->attackerStrikePercentage ?>% effectiveness
		with <?= numecho($this->b->attackerStrength) ?> force. <br />
		<?= $this->target->getNameLink() ?> defended at <?= $this->b->targetDefensePercentage ?>% effectiveness
		with <?= numecho($this->b->targetStrength) ?> force.
	</p>

	<p id="result">
		<? if ($this->b->getSuccess()) { ?>
			<? if ($this->b->attackType == 6) { ?>
				The forces of <?= $this->attacker->getNameLink() ?> overwhelmed those of <?= $this->target->getNameLink() ?>.<br />
				<?= $this->attacker->getNameLink() ?>  stole <span class="gold"><?= numecho($this->b->goldStolen) ?></span> gold, which was <?= $this->b->percentStolen ?>% of the total.
			<? } ?>
		<? }
			else {?>
			The forces of <?= $this->target->getNameLink() ?> <span style="font-weight:bold;">defended</span> those 
			of <?= $this->attacker->getNameLink() ?>.
		<? } ?>
	</p>
	
	<p id="damage">
		<?= $this->attacker->getNameLink() ?> did <?= numecho($this->b->attackerDamage) ?> damage to <?= $this->target->getNameLink()   ?>'s weapons<br />
		<?= $this->target->getNameLink()   ?> did <?= numecho($this->b->targetDamage)   ?> damage to <?= $this->attacker->getNameLink() ?>'s weapons<br />
	</p>
	
	<p id="attacker-personnel">
		<?= $this->attacker->getNameLink() ?>'s Personnel:<br />
		<table class="personnel">
			<tr>
				<th>Unit</th>
				<th>Armed</th>
				<th>Unarmed</th>
			</tr>
			<tr>
				<td>Trained Soldier</td>
				<td><?= numecho($this->b->satrained)   ?></td>
				<td><?= numecho($this->b->satrainednw) ?></td>
			</tr>
			<tr>
				<td>Mercenary</td>
				<td><?= numecho($this->b->samercs)   ?></td>
				<td><?= numecho($this->b->samercsnw) ?></td>
			</tr>
			<tr>
				<td>Untrained Soldier</td>
				<td><?= numecho($this->b->sauntrained)   ?></td>
				<td><?= numecho($this->b->sauntrainednw) ?></td>
			</tr>
		</table>
	</p>
	
	<p id="target-personnel">
		<?= $this->target->getNameLink() ?>'s Personnel:<br />
		<table class="personnel">
			<tr>
				<th>Unit</th>
				<th>Armed</th>
				<th>Unarmed</th>
			</tr>
			<tr>
				<td>Trained Soldier</td>
				<td><?= numecho($this->b->datrained)   ?></td>
				<td><?= numecho($this->b->datrainednw) ?></td>
			</tr>
			<tr>
				<td>Mercenary</td>
				<td><?= numecho($this->b->damercs)   ?></td>
				<td><?= numecho($this->b->damercsnw) ?></td>
			</tr>
			<tr>
				<td>Untrained Soldier</td>
				<td><?= numecho($this->b->dauntrained)   ?></td>
				<td><?= numecho($this->b->dauntrainednw) ?></td>
			</tr>
		</table>
	</p>
	
	<p id="losses">
		<?= $this->attacker->getNameLink() ?> lost <?= numecho($this->b->attackerLosses) ?> people.<br />
		<?= $this->target->getNameLink()   ?> lost <?= numecho($this->b->targetLosses)   ?> people.<br />
	</p>
	<p id="pow">
		<?= $this->attacker->getNameLink() ?> took <?= numecho($this->b->attackerHostages) ?> prisoners of war.<br />
		<?= $this->target->getNameLink()   ?> took <?= numecho($this->b->targetHostages)   ?> prisoners of war.<br />
	</p>
	<p id="ra">
		<?= $this->attacker->getNameLink() ?> had a retaliation strength of <?= numecho($this->b->attackerRA) ?> (<?= $this->b->attackerRAPercentage ?>%). <br />
		<?= $this->target->getNameLink() ?> had a retaliation strength of <?= numecho($this->b->targetRA) ?> (<?= $this->b->targetRAPercentage ?>%). <br />
		<? if ($this->b->attackerRA > $this->b->targetRA) { ?>
			<?= $this->attacker->getNameLink() ?> did <?= numecho($this->RADamage) ?> damage to <?= $this->target->getNameLink() ?>'s attack weapons.<br />
		<? }
			else { ?>
			<?= $this->target->getNameLink() ?> did <?= numecho($this->RADamage) ?> damage to <?= $this->attacker->getNameLink() ?>'s defense weapons.<br />
		<? } ?>
	</p>
	<br />
	<? if ($user->getSupport('quick-attack')) { ?>
		<? if ($this->attacker->id == $user->id) {
			$target = $this->target;
		}
		else {
			$target = $this->attacker;
		} ?>
		<p id="isview">
			<a href="attack.php?uid=<?= $target->id ?>&amp;raid=Raid">Attack <?= $target->getNameHTML() ?></a>&nbsp;||&nbsp;
			<a href="spy.php?uid=<?= $target->id ?>&amp;spy=Spy">Spy on <?= $target->getNameHTML() ?></a>
		</p>
	<? } ?>
	
</div>
<!-- End Battle log -->
