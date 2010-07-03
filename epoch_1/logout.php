<?
session_start();
$_SESSION['isLogined'] = 0;
header("Location: index.php");
?>