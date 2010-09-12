
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
<!-- begin bank page -->
<div id="bank-container">
	<center>
	Gold in hand: <?=numecho2($user->gold);?><br />
	Gold in bank: <?=numecho2($user->bank);?><br />
	Total: <?= numecho2($user->gold + $user->bank) ?><br />
	<br />
	<small>Deposit Fee <?= $user->bankper ?>%</small>
	<form  name="<?= getPassword(10) ?>" method="post">
		<input name="<? $_SESSION['depbox'] = getPassword(10); echo $_SESSION['depbox']; ?>" type="text" size="12" maxlength="17" value="<?= numecho2($user->gold) ?>" />
		<? if($user->bankimg == 1) { ?>
			<br />
			<img src="imageclick.php?<?= session_name() . '=' . session_id() ?>" title="random characters" alt="random characters"><br />
			<select name="turing">
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
		<?}?> <br />
		<input type="submit" value="Deposit" />
	</form>	
</div>
<!-- end bank page -->
