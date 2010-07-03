<? include "gzheader.php";
include "scripts/vsys.php";
function getKilled($count, $atturns, $success, $death) {
	if ($success) {
		return floor(rand(0, 80) / 100000 * $count * (1 / $atturns) * ($death / 5));
	} else {
		return floor(rand(0, 200) / 100000 * $count * (1 / $atturns) * ($death / 5));
	}
}
function damageWeapons($userid, $attacker = 0, $ra = 0, $attackturns, $success) {
	$q = mysql_query("SELECT sum(weaponStrength*weaponCount) AS wpoints,sum(weaponCount) AS wcount FROM Weapon WHERE userID=$userid AND isAttack=$attacker") or die(mysql_error());
	$a = mysql_fetch_array($q, MYSQL_ASSOC);
	$count = $a['wcount'] ? $a['wcount'] : 0;
	$a['wcount'] = ($a['wcount'] == 0 ? 1 : $a['wcount']);
	if ($success == 1) {
		if ($attackturns == 1) {
			$attdmg = floor((($a['wpoints'] * ((rand(15, 100) / 10000))) / $a['wcount']));
		} elseif ($attackturns == 8) {
			$attdmg = floor((($a['wpoints'] * ((rand(1, 40) / 10000))) / $a['wcount']) * 0.2);
		} else {
			$attdmg = floor((($a['wpoints'] * ((rand(1, 15) / 10000))) / $a['wcount']) * 0.06);
		}
	} else {
		$attdmg = floor($attackturns / 2);
	}
	$before = $a['wpoints'];
	mysql_free_result($q);
	$q = mysql_query("SELECT ID,weaponStrength FROM Weapon WHERE userID=$userid AND isAttack=$attacker ORDER BY weaponID ASC") or die(mysql_error());
	while ($row = mysql_fetch_object($q)) {
		if ($attdmg >= $row->weaponStrength) {
			$attdmg-= $row->weaponStrength;
			$d = mysql_query("UPDATE Weapon SET weaponStrength=0 WHERE ID={$row->ID}");
		} else {
			$d = mysql_query("UPDATE Weapon SET weaponStrength=weaponStrength-$attdmg WHERE ID={$row->ID}");
			$attdmg-= $row->weaponStrength;
		}
		if ($attdmg <= 0) {
			break;
		}
	}
	mysql_query("DELETE FROM Weapon WHERE weaponStrength<=0");
	$q = mysql_query("SELECT sum(weaponStrength*weaponCount) AS wpoints FROM Weapon WHERE userID=$userid AND isAttack=$attacker") or die(mysql_error());
	$a = mysql_fetch_array($q, MYSQL_ASSOC);
	$after = $a['wpoints'];
	mysql_free_result($q);
	$p = (100 - ($before > 0 ? round($after / $before * 100, 2) : 100));
	return array('per' => $p ? $p : 0, 'count' => $count);
}
function spiedValue($value, $difPerc, $attCA, $defCA, $race) {
	//$value=(rand(($difPerc-100),$difPerc)>0)?$value:"???";
	$rdefCA = (float)(rand(80, 105) / 100) * $defCA;
	$rattCA = (float)(rand(95, 105) / 100) * $attCA;
	if ($rdefCA > $rattCA) //disinfomation
	{
		if (is_int($value)) { //must be an integer
			if ($race == 2) { //give the ca race more accuate values
				$value = floor((rand(85, 115) / 100) * $value);
			} else {
				$value = floor((rand(50, 150) / 100) * $value);
			}
		} else {
			$value = "???";
		}
	} else { //can show the value
		$value = $value;
	}
	return $value;
}
if ($cgi['defender_id2'] || $cgi['defender_id']) {
	if (($cgi['defender_id2'] == $_SESSION['isLogined']) || ($cgi['defender_id'] == $_SESSION['isLogined'])) {
		$strErr = " You can not attack or spy yourself. ";
		$cgi['id'] = ($cgi['defender_id2']) ? $cgi['defender_id2'] : $cgi['defender_id'];
		$cgi['defender_id2'] = '';
		$cgi['defender_id'] = '';
	}
	
}

