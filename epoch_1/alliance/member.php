

<table width="100%">
	<TR><TD>Alliance</TD><td><?=$alliances[MEMBER]->name ?></td></TR>
	<TR><TD>SA Bonus</TD><td><?=$alliances[MEMBER]->SA * 100 ?>%</td></TR>
	<TR><TD>DA Bonus</TD><td><?=$alliances[MEMBER]->DA * 100 ?>%</td></TR>
	<TR><TD>CA Bonus</TD><td><?=$alliances[MEMBER]->CA * 100 ?>%</td></TR>
	<TR><TD>RA Bonus</TD><td><?=$alliances[MEMBER]->RA * 100 ?>%</td></TR>
	<TR><TD>UP Bonus</TD><td>+<?=$alliances[MEMBER]->UP ?></td></TR>
	<TR><TD>Gold</TD><TD><?=number_format($alliances[MEMBER]->gold) ?></TD></TR>
	<TR><TD>Experience</TD><TD><?=number_format($alliances[MEMBER]->exp) ?></TD></TR>
	<TR><TD>Income Tax</TD><td><?=round($alliances[MEMBER]->tax * 100, 2) ?>%</td></TR>
	<TR><TD>Forum</TD><td><? echoURL($alliances[MEMBER]->url, stripslashes($alliances[MEMBER]->url)) ?></td></TR>
	<TR><TD>Message</TD><td><?=htmlentities(stripslashes($alliances[MEMBER]->message)) ?></td></TR>
	
</table>

<table width="100%" class="table_lines">
	<tr><TD colspan="3" align="center">Alliance Members</TD></tr>
	<tr><Th class="subh">Rank</Th><Th class="subh">Username</Th></tr>
<?
$q = mysql_query("SELECT ID,username,rank FROM UserDetails,Ranks WHERE Ranks.userid=UserDetails.id AND alliance=" . MEMBER . " AND aaccepted=1 AND UserDetails.active=1 ORDER BY rank ASC") or die(mysql_error());
while ($m = mysql_fetch_object($q)) {
	echo "<tr><td>{$m->rank}</td><td><a href=\"stats.php?id={$m->ID}\">{$m->username}</a></td></tr>";
}
?>
</table>
<?
printf("Total: %i Users", mysql_num_rows($q));
?>
		<Br />
		<hr />
		<form action="alliance.php?do=donate">
			<input type="hidden" name="do" value="donate" />
			<table width="100%">
				<tr>
					<th colspan="3">Contribute Resources</th>
				</tr>
				<tr>
					<td>Gold</td><td><input name="dgold"  /></td>
				<tr>
				<tr>
					<td>Experience</td><td><input name="dexp"  /></td>
				<tr>	
				<tr><td></td><td><input type="submit" value="Contribute" /></td></tr>
			</table>
		</form>
		<br />
		<hr />
<a href="paypal.php?ad=true">Alliance Donation</a>
<a href="alliance.php?leave=true">Leave Alliance</a>
<a href="alliance.php?view=list">List Other Alliances</a>
