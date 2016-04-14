<?php
if (substr_count($_SERVER['DOCUMENT_ROOT'], 'public/backend')) {
    $backend = true;
}

include_once 'config.php';

pelican_import('Controller.Front');
pelican_import('Controller.Back');
pelican_import('Ajax.Adapter.Jquery');
pelican_import('Http.UserAgent');

$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';

Pelican_Request::$multidevice = true;
Pelican_Request::$multidevice_template_switch = false;

$args = $_REQUEST['values'];
$route = $_GET['route'];
Pelican_Request::$userAgentFeatures['device'] = new Pelican_Http_UserAgent($previewMode);
$response = Pelican_Request::call($route, $args);

$ajaxResponse = Pelican_Request::getResponseCommand();

echo Pelican_Ajax_Adapter_Jquery::getResponse($ajaxResponse);
