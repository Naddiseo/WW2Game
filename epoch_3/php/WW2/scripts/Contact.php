<?
/***

    World War II MMORPG
    Copyright (C) 2009-2010 Richard Eames

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

***/

define('CONTACT_TYPE_CONTACT_PAGE', 0);
define('CONTACT_TYPE_REPORT_USER', 1);


class Contact extends BaseClass {
	public static
	$CONTACT_TYPE_NAME = array(
		0 => 'Contact Page',
		1 => 'Report User'
	);

	public
		$id        = 0,
		$email     = '',
		$type      = 0,
		$text      = '',
		$date      = 0,
		$done      = 0,
		$reference = 0,
		$replied   = 0;
	
	public function
	getNotDone() {
		$ret = array();
		$q = mysql_query("SELECT * FROM Contact WHERE done = 0") or die(mysql_error());
		
		while ($r = mysql_fetch_object($q, 'Contact')) {
			$ret[] = $r;
		}
		
		return $ret;
	}
}
?>
