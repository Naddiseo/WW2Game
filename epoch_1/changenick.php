<?
include "scripts/vsys.php";
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD><TITLE><? echo $conf["sitename"]; ?> ::<?=$user->userName; ?>
' Change Username</TITLE>
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

<META content="MSHTML 5.50.4522.1800" name=GENERATOR>

</HEAD>
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
?>
	<?
//if ($user->admin != 1) {
//echo "<br><br><center><font color=red>You are not a Game Administrator!</font></center><br><br><br><br>";
//include "bottom.php";
//exit;
//}
if (($user->changenick == 1) && ($user->admin != 1)) {
	echo "<br><br><center><font color=red>You have already changed your nick once this age!</font></center><br><br><br><br>";
	include "bottom.php";
	exit;
}
if ($cgi['submit']) {
	echo "<center><font color=red>";
	if ($cgi['username'] != $cgi['usernametwo']) {
		echo "Usernames you have entered do not match. Try to re-enter them.";
	} elseif (!valchar($cgi['username'])) {
		echo "You can only use Alphanumeric Characters and no spaces!";
		echo "</font></center>";
	} elseif (getUserDetailsByName($cgi['username'])) {
		echo "That username has already been taken";
		echo "</font></center>";
	} else {
		updateUser($user->ID, " userName='{$cgi['username']}' ");
		mail($user->e_mail, "Your New Username", "Your New Username is " . $cgi['username'] . "");
		mysql_query("update UserDetails set changenick=1 where ID='" . $user->ID . "'");
		echo "Please Relogin, Thanks.";
		$_SESSION['isLogined'] = 0;
		echo "</font>";
		include "bottom.php";
		exit;
		echo "</center>";
	}
}
?>


              <form method="post" action="changenick.php">
	            <table width="50%" class="table_lines" cellpadding="6" border="0" cellspacing="0">
	              <tr>
	                <th colspan="2">Change Username</th>
	              </tr>
	              <tr>
	                <td colspan="2">Be Careful with this Function! Please. Limit of 1 per age </td>
	              </tr>
	              <tr>
	                <td>New Username:</td>
	                <td><input type="text" name="username"></td>
	              </tr>
	              <tr>
	                <td>Confirm New Username:</td>
	                <td><input type="text" name="usernametwo"></td>
	              </tr>
	              <tr>
	                <td colspan="2"><input name="submit" type="submit" value="OK"></td>
	              </tr>
	            </table>
	            <input type="hidden" name="do" value="username">
        </form>

        <P>
          <?
include ("bottom.php");
?>
	 </TD></TR></TBODY></TABLE>
</BODY></HTML>
