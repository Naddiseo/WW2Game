<? include "gzheader.php";
include "scripts/vsys.php";
if (!$cgi['id']) {
	header('Location: battlefeild.php');
	exit;
}
if ($cgi['mkcomander'] and intval($cgi['mkcomander'])) {
	updateUser($_SESSION['isLogined'], " commander='$cgi[mkcomander]', accepted=0 ");
}
$attacker = $user;
$def = getUserDetails($cgi['id']);
if ($user->alliance > 0 and $user->accepted > 0) {
	$tagq = mysql_query("SELECT tag FROM alliances WHERE id={$def->alliance}") or die(mysql_error());
	$t = mysql_fetch_array($tagq, MYSQL_ASSOC);
	$tag = $t['tag'];
}
if ($def->active != 1) {
	header("Location: base.php");
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD><TITLE><? echo $conf["sitename"]; ?> :: 
<? echo $def->userName; ?>'s Stats</TITLE>
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
//include "islogined.php";
$defR = getUserRanks($cgi['id']);
if ($strErr) {
	echo "<font color='red'>$strErr</font>";
}
?>
        <TABLE cellSpacing=15 cellPadding=0 width="100%" border=0>
          <TBODY>
            <TR> 
              <TD vAlign=top width="50%"> <TABLE class=table_lines cellSpacing=0 cellPadding=6 width="100%" 
            border=0>
                  <TBODY>
                    <TR> 
                      <TH colSpan=2>User Stats</TH>
                    </TR>
                    <TR> 
                      <TD><B>Name:</B></TD>
                      <TD>
                        <A href='recruit.php?uniqid=<?=$def->ID
?>'><?=$def->userName
?></a>&nbsp;<?=($tag != '' ? '[' . $tag . ']' : '') ?>
                      </TD>
                    </TR>
                    <TR> 
                      <TD><B>Commander:</B></TD>
                      <TD>
                        <? if ($def->commander != 0) {
	$defC = getUserDetails($def->commander, 'userName');
	echo "<a href='stats.php?id=" . $def->commander . "'>" . $defC->userName . "</a>";
} else {
	echo "None";
}
?>
                      </TD>
                    </TR>
                    <TR> 
                      <TD><B>Race:</B></TD>
                      <TD>
                        <?=$conf["race"][$def->race]["name"] ?>
                      </TD>
                    </TR>
                    <TR> 
                      <TD><B>Rank:</B></TD>
                      <TD>
                        <? numecho($defR->rank) ?>
                      </TD>
                    </TR>                    
                    <TR> 
                      <TD><B>Army Size:</B></TD>
                      <TD>
                        <? numecho(getTotalFightingForce($def)) ?>
                      </TD>
                    </TR>
                    <TR> 
                      <TD><B>Treasury:</B></TD>
                      <TD>
                        <? if (($_SESSION['isLogined'] != $def->ID) AND ((getCovertAction($def) * (rand(90, 110) / 100)) > getCovertAction($user) or !$_SESSION['isLogined'])) {
	echo "??????";
} else {
	numecho($def->gold);
} ?>
                      </TD>
                    </TR>
                    <TR> 
                      <TD><B>Fortifications:</B></TD>
                      <TD>
                        <? echo $conf["race"][$def->race]["fortification"][$def->dalevel]["name"]; ?>
                      </TD>
                    </TR>
			
                  </TBODY>
                </TABLE>
                <BR> <TABLE cellSpacing=10 width="100%">
                  <TBODY>
                    <TR> 
                      <TD align=right width="33%"> <FORM action=writemail.php method=get>
                          <INPUT type=hidden value=<?=$cgi['id'] ?> name=to>
                          <INPUT name="submit" type=submit value="Send Message">
                        </FORM></TD>
                     <? if (!$_SESSION['isLogined'] OR $attacker->commander == $cgi['id'] OR $def->commander == $attacker->ID) {
} else { ?>
                     
                     	 
                     	 <TD align=middle width="33%"> <FORM action="attack.php#spy" method=get>
                          <INPUT type=hidden value=<?=$cgi['id'] ?> name=id>
                          <INPUT name="submit" type=submit value=Spy>
                        </FORM></TD>                       
                       
                            <TD align=middle width="33%"> <FORM action=spy.php#ws method=get>
                                <INPUT type=hidden value=<?=$cgi['id'] ?> name=id>
                                <INPUT name="submit" type=submit value='Thieve'>
                            </FORM>
                            </TD>
                     
                      <TD align=left width="33%">
						<FORM action=attack.php method=get>
                          <INPUT type=hidden value=<?=$cgi['id'] ?> name=id>
                          <? if ($attacker->commander == $cgi['id']) {
		echo "[This is your commander]";
	} elseif ($def->commander == $attacker->ID) {
		echo "[This is your officer]";
	} else {
?>
                          <INPUT name="submit" type=submit value=Attack>
                          <?
	}
?>
                        </FORM>		
		</TD>
                    </TR>
			 <tr>
		    	<TD colspan="3" align="center">
			<?
	$officersC = getOfficersCount($cgi['id']);
	if ($attacker->commander == $cgi['id']) {
		echo "[This is your commander]";
	} elseif ($officersC >= $def->maxofficers) {
		echo "[This player has enough officers]";
	} else {
?>
				<form action="stats.php" method="get">
		   		 <input type="hidden" name="mkcomander" value="<?=$cgi['id'] ?>" />
				   <INPUT type=hidden    value=<?=$cgi['id'] ?> name=id>
                                     <input type="submit" value="Make this user my commander!" />
		    		</form>
			<?
	}
?>
			</TD><?
} ?>
		</tr>
                  </TBODY>
                </TABLE>
               
                </TD>
              <TD vAlign=top width="50%"> <TABLE class=table_lines cellSpacing=0 cellPadding=6 width="100%" 
            border=0>
                  <TBODY>
                    <TR> 
                      <TH align=middle colSpan=4>Officers</TH>
                    </TR>
                    <TR> 
                      <TH class=subh align=left>Name</TH>
                      <TH class=subh>&nbsp;</TH>
                      <TH class=subh align=left>Army</TH>
                      <TH class=subh align=right>Rank</TH>
                    </TR>
<?
$officersCq = mysql_query("SELECT count(*) FROM UserDetails WHERE commander={$def->ID}") or die(mysql_error());
$a = mysql_fetch_array($officersCq);
$officersC = $a[0];
if ($officersC) {
	$pCount = $officersC / $conf["users_per_page"];
	$pCountF = floor($pCount);
	$pCountF+= (($pCount > $pCountF) ? 1 : 0);
	if (!$cgi['page']) {
		$cgi['page'] = 1;
	}
	$officers = getOfficers($cgi['id'], $cgi['page']);
	for ($i = 0;$i < count($officers);$i++) {
?>					
                    <tr>
        <td><a href="stats.php?id=<?=$officers[$i]->userID ?>"><?=$officers[$i]->userName ?></a></td>
		<td align="right"><? numecho(getTotalFightingForce($officers[$i])); ?></td>
		              <td align="left"> 
                        <?=$conf["race"][$officers[$i]->race]["name"] ?>
                      </td>
        <td align="right"><? numecho($officers[$i]->rank); ?></td>
    </tr>
<?
	}
} else {
?>
					<TR> 
                      <TD align=middle colSpan=4>No Officers</TD>
                    </TR>
<?
} ?>
                    <TR> 
                      <TD>
                        <?
if ($cgi['page'] > 1) {
	echo "<A href='stats.php?page=" . ($cgi['page'] - 1) . "&id=" . $cgi['id'] . "'>&lt;&lt; Prev</A>";
} else {
	echo "&nbsp;";
}
?>
                      </TD>
                      <TD align=middle colSpan=2> 
                        <? numecho($officersC) ?>
                        officers total | page 
                        <?=$cgi['page'] ?>
                        of 
                        <? numecho($pCountF); ?>
                      </TD>
                      <TD>
                        <?
if ($cgi['page'] < $pCountF) {
	echo '<A href="stats.php?page=' . ($cgi['page'] + 1) . '&id=' . $cgi["id"] . '">Next &gt;&gt;</A>';
} else {
	echo "&nbsp;";
}
?>
                      </TD>
                    </TR>
                  </TBODY>
                </TABLE>
                
                </TD>
            </TR>            
          </TBODY>
        </TABLE> 
        <P>
      <?
include ("bottom.php");
?>	
	 </TD></TR></TBODY></TABLE>
</BODY></HTML>

<? include "gzfooter.php"; ?>
