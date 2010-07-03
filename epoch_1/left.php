
<TABLE bgcolor="#000000" cellSpacing=0 cellPadding=0 width=137 border=0>
  <TBODY>
    <?php if (!$_SESSION['isLogined']) { ?>
    <TD width="209">  
	<? for ($xxx = 0;$xxx <= rand(0, 4);$xxx++) {
		echo "<form></form>";
	} ?> 
      <FORM method=post>
	    
        <INPUT type=hidden name=username><INPUT type=hidden name=pword>
        <TABLE class=small style="padding-left: 0px; padding-right: 0px; padding-top: 0px;border-color: red;border-width:10px; padding-bottom: 5px" width=130 align=center cellspacing="0" cellpadding="0">
        	<TBODY>
        		<TR>
        			<td width="204" bgcolor="#000000" bordercolor="#000000" bordercolorlight="#000000" bordercolordark="#000000">
        				<p align="left">
        					<img src="pic/navigation_r1_c1.gif" width="142" height="72">
						</p>
					</td>
        		</TR>
        		<tr>
              		<td width="204">
						<p align="center">
                  			<FONT color=White>Username:</FONT>
				  		</p>
				  	</td>
                </tr>          
            	<TR>
              		<TD align=middle width="139">
                		<INPUT class=login_input name='<? $_SESSION['uname'] = genUniqueTxt(10);
	echo $_SESSION['uname']; ?>'>
              		</TD>
            	</TR>
				<tr>
            		<TD align=middle width="139">
              			<p align="center">
                			<FONT color=White>Password:</FONT>
              			</p>
            		</TD>
            	</TR>
            	<TR>
              		<TD align=middle width="139">
                		<INPUT class=login_input type=password name='<? $_SESSION['psword'] = genUniqueTxt(10);
	echo $_SESSION['psword']; ?>'>
              		</TD>
            	</TR>
            	<TR>
              		<TD style="PADDING-TOP: 5px" align="middle" width="142">
                		<INPUT class=login_input style="WIDTH: 50px" type=submit value=Login>
              		</TD>
           	 	</TR>				
				<TR>
            		<TD class="menu_cell_repeater_vert" width="67"  align="middle" width="142">
              			<p align="center">
                			<div><A  href="register.php">Register</A></div>
              				<div><A href="forgotpass.php">Forgot Login?</A></div>
						</p>
              			<br />
              			<img src="pic/navigation_r11_c1.gif" width="142" height="10" />
					</TD>
          		</TR>         
		  </TBODY>
        </TABLE>
		
		 </FORM>
		  <? for ($xxx = 0;$xxx <= rand(0, 4);$xxx++) {
		echo "<form></form>";
	} ?>
        <?
} else { ?>
        <TABLE>
          <TR>
            <TD class="menu_cell_repeater_vert" width="142" vAlign=top   align=middle >
              <A href="index.php">
              <center>
                <img src="pic/Top.gif" width="142" height="10" border="0">
              </center></a><a href="base.php">
          <center>
             Base
          </center>
          </a>
          <a href="bank.php">
          <center>
             Treasury
          </center>
          </a>
          <hr>
          <a href="upgrades.php" >
          <center>
             Upgrades
          </center>
          </a>
          <!--<a href="nuke.php">
          <center>
             Nuclear Research
          </center>
          </a>-->
          <a href="train.php">
          <center>
             Training
          </center>
          </a>
          <a href="mercs.php">
          <center>
             Mercenaries
          </center>
          </a>
          <hr>
          
          
          <a href="battlefield.php">
          <center>
             Attack
          </center>
          </a>
          </a>
          <a href="armory.php"><center>
             Armory
          </center>
          </a>
          <a href="attacklog.php">
          <center>
             Attack Log
          </center>
          </a><a href="intel.php">
          <center>
             Intel
          </center>
          </a>
          <hr>
          <a href="logout.php">
          <center>
             Logout
          </center>
          </a>
          <img src="pic/Bottom.gif">
            </TD>
          </TR>
          <?
}
?>
          </TBODY>
        </TABLE>
        <P>
          <?
