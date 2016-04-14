<?php
/**
 * Params API endpoint
 */
require_once 'config.php';
require_once '../params/Db.php';
require_once Pelican::$config["DOCUMENT_INIT"] . '/../vendor/autoload.php';

use Luracast\Restler\Defaults;
use Luracast\Restler\Restler;

$productionMode = true;
if (Pelican::$config["SHOW_DEBUG"] === true) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('html_errors', 0);
    ini_set('error_log', __DIR__ . '/error-php.log');
    ini_set('log_errors', 1);
    $_SERVER['HTTP_ACCEPT'] = null;
    $productionMode = false;
}

// Restler init
Defaults::$useUrlBasedVersioning = true;
$r = new Restler($productionMode);
$r->setAPIVersion(1);
$r->setSupportedFormats('JsonFormat', 'XmlFormat');
$r->addAPIClass('ParamsApi\\Params', '');
$r->addAuthenticationClass('ParamsApi\\Auth');
$r->handle();
