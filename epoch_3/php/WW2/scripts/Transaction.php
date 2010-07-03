<?
/***

    World War II MMORPG
    Copyright (C) 2009 Richard Eames

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

class Transaction {
	public
		$id         = 0,
		$time       = 0,
		$amount     = 0.0,
		$userId     = 0,
		$forId      = 0,
		$isAlliance = 0,
		// part 1
		$token         = '',
		$timestamp     = '',
		$correlationId = '',
		$ack           = '',
		$version       = '',
		$build         = '',
		$part1Success  = 0,
		$part4Success  = 0,
		// part 2
		$payerId        = '',
		//part 4
		$transactionId   = '',
		$transactionType = '',
		$paymentType     = '',
		$orderTime       = '',
		$fee             = 0.00,
		$tax             = 0.00,
		$currencyCode    = '',
		$paymentStatus   = '',
		$pendingReason   = '',
		$reasonCode      = '',
		
		// error stuff
		$errorInfo       = '';
		
		
	public function
	create() {
		$sql = "INSERT INTO Transaction set ";
		$values = array();
		foreach ((array)$this as $k => $value) {
			if ($k != 'id') {
				$value = mysql_real_escape_string($value);
				$values[] = "`$k` = \"$value\"";
			}
		}

		$sql .= implode(', ', $values);
		$q = mysql_query($sql) or die(mysql_error());
		$this->id = mysql_insert_id();
		return $this->id;
	}
		
	public function
	get($id) {
		$r = mysql_query("SELECT * FROM Transaction WHERE id = $id LIMIT 1") or die(mysql_error());
		$a = mysql_fetch_array($r, MYSQL_ASSOC);
		foreach ($a as $key => $value) {
			$this->$key = $value;
		}
	}
	
	public function
	getByToken($t) {
		$t = mysql_real_escape_string($t);
		$r = mysql_query("SELECT * FROM Transaction WHERE token = \"$t\" LIMIT 1") or die(mysql_error());
		$a = mysql_fetch_array($r, MYSQL_ASSOC);
		if ($a['id']) {
			foreach ($a as $key => $value) {
				$this->$key = $value;
			}
		}
	}
	
	public function
	save() {
		$sql = "UPDATE Transaction set ";
		$values = array();
		foreach ((array)$this as $k => $value) {
			if ($k != 'id' and $k != '_cache' and !is_array($value)) {
				$value = mysql_real_escape_string($value);
				$values[] = "`$k` = \"$value\"";
			}
		}

		$sql .= implode(', ', $values);
		$sql .= " where id =$this->id;";
		mysql_query($sql) or die(mysql_error());
	}
		
}
?>
