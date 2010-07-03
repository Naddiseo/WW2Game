<? include "gzheader.php";
include ("scripts/vsys.php");
//Upgrade Fortification
$user = getUserDetails($_SESSION['isLogined']);
if ($cgi[upgrade_fortification]) {
	$detail = Upgrade($user, 'fortification', $cgi[upgrade_fortification]);
	header("Location: upgrades.php?strErr=$detail");
}
//Upgrade Siege
if ($cgi[upgrade_siege]) {
	$detail = Upgrade($user, 'siege', $cgi[upgrade_siege]);
	header("Location: upgrades.php?strErr=$detail");
}
if ($cgi[spyupgrade]) {
	$detail = Trainupgrade($user, 'spy');
	header("Location: upgrades.php?strErr=$detail");
}
if ($cgi[$_SESSION[unitupgrade]]) {
	$detail = Trainupgrade($user, 'unit');
	header("Location: upgrades.php?strErr=$detail");
}
if ($cgi['sfupgrade']) {
	$detail = Trainupgrade($user, 'sf');
	header("Location: upgrades.php?strErr=$detail");
}
if ($cgi['ofupgrade']) {
	$detail = Trainupgrade($user, 'of');
	header("Location: upgrades.php?strErr=$detail");
}
if ($cgi['bupgrade']) {
	$detail = Trainupgrade($user, 'b');
	header("Location: upgrades.php?strErr=$detail");
}
if ($cgi['wupgrade']) {
	$detail = Trainupgrade($user, 'w');
	header("Location: upgrades.php?strErr=$detail");
}
if ($cgi['hh']) {
	$detail = Trainupgrade($user, 'hh');
	header("Location: upgrades.php?strErr=$detail");
}
if ($cgi[$_SESSION[maxupgrade]]) {
	$Li = $user->up;
	$gold = $user->gold;
	if ($user->race == 4) {
		$v = 1.17647059; // 10000/8500
		$Lf = 1 / 2 - $v + sqrt($v * ($v - 1) + 0.25 + $Li * (2 * $v + $Li - 1) + $gold / 4250);
		$up = floor($Lf);
		$gold = 10000 * ($Lf - $up) + 4250 * ($Lf * ($Lf - 1) - $up * ($up - 1));
	} else {
		$Lf = sqrt(0.25 + $Li * (1 + $Li) + $gold * 0.0002) - 0.5;
		$up = floor($Lf);
		$gold = 10000 * ($Lf - $up) + 5000 * ($Lf * ($Lf - 1) - $up * ($up - 1));
	}
	updateUser($_SESSION['isLogined'], "up='$up',gold='$gold'");
	$cgi["strErr"] = "Unit Production increased by " . ($up - $Li);
}
/*if($cgi[$_SESSION['maxupgrade']]){
	 $up=$user->up;
	if($user->race!=4){
	 $upcost=$up*10000+10000;
	}else{
		$upcost=$up*8500+10000;
	}
	$gold=$user->gold;
	$count=0;
	//echo $gold." ".$upcost." ".$up;
	while($gold>=$upcost){
		$gold=$gold-$upcost;
		$up=$up+1;
		if($user->race!=4){
	 		$upcost=$up*10000+10000;
		}else{
			$upcost=$up*8500+10000;
		}
		$count++;
  	  
	}
	updateUser($_SESSION['isLogined'],"up=up+'$count',gold='$gold'");
	$cgi["strErr"]="Unit Production increased by $count";
}*/
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" 
>
<HTML>
  <HEAD>
    <TITLE><?=$conf["sitename"]; ?> :: Upgrades</TITLE>
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
            <p>
              <strong><center>
                <font color=red><? echo $cgi["strErr"]; ?></font>
              </center></strong>
            </p>
            <?
include "islogined.php";
?>
            <table>
              <tr>
                <TD >
                  <FORM action=upgrades.php method=post>
                    <TABLE class=table_lines cellSpacing=0 cellPadding=6 width="100%" 
 		border=0>
                      <TBODY>
                        <TR>
                          <TH align=middle colSpan=2>
                             Upgrade Max Officer Limit 
                          </TH>
                        </TR>
                        <TR>
                          <TH class=subh align=left>
                             Current Max Officer Limit 
                          </TH>
                          <TH class=subh>
                             Upgrade 
                          </TH>
                        </TR>
                        <TR>
                          <TD>
                            <? echo $user->maxofficers
?>
                          </TD>
                          <?
