<? include "gzheader.php";
include "scripts/vsys.php";
if ($cgi['buy_merc']) {
	$merc = getCommonInfo();
	if ($cgi['mercs_attack']) {
		if ($cgi['mercs_attack'] < 1.0) {
			$cgi['mercs_attack'] = 1;
		}
		$cgi['mercs_attack'] = round($cgi['mercs_attack'], 0);
		if ($cgi['mercs_attack'] > $merc->attackSpecCount) $cgi['mercs_attack'] = $merc->attackSpecCount;
		$gold = $cgi['mercs_attack'] * 10000;
		if ($user->gold > $gold) {
			updateMercenary(" attackSpecCount=attackSpecCount-{$cgi['mercs_attack']} ");
			updateUser($_SESSION['isLogined'], " samercs=samercs+{$cgi['mercs_attack']} , gold=gold-$gold ");
		} elseif ($user->savings > $gold AND $conf_use_savings AND $cgi['c_savings']) {
			updateMercenary(" attackSpecCount=attackSpecCount-{$cgi['mercs_attack']} ");
			updateUser($_SESSION['isLogined'], " samercs=samercs+{$cgi['mercs_attack']} , savings=savings-$gold ");
		} else {
			$strErr = "You do not have enough money";
		}
	}
	if ($cgi['mercs_defend']) {
		if ($cgi['mercs_defend'] < 1.0) {
			$cgi['mercs_defend'] = 1;
		}
		$cgi['mercs_defend'] = round($cgi['mercs_defend'], 0);
		if ($cgi['mercs_defend'] > $merc->defSpecCount) $cgi['mercs_defend'] = $merc->defSpecCount;
		$gold = $cgi['mercs_defend'] * 10000;
		if ($user->gold > $gold) {
			updateMercenary(" defSpecCount =defSpecCount -{$cgi['mercs_defend']} ");
			updateUser($_SESSION['isLogined'], " damercs=damercs+{$cgi['mercs_defend']}  , gold=gold-$gold ");
		} elseif ($user->savings > $gold AND $conf_use_savings AND $cgi['c_savings']) {
			updateMercenary(" defSpecCount=defSpecCount-{$cgi['mercs_defend']} ");
			updateUser($_SESSION['isLogined'], " damercs=damercs+{$cgi['mercs_defend']} , savings=savings-$gold ");
		} else {
			$strErr = "You do not have enough money";
		}
	}
	if ($cgi['mercs_attacks']) {
		if ($cgi['mercs_attacks'] < 1.0) {
			$cgi['mercs_attacks'] = 1;
		}
		$cgi['mercs_attacks'] = round($cgi['mercs_attacks'], 0);
		if ($cgi['mercs_attacks'] > $user->samercs) $cgi['mercs_attacks'] = $merc->samercs;
		$gold = $cgi['mercs_attacks'] * 5000;
		updateMercenary(" attackSpecCount=attackSpecCount+{$cgi['mercs_attacks']} ");
		updateUser($_SESSION['isLogined'], " samercs=samercs-{$cgi['mercs_attacks']} , gold=gold+$gold ");
	}
	if ($cgi['mercs_defends']) {
		if ($cgi['mercs_defends'] < 1.0) {
			$cgi['mercs_defends'] = 1;
		}
		$cgi['mercs_defends'] = round($cgi['mercs_defends'], 0);
		if ($cgi['mercs_defends'] > $user->damercs) $cgi['mercs_defends'] = $user->damercs;
		$gold = $cgi['mercs_defends'] * 5000;
		updateMercenary(" defSpecCount =defSpecCount +{$cgi['mercs_defends']} ");
		updateUser($_SESSION['isLogined'], " damercs=damercs-{$cgi['mercs_defends']}  , gold=gold+$gold ");
	}
	updateUserStats($user);
	header("Location: mercs.php?strErr=$strErr");
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<HTML><HEAD><TITLE><? echo $conf["sitename"]; ?> :: Neutral Portuguese Mercenaries  </TITLE>
<META http-equiv=Content-Type content="text/html; charset=iso-8859-1"><!-- ZoneLabs Privacy Insertion -->
<SCRIPT language=javascript src="js/js"></SCRIPT>
<LINK href="css/common.css" type=text/css rel=stylesheet>
<META  
content="ww2 , rpg, mmorpg, role playing, game, online game, text based game, armory, mercenaries, spy, attack, army, battle, recruit, spies, spy skill, weapons, messaging, sabotage, recon, intelligence, pnp, mud, games, stockade, free, browser game" 
name=keywords>
<META 
content="World War 2 is a Massively Multiplayer Online Role Playing Game with over 500,000 players. Players can choose one of four races: Orcs, Humans,  Elves and Dwarves and build armies, recruit friends as officers, buy weapons, and spy and attack on each other." 
name=description>
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
    vAlign=top align=left> <? include "islogined.php";
if ($cgi['strErr']) {
	echo "<center><font color=red>{$cgi['strErr']}</font></center>";
} ?><BR>
      <H3>Mercenaries</H3>
        <TABLE class=table_lines cellSpacing=0 cellPadding=6 width="380" 
            border=0>
          <TBODY>
            <TR> 
              <TH colSpan=2>Personnel</TH>
            </TR>
            <TR> 
              <TD><B>Trained Attack Soldiers</B></TD>
              <TD align=right>
                <? numecho($user->sasoldiers) ?>
              </TD>
            </TR>
            <TR> 
              <TD><B>Trained Attack Mercenaries</B></TD>
              <TD align=right>
                <? numecho($user->samercs) ?>
              </TD>
            </TR>
            <TR> 
              <TD><B>Trained Defense Soldiers</B></TD>
              <TD align=right>
                <? numecho($user->dasoldiers) ?>
              </TD>
            </TR>
            <TR> 
              <TD><B>Trained Defense Mercenaries</B></TD>
              <TD align=right>
                <? numecho($user->damercs) ?>
              </TD>
            </TR>
            <TR> 
              <TD><B>Untrained Soldiers</B></TD>
              <TD align=right>
                <? numecho($user->uu) ?>
              </TD>
            </TR> 
            <TR> 
              <TD class=subh><B>Spies</B></TD>
              <TD class=subh align=right>
                <? numecho($user->spies) ?>
              </TD>
            </TR>
            <TR> 
              <TD><B>Total Fighting Force</B></TD>
              <TD align=right>
                <? numecho(getTotalFightingForce($user)) ?>
              </TD>
            </TR>
          </TBODY>
        </TABLE>
        <P>
		<?
$merc = getCommonInfo();
?>
      <FORM action=mercs.php method=post>
      <TABLE class=table_lines cellSpacing=0 cellPadding=6 width="100%" 
border=0>
        <TBODY>
        <TR>
          <TH align=middle colSpan=4>Buy Portuguese Mercenaries</TH>
        </TR>
        <TR>
          <TH class=subh align=left>Mercenary Training</TH>
          <TH class=subh align=right>Cost Per Unit</TH>
          <TH class=subh align=right>Quantity Available</TH>
          <TH class=subh align=middle>Quantity to Buy</TH></TR>
        <TR>
          <TD>Portuguese Attack Specialist</TD>
          <TD align=right><? numecho(10000) ?> Gold</TD>
          <TD align=right><? numecho($merc->attackSpecCount); ?></TD>
          <TD align=middle><INPUT size=3 value=0 name=mercs_attack></TD></TR>
        <TR>
          <TD>Portuguese Defense Specialist</TD>
                <TD align=right>
                  <? numecho(10000) ?>
                  Gold</TD>
                <TD align=right>
                  <? numecho($merc->defSpecCount); ?>
                </TD>
          <TD align=middle><INPUT size=3 value=0 name=mercs_defend></TD></TR>
        
          <TD align=middle colSpan=4><? if ($conf_use_savings) { ?>
                	From Savings:<input type="checkbox" name="c_savings" />
                <?
} ?><INPUT type=submit value=Buy> 
        </TD></TR></TBODY></TABLE>
	  <INPUT type=hidden value=1 name=buy_merc></FORM>
          
          <FORM action=mercs.php method=post>
      <TABLE class=table_lines cellSpacing=0 cellPadding=6 width="100%" 
border=0>
        <TBODY>
        <TR>
          <TH align=middle colSpan=4>Sell Portuguese Mercenaries</TH>
        </TR>
        <TR>
          <TH class=subh align=left>Mercenary Training</TH>
          <TH class=subh align=right>Cost Per Unit</TH>
          <TH class=subh align=right>Quantity Available</TH>
          <TH class=subh align=middle>Quantity to Sell</TH></TR>
        <TR>
          <TD>Portuguese Attack Specialist</TD>
          <TD align=right><? numecho(5000) ?> Gold</TD>
          <TD align=right><? numecho($user->samercs); ?></TD>
          <TD align=middle><INPUT size=3 value=0 name=mercs_attacks></TD></TR>
        <TR>
          <TD>Portuguese Defense Specialist</TD>
                <TD align=right>
                  <? numecho(5000) ?>
                  Gold</TD>
                <TD align=right>
                  <? numecho($user->damercs); ?>
                </TD>
          <TD align=middle><INPUT size=3 value=0 name=mercs_defends></TD></TR>
             
          <TD align=middle colSpan=4><INPUT type=submit value=Sell> 
        </TD></TR></TBODY></TABLE>
	  <INPUT type=hidden value=1 name=buy_merc></FORM>
      <?
include ("bottom.php");
?>	
	  </TD></TR></TBODY></TABLE>
</BODY></HTML>
<? include "gzfooter.php"; ?>
