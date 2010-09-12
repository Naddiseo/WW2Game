<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
 "http://www.w3.org/TR/html4/loose.dtd">

<html>
	<head>

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
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?= $this->pageTitle  ?></title>
		<link href="<?= $this->css('common') ?>" rel="stylesheet" type="text/css" >
		<? if ($this->css) { ?>
			<link href="<?= $this->css($this->templateName) ?>" rel="stylesheet" type="text/css" >
		<? } ?>
		<script language="JavaScript" type="text/javascript" src="<?= $this->js('common') ?>?v=<?= VERSION ?>"></script>
		<? if ($this->js) { ?>
			<script language="JavaScript" type="text/javascript" src="<?= $this->js($this->templateName) ?>?v=<?= VERSION ?>"></script>
		<? } ?>
		<script language="JavaScript" type="text/javascript">
			var serverTime = <?= time(); ?>;
			var ageEnd = <?= ($this->ageEnd ? $this->ageEnd : 0)  ?>;
			//var announcement = "<?= $this->announcement ?>";
			var img1 = new Image();
			img1.src = "<?= $this->image('WW2GameLogo1.jpg') ?>";
			var img2 = new Image();
			img2.src = "<?= $this->image('WW2GameLogo2.jpg') ?>";
			
			var minPerTurn = <?= $conf['minutes_per_turn'] ?>;
			
			
			var nextTurn = <?= ($conf["minutes_per_turn"] * 60) - (time() - $conf['last-turn']) ?>;
			var $min = Math.round(nextTurn / 60);
			var $sec =  nextTurn - ($min * 60);

			if ($sec < 0){
				$min = $min - 1;
				$sec = 60 + $sec;
			}
			else if ($sec < 10){
				$sec = '0' + $sec;
			}
			if ($min < 10){
				$min = '0' + $min;
			}

			
			
			<? if ($user) { ?>
			
			var user = {
				id : <?= $user->id ?>,
				primary: <?= ($user->getPrimary()) ?>,
				secondary: <?= $user->getSecondary() ?>,
				uu: <?= $user->uu ?>,
				alliance: <?= intval($user->alliance) ?>,
				commander: <?= intval($user->commander) ?>
			};
			
			<? } ?>

			function loadComplete() {
				<? if ($user) { ?>
				startTimer();
				<? } ?>
				
				<?= $this->onload ?>
			
				return true;
			}


		</script>

		<meta name="keywords" content="ww2 game, wwii online game, world war 2 games, ww2 games ww2 online games, online ww2 games, ww2 games online, world war 2 online games, browser based game, MMORPG, Germans, Germany, Japan, Japanese, Britain, UK, USA, United States, USSR, Russia, turn based game, free game, free online game" />
		<meta name="description" content="World War 2 is a free online browser text based game. You choose between 5 nations - Germany, Japan, USA, UK, and USSR - and battle for first place." />
	</head>
	<body onload="javascript: return loadComplete();">

		<div id="main-container">
			<!-- Start of centered content -->

			<!-- Start of top navbar -->
			<div id="main-top-nav">
				<div id="logo">
					<a href="http://www.ww2game.net">
