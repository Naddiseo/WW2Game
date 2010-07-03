
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
<!-- Begin Message Ignore list -->
<div id="message-ignore-container">
	<div class="panel">
		<div class="panel-title">
			Ignore List&nbsp;<span class="no-hl">(<a href="messages.php?view=0">Inbox</a> || <a href="messages.php?view=1">Outbox</a> || <a href="writemail.php">New</a> || <a href="message-ignore.php">Ignore List</a>)</span>
		</div>
		<form class="large" method="post">
		
			<div class="line"><p class="info">Add User to Ignore List</p></div>
			
			<div class="line">
				<label for="ignore-username">User:</label>
				<input type="text" name="ignore-username" />
			</div>
			<div class="line">
				<label for="ignore-note">Note/Reason: </label>
				<input type="text" name="ignore-note" />
			</div>
			<div class="line">
				<input class="submit" type="submit" name="ignore-add-submit" value="Ignore User" />			
			</div>
			<table>
				<tr>
					<th>Date</th>
					<th>User</th>
					<th>Note</th>
					<th><input type="submit" name="ignore-remove-submit" value="Remove" /></th>
				</tr>
				<? foreach ($this->ignoreList as $i) { ?>
					<tr>
						<td><?= date('H:i d/M', $i->time) ?></td>
						<td><?= getCachedUser($i->targetId)->getNameLink() ?></td>
						<td><?= htmlentities($i->note) ?> </td>
						<td>
							<input name="ignore-remove[]" type="checkbox" value="<?= $i->id ?>" />
						</td>
					</tr>
				<? } ?>
			</table>					
		</form>
	</div>
</div>
<!-- End Message ignore list -->
