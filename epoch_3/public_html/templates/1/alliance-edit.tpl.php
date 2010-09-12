
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
<!-- Begin Alliance Edit -->
<div id="allianceedit-container">
	<div class="panel">
		<div class="panel-title">
			Edit <? $this->load('alliance-header') ?>
		</div>
		<form class="large" method="post">
			<div class="line">
				<label>Name:</label>
				<input type="text" name="alliance-name" maxlength="30" value="<?= $this->alliance->getNameHTML() ?>" />

			</div>
			<div class="line">
				<label>Tag:</label>
				<input type="text" name="alliance-tag" maxlength="6" value="<?= addslashes($this->alliance->tag) ?>" />

			</div>
			<div class="line">
				<label>Alliance URL:</label>
				<input type="text" name="alliance-url" maxlength="255" value="<?= 
					(strpos($this->alliance->url, 'http://') === false ? 'http://' . $this->alliance->url : $this->alliance->url ) 
				?>" />
			</div>
			<div class="line">
				<label>IRC Server:</label>
				<input type="text" name="alliance-ircserver" maxlength="255" value="<?= $this->alliance->getServer() ?>" />
			</div>
			<div class="line">
				<label>IRC Channel:</label>
				<input type="text" name="alliance-ircchannel" maxlength="10" value="<?= $this->alliance->getChannel() ?>" />
			</div>
			<div class="line">
				<label>Status:</label>
				<select name="alliance-status">
					<option value="0" <?= ($this->alliance->status == 0 ? 'selected="selected"' : '') ?>>Open to new players</option>
					<option value="1" <?= ($this->alliance->status == 1 ? 'selected="selected"' : '') ?>>Closed to new players</option>
				</select>
			</div>
			<div class="line">
				<label for="alliance-message">Welcome Message</label>
				<input name="alliance-message" maxlength="255" value="<?= addslashes($this->alliance->message) ?>" />
			</div>
			<div class="line">
				<label>Alliance News</label>
				<textarea name="alliance-news" rows="2" cols="20"><?= $this->alliance->news ?></textarea>
			</div>
			<div class="line">
				<input class="submit" type="submit" value="Edit" name="alliance-submit" />
			</div>
			<div class="flat clear"></div>
		</form>
	</div>
</div>
<!-- End Alliance Edit -->
