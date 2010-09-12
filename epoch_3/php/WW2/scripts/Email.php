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

require_once('scripts/phpmailer/class.phpmailer.php');

class Email {
	private $headers = array(
		'From' => "\"Admin\" <admin@example.net>",
		'Reply-To' => "\"Admin\" <admin@example.net>",
		'MIME-Version' => '1.1',
		"Return-Path" => "<admin@example.net>",
		"Organization" => "game"
	);

	private $boundary = '';
	private $message = '';
	private $to = '';
	private $subject = '';
	
	public $mailer = '';


	public function 
	__construct($to, $subject, $html, $plain) {
		$this->mailer = new PHPMailer();
		$this->mailer->Mailer = 'smtp';
		$this->mailer->IsSMTP();
//		$this->mailer->SMTPDebug = true;
		$this->mailer->Host = 'smtp.gmail.com';
		$this->mailer->Port = 465;
		$this->mailer->SMTPSecure = 'ssl';
		$this->mailer->Timeout = 30;
		$this->mailer->SMTPAuth = true;
		$this->mailer->Username = 'admin@example.net';
		$this->mailer->Password = 'password';
		
		$this->mailer->From     = 'admin@example.net';
		$this->mailer->FromName = 'Admin';
		$this->mailer->AddReplyTo('admin@example.net', 'Admin');
		$this->mailer->WordWrap = 70;
		
		$this->mailer->AddAddress($to);
		
		$this->mailer->IsHTML(true);
		
		$this->mailer->Subject = $subject;
		$this->mailer->Body    = $html;
		$this->mailer->AltBody = $plain;
		
		/*
		$this->headers["X-Mailer"] = 'PHP/' . phpversion();
		$this->generateBoundary();
		$this->addheader('Content-Type',  "Multipart/Alternative; boundary=\"{$this->boundary}\"");
		$this->to = $to;
		$this->addTEXTPart($plain);
		$this->addHTMLPart($html);
		$this->message.= '--' . $this->boundary . "--\n\n";
		$this->subject = $subject;
		*/
	}

	public function 
	addHTMLPart($msg) {
		$this->message .= '--' . $this->boundary . "\nContent-Type: text/html; charset=UTF-8\n\n$msg\n";
	}

	public function 
	addTEXTPart($msg) {

		$this->message .= '--' . $this->boundary . "\nContent-Type: text/plain; charset=UTF-8\n\n$msg\n";
	
	}

	private function 
	buildheaders(){
		$h = '';

		foreach ($this->headers as $k => $v){
			$h .= "$k: $v\n";
		}
		return $h;

	}

	function addheader($name,$value){

		$this->headers[$name] = $value;
		
	}

	function generateBoundary() {
	
		$this->boundary = '-BoundRy' . time() . chr(rand(ord('a'),ord('z'))) . chr(rand(ord('a'),ord('z'))) . chr(rand(ord('A'),ord('Z')));

	}