if ($user->maxofficers < 15) {
	$pris = pow(2, floor($user->maxofficers / 2)) * 1000;
?>
                          <TD align=middle>
                            <INPUT type=submit size=5 value="<? numecho($pris) ?> Exp" name='ofupgrade'>
                            <INPUT type=hidden value=yes 
 		name=of_upgrade>
                          </TD>
                          <?
} else { ?>
                          <td align="center">[No more upgrades]</td>
                          <?
} ?>
                        </TR>
                      </TBODY>
                    </TABLE>
                  </FORM>
                </td>
                <TD >
                  <FORM action=upgrades.php method=post>
                    <TABLE class=table_lines cellSpacing=0 cellPadding=6 width="100%" 
 		border=0>
                      <TBODY>
                        <TR>
                          <TH align=middle colSpan=2>
                             Upgrade Bank Deposit Percentage 
                          </TH>
                        </TR>
                        <TR>
                          <TH class=subh align=left>
                             Current Percentage 
                          </TH>
                          <TH class=subh>
                             Upgrade 
                          </TH>
                        </TR>
                        <TR>
                          <TD>
                            <? echo $user->bankper
?>
                          </TD>
                          <TD align=middle>
                            <?
if ($user->bankper >= 2) {
	$pris = pow(3, (10 - $user->bankper)) * 1800 + 1500;
?>
                            <INPUT type=submit size=5 value="<? numecho($pris) ?> Exp" name='bupgrade'>
                            <INPUT type=hidden value=yes 
 		name=b_upgrade>
		<?
} else {
?>
                             [No more Upgrades] <?
}
?>
                          </TD>
                        </TR>
                      </TBODY>
                    </TABLE>
                  </FORM>
                </td>
              </tr>
              <tr>
                <TD colspan="2">
                  <FORM action=upgrades.php method=post>
                    <TABLE class=table_lines cellSpacing=0 cellPadding=6 width="100%" 
 		border=0>
                      <TBODY>
                        <TR>
                          <TH align=middle colSpan=2>
                             Upgrade Weapon Sell Percentage 
                          </TH>
                        </TR>
                        <TR>
                          <TH class=subh align=left>
                             Current Percentage 
                          </TH>
                          <TH class=subh>
                             Upgrade 
                          </TH>
                        </TR>
                        <TR>
                          <TD>
                            <?=$user->weapper
?>
                          </TD>
                          <TD align=middle>
                            <?
if ($user->weapper <= 3) {
	$pris = pow(4, $user->weapper) * 1800 + 1500;
?>
                            <INPUT type=submit size=5 value="<? numecho($pris) ?> Exp" name='wupgrade'>
                            <INPUT type=hidden value=yes 
 		name=w_upgrade>
		<?
} else {
?>
                             [No more Upgrades] <?
}
?>
                          </TD>
                        </TR>
                      </TBODY>
                    </TABLE>
                  </FORM>
                </td>
               
              </tr>
              <tr>
                <TD >
                  <FORM action=upgrades.php method=post>
                    <TABLE class=table_lines cellSpacing=0 cellPadding=6 width="100%" 
 		border=0>
                      <TBODY>
                        <TR>
                          <TH align=middle colSpan=2>
                             Upgrade Special Forces Level 
                          </TH>
                        </TR>
                        <TR>
                          <TH class=subh align=left>
                             Current Special Forces Level 
                          </TH>
                          <TH class=subh>
                             Upgrade 
                          </TH>
                        </TR>
                        <TR>
                          <TD>
                            <? echo $user->sflevel
?>
                          </TD>
                          <? $pris = pow(2, $user->sflevel) * 100000 + 100000;
?>
                          <TD align=middle>
                            
                            <INPUT type=submit size=5 value="<? numecho($pris) ?> Gold" name='sfupgrade'>
                            <INPUT type=hidden value=yes 
 		name=sf_upgrade>
                          </TD>
                        </TR>
                      </TBODY>
                    </TABLE>
                  </FORM>
                </td>
                <TD>
                  <FORM action=upgrades.php method=post>
                    <TABLE class=table_lines cellSpacing=0 cellPadding=6 width="100%" border=0>
                      <TBODY>
                        <TR>
                          <TH align=middle colSpan=2>
                             Hand to Hand Training 
                          </TH>
                        </TR>
                        <TR>
                          <TH class=subh align=left>
                             Current Hand to Hand Level 
                          </TH>
                          <TH class=subh>
                             Upgrade 
                          </TH>
                        </TR>
                        <TR>
                          <TD>
                            <? echo $user->hhlevel
?>
                          </TD>
                          <? $pris = pow(2.5, $user->hhlevel) * 125000 + 112500;
?>
                          <TD align=middle>                            
								<INPUT type=submit size=5 value="<? numecho($pris) ?> Gold" name='hh'>
								<INPUT type=hidden value=yes name=hh_upgrade>
						
                          </TD>
                        </TR>
                      </TBODY>
                    </TABLE>
                  </FORM>
                </td>
              </tr>
              <tr>
                <TD>
                  <FORM action=upgrades.php method=post>
                    <TABLE class=table_lines cellSpacing=0 cellPadding=6 width="100%" 
 		border=0>
                      <TBODY>
                        <TR>
                          <TH align=middle colSpan=2>
                             Upgrade Spy Skill 
                          </TH>
                        </TR>
                        <TR>
                          <TH class=subh align=left>
                             Current Spy Skill 
                          </TH>
                          <TH class=subh>
                             Upgrade 
                          </TH>
                        </TR>
                        <tr>
                          <TD>
                             Level <? echo $user->calevel
