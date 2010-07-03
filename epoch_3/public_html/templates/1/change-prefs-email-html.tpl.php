<html>
<head><title>Login credential change notification for <?= $this->username ?></title>
</head>
<body>
<h1>Your login information has changed</h1>
<p>This is a notification email for WW2, in case you have forgotten 
the url is http://www.ww2game.net (<a href="http://www.ww2game.net">
www.ww2game.net</a>)
</p>
<p>If you still want to play, you'll first need to verify your account.
<br> To do this go to
http://www.ww2game.net/loginverify.php<br>
There you can choose your verify your username, email, and password</p>
<p>If you have any trouble verifying, please try the "resend password" on
the main page. This will reset your password again. If this still does not work
please do not hesitate to contact an admin on the forum, or just reply to this 
email. Please note that we check emails less frequently, and may not reply for 
a few days.</p>
<p>
Here is your information:<br>
<? if ($this->username) { ?>
Username: <?= $this->username ?><br>
<? } ?>
<? if ($this->email) { ?>
Email: <?= $this->email ?><br>
<? } ?>
<? if ($this->password) { ?>
Password: <?= $this->password ?><br>
<? } ?>
<br><br>
Alternately, go to 
<a href="http://www.ww2game.net/loginverify.php?id=<?= $this->user->id ?>&key=<?= $this->key ?>">
	http://www.ww2game.net/loginverify.php?id=<?= $this->user->id ?>&key=<?= $this->key ?>
</a>
<br><br>
Good Luck, and have fun.<br>
The WW2 Game Team
</p>
</body>
</html>
