
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
<!-- Begin register page -->
<div id="register-container">
	<div class="panel">
		<div class="panel-title">
			Register a new World War 2 account
		</div>
		<div class="email-help">
			<p>Help: <br />If you have trouble activating or verifying your account, please try the <a href="forgotpass.php">Forgot Password</a> page.
			This should send a new password. If this still does not work, please <a href="contact.php">contact us</a>, and provide your username.</p>
		</div>
		<form method="post">			
			<div class="register-line">
				<label for="register-username">Username</label>
				<input type="text" name="register-username" maxlength="25" value="<?= $this->username ?>" />
				<small>Must be between 3 and 25 characters</small>
			</div>
			
			<div class="register-line">
				<label for="register-email">Email</label>			
				<input type="text" name="register-email" maxlength="100" value="<?= $this->email ?>" />
			</div>
			
			<div class="register-line">
				<label for="register-emailv">Email Again</label>
				<input type="text" name="register-emailv" maxlength="100" value="<?= $this->emailv ?>" />
				<small>Must be the same as above</small>
			</div>
			
			<div class="register-line">
				<p class="info">
				An activation password will be emailed to you. <br />Please add signups&#64;ww2game&#46;&#110;et to your email whitelists.
				</p>
			</div>
			
			<div class="register-line">
				<label for="register-nation">Nation</label>
				<select name="register-nation">
					<option value="0" <?= ($this->nation == 0 ? 'selected="selected"': '') ?>>United States (USA)</option>
					<option value="1" <?= ($this->nation == 1 ? 'selected="selected"': '') ?>>Great Britain (UK)</option>
					<option value="2" <?= ($this->nation == 2 ? 'selected="selected"': '') ?>>Japan</option>
					<option value="3" <?= ($this->nation == 3 ? 'selected="selected"': '') ?>>Germany</option>
					<option value="4" <?= ($this->nation == 4 ? 'selected="selected"': '') ?>>Union of Soviet Socialist Republic (USSR)</option>
				</select>
			
			</div>
			
			<div class="register-line">							
				<label class="lbl-chkbox" for="register-rules">
					<input class="input-chkbox" type="checkbox" name="register-rules" value="true" <?= ($this->rules ? 'checked="checked"' : '') ?>/>
					I have read and agree to the <a href="rules.php">rules</a> and <a href="tos.php">Terms of Service</a>
				</label>				
			</div>
			
			<? if ($this->referrer) { ?>
			<div class="register-line">
				<label for="register-referrer">Referrer</label>
				<input type="hidden" name="register-referrer" value="<?= $this->referrer->id ?>" />
				<span><?= $this->referrer->getNameLink('', true) ?></span>
			</div?
			<? } ?>
			
			<div class="register-line">
				<label for="captcha-image">Picture</label>
				<img src="imageclick.php?<?= (session_name() . '=' . session_id()) ?>" alt="random chars">
			</div>
			<div class="register-line">
				<label for="register-captcha">Number</label>
				<select name="register-captcha">
					<option value="1">one</option>
					<option value="2">two</option>
					<option value="3">three</option>
					<option value="4">four</option>
					<option value="5">five</option>
					<option value="6">six</option>
					<option value="7">seven</option>
					<option value="8">eight</option>
					<option value="9">nine</option>
					<option value="10">ten</option>
					<option value="11">eleven</option>
					<option value="12">twelve</option>
					<option value="13">thirteen</option>
					<option value="14">fourteen</option>
					<option value="15">fifteen</option>			
				</select>
				<small>Cookies must be enabled</small>
			</div>
			
			<div class="register-line">
				<input class="submit" type="submit" name="register-submit" value="Register" />
			</div>
		</form>
	</div>
</div>
<!-- End register page -->
