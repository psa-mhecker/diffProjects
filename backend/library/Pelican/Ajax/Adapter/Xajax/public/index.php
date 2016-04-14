<?php
/** Serveur XAJAX
 *
 */
global $xajax;

require_once 'config.php';

/* déclaration des fonctions ajax */
pelican_import('Ajax.Adapter.Xajax');
Pelican_Ajax_Adapter_Xajax::init();

/* Debug in response results a 'données incompréhensibles' message */
Pelican::$config["SHOW_DEBUG"] = false;

/* Besoin de get Smarty */
pelican_import('Index.Frontoffice.Zone');
define('XAJAX_DEFAULT_CHAR_ENCODING', (Pelican::$config['CHARSET'] ? Pelican::$config['CHARSET'] : 'ISO-8859-1'));

function callhmvc()
{
    pelican_import('Controller.Front');
    pelican_import('Controller.Back');

    $args = func_get_args();
    $route = array_shift($args);

    $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';

    $response = Pelican_Request::call($route, $args);

    $ajaxResponse = Pelican_Request::getResponseCommand();

    return Pelican_Ajax_Adapter_Xajax::getResponse($ajaxResponse);
}

/** Compatibilité descendante
 * Les anciennes fonctions xajax_XXX déclarées ressortent ici en erreur et si le fichier php est présent
 * La fonction est exécutée.
 **/
function unknownFunction()
{
    global $xajax;

    //$function = $xajax->objPluginManager->aRequestPlugins[100]->sRequestedFunction;
    $function = $_REQUEST['xjxfun'];

    $objArgumentManager = & xajaxArgumentManager::getInstance();
    $args = $objArgumentManager->process();

    $path = Pelican::$config["CONTROLLERS_ROOT"]."/ajax/".$function.".php";

    return evalFunction($function, $path, $args);
}

/**
 * @param $function
 * @param $path
 * @param $args
 *
 * @return unknown_type
 */
function evalFunction($function, $path, $args = array())
{
    $pathinfo = pathinfo($path);

    if (file_exists($path) && $pathinfo['extension'] == 'php') {
        include str_replace('..', '', $path);

        return call_user_func_array($function, $args);
    } else {
        if (Pelican::$config["TYPE_ENVIRONNEMENT"] == 'dev') {
            $objResponse = new xajaxResponse();
            $objResponse->alert("Fonction : '".basename($path)."' inexistante.");

            return $objResponse;
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            header("Status: 500");
            $objResponse = new xajaxResponse();
            $objResponse->alert("Fonction : '".basename($function)."' inexistante.");

            return $objResponse;
        }
    }
}

/**
 * @param $return
 *
 * @return unknown_type
 */
function debugAjax($return)
{
    $objResponse = new xajaxResponse();
    $objResponse->alert($return);

    return $objResponse;
}

$xajax->register(XAJAX_PROCESSING_EVENT, XAJAX_PROCESSING_EVENT_INVALID, "unknownFunction");

$xajax->processRequest();
