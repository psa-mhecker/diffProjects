<?php

$time_start = microtime(true);
$aDir = explode('/', dirname(__FILE__));
include('../../application/sites/' . $aDir[count($aDir) - 1] . '/bootstrap.php');
$time = microtime(true) - $time_start;
//debug(1);
if ($_GET['time']) {
	echo '<!-- time : '.substr($time,0,5).' sec. -->';
}
//debug(1);