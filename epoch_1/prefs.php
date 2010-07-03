<? include "gzheader.php";
include "scripts/vsys.php";
if ($cgi['ircnick']) {
	$nick = str_replace(" ", "_", $cgi['ircnick']);
	$nick = str_replace("'", "", $nick);
	$nick = str_replace("/", "", $nick);
	$nick = str_replace("\\", "", $nick);
	$nick = substr($nick, 0, 25);
	$nick = mysql_escape_string($nick);
	updateUser($user->ID, "ircnick=\"$cgi[ircnick]\"");
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD><TITLE><? echo $conf["sitename"]; ?> :: Preferences</TITLE>
<META http-equiv=Content-Type content="text/html; charset=iso-8859-1">
<LINK href="css/common.css" type=text/css rel=stylesheet>

<SCRIPT language=javascript type=text/javascript>
		<!--
		function checkCR(evt) {
		var evt = (evt) ? evt : ((event) ? event : null);
		var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
		if ((evt.keyCode == 13) && (node.type=="text")) {return false;}
		}
		document.onkeypress = checkCR;
		//-->
		</SCRIPT>

<META content="MSHTML 5.50.4522.1800" name=GENERATOR></HEAD>
<BODY text=#ffffff bgColor=#000000 leftMargin=0 topMargin=0 marginheight="0" 
marginwidth="0">
<?
include "top.php";
?>

<TABLE cellSpacing=0 cellPadding=5 width="100%" border=0>
  <TBODY>
  <TR>
    <TD class=menu_cell_repeater style="PADDING-LEFT: 15px" vAlign=top width=140>
<?
include ("left.php");
?>
</TD>
      <TD style="PADDING-RIGHT: 15px; PADDING-LEFT: 15px; PADDING-TOP: 12px" 
    vAlign=top align=left> <BR>
        <?
include "islogined.php";
$em = mysql_real_escape_string($cgi['email']);
$q = mysql_query("SELECT count(*) FROM UserDetails WHERE email=\"$em\"") or die(mysql_error());
$a = mysql_fetch_array($q);
if ($cgi['submit']) {
	//change e-mail
	echo "<center><font color=red>";
	if ($a[0] > 0) {
		echo "That email address is already in use";
	} elseif ($cgi['email'] != $cgi['emailtwo']) {
		echo "Emails you have entered do not match. Try to re-enter them.";
	} else {
		$pas = genRandomPas();
		updateUser($user->ID, " password='" . md5($pas) . "', email=\"{$em}\", active='0' ");
		$title = rand(0, 1) ? "World War II :: Activation Email" : "Activation Password for WW2";
		$html = "<html><body>Your new activation password is: $pas</body></html>";
		$plain = "Your new activation password is: $pas";
		$email = new clsMAIL($cgi['email'], $title, $html, $plain);
		//$email->addheader("To","\"$a[userName]\" <$cgi[email]>");
		if ($email->send()) {
			echo "Your e-mail was changed and new activation password was sent to your new e-mail. You will not be able to login again untill you enter new activation password.";
		} else {
			echo "There was an error sending the email message";
		}
		echo "Your e-mail was changed and new activation password was sent to your new e-mail. You will not be able to login again untill you enter new activation password.";
		$_SESSION['isLogined'] = 0;
	}
	echo "</font></center>";
} elseif ($cgi['submit2']) {
	//change pass
	echo "<center><font color=red>";
	if ($cgi['passone'] != $cgi['passtwo']) {
		echo "Passwords you have entered do not match. Try to re-enter them.";
	} else {
		updateUser($user->ID, " password='" . md5($cgi['passone']) . "' ");
		echo "Your password was changed successfully.";
	}
	echo "</font></center>";
}
?>
        <form method="post" action="prefs.php">
          <table width="50%" class="table_lines" cellpadding="6" border="0" cellspacing="0">
            <tr> 
              <th colspan="2">Change E-mail</th>
            </tr>
            <tr> 
              <td colspan="2">MAKE SURE YOU SPECIFY A VALID E-MAIL ADDRESS. If 
                this e-mail does not go through, you will be UNABLE to access 
                your account EVER AGAIN! PLEASE BE CAREFUL! </td>
            </tr>
            <tr> 
              <td>E-Mail:</td>
              <td><input type="text" name="email"></td>
            </tr>
            <tr> 
              <td>Confirm E-mail:</td>
              <td><input type="text" name="emailtwo"></td>
            </tr>
            <tr> 
              <td colspan="2"><input name="submit" type="submit" value="OK"></td>
            </tr>
          </table>
          <input type="hidden" name="do" value="email">
        </form>
        <form method="post" action="prefs.php">
          <table width="50%" class="table_lines" cellpadding="6" border="0" cellspacing="0">
            <tr> 
              <th colspan="2">Change Password</th>
            </tr>
            <tr> 
              <td>New Password:</td>
              <td><input type="password" name="passone"></td>
            </tr>
            <tr> 
              <td>Confirm New Password:</td>
              <td><input type="password" name="passtwo"></td>
            </tr>
            <tr> 
              <td colspan="2"><input name="submit2" type="submit" value="OK"></td>
            </tr>
          </table>
          <input type="hidden" name="do2" value="pass">
        </form>       
        <br>
        <hr>
        <form method="post">
        IRC nickname<br />
        <input maxlength="25" type="text" name="ircnick" value="<?=$user->ircnick
?>" /><input type="submit" value="Set" />
        </form>
        <P> 
          <?
include ("bottom.php");
?>	
	 </TD></TR></TBODY></TABLE>
</BODY></HTML>
<? include "gzfooter.php"; ?>
