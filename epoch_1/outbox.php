<? if (!$_SESSION['isLogined']) {
	die("no access");
} ?>
<h3>Outbox</h3>
<font color="red">&nbsp;</font>
<p>
      <p> You have<a href="messages.php"><font size="4" color="RED">
        <?=getNewMessageCount($user->ID) ?></font></a> unread
        messages of <font size="3" color="RED"><?=getMessagesCount($user->ID) ?></font> Messages
      <p>
	  
	  <?
if ($cgi['type'] == 'delete') {
	deleteOutMessage($cgi['id']);
}
if ($cgi['type'] == 'view') {
	$mes = getOutMessage($cgi['id']);
	if ($mes->userID != $_SESSION['isLogined']) {
		echo "<b><font color=red>You are not allowed to view other peoples messages!</font></b><br /><br />";
	} else {
		echo "<b>Subject:  " . $mes->subject . "</b><br><br>";
		echo "<b>Message text:</b><br><br>";
		echo $mes->text;
	}
} else {
?>
      <table width="100%" bgcolor="#000000" border="0" cellspacing="6" cellpadding="6">
        <tr> 
          <th>To</th>
          <th>Subject</th>
          <th>Sent</th>
          <th>Delete</th>
        </tr>
        <?
	$messagesC = getOutMessagesCount($user->ID);
	if ($messagesC) {
		$pCount = $messagesC / $conf["users_per_page"];
		$pCountF = floor($pCount);
		$pCountF+= (($pCount > $pCountF) ? 1 : 0);
		if (!$cgi['page']) {
			$cgi['page'] = 1;
		}
		$messages = getAllOutMessages($user->ID, $cgi['page']);
		for ($i = 0;$i < count($messages);$i++) {
?>
        <tr align="center"> 
          <td><? if ($messages[$i]->toID > 0) { ?>
           <a href="stats.php?id=<?=$messages[$i]->toID ?>">
            <? $sentUser = getUserDetails($messages[$i]->toID, "userName");
				echo $sentUser->userName; ?>
            </a>             
            <?
			} else {
				echo "All Officers";
			} ?></td>
          <td> <a href="messages.php?box=outbox&amp;id=<?=$messages[$i]->ID ?>&amp;type=view" title="view message">
            <?=$messages[$i]->subject ?>
            </a></td>
          <td><?=vDate($messages[$i]->date) ?></td>
          <td><a href="messages.php?box=outbox&amp;id=<?=$messages[$i]->ID ?>&amp;type=delete">delete</a></td>
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
      </table>
	  <?
} ?>

