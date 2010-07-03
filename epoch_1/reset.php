<? include "gzheader.php";
include "scripts/vsys.php";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD><TITLE><? echo $conf["sitename"]; ?> :: Reset</TITLE>
<META http-equiv=Content-Type content="text/html; charset=iso-8859-1"><!-- ZoneLabs Privacy Insertion -->
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
$q = mysql_query("SELECT count(*) FROM UserDetails WHERE race=4 and active=1") or die(mysql_error());
$a = mysql_fetch_array($q);
$q = mysql_query("SELECT count(*) FROM UserDetails WHERE active=1") or die(mysql_error());
$b = mysql_fetch_array($q);
$ussrpercent = round($a[0] / $b[0] * 100);
if ($cgi['submit']) {
	echo "<center><font color=red>";
	if ($cgi['race'] == 4 AND $ussrpercent >= 15) {
		echo "Only 15% of the active players may be USSR";
	} elseif ($cgi['passone'] == $cgi['passtwo'] and $cgi['passone'] != '') {
		updateUser($_SESSION['isLogined'], " bank=0, gold=gold*0.50, race='{$cgi['race']}', password='" . md5($cgi['passone']) . "'  ");
		echo "Updated Successfully";
		deleteUserWeapon($_SESSION['isLogined']);
		clearRanks($_SESSION['isLogined']);
		$user = getUserDetails($_SESSION['isLogined']);
		$userR = getUserRanks($_SESSION['isLogined']);
	} else {
		echo "Password one and password two do not match, or the passwords are of 0 length.";
	}
	echo "</font></center>";
}
?>
        <P>
        <form action="reset.php" method="post">
          <table border="0" cellpadding="6" cellspacing="0" width="50%">
            <tr> 
              <td colspan="2"> Please note that by continuing all of your current 
                stats and weapons WILL BE LOST! If you wish to keep your current 
                stats, do not proceed.
                <p>
                50% of all gold on hand will be lost, all banked gold will be lost and all weapons will be lost.<br />
                You KEEP upgrades and personnel.
                </p>
                <p> If you wish, you may choose a new Nation. Note 
                  the current Nation bonuses: 
                <p>
                
                <table cellspacing="6" cellpadding="6">
                	<tr><td>Nation</td><td>Bonus Percent</td><td>Main Bonus</td><td>Extra Bonses</td></tr> 
                  <tr> 
                    <td>USA </td>
                    <td><font size="4" color="BLUE">22%</font></td>
                    <td>Income Bonus</td>
                    <td>Gold from thieving</td>
                  </tr>
                  <tr> 
                    <td>Germany  </td>
                    <td><font size="4" color="666600">22%</font></td>
                    <td>Attack Bonus</td>
                    <td>Prisoners of War</td>
                  </tr>
                  <tr> 
                    <td>Japan  </td>
                    <td><font size="4" color="GREEN">25%</font></td> 
                    <td>Covert Bonus</td>    
                    <td>Hostages</td>               
                  </tr>
                  <tr> 
                    <td>United Kingdom  </td>
                    <td><font size="4" color="RED">30%</font></td>
                    <td>Defense Bonus</td>
                    <td>Prisoners of War</td>
                  </tr>
                  <tr> 
                    <td>USSR  </td>
                    <td><font size="4" color="006666">25%</font></td>
                    <td>Retaliation Bonus</td>
                    <td>5% Attack/Defence/Covert Bonus, Unit Production Bonus</td>
                  </tr>
                </table>
                <p> <font color="red">Only upto 15% of the active player may be USSR.<br>
                Currently&nbsp;<?=$ussrpercent
?>%&nbsp;are.
                </font> </td>
            </tr>
            <tr> 
              <td> <table class="table_lines" width="100%" cellspacing="0" cellpadding="6">
                  <tr> 
                    <th colspan="2">Reset Account</th>
                  </tr>
                  <tr> 
                    <td> Username: </td>
                    <td> <?=$user->userName ?> 
                    </td>
                  </tr>
                  <tr> 
                    <td> Race: </td>
                    <td> <select name="race">
                        <?
for ($i = 0;$i < count($conf["race"]);$i++) {
	echo "<option value=$i ";
	if ($user->race == $i) {
		echo " selected ";
	}
	echo " >{$conf['race'][$i]['name']}</option> ";
}
?>
                      </select> </td>
                  </tr>
                  <tr> 
                    <td>New Password:</td>
                    <td><input type="password" name="passone"></td>
                  </tr>
                  <tr> 
                    <td>Verify Password:</td>
                    <td><input type="password" name="passtwo"></td>
                  </tr>
                  <tr> 
                    <td colspan="2" align="center"> <input name="submit" type="submit" value="Reset"> 
                    </td>
                  </tr>
                </table></td>
            </tr>
          </table>
        </form>
        <?
include ("bottom.php");
?>	
	 </TD></TR></TBODY></TABLE>
</BODY></HTML>

<? include "gzfooter.php"; ?>
