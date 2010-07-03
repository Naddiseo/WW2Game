<?
function getNewMessageCount($userID) {
	if ($userID != $_SESSION['isLogined']) {
		echo "You are not allowed to view other peoples messages!";
	} else {
		$str = "SELECT COUNT(*) FROM `Messages` WHERE userID='$userID' AND `read`=0";
		$q = mysql_query($str) or die(mysql_error());
		if ($q) {
			$st = mysql_fetch_array($q);
			return $st[0];
		} else {
			return 0;
		}
	}
}
function getMessagesCount($userID) {
	if ($userID != $_SESSION['isLogined']) {
		echo "You are not allowed to view other peoples messages!";
	} else {
		$str = "SELECT COUNT(*) FROM Messages WHERE userID='$userID' AND `read` != 2 ";
		$q = mysql_query($str) or die(mysql_error());
		//echo $userID;
		//print_r(mysql_fetch_array($q));
		if ($q) {
			$st = mysql_fetch_array($q);
			return $st[0];
		} else {
			return 0;
		}
	}
}
function getAllMessages($userID) {
	if ($userID != $_SESSION['isLogined']) {
		echo "You are not allowed to view other peoples messages!";
	} else {
		if (isset($_SESSION['admin'])) {
			$str = "SELECT * FROM `Messages` WHERE userID='$userID' ORDER BY date DESC";
		} else {
			$str = "SELECT * FROM `Messages` WHERE userID='$userID' and `read`!=2 ORDER BY date DESC";
		}
		//print $str;
		$q = mysql_query($str);
		if (!$q) {
			print ('Query failed: ' . mysql_error());
			return;
		}
		if (!mysql_num_rows($q)) {
			return;
		} else {
			$st = "";
			$i = 0;
			while ($row = mysql_fetch_object($q)) {
				$st[$i] = $row;
				$st[$i]->subject = stripslashes(htmlspecialchars(urldecode($st[$i]->subject)));
				$st[$i]->text = stripslashes(htmlspecialchars(urldecode($st[$i]->text)));
				$i++;
			}
			return $st;
		}
	}
}
function getMessage($messID) {
	$str = "select * from `Messages` where  ID='$messID' ORDER BY date ASC";
	//echo $str;
	$q = mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
	if (!@mysql_num_rows($q)) {
		return 0;
	} else {
		$st = "";
		$st = mysql_fetch_object($q);
		$st->subject = urldecode(stripslashes($st->subject));
		$st->text = urldecode(stripslashes($st->text));
		if (!isset($_SESSION['admin'])) {
			mysql_query("UPDATE `Messages` SET `read`='1' WHERE ID='$messID'") or die(mysql_error());
		}
		return $st;
	}
}
function sendMessage($id, $toid, $subject, $text) {
	$text = mysql_real_escape_string(htmlspecialchars($text));
	$subject = mysql_real_escape_string(htmlspecialchars($subject));
	$date = time();
	if (strpos(strtolower($alert), "mass")) {
		return "Noob";
	}
	if ($toid == "msgoff") {
		$str = "SELECT ID  FROM `UserDetails` WHERE active=1  AND commander='$id'";
		$s = mysql_query($str) or die(mysql_error());
		while ($row = mysql_fetch_object($s)) {
			// print_r($row);
			$str = "INSERT INTO Messages (fromID , userID ,subject ,text,date ) VALUES ('$id','{$row->ID}','$subject','$text','$date')";
			$q = mysql_query($str) or die(mysql_error());
		}
		$str = "INSERT INTO `outbox` (toID,userID,subject,text,date) VALUES ('0','$id','$subject','$text','$date')";
		mysql_query($str) or die(mysql_error());
	} elseif ($toid == "msgall") {
		$str = "SELECT u1.ID  FROM UserDetails u1,UserDetails u2 WHERE u1.active=1 AND u2.ID='$_SESSION[isLogined]' AND u1.alliance=u2.alliance AND u1.ID!=u2.ID";
		$s = mysql_query($str) or die(mysql_error());
		while ($row = mysql_fetch_object($s)) {
			// print_r($row);
			$str = "INSERT INTO Messages (fromID , userID ,subject ,text,date ) VALUES ('$id','{$row->ID}','$subject','$text','$date')";
			$q = mysql_query($str) or die(mysql_error());
		}
		$str = "INSERT INTO `outbox` (toID,userID,subject,text,date) VALUES ('0','$id','$subject','$text','$date')";
		mysql_query($str) or die(mysql_error());
	} else {
		$str = "INSERT INTO Messages (fromID , userID ,subject ,text,date ) VALUES ('$id','$toid','$subject','$text','$date')";
		$q = @mysql_query($str);
		$str = "INSERT INTO `outbox` (toID,userID,subject,text,date) VALUES ('$toid','$id','$subject','$text','$date')";
		@mysql_query($str) or die(mysql_error());
	}
	return $q;
}
function deleteMessage($mesID) {
	$mess = getMessage($mesID);
	If ($mess->userID == $_SESSION['isLogined']) {
		$str = "UPDATE `Messages` SET `read`=2 WHERE ID='$mesID'";
		//echo $str;
		$q = @mysql_query($str);
	}
}
function deleteMessagesOfUser($id) {
	$str = "UPDATE `Messages` SET `read`=2 WHERE userID='$id'";
	//echo $str;
	$q = @mysql_query($str);
}
//outbox functions
function getAllOutMessages($userID) {
	if ($userID != $_SESSION['isLogined']) {
		echo "You are not allowed to view other peoples messages!";
	} else {
		$str = "SELECT * FROM `outbox` WHERE userID='$userID' ORDER BY date DESC";
		//print $str;
		$q = @mysql_query($str);
		if (!$q) {
			print ('Query failed: ' . mysql_error());
			return;
		}
		if (!@mysql_num_rows($q)) {
			return;
		} else {
			$st = "";
			$i = 0;
			while ($row = mysql_fetch_object($q)) {
				$st[$i] = $row;
				$st[$i]->subject = stripslashes(htmlspecialchars(urldecode($st[$i]->subject)));
				$st[$i]->text = stripslashes(htmlspecialchars(urldecode($st[$i]->text)));
				$i++;
			}
			return $st;
		}
	}
}
function getOutMessage($messID) {
	$str = "select * from `outbox` where  ID='$messID' ORDER BY date ASC";
	//echo $str;
	$q = @mysql_query($str);
	if (!$q) {
		print ('Query failed: ' . mysql_error());
		return;
	}
	if (!@mysql_num_rows($q)) {
		return 0;
	} else {
		$st = "";
		$st = mysql_fetch_object($q);
		$st->subject = stripslashes(urldecode($st->subject));
		$st->text = nl2br(stripslashes(urldecode($st->text)));
		return $st;
	}
}
function deleteOutMessage($mesID) {
	$mess = getOutMessage($mesID);
	If ($mess->userID == $_SESSION['isLogined']) {
		$str = "DELETE FROM  `outbox` WHERE ID='$mesID'";
		//echo $str;
		$q = @mysql_query($str);
	}
}
function getOutMessagesCount($userID) {
	if ($userID != $_SESSION['isLogined']) {
		echo "You are not allowed to view other peoples messages!";
	} else {
		$str = "SELECT COUNT(*) FROM `outbox` where userID='$userID' ";
		$q = @mysql_query($str);
		if ($q) {
			$st = mysql_fetch_array($q);
			return $st[0];
		} else {
			return 0;
		}
	}
}
//=========
//bbcode
//==========
function bbcode($text) {
	$t = $text;
	//$t = nl2br(htmlspecialchars($t));
	$patterns = array('`\[b\](.+?)\[/b\]`is', '`\[i\](.+?)\[/i\]`is', '`\[u\](.+?)\[/u\]`is', '`\[strike\](.+?)\[/strike\]`is', '`\[color=#([0-9]{6})\](.+?)\[/color\]`is', '`\[email\](.+?)\[/email\]`is', '`\[img\](.+?)\[/img\]`is', '`\[url=([a-z0-9]+://)([\w\-]+\.([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*?)?)\](.*?)\[/url\]`si', '`\[url\]([a-z0-9]+?://){1}([\w\-]+\.([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*)?)\[/url\]`si', '`\[url\]((www|ftp)\.([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*?)?)\[/url\]`si', '`\[indent](.+?)\[/indent\]`is', '`\[size=([1-6]+)\](.+?)\[/size\]`is');
	$replaces = array('<b>\\1</b>', '<i>\\1</i>', '<u">\\1</u>', '<strike>\\1</strike>', '<span style="color:#\1;">\2</span>', '<a href="mailto:\1">\1</a>', '<img src="\1" alt="" style="border:0px;" />', '<a href="\1\2">\6</a>', '<a href="\1\2">\1\2</a>', '<a href="http://\1">\1</a>', '<pre>\\1</pre>', '<h\1>\2</h\1>');
	$mt = preg_replace('@<script[^>]*?>.*?</script>@si', '', $t);
	$t = preg_replace("#\<\!\-\-(.+?)\-\-\>#is", "", $mt);
	$t = preg_replace("#javascript\:#is", "java script:", $t);
	$t = str_replace("`", "&#96;", $t);
	$t = preg_replace("/javascript/i", "j&#097;v&#097;script", $t);
	$t = preg_replace("/alert/i", "&#097;lert", $t);
	$t = preg_replace("/about:/i", "&#097;bout:", $t);
	$t = preg_replace("/onmouseover/i", "&#111;nmouseover", $t);
	$t = preg_replace("/onclick/i", "&#111;nclick", $t);
	$t = preg_replace("/onload/i", "&#111;nload", $t);
	$t = preg_replace("/onsubmit/i", "&#111;nsubmit", $t);
	$t = preg_replace($patterns, $replaces, $t);
	$t = preg_replace("#\[(left|right|center)\](.+?)\[/\\1\]#is", "<div align=\"\\1\">\\2</div>", $t);
	$t = str_replace('[quote]', '<strong>Quote:</strong><div class=\'quote\'"><i>', $t);
	$t = str_replace('[/quote]', '</i></div>', $t);
	return $t;
}
?>
