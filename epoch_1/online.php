<? include "gzheader.php";
include "scripts/vsys.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD><TITLE><? echo $conf["sitename"]; ?> :: Online List</TITLE>
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
	</TD><td valign="TOP"><center> <b>Users Online</b><br>
	<table class=table_lines cellSpacing=0 cellPadding=6 width="50%" 
border=0>
	<tr><TD>UserName</td><td>Nation</td><TD>Rank</td></tr>
	<? $users = getOnlineUsers();
$all_cache = array();
for ($i = 0;$i < count($users);$i++) {
	$tag = '';
	if (in_array($users[$i]->alliance, $all_cache) AND !isset($all_cache[$users[$i]->alliance])) {
		$tag = $all_cache[$users[$i]->alliance];
	} elseif ($users[$i]->alliance > 0) {
		$qal = mysql_query("SELECT tag FROM alliances WHERE ID='" . $users[$i]->alliance . "'") or die(mysql_error());
		$r = mysql_fetch_array($qal, MYSQL_ASSOC);
		$all_cache[$users[$i]->alliance] = $r['tag'];
		if ($r['tag']) {
			$tag = "[" . $r['tag'] . "]";
		} else {
			$tag = '';
		}
	}
	if ($users[$i]->rank == 0) {
		echo "<tr><td>" . $users[$i]->userName . "</td><td>" . $conf["race"][$users[$i]->race]["name"] . "</td><td>";
		echo "Unranked";
	} else {
		echo "<tr><td><a href='stats.php?id=" . $users[$i]->ID . "'>" . $users[$i]->userName . "</a>&nbsp;$tag</td><td>" . $conf["race"][$users[$i]->race]["name"] . "</td><td>";
		echo $users[$i]->rank;
	}
	echo "</td>";
	echo "</tr>\n";
}
?>
	</table>
	 </center>
	      <?
include ("bottom.php");
?>	

</TD></TR></TBODY></TABLE></BODY></HTML>
	  
<? include "gzfooter.php"; ?>
