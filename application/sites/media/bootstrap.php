<?php
include_once ('config.php');

Pelican::$config['BYPASS_SESSION'] = true;

Pelican::$config['route_sequence'] = array(
    'Image' , 
    'Mvc'
);

$body = Pelican_Request::getInstance()->execute()->sendHeaders()->getResponse();

echo $body;
