<?php
include_once 'config.php';

//session_start();

$oauth = null;
if(isset($_SESSION['itkg_consumer_oauth2'])) {
    // oauth 2.0
    $oauth = $_SESSION['itkg_consumer_oauth2'];

}else if(isset($_SESSION['itkg_consumer_oauth'])) {
    // oauth 1.0A
    $oauth = $_SESSION['itkg_consumer_oauth'];
}
// Handle callback
if($oauth) {
    $oauth->handleCallback($_GET);
}
