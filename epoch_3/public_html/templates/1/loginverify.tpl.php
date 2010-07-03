
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
<!-- Begin login verify -->
<div id="loginverify-container">
	<div class="panel">
		<div class="panel-title">
			Login Verification
		</div>
		<div class="email-help">
			<p>Help: <br />If you have trouble activating or verifying your account, please try the <a href="forgetpass.php">Forgot Password</a> page.
			This should send a new password. If this still does not work, please <a href="contact.php">contact us</a>, and provide your username.</p>
		</div>
		<form class="large" method="post" autocomplete="off">
			<div class="line">
				<label for="verify-username">Username:</label>
				<input type="text" name="verify-username" maxlength="25" />
			</div>
			
			<div class="line">
				<label for="verify-email">Email:</label>
				<input type="text" name="verify-email" maxlength="100" />
			</div>
			
			<div class="line">
				<label for="verify-password">Password:</label>
				<input type="password" name="verify-password" maxlength="100" />
			</div>
			
			<div class="line">
				<input type="submit" name="verify-submit" class="submit" value="Login!" />
			</div>
		</form>
	</div>
</div>
<!-- End login verify -->
