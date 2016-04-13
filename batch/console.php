<?php

$aDir = explode('/', dirname(__FILE__));
$aDir[count($aDir) - 1] = null;
$aInclude = implode($aDir,'/');
//set_include_path($aInclude.'library:'.$aInclude.'application/configs:'.$aInclude.'library/Pelican:'.$aInclude.'vendor/zendframework/zendframework1/library');
set_include_path($aInclude.'library:'.$aInclude.'application/configs:'.$aInclude.'library/Pelican');

include_once ('config-cli.php');

// Inclusion de la console Itkg
require_once(Pelican::$config['VENDOR_ROOT'].'/itkg/app/console_include.php');