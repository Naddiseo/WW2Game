<table width="100%">
	<TR><TD>Strike Bonus</TD><TD><?=$alliances[MEMBER]->SA * 100 ?>%</TD></TR>
	<TR><TD>Defence Bonus</TD><TD><?=$alliances[MEMBER]->DA * 100 ?>%</TD></TR>
	<TR><TD>Covert Bonus</TD><TD><?=$alliances[MEMBER]->CA * 100 ?>%</TD></TR>
	<TR><TD>Retaliation Bonus</TD><TD><?=$alliances[MEMBER]->RA * 100 ?>%</TD></TR>
	<TR><TD>Unit Production Bonus</TD><TD>+<?=$alliances[MEMBER]->UP ?></TD></TR>
	<TR><TD>Gold</TD><TD><?=number_format($alliances[MEMBER]->gold) ?></TD></TR>
	<TR><TD>Experience</TD><TD><?=number_format($alliances[MEMBER]->exp) ?></TD></TR>
	<TR><TD>Income Tax</TD><TD><?=number_format($alliances[MEMBER]->tax, 2) * 100 ?>%</TD></TR>
	<TR><TD>Alliance Points</TD><TD><?=$alliances[MEMBER]->ap ?>/<?=$alliances[MEMBER]->usedap ?></TD></TR>
	<TR><TD>Donated</TD><TD>$<?=number_format($alliances[MEMBER]->usedcash, 2) ?>/$<?=number_format($alliances[MEMBER]->donated, 2) ?></TD></TR>
</table>
<hr />
<table class="table_lines" width="100%">
	<TR>
		<TD><a href="?view=list">View Alliance List</a></TD>
		<td><a href="?leaderpage=members">Manage Members</a></td>
		<td><a href="?leaderpage=edit">Edit Alliance</a></td>
	</TR>
	<TR>
		<TD>&nbsp;</TD>
		<td><a href="writemail.php?to=msgall">Message Alliance Members</a></td>
		<td><a href="?leaderpage=convert">Convert Alliance Resources</a></td>
	</TR>
	<tr>
		<td><a href="?leaderpage=alliance_upgrades">Alliance Upgrades</a></td>
		<td><a href="paypal.php?ad=true">Alliance Donation</a></td>
		<td><a href="?leaderpage=disband">Disband Alliance</a></td>
	</tr>
</table>
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

