<? include "gzheader.php";
include "scripts/vsys.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>

		<title><? echo $conf["sitename"]; ?> :: Massively Multiplayer Online Role Playing Game</title>
		<LINK href="css/common.css" rel="stylesheet" type="text/css">
		
		<SCRIPT language=javascript src="js/js"></SCRIPT>
		

		<script language="javascript" type="text/javascript">
		<!--
		function checkCR(evt) {
		var evt = (evt) ? evt : ((event) ? event : null);
		var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
		if ((evt.keyCode == 13) && (node.type=="text")) {return false;}
		}
		document.onkeypress = checkCR;
		//-->
		</script>
	</head>
<body bgcolor="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" text="#FFFFFF">
<?
include "top.php";
?>
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr> 
    <TD class=menu_cell_repeater style="PADDING-LEFT: 15px" vAlign=top width=140>
<?
include ("left.php");
?>
    </td>
    <td style="padding-left: 15px; padding-top: 12px; padding-right: 15px;" valign="top" align="left"> 
      <br>
<h3>Forgot Password</h3>
<p>
Enter your e-mail address or username to retrieve your login details.
<p>
<font color="RED"><?
if ($cgi['email']) {
	$cgi['email'] = addslashes($cgi['email']);
	$q = mysql_query("SELECT userName,admin FROM UserDetails WHERE email LIKE '%$cgi[email]%' LIMIT 0,1") or die(mysql_error());
	$a = mysql_fetch_array($q, MYSQL_ASSOC);
	if ($a[admin] == 1) {
		$strErr.= "You are not allowed to change an admin's password<br />";
	}
	if ($a['userName'] and $cgi[admin] == 0) {
		$newpass = genRandomPas();
		$str = "<html><body>Your login: {$a[userName]} \n password: $newpass </body></html>";
		mysql_query("UPDATE UserDetails SET password='" . md5($newpass) . "' WHERE userName='$a[userName]'") or die(mysql_error());
		//echo $str;
		//change this:
		$subject = rand(0, 1) ? $a['userName'] : (rand(0, 1) ? "$a[userName]'s password" : "WW2: $a[userName]");
		$email = new clsMAIL($cgi['email'], $subject, $str, "Your login: {$a[userName]} \n password: $newpass");
		$email->addheader("To", "\"$a[userName]\" <$cgi[email]>");
		if ($email->send()) {
			echo ("Message sent to $a[userName], check your email for the password :)");
		} else {
			echo "There was an error sending the email message";
		}
	} else {
		$strErr.= "There is no user with such e-mail address";
	}
}
echo $strErr;
?></font><p>
<form method="post" action="forgotpass.php">
	<table cellpadding="6" cellspacing="1" border="0">
		<tr>
			<th colspan="2">E-Mail Address</th>
		</tr>
		<tr>
			<td>E-Mail Address:</td>
			<td><input type="text" name="email"></td>
		</tr>
		<tr>
			<td align="center" colspan="2"><input type="submit" value="Send me my login info"></td>
		</tr>		
	</table>
</form>
<?
include ("bottom.php");
?>		</td>
	</tr>
</table>
</body>
</html>

<? include "gzfooter.php"; ?>