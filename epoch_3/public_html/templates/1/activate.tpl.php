
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

<!-- Begin activation page -->
<div id="activation-container">
	<div class="panel">
		<div class="panel-title">
			Activate Your Account
		</div>
		<div class="email-help">
			<p>Help: <br />If you have trouble activating or verifying your account, please try the <a href="forgotpass.php">Forgot Password</a> page.
			This should send a new password. If this still does not work, please <a href="contact.php">contact us</a>, and provide your username.</p>
		</div>
		<form method="post">
			<input type="hidden" name="activation-id" value="<?= $this->activationId ?>" />
			<table>
				<tr>
					<td>Username</td>
					<td>	
						<? /* Can this be exploited somehow? */ ?>						
						<? if (!$this->activationId) { ?>
							<input type="text" maxlength="25" name="activation-username" />							
						<? }
							else { ?>
							<?= $this->username ?>
						<? } ?>
					</td>
				</tr>
				<? if ($this->activationPassword) { ?>
					<input type="hidden" name="activation-apassword" value="<?= $this->activationPassword ?>" />
				<? }
				else { ?>
					<tr>
						<td>Activation Password</td>
						<td>
							<input type="password" maxlength="100" name="activation-apassword" />
						</td>
					</tr>
				<? } ?>
				<tr>
					<td>
						New Password
					</td>
					<td>
						<input type="password" maxlength="100" name="activation-password" />
					</td>
				</tr>
				
				<tr>
					<td>
						New Password Again
					</td>
					<td>
						<input type="password" maxlength="100" name="activation-passwordv" />
					</td>
				</tr>
				
				<tr>
					<td>&nbsp;</td>
					<td>
						<input type="submit" name="activation-submit" value="Activate" />
					</td>
				</tr>
			</table>
		</form>
	</div>
</div>
<!-- End activate page -->
