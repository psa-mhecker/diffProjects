<?php
include_once 'Pelican/Application.php'; 
include_once 'Pelican.php';

Pelican::$config['BYPASS_SESSION'] = true;

include_once ('config.php');

Pelican::$config['route_sequence'] = array(
    'Image' ,
    'Mvc',
);

$body = Pelican_Request::getInstance()->execute()->sendHeaders()->getResponse();

echo $body;
