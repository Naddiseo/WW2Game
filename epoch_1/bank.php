<? include "gzheader.php";
include "scripts/vsys.php";
$user = getUserDetails($_SESSION["isLogined"]);
if ($cgi[$_SESSION['depbox']]) {
	if ($user->bankimg == 1) {
		if (strtolower($cgi['turing']) != strtolower($_SESSION['number'])) {
			header("Location: bank.php?strErr=Image not matched correctly");
			exit;
		}
	}
	$g = abs(round(ccomma($cgi[$_SESSION['depbox']]), 0));
	if ($g < 1) {
		$g = 1;
	}
	if ($g > $user->gold) {
		$strErr = "Not Enough Gold to Deposit";
	} else {
		$goldi = $user->gold;
		$newg = $user->gold - $g;
		$bankfee = $g;
		$g = round((1.0 - ($user->bankper / 100)) * $g); //deduct the 10%
		$bankfee = $bankfee - $g;
		if ($bankfee == 0) {
			//wouldn't want them getting free gold
			$bankfee = 1;
			$g = $g - 1;
		}
		updateUser($_SESSION["isLogined"], " gold='$newg',bank=bank+$g,bankimg=0 ");
		$strErr = "You have just Banked " . numecho2($g) . " Gold.<br>You had " . numecho2($goldi) . " Gold <br>
	Bank Fee was " . $user->bankper . "% ( " . numecho2($bankfee) . " Gold ).";
	}
	if (rand(1, 40) == 25) {
		$_SESSION['isLogined'] = 0; //random logout
		
	}
}
if ($cgi['withdrawbox']) {
	$g = abs(round(ccomma($cgi['withdrawbox']), 0));
	if ($g < 1) {
		$g = 1;
	}
	if ($g > $user->bank) {
		$strErr = "Not Enough Gold in Bank to Withdraw";
	} else {
		$goldi = $user->gold;
		$newg = $user->gold + $g;
		//$g=0.9*$g;//deduct the 10%
		updateUser($_SESSION["isLogined"], " gold='$newg',bank=bank-$g ");
		$strErr = "You have just Withdrawn " . numecho2($g) . " <br> Gold. You had " . numecho2($goldi) . " Gold";
	}
}
$user = getUserDetails($_SESSION['isLogined']);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD><TITLE><? echo $conf["sitename"]; ?> :: Treasury</TITLE>
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
		//-->
		</SCRIPT>

<META content="MSHTML 5.50.4522.1800" name=GENERATOR></HEAD>
<BODY text=#ffffff bgColor=#000000 leftMargin=0 topMargin=0 marginheight="0" 
marginwidth="0"  onload="gm(<?=$user->ID ?>);">
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
     <center>   <FONT 
      color=red><? include "islogined.php";
echo $strErr; ?></FONT></center>
      <P>
      <TABLE width="100%">
        <TBODY>
        <TR>
	<td>
	<center>
	Gold in hand: <?=numecho2($user->gold); ?><br>
	Gold in bank: <?=numecho2($user->bank); ?><br>
	
	<br>
	<small>Deposit Fee <?=$user->bankper ?>%</small>
	<form action=bank.php name='<? $d = genUniqueTxt(10);
echo $d; ?>' method=POST>
 	<input name='<? $_SESSION['depbox'] = genUniqueTxt(10);
echo $_SESSION['depbox']; ?>' type=text size=12 maxlength=17 value=<?=numecho2($user->gold); ?> />
 	<? if ($user->bankimg == 1) { ?><br /><img src="imageclick.php?<? $SID = session_name() . "=" . session_id();
	echo $SID; ?>" alt="random chars"><br>
		<SELECT name=turing>
		    <option value="1">one</option>
		    <option value="2">two</option>
		    <option value="3">three</option>
		    <option value="4">four</option>
		    <option value="5">five</option>
		    <option value="6">six</option>
		    <option value="7">seven</option>
		    <option value="8">eight</option>
		    <option value="9">nine</option>
		    <option value="10">ten</option>
		    <option value="11">eleven</option>
		    <option value="12">twelve</option>
		    <option value="13">thirteen</option>
		    <option value="14">fourteen</option>
		    <option value="15">fifteen</option>
		    
		</SELECT><?
} ?> <br />
		<input type="submit" value="Deposit" />
	</form>	
	<form action=bank.php method=POST>
	<input name='withdrawbox' type=text size=12 maxlength=17 value=<?=numecho2($user->bank); ?> /><input type=submit value= 'Withdraw Gold'/>
	</form>
	
	</center>
	</td>

        </TR></TBODY></TABLE>
     <?
include ("bottom.php");
?>	
</TD></TR></TBODY></TABLE></BODY></HTML>

<? include "gzfooter.php"; ?>