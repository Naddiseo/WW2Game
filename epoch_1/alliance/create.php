<form action="?leader=create" method="POST">
<input type="hidden" name="leader" value="create" />
<table width="100%">
	<tr><TD colspan="2" align="center">Create Alliance</TD></tr>
	<TR>
		<TD>Name</TD>
		<td><input name="alliance_name" type="text"  size="30" maxlength="30" /></td>
	</TR>
	<TR>
		<TD>TAG</TD>
		<td><input name="alliance_tag" type="text"  size="10"  maxlength="8" /></td>
	</TR>
	<TR>
		<TD>URL</TD>
		<td><input name="alliance_url" type="text"  size="50"  maxlength="255" /></td>
	</TR>
	<TR>
		<TD>IRC Channel</TD>
		<td><input name="alliance_irc" type="text"  size="20"  maxlength="10" /></td>
	</TR>
	<TR>
		<TD>Income Tax</TD>
		<td><input name="alliance_tax" type="text"  size="10"  maxlength="6" /></td>
	</TR>
	<TR>
		<TD>Message</TD>
		<td><textarea name="alliance_message" cols="50" rows="10" ></textarea></td>
	</TR>
	<TR>
		
		<td align="right" colspan="2"><input name="alliance_edit_submit" type="submit" value="Create" /></td>
	</TR>
</table>
</form>