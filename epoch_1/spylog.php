<? include "gzheader.php";
include "scripts/vsys.php";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD><TITLE><? echo $conf["sitename"]; ?> :: Spy Log</TITLE>
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
    vAlign=top align=left> <p><BR>
          <?
include "islogined.php";
if (!isset($cgi['id']) OR !is_numeric($cgi['id'])) {
	die("Spy Log cannot be found");
}
$spyL = getSpy($cgi['id']);
$user = getUserDetails($spyL->userID);
$user4 = getUserDetails($spyL->toUserID);
$tus = getUserDetails($spyL->toUserID, " userName ");
//  echo "<!--";print_r($spyL);echo "-->";
if ($spyL->toUserID == $_SESSION['isLogined'] OR $_SESSION['isLogined'] == $spyL->userID OR $_SESSION['admin']) {
	//if ($user->admin == 1) {
	//echo print_r($spyL);
	//}
	if ($spyL->type == 0) {
?>
            </p>
            <h3>Covert Mission Report</h3>
            Under the cover of night, your
            <? numecho($spyL->spies); ?>
            spies sneak <?=$tus->userName ?>'s camp.
            <?
		if ($spyL->isSuccess) {
?>
            <p> Your spies move stealthily through
            <?=$tus->userName
?>'s camp undetected. They are able to gather many documents recording
            the status of weapons, army size and preparedness, and fortifications.
            Unfortunately, in order to avoid detection, they are not able to provide
            complete information. Your Chief of Intelligence provides you with the
            information gathered:<br>
            <p>
            <table width="100%" class="table_lines" border="0" cellspacing="0" cellpadding="6">
                <tr>
                    <th colspan="4" align="center">Army Size:</th>
                </tr>
                <tr>
                    <th class="subh">Unit Type</th>
                    <th class="subh">Attack Specialist</th>
                    <th class="subh">Defense Specialist</th>
                    <th class="subh">Untrained</th>
                </tr>
                <tr>
                    <td>Mercenaries</td>
                    <td><? numecho($spyL->samercs); ?></td>
                    <td>
                     <? numecho($spyL->damercs); ?>
                    </td>
                    <td>
              --
            </td>
          </tr>
          <tr>
            <td>Soldiers</td>
            <td>
              <? numecho($spyL->sasoldiers); ?>
            </td>
            <td>
              <? numecho($spyL->dasoldiers); ?>
            </td>
            <td>
              <? numecho($spyL->uu); ?>
            </td>
          </tr>
        </table>
        <p>
        <table width="50%" class="table_lines" border="0" cellspacing="0" cellpadding="6">
          <tr>
            <th colspan="2">Military Stats</th>
          </tr>
          <tr>
            <td>Strike Action:</td>
            <td>
              <? numecho($spyL->strikeAction); ?>
            </td>
          </tr>
          <tr>
            <td>Defensive Action</td>
            <td>
              <? numecho($spyL->defenceAction); ?>
            </td>
          </tr>
          <tr>
            <td>Covert Skill:</td>
            <td>
              <? numecho($spyL->covertSkill); ?>
            </td>
          </tr>
          <tr>
            <td>Covert Operatives:</td>
            <td>
              <? numecho($spyL->covertOperatives); ?>
            </td>
          </tr>
           <tr>
            <td>Special Forces Level:</td>
            <td>
              <? numecho($spyL->sflevel); ?>
            </td>
          </tr>
          <tr>
            <td>Special Forces Operatives:</td>
            <td>
              <? numecho($spyL->sf); ?>
            </td>
          </tr>
          <tr>
            <td>Siege Technology:</td>
            <td>
              <?=$spyL->salevel
?>
            </td>
          </tr>
          <tr>
            <td>Attack Turns:</td>
            <td>
              <? numecho($spyL->attackTurns); ?>
            </td>
          </tr>
          <!--<tr>
            <td>Treasury:</td>
            <td>
              <?
			//numecho($spyL->gold2);
			 ?>
            </td>
          </tr>-->
          <tr>
            <td>Unit Production:</td>
            <td>
              <? numecho($spyL->unitProduction); ?>
            </td>
          </tr>
        </table>
        <p>
        <table width="100%" class="table_lines" border="0" cellspacing="0" cellpadding="6">
          <tr>
            <th colspan="4">Weapons</th>
          </tr>
          <tr>
            <th class="subh">Name</th>
            <th class="subh">Type</th>
            <th class="subh">Quantity</th>
            <th class="subh">Strength</th>
          </tr>
          <?
			$weaponA = explode(";", $spyL->weapons);
			$typesA = explode(";", $spyL->types);
			$types2A = explode(";", $spyL->types2);
			$quantitiesA = explode(";", $spyL->quantities);
			$strengthsA = explode(";", $spyL->strengths);
			$allStrengthsA = explode(";", $spyL->allStrengths);
			if ($spyL->weapons) {
				for ($i = 0;$i < count($weaponA);$i++) {
?>
		          <tr>
                            <td><?
					if ($weaponA[$i] == '???') echo $weaponA[$i];
					else echo $conf["race"][$spyL->race][(($types2A[$i]) ? "weapon" : "defenseweapon") ][$weaponA[$i]]["name"];
?></td>
                                <td><?
					if ($typesA[$i] == '???') echo $typesA[$i];
					else echo (($typesA[$i]) ? "Attack" : "Defense");
?></td>
                                <td><? numecho($quantitiesA[$i]); ?></td>
                                <td><? numecho($strengthsA[$i]);
					echo "/";
					numecho($allStrengthsA[$i]); ?></td>
                            </tr>
		      <?
				}
			} ?>
            </table>
             </p>


        <?
		} else { ?>
        <P>As they approach the enemy armory, an alarm is cried out by an alert
          sentinel. Your spies are quickly rounded up and executed.
          <?=$tus->userName
?>
          is now aware of your actions. Your Chief of Intelligence recommends
          preparing your defenses against a retalitory assault by
          <?=$tus->userName
?>
          .<BR>
          <BR>
          <?
		} ?>
          <br>
		  <? if (!$cgi['isview']) { ?>
        <form method="get" action="attack.php">
          <input type="hidden" name="id" value="<?=$spyL->toUserID
?>">
          <input name="submit" type="submit" value="Attack / Spy Again">
        </form>
		<?
		}
		if (!$cgi['isview'] && $user->supporter > 0) {
?>
		<FORM name=attack action=attack.php method=post>
                                                <TABLE class=table_lines cellSpacing=0 cellPadding=6 width="100%"
            border=0>
                                                    <TBODY>
                                                        <TR>
                                                            <TH align=middle colSpan=2>
                                                                 Attack Mission
                                                            </TH>
                                                        </TR>                                                       
                                                        
                                                        <TR>
                                                            <TD>
                                                                 Attack Type
                                                            </TD>
                                                            <TD>
                                                                <INPUT  type=submit value=Mass! name=attackbut>
                                                                <INPUT  type=submit value=Balanced! name=attackbut>
                                                                <INPUT otype=submit value=Raid! name=attackbut>
                                                            </TD>
                                                        </TR>   
                                                        <tr><td colspan="2"><small>Mass: Uses one attack turn, causes a lot of damage and deaths but doesn't steal gold.<br />
                                                        			Balanced Attack: Causes Damage and steals gold.<br />
                                                        			Raid: Causes little or no damage but steals the most gold.
                                                        			</small></td></tr>                                                     
                                                    </TBODY>
                                                </TABLE>
                                                <input
            type=hidden value="<?=$spyL->toUserID
?>" name="defender_id2">
                                            </FORM>
		<?
		} ?>
        <p>&nbsp; </p>
        <P>
          <?
		//}
		
	} else {
		if ($spyL->isSuccess AND $spyL->weaponamount > 0) { ?>


                                            <p>Under the cover of night, <?=$user->userName ?>'s
                                            <? numecho($spyL->spies);
			echo (($spyL->spies == 1) ? " spy sneaks" : " spies sneak"); ?>
                                              into&nbsp;<?=$tus->userName
?>'s camp.<br>
                                            They are able to steal<font color="green">
                                            <?
			$name = $conf["race"][$spyL->race][(($spyL->weapontype) ? "weapon" : "defenseweapon") ][$spyL->weapontype2]["name"];
			echo $spyL->weaponamount;
			echo "</font>  $name's from the enemy's camp";
?><? if ($spyL->uu) { ?> <br>While <?=$user->userName ?>'s <? echo (($spyL->spies == 1) ? "spy was" : "spies were"); ?>  stealing weapons they had to take <font color=red><b><? numecho($spyL->uu); ?></b></font> hostages, after hearing of <?=$user->userName ?>'s nation these hostages agreed to join <?=$user->userName ?>'s forces.
                                            <?
			} elseif ($spyL->gold) { ?>
                                               <br> While <?=$user->userName ?>'s <? echo (($spyL->spies == 1) ? "spy was" : "spies were"); ?>  stealing weapons, <? echo (($spyL->spies == 1) ? "the spy" : "a few spies"); ?>  managed to steal&nbsp;<b><font color=red><? numecho($spyL->gold); ?></font></b>&nbsp;gold from <?=$tus->userName ?>.
                                            <?
			} ?>
                                            </p>


                                        <?
		} else {
?>
                                              <p>
                                              <?=$user->userName
?>'s <? numecho($spyL->spies);
			echo (($spyL->spies == 1) ? " spy" : " spies"); ?> tried to steal weapons from <?=$tus->userName ?> but failed.<br>
                                             <font color=red> <? numecho($spyL->uu); ?> </font>spies died trying and the rest escaped.<br>
                                            </p>
                                            <?
		}
	}
} else {
	echo "You cannot view other's spy logs";
}
include ("bottom.php");
?>
	 </TD></TR></TBODY></TABLE>
</BODY></HTML>
