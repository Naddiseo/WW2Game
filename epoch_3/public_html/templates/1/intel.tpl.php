
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
<!-- Begin Attack Logs -->
<div id="attacklog-container">

	<div class="panel">
		<div class="panel-title">
			Intercepted Intelligence Reports
		</div>
		<table>
			<tr>
				<th>Time</th>
				<th>Enemy</th>
				<th>Mission Type</th>
				<th>Result</th>
				<th>Report</th>
			</tr>
			<? foreach ($this->defenseLogs as $log) { ?>
				<? $attacker = getCachedUser($log->attackerId) ?>
				<tr>
					<td><?= $log->getTime() ?></td>
					<td>
						<?= $attacker->getNameLink() ?>
					</td>
					<td>
						<?= ($log->type == 1 ? 'Thievery' : ($log->type == 2 ? 'Gold Theft' : 'Recon')) ?>
					</td>
					<td>
						<?= ($log->isSuccess ? 'Success' : 'Fail') ?>
					</td>
					<td>
						<a href="spylog.php?id=<?= $log->id ?>&amp;isview=1">details</a>
					</td>
				</tr>
			<? } ?>
			<tr>
				<td>
					<? if ($this->dpage > 1) { ?>
						<a href="?defense-page=<?= $this->dpage - 1?>&amp;attack-page=<?= $this->apage ?>">&lt;&lt;</a>&nbsp;
					<? } else { echo "<<"; } ?>				
				</td>
				<td colspan="3">
					<? for($i = 1; $i <= $this->totalDPages; $i++) { ?>
						<a <?= ($i == $this->dpage ? 'class="selected"' : '') ?> href="?defense-page=<?= $i ?>&amp;attack-page=<?= $this->apage ?>"><?= numecho($i) ?></a>&nbsp;
					<? } ?>
				</td>
				<td>					
					<? if ($this->dpage < $this->totalDPages) { ?>
						&nbsp;<a href="?defense-page=<?= $this->dpage + 1?>&amp;attack-page=<?= $this->apage ?>">&gt;&gt;</a>&nbsp;
					<? } else { echo ">>"; } ?>
				</td>
			</tr>
			<tr>
				<td colspan="5">Page <?= numecho($this->dpage) ?> of <?= numecho($this->totalDPages) ?></td>
			</tr>
			<tr><td colspan="5"><?= numecho($this->totaldAttacks) ?> Total Operations</td></tr>
		</table>
	</div>



	<div class="panel">
		<div class="panel-title">
			Outgoing Intelligence Reports
		</div>
		<table>
			<tr>
				<th>Time</th>
				<th>Enemy</th>
				<th>Mission Type</th>
				<th>Result</th>
				<th>Report</th>
			</tr>
			<? foreach ($this->attackLogs as $log) { ?>
				<? $defender = getCachedUser($log->targetId) ?>
				<tr>
					<td><?= $log->getTime() ?></td>
					<td>
						<?= $defender->getNameLink() ?>
					</td>
					<td>
						<?= ($log->type == 1 ? 'Thievery' : 'Recon') ?>
					</td>
					<td>
						<?= ($log->isSuccess ? 'Success' : 'Fail') ?>
					</td>
					<td>
						<a href="spylog.php?id=<?= $log->id ?>&amp;isview=1">details</a>
					</td>
				</tr>
			<? } ?>
			<tr>
				<td>
					<? if ($this->apage > 1) { ?>
						<a href="?defense-page=<?= $this->dpage ?>&amp;attack-page=<?= $this->apage - 1?>">&lt;&lt;</a>&nbsp;
					<? } else { echo "<<"; } ?>
				</td>
				<td colspan="3">
					<? for($i = 1; $i <= $this->totalPages; $i++) { ?>
						<a <?= ($i == $this->apage ? 'class="selected"' : '') ?> href="?defense-page=<?= $this->dpage ?>&amp;attack-page=<?= $i ?>"><?= numecho($i) ?></a>&nbsp;
					<? } ?>
				</td>
				<td>
					<? if ($this->apage < $this->totalPages) { ?>
						&nbsp;<a href="?defense-page=<?= $this->dpage ?>&amp;attack-page=<?= $this->apage + 1?>">&gt;&gt;</a>&nbsp;
					<? } else { echo ">>"; } ?>
				</td>
			</tr>
			<tr>
				<td colspan="5">Page <?= numecho($this->apage) ?> of <?= numecho($this->totalPages) ?></td>
			</tr>
			<tr><td colspan="5"><?= numecho($this->totalAttacks) ?> Total Operations</td></tr>
		</table>
	</div>
	
</div>
<!-- End Attack Logs -->
