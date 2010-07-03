<?
//include "vsys.php";

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD><TITLE><? echo $conf["sitename"]; ?> :: Massively Multiplayer Online Role Playing Game</TITLE>
<META http-equiv=Content-Type content="text/html; charset=iso-8859-1">
<SCRIPT language=javascript src="js/js"></SCRIPT>
<LINK href="css/common.css" type=text/css rel=stylesheet><LINK 
href="css/main.css" type=text/css rel=stylesheet>

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
include ("top.php");
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
  <center>
<table cellpadding="0" cellspacing="0" class="rno"><tbody><tr>
  <td><? echo $MessageStr; ?></td>
</tr></tbody></table>
</center>
	  
      <BR>
          <P><br>
            <br>
            <br><br>
<br>

          <P>
      <?
include ("bottom.php");
?>
	  </CENTER></TD></TR></TBODY></TABLE>
</BODY></HTML>