if ($_SESSION['isLogined']) {
	$userR = getUserRanks($_SESSION['isLogined']);
	if (!$user) {
		$user = getUserDetails($_SESSION['isLogined']);
	}
?>
          <TABLE  bgcolor="#000000" cellSpacing=0 cellPadding=0 width=142 border=0>
            <TR>
              <TD 
                align=center style="FONT-SIZE: 8pt; COLOR: white">
                <div align="left">
                  <img src="pic/Top.gif" width="142" height="13">
                </div>
              </TD>
              <TD style="FONT-SIZE: 8pt">&nbsp;
                 
              </TD>
            </TR>
            <TBODY>
              <td width="142" align="middle" valign="top" bgcolor="#4d4d4d" style="border-left-color : #ac7c28; border-left-style : solid; border-left-width : 5px; border-right-color : #ac7c28; border-right-style : solid; border-right-width : 5px; display : table-cell; overflow : hidden; padding-left : 2px; padding-right : 2px; width : 142px;">
                <TABLE bordercolor="#C1C1C1"  cellSpacing=0 cellPadding=3>
                  <TBODY>
                    <TR>
                      <TD width="41" align="left" style="FONT-SIZE:11px; COLOR: white">
                         Rank:
                      </TD>
                      <TD width="67" style="FONT-SIZE: 11px">
                        <? numecho($userR->rank)
?>
                      </TD>
                    </TR>
                    <TR>
                      <TD style="FONT-SIZE: 11px; COLOR: white" align="left">
                         Turns:
                      </TD>
                      <TD style="FONT-SIZE: 11px">
                        <FONT color=white><? numecho($user->attackturns) ?></FONT>
                      </TD>
                    </TR>
                    <TR>
                      <TD style="FONT-SIZE: 11px; COLOR: white" align="left">
                         Gold:
                      </TD>
                      <TD style="FONT-SIZE: 11px">
                        <FONT color=white>
                <? numecho($user->gold) ?>
                        </FONT>
                      </TD>
                    </TR>
                    <TR>
                      <TD style="FONT-SIZE: 11px; COLOR: white" align=left>
                         <a href="bank.php" style="color: white; font-size: 11px; font-weight: normal;">Treasury:</a>
                      </TD>
                      <TD style="FONT-SIZE: 11px">
                        <FONT color=white size="small"><a href="bank.php" style="color: white; font-size: 11px;font-weight: normal;">
                <? numecho($user->bank) ?></a>
                        </FONT>
                      </TD>
                    </TR>
                    <TR>
                      <TD style="FONT-SIZE: 11px; COLOR: white" align=left>
                         Experience:
                      </TD>
                      <TD style="FONT-SIZE: 11px">
                        <FONT color=white>
                <? numecho($user->exp) ?>
                        </FONT>
                      </TD>
                    </TR>
                    <TR>
                      <TD style="FONT-SIZE: 11px; COLOR: white" align=left>
                         Next Turn:
                      </TD>
                      <TD style="FONT-SIZE: 11px">
                        <div id="idTimer" style="color: white;">
                          <span id="idMin" style="font-weight: bold; font-size: 11px;">Getting Time</span>:<span id="idSec" style="font-weight: bold; font-size: 8pt;"></span>
                        </div>
                        <script language="JavaScript" src="javafunctions.js">
								 
				
				</script><script language="JavaScript">
				
					StartTimer();
					
					
					</script>
				
				<?
	$temp = explode(":", $nextTurnMin = getNextTurn($user));
	$min = intval($temp[0]);
	$sec = intval($temp[1]);
	echo "<script language=\"JavaScript\"> \n";
	echo "<!-- \n";
	echo "var min=$min; \n";
	echo "var sec=$sec; \n";
	echo "-->\n";
	echo "</script> \n";
	//$nextTurnMin=round($nextTurnMin/60);
	
?>
                      </TD>
                    </TR>
                    <TR>
                      <TD height="24"  align=left style="FONT-SIZE: 10px; COLOR: white">
                         Messages:
                      </TD>
                      <TD >
                        <div>
                          <A class="messagesUR" href="messages.php">
                          	<?=getNewMessageCount($user->ID) ?>                          	
                          </A>
                         
                          <A class="messagesR" href="messages.php">&nbsp;(<?=getMessagesCount($user->ID) ?>) Read</A>
                          
                          </div>
                          </TD>
                          </TR>
                          </TBODY>
                          </TABLE>
                          </TD>
                          </TR>
                          </TBODY>
                          <TR>
                            <TD style="FONT-SIZE: 6pt; COLOR: white" 
                align=center>
                              <div align="left">
                                <img src="pic/Bottom.gif" width="142" height="12">
                              </div>
                            </TD>
                            <TD style="FONT-SIZE: 6pt">&nbsp;
                               
                            </TD>
                          </TR>
                          </TABLE>
                          <?
}
?>
                          <p>
                            <TABLE bgcolor="#000000" cellSpacing=0 cellPadding=0 width=142 border=0>
                              <TBODY>
                                <TR>
                                  <TD class=menu_cell_repeater_vert>
                                    <div align="left">
                                      <img src="pic/Top.gif" width="142" height="13">
                                    </div>
                                  </TD>
                                </TR>
                                <TR>
                                  <TD class=menu_cell_repeater_vert>
                                    <P align=center>
                                      <FONT color=#ff0000><a href="online.php">
          <?=getOnlineUsersCount() /* + $number=rand(100,105)*/;;
