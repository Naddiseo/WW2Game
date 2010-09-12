
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
<!-- Begin preferences page -->
<div id="preferences-container">
	<div class="panel">
		<div class="panel-title">
			Preferences and Settings
		</div>
		<form method="post" class="large" autocomplete="off">
		
			<div class="line">
				<label for="prefs-username">Username:</label>
					<? if (!$user->changenick) { ?>
						<input type="text" name="prefs-username" maxlength="25" value="<?= $user->username ?>" />
						<small>Limited to one change per age</small>
					<? } 
						else { ?>
						<span>You have already changed your name this age</span>
					<? } ?>
			</div>
			
			<div class="line">
				<label for="prefs-email">Email:</label>
				<input type="text" name="prefs-email" maxlength="50" value="<?= $user->email ?>" />
				<small>Must be valid</small>
			</div>
			
			<div class="line">
				<label for="prefs-emailv">Email Again:</label>
				<input type="text" name="prefs-emailv" maxlength="50" value="<?= $user->email ?>" />
			</div>			
			
			<div class="line">
				<label for="prefs-old-password">Old Password:</label>
				<input autocomplete="off" type="password" name="prefs-old-password" maxlength="50" value="" />
				<small>Only fill in if you need to change your password</small>
			</div>
			
			<div class="line">
				<label for="prefs-new-password">New Password:</label>
				<input autocomplete="off" type="password" name="prefs-new-password" maxlength="50" value="" />
			</div>
			
			<div class="line">
				<label for="prefs-new-passwordv">New Password:</label>
				<input autocomplete="off" type="password" name="prefs-new-passwordv" maxlength="50" value="" />
			</div>
			
			<div class="line">
				<label for="prefs-nation">Nation:</label>
					
				<? if ($conf['can-change-nation']) { ?>
					<select name="prefs-nation">
						<option value="0" <?= ($user->nation == 0 ? 'selected="selected"': '') ?>>USA</option>
						<option value="1" <?= ($user->nation == 1 ? 'selected="selected"': '') ?>>UK</option>
						<option value="2" <?= ($user->nation == 2 ? 'selected="selected"': '') ?>>Japan</option>
						<option value="3" <?= ($user->nation == 3 ? 'selected="selected"': '') ?>>Germany</option>
						<option value="4" <?= ($user->nation == 4 ? 'selected="selected"': '') ?>>USSR</option>
					</select>
					<small>Please read <a href="changenation.php" target="_blank">About Changing Nations</a> before changing!</small>
				<? }
					else { ?>
					<input type="hidden" name="prefs-nation" value="<?= $user->nation ?>" />
					<span>[ You cannot change nation this late into the age ]</span>	
				<? } ?>
			</div>
			
			<? if ($user->getSupport('minhit')) { ?>
				<div class="line">
					<label for="prefs-minhit">Minimum Hit:</label>
					<input type="text" name="prefs-minhit" value="<?= numecho($user->minattack) ?>" />
				</div>
			<? } ?>
			
			<? if ($this->allow_vacation) { ?>
				<div class="line">
					<label for="prefs-vacation">Vacation Mode:</label>
					<input type="checkbox" name="prefs-vacation" value="yes">&nbsp;Enter Vacation Mode</input>
					<small>You will remain in vacation mode for at least 2 days</small>
				</div>
			<? } ?>
			
			<div class="line">
				<input type="submit" name="prefs-submit" class="submit" value="Save!" />
			</div>
		</form>
	</div>
</div>
<!-- Eng preferences page -->
