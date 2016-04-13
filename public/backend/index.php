<?php
//ini_set('display_errors', 1);
//error_reporting(E_ALL); 
$time_start = microtime(true);
$aDir = explode('/', dirname(__FILE__));
include ('../../application/sites/' . $aDir[count($aDir) - 1] . '/bootstrap.php');
$time = microtime(true) - $time_start;
if ($_GET['time']) {
    echo '<!-- time : ' . substr($time, 0, 5) . ' sec. -->';
}