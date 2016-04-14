<?php
use Symfony\Component\HttpFoundation\Response;

$loader = require_once __DIR__.'/../../../../app/bootstrap.php.cache';

if (file_exists('report.html')) {
    $response = new Response(file_get_contents('report.html'));
} else {
	$response = new Response('the behat report does not exist');    
}

$response->send();

