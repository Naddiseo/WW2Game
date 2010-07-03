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

require_once('scripts/Transaction.php');
class Paypal {

	public function
	step1And2($txn) {
		$returnURL = urlencode(BASEURL . '/support.php?e=1');
		$cancelURL = urlencode(BASEURL . '/support.php?e=2');

		$nvpstr = "&AMT=$txn->amount&PAYMENTACTION=Sale&ReturnUrl=$returnURL&CANCELURL=$cancelURL&CURRENCYCODE=USD";
		$nvpstr .= '&L_NAME0=' . urlencode("\$$txn->amount Supporter Package") .
			"&L_NUMBER0=" . urlencode($txn->id) . 
			"&L_DESC0=" . urlencode("Support Pacakge for for userId $txn->forId") .
			"&L_AMT0=" . urlencode($txn->amount) . 
			"&L_QTY0=1&TAXAMT=0&SHIPPINGAMT=0&HANDLINGAMT=0&SHIPDISCAMT=0&NOSHIPPING=1" . 
			"&PAGESTYLE=" . urlencode("WW2_Game") .
			"&ALLOWNOTE=0&ITEMAMT=$txn->amount";
		$ret = $this->hashCall('SetExpressCheckout', $nvpstr);
		$_SESSION['pp_ret'] = $ret;
	
		$txn->token         = $ret['TOKEN'];
		$txn->timestamp     = $ret['TIMESTAMP'];
		$txn->correlationId = $ret['CORRELATIONID'];
		$txn->ack           = $ret['ACK'];
		$txn->version       = $ret['VERSION'];
		$txn->build         = $ret['BUILD'];
		$txn->part1Success  = (strtoupper($ret['ACK']) == 'SUCCESS' ? 1 : 0);
		$txn->save();
		
		if ($txn->part1Success) {		
			// Redirect to paypal.com here
			$token = urldecode($ret['TOKEN']);
			$payPalURL = PAYPAL_URL . $token;
			header("Location: $payPalURL");
			exit;
		}
		else {
			$err = array();
			$count = 0;
			while (isset($ret["L_SHORTMESSAGE$count"])) {
				$msg = array();
				$msg['errCode'] = $ret["L_ERRORCODE$count"];
				$msg['short']   = $ret["L_SHORTMESSAGE$count"];
				$msg['long']    = $ret["L_LONGMESSAGE$count"];
				$err[] = $msg;
				$count++;
			}
			$txn->errorInfo = serialize($err);
			$txn->save();
			return false;
		}
	}
	
	public function
	step4($txn) {
		$nvpstr = 
			'&TOKEN='             . urlencode($txn->token)   .
			'&PAYERID='           . urlencode($txn->payerId) . 
			'&PAYMENTACTION=Sale' . 
			'&AMT='               . urlencode($txn->amount)  .
			'&CURRENCYCODE=USD'   .
			'&IPADDRESS=' . urlencode($_SERVER['SERVER_NAME']);
		
		$ret = $this->hashCall('DoExpressCheckoutPayment', $nvpstr);
		
		$txn->part4Success = (strtoupper($ret['ACK']) == 'SUCCESS' ? 1 : 0);
		
		if (strtoupper($ret['ACK']) == 'SUCCESS') {
			$txn->transactionType = $ret['TRANSACTIONTYPE'];
			$txn->paymentType     = $ret['PAYMENTTYPE'];
			$txn->orderTime       = $ret['ORDERTIME'];
			$txn->fee             = $ret['FEEAMT'];
			$txn->tax             = $ret['TAXAMT'];
			$txn->currencyCode    = $ret['CURRENCYCODE'];
			$txn->paymentStatus   = $ret['PAYMENTSTATUS'];
			$txn->pendingReason   = $ret['PENDINGREASON'];
			$txn->reasonCode      = $ret['REASONCODE'];
	
			
		}
		else {
			$err = array();
			$count = 0;
			while (isset($ret["L_SHORTMESSAGE$count"])) {
				$msg = array();
				$msg['errCode'] = $ret["L_ERRORCODE$count"];
				$msg['short']   = $ret["L_SHORTMESSAGE$count"];
				$msg['long']    = $ret["L_LONGMESSAGE$count"];
				$err[] = $msg;
				$count++;
			}
			$txn->errorInfo = serialize($err);
		}
		
		$txn->save();

		return $txn->part4Success;
	}

	public function
	hashCall($methodName, $nvpStr) {
		//declaring of global variables
		global $API_Endpoint,$version,$API_UserName,$API_Password,$API_Signature,$nvp_Header;

		//setting the curl parameters.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, API_ENDPOINT);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);

		//turning off the server and peer verification(TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POST, 1);
		//if USE_PROXY constant set to TRUE in Constants.php, then only proxy will be enabled.
		//Set proxy name to PROXY_HOST and port number to PROXY_PORT in constants.php 
		//if(USE_PROXY)
		//curl_setopt ($ch, CURLOPT_PROXY, PROXY_HOST.":".PROXY_PORT); 

		//NVPRequest for submitting to server
		$nvpreq = 
			"METHOD="     . urlencode($methodName)     . 
			"&VERSION="   . urlencode(VERSION)         . 
			"&PWD="       . urlencode(PAYPAL_PASSWORD) . 
			"&USER="      . urlencode(PAYPAL_USERNAME) . 
			"&SIGNATURE=" . urlencode(PAYPAL_SIG)      . 
			$nvpStr;

		//setting the nvpreq as POST FIELD to curl
		curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

		//getting response from server
		$response = curl_exec($ch);

		//convrting NVPResponse to an Associative Array
		$nvpResArray = $this->deformatNVP($response);
		$nvpReqArray = $this->deformatNVP($nvpreq);
		$_SESSION['nvpReqArray'] = $nvpReqArray;

		if (curl_errno($ch)) {
			// moving to display page to display curl errors
			$_SESSION['curl_error_no']  = curl_errno($ch) ;
			$_SESSION['curl_error_msg'] = curl_error($ch);
			$location = "APIError.php";
			header("Location: support.php?e=3");
		} else {
			//closing the curl
			curl_close($ch);
		}

		return $nvpResArray;
	}
	
	
	/** This function will take NVPString and convert it to an Associative Array and it will decode the response.
	  * It is usefull to search for a particular key and displaying arrays.
	  * @nvpstr is NVPString.
	  * @nvpArray is Associative Array.
	  */

	function deformatNVP($nvpstr) {

		$intial=0;
	 	$nvpArray = array();


		while(strlen($nvpstr)){
			//postion of Key
			$keypos= strpos($nvpstr,'=');
			//position of value
			$valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);

			/*getting the Key and Value values and storing in a Associative Array*/
			$keyval=substr($nvpstr,$intial,$keypos);
			$valval=substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
			//decoding the respose
			$nvpArray[urldecode($keyval)] =urldecode( $valval);
			$nvpstr=substr($nvpstr,$valuepos+1,strlen($nvpstr));
		 }
		return $nvpArray;
	}

}
?>
