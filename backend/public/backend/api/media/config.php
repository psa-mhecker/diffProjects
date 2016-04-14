<?php
/**
 * Lecture de la configuration de phpfactory
 */

class Pelican
{
    public static $config = array();
}

define('APP', $_SERVER['HTTP_HOST']);
define('APP_PREFIXE', 'psa_');

$_ENV["TYPE_ENVIRONNEMENT"] = isset($_SERVER["TYPE_ENVIRONNEMENT"]) ? $_SERVER["TYPE_ENVIRONNEMENT"] : null;
$_ENV["BACKEND_VAR_PATH"] = isset($_SERVER["BACKEND_VAR_PATH"]) ? $_SERVER["BACKEND_VAR_PATH"] : null;

Pelican::$config["DOCUMENT_INIT"] = realpath( __DIR__ . '/../../../..');
Pelican::$config["VAR_ROOT"] = $_ENV["BACKEND_VAR_PATH"];
Pelican::$config["LOG_ROOT"] = Pelican::$config["VAR_ROOT"] . '/logs';
Pelican::$config['VENDOR_ROOT'] = Pelican::$config['DOCUMENT_INIT'] . '/../vendor';

include_once Pelican::$config["DOCUMENT_INIT"] . '/application/configs/config-api.ini.php';
include_once Pelican::$config["DOCUMENT_INIT"] . '/application/configs/local/' . $_ENV["TYPE_ENVIRONNEMENT"] . '.ini.php';