if (!($cgi['defender_id2'] || $cgi['defender_id'] || $cgi['id'])) {
	header('Location: battlefield.php');
	exit;
}

if ($cgi['defender_id2'] and $cgi['attackbut']) {
	$cgi['id'] = $cgi['defender_id2'];

	if (!$cgi['id']) {
		header('Location: battlefield.php');
		exit;
	}
	switch ($cgi['attackbut']) {
		default:
		case 'Mass!':
			$cgi['attacks'] = 1;
		break;
		case 'Balanced!':
			$cgi['attacks'] = 8;
		break;
		case 'Raid!':
			$cgi['attacks'] = 15;
		break;
	}
	//get the users
	$attacker = getUserDetails($_SESSION['isLogined']);
	$defender = getUserDetails($cgi['id']);
	$time = time() - (60 * 60 * 12);
	$a = mysql_query("SELECT count(*) FROM AttackLog WHERE toUserID='$cgi[id]' AND UserID='$_SESSION[isLogined]' AND time>$time;") or die(mysql_error() . "err10");
	$aq = mysql_fetch_array($a);
	if ($aq[0] >= 10) {
		header("Location: attack.php?strErr=You have maxed out your attacking potential on this player");
		exit;
	}
	//make sure it's a real value
	if ($cgi['attacks'] < 1.0 or !is_numeric($cgi['attacks'])) {
		$cgi['attacks'] = 1;
	}
	if ($cgi['attacks'] > 15) {
		$cgi['attacks'] = 15;
	}
	//attack must have enough attack turns
	if ($attacker->attackturns >= $cgi['attacks']) {
		$Osa = getStrikeAction($attacker, 1);
		$Oda = getDefenseAction($defender, 1);
		if ($Oda == 0) {
			$Oda = 1;
		}
		if ($Osa == 0) {
			$Osa = 1;
		}
		if ($Osa <= $Oda * 0.15) {
			header("Location: attack.php?strErr=The enemy's army vastly outnumbers your forces. Your army refuses to fight, and retreats cowardly.");
			exit;
		}
		$sa = floor($Osa * rand(60, 100) / 100);
		$da = floor($Oda * rand(60, 100) / 100);
		if ($da == 0) {
			$da = 1;
		}
		if ($sa == 0) {
			$sa = 1;
		}
		$saper = floor($sa / $Osa * 100);
		$daper = floor($da / $Oda * 100);
		if ($sa > $da) {
			$success = true;
		} else {
			$success = false;
		}
		//gold
		$gold = 0;
		$pergold = 0;
		$attexp = 0;
		$defexp = 0;
		if ($success AND $cgi['attacks'] > 1) {
			$goldbonus = rand(60, 90) / 100;
			if ($attacker->race == 3) {
				$goldbonus+= 0.1;
			}
			if ($defender->race == 1) {
				$goldbonus-= 0.1;
			}
			if ($cgi['attacks'] == 15) {
				$gold = floor(($defender->gold * $goldbonus)); //make bigger
				
			} else {
				$gold = floor(($defender->gold * 0.8 * $goldbonus)); //make bigger
				
			}
			$pergold = floor($gold / $defender->gold * 100);
			$attexp = floor(($da / $sa) * 400);
			$attexp = $attexp > 400 ? 400 : $attexp;
		} else {
			$defexp = floor(($sa / $da) * 400);
			$defexp = $defexp > 400 ? 400 : $defexp;
		}
		$userhost = 0;
		$defuserhost = 0;
		if ($success AND $attacker->race == 3 AND rand(0, 20) == 12) {
			$userhost = (rand(0, 2) / 100) * $defender->uu;
		} elseif (!$success AND $defender->race == 1 AND rand(0, 20) == 15) {
			$defuserhost = (rand(0, 3) / 100) * $attacker->uu;
		}
		$attackTrained = $attacker->sasoldiers; // +$atacker->samercs;
		$attackMercs = $attacker->samercs;
		$attackUnTrained = $attacker->uu;
		$defTrained = $defender->dasoldiers; // +$defender->samercs;
		$defMercs = $defender->damercs;
		$defUnTrained = $defender->uu;
		if ($success) {
			$attackUsersKilledAS = getKilled($attacker->sasoldiers, $cgi['attacks'], true, 2);
			$attackUsersKilledAM = getKilled($attacker->samercs, $cgi['attacks'], true, 0);
			$attackUsersKilledUS = getKilled($attacker->uu, $cgi['attacks'], true, 3);
			$defUsersKilledDS = getKilled($defender->dasoldiers, $cgi['attacks'], false, 4);
			$defUsersKilledDM = getKilled($defender->damercs, $cgi['attacks'], false, 3);
			$defUsersKilledUS = getKilled($defender->uu, $cgi['attacks'], false, 5);
		} else {
			$attackUsersKilledAS = getKilled($attacker->sasoldiers, $cgi['attacks'], false, 4);
			$attackUsersKilledAM = getKilled($attacker->samercs, $cgi['attacks'], false, 3);
			$attackUsersKilledUS = getKilled($attacker->uu, $cgi['attacks'], false, 5);
			$defUsersKilledDS = getKilled($defender->dasoldiers, $cgi['attacks'], true, 2);
			$defUsersKilledDM = getKilled($defender->damercs, $cgi['attacks'], true, 0);
			$defUsersKilledUS = getKilled($defender->uu, $cgi['attacks'], true, 3);
		}
		$attackUsersKilled = $attackUsersKilledAS + $attackUsersKilledAM + $attackUsersKilledUS;
		$defUsersKilled = $defUsersKilledAS + $defUsersKilledAM + $defUsersKilledUS;
		updateUser($cgi['id'], "gold=gold-$gold,exp=exp+$defexp,
								uu=uu-($userhost+$defUsersKilledUS)+$defuserhost,
								dasoldiers=dasoldiers-$defUsersKilledDS,
								damercs=damercs-$defUsersKilledDM");
		updateUser($_SESSION['isLogined'], "attackTurns=attackturns-$cgi[attacks],
											uu=uu-($defuserhost+$attackUsersKilledUS)+$userhost,
											sasoldiers=sasoldiers-$attackUsersKilledAS,
											samercs=samercs-$attackUsersKilledAM,
											gold=gold+$gold,exp=exp+$attexp");
		mysql_query('UPDATE UserDetails SET gold=0 WHERE gold<0');
		//calculate th extra amount of damage RA will cause
		$attRA = getRetaliationAction($attacker, 1) * (rand(50, 100) / 100);
		$defRA = getRetaliationAction($defender, 1) * (rand(50, 100) / 100);
		$radamage = rand(1, 35) / 10000;
		//do the RA damage
		$defdam = 0;
		$defdamA = 0;
		$dcount = 0;
		$atdam = 0;
		$atdamD = 0;
		$acount = 0;
		if (!$success) {
			$d = 0;
			$a = 1;
		} else {
			$d = 1;
			$a = 1;
		}
		if ($attRA > $defRA) {
			$defdam = damageWeapons($cgi['id'], 0, $radamage, $cgi['attacks'], $d);
			$defdamA = damageWeapons($cgi['id'], 1, $radamage, $cgi['attacks'], $d);
			$dcount = $defdam['count'];
			$defdam = round($defdam['per'], 2);
			$defdamA = round($defdamA['per'], 2);
			$atdam = damageWeapons($_SESSION['isLogined'], 1, 0, $cgi['attacks'], $a);
			$acount = $atdam['count'];
			$atdam = round($atdam['per'], 2);
		} else {
			//($userid,$attacker=0,$ra=0,$attackturns,$success)
			$defdam = damageWeapons($cgi['id'], 0, $d, $cgi['attacks'], $d);
			$dcount = $defdam['count'];
			$defdam = round($defdam['per'], 2);
			$atdam = damageWeapons($_SESSION['isLogined'], 1, $radamage, $cgi['attacks'], $a);
			$atdamD = damageWeapons($_SESSION['isLogined'], 0, $radamage, $cgi['attacks'], $a);
			$acount = $atdam['count'];
			$atdam = round($atdam['per'], 2);
			$atdamD = round($atdamD['per'], 2);
		}
		$fields = "attackturns,attackStrength, defStrength, gold,  attackUsersKilled, defUsersKilled,	 
	 attackTrained, attackUnTrained, defTrained,defUnTrained, attackWeapons,defWeapons, time, attackWeaponCount, 
	 defWeaponCount, pergold,attackMercs, defMercs, defexp,attexp,attper,defper, userhost,defuserhost,	 
	 type, raeff ";
		$time = time();
		$values = "$cgi[attacks],$sa,$da,$gold,$attackUsersKilled,$defUsersKilled,$attackTrained,$attackUnTrained,
				$defTrained,$defUnTrained,'$atdam:$atdamD','$defdam:$defdamA',$time,$acount,$dcount,$pergold,$attackMercs,
				$defMercs,$defexp,$attexp,$saper,$daper,$userhost,$defuserhost,0," . round($radamage * 100, 2);
		updateUserStats($attacker);
		updateUserStats($defender);
		//addAttack($_SESSION['isLogined'],$cgi['id'],$fields,$values);
		$sql = "INSERT INTO AttackLog (userID,toUserID,$fields) VALUES ($_SESSION[isLogined],$cgi[id],$values);";
		//echo $sql;
		mysql_query($sql) or die(mysql_error() . "err");
		$attak = getAttackByUser1User2AndTime($_SESSION['isLogined'], $cgi['id'], $time, " ID ");
		header("Location: battlelog.php?id={$attak->ID}");
		exit;
	} else {
		$strErr = "You do not have enough turns.";
	}
} elseif ($cgi['defender_id']) {
	$cgi['id'] = $cgi['defender_id'];
	$attacker = getUserDetails($_SESSION['isLogined']);
	if ($cgi['numspies'] < 1.0) {
		$cgi['numspies'] = 1;
	}
	if ($cgi['numspies'] > $attacker->spies) $cgi['numspies'] = $attacker->spies;
	if ($cgi['numspies'] && $attacker->spies) {
		//echo "Spying  ";
		$defender = getUserDetails($cgi['id']);
		$time = time();
		//echo ($atacker->currentSpySkill+1).";;".$defender->currentSpySkill."##<br>";
		//if (($atacker->currentSpySkill+1)<$defender->currentSpySkill){
		$tSpies = $atacker->spies;
		$attacker->spies = $attacker->spies - $cgi['numspies'];
		$spyStrength = getCovertAction($attacker, 1);
		$attacker->spies = $tSpies;
		$spyDefStrength = getCovertAction($defender);
		if ($spyStrength < ($spyDefStrength * (rand(80, 120) / 100))) {
			$isSuccess = 0;
			$fields = " `time` ,  `spies`,  `isSuccess`";
			$values = "'$time' ,  '" . $cgi['numspies'] . "',  '$isSuccess'";
			updateUser($attacker->ID, " spies=spies-{$cgi['numspies']} ");
		} else {
			$isSuccess = 1;
			$spies = $cgi['numspies'];
			$race = $defender->race;
			$arace = $attacker->race;
			//$atacker->spies=$cgi['numspies'];
			$dif = $spyStrength - $spyDefStrength;
			if ($dif > 100) $dif = 100;
			$dif+= 100;
			$difPerc = $dif / 2;
			//  echo "--$difPerc--<br>";
			$sasoldiers = spiedValue($defender->sasoldiers, $difPerc, $spyStrength, $spyDefStrength, $arace);
			$samercs = spiedValue($defender->samercs, $difPerc, $spyStrength, $spyDefStrength, $arace);
			$dasoldiers = spiedValue($defender->dasoldiers, $difPerc, $spyStrength, $spyDefStrength, $arace);
			$damercs = spiedValue($defender->damercs, $difPerc, $spyStrength, $spyDefStrength, $arace);
			$untrainedMerc = spiedValue($defender->untrainedMerc, $difPerc, $spyStrength, $spyDefStrength, $arace);
			$uu = spiedValue($defender->uu, $difPerc, $spyStrength, $spyDefStrength, $arace);
			$strikeAction = getStrikeAction($defender);
			$strikeAction = spiedValue($strikeAction, $difPerc, $spyStrength, $spyDefStrength, $arace);
			$defenceAction = getDefenseAction($defender);
			$defenceAction = spiedValue($defenceAction, $difPerc, $spyStrength, $spyDefStrength, $arace);
			$covertSkill = spiedValue($defender->calevel, $difPerc, $spyStrength, $spyDefStrength, $arace);
			$sf = spiedValue($defender->specialforces, $difPerc, $spyStrength, $spyDefStrength, $arace);
			$sflevel = spiedValue($defender->sflevel, $difPerc, $spyStrength, $spyDefStrength, $arace);
			$hh = spiedValue($defender->hhlevel, $difPerc, $spyStrength, $spyDefStrength, $arace);
			$covertOperatives = spiedValue($defender->spies, $difPerc, $spyStrength, $spyDefStrength, $arace);
			$siegeLevel = spiedValue($defender->salevel, $difPerc, $spyStrength, $spyDefStrength, $arace);
			$atackTurns = spiedValue($defender->attackturns, $difPerc, $spyStrength, $spyDefStrength, $arace);
			$unitProduction = spiedValue($defender->up, $difPerc, $spyStrength, $spyDefStrength, $arace);
			/*
					 $weapons
					 $types
					 $quantities
					 $strengths
					 $allStrengths
			*/
			$weapon1 = getUserAllWeapon($defender);
			for ($i = 0;$i < count($weapon1);$i++) {
				if (!spiedValue(0, $difPerc, $spyStrength, $spyDefStrength, $arace)) {
					$weapons.= spiedValue($weapon1[$i]->weaponID, $difPerc, $spyStrength, $spyDefStrength, $arace) . ";";
					$types.= spiedValue($weapon1[$i]->isAttack, $difPerc, $spyStrength, $spyDefStrength, $arace) . ";";
					$types2.= $weapon1[$i]->isAttack . ";";
					$quantities.= spiedValue($weapon1[$i]->weaponCount, $difPerc, $spyStrength, $spyDefStrength, $arace) . ";";
					$strengths.= spiedValue($weapon1[$i]->weaponStrength, $difPerc, $spyStrength, $spyDefStrength, $arace) . ";";
					//$allStrengths.=spiedValue($weapon1[$i]->weaponID,$difPerc).";";
					$tS = $conf["weapon" . $weapon1[$i]->weaponID . "strength"];
					$allStrengths.= $tS . ";";
				}
			}
			if (strlen($weapons)) {
				$weapons = substr($weapons, 0, strlen($weapons) - 1);
				$types = substr($types, 0, strlen($types) - 1);
				$types2 = substr($types2, 0, strlen($types2) - 1);
				$quantities = substr($quantities, 0, strlen($quantities) - 1);
				$strengths = substr($strengths, 0, strlen($strengths) - 1);
				$allStrengths = substr($allStrengths, 0, strlen($allStrengths) - 1);
			}
			$spygold = $defender->bank;
			//$sasoldiers =$defender->sasoldiers;
			$fields = "`spyStrength`,  `spyDefStrength`,  `sasoldiers`,  `samercs`,  `dasoldiers`,  `damercs`,  `untrainedMerc`,  `uu`,
			  `strikeAction` ,  `defenceAction`,  `covertSkill` ,  `covertOperatives`,  `salevel` ,  `attackTurns` ,  `unitProduction` ,  
			  `weapons`,  `types`,  `types2` ,  `quantities`,  `strengths` , `allStrengths`, `time` ,  `spies`,  `isSuccess`, 
			  `race`,`sf`,`sflevel`,`hh`, `gold`";
			$values = "'$spyStrength',  '$spyDefStrength',  '$sasoldiers',  '$samercs',  '$dasoldiers',  '$damercs',  '$untrainedMerc',  
			'$uu',  '$strikeAction',  '$defenceAction',  '$covertSkill',  '$covertOperatives',  '$siegeLevel' , 
			 '$atackTurns' ,  '$unitProduction' ,  '$weapons',  '$types',  '$types2' ,  '$quantities', 
			  '$strengths' ,'$allStrengths',  '$time' ,  '$spies',  '$isSuccess', '$race','$sf','$sflevel','$hh', '$spygold'";
		}
		//echo $fields."<br>";
		//echo $values;
		//return;
		addSpy($attacker->ID, $defender->ID, $fields, $values);
		//return;
		$spy = getSpyByUser1User2AndTime($attacker->ID, $defender->ID, $time, " ID ");
		if (!$spy->ID) {
			die('No spyid');
		}
		header("Location: spylog.php?id={$spy->ID}");
		exit;
	} else {
		$strErr = "You should enter number of (o have) spies to send.";
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"
>
<HTML>
	<HEAD>
		<TITLE><? echo $conf["sitename"]; ?> :: Attack</TITLE>
		<META http-equiv=Content-Type content="text/html; charset=iso-8859-1">
		<LINK href="css/common.css" type=text/css rel=stylesheet>
		<META
content=" orcs, rpg, mmorpg, role playing, game, online game, text based game, armory, mercenaries, spy, attack, army, battle, recruit, spies, spy skill, weapons, messaging, sabotage, recon, intelligence, pnp, mud, games, stockade, free, browser game"
name=keywords>
		<META
content="WW2 is a Massively Multiplayer Online Role Playing Game with over 500,000 players. Players can choose one of four races: Orcs, Humans,  Elves and Dwarves and build armies, recruit friends as officers, buy weapons, and spy and attack on each other."
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
	<BODY text=#ffffff bgColor=#000000 leftMargin=0 topMargin=0 marginheight="0" marginwidth="0">
		<? include "top.php"; ?>
		<TABLE cellSpacing=0 cellPadding=5 width="100%" border=0>
			<TBODY>
				<TR>
					<TD class=menu_cell_repeater style="PADDING-LEFT: 15px" vAlign=top width=140>
						<? include ("left.php"); ?>
					</TD>
					<TD style="PADDING-RIGHT: 15px; PADDING-LEFT: 15px; PADDING-TOP: 12px"
	vAlign=top align=left>
						<BR>
						<center>
							<FONT color=red><? include "islogined.php"; echo $_GET['strErr']; echo $strErr ?></FONT>
						</center>
						<P>
							<TABLE width="100%">
								<TBODY>
									<TR>
										<TD style="PADDING-RIGHT: 25px" vAlign=top width="50%">
											<? $enemy = getUserDetails($cgi['id']); ?>
											<FORM name=attack action=attack.php method=post>
												<TABLE class=table_lines cellSpacing=0 cellPadding=6 width="100%" border=0>
													<TBODY>
														<TR>
															<TH align=middle colSpan=2>
																 Attack Mission
															</TH>
														</TR>
														<TR>
															<TD>
																 Target:
															</TD>
															<TD>
																<?=$enemy->userName ?>
															</TD>
														</TR>
					<? if (($enemy->gold >= 0) && ($user->gold >= 0)) { ?>
														<TR>
															<TD>
																 Attack Type
															</TD>
															<TD>
																<!-- TODO: get rid of the onclick, and put it in a javascript file -->
																<!-- <input onclick="document.attack.attackbut.value='Attacking..'; document.attack.attackbut.disabled=true; document.attack.submit();return true;" type="submit" value="Mass!" name="attackbut" />
																<input onclick="document.attack.attackbut.value='Attacking..'; document.attack.attackbut.disabled=true; document.attack.submit(); return true;" type="submit" value="Balanced!" name="attackbut" />
																<input onclick="javascript:document.attack.attackbut.value='Attacking..'; document.attack.attackbut.disabled=true; document.attack.submit(); return true;" type="submit" value="Raid!" name="attackbut" />
																-->
																<input type="submit" value="Mass!" name="attackbut" />
																<input type="submit" value="Balanced!" name="attackbut" />
																<input type="submit" value="Raid!" name="attackbut" />
															</TD>
														</TR>   
														<tr><td colspan="2"><small>Mass: Uses one attack turn, causes a lot of damage and deaths but doesn't steal gold.<br />
																	Balanced Attack: Causes Damage and steals gold.<br />
																	Raid: Causes little or no damage but steals the most gold.
																	</small></td>
														</tr> 
													</TBODY>
												</TABLE>
												<input type="hidden" value="<?=$cgi['id'] ?>" name="id" />
												<input type="hidden" value="<?=$cgi['id'] ?>" name="defender_id2" />
											</FORM>
					<? } elseif ($user->gold < 0) { ?>
									<tr>
										<td colspan=2>
											 [You need to pay back your debt]
										</td>
									</tr>
								</TBODY>
							</TABLE>
					<? } else { ?>
								<tr>
									<td colspan=2>
										 [This player has to pay back debt]
									</td>
								</tr>
							</TBODY>
						</TABLE>
					<? } ?>
		<BR>
		<A
			name=spy></A>
		<FORM name=spyr action=attack.php method=post>
			<INPUT type=hidden
			value=recon name=mission_type>
			<TABLE class=table_lines cellSpacing=0 cellPadding=6 width="100%"
			border=0>
				<TBODY>
					<TR>
						<TH colSpan=2>
							 Reconaissance Mission
						</TH>
					</TR>					
					<TR>
						<TD align=middle colSpan=2>
						<INPUT type=hidden name=numspies value="1" size=3>
							<INPUT onClick="document.spyr.spyrbut.value='Spying..'; document.spyr.spyrbut.disabled=true; document.spyr.submit();" type=submit value="Spy!" name=spyrbut>
						</TD>
					</TR>
				</TBODY>
			</TABLE>
			<INPUT
			type=hidden value="<?=$cgi['id'] ?>" name=defender_id>
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
							<? numecho($user->sasoldiers)?>
						</TD>
					</TR>
					<TR>
						<TD>
							<B>Trained Attack Mercenaries</B>
						</TD>
						<TD align=right>
							<? numecho($user->samercs)?>
						</TD>
					</TR>
					<TR>
						<TD>
							<B>Trained Defense Soldiers</B>
						</TD>
						<TD align=right>
							<? numecho($user->dasoldiers)?>
						</TD>
					</TR>
					<TR>
						<TD>
							<B>Trained Defense Mercenaries</B>
						</TD>
						<TD align=right>
							<? numecho($user->damercs)?>
						</TD>
					</TR>
					<TR>	
						<TD>
							<B>Untrained Soldiers</B>
						</TD>
						<TD align=right>
							<? numecho($user->uu)?>
						</TD>
					</TR>
					<TR>
						<TD class=subh>
							<B>Spies</B>
						</TD>
						<TD class=subh align=right>
							<? numecho($user->spies)?>
						</TD>
					</TR>
					<TD class=subh>
						<B>Special Forces</B>
					</TD>
					<TD class=subh align=right>
						<? numecho($user->specialforces)?>
					</TD>
					</TR>
					<TR>
						<TD>
							<B>Total Fighting Force</B>
						</TD>
						<TD align=right>
							<? numecho(getTotalFightingForce($user))?>
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
							<? numecho($user->SA)?>
						</TD>
						<TD align=right>
							 Ranked&nbsp;<?
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
							<? numecho($user->DA)?>
						</TD>
						<TD align=right>
							 Ranked&nbsp;<?
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
							<? numecho($user->CA)?>
						</TD>
						<TD align=right>
							 Ranked&nbsp;<?
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
							<? numecho($user->RA) ?>
						</TD>
						<TD align=right>
							 Ranked&nbsp;<?
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
		<?include ("bottom.php");?>
		</TD>
		</TR>
		</TBODY>
		</TABLE>
	</BODY>
</HTML>
<? include "gzfooter.php";
?>
