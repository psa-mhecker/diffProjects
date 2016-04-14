<?php
use Symfony\Component\HttpFoundation\Response;

$loader = require_once __DIR__.'/../../../../app/bootstrap.php.cache';

if (file_exists('gallery.html')) {
    $response = new Response(file_get_contents('gallery.html'));
} else {
	$response = new Response('the wraith report does not exist');    
}

$response->send();

