<? include "gzheader.php";
include "scripts/vsys.php";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD><TITLE><? echo $conf["sitename"]; ?>:: Attack Log</TITLE>
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
    <TD style="PADDING-RIGHT: 15px; PADDING-LEFT: 15px; PADDING-TOP: 12px"   vAlign=top align=left>
     <BR>
     <table width="100%" class="table_lines" cellspacing="0" cellpadding="6" border="0">
          <tr> 
            <th colspan="10" align="center">Attacks on You</th>
          </tr>
          <tr> 
            <th class="subh">&nbsp;</th>
            <th class="subh" align="left">Time</th>
            <th class="subh" align="left">Enemy</th>
            <th class="subh" align="right">Result</th>
            <th class="subh" align="right">Attacks</th>
            <th class="subh" align="right">Enemy Losses</th>
            <th class="subh" align="right">Your Losses</th>
            <th class="subh" align="right">Enemy Damage</th>
            <th class="subh" align="right">Your Damage</th>
            <th class="subh" align="center">Report</th>
          </tr>
          <?
if (!$cgi['page1']) {
	$cgi['page1'] = 1;
}
$atackA1 = getAttackByDefender($user->ID, $cgi['page1']);
for ($i = 0;$i < count($atackA1);$i++) {
?>
          <tr> 
            <td colspan="2" align="left"><? echo vDate($atackA1[$i]->time); ?></td>
            <td align="left"> 
              <?
	$tus = getUserDetails($atackA1[$i]->userID, "userName,active");
	if ($tus->active == 1) {
		echo "<a href='stats.php?id={$atackA1[$i]->userID}'>" . $tus->userName . "</a>";
	} else {
		echo "{" . $tus->userName . "}";
	}
?>
            </td>
            <td align="right"><?
	if ($atackA1[$i]->type == 1) {
		echo "<b>Nuke</b>";
	} else {
		if ($atackA1[$i]->attackStrength > $atackA1[$i]->defStrength) {
			numecho($atackA1[$i]->gold);
			echo " Gold stolen";
		} else {
			echo "Attack defended";
		}
	}
?></td>
            <td align="right"><?=$atackA1[$i]->attackTurns ?></td>
            <td align="right">
              <?=$atackA1[$i]->attackUsersKilled; ?>   
            </td>
            <td align="right">
              <?=$atackA1[$i]->defUsersKilled; ?>  
            </td>
            <td align="right">
              <? numecho($atackA1[$i]->attackStrength); ?>
            </td>
            <td align="right">
              <? numecho($atackA1[$i]->defStrength); ?>
            </td>
            <td align="center"><a href="battlelog.php?id=<?=$atackA1[$i]->ID ?>&isview=1">details</a></td>
          </tr>
          <?
}
?>
          <tr> 
            <td><? if ($cgi['page1'] > 1) {
	echo "<nobr><A href='attacklog.php?page1=" . ($cgi['page1'] - 1) . "'>&lt;&lt; Prev</A></nobr>";
} else {
	echo "&nbsp;";
}
?></td>
            <td colspan="8" align="center"><?
$atacks1C = getAttackByDefenderCount($user->ID);
$pCount1 = $atacks1C / $conf["users_per_page_on_attack_log"];;
$pCountF1 = floor($pCount1);
$pCountF1+= (($pCount1 > $pCountF1) ? 1 : 0);
numecho($atacks1C);
?> attacks total | page <? numecho($cgi['page1']); ?> of <? numecho($pCountF1); ?></td>
            <td> 
              <? if ($cgi['page1'] < $pCountF1) {
	echo "<nobr><A href='attacklog.php?page1=" . ($cgi['page1'] + 1) . "'>Next &gt;&gt;</A></nobr>";
} else {
	echo "&nbsp;";
}
?>
               </td>
          </tr>
        </table>
      <P>
        <table width="100%" class="table_lines" cellspacing="0" cellpadding="6" border="0">
          <tr> 
            <th colspan="10" align="center">Attacks on Others</th>
          </tr>
          <tr> 
            <th width="1%" class="subh">&nbsp;</th>
            <th width="7%" align="left" class="subh">Time</th>
            <th width="8%" align="left" class="subh">Enemy</th>
            <th width="7%" align="right" class="subh">Result</th>
            <th width="8%" align="right" class="subh">Attacks</th>
            <th width="15%" align="right" class="subh">Enemy Losses</th>
            <th width="13%" align="right" class="subh">Your Losses</th>
            <th width="16%" align="right" class="subh">Enemy Damage</th>
            <th width="14%" align="right" class="subh">Your Damage</th>
            <th width="9%" align="center" class="subh">Report</th>
          </tr>
          <?
if (!$cgi['page2']) {
	$cgi['page2'] = 1;
}
$atackA2 = getAttackByAttacker($user->ID, $cgi['page2']);
for ($i = 0;$i < count($atackA2);$i++) {
?>
          <tr> 
            <td colspan="2" align="right"><? echo vDate($atackA2[$i]->time); ?></td>
            <td align="left"> 
              <?
	$tus = getUserDetails($atackA2[$i]->toUserID, "userName,active");
	if ($tus->active == 1) {
		echo "<a href='stats.php?id={$atackA2[$i]->toUserID}'>" . $tus->userName . "</a>";
	} else {
		echo "{" . $tus->userName . "}";
	}
?>
            </td>
            <td align="right"> 
              <?
	if ($atackA2[$i]->type == 1) {
		echo "<b>Nuke</b>";
	} else {
		if ($atackA2[$i]->attackStrength > $atackA2[$i]->defStrength) {
			numecho($atackA2[$i]->gold);
			echo " Gold stolen";
		} else {
			echo "Attack defended";
		}
	}
?>
            </td>
            <td align="right"> 
              <?=$atackA2[$i]->attackTurns ?>
            </td>
            <td align="right"> 
              <?=$atackA2[$i]->defUsersKilled; ?>
            </td>
            <td align="right"> 
              <?=$atackA2[$i]->attackUsersKilled; ?>
            </td>             
            <td align="right"> 
               <? numecho($atackA2[$i]->defStrength); ?> 
            </td>
            <td align="right"> 
            <? numecho($atackA2[$i]->attackStrength); ?>
            </td>
            <td align="center"><a href="battlelog.php?id=<?=$atackA2[$i]->ID ?>&amp;isview=1">details</a></td>
          </tr>
          <?
} ?>
          <tr> 
            <td> 
              <? if ($cgi['page2'] > 1) {
	echo "<nobr><A href='attacklog.php?page2=" . ($cgi['page2'] - 1) . "'>&lt;&lt; Prev</A></nobr>";
} else {
	echo "&nbsp;";
}
?>
            </td>
            <td colspan="8" align="center"> 
              <?
$atacks2C = getAttackByAttackerCount($user->ID);
$pCount2 = $atacks2C / $conf["users_per_page_on_attack_log"];
$pCountF2 = floor($pCount2);
$pCountF2+= (($pCount2 > $pCountF2) ? 1 : 0);
numecho($atacks2C);
?>
              attacks total | page 
              <? numecho($cgi['page2']); ?>
              of 
              <? numecho($pCountF2); ?>
            </td>
            <td>
              <? if ($cgi['page2'] < $pCountF2) {
	echo "<nobr><A href='attacklog.php?page2=" . ($cgi['page2'] + 1) . "'>Next &gt;&gt;</A></nobr>";
} else {
	echo "&nbsp;";
}
?>
            </td>
          </tr>
        </table>

<?
include ("bottom.php");
?>	
     </TD></TR></TBODY></TABLE>
</BODY></HTML>

<? include "gzfooter.php"; ?>
