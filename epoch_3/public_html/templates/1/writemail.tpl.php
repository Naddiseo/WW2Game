
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
<!-- Begin write mail page -->
<div id="writemail-container">
	<div class="panel">
		<div class="panel-title">
			Write a new Message
		</div>
		<form method="post" class="large">
		
			<!-- The msg id that we're replying to -->
			<input type="hidden" name="msg-id" value="<?= $this->msgId ?>" />
		
			<? if (!($this->alliance and $this->officers)) { ?>
				<div class="line">
					<label>Quick Links</label>
					<span>
						<!-- 11 Nov, 09: make sure the user can't message the alliance when not accepted -->
						<? if ($user->alliance and $user->aaccepted and !$this->alliance) { ?>
							<input type="submit" value="Alliance" name="alliance" />
						<? } ?>
						<? if ($user->numofficers and !$this->officers) { ?>
							<input type="submit" value="Officers" name="officers" />
						<? } ?>
					</span>
				</div>
			<? } ?>
			<div class="line" id="todiv">
				<label>To:</label>
				<? if ($this->alliance) { ?>
					<span>Alliance</span>
					<input type="hidden" name="alliance" value="yes" />
				<? } ?>
				<? if ($this->officers) { ?>
					<span>Officers</span>
					<input type="hidden" name="officers" value="yes" />
				<? } ?>
				<? foreach ($this->targets as $t) { ?>
					<span><?= $t->getNameLink() ?></span>
					<input type="hidden" name="to[]" value="<?= $t->id ?>" />
				<? } ?>
			</div>
			<div class="line" style="position:relative;">
				<label>Add user</label>
				<input type="text" onkeypress="javascript:Message.ajaxSearch(this);" name="search-name" /><input type="submit" value="Search" name="user-search" />
				<div id="search-users" style="<?= (count($this->searchUsers) > 0 ? '' : 'display:none') ?>" >
					<? foreach ($this->searchUsers as $target) { ?>
						<input type="checkbox" name="to[]" value="<?= $target->id?>" /><?= $target->getNameLink() ?>
					<? } ?>
					<input type="submit" name="add-search-user" value="Add Selected" />
					<div class="clear flat"></div>
				</div>
			</div>
			<div class="line">
				<label>Subject:</label>
				<input type="text" name="subject" value="<?= ($this->quote->subject ? $this->quote->subject : '') ?>" />
			</div>
			<div class="line">
				<textarea id="msg-text" rows="15" cols="45" name="text"><?
					if ($this->quote->text) {
						echo $this->quote->text;
					}
				 ?></textarea>
			</div>
			<div class="line">
				<input class="submit" type="submit" value="Send!" name="msg-submit" />
				<? if ($user->admin) { ?>
					<input type="checkbox" name="admin" value="1" /> 
				<? } ?>
			</div>
		</form>
	</div>
</div>
<!-- End write mail page -->
