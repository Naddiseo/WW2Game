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

class Template {
	public
		$templateName = '',
		$templateId   = 1,
		$user         = null;
		
	private $title = array(
		'WW2',
		'WWII',
		'World War 2',
		'World War II',
		'World War Two'
	);

	private $other = array(
		'Massively Multiplayer Online Role Playing Game',
		'MMORPG',
		'Online Game',
		'Free Online Game',
		'Browser Based Game',
		'Free MMORPG',
		'Online RPG',
		'Turn Based Game',
	);

	public function
	__construct($page = '', $tid = 1) {
		global $user;
		$this->templateName = $page;
		$tid                = max(1, min(3, $tid));
		$this->templateId   = $tid;
		// TODO: autoset $user
		$this->user = $user;
	}

	public function
	display() {
		$this->load('template');
	}

	public function
	load($name) {
		global $conf, $conf_announcement;
		$user = $this->user;
		$this->announcement = $conf_announcement;
		
		$t = $this->title[rand(0,4)] . '::';
		$t .= ($this->pageTitle ? $this->pageTitle : 'Game') . ' - ';
		$t .= $this->other[rand(0, 7)];
		
		$this->pageTitle = $t;
		
		require_once("templates/$this->templateId/$name.tpl.php");
	}

	public function
	getContents($name) {
		global $conf, $conf_announcement;
		$user = $this->user;
		$this->announcement = $conf_announcement;
		ob_start();
		require_once("templates/$this->templateId/$name.tpl.php");
		return ob_get_clean();
	}

	public function
	css($name) {
		return "templates/$this->templateId/css/$name.css";
	}

	public function
	js($name) {
		return "templates/$this->templateId/js/$name.js";
	}

	public function
	image($name) {
		return "templates/$this->templateId/images/$name";
	}
	
	public function
	captcha() {
		return <<<EOF
		<br />
			<img src="http://www.ww2game.net/imageclick.php?<?= session_name() . '=' . session_id() ?>" title="random characters" alt="random characters"><br />
			<select name="turing">
				<option value="1">one</option>
				<option value="2">two</option>
				<option value="3">three</option>
				<option value="4">four</option>
				<option value="5">five</option>
				<option value="6">six</option>
				<option value="7">seven</option>
				<option value="8">eight</option>
				<option value="9">nine</option>
				<option value="10">ten</option>
				<option value="11">eleven</option>
				<option value="12">twelve</option>
				<option value="13">thirteen</option>
				<option value="14">fourteen</option>
				<option value="15">fifteen</option>
			</select>
EOF;
	}
}
?>
