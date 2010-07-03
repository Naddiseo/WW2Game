<html>
<head><title>Welcome to World War II Game <?= $this->username ?></title>
</head>
<body>
<h1>Welcome to World War 2 Game</h1>
<p>This is your activation email for WW2, in case you have forgotten 
the url is http://www.ww2game.net (<a href="http://www.ww2game.net">
www.ww2game.net</a>)
</p>
<p>If you still want to play, you'll first need to activate your account.
<br> To do this use the provided username and password below, and go to
http://www.ww2game.net/activate.php<br>
There you can choose your own password, and then start playing</p>
<p>If you have any trouble activating, please try the "resend password" on
the main page. This will resend your activation password. If this still does 
not work please do not hesitate to contact an admin on the forum, or just reply
 to this email. Please note that we check emails less frequently, and may not 
 reply for a few days. </p>
<p>
Here is your username and password:<br>
Username: <?= $this->username ?><br>
Password: <?= $this->password ?><br>
<br>
<br>
Alternately, you can activate your account by going to 
<a href="http://www.ww2game.net/activate.php?key=<?= $this->password ?>">
	http://www.ww2game.net/activate.php?key=<?= $this->password ?>
</a>
<br>
Good Luck, and have fun.<br>
The WW2 Game Team
</p>
</body>
</html>
