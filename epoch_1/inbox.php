<? if (!$_SESSION['isLogined']) {
	die("No Access");
} ?>
<h3>Inbox</h3>
<font color="red">&nbsp;</font>

<p>
      <p> You have<a href="messages.php"><font size="4" color="RED">
        <?=getNewMessageCount($_SESSION['isLogined']) ?></font></a> unread
        messages of <font size="3" color="RED"><?=getMessagesCount($_SESSION['isLogined']) ?></font> Messages
      <p>
	  
	  <?
if ($cgi['delallmsg']) {
	$str = "UPDATE `Messages` SET `read`=2 WHERE userID='$_SESSION[isLogined]'";
	mysql_query($str) or die(mysql_error());
}
if ($cgi['type'] == 'delete') {
	deleteMessage($cgi['id']);
}
if ($cgi['delsel'] AND $cgi['delarr']) {
	foreach ($cgi['delarr'] as $key => $value) {
		deleteMessage($value);
	}
}
if ($cgi['type'] == 'view') {
	$mes = getMessage($cgi['id']);
	if ($mes->userID != $_SESSION['isLogined']) {
		echo "<b><font color=red>You are not allowed to view other peoples messages!</font></b><br /><br />";
	} else {
		echo "<b>Subject:  " . $mes->subject . "</b><br><br>";
		echo "<b>Message text:</b><br><br>";
		echo bbcode(str_replace("\n", "<br />", stripslashes($mes->text)));
		echo "<br><br><br><hr>";
		echo "<center>";
?>
			<table><tr><td>
			<form action="writemail.php" method="POST">
			<input type="hidden" name="to" value="<?=$mes->fromID
?>" />
			<input type="hidden" name="repmsg" value="<?=$mes->text
?>" />
			<input type="submit" name="rep" value="Reply to: <?
		$sentUser = getUserDetails($mes->fromID, "userName");
		echo $sentUser->userName;
?>" />
			</form></td><td><form action="writemail.php">
			<input type="hidden" name="id" value="<?=$mes->ID
?>" />
			<input type="hidden" name="type" value="delete" />
			<input type="submit" name="del" value="Delete" />
			</form></td></tr></table>
			</center><hr />
			
			<?
	}
} else {
?>
	  
	  	<input type="hidden" name="delsel" value="delsel" />
      <table width="100%" bgcolor="#000000" border="0" cellspacing="6" cellpadding="6">
        <tr> 
          <th>From</th>
          <th>Subject</th>
          <th>Sent</th>
          <th>Reply</th>
          <th>Delete</th>
         
          <th><form name='selmess' action="messages.php" method="POST"><input type="submit" name="delallmsg" value="Delete all"></form><form name='selmess' action="messages.php" method="POST"><input type="submit" name="delsel" value="Delete Selected"></th>
        	
        </tr>
        <?
	$messagesC = getMessagesCount($user->ID);
	if ($messagesC) {
		$pCount = $messagesC / $conf["users_per_page"];
		$pCountF = floor($pCount);
		$pCountF+= (($pCount > $pCountF) ? 1 : 0);
		if (!$cgi['page']) {
			$cgi['page'] = 1;
		}
		$messages = getAllMessages($_SESSION['isLogined'], $cgi['page']);
		for ($i = 0;$i < count($messages);$i++) {
?>
        <tr align="center"> 
          <td> <a href="stats.php?id=<?=$messages[$i]->fromID ?>"> 
            <? $sentUser = getUserDetails($messages[$i]->fromID, "userName");
			echo $sentUser->userName; ?>
            </a> </td>
          <td> <a href="messages.php?id=<?=$messages[$i]->ID ?>&amp;type=view" title="view message">
            <?=$messages[$i]->subject ?>
            </a>
	    <?
			if ($messages[$i]->read == 0) {
				echo "<font color=red>!</font>";
			}
			if ($messages[$i]->fromadmin == 1) {
				echo "<font color=blue>Admin Message</font>";
			}
?></td>
          <td><?=vDate($messages[$i]->date) ?></td>
          <td><a href="writemail.php?to=<?=$messages[$i]->fromID ?>">reply</a></td>
          <td><a href="messages.php?id=<?=$messages[$i]->ID ?>&amp;type=delete">delete</a></td>
          <td><input type="checkbox" name="delarr[]" value="<?=$messages[$i]->ID ?>"> </td>
        	
        </tr>
        <?
		}
	} else {
?>
        <tr> 
          <td colspan="3" align="center">No Messages</td>
        </tr>
        <?
	} ?>
      </table></form>
	  <?
} ?>

