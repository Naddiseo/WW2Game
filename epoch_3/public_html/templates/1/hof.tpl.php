
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
<!-- Begin HOF -->
<div id="hof-container">
	<form method="get">
		<select name="age">
			<? for ($i = 1; $i < $this->current_age; $i++) { ?>
				<option value="<?= $i ?>" <?= ($i == $this->age ? 'selected="selected"' : '') ?>>Age <?= $i ?></option>
			<? } ?>
		</select>
		<? if ($this->age >= 15) { ?>
			<select name="area">
				<? for ($i = 1; $i <= 3; $i++) { ?>
					<option value="<?= $i ?>" <?= ($this->area == $i ? 'selected="selected"' : '') ?>><?= $conf['area'][$i]['name'] ?></option>
				<? } ?>
			</select>
		<? } ?>
		<input type="submit" value="Change Age" />
	</form>
	<br />
	<? if ($this->u) { ?>
		<div class="panel">
			<div class="panel-title">
				<?= $this->u->username ?>'s Stats
			</div>
			<div class="large">
				<div class="line">
					<label>SA</label>
					<span><?= numecho($this->u->sa) ?></span>
					<small>Soldiers: <?= numecho($this->u->trainedsa) ?> ||  Mercs: <?= numecho($this->u->samerc) ?></small>
				</div>
				<div class="line">
					<label>DA</label>
					<span><?= numecho($this->u->da) ?></span>
					<small>Soldiers: <?= numecho($this->u->trainedda) ?> ||  Mercs: <?= numecho($this->u->damerc) ?></small>
				</div>
				<div class="line">
					<label>CA</label>
					<span><?= numecho($this->u->ca) ?></span>
					<small>Spies: <?= numecho($this->u->spies) ?> || Covert Tech: <?= numecho($this->u->caupgrade) ?></small>
				</div>
				<div class="line">
					<label>RA</label>
					<span><?= numecho($this->u->ra) ?></span>
					<small>Special Forces: <?= numecho($this->u->sf) ?> || Retal Tech: <?= numecho($this->u->raupgrade) ?></small>
				</div>
			</div>
		</div>
	<? } ?>

	<div class="panel">
		<div class="panel-title">
			Rankings
		</div>
		<table class="large">
			<tr>
				<th>Rank</th>
				<th>Nation</th>
				<th>Username</th>
				<th>SA Rank</th>
				<th>DA Rank</th>
				<th>CA Rank</th>
				<th>RA Rank</th>
				<th>Rank Average</th>
			</tr>
			<? $rank = 0; $lastR = 0; ?>
			<? foreach ($this->ranks as $r) { ?>
				<? if ($r->rave > $lastR) { $rank++; }  ?>
				<!--<? var_dump($r) ?>-->
				<tr>
					<td><?= $rank ?></td>
					<td><img src="<?= $this->image('nation' . $r->nation . '.gif')?>" /></td>
					<td><a href="hof.php?age=<?= $this->age ?>&amp;uid=<?= $r->id ?>&amp;area=<?= $this->area ?>"><?= $r->username ?></a></td>
					<td><?= numecho($r->sarank) ?></td>
					<td><?= numecho($r->darank) ?></td>
					<td><?= numecho($r->carank) ?></td>
					<td><?= numecho($r->rarank) ?></td>
					<td><?= number_format($r->rave * 0.25, 2) ?></td>
				</tr>
				<? $lastR = $r->rave; if ($rank == 100) { break; }?>
			<? } ?>
		</table>
	</div>
</div>
<!-- End HOF -->
