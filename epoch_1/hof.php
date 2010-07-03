<? include "gzheader.php";
include "scripts/vsys.php";
if (!isset($cgi['age']) OR !is_numeric($cgi['age']) OR $cgi['age'] < 0.0) {
	$cgi['age'] = 1;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"
>
<HTML>
    <HEAD>
        <TITLE><? echo $conf["sitename"]; ?> ::Hall of Fame</TITLE>
        <META http-equiv=Content-Type content="text/html; charset=iso-8859-1">
        <LINK href="css/common.css" type=text/css rel=stylesheet>
        <STYLE type="text/css">


    table.lines TD {
        font-size : 11px;
        text-align: center;
        border: 1px solid #888888;
	border-top: 0;
	color: #ffffff; 
	margin: 0; 
    }
    table.lines TH {
        font-size : 13px;
        text-align: center;
        border: 1px solid #888888;
	border-top: 0;
        background-color: gray;
	color: #3A4F6C; 
	margin: 0; 
    }
    table.lines{
         width:85%;
        
    
    }

</STYLE>
       
        <META content="MSHTML 5.50.4522.1800" name=GENERATOR>
    </HEAD>
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
                    <TD style="PADDING-RIGHT: 15px; PADDING-LEFT: 15px; PADDING-TOP: 12px" vAlign=top align=left>
                        <BR>
                      
                        Please Select an Age:<br>
                        <form name=age_form >
                        <select name="age" onchange="document.age_form.submit();">
                        <OPTION value=1 <? if ($cgi['age'] == 1) {
	echo "selected='selected'";
} ?>>Age 1</OPTION>
					
                        </select>
                        </form>
                        <br>
                        <form name=type_form >
                        <input type="hidden" value="<?=$cgi['age'] ?>" name="age" />
                        <select name="type"  onchange="document.type_form.submit();">
                                <OPTION value=-1>--</OPTION>
                                <OPTION value=0>Ranks</OPTION>
                                <OPTION value=1>Top Stats</OPTION>
                                <OPTION value=2>Race Stats</OPTION>
                                </select></form> <br><br> <center>
                                   <? if ($cgi['type'] == 0) {
	echo "<table class=\"lines\"><TR><Th >Rank</Th><TH class=subh>UserName</TH><TH >Nation</TH><TH >Army Size</TH><TH >Attack</TH><TH >Defence</TH><TH >Covert</TH><TH >Retaliation</TH></TR>";
	$query = mysql_query("SELECT ((sarank+darank+carank+rarank)/4) AS rf,hof" . $cgi['age'] . ".* FROM hof" . $cgi['age'] . " ORDER BY rf ASC limit 0,100") or die(mysql_error());
	$i = 1;
	while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
		echo "<Tr><td>";
		echo $i;
		echo "</td><td>";
		echo $row['username'];
		echo "</td><td>";
		echo $conf["race"][$row['race']]["name"];
		echo "</td><td>";
		echo numecho($row['untrained'] + $row['trainedsa'] + $row['trainedda'] + $row['spies'] + $row['sf'] + $row['samerc'] + $row['damerc']);
		echo "</td><td>";
		echo numecho($row['sa']) . " <br><small>Ranked: " . $row['sarank'] . "</small>";
		echo "</td><td>";
		echo numecho($row['da']) . " <br><small>Ranked: " . $row['darank'] . "</small>";
		echo "</td><td>";
		echo numecho($row['ca']) . " <br><small>Ranked: " . $row['carank'] . "</small>";
		echo "</td><td>";
		echo numecho($row['ra']) . " <br><small>Ranked: " . $row['rarank'] . "</small>";
		echo "</td></tr>";
		$i++;
	}
	echo "</table>";
} elseif ($cgi['type'] == 1) {
	echo "<center><b>Top Strike Action</b></center>";
	echo "<table  class=\"lines\"><TR><Th >Rank</Th><TH class=subh>User</TH><TH >Strike Action</TH><TH >Nation</TH></TR>";
	$query = mysql_query("SELECT username, sa,sarank,race FROM hof" . $cgi['age'] . " order by sarank asc LIMIT 0,10") or die(mysql_error());
	while ($row = mysql_fetch_array($query)) {
		echo "<tr><td>$row[sarank]</td><td>$row[username]</td><td>";
		numecho($row[sa]);
		echo "</td><td>{$conf['race'][$row[race]]['name']}</td></tr>";
	}
	echo "</table><br>";
	echo "<center><b>Top Defensive Action</b></center>";
	echo "<table  class=\"lines\"><TR><Th >Rank</Th><TH class=subh>User</TH><TH >Defence Action</TH><TH >Nation</TH></TR>";
	$query = mysql_query("SELECT username, da,race, darank FROM hof" . $cgi['age'] . " order by darank asc LIMIT 0,10") or die(mysql_error());
	while ($row = mysql_fetch_array($query)) {
		echo "<tr><td>$row[darank]</td><td>$row[username]</td><td>";
		numecho($row[da]);
		echo "</td><td>{$conf['race'][$row[race]]['name']}</td></tr>";
	}
	echo "</table><br>";
	echo "<center><b>Top Covert Action</b></center>";
	echo "<table  class=\"lines\"><TR><Th >Rank</Th><TH class=subh>User</TH><TH >Covert Action</TH><TH >Nation</TH><th>Spies</th><th>Covert Skill</th></TR>";
	$query = mysql_query("SELECT username, ca,race,spies,caupgrade, carank FROM hof" . $cgi['age'] . " order by ca desc LIMIT 0,10") or die(mysql_error());
	while ($row = mysql_fetch_array($query)) {
		echo "<tr><td>$row[carank]</td><td>$row[username]</td><td>";
		numecho($row[ca]);
		echo "</td><td>{$conf['race'][$row[race]]['name']}</td><td>";
		numecho($row[spies]);
		echo "</td><td>$row[caupgrade]</td></tr>";
	}
	echo "</table><br>";
	echo "<center><b>Top Retaliation Action</b></center>";
	echo "<table  class=\"lines\"><TR><Th >Rank</Th><TH class=subh>User</TH><TH >Retailiation Action</TH><TH >Nation</TH><th>Special Forces Operatives</th><th>Special Forces Level</th></TR>";
	$query = mysql_query("SELECT username, ra,race,sf,raupgrade, rarank FROM hof" . $cgi['age'] . " order by rarank asc LIMIT 0,10") or die(mysql_error());
	while ($row = mysql_fetch_array($query)) {
		echo "<tr><td>$row[rarank]</td><td>$row[username]</td><td>";
		numecho($row[ra]);
		echo "</td><td>{$conf['race'][$row[race]]['name']}</td><td>";
		numecho($row[sf]);
		echo "</td><td>$row[raupgrade]</td></tr>";
	}
	echo "</table><br>";
	$i = 1;
	echo "<center><b>Lowest Bank Percentage</b></center>";
	echo "<table  class=\"lines\"><TR><Th >Rank</Th><TH class=subh>User</TH><TH >Bank Percentage</TH></TR>";
	$query = mysql_query("SELECT username, bankper FROM hof" . $cgi['age'] . " order by bankper asc LIMIT 0,5") or die(mysql_error());
	while ($row = mysql_fetch_array($query)) {
		echo "<tr><td>$i</td><td>$row[username]</td><td>";
		numecho($row[bankper]);
		echo "</td></tr>";
		$i++;
	}
	echo "</table><br>";
	$i = 1;
	echo "<center><b>Biggest Armies</b></center>";
	echo "<table  class=\"lines\"><TR><Th >Rank</Th><TH class=subh>User</TH><TH >Army Size</TH><TH >Nation</TH></TR>";
	$query = mysql_query("SELECT username, (untrained+trainedsa+trainedda+spies+sf) as armysize,race FROM hof" . $cgi['age'] . " order by armysize desc LIMIT 0,10") or die(mysql_error());
	while ($row = mysql_fetch_array($query)) {
		echo "<tr><td>$i</td><td>$row[username]</td><td>";
		numecho($row[armysize]);
		echo "</td><td>{$conf['race'][$row[race]]['name']}</td></tr>";
		$i++;
	}
	echo "</table><br>";
	$i = 1;
	echo "<center><b>Highest Unit Production</b></center>";
	echo "<table  class=\"lines\"><TR><Th >Rank</Th><TH class=subh>User</TH><TH >Unit Production</TH></TR>";
	$query = mysql_query("SELECT username, up FROM hof" . $cgi['age'] . " order by up desc LIMIT 0,10") or die(mysql_error());
	while ($row = mysql_fetch_array($query)) {
		echo "<tr><td>$i</td><td>$row[username]</td><td>";
		numecho($row[up]);
		echo "</td></tr>";
		$i++;
	}
	echo "</table><br>";
	$i = 1;
	echo "<center><b>Biggest Officer Bonus Unit Production</b></center>";
	echo "<table  class=\"lines\"><TR><Th >Rank</Th><TH class=subh>User</TH><TH >Unit Production</TH></TR>";
	$query = mysql_query("SELECT username, offup FROM hof" . $cgi['age'] . " order by offup desc LIMIT 0,10") or die(mysql_error());
	while ($row = mysql_fetch_array($query)) {
		echo "<tr><td>$i</td><td>$row[username]</td><td>";
		numecho($row[offup]);
		echo "</td></tr>";
		$i++;
	}
	echo "</table><br>";
	$i = 1;
	echo "<center><b>Most Gold Won</b></center>";
	echo "<table  class=\"lines\"><TR><Th >Rank</Th><TH class=subh>User</TH><TH >Gold Won</TH></TR>";
	$query = mysql_query("SELECT username, goldwon FROM hof" . $cgi['age'] . " order by goldwon desc LIMIT 0,10") or die(mysql_error());
	while ($row = mysql_fetch_array($query)) {
		echo "<tr><td>$i</td><td>$row[username]</td><td>";
		numecho($row[goldwon]);
		echo "</td></tr>";
		$i++;
	}
	echo "</table><br>";
	$i = 1;
	echo "<center><b>Most Gold Lost</b></center>";
	echo "<table  class=\"lines\"><TR><Th >Rank</Th><TH class=subh>User</TH><TH >Gold Lost</TH></TR>";
	$query = mysql_query("SELECT username, goldlost FROM hof" . $cgi['age'] . " order by goldlost desc LIMIT 0,10") or die(mysql_error());
	while ($row = mysql_fetch_array($query)) {
		echo "<tr><td>$i</td><td>$row[username]</td><td>";
		numecho($row[goldlost]);
		echo "</td></tr>";
		$i++;
	}
	echo "</table><br>";
	$i = 1;
	echo "<center><b>Most Battles Won</b></center>";
	echo "<table  class=\"lines\"><TR><Th >Rank</Th><TH class=subh>User</TH><TH >Battles Won</TH><th>Nation</th></TR>";
	$query = mysql_query("SELECT username, battleswon,race FROM hof" . $cgi['age'] . " order by battleswon desc LIMIT 0,10") or die(mysql_error());
	while ($row = mysql_fetch_array($query)) {
		echo "<tr><td>$i</td><td>$row[username]</td><td>";
		numecho($row[battleswon]);
		echo "</td><td>{$conf['race'][$row[race]]['name']}</td></tr>";
		$i++;
	}
	echo "</table><br>";
	$i = 1;
	echo "<center><b>Most Battles Lost</b></center>";
	echo "<table  class=\"lines\"><TR><Th >Rank</Th><TH class=subh>User</TH><TH >Battles Lost</TH><th>Nation</th></TR>";
	$query = mysql_query("SELECT username, battleslost,race FROM hof" . $cgi['age'] . " order by battleslost desc LIMIT 0,10") or die(mysql_error());
	while ($row = mysql_fetch_array($query)) {
		echo "<tr><td>$i</td><td>$row[username]</td><td>";
		numecho($row[battleslost]);
		echo "</td><td>{$conf['race'][$row[race]]['name']}</td></tr>";
		$i++;
	}
	echo "</table><br>";
	$i = 1;
	echo "<center><b>Most Battles Defended</b></center>";
	echo "<table  class=\"lines\"><TR><Th >Rank</Th><TH class=subh>User</TH><TH >Battles Defended</TH><th>Nation</th></TR>";
	$query = mysql_query("SELECT username, battlesdefended,race FROM hof" . $cgi['age'] . " order by battlesdefended desc LIMIT 0,10") or die(mysql_error());
	while ($row = mysql_fetch_array($query)) {
		echo "<tr><td>$i</td><td>$row[username]</td><td>";
		numecho($row[battlesdefended]);
		echo "</td><td>{$conf['race'][$row[race]]['name']}</td></tr>";
		$i++;
	}
	echo "</table><br>";
	$i = 1;
	echo "<center><b>Highest Income</b></center>";
	echo "<table  class=\"lines\"><TR><Th >Rank</Th><TH class=subh>User</TH><TH >Income</TH><th>Nation</th></TR>";
	$query = mysql_query("SELECT username, income,race FROM hof" . $cgi['age'] . " order by income desc LIMIT 0,10") or die(mysql_error());
	while ($row = mysql_fetch_array($query)) {
		echo "<tr><td>$i</td><td>$row[username]</td><td>";
		numecho($row[income]);
		echo "</td><td>{$conf['race'][$row[race]]['name']}</td></tr>";
		$i++;
	}
	echo "</table><br>";
	$i = 1;
	echo "<center><b>Theft Score</b></center>";
	echo "<table  class=\"lines\"><TR><Th >Rank</Th><TH class=subh>User</TH><TH >Score</TH><th>Nation</th></TR>";
	$query = mysql_query("SELECT username, thieftscore,race FROM hof" . $cgi['age'] . " order by thieftscore desc LIMIT 0,10") or die(mysql_error());
	while ($row = mysql_fetch_array($query)) {
		echo "<tr><td>$i</td><td>$row[username]</td><td>";
		numecho($row[thieftscore]);
		echo "</td><td>{$conf['race'][$row[race]]['name']}</td></tr>";
		$i++;
	}
	echo "</table><br>";
	$i = 1;
	echo "<center><b>Gold Stolen With Theft</b></center>";
	echo "<table  class=\"lines\"><TR><Th >Rank</Th><TH class=subh>User</TH><TH >Amount</TH>";
	$query = mysql_query("SELECT username, thieftgold FROM hof" . $cgi['age'] . " order by thieftgold desc LIMIT 0,10") or die(mysql_error());
	while ($row = mysql_fetch_array($query)) {
		echo "<tr><td>$i</td><td>$row[username]</td><td>";
		numecho($row[thieftgold]);
		echo "</td></tr>";
		$i++;
	}
	echo "</table><br>";
	$i = 1;
	echo "<center><b>Hostages Taken With Theft</b></center>";
	echo "<table  class=\"lines\"><TR><Th >Rank</Th><TH class=subh>User</TH><TH >Amount</TH>";
	$query = mysql_query("SELECT username, thieftuu FROM hof" . $cgi['age'] . " order by thieftuu desc LIMIT 0,10") or die(mysql_error());
	while ($row = mysql_fetch_array($query)) {
		echo "<tr><td>$i</td><td>$row[username]</td><td>";
		numecho($row[thieftuu]);
		echo "</td></tr>";
		$i++;
	}
	echo "</table><br>";
} elseif ($cgi['type'] == 2) {
	echo "<center><b>Race Ratio</b></center>";
	echo "<table width=\"100%\" class=\"lines\"><TR><Th >Nation</Th><TH class=subh>Total</TH><TH >Percent</TH></TR>";
	$query = mysql_query("SELECT count(*) FROM hof" . $cgi['age'] . "") or die(mysql_error());
	$a = mysql_fetch_array($query);
	$total = $a[0];
	$query = mysql_query("SELECT count(*) FROM hof" . $cgi['age'] . " WHERE race='0'") or die(mysql_error());
	$a = mysql_fetch_array($query);
	$usa = $a[0];
	$query = mysql_query("SELECT count(*) FROM hof" . $cgi['age'] . " WHERE race='1'") or die(mysql_error());
	$a = mysql_fetch_array($query);
	$uk = $a[0];
	$query = mysql_query("SELECT count(*) FROM hof" . $cgi['age'] . " WHERE race='2'") or die(mysql_error());
	$a = mysql_fetch_array($query);
	$jap = $a[0];
	$query = mysql_query("SELECT count(*) FROM hof" . $cgi['age'] . " WHERE race='3'") or die(mysql_error());
	$a = mysql_fetch_array($query);
	$germ = $a[0];
	$query = mysql_query("SELECT count(*) FROM hof" . $cgi['age'] . " WHERE race='4'") or die(mysql_error());
	$a = mysql_fetch_array($query);
	$ussr = $a[0];
	echo "<tr><td>{$conf['race'][0]['name']}</td><td>$usa</td><td>" . round($usa / $total * 100) . "%</td></tr>";
	echo "<tr><td>{$conf['race'][1]['name']}</td><td>$uk</td><td>" . round($uk / $total * 100) . "%</td></tr>";
	echo "<tr><td>{$conf['race'][2]['name']}</td><td>$jap</td><td>" . round($jap / $total * 100) . "%</td></tr>";
	echo "<tr><td>{$conf['race'][3]['name']}</td><td>$germ</td><td>" . round($germ / $total * 100) . "%</td></tr>";
	echo "<tr><td>{$conf['race'][4]['name']}</td><td>$ussr</td><td>" . round($ussr / $total * 100) . "%</td></tr>";
	echo "</table>";
	echo "<center><b>Top 10 {$conf['race'][0]['name']}</b></center>";
	echo "<table width=\"100%\" class=\"lines\"><TR><Th >Rank</Th><TH class=subh>UserName</TH><TH >Army Size</TH></TR>";
	$query = mysql_query("SELECT ((sarank+darank+carank+rarank)/4) AS rf,hof" . $cgi['age'] . ".* FROM hof" . $cgi['age'] . " WHERE race='0' ORDER BY rf ASC limit 0,10") or die(mysql_error());
	$i = 1;
	while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
		echo "<Tr><td>";
		echo $i;
		echo "</td><td>";
		echo $row['username'];
		echo "</td><td>";
		echo numecho($row['untrained'] + $row['trainedsa'] + $row['trainedda'] + $row['spies'] + $row['sf'] + $row['samerc'] + $row['damerc']);
		echo "</td><td>";
		echo "</td></tr>";
		$i++;
	}
	echo "</table>";
	echo "<center><b>Top 10 {$conf['race'][1]['name']}</b></center>";
	echo "<table width=\"100%\" class=\"lines\"><TR><Th >Rank</Th><TH class=subh>UserName</TH><TH >Army Size</TH></TR>";
	$query = mysql_query("SELECT ((sarank+darank+carank+rarank)/4) AS rf,hof" . $cgi['age'] . ".* FROM hof" . $cgi['age'] . " WHERE race='1' ORDER BY rf ASC limit 0,10") or die(mysql_error());
	$i = 1;
	while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
		echo "<Tr><td>";
		echo $i;
		echo "</td><td>";
		echo $row['username'];
		echo "</td><td>";
		echo numecho($row['untrained'] + $row['trainedsa'] + $row['trainedda'] + $row['spies'] + $row['sf'] + $row['samerc'] + $row['damerc']);
		echo "</td><td>";
		echo "</td></tr>";
		$i++;
	}
	echo "</table>";
	echo "<center><b>Top 10 {$conf['race'][2]['name']}</b></center>";
	echo "<table width=\"100%\" class=\"lines\"><TR><Th >Rank</Th><TH class=subh>UserName</TH><TH >Army Size</TH></TR>";
	$query = mysql_query("SELECT ((sarank+darank+carank+rarank)/4) AS rf,hof" . $cgi['age'] . ".* FROM hof" . $cgi['age'] . " WHERE race='2' ORDER BY rf ASC limit 0,10") or die(mysql_error());
	$i = 1;
	while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
		echo "<Tr><td>";
		echo $i;
		echo "</td><td>";
		echo $row['username'];
		echo "</td><td>";
		echo numecho($row['untrained'] + $row['trainedsa'] + $row['trainedda'] + $row['spies'] + $row['sf'] + $row['samerc'] + $row['damerc']);
		echo "</td><td>";
		echo "</td></tr>";
		$i++;
	}
	echo "</table>";
	echo "<center><b>Top 10 {$conf['race'][3]['name']}</b></center>";
	echo "<table width=\"100%\" class=\"lines\"><TR><Th >Rank</Th><TH class=subh>UserName</TH><TH >Army Size</TH></TR>";
	$query = mysql_query("SELECT ((sarank+darank+carank+rarank)/4) AS rf,hof" . $cgi['age'] . ".* FROM hof" . $cgi['age'] . " WHERE race='3' ORDER BY rf ASC limit 0,10") or die(mysql_error());
	$i = 1;
	while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
		echo "<Tr><td>";
		echo $i;
		echo "</td><td>";
		echo $row['username'];
		echo "</td><td>";
		echo numecho($row['untrained'] + $row['trainedsa'] + $row['trainedda'] + $row['spies'] + $row['sf'] + $row['samerc'] + $row['damerc']);
		echo "</td><td>";
		echo "</td></tr>";
		$i++;
	}
	echo "</table>";
	echo "<center><b>Top 10 {$conf['race'][4]['name']}</b></center>";
	echo "<table width=\"100%\" class=\"lines\"><TR><Th >Rank</Th><TH class=subh>UserName</TH><TH >Army Size</TH></TR>";
	$query = mysql_query("SELECT ((sarank+darank+carank+rarank)/4) AS rf,hof" . $cgi['age'] . ".* FROM hof" . $cgi['age'] . " WHERE race='4' ORDER BY rf ASC limit 0,10") or die(mysql_error());
	$i = 1;
	while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
		echo "<Tr><td>";
		echo $i;
		echo "</td><td>";
		echo $row['username'];
		echo "</td><td>";
		echo numecho($row['untrained'] + $row['trainedsa'] + $row['trainedda'] + $row['spies'] + $row['sf'] + $row['samerc'] + $row['damerc']);
		echo "</td><td>";
		echo "</td></tr>";
		$i++;
	}
	echo "</table>";
}
echo "</center>";
include ("bottom.php");
?>
                    </TD>
                </TR>
            </TBODY>
        </TABLE>
    </BODY>
</HTML>

<? include "gzfooter.php"; ?>
