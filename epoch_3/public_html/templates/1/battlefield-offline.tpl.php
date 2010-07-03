
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
<!-- Begin battlefield page -->
<div id="battlefield-container">
	<div class="panel">
		<div class="panel-title">
			Ranking 
			<span class="small">(
				<? foreach ($conf['area'] as $n => $a) { ?>
					<? if ($this->area == $n) { ?>
						<?= $conf['area'][$n]['name'] ?>		
					<? }
						else { ?>
						<a href="?area=<?= $n ?>"><?= $conf['area'][$n]['name'] ?></a>
					<? } ?>
				<? } ?>
			)</span>
		</div>
		<table class="odd-even large">
			<tr>
				<th>Rank</th>
				<th>Nation</th>
				<th>Name</th>
				<th>Army Size</th>
			</tr>
			<? foreach($this->users as $target) { ?>
				<tr>
					<td><?= numecho($target->rank) ?></td>
					<td><img title="<?= $target->getNation() ?>" alt="<?= $target->getNation() ?>" src="<?= $this->image($target->getNationFlag()) ?>" /></td>

					<td class="<?= $class ?>" style="text-align: left;">
						<?= $target->getNameLink('', true) ?>
						<? if ($target->alliance) { echo $target->getAlliance()->getTag(); } ?>
					</td>
					<td><?= numecho($target->getTFF()) ?></td>
				</tr>
			<? } ?>
			<tr>
				<td>
					<? if ($this->page > 1) { ?>
						<a href="?page=<?= $this->page - 1?>&amp;search=<?= urlencode($this->search) ?>&amp;search-type=<?= $this->searchType ?>&amp;area=<?= $this->area ?>">&lt;&lt;</a>&nbsp;
					<? } else { echo "<<"; } ?>
				</td>
				<td colspan="<?= ($quickAttack ? 3 : 2) ?>">
					<? for($i = 1; $i <= $this->totalPages; $i++) { ?>
						<a <?= ($i == $this->page ? 'class="selected"' : '') ?> href="?page=<?= $i ?>&amp;search=<?= urlencode($this->search) ?>&amp;search-type=<?= $this->searchType ?>&amp;area=<?= $this->area ?>"><?= numecho($i) ?></a>&nbsp;
					<? } ?>
				</td>
				<td>
					<? if ($this->page < $this->totalPages) { ?>
						&nbsp;<a href="?page=<?= $this->page + 1?>&amp;search=<?= urlencode($this->search) ?>&amp;search-type=<?= $this->searchType ?>&amp;area=<?= $this->area ?>">&gt;&gt;</a>&nbsp;
					<? } else { echo ">>"; } ?>
				</td>
			</tr>
			<tr>
				<td colspan="<?= ($quickAttack ? 5 : 4) ?>">Page <?= numecho($this->page) ?> of <?= numecho($this->totalPages) ?></td>
			</tr>
			<tr><td colspan="<?= ($quickAttack ? 5 : 4) ?>"><?= numecho($this->usersCount) ?> active players</td></tr>
		</table>
		<form method="get" class="large">
			<div class="line">
				<label>Area</label>
				<select name="area">
					<option <?= (!$this->area  ? 'selected="selected"' : '') ?> value="<?= $conf['area-count'] + 1 ?>">All</option>
					<? foreach ($conf['area'] as $n => $a) { ?>
						<option <?= ($this->area == $n ? 'selected="selected"' : '') ?> value="<?= $n ?>"><?= $a['name'] ?></option>
					<? } ?>
				</select>
			</div>
			<div class="line">
				<label>Search Type</label>
				<select name="search-type">
					<option value="1">Begins with</option>
					<option value="2">Ends with</option>
					<option value="3">Contains</option>
				</select>
			</div>
			<div class="line">
				<label>Query</label>
				<input type="text" maxlength="25" name="search" />
			</div>
			<div class="line">
				<input type="submit" class="submit" value="Search!" />
			</div>
		</form>
	</div>
</div>
<!-- End battlefield page -->
