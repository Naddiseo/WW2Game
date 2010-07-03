
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
<!-- Begin online page -->
<div id="online-container">
	<div class="panel">
		<div class="panel-title">
			Users Online
		</div>
		<table class="odd-even large">
			<tr>
				<th>Rank</th>
				<th>Nation</th>
				<th>Name</th>
				<th>Army Size</th>
				<? if (Privacy::isAdmin()) { ?>
					<th>IP</td>
				<? } ?>
			</tr>
			<? $area = 0; ?>
			<? foreach($this->users as $target) { ?>
				<? if ($area != $target->area) { $area = $target->area;?>
					<tr><td colspan="4"><?= $conf['area'][$area]['name'] ?></td></tr>
				<? } ?>
				<tr>
					<? if ($target->rank == 0) { ?>
						<td>Unranked</td>
						<td><img title="<?= $target->getNation() ?>" alt="<?= $target->getNation() ?>" src="<?= $this->image($target->getNationFlag()) ?>" /></td>
						<td><?= $target->getNameHTML() ?></td>
						<td><?= numecho($target->getTFF()) ?></td>
					<? }
						else { ?>
						<td><?= numecho($target->rank) ?></td>
						<td><img title="<?= $target->getNation() ?>" alt="<?= $target->getNation() ?>" src="<?= $this->image($target->getNationFlag()) ?>" /></td>
						<td style="text-align:left;">
							<?= $target->getNameLink() ?>
							<? if ($target->alliance) { echo $target->getAlliance()->getTag(); } ?>
						</td>
						<td><?= numecho($target->getTFF()) ?></td>
					<? } ?>
					<? if (Privacy::isAdmin()) { ?>
						<td><?= $target->currentIP ?> <br /> (<?= gethostbyaddr($target->currentIP) ?>)</td>
					<? } ?>
				</tr>
			<? } ?>
			<tr><td colspan="4"><?= numecho($this->usersCount) ?> online players</td></tr>
		</table>
	</div>
</div>
<!-- End online page -->
