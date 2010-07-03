<?
if ($_SESSION['isLogined'] > 0 or $_SESSION['admin']) {
	//echo "LOGINNED";
	
} else {
	echo "<br><br><center><font color=red>You are not logged in. Please login again.</font></center><br><br><br><br>";
	include "bottom.php";
	exit;
}
?>