?> online</a> </FONT>
                                    </P>
                                    <p align="center">
                                      <a href='battlefield.php?page=1'>Rankings</a>
                                    </p>
                                    <p align="center">
                                      <a href='statistics.php'>Statistics</a>
                                    </p>
                                    <p align="center"><a href='hof.php'>Previous Age Statistics</a></p>
                                    <p align="center">
                                      <a href='chat.php' target="_blank">Chat</a>
                                    </p>
									 <p align="center">
                                      <a href='wiki' target="_blank">Help</a>
                                    </p>
                                    <p align="center">
                                      <a href='http://ww2game.net/forum'>Forum</a>
                                    </p>
                                  </TD>
                                </TR>
                                <TR>
                                  <TD class=menu_cell_repeater_vert>
                                    <div align="left">
                                      <img src="pic/Bottom.gif" width="142" height="12">
                                    </div>
                                  </TD>
                                </TR>
                                <tr>
                                </tr>
                              </TBODY>
                            </TABLE>
                          </p>
                          <p>
                            <TABLE bgcolor="#000000" cellSpacing=0 cellPadding=0 width=142 border=0>
                              <TBODY>
                                <TR>
                                  <TD class=menu_cell_repeater_vert>
                                    <div align="left">
                                      <img src="pic/Top.gif" width="142" height="13">
                                    </div>
                                  </TD>
                                </TR>
                                <TR>
                                  <TD class=menu_cell_repeater_vert>
                                    <P align=center>
                                      <FONT color=#ff0000>
          <?
echo gmdate("l");
echo "<br>";
echo (gmdate("jS F y"));
echo "<br>";
echo (gmdate("h:i A T"));
echo " ";
/* june 9, 06. Fixed the wrong date; server is using EST+12 as unix time... */
?>
                                      </FONT>
                                    </P>
                                  </TD>
                                </TR>
                                <TR>
                                  <TD class=menu_cell_repeater_vert>
                                    <div align="left">
                                      <img src="pic/Bottom.gif" width="142" height="12">
                                    </div>
                                  </TD>
                                </TR>
                                <tr>
                                </tr>
                              </TBODY>
                            </TABLE>
                          </p>
                         
                          <p>
                            <TABLE bgcolor="#000000" cellSpacing=0 cellPadding=0 width=142 border=0>
                              <TBODY>
                                <TR>
                                  <TD class=menu_cell_repeater_vert>
                                    <div align="left">
                                      <img src="pic/Top.gif" width="142" height="13">
                                    </div>
                                  </TD>
                                </TR>
                                <TR>
                                  <TD class=menu_cell_repeater_vert>
                                    <div align=center border="0" style="color: white;">
                                      
                                    </div>
                                  </TD>
                                </TR>
                                <TR>
                                  <TD class=menu_cell_repeater_vert>
                                    <div align="left">
                                      <img src="pic/Bottom.gif" width="142" height="12">
                                    </div>
                                  </TD>
                                </TR>
                                <tr>
                                </tr>
                              </TBODY>
                            </TABLE>
                          </p>






