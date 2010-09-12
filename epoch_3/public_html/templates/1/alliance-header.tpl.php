
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
			<span class="no-hl">(
				<? if ($user->alliance) { ?>
				<a href="alliance-home.php">Home</a>
				|| <a href="alliance-home.php?leave-alliance=yes">Leave</a>
				<? } 
				else { ?>
					|| <a href="alliance-create.php">Create</a>
				<? }?>
				<? if ($user->alliance and $this->alliance and $this->alliance->isLeader($user)) { ?>
					|| <a href="alliance-edit.php">Edit</a>
				<? } ?>
				||	<a href="alliance-list.php">List</a>
			)</span>