?>
                            </td>
				<? $pris = pow(2, $user->calevel) * 12000;
?>
                          <TD align=middle>
                            
                            <INPUT type=submit size=5 value="<? numecho($pris) ?> Gold" name=spyupgrade />
                            <INPUT type=hidden value=yes 
 		name=upgrade_spy />
                          </TD>
                        </TR>
                      </TBODY>
                    </TABLE>
                  </FORM>
                </td>
                <TD>
                  <FORM action=upgrades.php method=post>
                    <TABLE class=table_lines cellSpacing=0 cellPadding=6 width="100%" 		border=0>
                      <TBODY>
                        <TR>
                          <TH align=middle colSpan=2>
                             Upgrade Unit Production 
                          </TH>
                        </TR>
                        <TR>
                          <TH class=subh align=left>
                             Current Unit Production 
                          </TH>
                          <TH class=subh>
                             Upgrade 
                          </TH>
                        </TR>
                        <TR>
                          <TD>
                            <? echo $user->up
?>
                             per turn 
                          </TD>
                          <? if ($user->race == 4) {
	$pris = $user->up * 8500 + 10000;
} else {
	$pris = $user->up * 10000 + 10000;
}
?>
                          <TD align=middle>
                           
                            <INPUT type=submit size=5 value="<? numecho($pris) ?> Gold" name=<? $_SESSION[unitupgrade] = genUniqueTxt(10);
echo $_SESSION[unitupgrade]; ?>>
                            <INPUT type=hidden value=yes name=upgrade_prod>
                          </TD>
                        </TR>
                        <tr>
                          <TD>
                           
                            <INPUT type=submit size=5 value="Max" name=<? $_SESSION[maxupgrade] = genUniqueTxt(10);
echo $_SESSION[maxupgrade]; ?>>
                          </TD>
                        </tr>
                      </TBODY>
                    </TABLE>
                  </FORM>
                </td>
              </tr>
              <tr>
                <TD>
                  <FORM action=upgrades.php method=post>
                    <TABLE class=table_lines cellSpacing=0 cellPadding=6 width="100%" 
border=0>
                      <TBODY>
                        <TR>
                          <TH align=middle colSpan=2>
                             Upgrade <span class="subh">Shielding Technology</span>
                          </TH>
                        </TR>
                        <TR>
                          <TH class=subh align=left>
                             Current Shielding Technology 
                          </TH>
                          <TH class=subh width="50%">
                             Upgrade 
                          </TH>
                        </TR>
                        <TR>
                          <? $fl = $conf["race"][$user->race]["fortification"][$user->dalevel]["name"];
if ($conf["race"][$user->race]["fortification"][$user->dalevel + 1]["price"]) {
	$kn = numecho2($conf["race"][$user->race]["fortification"][$user->dalevel + 1]["price"]) . ' Gold' . ' (+25%)';
} else {
	$kn = 'No more upgrades available';
}
?>
                          <TD>
                            <?=$fl
?>
                          </TD>
                          <TD align=middle>
                            <?=$conf["race"][$user->race]["fortification"][$user->dalevel + 1]["name"]
?>
                            <br/>
                           
                            <INPUT type=submit size=5 value="<?=$kn
?>" width="100%" name=upgrade_fortification>
                          </TD>
                        </TR>
                      </TBODY>
                    </TABLE>
                  </FORM>
                </td>
                <TD>
                  <FORM action=upgrades.php method=post>
                    <TABLE class=table_lines cellSpacing=0 cellPadding=6 width="100%" border=0>
                      <TBODY>
                        <TR>
                          <TH align=middle colSpan=2>
                             Upgrade <span class="subh">Weapons Technology</span>
                          </TH>
                        </TR>
                        <TR>
                          <TH class=subh align=left>
                             Current Weapons Technology 
                          </TH>
                          <TH class=subh width="50%">
                             Upgrade 
                          </TH>
                        </TR>
                        <TR>
                          <? $sl = $conf["race"][$user->race]["siege"][$user->salevel]["name"];
if ($conf["race"][$user->race]["siege"][$user->salevel + 1]["price"]) {
	$kn = numecho2($conf["race"][$user->race]["siege"][$user->salevel + 1]["price"]) . ' Gold ' . ' (+25%)';
} else {
	$kn = 'No more upgrades available';
}
?>
                          <TD>
                            <?=$sl
?>
                          </TD>
                          <TD align=middle>
                            <?=$conf["race"][$user->race]["siege"][$user->salevel + 1]["name"]
?>
                            <br>
                           
                            <INPUT type=submit size=5 value="<?=$kn
?>" width="100%" name=upgrade_siege>
                          </TD>
                        </TR>
                      </TBODY>
                    </TABLE>
                  </FORM>
                </td>
              </tr>
            </table>
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
