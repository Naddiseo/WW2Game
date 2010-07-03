<?
$userid = $_SESSION['isLogined'] ? $_SESSION['isLogined'] : 0;
if (ENABLE_LOGGING and !$_SESSION['admin'] and HAV_DB and ($userid == 0 or $user->toTrack == 1)) {
	$save = array('POST' => $_POST, 'GET' => $_GET, 'SERVER' => $_SERVER, 'COOKIE' => $_COOKIE, 'RESTORE' => $restore_string);
	$s = serialize($save);
	unset($save);
	$time = time();
	if (is_dir(LOGGIN_DIR . "$userid")) {
		$f = fopen(LOGGIN_DIR . "$userid/$time", "w+");
		fwrite($f, $s);
		fclose($f);
	} else {
		mkdir(LOGGIN_DIR . $userid);
		$f = fopen(LOGGIN_DIR . "$userid/$time", "w+");
		fwrite($f, $s);
		fclose($f);
	}
	if (HAV_DB) {
		mysql_query("INSERT INTO log_{$conf_ltable} SET time='$time',uid='$userid',ip='$_SERVER[REMOTE_ADDR]';") or die(mysql_error());
	}
}
?>
