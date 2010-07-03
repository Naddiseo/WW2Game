<center>

<table background="pic/top_background_repeater2.jpg" border="0" cellpadding="0" cellspacing="0" height="140" width="100%">
      <tbody><tr>
        <td width="140">&nbsp;
        </td>
        <td><div align="center"><a href="#"><img src="pic/banner.gif" border="0" ></a></div></td>
          <td align="right" valign="bottom" width="160">&nbsp;</td>
      </tr>
    </tbody></table>  
  
 <table background="pic/bar_repeader2.jpg" border="0" cellpadding="0" cellspacing="0" height="20" width="100%">
 <td width="100%"> 
 
 <div id="subMenu2" align="center">
 <marquee id="announcements" behavior="scroll" direction="left" width="60%" scrolldelay="60" scrollamount="2">
<?=$conf_announcement
?>
</marquee>
 </div> 
 </td>
	  
  </table>  

</center>
<?
if ($offline == true) {
} else {
	$user = getUserDetails($_SESSION['isLogined']);
} ?>