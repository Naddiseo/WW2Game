<?
include "gzheader.php";
include "scripts/vsys.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" 
>
<HTML>
  <HEAD>
    <TITLE><? echo $conf["sitename"]; ?> :: Send Message </TITLE>
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
	vAlign=top align=left>
            <BR>
            <?
include "islogined.php";
if ($cgi['send'] AND $cgi['message']) {
	if (!$cgi['subject']) {
		$cgi['subject'] = "(No Subject)";
	}
	echo "<center><font color='red'>";
	if ($cgi['to'] == 'msgall') {
		if (!$user->alliance) {
			echo "You are not part of an alliance";
		} else {
			$q = mysql_query("SELECT leaderid1,leaderid2,leaderid3 FROM alliances where id={$user->alliance}") or die(mysql_error());
			$a = mysql_fetch_object($q);
			if ($a->leaderid1 == $user->ID OR $a->leaderid2 == $user->ID OR $a->leaderid3 == $user->ID) {
				if (sendMessage($_SESSION['isLogined'], $cgi['to'], $cgi['subject'], $cgi['message'])) {
					echo "Messages sent successfully";
				} else {
					echo "Error while sending the mail. Try again later.";
				}
			} else {
				echo "You are not the alliance Leader";
			}
		}
	} else {
		if (sendMessage($_SESSION['isLogined'], $cgi['to'], $cgi['subject'], $cgi['message'])) {
			echo "Message sent successfully";
		} else {
			echo "Error while sending the mail. Try again later.";
		}
	}
	echo "</font></center>";
}
?>
            <form action="writemail.php" method="post" name="REPLIER" id="REPLIER">
              <input type="hidden" name="to" value="<?=$cgi['to'] ?>">
              <table border="0" cellspacing="6" cellpadding="6">
                <tr>
                  <td colspan="2" align="center">
                    <h3>
                       Write Mail 
                    </h3>
                  </td>
                </tr>
                <tr>
                  <th align="left">
                    <b>To:</b> <?
if ($cgi['to'] != "msgoff" AND $cgi['to'] != "msgall") {
	$user1 = getUserDetails($cgi['to'], "userName,ID");
	echo $user1->userName;
	if ($user1->admin > 0) {
		echo "<font color=\"Blue\">{Admin}</font>";
	}
} elseif ($cgi['to'] == "msgall") {
	echo "Alliance";
} else {
	echo "Officers";
}
?>
                  </th>
                  <th align="left">
                    <b>Subject:</b><input type="text" maxlength="25" name="subject" value="">
                  </th>
                </tr>
                <tr>
                  <td colspan="2" style="border: #666666 1px solid;" bgcolor="#121212" align="center">
                    <textarea id="message" name="message" rows="20" cols="77"><? if (isset($cgi['repmsg'])) {
	echo "[quote]" . str_replace("<br />", "\n", stripslashes($cgi['repmsg'])) . "[/quote]\n\n";
} ?></textarea>
                  </td>
                </tr>
                <tr>
                  <td colspan="2" align="center">
                    <b>Please remember that we will NEVER ask for your password, email <br> or any other personal information using this messaging system.</b>
                  </td>
                </tr>
                <tr align="center">
                  <td colspan="2">
                    <input type="submit" name="send" value="Send">
                  </td>
                </tr>
              </table>
            </form>
            <P>
              <?
include ("bottom.php");
?>
          </TD>
        </TR>
      </TBODY>
    </TABLE>
  </BODY>
</HTML>
<? include "gzfooter.php";
?>
