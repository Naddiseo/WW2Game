<? $incron = true;
include "scripts/vsys.php";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD><TITLE><? echo $conf["sitename"]; ?> ::IRC Chat</TITLE>
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

<style type="text/css">
<!--
.style1 {font-size: 7pt}
.style2 {color: #cc9900}
-->
</style>
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
/*include ("left.php");*/
?>
</TD>
      <TD style="PADDING-RIGHT: 15px; PADDING-LEFT: 15px; PADDING-TOP: 12px" 
    vAlign=top align=left> <BR>
    
        
          <table width="50%" border="0" align="center"  cellpadding="6" cellspacing="0">
          <tr><TD>To change your nick, type: /nick your_new_name_here</TD></tr>
            <tr> 
              <th><applet code=IRCApplet.class archive="irc.jar,pixx.jar" width=640 height=400>
                <param name="CABINETS" value="irc.cab,securedirc.cab,pixx.cab">
                <param name="host" value="<?=$conf['ip_server'] ?>">
                <param name="alternateserver1" value="<?=$conf['alternate_ip_server'] ?>">
                <param name="port" value="6667">
                <param name="nick" value="<?
$n = getUserDetails($_SESSION['isLogined'], 'username');
if (isset($n->ircnick)) {
	//echo "LOGINNED";
	$nick = str_replace(" ", "_", stripslashes($n->ircnick));
	$nick = str_replace("'", "", $nick);
	$nick = str_replace("/", "", $nick);
	$nick = str_replace("\\", "", $nick);
	echo $nick;
} elseif (isset($n->username)) {
	//echo "LOGINNED";
	$nick = str_replace(" ", "_", stripslashes($n->username));
	$nick = str_replace("'", "", $nick);
	$nick = str_replace("/", "", $nick);
	$nick = str_replace("\\", "", $nick);
	echo $nick;
} else {
	echo "WWII???";
} ?>">
                <param name="alternatenick" value="<?php
if ($_SESSION['isLogined']) {
	//echo "LOGINNED";
	$nick = str_replace(" ", "_", stripslashes($user->ircnick));
	$nick = str_replace("'", "", $nick);
	$nick = str_replace("/", "", $nick);
	$nick = str_replace("\\", "", $nick);
	echo $nick;
} else {
	echo "WWII???";
} ?>">
                <param name="fullname" value="WW2 IRC User">
                <param name="command1" value="/join #WW2">
                <param name="quitmessage" value="GO GO GO!">
                <param name="gui" value="pixx">
                <param name="style:bitmapsmileys" value="true">
                <param name="style:smiley1" value=":) img/sourire.gif">
                <param name="style:smiley2" value=":-) img/sourire.gif">
                <param name="style:smiley3" value=":-D img/content.gif">
                <param name="style:smiley4" value=":d img/content.gif">
                <param name="style:smiley5" value=":-O img/OH-2.gif">
                <param name="style:smiley6" value=":o img/OH-1.gif">
                <param name="style:smiley7" value=":-P img/langue.gif">
                <param name="style:smiley8" value=":p img/langue.gif">
                <param name="style:smiley9" value=";-) img/clin-oeuil.gif">
                <param name="style:smiley10" value=";) img/clin-oeuil.gif">
                <param name="style:smiley11" value=":-( img/triste.gif">
                <param name="style:smiley12" value=":( img/triste.gif">
                <param name="style:smiley13" value=":-| img/OH-3.gif">
                <param name="style:smiley14" value=":| img/OH-3.gif">
                <param name="style:smiley15" value=":'( img/pleure.gif">
                <param name="style:smiley16" value=":$ img/rouge.gif">
                <param name="style:smiley17" value=":-$ img/rouge.gif">
                <param name="style:smiley18" value="(H) img/cool.gif">
                <param name="style:smiley19" value="(h) img/cool.gif">
                <param name="style:smiley20" value=":-@ img/enerve1.gif">
                <param name="style:smiley21" value=":@ img/enerve2.gif">
                <param name="style:smiley22" value=":-S img/roll-eyes.gif">
                <param name="style:smiley23" value=":s img/roll-eyes.gif">
                <param name="style:floatingasl" value="true">
                <param name="pixx:highlight" value="true">
                <param name="pixx:highlightnick" value="true">
                <param name="pixx:helppage" value="rules.php">
                <param name="pixx:timestamp" value="true">
                <param name="pixx:showabout" value="false">
                <param name="pixx:showclose" value="false">
              </applet></th>
            </tr>
			  <tr> 
              <th>
			  
			    <div align="right" class="style1">
			      <p align="center" class="style2"> Welcome, to the unoffical <a href="<?=$conf['base_url'] ?>" target="_blank">WW2</a> JavaIRC room. The rules can be found <a href="chatrules.php" target="_blank">here</a>. <br>
			        <br>
		          Please read the rules before typing   in anything.</p>
			      <p class="style2">Special thanks to Whitey for the IRC script </p>
			    </div></th>
			  
            </tr>
        </table>
         
        
        <P> 
          <?
include ("bottom.php");
?>	
	 </TD></TR></TBODY></TABLE>
</BODY></HTML>
