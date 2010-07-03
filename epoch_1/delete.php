<? include "gzheader.php";
include "scripts/vsys.php";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD><TITLE><? echo $conf["sitename"]; ?> :: Delete account</TITLE>
<META http-equiv=Content-Type content="text/html; charset=iso-8859-1">
<SCRIPT language=javascript src="js/js"></SCRIPT>
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
//deleteUser($_SESSION['isLogined']);
if (is_array($_SESSION["isLogined"])) {
	$_SESSION["isLogined"] = $_SESSION["isLogined"][0];
}
mysql_query("UPDATE UserDetails SET active=5 WHERE ID=" . $_SESSION["isLogined"] . " LIMIT 1") or die(mysql_error());
$_SESSION['isLogined'] = 0;
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
    vAlign=top align=center> <BR><font color="red"><? if (mysql_error()) { ?>Your user (<?=$user->userName ?>) could not deleted<?
} else { ?>User Deleted<?
} ?></font>
        <P>
      <?
include ("bottom.php");
?>	
	 </TD></TR></TBODY></TABLE>
</BODY></HTML>

<? include "gzfooter.php"; ?>
