<?php
/**
 * Media API endpoint
 *
 * @author Vincent Paré <vincent.pare@businessdecision.com>
 */

$endpointStartTime = microtime(true);

// API autoload
spl_autoload_register(function ($class) {
    if (substr($class, 0, 10) !== 'MediaApi\\v') {
        return false;
    }
    $classStub = preg_replace('~^MediaApi\\\\~', '', $class);
    $path = __DIR__ . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $classStub) . '.php';
    if (!is_readable($path)) {
        return false;
    }
    require $path;
    return true;
});

require_once 'config.php';
require_once Pelican::$config['VENDOR_ROOT'] . '/autoload.php';

use Luracast\Restler\Defaults;
use Luracast\Restler\Restler;
use Luracast\Restler\RestException;

// Debug
$productionMode = true;
if (Pelican::$config["SHOW_DEBUG"] === true) {
    $productionMode = false;
}

// Restler init
Defaults::$useUrlBasedVersioning = true;
$r = new Restler($productionMode);
$probe = new MediaApi\v1\Logging\Probe($r, $endpointStartTime);
$r->setAPIVersion(1);
$r->setSupportedFormats('JsonFormat', 'XmlFormat');
$r->addAPIClass('MediaApi\\Media', '');
$r->addAuthenticationClass('MediaApi\\BasicAuth');
$r->onCall(function () use ($r) {
    $mediaModelClass = Pelican::$config['API']['MEDIA']['APP'] == 'NDP'
        ? 'MediaApi\\v' . $r->getRequestedApiVersion() . '\\Model\\NdpMedia'
        : 'MediaApi\\v' . $r->getRequestedApiVersion() . '\\Model\\Media';
    if (!class_exists($mediaModelClass)) {
        throw new RestException(500, "Missing class $mediaModelClass");
    }
    $r->mediaModel = new $mediaModelClass;
});
$r->onComplete(array($probe, 'onComplete'));
$r->handle();
