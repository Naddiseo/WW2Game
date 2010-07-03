<? class clsMAIL {
	var $headers = array('From' => "\"Admin\" <admin@example.net>", 'Reply-To' => "\"Admin\" <admin@example.net>", 'MIME-Version' => '1.1', "Return-Path" => "<admin@example.net>", "Organization" => "WW2game");
	var $boundary = '';
	var $message = '';
	var $to = '';
	var $subject = '';
	function __construct($to, $subject, $html, $plain) {
		$this->headers["X-Mailer"] = "PHP/" . phpversion();
		$this->GenerateBoundary();
		//$this->addheader('Content-Type',  "Multipart/Alternative; boundary=\"{$this->boundary}\"");
		$this->to = $to;
		//$this->addHTMLPart($html);
		//$this->addTEXTPart($plain);
		//$this->message.=$this->boundary;
		$this->message = $plain;
		$this->subject = $subject;
	}
	function addHTMLPart($msg) {
		$this->message.= $this->boundary . "\r\nContent-Type: text/html; charset=UTF-8\r\n\r\n" . $msg . "\r\n";
	}
	function addTEXTPart($msg) {
		$this->message.= $this->boundary . "\r\nContent-Type: text/plain; charset=UTF-8\r\n\r\n" . $msg . "\r\n";
	}
	function _buildheaders() {
		$h = '';
		foreach ($this->headers as $k => $v) {
			$h.= "$k: $v\r\n";
		}
		return $h . "\r\n";
	}
	function addheader($name, $value) {
		$this->headers[$name] = $value;
	}
	function GenerateBoundary() {
		$this->boundary = "-------------------" . time() . rand(ord('a'), ord('z')) . rand(ord('a'), ord('z')) . rand(ord('A'), ord('Z'));
	}
	function send() {
		$headers = $this->_buildheaders();
		return mail($this->to, $this->subject, $this->message, $headers);
	}
} ?>
