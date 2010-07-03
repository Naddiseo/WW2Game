Your login information has changed

This is a notification email for WW2, in case you have forgotten 
the url is http://www.ww2game.net 



If you still want to play, you'll first need to verify your account.
To do this go to
http://www.ww2game.net/loginverify.php

There you can choose your verify your username, email, and password

If you have any trouble verifying, please try the "resend password" on
the main page. This will reset your password again. If this still does not work
please do not hesitate to contact an admin on the forum, or just reply to this 
email. Please note that we check emails less frequently, and may not reply for 
a few days.



Here is your information:

<? if ($this->username) { ?>
Username: <?= $this->username ?>

<? } ?>
<? if ($this->email) { ?>
Email: <?= $this->email ?>

<? } ?>
<? if ($this->password) { ?>
Password: <?= $this->password ?>

<? } ?>

Alternately, go to 
http://www.ww2game.net/loginverify.php?id=<?= $this->user->id ?>&key=<?= $this->key ?>



Good Luck, and have fun.

The WW2 Game Team
