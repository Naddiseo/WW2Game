<?
if (IS_LEADER === true) {
	echo "<a href=\"?leaderpage=main\">Alliance Leader Panel</a><br />";
}
if ($cgi['showdetails'] and isset($alliances[$cgi['showdetails']])) {
	//$cgi['showdetails']=1;
	
?>
<table width="100%">
	<TR><TD>Alliance</TD><td><?=$alliances[$cgi['showdetails']]->name ?></td></TR>
	<TR><TD>SA Bonus</TD><td><?=$alliances[$cgi['showdetails']]->SA * 100 ?>%</td></TR>
	<TR><TD>DA Bonus</TD><td><?=$alliances[$cgi['showdetails']]->DA * 100 ?>%</td></TR>
	<TR><TD>CA Bonus</TD><td><?=$alliances[$cgi['showdetails']]->CA * 100 ?>%</td></TR>
	<TR><TD>RA Bonus</TD><td><?=$alliances[$cgi['showdetails']]->RA * 100 ?>%</td></TR>
	<TR><TD>UP Bonus</TD><td>+<?=$alliances[$cgi['showdetails']]->UP ?>/10</td></TR>
	
	<TR><TD>Income Tax</TD><td><?=round($alliances[$cgi['showdetails']]->tax * 100, 2) ?>%</td></TR>
	<TR><TD>Message</TD><td><?=htmlentities(stripslashes($alliances[$cgi['showdetails']]->message)) ?></td></TR>
	<? if (NO_ALLIANCE) { ?>
	<tr><TD>Join</TD><td><a href="?join=<?=$cgi['showdetails'] ?>"><?=stripslashes($alliances[$cgi['showdetails']]->name) ?></a></td></tr>
	<?
	} ?>
</table>
<?
} ?>
<table class=table_lines cellSpacing=0 cellPadding=6 width="100%" border=0 width="100%">
	<TR>
		<TH class="subh" >Name</TH>
		<TH class="subh" >Tag</TH>
		<TH class="subh" >Leader1</TH>
		<th class="subh">Leader2</th>
		<th class="subh">Leader3</th>
		<th class="subh">IRC Channel</th>
		<th class="subh" >&nbsp;</th>
	</TR>
	<? foreach ($alliances as $alliance) { ?>
	<tr>
		<TD><? echoURL($alliance->url, $alliance->name) ?></TD>
		<TD><?=stripslashes($alliance->tag) ?></TD>
		<TD><a href="stats.php?id=<?=$alliance->leaderid1 ?>"><? $u = getUserDetails($alliance->leaderid1, 'username');
	echo $u->username; ?></a></TD>
		<TD><?
	if ($alliance->leaderid2) {
?><a href="stats.php?id=<?=$alliance->leaderid2
?>"><? $u = getUserDetails($alliance->leaderid2, 'username');
		echo $u->username; ?></a>
			<?
	} else {
		echo "(none)";
	} ?></TD>
			<TD><?
	if ($alliance->leaderid3) {
?><a href="stats.php?id=<?=$alliance->leaderid3
?>"><? $u = getUserDetails($alliance->leaderid3, 'username');
		echo $u->username; ?></a>
			<?
	} else {
		echo "(none)";
	} ?></TD>
		<TD><?=(strlen(stripslashes($alliance->irc)) > 0 ? stripslashes($alliance->irc) : '(none)') ?></TD>
		<td>
			<a href="?showdetails=<?=$alliance->ID ?>&amp;view=list">View Details</a>
		</td>
	</tr>
	<?
} ?>
</table>
<a href="?do=create">Create an Alliance</a>