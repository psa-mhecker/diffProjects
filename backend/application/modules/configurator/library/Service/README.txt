<?php

/**
 *      WS CONFIG
 **/

/*** WebService library***/
include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/conf/services.ini.php');

include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Service/MotCfgConfig.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Service/MotCfgConfig/Configuration.php');

include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Service/MotCfgConfig/Model/Request.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Service/MotCfgConfig/Model/Response.php');

ini_set('default_socket_timeout', 10);

/*** Webservice params***/
$serviceParams = array(
    'client' => 'NDP',
    'brandId' => 'P',
    'date' => '2014-11-05',
    'country' => 'FR',
    'tariffCode' => 'TC',
    'taxIncluded' => 'true',
    'professionalUse' => 'false',
);

$service = \Itkg\Service\Factory::getService('SERVICE_MOTCFGCONFIG', array());
$response = $service->call('config', $serviceParams);

echo "<pre>".print_r($response,true)."</pre>";

/**
 *      WS COMPAREGRADE
 **/

/*** WebService library***/
include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/conf/services.ini.php');

include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Service/MotCfgCompareGrade.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Service/MotCfgCompareGrade/Configuration.php');

include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Service/MotCfgCompareGrade/Model/Request.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Service/MotCfgCompareGrade/Model/Response.php');

ini_set('default_socket_timeout', 10);

/*** Webservice params***/
$serviceParams = array(
    'client' => 'NDP',
    'brand' => 'P',
    'date' => '2014-11-05',
    'country' => 'FR',
    'vehicleUse' => '3',
    'model' => '2',
    'bodyStyle' => '1',
);

$service = \Itkg\Service\Factory::getService('SERVICE_MOTCFGCOMPAREGRADE', array());
$response = $service->call('compareGrades', $serviceParams,true);

/**
 *     WS ENGINE CRITERIA
 **/

 /*** WebService library***/
 include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/conf/services.ini.php');

 include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Service/MotCfgEngineCriteria.php');
 include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Service/MotCfgEngineCriteria/Configuration.php');

 include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Service/MotCfgEngineCriteria/Model/Request.php');
 include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Service/MotCfgEngineCriteria/Model/Response.php');

 ini_set('default_socket_timeout', 10);

 /*** Webservice params***/
 $serviceParams = array(
     'client' => 'NDP',
     'brand' => 'P',
     'date' => '2014-11-05',
     'country' => 'FR',
     'version' => '1',
 );

 $service = \Itkg\Service\Factory::getService('SERVICE_MOTCFGENGINECRITERIA', array());
 $response = $service->call('engineCriteria', $serviceParams,true);

 /**
  *     WS SELECT
  */

include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/conf/services.ini.php');

include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Service/MotCfgSelect.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Service/MotCfgSelect/Configuration.php');

include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Service/MotCfgSelect/Model/Request.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Service/MotCfgSelect/Model/Response.php');

ini_set('default_socket_timeout', 10);

/*** Webservice params***/
$serviceParams = array(
    'client' => 'NDP',
    'brand' => 'P',
    'date' => '2014-11-05',
    'country' => 'FR',
);

$service = \Itkg\Service\Factory::getService('SERVICE_MOTCFGSELECT', array());
$response = $service->call('select', $serviceParams,true);

/**
 *      WS LOOKCOMBINATIONS
 **

/*** WebService library***/
include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/conf/services.ini.php');

include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Service/MotCfgLookCombinations.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Service/MotCfgLookCombinations/Configuration.php');

include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Service/MotCfgLookCombinations/Model/Request.php');
include_once(Pelican::$config["PLUGIN_ROOT"] . '/configurator/library/Service/MotCfgLookCombinations/Model/Response.php');

ini_set('default_socket_timeout', 10);

/*** Webservice params***/
$serviceParams = array(
    'client' => 'NDP',
    'brand' => 'P',
    'date' => '2014-11-05',
    'country' => 'FR',
);

$service = \Itkg\Service\Factory::getService('SERVICE_MOTCFGLOOKCOMBINATIONS', array());
$response = $service->call('lookCombinations', $serviceParams,true);
