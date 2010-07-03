<?
function duration($time, $N = 1) {
	if ($N == 3) {
		$hour = floor($time / 3600);
		$time = $time - $hour * 3600;
		$min = floor($time / 60);
		$sec = $time - $min * 60;
		return str_pad($hour, 2, "0", STR_PAD_LEFT) . ":" . str_pad($min, 2, "0", STR_PAD_LEFT) . ":" . str_pad($sec, 2, "0", STR_PAD_LEFT);
	}
	$input['week'] = floor($time / 604800);
	$time = $time - $input['week'] * 604800;
	$input['day'] = floor($time / 86400);
	$time = $time - $input['day'] * 86400;
	$input['hour'] = floor($time / 3600);
	$time = $time - $input['hour'] * 3600;
	$input['min'] = floor($time / 60);
	$input['sec'] = $time - $input['min'] * 60;
	$output = "";
	if ($input['week']) {
		if ($input['week'] > 1) {
			$output.= $input['week'] . "wks ";
		} else {
			$output.= $input['week'] . "wk ";
		}
	}
	if ($input['day']) {
		if ($input['day'] > 1) {
			$output.= $input['day'] . "days ";
		} else {
			$output.= $input['day'] . "day ";
		}
	}
	if ($input['hour']) {
		if ($input['hour'] > 1) {
			$output.= $input['hour'] . "hours ";
		} else {
			$output.= $input['hour'] . "hour ";
		}
	}
	if ($input['min']) {
		if ($input['min'] > 1) {
			$output.= $input['min'] . "mins ";
		} else {
			$output.= $input['min'] . "min ";
		}
	}
	if ($input['sec']) {
		if ($N == 1) {
			if ($input['sec'] > 1) {
				$output.= $input['sec'] . "secs ";
			} else {
				$output.= $input['sec'] . "sec ";
			}
		} elseif (($output == NULL) && ($N == 2)) {
			if ($input['sec'] > 1) {
				$output.= $input['sec'] . "secs ";
			} else {
				$output.= $input['sec'] . "sec ";
			}
		}
	}
	if (substr($output, -1) == chr(32)) {
		return substr($output, 0, strlen($output) - 1);
	}
	return $output;
}
include ("scripts/conf.php");
if ($game_offline == "false") {
	header("Location: $conf[base_url]");
	exit();
}
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD><TITLE>Offline</TITLE>
<META http-equiv=Content-Type content="text/html; charset=iso-8859-1"><!-- ZoneLabs Privacy Insertion -->
<SCRIPT language=javascript src="js/js"></SCRIPT>
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

<META content="MSHTML 5.50.4522.1800" name=GENERATOR></HEAD>
<BODY text=#ffffff bgColor=#000000 leftMargin=0 topMargin=0 marginheight="0" 
marginwidth="0">
<? $offline = true;
function getUserDetails($id) {
	return 0;
}
include "top.php";
?>
<TABLE cellSpacing=0 cellPadding=5 width="100%" border=0>
  <TBODY>
  <TR>
    <TD class=menu_cell_repeater style="PADDING-LEFT: 15px" vAlign=top width=140>
<?
//include ("left.php");

?>

	</TD>
      <TD style="PADDING-RIGHT: 15px; PADDING-LEFT: 15px; PADDING-TOP: 12px">
       <center><?=$offline_message
?><Br />
       <Br /><a href="chat.php">IRC Chatroom</a>
       <br /><a href="forum">Forum</a></center>
    
     <?
// include ("bottom.php");

?>	
</TD></TR></TBODY></TABLE></BODY></HTML>
