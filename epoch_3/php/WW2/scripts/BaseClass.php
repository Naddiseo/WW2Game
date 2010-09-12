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

class BaseClass {

	public function
	create() {
		$sql = 'INSERT INTO `' . get_class($this) . '` set ';
		$values = array();
		foreach ((array)$this as $k => $value) {
			if ($k != 'id' and $k != '_cache' and !is_array($value)) {
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
		$r = mysql_query('SELECT * FROM `' . get_class($this) . "` WHERE id = $id LIMIT 1") or die(mysql_error());
		$a = mysql_fetch_assoc($r);
		if ($a) {
			foreach ($a as $key => $value) {
				$this->$key = $value;
			}
		}
	}
	
	public function
	save() {
		$sql = 'UPDATE `' . get_class($this) . '` SET ';
		$values = array();
		foreach ((array)$this as $k => $value) {
			if ($k != 'id' and $k != '_cache' and !is_array($value)) {
				$value = mysql_real_escape_string($value);
				$values[] = "`$k` = \"$value\"";
			}
		}

		$sql .= implode(', ', $values);
		$sql .= " where id =$this->id;";
		
		if ($this->id) {
			mysql_query($sql) or die(mysql_error());
		}
	}
	
	public function
	delete() {
		if ($this->id) {
			mysql_query('DELETE FROM `' . get_class($this) . "` WHERE id = $this->id LIMIT 1") or die(mysql_error());
		}
	}
	
	
	public static function
	getAll($c) {
		$ret = array();
		$q = mysql_query('SELECT * FROM `' . $c . "`") or die(mysql_error());
		
		while ($r = mysql_fetch_object($q, $c)) {
			$ret[] = $r;
		}
		
		return $ret;
	}
	
}
?>
