<? include "gzheader.php";
include "scripts/vsys.php";
if ($cgi[train] and ($user->gold >= 0)) {
	$pris = ((2000 * $cgi[atsold]) + (2000 * $cgi[defsold]) + (3500 * $cgi[spy]) + (100000 * $cgi[sf]));
	if ($pris > $user->gold AND $pris > $user->savings) {
		$detail = "Not Enough Gold!";
		header("Location: train.php?strErr=$detail");
		exit;
	}
	if ($cgi[atsold]) {
		$wal = (int)$cgi[atsold];
		$typ = 0;
		if ($wal < 1.0) {
			$wal = 1;
		}
		$detail.= Train($user, $wal, $typ);
	}
	if ($cgi[defsold]) {
		$wal = (int)$cgi[defsold];
		$typ = 1;
		if ($wal < 1.0) {
			$wal = 1;
		}
		$detail.= Train($user, $wal, $typ);
	}
	if ($cgi[spy]) {
		$wal = (int)$cgi[spy];
		$typ = 2;
		if ($wal < 1.0) {
			$wal = 1;
		}
		$detail.= Train($user, $wal, $typ);
	}
	if ($cgi[sf]) {
		$wal = (int)$cgi[sf];
		$typ = 3;
		if ($wal < 1.0) {
			$wal = 1;
		}
		$detail.= Train($user, $wal, $typ);
	}
	if ($cgi[reat]) {
		$wal = (int)$cgi[reat];
		$typ = 4;
		if ($wal < 1.0) {
			$wal = 1;
		}
		if (!($wal > $user->sasoldiers)) {
			$detail.= Train($user, $wal, $typ);
		} else {
			$detail = "Not enough Soldiers to untrain";
		}
	}
	if ($cgi[redef]) {
		$wal = (int)$cgi[redef];
		$typ = 5;
		if ($wal < 1.0) {
			$wal = 1;
		}
		if (!($wal > $user->dasoldiers)) {
			$detail.= Train($user, $wal, $typ);
		} else {
			$detail = "Not enough Soldiers to untrain";
		}
	}
	if ($cgi[respy]) {
		$wal = (int)$cgi[respy];
		$typ = 6;
		if ($wal < 1.0) {
			$wal = 1;
		}
		if (!($wal > $user->spies)) {
			$detail.= Train($user, $wal, $typ);
		} else {
			$detail = "Not enough Soldiers to untrain";
		}
	}
	if ($cgi[resf]) {
		$wal = (int)$cgi[resf];
		$typ = 7;
		if ($wal < 1.0) {
			$wal = 1;
		}
		if (!($wal > $user->specialforces)) {
			$detail.= Train($user, $wal, $typ);
		} else {
			$detail = "Not enough Soldiers to untrain";
		}
	}
	//echo "--$typ--";
	header("Location: train.php?strErr=$detail");
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD><TITLE><? echo $conf["sitename"]; ?> :: Training</TITLE>
<META http-equiv=Content-Type content="text/html; charset=iso-8859-1">

<LINK href="css/common.css" type=text/css rel=stylesheet>
<SCRIPT>
        function openchatwin() {
                var popurl="chat.html";
                winpops=window.open(popurl,"","width=750,height=550");
        }
        </SCRIPT>


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
    vAlign=top align=left> <BR>
	<?
include "islogined.php";
?>
      <H3>Training</H3>
	  <p><strong><center><font color=red><? echo $cgi["strErr"]; ?></font></center></strong></p>
      <TABLE width="100%">
        <TBODY>
        <TR>
          <TD style="PADDING-RIGHT: 25px" vAlign=top width="50%">
            <TABLE class=table_lines cellSpacing=0 cellPadding=6 width="100%" 	border=0>
			  <TBODY>
			  <TR>
			    <TH colSpan=2>Personnel</TH></TR>
			  <TR>
			    <TD><B>Trained Attack Soldiers</B></TD>
			    <TD align=right><? numecho($user->sasoldiers) ?></TD></TR>
			  <TR>
			    <TD><B>Trained Attack Mercenaries</B></TD>
			    <TD align=right><? numecho($user->samercs) ?></TD></TR>
			  <TR>
			    <TD><B>Trained Defense Soldiers</B></TD>
			    <TD align=right><? numecho($user->dasoldiers) ?></TD></TR>
			  <TR>
			    <TD><B>Trained Defense Mercenaries</B></TD>
			    <TD align=right><? numecho($user->damercs) ?></TD></TR>
			  <TR>
			    <TD><B>Untrained Soldiers</B></TD>
			    <TD align=right><? numecho($user->uu) ?></TD></TR>			  
			  <TR>
			    <TD class=subh><B>Spies</B></TD>
			    <TD class=subh align=right><? numecho($user->spies) ?></TD></TR>
			    <TR>
			    <TD class=subh><B>Special Forces</B></TD>
			    <TD class=subh align=right><? numecho($user->specialforces) ?></TD></TR>
			    <TR>
			   <TR>
			    <TD><B>Total Fighting Force</B></TD>
				<TD align=right><? numecho(getTotalFightingForce($user)) ?></TD></TR>
				</TBODY>
			</TABLE><BR></TD> 
          <TD rowspan="2" vAlign=top width="50%">
            <FORM action=train.php method=post>
            <TABLE class=table_lines cellSpacing=0 cellPadding=6 width="100%" 
            border=0>
              <TBODY>
              <TR>
                <TH align=middle colSpan=4>Train Your Troops</TH></TR>
              <TR>
                <TH class=subh align=left>Training Program</TH>
                <TH class=subh align=right>Cost Per Unit</TH>
                <TH class=subh>Quantity</TH><TH class=subh></TH></TR>
              <TR>
                <TD>Attack Specialist</TD>
                <TD align=right>2,000 Gold</TD>
                <TD align=middle><INPUT id=atsold size=5 value=0 
              name=atsold></TD>
	      <td><input type=button value='Max'  onclick="document.getElementById('atsold').value='<?
$bygold = floor($user->gold / 2000);
if ($bygold > $user->uu) {
	echo $user->uu;
} else {
	echo $bygold;
}
?>';"></td>	      </TR>
              <TR>
                <TD>Defense Specialist</TD>
                <TD align=right>2,000 Gold</TD>
                <TD align=middle><INPUT size=5 value=0 
             id=defsold name=defsold></TD>
	       <td><input type=button value='Max'  onclick="document.getElementById('defsold').value='<?
$bygold = floor($user->gold / 2000);
if ($bygold > $user->uu) {
	echo $user->uu;
} else {
	echo $bygold;
}
?>';"></td>	      
	      </TR>
              <TR>
                <TD>Spy</TD>
                <TD align=right>3,500 Gold</TD>
                <TD align=middle><INPUT id=spy size=5 value=0 name=spy></TD>
		<td><input type=button value='Max'  onclick="document.getElementById('spy').value='<?
$bygold = floor($user->gold / 3500);
if ($bygold > $user->uu) {
	echo $user->uu;
} else {
	echo $bygold;
}
?>';"></td>	      
		</TR>
	      <TR>
                <TD>Special Forces Operative</TD>
                <TD align=right>100,000 Gold</TD>
                <TD align=middle><INPUT id=sf size=5 value=0 name='sf'></TD>
		<td><input type=button value='Max'  onclick="document.getElementById('sf').value='<?
$bygold = floor($user->gold / 100000);
if ($bygold > $user->uu) {
	echo $user->uu;
} else {
	echo $bygold;
}
?>';"></td>	      
		</TR>		 
		<tr><TD colspan="4"><hr></TD></tr>	
              <TR>
                <TD>Reassign Attack Specialist</TD>
                <TD align=right>0 Gold</TD>
                <TD align=middle><INPUT size=5 value=0 id=reat name=reat></TD>
		<td><input type=button value='Max'  onclick="document.getElementById('reat').value='<?=$user->sasoldiers
?>';"></td>	      
		</TR>
              <TR>
                <TD>Reassign Defense Specialist</TD>
                <TD align=right>0 Gold</TD>
                <TD align=middle><INPUT size=5 value=0 id=redef name=redef></TD>
		<td><input type=button value='Max'  onclick="document.getElementById('redef').value='<?=$user->dasoldiers
?>';"></td>	      
		</TR>
		<TR>
                <TD>Reassign Spies</TD>
                <TD align=right>0 Gold</TD>
                <TD align=middle><INPUT size=5 value=0 id=respy name=respy></TD>
		<td><input type=button value='Max'  onclick="document.getElementById('respy').value='<?=$user->spies
?>';"></td>
		</TR>
              <TR>
                <TD>Reassign Special Forces</TD>
                <TD align=right>0 Gold</TD>
                <TD align=middle><INPUT size=5 value=0 id=resf name=resf></TD>
		<td><input type=button value='Max'  onclick="document.getElementById('resf').value='<?=$user->specialforces
?>';"></td>
		</TR>
		
              <TR>
                <TD align=middle colSpan=4><INPUT type=submit value=Train! name=train> 
              </TD></TR></TBODY></TABLE> </FORM><BR></TD></TR>
	      
        </TBODY></TABLE>
      <?
include ("bottom.php");
?>	
	  </TD></TR></TBODY></TABLE>
</BODY></HTML>

<? include "gzfooter.php"; ?>