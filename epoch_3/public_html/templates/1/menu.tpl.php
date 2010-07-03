
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
					<!-- Begin left menu -->
					<div id="left-menu">
						<ul>
							<li><a href="base.php" title="Military Home">Base</a></li>
							<li><a href="treasury.php" title="Your Bank">Treasury</a></li>
							<li><a href="upgrades.php" title="Technology Upgrades">Upgrades</a></li>
							<li><a href="train.php" title="Train You Soldiers">Training</a></li>
							<li><a href="battlefield.php?page=<?= floor($user->rank / $conf['users-per-page']) + 1 ?>" title="Show Potential Targets">Attack</a></li>
							<li><a href="armoury.php" title="Buy Amoury">Armoury</a></li>
							<li><a href="attacklog.php" title="See Who Has Attacked You">Attack Log</a></li>
							<li><a href="intel.php" title="See Who Has Spied On You">Intel</a></li>
							<li><a href="prefs.php" title="Change Your Preferences">Preferences</a></li>
							<li><a href="logout.php" title="Good Bye">Logout</a></li>
						</ul>
						<br />
						<ul>
							<li>Next turn: <span id="menu-next-turn-min"></span>:<span id="menu-next-turn-sec"></span></li>
							<li>Rank: <?= numecho($user->rank)         ?></li>
							<li>Turns: <?= numecho($user->attackturns) ?> / <?= numecho($conf['attackturn-cap']) ?></li>
							<? if ($user->getSupport('combined-gold')) { ?>
								<? $user->primary = 2; ?>
								<li class="primary">Total: <?= numecho($user->getPrimary()) ?></li>						
							<? }
								else { ?>
								<li><a href="?switch-primary=<?= $this->templateName ?>&amp;<?= http_build_query($_GET) ?>" title="Switch Your Primary Gold Source">Switch Primary</a></li>
							<? } ?>
							
							<li <?= ($user->primary == 0 ? 'class="primary"' : '' ) ?>>Gold: <?= numecho($user->gold)?></li>
							<li <?= ($user->primary == 1 ? 'class="primary"' : '' ) ?>>Bank: <?= numecho($user->bank)?></li>
							<li><a <?= ($user->unreadMsg > 0 ? 'class="unread"' : '' ) ?> href="messages.php" title="Read Your Messages">Messages <?= $user->unreadMsg ?> (<?= $user->msgCount ?>)</a></li>
						</ul>
					</div>
					<!-- End left menu -->
