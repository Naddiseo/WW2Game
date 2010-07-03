<?
session_start();
include ("../scripts/conf.php");
include ("../scripts/db.php");
include ('../scripts/checkdonator.php');
function duration($time, $N = 1) {
	if ($N == 3) {
		$hour = floor($time / 3600);
		$time = $time - $hour * 3600;
		$min = floor($time / 60);
		$sec = $time - $min * 60;
		return str_pad($hour, 2, "0", STR_PAD_LEFT) . ":" . str_pad($min, 2, "0", STR_PAD_LEFT) . ":" . str_pad($sec, 2, "0", STR_PAD_LEFT);
	}
	$input['week'] = floor($time / 604800);
	$time = $time - $input['week'] * 604800;
	$input['day'] = floor($time / 86400);
	$time = $time - $input['day'] * 86400;
	$input['hour'] = floor($time / 3600);
	$time = $time - $input['hour'] * 3600;
	$input['min'] = floor($time / 60);
	$input['sec'] = $time - $input['min'] * 60;
	$output = "";
	if ($input['week']) {
		if ($input['week'] > 1) {
			$output.= $input['week'] . "w";
		} else {
			$output.= $input['week'] . "w";
		}
		return $output;
	}
	if ($input['day']) {
		if ($input['day'] > 1) {
			$output.= $input['day'] . "d";
		} else {
			$output.= $input['day'] . "d";
		}
	}
	if ($input['hour']) {
		if ($input['hour'] > 1) {
			$output.= $input['hour'] . "h";
		} else {
			$output.= $input['hour'] . "h";
		}
	}
	if ($input['min']) {
		if ($input['min'] > 1) {
			$output.= $input['min'] . "m";
		} else {
			$output.= $input['min'] . "m";
		}
	}
	if ($input['sec']) {
		if ($N == 1) {
			if ($input['sec'] > 1) {
				$output.= $input['sec'] . "s";
			} else {
				$output.= $input['sec'] . "s";
			}
		} elseif (($output == NULL) && ($N == 2)) {
			if ($input['sec'] > 1) {
				$output.= $input['sec'] . "s";
			} else {
				$output.= $input['sec'] . "s";
			}
		}
	}
	if (substr($output, -1) == chr(32)) {
		return substr($output, 0, strlen($output) - 1);
	}
	return $output;
}
function doMask($w) {
	if ($_SESSION['admin_mask'] == 'all') {
		return true;
	}
	return (strpos(" " . $_SESSION['admin_mask'], '|' . $w) > 0);
}
include ("header.php");
if ($cgi['checkdonator']) {
	setDonators();
	echo "Done";
}
if (!$_SESSION['admin_user']) {
	include ('pages/login.php');
} elseif (!$cgi['path']) {
	include ("main.php");
} else {
	switch ($cgi['path']) {
		case 'logs_advanced':
			if (doMask('logs')) {
				include ('pages/advancedlog.php');
			}
		break;
		case 'iptoid':
			if (doMask('iptoid')) {
				include ('pages/iptoid.php');
			}
		break;
		case 'idtoip':
			if (doMask('iptoid')) {
				include ('pages/idtoip.php');
			}
		break;
		case 'ban':
			if (doMask('ban')) {
				include ('pages/ban.php');
			}
		break;
		case 'stats':
			if (doMask('stats')) {
				include ('pages/stats.php');
			}
		break;
		case 'search':
			if (doMask('search')) {
				include ('pages/search.php');
			}
		break;
		default:
			echo "You do not have the required permission to use that tool";
	}
}
include ("footer.php");
?>