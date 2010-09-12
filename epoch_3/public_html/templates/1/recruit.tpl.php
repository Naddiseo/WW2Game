
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
<!-- Begin Recruitment Page -->
<div id="recruitment-container">
	<div class="panel">
		<div class="panel-title">
			Recruit for <?= $this->target->getNameLink() ?>
		</div>
		<form class="large" method="post">
			<div class="line img">
				<img src="imageclick.php?<?= session_name() . '=' . session_id() ?>" title="random characters" alt="random characters"><br />
			</div>
			<? for ($i = 0; $i < 3; $i++) { ?>
				<div class="line">
					<? for ($j = 0; $j < 5; $j++) { ?>
						<input class="number" type="submit" name="number" value="<?= ($i * 5 + $j+ 1) ?>" />
					<? } ?>
				</div>
			<? } ?>
		</form>
	</div>
</div>
<!-- End Recruitment Page -->
