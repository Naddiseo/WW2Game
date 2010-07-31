<?

$cachedir    = $argv[1];
$current_age = $argv[2];

require_once($cachedir . '/end_time.php');
require_once($cachedir . '/start_time.php');

// find the last friday of the (next) month
$ori = new DateTime($_END_TIME[$current_age]);
echo $ori->format('Y-m-d H:i:sP') . "\n";

$year = $ori->format('Y');
$m    = $ori->format('m') + 1;

$date = new DateTime(
	"$year-$m-01T12:59:59"
);

//echo "First of next month: " . $date->format('Y-m-d H:i:sP') . "\n";

$startdate = $ori;
$startdate->modify('+24hours 1second');
echo "Start date: " . $startdate->format('c') . "\n";

$lastday = 0;
$month = $date->format('n');
while ($month == $date->format('n')) {
	$date->modify('+1 day');
	$day = $date->format('N');
	#echo "day: $day\n";
	if ($day == 6) {
		$lastday =  $date->format('U');
		//echo "current last day: " . $date->format('c') . "\n";
	}
}

echo "Last day = $lastday\n";

$_START_TIME[$current_age + 1] = '@' . $startdate->format('U');
$_END_TIME[$current_age + 1] = '@' . $lastday;

echo "opening $cachedir/end_time.php\n";
$endtime = fopen($cachedir . '/end_time.php', 'w+') or die("Couldn't' open end time\n");
fwrite($endtime, '<? $_END_TIME = ' . var_export($_END_TIME, true) . ';?>');
fclose($endtime);

echo "opening $cachedir/start_time.php\n";
$starttime = fopen($cachedir . '/start_time.php', 'w+') or die("Couldn't' open start time\n");
fwrite($starttime, '<? $_START_TIME = ' . var_export($_START_TIME, true) . ';?>');
fclose($starttime);
?>

