<? include "gzheader.php";
include "scripts/vsys.php";
//==== vote stuff
if ($cgi['vote'] AND $user->voted == 0) {
	$v = ($cgi['vote'] == 'no' ? '0' : '1');
	mysql_query("UPDATE UserDetails SET voted=1,vote=$v WHERE ID={$user->ID}") or die(mysql_error());
	$user->voted = 1;
}
if ($user->voted) {
	$t = time() - 259200;
	$voteq = mysql_query("SELECT count(*) FROM UserDetails WHERE vote=1 and lastturntime>$t;") or die(mysql_error());
	$votea = mysql_fetch_array($voteq);
	mysql_free_result($voteq);
	$yesvotes = $votea[0];
	$voteq = mysql_query("SELECT count(*) FROM UserDetails WHERE vote=0 AND voted=1 and lastturntime>$t;") or die(mysql_error());
	$votea = mysql_fetch_array($voteq);
	mysql_free_result($voteq);
	$novotes = $votea[0];
	$voteq = mysql_query("SELECT count(*) FROM UserDetails WHERE lastturntime>$t;") or die(mysql_error());
	$votea = mysql_fetch_array($voteq);
	mysql_free_result($voteq);
	$activevotes = $votea[0];
}
//======end vote
if ($cgi['kick'] == 'Kick' and (int)$cgi['ofid'] > 0) {
	mysql_query("UPDATE UserDetails SET commander=0,accepted=0 WHERE ID=$cgi[ofid]") or die(mysql_error());
}
//print_r($cgi); // dont show that many info to the users plz :)   I only have these turned on in dev
if ($cgi['accept'] == 'Accept' and (int)$cgi['ofid'] > 0) {
	//echo "here";
	mysql_query("UPDATE UserDetails SET accepted=1 WHERE ID=$cgi[ofid]") or die(mysql_error());
}
if ($cgi['clickall'] AND $user->clickall == 0 AND $_SESSION['isLogined']) {
	$user->clickall = 1;
	mysql_query("UPDATE UserDetails SET uu=uu+10,gclick=gclick-1 WHERE gclick>0 AND active=1 AND id!=$_SESSION[isLogined]");
	$gc = ($user->gclick >= 15) ? 0 : 1;
	updateUser($user->ID, "clickall=1,gclick=gclick+$gc");
}
if ($cgi['leave']) {
	updateUser($user->ID, "commander=0,accepted=0");
}
if ($cgi['cd'] and checkD($user->email)) {
	updateUser($_SESSION['isLogined'], 'supporter=1');
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"
>
<HTML>
    <HEAD>
        <TITLE><? echo $conf["sitename"]; ?> ::<? echo $user->userName; ?>'s Camp</TITLE>
        <META http-equiv=Content-Type content="text/html; charset=iso-8859-1">
        <LINK href="css/common.css" type=text/css rel=stylesheet>
        <script language="javascript" type="text/javascript" src="prototype.js"></script>
        <script language="javascript" type="text/javascript" src="javafunctions.js"></script>
        
        <SCRIPT language=javascript type=text/javascript>
		<!--
		function checkCR(evt) {
		var evt = (evt) ? evt : ((event) ? event : null);
		var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
		if ((evt.keyCode == 13) && (node.type=="text")) {return false;}
		}
		document.onkeypress = checkCR;
                
                function Confoff(nam){
	           if(! confirm("Are you sure you want to kick " + nam + "?"))
	           {	 return false;	}

	           return true;
                }
        
		//-->
		</SCRIPT>
        <META content="MSHTML 5.50.4522.1800" name=GENERATOR>
    </HEAD>
    <BODY text=#ffffff bgColor=#000000 leftMargin=0 topMargin=0 marginheight="0" 
marginwidth="0" onload="gm(<?=$user->ID ?>);">
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
                        <?
include "islogined.php";
?>
                        <TABLE width="100%">
                            <TBODY>
                                <TR>
                                    <TD style="PADDING-RIGHT: 25px" vAlign=top width="50%">
                                        <TABLE class=table_lines cellSpacing=0 cellPadding=6 width="100%" 
            border=0>
                                            <TBODY>
											<tr>			
												
                                                <TR>
                                                    <TH class=subh align=middle colSpan=2>
                                                         User Info
                                                    </TH>
                                                </TR>
                                                <TR>
                                                    <TD>
                                                        <B>Name</B>
                                                    </TD>
                                                    <TD>
                                                        <?=$user->userName
?>
                                                         <? if ($user->supporter > 0) {
	echo "{Supporter}";
} ?>
                                                    </TD>
                                                </TR>
                                                <?
//                                                 if($user->voted!=1 AND false==true){
//
 ?>
                                              <!-- <tr>
//                                                 	<td>Public Vote: Should nukes be removed next round?</td>
//                                                 	<td><form>
//                                                 		<select name="vote">
//                                                 		<option value="no">No</option>
//                                                 		<option value="yes">Yes</option>
//                                                 		</select>
//                                                 		<input type="submit" value="Vote" />
//                                                 	</form></td>
//                                                 </tr>-->
                                                <?
//}else{

?>
                                                	<!--<tr>
                                               		<td>Vote Results</td>
                                                		<td>Yes: <? //$yesvotes
 ?>/<? //$activevotes
 ?>&nbsp;&nbsp;No: <? //$novotes
 ?>/<? //$activevotes
 ?></td>
                                               	</tr>-->
                                                <?
//}

?>
                                                <tr><td><b>Alliance</b></td>
                                                <td><a href="alliance.php">
                                                <?
if ($user->alliance > 0) {
	$qal = mysql_query("SELECT name,up,bunkers FROM alliances WHERE ID='" . $user->alliance . "'") or die(mysql_error());
	$alliance_stuff = mysql_fetch_array($qal, MYSQL_ASSOC);
	echo (($alliance_stuff['name'] != '') ? $alliance_stuff['name'] . ($user->aaccepted == 0 ? '&nbsp;<a href="alliance.php?leave=true"">Leave<a>' : '') : "None");
} else {
	echo "None";
}
?></a>                                            
                                                </td>
                                                </tr>
                                                <TR>
                                                    <TD>
                                                        <B>E-mail</B>
                                                    </TD>
                                                    <TD>
                                                        <?=$user->email
?>
                                                    </TD>
                                                </TR>
                                                <TR>
                                                    <TD>
                                                        <B>Nation</B>
                                                    </TD>
                                                    <TD>
                                                        <?=$conf["race"][$user->race]["name"]
?>
                                                    </TD>
                                                </TR>                                              
                                                <TR>
                                                    <TD>
                                                        <B>Rank</B>
                                                    </TD>
                                                    <TD>
                                                        <? numecho($userR->rank)
?><!--&nbsp;in&nbsp;<?
//echo getarea($user->area);

?>-->
                                                    </TD>
                                                </TR>
                                                <TR>
                                                    <TD>
                                                        <B>Commander</B>
                                                    </TD>
                                                    <TD>
                                                        <?
if ($user->commander) {
	$userC = getUserDetails($user->commander, 'userName,active');
	if ($userC->active == 1) {
		echo "<a href='stats.php?id=" . $user->commander . "'>" . $userC->userName . "</a>";
		if ($user->accepted == 0) {
			echo "[Not Accepted]";
		}
		echo "&nbsp;<a href='base.php?leave=commander'>Leave</a>&nbsp;";
	} else {
		echo "None";
	}
} else {
	echo "None";
}
?>
                                                    </TD>
                                                </TR>
                                                <TR>
                                                    <TD>
                                                        <B>Shielding Technology</B>
                                                    </TD>
                                                    <TD>
                                                        <?=$conf["race"][$user->race]["fortification"][$user->dalevel]["name"]
?>
                                                    </TD>
                                                </TR>
                                                <TR>
                                                    <TD>
                                                        <B>Weapons Technology</B>
                                                    </TD>
                                                    <TD>
                                                        <?=$conf["race"][$user->race]["siege"][$user->salevel]["name"]
?>
                                                    </TD>
                                                </TR>
                                                <TR>
                                                    <TD>
                                                        <B>Covert Skill</B>
                                                    </TD>
                                                    <TD>
                                                        <?=$user->calevel
?>
                                                    </TD>
                                                </TR>
                                                <TR>
                                                    <TD>
                                                        <B>Special Forces Level</B>
                                                    </TD>
                                                    <TD>
                                                        <?=$user->sflevel
?>
                                                    </TD>
                                                </TR>                                                                                            
                                                <TR>
                                                    <TD>
                                                        <B>Unit Production</B>
                                                    </TD>
                                                    <TD>
                                                        <? numecho($user->up)
?> per turn (+ <? numecho(($user->accepted ? $user->officerup : 0));
echo " +";
numecho(($user->aaccepted ? $alliance_stuff['up'] : 0));
?>
                                                         )
                                                    </TD>
                                                </TR>
                                                <TR>
                                                    <TD>
                                                        <B>Available Funds</B>
                                                    </TD>
                                                    <TD>
                                                        <? numecho($user->gold);
?>
                                                         Gold
                                                    </TD>
                                                </TR>
                                                <TR>
                                                    <TD>
                                                        <B>Experience</B>
                                                    </TD>
                                                    <TD>
                                                        <? numecho($user->exp);
?>
                                                    </TD>
                                                </TR>
                                              
                                                <TR>
                                                    <TD>
                                                        <B>Projected Income Next Turn</B>
                                                    </TD>
                                                    <TD>
                                                        <? numecho(getUserIncome($user));
?>
                                                         (+<? numecho($user->commandergold);
?>
                                                         ) Gold (in&nbsp;<?
$temp = explode(":", $nextTurnMin = getNextTurn($user));
echo $min = $temp[0];
?>
                                                         minutes)
                                                    </TD>
                                                </TR>
                                                <TR>
                                                    <TD>
                                                        <B>Attack Turns</B>
                                                    </TD>
                                                    <TD>
                                                        <? numecho($user->attackturns)
?>
                                                    </TD>
                                                </TR>
                                           
                                                <TR>
                                                    <TD >
                                                        <b>Click Credits</b>
                                                    </TD>
                                                    <td>
                                                        <?=$user->gclick; ?>
                                                   </td>                                                    
                                                </TR>
                                            </TBODY>
                                        </TABLE>
                                        <BR>
                                        <BR>
                                        <TABLE class=table_lines cellSpacing=0 cellPadding=6 width="100%" 
            border=0>
                                            <TBODY>
                                                <TR>
                                                    <TH class=subh align=middle colSpan=6>
                                                         Officers <? $officersC = getOfficersCount($user->ID); ?>
                                                    </TH>
                                                </TR>
                                                <? if ($officersC) { ?>
                                                <tr>
                                                    <TD>
                                                        <form action="writemail.php" method="POST">
                                                            <input type="hidden" name="to" value="msgoff">
                                                            <input type="submit" name="msgoff" value="Message All Officers">
                                                        </form>
                                                    </TD>
                                               </tr>
                                               <?
} ?>
                                                <TR>
                                                    <TH align=left>
                                                         Name
                                                    </TH>
                                                    <TH >&nbsp;
                                                         
                                                    </TH>
                                                    <TH align=left>
                                                         Army
                                                    </TH>
                                                    <TH align=right>
                                                         Rank
                                                    </TH>
                                                       <th align="right">
                                                    Last Active
                                                    </th>
                                                    <th align="right">
                                                         Kick/Accept
                                                    </th>
                                                </TR>
                                                <?
if ($officersC) {
	$pCount = $officersC / $conf["users_per_page"];
	$pCountF = floor($pCount);
	$pCountF+= (($pCount > $pCountF) ? 1 : 0);
	if (!$cgi['page']) {
		$cgi['page'] = 1;
	}
	$officers = getOfficers($user->ID, $cgi['page']);
	for ($i = 0;$i < count($officers);$i++) {
?>
                                                <tr>
                                                    <td>
                                                        <a href="stats.php?id=<?=$officers[$i]->userID ?>"><?=$officers[$i]->userName ?>
                                                        </a>
                                                    </td>
                                                    <td align="right">
                                                        <? numecho(getTotalFightingForce($officers[$i]));
?>
                                                    </td>
                                                    <td align="left">
                                                        <?=$conf["race"][$officers[$i]->race]["name"]
?>
                                                    </td>
                                                    <td align="right">
                                                        <? numecho($officers[$i]->rank);
?>
                                                    </td>
                                                      <td align="right">
                                                    <?=duration(time() - $officers[$i]->lastturntime) ?>
                                                    </td>
                                                    <td align="right">
                                                        <?
		if ($officers[$i]->accepted == 0) {
?>
                                                        <form action="base.php" method="POST" >
                                                            <input type=hidden name=ofid value="<?=$officers[$i]->userID ?>" />
                                                            <input type=submit name='accept' value='Accept' />
                                                        </form>
                                                        <?
		}
?>
                                                        <form action="base.php" method="POST" onSubmit="return Confoff('<?=$officers[$i]->userName ?>');">
                                                            <?
		//$officers[$i]->userName
		
?>
                                                            <input type=hidden name=ofid value="<?=$officers[$i]->userID ?>" />
                                                            <input name="kick" value="Kick" type=Submit />
                                                        </form>
                                                    </td>
                                                </tr>
                                                <?
	}
} else {
?>
                                                <TR>
                                                    <TD align=middle colSpan=6>
                                                         No Officers
                                                    </TD>
                                                </TR>
                                                <?
}
?>
                                                <TR>
                                                    <TD>
                                                        <?
if ($cgi['page'] > 1) {
	echo "<A href='base.php?page=" . ($cgi['page'] - 1) . "&id=" . $user->ID . "'>&lt;&lt; Prev</A>";
} else {
	echo "&nbsp;";
}
?>
                                                    </TD>
                                                    <TD align=middle colSpan=4>
                                                        <? numecho($officersC)
?>
                                                         officers total | page&nbsp;<?=$cgi['page']
?>
                                                         of&nbsp;<? numecho($pCountF);
?>
                                                    </TD>
                                                    <TD>
                                                        <?
if ($cgi['page'] < $pCountF) {
	echo '<A href="base.php?page=' . ($cgi['page'] + 1) . '&id=' . $user->ID . '">Next &gt;&gt;</A>';
} else {
	echo "&nbsp;";
}
?>
                                                    </TD>
                                                </TR>
                                            </TBODY>
                                        </TABLE>
                                    </TD>
                                    <TD vAlign=top>
                                        <TABLE class=table_lines cellSpacing=0 cellPadding=6 width="100%" border=0>
                                            <TBODY>
                                                <TR>
                                                    <TH class=subh colSpan=3>
                                                         Military Effectiveness
                                                    </TH>
                                                </TR>
                                                <TR>
                                                    <TD>
                                                        <B>Strike Action</B>
                                                    </TD>
                                                    <TD align=right>
                                                        <? numecho($user->SA)
?>
                                                    </TD>
                                                    <TD align=left>
                                                         Ranked <?
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
                                                        <? numecho($user->DA)
?>
                                                    </TD>
                                                    <TD align=left>
                                                         Ranked <?
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
                                                    <TD align='right'>
                                                        <? numecho($user->CA)
?>
                                                    </TD>
                                                    <TD align='left'>
                                                         Ranked <?
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
                                                        <? numecho($user->RA)
?>
                                                    </TD>
                                                    <TD align=left>
                                                         Ranked <?
if ($userR->rarank) {
	numecho($userR->rarank);
} else echo "#unranked";
?>
                                                    </TD>
                                                </TR>
                                            </TBODY>
                                        </TABLE>
                                        <BR>
                                        <BR>
                                        <TABLE class=table_lines cellSpacing=0 cellPadding=6 width="100%" 
            border=0>
                                            <TBODY>
                                                <TR>
                                                    <TH class=subh colSpan=2>
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
                                                <TR>
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
                                        <BR>
                                        <TABLE class=table_lines cellSpacing=0 cellPadding=6 width="100%" 
            border=0>
                                            <TBODY>
                                                <TR>
                                                    <TH class=subh>
                                                         Preferences
                                                    </TH>
                                                </TR>
                                                <TR>
                                                    <TD align=middle>
                                                        <A 
                  href="paypal.php">Donate to the Site</A>
                                                    </TD>
                                                </TR>
                                                <TR>
                                                    <TD align=middle>
                                                        <A 
                  href="prefs.php">Preferences</A>
                                                    </TD>
                                                </TR>
												 <TR>
                                                    <TD align=middle>
                                                        <A 
                  href="changenick.php">Change Name</A>
                                                    </TD>
                                                </TR>
                                                <TR>
                                                    <TD align=middle>
                                                        <A 
                  href="reset.php">Reset Account / Change Race</A>
                                                    </TD>
                                                </TR>
                                                <TR>
                                                    <script>
					function deleteAccount(){
					if (confirm("Are you sure you want to delete your account?")){
						document.location="delete.php";
					}
					}
				</script>
                                                    <TD align=middle>
                                                        <A 
                  href="javascript:deleteAccount();" >Delete Account</A>
                                                    </TD>
                                                </TR>
                                                <? if ($_SESSION['admin']) {
	if ($cgi['adminbtn']) {
		$act = 0;
		$r = mysql_real_escape_string($cgi['reason']);
		switch ($cgi['btype']) {
			default:
			case 'unban':
				$act = 1;
				$r = '';
			break;
			case 'ban':
				$act = 4;
			break;
			case 'suspect':
				$act = 3;
			break;
			case 'vacation':
				$act = 2;
			break;
		}
		mysql_query("UPDATE UserDetails SET reason=\"$r\",active=$act WHERE id={$user->ID}") or die(mysql_error());
	}
?>
                                                  <tr><td>
													<form method="POST">
														<select name="btype">
															<option value="unban">Unban</option>
															<option value="ban">Ban</option>
															<option value="suspect">Suspected Cheat</option>
															<option value="vaction">Vaction</option>
														</select>
														<input type="text" name="reason" />
														<input type="submit" name="adminbtn" />
													</form>
                                                  </td></tr>
													Active:<?=$user->active
?><br />
													Reason:<?=$user->reason
?>
                                                <?
} ?>
                                            </TBODY>
                                        </TABLE>
                                        <P>
                                            <INPUT type=hidden value=email name=do>
                                            </form>
                                        </P>
                                    </TD>
                                </TR>
                            </TBODY>
                        </TABLE>
                        <P>
                            <TABLE class=table_lines cellSpacing=0 cellPadding=6 width="100%" 
border=0>
                                <TBODY>
                                    <!--<TR>
                                        <TH class=subh align=middle>
                                             Recruiting
                                        </TH>
                                    </TR>
                                    <TR>
                                        <TD>
                                             Give this link to your friends to recruit them as officers in your army. DO NOT post this link in chat rooms, newsgroups, message boards, or forums unless it is <B>explicitly</B> allowed by the owner/operator of the chat room, newsgroup, message board, forum, etc.. DO NOT e-mail this link to people whom you do not know, or to e-mail lists or "reflectors". You may post this link on a personal website, or in your AIM profile.
                                        </TD>
                                    </TR>
                                    <TR>
                                        <TD align=middle>
                                            <A 
                                            
            href="http://ww2game.net/recruit.php?uniqid=<?
//$user->ID

?>">http://ww2game.net/recruit.php?uniqid=<?
//$user->ID

?></A>
                                        </TD>
                                    </TR>-->
                                    <? if ($user->clickall == 0) { ?>
                                    <tr><td><form method="POST">
                                    <input type="submit" name="clickall" value="Global Click" /><br>
                                    <small>Adds 10 soldiers to everyone.</small>
                                    </form></td></tr>
                                    <?
} ?>
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

<? include "gzfooter.php"; ?>
