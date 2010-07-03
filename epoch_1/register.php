<? include "gzheader.php";
include "scripts/vsys.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head>
		
		<title><?=$conf["sitename"]; ?> :: Massively Multiplayer Online Role Playing Game</title>
		<link href="css/common.css" rel="stylesheet" type="text/css">
		<link href="css/main.css" rel="stylesheet" type="text/css">


		
				<SCRIPT language=javascript src="js/js"></SCRIPT>
		<script language="javascript" type="text/javascript">
		<!--
		function checkCR(evt) {
		var evt = (evt) ? evt : ((event) ? event : null);
		var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
		if ((evt.keyCode == 13) && (node.type=="text")) {return false;}
		}
		document.onkeypress = checkCR;
		//-->
		</script></head>

	<body bgcolor="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" text="#ffffff">
<?
include "top.php";
?>
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tbody><tr> 
    <TD class=menu_cell_repeater style="PADDING-LEFT: 15px" vAlign=top width=140>
<?
include ("left.php");
?>
    </td>
      <td style="padding-left: 15px; padding-top: 12px; padding-right: 15px;" valign="top" align="left"> 
        <font color="red">
        <?
if ($cgi['submit']) {
	if (($HTTP_SERVER_VARS['REMOTE_ADDR']) && (isIP($HTTP_SERVER_VARS['REMOTE_ADDR']))) {
		$echoS = "Oops. Somebody has already registered from this IP. If you beleive it is a fault you can contact us via mail {$conf['mail']}.";
	} elseif (!$cgi['username']) {
		$echoS = "You should enter user name.";
	} elseif (getUserDetailsByName($cgi['username'])) {
		$echoS = "That username has already been taken.";
	} elseif ($cgi['email'] != $cgi['email2']) {
		$echoS = "Mails you have entered are not equal.";
	} elseif (!preg_match("/^.+?@.+?\..+?/i", $cgi['email'])) {
		$echoS = "You should enter correct e-mail.";
	} elseif (strpos($cgi['email'], "hotmail.com") > 0) {
		$echoS = "We have been getting too many complaints that hotmail blocks our activation emails, please use an alternate email such as <a href=\"http://www.gmail.com\" target=\"_blank\">Gmail</a>";
	} elseif (getUserDetailsByEmail($cgi['email'])) {
		$echoS = "That e-mail has already been taken.";
	} elseif (!$cgi['tos']) {
		$echoS = "You should agree to comply with the terms of service.";
	} elseif (!$cgi['rules']) {
		$echoS = "You should agree to comply with the rules.";
	} elseif (!$cgi['cheat']) {
		$echoS = "You should promise not to try to gain an unfair advantage by breaking the rules.";
	} elseif (!$cgi['account']) {
		$echoS = "You should agree to have ONLY one " . $conf["sitename"] . " account.";
	} elseif (!$cgi['turing'] || (strtolower($cgi['turing']) != strtolower($_SESSION['number']))) {
		$echoS = "You should type the text that you see on the image.";
	} else {
		//echo "Registering";
		$isResistered = 1;
		$pas = genRandomPas();
		createUser($cgi['username'], $cgi['race'], $cgi['email'], $pas, $cgi['uniqid']);
		if ($cgi['uniqid']) {
			updateUser($cgi['uniqid'], " uu=uu+10 ");
		}
		$us = getUserDetailsByName($cgi['username'], " ID ");
		addIP($HTTP_SERVER_VARS['REMOTE_ADDR'], $us->ID);
		//echo "==".$cgi['email']."==";
		$html = "<html><body>Your name is: {$cgi['username']} <br>\n Your activation password is {$pas}</body></html>";
		$plain = "Your name is: {$cgi['username']} \n Your activation password is {$pas}";
		$email = new clsMAIL($cgi['email'], "World War II :: {$cgi['username']}", $html, $plain);
		$email->addheader("To", "\"$cgi[username]\" <$cgi[email]>");
		if ($email->send()) {
			echo "<br><br><center><font color=red>Your activation password was sent to your e-mail.</font></center><br><br><br><br>";
		} else {
			echo "There was an error sending the email message";
		}
		//echo "--$ism--";
		
	}
}
?>
        </font><br><? if (!$isResistered) { ?>
<form action="register.php" method="post">
          <table border="0" class="table_lines" cellspacing="0" cellpadding="6">
            <tbody>
              <tr> 
                <th colspan="2">Register</th>
              </tr>
              <tr> 
                <td colspan="2"><font color="red"><? echo $echoS; ?></font></td>
              </tr>
              <tr> 
                <td>Desired Username:</td>
                <td><input type="text" name="username" value="<?=$cgi['username'] ?>"></td>
              </tr>
              <tr> 
                <td>Desired Nation:</td>
                <td> <select name="race">
                	<option value="0">United States</option>
                	<option value="1">United Kingdom</option>
                	<option value="2">Japan</option>
                	<option value="3">Germany</option>                	                 
                  </select> </td>
              </tr>
              <tr> 
                <td>Password:</td>
                <td>Will be e-mailed to the address below</td>
              </tr>
              <tr> 
                <td><b>Valid</b> E-mail Address:</td>
                <td><input type="text" name="email" value="<?=$cgi['email'] ?>">&nbsp;&nbsp;<small>We no longer accept hotmail email addresses</small></td>
              </tr>
              <tr> 
                <td>E-mail Address Again:</td>
                <td><input type="text" name="email2" value="<?=$cgi['email2'] ?>"></td>
              </tr>
              <tr> 
                <td colspan="2"><input type="checkbox" name="tos" value="true">
                  I have read and agree to comply with the <a href="tos.php" target="_new">terms 
                  of service</a></td>
              </tr>
              <!--<tr><td>In this beta there are no <i>game</i> rules</td></tr>
              --><tr> 
                <td colspan="2"><input type="checkbox" name="rules" value="true">
                  I have read and agree to comply with the <a href="help.php#rules" target="_new">rules</a></td>
              </tr>
              <tr> 
                <td colspan="2"><input type="checkbox" name="cheat" value="true"> 
                  I promise not to try to gain an unfair advantage by breaking 
                  the rules</td>
              </tr>
              <tr> 
                <td colspan="2"><input type="checkbox" name="account" value="true"> 
                  This is my <b>ONLY</b> <? echo $conf["sitename"]; ?> account</td>
              </tr>
              <tr>
		<td colspan="2">Copy the text below into the adjacent box.</td>
	</tr>
	<tr>
		<td align="center"><img src="imageclick.php?<? $SID = session_name() . "=" . session_id();
	echo $SID; ?>" alt="random chars"></td>
		<td><SELECT name=turing>
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
		    
		</SELECT></td>
	</tr>
              <tr> 
                <td>Commander:</td>
                <td valign="middle"><?
	$str = "None";
	if ($cgi['uniqid']) {
		$us = getUserDetails($cgi['uniqid']);
		$str = $us->userName;
		echo "<input type=hidden name=uniqid value='{$cgi['uniqid']}'>";
		echo '<a href="stats.php?id=' . $cgi['uniqid'] . '">';
	}
	echo $str;
	if ($cgi['uniqid']) {
		echo "</a>";
	}
?>
                  <font style="font-size: 8pt;"> [Note: This can be changed 
                  once you register]</font></td>
              </tr>
              <tr> 
                <td colspan="2" align="center"><input type="submit" name=submit value="Register">   </td>
              </tr>
            </tbody>
          </table>

</form>

<?
}
include ("bottom.php");
?>	
	</td>
	</tr>
</tbody></table>
</body></html>
<? include "gzfooter.php"; ?>