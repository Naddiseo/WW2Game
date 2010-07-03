<? include "gzheader.php";
include "scripts/vsys.php";
if ($cgi['defender_id2'] || $cgi['defender_id']) {
	if (($cgi['defender_id2'] == $_SESSION['isLogined']) || ($cgi['defender_id'] == $_SESSION['isLogined'])) {
		$strErr = " You can not Thieve yourself. ";
		$cgi['id'] = ($cgi['defender_id2']) ? $cgi['defender_id2'] : $cgi['defender_id'];
		$cgi['defender_id2'] = '';
		$cgi['defender_id'] = '';
	}
}
if (!($cgi['defender_id2'] || $cgi['defender_id'] || $cgi['id'])) {
	header('Location: battlefield.php');
	exit;
}

$attacker = $user;
//echo "<!--";print_r($cgi);print_r($attacker);echo "-->";
if ($cgi['defender_id2']) {
	// echo "test";
	$cgi['id'] = $cgi['defender_id2'];
	if ($cgi['wsspies'] < 1.0 or !is_numeric($cgi['wsspies'])) {
		$cgi['wsspies'] = 1;
	}
	if ($cgi['wsspies'] > $attacker->spies) {
		$cgi['wsspies'] = $attacker->spies;
	}
	if ($cgi['wsspies'] && $attacker->spies) {
		//--------------------------Attacking
		$attRanks = getUserRanks($_SESSION['isLogined']);
		$time = time() - (60 * 60);
		$a = mysql_query("SELECT count(*) FROM SpyLog WHERE toUserID='$cgi[id]' AND UserID='$_SESSION[isLogined]' AND type='1' AND time>$time;") or die(mysql_error());
		$aq = mysql_fetch_array($a);
		if ($aq[0] >= 10) {
			$strErr = "You have maxed out your attacking potential";
		} else if ($attacker->attackturns >= 3) {
			$defender = getUserDetails($cgi['id']);
			$time = time();
			$tSpies = $attacker->spies;
			$attacker->spies = $cgi['wsspies'];
			$spyStrength = getCovertAction($attacker);
			if ($spyStrength <= 0) {
				$spyStrength = 1;
			}
			$attacker->spies = $tSpies;
			$spyDefStrength = getCovertAction($defender);
			if ($spyDefStrength <= 0) {
				$spyDefStrength = 1;
			}
			$rnd = ($spyDefStrength * (rand(80, 100) / 100));
			$per = ($spyStrength / $rnd) * 100;
			if ($spyStrength < $rnd) {
				$isSuccess = 0;
				$arace = $attacker->race;
				//   echo "here 1 $per" ;
				if ($per < 50) {
					$uu = floor($cgi['wsspies'] * (rand(0, 10) / 100));
				} elseif ($per > 50 && $per < 80) {
					$uu = floor($cgi['wsspies'] * (rand(0, 8) / 100));
				} else {
					$uu = floor($cgi['wsspies'] * (rand(0, 5) / 100));
				}
				//$fields=" `time` ,`uu`,  `spies`,  `isSuccess`";
				//$values="'$time' ,`$uu  '".$cgi['wsspies']."',  '$isSuccess'";
				$fields = "`spyStrength`,  `spyDefStrength`,  `sasoldiers`,  `samercs`,  `dasoldiers`,  `damercs`,    `strikeAction` ,  `defenceAction`,  `covertSkill` ,  `covertOperatives`,  `salevel` ,  `attackturns` ,  `unitProduction` ,  `weapons`,  `types`,  `types2` ,  `quantities`,  `strengths` , `allStrengths`, `time` ,  `spies`,  `isSuccess`, `race`,`sf`,`sflevel`,`hh`,`uu`,`type`,`arace`";
				$values = "'0',  '0',  '0',  '0',  '0',  '0',  '0',  '0',  '0',  '0',  '0',  '0' ,  '0' ,  '0' ,   '0',  '0' ,  '0',  '0' ,'0',  '$time' ,  '$cgi[wsspies]',  '0', '0','0','0','0','$uu','1' ,'$arace'";
				updateUser($attacker->ID, " spies=spies-$uu, attackturns=attackturns - " . ($conf[thieft_turns] - 1) . "  ");
				addSpy($attacker->ID, $defender->ID, $fields, $values);
				$spy = getSpyByUser1User2AndTime($attacker->ID, $defender->ID, $time, " ID ");
				if (!$spy->ID) {
					die("error");
				}
				header("Location: spylog.php?id={$spy->ID}");
				exit;
			} elseif ($per > 200 AND ($attacker->spies > 50)) {
				// echo "here 2 $per" ;
				$isSuccess = 0;
				$arace = $attacker->race;
				if ($per > 200 && $per < 250) {
					$uu = floor($cgi['wsspies'] * (rand(0, 13) / 100));
				} else {
					$uu = floor($cgi['wsspies'] * (rand(0, 18) / 100));
				}
				$fields = "`spyStrength`,  `spyDefStrength`,  `sasoldiers`,  `samercs`,  `dasoldiers`,  `damercs`,    `strikeAction` ,  `defenceAction`,  `covertSkill` ,  `covertOperatives`,  `salevel` ,  `attackturns` ,  `unitProduction` ,  `weapons`,  `types`,  `types2` ,  `quantities`,  `strengths` , `allStrengths`, `time` ,  `spies`,  `isSuccess`, `race`,`sf`,`sflevel`,`hh`,`uu`,`type`,`arace`";
				$values = "'0',  '0',  '0',  '0',  '0',  '0',  '0',  '0',  '0',   '0',  '0',  '0' ,  '0' ,  '0' ,   '0',  '0' ,  '0',  '0' ,'0',  '$time' ,  '$cgi[wsspies]',  '0', '0','0','0','0','$uu','1' ,'$arace'";
				updateUser($attacker->ID, " spies=spies-$uu, attackturns=attackturns - " . ($conf[thieft_turns] - 1) . " ");
				addSpy($attacker->ID, $defender->ID, $fields, $values);
				//return;
				$spy = getSpyByUser1User2AndTime($attacker->ID, $defender->ID, $time, " ID ");
				if (!$spy->ID) {
					die("error");
				}
				header("Location: spylog.php?id={$spy->ID}");
				exit;
			} else {
				$isSuccess = 1;
				$race = $defender->race;
				$arace = $attacker->race;
				$gold = 0;
				$uu = 0;
				// echo "here 3 $per";
				$weapontype = $cgi['weaptype'];
				if ($weapontype == 1) {
					$aW = getUserWeapon($defender, "weaponCount");
				} else {
					$aW = getDefUserWeapon($defender, "weaponCount");
				}
				if ($aW) {
					$weapontype2 = $aW[0]->weaponID;
					$rnda = rand(0, 55) / 1000;
					$weaponamount = floor($rnda * $aW[0]->weaponCount);
					if ($aW[0]->weaponCount <= 15) {
						$weaponamount = 0;
					}
					//setWeapon($aw[0]->ID," weaponCount=weaponCount-'$weaponamount' ");
					if ($aW[0]->weaponCount - $weaponamount <= 0) {
						$sql = "DELETE FROM Weapon WHERE weaponID='$weapontype2' AND userID='$defender->ID' AND isAttack='$weapontype';";
					} else {
						$sql = "UPDATE Weapon SET weaponCount=weaponCount-'$weaponamount' WHERE weaponID='$weapontype2' AND userID='$defender->ID' AND isAttack='$weapontype';";
					}
					mysql_query($sql) or die(mysql_error());
					//  print $sql;
					if ($weapontype == 1) {
						$a = getUserWeapon($attacker);
					} else {
						$a = getDefUserWeapon($attacker);
					}
					$in = true;
					// print_r($a);
					for ($x = - 1;$x <= count($a);$x++) {
						if ($a[$x]->weaponID == $aW[0]->weaponID) {
							setWeapon($a[$x]->ID, " weaponCount=weaponCount+$weaponamount ");
							$in = false;
						}
					}
					if ($in == true AND $weaponamount > 0) {
						$str = $conf["weapon$weapontype2" . "strength"];
						$q = @mysql_query("insert into `Weapon` (weaponID, weaponStrength, weaponCount, isAttack, userID) values ('$weapontype2', '$str', '$weaponamount', '$weapontype', '$attacker->ID')");
					}
				} else {
					$weaponamount = 0;
				}
				if ($weaponamount > 0) {
					if ($arace == 2) {
						if (rand(0, 20) == 12) {
							$uu = floor((rand(0, 4) / 100) * $defender->uu);
						} else {
							$uu = 0;
						}
					} elseif ($arace == 0) {
						if (rand(0, 20) == 12) {
							$gold = floor((rand(0, 10) / 100) * $defender->gold);
						} else {
							$gold = 0;
						}
					} else {
						$uu = 0;
						$gold = 0;
					}
				} else {
					$isSuccess = 0;
				}
				$time = time();
				//updateUser($attacker->ID,"  attackturns=attackturns - 4 ,  uu=uu + '$uu' ");
				$sql = "UPDATE UserDetails SET attackturns=attackturns - $conf[thieft_turns] ,  uu=uu + '$uu' WHERE ID='$attacker->ID'";
				mysql_query($sql) or die(mysql_error());
				updateUser($defender->ID, " uu=uu - $uu,gold=gold-$gold  ");
				updateUser($attacker->ID, " gold=gold + $gold  ");
				$fields = "`spyStrength`,  `spyDefStrength`,  `sasoldiers`,  `samercs`,  `dasoldiers`,  `damercs`,    `strikeAction` ,  `defenceAction`,  `covertSkill` ,  `covertOperatives`,  `salevel` ,  `attackturns` ,  `unitProduction` ,  `weapons`,  `types`,  `types2` ,  `quantities`,  `strengths` , `allStrengths`, `time` ,  `spies`,  `isSuccess`, `race`,`sf`,`sflevel`,`hh`,`weapontype`,`weapontype2`,`type`,`weaponamount`,`uu`,`arace`,`gold`";
				#`race`,`sf`,`sflevel`,`hh`,`weapontype`,`type`,`weaponamount`,`uu`";
				$values = "'$spyStrength',  '$spyDefStrength',  '0',                                   '0',                             '0',            '0',             '0',               '0',             '0',                '0',               '0' ,            '0' ,          '0' ,            '0',          '0',      '0' ,     '0',             '0' ,           '0',      '$time' ,  '$cgi[wsspies]',  '1', '$race','0','0','0','$weapontype','$weapontype2','1','$weaponamount','$uu','$arace','$gold' ";
				//echo $values;
				//return;
				addSpy($attacker->ID, $defender->ID, $fields, $values);
				//return;
				$spy = getSpyByUser1User2AndTime($attacker->ID, $defender->ID, $time, " ID ");
				if (!$spy->ID) {
					die("error");
				}
				header("Location: spylog.php?id={$spy->ID}");
				exit;
			}
		} else {
			$strErr = "You do not have enough attack turns ($conf[thieft_turns])";
		}
	} else {
		$strErr = "You do not have enough spies";
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"
>
<HTML>
    <HEAD>
        <TITLE><? echo $conf["sitename"]; ?> :: Thieve</TITLE>
        <META http-equiv=Content-Type content="text/html; charset=iso-8859-1">
        <LINK href="css/common.css" type=text/css rel=stylesheet>
        <META content="  rpg, mmorpg, role playing, game, online game, text based game, armory, mercenaries, spy, attack, army, battle, recruit, spies, spy skill, weapons, messaging, sabotage, recon, intelligence, pnp, mud, games, stockade, free, browser game"
name=keywords>
        <META
content="WW2 is a Massively Multiplayer Online Role Playing Game with over 1,000 players. Players can choose one of four races: Orcs, Humans,  Elves and Dwarves and build armies, recruit friends as officers, buy weapons, and spy and attack on each other."
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
                        <center>
                            <FONT
      color=red><? include "islogined.php";
echo $strErr; ?></FONT>
                        </center>
                        <P>
                            <TABLE width="100%">
                                <TBODY>
                                    <TR>
                                        <TD style="PADDING-RIGHT: 25px" vAlign=top width="50%">
                                            <?
$enemy = getUserDetails($cgi['id']);
?>
                                            <a name="ws" />
                                            <FORM name=steal action=spy.php method=post>
                                                <INPUT type=hidden value='<?=$cgi['id'] ?>' name='id'>
                                                <TABLE class=table_lines cellSpacing=0 cellPadding=6 width="100%"            border=0>
                                                    <TBODY>
                                                        <TR>
                                                            <TH align=middle colSpan=2>
                                                                 Thief Mission
                                                            </TH>
                                                        </TR>
                                                        <TR>
                                                            <TD>
                                                                 Target:
                                                            </TD>
                                                            <TD>
                                                                <?=$enemy->userName
?>
                                                            </TD>
                                                        </TR>
                                                        <TR>
                                                            <TD>
                                                                 Spies to send?
                                                            </TD>
                                                            <TD>
                                                                <INPUT id="wsspies" size="8" name="wsspies">
                                                                <?
if ($user->supporter > 0) {
	//SP2 = ((SP1/100)*(2^L1)*B1*A1+SP1)/((2^L2)*B2*A2/100+1)
	$A1 = 1.0;
	$A2 = 1.0;
	//echo "<!-- ";
	//echo (($conf["cabonus{$enemy->race}"]+1)/($conf["cabonus{$user->race}"]+1));
	if ($enemy->alliance > 0) {
		$q = mysql_query("SELECT CA FROM alliances WHERE id={$enemy->alliance}") or die(mysql_error());
		$a = mysql_fetch_object($q);
		$A1+= (float)$a->CA;
	}
	if ($user->alliance > 0) {
		$q = mysql_query("SELECT CA FROM alliances WHERE id={$user->alliance}") or die(mysql_error());
		$a = mysql_fetch_object($q);
		$A2+= (float)$a->CA;
	}
	$spies = floor($enemy->spies * pow(2, $enemy->calevel - $user->calevel) * ($A1 / $A2) * (($conf["cabonus{$enemy->race}"] + 1) / ($conf["cabonus{$user->race}"] + 1)) * 1.3);
	/*$spies=round(1.3*
																					((
																						($enemy->spies/100) * (2^$enemy->calevel) * ($conf["cabonus{$enemy->race}"]+1)
																						*$A1+$enemy->spies
																					) /
																					(
																						(2^$user->calevel)*($conf["cabonus{$user->race}"]+1)*$A2/100+1
																					))
																				);*/
	//echo " -->";
	echo "<button onclick=\"document.getElementById('wsspies').value='$spies';return false;\" >Suggested Spies</button>";
} ?>
                                                            </TD>
                                                        </TR>
                                                        <tr>
                                                            <TD>
                                                                 Weapon Type?
                                                            </TD>
                                                            <td>
                                                            	<input type="radio" checked="checled" name="weaptype" value="0">Defense<br />
                                                            	<input type="radio" name="weaptype" value="1">Attack
                                                                
                                                            </td>
                                                        </tr>
                                                        <TR>
                                                            <TD align=middle colSpan=2>
                                                                <INPUT onClick="document.steal.stealbut.value='Stealing..'; document.steal.stealbut.disabled=true; document.steal.submit();" type=submit value=Steal! name=stealbut>
                                                            </TD>
                                                        </TR>
                                                    </TBODY>
                                                </TABLE>
                                                 <input type=hidden value=<?=$cgi['id'] ?> name=id />
                                                <input type=hidden value=<?=$cgi['id'] ?> name=defender_id2>
                                            </FORM>
                                        </TD>
                                        <TD vAlign=top>
                                            <TABLE class=table_lines cellSpacing=0 cellPadding=6 width="100%"
            border=0>
                                                <TBODY>
                                                    <TR>
                                                        <TH colSpan=2>
                                                             Personnel
                                                        </TH>
                                                    </TR>
                                                    <TR>
                                                        <TD>
                                                            <B>Trained Attack Soldiers</B>
                                                        </TD>
                                                        <TD align=right>
                                                            <? numecho($user->sasoldiers)
?>
                                                        </TD>
                                                    </TR>
                                                    <TR>
                                                        <TD>
                                                            <B>Trained Attack Mercenaries</B>
                                                        </TD>
                                                        <TD align=right>
                                                            <? numecho($user->samercs)
?>
                                                        </TD>
                                                    </TR>
                                                    <TR>
                                                        <TD>
                                                            <B>Trained Defense Soldiers</B>
                                                        </TD>
                                                        <TD align=right>
                                                            <? numecho($user->dasoldiers)
?>
                                                        </TD>
                                                    </TR>
                                                    <TR>
                                                        <TD>
                                                            <B>Trained Defense Mercenaries</B>
                                                        </TD>
                                                        <TD align=right>
                                                            <? numecho($user->damercs)
?>
                                                        </TD>
                                                    </TR>
                                                    <TR>
                                                        <TD>
                                                            <B>Untrained Soldiers</B>
                                                        </TD>
                                                        <TD align=right>
                                                            <? numecho($user->uu)
?>
                                                        </TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class=subh>
                                                            <B>Spies</B>
                                                        </TD>
                                                        <TD class=subh align=right>
                                                            <? numecho($user->spies)
?>
                                                        </TD>
                                                    </TR>
                                                    <TD class=subh>
                                                        <B>Special Forces</B>
                                                    </TD>
                                                    <TD class=subh align=right>
                                                        <? numecho($user->specialforces)
?>
                                                    </TD>
                                                    </TR>
                                                    <TR>
                                                        <TD>
                                                            <B>Total Fighting Force</B>
                                                        </TD>
                                                        <TD align=right>
                                                            <? numecho(getTotalFightingForce($user))
?>
                                                        </TD>
                                                    </TR>
                                                </TBODY>
                                            </TABLE>
                                            <BR>
                                            <TABLE class=table_lines cellSpacing=0 cellPadding=6 width="100%"
            border=0>
                                                <TBODY>
                                                    <TR>
                                                        <TH colSpan=3>
                                                             Military Effectiveness
                                                        </TH>
                                                    </TR>
                                                    <TR>
                                                        <TD>
                                                            <B>Strike Action</B>
                                                        </TD>
                                                        <TD align=right>
                                                            <? numecho(getStrikeAction($user))
?>
                                                        </TD>
                                                        <TD align=right>
                                                             Ranked<?
if ($userR->sarank) {
	numecho($userR->sarank);
} else echo "#unranked";
?>
                                                        </TD>
                                                    </TR>
                                                    <TR>
                                                        <TD>
                                                            <B>Defensive Action</B>
                                                        </TD>
                                                        <TD align=right>
                                                            <? numecho(getDefenseAction($user))
?>
                                                        </TD>
                                                        <TD align=right>
                                                             Ranked<?
if ($userR->darank) {
	numecho($userR->darank);
} else echo "#unranked";
?>
                                                        </TD>
                                                    </TR>
                                                    <TR>
                                                        <TD>
                                                            <B>Covert Action</B>
                                                        </TD>
                                                        <TD align=right>
                                                            <? numecho(getCovertAction($user))
?>
                                                        </TD>
                                                        <TD align=right>
                                                             Ranked<?
if ($userR->carank) {
	numecho($userR->carank);
} else echo "#unranked";
?>
                                                        </TD>
                                                    </TR>
                                                    <TR>
                                                        <TD>
                                                            <B>Retaliation Action</B>
                                                        </TD>
                                                        <TD align=right>
                                                            <? numecho(getRetaliationAction($user))
?>
                                                        </TD>
                                                        <TD align=right>
                                                             Ranked<?
if ($userR->rarank) {
	numecho($userR->rarank);
} else echo "#unranked";
?>
                                                        </TD>
                                                    </TR>
                                                </TBODY>
                                            </TABLE>
                                        </TD>
                                    </TR>
                                </TBODY>
                            </TABLE>
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
