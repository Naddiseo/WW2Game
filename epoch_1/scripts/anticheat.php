<?
//==================
// ANTI PAGE REFRESH
//==================
$CK = '';
$min = (int)date("i", time());
if (isset($_SESSION['hash'])) {
	eval($_SESSION['hash']);
	$_SESSION['hash'] = false;
	$lastmin = ((isset($CK['lastmin'])) ? $CK['lastmin'] : 1);
	$CK['lastmin'] = $min;
	$interval = $min - $lastmin;
	//$_SESSION['interval'] = $interval;
	$_CK['page'] = $_SERVER['PHP_SELF'];
	if (isset($CK['lastinterval']) AND $interval == $CK['lastinterval'] AND $interval != 0)
	//AND $CK['page'] == $CK['oldpage'] )// oldpage isn't set here....
	{
		$CK['count'] = $CK['count'] + 1;
		if ($CK['count'] == 10) { //They have refreshed the page 10 times at the same interval
			echo "<script>window.close();</script>"; //Try this
			$str = "http://www.google.ca/search?hl=en&q=stop+using+auto+refreshers&btnG=Google+Search&meta=";
			echo "<meta http-equiv=\"refresh\" content=\"1;url=$str\">"; // redirect to google
			$alert.= 'auto refresh';
		} elseif ($CK['count'] > 10) { //They have javascript disabled
			$str = "http://www.google.ca/search?hl=en&q=stop+using+auto+refreshers&btnG=Google+Search&meta=";
			header("Location: $str"); //Send them to google
			$CK['count'] = 0;
		}
	} else {
		$CK['count'] = 0;
	}
	$CK['lastinterval'] = $interval;
	$CK['oldpage'] = $_SERVER['PHP_SELF'];
	//=====================
	// Stop switching of IP
	//=====================
	$ip = $_SERVER[REMOTE_ADDR];
	if (isset($CK['lastip'])) {
		if ($ip != $CK['lastip'] && ($CK['time'] + (60 * 60)) >= time()) {
			//Possible IP switching
			if (isset($CK['ipcount']) && $CK['ipcount'] >= 3 && isset($_SESSION['isLogined'])) {
				mysql_query("UPDATE UserDetails SET active=3 WHERE ID='$_SESSION[isLogined]'");
			}
			$_SESSION['isLogined'] = null;
			$CK['ipcount'] = $CK['ipcount'] + 1;
			$alert.= 'ip switch';
		}
	}
	$CK['lastip'] = $ip;
	$CK['lasttime'] = time();
	//=============================
	// proxies
	//=============================
	if ($HTTP_X_FORWARDED_FOR) {
		if ($HTTP_X_FORWARDED_FOR != $ip) {
			$alert.= "proxy1";
		}
		if (strstr($HTTP_VIA, 'proxy') OR $HTTP_VIA == '') {
			$alert.= "proxy2";
		}
	}
	$proxy_query = mysql_query("SELECT count(*) FROM proxylist WHERE ip LIKE '$ip%'") or die(mysql_error());
	$proxy_array = mysql_fetch_array($proxy_query);
	if ($proxy_array[0] > 0) {
		$alert.= "proxy3";
	}
	//=================================
	//=== Auto Farm
	//=================================
	if ($cgi['peeword'] OR $cgi['usrname']) {
		$alert.= "autofarm";
	}
	$b = "$" . "CK=array(";
	foreach ($CK as $key => $value) {
		$b.= "'$key' => '$value',";
	}
	$b.= "'END'=>0);";
	$_SESSION['hash'] = $b;
	//================================
	//=  autoscripts
	//================================
	if (isset($cgi['uname']) && isset($cgi['psword'])) {
		if ($_SESSION['banpass'] == 1) {
			$_SESSION['banpass'] = null;
			$alert.= "auto-banned";
			$get = mysql_query("SELECT ID FROM UserDetails WHERE userName='$cgi[uname]' AND password='" . md5($cgi['psword']) . "'");
			$ar = mysql_fetch_array($get, MYSQL_ASSOC);
			mysql_query("UPDATE UserDetails SET active=4 WHERE ID='$ar[ID]'");
			$_SESSION['isLogined'] = null;
			mail($conf['admin_email'], "Banned - $cgi[uname]", "User $ar[ID] $cgi[uname] Banned for automated scripts");
		} else {
			$alert.= "auto-warned";
			$_SESSION['banpass'] = 1;
		}
	}
	//=================
	//  end
	//=================
	
} else {
	$b = "$" . "CK= array(
	 'lastint'=>0,
	 'oldpage'=>'$_SERVER[PHP_SELF]',
	 'count'=>0,
	 'lastmin'=>'$min',
	 'lastip'=>'$_SERVER[REMOTE_ADDR]',
	 'lasttime'=>'" . time() . "' );
	";
	$_SESSION['hash'] = $b;
}
// print_r($CK);
//==================
// END
//==================

?>