<!-- 						<img title="World War II Game" src="/images/logo.jpg" /> -->
						<img id="page-logo" title="World War 2 Game" src="<?= $this->image('WW2GameLogo1.jpg') ?>"
						 onmouseover="this.src=img2.src;"
						 onmouseout="this.src=img1.src;"
						 />
					</a>
				</div>
				<? if (!$user or ($user and !$user->supporter and !$_SESSION['admin'])) { ?>
					<div style="height:60px;width:468px">
						
					</div>
				 <? } ?>

				<div id="nav-icons">
					<a href="/irc/" target="_blank">Chat</a><br />
					<a href="/forum" target="_blank">Forum</a><br />
					<a href="online<?= ($user ? '' : '-offline') ?>.php">Online (<?= $conf['online-now'] ?>)</a>
				</div>
			</div>
			<!-- End of top navbar -->

			<!-- Start of Content panel -->
			<div id="main-content-panel">
				<div id="main-announce-bar" class="layer2">
					<?= $this->announcement ?>
					<? if ($user and $user->admin) { ?>
						|| <a href="?admin-key=<?= ($_SESSION['admin'] ? 'off&amp;' : 'on&amp;') .  http_build_query($_GET) ?>" >Admin <?= ($_SESSION['admin'] ? 'off' : 'on') ?></a>
						<? if ($_SESSION['admin']) { ?>
							|| <a href="admin.php">Admin Panel</a>
							|| <a href="admin-contact.php">Contact Manager</a>
						<? } ?>
					<? } ?>
				</div>

					<div id="left-panel">

						<!-- Start of Left panel -->
						<div id="main-left-container" class="layer3">
							<!-- only show left if not offline -->
							<? if (!$this->offline) { ?>
							<?
								if (Privacy::isIn() and $user) {
									$this->load('menu');
								}
								else {
									$this->load('login-menu');
								}
							?>
							
							<? } ?>
							<ul id="common-menu-items">
								<li><a href="online<?= ($user ? '' : '-offline') ?>.php">Online Now: <?= $conf['online-now'] ?></a></li>
								<li>Online Today: <span><?= $conf['online-today'] ?></span></li>
								<li>Attacks Today: <span><?= $conf['attacks-today'] ?></span></li>
								<li>Server Time: <span><?= date ('H:i', time() + (60*60*13)) ?></span></li>
							</ul>
							<ul id="menu-links">
								<li><a href="/forum" target="_blank">Forum</a></li>
								<li><a href="/irc/" target="_blank">IRC Chat</a></li>
								<li><a href="statistics.php">Current Statistics</a></li>
								<li><a href="hof.php">Hall of Fame</a></li>
								<li><a href="rules.php">Rules</a></li>
								<li><a href="http://forum.ww2game.net/index.php?board=4.0" target="_blank">Help</a></li>
							</ul>

							
							<? if (!$user or ($user and !$user->supporter)) { ?>
								<ul id="ad-list">
									<li id="google-ad1">
										
									</li>
								</ul>
							<? } ?>

						</div>
						<!-- End of Left panel -->
						<div class="clear flat"></div>
					</div>
					<!-- Start of Main page content panel -->
					<? if ($this->err) { ?>
						<div id="main-error-bar" class="layer2"><?= $this->err ?></div>
					<? } ?>
					<? if ($this->msg) { ?>
						<div id="main-msg-bar" class="layer2"><?= $this->msg ?></div>
					<? } ?>
					<div id="page-content">

						<? $this->load($this->templateName) ?>

					</div>
					<!-- End of main page content panel -->


				<div class="clear flat"></div>
			</div>
			<!-- End of Content panel -->

			<!-- Start of Footer panel -->
			<div id="main-content-footer">
				<div class="right">
					<a href="spam.php">Report Spam</a>&nbsp;|
					<a href="privacy.php">Privacy Policy</a>&nbsp;|
					<a href="advertising.php">Advertising</a>&nbsp;|
					<a href="contact.php">Contact Us</a>&nbsp;|
					<a href="tos.php">Terms of Service</a>
				</div>
				<div class="clear flat"></div>
				<div id="copyright">
					<a href="http://www.http://github.com/Naddiseo/WW2Game" target="_blank" >Epoch 3</a>under GNU/GPL3, coding by Naddiseo<br />
					Rolz14 (IRC Service)<br />
					Epoch 1 design by SilentWarrior and Xamir<br />
					<i>Copyright  2005-2010<br/>
					World War II Game, All rights reserved.</i><br />
					<small>This game is not a clone, all code is original</small>
				</div>
			</div>
			<!-- End of Footer panel -->

			<!-- End of centered content -->
		</div>

		
	</body>
</html>
<? require_once('logger.php') ?>
