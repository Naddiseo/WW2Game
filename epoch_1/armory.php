<? include "gzheader.php";
include "scripts/vsys.php";
// Repair weapons
if ($user->supporter > 0 and $cgi['maxall']) {
	RepairWeaponMax(0, $user, 0);
	$user = getUserDetails($_SESSION['isLogined']);
	RepairWeaponMax(0, $user, 1);
	$user = getUserDetails($_SESSION['isLogined']);
	RepairWeaponMax(0, $user, 2);
	$user = getUserDetails($_SESSION['isLogined']);
	RepairWeaponMax(0, $user, 3);
	$user = getUserDetails($_SESSION['isLogined']);
	RepairWeaponMax(0, $user, 4);
	$user = getUserDetails($_SESSION['isLogined']);
	RepairWeaponMax(0, $user, 5);
	$user = getUserDetails($_SESSION['isLogined']);
	RepairWeaponMax(0, $user, 6);
	$user = getUserDetails($_SESSION['isLogined']);
	RepairWeaponMax(0, $user, 7);
	$user = getUserDetails($_SESSION['isLogined']);
	RepairWeaponMax(0, $user, 8);
	$user = getUserDetails($_SESSION['isLogined']);
	RepairWeaponMax(0, $user, 9);
	$user = getUserDetails($_SESSION['isLogined']);
	RepairWeaponMax(1, $user, 0);
	$user = getUserDetails($_SESSION['isLogined']);
	RepairWeaponMax(1, $user, 1);
	$user = getUserDetails($_SESSION['isLogined']);
	RepairWeaponMax(1, $user, 2);
	$user = getUserDetails($_SESSION['isLogined']);
	RepairWeaponMax(1, $user, 3);
	$user = getUserDetails($_SESSION['isLogined']);
	RepairWeaponMax(1, $user, 4);
	$user = getUserDetails($_SESSION['isLogined']);
	RepairWeaponMax(1, $user, 5);
	$user = getUserDetails($_SESSION['isLogined']);
	RepairWeaponMax(1, $user, 6);
	$user = getUserDetails($_SESSION['isLogined']);
	RepairWeaponMax(1, $user, 7);
	$user = getUserDetails($_SESSION['isLogined']);
	RepairWeaponMax(1, $user, 8);
	$user = getUserDetails($_SESSION['isLogined']);
	RepairWeaponMax(1, $user, 9);
	$user = getUserDetails($_SESSION['isLogined']);
}
if ($cgi[doatrepair]) {
	if ($cgi[atrepair0]) {
		$wepid = 0;
		$wal = $cgi[atrepair0];
	}
	if ($cgi[atrepair1]) {
		$wepid = 1;
		$wal = $cgi[atrepair1];
	}
	if ($cgi[atrepair2]) {
		$wepid = 2;
		$wal = $cgi[atrepair2];
	}
	if ($cgi[atrepair3]) {
		$wepid = 3;
		$wal = $cgi[atrepair3];
	}
	if ($cgi[atrepair4]) {
		$wepid = 4;
		$wal = $cgi[atrepair4];
	}
	if ($cgi[atrepair5]) {
		$wepid = 5;
		$wal = $cgi[atrepair5];
	}
	if ($cgi[atrepair6]) {
		$wepid = 6;
		$wal = $cgi[atrepair6];
	}
	if ($cgi[atrepair7]) {
		$wepid = 7;
		$wal = $cgi[atrepair7];
	}
	if ($cgi[atrepair8]) {
		$wepid = 8;
		$wal = $cgi[atrepair8];
	}
	if ($cgi[atrepair9]) {
		$wepid = 9;
		$wal = $cgi[atrepair9];
	}
	$detail = RepairWeapon($wepid, $wal, $user, 1);
	header("Location: armory.php?strErr=$detail");
}
if ($cgi[doatrepairmax]) {
	if ($cgi[doatrepairmax_points0]) {
		$wepid = 0;
		$wal = $cgi[doatrepairmax_points0];
	}
	if ($cgi[doatrepairmax_points1]) {
		$wepid = 1;
		$wal = $cgi[doatrepairmax_points1];
	}
	if ($cgi[doatrepairmax_points2]) {
		$wepid = 2;
		$wal = $cgi[doatrepairmax_points2];
	}
	if ($cgi[doatrepairmax_points3]) {
		$wepid = 3;
		$wal = $cgi[doatrepairmax_points3];
	}
	if ($cgi[doatrepairmax_points4]) {
		$wepid = 4;
		$wal = $cgi[doatrepairmax_points4];
	}
	if ($cgi[doatrepairmax_points5]) {
		$wepid = 5;
		$wal = $cgi[doatrepairmax_points5];
	}
	if ($cgi[doatrepairmax_points6]) {
		$wepid = 6;
		$wal = $cgi[doatrepairmax_points6];
	}
	if ($cgi[doatrepairmax_points7]) {
		$wepid = 7;
		$wal = $cgi[doatrepairmax_points7];
	}
	if ($cgi[doatrepairmax_points8]) {
		$wepid = 8;
		$wal = $cgi[doatrepairmax_points8];
	}
	if ($cgi[doatrepairmax_points9]) {
		$wepid = 9;
		$wal = $cgi[doatrepairmax_points9];
	}
	$detail = RepairWeapon($wepid, $wal, $user, 1);
	header("Location: armory.php?strErr=$detail");
}
if ($cgi[dodefrepair]) {
	if ($cgi[defrepair0]) {
		$wepid = 0;
		$wal = $cgi[defrepair0];
	}
	if ($cgi[defrepair1]) {
		$wepid = 1;
		$wal = $cgi[defrepair1];
	}
	if ($cgi[defrepair2]) {
		$wepid = 2;
		$wal = $cgi[defrepair2];
	}
	if ($cgi[defrepair3]) {
		$wepid = 3;
		$wal = $cgi[defrepair3];
	}
	if ($cgi[defrepair4]) {
		$wepid = 4;
		$wal = $cgi[defrepair4];
	}
	if ($cgi[defrepair5]) {
		$wepid = 5;
		$wal = $cgi[defrepair5];
	}
	if ($cgi[defrepair6]) {
		$wepid = 6;
		$wal = $cgi[defrepair6];
	}
	if ($cgi[defrepair7]) {
		$wepid = 7;
		$wal = $cgi[defrepair7];
	}
	if ($cgi[defrepair8]) {
		$wepid = 8;
		$wal = $cgi[defrepair8];
	}
	if ($cgi[defrepair9]) {
		$wepid = 9;
		$wal = $cgi[defrepair9];
	}
	$detail = RepairWeapon($wepid, $wal, $user, 0);
	header("Location: armory.php?strErr=$detail");
}
if ($cgi[dodefrepairmax]) {
	if ($cgi[dodefrepairmax_points0]) {
		$wepid = 0;
		$wal = $cgi[dodefrepairmax_points0];
	}
	if ($cgi[dodefrepairmax_points1]) {
		$wepid = 1;
		$wal = $cgi[dodefrepairmax_points1];
	}
	if ($cgi[dodefrepairmax_points2]) {
		$wepid = 2;
		$wal = $cgi[dodefrepairmax_points2];
	}
	if ($cgi[dodefrepairmax_points3]) {
		$wepid = 3;
		$wal = $cgi[dodefrepairmax_points3];
	}
	if ($cgi[dodefrepairmax_points4]) {
		$wepid = 4;
		$wal = $cgi[dodefrepairmax_points4];
	}
	if ($cgi[dodefrepairmax_points5]) {
		$wepid = 5;
		$wal = $cgi[dodefrepairmax_points5];
	}
	if ($cgi[dodefrepairmax_points6]) {
		$wepid = 6;
		$wal = $cgi[dodefrepairmax_points6];
	}
	if ($cgi[dodefrepairmax_points7]) {
		$wepid = 7;
		$wal = $cgi[dodefrepairmax_points7];
	}
	if ($cgi[dodefrepairmax_points8]) {
		$wepid = 8;
		$wal = $cgi[dodefrepairmax_points8];
	}
	if ($cgi[dodefrepairmax_points9]) {
		$wepid = 9;
		$wal = $cgi[dodefrepairmax_points9];
	}
	$detail = RepairWeapon($wepid, $wal, $user, 0);
	header("Location: armory.php?strErr=$detail");
}
// Sell weapons
if ($cgi[doatscrapsell]) {
	if ($cgi[atscrapsell0]) {
		$wepid = 0;
		$wal = $cgi[atscrapsell0];
	}
	if ($cgi[atscrapsell1]) {
		$wepid = 1;
		$wal = $cgi[atscrapsell1];
	}
	if ($cgi[atscrapsell2]) {
		$wepid = 2;
		$wal = $cgi[atscrapsell2];
	}
	if ($cgi[atscrapsell3]) {
		$wepid = 3;
		$wal = $cgi[atscrapsell3];
	}
	if ($cgi[atscrapsell4]) {
		$wepid = 4;
		$wal = $cgi[atscrapsell4];
	}
	if ($cgi[atscrapsell5]) {
		$wepid = 5;
		$wal = $cgi[atscrapsell5];
	}
	if ($cgi[atscrapsell6]) {
		$wepid = 6;
		$wal = $cgi[atscrapsell6];
	}
	if ($cgi[atscrapsell7]) {
		$wepid = 7;
		$wal = $cgi[atscrapsell7];
	}
	if ($cgi[atscrapsell8]) {
		$wepid = 8;
		$wal = $cgi[atscrapsell8];
	}
	if ($cgi[atscrapsell9]) {
		$wepid = 9;
		$wal = $cgi[atscrapsell9];
	}
	ScrapSell($wepid, $wal, $cgi[doscrapsell], $user, 1);
}
if ($cgi[dodefscrapsell]) {
	if ($cgi[defscrapsell0]) {
		$wepid = 0;
		$wal = $cgi[defscrapsell0];
	}
	if ($cgi[defscrapsell1]) {
		$wepid = 1;
		$wal = $cgi[defscrapsell1];
	}
	if ($cgi[defscrapsell2]) {
		$wepid = 2;
		$wal = $cgi[defscrapsell2];
	}
	if ($cgi[defscrapsell3]) {
		$wepid = 3;
		$wal = $cgi[defscrapsell3];
	}
	if ($cgi[defscrapsell4]) {
		$wepid = 4;
		$wal = $cgi[defscrapsell4];
	}
	if ($cgi[defscrapsell5]) {
		$wepid = 5;
		$wal = $cgi[defscrapsell5];
	}
	if ($cgi[defscrapsell6]) {
		$wepid = 6;
		$wal = $cgi[defscrapsell6];
	}
	if ($cgi[defscrapsell7]) {
		$wepid = 7;
		$wal = $cgi[defscrapsell7];
	}
	if ($cgi[defscrapsell8]) {
		$wepid = 8;
		$wal = $cgi[defscrapsell8];
	}
	if ($cgi[defscrapsell9]) {
		$wepid = 9;
		$wal = $cgi[defscrapsell9];
	}
	ScrapSell($wepid, $wal, $cgi[doscrapsell], $user, 0);
}
//buy weapons
if ($cgi[buybut] and ($user->gold >= 0)) {
	$user = getUserDetails($_SESSION['isLogined']);
	if ($cgi[buy_w0]) {
		$detail = BuyWeapon(0, $cgi[buy_w0], 1, $user);
		$user = getUserDetails($_SESSION['isLogined']);
	}
	if ($cgi[buy_w1]) {
		$detail = BuyWeapon(1, $cgi[buy_w1], 1, $user);
		$user = getUserDetails($_SESSION['isLogined']);
	}
	if ($cgi[buy_w2]) {
		$detail = BuyWeapon(2, $cgi[buy_w2], 1, $user);
		$user = getUserDetails($_SESSION['isLogined']);
	}
	if ($cgi[buy_w3]) {
		$detail = BuyWeapon(3, $cgi[buy_w3], 1, $user);
		$user = getUserDetails($_SESSION['isLogined']);
	}
	if ($cgi[buy_w4]) {
		$detail = BuyWeapon(4, $cgi[buy_w4], 1, $user);
		$user = getUserDetails($_SESSION['isLogined']);
	}
	if ($cgi[buy_w5]) {
		$detail = BuyWeapon(5, $cgi[buy_w5], 1, $user);
		$user = getUserDetails($_SESSION['isLogined']);
	}
	if ($cgi[buy_w6]) {
		$detail = BuyWeapon(6, $cgi[buy_w6], 1, $user);
		$user = getUserDetails($_SESSION['isLogined']);
	}
	if ($cgi[buy_w7]) {
		$detail = BuyWeapon(7, $cgi[buy_w7], 1, $user);
		$user = getUserDetails($_SESSION['isLogined']);
	}
	if ($cgi[buy_w8]) {
		$detail = BuyWeapon(8, $cgi[buy_w8], 1, $user);
		$user = getUserDetails($_SESSION['isLogined']);
	}
	if ($cgi[buy_w9]) {
		$detail = BuyWeapon(9, $cgi[buy_w9], 1, $user);
		$user = getUserDetails($_SESSION['isLogined']);
	}
	if ($cgi[buy_dw0]) {
		$detail = BuyWeapon(0, $cgi[buy_dw0], 0, $user);
		$user = getUserDetails($_SESSION['isLogined']);
	}
	if ($cgi[buy_dw1]) {
		$detail = BuyWeapon(1, $cgi[buy_dw1], 0, $user);
		$user = getUserDetails($_SESSION['isLogined']);
	}
	if ($cgi[buy_dw2]) {
		$detail = BuyWeapon(2, $cgi[buy_dw2], 0, $user);
		$user = getUserDetails($_SESSION['isLogined']);
	}
	if ($cgi[buy_dw3]) {
		$detail = BuyWeapon(3, $cgi[buy_dw3], 0, $user);
		$user = getUserDetails($_SESSION['isLogined']);
	}
	if ($cgi[buy_dw4]) {
		$detail = BuyWeapon(4, $cgi[buy_dw4], 0, $user);
		$user = getUserDetails($_SESSION['isLogined']);
	}
	if ($cgi[buy_dw5]) {
		$detail = BuyWeapon(5, $cgi[buy_dw5], 0, $user);
		$user = getUserDetails($_SESSION['isLogined']);
	}
	if ($cgi[buy_dw6]) {
		$detail = BuyWeapon(6, $cgi[buy_dw6], 0, $user);
		$user = getUserDetails($_SESSION['isLogined']);
	}
	if ($cgi[buy_dw7]) {
		$detail = BuyWeapon(7, $cgi[buy_dw7], 0, $user);
		$user = getUserDetails($_SESSION['isLogined']);
	}
	if ($cgi[buy_dw8]) {
		$detail = BuyWeapon(8, $cgi[buy_dw8], 0, $user);
		$user = getUserDetails($_SESSION['isLogined']);
	}
	if ($cgi[buy_dw9]) {
		$detail = BuyWeapon(9, $cgi[buy_dw9], 0, $user);
	}
	$user = getUserDetails($_SESSION['isLogined'], "*");
	header("Location: armory.php?strErr=$detail");
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
    <HEAD>
        <TITLE><?=$conf["sitename"]; ?> :: Armory</TITLE>
        <META http-equiv=Content-Type content="text/html; charset=iso-8859-1">
        <!-- ZoneLabs Privacy Insertion --><SCRIPT language=javascript src="js/js"></SCRIPT>
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
    vAlign=top align=left> <? include "islogined.php"; ?>
                        <BR>
                        <H3>
                             Armory
                        </H3>
                        <P>
                            <strong><center>
                                <font color=red><? echo $cgi["strErr"]; ?></font>
                            </center></strong>
                        </p>
                        <TABLE class=table_lines cellSpacing=0 cellPadding=6 width="100%" border=0>
                            <TBODY>
                                <TR>
                                    <TH align=middle colSpan=6>
                                         Current Weapon Inventory
                                    </TH>
                                </TR>
                                <TR>
                                    <TH class=subh align=left>
                                         Attack Weapons
                                    </TH>
                                    <TH class=subh align=right>
                                         Quantity
                                    </TH>
                                    <TH class=subh>
                                         Strength
                                    </TH>
                                    <TH class=subh>
                                         Repair
                                    </TH>
                                    <TH class=subh>
                                         Scrap/Sell
                                    </TH>
                                </TR>
                                <? $wep = getUserWeapon($user);
$totalWCount = 0;
for ($i = 0;$i < count($wep);$i++) {
	$totalWCount+= $wep[$i]->weaponCount;
	printf("<TR><TD>%s</TD>", $conf["race"][$user->race]["weapon"][$wep[$i]->weaponID]["name"]);
?>
                                <TD align=right>
                                    <? numecho($wep[$i]->weaponCount)
?>
                                </TD>
                                <? $x = $wep[$i]->weaponStrength;
	$y = $conf["weapon{$wep[$i]->weaponID}strength"];
	$torepair = $torepairm = $y - $x;
	$cost = $costm = round($conf["weapon{$wep[$i]->weaponID}pp"] * $wep[$i]->weaponCount * $torepair);
	if ($cost > $user->gold) {
		$torepairm = floor($user->gold / ($conf["weapon{$wep[$i]->weaponID}pp"] * $wep[$i]->weaponCount));
		$costm = floor($conf["weapon{$wep[$i]->weaponID}pp"] * $wep[$i]->weaponCount * $torepair);
	}
?>
                                <TD align=middle>
                                    <? numecho($x)
?>
                                     /<? numecho($y)
?>
                                </TD>
                                <FORM action=armory.php method=post>
                                    <TD>
                                    
                                        <TABLE cellSpacing=0 cellPadding=2 width="100%" border=0>
                                            <TBODY>
                                                <TR>
                                                    <? $rem = round($y) * $wep[$i]->weaponCount;
	$id = $wep[$i]->weaponID;
?>
                                                    <TD style="BORDER-BOTTOM: medium none">
                                                        <INPUT type=input value= "<? echo ($torepair); ?>" maxLength=6 size=4 value=0 name=atrepair<? echo $id; ?> >
                                                    </TD>
                                                    <TD style="BORDER-BOTTOM: medium none" width="75%">
                                                        <INPUT style="WIDTH: 100%" type=submit value="<? numecho($conf["weapon{$wep[$i]->weaponID}pp"] * $wep[$i]->weaponCount) ?> Gold/Point" name=doatrepair>
                                                    </TD>
                                                    <TD colspan="2" style="BORDER-BOTTOM: medium none" width="25%">
                                                        <input type=hidden name=doatrepairmax_points<? echo $id; ?> value='<?=$torepairm ?>'>
                                                        <INPUT style="WIDTH: 100%" type=submit value="Max" name=doatrepairmax>
                                                    </TD> 
                                                </TR> 
                                            </TBODY>
                                        </TABLE>
                                    </TD>
                                </FORM>
                                <FORM action=armory.php method=post>
                                    <TD>
                                        <TABLE cellSpacing=0 cellPadding=1 width="100%" border=0>
                                            <TBODY>
                                                <TR>
                                                    <TD style="BORDER-BOTTOM: medium none">
                                                        <INPUT type=input maxLength=6 size=2 value=0 name=atscrapsell<? echo $id; ?> >
                                                    </TD>
                                                    <TD style="BORDER-BOTTOM: medium none" width="100%">
                                                        <INPUT style="WIDTH: 100%" type=submit value="<?
	$pr = round(($conf["weapon{$wep[$i]->weaponID}price"] * (1 + ($user->weapper / 100)) * ($x / $y - 0.2)));
	if ($pr > 0) {
		echo "Sell for ";
		numecho($pr);
		echo " Gold";
	} else echo "Scrap"; ?>" name=doatscrapsell>
                                                    </TD>
                                                </TR>
                                            </TBODY>
                                        </TABLE>
                                    </TD>
                                </FORM>
                                </TR>
                                <?
}
?>
                                <tr><td></td><td>Total: <? numecho($totalWCount) ?></td><td></td><td></td><td></td></tr>
                                <TR>
                                    <TH class=subh align=left>
                                         Defense Weapons
                                    </TH>
                                    <TH class=subh align=right>
                                         Quantity
                                    </TH>
                                    <TH class=subh>
                                         Strength
                                    </TH>
                                    <TH class=subh>
                                         Repair
                                    </TH>
                                    <TH class=subh>
                                         Scrap/Sell
                                    </TH>
                                </TR>
                                <? $wep = getDefUserWeapon($user);
$totalWCount = 0;
for ($i = 0;$i < count($wep);$i++) {
	$totalWCount+= $wep[$i]->weaponCount;
	printf("<TR><TD>%s</TD>", $conf["race"][$user->race]["defenseweapon"][$wep[$i]->weaponID]["name"]);
?>
                                <TD align=right>
                                    <? numecho($wep[$i]->weaponCount)
?>
                                </TD>
                                <? $x = $wep[$i]->weaponStrength;
	$y = $conf["weapon{$wep[$i]->weaponID}strength"];
	$torepair2 = $torepair2m = $y - $x;
	$cost = $costm = round($conf["weapon{$wep[$i]->weaponID}pp"] * $wep[$i]->weaponCount * $torepair2);
	if ($cost > $user->gold) {
		$torepair2m = floor($user->gold / ($conf["weapon{$wep[$i]->weaponID}pp"] * $wep[$i]->weaponCount));
		$costm = floor($conf["weapon{$wep[$i]->weaponID}pp"] * $wep[$i]->weaponCount * $torepair2);
	}
?>
                                <TD align=middle>
                                    <? numecho($x)
?>
                                     /<? numecho($y)
?>
                                </TD>
                                <FORM action=armory.php method=post>
                                    <TD>
                             
                                        <TABLE cellSpacing=0 cellPadding=2 width="100%" border=0>
                                            <TBODY>
                                                <TR>
                                                    <? $rem = round($y) * $wep[$i]->weaponCount;
	$id = $wep[$i]->weaponID;
?>
                                                    <TD style="BORDER-BOTTOM: medium none">
                                                        <INPUT type=input maxLength=6 size=4 value="<?=$torepair2
?>" value=0 name=defrepair<? echo $id; ?> >
                                                    </TD>
                                                    <TD style="BORDER-BOTTOM: medium none" width="75%">
                                                        <INPUT style="WIDTH: 100%" type=submit value="<? numecho($conf["weapon{$wep[$i]->weaponID}pp"] * $wep[$i]->weaponCount); ?> Gold/Point" name=dodefrepair>
                                                    </TD>
                                                    <TD colspan="2" style="BORDER-BOTTOM: medium none" width="25%">
                                                        <input type=hidden name=dodefrepairmax_points<? echo $id; ?> value='<?=$torepair2m ?>'>
                                                        <INPUT style="WIDTH: 100%" type=submit value="Max" name=dodefrepairmax>
                                                    </TD> 
                                                </TR>
                                            </TBODY>
                                        </TABLE>
                                    </TD>
                                </FORM>
                                <FORM action=armory.php method=post>
                                    <TD>
                                        <TABLE cellSpacing=0 cellPadding=1 width="100%" border=0>
                                            <TBODY>
                                                <TR>
                                                    <TD style="BORDER-BOTTOM: medium none">
                                                        <INPUT type=input maxLength=6 size=2 value=0 name=defscrapsell<? echo $id; ?> >
                                                    </TD>
                                                    <TD style="BORDER-BOTTOM: medium none" width="100%">
                                                        <INPUT style="WIDTH: 100%" type=submit value="<?
	$pr = round(($conf["weapon{$wep[$i]->weaponID}price"]) * (1 + ($user->weapper / 100)) * ($x / $y - 0.2));
	if ($pr > 0) {
		echo "Sell for ";
		numecho($pr);
		echo " Gold";
	} else echo "Scrap"; ?>" name=dodefscrapsell>
                                                    </TD>
                                                </TR>
                                            </TBODY>
                                        </TABLE>
                                    </TD>
                                </FORM>
                                </TR>
                                <?
}
?>
                                <tr><td>
                                <? if ($user->supporter > 0) { ?>
                                <form method="POST" action="armory.php">
                             
                                <input type="submit" name="maxall" value="Repair All Weapons" />
                                </form><?
} ?>
                                </td><td>Total: <? numecho($totalWCount) ?></td><td></td><td></td><td></td></tr>
                                
                                
                                <!--<tr><td></td></tr>-->
                                
                            </TBODY>
                        </TABLE>
                        <P>
                            <TABLE width="100%">
                                <TBODY>
                                    <TR>
                                        <TD style="PADDING-RIGHT: 25px" vAlign=top width="50%">
                                            <TABLE class=table_lines cellSpacing=0 cellPadding=6 width="100%" border=0>
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
                                                            <? numecho($user->SA)
?>
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
                                                            <? numecho($user->DA)
?>
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
                                                            <? numecho($user->CA)
?>
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
                                                            <? numecho($user->RA)
?>
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
                                            <BR>
                                            <TABLE class=table_lines cellSpacing=0 cellPadding=6 width="100%" 	border=0>
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
                                        </TD>
                                        <TD vAlign=top width="50%">
                                            <FORM action=armory.php method=post>
                                                <TABLE class=table_lines cellSpacing=0 cellPadding=6 width="100%" border=0>
                                                    <TBODY>
                                                        <TR>
                                                            <TH colSpan=4>
                                                                 Buy Weapons
                                                            </TH>
                                                        </TR>
                                                        <TR>
                                                            <TH class=subh align=left>
                                                                 Attack Weapons
                                                            </Th>
                                                            <TH class=subh align=right>
                                                                 Strength
                                                            </Th>
                                                            <TH class=subh align=right>
                                                                 Price
                                                            </Th>
                                                            <TH class=subh>
                                                                 Buy
                                                            </Th>
                                                            <TH class=subh>
                                                                 Max
                                                            </Th>
                                                        </TR>
                                                        <? for ($i = - 1;$i < $user->salevel;$i++) {
	if ($i < 8) {
		printf("<TR><TD>%s</TD>", $conf["race"][$user->race]["weapon"][$i + 1]["name"]);
?>
                                                        <TD align=right>
                                                            <? numecho($conf["weapon" . ($i + 1) . "strength"]);
?>
                                                        </TD>
                                                        <TD align=right>
                                                            <? numecho($conf["weapon" . ($i + 1) . "price"] * (($user->salevel / 2) + 1));
?>
                                                             Gold
                                                        </TD>
                                                        <? echo "<TD align=middle><INPUT size=3 value=0 id=buy_w" . ($i + 1) . " name=buy_w" . ($i + 1) . "></TD>";
?>
                                                        <td>
                                                            <input type=button value='Max'  onclick="document.getElementById('<? echo "buy_w" . ($i + 1); ?>').value='<?=floor($user->gold / ($conf["weapon" . ($i + 1) . "price"] * (($user->salevel / 2) + 1))) ?>'">
                                                        </td>
                                                        <?
	}
}
?>
                                                        <TR>
                                                            <TH class=subh align=left>
                                                                 Defense Weapons
                                                            </Th>
                                                            <TH class=subh align=right>
                                                                 Strength
                                                            </Th>
                                                            <TH class=subh align=right>
                                                                 Price
                                                            </Th>
                                                            <TH class=subh>
                                                                 Buy
                                                            </Th> 
                                                            <TH class=subh>
                                                                 Max
                                                            </Th>
                                                        </TR>
                                                        <? for ($i = - 1;$i < $user->dalevel;$i++) {
	if ($i < 8) {
		printf("<TR><TD>%s</TD>", $conf["race"][$user->race]["defenseweapon"][$i + 1]["name"]);
?>
                                                        <TD align=right>
                                                            <? numecho($conf["weapon" . ($i + 1) . "strength"]);
?>
                                                        </TD>
                                                        <TD align=right>
                                                            <? numecho($conf["weapon" . ($i + 1) . "price"] * (($user->dalevel / 2) + 1));
?>
                                                             Gold
                                                        </TD>
                                                        <? echo "<TD align=middle><INPUT size=3 value=0 id=buy_dw" . ($i + 1) . " name=buy_dw" . ($i + 1) . "></TD>";
?>
                                                        <td>
                                                            <input type=button value='Max'  onclick="document.getElementById('<? echo "buy_dw" . ($i + 1); ?>').value='<?=floor($user->gold / ($conf["weapon" . ($i + 1) . "price"] * (($user->dalevel / 2) + 1))) ?>'">
                                                        </td>
                                                        <?
	}
}
?>
                                                        <TR>
                                                            <TD align=middle colSpan=5>
                                         
                                                                <INPUT type=submit value="Process Order" name=buybut>
                                                            </TD>
                                                        </TR>
                                                    </TBODY>
                                                </TABLE>
                                            </FORM>
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
