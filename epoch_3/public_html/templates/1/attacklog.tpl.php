
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
			Defense Logs
		</div>
		<table>
			<tr>
				<th>Time</th>
				<th>Attacker</th>
				<th>Result</th>
				<th>Report</th>
			</tr>
			<? foreach ($this->defenseLogs as $log) { ?>
				<? $attacker = getCachedUser($log->attackerId) ?>
				<tr>
					<td class="time"><?= $log->getTime() ?></td>
					<td>
						<?= $attacker->getNameLink() ?>
					</td>
					<td>
						<? if ($log->getSuccess()) { ?>
							<?= numecho($log->goldStolen) ?> Gold Stolen
						<? }
							else { ?>
							Defended
						<? } ?>
					</td>
					<td class="details">
						<a href="battlelog.php?id= <?=$log->id ?>&amp;isview=1">details</a>
						<? if ($user->getSupport('attacklog-info')) { ?>
							<a alt="Show hidden information" title="Show hidden information" href="#" onclick="javascript: return unhideAttackLogInfo(this, 'd<?= $log->id ?>')">
								 [ + ]
							</a>
						<? } ?>
					</td>
				</tr>
				<? if ($user->getSupport('attacklog-info')) { ?>
					<tr class="attacklog-hidden-info" id="hidden-1-d<?= $log->id ?>">
						<td>&nbsp;</td>
						<td>Attack turns</td>
						<td colspan="2"><?= $log->attackType ?></td>
					</tr>
					<tr class="attacklog-hidden-info" id="hidden-2-d<?= $log->id ?>">
						<td>&nbsp;</td>
						<td>Enemy Losses</td>
						<td colspan="2"><?= numecho($log->attackerLosses) ?></td>
					</tr>
					<tr class="attacklog-hidden-info" id="hidden-3-d<?= $log->id ?>">
						<td>&nbsp;</td>
						<td>Your Losses:</td>
						<td colspan="2"><?= numecho($log->targetLosses) ?></td>
					</tr>
					<tr class="attacklog-hidden-info" id="hidden-4-d<?= $log->id ?>">
						<td>&nbsp;</td>
						<td>Enemy Damage:</td>
						<td colspan="2"><?= numecho ($log->attackerStrength) ?></td>
					</tr>
					<tr class="attacklog-hidden-info attacklog-hidden-last" id="hidden-5-d<?= $log->id ?>">
						<td>&nbsp;</td>
						<td>Your Damage:</td>
						<td colspan="2"><?= numecho ($log->targetStrength ) ?></td>
					</tr>
				<? } ?>
			<? } ?>
			<tr>
				<td>
					<? if ($this->dpage > 1) { ?>
						<a href="?defense-page=<?= $this->dpage - 1?>&amp;attack-page=<?= $this->apage ?>">&lt;&lt;</a>&nbsp;
					<? } else { echo "<<"; } ?>
				</td>
				<td colspan="2">
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
				<td colspan="4">Page <?= numecho($this->dpage) ?> of <?= numecho($this->totalDPages) ?></td>
			</tr>
			<tr><td colspan="4"><?= numecho($this->totaldAttacks) ?> Total Attacks</td></tr>
		</table>
	</div>


	<div class="panel">
		<div class="panel-title">
			Attack Logs
		</div>
		<table>
			<tr>
				<th>Time</th>
				<th>Defender</th>
				<th>Result</th>
				<th>Report</th>
			</tr>
			<? foreach ($this->attackLogs as $log) { ?>
				<? $defender = getCachedUser($log->targetId) ?>
				<tr>
					<td class="time"><?= $log->getTime() ?></td>
					<td>
						<?= $defender->getNameLink() ?>
					</td>
					<td>
						<? if ($log->getSuccess()) { ?>
							<?= numecho($log->goldStolen) ?> Gold Stolen
						<? }
							else { ?>
							Defended
						<? } ?>
					</td>
					<td class="details">
						<a href="battlelog.php?id= <?=$log->id ?>&amp;isview=1">details</a>
						<? if ($user->getSupport('attacklog-info')) { ?>
							<a alt="Show hidden information" title="Show hidden information" href="#" onclick="javascript: return unhideAttackLogInfo(this, 'a<?= $log->id ?>')">
								 [ + ]
							</a>
						<? } ?>
					</td>
				</tr>
				<? if ($user->getSupport('attacklog-info')) { ?>
					<tr class="attacklog-hidden-info" id="hidden-1-a<?= $log->id ?>">
						<td>&nbsp;</td>
						<td>Attack turns</td>
						<td colspan="2"><?= $log->attackType ?></td>
					</tr>
					<tr class="attacklog-hidden-info" id="hidden-2-a<?= $log->id ?>">
						<td>&nbsp;</td>
						<td>Enemy Losses</td>
						<td colspan="2"><?= numecho($log->targetLosses) ?></td>
					</tr>
					<tr class="attacklog-hidden-info" id="hidden-3-a<?= $log->id ?>">
						<td>&nbsp;</td>
						<td>Your Losses:</td>
						<td colspan="2"><?= numecho($log->attackerLosses) ?></td>
					</tr>
					<tr class="attacklog-hidden-info" id="hidden-4-a<?= $log->id ?>">
						<td>&nbsp;</td>
						<td>Enemy Damage:</td>
						<td colspan="2"><?= numecho ($log->targetStrength) ?></td>
					</tr>
					<tr class="attacklog-hidden-info attacklog-hidden-last" id="hidden-5-a<?= $log->id ?>">
						<td>&nbsp;</td>
						<td>Your Damage:</td>
						<td colspan="2"><?= numecho ($log->attackerStrength ) ?></td>
					</tr>
				<? } ?>
			<? } ?>
			<tr>
				<td>
					<? if ($this->apage > 1) { ?>
						<a href="?defense-page=<?= $this->dpage ?>&amp;attack-page=<?= $this->apage - 1 ?>">&lt;&lt;</a>&nbsp;
					<? } else { echo "<<"; } ?>
				</td>
				<td colspan="2">					
					<? for($i = 1; $i <= $this->totalPages; $i++) { ?>
						<a <?= ($i == $this->apage ? 'class="selected"' : '') ?> href="?defense-page=<?= $this->dpage ?>&amp;attack-page=<?= $i ?>"><?= numecho($i) ?></a>&nbsp;
					<? } ?>
				</td>
				<td>
					<? if ($this->apage < $this->totalPages) { ?>
						&nbsp;<a href="?defense-page=<?= $this->dpage?>&amp;attack-page=<?= $this->apage + 1 ?>">&gt;&gt;</a>&nbsp;
					<? } else { echo ">>"; } ?>
				</td>
			</tr>
			<tr>
				<td colspan="4">Page <?= numecho($this->apage) ?> of <?= numecho($this->totalPages) ?></td>
			</tr>
			<tr><td colspan="4"><?= numecho($this->totalAttacks) ?> Total Attacks</td></tr>
		</table>
	</div>
	
</div>
<!-- End Attack Logs -->
