
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
<!-- Begin Supporter Stuff -->
<div id="support-container">
	<div class="panel">
		<? if (!$this->ppVerify->id) { ?>
		
			<div class="panel-title">
				Become a Supporter
			</div>

			<form class="large" method="post">

				<div class="line">
					<label>User ID</label>
					<input type="text" name="support-userId" maxlength="5" value="<?= $this->uid ?>" />
					<small>The user ID of the player for who you wish to buy supporter status</small>
				</div>
				<div class="line">
					<fieldset>
						<legend>Supporter Package</legend>
						<label>
							<input type="radio" name="support-type" value="1" /> $1 USD Basic Package
						</label>
						<label>
							<input type="radio" name="support-type" value="5" /> $5 USD Enhanced Package
						</label>
						<span>
							<a href="http://www.ww2game.net/forum/index.php?topic=27.0" target="_blank" title="Supporter package information">Information about the packages</a>
						</span>
					</fieldset>
				</div>
				<div class="line">
					<input style="background:white;" type="image" class="submit" name="paypal-submit" src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif" />
				</div>
			</form>
		<? }
			else { ?>
			<div class="panel-title">
				Verify Paypal Order
			</div>
			<form method="post" class="large">
				<input type="hidden" name="token" value="<?= $this->ppVerify->token ?>" />
				<div class="line">
					<label>Your User ID</label>
					<span><?= $this->ppVerify->userId ?></span>
				</div>
				<div class="line">
					<label>For User ID</label>
					<span><?= $this->ppVerify->forId ?> (<?= $this->forName ?>)</span>
				</div>
				<div class="line">
					<label>Amount</label>
					<span>$<?= numecho(round($this->ppVerify->amount, 2)) ?> USD</span>
				</div>
				<div class="line">
					<input type="submit" class="submit" name="paypal-verify-txn" value="This is correct, continue" />
					<a href="support.php">Cancel</a>
				</div>
				<div class="clear flat"></div>
			</form>
		<? } ?>
	</div>
</div>
<!-- End supporter stuff -->
