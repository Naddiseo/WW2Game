<? include "gzheader.php";
include "scripts/vsys.php";
if (isset($_SESSION['number1'])) {
	unset($_SESSION['number1']);
}
//if( !is_numeric($cgi['uniqid'])){
//   header("Redirect: $conf[base_url]");
//}
$us = getUserDetails($cgi['uniqid'], "*");
$ip = $_SERVER['REMOTE_ADDR'];
$time = $conf["hours_to_block_same_user_recruiting"] * 60 * 60;
$ttt = time() - $time;
$a = mysql_query("SELECT * FROM click WHERE ip='$ip' AND uid='" . $us->ID . "' AND time>'$ttt'") or die(mysql_error());
$ar = mysql_fetch_array($a);
$acount = mysql_num_rows($a);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"
>
<HTML>
    <HEAD>
        <TITLE><? echo $conf["sitename"]; ?> :: Massively Multiplayer Online Role Playing Game</TITLE>
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
include ("top.php");
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
                    <!--Recruiter: username (<?=$us->userName
?>)-->
                    
                        <?
if ($us->active != 1 OR !is_numeric($cgi['uniqid'])) {
	echo "<!--Recruiter: INVALID-->";
}
function recruitSoldier($id) {
	$com = getUserDetails($id, "commander");
	updateUser($id, " exp =exp+6,clicks=clicks+4");
	if ($com->commander AND $com->commander == $_SESSION['isLogined']) {
		updateUser($com->commander, " exp =exp+3,clicks=clicks+1,uu=uu+1");
	}
}
if ($cgi['image_click_value']) {
	if ($us->clicks >= 1000) {
?>
                        <!--Recruiter: ENOUGH <?=$us->ID ?>-->
                        <p>This user has been clicked enough for <?=$conf["hours_to_block_same_user_recruiting"] ?> hours.</p>
                        If you wish, you may still become one of <?=$us->userName ?>'s officers and build an army of your own. With <?=$us->userName ?>'s protection and support, you too may build an army that will someday win the war. If you accept this challenge,
                                <br>
                                <br>
                                <center>
                                    <font style="font-size: 16pt"> <a href="register.php?join=<?=$conf['race'][$us->race]['name'] ?>&amp;uniqid=<?=$us->ID ?>"><font style="font-size: 16pt">Join The War</font></a>!</font>
                                </center>   
                  <?
	} elseif (strstr($alert, "proxy")) {
?>
                        <!--Recruiter: PROXY <?=$us->ID ?>-->
                         <p>You may not recruit people from this IP</p>
                        If you wish, you may still become one of <?=$us->userName ?>'s officers and build an army of your own. With <?=$us->userName ?>'s protection and support, you too may build an army that will someday win the war. If you accept this challenge,
                                <br>
                                <br>
                                <center>
                                    <font style="font-size: 16pt"> <a href="register.php?join=<?=$conf['race'][$us->race]['name'] ?>&amp;uniqid=<?=$us->ID ?>"><font style="font-size: 16pt">Join The War</font></a>!</font>
                                </center>  
                    <?
	} elseif ($cgi['image_click_value'] == $_SESSION['number']) {
		//$_SESSION['number']=0;
		if (time() < ($ar[time] + $time) and $acount > 0) {
			//print_r($IP);
			//$IP=getIP($ipID);
			$time = time() - $ar[time];
			//alert($time);
			$time = $conf["hours_to_block_same_user_recruiting"] * 60 * 60 - $time;
			//alert($time);
			$t = $time / (60 * 60);
			$tF = floor($t);
			$timeA['tm_hour'] = $tF;
			$time = $time - $tF * 60 * 60;
			$t = $time / (60);
			$tF = floor($t);
			$timeA['tm_min'] = $tF;
			$time = $time - $tF * 60;
			$timeA['tm_sec'] = $time;
			//			$t=($time-$timeA['tm_sec'])
			
?>
                        <!--Recruiter: TOOSOON <?=$us->ID ?>-->
                         You have already clicked this link recently. You may click again in&nbsp;<?=$timeA['tm_hour']
?>
                         hours,&nbsp;<?=$timeA['tm_min']
?>
                         minutes and&nbsp;<?=$timeA['tm_sec']
?>
                         seconds&nbsp;<?
		} else {
			if (!isset($_SESSION['isLogined'])) {
				$s_id = 0;
			} else {
				$s_id = $_SESSION['isLogined'];
			}
			recruitSoldier($us->ID);
			mysql_query("INSERT INTO click (`ip`,`uid`,`time`,`sid`) VALUES ('$ip','" . $us->ID . "','" . time() . "','$s_id');") or die(mysql_error());
?>
                        <center>
                            <!--Recruiter: OK <?=$us->ID ?>-->
                            <!--Recruiter: army <?=$army ?>-->
                            <font style="font-size: 14pt;"><b>You have been recruited into&nbsp;<?=$us->userName ?>'s army!</b><br>
                            </font>
                            <p>
                                <?=$us->userName
?>
                                 is building an army of&nbsp;<?=$conf['race'][$us->race]['name']
?>
                                 and now has&nbsp;<?=$us->exp
?>&nbsp;
                                 experience.
                                <br>
                            <p>
                                 If you wish, you may become one of&nbsp;<?=$us->userName
?>'s officers and build an army of your own. With <?=$us->userName ?>'s protection and support, you too may build an army that will someday win the war. If you accept this challenge,
                                <br>
                                <br>
                                <center>
                                    <font style="font-size: 16pt"> <a href="register.php?join=<?=$conf['race'][$us->race]['name'] ?>&uniqid=<?=$us->ID ?>"><font style="font-size: 16pt">Join The War</font></a>!</font>
                                </center>
                                </center>
                                <?
		}
	} else {
?>
                                <!--Recruiter: INCORRECT <?=$us->ID ?>-->
                                <h1>
                                     Please Wait...
                                </h1>
                                 You failed to correctly match the image! For your sanity, there is a mandatory "cooling down" period that you must go through before trying again. Please try again in a <b>few minutes</b>.
                                 <?
		$_SESSION[hash2] = md5($_SERVER['REMOTE_ADDR'] . time());
	}
} elseif ($cgi['uniqid']) {
	$us = getUserDetails($cgi['uniqid']);
?>
                            <p>
                                 &nbsp;
                            </p>
                            <CENTER>
                                <center>
                                     If you reached this page via a link in an IRC or Chat channel, a message board or forum where the link to this page would be considered off topic, please close this page now.
                                    <p>
                                        <h3>
                                             Otherwise,
                                        </h3>
                                        <script language="JavaScript" type="text/javascript">
	function doDisable(frm, val) {
		//frm.submit();
		//return;
		for (var i=0; i<frm.elements.length; i++) {
			if (frm.elements[i].name == 'image_click_value' && frm.elements[i].value != val)
				frm.elements[i].disabled = true;
		}
		frm.submit();
	}
</script>
                                        <form action="recruit.php" method="post" name="image_clickthrough_form">
                                            <input type="hidden" name="uniqid" value="<?=$us->ID
?>">
                                            <p>
                                            <!--Recruiter: CLICKY <?=$us->ID ?>-->
                                                 Click on the number shown in the image to proceed to&nbsp;<a href="stats.php?id=<?=$us->ID ?>"><?=$us->userName ?></a>'s Recruitment Center
                                            <p>
                                                <img src="imageclick.php?<? $SID = session_name() . "=" . session_id();
	echo $SID;
	$number = rand(1, 15);
	unset($_SESSION['number']);
	$_SESSION['number'] = $number; ?>" alt="Click on #">
                                            <p>
                                                <table border="0" cellspacing="0" cellpadding="6">
                                                    <tr>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <input style="font-family: 'Century Schoolbook', 'Times New Roman', Serif; font-size: 24pt; height: 60; width: 60;" type="submit" value="1" name="image_click_value">
                                                        </td>
                                                        <td>
                                                            <input style="font-family: 'Century Schoolbook', 'Times New Roman', Serif; font-size: 24pt; height: 60; width: 60;" type="submit" value="2" name="image_click_value">
                                                        </td>
                                                        <td>
                                                            <input style="font-family: 'Century Schoolbook', 'Times New Roman', Serif; font-size: 24pt; height: 60; width: 60;" type="submit" value="3" name="image_click_value">
                                                        </td>
                                                        <td>
                                                            <input style="font-family: 'Century Schoolbook', 'Times New Roman', Serif; font-size: 24pt; height: 60; width: 60;" type="submit" value="4" name="image_click_value">
                                                        </td>
                                                        <td>
                                                            <input style="font-family: 'Century Schoolbook', 'Times New Roman', Serif; font-size: 24pt; height: 60; width: 60;" type="submit" value="5" name="image_click_value">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <input style="font-family: 'Century Schoolbook', 'Times New Roman', Serif; font-size: 24pt; height: 60; width: 60;" type="submit" value="6" name="image_click_value">
                                                        </td>
                                                        <td>
                                                            <input style="font-family: 'Century Schoolbook', 'Times New Roman', Serif; font-size: 24pt; height: 60; width: 60;" type="submit" value="7" name="image_click_value">
                                                        </td>
                                                        <td>
                                                            <input style="font-family: 'Century Schoolbook', 'Times New Roman', Serif; font-size: 24pt; height: 60; width: 60;" type="submit" value="8" name="image_click_value">
                                                        </td>
                                                        <td>
                                                            <input style="font-family: 'Century Schoolbook', 'Times New Roman', Serif; font-size: 24pt; height: 60; width: 60;" type="submit" value="9" name="image_click_value">
                                                        </td>
                                                        <td>
                                                            <input style="font-family: 'Century Schoolbook', 'Times New Roman', Serif; font-size: 24pt; height: 60; width: 60;" type="submit" value="10" name="image_click_value">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <input style="font-family: 'Century Schoolbook', 'Times New Roman', Serif; font-size: 24pt; height: 60; width: 60;" type="submit" value="11" name="image_click_value">
                                                        </td>
                                                        <td>
                                                            <input style="font-family: 'Century Schoolbook', 'Times New Roman', Serif; font-size: 24pt; height: 60; width: 60;" type="submit" value="12" name="image_click_value">
                                                        </td>
                                                        <td>
                                                            <input style="font-family: 'Century Schoolbook', 'Times New Roman', Serif; font-size: 24pt; height: 60; width: 60;" type="submit" value="13" name="image_click_value">
                                                        </td>
                                                        <td>
                                                            <input style="font-family: 'Century Schoolbook', 'Times New Roman', Serif; font-size: 24pt; height: 60; width: 60;" type="submit" value="14" name="image_click_value">
                                                        </td>
                                                        <td>
                                                            <input style="font-family: 'Century Schoolbook', 'Times New Roman', Serif; font-size: 24pt; height: 60; width: 60;" type="submit" value="15" name="image_click_value">
                                                        </td>
                                                    </tr>
                                                </table>
                                                <SCRIPT LANGUAGE="JavaScript">
                                                        var accountid="silentw"
                                                </SCRIPT>
                                                <SCRIPT LANGUAGE="JavaScript" SRC="http://www.drumcash.com/drumcash.dc">
                                                </SCRIPT>
                                                <input type="hidden" name="hash2" value="<? $_SESSION[hash2] = md5($_SERVER['REMOTE_ADDR'] . time());
	echo $_SESSION[hash2]; ?>">
                                                <input type="hidden" name="uniqid" value="<?=$cgi['uniqid'] ?>">
                                                </form>
                                            <p>
                                                <center>
                                                </center>
                                                <?
}
?>
                                                </center>
                                            <P>
                                            
                                            <P>
                                                <?
include ("bottom.php");
?>
                                                </CENTER>
                                            </TD>
                                            </TR>
                                            </TBODY>
                                            </TABLE>
                                            </BODY>
                                            </HTML>
                                            <? include "gzfooter.php"; ?>



