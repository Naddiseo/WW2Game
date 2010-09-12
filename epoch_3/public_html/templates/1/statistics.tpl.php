
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
<!-- Begin Statistics page -->
<div id="statistics-container">
	<div class="panel">
		<div class="panel-title">
			Misc
		</div>
	
		<div class="line">
			<label>Total Gold in Battlefeild</label>
			<span><?= numecho($conf['totalGold']) ?></span>
		</div>
	</div>
	<? foreach (array('europe' => 'Europe', 'africa' => 'Africa', 'graveyard' => 'The Graveyard') as $k => $v) { ?>
		<div class="panel">
			<div class="panel-title">
				<?= $v ?> Statistics (Last 48 hours)
			</div>
			<div class="large">
				<div class="line">
					<label>Average SA</label>
					<span><?= numecho($this->stats[$k]['avgs']['saAvg']) ?></span>
				</div>
			
				<div class="line">
					<label>Average DA</label>
					<span><?= numecho($this->stats[$k]['avgs']['daAvg']) ?></span>
				</div>
			
				<div class="line">
					<label>Average CA</label>
					<span><?= numecho($this->stats[$k]['avgs']['caAvg']) ?></span>
				</div>
			
				<div class="line">
					<label>Average RA</label>
					<span><?= numecho($this->stats[$k]['avgs']['raAvg']) ?></span>
				</div>
			
				<div class="line">
					<label>Average Army Size</label>
					<span><?= numecho($this->stats[$k]['avgs']['armyAvg']) ?></span>
				</div>
			
			
				<div class="line">
					<label>Average Unit Production</label>
					<span><?= numecho($this->stats[$k]['avgs']['upAvg']) ?></span>
				</div>
			
				<div class="line">
					<label>Average Banked Gold</label>
					<span><?= numecho($this->stats[$k]['avgs']['bankAvg']) ?></span>
				</div>
			
				<div class="line">
					<label>Total Gold in Battlefeild</label>
					<span><?= numecho($this->stats[$k]['avgs']['totalGold']) ?></span>
				</div>
				
			</div>
		</div>
		<div class="panel">
			<div class="panel-title">
				<?= $v ?> Top 10 Hits
			</div>
			<table class="large">
				<tr>
					<th>Amount</th>
					<th>Attacker</th>
					<th>Defender</th>
				</tr>
			<? foreach ($this->stats[$k]['hits']['top10'] as $k => $v) { ?>
				<tr>
					<td><?= numecho($v['goldStolen']) ?></td>
					<td><?= getCachedUser($v['attackerId'])->getNameLink() ?></td>
					<td><?= getCachedUser($v['targetId'])->getNameLink() ?></td>
				</tr>
			<? } ?>
			</table>
		</div>
	<? } ?>
</div>
<!-- End Statistics page -->
