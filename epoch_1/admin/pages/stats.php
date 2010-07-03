<?
if ($cgi['edit'] AND is_numeric($cgi['uid'])) {
	if ($cgi['edit'] == 'password') {
		$cgi['editval'] = md5($cgi['editval']);
	}
	$cgi['edit'] = mysql_escape_string($cgi['edit']);
	$cgi['editval'] = mysql_escape_string($cgi['editval']);
	$cgi['tbl'] = mysql_escape_string($cgi['tbl']);
	switch ($cgi['tbl']) {
		case 'UserDetails':
			$sql = "UPDATE UserDetails SET $cgi[edit]=\"$cgi[editval]\" WHERE id=$cgi[uid]";
			//echo $sql;
			mysql_query($sql);
			if (!($e = mysql_error())) {
				echo "Update Successful";
			} else {
				die($e);
			}
		break;
	}
}
if (is_numeric($cgi['uid'])) {
	$q = mysql_query("SELECT * FROM UserDetails WHERE id=$cgi[uid] LIMIT 0,1") or die(mysql_error());
	$details = mysql_fetch_array($q, MYSQL_ASSOC);
	$q = mysql_query("SELECT * FROM Ranks WHERE userid=$cgi[uid] limit 0,1") or die(mysql_error());
	$ranks = mysql_fetch_object($q);
	$q = mysql_query("SELECT * FROM Weapon WHERE userid=$cgi[uid] ORDER BY weaponid asc") or die(mysql_error());
	$weapons = array();
	while ($row = mysql_fetch_object($q)) {
		$weapons[$row->isAttack][$row->weaponID] = $row;
	}
}
$gname = array('userName' => 'User Name', 'race' => 'Nation', 'active' => 'User Status', 'toTrack' => '<b>Track User</b>', 'email' => 'E-Mail', 'password' => 'MD5 Password', 'commander' => 'Commander', 'dalevel' => 'Defence Level', 'salevel' => 'Offense Level', 'calevel' => 'Covert Level', 'sflevel' => 'Retaliation Level', 'gold' => 'Gold', 'bank' => 'Banked', 'attackturns' => 'Attack Turns', 'up' => 'Unit Production', 'maxofficers' => 'Max Officers', 'sasoldiers' => 'Attack Soldiers', 'samercs' => 'Attack Mercs', 'dasoldiers' => 'Defence Soldiers', 'damercs' => 'Defence Mercs', 'uu' => 'Untrained Units', 'spies' => 'Spies', 'exp' => 'Experience', 'specialforces' => 'Special Force Ops', 'bankper' => 'Bank Percentage', 'alliance' => 'Alliance', 'hhlevel' => 'Hand-to-hand Level', 'weapper' => 'Weapon Sell Percentage', 'reason' => 'Ban Reason', 'nukelevel' => 'Nuke Upgrade Level', 'scientists' => 'Scientists', 'plutonium' => 'Plutonium', 'bunkers' => 'bunkers', 'nukes' => 'Nukes', 'nukesteps' => 'Nuke Research Step', 'supporter' => 'Supporter Status');
foreach ($details as $k => $v) {
	if (array_key_exists($k, $gname)) {
		if ($k == 'supporter') {
			if ($_SESSION['admin_user'] == 'admin') echo "<form method=\"post\"><input type=\"hidden\" name=\"path\" value=\"stats\" /> <input type=\"hidden\" name=\"tbl\" value=\"UserDetails\" /><input type=\"hidden\" name=\"uid\" value=\"$cgi[uid]\" /><input type=\"hidden\" name=\"edit\" value=\"$k\" /><span>{$gname[$k]}:&nbsp;</span><input type=\"text\" name=\"editval\" value=\"$v\" width=\"30\" /><input type=\"submit\" value=\"Edit This\" /></form>\n";
		} else {
			echo "<form method=\"post\"><input type=\"hidden\" name=\"path\" value=\"stats\" /> <input type=\"hidden\" name=\"tbl\" value=\"UserDetails\" /><input type=\"hidden\" name=\"uid\" value=\"$cgi[uid]\" /><input type=\"hidden\" name=\"edit\" value=\"$k\" /><span>{$gname[$k]}:&nbsp;</span><input type=\"text\" name=\"editval\" value=\"$v\" width=\"30\" /><input type=\"submit\" value=\"Edit This\" /></form>\n";
		}
	}
}
?>
