<?php
/**
 * Suppression des application/caches portant le nom "grille"
 *
 * @package Pelican
 * @subpackage Pelican_Cache
 */
include_once 'config.php';

$_GET["param"] = str_replace(array(
    ' ',
    '%20'
), '', $_GET["param"]);

if (! $_SESSION[APP]["user"]["id"] && strtolower(Pelican::$config["TYPE_ENVIRONNEMENT"]) != "dev") {
    echo ("Veuillez vous identifier en Back Office");
    exit();
}

if ($_GET["param"]) {
    $param = Pelican_Security::execSafeCommandArg(str_replace('/', '', $_GET["param"]));
    $dir = Pelican::$config["CACHE_FW_ROOT"];
    $cmdCache[] = buildCommand(Pelican::$config["CACHE_FW_ROOT"], $param);
    $cmdCache[] = buildCommand(str_replace('application', 'views', Pelican::$config["CACHE_FW_ROOT"]), $param, true);
    $cmdCache[] = buildCommand(str_replace('application', 'view_compiles', Pelican::$config["CACHE_FW_ROOT"]), $param, true);
    $cmdCache[] = buildCommand(str_replace('application', 'extimage', Pelican::$config["CACHE_FW_ROOT"]), $param, true);
    //$cmdCache[] = buildCommand(Pelican::$config["VAR_ROOT"].'/i18n', $param);

    if (is_array($cmdCache)) {
        foreach ($cmdCache as $cmd) {
if (!empty($cmd)) {
            echo ($cmd);
            passthru($cmd);
            echo (' -> OK<br />');
}
        }
    }
}

function buildCommand($dir, $param, $subdir = false)
{
    $return = '';
    if (Pelican::$config['DOCUMENT_INIT'] != '/' && substr_count($dir, '/') >= 3) {
        if (is_dir($dir)) {
            $return = "rm -rf " . $dir . ($subdir ? '/*' : '') . "/*" . $param . "*";// &";
            $return = str_replace("\$", "", $return);
        }
    }

    return $return;
}
