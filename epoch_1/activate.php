<? include "gzheader.php";
include "scripts/vsys.php";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD><TITLE><? echo $conf["sitename"]; ?> ::</TITLE>
<META http-equiv=Content-Type content="text/html; charset=iso-8859-1">
<SCRIPT language=javascript src="js/js"></SCRIPT>
<LINK href="css/common.css" type=text/css rel=stylesheet>
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
if ($_SESSION['isLogined']) {
	header("location: base.php");
}
if ($cgi['submit']) {
	//change e-mail
	echo "<center><font color=red>";
	if ($cgi['passone'] != $cgi['passtwo']) {
		echo "Passwords you have entered do not match. Try to re-enter them.";
	} else {
		$pas = md5($cgi['passone']);
		updateUser($_SESSION["activationID"], " password=\"$pas\", active='1' ");
		setLastSeen($_SESSION['activationID'], time());
		mail($cgi['email'], "Your new  password", "Your new  password is: $pas ");
		echo "<font color=white size=5>Activate.</font><br><br> Your password  was changed and now you can login with this new password.";
		$isActivated = 1;
	}
	echo "</font></center>";
}
if (!$isActivated) {
?>
        <form method="post" action="activate.php">
          <table width="50%" class="table_lines" cellpadding="6" border="0" cellspacing="0">
            <tr> 
              <th colspan="2">Activate your new account</th>
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
              <td colspan="2"><input name="submit" type="submit" value="OK"></td>
            </tr>
          </table>
          <input type="hidden" name="do2" value="pass">
        </form>
        <P> 
          <?
} else {
	echo "<br><br><br>";
}
include ("bottom.php");
?>	
	 </TD></TR></TBODY></TABLE>
</BODY></HTML>

<? include "gzfooter.php"; ?>