	function send() {
		
		$this->message = wordwrap($this->message, 70);

		$headers = $this->buildheaders();
		if (!$this->mailer->Send()) {
		echo "Mailer Error: " . $this->mailer->ErrorInfo;exit;
		
		}
		return 1;//$this->mailer->Send();
		//return mail($this->to,$this->subject,$this->message,$headers);

	}
 }
 
 /* 
 
 FROM http://www.tienhuis.nl/files/email_verify_source.php
 
 License is GPL3
 
   $Id: VerifyEmailAddress.php 10 2008-11-16 18:49:20Z visser $
   
   Email address verification with SMTP probes
   Dick Visser <dick@tienhuis.nl>

   INTRODUCTION

   This function tries to verify an email address using several tehniques,
   depending on the configuration. 

   Arguments that are needed:

   $email (string)
   The address you are trying to verify

   $domainCheck (boolean)
   Check if any DNS MX records exist for domain part

   $verify (boolean)
   Use SMTP verify probes to see if the address is deliverable.

   $probe_address (string)
   This is the email address that is used as FROM address in outgoing
   probes. Make sure this address exists so that in the event that the
   other side does probing too this will work.
   
   $helo_address (string)
   This should be the hostname of the machine that runs this site.

   $return_errors (boolean)
   By default, no errors are returned. This means that the function will evaluate
   to TRUE if no errors are found, and false in case of errors. It is not possible
   to return those errors, because returning something would be a TRUE.
   When $return_errors is set, the function will return FALSE if the address
   passes the tests. If it does not validate, an array with errors is returned.


   A global variable $debug can be set to display all the steps.


   EXAMPLES

   Use more options to get better checking.
   Check only by syntax:  validateEmail('dick@tienhuis.nl')
   Check syntax + DNS MX records: validateEmail('dick@tienhuis.nl', true);   
   Check syntax + DNS records + SMTP probe:
   validateEmail('dick@tienhuis.nl', true, true, 'postmaster@tienhuis.nl', 'outkast.tienhuis.nl');


   WARNING
 
   This function works for now, but it may well break in the future.

*/
function validateEmail($email, $domainCheck = false, $verify = false, $probe_address='', $helo_address='', $return_errors=false) {
	global $debug;
	$server_timeout = 180; # timeout in seconds. Some servers deliberately wait a while (tarpitting)
	if($debug) {echo "<pre>";}
	# Check email syntax with regex
	if (preg_match('/^([a-zA-Z0-9\._\+-]+)\@((\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,7}|[0-9]{1,3})(\]?))$/', $email, $matches)) {
		$user = $matches[1];
		$domain = $matches[2];
		# Check availability of  MX/A records
		if ($domainCheck) {
			if(function_exists('checkdnsrr')) {
				# Construct array of available mailservers
				if(getmxrr($domain, $mxhosts, $mxweight)) {
					for($i=0;$i<count($mxhosts);$i++){
						$mxs[$mxhosts[$i]] = $mxweight[$i];
					}
					asort($mxs);
					$mailers = array_keys($mxs);
				} elseif(checkdnsrr($domain, 'A')) {
					$mailers[0] = gethostbyname($domain);
				} else {
					$mailers=array();
				}
			} else {
			# DNS functions do not exist - we are probably on Win32.
			# This means we have to resort to other means, like the Net_DNS PEAR class.
			# For more info see http://pear.php.net
			# For this you also need to enable the mhash module (lib_mhash.dll).
			# Another way of doing this is by using a wrapper for Win32 dns functions like
			# the one descrieb at http://px.sklar.com/code.html/id=1304
				require_once 'Net/DNS.php';
				$resolver = new Net_DNS_Resolver();
				# Workaround for bug in Net_DNS, you have to explicitly tell the name servers
				#
				# ***********  CHANGE THIS TO YOUR OWN NAME SERVERS **************
				$resolver->nameservers = array ('217.149.196.6', '217.149.192.6');
				
				$mx_response = $resolver->query($domain, 'MX');
				$a_response  = $resolver->query($domain, 'A');
				if ($mx_response) {
					foreach ($mx_response->answer as $rr) {
							$mxs[$rr->exchange] = $rr->preference;
					}
					asort($mxs);
					$mailers = array_keys($mxs);
				} elseif($a_response) {
					$mailers[0] = gethostbyname($domain);
				} else {
					$mailers = array();
				}
				
			}
			
			$total = count($mailers);
			# Query each mailserver
			if($total > 0 && $verify) {
				# Check if mailers accept mail
				for($n=0; $n < $total; $n++) {
					# Check if socket can be opened
					if($debug) { echo "Checking server $mailers[$n]...\n";}
					$connect_timeout = $server_timeout;
					$errno = 0;
					$errstr = 0;
					# Try to open up socket
					if($sock = @fsockopen($mailers[$n], 25, $errno , $errstr, $connect_timeout)) {
						$response = fgets($sock);
						if($debug) {echo "Opening up socket to $mailers[$n]... Succes!\n";}
						stream_set_timeout($sock, 30);
						$meta = stream_get_meta_data($sock);
						if($debug) { echo "$mailers[$n] replied: $response\n";}
						$cmds = array(
							"HELO $helo_address",
							"MAIL FROM: <$probe_address>",
							"RCPT TO: <$email>",
							"QUIT",
						);
						# Hard error on connect -> break out
						# Error means 'any reply that does not start with 2xx '
						if(!$meta['timed_out'] && !preg_match('/^2\d\d[ -]/', $response)) {
							$error = "Error: $mailers[$n] said: $response\n";
							break;
						}
						foreach($cmds as $cmd) {
							$before = microtime(true);
							fputs($sock, "$cmd\r\n");
							$response = fgets($sock, 4096);
							$t = 1000*(microtime(true)-$before);
							if($debug) {echo htmlentities("$cmd\n$response") . "(" . sprintf('%.2f', $t) . " ms)\n";}
							if(!$meta['timed_out'] && preg_match('/^5\d\d[ -]/', $response)) {
								$error = "Unverified address: $mailers[$n] said: $response";
								break 2;
							}
						}
						fclose($sock);
						if($debug) { echo "Succesful communication with $mailers[$n], no hard errors, assuming OK";}
						break;
					} elseif($n == $total-1) {
						$error = "None of the mailservers listed for $domain could be contacted";
					}
				}
			} elseif($total <= 0) {
				$error = "No usable DNS records found for domain '$domain'";
			}
		}
	} else {
		$error = 'Address syntax not correct';
	}
	if($debug) { echo "</pre>";}
	
	if($return_errors) {
		# Give back details about the error(s).
		# Return FALSE if there are no errors.
		if(isset($error)) return htmlentities($error); else return false;
	} else {
		# 'Old' behaviour, simple to understand
		if(isset($error)) return false; else return true;
	}
}

function verifyEmail($input) {
	list($email, $domain) = split('@', $input, 2);
	$mxhosts = array();
	if (!getmxrr($domain, $mxhosts)) {
		return false;
	}
	return true;
}
 
 ?>
