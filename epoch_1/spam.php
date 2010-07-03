<? include "gzheader.php";
include "scripts/vsys.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD><TITLE><? echo $conf["sitename"]; ?> :: Massively Multiplayer Online Role Playing Game</TITLE>
<META http-equiv=Content-Type content="text/html; charset=koi8-r"><!-- ZoneLabs Privacy Insertion -->
<SCRIPT language=javascript src="js/js"></SCRIPT>
<LINK href="css/common.css" type=text/css rel=stylesheet><LINK 
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

<META content="MSHTML 5.50.4522.1800" name=GENERATOR></HEAD>
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
      <TD style="PADDING-RIGHT: 15px; PADDING-LEFT: 15px; PADDING-TOP: 12px" 
    vAlign=top align=left> <BR>
      <H3>Report Spam</H3>
      <P><B>PLEASE NOTE: THE PURPOSE OF THIS PAGE IS  REPORTING ABUSE OF 
      SERVICES SUCH AS IRC OR CHAT, FORUMS AND MESSAGEBOARDS, AND NEWSGROUPS. IF 
      YOU DO NOT KNOW WHAT THIS MEANS, DO NOT POST HERE. DO NOT POST ANYTHING 
      ELSE HERE OR IT WILL BE IGNORED. </B>
      <P>Please fill out the following form. Boxes marked with a * are REQUIRED. 
      If you do not fill in all of the required boxes, or you fill them in 
      incorrectly, your submission will be IGNORED. Please READ all of the 
      guidelines below before submitting a complaint (yes, with your eyes). 
      <P><B>What ID Number?</B> 
      <P>The user's id refers to the number that appears in a user's link. For 
      example, if the link being spammed is 
      http://ww2game.net/recruit.php?uniqid=ABCDEFGH, the id number you 
      must enter is ABCDEFGH. If the user is spamming a link that is a redirect 
      to a normal link, PLEASE find out what the id number is before you send in 
      the report. 
      <P><B>Evidence</B> 
      <P>In order for us to take action against a user, you must provide some 
      evidence that the violation occurred. For example, if you are reporting 
      abuse on IRC, you must include a reasonably sized transcript. A reasonably 
      sized transcript will include CONTEXT, not simply the link. If you are 
      reporting abuse on a forum, you may include a LINK to a screenshot, or a 
      link to the forum and a transcript of the forum post. If you are reporting 
      a USENET Newsgroup violation, please include a transcript of the USENET 
      post. No matter what type of abuse you are reporting, if possible, include 
      the IP Address of the spammer. If you are reporting an e-mail violation, 
      you must include the headers from the email as well as the body of the 
      e-mail in your report. 
      <P><B>Process</B> 
      <P>We will read every submission and make a descision. Normally, we will 
      not respond to submissions unless there are special circumstances. If you 
      don't receive a reply, you aren't being ignored, it's just that we have 
      many other e-mails to respond to. 
      <P>Thank you for your cooperation in our effort to eliminate users who 
      spam. 
      <P>
      <P><A name=form><FONT color=red>&nbsp;</FONT>
      <FORM action=spam.php#form method=post>
      <TABLE cellPadding=3 border=0>
        <TBODY>
        <TR>
          <TD>Your E-Mail address: *</TD>
          <TD><INPUT name=email></TD></TR>
        <TR>
          <TD>ID number of contested user: *</TD>
          <TD><INPUT name=id></TD></TR>
        <TR>
          <TD>Service where the abuse occurred: *</TD>
          <TD><SELECT name=service> <OPTION value=0 selected 
              label="IRC/Chat">IRC/Chat</OPTION> <OPTION value=1 
              label="Forum">Forum</OPTION> <OPTION value=2 
              label="USENET Newsgroup">USENET Newsgroup</OPTION> <OPTION value=3 
              label="E-Mail">E-Mail</OPTION></SELECT> </TR></TBODY></TABLE>Evidence: 
      *<BR><TEXTAREA name=evidence rows=10 cols=40></TEXTAREA> 
      <P>Comments:<BR><TEXTAREA name=comments rows=10 cols=40></TEXTAREA> 
      <P> <INPUT type=submit value="Submit Report"> </FORM>
        <P>
          <?
include ("bottom.php");
?>
        </P></TD></TR></TBODY></TABLE></BODY></HTML>
<? include "gzfooter.php"; ?>
