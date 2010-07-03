<?
define('GAME', 'game');
define('VERSION', '2.0.1-ww2-2');

define('BASEURL', 'http://www.ww2game.net/');
define('BASEDIR', '/home/ww2game/public_html/');
define('INCDIR', '/home/ww2game/php/WW2/');
define('CACHEDIR', '/home/ww2game/php/WW2/cache/');


define('SESS_NAME', 'ww2game');


require_once(CACHEDIR . '/age.php');
require_once(CACHEDIR . '/start_time.php');
require_once(CACHEDIR . '/end_time.php');

$db_user = 'ww3game_user';
$db_pass = 'password';
$db_database = 'ww3game_db';

$game_offline = false;
$offline_message = 'We are resetting the game, please come back in a few hours';
//$endtime   = mktime(23,59,59,5,28,2010) + (60*60*13);
//$starttime = mktime(2, 0, 0, 4, 4, 2009) + (60*60*13);

$endtime   = str_replace('@', '', $_END_TIME[$_AGE]); //mktime(23,59,59,6,25,2010) + (60*60*13);
$starttime = str_replace('@', '', $_START_TIME[$_AGE]); //mktime(2,0,0,5,30,2010) + (60*60*13);

$conf['minutes_per_turn'] = 20;
$current_age = $_AGE;
$first_age   = 7;

$announce = '';

define('PAYPAL_USERNAME', 'blahblahblah');
define('PAYPAL_PASSWORD', 'blahblahblah');
define('PAYPAL_SIG',      'blahblahblah');
define('API_ENDPOINT', 'https://api-3t.paypal.com/nvp');
/* Define the PayPal URL. This is the URL that the buyer is
   first sent to to authorize payment with their paypal account
   change the URL depending if you are testing on the sandbox
   or going to the live PayPal site
   For the sandbox, the URL is
   https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=
   For the live site, the URL is
   https://www.paypal.com/webscr&cmd=_express-checkout&token=
   */
define('PAYPAL_URL', 'https://www.paypal.com/webscr&cmd=_express-checkout&token=');
define('PAYPAL_VERION', '57.0');

define('LOGGER_V', '1');



function updateAgefiles($nextage) {
	$first = false;
	$second = false;
	//return false;
	$nextage = intval($nextage);
	
	$fp = fopen(CACHEDIR . 'age.txt', 'w+');
	if ($fp) {
		if (flock($fp, LOCK_EX)) {
			ftruncate($fp, 0);
			if (fwrite($fp, $nextage) > 0 and fflush($fp)) {
				$first = true;
			}
			flock($fp,  LOCK_UN);
			
		}
		fclose($fp);
	}
	
	$fp = fopen(CACHEDIR . 'age.php', 'w+');
	if ($fp) {
		if (flock($fp, LOCK_EX)) {
			ftruncate($fp, 0);
			if (fwrite($fp, "<? \$_AGE = $nextage; ?>\n") > 0 and fflush($fp)) {
				$second = true;
			}
			flock($fp,  LOCK_UN);
			
		}
		fclose($fp);
	}
	
	return $first && $second;
}


?>
