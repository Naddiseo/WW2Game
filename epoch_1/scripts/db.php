<?
$db = mysql_pconnect('localhost', $db_user, $db_pass);
if (!$db) {
	define("HAV_DB", false);
	die(mysql_error());
}
if (!mysql_select_db($db_database, $db)) {
	$str = mysql_error();
	define("HAV_DB", false);
	if ($str) die($str);
}
?>