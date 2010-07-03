<CENTER>
      <P>
      <P><FONT style="FONT-SIZE: 8pt">| <A 
      href="spam.php">Report Spam</A> | <A 
      href="privacy.php">Privacy Policy</A> | <A 
      href="advertising.php">Advertising</A> | <A 
      href="mailto: admin@example.net">Business Queries</A> | <A 
      href="tos.php">Terms of Service</A> | 

      <P><!-- see README for credits -->
        <I>Copyright ww2game.net 2005-2007<BR>
<? echo $conf["sitename"]; ?>, All rights reserved.</I><BR></P></FONT></CENTER>
<?
$ip = $_SERVER['REMOTE_ADDR'];
$time = time() - (60 * 60 * 24);
include ('logger.php');
?>
