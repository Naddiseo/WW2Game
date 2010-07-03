<?
include "scripts/vsys.php";
if ($_SESSION['isLogined']) {
	header("Location: base.php");
	exit;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<HTML><HEAD>
<TITLE>
<? echo $conf["sitename"]; ?>
:: Massively Multiplayer Online Role Playing Game</TITLE>
<META http-equiv=Content-Type content="text/html; charset=iso-8859-1">
<SCRIPT language=javascript src="js/js"></SCRIPT>
<LINK href="css/common.css" type=text/css rel=stylesheet>
<LINK 
href="css/main.css" type=text/css rel=stylesheet>
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

<style type="text/css">
<!--
.style1 {
	font-size: 12pt;
	font-weight: bold;
}
.style3 {font-size: 10pt}
-->
</style>
</HEAD>
<BODY text=#ffffff bgColor=#000000 leftMargin=0 topMargin=0 marginheight="0" 
marginwidth="0">
<?
include ("top.php");
?>


        
<TABLE cellSpacing=0 cellPadding=5 width="100%" border=0>
  <TBODY>
  <TR>
        <TD class=subh style="PADDING-LEFT: 15px" vAlign=top width=140>
        <?
include ("left.php");
?>
        </TD>
    <TD class=subh style="PADDING-RIGHT: 15px; PADDING-LEFT: 15px; PADDING-TOP: 12px" 
    vAlign=top align=left>
      <CENTER>
          <table width="762" cellPadding=4 cellSpacing=5>
          <TBODY>
           <tr>
            <center><img src="pic/Ballies.png" width="740" height="40" border="0"></center>
                  
          
        </TR>
                </TBODY>
            </table>
                <TABLE cellSpacing=5 cellPadding=4>
        <TBODY>
        
         
          <tr>
            <TH><img src="pic/americans.gif" width="190" height="140"></TH>
            <TH><img src="pic/britains.gif" width="190" height="140"></TH>
          </TR>
        <TH><center><img src="pic/usa.png" width="250" height="30" border="0" alt="THE USA ARMY"></center></TH>
          <TH><center><img src="pic/uk.png" width="250" height="30" border="0" alt="THE USA ARMY"></center></TH>
    </TR>
    
        <TR>
          <TD class=subh vAlign=top align=middle>Gather your troops to fight the coming 
            enemy! </TD>
          <TD class=subh vAlign=top align=middle>Come to the aid of your allies and 
            destroy your enemies! </TD>
        </TR>
        <TR>
          <TD align=middle>22% Income Bonus</TD>
          <TD align=middle>30% Defence Bonus</TD>
        </TR>
        <FORM action=register.php method=get>
        <TR>
          <TD 
          align=middle><INPUT type=submit value="Join a USA division!" name=join></TD>
          <TD 
          align=middle><INPUT type=submit value="Join a UK division!" name=join></TD></TR>
            </TABLE>
                    <table width="762" cellPadding=4 cellSpacing=5>
           <tr>
            <center><img src="pic/Baxis.png" width="740" height="40" border="0"></center>
          
        </TR>
            </table>
                   <TABLE cellSpacing=5 cellPadding=4>
        <tr> 
          <TH><img src="pic/japanese.gif" width="190" height="140"></TH>
          <TH><img src="pic/german.gif" width="190" height="140"></TH>
        </tr>
        <tr>   <TH><center><img src="pic/jap.png" width="250" height="30" border="0" alt="THE USA ARMY"></center></TH>
          <TH><center><img src="pic/nazi.png" width="250" height="30" border="0" alt="THE GERMAN ARMY"></center></TH>
          </TH>
    </tr>
        <tr>
          <TD class=subh vAlign=top align=middle>Harness your strength and Kamikaze for your 
            people! </TD>
          <TD  class=subh vAlign=top align=middle>Use your Blitzkrieg to spread evil throughout 
            the land! </TD>
        </tr>
<tr>
  <TD align=middle>25% Spy Bonus</TD>
          <TD align=middle>20% Attack Bonus</TD>
</tr>
    


<tr>
          <TD align=middle><INPUT type=submit value="Join a Japanese division!" name=join></TD>
          <TD align=middle><INPUT type=submit value="Join a German division!" name=join></TD>
                  </tr></FORM></TBODY></TABLE>
      <BR>
      <P>
      <table class=table_lines cellspacing=6 cellpadding=6 width="100%">
        <tbody>
          <tr>
            <th><center><img src="pic/news.png" width="740" height="40" border="0"></center></th>
          </tr>
          <tr>
            <td height="154">
            	<blockquote>
            	Your news here
            	</blockquote>
            </td>
          </tr>            
        </tbody>
      </table>
      <BR>
      <P>
      <P>
      <?
include ("bottom.php");
?>
          </CENTER></TD></TR></TBODY></TABLE>
</BODY></HTML>
