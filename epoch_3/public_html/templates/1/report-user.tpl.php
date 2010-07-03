
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
<!-- Begin report-user page -->
<div id="reportuser-container">

	<div class="panel">
		<div class="panel-title">
			Report User
		</div>
		<form method="post" class="large" autocomplete="off">
			<input type="hidden" name="uid" value="<?= $this->uid ?>" />
			<div class="line">
				<label for="reportuser-text">Description:</label>
				<textarea name="reportuser-text" cols="80" rows="15"></textarea>
			</div>
			<div class="line">
				<input type="submit" name="reportuser-submit" class="submit" value="Report!" />
			</div>
		</form>
	</div>
</div>
<!-- End report-user page -->
