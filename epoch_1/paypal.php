<?php
/*  PHP Paypal IPN Integration Class Demonstration File
 *  4.16.2005 - Micah Carrick, email@micahcarrick.com
 *
 *  This file demonstrates the usage of paypal.class.php, a class designed
 *  to aid in the interfacing between your website, paypal, and the instant
 *  payment notification (IPN) interface.  This single file serves as 4
 *  virtual pages depending on the "action" varialble passed in the URL. It's
 *  the processing page which processes form data being submitted to paypal, it
 *  is the page paypal returns a user to upon success, it's the page paypal
 *  returns a user to upon canceling an order, and finally, it's the page that
 *  handles the IPN request from Paypal.
 *
 *  I tried to comment this file, aswell as the acutall class file, as well as
 *  I possibly could.  Please email me with questions, comments, and suggestions.
 *  See the header of paypal.class.php for additional resources and information.
*/
//die("Script Not in use");
// Setup class
include ("scripts/vsys.php");
require_once ('paypal.class.php'); // include the class file
$p = new paypal_class; // initiate an instance of the class
//$p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';   // testing paypal url
$p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr'; // paypal url
// setup a variable for this script (ie: 'http://www.micahcarrick.com/paypal.php')
$this_script = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
// if there is not action variable, set the default action of 'process'
if (empty($_GET['action'])) $_GET['action'] = 'display';
switch ($_GET['action']) {
	case 'display':
		if (!$_SESSION['isLogined']) {
			header("Location: index.php");
			exit;
		}
?>
   
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD><TITLE><? echo $conf["sitename"]; ?> :: 
PayPal Donate</TITLE>
<META http-equiv=Content-Type content="text/html; charset=iso-8859-1">
<LINK href="css/common.css" type=text/css rel=stylesheet>

<SCRIPT language="javascript" type="text/javascript">
		<!--
		function checkCR(evt) {
		var evt = (evt) ? evt : ((event) ? event : null);
		var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
		if ((evt.keyCode == 13) && (node.type=="text")) {return false;}
		}
		document.onkeypress = checkCR;
		
		function checkpaypal(val) {
			if(val.indexOf('ad')>-1 && val != 'ad') {
				alert('For alliance donations you do not need to put your own ID in, only the word "ad" (without the quotations)');
				return false;
			}
			return true;
		}
		
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
    vAlign=top align=left><? include "islogined.php";
		if ($_GET['strErr']) {
			echo "<font color=red><b>$_GET[strErr]</b></font>";
		} ?> <BR>
    <p>Donating to WW2 helps us keep the servers up and running, and keeps this game free.</p>
    <p style="color: red;">
    IMPORTANT: ALL payments are automated, and require you to complete the purchasing process.

	DO NOT close the browser before you are returned to the confirmation page!!.	
	If you do not follow the instructions you will not get supporter bonuses!
	</p>
	<p>You can donate for other players, to do this you will need to put their user ID below. A player's ID can be found
	from their stats page in the URL: http://<?=$_SERVER['HTTP_HOST'] ?>/stats.php?id=<b>123456</b></p>
	<p>For alliance donations please type in <b>just</b> &quot;ad&quot; for the user ID</p>
	<p>We will accept a minimum donation of $1USD for supporter bonuses, but because of paypal's fee $1.30 would be better.</p>
	<form action="paypal.php" method="GET" onsubmit="return checkpaypal(document.getElementById('forId').value);">
		<input type="hidden" name="action" value="process" />
		User ID:<input type="text" id="forId" name="forID" <? if ($cgi['ad']) {
			echo "value='ad'";
		} ?> />(Your ID is <?=$_SESSION['isLogined'] ?>)<br />
		
		<!--Game Version:<select name="gVersion">
			<option value="main">WW2 Main</option>
			<option value="reset">WW2 Reset</option>
		</select>-->
		<br />
		<input type="submit" value="Donate" />		
	</form>
        
	
	<?
		include ("bottom.php");
?>	
	 </TD></TR></TBODY></TABLE>
</BODY></HTML>

   <?
	break;
	case 'process': // Process and order...
		// There should be no output at this point.  To process the POST data,
		// the submit_paypal_post() function will output all the HTML tags which
		// contains a FORM which is submited instantaneously using the BODY onload
		// attribute.  In other words, don't echo or printf anything when you're
		// going to be calling the submit_paypal_post() function.
		// This is where you would have your form validation  and all that jazz.
		// You would take your POST vars and load them into the class like below,
		// only using the POST values instead of constant string expressions.
		// For example, after ensureing all the POST variables from your custom
		// order form are valid, you might have:
		//
		// $p->add_field('first_name', $_POST['first_name']);
		// $p->add_field('last_name', $_POST['last_name']);
		if (is_numeric($_GET['forID'])) {
			$uid = $_GET['forID'];
			$q = mysql_query("SELECT userName FROM UserDetails WHERE id={$uid}") or die(mysql_error());
			$a = mysql_fetch_object($q);
			$aname = str_replace(':', '_', $a->userName);
		} elseif ($_GET['forID'] == 'ad' OR $_GET['forID'] == '"ad"') {
			$ad = true;
			$q = mysql_query("SELECT u.alliance,a.name FROM UserDetails u,alliances a WHERE a.id=u.alliance AND u.id={$_SESSION[isLogined]}") or die(mysql_error());
			$a = mysql_fetch_object($q);
			$allid = $a->alliance;
			if ($allid == 0) {
				header("Location: $this_script?strErr=You do not belong to an alliance!");
				exit;
			}
		} else {
			header("Location: $this_script?strErr=No User ID Specified!");
			exit;
		}
		$p->add_field('business', $conf['admin_email']); //my email
		$p->add_field('return', $this_script . '?action=success');
		$p->add_field('cancel_return', $this_script . '?action=cancel');
		$p->add_field('notify_url', $this_script . '?action=ipn');
		if ($ad) {
			$p->add_field('item_name', "Supporter: AID($allid:{$a->name})");
		} else {
			$p->add_field('item_name', "Supporter: ID($uid:{$aname})");
		}
		$p->add_field('no_shipping', '1');
		$p->add_field('currency_code', 'USD');
		$p->add_field('no_note', '1');
		//$p->add_field('amount', '1.99');
		$p->submit_paypal_post(); // submit the fields to paypal
		//$p->dump_fields();      // for debugging, output a table of all the fields
		
	break;
	case 'success': // Order was successful...
		// This is where you would probably want to thank the user for their order
		// or what have you.  The order information at this point is in POST
		// variables.  However, you don't want to "process" the order until you
		// get validation from the IPN.  That's where you would have the code to
		// email an admin, update the database with payment status, activate a
		// membership, etc.
		//echo "<html><head><title>Success</title></head><body><h3>Thank you for your order.</h3>";
		//$uid=0;
		//foreach ($_POST as $key => $value) {
		// echo "$key: $value<br>";
		//if($key=='item_name'){
		//	$i=strpos($value,"ID(");
		//	$j=strpos($value,")",$i);
		//	$uid=substr($value,$i+3,$j-($i+3));
		//}
		//}
		// echo $uid;
		//echo "</body></html>";
		// You could also simply re-direct them to another page, or your own
		// order status page which presents the user with the status of their
		// order based on a database (which can be modified with the IPN code
		// below).
		//mysql_query("INSERT INTO donations SET date='$_POST[payment_date]',
		//amount='$_POST[payment_gross]',uid='$uid',details='".(addslashes(serialize($_POST)))."'");
		//if($_POST['payment_gross']>=1){
		//	UpdateUser($uid,"supporter=supporter+1,exp=exp+500");
		//}
		header("Location: paypal.php?strErr=Donation Successful, awaiting confirmation from paypal! Thank you for donating!");
	break;
	case 'cancel': // Order was canceled...
		// The order was canceled before being completed.
		echo "<html><head><title>Canceled</title></head><body><h3>The order was canceled.</h3>";
		echo "</body></html>";
	break;
	case 'ipn': // Paypal is calling page for IPN validation...
		// It's important to remember that paypal calling this script.  There
		// is no output here.  This is where you validate the IPN data and if it's
		// valid, update your database to signify that the user has payed.  If
		// you try and use an echo or printf function here it's not going to do you
		// a bit of good.  This is on the "backend".  That is why, by default, the
		// class logs all IPN data to a text file.
		if ($p->validate_ipn()) {
			$email = print_r($p, true);
			$uid = 0;
			$value = $p->ipn_data['item_name'];
			if (strpos($value, 'AID') > 0) {
				$isall = true;
			} else {
				$isall = false;
			}
			$email.= "isall=$isall.\n";
			$i = strpos($value, "ID(");
			$email.= "i=$i.\n";
			$j = strpos($value, ")", $i);
			$email.= "j=$j.\n";
			$str = substr($value, $i + 3, $j - ($i + 3));
			$email.= "str=$str.\n";
			$unid = explode(':', $str);
			$email.= "unid=" . print_r($unid, true) . ".\n";
			$uid = $unid[0];
			$uname = $unid[1];
			$email.= "uid=$uid.\n";
			$email.= "uname=$uname.\n";
			mysql_query("INSERT INTO donations SET date='{$p->ipn_data[payment_date]}',
 		amount='$p->ipn_data[payment_gross]',uid='$uid',details='" . (addslashes(serialize($_POST))) . "'");
			if ($p->ipn_data['payment_gross'] >= 1 AND !$isall) {
				UpdateUser($uid, "supporter=supporter+1,exp=exp+500");
			} elseif ($p->ipn_data['payment_gross'] >= 1 AND $isall) {
				$all = "UPDATE alliances SET donated=donated+" . floatval($p->ipn_data['payment_gross']) . " WHERE id=$uid";
				$email.= $all . "\n";
				@mysql_query($all);
			}
			$s = '';
			$vs = array();
			$ks = array();
			foreach ($p->ipn_data as $key => $value) {
				//$s .= "\n$key= \"$value\"";
				$ks[] = $key;
				$vs[] = "\"" . addslashes($value) . "\"";
			}
			$sales = "INSERT INTO sales SET (" . implode(',', $ks) . ") VALUES(" . implode(',', $vs) . ")";
			$email.= $sales;
			@mysql_query($sales);
			// Payment has been recieved and IPN is verified.  This is where you
			// update your database to activate or process the order, or setup
			// the database with the user's order details, email an administrator,
			// etc.  You can access a slew of information via the ipn_data() array.
			// Check the paypal documentation for specifics on what information
			// is available in the IPN POST variables.  Basically, all the POST vars
			// which paypal sends, which we send back for validation, are now stored
			// in the ipn_data() array.
			// For this example, we'll just email ourselves ALL the data.
			$subject = 'Instant Payment Notification - Recieved Payment';
			$to = 'admin@example.net'; //  your email
			$body = "An instant payment notification was successfully recieved\n";
			$body.= "from " . $p->ipn_data['payer_email'] . " on " . date('m/d/Y');
			$body.= " at " . date('g:i A') . "\n\nDetails:\n";
			foreach ($p->ipn_data as $key => $value) {
				$body.= "\n$key: $value";
			}
			mail($to, $subject, $body);
			//header("Location: paypal.php?strErr=Donation Successful! Thank you for donating!");
			
		}
	break;
}
?>
