
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
<!-- Begin Messages page -->
<div id="messages-container">
	<div class="panel">
		<? if ($this->messages) { ?>
			<div class="panel-title">
				Messages&nbsp;<span class="no-hl">(<a href="messages.php?view=0">Inbox</a> || <a href="messages.php?view=1">Outbox</a> || <a href="writemail.php">New</a> || <a href="message-ignore.php">Ignore List</a>)</span><br />
				Age: <?global $current_age, $first_age;
				for ($i = $first_age; $i <= $current_age; $i++) { ?>
					<? if ($this->age == $i) { ?>
						<span><?= $i ?></span>
					<? }
					else { ?>
						<a href="messages.php?age=<?= $i ?>&amp;v=<?= $this->view ?>"><?= $i ?></a>
					<? } ?> |
				<? } ?>
				<? if ($this->age == -1) { ?>
					<span>All</span>
				<? }
				else { ?>
					<a href="messages.php?age=-1&amp;view=<?= $this->view ?>">All</a>
				<? } ?>
			</div>
			<form id="message-list-form" method="post" class="large">
				<input type="hidden" name="view" value="<?= $this->view ?>" />
				<table>
					<tr>
						<th>Date</th>
						<th><?= ($this->view == 1 ? 'Receiver' : 'Sender') ?></th>
						<th>Title</th>
						<th><input type="submit" value="Delete" name="delete-button" /><button onclick="return Message.selectAll(this);">Select All</button></th>
					</tr>
					<!-- TODO: and user caching -->
					<? foreach ($this->messages as $msg) { ?>
						<? 
							$s     = ($this->view == 1 ? $msg->senderStatus : $msg->targetStatus);
							$class = ($s == MSG_STATUS_UNREAD ? 'unread' : '');
						?>
						<tr class="<?= $class ?>">
							<td><?= $msg->getTime() ?></td>
							<td><?= getCachedUser(($this->view == 1 ? $msg->targetId : $msg->senderId))->getNameLink() ?></td>

							<td>
								<a href="messages.php?id=<?= $msg->id ?>">
									<?= $msg->subject ?>
								</a>
								<? if ($msg->fromadmin) { ?>
									<span style="color:red">[From Admin]</span>
								<? } ?>
							</td>
							<td>
								<input type="checkbox" value="<?= $msg->id ?>" name="delete[]" />
							</td>
						</tr>
					<? } ?>
				</table>
			</form>
		<? }
			else if ($this->message) { ?>
			<? $a = new User(); $a->get($this->message->senderId) ?>
			<div class="panel-title">
				<?= $this->message->subject ?><br />
				<span class="no-hl">(<a href="messages.php?view=0">Inbox</a> || <a href="messages.php?view=1">Outbox</a> || <a href="writemail.php">New</a> || <a href="message-ignore.php">Ignore List</a>)</span>
			</div>
			<div class="message-date">
				<span>Date sent: <?= $this->message->getTime('M jS H:i', 'M jS H:i') ?></span>
				<form id="message-delete" action="messages.php" method="post">
					<input type="hidden" name="view" value="<?= (me($this->message->senderId) ? 1 : 0) ?>" />
					<input type="hidden" value="<?= $this->message->id ?>" name="delete[]" />
					<input type="submit" value="Delete" name="delete-button" />
				</form>
				<div class="clear flat"></div>
			</div>
			<div class="message-from-box">
				<span>From: <?= $a->getNameLink() ?></span>
				<div class="clear flat"></div>
			</div>
			<div id="message-text">
				<?= $this->message->getBB(); ?>
			</div>
			<form method="post" action="writemail.php">
				<input type="hidden" value="<?= $this->message->id ?>" name="msg-id" />
				<input type="hidden" value="<?= $this->message->senderId ?>" name="to" />
				<input type="submit" value="Reply to <?= $a->getNameHTML() ?>" />
			</form>
			<form method="post" action="message-ignore.php">
				<input type="hidden" name="ignore-username" value="<?= addslashes($a->username) ?>" />
				<input type="submit" name="ignore-add-submit" value="Ignore <?= $a->getNameHTML() ?>" />
			</form>
		<? } 
			else { ?>
			<div class="panel-title">
				Messages&nbsp;<span class="no-hl">(<a href="messages.php?view=0">Inbox</a> || <a href="messages.php?view=1">Outbox</a> || <a href="writemail.php">New</a> || <a href="message-ignore.php">Ignore List</a>)</span><br />
				Age: <?global $current_age, $first_age;
				for ($i = $first_age; $i <= $current_age; $i++) { ?>
					<? if ($this->age == $i) { ?>
						<span><?= $i ?></span>
					<? }
					else { ?>
						<a href="messages.php?age=<?= $i ?>&amp;v=<?= $this->view ?>"><?= $i ?></a>
					<? } ?> |
				<? } ?>
				<? if ($this->age == -1) { ?>
					<span>All</span>
				<? }
				else { ?>
					<a href="messages.php?age=-1&amp;view=<?= $this->view ?>">All</a>
				<? } ?>
			</div>
			<p>You have no messages</p>
		<? } ?>
	</div>
</div>
<!-- End Message page -->
